<?php
include "../koneksi.php";

/* ================= TAMBAH ================= */
if (isset($_POST['tambah'])) {

    $ktp_file = '';
    if (!empty($_FILES['ktp_file']['name'])) {
        $ktp_file = time().'_'.$_FILES['ktp_file']['name'];
        move_uploaded_file($_FILES['ktp_file']['tmp_name'], "../upload/ktp/".$ktp_file);
    }

    $kartu_tani_file = '';
    if (!empty($_FILES['kartu_tani_file']['name'])) {
        $kartu_tani_file = time().'_'.$_FILES['kartu_tani_file']['name'];
        move_uploaded_file($_FILES['kartu_tani_file']['tmp_name'], "../upload/kartu_tani/".$kartu_tani_file);
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
        move_uploaded_file($_FILES['ktp_file']['tmp_name'], "../upload/ktp/".$ktp_file);
    }

    $kartu_tani_file = $_POST['kartu_tani_lama'];
    if (!empty($_FILES['kartu_tani_file']['name'])) {
        $kartu_tani_file = time().'_'.$_FILES['kartu_tani_file']['name'];
        move_uploaded_file($_FILES['kartu_tani_file']['tmp_name'], "../upload/kartu_tani/".$kartu_tani_file);
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

/* ================= DATA ================= */
$data = $pdo->query("SELECT * FROM mitra ORDER BY created_at DESC")->fetchAll();

/* ================= PAGINATION ================= */
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page < 1 ? 1 : $page;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = '';
$params = [];

if ($search != '') {
    $where = "WHERE 
        nama_mitra LIKE :search OR
        alamat LIKE :search OR
        no_ktp LIKE :search OR
        no_kartu_tani LIKE :search OR
        bank LIKE :search OR
        nama_rekening LIKE :search OR
        no_rekening LIKE :search OR
        keterangan LIKE :search
    ";
    $params[':search'] = "%$search%";
}

/* total data */
$stmt = $pdo->prepare("SELECT COUNT(*) FROM mitra $where");
$stmt->execute($params);
$totalData = $stmt->fetchColumn();
$totalPage = ceil($totalData / $limit);

/* data per halaman */
$stmt = $pdo->prepare("
    SELECT * FROM mitra
    $where
    ORDER BY created_at DESC
    LIMIT :limit OFFSET :offset
");

foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll();
?>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<style>
    /* ================= GLOBAL ================= */
*{
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f4f6f9;
}

/* ================= CONTENT ================= */
.content-wrapper{
    margin-left:260px;
    padding:30px;
}

h2{
    margin-bottom:20px;
    font-size:22px;
}

/* ================= TOP BAR ================= */
.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
    gap:10px;
}

.search{
    width:260px;
    padding:10px 14px;
    border-radius:8px;
    border:1px solid #ddd;
    outline:none;
}

.search:focus{
    border-color:#2d8cff;
}

/* ================= BUTTON ================= */
.btn{
    padding:10px 18px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
}

.btn-add{
    background:#2d8cff;
    color:#fff;
}

.btn-add:hover{
    opacity:.9;
}

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

th,td{
    padding:14px;
    text-align:left;
    font-size:14px;
}

th{
    background:#f1f4f8;
    font-weight:600;
}

tr:nth-child(even){
    background:#fafafa;
}

.action a{
    margin-right:10px;
    text-decoration:none;
    font-size:18px;
    cursor:pointer;
}

.action a:hover{
    opacity:.7;
}

/* ================= MODAL ================= */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.4);
    z-index:999;
    align-items:center;
    justify-content:center;
}

.modal-content{
    background:#fff;
    width:480px;
    max-height:90vh;
    overflow-y:auto;
    padding:25px;
    border-radius:14px;
    animation:zoom .25s ease;
}

@keyframes zoom{
    from{transform:scale(.8);opacity:0}
    to{transform:scale(1);opacity:1}
}

.modal h3{
    margin-top:0;
    margin-bottom:15px;
}

/* ================= FORM ================= */
.modal input,
.modal textarea,
.modal select{
    width:100%;
    padding:11px 13px;
    margin-bottom:12px;
    border-radius:8px;
    border:1px solid #ddd;
    font-size:14px;
}

.modal textarea{
    resize:none;
    min-height:70px;
}

.modal input:focus,
.modal textarea:focus,
.modal select:focus{
    border-color:#2d8cff;
    outline:none;
}

/* ================= FILE INPUT ================= */
.modal label{
    font-size:13px;
    font-weight:600;
    margin-bottom:5px;
    display:block;
}

/* ================= MODAL BUTTON ================= */
.modal button{
    width:100%;
    margin-top:5px;
    padding:12px;
    border-radius:8px;
    border:none;
    cursor:pointer;
    font-weight:600;
}

.modal button[type="button"]{
    background:#eee;
    margin-top:8px;
}

.modal button[type="button"]:hover{
    background:#ddd;
}

