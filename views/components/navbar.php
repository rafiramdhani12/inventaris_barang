<?php require_once 'controllers/AuthController.php'; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Inventaris Barang</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php?action=dashboard">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?action=barang">Barang</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?action=transaksi">Transaksi</a></li>
        <?php if(AuthController::isAdmin()): ?>
        <li class="nav-item"><a class="nav-link" href="index.php?action=registrasi">Tambah Karyawan</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?action=karyawan">Daftar Karyawan</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="index.php?action=laporan">Laporan</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="index.php?action=logout">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>