<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    if ($id_user == $_SESSION['user_id']) {
        die("You can't delate yourself");
    }

    $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id_Utilisateur = ?");
    
    $stmt->execute([$id_user]);
    header("Location: users.php?msg=deleted");
}
?>