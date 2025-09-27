 <?php require_once "views/includes/header.php"; require_once "views/components/navbar.php";?>
 <!-- Tabel Barang Terbaru -->
 <div class="card mb-4">
     <div class="card-header bg-primary text-white">Daftar Karyawan</div>
     <div class="card-body">
         <table class="table table-striped">
             <thead>
                 <tr>
                     <th>Nama Karyawan</th>
                     <th>Username</th>
                     <th>Role</th>
                    </tr>
                </thead>
                <?php foreach($users as $user): ?>
                    <tbody>
          <tr>
              <td><?= htmlspecialchars( $user['nama'] );?></td>
              <td><?= htmlspecialchars( $user['username'] );?></td>
              <td><?= htmlspecialchars( $user['role'] );?></td>
            </tr>
        </tbody>
        <?php endforeach; ?>
    </table>
</div>
</div>
<?php require_once "views/includes/footer.php"; ?>