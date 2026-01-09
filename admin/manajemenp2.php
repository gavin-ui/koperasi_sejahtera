<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../koneksi.php"; // menghasilkan $pdo

/* ===================== TAMBAH ===================== */
if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $stmt = $pdo->prepare("
        INSERT INTO tb_p2
        (no_surat, tanggal, id_mitra, id_gapoktan, varietas, lokasi_pengambilan, jumlah_karung, est_karung, est_berat)
        VALUES (?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $_POST['no_surat'],
        $_POST['tanggal'],
        $_POST['id_mitra'],
        $_POST['id_gapoktan'],
        $_POST['varietas'],
        $_POST['lokasi_pengambilan'],
        $_POST['jumlah_karung'],
        $_POST['est_karung'],
        $_POST['est_berat']
    ]);
    echo "<script>alert('Data berhasil ditambahkan');location='manajemenp2.php';</script>";
    exit;
}

/* ===================== EDIT ===================== */
if (isset($_POST['aksi']) && $_POST['aksi'] === 'edit') {
    $stmt = $pdo->prepare("
        UPDATE tb_p2 SET
            tanggal=?,
            id_mitra=?,
            id_gapoktan=?,
            varietas=?,
            lokasi_pengambilan=?,
            jumlah_karung=?,
            est_karung=?,
            est_berat=?
        WHERE id_p2=?
    ");
    $stmt->execute([
        $_POST['tanggal'],
        $_POST['id_mitra'],
        $_POST['id_gapoktan'],
        $_POST['varietas'],
        $_POST['lokasi_pengambilan'],
        $_POST['jumlah_karung'],
        $_POST['est_karung'],
        $_POST['est_berat'],
        $_POST['id_p2']
    ]);
    echo "<script>alert('Data berhasil diubah');location='manajemenp2.php';</script>";
    exit;
}

/* ===================== HAPUS ===================== */
if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM tb_p2 WHERE id_p2=?");
    $stmt->execute([$_GET['hapus']]);
    echo "<script>alert('Data berhasil dihapus');location='manajemenp2.php';</script>";
    exit;
}

/* ===================== DATA LIST ===================== */
$stmt = $pdo->prepare("
    SELECT p.*, m.nama_mitra, g.nama_gapoktan
    FROM tb_p2 p
    JOIN mitra m ON p.id_mitra = m.id_mitra
    JOIN tb_gapoktan g ON p.id_gapoktan = g.id
    ORDER BY p.id_p2 DESC
");
$stmt->execute();
$dataP2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===================== DATA MASTER ===================== */
$mitra = $pdo->query("SELECT * FROM mitra ORDER BY nama_mitra ASC")->fetchAll(PDO::FETCH_ASSOC);
$gapoktan = $pdo->query("SELECT * FROM tb_gapoktan ORDER BY nama_gapoktan ASC")->fetchAll(PDO::FETCH_ASSOC);

include "partials/header.php";
include "partials/sidebar.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen P2</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<style>
/* ===== ROOT & BODY (NO SCROLL PAGE) ===== */
html, body {
    height: 100%;
    overflow: hidden; /* ⛔ matikan scroll halaman */
}

body {
    background: #f4f6f9;
    margin: 0;
    font-family: "Segoe UI", Roboto, Arial, sans-serif;
}

/* ===== MAIN CONTENT ===== */
.main-content {
    margin-left: 260px;            /* lebar sidebar */
    padding: 24px;
    height: calc(100vh - 60px);    /* pas 1 layar */
    overflow: hidden;              /* ⛔ no scroll di konten utama */
}

/* ===== CARD ===== */
.card {
    height: 100%;
    border: none;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
}

/* ===== CARD BODY ===== */
.card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* ===== PAGE TITLE ===== */
.page-title {
    font-weight: 600;
    color: #2c3e50;
}

/* ===== TABLE WRAPPER (SCROLL DALAM TABEL SAJA) ===== */
.table-wrapper {
    background: #ffffff;
    border-radius: 14px;
    padding: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,.06);
    flex: 1;
    overflow: auto;  /* ✅ scroll hanya di tabel */
}

/* ===== TABLE ===== */
.table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: linear-gradient(135deg,#4e73df,#224abe);
    color: #fff;
    text-align: center;
    border: none;
    padding: 14px;
    font-size: 14px;
}

.table tbody td {
    padding: 12px;
    font-size: 14px;
    vertical-align: middle;
    border-top: 1px solid #eee;
}

