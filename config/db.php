<?php 

$host = 'localhost';
$password = '';
$user = 'root';
$db = "gudang";

try{
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}


?>