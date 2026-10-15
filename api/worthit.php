<?php require 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Harga & Validasi | TabungGadget</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* CSS PASTI GELAP */
        select, input[type="text"], input[type="number"], textarea {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }
        select option { background-color: #0f172a !important; color: white !important; padding: 10px; }
        ::placeholder { color: rgba(255, 255, 255, 0.4) !important; }

        .price-good { color: var(--ok); font-weight: bold; }
        .price-ok { color: var(--warn); font-weight: bold; }
        .price-bad { color: var(--bad); font-weight: bold; }
        
        .price-box { background: rgba(0, 0, 0, 0.4); padding: 20px; border-radius: 12px; margin-top: 20px; border: 1px solid rgba(255, 255, 255, 0.1); }
        .spec-badge { background: rgba(255, 255, 255, 0.1); padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; margin-right: 5px; color: #e2e8f0; }
        .hidden { display: none; }

        .upgrade-box { margin-top: 15px; background: rgba(250, 204, 21, 0.1); border: 1px dashed #facc15; padding: 15px; border-radius: 8px; color: #fef08a; font-size: 0.9rem; }
        .upgrade-item { display:flex; justify-content:space-between; margin-bottom:3px; }
    </style>
</head>
<body>

<a href="#main" class="skip-link">Langsung ke konten</a>
<div id="loader"><div class="spinner"></div></div>
<nav>
    <h1>Tabung<span>Gadget</span></h1>
    <div class="menu">
        <a href="index.php">Beranda</a>
        <a href="tabungan.php">Tabungan</a>
        <a href="worthit.php" class="active">Worth It?</a>
        <a href="finder.php">Cari Gadget</a>
    </div>
</nav>

<main id="main">
<div class="container">
    <section class="card">
        <h2>💰 Cek Worth It (Smart Check)</h2>
        <p>Sistem akan memvalidasi apakah hardware cocok dengan jenis gadgetnya.</p>

        <div id="form-manual" style="margin-top:20px;">
            <form method="POST">
                <div style="background:rgba(77, 166, 255, 0.1); padding:15px; border-radius:10px; margin-bottom:20px; border:1px solid rgba(77, 166, 255, 0.3);">
                    <label style="color:#4da6ff; font-weight:bold;">📅 Rencana Dipakai Tahun Berapa?</label>
                    <input type="number" name="target_tahun" value="<?php echo date('Y'); ?>" min="<?php echo date('Y'); ?>" max="2030" required>
                </div>

                <label>1. Tipe Gadget</label>
                <select name="m_type" id="manualType" onchange="adjustForm()" required style="margin-bottom:10px;">
                    <option value="Laptop">Laptop</option>
                    <option value="Smartphone">Smartphone (HP)</option>
                    <option value="Tablet">Tablet</option>
                </select>
                
                <input type="text" name="nama_manual" id="namaInput" placeholder="Nama Gadget" required>
                
                <div style="margin-top:10px;">
                    <label>Processor / Chipset:</label>
                    <input type="text" name="cpu_manual" id="cpuLabel" placeholder="Cth: i5-13420H / Snapdragon 8 Gen 3" required>
                </div>

                <div id="vgaRow" class="hidden" style="margin-top:10px;">
                    <label>Kartu Grafis (VGA):</label>
                    <select name="gpu_manual" id="gpuSelect">
                        <option value="Integrated">Tanpa VGA Tambahan (Intel Iris/UHD)</option>
                        <option value="Entry">Entry (GTX 1650 / RTX 2050 / 3050)</option>
                        <option value="Mid">Mid-Range (RTX 4050 / 4060 / 3060)</option>
                        <option value="High">High-End (RTX 4070 / 4080 / 4090)</option>
                    </select>
                </div>
                
                <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:10px; margin-top:10px;">
                    <div>
                        <label style="font-size:0.8rem">RAM</label>
                        <select name="ram_manual" id="ramSelect" required></select>
                    </div>
                    
                    <div id="storageTypeBox">
                        <label style="font-size:0.8rem">Tipe Storage</label>
                        <select name="storage_type" id="storageTypeSelect">
                            <option value="SSD">SSD / NVMe</option>
                            <option value="HDD">Hardisk (HDD)</option>
                            <option value="eMMC">eMMC</option>
                        </select>
                    </div>
                    
                    <div id="storageCapacityBox">
                        <label style="font-size:0.8rem">Kapasitas (GB)</label>
                        <input type="text" name="storage_size" placeholder="Cth: 256" required>
                    </div>
                </div>

                <div style="margin-top:10px;">
                    <label>Kategori Utama:</label>
                    <select name="cat_manual" id="catSelect" required>
                        </select>
                </div>

                <div style="margin-top:20px; padding-top:20px; border-top:1px dashed rgba(255,255,255,0.2);">
                    <label>Harga Tawaran / Jual:</label>
                    <input type="text" name="harga_manual" class="input-rupiah" placeholder="Rp..." required style="font-size:1.2rem; font-weight:bold;">
                </div>
                
                <button type="submit" name="cek_manual" style="margin-top:20px;">⚡ Cek Analisis Lengkap</button>
            </form>
        </div>

        <?php
        if (isset($_POST['cek_manual'])) {
            $targetYear = $_POST['target_tahun'];
            $nama = $_POST['nama_manual'];
            $type = $_POST['m_type'];
            $cpu = $_POST['cpu_manual'];
            $ram = $_POST['ram_manual'];
            $storageSize = (int)$_POST['storage_size'];
            $category = $_POST['cat_manual'];
            $hargaInput = (int)str_replace('.', '', $_POST['harga_manual']);
            
            $gpu = ($type == 'Laptop' && isset($_POST['gpu_manual'])) ? $_POST['gpu_manual'] : 'Integrated';
            $storageType = ($type == 'Laptop') ? $_POST['storage_type'] : 'SSD'; 

            // STEP 1: VALIDASI INPUT HARDWARE (FITUR BARU)
            $validasi = validasiHardware($type, $cpu);
            
            if ($validasi !== "OK") {
                // Jika validasi gagal, tampilkan error
                echo "<div class='card' style='margin-top:20px; border-left: 3px solid #f87171;'>
                        <h3>❌ Input Tidak Logis</h3>
                        <div class='price-box'>
                            <h2 class='price-bad'>KESALAHAN INPUT</h2>
                            <p style='color:#fca5a5;'>$validasi</p>
                            <hr style='opacity:0.1'>
                            <small>Silakan perbaiki nama Processor/Chipset atau Jenis Gadget Anda.</small>
                        </div>
                      </div>";
            } else {
                // Jika lolos, lanjut analisis
                analisisLengkap($nama, $ram, $storageType, $storageSize, $cpu, $gpu, $type, $category, $hargaInput, $targetYear);
            }
        }

        // --- FUNGSI BARU: VALIDASI HARDWARE ---
        function validasiHardware($type, $cpu) {
            $cpu_l = strtolower($cpu);
            
            // 1. CEK JIKA USER MEMILIH LAPTOP
            if ($type == 'Laptop') {
                // Laptop tidak mungkin pakai Chipset HP (Snapdragon Mobile, Dimensity, Helio, Exynos)
                // Kecuali Snapdragon X Elite (Laptop ARM), tapi kita anggap user awam dulu.
                if (preg_match('/(dimensity|helio|exynos|unisoc|kirin|bionic|tensor)/', $cpu_l)) {
                    return "Chipset <b>'$cpu'</b> adalah prosesor HP/Tablet, tidak mungkin ada di Laptop.";
                }
                if (preg_match('/(snapdragon)/', $cpu_l) && !preg_match('/(x elite|compute)/', $cpu_l) && !preg_match('/(8cx|7c)/', $cpu_l)) {
                     // Blokir Snapdragon biasa (hp), izinkan Snapdragon X Elite (laptop)
                     if (preg_match('/(gen 1|gen 2|gen 3|680|695|778)/', $cpu_l)) {
                        return "Chipset <b>'$cpu'</b> adalah untuk HP. Laptop Windows ARM biasanya pakai 'Snapdragon X Elite'.";
                     }
                }
            }

            // 2. CEK JIKA USER MEMILIH HP / TABLET
            if ($type == 'Smartphone' || $type == 'Tablet') {
                // HP tidak mungkin pakai Processor PC (Intel Core, AMD Ryzen, RTX, GTX)
                // Kecuali Tablet Windows (Surface) tapi itu biasanya masuk kategori Laptop di pasar
                if (preg_match('/(rtx|gtx|ryzen|intel core|i3|i5|i7|i9)/', $cpu_l)) {
                    return "Processor <b>'$cpu'</b> adalah komponen PC/Laptop. Tidak mungkin ada di Smartphone/Tablet Android/iOS.";
                }
            }

            return "OK"; // Lolos Validasi
        }

        function analisisLengkap($nama, $ram, $storageType, $storageSize, $cpu, $gpu, $type, $category, $hargaInput, $targetYear) {
            $cpu_l = strtolower($cpu);
            $upgrade_list = [];
            $biaya_upgrade = 0;
            $is_laptop = ($type == 'Laptop');
            $alasan = [];
            $expert_verdict = [];
            
            // --- KASTA CHIPSET/CPU ---
            $tier = "Basic";
            if ($is_laptop) {
                if (preg_match('/(i9|ryzen 9|m3 max|m4 max|ultra 9|x elite)/', $cpu_l)) $tier = "Enthusiast";
                elseif (preg_match('/(i7|ryzen 7|m2 pro|m3 pro|ultra 7)/', $cpu_l)) $tier = "High-End";
                elseif (preg_match('/(i5|ryzen 5|m1|m2|m3|ultra 5)/', $cpu_l)) $tier = "Mid-Range";
                elseif (preg_match('/(i3|ryzen 3)/', $cpu_l)) $tier = "Entry";
            } else {
                if (preg_match('/(gen 3|gen 4|a17|a18|dimensity 9|m4)/', $cpu_l)) $tier = "Flagship";
                elseif (preg_match('/(gen 2|888|a15|a16|dimensity 8)/', $cpu_l)) $tier = "High-End";
                elseif (preg_match('/(7 gen|dimensity 7|helio g99)/', $cpu_l)) $tier = "Mid-Range";
                else $tier = "Entry Level";
            }

            // --- STANDAR 2026 ---
            $req_ram = 8;
            $req_storage = 256;
            if ($is_laptop) {
                if ($category == 'Gaming' || $category == 'Editing') { $req_ram = 16; $req_storage = 512; }
                else { $req_ram = 8; $req_storage = 256; }
            } else {
                if ($category == 'Gaming' || $category == 'Camera') { $req_ram = 8; $req_storage = 256; }
                else { $req_ram = 6; $req_storage = 128; }
            }

            // --- BOTTLENECK & SYNERGY DETECTION (EXPERT LOGIC) ---
            $is_bottleneck = false;
            $is_overkill = false;

            if ($is_laptop) {
                // Bottleneck GPU kuat tapi CPU lemah
                if (($gpu == 'High' || $gpu == 'Mid') && $tier == "Entry") {
                    $expert_verdict[] = "💥 **BOTTLENECK CPU:** Processor kelas Entry ($cpu) tidak akan sanggup mengimbangi kecepatan VGA Dedicated kelas atas/menengah. Kinerja grafis akan sangat terhambat (*stuttering* parah).";
                    $is_bottleneck = true;
                }
                // Bottleneck CPU kuat tapi RAM kecil
                if (($tier == "High-End" || $tier == "Enthusiast") && $ram < 16) {
                    $expert_verdict[] = "💥 **BOTTLENECK RAM:** Processor kasta atas ($cpu) dipasangkan dengan RAM yang sangat kecil ($ram GB). Ini sangat menyiksa dan mengubur performa prosesor.";
                    $is_bottleneck = true;
                }
                // Overkill
                if ($category == 'Office' && ($gpu == 'High' || $tier == "Enthusiast")) {
                    $expert_verdict[] = "💰 **OVERKILL SPESIFIKASI:** Spesifikasi ini terlalu *dewa* hanya untuk kebutuhan Office/Ngetik biasa. Baterainya dipastikan sangat boros dan bodinya kemungkinan tebal. Sangat mubazir jika Anda tidak berniat main game atau *editing*.";
                    $is_overkill = true;
                }
            } else {
                if (($tier == "Flagship" || $tier == "High-End") && $ram <= 6) {
                    $expert_verdict[] = "💥 **BOTTLENECK RAM:** Membeli HP Flagship ($cpu) tapi dengan RAM cuma $ram GB adalah bunuh diri spesifikasi. Multitasking akan berantakan dan game akan sering *force close*.";
                    $is_bottleneck = true;
                }
                if ($category == 'Daily' && ($tier == "Flagship" || $tier == "High-End")) {
                    $expert_verdict[] = "💰 **OVERKILL SPESIFIKASI:** Chipset super kencang ($cpu) cenderung menguras baterai dan panas. Untuk pemakaian Daily (sosmed/chat), Anda justru membuang uang dan baterai. Sebaiknya cari HP Mid-Range yang fokus pada efisiensi daya.";
                    $is_overkill = true;
                }
            }

            // --- CEK KOMPONEN KURANG KHUSUS ---
            if ($ram < $req_ram) {
                $alasan[] = "Kapasitas RAM ($ram GB) di bawah standar tahun $targetYear untuk kebutuhan $category. Disarankan minimal $req_ram GB.";
                if ($is_laptop) {
                    $biaya = ($req_ram - $ram) * 50000; $biaya_upgrade += $biaya;
                    $upgrade_list[] = ["Upgrade RAM ke minimum $req_ram GB", $biaya];
                } else {
                    $alasan[] = "RAM pada Smartphone/Tablet bersifat permanen. Jika dipaksa dipakai kerja berat, performa akan sering lag dan aplikasi otomatis menutup.";
                }
            }

            if ($storageSize < $req_storage) {
                $alasan[] = "Penyimpanan $storageSize GB sangat sempit, OS dan aplikasi tahun $targetYear sangat rakus memori.";
                if ($is_laptop) {
                    $biaya = 500000; $biaya_upgrade += $biaya;
                    $upgrade_list[] = ["Upgrade / Tambah kapasitas Storage SSD", $biaya];
                }
            }

            if ($is_laptop && $storageType == 'HDD') {
                $alasan[] = "Masih menggunakan HDD. Menjalankan Windows di tahun $targetYear menggunakan HDD akan membuat proses *booting* & *loading* sangat lambat.";
                $biaya = 350000; $biaya_upgrade += $biaya;
                $upgrade_list[] = ["Ganti HDD menjadi SSD NVMe/SATA", $biaya];
            }

            if ($category == 'Gaming') {
                if ($is_laptop && $gpu == 'Integrated') {
                    $alasan[] = "Kebutuhan Anda GAMING, tapi Laptop ini HANYA menggunakan VGA Bawaan (Integrated). Sistem BUKAN LAPTOP GAMING.";
                    $is_bottleneck = true;
                }
                if (!$is_laptop && $tier == "Entry Level") {
                    $alasan[] = "Kasta chipset ($cpu) berada di kelas Entry Level. Chipset kelas ini pasti overheat dan patah-patah parah untuk game modern.";
                }
            }

            if ($category == 'Editing') {
                if ($tier == "Entry") {
                    $alasan[] = "Processor kelas Entry ($cpu) akan membuat proses *Rendering* video memakan waktu berjam-jam.";
                }
                if ($is_laptop && $gpu == 'Integrated' && !preg_match('/(m1|m2|m3|m4)/', $cpu_l)) {
                    $alasan[] = "Editing modern butuh Akselerasi VGA. VGA Integrated Windows sangat payah untuk *timeline* video berat.";
                }
            }
            
            if ($category == 'Programming') {
                if ($ram < 16) {
                    $alasan[] = "Kebutuhan Anda PROGRAMMING (Coding). Menjalankan emulator, Docker, atau IDE (seperti Android Studio) dengan RAM $ram GB akan sering membuat laptop hang.";
                }
                if ($tier == "Entry") {
                    $alasan[] = "Processor kelas Entry ($cpu) akan membuat proses *Compile / Build* kode menjadi sangat lama.";
                }
            }
            
            if ($category == 'Camera') {
                if ($tier == "Entry Level" || $tier == "Mid-Range") {
                    $alasan[] = "Kebutuhan Anda FOTOGRAFI, namun Chipset kelas ini ($cpu) memiliki pemroses gambar (ISP) yang standar. Kualitas foto malam akan *noise*.";
                }
            }

            // --- NEW VALUATION LOGIC (2026 MARKET STANDARDS) ---
            $base_val = 0;
            if ($is_laptop) {
                if ($tier == "Enthusiast") $base_val = 22000000;
                elseif ($tier == "High-End") $base_val = 14000000;
                elseif ($tier == "Mid-Range") $base_val = 8500000;
                elseif ($tier == "Entry") $base_val = 5500000;

                if ($gpu == 'High') $base_val += 12000000;
                elseif ($gpu == 'Mid') $base_val += 6000000;
                elseif ($gpu == 'Entry') $base_val += 2500000;
            } else {
                if ($tier == "Flagship") $base_val = 15000000;
                elseif ($tier == "High-End") $base_val = 8000000;
                elseif ($tier == "Mid-Range") $base_val = 4500000;
                elseif ($tier == "Entry") $base_val = 2000000;
            }

            // Premium Synergy (Build Quality / Screen Quality / Brand Premium)
            if ($is_laptop && preg_match('/(m1|m2|m3|m4|zenbook|xps|spectre|yoga|rog|legion)/i', $nama)) {
                $base_val += 3000000; // Premium Build Quality Value
            }

            // RAM/Storage Extra Valuations
            if ($ram > 8) $base_val += ($ram - 8) * 120000; // Valuasi per GB sisa
            if ($storageSize > 256) $base_val += (($storageSize - 256) / 256) * 400000;

            // Jika Bottleneck Parah, Value total alat ini jatuh di mata pakar
            if ($is_bottleneck && $is_laptop) {
                $base_val = $base_val * 0.7; // Diskon / Penalti 30% karena performa cacat pabrik
            }

            // HITUNG RASIO HARGA TAHAP AKHIR
            $total_modal = $hargaInput + $biaya_upgrade;
            $wajar_max = $base_val * 1.20; // 20% toleransi margin (pajak/toko ambil untung)
            $wajar_min = $base_val * 0.85; // Bawah 85% = super murah / cuci gudang
            
            // --- KEPUTUSAN STATUS ---
            $status = ""; $class = "";

            if ($total_modal > $wajar_max) {
                $status = "⚠️ OVERPRICE (KEMAHALAN)"; 
                $class = "price-bad";
                $expert_verdict[] = "📉 **VALUASI HARGA:** Mengacu pada standar harga baru di $targetYear, nilai wajar/layak pasar gadget ini seharusnya hanya berkisar di angka **Rp ".number_format($base_val,0,',','.')."**. Tawaran Rp ".number_format($hargaInput,0,',','.')." jelas sangat menguras kantong Anda (Terlalu Mahal).";
            } elseif ($total_modal < $wajar_min) {
                if (count($upgrade_list) > 0) {
                    $status = "🔥 DEAL BAGUS (WAJIB UPGRADE)"; 
                    $class = "price-good";
                    $expert_verdict[] = "📈 **VALUASI HARGA:** Anda mendapat tawaran yang SANGAT MURAH! Nilai pasarnya sekitar **Rp ".number_format($base_val,0,',','.')."**. Namun pastikan Anda menyisihkan *budget* untuk melakukan **UPGRADE KOMPONEN** yang disarankan agar gadget ini sempurna.";
                } else {
                    $status = "🔥 SUPER STEAL DEAL (SANGAT WORTH IT)"; 
                    $class = "price-good";
                    $expert_verdict[] = "📈 **VALUASI HARGA:** Ini adalah harga hancur / diskon besar! Harga pasar wajarnya berada di angka **Rp ".number_format($base_val,0,',','.')."**. Langsung *checkout* jika Anda yakin toko/barangnya terpercaya!";
                }
            } else {
                if (count($upgrade_list) > 0) {
                    $status = "✅ HARGA STANDAR (TAPI WAJIB UPGRADE)"; 
                    $class = "price-ok";
                    $expert_verdict[] = "⚖️ **VALUASI HARGA:** Harga yang ditawarkan sesuai dengan standar $targetYear (yaitu di angka **Rp ".number_format($base_val,0,',','.')."**). Namun, karena memiliki kekurangan komponen yang kritis, Anda **WAJIB melakukan upgrade** komponen.";
                } else {
                    $status = "✅ HARGA PASARAN (WORTH IT)"; 
                    $class = "price-ok";
                    $expert_verdict[] = "⚖️ **VALUASI HARGA:** Harga yang wajar dan tepat (berkisar di angka **Rp ".number_format($base_val,0,',','.')."**). Tidak kemahalan, tidak kemurahan. Cocok dan aman untuk dibeli.";
                }
            }
            
            // Overrides (Menolak jika HP/Laptop tidak bisa diupgrade tapi speknya mati)
            if (!$is_laptop && !empty($alasan) && $status != "⚠️ OVERPRICE (KEMAHALAN)") {
                $status = "❌ JANGAN DIBELI (SPEK MATI)"; 
                $class = "price-bad";
                $expert_verdict[] = "⛔ **KESIMPULAN FINAL:** Walaupun harganya dinilai wajar atau murah, spesifikasinya cacat (kurang memori) dan komponen HP/Tablet TIDAK BISA DIUPGRADE sama sekali. Sebaiknya urungkan niat Anda.";
            }

            if ($is_laptop && $category == 'Gaming' && $gpu == 'Integrated') {
                $status = "❌ FATAL: BUKAN LAPTOP GAMING"; 
                $class = "price-bad";
                $expert_verdict[] = "⛔ **KESIMPULAN FINAL:** Membeli laptop tanpa VGA Dedicated (VGA Tambahan) untuk *Gaming* adalah kesalahan besar. Tolong cari laptop tipe lain.";
            }

            // RENDER TAMPILAN
            echo "<div class='card' style='margin-top:20px; border-left: 3px solid ".($class == 'price-good' ? '#4ade80' : ($class == 'price-ok' ? '#facc15' : '#f87171')).";'>";
            echo "<h3>Analisis Pakar: $nama</h3>";
            echo "<p><span class='spec-badge'>$category</span> <span class='spec-badge'>$tier</span> <span class='spec-badge'>$ram GB RAM</span></p>";
            
            echo "<div class='price-box'>
                    <h2 class='$class' style='margin:0; margin-bottom:15px;'>$status</h2>";
            
            if (!empty($expert_verdict)) {
                echo "<div style='margin-top:10px; padding:10px; background:rgba(0,0,0,0.3); border-radius:8px;'>";
                foreach($expert_verdict as $ver) { 
                    $ver_html = preg_replace('/\*\*(.*?)\*\*/', '<b style="color:#ffffff;">$1</b>', $ver);
                    echo "<p style='color:#cbd5e1; font-size:0.95rem; margin-bottom:10px; line-height:1.6;'>$ver_html</p>"; 
                }
                echo "</div>";
            }

            if (!empty($alasan)) {
                echo "<div style='margin-top:15px; background:rgba(255,0,0,0.05); padding:15px; border-radius:8px; border-left:3px solid #f87171;'>";
                echo "<strong style='color:#f87171;'>⚠️ KELEMAHAN & KEKURANGAN:</strong><ul style='margin:10px 0 0 20px; color:#fca5a5; font-size:0.9rem; line-height:1.6;'>";
                foreach($alasan as $w) { echo "<li style='margin-bottom:8px;'>$w</li>"; }
                echo "</ul></div>";
            }

            if (!empty($upgrade_list)) {
                echo "<div class='upgrade-box' style='margin-top:15px;'><span class='upgrade-title'>🛠️ SOLUSI UPGRADE AGAR LAYAK PAKAI:</span>";
                foreach($upgrade_list as $up) { echo "<div class='upgrade-item'><span>• {$up[0]}</span> <span>+Rp ".number_format($up[1], 0, ',', '.')."</span></div>"; }
                echo "<hr style='border-color:rgba(0,0,0,0.1); margin:10px 0;'>
                      <div class='upgrade-item' style='font-weight:bold; color:#4ade80;'><span>Total Biaya Tambahan Upgrade:</span> <span>Rp ".number_format($biaya_upgrade, 0, ',', '.')."</span></div></div>";
            }

            echo "<hr style='opacity:0.1; margin:20px 0 15px 0;'>
                    <div style='display:grid; grid-template-columns: 1fr 1fr; gap:10px;'>
                        <div style='background:rgba(255,255,255,0.05); padding:10px; border-radius:8px;'>
                            <small>Harga Beli / Tawaran</small><br><b style='font-size:1.1rem;'>Rp ".number_format($hargaInput, 0, ',', '.')."</b>
                        </div>
                        <div style='background:rgba(255,255,255,0.05); padding:10px; border-radius:8px;'>
                            <small>Total Modal (Harga Beli + Upgrade)</small><br><b style='font-size:1.1rem; color:".($biaya_upgrade>0?'#facc15':'#4ade80').";'>Rp ".number_format($total_modal, 0, ',', '.')."</b>
                        </div>
                    </div>
                  </div>";
            echo "</div>";
        }
        ?>
    </section>
</div>

<script>
    function adjustForm() {
        const type = document.getElementById('manualType').value;
        const ram = document.getElementById('ramSelect');
        const cpu = document.getElementById('cpuLabel');
        const catSelect = document.getElementById('catSelect');
        const vgaRow = document.getElementById('vgaRow');
        const storageBox = document.getElementById('storageTypeBox');
        const capBox = document.getElementById('storageCapacityBox');
        const nameInput = document.getElementById('namaInput');

        if (type === 'Laptop') {
            nameInput.placeholder = "Cth: Lenovo Legion / Acer Nitro";
            cpu.placeholder = "Cth: i5-13420H / Ryzen 5";
            vgaRow.classList.remove('hidden'); 
            storageBox.style.display = 'block'; 
            capBox.style.gridColumn = 'auto';
            catSelect.innerHTML = `<option value="Office">Office / Kuliah</option><option value="Gaming">Gaming / Berat</option><option value="Editing">Editing / Desain</option><option value="Programming">Coding</option>`;
            ram.innerHTML = `<option value="4">4 GB</option><option value="8">8 GB</option><option value="16">16 GB</option><option value="32">32 GB</option>`;
        } else {
            nameInput.placeholder = "Cth: Samsung S24 / iPhone 15";
            cpu.placeholder = "Cth: Snapdragon 8 Gen 3";
            vgaRow.classList.add('hidden'); 
            storageBox.style.display = 'none'; 
            capBox.style.gridColumn = 'span 2';
            catSelect.innerHTML = `<option value="Daily">Daily / Baterai Awet</option><option value="Gaming">Gaming Mobile</option><option value="Camera">Fotografi</option><option value="Multitasking">Multitasking</option>`;
            ram.innerHTML = `<option value="4">4 GB</option><option value="6">6 GB</option><option value="8">8 GB</option><option value="12">12 GB</option><option value="16">16 GB</option>`;
        }
    }

    document.addEventListener('keyup', function(e) {
        if (e.target.classList.contains('input-rupiah')) {
            let val = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });

    window.onload = adjustForm;
</script>
</body>
</html>
