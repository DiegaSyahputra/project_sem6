<?php

namespace App\Http\Controllers\Api\Listview;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function getPresenceStudent(Request $request)
    {
        // Validasi input
        $request->validate([
            'mahasiswa_id' => 'required|integer'
        ]);

        $mahasiswaId = $request->get('mahasiswa_id');
        $currentTime = Carbon::now()->format('H:i:s');
        $today = Carbon::today();


        $presensiList = Presensi::with([
            'pertemuan:id,matkul_id,prodi_id,semester',
            'pertemuan.matkul:id,nama_matkul,durasi_matkul,kode_matkul',
            'ruangan:id,nama_ruangan',
            'lokasi:id,nama',
            'dosen:id,nama',
            'detailPresensi.mahasiswa:id,nim,semester'
        ])
            ->whereDate('tgl_presensi', $today)
            ->whereTime('jam_awal', '<=', $currentTime)
            ->whereTime('jam_akhir', '>=', $currentTime)
            ->whereHas('detailPresensi', function ($query) use ($mahasiswaId) {
                $query->where('mahasiswa_id', $mahasiswaId)
                    ->where('status', 0);
            })
            ->orderBy('jam_awal')
            ->get();

        if ($presensiList->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data presensi tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Format data
        $data = $presensiList->map(function ($presensi) use ($mahasiswaId) {
            $detail = $presensi->detailPresensi
                ->firstWhere('mahasiswa_id', $mahasiswaId);

            return [
                'presensis_id' => $presensi->id,
                'lokasi_id' => $presensi->lokasi_id ?? null,
                'nama_lokasi' => $presensi->lokasi->nama ?? null,
                'nim' => $detail?->mahasiswa?->nim,
                'semester' => $detail?->mahasiswa?->semester,
                'presensi_id' => $presensi->presensis_id,
                'durasi_presensi' => Carbon::parse($presensi->jam_awal)->format('H:i') . ' - ' . Carbon::parse($presensi->jam_akhir)->format('H:i'),
                'nama_matkul' => $presensi->pertemuan->matkul->nama_matkul ?? null,
                'durasi_matkul' => $presensi->pertemuan->matkul->durasi_matkul ?? null,
                'kode_matkul' => $presensi->pertemuan->matkul->kode_matkul ?? null,
                'nama_ruangan' => $presensi->ruangan->nama_ruangan ?? null,
                'link_zoom' => $presensi->link_zoom ?? null,
                'nama_dosen' => $presensi->dosen->nama ?? null,
                'tgl_presensi' => $presensi->tgl_presensi
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data presensi berhasil diambil',
            'data' => $data
        ]);
    }
}
