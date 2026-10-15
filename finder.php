<?php require 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cari gadget terbaik sesuai kategori dan budget kamu di TabungGadget.">
    <title>Cari Gadget | TabungGadget</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <a href="#main" class="skip-link">Langsung ke konten</a>
    <div id="loader"><div class="spinner"></div></div>

    <nav>
        <h1>Tabung<span>Gadget</span></h1>
        <div class="menu">
            <a href="index.php">Beranda</a>
            <a href="tabungan.php">Tabungan</a>
            <a href="worthit.php">Worth It?</a>
            <a href="finder.php" class="active">Cari Gadget</a>
        </div>
    </nav>

    <main id="main">
        <div class="container">
            <section class="card">
                <h2>Cari gadget</h2>
                <p style="margin-top: 4px;">Filter berdasarkan jenis, kategori, dan batas harga.</p>
                <form method="POST">
                    <div class="flex gap-3 mt-4" style="flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 180px;">
                            <label for="type">Jenis gadget</label>
                            <select id="type" name="type" required>
                                <option value="Laptop">Laptop</option>
                                <option value="Smartphone">Smartphone</option>
                                <option value="Tablet">Tablet</option>
                            </select>
                        </div>
                        <div style="flex: 1; min-width: 180px;">
                            <label for="category">Kategori</label>
                            <select id="category" name="category">
                                <option value="">Semua kategori</option>
                                <?php
                                $q_cat = mysqli_query($conn, "SELECT DISTINCT category FROM gadgets WHERE category IS NOT NULL ORDER BY category ASC");
                                while ($cat = mysqli_fetch_assoc($q_cat)) {
                                    echo "<option value='{$cat['category']}'>{$cat['category']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div style="flex: 1; min-width: 180px;">
                            <label for="budget">Budget maksimum (Rp)</label>
                            <input id="budget" type="number" name="budget" placeholder="Contoh: 10000000" required>
                        </div>
                    </div>
                    <button type="submit" name="cari" style="width: auto; margin-top: 20px;">Cari sekarang</button>
                </form>
            </section>

            <div class="grid-result">
                <?php
                if (isset($_POST['cari'])) {
                    $t = mysqli_real_escape_string($conn, $_POST['type']);
                    $c = mysqli_real_escape_string($conn, $_POST['category']);
                    $b = (int) $_POST['budget'];
                    $cat_query = ($c === '') ? '' : "AND category='$c'";
                    $q = mysqli_query($conn, "SELECT * FROM gadgets WHERE gadget_type='$t' $cat_query AND price <= $b ORDER BY price DESC");

                    if (mysqli_num_rows($q) > 0) {
                        while ($d = mysqli_fetch_assoc($q)) {
                            echo "
                            <div class='laptop-item'>
                                <span class='gadget-badge'>{$d['gadget_type']} &mdash; {$d['category']}</span>
                                <h4>{$d['name']}</h4>
                                <p>{$d['cpu']}</p>
                                <p>RAM {$d['ram']} GB &nbsp;&middot;&nbsp; {$d['storage']}</p>
                                <span class='price-tag'>Rp " . number_format($d['price'], 0, ',', '.') . "</span>
                                <a href='tabungan.php?pilih={$d['id']}' class='btn-tabung'>Mulai menabung untuk ini</a>
                            </div>";
                        }
                    } else {
                        echo "<p style='grid-column:1/-1; color: var(--text-secondary); padding: 20px 0;'>Tidak ada gadget yang sesuai. Coba naikkan budget atau ganti kategori.</p>";
                    }
                }
                ?>
            </div>
        </div>
    </main>

    <script src="assets/script.js"></script>
</body>
</html>
