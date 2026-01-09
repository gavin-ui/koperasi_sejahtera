<?php
include "../koneksi.php";

/* ===================== PROSES CRUD ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'])) {

    $aksi = $_POST['aksi'];
    $id   = $_POST['id'] ?? null;
    $kode = $_POST['kode'];
    $komoditas = $_POST['komoditas_id'];
    $nama = $_POST['nama_varietas'];
    $ket  = $_POST['keterangan'];

    // Upload gambar
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        if(!is_dir("uploads")) mkdir("uploads");
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/" . $gambar);
    }

    if ($aksi === 'tambah') {
        $stmt = $pdo->prepare("INSERT INTO tb_varietas 
            (kode, komoditas_id, nama_varietas, keterangan, gambar)
            VALUES (?,?,?,?,?)");
        $stmt->execute([$kode,$komoditas,$nama,$ket,$gambar]);
    }

    if ($aksi === 'edit') {
        if ($gambar) {
            $stmt = $pdo->prepare("UPDATE tb_varietas 
                SET kode=?, komoditas_id=?, nama_varietas=?, keterangan=?, gambar=?
                WHERE id=?");
            $stmt->execute([$kode,$komoditas,$nama,$ket,$gambar,$id]);
        } else {
            $stmt = $pdo->prepare("UPDATE tb_varietas 
                SET kode=?, komoditas_id=?, nama_varietas=?, keterangan=?
                WHERE id=?");
            $stmt->execute([$kode,$komoditas,$nama,$ket,$id]);
        }
    }

    if ($aksi === 'hapus') {
        $stmt = $pdo->prepare("DELETE FROM tb_varietas WHERE id=?");
        $stmt->execute([$id]);
    }

    header("Location: varietas.php");
    exit;
}

/* ================= DATA ================= */
$limit = 10;
$page  = $_GET['page'] ?? 1;
$start = ($page - 1) * $limit;
$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT v.*, k.nama_komoditas 
        FROM tb_varietas v 
        JOIN tb_komoditas k ON v.komoditas_id = k.id";

if ($keyword) {
    $sql .= " WHERE v.kode LIKE :kw 
              OR v.nama_varietas LIKE :kw 
              OR k.nama_komoditas LIKE :kw";
}

$sql .= " ORDER BY v.id DESC LIMIT :s,:l";
$data = $pdo->prepare($sql);

if ($keyword) $data->bindValue(':kw', "%$keyword%");
$data->bindValue(':s', $start, PDO::PARAM_INT);
$data->bindValue(':l', $limit, PDO::PARAM_INT);
$data->execute();

$total = $pdo->query("SELECT COUNT(*) FROM tb_varietas")->fetchColumn();
$pages = ceil($total / $limit);
$komoditas = $pdo->query("SELECT * FROM tb_komoditas")->fetchAll();
?>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<div class="admin-content">
<div class="card-admin">

<div class="page-header">
  <div>
    <div class="page-title">Varietas</div>
    <div class="page-subtitle">Manajemen varietas komoditas</div>
  </div>
  <button class="btn-add" onclick="openTambah()">+ Tambah</button>
</div>

<form class="search-box" method="GET">
  <input type="text" name="keyword" placeholder="Cari varietas..." value="<?= $keyword ?>">
  <button>Cari</button>
</form>

<table class="table">
<thead>
<tr>
  <th>No</th>
  <th>Kode</th>
  <th>Komoditas</th>
  <th>Varietas</th>
  <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php foreach($data as $i=>$d): ?>
<tr>
<td><?= $start + $i + 1 ?></td>
<td><?= $d['kode'] ?></td>
<td><?= $d['nama_komoditas'] ?></td>
<td><?= $d['nama_varietas'] ?></td>
<td>
<div class="action-group">
<button class="action-btn view" onclick='detailData(<?= json_encode($d) ?>)'>👁</button>
<button class="action-btn edit" onclick='editData(<?= json_encode($d) ?>)'>✏</button>
<button class="action-btn delete" onclick="hapusData(<?= $d['id'] ?>)">🗑</button>
</div>
</td>
</tr>
<?php endforeach ?>
</tbody>
</table>

<div class="pagination">
<?php for($i=1;$i<=$pages;$i++): ?>
<a href="?page=<?= $i ?>" class="<?= $i==$page?'active':'' ?>"><?= $i ?></a>
<?php endfor ?>
</div>

