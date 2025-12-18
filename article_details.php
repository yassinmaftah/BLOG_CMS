<?php
session_start();
require 'config/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id_article = $_GET['id'];
$message = "";

if (isset($_POST['submit_comment'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $content = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if (!empty($content)) 
    {
        $sql = "INSERT INTO commentair (comment_content, id_article, id_utilisateur, status_comment, created_at) 
                VALUES (?, ?, ?, 'approved', NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$content, $id_article, $user_id]);
    } else {
        $message = "content in empty!";
    }
}

$stmt = $pdo->prepare("SELECT article.*, utilisateur.user_name, category.name_category 
                       FROM article
                       LEFT JOIN utilisateur ON article.id_auther = utilisateur.id_Utilisateur
                       LEFT JOIN category ON article.id_category = category.id_category
                       WHERE id_article = ?");
$stmt->execute([$id_article]);
$article = $stmt->fetch();

if (!$article) 
    die("Article introuvable.");

$sql_comments = "SELECT commentair.*, utilisateur.user_name 
                 FROM commentair 
                 JOIN utilisateur ON commentair.id_utilisateur = utilisateur.id_Utilisateur
                 WHERE id_article = ? 
                 ORDER BY created_at DESC";
$stmt_c = $pdo->prepare($sql_comments);
$stmt_c->execute([$id_article]);
$comments = $stmt_c->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($article['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">

    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-blue-600">BlogCMS</a>
            <div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="admin/dashboard.php" class="text-gray-700 hover:text-blue-500 mr-4">Dashboard</a>
                    <a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-blue-600 font-bold">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 max-w-4xl mb-12">
        
        <article class="bg-white p-8 rounded-lg shadow-md mb-8">
            <span class="text-blue-600 font-bold text-sm uppercase tracking-wide">
                <?php echo htmlspecialchars($article['name_category'] ?? 'General'); ?>
            </span>
            <h1 class="text-4xl font-bold text-gray-900 mt-2 mb-4">
                <?php echo htmlspecialchars($article['title']); ?>
            </h1>
            
            <div class="flex items-center text-gray-500 text-sm mb-6 border-b pb-4">
                <i class="fas fa-user mr-2"></i> <?php echo htmlspecialchars($article['user_name']); ?>
                <span class="mx-3">•</span>
                <i class="fas fa-calendar-alt mr-2"></i> <?php echo date('F j, Y', strtotime($article['created_at'])); ?>
            </div>

            <?php if (!empty($article['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($article['image_url']); ?>" class="w-full h-96 object-cover rounded-lg mb-8 shadow-sm">
            <?php endif; ?>

            <div class="prose max-w-none text-gray-700 leading-relaxed text-lg">
                <?php echo nl2br(htmlspecialchars($article['content'])); ?>
            </div>
        </article>

        <div class="bg-white p-8 rounded-lg shadow-md">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">
                <i class="fas fa-comments mr-2"></i> Comments (<?php echo count($comments); ?>)
            </h3>

            <?php if($message): ?>
                <div class="bg-red-100 text-white-700 p-3 rounded mb-4"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if(isset($_SESSION['user_id'])): ?>
                <form method="POST" class="mb-8">
                    <label class="block font-bold text-gray-700 mb-2">Leave a comment</label>
                    <textarea name="comment" rows="3" placeholder="Write something..." 
                              class="w-full border rounded p-3 focus:outline-none focus:border-blue-500"></textarea>
                    <button type="submit" name="submit_comment" class="mt-2 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-bold">
                        Post Comment
                    </button>
                </form>
            <?php else: ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                    <p class="text-yellow-700">
                        Please <a href="login.php" class="font-bold underline">Login</a> to write a comment.
                    </p>
                </div>
            <?php endif; ?>

            <div class="space-y-6">
                <?php foreach($comments as $com): ?>
                    <div class="flex items-start space-x-4 border-b pb-4 last:border-0">
                        <div class="bg-gray-200 rounded-full w-10 h-10 flex items-center justify-center text-gray-600 font-bold">
                            <?php echo strtoupper(substr($com['user_name'], 0, 1)); ?>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <h4 class="font-bold text-gray-900"><?php echo htmlspecialchars($com['user_name']); ?></h4>
                                <span class="text-xs text-gray-500"><?php echo date('d M Y, H:i', strtotime($com['created_at'])); ?></span>
                            </div>
                            <p class="text-gray-700 mt-1">
                                <?php echo nl2br(htmlspecialchars($com['comment_content'])); ?>
                            </p>
                        </div>
                        <?php if($_SESSION['role'] == "admin"): ?>
                            <a href="deletecomment.php?id=<?php echo $com['id_commentair']?>&id_article=<?php echo $article['id_article'] ?>" class="text-red-500 hover:text-red-700">Delete</a>
                        <?php endif ?>
                    </div>
                <?php endforeach; ?>
                
                <?php if(count($comments) == 0): ?>
                    <p class="text-gray-500 text-center italic">Be the first to comment!</p>
                <?php endif; ?>
            </div>

        </div>

    </div>
</body>
</html>