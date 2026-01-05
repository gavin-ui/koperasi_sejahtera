<?php
include "partials/header.php";
include "partials/sidebar.php";
include "../koneksi.php";

/* =========================
   PAGINATION & SEARCH
========================= */
$limit = 10;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$where  = "";

if($search != ""){
  $where = "WHERE nama_gapoktan LIKE :search OR nama_ketua LIKE :search";
}

/* =========================
   TAMBAH GAPOKTAN
========================= */
if(isset($_POST['tambah'])){
  $logo = "";

  if(!empty($_FILES['logo']['name'])){
    $folder = __DIR__."/uploads/";
    if(!is_dir($folder)) mkdir($folder,0777,true);

    $logo = time().'_'.basename($_FILES['logo']['name']);
    move_uploaded_file($_FILES['logo']['tmp_name'], $folder.$logo);
  }

  $stmt = $pdo->prepare("
    INSERT INTO tb_gapoktan
    (nama_gapoktan, nama_ketua, alamat, logo, tanggal)
    VALUES (?,?,?,?,CURDATE())
  ");

  $stmt->execute([
    $_POST['nama_gapoktan'],
    $_POST['nama_ketua'],
    $_POST['alamat'],
    $logo
  ]);

  echo "<script>location='gapoktan.php';</script>";
}

/* =========================
   EDIT GAPOKTAN
========================= */
if(isset($_POST['edit'])){
  if(!empty($_FILES['logo']['name'])){
    $folder = __DIR__."/uploads/";
    if(!is_dir($folder)) mkdir($folder,0777,true);

    $logo = time().'_'.basename($_FILES['logo']['name']);
    move_uploaded_file($_FILES['logo']['tmp_name'], $folder.$logo);

    $sql = "UPDATE tb_gapoktan 
            SET nama_gapoktan=?, nama_ketua=?, alamat=?, logo=?
            WHERE id=?";
    $data = [
      $_POST['nama_gapoktan'],
      $_POST['nama_ketua'],
      $_POST['alamat'],
      $logo,
      $_POST['id']
    ];
  } else {
    $sql = "UPDATE tb_gapoktan 
            SET nama_gapoktan=?, nama_ketua=?, alamat=?
            WHERE id=?";
    $data = [
      $_POST['nama_gapoktan'],
      $_POST['nama_ketua'],
      $_POST['alamat'],
      $_POST['id']
    ];
  }

  $pdo->prepare($sql)->execute($data);
  echo "<script>location='gapoktan.php';</script>";
}

/* =========================
   HAPUS GAPOKTAN
========================= */
if(isset($_GET['hapus'])){
  $pdo->prepare("DELETE FROM tb_gapoktan WHERE id=?")->execute([$_GET['hapus']]);
  echo "<script>location='gapoktan.php';</script>";
}
?>

<!-- ========================== CONTENT ========================== -->
<div class="admin-content">
<div class="card-admin">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
      <div class="page-title">Master Gapoktan</div>
      <div class="page-subtitle">
        Kelola data gapoktan secara profesional & terstruktur
      </div>
    </div>

    <div class="d-flex gap-2">
      <form method="get" class="d-flex">
        <input type="text" name="search" value="<?= htmlspecialchars($search); ?>"
               class="form-control form-control-premium"
               placeholder="Cari gapoktan...">
      </form>

      <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
        + Tambah Gapoktan
      </button>
    </div>
  </div>

  <div class="table-responsive">
  <table class="table align-middle">
    <thead>
      <tr>
        <th>No</th>
        <th>Logo</th>
        <th>Nama Gapoktan</th>
        <th>Nama Ketua</th>
        <th>Tanggal</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>

<?php
$sql = "SELECT * FROM tb_gapoktan $where ORDER BY id DESC LIMIT $start,$limit";
$stmt = $pdo->prepare($sql);

if($search != ""){
  $stmt->bindValue(':search', "%$search%");
}

$stmt->execute();

$no = $start + 1;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
?>
<tr>
  <td><?= $no++; ?></td>
  <td>
    <?php if($row['logo']!=""){ ?>
      <img src="uploads/<?= $row['logo']; ?>" class="logo-thumb">
    <?php } else { ?>
      <span class="text-muted">-</span>
    <?php } ?>
  </td>
  <td><b><?= $row['nama_gapoktan']; ?></b></td>
  <td><?= $row['nama_ketua']; ?></td>
  <td><?= date("d M Y",strtotime($row['tanggal'])); ?></td>
  <td class="text-center">
    <button class="action-btn action-edit"
      data-bs-toggle="modal"
      data-bs-target="#edit<?= $row['id']; ?>">
      <i class="bi bi-pencil-square"></i>
    </button>
    <a href="?hapus=<?= $row['id']; ?>"
       onclick="return confirm('Hapus data ini?')"
       class="action-btn action-delete">
      <i class="bi bi-trash"></i>
    </a>
  </td>
</tr>

<!-- ================= MODAL EDIT ================= -->
<div class="modal fade" id="edit<?= $row['id']; ?>">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content modal-premium">
<form method="post" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $row['id']; ?>">
<input type="hidden" name="edit">

<div class="modal-header modal-header-premium">
  <h5>Edit Gapoktan</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body row g-4 p-4">
  <div class="col-md-6">
    <label>Nama Gapoktan</label>
    <input class="form-control form-control-premium" name="nama_gapoktan"
           value="<?= $row['nama_gapoktan']; ?>" required>
  </div>

  <div class="col-md-6">
    <label>Nama Ketua</label>
    <input class="form-control form-control-premium" name="nama_ketua"
           value="<?= $row['nama_ketua']; ?>" required>
  </div>

  <div class="col-12">
    <label>Alamat</label>
    <input class="form-control form-control-premium" name="alamat"
           value="<?= $row['alamat']; ?>">
  </div>

  <div class="col-12">
    <label>Upload Logo</label>
    <input type="file" class="form-control form-control-premium" name="logo">
  </div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
  <button class="btn btn-success">Simpan</button>
</div>

</form>
</div>
</div>
</div>

<?php } ?>
</tbody>
</table>
</div>

