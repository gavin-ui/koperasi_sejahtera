<?php
include "partials/header.php";
include "partials/sidebar.php";
include "../koneksi.php";

/* ==========================
   HANDLE EDIT KELOMPOK
========================== */
if(isset($_POST['edit'])){

  if(!empty($_FILES['foto']['name'])){
    $foto = time().'_'.$_FILES['foto']['name'];
    move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/".$foto);

    $sql = "UPDATE tb_kelompok SET 
            nama_kelompok=?, keterangan=?, foto=?
            WHERE id=?";
    $data = [
      $_POST['nama_kelompok'],
      $_POST['keterangan'],
      $foto,
      $_POST['id']
    ];
  } else {
    $sql = "UPDATE tb_kelompok SET 
            nama_kelompok=?, keterangan=?
            WHERE id=?";
    $data = [
      $_POST['nama_kelompok'],
      $_POST['keterangan'],
      $_POST['id']
    ];
  }

  $pdo->prepare($sql)->execute($data);
  echo "<script>location='kelompok.php';</script>";
}
?>

<div class="admin-content">
<div class="card-admin">

<div class="mb-4">
  <div class="page-title">Data Kelompok</div>
  <div class="page-subtitle">Daftar kelompok binaan</div>
</div>

<table class="table align-middle">
<thead>
<tr>
  <th width="60">No</th>
  <th>Nama Kelompok</th>
  <th>Keterangan</th>
  <th class="text-center">Aksi</th>
</tr>
</thead>
<tbody>

<?php
$no = 1;
$stmt = $pdo->query("SELECT * FROM tb_kelompok ORDER BY id DESC");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
?>

<tr>
  <td><?= $no++; ?></td>
  <td><b><?= htmlspecialchars($row['nama_kelompok']); ?></b></td>
  <td><?= nl2br(htmlspecialchars($row['keterangan'])); ?></td>
  <td class="text-center">

    <!-- DETAIL -->
    <button class="action-btn text-info"
      data-bs-toggle="modal"
      data-bs-target="#detail<?= $row['id']; ?>">
      <i class="bi bi-eye-fill"></i>
    </button>

    <!-- EDIT -->
    <button class="action-btn text-primary"
      data-bs-toggle="modal"
      data-bs-target="#edit<?= $row['id']; ?>">
      <i class="bi bi-pencil-square"></i>
    </button>

  </td>
</tr>

<!-- ================= MODAL DETAIL ================= -->
<div class="modal fade" id="detail<?= $row['id']; ?>">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header">
<h5>Detail Kelompok</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body row g-4">

<div class="col-md-4 text-center">
<?php if($row['foto']){ ?>
<img src="uploads/<?= $row['foto']; ?>" class="img-fluid rounded shadow">
<?php } else { ?>
<div class="text-muted fst-italic">Belum ada foto</div>
<?php } ?>
</div>

<div class="col-md-8">
<p><b>Nama Kelompok</b><br><?= htmlspecialchars($row['nama_kelompok']); ?></p>
<p><b>Keterangan</b><br><?= nl2br(htmlspecialchars($row['keterangan'])); ?></p>
</div>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>

</div>
</div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div class="modal fade" id="edit<?= $row['id']; ?>">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<form method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $row['id']; ?>">
<input type="hidden" name="edit">

<div class="modal-header">
<h5>Edit Kelompok</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body row g-3">

<div class="col-md-6">
<label>Nama Kelompok</label>
<input class="form-control" name="nama_kelompok"
       value="<?= htmlspecialchars($row['nama_kelompok']); ?>" required>
</div>

<div class="col-md-6">
<label>Upload / Ganti Foto</label>
<input type="file" name="foto" class="form-control">
</div>

<div class="col-12">
<label>Keterangan</label>
<textarea name="keterangan" class="form-control" rows="3"><?= htmlspecialchars($row['keterangan']); ?></textarea>
</div>

<?php if($row['foto']){ ?>
<div class="col-12 text-center">
<img src="uploads/<?= $row['foto']; ?>" class="img-fluid rounded shadow" style="max-height:200px">
</div>
<?php } ?>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-success">Simpan Perubahan</button>
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

<?php include "partials/footer.php"; ?>

<style>
.admin-content{
  margin-left:260px;
  padding:90px 30px;
  background:#f4f7fb;
  min-height:100vh
}
.card-admin{
  background:#fff;
  border-radius:18px;
  padding:30px;
  box-shadow:0 15px 35px rgba(0,0,0,.08)
}
.page-title{
  font-size:26px;
  font-weight:700;
  color:#0b8a34
}
.page-subtitle{
  font-size:14px;
  color:#6c757d
}
.table{
  border-collapse:separate;
  border-spacing:0 10px
}
.table tbody tr{
  background:#fff;
  box-shadow:0 6px 18px rgba(0,0,0,.05)
}
.action-btn{
  background:none;
  border:none;
  font-size:18px;
  margin:0 6px
}
</style>
