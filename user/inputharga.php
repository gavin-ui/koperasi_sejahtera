<?php
$title = "Input Harga Varietas";
require "../koneksi.php";
include "partials/header.php";
include "partials/navbar.php";

/* ================= DATA USER ================= */
$daerah_id = $_SESSION['daerah_id'];
$error = "";
$success = "";

/* ================= AMBIL VARIETAS USER ================= */
$stmt = $pdo->prepare("
    SELECT id, nama_varietas
    FROM user_varietas
    WHERE daerah_id = ?
    ORDER BY nama_varietas ASC
");
$stmt->execute([$daerah_id]);
$varietas = $stmt->fetchAll();

/* ================= SIMPAN HARGA ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $varietas_id = $_POST['varietas_id'];
    $tanggal     = $_POST['tanggal'];
    $harga       = $_POST['harga'];

    if (!$varietas_id || !$tanggal || !$harga) {
        $error = "Semua field wajib diisi!";
    } else {
        // pastikan varietas milik daerah user
        $cekVar = $pdo->prepare("
            SELECT id FROM user_varietas
            WHERE id = ? AND daerah_id = ?
        ");
        $cekVar->execute([$varietas_id, $daerah_id]);

        if ($cekVar->rowCount() === 0) {
            $error = "Varietas tidak valid!";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO user_varietas_harga (varietas_id, daerah_id, tanggal, harga)
                VALUES (?, ?, ?, ?)
            ");

            try {
                $stmt->execute([$varietas_id, $daerah_id, $tanggal, $harga]);
                $success = "Harga berhasil disimpan.";
            } catch (PDOException $e) {
                $error = "Harga untuk tanggal ini sudah ada!";
            }
        }
    }
}
?>

<div class="main-wrapper">
    <main>

        <div class="card">
            <h2>Input Harga Varietas</h2>

            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>Varietas</label>
                <select name="varietas_id" required>
                    <option value="">-- Pilih Varietas --</option>
                    <?php foreach ($varietas as $v): ?>
                        <option value="<?= $v['id']; ?>">
                            <?= htmlspecialchars($v['nama_varietas']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label style="margin-top:15px">Tanggal</label>
                <input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required>

                <label style="margin-top:15px">Harga (Rp)</label>
                <input type="number" name="harga" placeholder="Masukkan Harga" required>

                <button type="submit">Simpan Harga</button>
            </form>
        </div>

    </main>
</div>

<?php include "partials/footer.php"; ?>
