<?php 

require_once 'models/Barang.php';

class BarangController{
    public static function listBarang(){

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $data_barang = Barang::paginateBarang($offset, $limit);

        $total_data = Barang::countBarang();
        $total_pages = ceil($total_data / $limit);

        require 'views/pages/barang/index.php';
    }

    public static function addBarang(){
        require 'views/pages/barang/create.php';
    }

    public static function saveBarang(){
        $barang = $_POST['nama_barang'];
        $kategori = $_POST['kategori'];
        $jumlah = $_POST['jumlah'];
        $lokasi = $_POST['lokasi'];

        try {
            Barang::createBarang($barang , $kategori , $jumlah , $lokasi);
            header("Location: index.php?action=list_barang");
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }

}



?>