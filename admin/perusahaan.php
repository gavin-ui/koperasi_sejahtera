<?php
include "../koneksi.php";

/* ================= TAMBAH ================= */
if (isset($_POST['tambah'])) {
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO perusahaan 
        (username, password, nama_perusahaan, alamat_perusahaan, pic, kontak)
        VALUES (?,?,?,?,?,?)");

    $stmt->execute([
        $_POST['username'],
        $passwordHash,
        $_POST['nama_perusahaan'],
        $_POST['alamat_perusahaan'],
        $_POST['pic'],
        $_POST['kontak']
    ]);

    header("Location: perusahaan.php");
    exit;
}

/* ================= EDIT ================= */
if (isset($_POST['edit'])) {
    if (!empty($_POST['password'])) {
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE perusahaan SET
            username=?,
            password=?,
            nama_perusahaan=?,
            alamat_perusahaan=?,
            pic=?,
            kontak=?
            WHERE id_perusahaan=?");

        $stmt->execute([
            $_POST['username'],
            $passwordHash,
            $_POST['nama_perusahaan'],
            $_POST['alamat_perusahaan'],
            $_POST['pic'],
            $_POST['kontak'],
            $_POST['id']
        ]);
    } else {
        $stmt = $pdo->prepare("UPDATE perusahaan SET
            username=?,
            nama_perusahaan=?,
            alamat_perusahaan=?,
            pic=?,
            kontak=?
            WHERE id_perusahaan=?");

        $stmt->execute([
            $_POST['username'],
            $_POST['nama_perusahaan'],
            $_POST['alamat_perusahaan'],
            $_POST['pic'],
            $_POST['kontak'],
            $_POST['id']
        ]);
    }
    header("Location: perusahaan.php");
    exit;
}

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {
    $pdo->prepare("DELETE FROM perusahaan WHERE id_perusahaan=?")
        ->execute([$_GET['hapus']]);
    header("Location: perusahaan.php");
    exit;
}

/* ================= PAGINATION & SEARCH ================= */
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page < 1 ? 1 : $page;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search != '') {
    // search di semua kolom
    $stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM perusahaan 
        WHERE username LIKE :s
        OR nama_perusahaan LIKE :s
        OR alamat_perusahaan LIKE :s
        OR pic LIKE :s
        OR kontak LIKE :s");
    $stmtTotal->execute([':s' => "%$search%"]);
    $totalData = $stmtTotal->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM perusahaan
        WHERE username LIKE :s
        OR nama_perusahaan LIKE :s
        OR alamat_perusahaan LIKE :s
        OR pic LIKE :s
        OR kontak LIKE :s
        ORDER BY id_perusahaan DESC
        LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':s', "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll();
} else {
    $totalData = $pdo->query("SELECT COUNT(*) FROM perusahaan")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM perusahaan 
        ORDER BY id_perusahaan DESC 
        LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll();
}

$totalPage = ceil($totalData / $limit);
?>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>
/* ===== CSS ASLI TIDAK DIHAPUS ===== */
body{font-family:Segoe UI;background:#f5f7fb;margin:0}
.content-wrapper{padding:30px;margin-left:260px}
h2{margin-bottom:20px}

.top{
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
}

.search{
    width:260px;
    padding:10px 14px;
    border-radius:8px;
    border:1px solid #ddd;
}

.btn{
    padding:10px 18px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
}
.btn-primary{background:#0d6efd;color:#fff}
.btn-light{background:#e9ecef}

table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
}
th,td{
    padding:14px;
    border-bottom:1px solid #eee;
    font-size:14px;
}
th{background:#f1f3f6;text-align:left}

.action a{
    margin-right:10px;
    cursor:pointer;
    text-decoration:none;
}

/* ================= RAPIN TABEL ================= */

/* tabel lebih clean */
table {
    table-layout: fixed;
}

/* header lebih tegas */
th {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* isi tabel */
td {
    font-size: 14px;
    color: #333;
    vertical-align: middle;
    word-wrap: break-word;
}

/* hover baris */
table tr:hover td {
    background: #f8fbff;
}

/* kolom NO */
th:nth-child(1),
td:nth-child(1) {
    width: 60px;
    text-align: center;
    font-weight: 600;
}

/* kolom USERNAME */
th:nth-child(2),
td:nth-child(2) {
    width: 160px;
}

/* kolom NAMA PERUSAHAAN */
th:nth-child(3),
td:nth-child(3) {
    width: 220px;
    font-weight: 600;
}

/* kolom ALAMAT */
th:nth-child(4),
td:nth-child(4) {
    width: auto;
    color: #555;
}

/* kolom PIC */
th:nth-child(5),
td:nth-child(5) {
    width: 140px;
}

/* kolom ACTION */
th:nth-child(6),
td:nth-child(6) {
    width: 110px;
    text-align: center;
}

/* action icon */
.action a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: #f1f3f6;
    transition: .2s;
}

.action a:hover {
    background: #0d6efd;
    color: #fff;
}

/* ================= CENTER TEXT TABEL ================= */
table th,
table td {
    text-align: center;
    vertical-align: middle;
}

/* action icon tetap rapi di tengah */
.action {
    text-align: center;
}

/* optional: biar alamat tetap enak dibaca walau center */
td:nth-child(4) {
    line-height: 1.5;
}

/* ================= MODAL ================= */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.4);
    align-items:center;
    justify-content:center;
    z-index:999;
}
.modal-box{
    background:#fff;
    width:700px;
    padding:25px;
    border-radius:12px;
}
.modal h3{margin-top:0}

.form-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}
.form-group{margin-bottom:15px}
input,textarea{
    width:100%;
    padding:10px 12px;
    border-radius:8px;
    border:1px solid #ddd;
}
textarea{resize:none;height:80px}

