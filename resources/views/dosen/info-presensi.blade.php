<x-layout>
    <div class="h-full dark:text-white">
        <x-slot:title>{{ $title }}</x-slot:title>
        <p class="text-gray-800 dark:text-gray-200">Tinjau detail kehadiran perkuliahan</p>

        <div class="w-full mt-5 p-5 bg-white dark:bg-gray-800 rounded-sm shadow-xl">
            <div class="mb-5 justify-start flex">
                <a href="{{route('dosen.presensi.index')}}">
                <button class="px-5 py-2 bg-red-500 hover:bg-red-600 active:bg-red-700 text-white font-semibold rounded-md cursor-pointer"><i class="bi bi-arrow-return-left"></i></button>
                </a>
            </div>
            <h1 class="mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-100">Dosen Pengajar</h1>
            <div class="overflow-x-auto w-full mt-3 pb-3"> 
                <table id="detail-presensi" class="text-sm w-full table-auto pt-1 dark:text-white" style="width: 100% !important;">
                    <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Tanggal</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Jam Perkuliahan</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Mata Kuliah</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Dosen</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Program Studi</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Semester</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Ruangan</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Pertemuan Ke</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Jenis Perkuliahan</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Status</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Tahun Ajaran</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Link Zoom</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="text-center text-gray-800 dark:text-gray-100">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$presensi->tgl_presensi ?? '-'}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{substr($presensi->jam_awal,0,5) .' - '.substr($presensi->jam_akhir,0,5) ?? '-'}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$presensi->pertemuan->matkul->nama_matkul ?? '-'}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$presensi->dosen->nama ?? '-'}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$presensi->pertemuan->prodi->nama_prodi ?? '-'}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$presensi->pertemuan->semester ?? '-'}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$presensi->ruangan->nama_ruangan ?? '-'}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$presensi->pertemuan->pertemuan_ke ?? '-'}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{strtoupper($presensi->pertemuan->jenis ?? '-')}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center">
                            @switch($presensi->pertemuan->status)
                                @case('aktif')
                                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-green-500 rounded-full">Aktif</span>
                                @break
                                @case('uts')
                                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-red-500 rounded-full">UTS</span>
                                @break
                                @case('uas')
                                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-red-500 rounded-full">UAS</span>
                                @break
                                @case('libur')
                                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-red-500 rounded-full">Libur</span>
                                @break
                                @default
                                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-gray-500 rounded-full">-</span>
                            @endswitch
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$presensi->pertemuan->tahun->tahun_awal .'/'. $presensi->pertemuan->tahun->tahun_akhir .' '. $presensi->pertemuan->tahun->keterangan ?? '-'}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$presensi->link_zoom ?? '-'}}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$presensi->link_zoom ? 'Daring' : 'Luring'}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h1 class="mb-2 mt-6 text-2xl font-semibold text-gray-700 dark:text-gray-100">Mahasiswa</h1>
            <div class="overflow-x-auto w-full mt-3 pb-3"> 
                <table id="datail-mahasiswa" class="text-sm w-full table-auto pt-1 dark:text-white" style="width: 100% !important;">
                    <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">No</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Nim</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Nama</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Waktu Presensi</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Presensi</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Alasan</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100">
                        @foreach ($detail as $dp )
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$loop->iteration}}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$dp->mahasiswa->nim}}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$dp->mahasiswa->nama}}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$dp->waktu_presensi ?? '-'}}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center">
                                    @switch($dp->status)
                                        @case(0)
                                            <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-red-500 rounded-full">Alpha</span>
                                        @break
                                        @case(1)
                                            <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-green-500 rounded-full">Hadir</span>
                                        @break
                                        @case(2)
                                            <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-gray-500 rounded-full">Izin</span>
                                        @break
                                        @case(3)
                                            <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-orange-500 rounded-full">Sakit</span>
                                        @break
                                        @case(4)
                                            <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-amber-500 rounded-full">Pending</span>
                                        @break
                                        @default
                                            <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-gray-500 rounded-full">-</span>
                                        @endswitch
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{$dp->alasan ?? '-'}}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center">
                                    <div x-data="{ 
                                            openEdit: false, 
                                            openConfirm: false, 
                                            status: '{{$dp->status}}', 
                                            defaultStatus: '{{$dp->status}}', 
                                            alasan: '{{$dp->alasan ?? ''}}', 
                                            defaultAlasan: '{{$dp->alasan ?? ''}}' 
                                        }" 
                                        class="flex justify-center gap-2">
                                        
                                        <button @click="openEdit = true" class="cursor-pointer px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-md" title="Ubah Manual">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </button>

                                        @if($dp->status == 4)
                                            <button @click="openConfirm = true" class="cursor-pointer px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white rounded-md" title="Konfirmasi Surat Izin">
                                                <i class="bi bi-check-circle text-lg"></i>
                                            </button>
                                        @endif

                                        <form action="{{route('dosen.update-detail-presensi')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="mahasiswa_id" value="{{ $dp->mahasiswa_id }}">
                                            <input type="hidden" name="presensi_id" value="{{ $dp->presensi_id }}">
                                            
                                            <div x-show="openEdit" x-cloak x-transition class="fixed inset-0 z-50 flex justify-center items-center">
                                                <div class="absolute inset-0 bg-black opacity-50"></div>
                                                <div @click.outside="status = defaultStatus; alasan = defaultAlasan; openEdit = false" class="relative z-10 bg-white dark:bg-gray-900 rounded-lg shadow-2xl w-[90%] max-w-md p-6 text-left">
                                                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white text-center mb-6">Ubah Presensi Manual</h2>
                                                    
                                                    <div class="flex flex-wrap justify-center gap-4 mb-6 text-gray-800 dark:text-gray-100">
                                                        <label class="inline-flex items-center"><input type="radio" name="status" value="1" x-model="status" class="text-green-600"><span class="ml-2">Hadir</span></label>
                                                        <label class="inline-flex items-center"><input type="radio" name="status" value="2" x-model="status" class="text-gray-500"><span class="ml-2">Izin</span></label>
                                                        <label class="inline-flex items-center"><input type="radio" name="status" value="3" x-model="status" class="text-yellow-500"><span class="ml-2">Sakit</span></label>
                                                        <label class="inline-flex items-center"><input type="radio" name="status" value="0" x-model="status" class="text-red-600"><span class="ml-2">Alpha</span></label>
                                                        <label class="inline-flex items-center"><input type="radio" name="status" value="4" x-model="status" class="text-orange-600"><span class="ml-2">Pending</span></label>
                                                    </div>

                                                    <div class="mb-6 w-full">
                                                        <label class="block text-gray-700 dark:text-gray-200 mb-1 font-semibold">Alasan:</label>
                                                        <textarea name="alasan" x-model="alasan" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md focus:ring-2 focus:ring-blue-500" x-bind:disabled="!(status == 2 || status == 3 || status == 4)"></textarea>
                                                    </div>

                                                    <div class="flex justify-end space-x-3">
                                                        <button type="button" @click="status = defaultStatus; alasan = defaultAlasan; openEdit = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-white rounded hover:bg-gray-300">Batal</button>
                                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div x-show="openConfirm" x-cloak x-transition class="fixed inset-0 z-50 flex justify-center items-center">
                                            <div class="absolute inset-0 bg-black opacity-50"></div>
                                            <div @click.outside="openConfirm = false" class="relative z-10 bg-white dark:bg-gray-900 rounded-lg shadow-2xl w-[90%] max-w-lg p-6 text-left">
                                                <div class="flex justify-between items-center mb-4">
                                                    <h2 class="text-xl font-bold dark:text-white">Konfirmasi Surat Izin</h2>
                                                    <button @click="openConfirm = false" class="text-gray-500 hover:text-gray-800"><i class="bi bi-x-lg"></i></button>
                                                </div>

                                                <div class="mb-4">
                                                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Bukti Surat Izin:</p>
                                                    <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-gray-50 flex justify-center items-center p-2">
                                                        <img src="https://picsum.photos/seed/surat/500/600" class="max-h-64 w-auto object-contain shadow-sm" alt="Preview Surat">
                                                    </div>
                                                    
                                                    <a href="https://picsum.photos/seed/surat/500/600" target="_blank" rel="noopener noreferrer" class="block mt-3 text-blue-600 hover:underline text-sm text-center font-bold">
                                                        <i class="bi bi-box-arrow-up-right mr-1"></i> Lihat Full Dokumen
                                                    </a>
                                                </div>

                                                <form action="{{route('dosen.update-detail-presensi')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="mahasiswa_id" value="{{ $dp->mahasiswa_id }}">
                                                    <input type="hidden" name="presensi_id" value="{{ $dp->presensi_id }}">
                                                    
                                                    <div class="mb-4">
                                                        <label class="block text-sm font-semibold dark:text-white mb-1">Setujui Sebagai:</label>
                                                        <select name="status" required class="w-full px-3 py-2 border dark:bg-gray-800 dark:text-white rounded-md">
                                                            <option value="2">Izin (Disetujui)</option>
                                                            <option value="3">Sakit (Disetujui)</option>
                                                            <option value="0">Tolak (Alpha)</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-6">
                                                        <label class="block text-sm font-semibold dark:text-white mb-1">Catatan/Alasan Tambahan:</label>
                                                        <textarea name="alasan" rows="2" class="w-full px-3 py-2 border dark:bg-gray-800 dark:text-white rounded-md">{{ $dp->alasan }}</textarea>
                                                    </div>

                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" @click="openConfirm = false" class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm">Batal</button>
                                                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-bold">Setujui & Perbarui</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>

<script>
    $(document).ready(function () {
        table = $("#detail-mahasiswa").DataTable({
            searching: true,
            paging: true,
            info: true,
            scrollX: true,
            autoWidth: false,
        });
    });
</script>
