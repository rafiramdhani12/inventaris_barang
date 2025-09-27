<?php require_once "views/includes/header.php"; require_once "views/components/navbar.php";?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Transaksi</h3>
    <a href="index.php?action=add_transaksi">
      <button class="btn btn-primary">
        + Tambah Transaksi
      </button>
    </a>
  </div>

  <!-- 🔹 Form Search & Filter -->
  <form method="GET" action="index.php" class="row g-3 mb-4">
    <input type="hidden" name="action" value="transaksi">

    <!-- Search keyword -->
    <div class="col-md-3">
      <input type="text" name="keyword" class="form-control" 
             placeholder="Cari nama/kode/keterangan"
             value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
    </div>

    <!-- Filter tanggal -->
    <div class="col-md-3">
      <input type="date" name="start_date" class="form-control"
             value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>">
    </div>
    <div class="col-md-3">
      <input type="date" name="end_date" class="form-control"
             value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>">
    </div>

    <!-- Tombol -->
    <div class="col-md-2 d-grid">
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>
    <div class="col-md-1 d-grid">
      <a href="index.php?action=transaksi" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  <!-- Info Pagination -->
  <div class="mb-3">
    <small class="text-muted">
      Menampilkan <?= (($halaman-1) * 10) + 1 ?> - <?= min($halaman * 10, $total_data) ?> 
      dari <?= $total_data ?> data
    </small>
  </div>

  <!-- Tabel Transaksi -->
  <table class="table table-striped table-bordered">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nama Barang</th>
        <th>Jumlah</th>
        <th>Status</th>
        <th>Keterangan</th>
        <th>User</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if(empty($data_transaksi)): ?>
        <tr>
          <td colspan="8" class="text-center">Tidak ada data transaksi</td>
        </tr>
      <?php else: ?>
        <?php 
        $no = (($halaman - 1) * 10) + 1;
        foreach($data_transaksi as $transaksi): 
        ?>
        <?php 
          $row_class = '';
          $badge_class = '';
          
          if(strtolower($transaksi['status']) == 'masuk') {
            $row_class = 'transaksi-masuk';
            $badge_class = 'badge-masuk';
          } elseif(strtolower($transaksi['status']) == 'keluar') {
            $row_class = 'transaksi-keluar';
            $badge_class = 'badge-keluar';
          }
        ?>
        <tr class="<?= $row_class; ?>">
          <td><?= $no++; ?></td>
          <td><?= date('d/m/Y H:i', strtotime($transaksi['create_time'])); ?></td>
          <td><?= htmlspecialchars($transaksi['nama_barang']); ?></td>
          <td><strong><?= htmlspecialchars($transaksi['jumlah']); ?></strong></td>
          <td>
            <span class="badge <?= $badge_class; ?>">
              <?= strtoupper(htmlspecialchars($transaksi['status'])); ?>
            </span>
          </td>
          <td><?= htmlspecialchars($transaksi['keterangan']); ?></td>
          <td><?= htmlspecialchars($transaksi['username']); ?></td>
          <td>
            <button class="btn btn-sm btn-warning" onclick="editTransaksi(<?= $transaksi['id']; ?>)">
              Edit
            </button>
            <button class="btn btn-sm btn-danger" onclick="deleteTransaksi(<?= $transaksi['id']; ?>)">
              Delete
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <?php if($total_pages > 1): ?>
  <nav aria-label="Pagination">
    <ul class="pagination justify-content-center">
      <?php if($halaman > 1): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?action=transaksi&halaman=<?= $halaman - 1; ?>">
            &laquo; Previous
          </a>
        </li>
      <?php else: ?>
        <li class="page-item disabled"><span class="page-link">&laquo; Previous</span></li>
      <?php endif; ?>

      <?php 
      $start = max(1, $halaman - 2);
      $end = min($total_pages, $halaman + 2);
      if($halaman <= 3) { $end = min($total_pages, 5); }
      if($halaman > $total_pages - 3) { $start = max(1, $total_pages - 4); }
      ?>
      
      <?php for($i = $start; $i <= $end; $i++): ?>
        <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
          <a class="page-link" href="index.php?action=transaksi&halaman=<?= $i; ?>">
            <?= $i; ?>
          </a>
        </li>
      <?php endfor; ?>

      <?php if($halaman < $total_pages): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?action=transaksi&halaman=<?= $halaman + 1; ?>">
            Next &raquo;
          </a>
        </li>
      <?php else: ?>
        <li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>
      <?php endif; ?>
    </ul>
  </nav>

  <div class="text-center mt-3">
    <small class="text-muted">
      Halaman <?= $halaman ?> dari <?= $total_pages ?> 
      (Total: <?= $total_data ?> transaksi)
    </small>
  </div>
  <?php endif; ?>

</div>


<?php require_once "views/includes/footer.php";?>
