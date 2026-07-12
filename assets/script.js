// Loader
window.onload = () => {
    const loader = document.getElementById("loader");
    if(loader) {
        loader.style.opacity = "0";
        setTimeout(() => loader.style.display = "none", 500);
    }
};

// Fungsi Grafik Tabungan (Dipanggil di tabungan.php)
// Fungsi Render Chart Fleksibel (Harian/Bulanan)
function renderChart(targetHarga, tabunganAwal, nabungHarian, totalHari) {
    const ctx = document.getElementById('chartCanvas');
    if(!ctx) return;

    if(window.myChart instanceof Chart) {
        window.myChart.destroy();
    }

    // Konversi hari ke bulan untuk tampilan grafik agar tidak terlalu padat
    let labels = [];
    let data = [];
    
    // Titik Awal (Bulan 0 / Hari Ini)
    labels.push('Hari Ini');
    data.push(tabunganAwal);

    let currentMoney = tabunganAwal;
    const nabungPerBulan = nabungHarian * 30; 
    let bulanKe = 1;

    // Loop simulasi per bulan
    while (currentMoney < targetHarga) {
        currentMoney += nabungPerBulan;
        if(currentMoney > targetHarga) currentMoney = targetHarga; // Cap di harga max
        
        labels.push('Bulan ke-' + bulanKe);
        data.push(currentMoney);
        bulanKe++;

        // Safety break agar browser tidak hang jika nabung terlalu kecil (max 60 bulan/5 tahun)
        if(bulanKe > 60) break;
    }

    window.myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Progres Tabungan',
                data: data,
                borderColor: '#4da6ff',
                backgroundColor: 'rgba(77, 166, 255, 0.15)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#020617',
                pointBorderColor: '#4da6ff'
            },
            {
                label: 'Harga gadget',
                data: Array(labels.length).fill(targetHarga), // Garis lurus target
                borderColor: '#facc15', // Warna Kuning
                borderWidth: 2,
                borderDash: [5, 5], // Garis putus-putus
                pointRadius: 0,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: 'white' } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: { 
                    grid: { color: 'rgba(255,255,255,0.05)' }, 
                    ticks: { color: '#94a3b8', callback: val => (val/1000000).toFixed(1) + ' Jt' },
                    min: 0,
                    suggestedMax: targetHarga * 1.1
                },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });
}
/* --- FORMAT RUPIAH INPUT --- */
function formatRupiah(input) {
    // Hapus karakter selain angka
    let value = input.value.replace(/[^0-9]/g, '');
    
    // Tambahkan titik setiap 3 digit dari belakang
    if (value) {
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    } else {
        input.value = "";
    }
}

// Otomatis pasang event listener ke semua input dengan class "input-rupiah"
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('input-rupiah')) {
        formatRupiah(e.target);
    }
})
