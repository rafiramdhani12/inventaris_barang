<?php 

class User{
    public static function getUser(){
        global $pdo;
        $sql = "SELECT * FROM users";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll($pdo::FETCH_ASSOC);
    }


    public static function getUserById($id){
        global $pdo;
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch($pdo::FETCH_ASSOC);
    }


    public static function createUser($nama , $username , $password, $role){
        global $pdo;
        $sql = "INSERT INTO users (nama ,username, password, role) VALUES (:nama,:username, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nama' => $nama,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role
        ]);
    }

    public static function getUserByUsername($username){
    global $pdo;
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    return $stmt->fetch($pdo::FETCH_ASSOC);
}

public static function updateUser($id, $nama, $username, $password, $role){
    global $pdo;
    $sql = "UPDATE users 
            SET nama = :nama, username = :username, password = :password, role = :role 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'nama' => $nama,
        'username' => $username,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'role' => $role
    ]);
}

public static function deleteUser($id){
    global $pdo;
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}




}

?>