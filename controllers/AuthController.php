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

    public static function isAdmin(){
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public static function register($nama, $username, $password, $role){
        global $pdo;
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (nama, username, password, role) VALUES (:nama, :username, :password, :role)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([
                'nama' => $nama,
                'username' => $username,
                'password' => $hashedPassword,
                'role' => $role
            ]);
            header("Location: index.php?action=login");
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "Username sudah terdaftar.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public static function logout(){
        session_destroy();
        header("Location: index.php");
    }


}



?>