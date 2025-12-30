<?php
// kalau nanti kamu mau include header/footer tinggal dipisah.
// untuk sekarang simple di 1 file dulu.
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Koperasi Sejahtera - Pertanian</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background: #f8f9fa; }
    .hero {
        background: url('images/pertanian.jpg') center/cover no-repeat;
        height: 75vh;
        display: flex;
        align-items: center;
        color: white;
    }
    .hero-overlay {
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        padding: 50px;
    }
    .section-title{
        font-weight: bold;
        text-transform: uppercase;
        color:#2a7b40;
    }
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#2a7b40;">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Koperasi Sejahtera</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Berita</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Galeri</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO BANNER -->
<section class="hero">
  <div class="hero-overlay d-flex flex-column justify-content-center">
      <div class="container">
        <h1 class="fw-bold display-4">Koperasi Sejahtera</h1>
        <p class="fs-4">Bersama Membangun Pertanian yang Maju dan Sejahtera</p>
        <a href="#" class="btn btn-light btn-lg">Pelajari Lebih Lanjut</a>
      </div>
  </div>
</section>

<!-- TENTANG -->
<section class="py-5">
  <div class="container">
    <h3 class="section-title text-center mb-4">Tentang Kami</h3>
    <p class="text-center">
      Koperasi Sejahtera adalah koperasi berbasis pertanian yang bertujuan untuk 
      meningkatkan kesejahteraan petani melalui pendampingan, pemasaran hasil tani,
      pembiayaan, dan penguatan sumber daya pertanian.
    </p>
  </div>
</section>

<!-- PRODUK / KOMODITAS -->
<section class="py-5 bg-white">
  <div class="container">
    <h3 class="section-title text-center mb-4">Produk & Komoditas</h3>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card">
          <img src="images/padi.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Padi / Gabah</h5>
            <p>Padi merupakan komoditas tanaman pangan yang penting di Indonesia. Selain itu, padi juga termasuk tanaman pertanian yang berasal dari dua benua yaitu Asia dan Afrika Barat.</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <img src="images/cabai.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Cabai</h5>
            <p>Cabai merupakan komoditas sayuran potensial yang mempunyai nilai ekonomi tinggi dan memiliki potensi untuk terus dikembangkan.</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <img src="images/bawang.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Bawang Merah</h5>
            <p>Bawang merah merupakan salah satu komoditas sayuran unggulan yang sejak lama telah diusahakan oleh petani secara intensif. Komoditas sayuran ini kelompok rempah</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="col-md-4">
        <div class="card">
          <img src="images/bawang.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Kentang</h5>
            <p>Kentang (Solanum tuberosum L) merupakan salah satu komoditas sayuran penting di Indonesia. Produksi kentang telah berkembang dengan pesat.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="col-md-4">
        <div class="card">
          <img src="images/bawang.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Sawi</h5>
            <p>Sawi merupakan salah satu komoditas tanaman hortikultura dari jenis sayur sayuran yang dimanfaatkan daun-daun yang masih muda.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="col-md-4">
        <div class="card">
          <img src="images/bawang.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Tomat</h5>
            <p>Buah tomat adalah komoditas yang multiguna, berfungsi sebagai sayuran, bumbu, masak, buah meja, bahan pewarna makanan dan obat-obatan.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VISI MISI -->
<section class="py-5">
  <div class="container">
    <h3 class="section-title text-center mb-4">Visi & Misi</h3>

    <div class="row">
      <div class="col-md-6">
        <div class="p-4 bg-white shadow rounded">
          <h5 class="fw-bold">Visi</h5>
          <p>Menjadi koperasi yang amanah dengan tujuan memajukan kesejahteraan anggota khususnya dan para petani Indonesia umumnya dengan sistem pengelolaan pertanian yang maju, mandiri dan berdaya saing berbasis ekonomi dan edukasi.</p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="p-4 bg-white shadow rounded">
          <h5 class="fw-bold">Misi</h5>
          <ul>
            <li>Mengembangkan dan meningkatkan potensi ekonomi anggota berbasis ekonomi dan edukasi.</li>
            <li>Mengembangkan dan meningkatkan potensi ekonomi anggota berbasis sumber daya lokal.</li>
            <li>Menghasilkan produk pertanian dan olahannya yang berkualitas, berdaya saing tinggi dan berwawasan lingkungan.</li>
            <li>Menyediakan peralatan dan bahan-bahan yang dibutuhkan anggota.</li>
            <li>Menampung hasil produksi anggota yang selanjutnya dilakukan penyempurnaan dan mendistribusikannya.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PROGRAM / KEUNGGULAN -->
<section class="py-5 bg-white">
  <div class="container">
    <h3 class="section-title text-center mb-4">Program & Keunggulan</h3>

    <div class="row text-center">
      <div class="col-md-4">
        <div class="p-4 shadow rounded">
          <h5>Pendampingan Petani</h5>
          <p>Pembinaan dan edukasi budidaya modern</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="p-4 shadow rounded">
          <h5>Pemasaran Hasil Tani</h5>
          <p>Membantu distribusi ke pasar lebih luas</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="p-4 shadow rounded">
          <h5>Dukungan Permodalan</h5>
          <p>Memberikan akses pembiayaan yang lebih mudah</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BERITA -->
<section class="py-5">
  <div class="container">
    <h3 class="section-title text-center mb-4">Berita & Kegiatan</h3>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card">
          <img src="images/berita1.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Kegiatan Panen Raya</h5>
            <p>Dokumentasi kegiatan panen raya bersama anggota koperasi.</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <img src="images/berita2.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Pelatihan Petani</h5>
            <p>Program pelatihan peningkatan kualitas pertanian.</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <img src="images/berita3.jpg" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title">Kerjasama Pemasaran</h5>
            <p>Kerjasama dengan pihak mitra untuk pemasaran hasil tani.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- GALERI -->
<section class="py-5 bg-white">
  <div class="container">
    <h3 class="section-title text-center mb-4">Galeri</h3>

    <div class="row g-3">
      <div class="col-md-3"><img src="images/g1.jpg" class="img-fluid rounded"></div>
      <div class="col-md-3"><img src="images/g2.jpg" class="img-fluid rounded"></div>
      <div class="col-md-3"><img src="images/g3.jpg" class="img-fluid rounded"></div>
      <div class="col-md-3"><img src="images/g4.jpg" class="img-fluid rounded"></div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="py-3 text-center text-light" style="background:#2a7b40;">
    <p class="mb-0">© 2025 Koperasi Sejahtera | Semua Hak Dilindungi</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
