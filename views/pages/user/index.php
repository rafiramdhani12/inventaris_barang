 <?php require_once "views/includes/header.php"; require_once "views/components/navbar.php";?>
 <!-- Tabel Barang Terbaru -->
  <div class="container-fluid">
      <div class="card mt-4">
          <div class="card-header bg-primary text-white">Daftar Karyawan</div>
          <div class="card-body">
              <table class="table table-striped">
                  <thead>
                      <tr>
                          <th>Nama Karyawan</th>
                          <th>Username</th>
                          <th>Role</th>
                          <th>aksi</th>
                         </tr>
                     </thead>
                     <?php foreach($users as $user): ?>
                         <tbody>
               <tr>
                   <td><?= htmlspecialchars( $user['nama'] );?></td>
                   <td><?= htmlspecialchars( $user['username'] );?></td>
                   <td><?= htmlspecialchars( $user['role'] );?></td>
                   <td>
                        <a href="index.php?action=edit_karyawan&id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?action=delete_karyawan&id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus karyawan ini?')">Delete</a>
                   </td>
                </tr>
             </tbody>
             <?php endforeach; ?>
         </table>
     </div>
  </div>

<?php require_once "views/includes/footer.php"; ?>