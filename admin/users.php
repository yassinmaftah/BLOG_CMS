<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

require '../config/db.php';

$stmt = $pdo->query("SELECT * FROM utilisateur ORDER BY date_creation DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - BlogCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">
    <aside class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="h-16 flex items-center justify-center text-2xl font-bold border-b border-gray-700">Panel</div>
        <nav class="flex-1 px-2 py-4 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded text-gray-300">Dashboard</a>
            <a href="categories.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded text-gray-300">Categories</a>
            <a href="users.php" class="flex items-center px-4 py-2 bg-gray-900 text-blue-400 rounded">Users</a>
            <a href="articles.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded text-gray-300">My Articles</a>
        </nav>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Manage Users</h2>

        <div class="bg-white shadow-md rounded overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                        <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Username</th>
                        <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                        <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                        <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-5 py-5 text-sm"><?php echo $user['id_Utilisateur']; ?></td>
                        
                        <td class="px-5 py-5 text-sm font-bold">
                            <?php echo htmlspecialchars($user['user_name']); ?>
                            <?php if($user['id_Utilisateur'] == $_SESSION['user_id']) echo "<span class='text-blue-500 text-xs'>(You)</span>"; ?>
                        </td>
                        
                        <td class="px-5 py-5 text-sm"><?php echo htmlspecialchars($user['email']); ?></td>
                        
                        <td class="px-5 py-5 text-sm">
                            <?php 
                                $color = 'gray';
                                if($user['role'] == 'admin') $color = 'red';
                                if($user['role'] == 'author') $color = 'blue';
                            ?>
                            <span class="bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800 px-2 py-1 rounded-full text-xs font-bold uppercase">
                                <?php echo $user['role']; ?>
                            </span>
                        </td>
                        
                        <td class="px-5 py-5 text-sm">
                            <a href="edit_user.php?id=<?php echo $user['id_Utilisateur']; ?>" class="text-blue-600 hover:text-blue-900 font-bold">
                                <i class="fas fa-user-cog"></i> Change Role
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>