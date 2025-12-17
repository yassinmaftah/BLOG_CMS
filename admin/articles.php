<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../config/db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT article.*, category.name_category 
        FROM article 
        LEFT JOIN category ON article.id_category = category.id_category
        WHERE id_auther = ?
        ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$my_articles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Articles - BlogCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">
    <aside class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="h-16 flex items-center justify-center text-2xl font-bold border-b border-gray-700">Panel</div>
        <nav class="flex-1 px-2 py-4 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded text-gray-300">Dashboard</a>
            <a href="articles.php" class="flex items-center px-4 py-2 bg-gray-900 text-blue-400 rounded">My Articles</a>
            <a href="add_article.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded text-gray-300">Add Article</a>
            <a href="../logout.php" class="flex items-center px-4 py-2 text-red-400 mt-4">Logout</a>
        </nav>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">My Articles</h2>
            <a href="add_article.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> New Article
            </a>
        </div>

        <div class="bg-white shadow-md rounded overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($my_articles as $art): ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <img class="w-full h-full rounded-full object-cover" src="../<?php echo htmlspecialchars($art['image_url']); ?>" alt="" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-900 whitespace-no-wrap font-bold">
                                        <?php echo htmlspecialchars($art['title']); ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                <span class="relative"><?php echo htmlspecialchars($art['name_category'] ?? 'N/A'); ?></span>
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap"><?php echo date('d/m/Y', strtotime($art['created_at'])); ?></p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="edit_article.php?id=<?php echo $art['id_article']; ?>" class="text-blue-600 hover:text-blue-900 mr-4">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete_article.php?id=<?php echo $art['id_article']; ?>" 
                               class="text-red-600 hover:text-red-900"
                               onclick="return confirm('Are you sure you want to delete this article?');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if(count($my_articles) == 0): ?>
                <p class="text-center p-6 text-gray-500">You haven't posted any articles yet.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>