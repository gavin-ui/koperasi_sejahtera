<?php
include "partials/header.php";
include "partials/sidebar.php";
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
  echo "<script>location='komoditas.php';</script>";
}

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
  echo "<script>location='komoditas.php';</script>";
}

/* =========================
   HAPUS KOMODITAS
========================= */
if(isset($_GET['hapus'])){
  $pdo->prepare("DELETE FROM tb_komoditas WHERE id=?")->execute([$_GET['hapus']]);
  echo "<script>location='komoditas.php';</script>";
}
?>

<div class="admin-content">
<div class="card-admin">

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold">Master Komoditas</h4>
  <button class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#modalTambah">
    + Tambah
  </button>
</div>

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

<?php
$no=1;
$data=$pdo->query("SELECT * FROM tb_komoditas ORDER BY id DESC");
foreach($data as $row){
?>
<tr>
  <td><?= $no++; ?></td>
  <td><?= $row['nama_komoditas']; ?></td>
  <td><?= $row['kelompok']; ?></td>
  <td><?= $row['satuan']; ?></td>
  <td class="text-center">
    <button class="btn btn-sm btn-warning"
      data-bs-toggle="modal"
      data-bs-target="#edit<?= $row['id']; ?>">
      ✏️
    </button>
    <a href="?hapus=<?= $row['id']; ?>"
       onclick="return confirm('Hapus data ini?')"
       class="btn btn-sm btn-danger">
       🗑️
    </a>
  </td>
</tr>

<!-- ================= MODAL EDIT ================= -->
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
<input class="form-control mb-2" name="nama_komoditas" value="<?= $row['nama_komoditas']; ?>" required>

<label>Kelompok</label>
<select name="kelompok" class="form-control mb-2" required>
<?php
$kel=$pdo->query("SELECT * FROM tb_kelompok");
foreach($kel as $k){
?>
<option <?= $row['kelompok']==$k['nama_kelompok']?'selected':''; ?>>
  <?= $k['nama_kelompok']; ?>
</option>
<?php } ?>
</select>

<label>Satuan</label>
<input class="form-control" name="satuan" value="<?= $row['satuan']; ?>" required>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-primary">Simpan</button>
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
<input class="form-control mb-2" name="nama_komoditas" required>

<label>Kelompok</label>
<select name="kelompok" class="form-control mb-2" required>
<option value="">Pilih Kelompok</option>
<?php
$kel=$pdo->query("SELECT * FROM tb_kelompok");
foreach($kel as $k){
?>
<option><?= $k['nama_kelompok']; ?></option>
<?php } ?>
</select>

<label>Satuan</label>
<input class="form-control" name="satuan" required>
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
.admin-content{
  margin-left:260px;
  padding:90px 30px;
  background:#f5f7fb;
  min-height:100vh
}
.card-admin{
  background:#fff;
  border-radius:18px;
  padding:30px;
  box-shadow:0 12px 30px rgba(0,0,0,.08)
}
.table tbody tr{
  background:#fff;
  box-shadow:0 6px 15px rgba(0,0,0,.05)
}
.modal-modern{
  border-radius:16px;
  box-shadow:0 20px 40px rgba(0,0,0,.2)
}
.btn-add{
  border-radius:30px;
  padding:8px 20px;
  font-weight:600
}
</style>
