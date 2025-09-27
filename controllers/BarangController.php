<?php 

require_once 'models/Barang.php';

class BarangController{
  public static function indexBarang(){

    $search = $_GET['search'] ?? '';
    $page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // param filter
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $lokasi = isset($_GET['lokasi']) ? $_GET['lokasi'] : '';
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    // default
    $data_barang = [];
    $total_data = 0;

    // find query

    if(!empty($keyword)){
        $data_barang = Barang::searchBarang($keyword);
        $total_data = Barang::countSearch($keyword);
    } else if(!empty($start_date) && !empty($end_date)){
        $data_barang = Barang::getBarangByDateRange($start_date, $end_date, $offset, $limit);
        $total_data = Barang::countByDateRange($start_date, $end_date);
    } else if($lokasi){
        $data_barang = Barang::getByLokasi($lokasi, $offset, $limit);
        $total_data = Barang::countByLokasi($lokasi);
    } else {
        $data_barang = Barang::paginateBarang($offset, $limit);
        $total_data = Barang::countBarang();
    }

    $lokasi_list = Barang::getDistinctLokasi();

    $total_pages = ceil($total_data / $limit);
    require 'views/pages/barang/index.php';

}

    public static function addBarang(){
        require 'views/pages/barang/create.php';
    }

    public static function saveBarang(){
        $kode_barang = $_POST['kode_barang'];
        $barang = $_POST['nama_barang'];
        $kategori = $_POST['kategori'];
        $jumlah = $_POST['jumlah'];
        $kondisi = $_POST['kondisi'];
        $lokasi = $_POST['lokasi'];
        $updated_by = $_SESSION['user_id'];

        try {
            Barang::createBarang($kode_barang, $barang , $kategori , $jumlah , $kondisi, $lokasi, $updated_by);
            header("Location: index.php?action=list_barang");
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }

    public static function searchBarang(){
        $search = $_GET['search'] ?? '';
        $data_barang = Barang::searchBarang($search);
        require 'views/pages/barang/index.php';
    }

    public static function editBarang($id){
        $barang = Barang::getBarangById($id);
        require 'views/pages/barang/edit.php';
    }

    public static function updateBarang(){
        $id = $_POST['id'];
        $kode_barang = $_POST['kode_barang'];
        $barang = $_POST['nama_barang'];
        $kategori = $_POST['kategori'];
        $jumlah = $_POST['jumlah'];
        $kondisi = $_POST['kondisi'];
        $lokasi = $_POST['lokasi'];
        $updated_by = $_SESSION['user_id'];

        try {
            Barang::updateBarang($id, $kode_barang, $barang , $kategori , $jumlah , $kondisi, $lokasi, $updated_by);
            header("Location: index.php?action=list_barang");
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }

    public static function deleteBarang(){
        $id = $_POST['id'];
        try {
            Barang::deleteBarang($id);
            header("Location: index.php?action=barang");
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

}



?>