<?php
$totalData = $pdo->prepare("SELECT COUNT(*) FROM tb_gapoktan $where");
if($search!="") $totalData->bindValue(':search', "%$search%");
$totalData->execute();

$totalPage = ceil($totalData->fetchColumn() / $limit);
?>

<!-- ================= PAGINATION ================= -->
<nav class="mt-4">
<ul class="pagination justify-content-center">
<?php for($i=1;$i<=$totalPage;$i++){ ?>
<li class="page-item <?= $i==$page?'active':''; ?>">
<a class="page-link"
   href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>">
   <?= $i; ?>
</a>
</li>
<?php } ?>
</ul>
</nav>

</div>
</div>

<!-- ================= MODAL TAMBAH ================= -->
<div class="modal fade" id="modalTambah">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content modal-premium">
<form method="post" enctype="multipart/form-data">

<input type="hidden" name="tambah">

<div class="modal-header modal-header-premium">
  <h5>Tambah Gapoktan</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body row g-4 p-4">
  <div class="col-md-6">
    <label>Nama Gapoktan</label>
    <input class="form-control form-control-premium" name="nama_gapoktan" required>
  </div>

  <div class="col-md-6">
    <label>Nama Ketua</label>
    <input class="form-control form-control-premium" name="nama_ketua" required>
  </div>

  <div class="col-12">
    <label>Alamat</label>
    <input class="form-control form-control-premium" name="alamat">
  </div>

  <div class="col-12">
    <label>Upload Logo</label>
    <input type="file" class="form-control form-control-premium" name="logo">
  </div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
  <button class="btn btn-primary">Simpan</button>
</div>

</form>
</div>
</div>
</div>

<?php include "partials/footer.php"; ?>
<style>
  /* ===============================
   GAPOKTAN PREMIUM UI
================================ */

