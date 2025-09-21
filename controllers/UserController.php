<?php 

require_once 'models/User.php';

class UserController{
    public static function listUser(){
        $users = User::getUser();
        require 'views/user/index.php';
    }

    public static function createUser(){
        require 'views/registrasi.php';
    }

    public static function saveUser(){
        $nama = $_POST['nama'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        try {
            User::createUser($nama , $username , $password, $role);
            header("Location: views/login.php");
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }

}


?>