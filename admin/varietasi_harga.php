<?php
include "../koneksi.php";

/* ================= PROSES CRUD ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $aksi = $_POST['aksi'];
    $id   = $_POST['id'] ?? null;
    $perusahaan = $_POST['perusahaan_id'];
    $varietas   = $_POST['varietas_id'];
    $harga      = $_POST['harga'];

    if ($aksi == 'tambah') {
        $stmt = $pdo->prepare("INSERT INTO tb_varietas_harga (perusahaan_id, varietas_id, harga) VALUES (?,?,?)");
        $stmt->execute([$perusahaan, $varietas, $harga]);
    }

    if ($aksi == 'edit') {
        $stmt = $pdo->prepare("UPDATE tb_varietas_harga SET perusahaan_id=?, varietas_id=?, harga=? WHERE id=?");
        $stmt->execute([$perusahaan, $varietas, $harga, $id]);
    }

    if ($aksi == 'hapus') {
        $stmt = $pdo->prepare("DELETE FROM tb_varietas_harga WHERE id=?");
        $stmt->execute([$id]);
    }

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* ================= DATA ================= */
$limit = 10;
$page  = $_GET['page'] ?? 1;
$start = ($page - 1) * $limit;

$data = $pdo->prepare("
    SELECT 
        vh.*, 
        p.nama_perusahaan, 
        v.nama_varietas,
        v.kode
    FROM tb_varietas_harga vh
    JOIN perusahaan p ON vh.perusahaan_id = p.id_perusahaan
    JOIN tb_varietas v ON vh.varietas_id = v.id
    ORDER BY vh.id DESC
    LIMIT :s, :l
");
$data->bindValue(':s', $start, PDO::PARAM_INT);
$data->bindValue(':l', $limit, PDO::PARAM_INT);
$data->execute();

$total = $pdo->query("SELECT COUNT(*) FROM tb_varietas_harga")->fetchColumn();
$pages = ceil($total / $limit);

$perusahaan = $pdo->query("SELECT * FROM perusahaan")->fetchAll();
$varietas   = $pdo->query("SELECT * FROM tb_varietas")->fetchAll();
?>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style> 
    /* === FIX LAYOUT UTAMA === */
body {
    margin: 0;
    background: #f5f6fa;
}

/* Sidebar tetap di kiri */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 260px;
    height: 100vh;
    z-index: 1000;
}

