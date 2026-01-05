<?php 
include "partials/header.php"; 
include "partials/sidebar.php"; 
include "../koneksi.php"; 

/* ==========================
   PAGINATION & SEARCH
=========================== */
$limit = 10;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = ($page < 1) ? 1 : $page;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where  = "";
$params = [];

if($search != ""){
  $where = "WHERE nama LIKE :search 
            OR username LIKE :search 
            OR email LIKE :search";
  $params[':search'] = "%$search%";
}

/* total data */
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM tb_user $where");
$totalStmt->execute($params);
$totalData = $totalStmt->fetchColumn();
$totalPage = ceil($totalData / $limit);

/* data per halaman */
$sql = "SELECT * FROM tb_user $where 
        ORDER BY id DESC 
        LIMIT $start,$limit";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

/* ==========================
   HANDLE TAMBAH USER
=========================== */
if(isset($_POST['tambah'])){
  $pdo->prepare(
    "INSERT INTO tb_user 
    (nama, alamat, email, username, password, jabatan, hak_akses, keterangan, tanggal)
    VALUES (?,?,?,?,?,?,?,?,CURDATE())"
  )->execute([
    $_POST['nama'],
    $_POST['alamat'],
    $_POST['email'],
    $_POST['username'],
    password_hash($_POST['password'], PASSWORD_DEFAULT),
    $_POST['jabatan'],
    $_POST['hak_akses'],
    $_POST['keterangan']
  ]);
  echo "<script>location='user.php';</script>";
}

/* ==========================
   HANDLE EDIT USER
=========================== */
if(isset($_POST['edit'])){
  if($_POST['password']==""){
    $sql="UPDATE tb_user SET 
          nama=?, alamat=?, email=?, username=?, jabatan=?, hak_akses=?, keterangan=? 
          WHERE id=?";
    $data=[
      $_POST['nama'],$_POST['alamat'],$_POST['email'],$_POST['username'],
      $_POST['jabatan'],$_POST['hak_akses'],$_POST['keterangan'],$_POST['id']
    ];
  }else{
    $sql="UPDATE tb_user SET 
          nama=?, alamat=?, email=?, username=?, password=?, jabatan=?, hak_akses=?, keterangan=? 
          WHERE id=?";
    $data=[
      $_POST['nama'],$_POST['alamat'],$_POST['email'],$_POST['username'],
      password_hash($_POST['password'],PASSWORD_DEFAULT),
      $_POST['jabatan'],$_POST['hak_akses'],$_POST['keterangan'],$_POST['id']
    ];
  }
  $pdo->prepare($sql)->execute($data);
  echo "<script>location='user.php';</script>";
}

/* ==========================
   HANDLE HAPUS
=========================== */
if(isset($_GET['hapus'])){
  $pdo->prepare("DELETE FROM tb_user WHERE id=?")->execute([$_GET['hapus']]);
  echo "<script>location='user.php';</script>";
}
?>

<!-- ========================== CONTENT ========================== -->
<div class="admin-content">
<div class="card-admin">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <div class="page-title">Manajemen User</div>
    <div class="page-subtitle">Kelola akun pengguna sistem dengan mudah & elegan</div>
  </div>
  <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-circle me-1"></i> Tambah User
  </button>
</div>

<!-- SEARCH -->
<form method="get" class="row g-2 mb-4">
  <div class="col-md-4 col-8">
    <input class="form-control form-control-premium" 
           name="search" 
           value="<?= htmlspecialchars($search); ?>"
           placeholder="Cari nama / username / email">
  </div>
  <div class="col-md-2 col-4">
    <button class="btn btn-add w-100">Search</button>
  </div>
</form>

<div class="table-responsive">
<table class="table align-middle">
<thead>
<tr>
<th>No</th>
<th>Nama</th>
<th>Username</th>
<th>Email</th>
<th>Hak Akses</th>
<th>Tanggal</th>
<th class="text-center">Aksi</th>
</tr>
</thead>
<tbody>

