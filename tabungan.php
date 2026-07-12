<?php
require 'koneksi.php';

// Logic PHP (Simpan, Reset, Buat Target)
if (isset($_POST['tabung'])) {
    $goal_id = $_POST['goal_id'];
    $nom = (int)str_replace('.', '', $_POST['nominal']);
    if ($nom > 0) {
        if(mysqli_query($conn, "INSERT INTO savings_entries (goal_id, amount) VALUES ('$goal_id', '$nom')")){
            header("Location: tabungan.php"); exit();
        }
    }
}
if (isset($_POST['reset'])) {
    $goal_id = $_POST['goal_id'];
    if(mysqli_query($conn, "DELETE FROM savings_goals WHERE id='$goal_id'")) {
        header("Location: tabungan.php"); exit();
    }
}
if (isset($_POST['buat_target'])) {
    $user_id = $_SESSION['user_id']; $gid = $_POST['gadget_id']; $m = $_POST['months'];
    mysqli_query($conn, "INSERT INTO savings_goals (user_id, gadget_id, target_months) VALUES ('$user_id', '$gid', '$m')");
    header("Location: tabungan.php"); exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>TabunganKu | TabungGadget</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* --- CSS FIX DROPDOWN (PASTI JALAN) --- */
        select, input {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }
        select option {
            background-color: #0f172a !important;
            color: white !important;
            padding: 10px;
        }
        ::placeholder { color: rgba(255, 255, 255, 0.5) !important; }

        /* Style Progress Bar */
        .progress-bar { height: 25px; background: #334155; border-radius: 15px; overflow: hidden; margin: 15px 0; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #4da6ff, #7f5af0); width: 0%; transition: width 1s ease; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold; color: white; }
        .history-list { list-style: none; padding: 0; max-height: 200px; overflow-y: auto; }
        .history-item { background: rgba(255, 255, 255, 0.05); padding: 10px; margin-bottom: 5px; border-radius: 8px; display: flex; justify-content: space-between; font-size: 0.9rem; }
    </style>
</head>

<body>
    <nav>
        <h1>Tabung<span>Gadget</span></h1>
        <div class="menu">
            <a href="index.php">Beranda</a>
            <a href="tabungan.php" class="active">Tabungan</a>
            <a href="worthit.php">Worth It</a>
            <a href="finder.php">Cari Gadget</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" style="color:#f87171;">Logout</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <section class="card" style="text-align:center;">
                <h2>🔒 Fitur Member</h2>
                <p>Silakan login untuk mencatat tabungan harian Anda.</p>
                <a href="login.php"><button style="width:auto; padding:10px 40px;">Login</button></a>
            </section>
        <?php else:
            $user_id = $_SESSION['user_id'];
            $q_goal = mysqli_query($conn, "SELECT sg.*, g.name, g.price FROM savings_goals sg JOIN gadgets g ON sg.gadget_id = g.id WHERE sg.user_id = '$user_id' AND sg.status = 'active'");
            $goal = mysqli_fetch_assoc($q_goal);
        ?>

            <?php if (!$goal): ?>
                <section class="card">
                    <h2>🎯 Pilih Gadget Impian</h2>
                    <form method="POST">
                        <label>1. Jenis Gadget:</label>
                        <select id="typeSelect" onchange="updateFilters()" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Laptop">Laptop</option>
                            <option value="Smartphone">Smartphone (HP)</option>
                            <option value="Tablet">Tablet</option>
                        </select>

                        <label style="margin-top:15px; display:block;">2. Kategori:</label>
                        <select id="catSelect" onchange="updateFilters()" required>
                            <option value="">-- Pilih Kebutuhan --</option>
                            <option value="Gaming">Gaming</option>
                            <option value="Office">Office / Kantoran</option>
                            <option value="Editing">Editing / Desain</option>
                            <option value="Belajar">Belajar / Sekolah</option>
                        </select>

                        <label style="margin-top:15px; display:block;">3. Pilih Model Gadget:</label>
                        <select name="gadget_id" id="gadgetSelect" disabled required></select>

                        <label style="margin-top:15px; display:block;">4. Target Waktu (Bulan):</label>
                        <input type="number" name="months" placeholder="Contoh: 12" required>

                        <button type="submit" name="buat_target" style="margin-top:20px;">Mulai Menabung Sekarang</button>
                    </form>

                    <?php
                    $res = mysqli_query($conn, "SELECT id, name, price, gadget_type, category FROM gadgets");
                    $list = []; while ($r = mysqli_fetch_assoc($res)) { $list[] = $r; }
                    ?>
                    <script>
                        const allGadgets = <?php echo json_encode($list); ?>;
                        function updateFilters() {
                            const type = document.getElementById('typeSelect').value;
                            const cat = document.getElementById('catSelect').value;
                            const model = document.getElementById('gadgetSelect');
                            model.innerHTML = '<option value="">-- Pilih Model --</option>';
                            const filtered = allGadgets.filter(g => g.gadget_type === type && g.category === cat);
                            if (filtered.length > 0) {
                                model.disabled = false;
                                filtered.forEach(g => {
                                    let opt = document.createElement('option');
                                    opt.value = g.id;
                                    opt.text = g.name + " (Rp " + parseInt(g.price).toLocaleString('id-ID') + ")";
                                    model.add(opt);
                                });
                            } else { model.disabled = true; }
                        }
                    </script>
                </section>

            <?php else:
                $goal_id = $goal['id'];
                $target_harga = $goal['price'];
                $q_tabungan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM savings_entries WHERE goal_id='$goal_id'"));
                $terkumpul = $q_tabungan['total'] ?? 0;
                $kurang = max(0, $target_harga - $terkumpul);
                $persen = min(100, round(($terkumpul / $target_harga) * 100, 1));
            ?>
                <section class="card">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <h2 style="margin:0;"><?php echo $goal['name']; ?></h2>
                            <small>Sisa: <b>Rp <?php echo number_format($kurang, 0, ',', '.'); ?></b></small>
                        </div>
                        <div style="text-align:right;">
                            <h3 style="color:#4ade80; margin:0;">Rp <?php echo number_format($terkumpul, 0, ',', '.'); ?></h3>
                            <small>Target Rp <?php echo number_format($target_harga, 0, ',', '.'); ?></small>
                        </div>
                    </div>

                    <div class="progress-bar">
                        <div class="progress-fill" style="width:<?php echo $persen; ?>%"><?php echo $persen; ?>%</div>
                    </div>

                    <div style="background:rgba(255,255,255,0.05); padding:20px; border-radius:15px; margin-top:20px;">
                        <label>💰 Input Tabungan Hari Ini:</label>
                        <form method="POST" style="display:flex; gap:10px; margin-top:10px;">
                            <input type="hidden" name="goal_id" value="<?php echo $goal_id; ?>">
                            <input type="text" name="nominal" class="input-rupiah" placeholder="Contoh: 50.000" required>
                            <button type="submit" name="tabung" style="width:auto; margin:0;">Simpan</button>
                        </form>
                    </div>

                    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px; margin-top:30px;">
                        <div style="height:300px;"><canvas id="chartHistory"></canvas></div>
                        <div>
                            <strong>🕒 Riwayat Tabungan</strong>
                            <ul class="history-list" style="margin-top:10px;">
                                <?php
                                $q_h = mysqli_query($conn, "SELECT * FROM savings_entries WHERE goal_id='$goal_id' ORDER BY entry_date DESC");
                                while ($h = mysqli_fetch_assoc($q_h)) {
                                    echo "<li class='history-item'>
                                            <span>" . date('d/m', strtotime($h['entry_date'])) . "</span>
                                            <span style='color:#4ade80;'>+Rp " . number_format($h['amount'], 0, ',', '.') . "</span>
                                          </li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <form method="POST" onsubmit="return confirm('Hapus target?')" style="text-align:right; margin-top:20px;">
                        <input type="hidden" name="goal_id" value="<?php echo $goal_id; ?>">
                        <button type="submit" name="reset" style="background:#ef4444; width:auto; padding:8px 15px; font-size:0.8rem; border:none; color:white; border-radius:5px; cursor:pointer;">🗑️ Batalkan Target</button>
                    </form>
                </section>

                <script>
                    const ctx = document.getElementById('chartHistory').getContext('2d');
                    <?php
                    $labels = []; $data = []; $running_total = 0;
                    $q_c = mysqli_query($conn, "SELECT DATE(entry_date) as tgl, SUM(amount) as jml FROM savings_entries WHERE goal_id='$goal_id' GROUP BY tgl ORDER BY tgl ASC");
                    while ($r = mysqli_fetch_assoc($q_c)) {
                        $running_total += $r['jml'];
                        $labels[] = date('d M', strtotime($r['tgl']));
                        $data[] = $running_total;
                    }
                    ?>
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: <?php echo json_encode($labels); ?>,
                            datasets: [{
                                label: 'Total Saldo',
                                data: <?php echo json_encode($data); ?>,
                                borderColor: '#4da6ff',
                                backgroundColor: 'rgba(77, 166, 255, 0.1)',
                                fill: true, tension: 0.3
                            }]
                        }
                    });
                </script>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        document.querySelectorAll('.input-rupiah').forEach(input => {
            input.addEventListener('keyup', function(e) {
                let val = this.value.replace(/[^0-9]/g, '');
                this.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            });
        });
    </script>
</body>
</html>