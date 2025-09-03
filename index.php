<?php
require "db.php"; // connexion à la base
session_start(); // pour les messages de succès/erreur

// ========== AFFICHAGE DES MESSAGES DE SESSION ==========
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

// ========== TRAITEMENT DU FORMULAIRE ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation simple
    if ($nom == "" || $prenom == "" || $email == "" || $password == "") {
        $error = "Tous les champs sont obligatoires !";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide !";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, password) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$nom, $prenom, $email, $hashedPassword]);
            $success = "Utilisateur ajouté avec succès !";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Cet email existe déjà !";
            } else {
                $error = "Erreur : " . $e->getMessage();
            }
        }
    }
}

// ========== RECUPERATION DES UTILISATEURS ==========
$stmt = $pdo->query("SELECT * FROM utilisateurs ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription Utilisateur</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f2f5;
    margin: 40px;
}

h2, h3 {
    color: #333;
}

form {
    background: #fff;
    padding: 20px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    max-width: 400px;
    margin-bottom: 30px;
}

input {
    padding: 10px;
    margin: 10px 0;
    width: 100%;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    font-size: 14px;
}

button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

button:hover {
    background-color: #45a049;
}

.success {
    color: green;
    font-weight: bold;
    margin-bottom: 15px;
}

.error {
    color: red;
    font-weight: bold;
    margin-bottom: 15px;
}

table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

th, td {
    padding: 12px 15px;
    text-align: left;
}

th {
    background-color: #4CAF50;
    color: white;
    font-weight: 600;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

a {
    color: #3498db;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

@media (max-width: 600px) {
    form, table {
        width: 100%;
    }
    th, td {
        font-size: 12px;
        padding: 8px;
    }
}
</style>
</head>
<body>

<h2>Inscription Utilisateur</h2>

<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
<?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

<form method="POST" action="">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">S'inscrire</button>
</form>

<h3>Liste des utilisateurs</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Date d'inscription</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['nom']) ?></td>
        <td><?= htmlspecialchars($user['prenom']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['created_at']) ?></td>
        <td>
            <a href="edit.php?id=<?= $user['id'] ?>">Modifier</a> | 
            <a href="delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