<?php 
$no = $start + 1;
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
?>
<tr>
<td><?= $no++; ?></td>
<td><b><?= $row['nama']; ?></b></td>
<td><?= $row['username']; ?></td>
<td><?= $row['email']; ?></td>
<td>
<span class="badge-access badge-<?= $row['hak_akses']; ?>">
<?= strtoupper($row['hak_akses']); ?>
</span>
</td>
<td><?= date("d M Y",strtotime($row['tanggal'])); ?></td>
<td class="text-center">
<button class="action-btn action-edit" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id']; ?>">
<i class="bi bi-pencil-square"></i>
</button>
<a href="?hapus=<?= $row['id']; ?>" onclick="return confirm('Hapus user ini?')" class="action-btn action-delete">
<i class="bi bi-trash"></i>
</a>
</td>
</tr>

<!-- MODAL EDIT -->
<div class="modal fade" id="edit<?= $row['id']; ?>">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content modal-premium">
<form method="post">
<input type="hidden" name="id" value="<?= $row['id']; ?>">
<input type="hidden" name="edit">

<div class="modal-header modal-header-premium">
<h5><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body modal-body-premium row g-4">
<div class="col-md-6"><label>Nama</label><input class="form-control form-control-premium" name="nama" value="<?= $row['nama']; ?>"></div>
<div class="col-md-6"><label>Alamat</label><input class="form-control form-control-premium" name="alamat" value="<?= $row['alamat']; ?>"></div>
<div class="col-md-6"><label>Email</label><input class="form-control form-control-premium" name="email" value="<?= $row['email']; ?>"></div>
<div class="col-md-6"><label>Username</label><input class="form-control form-control-premium" name="username" value="<?= $row['username']; ?>"></div>
<div class="col-md-6"><label>Password (opsional)</label><input type="password" class="form-control form-control-premium" name="password"></div>

<div class="col-md-6">
<label>Hak Akses</label>
<select name="hak_akses" class="form-control form-control-premium">
<option <?= $row['hak_akses']=="admin"?'selected':''; ?>>admin</option>
<option <?= $row['hak_akses']=="TL"?'selected':''; ?>>TL</option>
<option <?= $row['hak_akses']=="HO"?'selected':''; ?>>HO</option>
</select>
</div>

<div class="col-12"><label>Keterangan</label>
<input class="form-control form-control-premium" name="keterangan" value="<?= $row['keterangan']; ?>"></div>
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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

<!-- PAGINATION -->
<div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
<small class="text-muted">
Menampilkan <?= min($totalData,$start+1); ?>–<?= min($totalData,$start+$limit); ?> dari <?= $totalData; ?> data
</small>

<ul class="pagination pagination-premium">
<?php if($page>1){ ?>
<li class="page-item"><a class="page-link" href="?page=<?= $page-1; ?>&search=<?= $search; ?>">‹</a></li>
<?php } ?>

<?php for($i=1;$i<=$totalPage;$i++){ ?>
<li class="page-item <?= $i==$page?'active':''; ?>">
<a class="page-link" href="?page=<?= $i; ?>&search=<?= $search; ?>"><?= $i; ?></a>
</li>
<?php } ?>

<?php if($page<$totalPage){ ?>
<li class="page-item"><a class="page-link" href="?page=<?= $page+1; ?>&search=<?= $search; ?>">›</a></li>
<?php } ?>
</ul>
</div>

</div>
</div>

<!-- ================= MODAL TAMBAH USER ================= -->
<div class="modal fade" id="modalTambah">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content modal-premium">
<form method="post">

<input type="hidden" name="tambah">

