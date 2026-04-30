<?php

namespace App\Http\Controllers\Api\Listview;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceStudentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|exists:mahasiswa,nim',
        ]);

        $nim = $request->nim;

        $rekap = DB::table('detail_presensi')
            ->join('presensi', 'presensi.id', '=', 'detail_presensi.presensi_id')
            ->join('pertemuan', 'pertemuan.id', '=', 'presensi.pertemuan_id')
            ->join('matkul', 'pertemuan.matkul_id', '=', 'matkul.id')
            ->join('mahasiswa', function ($join) {
                $join->on('mahasiswa.id', '=', 'detail_presensi.mahasiswa_id')
                    ->on('mahasiswa.semester', '=', 'matkul.semester');
            })
            ->where('mahasiswa.nim', $nim)
            ->select(
                'detail_presensi.mahasiswa_id',
                'mahasiswa.nim',
                'matkul.nama_matkul',
                'matkul.kode_matkul',
                'detail_presensi.status',
                'matkul.semester'
            )
            ->get();

        if ($rekap->isEmpty()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Data rekap tidak ditemukan'
            ], 404);
        }

        $rekap = $rekap->map(function ($item) {
            return [
                'mahasiswa_id' => (int) $item->mahasiswa_id,
                'nim' => $item->nim,
                'nama_matkul' => $item->nama_matkul,
                'kode_matkul' =>  $item->kode_matkul,
                'status' => (int) $item->status,
                'semester' => (int) $item->semester,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data Rekap semester sekarang ditemukan',
            'data' => $rekap
        ], 200);
    }
}
