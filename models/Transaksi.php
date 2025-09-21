<?php 

class Transaksi {
    public static function getTransaksi(){
        global $pdo;
        $sql = "SELECT t.*, b.nama_barang, u.username 
                FROM transaksi t
                LEFT JOIN barang b ON t.id_barang = b.id
                LEFT JOIN users u ON t.user_id = u.id
                ORDER BY t.create_time DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function limitTransaksi($limit){
        global $pdo;
        $sql = "SELECT t.*, b.nama_barang, u.username 
                FROM transaksi t
                LEFT JOIN barang b ON t.id_barang = b.id
                LEFT JOIN users u ON t.user_id = u.id
                ORDER BY t.create_time DESC
                LIMIT :limit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTransaksiById($id){
        global $pdo;
        $sql = "SELECT * FROM transaksi WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createTransaksi($id_barang, $jenis, $jumlah, $keterangan, $user_id){
        global $pdo;
        $sql = "INSERT INTO transaksi (id_barang, jenis, jumlah, keterangan, user_id) 
                VALUES (:id_barang, :jenis, :jumlah, :keterangan, :user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id_barang'   => $id_barang,
            'jenis'       => $jenis,
            'jumlah'      => $jumlah,
            'keterangan'  => $keterangan,
            'user_id'     => $user_id
        ]);
    }

    public static function updateTransaksi($id, $id_barang, $jenis, $jumlah, $keterangan, $user_id){
        global $pdo;
        $sql = "UPDATE transaksi 
                SET id_barang = :id_barang, jenis = :jenis, jumlah = :jumlah, 
                    keterangan = :keterangan, user_id = :user_id
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id'          => $id,
            'id_barang'   => $id_barang,
            'jenis'       => $jenis,
            'jumlah'      => $jumlah,
            'keterangan'  => $keterangan,
            'user_id'     => $user_id
        ]);
    }

    public static function deleteTransaksi($id){
        global $pdo;
        $sql = "DELETE FROM transaksi WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
?>
