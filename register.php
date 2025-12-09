<?php
require 'config/db.php';

$error = "";
$success = "";

if (isset($_POST['register_btn'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "inputs are empty!!!.";
    } else {
        $stmt = $pdo->prepare("SELECT id_Utilisateur FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "email not valiid.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO utilisateur (user_name, email, password, role, date_creation) 
                    VALUES (?, ?, ?, 'author', NOW())";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $success = "account creation valid";
            } else {
                $error = "account creation not valid.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - BlogCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Create Account</h2>

        <?php if($error): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                <?php echo $success; ?> <a href="login.php" class="underline font-bold">Login here</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
      <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" name="username" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" name="register_btn" 
                    class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                Sign Up
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login</a>
        </p>
    </div>

</body>
</html>