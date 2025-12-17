<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../config/db.php';

$error = "";
$success = "";
$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: articles.php");
    exit();
}

$article_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM article WHERE id_article = ? AND id_auther = ?");
$stmt->execute([$article_id, $user_id]);
$article = $stmt->fetch();

if (!$article) {
    die("Article introuvable ou vous n'avez pas la permission.");
}

$cats = $pdo->query("SELECT * FROM category")->fetchAll();

if (isset($_POST['update_btn'])) {
    
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'];
    
    $image_path_final = $article['image_url'];

    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        
        $upload_dir = "../uploads/";
        $target_file = $upload_dir . basename($image_name);
        
        if (move_uploaded_file($image_tmp, $target_file)) {
            $image_path_final = "uploads/" . basename($image_name);
        } else {
            $error = "Erreur upload image.";
        }
    }

    if (empty($error)) {
        $sql = "UPDATE article SET title=?, content=?, id_category=?, image_url=?, updated_at=NOW() 
                WHERE id_article=? AND id_auther=?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$title, $content, $category_id, $image_path_final, $article_id, $user_id])) {
            $success = "Article t-modifia b naja7! ✅";
            header("Refresh:2; url=articles.php");
        } else {
            $error = "Erreur database.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Article - BlogCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">
    <aside class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="h-16 flex items-center justify-center text-2xl font-bold border-b border-gray-700">Panel</div>
        <nav class="flex-1 px-2 py-4 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded text-gray-300">Dashboard</a>
            <a href="articles.php" class="flex items-center px-4 py-2 bg-gray-900 text-blue-400 rounded">My Articles</a>
        </nav>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Edit Article</h2>

        <?php if($success): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4"><?php echo $success; ?> (Redirection...)</div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md max-w-2xl">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required 
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Category</label>
                <select name="category_id" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                    <?php foreach($cats as $cat): ?>
                        <option value="<?php echo $cat['id_category']; ?>" 
                            <?php echo ($cat['id_category'] == $article['id_category']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name_category']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Current Image</label>
                <img src="../<?php echo htmlspecialchars($article['image_url']); ?>" class="h-32 rounded border p-1">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Change Image (Optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-gray-700 border rounded py-2 px-3">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Content</label>
                <textarea name="content" rows="10" required class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500"><?php echo htmlspecialchars($article['content']); ?></textarea>
            </div>

            <button type="submit" name="update_btn" class="bg-yellow-600 text-white font-bold py-2 px-6 rounded hover:bg-yellow-700">
                Update Article
            </button>
            <a href="articles.php" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </form>
    </main>
</div>

</body>
</html>