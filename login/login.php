<?php
session_start();
require "../koneksi.php";

/* ================= CEK SUDAH LOGIN ================= */
if (isset($_SESSION['login']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/index.php");
    } else {
        header("Location: ../user/index.php");
    }
    exit;
}

$error = "";

/* ================= PROSES LOGIN ================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['login']     = true;
        $_SESSION['id']        = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];
        $_SESSION['daerah_id']= $user['daerah_id'];


        /* ===== REDIRECT BERDASARKAN ROLE ===== */
        if ($user['role'] === 'admin') {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../user/index.php");
        }
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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Agro Lumintu Sejahtera</title>

<style>
:root {
    --bg: #f4f7f6;
    --card: #fff;
    --text: #222;
    --accent: #5fb878;
}
.dark {
    --bg:#111;
    --card:#1c1c1c;
    --text:#eee;
}

*{box-sizing:border-box;font-family:'Segoe UI'}

body{
    background:var(--bg);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0;
}

.container{
    width:min(100%,1000px);
    display:grid;
    grid-template-columns:1fr 1fr;
    background:var(--card);
    border-radius:18px;
    box-shadow:0 30px 70px rgba(0,0,0,.2);
    overflow:hidden;
    position:relative;
}

.login-box{
    padding:50px;
}

.login-box h2{margin:0;font-size:28px;color:var(--text)}
.login-box p{color:#777}

.back-btn{
    position:relative;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:12px 26px;
    margin-bottom:30px;

    border-radius:999px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    letter-spacing:.2px;

    color:#2f6f4e;
    background:
        linear-gradient(135deg,#ffffff,#eef8f1);
    border:1px solid rgba(95,184,120,.35);

    box-shadow:
        0 18px 45px rgba(0,0,0,.12),
        inset 0 1px 0 rgba(255,255,255,.8);

    overflow:hidden;
    transition:all .4s ease;
}

/* Shine effect */
.back-btn .shine{
    position:absolute;
    inset:0;
    background:
        linear-gradient(
            120deg,
            transparent 20%,
            rgba(255,255,255,.6),
            transparent 80%
        );
    transform:translateX(-120%);
}

/* Text */
.back-btn .text{
    position:relative;
    z-index:1;
}

/* Hover */
.back-btn:hover{
    transform:translateY(-3px) scale(1.02);
    box-shadow:0 30px 65px rgba(0,0,0,.18);
}

.back-btn:hover .shine{
    transform:translateX(120%);
    transition:transform .8s ease;
}

/* Active click */
.back-btn:active{
    transform:scale(.97);
}

/* Dark mode support */
.dark .back-btn{
    background:linear-gradient(135deg,#1e1e1e,#2a2a2a);
    border-color:#3e8e61;
    color:#dff6ea;
}

input{
    width:100%;
    padding:14px;
    border-radius:10px;
    border:1px solid #ccc;
    margin-bottom:15px;
}

button{
    width:100%;
    padding:14px;
    border:none;
    background:linear-gradient(135deg,#5fb878,#4fa368);
    color:#fff;
    font-weight:600;
    border-radius:12px;
    cursor:pointer;
}

.brand{
    background:linear-gradient(160deg,#5fb878,#3e8e61);
    color:#fff;
    padding:60px;
}

.brand img{
    width:220px;
    margin-bottom:30px;
    filter:drop-shadow(0 20px 40px rgba(0,0,0,.4));
}

.error{
    background:#ffe6e6;
    padding:10px;
    border-radius:10px;
    margin-bottom:15px;
    color:#b10000;
}

.toggle{
    position:absolute;
    top:20px;
    right:20px;
    cursor:pointer;
}

.loading{
    display:none;
    text-align:center;
    margin-top:10px;
}

/* RESPONSIVE */
@media(max-width:768px){
    .container{
        grid-template-columns:1fr;
    }
    .brand{
        text-align:center;
    }
}
</style>
</head>

<body>

<div class="toggle" onclick="toggleDark()">🌙</div>

<div class="container">
<div class="login-box">

<!-- TOMBOL KEMBALI -->
<a href="../index.php" class="back-btn">
    <span class="shine"></span>
    <span class="text">Kembali ke Beranda</span>
</a>

<h2>Masuk</h2>
<p>Masukkan username dan password Anda</p>

<?php if($error): ?>
<div class="error"><?= $error ?></div>
<?php endif ?>

<form method="POST" onsubmit="loading()">
<input name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button>Login</button>
<div class="loading" id="load">⏳ Memproses...</div>
</form>

<p style="margin-top:15px">
Belum punya akun? <a href="register.php">Buat akun</a>
</p>

</div>

<div class="brand">
<img src="assets/Screenshot_2025-12-30_094123-removebg-preview.png">
<h1>Agro Lumintu<br><span>Sejahtera</span></h1>
</div>
</div>

<script>
function toggleDark(){
    document.body.classList.toggle("dark");
}
function loading(){
    document.getElementById("load").style.display="block";
}
</script>

</body>
</html>
