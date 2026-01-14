<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ================= PROTEKSI USER ================= */
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'User | Agro Lumintu Sejahtera'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* ===== RESET DASAR ===== */
        *{
            box-sizing:border-box;
        }
        html, body{
            margin:0;
            padding:0;
            height:100%;
        }

        /* ===== BODY LAYOUT ===== */
        body{
            font-family:'Segoe UI',sans-serif;
            background:#f4f7f6;
            display:flex;
            flex-direction:column;
        }

        /* ===== NAVBAR STYLE ===== */
        nav{
            width:100%;
            background:#fff;
        }

        .nav-container{
            max-width:1200px;
            margin:0 auto;
            padding:15px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .nav-brand{
            font-weight:700;
            font-size:18px;
            color:#3e8e61;
        }

        .nav-menu{
            display:flex;
            align-items:center;
            gap:20px;
        }

        .nav-user{
            color:#555;
            font-size:14px;
        }

        .nav-link{
            text-decoration:none;
            color:#333;
            font-size:14px;
        }

        .nav-link:hover{
            color:#5fb878;
        }

        .nav-logout{
            text-decoration:none;
            padding:8px 16px;
            border-radius:20px;
            background:#e74c3c;
            color:#fff;
            font-size:14px;
        }

        /* ===== WRAPPER KONTEN ===== */
        .main-wrapper{
            flex:1;
            width:100%;
            display:flex;
            justify-content:center;
            padding-top:90px; /* tinggi navbar */
        }

        /* ===== AREA KONTEN ===== */
        main{
            width:100%;
            max-width:1000px;
            padding:40px 20px;
        }

        /* ===== CARD ===== */
        .card{
            background:#fff;
            padding:25px;
            border-radius:14px;
            box-shadow:0 10px 25px rgba(0,0,0,.08);
            margin-bottom:30px;
        }

        /* ===== FORM ===== */
        input{
            width:100%;
            padding:12px;
            border-radius:8px;
            border:1px solid #ccc;
            margin-top:6px;
        }
        button{
            margin-top:15px;
            padding:12px 20px;
            background:#5fb878;
            color:#fff;
            border:none;
            border-radius:8px;
            cursor:pointer;
        }

        /* ===== TABLE ===== */
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }
        th, td{
            padding:12px;
            border-bottom:1px solid #eee;
        }
        th{
            background:#f0f5f2;
            text-align:left;
        }

        /* ===== ALERT ===== */
        .error{
            background:#ffe6e6;
            color:#b10000;
            padding:10px;
            border-radius:8px;
            margin-bottom:10px;
        }
        .success{
            background:#e6fff0;
            color:#0a7a3c;
            padding:10px;
            border-radius:8px;
            margin-bottom:10px;
        }
        select{
            width:100%;
            padding:12px;
            border-radius:8px;
            border:1px solid #ccc;
            margin-top:6px;
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield; /* Firefox */
        }
    </style>
</head>
<body>
