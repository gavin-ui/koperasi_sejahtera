<?php
include "../koneksi.php";

/* ================= SIMPAN DATA ================= */
if (isset($_POST['simpan'])) {

    $file = null;
    if (!empty($_FILES['file_hasil']['name'])) {
        if (!is_dir("../uploads/q2cp")) {
            mkdir("../uploads/q2cp", 0777, true);
        }
        $file = time().'_'.$_FILES['file_hasil']['name'];
        move_uploaded_file($_FILES['file_hasil']['tmp_name'], "../uploads/q2cp/".$file);
    }

    $stmt = $pdo->prepare("
        INSERT INTO tb_q2cp (
            no_surat, tanggal_pemeriksaan, id_p2, id_mitra, id_gapoktan,
            petugas_qc, kepala_unit, varietas, lokasi_pengambilan,
            jumlah_karung, berat_qc, harga_standar,
            kadar_air, kadar_hampa, chalky, kuning_rusak,
            final_potongan, nilai_bersih, keterangan, file_hasil
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $_POST['no_surat'],
        $_POST['tanggal'],
        $_POST['id_p2'],
        $_POST['id_mitra'],
        $_POST['id_gapoktan'],
        $_POST['petugas_qc'] ?? null,
        $_POST['kepala_unit'] ?? null,
        $_POST['varietas'],
        $_POST['lokasi_pengambilan'],
        $_POST['jumlah_karung'],
        $_POST['berat_qc'],
        $_POST['harga_standar'],
        $_POST['kadar_air'],
        $_POST['kadar_hampa'],
        $_POST['chalky'],
        $_POST['kuning_rusak'],
        $_POST['final_potongan'],
        $_POST['nilai_bersih'],
        $_POST['keterangan'],
        $file
    ]);

    echo "<script>alert('Data Q2CP berhasil disimpan');location='manajemen_q2cp.php';</script>";
    exit;
}

/* ================= DATA P2 ================= */
$p2 = $pdo->query("
    SELECT p.*, m.nama_mitra, g.nama_gapoktan
    FROM tb_p2 p
    JOIN mitra m ON p.id_mitra=m.id_mitra
    JOIN tb_gapoktan g ON p.id_gapoktan=g.id
    ORDER BY p.id_p2 DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Q2CP</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
/* ================= ROOT & BODY ================= */
html, body {
    height: 100%;
}

body {
    background: #f4f6f9;
    margin: 0;
    font-family: "Segoe UI", Roboto, Arial, sans-serif;
}

.main-content {
    margin-left: 260px;
    padding: 24px;
    padding-top: calc(64px + 24px);
}

/* ================= CARD ================= */
.card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,.05);
}

.card-body {
    padding: 20px;
}

/* ================= PAGE TITLE ================= */
h4 {
    font-weight: 600;
    color: #2c3e50;
}

/* ================= TABLE ================= */
.table {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
}