/* ===== LAYOUT ===== */
.admin-content{
  margin-left:260px;
  padding:100px 30px 40px;
  background:linear-gradient(180deg,#f4f7fb,#eef2f7);
  min-height:100vh;
}

@media(max-width:991px){
  .admin-content{
    margin-left:0;
    padding:90px 16px 30px;
  }
}

/* ===== CARD ===== */
.card-admin{
  background:#ffffff;
  border-radius:22px;
  padding:30px;
  box-shadow:
    0 10px 30px rgba(15,23,42,.08),
    inset 0 1px 0 rgba(255,255,255,.6);
}

/* ===== PAGE HEADER ===== */
.page-title{
  font-size:28px;
  font-weight:800;
  color:#0f172a;
  letter-spacing:.3px;
}

.page-subtitle{
  font-size:14px;
  color:#64748b;
}

/* ===== SEARCH INPUT ===== */
.form-control-premium{
  border-radius:14px;
  padding:12px 14px;
  border:1px solid #e2e8f0;
  min-width:240px;
  transition:.3s;
}

.form-control-premium:focus{
  border-color:#22c55e;
  box-shadow:0 0 0 4px rgba(34,197,94,.2);
}

/* ===== BUTTON ADD ===== */
.btn-add{
  background:linear-gradient(135deg,#16a34a,#22c55e);
  color:#fff;
  border:none;
  border-radius:999px;
  padding:10px 26px;
  font-weight:700;
  letter-spacing:.3px;
  box-shadow:0 8px 20px rgba(34,197,94,.35);
  transition:.3s;
}

.btn-add:hover{
  transform:translateY(-1px);
  box-shadow:0 12px 30px rgba(34,197,94,.45);
}

/* ===== TABLE ===== */
.table{
  border-collapse:separate;
  border-spacing:0 12px;
}

.table thead th{
  font-size:12px;
  text-transform:uppercase;
  letter-spacing:1px;
  color:#64748b;
  border:none;
}

.table tbody tr{
  background:#ffffff;
  border-radius:16px;
  box-shadow:0 8px 25px rgba(15,23,42,.06);
  transition:.3s;
}

.table tbody tr:hover{
  transform:translateY(-2px);
  box-shadow:0 12px 35px rgba(15,23,42,.12);
}

.table tbody td{
  border:none;
  padding:14px 16px;
  vertical-align:middle;
}

/* ===== LOGO THUMB ===== */
.logo-thumb{
  width:42px;
  height:42px;
  object-fit:cover;
  border-radius:12px;
  box-shadow:0 4px 10px rgba(0,0,0,.15);
}

/* ===== ACTION BUTTON ===== */
.action-btn{
  border:none;
  background:#f1f5f9;
  width:38px;
  height:38px;
  border-radius:12px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  font-size:16px;
  transition:.3s;
}

.action-edit{
  color:#2563eb;
}

.action-edit:hover{
  background:#dbeafe;
}

.action-delete{
  color:#dc2626;
}

.action-delete:hover{
  background:#fee2e2;
}

/* ===== MODAL PREMIUM ===== */
.modal-premium{
  border-radius:22px;
  overflow:hidden;
  box-shadow:0 25px 60px rgba(15,23,42,.3);
}

.modal-header-premium{
  background:linear-gradient(135deg,#16a34a,#22c55e);
  color:#fff;
  padding:18px 26px;
}

.modal-header-premium h5{
  font-weight:800;
  letter-spacing:.5px;
}

.modal-body label{
  font-size:13px;
  font-weight:600;
  color:#334155;
  margin-bottom:6px;
}

/* ===== MODAL FOOTER ===== */
.modal-footer{
  padding:16px 26px;
}

/* ===== PAGINATION ===== */
.pagination .page-item .page-link{
  border:none;
  margin:0 4px;
  border-radius:12px;
  padding:8px 14px;
  color:#475569;
  font-weight:600;
}

.pagination .page-item.active .page-link{
  background:linear-gradient(135deg,#16a34a,#22c55e);
  color:#fff;
  box-shadow:0 6px 18px rgba(34,197,94,.45);
}

/* ===== MOBILE ===== */
@media(max-width:576px){
  .modal-dialog{
    margin:10px;
  }

  .form-control-premium{
    min-width:100%;
  }

  .logo-thumb{
    width:36px;
    height:36px;
  }
}
</style>