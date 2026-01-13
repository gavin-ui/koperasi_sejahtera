<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar" id="sidebar">

  <!-- BRAND -->
  <div class="brand">
    <span class="brand-text">AGRO LUMINTU</span>
    <button class="collapse-btn" onclick="toggleSidebar()">
      <i class="bi bi-chevron-left"></i>
    </button>
  </div>

  <!-- DASHBOARD -->
  <a href="index.php" class="menu-item <?= ($currentPage == 'index.php') ? 'active' : '' ?>">
    <i class="bi bi-speedometer2"></i>
    <span>Dashboard</span>
  </a>

  <!-- MASTER -->
  <div class="menu-section">MASTER</div>

  <a href="user.php" class="menu-item <?= ($currentPage == 'user.php') ? 'active' : '' ?>">
    <i class="bi bi-people"></i>
    <span>User</span>
  </a>

  <a href="gapoktan.php" class="menu-item <?= ($currentPage == 'gapoktan.php') ? 'active' : '' ?>">
    <i class="bi bi-diagram-3"></i>
    <span>Gapoktan</span>
  </a>

  <a href="mitra.php" class="menu-item <?= ($currentPage == 'mitra.php') ? 'active' : '' ?>">
    <i class="bi bi-person-badge"></i>
    <span>Mitra</span>
  </a>

  <a href="../admin/perusahaan.php" class="menu-item <?= ($currentPage == 'perusahaan.php') ? 'active' : '' ?>">
    <i class="bi bi-building"></i>
    <span>Perusahaan</span>
  </a>

  <a href="kelompok.php" class="menu-item <?= ($currentPage == 'kelompok.php') ? 'active' : '' ?>">
    <i class="bi bi-layers"></i>
    <span>Kelompok</span>
  </a>

  <a href="komoditas.php" class="menu-item <?= ($currentPage == 'komoditas.php') ? 'active' : '' ?>">
    <i class="bi bi-box-seam"></i>
    <span>Komoditas</span>
  </a>

  <a href="varietas.php" class="menu-item <?= ($currentPage == 'varietas.php') ? 'active' : '' ?>">
    <i class="bi bi-tags"></i>
    <span>Varietas</span>
  </a>

  <a href="varietasi_harga.php" class="menu-item <?= ($currentPage == 'varietasi_harga.php') ? 'active' : '' ?>">
    <i class="bi bi-cash-coin"></i>
    <span>Varietas Harga</span>
  </a>

  <!-- MANAJEMEN -->
  <div class="menu-section">MANAJEMEN</div>

  <a href="manajemenp2.php" class="menu-item <?= ($currentPage == 'manajemenp2.php') ? 'active' : '' ?>">
    <i class="bi bi-kanban"></i>
    <span>Manajemen P2</span>
  </a>

  <a href="#" class="menu-item">
    <i class="bi bi-clipboard-data"></i>
    <span>Manajemen Q2CP</span>
  </a>

  <a href="#" class="menu-item">
    <i class="bi bi-diagram-2"></i>
    <span>Manajemen P3GB</span>
  </a>

  <a href="#" class="menu-item">
    <i class="bi bi-stack"></i>
    <span>Manajemen P4</span>
  </a>

  <a href="#" class="menu-item">
    <i class="bi bi-receipt"></i>
    <span>Manajemen Transaksi</span>
  </a>

</div>

<style>
  :root{
  --sidebar-width:260px;
  --sidebar-mini:80px;
  --primary:#16a34a;
  --bg-dark:#0f172a;
  --bg-soft:#020617;
  --text:#e5e7eb;
  --muted:#94a3b8;
}

.sidebar{
  position:fixed;
  top:0;
  left:0;
  height:100vh;
  width:var(--sidebar-width);
  background:linear-gradient(180deg,#020617,#020617,#020617);
  padding:18px 14px;
  transition:.35s ease;
  overflow:hidden;
  z-index:1100;
}

/* COLLAPSE */
.sidebar.collapsed{
  width:var(--sidebar-mini);
}

/* BRAND */
.brand{
  display:flex;
  align-items:center;
  justify-content:space-between;
  color:white;
  font-weight:800;
  font-size:16px;
  padding:14px 12px;
  margin-bottom:10px;
}

.brand-text{
  transition:.3s;
}

.sidebar.collapsed .brand-text{
  opacity:0;
  width:0;
}

/* TOGGLE */
.collapse-btn{
  background:#020617;
  border:none;
  color:#cbd5f5;
  font-size:18px;
  width:38px;
  height:38px;
  border-radius:12px;
  cursor:pointer;
  transition:.3s;
}

.collapse-btn:hover{
  background:#020617;
  color:white;
}

.sidebar.collapsed .collapse-btn i{
  transform:rotate(180deg);
}

/* MENU */
.menu-item{
  display:flex;
  align-items:center;
  gap:14px;
  color:var(--text);
  padding:12px 14px;
  border-radius:12px;
  text-decoration:none;
  font-weight:500;
  margin:4px 0;
  transition:.25s;
}

.menu-item i{
  font-size:20px;
  min-width:24px;
  text-align:center;
}

.menu-item:hover{
  background:rgba(22,163,74,.15);
  color:white;
}

.menu-item.active{
  background:var(--primary);
  color:white;
  box-shadow:0 10px 30px rgba(22,163,74,.4);
}

/* TEXT HIDE */
.sidebar.collapsed .menu-item span{
  opacity:0;
  width:0;
  overflow:hidden;
}

/* SECTION */
.menu-section{
  margin:16px 10px 8px;
  font-size:11px;
  font-weight:700;
  letter-spacing:1px;
  color:var(--muted);
  transition:.3s;
}

.sidebar.collapsed .menu-section{
  opacity:0;
}
</style>
<script>
function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('collapsed');

  document.documentElement.style.setProperty(
    '--sidebar-width',
    document.getElementById('sidebar').classList.contains('collapsed')
      ? '80px'
      : '260px'
  );
}
</script>