.modal-footer{
    display:flex;
    justify-content:center;
    gap:10px;
    margin-top:20px;
}

html, body {
    height: 100%;
}

body {
    display: flex;
    flex-direction: column;
}

/* pembungkus utama konten */
.main-wrapper {
    flex: 1;
    display: flex;
}

/* konten kanan (yang ada tabel) */
.content-wrapper {
    flex: 1;
    padding: 30px;
    margin-left: 260px;
}

/* footer nempel bawah */
.footer {
    background: #fff;
    border-top: 1px solid #ddd;
    padding: 12px 20px;
    text-align: center;
    font-size: 13px;
    color: #666;
}

/* ================= PAGINATION ================= */
.pagination{
    margin-top:20px;
    display:flex;
    justify-content:center;
    gap:8px;
}

.pagination a{
    padding:8px 14px;
    border-radius:8px;
    border:1px solid #ddd;
    text-decoration:none;
    color:#333;
    font-weight:600;
    background:#fff;
    transition:.2s;
}

.pagination a:hover{
    background:#0d6efd;
    color:#fff;
    border-color:#0d6efd;
}

.pagination a.active{
    background:#0d6efd;
    color:#fff;
    border-color:#0d6efd;
}

</style>

<div class="content-wrapper">

<h2>Master Perusahaan</h2>

<div class="top">
    <form method="GET" style="display:flex; gap:10px;">
        <input class="search" name="search" placeholder="Cari Perusahaan" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-primary">Cari</button>
    </form>
    <button class="btn btn-primary" onclick="openTambah()">+ Tambah</button>
</div>

<table id="table">
<tr>
    <th>NO</th>
    <th>USERNAME</th>
    <th>NAMA PERUSAHAAN</th>
    <th>ALAMAT PERUSAHAAN</th>
    <th>PIC</th>
    <th>ACTION</th>
</tr>
<tbody>
<?php foreach ($data as $i => $d): ?>
<tr>
    <td><?= ($page - 1) * $limit + $i + 1 ?></td>
    <td><?= htmlspecialchars($d['username']) ?></td>
    <td><?= htmlspecialchars($d['nama_perusahaan']) ?></td>
    <td><?= htmlspecialchars($d['alamat_perusahaan']) ?></td>
    <td><?= htmlspecialchars($d['pic']) ?></td>
    <td class="action">
        <a onclick='editData(<?= json_encode($d) ?>)'>✏</a>
        <a href="?hapus=<?= $d['id_perusahaan'] ?>" onclick="return confirm('Hapus data?')">🗑</a>
    </td>
</tr>
<?php endforeach ?>
</tbody>
</table>

<?php if ($totalPage > 1): ?>
<div class="pagination">
<?php
$searchParam = $search != '' ? "&search=" . urlencode($search) : '';
for ($i = 1; $i <= $totalPage; $i++): ?>
    <a 
        href="?page=<?= $i ?><?= $searchParam ?>"
        class="<?= $i == $page ? 'active' : '' ?>"
    >
        <?= $i ?>
    </a>
<?php endfor; ?>
</div>
<?php endif; ?>

</div>

<!-- ================= MODAL ================= -->
<div class="modal" id="modal">
<div class="modal-box">
<h3 id="title"></h3>

<form method="POST" id="form">
<input type="hidden" name="id" id="id">

<div class="form-group">
<input name="username" id="username" placeholder="Username" required>
</div>

<div class="form-group">
    <input type="password" name="password" id="password" placeholder="Password">
</div>

<div class="form-row">
<div class="form-group">
<input name="nama_perusahaan" id="nama_perusahaan" placeholder="Nama Perusahaan" required>
</div>
<div class="form-group">
<input name="kontak" id="kontak" placeholder="Kontak">
</div>
</div>

<div class="form-group">
<textarea name="alamat_perusahaan" id="alamat_perusahaan" placeholder="Alamat Perusahaan"></textarea>
</div>

<div class="form-group">
<input name="pic" id="pic" placeholder="PIC">
</div>

<div class="modal-footer">
<button class="btn btn-light" type="button" onclick="closeModal()">Batal</button>
<button class="btn btn-primary" id="submit"></button>
</div>
</form>
</div>
</div>

<script>
function openTambah(){
    modal.style.display='flex';
    title.innerText='Tambah Data';
    form.reset();
    submit.innerText='Simpan';
    submit.name='tambah';
}

function editData(d){
    modal.style.display='flex';
    title.innerText='Edit Data';
    submit.innerText='Update';
    submit.name='edit';
    id.value=d.id_perusahaan;
    username.value=d.username;
    nama_perusahaan.value=d.nama_perusahaan;
    alamat_perusahaan.value=d.alamat_perusahaan;
    pic.value=d.pic;
    kontak.value=d.kontak;
    password.value='';
}

function closeModal(){
    modal.style.display='none';
}
</script>

<?php include "partials/footer.php"; ?>
