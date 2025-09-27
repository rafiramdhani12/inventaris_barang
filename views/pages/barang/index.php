<?php require_once "views/includes/header.php"; require_once "views/components/navbar.php";?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Barang</h3>
    <a href="index.php?action=add_barang">
    <button class="btn btn-primary">
            + Tambah Barang
        </button>
    </a>
  </div>
  
  <form method="GET" action="index.php" class="row g-3 mb-4">
    <input type="hidden" name="action" value="barang">

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

   <div class="col-md-3">
  <select class="form-select" name="lokasi">
    <option value="">-- Semua Lokasi --</option>
    <?php foreach ($lokasi_list as $row): ?>
      <option value="<?= htmlspecialchars($row['lokasi']); ?>"
        <?= (isset($_GET['lokasi']) && $_GET['lokasi'] == $row['lokasi']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($row['lokasi']); ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>


    <!-- Tombol -->
    <div class="col-md-2 d-grid">
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>
    <div class="col-md-1 d-grid">
      <a href="index.php?action=barang" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  <div class="mb-3">
    <small class="text-muted">
      Menampilkan <?= (($page-1) * 10) + 1 ?> - <?= min($page * 10, $total_data) ?> 
      dari <?= $total_data ?> data
    </small>
  </div>

  <!-- Tabel Barang -->
<table class="table table-striped table-bordered">
  <thead class="table-dark">
    <tr>
      <th>Kode</th>
      <th>Nama Barang</th>
      <th>Kategori</th>
      <th>Jumlah</th>
      <th>Kondisi</th>
      <th>Lokasi</th>
      <th>Tgl Masuk</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($data_barang as $barang): ?>
    <tr>
      <td><?= htmlspecialchars($barang['kode_barang']); ?></td>
      <td><?= htmlspecialchars($barang['nama_barang']); ?></td>
      <td><?= htmlspecialchars($barang['kategori']); ?></td>
      <td><?= htmlspecialchars($barang['jumlah']); ?></td>
      <td><?= htmlspecialchars($barang['kondisi']); ?></td>
      <td><?= htmlspecialchars($barang['lokasi']); ?></td>
      <td><?= htmlspecialchars($barang['create_time']); ?></td>
      <td>
        <a class="btn btn-sm btn-warning" href="index.php?action=edit_barang&id=<?= $barang['id']; ?>">Edit</a>
        <a class="btn btn-sm btn-danger" href="index.php?action=delete_barang&id=<?= $barang['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Pagination -->
  <?php if($total_pages > 1): ?>
  <nav aria-label="Pagination">
    <ul class="pagination justify-content-center">
      <?php if($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?action=barang&halaman=<?= $page - 1; ?>">
            &laquo; Previous
          </a>
        </li>
      <?php else: ?>
        <li class="page-item disabled"><span class="page-link">&laquo; Previous</span></li>
      <?php endif; ?>

      <?php 
      $start = max(1, $page - 2);
      $end = min($total_pages, $page + 2);
      if($page <= 3) { $end = min($total_pages, 5); }
      if($page > $total_pages - 3) { $start = max(1, $total_pages - 4); }
      ?>
      
      <?php for($i = $start; $i <= $end; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
          <a class="page-link" href="index.php?action=barang&halaman=<?= $i; ?>">
            <?= $i; ?>
          </a>
        </li>
      <?php endfor; ?>

      <?php if($page < $total_pages): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?action=barang&halaman=<?= $hpage + 1; ?>">
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
      Halaman <?= $page ?> dari <?= $total_pages ?> 
      (Total: <?= $total_data ?> transaksi)
    </small>
  </div>
  <?php endif; ?>

</div>

<?php  require_once "views/includes/footer.php";?>