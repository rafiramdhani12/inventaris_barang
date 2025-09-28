<?php require_once "views/includes/header.php"; ?>
<?php require_once "views/components/navbar.php"; ?>

<div class="container my-4">
  <!-- Judul halaman -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Laporan Transaksi Barang</h3>
    <button onclick="window.print()" class="btn btn-success">
      🖨 Cetak Laporan
    </button>
  </div>

  <!-- Form filter -->
  <form method="GET" action="index.php" class="row g-3 mb-4">
    <input type="hidden" name="action" value="laporan">

    <div class="col-md-3">
      <label class="form-label">Tanggal Mulai</label>
      <input type="date" name="start_date" class="form-control"
             value="<?= $_GET['start_date'] ?? '' ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Tanggal Akhir</label>
      <input type="date" name="end_date" class="form-control"
             value="<?= $_GET['end_date'] ?? '' ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Lokasi</label>
      <select class="form-select" name="lokasi">
        <option value="">-- Semua Lokasi --</option>
        <?php foreach($lokasi_list as $lokasi): ?>
          <option value="<?= htmlspecialchars($lokasi); ?>"
            <?= (isset($_GET['lokasi']) && $_GET['lokasi'] == $lokasi) ? 'selected' : '' ?>>
            <?= htmlspecialchars($lokasi); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3 d-flex align-items-end">
      <button type="submit" class="btn btn-primary me-2">Filter</button>
      <a href="index.php?action=laporan" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  <!-- Header laporan -->
  <div class="text-center mb-4">
    <h4>Laporan Transaksi Barang</h4>
    <p>
      Periode: <?= $_GET['start_date'] ?? '-' ?> s/d <?= $_GET['end_date'] ?? '-' ?><br>
      Lokasi: <?= $_GET['lokasi'] ?? 'Semua Lokasi' ?>
    </p>
  </div>

  <!-- Tabel laporan -->
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nama Barang</th>
        <th>Jumlah</th>
        <th>Status</th>
        <th>Keterangan</th>
        <th>Lokasi</th>
      </tr>
    </thead>
    <tbody>
      <?php if(empty($data_laporan)): ?>
        <tr>
          <td colspan="7" class="text-center">Tidak ada data</td>
        </tr>
      <?php else: ?>
        <?php $no = 1; foreach($data_laporan as $row): ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= date('d/m/Y H:i', strtotime($row['create_time'])); ?></td>
          <td><?= htmlspecialchars($row['nama_barang']); ?></td>
          <td><?= htmlspecialchars($row['jumlah']); ?></td>
          <td><?= strtoupper(htmlspecialchars($row['status'])); ?></td>
          <td><?= htmlspecialchars($row['keterangan']); ?></td>
          <td><?= htmlspecialchars($row['lokasi']); ?></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- CSS khusus saat print -->
<style>
@media print {
  button, form, nav, .navbar {
    display: none !important;
  }
  table {
    border-collapse: collapse;
    width: 100%;
  }
  th, td {
    border: 1px solid #000 !important;
    padding: 6px;
    font-size: 12px;
  }
}
</style>

<?php require_once "views/includes/footer.php"; ?>