.table thead th {
    background: linear-gradient(135deg,#4e73df,#224abe);
    color: #fff;
    text-align: center;
    font-size: 13px;
    border: none;
    padding: 12px;
}

.table tbody td {
    padding: 12px;
    font-size: 13px;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #f1f4ff;
}

/* ================= BUTTON ================= */
.btn {
    border-radius: 8px;
    font-size: 13px;
    padding: 6px 14px;
}

.btn-primary {
    background: #4e73df;
    border: none;
}

.btn-secondary {
    border: none;
}

.btn:hover {
    opacity: .92;
}

/* ================= FORM ================= */
.form-control,
.form-select {
    border-radius: 10px;
    font-size: 13px;
    padding: 10px 12px;
}

.form-control:read-only {
    background-color: #f1f3f7;
}

label {
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 4px;
}

/* ================= MODAL ================= */
.modal-content {
    border-radius: 18px;
    border: none;
}

.modal-header {
    border-bottom: 1px solid #eee;
}

.modal-title,
.modal-header h5 {
    font-weight: 600;
    color: #2c3e50;
}

.modal-footer {
    border-top: 1px solid #eee;
}

.modal-body {
    max-height: calc(100vh - 180px);
    overflow-y: auto;
}

/* ================= UPLOAD ================= */
input[type="file"] {
    padding: 8px;
}

/* ================= INFO LINE ================= */
hr {
    border-top: 1px dashed #ddd;
    margin: 10px 0;
}

/* ================= FOOTER ================= */
.footer {
    margin-left: 260px;
    width: calc(100% - 260px);
    background: #fff;
    border-top: 1px solid #e5e5e5;
    padding: 12px 0;
    text-align: center;
    font-size: 13px;
    color: #666;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 991px) {

    .main-content {
        margin-left: 0;
        padding: 16px;
    }

    .footer {
        margin-left: 0;
        width: 100%;
    }

    .modal-dialog {
        margin: 0.75rem;
    }
}
</style>

</head>
<body>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<div class="main-content">

<div class="card">
<div class="card-body">

<div class="d-flex justify-content-between mb-3">
    <h4>Manajemen Q2CP</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        + Tambah
    </button>
</div>

<table class="table table-bordered">
<thead>
<tr>
    <th>No</th>
    <th>No Surat</th>
    <th>Tanggal</th>
    <th>Nama Mitra</th>
    <th>Nama Gapoktan</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
<tr>
    <td colspan="6" class="text-center">No data available</td>
</tr>
</tbody>
</table>

</div>
</div>

</div>

<!-- ================= MODAL TAMBAH Q2CP ================= -->
<div class="modal fade" id="modalTambah" tabindex="-1">
<div class="modal-dialog modal-xl modal-dialog-scrollable">
<form method="post" enctype="multipart/form-data">
<div class="modal-content">

<!-- HEADER -->
<div class="modal-header">
    <h5 class="modal-title">Tambah Data</h5>
</div>

<!-- BODY -->
<div class="modal-body">

<div class="alert alert-warning small mb-4">
    Informasi Varietas, Lokasi Pengambilan, Jumlah Karung, Berat QC, dan Harga Standar
    akan otomatis terisi setelah memilih <b>No. P2</b>
</div>

<div class="row g-3">

<!-- BARIS 1 -->
<div class="col-md-6">
<label>No. Surat</label>
<input class="form-control" name="no_surat"
       value="Q2CP-0001/<?= date('d/m/Y') ?>" readonly>
</div>

<div class="col-md-6">
<label>Petugas QC</label>
<select name="petugas_qc" class="form-select">
<option value="">Pilih Petugas QC</option>
</select>
</div>

<!-- BARIS 2 -->
<div class="col-md-6">
<label>Tanggal Pemeriksaan</label>
<input type="date" name="tanggal" class="form-control">
</div>

<div class="col-md-6">
<label>Kepala Unit Penyerapan</label>
<select name="kepala_unit" class="form-select">
<option value="">Pilih Kepala Unit Penyerapan</option>
</select>
</div>

<!-- BARIS 3 -->
<div class="col-md-4">
<label>Varietas</label>
<input id="varietas" name="varietas" class="form-control" readonly>
</div>

<div class="col-md-4">
<label>Lokasi Pengambilan</label>
<select id="lokasi" name="lokasi_pengambilan" class="form-select" readonly>
<option>Lokasi Pengambilan</option>
</select>
</div>

<div class="col-md-4">
<label>Gapoktan/Koperasi Tani</label>
<select name="id_gapoktan" class="form-select">
<option>Pilih Gapoktan</option>
</select>
</div>

<!-- BARIS 4 -->
<div class="col-md-4">
<label>Jumlah Karung</label>
<input id="karung" name="jumlah_karung" type="number" class="form-control" readonly>
</div>

<div class="col-md-4">
<label>Berat QC</label>
<input name="berat_qc" type="number" class="form-control" readonly>
</div>

<div class="col-md-4">
<label>Nama Mitra</label>
<select name="id_mitra" class="form-select">
<option>Pilih Mitra</option>
</select>
</div>

<!-- BARIS 5 -->
<div class="col-md-4">
<label>Harga Standar</label>
<div class="input-group">
<span class="input-group-text">Rp</span>
<input id="harga" name="harga_standar" class="form-control" readonly>
</div>
</div>

<div class="col-md-8">
<label>No. P2 <small class="text-muted">(Pilih nama mitra terlebih dahulu)</small></label>
<select name="id_p2" id="p2" class="form-select">
<option>Pilih P2</option>
</select>
</div>

<!-- ================= QC VISUAL ================= -->
<hr class="my-3">

<div class="col-md-3">
<label>Berbau</label>
<select name="berbau" class="form-select">
<option>Pilih Kondisi</option>
<option value="baik">Baik</option>
<option value="tidak">Tidak</option>
</select>
</div>

<div class="col-md-3">
<label>Panas</label>
<select name="panas" class="form-select">
<option>Pilih Kondisi</option>
<option value="baik">Baik</option>
<option value="tidak">Tidak</option>
</select>
</div>

<div class="col-md-3">
<label>Kecambah</label>
<select name="kecambah" class="form-select">
<option>Pilih Kondisi</option>
<option value="baik">Baik</option>
<option value="tidak">Tidak</option>
</select>
</div>

<div class="col-md-3">
<label>Kuning/Hitam</label>
<select name="kuning_hitam" class="form-select">
<option>Pilih Kondisi</option>
<option value="baik">Baik</option>
<option value="tidak">Tidak</option>
</select>
</div>

<!-- ================= PARAMETER QC ================= -->
<div class="col-md-6">
<label>Kadar Air</label>
<div class="input-group">
<input name="kadar_air" type="number" class="form-control potong" value="0">
<span class="input-group-text">%</span>
</div>
</div>
<div class="col-md-6 small text-muted d-flex align-items-end">
(20,0% standar bulog, Maks. 25,0%) &nbsp; <b>0%</b>
</div>

<div class="col-md-6">
<label>Kadar Hampa</label>
<div class="input-group">
<input name="kadar_hampa" type="number" class="form-control potong" value="0">
<span class="input-group-text">%</span>
</div>
</div>
<div class="col-md-6 small text-muted d-flex align-items-end">
(2,0% standar bulog, Maks. 4,0%) &nbsp; <b>0%</b>
</div>

<div class="col-md-6">
<label>Chalky/Butiran Kapur</label>
<div class="input-group">
<input name="chalky" type="number" class="form-control potong" value="0">
<span class="input-group-text">%</span>
</div>
</div>
<div class="col-md-6 small text-muted d-flex align-items-end">
(5,0% standar bulog, Maks. 7,0%) &nbsp; <b>0%</b>
</div>

<div class="col-md-6">
<label>Kuning/Rusak</label>
<div class="input-group">
<input name="kuning_rusak" type="number" class="form-control potong" value="0">
<span class="input-group-text">%</span>
</div>
</div>
<div class="col-md-6 small text-muted d-flex align-items-end">
(3,0% standar bulog, Maks. 4,0%) &nbsp; <b>0%</b>
</div>

<!-- ================= HASIL ================= -->
<div class="col-md-6">
<label>Final Potongan</label>
<div class="input-group">
<span class="input-group-text">Rp</span>
<input name="final_potongan" id="final_potongan" class="form-control" readonly>
</div>
</div>

<div class="col-md-6 small text-muted d-flex align-items-end">
(Rp. 6.9 per 1% potongan)
</div>

<div class="col-md-12">
<label>Nilai Bersih</label>
<div class="input-group">
<span class="input-group-text">Rp</span>
<input name="nilai_bersih" id="nilai_bersih" class="form-control" readonly>
</div>
</div>

<div class="col-md-12">
<label>Keterangan</label>
<textarea name="keterangan" class="form-control" placeholder="Type Here.."></textarea>
</div>

<div class="col-md-12">
<label>Upload Hasil Pengukuran</label>
<div class="input-group">
<input type="file" name="file_hasil" class="form-control">
<button type="button" class="btn btn-primary">
<i class="bi bi-upload"></i> Upload
</button>
</div>
</div>

</div>

</div>

<!-- FOOTER -->
<div class="modal-footer justify-content-center">
<button type="button" class="btn btn-light" data-bs-dismiss="modal">✖ Batal</button>
<button type="submit" name="simpan" class="btn btn-primary">💾 Simpan</button>
</div>

</div>
</form>
</div>
</div>

</div>
</form>
</div>
</div>


<script>
$('#p2').on('change', function(){
    let o = $(this).find(':selected');

    $('#varietas').val(o.data('varietas'));
    $('#lokasi').val(o.data('lokasi'));
    $('#karung').val(o.data('karung'));
    $('#harga').val(o.data('harga'));

    $('#id_mitra').val(o.data('mitra'));
    $('#id_gapoktan').val(o.data('gapoktan'));
});

$('.potong').on('input', function(){
    let total = 0;
    $('.potong').each(function(){
        total += parseFloat($(this).val() || 0);
    });

    let potongan = total * 6900;
    $('#final_potongan').val(potongan);

    let harga = parseFloat($('#harga').val() || 0);
    $('#nilai_bersih').val(harga - potongan);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include "partials/footer.php"; ?>
</body>
</html>
