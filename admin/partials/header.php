<?php
// header global admin
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
:root{
  --primary:#4f46e5;
  --dark:#0b1226;
  --dark-soft:#111a3a;
  --accent:#22c55e;
  --text:#cbd5e1;
}

/* GLOBAL */
body{
  background:linear-gradient(180deg,#f6f8fd,#eef2ff);
  font-family:'Inter',system-ui,-apple-system;
  overflow-x:hidden;
}

/* =====================
   SIDEBAR
===================== */
.sidebar{
  width:260px;
  height:100vh;
  position:fixed;
  left:0;
  top:0;
  background:linear-gradient(180deg,#0b1226,#111a3a);
  color:#fff;
  padding:20px 0;
  overflow-y:auto;
  scrollbar-width:thin;
  scrollbar-color:#4f46e5 transparent;
  box-shadow:8px 0 30px rgba(0,0,0,.25);
  z-index:999;
}

/* Custom Scrollbar */
.sidebar::-webkit-scrollbar{
  width:6px;
}
.sidebar::-webkit-scrollbar-thumb{
  background:#4f46e5;
  border-radius:10px;
}
.sidebar::-webkit-scrollbar-track{
  background:transparent;
}

/* Logo / Title */
.sidebar .brand{
  text-align:center;
  font-size:20px;
  font-weight:700;
  letter-spacing:1px;
  margin-bottom:25px;
  color:#fff;
}

/* Menu */
.sidebar a{
  color:var(--text);
  text-decoration:none;
  display:flex;
  align-items:center;
  gap:12px;
  padding:12px 22px;
  margin:4px 12px;
  border-radius:12px;
  transition:.35s ease;
  font-weight:500;
}

/* Icon */
.sidebar a i{
  font-size:18px;
  min-width:22px;
}

/* Hover */
.sidebar a:hover{
  background:linear-gradient(135deg,#4f46e5,#6366f1);
  color:#fff;
  transform:translateX(6px);
  box-shadow:0 8px 25px rgba(79,70,229,.35);
}

/* Active Menu (jika mau) */
.sidebar a.active{
  background:linear-gradient(135deg,#22c55e,#16a34a);
  color:#fff;
}

/* =====================
   HEADER
===================== */
.header{
  position:fixed;
  left:260px;
  top:0;
  right:0;
  height:70px;
  background:rgba(255,255,255,.85);
  backdrop-filter:blur(12px);
  box-shadow:0 10px 30px rgba(0,0,0,.08);
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 30px;
  z-index:998;
}

/* Header Title */
.header h5{
  margin:0;
  font-weight:700;
  color:#1e293b;
}

/* User Info */
.header .user{
  display:flex;
  align-items:center;
  gap:12px;
}

.header .user img{
  width:38px;
  height:38px;
  border-radius:50%;
  border:2px solid var(--primary);
}

/* =====================
   CONTENT
===================== */
.content{
  margin-left:260px;
  padding:100px 30px 30px;
}

/* =====================
   CARD EFFECT
===================== */
.card-dashboard{
  border-radius:20px;
  box-shadow:0 20px 40px rgba(0,0,0,.1);
  transition:.4s;
}
.card-dashboard:hover{
  transform:translateY(-6px);
  box-shadow:0 30px 60px rgba(0,0,0,.15);
}
</style>
</head>
<body>
