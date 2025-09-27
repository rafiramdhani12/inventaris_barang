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

    public static function saveTransaksi(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $kode_barang = $_POST['kode_barang'];
            $status = $_POST['status'];
            $jumlah = $_POST['jumlah'];
            $keterangan = $_POST['keterangan'];
            $user_id = $_SESSION['user_id'];

            Transaksi::createTransaksi($kode_barang, $status, $jumlah , $keterangan , $user_id);  
            header('Location: index.php?action=transaksi');
        }
    }

    public static function editTransaksi($id){
        $transaksi = Transaksi::getTransaksiById($id);
        $barang = Barang::getBarangById($id);
        require_once 'views/pages/transaksi/edit.php';
    }

    public static function updateTransaksi(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $kode_barang = $_POST['kode_barang'];
            $status = $_POST['status'];
            $jumlah = $_POST['jumlah'];
            $keterangan = $_POST['keterangan'];
            $user_id = $_SESSION['user_id'];

            Transaksi::updateTransaksi($id, $kode_barang, $status, $jumlah , $keterangan, $user_id);  
            header('Location: index.php?action=transaksi');
        }
    }

    public static function deleteTransaksi(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            Transaksi::deleteTransaksi($id);
            header('Location: index.php?action=transaksi');
        }
    }

    // public static function save(){
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $id_barang = $_POST['id_barang'];
    //         $jumlah = $_POST['jumlah'];
    //         $tanggal = $_POST['tanggal'];

    //         Transaksi::createTransaksi($id_barang, $jumlah, $tanggal);
    //         header('Location: index.php?action=transaksi');
    //     }
    // }

}



?>