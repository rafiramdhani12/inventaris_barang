<?php require_once "views/includes/header.php"; require_once "views/components/navbar.php";?>

<!-- Content -->
<div class="container my-4">
  <div class="row">
    <!-- Cards -->
    <div class="col-md-3">
      <div class="card text-bg-primary mb-3">
        <div class="card-body">
          <h5 class="card-title">Total Barang</h5>
          <p class="card-text fs-4"><?= $total_barang?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-bg-success mb-3">
        <div class="card-body">
          <h5 class="card-title">Barang Baik</h5>
          <p class="card-text fs-4"><?= $total_baik ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-bg-warning mb-3">
        <div class="card-body">
          <h5 class="card-title">Barang Rusak</h5>
          <p class="card-text fs-4"><?= $total_rusak ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-bg-info mb-3">
        <div class="card-body">
          <h5 class="card-title">Lokasi</h5>
          <p class="card-text fs-4"><?= $total_lokasi?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Barang Terbaru -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">Barang Terbaru</div>
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Kondisi</th>
            <th>lokasi</th>
          </tr>
        </thead>
        <?php foreach($data_barang as $barang): ?>
        <tbody>
          <tr>
            <td><?= htmlspecialchars( $barang['kode_barang'] );?></td>
            <td><?= htmlspecialchars( $barang['nama_barang'] );?></td>
            <td><?= htmlspecialchars( $barang['kategori'] );?></td>
            <td><?= htmlspecialchars( $barang['jumlah'] );?></td>
            <td><?= htmlspecialchars( $barang['kondisi'] );?></td>
            <td><?= htmlspecialchars( $barang['lokasi'] );?></td>
          </tr>
        </tbody>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <!-- Tabel Transaksi Terbaru -->
  <div class="card">
    <div class="card-header bg-success text-white">Transaksi Terbaru</div>
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Barang</th>
            <th>Jenis</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <?php foreach($data_transaksi as $transaksi): ?>
        <tbody>
          <tr>
            <td><?= htmlspecialchars( $transaksi['create_time'] );?></td>
            <td><?= htmlspecialchars( $transaksi['nama_barang'] );?></td>
            <td><?= htmlspecialchars( $transaksi['jenis'] );?></td>
            <td><?= htmlspecialchars( $transaksi['jumlah'] );?></td>
            <td><?= htmlspecialchars( $transaksi['keterangan'] );?></td>
          </tr>
        </tbody>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</div>


<?php require_once "views/includes/footer.php"; require_once "views/components/footer.php";?>