<div class="modal-header modal-header-premium">
  <h5>
    <i class="bi bi-person-plus-fill me-2"></i>
    Tambah User Baru
  </h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body modal-body-premium row g-4">

  <div class="col-md-6">
    <label>Nama Lengkap</label>
    <input type="text" class="form-control form-control-premium" name="nama" required>
  </div>

  <div class="col-md-6">
    <label>Alamat</label>
    <input type="text" class="form-control form-control-premium" name="alamat">
  </div>

  <div class="col-md-6">
    <label>Email</label>
    <input type="email" class="form-control form-control-premium" name="email" required>
  </div>

  <div class="col-md-6">
    <label>Username</label>
    <input type="text" class="form-control form-control-premium" name="username" required>
  </div>

  <div class="col-md-6">
    <label>Password</label>
    <input type="password" class="form-control form-control-premium" name="password" required>
  </div>

  <div class="col-md-6">
    <label>Jabatan</label>
    <select name="jabatan" class="form-control form-control-premium" required>
      <option value="">-- Pilih Jabatan --</option>
      <option>kepala unit penyerapan</option>
      <option>petugas quality control</option>
      <option>keuangan</option>
    </select>
  </div>

  <div class="col-md-6">
    <label>Hak Akses</label>
    <select name="hak_akses" class="form-control form-control-premium" required>
      <option value="">-- Pilih Hak Akses --</option>
      <option>admin</option>
      <option>TL</option>
      <option>HO</option>
    </select>
  </div>

  <div class="col-12">
    <label>Keterangan</label>
    <textarea class="form-control form-control-premium" 
              name="keterangan" rows="2"
              placeholder="Opsional"></textarea>
  </div>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
    <i class="bi bi-x-circle me-1"></i> Batal
  </button>
  <button class="btn btn-success px-4">
    <i class="bi bi-save me-1"></i> Simpan
  </button>
</div>

</form>
</div>
</div>
</div>


<?php include "partials/footer.php"; ?>

<style>
/* ===============================
   LAYOUT CONTENT
================================ */
.admin-content{
  margin-left:260px;
  padding:100px 30px 40px;
  background:linear-gradient(180deg,#f4f7fb,#eef2f7);
  min-height:100vh;
}

/* MOBILE FIX */
@media(max-width:991px){
  .admin-content{
    margin-left:0;
    padding:90px 16px 30px;
  }
}

/* ===============================
   CARD
================================ */
.card-admin{
  background:#ffffff;
  border-radius:22px;
  padding:30px;
  box-shadow:
    0 10px 30px rgba(15,23,42,.08),
    inset 0 1px 0 rgba(255,255,255,.6);
}

/* ===============================
   PAGE HEADER
================================ */
.page-title{
  font-size:28px;
  font-weight:800;
  color:#0f172a;
  letter-spacing:.3px;
}

.page-subtitle{
  font-size:14px;
  color:#64748b;
  margin-top:2px;
}

/* ===============================
   BUTTON
================================ */
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

/* ===============================
   TABLE PREMIUM
================================ */
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
}

.table tbody td{
  border:none;
  padding:14px 16px;
  vertical-align:middle;
}

.table tbody tr:hover{
  box-shadow:0 12px 35px rgba(15,23,42,.1);
}

/* TABLE SCROLL MOBILE */
@media(max-width:768px){
  .table-responsive{
    border-radius:16px;
    overflow:auto;
  }
}

/* ===============================
   BADGE AKSES
================================ */
.badge-access{
  padding:6px 16px;
  border-radius:999px;
  font-size:11px;
  font-weight:700;
  letter-spacing:.5px;
}

.badge-admin{
  background:linear-gradient(135deg,#22c55e,#16a34a);
  color:#fff;
}

.badge-TL{
  background:linear-gradient(135deg,#f59e0b,#f97316);
  color:#fff;
}

.badge-HO{
  background:linear-gradient(135deg,#6366f1,#4f46e5);
  color:#fff;
}

/* ===============================
   ACTION BUTTON
================================ */
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

/* ===============================
   MODAL PREMIUM
================================ */
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

.form-control-premium{
  border-radius:14px;
  padding:12px 14px;
  border:1px solid #e2e8f0;
  transition:.3s;
}

.form-control-premium:focus{
  border-color:#22c55e;
  box-shadow:0 0 0 4px rgba(34,197,94,.2);
}

/* ===============================
   MODAL MOBILE
================================ */
@media(max-width:576px){
  .modal-dialog{
    margin:10px;
  }
  .modal-body{
    padding:20px;
  }
}
</style>
