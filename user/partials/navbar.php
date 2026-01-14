<nav>
    <div class="nav-container">

        <!-- BRAND -->
        <div class="nav-brand">
            Agro Lumintu Sejahtera
        </div>

        <!-- MENU USER -->
        <div class="nav-menu">

            <span class="nav-user">
                Halo, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
            </span>

            <a href="index.php" class="nav-link">
                Beranda
            </a>

            <a href="inputvarietas.php" class="nav-link">
                Input Varietas
            </a>

            <a href="inputharga.php" class="nav-link">
                Input Harga
            </a>

            <a href="profil.php" class="nav-link">
                Profil
            </a>

            <a href="../auth/logout.php" class="nav-logout">
                Logout
            </a>

        </div>

    </div>
</nav>
