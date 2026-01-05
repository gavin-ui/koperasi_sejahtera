<?php
include "../koneksi.php";

/* ================= TAMBAH ================= */
if (isset($_POST['tambah'])) {

    $ktp_file = null;
    if (!empty($_FILES['ktp_file']['name'])) {
        $ktp_file = time().'_'.$_FILES['ktp_file']['name'];
        move_uploaded_file($_FILES['ktp_file']['tmp_name'], __DIR__."/uploads/".$ktp_file);
    }

    $kartu_tani_file = null;
    if (!empty($_FILES['kartu_tani_file']['name'])) {
        $kartu_tani_file = time().'_'.$_FILES['kartu_tani_file']['name'];
        move_uploaded_file($_FILES['kartu_tani_file']['tmp_name'], __DIR__."/uploads/".$kartu_tani_file);
    }

    $stmt = $pdo->prepare("INSERT INTO mitra 
    (nama_mitra, alamat, no_ktp, no_kartu_tani, bank, nama_rekening, no_rekening, keterangan, ktp_file, kartu_tani_file)
    VALUES (?,?,?,?,?,?,?,?,?,?)");

    $stmt->execute([
        $_POST['nama_mitra'],
        $_POST['alamat'],
        $_POST['no_ktp'],
        $_POST['no_kartu_tani'],
        $_POST['bank'],
        $_POST['nama_rekening'],
        $_POST['no_rekening'],
        $_POST['keterangan'],
        $ktp_file,
        $kartu_tani_file
    ]);

    header("Location: mitra.php");
    exit;
}

/* ================= EDIT ================= */
if (isset($_POST['edit'])) {

    $ktp_file = $_POST['ktp_lama'];
    if (!empty($_FILES['ktp_file']['name'])) {
        $ktp_file = time().'_'.$_FILES['ktp_file']['name'];
        move_uploaded_file($_FILES['ktp_file']['tmp_name'], __DIR__."/uploads/".$ktp_file);
    }

    $kartu_tani_file = $_POST['kartu_tani_lama'];
    if (!empty($_FILES['kartu_tani_file']['name'])) {
        $kartu_tani_file = time().'_'.$_FILES['kartu_tani_file']['name'];
        move_uploaded_file($_FILES['kartu_tani_file']['tmp_name'], __DIR__."/uploads/".$kartu_tani_file);
    }

    $stmt = $pdo->prepare("UPDATE mitra SET
        nama_mitra=?,
        alamat=?,
        no_ktp=?,
        no_kartu_tani=?,
        bank=?,
        nama_rekening=?,
        no_rekening=?,
        keterangan=?,
        ktp_file=?,
        kartu_tani_file=?
        WHERE id_mitra=?");

    $stmt->execute([
        $_POST['nama_mitra'],
        $_POST['alamat'],
        $_POST['no_ktp'],
        $_POST['no_kartu_tani'],
        $_POST['bank'],
        $_POST['nama_rekening'],
        $_POST['no_rekening'],
        $_POST['keterangan'],
        $ktp_file,
        $kartu_tani_file,
        $_POST['id']
    ]);

    header("Location: mitra.php");
    exit;
}

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {
    $pdo->prepare("DELETE FROM mitra WHERE id_mitra=?")->execute([$_GET['hapus']]);
    header("Location: mitra.php");
    exit;
}

/* ================= PAGINATION + SEARCH ================= */
$limit = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$search = trim($_GET['search'] ?? '');
$where = '';
$params = [];

if ($search) {
    $where = "WHERE nama_mitra LIKE :s OR alamat LIKE :s OR no_ktp LIKE :s";
    $params[':s'] = "%$search%";
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM mitra $where");
$stmt->execute($params);
$totalData = $stmt->fetchColumn();
$totalPage = ceil($totalData / $limit);

$stmt = $pdo->prepare("SELECT * FROM mitra $where ORDER BY created_at DESC LIMIT :l OFFSET :o");
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->bindValue(':l', $limit, PDO::PARAM_INT);
$stmt->bindValue(':o', $offset, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll();

include "partials/header.php";
include "partials/sidebar.php";
?>

<div class="content-wrapper">
<h2>Data Mitra</h2>

<div class="top-bar">
<form>
<input type="hidden" name="page" value="1">
<input class="search" name="search" placeholder="Cari mitra..." value="<?= htmlspecialchars($search) ?>">
</form>
<button class="btn btn-add" onclick="openTambah()">+ Tambah Mitra</button>
</div>

<table>
<tr><th>No</th><th>Nama</th><th>Tanggal</th><th>Aksi</th></tr>
<?php foreach ($data as $i => $m): ?>
<tr>
<td><?= ($page-1)*$limit+$i+1 ?></td>
<td><?= $m['nama_mitra'] ?></td>
<td><?= date('d-m-Y', strtotime($m['created_at'])) ?></td>
<td>
<a onclick='detail(<?= json_encode($m) ?>)'>👁</a>
<a onclick='editData(<?= json_encode($m) ?>)'>✏</a>
<a href="?hapus=<?= $m['id_mitra'] ?>" onclick="return confirm('Hapus data ini?')">🗑</a>
</td>
</tr>
<?php endforeach ?>
</table>

<div class="pagination">
<?php for($i=1;$i<=$totalPage;$i++): ?>
<a class="<?= $i==$page?'active':'' ?>" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
<?php endfor ?>
</div>
</div>

<!-- MODAL TAMBAH / EDIT -->
<div class="modal" id="modal">
<div class="modal-content wide">
<h3 id="modalTitle"></h3>
<form method="POST" id="form" enctype="multipart/form-data">
<input type="hidden" name="id" id="id">
<input type="hidden" name="ktp_lama" id="ktp_lama">
<input type="hidden" name="kartu_tani_lama" id="kartu_tani_lama">

<div class="grid-2">
<input name="nama_mitra" id="nama_mitra" placeholder="Nama Mitra" required>
<input name="no_ktp" id="no_ktp" placeholder="No KTP">
<input name="no_kartu_tani" id="no_kartu_tani" placeholder="No Kartu Tani">
<select name="bank" id="bank"><option value="">Pilih Bank</option><option>BANK BRI</option><option>BANK BCA</option></select>
<input name="nama_rekening" id="nama_rekening" placeholder="Nama Rekening">
<input name="no_rekening" id="no_rekening" placeholder="No Rekening">
<input type="file" name="ktp_file">
<input type="file" name="kartu_tani_file">
</div>

<textarea name="alamat" id="alamat" placeholder="Alamat"></textarea>
<textarea name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>

<button class="btn btn-add" id="btnSubmit"></button>
<button type="button" onclick="closeModal()">Batal</button>
</form>
</div>
</div>

<!-- MODAL DETAIL -->
<div class="modal" id="modalDetail">
  <div class="modal-content wide">
    <h3>Detail Mitra</h3>

    <div class="grid-2">
      <input id="d_nama" readonly placeholder="Nama Mitra">
      <input id="d_bank" readonly placeholder="Bank">
      <input id="d_no_rek" readonly placeholder="No Rekening">

      <textarea id="d_alamat" readonly placeholder="Alamat"></textarea>
      <textarea id="d_keterangan" readonly placeholder="Keterangan"></textarea>
    </div>

    <!-- FOTO -->
    <div class="grid-2 mt-3">
      <div class="photo-box">
        <label>Foto KTP</label>
        <a id="ktp_link" target="_blank">
          <img id="d_ktp" class="photo-preview" alt="Foto KTP">
        </a>
        <small id="ktp_empty" class="text-muted"></small>
      </div>

      <div class="photo-box">
        <label>Foto Kartu Tani</label>
        <a id="kartu_link" target="_blank">
          <img id="d_kartu" class="photo-preview" alt="Foto Kartu Tani">
        </a>
        <small id="kartu_empty" class="text-muted"></small>
      </div>
    </div>

    <div style="text-align:right;margin-top:20px">
      <button onclick="closeDetail()">Tutup</button>
    </div>
  </div>
</div>

<script>
function openTambah(){
modal.style.display='flex';
form.reset();
modalTitle.innerText='Tambah Mitra';
btnSubmit.innerText='Simpan';
btnSubmit.name='tambah';
}
function editData(d){
openTambah();
modalTitle.innerText='Edit Mitra';
btnSubmit.innerText='Update';
btnSubmit.name='edit';
Object.keys(d).forEach(k=>{ if(document.getElementById(k)) document.getElementById(k).value=d[k];});
ktp_lama.value=d.ktp_file;
kartu_tani_lama.value=d.kartu_tani_file;
}
function detail(d){
  modalDetail.style.display='flex';

  d_nama.value = d.nama_mitra;
  d_bank.value = d.bank;
  d_no_rek.value = d.no_rekening;
  d_alamat.value = d.alamat;
  d_keterangan.value = d.keterangan;

  // FOTO KTP
  if(d.ktp_file){
    d_ktp.src = "uploads/" + d.ktp_file;
    ktp_link.href = "uploads/" + d.ktp_file;
    d_ktp.style.display = 'block';
    ktp_empty.innerText = '';
  }else{
    d_ktp.style.display = 'none';
    ktp_empty.innerText = 'Tidak ada foto KTP';
  }

  // FOTO KARTU TANI
  if(d.kartu_tani_file){
    d_kartu.src = "uploads/" + d.kartu_tani_file;
    kartu_link.href = "uploads/" + d.kartu_tani_file;
    d_kartu.style.display = 'block';
    kartu_empty.innerText = '';
  }else{
    d_kartu.style.display = 'none';
    kartu_empty.innerText = 'Tidak ada foto Kartu Tani';
  }
}
</script>

<?php include "partials/footer.php"; ?>

<style>
/* =================================================
   MITRA PREMIUM UI – FINAL FIX (MATCH GAPOKTAN)
================================================= */

/* ===== LAYOUT ===== */
.mitra-content,
.content-wrapper{
  margin-left:260px;
  padding:100px 30px 40px;
  background:linear-gradient(180deg,#f4f7fb,#eef2f7);
  min-height:100vh;
}

@media(max-width:991px){
  .mitra-content,
  .content-wrapper{
    margin-left:0;
    padding:90px 16px 30px;
  }
}

/* ===== PAGE TITLE ===== */
.content-wrapper h2{
  font-size:28px;
  font-weight:800;
  color:#0f172a;
  letter-spacing:.3px;
}

/* ===== TOP BAR ===== */
.top-bar{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin:25px 0 30px;
  gap:14px;
  flex-wrap:wrap;
}

/* ===== SEARCH ===== */
.search{
  border-radius:14px;
  padding:12px 14px;
  border:1px solid #e2e8f0;
  min-width:240px;
  transition:.3s;
}

.search:focus{
  outline:none;
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
table{
  width:100%;
  border-collapse:separate;
  border-spacing:0 12px;
}

table th{
  font-size:12px;
  text-transform:uppercase;
  letter-spacing:1px;
  color:#64748b;
  padding:8px 14px;
  text-align:left;
}

table td{
  background:#ffffff;
  padding:14px 16px;
  border:none;
  vertical-align:middle;
}

table tr{
  box-shadow:0 8px 25px rgba(15,23,42,.06);
  border-radius:16px;
  transition:.3s;
}

table tr:hover{
  transform:translateY(-2px);
  box-shadow:0 12px 35px rgba(15,23,42,.12);
}

/* ===== ACTION ICON ===== */
td a{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  width:38px;
  height:38px;
  background:#f1f5f9;
  border-radius:12px;
  text-decoration:none;
  font-size:16px;
  margin-right:6px;
  transition:.3s;
}

td a:nth-child(1){ color:#16a34a; }
td a:nth-child(2){ color:#2563eb; }
td a:nth-child(3){ color:#dc2626; }

td a:nth-child(1):hover{ background:#dcfce7; }
td a:nth-child(2):hover{ background:#dbeafe; }
td a:nth-child(3):hover{ background:#fee2e2; }

/* ===== PAGINATION ===== */
.pagination{
  display:flex;
  justify-content:center;
  margin-top:30px;
  gap:6px;
}

.pagination a{
  border:none;
  border-radius:12px;
  padding:8px 14px;
  color:#475569;
  font-weight:600;
  text-decoration:none;
  background:#f1f5f9;
}

.pagination a.active{
  background:linear-gradient(135deg,#16a34a,#22c55e);
  color:#fff;
  box-shadow:0 6px 18px rgba(34,197,94,.45);
}

/* =================================================
   MODAL PREMIUM – TAMBAH / EDIT / DETAIL
================================================= */

/* ===== OVERLAY ===== */
.modal{
  display:none;
  position:fixed;
  inset:0;
  background:rgba(15,23,42,.55);
  z-index:999;
  justify-content:center;
  align-items:center;
  padding:20px;
}

/* ===== MODAL BOX ===== */
.modal-content{
  background:#ffffff;
  border-radius:22px;
  width:100%;
  max-width:820px;
  box-shadow:0 25px 60px rgba(15,23,42,.3);
  overflow:hidden;
  animation:fadeUp .3s ease;
}

/* ===== MODAL HEADER ===== */
.modal-content h3{
  margin:0;
  padding:16px 24px;
  font-size:18px;
  font-weight:800;
  color:#ffffff;
  background:linear-gradient(135deg,#16a34a,#22c55e);
  letter-spacing:.4px;
}

/* ===== MODAL BODY (LANDSCAPE GRID) ===== */
.modal-content form{
  padding:22px 24px;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:14px 18px;
}

/* Full width */
.modal-content .modal-full,
.modal-content textarea,
.modal-content label{
  grid-column:1 / -1;
}

/* ===== LABEL ===== */
.modal-content label{
  font-size:13px;
  font-weight:600;
  color:#334155;
}

/* ===== INPUT ===== */
.modal-content input,
.modal-content textarea,
.modal-content select{
  width:100%;
  border-radius:12px;
  padding:10px 13px;
  border:1px solid #e2e8f0;
  font-size:14px;
}

.modal-content textarea{
  resize:none;
  min-height:80px;
}

.modal-content input:focus,
.modal-content textarea:focus,
.modal-content select:focus{
  outline:none;
  border-color:#22c55e;
  box-shadow:0 0 0 3px rgba(34,197,94,.18);
}

/* ===== BUTTON AREA ===== */
.modal-content button{
  border:none;
  border-radius:999px;
  padding:10px 24px;
  font-weight:700;
  cursor:pointer;
}

.modal-content button[type="submit"],
#btnSubmit{
  background:linear-gradient(135deg,#16a34a,#22c55e);
  color:#fff;
  box-shadow:0 6px 16px rgba(34,197,94,.35);
}

.modal-content button[type="button"]{
  background:#e5e7eb;
  color:#334155;
}

/* ===== ANIMATION ===== */
@keyframes fadeUp{
  from{
    opacity:0;
    transform:translateY(25px);
  }
  to{
    opacity:1;
    transform:translateY(0);
  }
}

/* ===== MOBILE ===== */
@media(max-width:768px){
  .modal-content{
    max-width:100%;
  }
  .modal-content form{
    grid-template-columns:1fr;
  }
}
.grid-2{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:16px;
}

.wide{
  max-width:900px;
}

.photo-box{
  background:#f8fafc;
  padding:14px;
  border-radius:14px;
  text-align:center;
}

.photo-box label{
  font-size:13px;
  font-weight:700;
  color:#334155;
  display:block;
  margin-bottom:8px;
}

.photo-preview{
  width:100%;
  max-height:220px;
  object-fit:contain;
  border-radius:12px;
  box-shadow:0 6px 18px rgba(0,0,0,.15);
}

.text-muted{
  color:#94a3b8;
  font-size:12px;
}
</style>