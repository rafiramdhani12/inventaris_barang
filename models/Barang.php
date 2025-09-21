<?php 

class Barang{
    public static function getBarang(){
        global $pdo;
        $sql = "SELECT * FROM barang";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll($pdo::FETCH_ASSOC);
    }

    public static function limitBarang($limit){
        global $pdo;
        $sql = "SELECT * FROM barang LIMIT :limit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll($pdo::FETCH_ASSOC);
    }

    public static function paginateBarang($offset, $limit){
        global $pdo;
        $sql = "SELECT * FROM barang LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll($pdo::FETCH_ASSOC);
    }

    public static  function countBarang(){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM barang";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch($pdo::FETCH_ASSOC);
        return $result['total'];
    }

    public static function countBarangRusak(){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM barang WHERE kondisi = 'rusak'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch($pdo::FETCH_ASSOC);
        return $result['total'];
    }

    public static function countBarangBaik(){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM barang WHERE kondisi = 'baik'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch($pdo::FETCH_ASSOC);
        return $result['total'];
    }

    public static function countLokasi(){
        global $pdo;
        $sql = "SELECT COUNT(DISTINCT lokasi) as total FROM barang";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch($pdo::FETCH_ASSOC);
        return $result['total'];
    }

    public static function getBarangById($id){
        global $pdo;
        $sql = "SELECT * FROM barang WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch($pdo::FETCH_ASSOC);
    }

    public static function createBarang($nama_barang , $kaegori , $jumlah , $lokasi){
        global $pdo;
        $sql = "INSERT INTO barang (nama_barang, kategori, jumlah , lokasi ) VALUES (:nama_barang, :kategori, :jumlah , :lokasi )";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nama_barang' => $nama_barang,
            'kategori' => $kaegori,
            'jumlah' => $jumlah,
            'lokasi' => $lokasi
        ]);
    }

    public static function updateBarang($id, $nama_barang, $kategori, $jumlah, $lokasi){
    global $pdo;
    $sql = "UPDATE barang 
            SET nama_barang = :nama_barang, kategori = :kategori, jumlah = :jumlah, lokasi = :lokasi 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'nama_barang' => $nama_barang,
        'kategori' => $kategori,
        'jumlah' => $jumlah,
        'lokasi' => $lokasi
    ]);
}

public static function deleteBarang($id){
    global $pdo;
    $sql = "DELETE FROM barang WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}


}


?>