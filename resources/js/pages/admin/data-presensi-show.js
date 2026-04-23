const presensiId = document.querySelector("#presensi-id").value;

const statusBadge = (status) => {
    const map = {
        0: { color: "bg-red-500", label: "Alpha" },
        1: { color: "bg-green-500", label: "Hadir" },
        2: { color: "bg-blue-500", label: "Izin" },
        3: { color: "bg-yellow-500", label: "Sakit" },
        4: { color: "bg-orange-500", label: "Pending" },
    };
    const s = map[status] ?? { color: "bg-gray-400", label: "-" };
    return `<span class="inline-block px-3 py-1 text-sm font-semibold text-white ${s.color} rounded-full">${s.label}</span>`;
};

function pollStatus() {
    fetch(`/admin/presensi/${presensiId}/status`)
        .then((res) => res.json())
        .then((data) => {
            data.forEach((item) => {
                // cari baris berdasarkan mahasiswa_id
                const row = document.querySelector(
                    `tr[data-mahasiswa-id="${item.mahasiswa_id}"]`,
                );
                if (!row) return;

                // update badge status
                row.querySelector(".col-status").innerHTML = statusBadge(
                    item.status,
                );

                // update waktu jika sudah presensi
                if (item.waktu_presensi) {
                    row.querySelector(".col-waktu").textContent =
                        item.waktu_presensi.slice(11, 19); // ambil HH:MM:SS
                }
            });
        })
        .catch((err) => console.error("Polling error:", err));
}

// Jalankan saat buka halaman
pollStatus();

// Polling tiap 5 detik
setInterval(pollStatus, 5000);
