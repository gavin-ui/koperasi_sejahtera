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
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/" . $gambar);
    }

    // TAMBAH
    if ($aksi === 'tambah') {
        $stmt = $pdo->prepare("INSERT INTO tb_varietas 
            (kode, komoditas_id, nama_varietas, keterangan, gambar)
            VALUES (?,?,?,?,?)");
        $stmt->execute([$kode,$komoditas,$nama,$ket,$gambar]);
    }

    // EDIT
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

    // HAPUS
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

if (!empty($keyword)) {
    $sql .= " WHERE 
        v.kode LIKE :kw OR 
        v.nama_varietas LIKE :kw OR 
        k.nama_komoditas LIKE :kw";
}

$sql .= " ORDER BY v.id DESC LIMIT :s, :l";

$data = $pdo->prepare($sql);

if (!empty($keyword)) {
    $data->bindValue(':kw', "%$keyword%", PDO::PARAM_STR);
}

$data->bindValue(':s', $start, PDO::PARAM_INT);
$data->bindValue(':l', $limit, PDO::PARAM_INT);
$data->execute();

if (!empty($keyword)) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_varietas v 
                            JOIN tb_komoditas k ON v.komoditas_id = k.id
                            WHERE v.kode LIKE :kw 
                            OR v.nama_varietas LIKE :kw 
                            OR k.nama_komoditas LIKE :kw");
    $stmt->bindValue(':kw', "%$keyword%");
    $stmt->execute();
    $total = $stmt->fetchColumn();
} else {
    $total = $pdo->query("SELECT COUNT(*) FROM tb_varietas")->fetchColumn();
}
$pages = ceil($total / $limit);
$komoditas = $pdo->query("SELECT * FROM tb_komoditas")->fetchAll();
?>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>
html, body {
    height: 100%;
    margin: 0;
}
body {
    display: flex;
    flex-direction: column;
    background: #f5f7fb;
}
.page-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.content {
    flex: 1;
    padding: 30px;
}
.card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
}
footer {
    margin-top: auto;
}
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.4);
    display: none;
    align-items: center;
    justify-content: center;
}
.modal-box {
    background: #fff;
    padding: 20px;
    width: 500px;
    border-radius: 10px;
}
</style>

<div class="page-wrapper">
<div class="content">
<h4>Varietas</h4>

<div style="display:flex;justify-content:space-between;margin-bottom:15px">
    <form method="GET" style="display:flex; gap:10px;">
        <input 
            type="text" 
            name="keyword" 
            class="form-control" 
            placeholder="Cari..." 
            value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>"
        >
        <button class="btn btn-primary" type="submit">Cari</button>
    </form>
    <button class="btn btn-primary" onclick="openTambah()">+ Tambah</button>
</div>

<div class="card">
<table class="table table-bordered">
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
<?php foreach($data as $i => $d): ?>
<tr>
    <td><?= ($page - 1) * $limit + $i + 1 ?></td>
    <td><?= $d['kode'] ?></td>
    <td><?= $d['nama_komoditas'] ?></td>
    <td><?= $d['nama_varietas'] ?></td>
    <td>
        <button class="btn btn-info btn-sm" onclick='openDetail(<?= json_encode($d) ?>)'>👁</button>
        <button class="btn btn-warning btn-sm" onclick='editData(<?= json_encode($d) ?>)'>✏</button>
        <button class="btn btn-danger btn-sm" onclick="hapusData(<?= $d['id'] ?>)">🗑</button>
    </td>
</tr>
<?php endforeach ?>
</tbody>
</table>
</div>

<!-- PAGINATION -->
<div style="margin-top:15px;">
<?php for($i=1;$i<=$pages;$i++): ?>
    <a href="?page=<?= $i ?>" class="btn <?= $i==$page?'btn-primary':'btn-light' ?>"><?= $i ?></a>
<?php endfor ?>
</div>

<!-- MODAL FORM -->
<div class="modal" id="modalForm">
<div class="modal-box">
<h5>Form Varietas</h5>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="aksi" id="aksi">
<input type="hidden" name="id" id="id">

<input class="form-control mb-2" name="kode" id="kode" placeholder="Kode" required>
<select class="form-control mb-2" name="komoditas_id" id="komoditas">
<?php foreach($komoditas as $k): ?>
<option value="<?= $k['id'] ?>"><?= $k['nama_komoditas'] ?></option>
<?php endforeach ?>
</select>

<input class="form-control mb-2" name="nama_varietas" id="nama" placeholder="Nama Varietas">
<textarea class="form-control mb-2" name="keterangan" id="ket"></textarea>

<input type="file" name="gambar" class="form-control mb-2">

<button class="btn btn-primary">Simpan</button>
<button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
</form>
</div>
</div>

<!-- MODAL DETAIL -->
<div class="modal" id="modalDetail">
<div class="modal-box">
<h5>Detail Varietas</h5>
<div style="text-align:center;margin-bottom:15px;">
<img id="detail_gambar" style="max-width:200px;border-radius:8px;display:none;">
</div>
<p><b>Kode:</b> <span id="detail_kode"></span></p>
<p><b>Komoditas:</b> <span id="detail_komoditas"></span></p>
<p><b>Varietas:</b> <span id="detail_nama"></span></p>
<p><b>Keterangan:</b></p>
<p id="detail_ket"></p>
<button class="btn btn-secondary" onclick="closeModal()">Tutup</button>
</div>
</div>
</div>

<script>
function openTambah(){
    document.getElementById('modalForm').style.display='flex';
    document.getElementById('aksi').value='tambah';
    document.querySelector('#modalForm form').reset();
}

function editData(d){
    document.getElementById('modalForm').style.display='flex';
    document.getElementById('aksi').value='edit';
    document.getElementById('id').value=d.id;
    document.getElementById('kode').value=d.kode;
    document.getElementById('nama').value=d.nama_varietas;
    document.getElementById('ket').value=d.keterangan;
    document.getElementById('komoditas').value=d.komoditas_id;
}

function openDetail(d){
    document.getElementById('detail_kode').innerText = d.kode;
    document.getElementById('detail_komoditas').innerText = d.nama_komoditas;
    document.getElementById('detail_nama').innerText = d.nama_varietas;
    document.getElementById('detail_ket').innerText = d.keterangan;
    if(d.gambar){
        document.getElementById('detail_gambar').src = 'uploads/' + d.gambar;
        document.getElementById('detail_gambar').style.display = 'block';
    }
    document.getElementById('modalDetail').style.display='flex';
}

function closeModal(){
    document.querySelectorAll('.modal').forEach(m=>m.style.display='none');
}

function hapusData(id){
    if(confirm('Yakin hapus data ini?')){
        const f = document.createElement('form');
        f.method = 'POST';
        f.innerHTML = `<input type="hidden" name="aksi" value="hapus">
                       <input type="hidden" name="id" value="${id}">`;
        document.body.appendChild(f);
        f.submit();
    }
}
</script>

<?php include "partials/footer.php"; ?>
