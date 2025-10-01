<?php
require_once 'models/Transaksi.php';
require_once 'models/Barang.php';

class TransaksiController {
    
    public static function indexTransaksi(){
        $halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
        $limit = 10;
        $offset = ($halaman - 1) * $limit;

        // param filter
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

        // default
        $data_transaksi = [];
        $total_data = 0;

        // find query
        if(!empty($keyword)){
            $data_transaksi = Transaksi::searchTransaksi($keyword, $offset, $limit);
            $total_data = Transaksi::countSearch($keyword);
        } else if(!empty($status)){
            $data_transaksi = Transaksi::filterByStatus($status, $offset, $limit);
            $total_data = Transaksi::countByStatus($status);
        } else if(!empty($start_date) && !empty($end_date)){
            $data_transaksi = Transaksi::getTransaksiByDateRange($start_date, $end_date, $offset, $limit);
            $total_data = Transaksi::countByDateRange($start_date, $end_date);
        } else {
            $data_transaksi = Transaksi::paginateTransaksi($offset, $limit);
            $total_data = Transaksi::countTransaksi();
        }

        $total_pages = ceil($total_data / $limit);

        require_once 'views/pages/transaksi/index.php';
    }

    public static function create(){
        $data_barang = Barang::getBarang();
        require_once 'views/pages/transaksi/create.php';
    }

    // 🔥 DIPERBAIKI - Tambah Error Handling
    public static function saveTransaksi(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $kode_barang = $_POST['kode_barang'];
                $status = $_POST['status'];
                $jumlah = (int)$_POST['jumlah'];
                $keterangan = $_POST['keterangan'];
                $user_id = $_SESSION['user_id'];

                // Validasi input
                if(empty($kode_barang) || empty($status) || $jumlah <= 0) {
                    throw new Exception("Data tidak lengkap atau jumlah tidak valid!");
                }

                // Panggil method create transaksi (sudah ada logic update stok di dalamnya)
                Transaksi::createTransaksi($kode_barang, $status, $jumlah, $keterangan, $user_id);
                
                $_SESSION['success'] = "Transaksi berhasil! Stok barang telah diupdate.";
                header('Location: index.php?action=transaksi');
                exit;
                
            } catch(Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
                header('Location: index.php?action=add_transaksi');
                exit;
            }
        }
    }

    public static function editTransaksi($id){
        $transaksi = Transaksi::getTransaksiById($id);
        $data_barang = Barang::getBarang(); // 🔥 DIPERBAIKI - Harusnya getBarang() bukan getBarangById()
        require_once 'views/pages/transaksi/edit.php';
    }

    // 🔥 DIPERBAIKI - Tambah Error Handling
    public static function updateTransaksi(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $id = (int)$_POST['id'];
                $kode_barang = $_POST['kode_barang'];
                $status = $_POST['status'];
                $jumlah = (int)$_POST['jumlah'];
                $keterangan = $_POST['keterangan'];
                $user_id = $_SESSION['user_id'];

                // Validasi input
                if(empty($kode_barang) || empty($status) || $jumlah <= 0) {
                    throw new Exception("Data tidak lengkap atau jumlah tidak valid!");
                }

                Transaksi::updateTransaksi($id, $kode_barang, $status, $jumlah, $keterangan, $user_id);
                
                $_SESSION['success'] = "Transaksi berhasil diupdate! Stok barang telah disesuaikan.";
                header('Location: index.php?action=transaksi');
                exit;
                
            } catch(Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
                header('Location: index.php?action=transaksi');
                exit;
            }
        }
    }

    // 🔥 DIPERBAIKI - Tambah Error Handling
    public static function deleteTransaksi(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $id = (int)$_POST['id'];
                
                Transaksi::deleteTransaksi($id);
                
                $_SESSION['success'] = "Transaksi berhasil dihapus! Stok barang telah dikembalikan.";
                header('Location: index.php?action=transaksi');
                exit;
                
            } catch(Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
                header('Location: index.php?action=transaksi');
                exit;
            }
        }
    }
}
?>