/* ================= SELECT2 ================= */
.select2-container--default .select2-selection--single{
    height:42px;
    border-radius:8px;
    border:1px solid #ddd;
}

.select2-selection__rendered{
    line-height:42px !important;
}

.select2-selection__arrow{
    height:42px !important;
}

/* ================= RESPONSIVE ================= */
@media(max-width:768px){
    .content-wrapper{
        margin-left:0;
        padding:20px;
    }

    .top-bar{
        flex-direction:column;
        align-items:flex-start;
    }

    .search{
        width:100%;
    }

    .modal-content{
        width:95%;
    }
}

html, body {
    height: 100%;
}

body {
    display: flex;
    flex-direction: column;
}

.wrapper,
.main-wrapper,
.content {
    flex: 1;
}

/* jika tidak ada wrapper khusus, pakai content-wrapper */
.content-wrapper {
    flex: 1;
}

.pagination{
    margin-top:20px;
    display:flex;
    justify-content:center;
    gap:8px;
}

.pagination a{
    padding:8px 14px;
    border-radius:8px;
    border:1px solid #ddd;
    text-decoration:none;
    color:#333;
    font-weight:600;
    background:#fff;
    transition:.2s;
}

.pagination a:hover{
    background:#2d8cff;
    color:#fff;
    border-color:#2d8cff;
}

.pagination a.active{
    background:#2d8cff;
    color:#fff;
    border-color:#2d8cff;
}
</style>
<div class="content-wrapper">

<h2>Data Mitra</h2>

<div class="top-bar">
    <form method="GET">
        <input type="hidden" name="page" value="1">

        <input 
            class="search" 
            name="search" 
            placeholder="Cari mitra..."
            value="<?= htmlspecialchars($search ?? '') ?>"
        >
    </form>
    <button class="btn btn-add" onclick="openTambah()">+ Tambah Mitra</button>
</div>

<table id="table">
<tr>
    <th>No</th>
    <th>Nama Mitra</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>

<?php foreach ($data as $i => $m): ?>
<tr>
    <td 
        class="no" 
        data-original-no="<?= ($page - 1) * $limit + $i + 1 ?>"
    >
        <?= ($page - 1) * $limit + $i + 1 ?>
    </td>
    <td><?= $m['nama_mitra'] ?></td>
    <td><?= date('d-m-Y', strtotime($m['created_at'])) ?></td>
    <td class="action">
        <a href="#" onclick='detail(<?= json_encode($m) ?>)'>👁</a>
        <a href="#" onclick='editData(<?= json_encode($m) ?>)'>✏</a>
        <a href="?hapus=<?= $m['id_mitra'] ?>" onclick="return confirm('Hapus data ini?')">🗑</a>
    </td>
</tr>
<?php endforeach ?>

</table>
<?php if ($totalPage > 1): ?>
<div class="pagination">
<?php for ($i = 1; $i <= $totalPage; $i++): ?>
    <a 
        href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
        class="<?= $i == $page ? 'active' : '' ?>"
    >
        <?= $i ?>
    </a>
<?php endfor; ?>
</div>
<?php endif; ?>
</div>

<!-- ================= MODAL ================= -->
<div class="modal" id="modal">
<div class="modal-content">
<h3 id="modalTitle"></h3>

<form method="POST" id="form" enctype="multipart/form-data">

<input type="hidden" name="id" id="id">
<input type="hidden" name="ktp_lama" id="ktp_lama">
<input type="hidden" name="kartu_tani_lama" id="kartu_tani_lama">

<input name="nama_mitra" id="nama_mitra" placeholder="Nama Mitra" required>
<textarea name="alamat" id="alamat" placeholder="Alamat"></textarea>
<input name="no_ktp" id="no_ktp" placeholder="No KTP">
<input name="no_kartu_tani" id="no_kartu_tani" placeholder="No Kartu Tani">

<select name="bank" id="bank" required>
<option value="">Pilih Bank</option>
<option>BANK BRI</option>
<option>BANK MANDIRI</option>
<option>BANK BNI</option>
<option>BANK BTN</option>
<option>BANK BCA</option>
<option>BANK BSI</option>
<option>BANK CIMB NIAGA</option>
<option>BANK DANAMON</option>
<option>PERMATA BANK</option>
<option>BANK MAYBANK</option>
<option>BANK PANIN</option>
<option>BANK OCBC NISP</option>
<option>BANK UOB</option>
<option>BANK MEGA</option>
<option>BANK JATENG</option>
<option>BANK JATIM</option>
<option>BANK DKI</option>
<option>BANK JABAR BANTEN</option>
<option>BANK ACEH</option>
<option>BANK NTB</option>
<option>BANK NTT</option>
<option>BANK PAPUA</option>
</select>

<input name="nama_rekening" id="nama_rekening" placeholder="Nama Rekening">
<input name="no_rekening" id="no_rekening" placeholder="No Rekening">
<textarea name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>

