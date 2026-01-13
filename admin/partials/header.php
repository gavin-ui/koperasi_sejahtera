<?php
// header global admin (HEADER ONLY)
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
/* ===============================
   HEADER ADMIN (FINAL)
================================ */
:root{
  --primary:#16a34a;
  --dark:#0f172a;
  --border:#e5e7eb;
}

body{
  margin:0;
  font-family:'Inter',system-ui,-apple-system;
}

/* HEADER */
.header{
  position:fixed;
  top:0;
  left:var(--sidebar-width);
  right:0;
  height:72px;
  background:rgba(255,255,255,.95);
  backdrop-filter:blur(8px);
  border-bottom:1px solid var(--border);
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 28px;
  z-index:1000;
  transition:.35s ease;
}

/* LEFT */
.header-left{
  display:flex;
  align-items:center;
  gap:14px;
}

.header-toggle{
  width:42px;
  height:42px;
  border-radius:14px;
  border:none;
  background:#f1f5f9;
  color:#0f172a;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:18px;
  cursor:pointer;
  transition:.25s;
}

.header-toggle:hover{
  background:var(--primary);
  color:white;
}

.header-title{
  font-size:18px;
  font-weight:700;
  color:var(--dark);
}

/* RIGHT */
.header-right{
  display:flex;
  align-items:center;
  gap:14px;
}

.header-user{
  display:flex;
  align-items:center;
  gap:10px;
  padding:6px 10px;
  border-radius:14px;
  transition:.25s;
}

.header-user:hover{
  background:#f1f5f9;
}

.header-user span{
  font-weight:600;
  font-size:14px;
  color:#334155;
}

.header-user img{
  width:38px;
  height:38px;
  border-radius:50%;
  border:2px solid #e5e7eb;
}
</style>
</head>

<body>

<!-- HEADER -->
<header class="header">
  <div class="header-left">
    <button class="header-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
      <i class="bi bi-layout-sidebar-inset"></i>
    </button>
    <div class="header-title">Dashboard</div>
  </div>

  <div class="header-right">
    <div class="header-user">
      <span>Admin</span>
      <img src="https://ui-avatars.com/api/?name=Admin" alt="Admin">
    </div>
  </div>
</header>
