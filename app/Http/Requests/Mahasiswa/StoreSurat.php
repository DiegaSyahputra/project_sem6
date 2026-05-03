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
            'tgl'           => 'required|date|before_or_equal:today',,
            'foto_surat'    => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'keterangan'    => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->has('tgl')) return;
            $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();
            if (!$mahasiswa) return;

            $sudahAda = Surat::where('mahasiswa_id', $mahasiswa->id)
                ->whereIn('status', ['pending', 'disetujui'])
                ->whereDate('tgl', $this->tgl)
                ->exists();

            if ($sudahAda) {
                $validator->errors()->add('tgl', 'Anda sudah memiliki pengajuan pada tanggal tersebut.');
            }
        });
    }

    public function messages(){
        return [
            'jenis.required' => 'Jenis Surat tidak boleh kosong',

            'tgl.required'               => 'Tanggal tidak boleh kosong.',
            'tgl.date'                   => 'Format tanggal tidak valid.',
            'tgl.before_or_equal'        => 'Tanggal tidak boleh lebih dari hari ini.',

            'foto_surat.required' => 'Foto Surat tidak boleh kosong',
            'foto_surat.max' => 'Ukuran Maksimal 2MB',
        ];
    }
}
