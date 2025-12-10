<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

require '../config/db.php';

$error = "";
$success = "";

if (isset($_POST['add_cat_btn'])) {
    $name = trim($_POST['name']);
    
    if (!empty($name)) {
        $stmt = $pdo->prepare("SELECT id_category FROM category WHERE name_category = ?");
        $stmt->execute([$name]);
        
        if ($stmt->rowCount() > 0) {
            $error = "We have this category in out blog";
        } else {
            $stmt = $pdo->prepare("INSERT INTO category (name_category) VALUES (?)");
            $stmt->execute([$name]);
        }
    } else {
        $error = "Entre a category name ";
    }
}

$categories = $pdo->query("SELECT * FROM category ORDER BY id_category DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories - BlogCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">
    <aside class="w-64 bg-gray-800 text-white flex flex-col">
        <div class="h-16 flex items-center justify-center text-2xl font-bold border-b border-gray-700">Panel</div>
        <nav class="flex-1 px-2 py-4 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded text-gray-300">Dashboard</a>
            <a href="categories.php" class="flex items-center px-4 py-2 bg-gray-900 text-blue-400 rounded">Categories</a>
            <a href="articles.php" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded text-gray-300">My Articles</a>
        </nav>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Manage Categories</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-white p-6 rounded shadow-md h-fit">
                <h3 class="text-xl font-bold mb-4">Add New Category</h3>
                
                <?php if($error): ?>
                    <div class="bg-red-100 text-red-700 p-2 rounded mb-2 text-sm"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="bg-green-100 text-green-700 p-2 rounded mb-2 text-sm"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Category Name</label>
                        <input type="text" name="name" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                    </div>
                    <button type="submit" name="add_cat_btn" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 w-full">
                        Add Category
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded shadow-md">
                <h3 class="text-xl font-bold mb-4">Existing Categories</h3>
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="text-left py-2 px-4">Name</th>
                            <th class="text-right py-2 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $cat): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-4 font-medium"><?php echo htmlspecialchars($cat['name_category']); ?></td>
                            <td class="py-2 px-4 text-right">
                                <a href="delete_category.php?id=<?php echo $cat['id_category']; ?>" 
                                   class="text-red-500 hover:text-red-700 text-sm"
                                   onclick="return confirm('Attention! Sure you want to delate <?php echo $cat['name_category'] ?> category?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</div>

</body>
</html>