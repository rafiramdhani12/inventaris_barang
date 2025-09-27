<?php include_once "views/includes/header.php"; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0">Tambah Transaksi Barang</h4>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="index.php?action=save_barang">
            
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Kode Barang</label>
                <select class="form-select" name="kode_barang" id="kode_barang">
                  <option value="">-- Pilih Barang --</option>
                  <?php foreach ($data_barang as $barang) : ?>
                    <option 
                      value="<?= $barang['kode_barang'] ?>"
                      data-nama="<?= $barang['nama_barang'] ?>"
                      data-kategori="<?= $barang['kategori'] ?>"
                    >
                      <?= $barang['kode_barang'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" class="form-control" placeholder="nama barang" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" id="kategori" class="form-control" placeholder="kategori barang">
              </div>
              <div class="col-md-6">
                <label class="form-label">Jumlah</label>
                <input type="text" name="jumlah" class="form-control" placeholder="jumlah barang">
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Keterangan</label>
                <input type="text" name="keterangan" class="form-control" placeholder="keterangan barang">
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <a href="index.php?action=transaksi" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>



<?php include_once "views/includes/footer.php"; ?>
