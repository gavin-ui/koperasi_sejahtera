<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Produk Koperasi</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Style Utama -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<?php include "partials/navbar.php"; ?>
<?php include "partials/header.php"; ?>

<section class="produk-section pt-5 mt-5">
  <div class="container text-center">

    <h2 class="fw-bold mb-3 text-success">Produk Unggulan Koperasi</h2>
    <p class="text-muted mb-5">
      Produk pertanian berkualitas terbaik dari anggota koperasi kami, segar, higienis, dan berkelanjutan 🌿
    </p>

    <div class="row g-4">

      <?php
      $produk = [
        ["Padi","gabah.jpg","Padi berkualitas tinggi sebagai bahan utama beras pilihan yang dihasilkan petani terbaik kami."],
        ["Bawang Merah","bawang merah.jpg","Bawang merah segar dengan aroma kuat dan kualitas premium."],
        ["Cabai","cabai.jpeg","Cabai merah pilihan dengan rasa pedas khas dan berkualitas."],
        ["Tomat","tomat.jpeg","Tomat segar kaya vitamin, cocok untuk berbagai kebutuhan dapur."],
        ["Kentang","kentang.webp","Kentang berkualitas tinggi, segar dan tahan lama."],
        ["Sawi","sawi.jpeg","Sawi hijau segar dan sehat, dipanen langsung dari kebun petani."]
      ];

      foreach($produk as $p){
      ?>
      <div class="col-md-4">
        <div class="card produk-card h-100">
          <img src="login/assets/img/<?= $p[1] ?>" class="card-img-top" alt="<?= $p[0] ?>">
          <div class="card-body">
            <h5 class="card-title fw-bold"><?= $p[0] ?></h5>
            <p class="card-text"><?= $p[2] ?></p>
          </div>
        </div>
      </div>
      <?php } ?>

    </div>

  </div>
</section>

<?php include "partials/footer.php"; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
.produk-card{
  border: none;
  border-radius: 12px;
  overflow: hidden;
  transition: .3s;
  box-shadow: 0 5px 18px rgba(0,0,0,.15);
}
.produk-card img{
  height: 220px;
  object-fit: cover;
}
.produk-card:hover{
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0,0,0,.25);
  border: 2px solid #e6c75e;
}
.card-title{
  color:#0b6623;
}
</style>

</body>
</html>
