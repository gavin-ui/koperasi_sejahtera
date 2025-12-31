<?php 
include "partials/header.php"; 
include "partials/sidebar.php"; 
include "../koneksi.php"; 

/* ==========================
   HANDLE TAMBAH USER
=========================== */
if(isset($_POST['tambah'])){
  $stmt = $pdo->prepare(
    "INSERT INTO tb_user 
    (nama, alamat, email, username, password, jabatan, hak_akses, keterangan, tanggal)
    VALUES (?,?,?,?,?,?,?,?,CURDATE())"
  );

  $stmt->execute([
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
    $sql = "UPDATE tb_user SET 
            nama=?, alamat=?, email=?, username=?, jabatan=?, hak_akses=?, keterangan=?
            WHERE id=?";
    $data = [
      $_POST['nama'],
      $_POST['alamat'],
      $_POST['email'],
      $_POST['username'],
      $_POST['jabatan'],
      $_POST['hak_akses'],
      $_POST['keterangan'],
      $_POST['id']
    ];
  } else {
    $sql = "UPDATE tb_user SET 
            nama=?, alamat=?, email=?, username=?, password=?, jabatan=?, hak_akses=?, keterangan=?
            WHERE id=?";
    $data = [
      $_POST['nama'],
      $_POST['alamat'],
      $_POST['email'],
      $_POST['username'],
      password_hash($_POST['password'], PASSWORD_DEFAULT),
      $_POST['jabatan'],
      $_POST['hak_akses'],
      $_POST['keterangan'],
      $_POST['id']
    ];
  }

  $pdo->prepare($sql)->execute($data);
  echo "<script>location='user.php';</script>";
}

/* ==========================
   HANDLE HAPUS USER
=========================== */
if(isset($_GET['hapus'])){
  $pdo->prepare("DELETE FROM tb_user WHERE id=?")->execute([$_GET['hapus']]);
  echo "<script>location='user.php';</script>";
}
?>

<!-- ========================== CONTENT ========================== -->
<div class="admin-content">
  <div class="card-admin">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <div class="page-title">Manajemen User</div>
        <div class="page-subtitle">
          Kelola akun pengguna sistem dengan mudah & elegan
        </div>
      </div>

      <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
        + Tambah User
      </button>
    </div>

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
$no=1;
$stmt=$pdo->query("SELECT * FROM tb_user ORDER BY id DESC");
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
    <button class="action-btn action-edit"
      data-bs-toggle="modal"
      data-bs-target="#edit<?= $row['id']; ?>">
      <i class="bi bi-pencil-square"></i>
    </button>
    <a href="?hapus=<?= $row['id']; ?>" 
       onclick="return confirm('Hapus user ini?')"
       class="action-btn action-delete">
       <i class="bi bi-trash"></i>
    </a>
  </td>
</tr>

<!-- ================= MODAL EDIT ================= -->
<div class="modal fade" id="edit<?= $row['id']; ?>">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content modal-premium">
<form method="post">
<input type="hidden" name="id" value="<?= $row['id']; ?>">
<input type="hidden" name="edit">

<div class="modal-header modal-header-premium">
<h5>Edit User</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body row g-4 p-4">
<div class="col-md-6"><label>Nama</label><input class="form-control form-control-premium" name="nama" value="<?= $row['nama']; ?>"></div>
<div class="col-md-6"><label>Alamat</label><input class="form-control form-control-premium" name="alamat" value="<?= $row['alamat']; ?>"></div>
<div class="col-md-6"><label>Email</label><input class="form-control form-control-premium" name="email" value="<?= $row['email']; ?>"></div>
<div class="col-md-6"><label>Username</label><input class="form-control form-control-premium" name="username" value="<?= $row['username']; ?>"></div>
<div class="col-md-6"><label>Password (opsional)</label><input type="password" class="form-control form-control-premium" name="password"></div>

<div class="col-md-6">
<label>Jabatan</label>
<select name="jabatan" class="form-control form-control-premium">
<option <?= $row['jabatan']=="kepala unit penyerapan"?'selected':''; ?>>kepala unit penyerapan</option>
<option <?= $row['jabatan']=="petugas quality control"?'selected':''; ?>>petugas quality control</option>
<option <?= $row['jabatan']=="keuangan"?'selected':''; ?>>keuangan</option>
</select>
</div>

<div class="col-md-6">
<label>Hak Akses</label>
<select name="hak_akses" class="form-control form-control-premium">
<option <?= $row['hak_akses']=="admin"?'selected':''; ?>>admin</option>
<option <?= $row['hak_akses']=="TL"?'selected':''; ?>>TL</option>
<option <?= $row['hak_akses']=="HO"?'selected':''; ?>>HO</option>
</select>
</div>

<div class="col-12"><label>Keterangan</label><input class="form-control form-control-premium" name="keterangan" value="<?= $row['keterangan']; ?>"></div>
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
</div>

<!-- ================= MODAL TAMBAH ================= -->
<div class="modal fade" id="modalTambah">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content modal-premium">
<form method="post">
<input type="hidden" name="tambah">

<div class="modal-header modal-header-premium">
<h5>Tambah User</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body row g-4 p-4">
<div class="col-md-6"><label>Nama</label><input class="form-control form-control-premium" name="nama"></div>
<div class="col-md-6"><label>Alamat</label><input class="form-control form-control-premium" name="alamat"></div>
<div class="col-md-6"><label>Email</label><input class="form-control form-control-premium" name="email"></div>
<div class="col-md-6"><label>Username</label><input class="form-control form-control-premium" name="username"></div>
<div class="col-md-6"><label>Password</label><input type="password" class="form-control form-control-premium" name="password"></div>

<div class="col-md-6">
<label>Jabatan</label>
<select name="jabatan" class="form-control form-control-premium">
<option>kepala unit penyerapan</option>
<option>petugas quality control</option>
<option>keuangan</option>
</select>
</div>

<div class="col-md-6">
<label>Hak Akses</label>
<select name="hak_akses" class="form-control form-control-premium">
<option>admin</option>
<option>TL</option>
<option>HO</option>
</select>
</div>

<div class="col-12"><label>Keterangan</label><input class="form-control form-control-premium" name="keterangan"></div>
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
.admin-content{
  margin-left:260px;
  padding:90px 30px;
  background:#f4f7fb;
  min-height:100vh;
}
.card-admin{
  background:#fff;
  border-radius:18px;
  padding:30px;
  box-shadow:0 15px 35px rgba(0,0,0,.08);
}
.page-title{font-size:26px;font-weight:700;color:#0b8a34}
.page-subtitle{font-size:14px;color:#6c757d}
.btn-add{
  background:linear-gradient(135deg,#1fa24a,#28c76f);
  color:#fff;border-radius:30px;padding:10px 22px;font-weight:600
}
.table{border-collapse:separate;border-spacing:0 10px}
.table tbody tr{background:#fff;box-shadow:0 8px 20px rgba(0,0,0,.05)}
.badge-access{padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600}
.badge-admin{background:#28c76f;color:#fff}
.badge-TL{background:#ff9f43;color:#fff}
.badge-HO{background:#7367f0;color:#fff}
.action-btn{border:none;background:none;font-size:18px}
.action-edit{color:#0d6efd}
.action-delete{color:#dc3545}

/* ===== MODAL PREMIUM ===== */
.modal-premium{border-radius:18px;overflow:hidden}
.modal-header-premium{
  background:linear-gradient(135deg,#1fa24a,#28c76f);
  color:#fff
}
.form-control-premium{
  border-radius:12px;
  padding:10px 14px;
}
.form-control-premium:focus{
  box-shadow:0 0 0 3px rgba(40,199,111,.25);
}
</style>
