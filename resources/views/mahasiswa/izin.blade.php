<x-layout>
    <div class="h-full dark:text-white" x-data="{
        preview: null,
        fileName: '',
        handleFile(event) {
            const file = event.target.files[0];
            if (file) {
                this.fileName = file.name;
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => this.preview = e.target.result;
                    reader.readAsDataURL(file);
                } else {
                    this.preview = null; // Jika PDF tidak muncul preview gambar
                }
            }
        }
    }">

        @if ($errors->any())
            <div class="alert alert-danger text-red-600 dark:text-red-400">
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <x-slot:title>{{ $title }}</x-slot:title>
        <p class="text-gray-700 dark:text-white">Silakan unggah surat keterangan sakit atau surat izin resmi</p>

        <div class="w-full mt-5 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl">
            <div class="mb-6 justify-start flex">
                <a href="{{ route('mahasiswa.presensi') }}">
                    <button
                        class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-md cursor-pointer transition-colors flex items-center gap-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </button>
                </a>
            </div>

            <form action="{{ route('mahasiswa.presensi.izin.store') }}" method="POST" class="form-validasi"
                enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">

                    <div class="lg:col-span-4 space-y-4">
                        <label
                            class="block text-sm font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Dokumen
                            Pendukung</label>

                        <div class="relative group">
                            <div
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-700/50 hover:border-orange-500 transition-all min-h-[300px]">

                                <template x-if="!fileName">
                                    <div class="text-center">
                                        <i
                                            class="bi bi-cloud-arrow-up text-6xl text-gray-300 dark:text-gray-500 group-hover:text-orange-500 transition-colors"></i>
                                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Klik atau seret file
                                            surat izin ke sini</p>
                                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                    </div>
                                </template>

                                <template x-if="preview">
                                    <div class="w-full">
                                        <img :src="preview"
                                            class="max-h-60 mx-auto rounded-lg shadow-md border dark:border-gray-600">
                                        <p class="mt-3 text-xs text-center text-green-600 dark:text-green-400 font-bold italic"
                                            x-text="fileName"></p>
                                    </div>
                                </template>

                                <template x-if="fileName && !preview">
                                    <div class="text-center">
                                        <i class="bi bi-file-earmark-pdf text-6xl text-red-500"></i>
                                        <p class="mt-3 text-sm font-bold dark:text-white" x-text="fileName"></p>
                                        <p class="text-xs text-gray-400">File dokumen terpilih</p>
                                    </div>
                                </template>

                                <input type="file" name="foto_surat" accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    @change="handleFile($event)" required>
                            </div>
                        </div>

                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex gap-3">
                            <i class="bi bi-info-circle text-blue-500"></i>
                            <p class="text-[11px] text-blue-700 dark:text-blue-300">Preview akan muncul otomatis jika
                                Anda mengunggah file gambar.</p>
                        </div>
                    </div>

                    <div class="lg:col-span-6 border-l dark:border-gray-700 lg:pl-8">
                        <div class="space-y-6">

                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-3">Jenis
                                    Pengajuan</label>
                                <div class="flex gap-6">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="radio" name="jenis" value="sakit"
                                            class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 focus:ring-orange-500"
                                            data-validate="surat" checked>
                                        <span
                                            class="ml-2 text-gray-700 dark:text-gray-200 group-hover:text-orange-500 transition-colors">Sakit</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="radio" name="jenis" value="izin"
                                            class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 focus:ring-orange-500"
                                            data-validate="surat">
                                        <span
                                            class="ml-2 text-gray-700 dark:text-gray-200 group-hover:text-orange-500 transition-colors">Izin
                                            Penting</span>
                                    </label>
                                </div>
                                <span class="text-red-600 text-sm" id="status_error">
                                    @error('status')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700/30 p-5 rounded-xl border dark:border-gray-700">
                                <label
                                    class="block text-sm font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-4">Rentang
                                    Waktu</label>
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center text-gray-700 dark:text-gray-200">
                                    {{-- <div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Mulai
                                            Dari</span>
                                        <input type="date" name="tgl_mulai" value="{{ old('tgl_mulai') }}"
                                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-600 focus:ring-2 focus:ring-orange-500 outline-none"
                                            required>
                                        <span class="text-red-600 text-sm" id="tgl_mulai_error">
                                            @error('tgl_mulai')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div> --}}
                                    <div class="relative">

                                        <input type="date" name="tgl" value="{{ old('tgl') }}"
                                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-600 focus:ring-2 focus:ring-orange-500 outline-none"
                                            required>
                                    </div>
                                    <span class="text-red-600 text-sm" id="tgl_error">
                                        @error('tgl')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label for="keterangan"
                                    class="block text-sm font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Alasan
                                    / Keterangan</label>
                                <textarea id="keterangan" name="keterangan" rows="4"
                                    class="w-full px-4 py-3 border rounded-xl dark:bg-gray-800 dark:border-gray-600 focus:ring-2 focus:ring-orange-500 outline-none text-gray-700 dark:text-gray-200"
                                    placeholder="Berikan alasan detail ketidakhadiran Anda..."></textarea>
                                <span class="text-red-600 text-sm" id="keterangan_error">
                                    @error('keterangan')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                                <a href="{{ route('mahasiswa.presensi') }}"
                                    class="sm:w-1/3 text-center px-8 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                    BATAL
                                </a>
                                <button type="submit"
                                    class="flex-1 px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-200 dark:shadow-none transition-all active:scale-95">
                                    KIRIM PENGAJUAN IZIN
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout>