.table tbody tr {
    transition: all .15s ease;
}

.table tbody tr:hover {
    background: #f1f4ff;
}

/* ===== BUTTON ===== */
.btn {
    border-radius: 8px;
    font-size: 13px;
    padding: 6px 12px;
}

.btn-info    { background:#36b9cc; border:none }
.btn-warning { background:#f6c23e; border:none }
.btn-danger  { background:#e74a3b; border:none }
.btn-primary { background:#4e73df; border:none }

.btn:hover {
    opacity: .9;
}

/* ===== MODAL (TETAP BOLEH SCROLL) ===== */
.modal-body {
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    html, body {
        overflow: auto; /* mobile tetap boleh scroll */
    }

    .main-content {
        margin-left: 0;
        height: auto;
        overflow: visible;
        padding: 16px;
    }

    .card {
        height: auto;
    }

    .table-wrapper {
        overflow: auto;
    }
}
body {
    display: flex;
    flex-direction: column;
}

/* area konten utama */
.main-content {
    flex: 1;
    margin-left: 260px;
    padding: 24px;
    overflow: hidden;
}

.footer {
    margin-left: 260px;                  /* sejajar konten */
    width: calc(100% - 260px);           /* ⬅ ini kuncinya */
    background: #fff;
    border-top: 1px solid #e5e5e5;
    padding: 12px 0;
    
    display: flex;
    align-items: center;
    justify-content: center;              /* 🎯 CENTER REAL */
    
    font-size: 13px;
    color: #666;
}
/* ===== FIX MODAL AGAR TIDAK NABRAK SIDEBAR ===== */
.modal {
    padding-left: 260px !important; /* dorong modal ke kanan */
}

.modal-dialog {
    margin: 1.75rem auto;
}

/* ===== RESPONSIVE (MOBILE) ===== */
@media (max-width: 991px) {
    .modal {
        padding-left: 0 !important;
    }
}

</style>

</head>
<body>

<!-- MAIN CONTENT (ANTI TABRAK SIDEBAR) -->
<div class="main-content">

  <div class="card shadow-sm">
    <div class="card-body">

      <!-- HEADER -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">Manajemen P2</h4>
        <button class="btn btn-primary px-4"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah">
          + Tambah
        </button>
      </div>

      <!-- TABLE WRAPPER -->
      <div class="table-responsive table-wrapper">
        <table class="table table-hover align-middle text-nowrap">

          <thead>
            <tr>
              <th>No</th>
              <th>No Surat</th>
              <th>Tanggal</th>
              <th>Nama Mitra</th>
              <th>Nama Gapoktan</th>
              <th class="text-center" width="180">Aksi</th>
            </tr>
          </thead>

          <tbody>
          <?php $no = 1; foreach ($dataP2 as $d): ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td><?= htmlspecialchars($d['no_surat']) ?></td>
              <td><?= date('d/m/Y', strtotime($d['tanggal'])) ?></td>
              <td><?= htmlspecialchars($d['nama_mitra']) ?></td>
              <td><?= htmlspecialchars($d['nama_gapoktan']) ?></td>
              <td class="text-center">

                <button class="btn btn-info btn-sm btn-detail"
                        data-json='<?= json_encode($d, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                  Detail
                </button>

                <button class="btn btn-warning btn-sm btn-edit"
                        data-json='<?= json_encode($d, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                  Edit
                </button>

                <a href="?hapus=<?= $d['id_p2'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                  Hapus
                </a>

              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>

        </table>
      </div>

    </div>
  </div>

</div>

<!-- ================= MODAL TAMBAH ================= -->
<div class="modal fade" id="modalTambah">
<div class="modal-dialog modal-lg">
<form method="post">
<input type="hidden" name="aksi" value="tambah">
<div class="modal-content">
<div class="modal-header"><h5>Tambah Data</h5></div>
<div class="modal-body row g-2">

<div class="col-md-12">
<label>No Surat</label>
<input class="form-control" name="no_surat"
value="P2-<?= rand(1000,9999) ?>/<?= date('d/m/Y') ?>" readonly>
</div>

<div class="col-md-6">
<label>Tanggal</label>
<input type="date" name="tanggal" class="form-control" required>
</div>

<div class="col-md-6">
<label>Gapoktan</label>
<select name="id_gapoktan" id="gapoktan" class="form-control" required>
<option value="">Pilih Gapoktan</option>
<?php foreach ($gapoktan as $g): ?>
<option value="<?= $g['id'] ?>" data-ketua="<?= htmlspecialchars($g['nama_ketua']) ?>">
<?= htmlspecialchars($g['nama_gapoktan']) ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-6">
<label>Nama Ketua</label>
<input id="nama_ketua" class="form-control" readonly>
</div>

<div class="col-md-6">
<label>Nama Mitra</label>
<select name="id_mitra" id="mitra" class="form-control" required>
<option value="">Pilih Mitra</option>
<?php foreach ($mitra as $m): ?>
<option value="<?= $m['id_mitra'] ?>"
 data-alamat="<?= htmlspecialchars($m['alamat']) ?>"
 data-ktp="<?= htmlspecialchars($m['no_ktp']) ?>"
 data-kartu="<?= htmlspecialchars($m['no_kartu_tani']) ?>"
 data-ktpfile="<?= htmlspecialchars($m['ktp_file']) ?>"
 data-kartufile="<?= htmlspecialchars($m['kartu_tani_file']) ?>">
 <?= htmlspecialchars($m['nama_mitra']) ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-6"><label>Alamat</label><input id="alamat" class="form-control" readonly></div>
<div class="col-md-6"><label>No KTP</label><input id="no_ktp" class="form-control" readonly></div>
<div class="col-md-6"><label>No Kartu Tani</label><input id="no_kartu" class="form-control" readonly></div>

<div class="col-md-12">
<img id="imgKtp" class="preview-img">
<img id="imgKartu" class="preview-img">
</div>

<div class="col-md-4"><label>Varietas</label><input name="varietas" class="form-control"></div>
<div class="col-md-4"><label>Lokasi Pengambilan</label><input name="lokasi_pengambilan" class="form-control"></div>
<div class="col-md-4"><label>Jumlah Karung</label><input name="jumlah_karung" type="number" class="form-control"></div>
<div class="col-md-4"><label>Est/Karung (Kg)</label><input name="est_karung" type="number" class="form-control"></div>
<div class="col-md-4"><label>Est Berat (Kg)</label><input name="est_berat" type="number" class="form-control"></div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button class="btn btn-primary">Simpan</button>
</div>
</div>
</form>
</div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div class="modal fade" id="modalDetail">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header"><h5>Detail Data</h5></div>
<div class="modal-body row g-2">
<input id="d_no_surat" class="form-control mb-2" readonly>
<input id="d_tanggal" class="form-control mb-2" readonly>
<input id="d_mitra" class="form-control mb-2" readonly>
<input id="d_gapoktan" class="form-control mb-2" readonly>
<input id="d_varietas" class="form-control mb-2" readonly>
<input id="d_lokasi" class="form-control mb-2" readonly>
<input id="d_jumlah" class="form-control mb-2" readonly>
<input id="d_estk" class="form-control mb-2" readonly>
<input id="d_estb" class="form-control mb-2" readonly>
</div>
<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
<button class="btn btn-primary" onclick="window.print()">Print</button>
</div>
</div>
</div>
</div>

<!-- ================= MODAL EDIT (LENGKAP) ================= -->
<div class="modal fade" id="modalEdit">
<div class="modal-dialog modal-xl modal-dialog-scrollable">
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="aksi" value="edit">
<input type="hidden" name="id_p2" id="e_id">

<div class="modal-content">
<div class="modal-header">
    <h5>Edit Data P2</h5>
</div>

<div class="modal-body row g-3">

<div class="col-md-12">
<label>No. Surat</label>
<input class="form-control" id="e_no_surat" readonly>
</div>

<div class="col-md-6">
<label>Tanggal Pengajuan</label>
<input type="date" name="tanggal" id="e_tanggal" class="form-control">
</div>

<div class="col-md-6">
<label>Gapoktan / Koperasi Tani</label>
<select name="id_gapoktan" id="e_gapoktan" class="form-control">
<?php foreach ($gapoktan as $g): ?>
<option value="<?= $g['id'] ?>" data-ketua="<?= $g['nama_ketua'] ?>">
<?= $g['nama_gapoktan'] ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-6">
<label>Nama Ketua</label>
<input class="form-control" id="e_nama_ketua" readonly>
</div>

<div class="col-md-6">
<label>Nama Mitra</label>
<select name="id_mitra" id="e_mitra" class="form-control">
<?php foreach ($mitra as $m): ?>
<option value="<?= $m['id_mitra'] ?>"
 data-alamat="<?= $m['alamat'] ?>"
 data-ktp="<?= $m['no_ktp'] ?>"
 data-kartu="<?= $m['no_kartu_tani'] ?>">
<?= $m['nama_mitra'] ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-6">
<label>Alamat</label>
<input class="form-control" id="e_alamat" readonly>
</div>

<div class="col-md-6">
<label>No. KTP</label>
<input class="form-control" id="e_no_ktp" readonly>
</div>

<div class="col-md-6">
<label>No. Kartu Tani</label>
<input class="form-control" id="e_no_kartu" readonly>
</div>

<div class="col-md-6">
<label>Varietas</label>
<input name="varietas" id="e_varietas" class="form-control">
</div>

<div class="col-md-6">
<label>Lokasi Pengambilan</label>
<input name="lokasi_pengambilan" id="e_lokasi" class="form-control">
</div>

<div class="col-md-4">
<label>Jumlah Karung</label>
<input name="jumlah_karung" id="e_jumlah" class="form-control">
</div>

<div class="col-md-4">
<label>Est / Karung (Kg)</label>
<input name="est_karung" id="e_estk" class="form-control">
</div>

<div class="col-md-4">
<label>Est Berat (Kg)</label>
<input name="est_berat" id="e_estb" class="form-control">
</div>

<hr>

<div class="col-md-12">
<label>Upload SPPT</label>
<input type="file" name="sppt_file" class="form-control">
</div>

<div class="col-md-12">
<label>Upload Surat Hak / Kuasa Pengolahan Bermaterai</label>
<input type="file" name="surat_kuasa_file" class="form-control">
</div>

<div class="col-md-12">
<label>Kartu Mitra</label>
<input type="file" name="kartu_mitra_file" class="form-control">
</div>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button class="btn btn-primary">Simpan Perubahan</button>
</div>

</div>
</form>
</div>
</div>

<script>
/* AUTO FILL TAMBAH */
$('#mitra').on('change', function(){
    let o=$(this).find(':selected');
    $('#alamat').val(o.data('alamat'));
    $('#no_ktp').val(o.data('ktp'));
    $('#no_kartu').val(o.data('kartu'));
    $('#imgKtp').attr('src','../uploads/'+o.data('ktpfile'));
    $('#imgKartu').attr('src','../uploads/'+o.data('kartufile'));
});
$('#gapoktan').on('change', function(){
    $('#nama_ketua').val($(this).find(':selected').data('ketua'));
});

/* DETAIL */
$('.btn-detail').on('click', function(){
    let d = $(this).data('json');
    $('#d_no_surat').val(d.no_surat);
    $('#d_tanggal').val(d.tanggal);
    $('#d_mitra').val(d.nama_mitra);
    $('#d_gapoktan').val(d.nama_gapoktan);
    $('#d_varietas').val(d.varietas);
    $('#d_lokasi').val(d.lokasi_pengambilan);
    $('#d_jumlah').val(d.jumlah_karung);
    $('#d_estk').val(d.est_karung);
    $('#d_estb').val(d.est_berat);
    new bootstrap.Modal(document.getElementById('modalDetail')).show();
});

/* EDIT */
$('.btn-edit').on('click', function () {
    let d = $(this).data('json');

    $('#e_id').val(d.id_p2);
    $('#e_no_surat').val(d.no_surat);
    $('#e_tanggal').val(d.tanggal);

    $('#e_gapoktan').val(d.id_gapoktan).trigger('change');
    $('#e_mitra').val(d.id_mitra).trigger('change');

    $('#e_varietas').val(d.varietas);
    $('#e_lokasi').val(d.lokasi_pengambilan);
    $('#e_jumlah').val(d.jumlah_karung);
    $('#e_estk').val(d.est_karung);
    $('#e_estb').val(d.est_berat);

    new bootstrap.Modal(document.getElementById('modalEdit')).show();
});

/* auto isi gapoktan */
$('#e_gapoktan').on('change', function(){
    $('#e_nama_ketua').val($(this).find(':selected').data('ketua'));
});

/* auto isi mitra */
$('#e_mitra').on('change', function(){
    let o = $(this).find(':selected');
    $('#e_alamat').val(o.data('alamat'));
    $('#e_no_ktp').val(o.data('ktp'));
    $('#e_no_kartu').val(o.data('kartu'));
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>\
</body>
</html>

<?php include "partials/footer.php"; ?>
