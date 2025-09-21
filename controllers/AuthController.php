<?php 
session_start();
require_once 'config/db.php';

class AuthController{
    public static function login($username , $password){
        global $pdo;
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch($pdo::FETCH_ASSOC);

        if($user && password_verify($password,$user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php?action=dashboard");
        } else {
            echo "Username atau password salah";
        }
    }

    public static function logout(){
        session_destroy();
        header("Location: views/login.php");
    }


}



?>