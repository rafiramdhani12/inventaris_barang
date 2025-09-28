<?php

require_once 'models/Barang.php';
require_once 'models/Transaksi.php';
require_once 'models/User.php';

class LaporanController {

    public static function indexLaporan(){

        $start_date = $_GET['start_date'] ?? '';
        $end_date   = $_GET['end_date'] ?? '';
        $lokasi     = $_GET['lokasi'] ?? '';

        // default kosong
        $data_laporan = [];

        if (!empty($start_date) && !empty($end_date)) {
            $data_laporan = Transaksi::getTransaksiByDateRange($start_date, $end_date);
        } elseif (!empty($lokasi)) {
            $data_laporan = Barang::getByLokasi($lokasi);
        } else {
            $data_laporan = Transaksi::getTransaksi(); 
        }


        require_once 'views/pages/laporan/index.php';
    }

}



?>