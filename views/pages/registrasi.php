<form method="POST" action="index.php?action=save_user">
    <input type="text" name="nama" placeholder="nama" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role" id="">
        <option value="admin">Admin</option>
        <option selected value="petugas">Petugas</option>
    </select>
    <button type="submit">submit</button>
</form>
