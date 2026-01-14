<?php
session_start();
require "../koneksi.php";

/* ambil daftar daerah */
$daerahList = $pdo->query("SELECT * FROM daerah ORDER BY nama_daerah")->fetchAll();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username  = trim($_POST['username']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm'];
    $role      = $_POST['role'];
    $daerah_id = $_POST['daerah_id'] ?? null; // ← WAJIB ADA

    if ($password !== $confirm) {
        $error = "Konfirmasi password tidak cocok!";
    } 
    elseif ($role === 'user' && empty($daerah_id)) {
        $error = "Daerah wajib dipilih untuk user!";
    } 
    else {
        $cek = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $cek->execute([$username]);

        if ($cek->rowCount() > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            /* ===== INI TEMPATNYA ===== */
            $stmt = $pdo->prepare(
                "INSERT INTO users (username, password, role, daerah_id)
                 VALUES (?, ?, ?, ?)"
            );

            if ($stmt->execute([$username, $hash, $role, $daerah_id])) {
                $success = "Akun berhasil dibuat. Mengalihkan ke login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Gagal membuat akun.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | Agro Lumintu Sejahtera</title>

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

/* CONTAINER */
.container {
    width: min(100%, 1000px);
    background: #fff;
    border-radius: 18px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    box-shadow: 0 25px 60px rgba(0,0,0,.12);
    overflow: hidden;
}

/* FORM */
.register-box {
    padding: clamp(30px, 5vw, 60px);
}

.register-box h2 {
    margin: 0;
    font-size: clamp(22px, 2.5vw, 28px);
    font-weight: 700;
}

.register-box p {
    color: #777;
    font-size: clamp(13px, 1.5vw, 15px);
    margin-bottom: clamp(20px, 4vw, 35px);
}

.form-group {
    margin-bottom: 16px;
}

label {
    display: block;
    font-size: 13px;
    margin-bottom: 6px;
    color: #444;
}

input {
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 15px;
    outline: none;
}

input::placeholder {
    color: #aaa;
}

input:focus {
    border-color: #5fb878;
}

/* BUTTON */
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

/* MESSAGE */
.error {
    background: #ffe6e6;
    color: #b10000;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 20px;
    text-align: center;
}

.success {
    background: #e6fff0;
    color: #0a7a3c;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 20px;
    text-align: center;
}

/* BRAND */
.brand {
    background: linear-gradient(160deg, #5fb878, #3e8e61);
    color: #fff;
    padding: clamp(30px, 5vw, 60px);
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.brand-logo {
    width: clamp(120px, 18vw, 260px);
    margin-bottom: 30px;
    filter: drop-shadow(0 22px 50px rgba(0,0,0,.5));
}

.brand h1 {
    font-size: clamp(24px, 3vw, 42px);
    margin: 0;
    line-height: 1.2;
}

.brand span {
    font-weight: 300;
}

.brand p {
    margin-top: 16px;
    font-size: 15px;
    opacity: .9;
}

/* LINK */
.link {
    margin-top: 20px;
    text-align: center;
}

.link a {
    color: #5fb878;
    text-decoration: none;
    font-weight: 600;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .container {
        grid-template-columns: 1fr;
    }

    .brand {
        text-align: center;
        align-items: center;
    }
}
</style>
</head>

<body>

<div class="container">

    <!-- FORM REGISTER -->
    <div class="register-box">
        <h2>Buat Akun</h2>
        <p>Isi data di bawah untuk mendaftar</p>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat password" required>
            </div>

            <div class="form-group">
                <label>Ulangi Password</label>
                <input type="password" name="confirm" placeholder="Ulangi password" required>
            </div>

            <div class="form-group">
                <label>Daftar Sebagai</label>
                <select name="role" required
                    style="width:100%;padding:14px;border-radius:10px;border:1px solid #ddd;font-size:15px;">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <div class="form-group" id="daerahBox" style="display:none">
                <label>Daerah</label>
                <select name="daerah_id"
                    style="width:100%;padding:14px;border-radius:10px;border:1px solid #ddd;font-size:15px;">
                    <option value="">-- Pilih Daerah --</option>
                    <?php foreach ($daerahList as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= $d['nama_daerah'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" id="btnRegister">Daftar</button>
        </form>

        <div class="link">
            Sudah punya akun? <a href="login.php">Login</a>
        </div>
    </div>

    <!-- BRAND -->
    <div class="brand">
        <img src="assets/Screenshot_2025-12-30_094123-removebg-preview.png" class="brand-logo" alt="Logo">
        <h1>Agro Lumintu<br><span>Sejahtera</span></h1>
    </div>

</div>

<script>
const btn = document.getElementById("btnRegister");
document.querySelector("form").addEventListener("submit", () => {
    btn.disabled = true;
    btn.innerHTML = "Membuat akun...";
});
</script>

<script>
const roleSelect = document.querySelector("select[name='role']");
const daerahBox  = document.getElementById("daerahBox");

roleSelect.addEventListener("change", function () {
    if (this.value === "user") {
        daerahBox.style.display = "block";
    } else {
        daerahBox.style.display = "none";
    }
});
</script>

</body>
</html>
