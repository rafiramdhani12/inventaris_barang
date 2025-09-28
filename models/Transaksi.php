<?php 

class Transaksi {
    
    // ===== READ OPERATIONS =====
    
    public static function getTransaksi(){
        global $pdo;
        $sql = "SELECT t.*, b.nama_barang, b.kategori, u.username , b.lokasi , b.create_time
                FROM transaksi t
                LEFT JOIN barang b ON t.kode_barang = b.kode_barang
                LEFT JOIN users u ON t.user_id = u.id
                ORDER BY t.create_time DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function paginateTransaksi($offset, $limit){
        global $pdo;
        $sql = "SELECT t.*, b.nama_barang, b.kategori, u.username
                FROM transaksi t
                LEFT JOIN barang b ON t.kode_barang = b.kode_barang
                LEFT JOIN users u ON t.user_id = u.id
                ORDER BY t.create_time DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function limitTransaksi($limit){
        global $pdo;
        $sql = "SELECT t.*, b.nama_barang, b.kategori, u.username
                FROM transaksi t
                LEFT JOIN barang b ON t.kode_barang = b.kode_barang
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
        $sql = "SELECT t.*, b.nama_barang, u.username
                FROM transaksi t
                LEFT JOIN barang b ON t.kode_barang = b.kode_barang
                LEFT JOIN users u ON t.user_id = u.id
                WHERE t.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===== COUNT OPERATIONS =====
    
    public static function countTransaksi(){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM transaksi";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public static function countTransaksiMasuk(){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM transaksi WHERE status = 'masuk'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public static function countTransaksiKeluar(){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM transaksi WHERE status = 'keluar'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Total barang masuk (sum jumlah)
    public static function totalBarangMasuk(){
        global $pdo;
        $sql = "SELECT COALESCE(SUM(jumlah), 0) as total FROM transaksi WHERE status = 'masuk'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Total barang keluar (sum jumlah)
    public static function totalBarangKeluar(){
        global $pdo;
        $sql = "SELECT COALESCE(SUM(jumlah), 0) as total FROM transaksi WHERE status = 'keluar'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public static function countSearch($keyword) {
    global $pdo;
    $sql = "SELECT COUNT(*) FROM transaksi t
            LEFT JOIN barang b ON t.kode_barang = b.kode_barang
            LEFT JOIN users u ON t.user_id = u.id
            WHERE t.kode_barang LIKE :keyword 
               OR b.nama_barang LIKE :keyword 
               OR t.keterangan LIKE :keyword
               OR u.username LIKE :keyword";
    $stmt = $pdo->prepare($sql);
    $searchTerm = '%' . $keyword . '%';
    $stmt->bindValue(':keyword', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
    }       

    public static function countByStatus($status) {
        global $pdo;
        $sql = "SELECT COUNT(*) FROM transaksi WHERE status = :status";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public static function countByDateRange($start_date, $end_date) {
        global $pdo;
        $sql = "SELECT COUNT(*) FROM transaksi 
                WHERE DATE(create_time) BETWEEN :start_date AND :end_date";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }


    // ===== CRUD OPERATIONS =====
    
    public static function createTransaksi($kode_barang, $status, $jumlah, $keterangan, $user_id){
        global $pdo;
        
        try {
            $pdo->beginTransaction();
            
            // Validate barang exists
            $sql_check = "SELECT * FROM barang WHERE kode_barang = :kode_barang";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute(['kode_barang' => $kode_barang]);
            $barang = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            if(!$barang) {
                throw new Exception("Barang dengan kode $kode_barang tidak ditemukan!");
            }
            
            // Check stock untuk transaksi keluar
            if($status == 'keluar' && $barang['jumlah'] < $jumlah) {
                throw new Exception("Stok barang tidak mencukupi! Stok tersedia: " . $barang['jumlah']);
            }
            
            // Insert transaksi
            $sql = "INSERT INTO transaksi (kode_barang, status, jumlah, keterangan, user_id) 
                    VALUES (:kode_barang, :status, :jumlah, :keterangan, :user_id)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                'kode_barang' => $kode_barang,
                'status'      => $status,
                'jumlah'      => (int)$jumlah,
                'keterangan'  => $keterangan,
                'user_id'     => $user_id
            ]);
            
            // Update stock barang
            $perubahan_stok = ($status == 'masuk') ? $jumlah : -$jumlah;
            $stok_baru = $barang['jumlah'] + $perubahan_stok;
            
            $sql_update = "UPDATE barang SET jumlah = :jumlah WHERE kode_barang = :kode_barang";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                'jumlah' => $stok_baru,
                'kode_barang' => $kode_barang
            ]);
            
            $pdo->commit();
            return $result;
            
        } catch(Exception $e) {
            $pdo->rollback();
            error_log("Error creating transaksi: " . $e->getMessage());
            throw $e; // Re-throw untuk handling di controller
        }
    }

    public static function updateTransaksi($id, $kode_barang, $status, $jumlah, $keterangan, $user_id){
        global $pdo;
        
        try {
            $pdo->beginTransaction();
            
            // Get transaksi lama
            $transaksi_lama = self::getTransaksiById($id);
            if(!$transaksi_lama) {
                throw new Exception("Transaksi tidak ditemukan!");
            }
            
            // Revert stock dari transaksi lama
            $sql_barang_lama = "SELECT jumlah FROM barang WHERE kode_barang = :kode_barang";
            $stmt_barang_lama = $pdo->prepare($sql_barang_lama);
            $stmt_barang_lama->execute(['kode_barang' => $transaksi_lama['kode_barang']]);
            $barang_lama = $stmt_barang_lama->fetch(PDO::FETCH_ASSOC);
            
            if($barang_lama) {
                $revert_stok = ($transaksi_lama['status'] == 'masuk') ? 
                              -$transaksi_lama['jumlah'] : $transaksi_lama['jumlah'];
                
                $sql_revert = "UPDATE barang SET jumlah = jumlah + :revert WHERE kode_barang = :kode_barang";
                $stmt_revert = $pdo->prepare($sql_revert);
                $stmt_revert->execute([
                    'revert' => $revert_stok,
                    'kode_barang' => $transaksi_lama['kode_barang']
                ]);
            }
            
            // Validate barang baru
            $sql_check = "SELECT * FROM barang WHERE kode_barang = :kode_barang";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute(['kode_barang' => $kode_barang]);
            $barang_baru = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            if(!$barang_baru) {
                throw new Exception("Barang dengan kode $kode_barang tidak ditemukan!");
            }
            
            // Check stock untuk transaksi keluar
            if($status == 'keluar' && $barang_baru['jumlah'] < $jumlah) {
                throw new Exception("Stok barang tidak mencukupi! Stok tersedia: " . $barang_baru['jumlah']);
            }
            
            // Update transaksi
            $sql = "UPDATE transaksi 
                    SET kode_barang = :kode_barang, status = :status, jumlah = :jumlah,
                        keterangan = :keterangan, user_id = :user_id
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                'id'          => $id,
                'kode_barang' => $kode_barang,
                'status'      => $status,
                'jumlah'      => (int)$jumlah,
                'keterangan'  => $keterangan,
                'user_id'     => $user_id
            ]);
            
            // Apply stock baru
            $perubahan_stok = ($status == 'masuk') ? $jumlah : -$jumlah;
            $sql_apply = "UPDATE barang SET jumlah = jumlah + :perubahan WHERE kode_barang = :kode_barang";
            $stmt_apply = $pdo->prepare($sql_apply);
            $stmt_apply->execute([
                'perubahan' => $perubahan_stok,
                'kode_barang' => $kode_barang
            ]);
            
            $pdo->commit();
            return $result;
            
        } catch(Exception $e) {
            $pdo->rollback();
            error_log("Error updating transaksi: " . $e->getMessage());
            throw $e;
        }
    }

    public static function deleteTransaksi($id){
        global $pdo;
        
        try {
            $pdo->beginTransaction();
            
            // Get transaksi yang akan dihapus
            $transaksi = self::getTransaksiById($id);
            if(!$transaksi) {
                throw new Exception("Transaksi tidak ditemukan!");
            }
            
            // Revert stock
            $revert_stok = ($transaksi['status'] == 'masuk') ? 
                          -$transaksi['jumlah'] : $transaksi['jumlah'];
            
            $sql_revert = "UPDATE barang SET jumlah = jumlah + :revert WHERE kode_barang = :kode_barang";
            $stmt_revert = $pdo->prepare($sql_revert);
            $stmt_revert->execute([
                'revert' => $revert_stok,
                'kode_barang' => $transaksi['kode_barang']
            ]);
            
            // Delete transaksi
            $sql = "DELETE FROM transaksi WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(['id' => $id]);
            
            $pdo->commit();
            return $result;
            
        } catch(Exception $e) {
            $pdo->rollback();
            error_log("Error deleting transaksi: " . $e->getMessage());
            throw $e;
        }
    }

    // ===== SEARCH & FILTER =====
    
    public static function searchTransaksi($keyword, $offset = 0, $limit = 10) {
        global $pdo;
        $sql = "SELECT t.*, b.nama_barang, u.username
                FROM transaksi t
                LEFT JOIN barang b ON t.kode_barang = b.kode_barang
                LEFT JOIN users u ON t.user_id = u.id
                WHERE t.kode_barang LIKE :keyword 
                   OR b.nama_barang LIKE :keyword 
                   OR t.keterangan LIKE :keyword
                   OR u.username LIKE :keyword
                ORDER BY t.create_time DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindValue(':keyword', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function filterByStatus($status, $offset = 0, $limit = 10) {
        global $pdo;
        $sql = "SELECT t.*, b.nama_barang, u.username
                FROM transaksi t
                LEFT JOIN barang b ON t.kode_barang = b.kode_barang
                LEFT JOIN users u ON t.user_id = u.id
                WHERE t.status = :status 
                ORDER BY t.create_time DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTransaksiByDateRange($start_date, $end_date, $offset = 0, $limit = 10) {
        global $pdo;
        $sql = "SELECT t.*, b.nama_barang, u.username
                FROM transaksi t
                LEFT JOIN barang b ON t.kode_barang = b.kode_barang
                LEFT JOIN users u ON t.user_id = u.id
                WHERE DATE(t.create_time) BETWEEN :start_date AND :end_date
                ORDER BY t.create_time DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>