</div>
</div>

<!-- MODAL FORM -->
<div class="modal" id="modalForm">
<div class="modal-box">
<h4>Form Varietas</h4>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="aksi" id="aksi">
<input type="hidden" name="id" id="id">
<input class="form-control" name="kode" id="kode" placeholder="Kode" required>
<select class="form-control" name="komoditas_id" id="komoditas">
<?php foreach($komoditas as $k): ?>
<option value="<?= $k['id'] ?>"><?= $k['nama_komoditas'] ?></option>
<?php endforeach ?>
</select>
<input class="form-control" name="nama_varietas" id="nama" placeholder="Nama varietas">
<textarea class="form-control" name="keterangan" id="ket"></textarea>
<input type="file" name="gambar" class="form-control">
<button class="btn-primary">Simpan</button>
<button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
</form>
</div>
</div>

<!-- MODAL DETAIL -->
<div class="modal" id="modalDetail">
<div class="modal-box">
<img id="detailImg">
<p><b>Kode:</b> <span id="dKode"></span></p>
<p><b>Komoditas:</b> <span id="dKomoditas"></span></p>
<p><b>Varietas:</b> <span id="dNama"></span></p>
<p id="dKet"></p>
<button class="btn-secondary" onclick="closeModal()">Tutup</button>
</div>
</div>

<script>
function openTambah(){
 modalForm.style.display='flex';
 aksi.value='tambah';
 modalForm.querySelector('form').reset();
}
function editData(d){
 modalForm.style.display='flex';
 aksi.value='edit';
 id.value=d.id;
 kode.value=d.kode;
 nama.value=d.nama_varietas;
 ket.value=d.keterangan;
 komoditas.value=d.komoditas_id;
}
function detailData(d){
 modalDetail.style.display='flex';
 dKode.innerText=d.kode;
 dKomoditas.innerText=d.nama_komoditas;
 dNama.innerText=d.nama_varietas;
 dKet.innerText=d.keterangan;
 detailImg.src=d.gambar?'uploads/'+d.gambar:'';
}
function closeModal(){
 document.querySelectorAll('.modal').forEach(m=>m.style.display='none');
}
function hapusData(id){
 if(confirm('Hapus data?')){
  const f=document.createElement('form');
  f.method='POST';
  f.innerHTML=`<input name="aksi" value="hapus"><input name="id" value="${id}">`;
  document.body.appendChild(f);f.submit();
 }
}
</script>

