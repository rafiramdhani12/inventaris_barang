<?php include_once "views/includes/header.php"; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0">Tambah karyawan</h4>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="index.php?action=registrasi">
            
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Nama Karyawan</label>
                <input type="text" name="nama" class="form-control" placeholder="nama karyawan" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" name="nama_barang" id="nama_barang" class="form-control" placeholder="nama barang" required>
              </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                <select class="form-select" name="role" id="role">
                    <option value="admin">Admin</option>
                    <option value="petugas" selected>Petugas</option>
                </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" id="kategori" class="form-control" placeholder="kategori barang">
                </div>
            </div>

            <div class="d-flex justify-content-between">
              <a href="index.php?action=dashboard" class="btn btn-secondary">
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
