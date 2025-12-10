<?php
session_start();
require 'config/db.php';

$sql = "SELECT 
            article.*, 
            utilisateur.user_name, 
            category.name_category 
        FROM article
        LEFT JOIN utilisateur ON article.id_auther = utilisateur.id_Utilisateur
        LEFT JOIN category ON article.id_category = category.id_category
        WHERE article.status_article = 'published' 
        ORDER BY article.created_at DESC";

$stmt = $pdo->query($sql);
$articles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-white shadow-lg mb-8">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold text-blue-600">BlogCMS</a>
        
        <div>
            <?php if(isset($_SESSION['user_id'])): ?>
                
                <span class="text-gray-500 mr-4">Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                
                <a href="admin/dashboard.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mr-2">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                
                <a href="logout.php" class="text-red-500 hover:text-red-700 font-bold">Logout</a>

            <?php else: ?>
                
                <a href="login.php" class="text-gray-600 hover:text-blue-500 mx-2">Login</a>
                <a href="register.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Register</a>
            
            <?php endif; ?>
        </div>
    </div>
</nav>

    <div class="container mx-auto px-6">
        
        <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">Latest Articles</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php if(count($articles) > 0): ?>
                <?php foreach($articles as $article): ?>
                    
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        
                        <img src="<?php echo htmlspecialchars($article['image_url']); ?>" 
                             alt="Article Image" 
                             class="w-full h-48 object-cover">
                        
                        <div class="p-6">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                <?php echo htmlspecialchars($article['name_category'] ?? 'Uncategorized'); ?>
                            </span>

                            <h2 class="text-xl font-bold mt-2 text-gray-800">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </h2>

                            <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                                <span>By: <span class="font-medium text-gray-700"><?php echo htmlspecialchars($article['user_name'] ?? 'Unknown'); ?></span></span>
                                <span><?php echo date('M d, Y', strtotime($article['created_at'])); ?></span>
                            </div>
                            
                            <a href="article_details.php?id=<?php echo $article['id_article']; ?>" 
                               class="block mt-4 text-center bg-gray-800 text-white py-2 rounded hover:bg-gray-700">
                                Read More
                            </a>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <p class="col-span-3 text-center text-gray-500 text-xl">
                    No articles found yet. Check back later!
                </p>
            <?php endif; ?>

        </div>
    </div>

    <footer class="bg-gray-800 text-white mt-12 py-6 text-center">
        <p>&copy; 2025 BlogCMS. All rights reserved.</p>
    </footer>

</body>
</html>