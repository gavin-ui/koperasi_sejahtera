<?php
session_start();

// Jika sudah login, langsung ke admin
if (isset($_SESSION['login'])) {
    header("Location: ../admin/index.php");
    exit;
}

// PROSES LOGIN
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // DATA LOGIN SEMENTARA
    $admin_user = "admin";
    $admin_pass = "12345";

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;

        header("Location: ../admin/index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login | Agro Lumintu Sejahtera</title>

<style>
* {
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    margin: 0;
    min-height: 100vh;
    background: linear-gradient(120deg, #e9f5ec, #f7faf9);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Container utama */
.container {
    width: 100%;
    max-width: 1000px;
    background: #fff;
    border-radius: 18px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    box-shadow: 0 25px 60px rgba(0,0,0,.12);
    overflow: hidden;
}

/* Kiri (Form) */
.login-box {
    padding: 60px;
}

.login-box h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.login-box p {
    color: #777;
    margin-bottom: 35px;
}

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    font-size: 14px;
    margin-bottom: 6px;
    color: #333;
}

input {
    width: 100%;
    padding: 14px 16px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 15px;
    outline: none;
}

input:focus {
    border-color: #5fb878;
}

button {
    width: 100%;
    margin-top: 10px;
    padding: 14px;
    background: linear-gradient(135deg, #5fb878, #4fa368);
    border: none;
    border-radius: 12px;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: .3s;
}

button:hover {
    opacity: .9;
}

.error {
    background: #ffe6e6;
    color: #b10000;
    padding: 12px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 20px;
    font-size: 14px;
}

/* Kanan (Branding) */
.brand {
    background: linear-gradient(160deg, #5fb878, #3e8e61);
    color: #fff;
    padding: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.brand-logo {
    width: 260px;
    max-width: 100%;
    margin-bottom: 40px;
    filter: drop-shadow(0 22px 50px rgba(0,0,0,.5));
    transition: transform .4s ease;
}

.brand-logo:hover {
    transform: scale(1.07);
}

@media (max-width: 900px) {
    .brand-logo {
        width: 180px;
        margin-bottom: 30px;
    }
}

@media (max-width: 500px) {
    .brand-logo {
        width: 140px;
        margin-bottom: 25px;
    }
}

.brand h1 {
    font-size: 42px;
    margin: 0;
    line-height: 1.2;
}

.brand span {
    font-weight: 300;
}

.brand p {
    margin-top: 18px;
    font-size: 15px;
    opacity: .9;
}

/* Responsive */
@media(max-width: 900px) {
    .container {
        grid-template-columns: 1fr;
    }
    .brand {
        display: none;
    }
}
</style>
</head>

<body>

<div class="container">

    <!-- FORM LOGIN -->
    <div class="login-box">
        <h2>Masuk</h2>
        <p>Masukkan username dan password Anda</p>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>

    <!-- BRAND -->
    <div class="brand">
        <img src="assets/Screenshot_2025-12-30_094123-removebg-preview.png" class="brand-logo">
        <h1>Agro Lumintu<br><span>Sejahtera</span></h1>
    </div>

</div>

</body>
</html>
