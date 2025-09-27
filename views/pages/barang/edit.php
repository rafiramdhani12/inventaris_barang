<?php include_once "views/includes/header.php"; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0">Tambah Data Barang</h4>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="index.php?action=update_barang">
            
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="id_dokter" class="form-label">Kode Barang</label>
                <input type="text" name="kode_barang" class="form-control"  required value="<?= $barang['kode_barang'] ?>?">
              </div>
              <div class="col-md-6">
                <label for="nama" class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="<?= $barang['nama_barang'] ?>" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="spesialis" class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control" value="<?= $barang['kategori'] ?>">
              </div>
              <div class="col-md-6">
                <label for="no_hp" class="form-label">Jumlah</label>
                <input type="text" name="jumlah" class="form-control" id="no_hp" value="<?= $barang['jumlah'] ?>">
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="hari" class="form-label">Kondisi</label>
                <i><?= $barang['kondisi'] ?> , kondisi sebelum edit</i>
                <select class="form-select" aria-label="Default select example" name="kondisi">
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                    <option value="hilang">Hilang</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="jam" class="form-label">Lokasi</label>
                <input type="text" name="lokasi" class="form-control" id="jam" value="<?= $barang['lokasi'] ?>">
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <a href="index.php?action=barang" class="btn btn-secondary">
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
