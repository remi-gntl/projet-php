<?php
session_start();

if (isset($_SESSION['utilisateur_id'])) {
    header('Location: index.php'); 
    exit;
}

$host = 'localhost';
$bdd = 'projet-diplome';
$user = 'remi-gntl';
$password = 'remi-gntl';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$bdd;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; 
    $sql = 'SELECT * FROM utilisateurs WHERE email = :email AND mot_de_passe = :mot_de_passe';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', $password); 
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['utilisateur_id'] = $utilisateur['id']; 
        $_SESSION['status'] = $utilisateur['status']; 
        header('Location: index.php'); 
        exit;
    }
     else {
        $message = "Identifiants invalides.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Connexion</h2>
    <?php if (isset($message)) : ?>
        <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
