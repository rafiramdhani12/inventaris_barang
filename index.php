<?php
require_once "controllers/AuthController.php";
require_once "controllers/BarangController.php";
require_once "controllers/UserController.php";
require_once "controllers/DashboardController.php";
require_once "controllers/TransaksiController.php";
require_once "controllers/LaporanController.php";

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            AuthController::login($_POST['username'], $_POST['password']);
        } else {
            require "views/pages/login.php";
        }
        break;

    case 'logout':AuthController::logout();break;
    case 'dashboard':DashboardController::dashboard();break;
    case 'registrasi':UserController::createUser();break;

    case 'save_user':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            UserController::saveUser();
        }
        break;

    // barang routes
    case 'barang':BarangController::indexBarang();break;
    case 'add_barang':BarangController::addBarang();break;
    case 'save_barang':BarangController::saveBarang();break;
    case 'edit_barang': BarangController::editBarang($_GET['id']); break;
    case 'update_barang':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            BarangController::updateBarang();
        }
        break;
    case 'delete_barang':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            BarangController::deleteBarang();
        }
        break;

    // transaksi routes
    case 'transaksi':TransaksiController::indexTransaksi();break;
    case 'add_transaksi':TransaksiController::create();break;
    case 'save_transaksi':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            TransaksiController::saveTransaksi();
        }
        break;
    case 'edit_transaksi': TransaksiController::editTransaksi($_GET['id']); break;
    case 'update_transaksi':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            TransaksiController::updateTransaksi();
        }
        break;
    
    // user routes
    case 'karyawan':UserController::listUser();break;

    // laporan routes
    case 'laporan' :LaporanController::indexLaporan();break;

    default:
        echo "404 Not Found";
}
