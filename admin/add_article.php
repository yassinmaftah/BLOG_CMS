<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../config/db.php';

$error = "";
$success = "";

$stmt = $pdo->query("SELECT * FROM category");
$categories = $stmt->fetchAll();

if (isset($_POST['add_article_btn'])) {
    
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'];
    $author_id = $_SESSION['user_id'];

    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    
    $upload_dir = "../uploads/";
    $target_file = $upload_dir . basename($image_name);
    
    if (!empty($title) && !empty($content) && !empty($image_name)) {
        
        if (move_uploaded_file($image_tmp, $target_file)) {
            
            $sql = "INSERT INTO article (title, content, image_url, id_auther, id_category, status_article, created_at) 
                    VALUES (?, ?, ?, ?, ?, 'published', NOW())";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([$title, $content, $db_image_path, $author_id, $category_id]);
        } else {
            $error = "Error in upload image!";
        }
    } else {
        $error = "please Entre All inputs";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Article - BlogCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">
    <aside class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="h-16 flex items-center justify-center text-2xl font-bold border-b border-gray-700">Panel</div>
        <nav class="flex-1 px-2 py-4 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded text-gray-300">Dashboard</a>
            <a href="add_article.php" class="flex items-center px-4 py-2 bg-gray-900 text-blue-400 rounded">Add Article</a>
             <a href="../logout.php" class="flex items-center px-4 py-2 text-red-400 mt-4">Logout</a>
        </nav>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Write New Article</h2>

        <?php if($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md max-w-2xl">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Title</label>
                <input type="text" name="title"  class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Category</label>
                <select name="category_id" class="w-full px-3 py-2 border rounded bg-white focus:outline-none focus:border-blue-500">
                    <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat['id_category']; ?>">
                            <?php echo htmlspecialchars($cat['name_category']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Cover Image</label>
                <input type="file" name="image" accept="image/*"  class="w-full text-gray-700 border rounded py-2 px-3">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Content</label>
                <textarea name="content" rows="10"  class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500"></textarea>
            </div>

            <button type="submit" name="add_article_btn" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700">
                Publish Article
            </button>
        </form>
    </main>
</div>

</body>
</html>