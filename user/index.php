<?php
$title = "Beranda User";
require "../koneksi.php";
include "partials/header.php";
include "partials/navbar.php";

$daerah_id = $_SESSION['daerah_id'];
$hari_ini  = date('Y-m-d');
$kemarin   = date('Y-m-d', strtotime('-1 day'));

/* ================= AMBIL VARIETAS + HARGA ================= */
$stmt = $pdo->prepare("
    SELECT 
        v.nama_varietas,

        (SELECT h.harga 
         FROM user_varietas_harga h
         WHERE h.varietas_id = v.id 
           AND h.tanggal = ?
         LIMIT 1) AS harga_hari_ini,

        (SELECT h.harga 
         FROM user_varietas_harga h
         WHERE h.varietas_id = v.id 
           AND h.tanggal = ?
         LIMIT 1) AS harga_kemarin

    FROM user_varietas v
    WHERE v.daerah_id = ?
    ORDER BY v.nama_varietas ASC
");
$stmt->execute([$hari_ini, $kemarin, $daerah_id]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-wrapper">
    <main>

        <h2>Selamat Datang 👋</h2>
        <p>
            Halo <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>,  
            selamat datang di halaman user <b>Agro Lumintu Sejahtera</b>.
        </p>

        <!-- GRID UTAMA -->
        <div style="
            margin-top:30px;
            display:grid;
            grid-template-columns:1fr 2fr;
            gap:25px;
            align-items:flex-start;
        ">

            <!-- TABEL HARGA -->
            <div class="card">
                <h3 style="margin-top:0">Perbandingan Harga</h3>

                <table>
                    <tr>
                        <th>Varietas</th>
                        <th style="text-align:right">Kemarin</th>
                        <th style="text-align:right">Hari Ini</th>
                    </tr>

                    <?php if (count($data) === 0): ?>
                        <tr>
                            <td colspan="3">Belum ada varietas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['nama_varietas']); ?></td>
                            <td style="text-align:right">
                                <?= $d['harga_kemarin'] !== null ? 'Rp '.number_format($d['harga_kemarin'],0,',','.') : '-' ?>
                            </td>
                            <td style="text-align:right">
                                <?= $d['harga_hari_ini'] !== null ? 'Rp '.number_format($d['harga_hari_ini'],0,',','.') : '-' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>

            <!-- DIAGRAM BATANG -->
            <div class="card">
                <h3 style="margin-top:0">Grafik Harga Hari Ini</h3>
                <canvas id="chartHarga" height="120"></canvas>
            </div>

        </div>

    </main>
</div>

<?php include "partials/footer.php"; ?>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?= json_encode(array_column($data, 'nama_varietas')); ?>;
const harga  = <?= json_encode(array_map(
    fn($d) => $d['harga_hari_ini'] !== null ? (int)$d['harga_hari_ini'] : 0,
    $data
)); ?>;

const ctx = document.getElementById('chartHarga');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Harga Hari Ini (Rp)',
            data: harga,
            backgroundColor: '#5fb878'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
