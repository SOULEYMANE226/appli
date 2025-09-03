<?php
// ================== Connexion à la base de données ==================
$host = "localhost";     // ou 127.0.0.1
$dbname = "Monappli";    // le nom de ta base
$username = "root";      // par défaut sur XAMPP/WAMP
$password = "";          // vide si tu n’as pas défini de mot de passe root

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "✅ Connexion réussie"; // (décommenter pour tester)
} catch (PDOException $e) {
    die("❌ Erreur de connexion : " . $e->getMessage());
}
?>
