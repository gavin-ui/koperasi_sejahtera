<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tentang Kami - Koperasi Sejahtera</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Style -->
<link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<?php include "partials/navbar.php"; ?>
<?php include "partials/header.php"; ?>

<!-- ABOUT SECTION -->
<section class="about-section py-5 mt-5">
  <div class="container">

    <div class="row align-items-center">
      <div class="col-md-6 mb-4">
        <img src="login/assets/img/tentang.jpeg" class="img-fluid rounded shadow" alt="Koperasi">
      </div>

      <div class="col-md-6">
        <h2 class="fw-bold text-success mb-3">Tentang Koperasi Agro Lumintu Sejahtera</h2>
        <p class="text-muted">
          Koperasi Sejahtera hadir sebagai wadah bagi para petani untuk meningkatkan kesejahteraan,
          memperkuat ekonomi kerakyatan, serta menciptakan ekosistem pertanian yang modern, mandiri,
          dan berkelanjutan. Melalui kolaborasi, transparansi, dan profesionalisme, kami berkomitmen
          memberikan manfaat nyata bagi anggota dan masyarakat.
        </p>
        <p class="text-muted">
          Dengan dukungan teknologi dan manajemen yang baik, kami memastikan setiap produk yang
          dihasilkan petani memiliki nilai ekonomi tinggi dan mampu bersaing di pasar.
        </p>
      </div>
    </div>

  </div>
</section>

<!-- VISI MISI -->
<section class="visi-misi py-5 bg-light">
  <div class="container text-center">

    <h2 class="fw-bold text-success mb-4">Visi & Misi</h2>

    <div class="row g-4">

      <div class="col-md-6">
        <div class="card visi-card p-4 h-100">
          <h4 class="fw-bold mb-3">Visi</h4>
          <p>
            Menjadi koperasi agroindustri terkemuka yang meningkatkan kesejahteraan
            anggota dan masyarakat melalui produk pertanian berkualitas dan berkelanjutan.
          </p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card visi-card p-4 h-100">
          <h4 class="fw-bold mb-3">Misi</h4>
          <ul class="text-start text-muted">
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

<!-- KEUNGGULAN -->
<section class="keunggulan py-5">
  <div class="container text-center">

    <h2 class="fw-bold text-success mb-4">Nilai & Keunggulan Kami</h2>

    <div class="row g-4">

      <div class="col-md-4">
        <div class="card advantage-card p-4 h-100">
          <h5 class="fw-bold">Berkualitas</h5>
          <p class="text-muted">Produk terpilih dan terjamin kualitasnya untuk konsumen.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card advantage-card p-4 h-100">
          <h5 class="fw-bold">Bersih & Higienis</h5>
          <p class="text-muted">Dikelola dengan standar kebersihan tinggi.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card advantage-card p-4 h-100">
          <h5 class="fw-bold">Berdaya Saing</h5>
          <p class="text-muted">Siap bersaing di pasar modern dengan harga terbaik.</p>
        </div>
      </div>

    </div>

  </div>
</section>

<?php include "partials/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>

.visi-card, .advantage-card{
  border-radius: 12px;
  border: none;
  box-shadow: 0 5px 18px rgba(0,0,0,.1);
  transition: .3s;
}
.visi-card:hover, .advantage-card:hover{
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0,0,0,.2);
  border: 2px solid #e6c75e;
}

</style>

</body>
</html>
