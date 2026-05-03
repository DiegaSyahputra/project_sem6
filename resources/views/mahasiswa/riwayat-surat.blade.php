<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <p class="text-gray-700 dark:text-white mb-4">Riwayat pengajuan surat izin & sakit Anda</p>

    <div class="w-full mt-5 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold dark:text-white">Riwayat Surat</h2>
            <a href="{{ route('mahasiswa.presensi.izin') }}"
                class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-lg">
                + Ajukan Baru
            </a>
        </div>

        <div class="space-y-4">
            @forelse ($surats as $s)
                <div
                    class="p-4 rounded-xl border dark:border-gray-700 bg-gray-50 dark:bg-gray-700/40 flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <p class="font-bold text-gray-800 dark:text-white">
                            {{ $s->jenis === 'sakit' ? '🤒 Sakit' : '📄 Izin Penting' }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Tanggal: {{ \Carbon\Carbon::parse($s->tgl)->translatedFormat('d F Y') }}
                        </p>
                        @if ($s->keterangan)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Keterangan: {{ $s->keterangan }}
                            </p>
                        @endif
                        @if ($s->status === 'ditolak' && $s->catatan_konfirmator)
                            <p class="text-sm text-red-500 mt-1">
                                Alasan ditolak: {{ $s->catatan_konfirmator }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <span class="text-sm font-semibold {{ $s->labelStatus['color'] }}">
                            {{ $s->labelStatus['label'] }}
                        </span>
                        <a href="{{ Storage::url($s->foto_surat) }}" target="_blank"
                            class="text-xs text-blue-500 hover:underline">
                            <i class="bi bi-file-earmark-image"></i> Lihat Surat
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-400">
                    <p class="text-5xl mb-3">📭</p>
                    <p class="font-semibold">Belum ada pengajuan surat</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
