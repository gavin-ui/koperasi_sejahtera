<?php 
$page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm" style="background: linear-gradient(90deg, #0b8a34, #0f9b3f);">
  <div class="container">
    
    <a class="navbar-brand d-flex align-items-center fw-bold text-white" href="index.php">
      <img src="login/assets/Screenshot_2025-12-30_094123-removebg-preview.png" alt="Logo" style="height:40px; margin-right:10px;">
      <span style="letter-spacing: .5px;">Koperasi Sejahtera</span>
    </a>

    <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">

        <li class="nav-item">
          <a class="nav-link nav-item-custom <?php echo ($page=='index.php')?'active':''; ?>" href="index.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-item-custom <?php echo ($page=='tentang.php')?'active':''; ?>" href="tentang.php">Tentang</a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-item-custom <?php echo ($page=='produk.php')?'active':''; ?>" href="produk.php">Produk</a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-item-custom <?php echo ($page=='berita.php')?'active':''; ?>" href="#">Berita</a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-item-custom <?php echo ($page=='galeri.php')?'active':''; ?>" href="galeri.php">Galeri</a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-item-custom <?php echo ($page=='kontak.php')?'active':''; ?>" href="#">Kontak</a>
        </li>

        <!-- Tombol Login -->
        <li class="ms-3">
          <a href="login/login.php" class="btn btn-login">Login</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- ================= CSS LANGSUNG ================= -->
<style>
.navbar .nav-link {
  color: #ffffff !important;
  font-weight: 500;
  letter-spacing: .3px;
  position: relative;
  padding: 8px 14px !important;
}

.nav-item-custom::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: 3px;
  width: 0%;
  height: 3px;
  background: linear-gradient(90deg, #d4af37, #f6e27a);
  border-radius: 2px;
  transition: .3s;
}

.nav-item-custom:hover::after,
.nav-item-custom.active::after {
  width: 100%;
}

.nav-item-custom:hover {
  color: #fffbcc !important;
}

/* Tombol Login */
.btn-login {
  background: linear-gradient(90deg, #d4af37, #e6c75e);
  color: #0b4f22 !important;
  font-weight: 600;
  border-radius: 30px;
  padding: 8px 20px;
  border: none;
}

.btn-login:hover {
  background: linear-gradient(90deg, #f4d35e, #ffd86b);
  color: #0a3c19 !important;
}
</style>
