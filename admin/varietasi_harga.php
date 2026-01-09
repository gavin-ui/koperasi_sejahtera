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

        <form method="POST" class="modal-form">
            <input type="hidden" name="aksi" id="aksi">
            <input type="hidden" name="id" id="id">

            <!-- PERUSAHAAN -->
            <div class="form-group">
                <label>Perusahaan</label>
                <select name="perusahaan_id" id="perusahaan" required>
                    <?php foreach($perusahaan as $p): ?>
                    <option value="<?= $p['id_perusahaan'] ?>">
                        <?= $p['nama_perusahaan'] ?>
                    </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- VARIETAS -->
            <div class="form-group">
                <label>Varietas</label>
                <select name="varietas_id" id="varietas" required>
                    <?php foreach($varietas as $v): ?>
                    <option value="<?= $v['id'] ?>">
                        <?= $v['nama_varietas'] ?>
                    </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- HARGA -->
            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" id="harga" placeholder="Masukkan harga" required>
            </div>

            <!-- ACTION -->
            <div class="modal-footer">
                <button type="button" class="btn btn-light" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DETAIL -->
<!-- MODAL DETAIL -->
<div class="modal" id="modalDetail">
    <div class="modal-box">
        <h3>Detail Varietas</h3>

        <div class="detail-list">
            <div class="detail-item">
                <span class="detail-label">Perusahaan</span>
                <span class="detail-value" id="d_perusahaan"></span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Varietas</span>
                <span class="detail-value" id="d_varietas"></span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Harga</span>
                <span class="detail-value price" id="d_harga"></span>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-light" onclick="closeDetail()">Tutup</button>
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

