<?php
require "../koneksi.php";
include "partials/header.php";
include "partials/navbar.php";

/* ================= DATA USER ================= */
$daerah_id = $_SESSION['daerah_id'];
$error = "";
$success = "";

/* ================= SIMPAN VARIETAS ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_varietas = trim($_POST['nama_varietas']);

    if ($nama_varietas === "") {
        $error = "Nama varietas tidak boleh kosong!";
    } else {
        $cek = $pdo->prepare("
            SELECT id FROM user_varietas 
            WHERE nama_varietas = ? AND daerah_id = ?
        ");
        $cek->execute([$nama_varietas, $daerah_id]);

        if ($cek->rowCount() > 0) {
            $error = "Varietas sudah ada di daerah Anda!";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO user_varietas (nama_varietas, daerah_id)
                VALUES (?, ?)
            ");

            if ($stmt->execute([$nama_varietas, $daerah_id])) {
                $success = "Varietas berhasil ditambahkan.";
            } else {
                $error = "Gagal menambahkan varietas.";
            }
        }
    }
}

/* ================= AMBIL DATA VARIETAS ================= */
$stmt = $pdo->prepare("
    SELECT nama_varietas, created_at
    FROM user_varietas
    WHERE daerah_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$daerah_id]);
$varietas = $stmt->fetchAll();
?>

<div class="main-wrapper">
    <main>

        <div class="card">
            <h2>Tambah Varietas</h2>

            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">
                <label>Nama Varietas</label>
                <input type="text" name="nama_varietas" placeholder="Masukkan Varietas" required>
                <button type="submit">Simpan Varietas</button>
            </form>
        </div>

        <div class="card">
            <h3>Daftar Varietas Anda</h3>

            <table>
                <tr>
                    <th>No</th>
                    <th>Nama Varietas</th>
                    <th>Tanggal Input</th>
                </tr>

                <?php if (count($varietas) === 0): ?>
                    <tr>
                        <td colspan="3">Belum ada varietas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($varietas as $i => $v): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($v['nama_varietas']); ?></td>
                        <td><?= date('d-m-Y', strtotime($v['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>

    </main>
</div>

<?php include "partials/footer.php"; ?>
