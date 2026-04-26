<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Requests\Mahasiswa\StoreSurat;
use App\Models\DetailPresensi;
use App\Models\Mahasiswa;
use App\Models\Surat;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data Presensi';
        $mahasiswa = Auth::user()->mahasiswa;
        $biodata = Mahasiswa::findOrFail($mahasiswa->id);

        $presensiHariIni = Presensi::with(['pertemuan','dosen','ruangan','detailPresensi' => function ($q) use ($mahasiswa){
            $q->where('mahasiswa_id', $mahasiswa->id);
        }])->whereDate('tgl_presensi', Carbon::today())->get();

        $now = Carbon::now();
        $start = $now->copy()->subMinutes(30);
        $end = $now->copy()->addMinutes(30);

        $presensi = Presensi::with('pertemuan.matkul', 'ruangan', 'detailPresensi')
        ->whereHas('detailPresensi', function ($q) use ($mahasiswa) {
            $q->where('mahasiswa_id', $mahasiswa->id);
        })
        ->whereHas('pertemuan', function ($q) {
            $q->whereIn('status', ['aktif','uts','uas']);
        })
        ->whereDate('tgl_presensi', Carbon::today())
        ->whereTime('jam_awal', '<=', $now->format('H:i:s'))
        ->whereTime('jam_akhir', '>=', $now->format('H:i:s'))
        ->first();

        $presensiTercatat = $presensi?->detailPresensi->first()?->waktu_presensi;

        $riwayat =  Presensi::with(['pertemuan','ruangan','detailPresensi' => function ($q) use ($mahasiswa){
            $q->where('mahasiswa_id', $mahasiswa->id);
        }])
        ->whereDate('tgl_presensi', '=', $now->toDateString())
        ->whereTime('jam_akhir', '<', $now->format('H:i:s'))
        ->whereHas('detailPresensi', function ($q) use ($mahasiswa) {
            $q->where('mahasiswa_id', $mahasiswa->id);
        })
        ->whereHas('pertemuan', function ($p) {
            $p->whereIn('status',['aktif','uts','uas']);
        })
        ->orderByDesc('tgl_presensi')
        ->get();

        // Ambil semua surat mahasiswa sekali query
        $semuaSurat = Surat::where('mahasiswa_id', $mahasiswa->id)->get();

        // Map riwayat — tambahkan info status label, warna, dan keterangan surat
        $riwayat = $riwayat->map(function ($r) use ($semuaSurat) {
            $detail = $r->detailPresensi->first();
            $statusPresensi = $detail->status ?? 0;
            $r->waktuPresensi = $detail->waktu_presensi ?? null;
            $statusPertemuan = strtolower($r->pertemuan->status ?? '');

            // Label & warna status presensi
            $statusMap = [
                0 => ['label' => '❌ Alpha',                      'color' => 'text-red-600 dark:text-red-400'],
                1 => ['label' => '✅ Hadir',                      'color' => 'text-green-600 dark:text-green-400'],
                2 => ['label' => '📄 Izin',                       'color' => 'text-blue-600 dark:text-blue-400'],
                3 => ['label' => '🤒 Sakit',                      'color' => 'text-yellow-600 dark:text-yellow-400'],
                4 => ['label' => '⏳ Menunggu Konfirmasi Surat',  'color' => 'text-orange-500 dark:text-orange-400'],
            ];

            $r->labelPresensi = $statusMap[$statusPresensi]['label'] ?? '⏳ Tidak Diketahui';
            $r->colorPresensi = ($statusMap[$statusPresensi]['color'] ?? 'text-gray-600') . ' font-semibold text-sm';

            // Label pertemuan (UTS/UAS)
            $r->labelPertemuan = match($statusPertemuan) {
                'uts'   => ' (UTS)',
                'uas'   => ' (UAS)',
                default => '',
            };

            // Cari surat terkait berdasarkan rentang tanggal
            $suratTerkait = $semuaSurat->first(function ($s) use ($r) {
                return $s->tgl_mulai <= $r->tgl_presensi && $s->tgl_selesai >= $r->tgl_presensi;
            });

            // Keterangan surat
            $r->keteranganSurat = null;
            if ($suratTerkait) {
                $r->keteranganSurat = match($suratTerkait->status) {
                    'pending'   => null, // sudah terwakili label "Menunggu Konfirmasi"
                    'disetujui' => null, // sudah terwakili label Sakit/Izin
                    'ditolak'   => '❗ Surat ditolak' . ($suratTerkait->catatan_konfirmator ? ': ' . $suratTerkait->catatan_konfirmator : ''),
                    default     => null,
                };
            }

            return $r;
        });

        return view('mahasiswa.presensi', compact('presensi','title','biodata','presensiTercatat','riwayat'));
    }

    public function izin()
    {
        $title = 'Pengajuan Izin Presensi';
        $mahasiswa = Auth::user()->mahasiswa;

        // Kirim variabel title ke view menggunakan compact atau array
        return view('mahasiswa.izin', compact('title', 'mahasiswa'));
    }

    public function store(StoreSurat $request){

        try {
            DB::transaction(function () use ($request) {

                $mahasiswa = Mahasiswa::where('user_id', auth()->id())->firstOrFail();

                // Cek duplikat pengajuan di rentang tanggal yang sama
                // $sudahAda = Surat::where('mahasiswa_id', $mahasiswa->id)
                //     ->whereIn('status', ['pending', 'disetujui'])
                //     ->where(function ($q) use ($request) {
                //         $q->whereBetween('tgl_mulai', [$request->tgl_mulai, $request->tgl_selesai])
                //         ->orWhereBetween('tgl_selesai', [$request->tgl_mulai, $request->tgl_selesai]);
                //     })->exists();

                // if ($sudahAda) {
                //     throw new \Exception('DUPLIKAT');
                // }

                // Simpan file
                $fotoPath = $request->file('foto_surat')->store('surat_sakits', 'public');

                // Simpan surat sakit
                Surat::create([
                    'mahasiswa_id'    => $mahasiswa->id,
                    'jenis'           => $request->jenis,
                    'tgl_mulai'   => $request->tgl_mulai,
                    'tgl_selesai' => $request->tgl_selesai,
                    'foto_surat'      => $fotoPath,
                    'keterangan'      => $request->keterangan,
                    'status'          => 'pending',
                ]);

                // Ambil semua presensi yang tanggalnya masuk rentang surat
                $presensiIds = Presensi::whereBetween('tgl_presensi', [
                    $request->tgl_mulai,
                    $request->tgl_selesai,
                ])->pluck('id');

                // Update status detail presensi mahasiswa jadi pending (4)
                // Hanya yang masih alpha (0), jangan timpa yang sudah hadir
                DetailPresensi::whereIn('presensi_id', $presensiIds)
                    ->where('mahasiswa_id', $mahasiswa->id)
                    ->where('status', 0)
                    ->update(['status' => 4]);
            });

            return redirect()->back()->with([
                'status'  => 'success',
                'message' => 'Pengajuan berhasil dikirim, menunggu konfirmasi dosen.'
            ]);

        } catch (\Exception $e) {

            if ($e->getMessage() === 'DUPLIKAT') {
                return redirect()->back()->withInput()->with([
                    'status'  => 'error',
                    'message' => 'Anda sudah memiliki pengajuan di rentang tanggal tersebut.'
                ]);
            }

            Log::error('Gagal menyimpan surat sakit/izin', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengirim pengajuan.'
            ]);
        }
    }

    public function validateField(Request $request)
    {
        $rules = (new StoreSurat())->rules();
        $messages = (new StoreSurat())->messages();
        $field = $request->input('field');
        $value = $request->input('value');

        $validator = Validator::make([$field => $value], [
            $field => $rules[$field] ?? '',
        ],$messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first($field)], 422);
        }

        return response()->json(['success' => true]);
    }
}
