<x-layout>
    <div class="dark:text-white">
        <x-slot:title>{{ $title }}</x-slot:title>
        <p class="mb-3">Hari Ini:
            <span class="text-md">
                {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
            </span>
        </p>

        @php
            $mahasiswa = Auth::user()->mahasiswa;
            $foto = $mahasiswa && $mahasiswa->jenis_kelamin === 'P'
                ? asset('images/halo-cewe.png')
                : asset('images/halo.png');
        @endphp

       <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border border-gray-200 dark:border-gray-700 flex items-center">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-2xl p-2 sm:p-10 py-10 w-full shadow-md">
                    <div class="text-center sm:text-left">
                        <h2 class="text-2xl sm:text-3xl font-bold leading-snug"> Selamat datang, <br>
                            <span class="font-extrabold text-white drop-shadow-md">{{Auth::user()->name ?? ''}}</span> 👋
                        </h2>
                        <p class="text-sm sm:text-base mt-2 text-white/90">Semoga harimu menyenangkan dan produktif!</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-700 flex flex-col">
                <div class="mb-3 p-4 rounded-t-xl border-b-2 border-gray-300 dark:border-gray-700 flex justify-between items-center">
                    <h1 class="text-lg font-semibold">Jadwal Perkuliahan Hari ini</h1>
                </div>
                <div class="overflow-x-auto w-full pb-3">
                    <table id="data-mengajar" class="text-sm w-full table-auto pt-1 dark:text-white" style="width: 100% !important;">
                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">No</th>
                                <th class="px-4 py-2 text-left font-semibold">Jam</th>
                                <th class="px-4 py-2 text-left font-semibold">Mata Kuliah</th>
                                <th class="px-4 py-2 text-left font-semibold">Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($presensiHariIni as $p)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">
                                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{substr($p->jam_awal,0,5) .' - '. substr($p->jam_akhir,0,5)}}</td>
                                    <td class="px-4 py-2">{{$p->pertemuan->matkul->nama_matkul}}</td>
                                    <td class="px-4 py-2">{{$p->ruangan->nama_ruangan ?? '-'}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-data="{
            openModal: false,
            originalPhoto: '{{ isset($biodata) && $biodata->foto ? asset('storage/' . $biodata->foto) : asset('images/profil-kosong.png') }}',
            photoPreview: null,
            resetPreview() { this.photoPreview = null; }
        }" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center text-white">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="bi bi-person-badge mr-3"></i> Biodata Mahasiswa
                </h2>
            </div>

            <div class="p-6 lg:p-10">
                <div class="flex flex-col lg:flex-row gap-10">
                    <div class="flex flex-col items-center lg:items-start shrink-0">
                        <div class="relative group">
                            <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-xl ring-4 ring-blue-100 dark:ring-blue-900">
                                <img :src="photoPreview || originalPhoto" alt="Foto Mahasiswa" class="w-full h-full object-cover">
                            </div>
                            <div @click="openModal = true" class="absolute inset-0 rounded-full bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-all duration-300">
                                <div class="text-center text-white text-xs">
                                    <i class="bi bi-camera text-2xl mb-1"></i><br>Ubah Foto
                                </div>
                            </div>
                        </div>
                        <button @click="openModal = true" class="mt-4 w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-md">
                            Ubah Foto Profil
                        </button>
                    </div>

                    <div class="flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <h3 class="text-lg font-bold border-b-2 border-blue-200 dark:border-blue-800 pb-2 text-blue-700 dark:text-blue-400">
                                    Informasi Pribadi
                                </h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Nama Lengkap</span>
                                        <span class="font-bold text-gray-800 dark:text-gray-100 text-lg">{{$biodata->nama}}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">NIM</span>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{$biodata->nim}}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Jenis Kelamin</span>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">{{$biodata->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}}</span>
                                        </div>
                                        <div>
                                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Semester</span>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">{{$biodata->semester}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg font-bold border-b-2 border-green-200 dark:border-green-800 pb-2 text-green-700 dark:text-green-400">
                                    Akademik & Kontak
                                </h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Program Studi</span>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{$biodata->prodi->nama_prodi}}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Email Institusi</span>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{$biodata->email}}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">No. Telepon</span>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{$biodata->no_telp}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-lg font-bold border-b-2 border-purple-200 dark:border-purple-800 pb-2 text-purple-700 dark:text-purple-400">
                                Domisili
                            </h3>
                            <div class="mt-3">
                                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Alamat Lengkap</span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                    {{$biodata->alamat}}, {{$biodata->kelurahan->name}}, {{$biodata->kecamatan->name}}, {{$biodata->kota->name}}, {{$biodata->provinsi->name}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout>

<script>
    $(document).ready(function () {
    table = $("#data-mengajar").DataTable({
        searching: false,
        paging: false,
        info: false,
        scrollX: true,
        autoWidth: false,
    });
});
</script>
