<?php require_once "views/includes/header.php"; require_once "views/components/navbar.php";?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Barang</h3>
    <a href="index.php?action=add_barang">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBarangModal">
            + Tambah Barang
        </button>
    </a>
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
        <button class="btn btn-sm btn-warning">Edit</button>
        <button class="btn btn-sm btn-danger">Delete</button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Pagination -->
<nav>
  <ul class="pagination">
    <?php for($i = 1; $i <= $total_pages; $i++): ?>
      <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
        <a class="page-link" href="index.php?action=barang&halaman=<?= $i; ?>">
          <?= $i; ?>
        </a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>

</div>

<?php  require_once "views/includes/footer.php";?>