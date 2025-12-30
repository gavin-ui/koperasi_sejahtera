<header class="hero-section d-flex align-items-center">
  <div class="container text-center text-white">

    <h1 class="fw-bold display-4 mb-3 hero-title">
      Koperasi Agro Lumintu Sejahtera
    </h1>

    <p class="lead hero-subtitle">
      Bersama Petani Membangun Kesejahteraan Berkelanjutan
    </p>

    <div class="mt-4">
      <a href="tentang.php" class="btn btn-hero me-2">Pelajari Lebih Lanjut</a>
      <a href="produk.php" class="btn btn-outline-light btn-lg rounded-pill px-4">
        Lihat Produk
      </a>
    </div>

  </div>
</header>

<!-- ================= CSS ================= -->
<style>
.hero-section{
  height: 90vh;
  background-image:
    linear-gradient(rgba(0, 80, 32, 0.7), rgba(0, 90, 35, 0.8)),
    url('assets/img/hero.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
}

.hero-title{
  letter-spacing:.5px;
  text-shadow: 1px 2px 8px rgba(0,0,0,.4);
}

.hero-subtitle{
  font-size: 1.2rem;
  max-width: 650px;
  margin: auto;
  opacity: .9;
}

.btn-hero{
  background: linear-gradient(90deg,#d4af37,#f1d97c);
  color:#0b3e13;
  font-weight:600;
  padding:12px 28px;
  border-radius:50px;
  border:none;
  font-size:18px;
}

.btn-hero:hover{
  background:linear-gradient(90deg,#f4d35e,#ffe27a);
  color:#0a3811;
}

/* Responsive */
@media(max-width:768px){
  .hero-section{height:80vh;}
  .hero-title{font-size:2rem;}
}
</style>
