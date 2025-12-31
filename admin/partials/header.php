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
body{
    background:#f6f8fd;
}
.card-dashboard{
    border-radius:15px;
    color:#fff;
}
.sidebar{
    width:260px;
    height:100vh;
    background:#0b1226;
    position:fixed;
    left:0;
    top:0;
    color:white;
    padding-top:20px;
}
.sidebar a{
    color:#dcdcdc;
    text-decoration:none;
    display:block;
    padding:10px 20px;
}
.sidebar a:hover{
    background:#1c274a;
}
.content{
    margin-left:260px;
    padding:20px;
}
</style>
</head>
<body>