<!-- ✅ INPUT YANG DIKEMBALIKAN -->
<label>Upload KTP</label>
<input type="file" name="ktp_file">

<label>Upload Kartu Tani</label>
<input type="file" name="kartu_tani_file">

<button class="btn btn-add" id="btnSubmit"></button>
<button type="button" onclick="closeModal()">Batal</button>

</form>
</div>
</div>
<!-- ================= MODAL DETAIL ================= -->
<div class="modal" id="modalDetail">
<div class="modal-content">
<h3>Detail Mitra</h3>

<table style="width:100%;font-size:14px">
<tr><td><b>Nama</b></td><td id="d_nama"></td></tr>
<tr><td><b>Alamat</b></td><td id="d_alamat"></td></tr>
<tr><td><b>No KTP</b></td><td id="d_ktp"></td></tr>
<tr><td><b>No Kartu Tani</b></td><td id="d_kartu_tani"></td></tr>
<tr><td><b>Bank</b></td><td id="d_bank"></td></tr>
<tr><td><b>Nama Rekening</b></td><td id="d_nama_rek"></td></tr>
<tr><td><b>No Rekening</b></td><td id="d_no_rek"></td></tr>
<tr><td><b>Keterangan</b></td><td id="d_ket"></td></tr>
</table>

<button type="button" onclick="closeDetail()">Tutup</button>
</div>
</div>

<?php include "partials/footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
/* =========================
   SELECT2 BANK
========================= */
$(document).ready(function () {
    $('#bank').select2({
        width: '100%',
        placeholder: 'Pilih Bank'
    });
});

/* =========================
   MODAL TAMBAH
========================= */
function openTambah(){
    document.getElementById('modal').style.display = 'flex';
    document.getElementById('modalTitle').innerText = 'Tambah Mitra';

    document.getElementById('form').reset();

    document.getElementById('btnSubmit').innerText = 'Simpan';
    document.getElementById('btnSubmit').name = 'tambah';

    document.getElementById('id').value = '';
    document.getElementById('ktp_lama').value = '';
    document.getElementById('kartu_tani_lama').value = '';

    $('#bank').val('').trigger('change');
}

/* =========================
   TUTUP MODAL TAMBAH / EDIT
========================= */
function closeModal(){
    document.getElementById('modal').style.display = 'none';
}

/* =========================
   MODAL EDIT
========================= */
function editData(data){
    document.getElementById('modal').style.display = 'flex';
    document.getElementById('modalTitle').innerText = 'Edit Mitra';

    document.getElementById('btnSubmit').innerText = 'Update';
    document.getElementById('btnSubmit').name = 'edit';

    document.getElementById('id').value = data.id_mitra;
    document.getElementById('nama_mitra').value = data.nama_mitra;
    document.getElementById('alamat').value = data.alamat;
    document.getElementById('no_ktp').value = data.no_ktp;
    document.getElementById('no_kartu_tani').value = data.no_kartu_tani;
    document.getElementById('nama_rekening').value = data.nama_rekening;
    document.getElementById('no_rekening').value = data.no_rekening;
    document.getElementById('keterangan').value = data.keterangan;

    document.getElementById('ktp_lama').value = data.ktp_file;
    document.getElementById('kartu_tani_lama').value = data.kartu_tani_file;

    $('#bank').val(data.bank).trigger('change');
}

/* =========================
   MODAL DETAIL
========================= */
function detail(data){
    document.getElementById('modalDetail').style.display = 'flex';

    document.getElementById('d_nama').innerText = data.nama_mitra || '-';
    document.getElementById('d_alamat').innerText = data.alamat || '-';
    document.getElementById('d_ktp').innerText = data.no_ktp || '-';
    document.getElementById('d_kartu_tani').innerText = data.no_kartu_tani || '-';
    document.getElementById('d_bank').innerText = data.bank || '-';
    document.getElementById('d_nama_rek').innerText = data.nama_rekening || '-';
    document.getElementById('d_no_rek').innerText = data.no_rekening || '-';
    document.getElementById('d_ket').innerText = data.keterangan || '-';
}

function closeDetail(){
    document.getElementById('modalDetail').style.display = 'none';
}

/* =========================
   SEARCH SERVER-SIDE (DATABASE)
   - Cari ke semua data
   - Reset ke page 1
   - Nomor TIDAK RUSAK
========================= */
const searchInput = document.querySelector('input[name="search"]');

if (searchInput) {
    let delayTimer;

    searchInput.addEventListener('keyup', function () {
        clearTimeout(delayTimer);

        delayTimer = setTimeout(() => {
            const form = this.form;

            // pastikan selalu ke halaman 1 saat search
            let pageInput = form.querySelector('input[name="page"]');
            if (!pageInput) {
                pageInput = document.createElement('input');
                pageInput.type = 'hidden';
                pageInput.name = 'page';
                form.appendChild(pageInput);
            }

            pageInput.value = 1;
            form.submit();
        }, 500); // delay 0.5 detik
    });
}
</script>
