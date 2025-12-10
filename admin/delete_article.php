<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../config/db.php';

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql_cmnt = "DELETE FROM commentair WHERE id_article = ?";
    $stmt_cmnt = $pdo->prepare($sql_cmnt);
    $stmt_cmnt->execute([$article_id]);

    $sql = "DELETE FROM article WHERE id_article = ? AND id_auther = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$article_id, $user_id])) {
        header("Location: articles.php?msg=deleted");
    } else {
        echo "You cannot delate this article";
    }

} else {
    header("Location: articles.php");
}
?>