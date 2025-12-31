<?php include "partials/navbar.php"; ?>
<?php include "partials/header.php"; ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Galeri Koperasi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f7fff7;">

<section class="galeri-section pt-5 mt-5">
  <div class="container">

    <div class="text-center mb-5">
      <h2 class="fw-bold text-success">Galeri Kegiatan Koperasi</h2>
      <p class="text-muted">
        Dokumentasi kegiatan koperasi, hasil panen, pelatihan, serta aktivitas anggota 🌿
      </p>
    </div>

    <div class="row g-4">

      <?php
      // FOTO SESUAI FOLDER KAMU: login/assets/img/
      $galeri = [
        ["login/assets/img/satu.jpeg", "Panen Padi Bersama Anggota"],
        ["login/assets/img/dua.jpeg", "Pelatihan Pertanian Modern"],
        ["login/assets/img/tiga.png", "Perkebunan Cabai"],
        ["login/assets/img/empat.jpeg", "Distribusi Sayuran Segar"],
        ["login/assets/img/lima.jpeg", "Rapat Pengurus Koperasi"],
        ["login/assets/img/enam.jpeg", "Hasil Panen Buah Segar"],
      ];

      foreach ($galeri as $g) { ?>

      <div class="col-md-4 col-sm-6">
        <div class="gallery-card shadow-sm">

          <img src="<?= $g[0] ?>" 
               class="img-fluid gallery-img"
               alt="<?= $g[1] ?>"
               onclick="openModal('<?= $g[0] ?>','<?= $g[1] ?>')">

          <div class="gallery-caption">
            <p><?= $g[1] ?></p>
          </div>

        </div>
      </div>

      <?php } ?>
    </div>
  </div>
</section>


<!-- ================= MODAL ================= -->
<div class="modal fade" id="galeriModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalTitle"></h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <img id="modalImg" class="img-fluid rounded shadow">
      </div>

    </div>
  </div>
</div>


<!-- ================= CSS ================= -->
<style>
.gallery-card{
  border-radius:12px;
  overflow:hidden;
  position:relative;
  background:white;
  transition:.3s;
}

.gallery-card:hover{
  transform:translateY(-5px);
  box-shadow:0 10px 25px rgba(0,0,0,.2);
  border:2px solid #e6c75e;
}

.gallery-img{
  width:100%;
  height:260px;
  object-fit:cover;
  transition:.4s;
  cursor:pointer;
}

.gallery-card:hover .gallery-img{
  transform:scale(1.1);
  filter:brightness(90%);
}

.gallery-caption{
  padding:12px;
  text-align:center;
  font-weight:500;
  color:#0a5c2d;
}
</style>


<!-- ================= SCRIPT ================= -->
<script>
function openModal(img, title){
  document.getElementById("modalImg").src = img;
  document.getElementById("modalTitle").innerHTML = title;

  var modal = new bootstrap.Modal(document.getElementById('galeriModal'));
  modal.show();
}
</script>


<?php include "partials/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
