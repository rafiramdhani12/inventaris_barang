<?php require "views/includes/header.php"; ?>

<div class="container-fluid d-flex justify-content-center align-items-center" style="height: 80vh;">
  <div class="card p-4 shadow" style="width: 400px;">
    <h3 class="text-center mb-4">Login</h3>
    
    <form method="POST" action="index.php?action=login">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username">
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php require "views/includes/footer.php"; ?>