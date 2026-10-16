<?php
require 'koneksi.php';

// Logic PHP (Simpan, Reset, Buat Target)
if (isset($_POST['tabung'])) {
    $goal_id = $_POST['goal_id'];
    $nom = (int)str_replace('.', '', $_POST['nominal']);
    if ($nom > 0) {
        if (mysqli_query($conn, "INSERT INTO savings_entries (goal_id, amount) VALUES ('$goal_id', '$nom')")) {
            header("Location: tabungan.php"); exit();
        }
    }
}
if (isset($_POST['reset'])) {
    $goal_id = $_POST['goal_id'];
    if (mysqli_query($conn, "DELETE FROM savings_goals WHERE id='$goal_id'")) {
        header("Location: tabungan.php"); exit();
    }
}
if (isset($_POST['buat_target'])) {
    $user_id = $_SESSION['user_id'];
    $gid     = $_POST['gadget_id'];
    $m       = $_POST['months'];
    mysqli_query($conn, "INSERT INTO savings_goals (user_id, gadget_id, target_months) VALUES ('$user_id', '$gid', '$m')");
    header("Location: tabungan.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lacak tabungan harian menuju gadget impianmu di TabungGadget.">
    <title>Tabunganku | TabungGadget</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <a href="#main" class="skip-link">Langsung ke konten</a>
    <div id="loader"><div class="spinner"></div></div>

    <nav>
        <h1>Tabung<span>Gadget</span></h1>
        <div class="menu">
            <a href="index.php">Beranda</a>
            <a href="tabungan.php" class="active">Tabungan</a>
            <a href="worthit.php">Worth It?</a>
            <a href="finder.php">Cari Gadget</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" style="color: var(--bad);">Keluar</a>
            <?php endif; ?>
        </div>
    </nav>

    <main id="main">
        <div class="container">

        <?php if (!isset($_SESSION['user_id'])): ?>
            <section class="card" style="text-align: center; padding: 56px 32px;">
                <h2>Fitur khusus member</h2>
                <p style="margin: 12px auto 28px; max-width: 40ch;">Masuk dulu untuk mencatat dan melacak tabungan harianmu.</p>
                <a href="login.php">
                    <button style="width: auto; margin: 0; padding: 10px 40px;">Masuk</button>
                </a>
            </section>

        <?php else:
            $user_id = $_SESSION['user_id'];
            $q_goal  = mysqli_query($conn, "SELECT sg.*, g.name, g.price FROM savings_goals sg JOIN gadgets g ON sg.gadget_id = g.id WHERE sg.user_id = '$user_id' AND sg.status = 'active'");
            $goal    = mysqli_fetch_assoc($q_goal);
        ?>

            <?php if (!$goal): ?>
                <!-- Belum ada target � form pilih gadget -->
                <section class="card">
                    <h2>Tentukan target</h2>
                    <p style="margin: 6px 0 0;">Pilih gadget yang ingin kamu tabung, lalu tentukan berapa bulan target menabungmu.</p>
                    <form method="POST">
                        <label for="typeSelect">Jenis gadget</label>
                        <select id="typeSelect" onchange="updateFilters()" required>
                            <option value="">Pilih tipe</option>
                            <option value="Laptop">Laptop</option>
                            <option value="Smartphone">Smartphone</option>
                            <option value="Tablet">Tablet</option>
                        </select>

                        <label for="catSelect" style="margin-top: 16px;">Kategori</label>
                        <select id="catSelect" onchange="updateFilters()" required>
                            <option value="">Pilih kebutuhan</option>
                            <option value="Gaming">Gaming</option>
                            <option value="Office">Office / Kantoran</option>
                            <option value="Editing">Editing / Desain</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Budget">Budget / Entry Level</option>
                            <option value="Flagship">Flagship / Premium</option>
                        </select>

                        <label for="gadgetSelect" style="margin-top: 16px;">Model gadget</label>
                        <select id="gadgetSelect" name="gadget_id" disabled required>
                            <option value="">Pilih jenis dan kategori dulu</option>
                        </select>

                        <label for="months" style="margin-top: 16px;">Target waktu (bulan)</label>
                        <select id="months" name="months" required>
                            <?php for ($i = 1; $i <= 36; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?> bulan</option>
                            <?php endfor; ?>
                        </select>

                        <button type="submit" name="buat_target" style="margin-top: 24px;">Mulai menabung</button>
                    </form>

                    <?php
                    $res  = mysqli_query($conn, "SELECT id, name, price, gadget_type, category FROM gadgets");
                    $list = [];
                    while ($r = mysqli_fetch_assoc($res)) { $list[] = $r; }
                    ?>
                    <script>
                        const allGadgets = <?php echo json_encode($list); ?>;
                        function updateFilters() {
                            const type  = document.getElementById('typeSelect').value;
                            const cat   = document.getElementById('catSelect').value;
                            const model = document.getElementById('gadgetSelect');
                            model.innerHTML = '<option value="">Pilih model</option>';
                            const filtered = allGadgets.filter(g => g.gadget_type === type && g.category === cat);
                            if (filtered.length > 0) {
                                model.disabled = false;
                                filtered.forEach(g => {
                                    const opt  = document.createElement('option');
                                    opt.value  = g.id;
                                    opt.text   = g.name + ' (Rp ' + parseInt(g.price).toLocaleString('id-ID') + ')';
                                    model.add(opt);
                                });
                            } else {
                                model.disabled = true;
                                model.innerHTML = '<option value="">Tidak ada gadget di kategori ini</option>';
                            }
                        }
                    </script>
                </section>

            <?php else:
                $goal_id      = $goal['id'];
                $target_harga = $goal['price'];
                $q_tabungan   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM savings_entries WHERE goal_id='$goal_id'"));
                $terkumpul    = $q_tabungan['total'] ?? 0;
                $kurang       = max(0, $target_harga - $terkumpul);
                $persen       = min(100, round(($terkumpul / $target_harga) * 100, 1));
            ?>
                <section class="card">

                    <!-- Header target -->
                    <div class="flex-between">
                        <div>
                            <h2 style="margin: 0;"><?= htmlspecialchars($goal['name']) ?></h2>
                            <small>Sisa <?= number_format($kurang, 0, ',', '.') ?> lagi</small>
                        </div>
                        <div class="text-right">
                            <p class="num" style="font-size: 1.25rem; font-weight: 600; color: var(--ok); margin: 0;">
                                Rp <?= number_format($terkumpul, 0, ',', '.') ?>
                            </p>
                            <small>dari Rp <?= number_format($target_harga, 0, ',', '.') ?></small>
                        </div>
                    </div>

                    <!-- Progress bar -->
                    <div class="progress-meta mt-4">
                        <small>Terkumpul</small>
                        <span class="progress-pct"><?= $persen ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $persen ?>%;"></div>
                    </div>

                    <!-- Input tabungan -->
                    <div class="inner-box mt-5">
                        <label for="nominal">Tambah tabungan hari ini</label>
                        <form method="POST" class="flex gap-3" style="margin-top: 10px;">
                            <input type="hidden" name="goal_id" value="<?= $goal_id ?>">
                            <input id="nominal" type="text" name="nominal" class="input-rupiah" placeholder="Contoh: 50.000" required>
                            <button type="submit" name="tabung" style="width: auto; margin: 0; flex-shrink: 0;">Simpan</button>
                        </form>
                    </div>

                    <!-- Grafik + Riwayat -->
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-top: 32px;">
                        <div style="height: 280px; position: relative;">
                            <canvas id="chartHistory"></canvas>
                        </div>
                        <div>
                            <p style="font-size: 0.8rem; font-weight: 600; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px;">Riwayat</p>
                            <ul class="history-list">
                                <?php
                                $q_h = mysqli_query($conn, "SELECT * FROM savings_entries WHERE goal_id='$goal_id' ORDER BY entry_date DESC");
                                while ($h = mysqli_fetch_assoc($q_h)) {
                                    echo "<li class='history-item'>
                                            <span class='date'>" . date('d/m', strtotime($h['entry_date'])) . "</span>
                                            <span class='amount'>+Rp " . number_format($h['amount'], 0, ',', '.') . "</span>
                                          </li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Tombol batalkan -->
                    <div class="text-right mt-6">
                        <button type="button"
                                class="btn-danger"
                                style="width: auto; padding: 8px 16px; font-size: 0.8rem;"
                                onclick="document.getElementById('modalBatal').classList.add('open')"
                                aria-haspopup="dialog">
                            Batalkan target
                        </button>
                    </div>
                </section>

                <!-- Modal konfirmasi -->
                <div id="modalBatal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
                    <div class="modal-box">
                        <h3 id="modalTitle">Hapus target tabungan?</h3>
                        <p>Seluruh riwayat tabungan untuk gadget ini akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.</p>
                        <div class="modal-actions">
                            <button type="button"
                                    class="btn-ghost"
                                    onclick="document.getElementById('modalBatal').classList.remove('open')">
                                Kembali
                            </button>
                            <form method="POST" style="flex: 1; max-width: 160px; margin: 0;">
                                <input type="hidden" name="goal_id" value="<?= $goal_id ?>">
                                <button type="submit" name="reset" class="btn-danger" style="margin: 0; width: 100%;">Ya, hapus</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Grafik riwayat -->
                <script>
                    const ctx = document.getElementById('chartHistory').getContext('2d');
                    <?php
                    $labels = []; $data = []; $running_total = 0;
                    $q_c = mysqli_query($conn, "SELECT DATE(entry_date) as tgl, SUM(amount) as jml FROM savings_entries WHERE goal_id='$goal_id' GROUP BY tgl ORDER BY tgl ASC");
                    while ($r = mysqli_fetch_assoc($q_c)) {
                        $running_total += $r['jml'];
                        $labels[] = date('d M', strtotime($r['tgl']));
                        $data[]   = $running_total;
                    }
                    ?>
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: <?php echo json_encode($labels); ?>,
                            datasets: [{
                                label: 'Total terkumpul',
                                data: <?php echo json_encode($data); ?>,
                                borderColor: '#e8a23a',
                                backgroundColor: 'rgba(232, 162, 58, 0.08)',
                                fill: true,
                                tension: 0.35,
                                borderWidth: 2,
                                pointRadius: 3,
                                pointBackgroundColor: '#e8a23a'
                            }, {
                                label: 'Target',
                                data: Array(<?php echo count($labels); ?>).fill(<?= $target_harga ?>),
                                borderColor: 'rgba(255,255,255,0.15)',
                                borderWidth: 1.5,
                                borderDash: [5, 5],
                                pointRadius: 0,
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { labels: { color: '#8a8480', font: { size: 11 } } },
                                tooltip: {
                                    callbacks: {
                                        label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    grid: { color: 'rgba(255,255,255,0.04)' },
                                    ticks: { color: '#5a5652', callback: val => (val / 1000000).toFixed(1) + ' Jt' },
                                    min: 0
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { color: '#5a5652' }
                                }
                            }
                        }
                    });

                    // Tutup modal saat klik overlay
                    document.getElementById('modalBatal').addEventListener('click', function(e) {
                        if (e.target === this) this.classList.remove('open');
                    });
                </script>

            <?php endif; ?>
        <?php endif; ?>

        </div>
    </main>

    <script src="../assets/script.js"></script>
</body>
</html>
