<?php 
session_start();
require 'config/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['id_article']))
{
    $id_commentair = $_GET['id'];
    $id_article = $_GET['id_article'];

    $sql = "DELETE FROM commentair WHERE id_commentair = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_commentair]);

    header("Location: article_details.php?id=" . $id_article);
    exit();
}
else
{
    header("Location: index.php");
    exit();
}
?>