<style> 
/* ===============================
   BASE
================================ */
body{
  margin:0;
  font-family: "Inter", "Segoe UI", sans-serif;
  background:linear-gradient(180deg,#f4f7fb,#eef2f7);
}

/* ===============================
   SIDEBAR
================================ */
.sidebar{
  position:fixed;
  inset:0 auto 0 0;
  width:260px;
  background:#020617;
  z-index:1000;
}

/* ===============================
   MAIN WRAPPER
================================ */
.main-wrapper{
  margin-left:260px;
  min-height:100vh;
  display:flex;
  flex-direction:column;
}

/* ===============================
   CONTENT AREA
================================ */
.content-area{
  flex:1;
  padding:100px 36px 40px;
}

@media(max-width:991px){
  .main-wrapper{
    margin-left:0;
  }
  .content-area{
    padding:90px 18px 30px;
  }
}

/* ===============================
   CARD CONTAINER
================================ */
.card-admin{
  background:#ffffff;
  border-radius:28px;
  padding:32px;
  box-shadow:0 25px 55px rgba(15,23,42,.08);
}

/* ===============================
   PAGE HEADER
================================ */
h2{
  margin:0;
  font-size:30px;
  font-weight:800;
  color:#020617;
  letter-spacing:.3px;
  text-align:center;
}

/* ===============================
   TOP BAR
================================ */
.top-bar{
  margin:28px 0 26px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  flex-wrap:wrap;
  gap:14px;
}

/* ===============================
   BUTTON
================================ */
.btn{
  padding:12px 26px;
  border:none;
  border-radius:999px;
  font-weight:700;
  cursor:pointer;
  transition:.3s;
}

.btn-primary{
  background:linear-gradient(135deg,#22c55e,#16a34a);
  color:#fff;
  box-shadow:0 10px 24px rgba(34,197,94,.4);
}

.btn-primary:hover{
  transform:translateY(-2px);
  box-shadow:0 16px 35px rgba(34,197,94,.45);
}

.btn-light{
  background:#f1f5f9;
  color:#020617;
}

/* ===============================
   TABLE PREMIUM
================================ */
.table-responsive{
  width:100%;
  overflow:auto;
}

table{
  width:100%;
  border-collapse:separate;
  border-spacing:0 14px;
  text-align:center;
}

thead th{
  font-size:12px;
  font-weight:800;
  letter-spacing:1px;
  text-transform:uppercase;
  color:#64748b;
  padding:10px 14px;
}

tbody tr{
  background:#fff;
  border-radius:20px;
  box-shadow:0 8px 22px rgba(15,23,42,.06);
  transition:.3s;
}

tbody tr:hover{
  transform:translateY(-3px);
  box-shadow:0 16px 40px rgba(15,23,42,.12);
}

tbody td{
  padding:16px 18px;
  font-size:14px;
  color:#020617;
  border:none;
  vertical-align:middle;
}

/* ===============================
   ACTION BUTTON
================================ */
.action-btn{
  border:none;
  width:38px;
  height:38px;
  border-radius:12px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  background:#f1f5f9;
  font-size:16px;
  cursor:pointer;
  transition:.25s;
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
  background:rgba(2,6,23,.75); /* lebih gelap */
  backdrop-filter: blur(4px);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:99999;
}
.modal-box{
  background:#ffffff;
  width:100%;
  max-width:520px;
  border-radius:26px;
  padding:28px;
  box-shadow:
    0 40px 80px rgba(15,23,42,.45);
  animation:modalZoom .35s ease;
}
.modal-premium{
  background:#ffffff;
  width:100%;
  max-width:520px;
  border-radius:28px;
  overflow:hidden;
  box-shadow:0 30px 70px rgba(15,23,42,.35);
}
.modal-box h3{
  margin:0 0 18px;
  font-size:22px;
  font-weight:800;
  color:#020617;
  text-align:center;
}


/* ===============================
   MODAL HEADER
================================ */
.modal-header{
  background:linear-gradient(135deg,#22c55e,#16a34a);
  padding:20px 28px;
  color:#fff;
}

.modal-header h3{
  margin:0;
  font-weight:800;
  letter-spacing:.4px;
}

/* ===============================
   MODAL BODY
================================ */
.modal-body{
  padding:28px;
}

.modal-body label{
  font-size:13px;
  font-weight:700;
  color:#334155;
  margin-bottom:6px;
}

/* ===============================
   INPUT
================================ */
.modal-body input,
.modal-body select{
  width:100%;
  border-radius:16px;
  padding:12px 16px;
  border:1px solid #e2e8f0;
  font-size:14px;
  margin-bottom:16px;
  transition:.3s;
}

.modal-body input:focus,
.modal-body select:focus{
  outline:none;
  border-color:#22c55e;
  box-shadow:0 0 0 4px rgba(34,197,94,.2);
}

/* ===============================
   MODAL FOOTER
================================ */
.modal-footer{
  display:flex;
  justify-content:center;
  gap:12px;
  padding:0 28px 26px;
}

/* ===============================
   FOOTER
================================ */
footer{
  background:#ffffff;
  border-top:1px solid #e2e8f0;
  padding:14px;
  text-align:center;
  font-size:13px;
  color:#64748b;
}

/* ===============================
   FORM GRID
================================ */
.modal-form{
  display:grid;
  gap:14px;
}

/* ===============================
   FORM GROUP
================================ */
.form-group{
  display:flex;
  flex-direction:column;
}

/* ===============================
   INPUT & SELECT PREMIUM
================================ */
.modal-body input,
.modal-body select,
.modal-body textarea{
  width:100%;
  border-radius:16px;
  padding:13px 16px;
  border:1px solid #e2e8f0;
  font-size:14px;
  background:#f8fafc;
  transition:.3s;
}

.modal-body textarea{
  resize:none;
  min-height:90px;
}

.modal-body input:focus,
.modal-body select:focus,
.modal-body textarea:focus{
  outline:none;
  border-color:#22c55e;
  background:#fff;
  box-shadow:0 0 0 4px rgba(34,197,94,.2);
}

.modal-form label{
  font-size:12px;
  font-weight:800;
  color:#475569;
  text-transform:uppercase;
  letter-spacing:.4px;
}

.modal-form input,
.modal-form select{
  width:100%;
  padding:13px 16px;
  border-radius:14px;
  border:1px solid #e2e8f0;
  background:#f8fafc;
  font-size:14px;
  transition:.3s;
}

.modal-form input:focus,
.modal-form select:focus{
  outline:none;
  background:#fff;
  border-color:#22c55e;
  box-shadow:0 0 0 4px rgba(34,197,94,.25);
}

/* ===============================
   MODAL DETAIL (READ ONLY)
================================ */
.modal-detail input,
.modal-detail textarea{
  background:#f1f5f9;
  border:1px dashed #cbd5e1;
  color:#334155;
}

.modal-detail input:focus,
.modal-detail textarea:focus{
  box-shadow:none;
  border-color:#cbd5e1;
}

/* ===============================
   MODAL FOOTER BUTTON
================================ */
.modal-footer{
  display:flex;
  justify-content:center;
  gap:14px;
  margin-top:20px;
}

.modal-footer .btn{
  min-width:130px;
}

/* ===============================
   MODAL ANIMATION
================================ */
.modal{
  animation:fadeIn .3s ease;
}

.modal-premium{
  animation:scaleUp .35s ease;
}

@keyframes fadeIn{
  from{opacity:0}
  to{opacity:1}
}

@keyframes scaleUp{
  from{
    transform:scale(.92);
    opacity:0;
  }
  to{
    transform:scale(1);
    opacity:1;
  }
}
@keyframes modalZoom{
  from{
    transform:scale(.92);
    opacity:0;
  }
  to{
    transform:scale(1);
    opacity:1;
  }
}

/* ===============================
   MOBILE FORM
================================ */
@media(max-width:576px){
  .form-grid{
    grid-template-columns:1fr;
  }
}
</style>