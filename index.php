<?php
// include "koneksi.php"; // kalau nanti butuh database tinggal aktifkan
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Koperasi Agro Lumintu Sejahtera</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background:#f8f9fa; }
    .section-title{
        font-weight:bold;
        text-transform:uppercase;
        color:#2a7b40;
    }
</style>
</head>

<body>

<?php include "partials/navbar.php"; ?>
<?php include "partials/header.php"; ?>

<!-- ===================================================== -->
<!-- VISI MISI -->
<!-- ===================================================== -->
<section class="py-5">
  <div class="container">
    <h3 class="section-title text-center mb-4">Visi & Misi</h3>

    <div class="row">
      <div class="col-md-6">
        <div class="shadow p-4 bg-white rounded">
          <h5 class="fw-bold">Visi</h5>
          <p>
            Menjadi koperasi agroindustri terkemuka yang meningkatkan kesejahteraan
            anggota dan masyarakat melalui produk pertanian berkualitas dan berkelanjutan.
          </p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="shadow p-4 bg-white rounded">
          <h5 class="fw-bold">Misi</h5>
          <ul>
            <li>Meningkatkan kapasitas produksi anggota dengan teknologi inovatif.</li>
            <li>Mengembangkan jaringan pemasaran produk agro yang luas dan kompetitif.</li>
            <li>Mendorong praktik pertanian ramah lingkungan.</li>
            <li>Meningkatkan pemberdayaan ekonomi anggota dan komunitas lokal.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===================================================== -->
<!-- PRODUK & LAYANAN -->
<!-- ===================================================== -->
<section class="py-5 bg-white">
  <div class="container">
    <h3 class="section-title text-center mb-4">Produk & Layanan</h3>

    <div class="row g-4">

      <div class="col-md-4">
        <div class="shadow p-4 rounded bg-light h-100">
          <h5 class="fw-bold">Produk Utama</h5>
          <ul>
            <li>Olahan pertanian (makanan sehat, bumbu alami, produk herbal)</li>
            <li>Hasil pertanian segar (sayuran organik, buah lokal, rempah)</li>
            <li>Produk bernilai tambah (kerajinan berbahan pertanian)</li>
          </ul>
        </div>
      </div>

      <div class="col-md-4">
        <div class="shadow p-4 rounded bg-light h-100">
          <h5 class="fw-bold">Pelayanan</h5>
          <ul>
            <li>Pelatihan pertanian modern bagi anggota</li>
            <li>Akses pasar untuk produk anggota</li>
            <li>Pendampingan sertifikasi (Organik / Halal / dll)</li>
          </ul>
        </div>
      </div>

      <div class="col-md-4">
        <div class="shadow p-4 rounded bg-light h-100">
          <h5 class="fw-bold">Tujuan</h5>
          <p>Mendukung peningkatan kualitas, daya saing, dan kesejahteraan petani.</p>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ===================================================== -->
<!-- STRUKTUR ORGANISASI -->
<!-- ===================================================== -->
<section class="py-5">
  <div class="container">
    <h3 class="section-title text-center mb-4">Struktur Organisasi</h3>

    <div class="row text-center g-4">

      <div class="col-md-3">
        <div class="shadow p-3 bg-white rounded">
          <h6 class="fw-bold">Ketua</h6>
        </div>
      </div>

      <div class="col-md-3">
        <div class="shadow p-3 bg-white rounded">
          <h6 class="fw-bold">Sekretaris</h6>
        </div>
      </div>

      <div class="col-md-3">
        <div class="shadow p-3 bg-white rounded">
          <h6 class="fw-bold">Bendahara</h6>
        </div>
      </div>

      <div class="col-md-3">
        <div class="shadow p-3 bg-white rounded">
          <h6 class="fw-bold">Manajer Operasional</h6>
        </div>
      </div>

    </div>

    <div class="row text-center mt-4">
      <div class="col-md-4">
        <div class="shadow p-3 bg-white rounded">
          Bidang Produksi & Pengembangan
        </div>
      </div>

      <div class="col-md-4">
        <div class="shadow p-3 bg-white rounded">
          Bidang Pemasaran & Penjualan
        </div>
      </div>

      <div class="col-md-4">
        <div class="shadow p-3 bg-white rounded">
          Bidang Keuangan & Administrasi
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===================================================== -->
<!-- KONSEP LOGO -->
<!-- ===================================================== -->
<section class="py-5 bg-white">
  <div class="container">
    <h3 class="section-title text-center mb-4">Konsep Logo</h3>

    <div class="row g-4">

      <div class="col-md-6">
        <div class="shadow p-4 rounded bg-light">
          <h5 class="fw-bold">Elemen Logo</h5>
          <ul>
            <li>Tanaman / Agro: fokus pertanian & agroindustri</li>
            <li>Lingkaran / Koperasi: kebersamaan & kesatuan</li>
            <li>Warna Hijau: kesuburan & keberlanjutan</li>
            <li>Warna Kuning / Emas: kemakmuran & kualitas</li>
            <li>Tipografi modern & profesional</li>
          </ul>
        </div>
      </div>

      <div class="col-md-6">
        <div class="shadow p-4 rounded bg-light">
          <h5 class="fw-bold">Arti Logo</h5>
          <p>
            Logo Koperasi Agro Lumintu Sejahtera menggambarkan sinergi antara alam (pertanian)
            dan kerjasama (koperasi), melambangkan komitmen keberlanjutan dan kesejahteraan bersama.
            Lingkaran menunjukkan kesatuan anggota, sementara elemen tanaman menunjukkan
            fokus pada agroindustri yang produktif dan ramah lingkungan.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ===================================================== -->
<!-- KEUNGGULAN -->
<!-- ===================================================== -->
<section class="py-5">
  <div class="container">
    <h3 class="section-title text-center mb-4">Keunggulan Koperasi</h3>

    <div class="row text-center g-4">

      <div class="col-md-4">
        <div class="shadow p-4 rounded bg-white">
          <h5 class="fw-bold">Komitmen Keberlanjutan</h5>
          <p>Fokus pada praktik pertanian ramah lingkungan.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="shadow p-4 rounded bg-white">
          <h5 class="fw-bold">Pemberdayaan Anggota</h5>
          <p>Meningkatkan kapasitas dan pendapatan anggota.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="shadow p-4 rounded bg-white">
          <h5 class="fw-bold">Inovasi Produk</h5>
          <p>Mengembangkan produk agroindustri bernilai tambah.</p>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ===================================================== -->
<!-- GALERI (TETAP ADA) -->
<!-- ===================================================== -->
<section class="py-5 bg-white">
  <div class="container">
    <h3 class="section-title text-center mb-4">Galeri</h3>

    <div class="row g-3">
      <div class="col-md-3"><img src="login/assets/img/dua.jpeg" class="img-fluid rounded"></div>
      <div class="col-md-3"><img src="login/assets/img/tiga.png" class="img-fluid rounded"></div>
      <div class="col-md-3"><img src="login/assets/img/empat.jpeg" class="img-fluid rounded"></div>
      <div class="col-md-3"><img src="login/assets/img/lima.jpeg" class="img-fluid rounded"></div>
    </div>
  </div>
</section>

<?php include "partials/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