/* Konten utama */
.main-wrapper {
    margin-left: 260px;  /* HARUS SAMA dengan lebar sidebar */
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Isi halaman */
.content-area {
    flex: 1;
    padding: 25px;
}

/* Footer selalu di bawah */
footer {
    margin-top: auto;
    background: #fff;
    padding: 15px;
    text-align: center;
    border-top: 1px solid #ddd;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

th, td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

/* Modal */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.4);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.modal-box {
    background: #fff;
    padding: 20px;
    width: 450px;
    border-radius: 8px;
}

/* === CENTER SEMUA KONTEN === */
.main-wrapper {
    margin-left: 260px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* konten utama */
.content,
.content-area {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

/* tabel */
table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
}

th, td {
    text-align: center;
    vertical-align: middle;
}

/* tombol di tengah */
.btn {
    margin: 2px;
}

/* header judul */
h2 {
    text-align: center;
}

/* modal center */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.modal-box {
    background: #fff;
    width: 450px;
    padding: 20px;
    border-radius: 10px;
    text-align: left;
}

/* form rapi */
.modal-box input,
.modal-box select {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
}
</style>

<!-- ================= CONTENT WRAPPER ================= -->
<div style="
    margin-left:260px;   /* SESUAIKAN dengan lebar sidebar kamu */
    min-height:100vh;
    display:flex;
    flex-direction:column;
    background:#f5f6fa;
">

    <div style="flex:1;padding:25px">

        <h2>Master Varietas Harga</h2>

<div style="
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin:15px 0;
">
    <!-- SEARCH -->
    <input 
        type="text" 
        id="searchInput"
        placeholder="Cari data..."
        onkeyup="searchTable()"
        style="
            padding:8px 12px;
            width:250px;
            border:1px solid #ccc;
            border-radius:5px;
        "
    >

    <!-- TOMBOL TAMBAH -->
    <button class="btn btn-primary" onclick="openForm()">
        + Tambah
    </button>
</div>

        <table style="width:100%;border-collapse:collapse;background:#fff;margin-top:15px">
            <tr>
                <th>No</th>
                <th>Perusahaan</th>
                <th>Kode</th>
                <th>Varietas</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>


            <?php foreach($data as $i => $d): ?>
            <tr>
                <td><?= ($page-1)*$limit + $i + 1 ?></td>
                <td><?= $d['nama_perusahaan'] ?></td>
                <td><?= $d['kode'] ?></td>
                <td><?= $d['nama_varietas'] ?></td>
                <td>Rp <?= number_format($d['harga']) ?></td>
                <td>
                    <button class="btn btn-info" onclick='detailData(<?= json_encode($d) ?>)'>Detail</button>
                    <button class="btn btn-warning" onclick='editData(<?= json_encode($d) ?>)'>Edit</button>
                    <button class="btn btn-danger" onclick="hapusData(<?= $d['id'] ?>)">Hapus</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- PAGINATION -->
        <div style="margin-top:15px">
        <?php for($i=1;$i<=$pages;$i++): ?>
            <a href="?page=<?= $i ?>" class="btn <?= $i==$page?'btn-primary':'btn-secondary' ?>"><?= $i ?></a>
        <?php endfor ?>
        </div>

    </div>

<?php include "partials/footer.php"; ?>
</div>
<!-- ================= END CONTENT ================= -->

<!-- MODAL TAMBAH / EDIT -->
<div class="modal" id="modalForm">
    <div class="modal-box">
        <h3 id="modalTitle">Tambah Data</h3>

        <form method="POST">
            <input type="hidden" name="aksi" id="aksi">
            <input type="hidden" name="id" id="id">

            <label>Perusahaan</label>
            <select name="perusahaan_id" id="perusahaan" required>
                <?php foreach($perusahaan as $p): ?>
                <option value="<?= $p['id_perusahaan'] ?>"><?= $p['nama_perusahaan'] ?></option>
                <?php endforeach ?>
            </select>

            <label>Varietas</label>
            <select name="varietas_id" id="varietas" required>
                <?php foreach($varietas as $v): ?>
                <option value="<?= $v['id'] ?>"><?= $v['nama_varietas'] ?></option>
                <?php endforeach ?>
            </select>

            <label>Harga</label>
            <input type="number" name="harga" id="harga" required>

            <div style="margin-top:15px; text-align:right">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DETAIL -->
<div class="modal" id="modalDetail">
    <div class="modal-box">
        <h3>Detail Varietas</h3>

        <p><b>Perusahaan:</b> <span id="d_perusahaan"></span></p>
        <p><b>Varietas:</b> <span id="d_varietas"></span></p>
        <p><b>Harga:</b> <span id="d_harga"></span></p>

        <div style="margin-top:15px;text-align:right;">
            <button class="btn btn-secondary" onclick="closeDetail()">Tutup</button>
        </div>
    </div>
</div>

<script>
function searchTable() {
    let input = document.getElementById("searchInput");
    let filter = input.value.toLowerCase();
    let rows = document.querySelectorAll("table tbody tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
}
</script>

<script>
function detailData(d){
    document.getElementById('d_perusahaan').innerText = d.nama_perusahaan;
    document.getElementById('d_varietas').innerText = d.nama_varietas;
    document.getElementById('d_harga').innerText = 'Rp ' + d.harga.toLocaleString();

    document.getElementById('modalDetail').style.display = 'flex';
}

function closeDetail(){
    document.getElementById('modalDetail').style.display = 'none';
}
</script>

<script>
function openForm(){
    modalForm.style.display='flex';
    aksi.value='tambah';
    modalTitle.innerText='Tambah Data';
}

function editData(d){
    modalForm.style.display='flex';
    aksi.value='edit';
    id.value=d.id;
    perusahaan.value=d.perusahaan_id;
    varietas.value=d.varietas_id;
    harga.value=d.harga;
    modalTitle.innerText='Edit Data';
}

function closeModal(){
    modalForm.style.display='none';
}

function hapusData(id){
    if(confirm('Yakin hapus data?')){
        const f=document.createElement('form');
        f.method='POST';
        f.innerHTML=`<input type="hidden" name="aksi" value="hapus">
                     <input type="hidden" name="id" value="${id}">`;
        document.body.appendChild(f);
        f.submit();
    }
}
</script>
