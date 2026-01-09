<?php
include "../koneksi.php";

/* ================= TAMBAH ================= */
if (isset($_POST['tambah'])) {
    $stmt = $pdo->prepare("
        INSERT INTO perusahaan 
        (username, nama_perusahaan, alamat_perusahaan, pic, kontak)
        VALUES (?,?,?,?,?)
    ");

    $stmt->execute([
        $_POST['username'],
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
    $stmt = $pdo->prepare("
        UPDATE perusahaan SET
            username=?,
            nama_perusahaan=?,
            alamat_perusahaan=?,
            pic=?,
            kontak=?
        WHERE id_perusahaan=?
    ");

    $stmt->execute([
        $_POST['username'],
        $_POST['nama_perusahaan'],
        $_POST['alamat_perusahaan'],
        $_POST['pic'],
        $_POST['kontak'],
        $_POST['id']
    ]);

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
$limit  = 10;
$page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search != '') {
    $stmtTotal = $pdo->prepare("
        SELECT COUNT(*) FROM perusahaan 
        WHERE username LIKE :s
        OR nama_perusahaan LIKE :s
        OR alamat_perusahaan LIKE :s
        OR pic LIKE :s
        OR kontak LIKE :s
    ");
    $stmtTotal->execute([':s' => "%$search%"]);
    $totalData = $stmtTotal->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT * FROM perusahaan
        WHERE username LIKE :s
        OR nama_perusahaan LIKE :s
        OR alamat_perusahaan LIKE :s
        OR pic LIKE :s
        OR kontak LIKE :s
        ORDER BY id_perusahaan DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':s', "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
} else {
    $totalData = $pdo->query("SELECT COUNT(*) FROM perusahaan")->fetchColumn();
    $stmt = $pdo->prepare("
        SELECT * FROM perusahaan
        ORDER BY id_perusahaan DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
}

$data = $stmt->fetchAll();
$totalPage = ceil($totalData / $limit);
?>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<div class="content-wrapper">

<h2>Master Perusahaan</h2>

<div class="top">
    <form method="GET" style="display:flex; gap:10px;">
        <input 
            class="search" 
            name="search" 
            placeholder="Cari Perusahaan" 
            value="<?= htmlspecialchars($search) ?>"
        >
        <button class="btn btn-primary">Cari</button>
    </form>

    <button class="btn btn-primary" onclick="openTambah()">+ Tambah</button>
</div>

<table>
<thead>
<tr>
    <th>NO</th>
    <th>USERNAME</th>
    <th>NAMA PERUSAHAAN</th>
    <th>ALAMAT PERUSAHAAN</th>
    <th>PIC</th>
    <th>ACTION</th>
</tr>
</thead>
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
        <a 
            href="?hapus=<?= $d['id_perusahaan'] ?>" 
            onclick="return confirm('Hapus data ini?')"
        >🗑</a>
    </td>
</tr>
<?php endforeach ?>
</tbody>
</table>

<?php if ($totalPage > 1): ?>
<div class="pagination">
<?php
$searchParam = $search ? '&search=' . urlencode($search) : '';
for ($i = 1; $i <= $totalPage; $i++):
?>
    <a 
        href="?page=<?= $i . $searchParam ?>"
        class="<?= $i == $page ? 'active' : '' ?>"
    ><?= $i ?></a>
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
    <input name="nama_perusahaan" id="nama_perusahaan" placeholder="Nama Perusahaan" required>
</div>

<div class="form-group">
    <textarea name="alamat_perusahaan" id="alamat_perusahaan" placeholder="Alamat Perusahaan"></textarea>
</div>

<div class="form-group">
    <input name="pic" id="pic" placeholder="PIC">
</div>

<div class="form-group">
    <input name="kontak" id="kontak" placeholder="Kontak">
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" onclick="closeModal()">Batal</button>
    <button type="submit" class="btn btn-primary" id="submit"></button>
</div>

</form>
</div>
</div>

<script>
const modal  = document.getElementById('modal');
const title  = document.getElementById('title');
const submit = document.getElementById('submit');
const form   = document.getElementById('form');

function openTambah(){
    modal.style.display = 'flex';
    title.innerText = 'Tambah Perusahaan';
    form.reset();
    submit.innerText = 'Simpan';
    submit.name = 'tambah';
}

function editData(d){
    modal.style.display = 'flex';
    title.innerText = 'Edit Perusahaan';
    submit.innerText = 'Update';
    submit.name = 'edit';

    id.value = d.id_perusahaan;
    username.value = d.username;
    nama_perusahaan.value = d.nama_perusahaan;
    alamat_perusahaan.value = d.alamat_perusahaan;
    pic.value = d.pic;
    kontak.value = d.kontak;
}

function closeModal(){
    modal.style.display = 'none';
}
</script>

<?php include "partials/footer.php"; ?>

<style>
/* ===============================
   BASE
================================ */
body{
  font-family:Segoe UI,system-ui,-apple-system,sans-serif;
  background:linear-gradient(180deg,#f8fafc,#eef2f7);
  margin:0;
}

html,body{
  height:100%;
}

/* ===============================
   LAYOUT
================================ */
.main-wrapper{
  display:flex;
  flex:1;
}

.content-wrapper{
  flex:1;
  padding:90px 40px 48px;
  margin-left:260px;
}

@media(max-width:991px){
  .content-wrapper{
    margin-left:0;
    padding:80px 20px 32px;
  }
}

/* ===============================
   PAGE HEADER
================================ */
h2{
  font-size:30px;
  font-weight:900;
  color:#020617;
  margin-bottom:22px;
}

/* ===============================
   TOP BAR
================================ */
.top{
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:14px;
  flex-wrap:wrap;
  margin-bottom:24px;
}

/* ===============================
   SEARCH
================================ */
.search{
  width:280px;
  padding:13px 22px;
  border-radius:999px;
  border:1px solid #e2e8f0;
  font-size:14px;
  background:#f8fafc;
  transition:.3s;
}

.search:focus{
  outline:none;
  background:#fff;
  border-color:#22c55e;
  box-shadow:0 0 0 5px rgba(34,197,94,.18);
}

/* ===============================
   BUTTON
================================ */
.btn{
  padding:13px 28px;
  border:none;
  border-radius:999px;
  cursor:pointer;
  font-weight:800;
  letter-spacing:.3px;
  transition:.3s;
}

.btn-primary{
  background:linear-gradient(135deg,#22c55e,#16a34a);
  color:#fff;
  box-shadow:0 10px 25px rgba(34,197,94,.45);
}

.btn-primary:hover{
  transform:translateY(-2px);
}

.btn-light{
  background:#f1f5f9;
  color:#334155;
}

/* ===============================
   CARD TABLE
================================ */
table{
  width:100%;
  border-collapse:separate;
  border-spacing:0 16px;
  background:transparent;
}

th{
  font-size:12px;
  text-transform:uppercase;
  letter-spacing:1px;
  color:#64748b;
  padding:10px 16px;
  border:none;
}

td{
  font-size:14px;
  color:#020617;
  padding:18px 20px;
  border:none;
  vertical-align:middle;
  word-wrap:break-word;
}

/* ===============================
   TABLE ROW
================================ */
tbody tr{
  background:#ffffff;
  border-radius:22px;
  box-shadow:0 10px 28px rgba(15,23,42,.06);
  transition:.35s;
}

tbody tr:hover{
  transform:translateY(-4px);
  box-shadow:0 20px 48px rgba(15,23,42,.14);
}

/* ===============================
   COLUMN SIZE
================================ */
th:nth-child(1),
td:nth-child(1){
  width:60px;
  text-align:center;
  font-weight:800;
}

th:nth-child(2),
td:nth-child(2){
  width:160px;
}

th:nth-child(3),
td:nth-child(3){
  width:240px;
  font-weight:800;
}

th:nth-child(4),
td:nth-child(4){
  width:auto;
  line-height:1.6;
  color:#475569;
}

th:nth-child(5),
td:nth-child(5){
  width:160px;
}

th:nth-child(6),
td:nth-child(6){
  width:130px;
  text-align:center;
}

/* ===============================
   ACTION BUTTON
================================ */
.action a{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  width:40px;
  height:40px;
  border-radius:14px;
  background:#f1f5f9;
  font-size:16px;
  transition:.25s;
  margin:0 3px;
  color:#2563eb;
}

.action a:hover{
  background:#22c55e;
  color:#fff;
  transform:translateY(-2px);
}

/* ===============================
   MODAL PREMIUM
================================ */
.modal{
  display:none;
  position:fixed;
  inset:0;
  background:rgba(15,23,42,.45);
  backdrop-filter:blur(6px);
  align-items:center;
  justify-content:center;
  z-index:999;
}

.modal-box{
  background:#fff;
  width:100%;
  max-width:720px;
  padding:34px;
  border-radius:32px;
  box-shadow:0 40px 90px rgba(15,23,42,.35);
  animation:modalUp .35s ease;
}

@keyframes modalUp{
  from{
    opacity:0;
    transform:translateY(30px) scale(.96);
  }
  to{
    opacity:1;
    transform:translateY(0) scale(1);
  }
}

.modal h3{
  margin-top:0;
  font-weight:900;
  font-size:22px;
  color:#020617;
}

/* ===============================
   FORM
================================ */
.form-row{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:18px;
}

.form-group{
  margin-bottom:18px;
}

input,textarea{
  width:100%;
  padding:14px 18px;
  border-radius:18px;
  border:1px solid #e2e8f0;
  font-size:14px;
  background:#f8fafc;
  transition:.3s;
}

input:focus,
textarea:focus{
  outline:none;
  background:#fff;
  border-color:#22c55e;
  box-shadow:0 0 0 5px rgba(34,197,94,.18);
}

textarea{
  resize:none;
  height:90px;
}

/* ===============================
   MODAL FOOTER
================================ */
.modal-footer{
  display:flex;
  justify-content:center;
  gap:12px;
  margin-top:24px;
}

/* ===============================
   PAGINATION
================================ */
.pagination{
  margin-top:28px;
  display:flex;
  justify-content:center;
  gap:10px;
}

.pagination a{
  padding:10px 18px;
  border-radius:999px;
  border:1px solid #e2e8f0;
  text-decoration:none;
  color:#334155;
  font-weight:800;
  background:#fff;
  transition:.25s;
}

.pagination a:hover{
  background:#22c55e;
  color:#fff;
  border-color:#22c55e;
}

.pagination a.active{
  background:linear-gradient(135deg,#22c55e,#16a34a);
  color:#fff;
  border:none;
  box-shadow:0 6px 18px rgba(34,197,94,.45);
}

/* ===============================
   FOOTER
================================ */
.footer{
  background:#fff;
  border-top:1px solid #e2e8f0;
  padding:14px 20px;
  text-align:center;
  font-size:13px;
  color:#64748b;
}

</style>