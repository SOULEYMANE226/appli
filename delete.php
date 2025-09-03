<?php
require "db.php";
session_start();

// Vérifier si un ID est passé en GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    try {
        $stmt->execute([$id]);
        $_SESSION['success'] = "Utilisateur supprimé avec succès !";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression : " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ID utilisateur invalide.";
}

header("Location: index.php");
exit;
?>
