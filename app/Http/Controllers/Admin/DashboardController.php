<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\DetailPresensi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matkul;
use App\Models\Presensi;
use App\Models\Prodi;


use Carbon\Carbon;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $data = [
    //         'title' => 'Dashboard',
    //         'mahasiswa' => Mahasiswa::count(),
    //         'dosen' => Dosen::count(),
    //         'matkul' => Matkul::count(),
    //         'prodi' => Prodi::count(),
    //         'dosenMengajar' => Presensi::with('pertemuan','dosen','ruangan')->whereDate('tgl_presensi', Carbon::today())->get(),
    //         'mingguan' => [],
    //     ];

    //     $statusMap = [
    //         1 => 'Hadir',
    //         2 => 'Izin',
    //         3 => 'Sakit',
    //         0 => 'Alpha'
    //     ];

    //     $chartData = [];

    //     foreach ($statusMap as $statusValue => $statusLabel) {
    //         $minggu = [];
    //         $startOfMonth = Carbon::now()->startOfMonth();
    //         $endOfMonth = Carbon::now()->endOfMonth();
    //         $weeksInMonth = ceil($startOfMonth->diffInDays($endOfMonth) / 7);


    //         for ($i = 1; $i <= $weeksInMonth; $i++) {
    //             $start = Carbon::now()->startOfMonth()->addWeeks($i - 1)->startOfWeek();
    //             $end = (clone $start)->endOfWeek();

    //             if ($start > $endOfMonth) break;
    //             if ($end > $endOfMonth) $end = $endOfMonth;

    //             $count = DetailPresensi::where('status', $statusValue)
    //                 ->whereHas('presensi', function ($q) use ($start, $end) {
    //                     $q->whereBetween('tgl_presensi', [$start, $end]);
    //                 })
    //                 ->count();

    //             $minggu[] = $count;
    //         }

    //         $chartData[] = [
    //             'name' => $statusLabel,
    //             'data' => $minggu
    //         ];
    //     }

    //     $data['tidakHadir'] = DetailPresensi::with(['mahasiswa','presensi.pertemuan.matkul','presensi.ruangan','presensi.pertemuan.prodi'])->whereIn('status', [0,2,3])->whereHas('presensi', function ($query){
    //         $query->whereDate('tgl_presensi', Carbon::today())->whereTime('jam_akhir', '<=', Carbon::now()->toTimeString());
    //     })->get();

    //     $data['hadir'] = DetailPresensi::with(['mahasiswa','presensi.pertemuan.matkul','presensi.ruangan','presensi.pertemuan.prodi'])->where('status', 1)->whereHas('presensi', function ($query){
    //         $query->whereDate('tgl_presensi', Carbon::today())->whereTime('jam_akhir', '<=', Carbon::now()->toTimeString());
    //     })->orderByDesc('waktu_presensi')->limit('40')->get();

    //     $data['mingguan'] = $chartData;
    //     return view('admin.dashboard',$data);
    // }


    public function index()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Ambil semua presensis_id dalam bulan ini sekali
        $presensiIdsBulanIni = Presensi::whereBetween('tgl_presensi', [
            $startOfMonth, $endOfMonth
        ])->pluck('id');

        // Ambil semua detail presensi bulan ini SEKALI
        $detailBulanIni = DetailPresensi::whereIn('presensis_id', $presensiIdsBulanIni)
            ->whereIn('status', [0, 1, 2, 3])
            ->with('presensi:id,tgl_presensi')
            ->get(['status', 'presensis_id']);

        // Proses chart di PHP, bukan di database
        $statusMap = [1 => 'Hadir', 2 => 'Izin', 3 => 'Sakit', 0 => 'Alpha'];
        $weeksInMonth = (int) ceil($startOfMonth->diffInDays($endOfMonth) / 7);
        $chartData = [];

        foreach ($statusMap as $statusValue => $statusLabel) {
            $minggu = [];

            for ($i = 1; $i <= $weeksInMonth; $i++) {
                $start = $startOfMonth->copy()->addWeeks($i - 1)->startOfWeek();
                $end = $start->copy()->endOfWeek();
                if ($start > $endOfMonth) break;
                if ($end > $endOfMonth) $end = $endOfMonth->copy();

                $count = $detailBulanIni->filter(function ($dp) use ($statusValue, $start, $end) {
                    return $dp->status == $statusValue
                        && $dp->presensi->tgl_presensi >= $start->toDateString()
                        && $dp->presensi->tgl_presensi <= $end->toDateString();
                })->count();

                $minggu[] = $count;
            }

            $chartData[] = ['name' => $statusLabel, 'data' => $minggu];
        }

        // Query hari ini — pisah presensis_id dulu
        $presensiIdsHariIni = Presensi::whereDate('tgl_presensi', $today)
            ->whereTime('jam_akhir', '<=', $now->toTimeString())
            ->pluck('id');

        $data = [
            'title'         => 'Dashboard',
            'mahasiswa'     => Mahasiswa::count(),
            'dosen'         => Dosen::count(),
            'matkul'        => Matkul::count(),
            'prodi'         => Prodi::count(),

            // Gunakan select untuk hindari ambil kolom yang tidak perlu
            'dosenMengajar' => Presensi::with('pertemuan.matkul','dosen:id,nama','ruangan:id,nama_ruangan')
                ->whereDate('tgl_presensi', $today)
                ->get(),

            'tidakHadir' => DetailPresensi::with([
                    'mahasiswa:id,nim,nama',
                    'presensi.pertemuan.matkul:id,nama_matkul',
                    'presensi.ruangan:id,nama_ruangan',
                    'presensi.pertemuan.prodi:id,nama_prodi'
                ])
                ->whereIn('status', [0, 2, 3])
                ->whereIn('presensis_id', $presensiIdsHariIni)
                ->get(),

            'hadir' => DetailPresensi::with([
                    'mahasiswa:id,nim,nama',
                    'presensi.pertemuan.matkul:id,nama_matkul',
                    'presensi.ruangan:id,nama_ruangan',
                    'presensi.pertemuan.prodi:id,nama_prodi'
                ])
                ->where('status', 1)
                ->whereIn('presensis_id', $presensiIdsHariIni)
                ->orderByDesc('waktu_presensi')
                ->limit(40)
                ->get(),

            'mingguan' => $chartData,
        ];

        return view('admin.dashboard', $data);
    }

}
