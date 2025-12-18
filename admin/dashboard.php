<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../config/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

$stmt = $pdo->query("SELECT COUNT(*) FROM article");
$total_articles = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM article WHERE id_auther = ?");
$stmt->execute([$user_id]);
$my_articles = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM category");
$total_categories = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - BlogCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">

    <aside class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="h-16 flex items-center justify-center text-2xl font-bold border-b border-gray-700">
            BlogCMS Panel
        </div>
        
        <nav class="flex-1 px-2 py-4 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-2 bg-gray-900 rounded text-blue-400">
                <i class="fas fa-tachometer-alt w-6"></i>
                <span>Dashboard</span>
            </a>
            <?php if($role !== "admin"): ?>

                <a href="articles.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded transition">
                    <i class="fas fa-newspaper w-6"></i>
                    <span>My Articles</span>
                </a>

                <a href="add_article.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded transition">
                    <i class="fas fa-plus-circle w-6"></i>
                    <span>Add Article</span>
                </a>
            <?php endif; ?>

            <?php if($role === 'admin'): ?>
                <div class="pt-4 pb-2 text-xs text-gray-400 uppercase font-bold">Administration</div>
                
                <a href="categories.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded transition">
                    <i class="fas fa-tags w-6"></i>
                    <span>Categories</span>
                </a>
                <a href="users.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded transition">
                    <i class="fas fa-users w-6"></i>
                    <span>Users</span>
                </a>
            <?php endif; ?>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <a href="../index.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded text-green-400 mt-4 transition">
                <i class="fas fa-globe w-6"></i>
                <span>View Website</span>
            </a>
            <a href="../logout.php" class="flex items-center px-4 py-2 text-red-400 hover:bg-gray-700 rounded transition">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        
        <header class="h-16 bg-white shadow flex items-center justify-between px-6">
            <h2 class="text-xl font-semibold text-gray-800">Overview</h2>
            <div class="flex items-center">
                <span class="text-gray-600 mr-2">Hello, <strong><?php echo htmlspecialchars($username); ?></strong></span>
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded uppercase">
                    <?php echo $role; ?>
                </span>
            </div>
        </header>

        <div class="p-6 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase">My Articles</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?php echo $my_articles; ?></h3>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                            <i class="fas fa-pen fa-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase">Total Published</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?php echo $total_articles; ?></h3>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full text-green-600">
                            <i class="fas fa-globe fa-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm font-medium uppercase">Categories</p>
                            <h3 class="text-3xl font-bold text-gray-800"><?php echo $total_categories; ?></h3>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full text-purple-600">
                            <i class="fas fa-tags fa-lg"></i>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-2">Instructions</h3>
                <p class="text-gray-600">
                    Welcome to your dashboard. Use the sidebar to manage your content. 
                    Currently, you can view statistics. Next step is to allow adding new articles.
                </p>
            </div>
        </div>
    </main>

</div>

</body>
</html>