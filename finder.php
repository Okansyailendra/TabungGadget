<?php require 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Gadget | TabungGadget</title>
    <link rel="stylesheet" href="assets/style.css">

</head>
<body>
    <nav>
        <h1>Tabung<span>Gadget</span></h1>
        <div class="menu">
            <a href="index.php">Beranda</a>
            <a href="tabungan.php">Tabungan</a>
            <a href="worthit.php">Worth It</a>
            <a href="finder.php" class="active">Cari Gadget</a>
        </div>
    </nav>

    <div class="container">
        <section class="card">
            <h2>🔍 Gadget Finder</h2>
            <p>Temukan gadget terbaik berdasarkan kategori dan budget Anda.</p>
            <form method="POST">
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <label>Jenis Gadget</label>
                        <select name="type" required>
                            <option value="Laptop">Laptop</option>
                            <option value="Smartphone">Smartphone (HP)</option>
                            <option value="Tablet">Tablet</option>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <label>Kategori / Kebutuhan</label>
                        <select name="category" required>
                            <option value="Gaming">Gaming</option>
                            <option value="Office">Office / Kantoran</option>
                            <option value="Editing">Editing / Desain</option>
                            <option value="Menggambar">Menggambar (Tablet)</option>
                            <option value="Belajar">Belajar / Sekolah</option>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <label>Budget Max (Rp)</label>
                        <input type="number" name="budget" placeholder="Contoh: 10000000" required>
                    </div>
                </div>
                <button type="submit" name="cari" style="margin-top:20px;">Cari Sekarang</button>
            </form>
        </section>

        <div class="grid-result">
            <?php
            if(isset($_POST['cari'])){
                $t = $_POST['type']; $c = $_POST['category']; $b = $_POST['budget'];
                $q = mysqli_query($conn, "SELECT * FROM gadgets WHERE gadget_type='$t' AND category='$c' AND price <= $b ORDER BY price DESC");
                if(mysqli_num_rows($q) > 0){
                    while($d = mysqli_fetch_assoc($q)){
                        echo "
                        <div class='laptop-item card'>
                            <h4>{$d['name']}</h4>
                            <span style='font-size:0.7rem; background:rgba(255,255,255,0.1); padding:3px 8px; border-radius:5px;'>{$d['gadget_type']} - {$d['category']}</span>
                            <div style='margin-top:10px; font-size:0.85rem;'>
                                <p>⚙️ {$d['cpu']}</p>
                                <p>📟 RAM {$d['ram']}GB | {$d['storage']}</p>
                            </div>
                            <span class='price-tag'>Rp ".number_format($d['price'],0,',','.')."</span>
                            <a href='tabungan.php?pilih={$d['id']}' style='display:block; text-align:center; margin-top:10px; text-decoration:none; color:var(--primary); border:1px solid var(--primary); padding:8px; border-radius:8px;'>Mulai Menabung</a>
                        </div>";
                    }
                } else { echo "<p style='grid-column:1/-1; text-align:center; padding:20px;'>Gadget tidak ditemukan. Coba naikkan budget atau ganti kategori.</p>"; }
            }
            ?>
        </div>
    </div>
</body>
</html>