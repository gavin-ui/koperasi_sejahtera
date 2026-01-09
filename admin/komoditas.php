<?php
include "../koneksi.php";

/* =========================
   TAMBAH KOMODITAS
========================= */
if(isset($_POST['tambah'])){
  $stmt = $pdo->prepare("
    INSERT INTO tb_komoditas (nama_komoditas, kelompok, satuan)
    VALUES (?,?,?)
  ");
  $stmt->execute([
    $_POST['nama_komoditas'],
    $_POST['kelompok'],
    $_POST['satuan']
  ]);

  header("Location: komoditas.php");
  exit;
}

/* =========================
   EDIT KOMODITAS
========================= */
if(isset($_POST['edit'])){
  $stmt = $pdo->prepare("
    UPDATE tb_komoditas 
    SET nama_komoditas=?, kelompok=?, satuan=?
    WHERE id=?
  ");
  $stmt->execute([
    $_POST['nama_komoditas'],
    $_POST['kelompok'],
    $_POST['satuan'],
    $_POST['id']
  ]);

  header("Location: komoditas.php");
  exit;
}

/* =========================
   HAPUS KOMODITAS
========================= */
if(isset($_GET['hapus'])){
  $pdo->prepare("DELETE FROM tb_komoditas WHERE id=?")
      ->execute([$_GET['hapus']]);

  header("Location: komoditas.php");
  exit;
}
?>

<?php
/* =========================
   BARU INCLUDE VIEW
========================= */
include "partials/header.php";
include "partials/sidebar.php";

/* =========================
   EDIT KOMODITAS
========================= */
if(isset($_POST['edit'])){
  $stmt = $pdo->prepare("
    UPDATE tb_komoditas SET
      nama_komoditas=?,
      kelompok=?,
      satuan=?
    WHERE id=?
  ");
  $stmt->execute([
    $_POST['nama_komoditas'],
    $_POST['kelompok'],
    $_POST['satuan'],
    $_POST['id']
  ]);
  header("Location: komoditas.php");
  exit;
}

/* =========================
   HAPUS KOMODITAS
========================= */
if(isset($_GET['hapus'])){
  $pdo->prepare("DELETE FROM tb_komoditas WHERE id=?")->execute([$_GET['hapus']]);
  header("Location: komoditas.php");
  exit;
}

/* =========================
   PAGINATION + FILTER
========================= */
$limit = 10;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = $page < 1 ? 1 : $page;
$offset = ($page - 1) * $limit;

$filter_kelompok = $_GET['kelompok'] ?? '';

$where  = '';
$params = [];

if($filter_kelompok != ''){
  $where = "WHERE kelompok = :kelompok";
  $params[':kelompok'] = $filter_kelompok;
}

/* total data */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_komoditas $where");
$stmt->execute($params);
$totalData = $stmt->fetchColumn();
$totalPage = ceil($totalData / $limit);

/* data */
$stmt = $pdo->prepare("
  SELECT * FROM tb_komoditas
  $where
  ORDER BY id DESC
  LIMIT :limit OFFSET :offset
");

foreach($params as $k => $v){
  $stmt->bindValue($k, $v);
}

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll();
?>

<div class="admin-content">
<div class="card-admin">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <h4 class="fw-bold mb-0">Master Komoditas</h4>

  <div class="d-flex gap-2 flex-wrap">
    <!-- FILTER -->
    <form method="GET">
      <select name="kelompok" class="form-control-premium" onchange="this.form.submit()">
        <option value="">Semua Kelompok</option>
        <?php
        $kelompok = ['Perikanan','Perkebunan','Peternakan','Pertanian'];
        foreach($kelompok as $k){
          $sel = $filter_kelompok == $k ? 'selected' : '';
          echo "<option $sel>$k</option>";
        }
        ?>
      </select>
    </form>

    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
      + Tambah
    </button>
  </div>
</div>

<!-- TABLE -->
<table class="table align-middle">
<thead>
<tr>
  <th>No</th>
  <th>Nama Komoditas</th>
  <th>Kelompok</th>
  <th>Satuan</th>
  <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<?php foreach($data as $i => $row): ?>
<tr>
  <td><?= ($page - 1) * $limit + $i + 1 ?></td>
  <td><?= $row['nama_komoditas']; ?></td>
  <td><?= $row['kelompok']; ?></td>
  <td><?= $row['satuan']; ?></td>
  <td class="text-center">
    <button class="action-btn action-edit"
      data-bs-toggle="modal"
      data-bs-target="#edit<?= $row['id']; ?>">
      ✏️
    </button>
    <a href="?hapus=<?= $row['id']; ?>"
       onclick="return confirm('Hapus data ini?')"
       class="action-btn action-delete">
       🗑️
    </a>
  </td>
</tr>

<!-- MODAL EDIT -->
<div class="modal fade" id="edit<?= $row['id']; ?>">
<div class="modal-dialog modal-md">
<div class="modal-content modal-modern">

<form method="post">
<input type="hidden" name="edit">
<input type="hidden" name="id" value="<?= $row['id']; ?>">

<div class="modal-header">
<h5>Edit Komoditas</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<label>Nama Komoditas</label>
<input name="nama_komoditas" value="<?= $row['nama_komoditas']; ?>" required>

<label>Kelompok</label>
<select name="kelompok" required>
<?php foreach($kelompok as $k): ?>
<option <?= $row['kelompok']==$k?'selected':'' ?>><?= $k ?></option>
<?php endforeach ?>
</select>

<label>Satuan</label>
<input name="satuan" value="<?= $row['satuan']; ?>" required>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-primary">Simpan</button>
</div>

</form>
</div>
</div>
</div>
<?php endforeach ?>

</tbody>
</table>

<!-- PAGINATION -->
<?php if($totalPage > 1): ?>
<div class="pagination mt-4">
<?php for($i=1;$i<=$totalPage;$i++): ?>
<a class="<?= $i==$page?'active':'' ?>"
   href="?page=<?= $i ?>&kelompok=<?= urlencode($filter_kelompok) ?>">
   <?= $i ?>
</a>
<?php endfor ?>
</div>
<?php endif ?>

</div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah">
<div class="modal-dialog modal-md">
<div class="modal-content modal-modern">

<form method="post">
<input type="hidden" name="tambah">

<div class="modal-header">
<h5>Tambah Komoditas</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<label>Nama Komoditas</label>
<input name="nama_komoditas" required>

<label>Kelompok</label>
<select name="kelompok" required>
<option value="">Pilih Kelompok</option>
<?php foreach($kelompok as $k): ?>
<option><?= $k ?></option>
<?php endforeach ?>
</select>

<label>Satuan</label>
<input name="satuan" required>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-primary">Simpan</button>
</div>

</form>
</div>
</div>
</div>

<?php include "partials/footer.php"; ?>

<style>
/* ============================================
   KOMODITAS ULTRA PREMIUM UI
   MATCH GAPOKTAN / MITRA
============================================ */

/* ===== LAYOUT ===== */
.admin-content{
  margin-left:260px;
  padding:110px 36px 50px;
  background:linear-gradient(180deg,#f4f7fb,#eef2f7);
  min-height:100vh;
}

@media(max-width:991px){
  .admin-content{
    margin-left:0;
    padding:95px 16px 40px;
  }
}

/* ===== CARD ===== */
.card-admin{
  background:#ffffff;
  border-radius:28px;
  padding:34px;
  box-shadow:
    0 18px 45px rgba(15,23,42,.08),
    inset 0 1px 0 rgba(255,255,255,.7);
  position:relative;
}

/* subtle top accent */
.card-admin::before{
  content:"";
  position:absolute;
  inset:0;
  border-radius:28px;
  pointer-events:none;
  box-shadow:inset 0 2px 0 rgba(34,197,94,.25);
}

/* ===== PAGE HEADER ===== */
.page-title{
  font-size:30px;
  font-weight:900;
  color:#0f172a;
  letter-spacing:.4px;
}

.page-subtitle{
  font-size:14px;
  color:#64748b;
  margin-top:4px;
}

/* ===== TOP BAR ===== */
.top-bar{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin:30px 0 34px;
  gap:16px;
  flex-wrap:wrap;
}

/* ===== SEARCH & FILTER ===== */
.form-control-premium{
  border-radius:18px;
  padding:13px 18px;
  border:1px solid #e2e8f0;
  min-width:260px;
  font-size:14px;
  background:#f8fafc;
  transition:.35s;
}

.form-control-premium:focus{
  outline:none;
  border-color:#22c55e;
  background:#ffffff;
  box-shadow:0 0 0 4px rgba(34,197,94,.18);
}

/* ===== BUTTON ADD ===== */
.btn-add{
  background:linear-gradient(135deg,#16a34a,#22c55e);
  color:#fff;
  border:none;
  border-radius:999px;
  padding:12px 32px;
  font-weight:900;
  letter-spacing:.4px;
  box-shadow:0 14px 32px rgba(34,197,94,.45);
  transition:.35s;
}

.btn-add:hover{
  transform:translateY(-2px);
  box-shadow:0 20px 44px rgba(34,197,94,.6);
}

/* ===== TABLE ===== */
.table{
  width:100%;
  border-collapse:separate;
  border-spacing:0 16px;
}

.table thead th{
  font-size:11px;
  text-transform:uppercase;
  letter-spacing:1.2px;
  color:#64748b;
  border:none;
  padding:10px 18px;
}

.table tbody tr{
  background:#ffffff;
  border-radius:20px;
  box-shadow:0 14px 34px rgba(15,23,42,.08);
  transition:.4s;
}

.table tbody tr:hover{
  transform:translateY(-4px);
  box-shadow:0 22px 48px rgba(15,23,42,.14);
}

.table tbody td{
  border:none;
  padding:18px 20px;
  vertical-align:middle;
  font-size:14px;
  color:#0f172a;
}

/* ===== KOMODITAS THUMB ===== */
.komoditas-thumb{
  width:48px;
  height:48px;
  object-fit:cover;
  border-radius:16px;
  background:#f1f5f9;
  box-shadow:0 8px 18px rgba(0,0,0,.18);
}

/* ===== ACTION BUTTON ===== */
.action-btn{
  border:none;
  background:#f1f5f9;
  width:42px;
  height:42px;
  border-radius:16px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  font-size:16px;
  transition:.35s;
}

.action-detail{ color:#16a34a; }
.action-edit{ color:#2563eb; }
.action-delete{ color:#dc2626; }

.action-btn:hover{
  transform:translateY(-2px);
}

.action-detail:hover{ background:#dcfce7; }
.action-edit:hover{ background:#dbeafe; }
.action-delete:hover{ background:#fee2e2; }

/* ===== MODAL ===== */
.modal-modern{
  border-radius:30px;
  overflow:hidden;
  box-shadow:
    0 40px 90px rgba(15,23,42,.4),
    inset 0 1px 0 rgba(255,255,255,.6);
}

/* ===== MODAL HEADER ===== */
.modal-header{
  background:linear-gradient(135deg,#16a34a,#22c55e);
  color:#fff;
  padding:20px 30px;
}

.modal-header h5{
  font-weight:900;
  letter-spacing:.5px;
}

/* ===== MODAL BODY (LANDSCAPE) ===== */
.modal-body{
  padding:30px;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:16px 20px;
}

.modal-body label{
  font-size:13px;
  font-weight:800;
  color:#334155;
}

.modal-body label,
.modal-body textarea{
  grid-column:1 / -1;
}

/* ===== INPUT ===== */
.modal-body input,
.modal-body textarea,
.modal-body select{
  width:100%;
  border-radius:16px;
  padding:13px 16px;
  border:1px solid #e2e8f0;
  font-size:14px;
  background:#f8fafc;
}

.modal-body input:focus,
.modal-body textarea:focus,
.modal-body select:focus{
  outline:none;
  background:#ffffff;
  border-color:#22c55e;
  box-shadow:0 0 0 4px rgba(34,197,94,.18);
}

/* ===== MODAL FOOTER ===== */
.modal-footer{
  padding:18px 30px;
  background:#f8fafc;
  border-top:1px solid #e5e7eb;
}

/* ===== PAGINATION ===== */
.pagination{
  margin-top:36px;
}

.pagination .page-item .page-link{
  border:none;
  margin:0 4px;
  border-radius:16px;
  padding:9px 16px;
  color:#475569;
  font-weight:800;
  background:#f1f5f9;
}

.pagination .page-item.active .page-link{
  background:linear-gradient(135deg,#16a34a,#22c55e);
  color:#fff;
  box-shadow:0 10px 24px rgba(34,197,94,.45);
}

/* ===== MOBILE ===== */
@media(max-width:576px){
  .komoditas-thumb{
    width:40px;
    height:40px;
  }

  .modal-body{
    grid-template-columns:1fr;
  }

  .form-control-premium{
    min-width:100%;
  }
}
</style>
