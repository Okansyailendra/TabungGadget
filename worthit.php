<?php require 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Harga & Validasi | TabungGadget</title>
    <link rel="stylesheet" href="assets/style.css">
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

        .price-good { color: #4ade80; font-weight: bold; }
        .price-ok { color: #facc15; font-weight: bold; }
        .price-bad { color: #f87171; font-weight: bold; }
        
        .price-box { background: rgba(0, 0, 0, 0.4); padding: 20px; border-radius: 12px; margin-top: 20px; border: 1px solid rgba(255, 255, 255, 0.1); }
        .spec-badge { background: rgba(255, 255, 255, 0.1); padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; margin-right: 5px; color: #e2e8f0; }
        .hidden { display: none; }

        .upgrade-box { margin-top: 15px; background: rgba(250, 204, 21, 0.1); border: 1px dashed #facc15; padding: 15px; border-radius: 8px; color: #fef08a; font-size: 0.9rem; }
        .upgrade-item { display:flex; justify-content:space-between; margin-bottom:3px; }
    </style>
</head>
<body>

<nav>
    <h1>Tabung<span>Gadget</span></h1>
    <div class="menu">
        <a href="index.php">Beranda</a>
        <a href="tabungan.php">Tabungan</a>
        <a href="worthit.php" class="active">Worth It</a>
        <a href="finder.php">Cari Gadget</a>
    </div>
</nav>

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
                echo "<div class='card' style='margin-top:20px; border-left: 5px solid #f87171;'>
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
            
            // --- KASTA CHIPSET/CPU ---
            $tier = "Basic";
            if ($is_laptop) {
                if (preg_match('/(i9|ryzen 9|m3 max|m4 max)/', $cpu_l)) $tier = "Enthusiast";
                elseif (preg_match('/(i7|ryzen 7|m2 pro|ultra 7)/', $cpu_l)) $tier = "High-End";
                elseif (preg_match('/(i5|ryzen 5|m1|ultra 5)/', $cpu_l)) $tier = "Mid-Range";
                elseif (preg_match('/(i3|ryzen 3)/', $cpu_l)) $tier = "Entry";
            } else {
                if (preg_match('/(gen 3|gen 2|a17|a18|dimensity 9|m4)/', $cpu_l)) $tier = "Flagship";
                elseif (preg_match('/(gen 1|888|a15|a16|dimensity 8)/', $cpu_l)) $tier = "High-End";
                elseif (preg_match('/(7 gen|dimensity 7|helio g99)/', $cpu_l)) $tier = "Mid-Range";
                else $tier = "Entry Level";
            }

            // --- CEK KECOCOKAN KATEGORI ---
            $cat_warning = [];
            
            if ($category == 'Camera') {
                if ($storageSize < 256) $cat_warning[] = "⚠️ Fotografi butuh penyimpanan besar. 128GB cepat habis.";
                if ($tier == "Entry Level" || $tier == "Mid-Range") $cat_warning[] = "⚠️ Chipset kelas ini biasanya punya ISP standar. Foto malam mungkin kurang bagus.";
            }
            if ($category == 'Daily') {
                if (preg_match('/(888|gen 1)/', $cpu_l)) $cat_warning[] = "⚠️ Chipset tipe ini (SD 888/Gen1) dikenal panas & boros baterai.";
                if ($ram < 6) $cat_warning[] = "⚠️ RAM kecil akan membuat HP sering reload aplikasi (boros daya).";
            }
            if ($category == 'Gaming') {
                if ($is_laptop && $gpu == 'Integrated') $cat_warning[] = "⛔ Laptop tanpa VGA Dedicated TIDAK COCOK untuk gaming berat.";
                if (!$is_laptop && $tier == "Entry Level") $cat_warning[] = "⛔ Chipset Entry Level akan lag main game berat modern.";
            }

            // --- REKOMENDASI UPGRADE ---
            if ($is_laptop) {
                $req_ram = ($category == 'Gaming' || $category == 'Editing') ? 16 : 8;
                if ($ram < $req_ram) {
                    $biaya = 350000; $biaya_upgrade += $biaya;
                    $upgrade_list[] = ["Tambah RAM (Target $req_ram GB)", $biaya];
                }
                if ($storageType == 'HDD') {
                    $biaya = 300000; $biaya_upgrade += $biaya;
                    $upgrade_list[] = ["Ganti HDD ke SSD", $biaya];
                }
            } else {
                if ($ram < 6 && $category != 'Daily') $cat_warning[] = "⚠️ RAM $ram GB tergolong kecil untuk $targetYear (HP tidak bisa upgrade).";
                if ($storageSize < 128) $cat_warning[] = "⚠️ Penyimpanan $storageSize GB sangat sempit untuk standar $targetYear.";
            }

            // --- VALUASI HARGA ---
            $base_val = 1500000;
            if ($tier == "Enthusiast" || $tier == "Flagship") $base_val = 11000000;
            elseif ($tier == "High-End") $base_val = 7500000;
            elseif ($tier == "Mid-Range") $base_val = 3500000;
            elseif ($tier == "Entry") $base_val = 2000000;

            if ($is_laptop && $gpu == 'High') $base_val += 5000000;
            if ($is_laptop && $gpu == 'Mid') $base_val += 3000000;
            if ($ram > 8) $base_val += ($ram - 8) * 150000;

            $total_modal = $hargaInput + $biaya_upgrade;
            $wajar = $base_val * 1.15; 
            $ratio = $total_modal / $wajar;

            // --- HASIL AKHIR ---
            $status = ""; $class = "";
            if ($ratio < 0.85) { $status = "🔥 DEAL BAGUS"; $class = "price-good"; }
            elseif ($ratio <= 1.2) { $status = "✅ HARGA WAJAR"; $class = "price-ok"; }
            else { $status = ($biaya_upgrade > 0) ? "⚠️ KEMAHALAN (SETELAH UPGRADE)" : "⚠️ KEMAHALAN"; $class = "price-bad"; }
            
            if (count($cat_warning) >= 2) { $status = "❌ TIDAK SESUAI KEBUTUHAN"; $class = "price-bad"; }

            echo "";
            echo "<div class='card' style='margin-top:20px; border-left: 5px solid ".($class == 'price-good' ? '#4ade80' : ($class == 'price-ok' ? '#facc15' : '#f87171')).";'>";
            echo "<h3>Analisis: $nama</h3>";
            echo "<p><span class='spec-badge'>$category</span> <span class='spec-badge'>$tier</span> <span class='spec-badge'>$ram GB</span></p>";
            
            echo "<div class='price-box'>
                    <h2 class='$class' style='margin:0;'>$status</h2>";

            if (!empty($cat_warning)) {
                echo "<div style='margin-top:10px; background:rgba(255,255,255,0.05); padding:10px; border-radius:8px;'>";
                foreach($cat_warning as $w) { echo "<p style='color:#fca5a5; font-size:0.9rem; margin:3px 0;'>$w</p>"; }
                echo "</div>";
            }

            if (!empty($upgrade_list)) {
                echo "<div class='upgrade-box'><span class='upgrade-title'>🛠️ REKOMENDASI UPGRADE:</span>";
                foreach($upgrade_list as $up) { echo "<div class='upgrade-item'><span>• {$up[0]}</span> <span>+Rp ".number_format($up[1])."</span></div>"; }
                echo "<hr style='border-color:rgba(0,0,0,0.1); margin:10px 0;'>
                      <div class='upgrade-item' style='font-weight:bold;'><span>Biaya Upgrade:</span> <span>Rp ".number_format($biaya_upgrade)."</span></div></div>";
            }

            echo "<hr style='opacity:0.1; margin:15px 0;'>
                    <div style='display:grid; grid-template-columns: 1fr 1fr; gap:10px;'>
                        <div><small>Harga Unit</small><br><b style='font-size:1.1rem;'>Rp ".number_format($hargaInput)."</b></div>
                        <div><small>Total Modal</small><br><b style='font-size:1.1rem;'>Rp ".number_format($total_modal)."</b></div>
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