<?php include_once "views/includes/header.php"; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0">Tambah Transaksi Barang</h4>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="index.php?action=save_transaksi" id="form_transaksi">
            
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Kode Barang</label>
                <select class="form-select" name="kode_barang" id="kode_barang" required>
                  <option value="">-- Pilih Barang --</option>
                  <?php foreach ($data_barang as $barang) : ?>
                    <option 
                      value="<?= $barang['kode_barang'] ?>"
                      data-nama="<?= $barang['nama_barang'] ?>"
                      data-kategori="<?= $barang['kategori'] ?>"
                      data-stok="<?= $barang['jumlah'] ?>"
                    >
                      <?= $barang['kode_barang'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Nama Barang</label>
                <input type="text" id="nama_barang" class="form-control" placeholder="Pilih barang dulu" readonly>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <input type="text" id="kategori" class="form-control" placeholder="Kategori" readonly>
              </div>

              <div class="col-md-6">
                <label class="form-label">Stok Tersedia</label>
                <input type="text" id="stok_tersedia" class="form-control" placeholder="0" readonly>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" name="status" id="status" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" 
                       placeholder="Masukkan jumlah" min="1" required>
                <small id="jumlah_warning" class="form-text"></small>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Keterangan</label>
              <textarea name="keterangan" class="form-control" rows="3" 
                        placeholder="Tambahkan keterangan (opsional)"></textarea>
            </div>

            <div class="d-flex justify-content-between">
              <a href="index.php?action=transaksi" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary" id="btn_submit">
                <i class="bi bi-save"></i> Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    const selectBarang = document.getElementById("kode_barang");
    const inputNama = document.getElementById("nama_barang");
    const inputKategori = document.getElementById("kategori");
    const inputStok = document.getElementById("stok_tersedia");
    const selectStatus = document.getElementById("status");
    const inputJumlah = document.getElementById("jumlah");
    const warningJumlah = document.getElementById("jumlah_warning");
    const btnSubmit = document.getElementById("btn_submit");
    const form = document.getElementById("form_transaksi");
    
    let stokTersedia = 0;

    // Event saat pilih barang
    selectBarang.addEventListener("change", function () {
        const selected = this.options[this.selectedIndex];
        
        if(this.value !== "") {
            inputNama.value = selected.getAttribute("data-nama") || "";
            inputKategori.value = selected.getAttribute("data-kategori") || "";
            stokTersedia = parseInt(selected.getAttribute("data-stok")) || 0;
            inputStok.value = stokTersedia;
            
            // Reset validasi
            inputJumlah.value = "";
            resetValidation();
        } else {
            inputNama.value = "";
            inputKategori.value = "";
            inputStok.value = "";
            stokTersedia = 0;
            inputJumlah.value = "";
            resetValidation();
        }
    });

    // Event saat pilih status
    selectStatus.addEventListener("change", function () {
        validateJumlah();
    });

    // Event saat input jumlah
    inputJumlah.addEventListener("input", function () {
        validateJumlah();
    });

    // Fungsi validasi jumlah
    function validateJumlah() {
        const status = selectStatus.value;
        const jumlah = parseInt(inputJumlah.value) || 0;

        // Reset dulu
        resetValidation();

        if(jumlah <= 0) {
            return; // Belum input apapun
        }

        if(status === "keluar") {
            if(jumlah > stokTersedia) {
                // Stok tidak cukup
                inputJumlah.classList.add("is-invalid");
                warningJumlah.classList.add("text-danger");
                warningJumlah.textContent = `Stok tidak cukup! Maksimal: ${stokTersedia}`;
                btnSubmit.disabled = true;
            } else {
                // Stok cukup
                inputJumlah.classList.add("is-valid");
                warningJumlah.classList.add("text-success");
                warningJumlah.textContent = `Sisa stok: ${stokTersedia - jumlah}`;
                btnSubmit.disabled = false;
            }
        } else if(status === "masuk") {
            // Transaksi masuk, tampilkan info
            inputJumlah.classList.add("is-valid");
            warningJumlah.classList.add("text-success");
            warningJumlah.textContent = `Stok akan menjadi: ${stokTersedia + jumlah}`;
            btnSubmit.disabled = false;
        }
    }

    // Reset validasi
    function resetValidation() {
        inputJumlah.classList.remove("is-valid", "is-invalid");
        warningJumlah.classList.remove("text-success", "text-danger");
        warningJumlah.textContent = "";
        btnSubmit.disabled = false;
    }

    // Validasi sebelum submit
    form.addEventListener("submit", function(e) {
        const status = selectStatus.value;
        const jumlah = parseInt(inputJumlah.value) || 0;
        const kodeBarang = selectBarang.value;

        // Cek apakah barang dipilih
        if(!kodeBarang) {
            e.preventDefault();
            alert("Silakan pilih barang terlebih dahulu!");
            selectBarang.focus();
            return false;
        }

        // Cek apakah status dipilih
        if(!status) {
            e.preventDefault();
            alert("Silakan pilih status transaksi!");
            selectStatus.focus();
            return false;
        }

        // Cek jumlah
        if(jumlah <= 0) {
            e.preventDefault();
            alert("Jumlah harus lebih dari 0!");
            inputJumlah.focus();
            return false;
        }

        // Validasi stok untuk transaksi keluar
        if(status === "keluar" && jumlah > stokTersedia) {
            e.preventDefault();
            alert(`Stok tidak mencukupi!\n\nStok tersedia: ${stokTersedia}\nJumlah diminta: ${jumlah}`);
            inputJumlah.focus();
            return false;
        }
    });
});
</script>

<?php include_once "views/includes/footer.php"; ?>