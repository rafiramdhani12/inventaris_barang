<?php 

class Barang {
    
    // ===== READ OPERATIONS =====
    
    public static function getBarang(){
        global $pdo;
        $sql = "SELECT * FROM barang ORDER BY create_time DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function limitBarang($limit){
        global $pdo;
        $sql = "SELECT * FROM barang ORDER BY create_time DESC LIMIT :limit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function paginateBarang($offset, $limit){
        global $pdo;
        $sql = "SELECT * FROM barang ORDER BY create_time DESC LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getBarangById($id){
        global $pdo;
        $sql = "SELECT * FROM barang WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function getBarangByKode($kode_barang){
        global $pdo;
        $sql = "SELECT * FROM barang WHERE kode_barang = :kode_barang";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['kode_barang' => $kode_barang]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByLokasi($lokasi, $offset = 0, $limit = 10) {
        global $pdo;
        $sql = "SELECT * FROM barang 
                WHERE lokasi = :lokasi 
                ORDER BY create_time DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':lokasi', $lokasi, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getDistinctLokasi() {
        global $pdo;
        $sql = "SELECT DISTINCT lokasi FROM barang WHERE lokasi IS NOT NULL AND lokasi != ''";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===== COUNT OPERATIONS =====
    
    public static function countBarang(){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM barang";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Count by kondisi - UPDATED untuk handle 3 kondisi
    public static function countBarangBaik(){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM barang WHERE kondisi = 'baik'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public static function countBarangRusak(){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM barang WHERE kondisi = 'rusak'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    // BARU - untuk handle kondisi 'hilang'
    public static function countBarangHilang(){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM barang WHERE kondisi = 'hilang'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public static function countLokasi(){
        global $pdo;
        $sql = "SELECT COUNT(DISTINCT lokasi) as total FROM barang WHERE lokasi IS NOT NULL AND lokasi != ''";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // BARU - Count by kategori
    public static function countByKategori($kategori){
        global $pdo;
        $sql = "SELECT COUNT(*) kategori WHERE kategori = :kategori";
        $stmt = $pdo->query($sql);
        $stmt ->bindValue(':kategori', $kategori, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // BARU - Count by lokasi
    public static function countByLokasi($lokasi){
        global $pdo;
        $sql = "SELECT COUNT(*) as total FROM barang WHERE lokasi = :lokasi";
        $stmt = $pdo->prepare($sql);
        $stmt ->bindValue(':lokasi', $lokasi, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    // ===== CRUD OPERATIONS =====
    
    public static function createBarang($kode_barang, $nama_barang, $kategori, $jumlah, $kondisi , $lokasi, $update_by){
        global $pdo;
        
        try {
            // Validate kode_barang unique
            $existing = self::getBarangByKode($kode_barang);
            if($existing) {
                throw new Exception("Kode barang sudah ada!");
            }
            
            $sql = "INSERT INTO barang (kode_barang, nama_barang, kategori, jumlah, kondisi, lokasi, update_by) 
                    VALUES (:kode_barang, :nama_barang, :kategori, :jumlah, :kondisi, :lokasi, :update_by)";
            $stmt = $pdo->prepare($sql);
            
            return $stmt->execute([
                'kode_barang' => $kode_barang,
                'nama_barang' => $nama_barang,
                'kategori'    => $kategori,
                'jumlah'      => (int)$jumlah,
                'kondisi'     => $kondisi,
                'lokasi'      => $lokasi,
                'update_by'   => $update_by
            ]);
            
        } catch(Exception $e) {
            error_log("Error creating barang: " . $e->getMessage());
            return false;
        }
    }

    public static function updateBarang($id, $kode_barang, $nama_barang, $kategori, $jumlah, $kondisi, $lokasi, $update_by){
        global $pdo;
        
        try {
            // Validate kode_barang unique (exclude current record)
            $sql_check = "SELECT id FROM barang WHERE kode_barang = :kode_barang AND id != :id";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute(['kode_barang' => $kode_barang, 'id' => $id]);
            
            if($stmt_check->fetch()) {
                throw new Exception("Kode barang sudah digunakan barang lain!");
            }
            
            $sql = "UPDATE barang 
                    SET kode_barang = :kode_barang,
                        nama_barang = :nama_barang,
                        kategori = :kategori,
                        jumlah = :jumlah,
                        kondisi = :kondisi,
                        lokasi = :lokasi,
                        update_by = :update_by
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            
            return $stmt->execute([
                'id'          => $id,
                'kode_barang' => $kode_barang,
                'nama_barang' => $nama_barang,
                'kategori'    => $kategori,
                'jumlah'      => (int)$jumlah,
                'kondisi'     => $kondisi,
                'lokasi'      => $lokasi,
                'update_by'   => $update_by
            ]);
            
        } catch(Exception $e) {
            error_log("Error updating barang: " . $e->getMessage());
            return false;
        }
    }

    public static function deleteBarang($id){
        global $pdo;
        
        try {
            // Check if barang has related transaksi
            $sql_check = "SELECT COUNT(*) as total FROM transaksi t 
                         JOIN barang b ON t.kode_barang = b.kode_barang 
                         WHERE b.id = :id";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute(['id' => $id]);
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            if($result['total'] > 0) {
                throw new Exception("Cannot delete barang. Ada transaksi terkait!");
            }
            
            $sql = "DELETE FROM barang WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute(['id' => $id]);
            
        } catch(Exception $e) {
            error_log("Error deleting barang: " . $e->getMessage());
            return false;
        }
    }

    // ===== SEARCH & FILTER =====
    
  public static function searchBarang($keyword) {
    global $pdo;
    $sql = "SELECT * FROM barang 
            WHERE kode_barang LIKE :keyword 
               OR nama_barang LIKE :keyword 
               OR kategori LIKE :keyword 
               OR lokasi LIKE :keyword
               OR kondisi LIKE :keyword
            ORDER BY create_time DESC";
    $stmt = $pdo->prepare($sql);
    $searchTerm = '%' . $keyword . '%';
    $stmt->bindValue(':keyword', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public static function filterByKondisi($kondisi, $offset = 0, $limit = 10) {
        global $pdo;
        $sql = "SELECT * FROM barang 
                WHERE kondisi = :kondisi 
                ORDER BY create_time DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':kondisi', $kondisi, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function filterByKategori($kategori, $offset = 0, $limit = 10) {
        global $pdo;
        $sql = "SELECT * FROM barang 
                WHERE kategori = :kategori 
                ORDER BY create_time DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':kategori', $kategori, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countByKondisi($kondisi){
        global $pdo;
        $sql = "SELECT COUNT(*) from barang WHERE kondisi = :kondisi";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':kondisi', $kondisi, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // ===== STOCK OPERATIONS (untuk transaksi) =====
    
    public static function updateStock($kode_barang, $jumlah_perubahan, $user_id) {
        global $pdo;
        
        try {
            $pdo->beginTransaction();
            
            // Get current stock
            $barang = self::getBarangByKode($kode_barang);
            if(!$barang) {
                throw new Exception("Barang tidak ditemukan!");
            }
            
            $stok_baru = $barang['jumlah'] + $jumlah_perubahan;
            
            if($stok_baru < 0) {
                throw new Exception("Stok tidak mencukupi!");
            }
            
            // Update stock
            $sql = "UPDATE barang SET jumlah = :jumlah WHERE kode_barang = :kode_barang";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['jumlah' => $stok_baru, 'kode_barang' => $kode_barang]);
            
            $pdo->commit();
            return true;
            
        } catch(Exception $e) {
            $pdo->rollback();
            error_log("Error updating stock: " . $e->getMessage());
            return false;
        }
    }

    // Modifikasi method search yang sudah ada untuk mendukung pagination
public static function searchBarangWithPagination($keyword, $offset, $limit) {
    global $pdo;
    $sql = "SELECT * FROM barang 
            WHERE kode_barang LIKE :keyword 
               OR nama_barang LIKE :keyword 
               OR kategori LIKE :keyword 
               OR lokasi LIKE :keyword
            ORDER BY create_time DESC
            LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $searchTerm = '%' . $keyword . '%';
    $stmt->bindValue(':keyword', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Method untuk menghitung total data hasil search
public static function countSearch($keyword) {
    global $pdo;
    $sql = "SELECT COUNT(*) as total FROM barang 
            WHERE kode_barang LIKE :keyword 
               OR nama_barang LIKE :keyword 
               OR kategori LIKE :keyword 
               OR lokasi LIKE :keyword";
    $stmt = $pdo->prepare($sql);
    $searchTerm = '%' . $keyword . '%';
    $stmt->bindValue(':keyword', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}


    public static function getBarangByDateRange($start_date, $end_date, $offset = 0, $limit = 10) {
        global $pdo;
        $sql = "SELECT * FROM barang 
                WHERE DATE(create_time) BETWEEN :start_date AND :end_date 
                ORDER BY create_time DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function countByDateRange($start_date, $end_date){
        global $pdo;
        $sql = "SELECT COUNT(*) from barang WHERE DATE(create_time) BETWEEN :start_date AND :end_date";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }


}

?>