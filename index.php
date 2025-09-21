<?php
require_once "controllers/AuthController.php";
require_once "controllers/BarangController.php";
require_once "controllers/UserController.php";
require_once "controllers/DashboardController.php";

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

    case 'list_barang':BarangController::listBarang();break;
    case 'add_barang':BarangController::addBarang();break;
    case 'save_barang':BarangController::saveBarang();break;


    default:
        echo "404 Not Found";
}