<style>
/* ===============================
   LAYOUT CONTENT
================================ */
.admin-content{
  margin-left:260px;
  padding:90px 40px 48px;
  background:linear-gradient(180deg,#f8fafc,#f1f5f9);
  min-height:100vh;
}

@media(max-width:991px){
  .admin-content{
    margin-left:0;
    padding:80px 20px 32px;
  }
}

/* ===============================
   CARD / CONTAINER
================================ */
.card-admin{
  background:#ffffff;
  border-radius:32px;
  padding:36px;
  box-shadow:
    0 25px 50px rgba(15,23,42,.08),
    inset 0 1px 0 rgba(255,255,255,.6);
}

/* ===============================
   PAGE HEADER
================================ */
.page-title{
  font-size:32px;
  font-weight:900;
  color:#020617;
  letter-spacing:.4px;
}

.page-subtitle{
  font-size:15px;
  color:#64748b;
  margin-top:6px;
}

/* ===============================
   TOP BAR
================================ */
.top-bar{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin:30px 0 28px;
  gap:16px;
  flex-wrap:wrap;
}

/* ===============================
   SEARCH
================================ */
.search-box{
  display:flex;
  gap:12px;
}

.search-box input{
  border-radius:999px;
  padding:13px 22px;
  border:1px solid #e2e8f0;
  min-width:280px;
  font-size:14px;
  background:#f8fafc;
  transition:.3s;
}

.search-box input:focus{
  outline:none;
  background:#ffffff;
  border-color:#22c55e;
  box-shadow:0 0 0 5px rgba(34,197,94,.18);
}

.search-box button{
  background:linear-gradient(135deg,#16a34a,#22c55e);
  border:none;
  color:#fff;
  padding:13px 30px;
  border-radius:999px;
  font-weight:800;
  letter-spacing:.3px;
  box-shadow:0 10px 25px rgba(34,197,94,.4);
  transition:.3s;
}

.search-box button:hover{
  transform:translateY(-2px);
}

/* ===============================
   BUTTON ADD
================================ */
.btn-add{
  background:linear-gradient(135deg,#22c55e,#16a34a);
  color:#fff;
  border:none;
  border-radius:999px;
  padding:13px 28px;
  font-weight:800;
  display:inline-flex;
  align-items:center;
  gap:10px;
  box-shadow:0 12px 28px rgba(34,197,94,.45);
  transition:.3s;
}

.btn-add:hover{
  transform:translateY(-3px);
  box-shadow:0 16px 36px rgba(34,197,94,.55);
}

/* ===============================
   TABLE PREMIUM
================================ */
.table{
  width:100%;
  border-collapse:separate;
  border-spacing:0 16px;
}

.table thead th{
  font-size:12px;
  font-weight:800;
  text-transform:uppercase;
  letter-spacing:1.1px;
  color:#64748b;
  border:none;
  padding:10px 16px;
}

.table tbody tr{
  background:#ffffff;
  border-radius:22px;
  box-shadow:0 10px 28px rgba(15,23,42,.06);
  transition:.35s;
}

.table tbody tr:hover{
  transform:translateY(-4px);
  box-shadow:0 20px 48px rgba(15,23,42,.14);
}

.table tbody td{
  border:none;
  padding:18px 20px;
  vertical-align:middle;
  font-size:14px;
  color:#020617;
}

/* ===============================
   USER NAME BOLD
================================ */
.table tbody td strong{
  font-weight:900;
}

/* ===============================
   BADGE AKSES
================================ */
.badge-access{
  padding:7px 20px;
  border-radius:999px;
  font-size:11px;
  font-weight:900;
  letter-spacing:.6px;
  display:inline-block;
}

.badge-admin{
  background:linear-gradient(135deg,#22c55e,#16a34a);
  color:#fff;
  box-shadow:0 6px 18px rgba(34,197,94,.4);
}

.badge-TL,
.badge-HO{
  background:#e5e7eb;
  color:#334155;
}

/* ===============================
   ACTION BUTTON
================================ */
.action-btn{
  border:none;
  width:40px;
  height:40px;
  border-radius:14px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  font-size:16px;
  cursor:pointer;
  background:#f1f5f9;
  transition:.25s;
}

.action-btn:hover{
  transform:translateY(-2px);
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
.modal{
  position:fixed;
  inset:0;
  background:rgba(15,23,42,.45);
  backdrop-filter:blur(6px);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:999;
}

.modal-premium{
  background:#ffffff;
  width:100%;
  max-width:560px;
  border-radius:34px;
  overflow:hidden;
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

/* ===============================
   MODAL HEADER
================================ */
.modal-header-premium{
  background:linear-gradient(135deg,#22c55e,#16a34a);
  color:#fff;
  padding:22px 32px;
}

.modal-header-premium h5{
  margin:0;
  font-weight:900;
  letter-spacing:.6px;
}

/* ===============================
   MODAL BODY
================================ */
.modal-body{
  padding:32px;
}

.modal-body label{
  font-size:12px;
  font-weight:800;
  color:#475569;
  margin-bottom:6px;
  text-transform:uppercase;
  letter-spacing:.6px;
}

/* ===============================
   INPUT PREMIUM
================================ */
.form-control-premium{
  width:100%;
  border-radius:18px;
  padding:14px 18px;
  border:1px solid #e2e8f0;
  font-size:14px;
  margin-bottom:18px;
  background:#f8fafc;
  transition:.3s;
}

.form-control-premium:focus{
  outline:none;
  background:#ffffff;
  border-color:#22c55e;
  box-shadow:0 0 0 5px rgba(34,197,94,.18);
}

/* ===============================
   MODAL FOOTER
================================ */
.modal-footer{
  padding:22px 32px;
  background:#f8fafc;
  display:flex;
  justify-content:flex-end;
  gap:12px;
}

.modal-footer .btn{
  border-radius:999px;
  padding:12px 28px;
  font-weight:800;
}

/* ===============================
   MOBILE
================================ */
@media(max-width:576px){
  .modal-premium{
    max-width:100%;
    margin:14px;
  }
}
</style>

<?php include "partials/footer.php"; ?>
