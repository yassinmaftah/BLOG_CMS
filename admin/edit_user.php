<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

require '../config/db.php';

$message = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_Utilisateur = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if(!$user) die("User not found");
}

if (isset($_POST['update_role_btn'])) {
    $new_role = $_POST['role'];
    
    $stmt = $pdo->prepare("UPDATE utilisateur SET role = ? WHERE id_Utilisateur = ?");
    if ($stmt->execute([$new_role, $id])) {
        header("Location: users.php"); 
        exit();
    } else {
        $message = "Erreur database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User Role</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-4">Edit Role</h2>
        <p class="mb-4 text-gray-600">User: <strong><?php echo htmlspecialchars($user['user_name']); ?></strong></p>

        <form method="POST">
            <label class="block mb-2 font-bold">Select Role:</label>
            <select name="role" class="w-full border p-2 rounded mb-6">
                <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="author" <?php echo ($user['role'] == 'author') ? 'selected' : ''; ?>>Author</option>
                <option value="visitor" <?php echo ($user['role'] == 'visitor') ? 'selected' : ''; ?>>Visitor</option>
                <option value="invite" <?php echo ($user['role'] == 'invite') ? 'selected' : ''; ?>>invite</option>
                <option value="editor" <?php echo ($user['role'] == 'editor') ? 'selected' : ''; ?>>editor</option>
                <option value="subscriber" <?php echo ($user['role'] == 'subscriber') ? 'selected' : ''; ?>>subscriber</option>
            </select>

            <button type="submit" name="update_role_btn" class="bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700">
                Save Changes
            </button>
            <a href="users.php" class="block text-center mt-4 text-gray-500 hover:underline">Cancel</a>
        </form>
    </div>

</body>
</html>
