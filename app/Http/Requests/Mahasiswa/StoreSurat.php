<?php

namespace App\Http\Requests\Mahasiswa;

use App\Models\Mahasiswa;
use App\Models\Surat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreSurat extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules($id = null): array
    {

        $id = $id ?? $this->route('mahasiswa.presensi.izin');

        return [
            'jenis'         => 'required|in:sakit,izin',
            'tgl_mulai'     => 'required|date',
            'tgl_selesai'   => 'required|date|after_or_equal:tgl_mulai',
            'foto_surat'    => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'keterangan'    => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();
            if (!$mahasiswa) return;

            $sudahAda = Surat::where('mahasiswa_id', $mahasiswa->id)
                ->whereIn('status', ['pending', 'disetujui'])
                ->where(function ($q) {
                    $q->whereBetween('tgl_mulai', [$this->tgl_mulai, $this->tgl_selesai])
                    ->orWhereBetween('tgl_selesai', [$this->tgl_mulai, $this->tgl_selesai])
                    ->orWhere(function ($q) {
                        // rentang surat sudah ada yang mencakup rentang baru
                        $q->where('tgl_mulai', '<=', $this->tgl_mulai)
                            ->where('tgl_selesai', '>=', $this->tgl_selesai);
                    });
                })->exists();

            if ($sudahAda) {
                $validator->errors()->add('tgl_mulai', 'Anda sudah memiliki pengajuan surat di rentang tanggal tersebut.');
            }
        });
    }

    public function messages(){
        return [
            'jenis.required' => 'Jenis Surat tidak boleh kosong',

            'tgl_mulai.required' => 'Pilih Tanggal Mulai terlebih dahulu',

            'tgl_selesai.required' => 'Pilih Tanggal Selesai terlebih dahulu',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai',

            'foto_surat.required' => 'Foto Surat tidak boleh kosong',
            'foto_surat.max' => 'Ukuran Maksimal 2MB',
        ];
    }
}
