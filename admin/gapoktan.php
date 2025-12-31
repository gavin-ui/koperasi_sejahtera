<?php
include "partials/header.php";
include "partials/sidebar.php";
include "../koneksi.php";

/* =========================
   TAMBAH GAPOKTAN
========================= */
if(isset($_POST['tambah'])){

  $logo = "";
  if(!empty($_FILES['logo']['name'])){
    $folder = __DIR__ . "/uploads/";
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
    $folder = __DIR__ . "/uploads/";
    if(!is_dir($folder)) mkdir($folder,0777,true);

    $logo = time().'_'.basename($_FILES['logo']['name']);
    move_uploaded_file($_FILES['logo']['tmp_name'], $folder.$logo);

    $sql = "UPDATE tb_gapoktan SET 
            nama_gapoktan=?, nama_ketua=?, alamat=?, logo=?
            WHERE id=?";
    $data = [
      $_POST['nama_gapoktan'],
      $_POST['nama_ketua'],
      $_POST['alamat'],
      $logo,
      $_POST['id']
    ];
  } else {
    $sql = "UPDATE tb_gapoktan SET 
            nama_gapoktan=?, nama_ketua=?, alamat=?
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

<div class="admin-content">
<div class="card-admin">

<div class="header-flex">
  <h4>Master Gapoktan</h4>
  <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
    + Tambah Data
  </button>
</div>

<table class="table table-hover align-middle">
<thead>
<tr>
  <th>No</th>
  <th>Nama Gapoktan</th>
  <th>Nama Ketua</th>
  <th>Tanggal</th>
  <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<?php
$no=1;
$data=$pdo->query("SELECT * FROM tb_gapoktan ORDER BY id DESC");
foreach($data as $row){
?>
<tr>
<td><?= $no++; ?></td>
<td><?= $row['nama_gapoktan']; ?></td>
<td><?= $row['nama_ketua']; ?></td>
<td><?= date("d F Y",strtotime($row['tanggal'])); ?></td>
<td class="text-center">
<button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id']; ?>">✏️</button>
<a href="?hapus=<?= $row['id']; ?>" onclick="return confirm('Hapus data?')" class="btn btn-sm btn-danger">🗑️</a>
</td>
</tr>

<!-- MODAL EDIT -->
<div class="modal fade" id="edit<?= $row['id']; ?>">
<div class="modal-dialog modal-dialog-centered modal-lg">
<div class="modal-content modal-elegant">
<form method="post" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $row['id']; ?>">
<input type="hidden" name="edit">

<div class="modal-header">
<h5>Edit Gapoktan</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="form-group">
<label>Nama Gapoktan</label>
<input class="form-control" name="nama_gapoktan" value="<?= $row['nama_gapoktan']; ?>" required>
</div>

<div class="form-group">
<label>Nama Ketua</label>
<input class="form-control" name="nama_ketua" value="<?= $row['nama_ketua']; ?>" required>
</div>

<div class="form-group">
<label>Alamat</label>
<input class="form-control" name="alamat" value="<?= $row['alamat']; ?>">
</div>

<div class="form-group">
<label>Upload Logo</label>
<input type="file" class="form-control" name="logo">
</div>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-success">Simpan</button>
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

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah">
<div class="modal-dialog modal-dialog-centered modal-lg">
<div class="modal-content modal-elegant">
<form method="post" enctype="multipart/form-data">

<input type="hidden" name="tambah">

<div class="modal-header">
<h5>Tambah Gapoktan</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="form-group">
<label>Nama Gapoktan</label>
<input class="form-control" name="nama_gapoktan" required>
</div>

<div class="form-group">
<label>Nama Ketua</label>
<input class="form-control" name="nama_ketua" required>
</div>

<div class="form-group">
<label>Alamat</label>
<input class="form-control" name="alamat">
</div>

<div class="form-group">
<label>Upload Logo</label>
<input type="file" class="form-control" name="logo">
</div>
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
.admin-content{margin-left:260px;padding:90px 30px;background:#f4f7fb;min-height:100vh}
.card-admin{background:#fff;border-radius:18px;padding:30px;box-shadow:0 15px 35px rgba(0,0,0,.08)}
.header-flex{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
.btn-add{background:linear-gradient(135deg,#1fa24a,#28c76f);color:#fff;border-radius:30px;padding:10px 22px}
.modal-elegant{border-radius:16px;box-shadow:0 20px 45px rgba(0,0,0,.15)}
.form-group{margin-bottom:15px}
.form-control{border-radius:12px;padding:12px}
</style>
