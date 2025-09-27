<?php 

require_once 'models/Barang.php';
require_once 'models/Transaksi.php';
require_once 'middleware/Auth.php';

class DashboardController{
    public static function dashboard(){
        $data_barang = Barang::limitBarang(5);
        $total_barang = Barang::countBarang();
        $total_rusak = Barang::countBarangRusak();
        $total_baik = Barang::countBarangBaik();
        $total_lokasi = Barang::countLokasi();
        $total_hilang = Barang::countBarangHilang();

        Auth::requireLogin();

        $data_transaksi = Transaksi::limitTransaksi(5);        
        require 'views/pages/dashboard.php';
    }
}


?>