<?php
session_start();

// redirection du user sur l'accueil
if (isset($_SESSION['utilisateur_id'])) {
    header('Location: index.php'); 
    exit;
}

$host = 'lakartxela.iutbayonne.univ-pau.fr';
$bdd = 'rgentil_bd';
$user = 'rgentil_bd';
$password = 'rgentil_bd';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$bdd;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage()); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; 
    $password = $_POST['password']; 

    $sql = "SELECT * FROM utilisateurs WHERE email = '$email' AND mot_de_passe = '$password'";
    $stmt = $pdo->query($sql); 
    
    // verif si user existe 
    if ($stmt->rowCount() > 0) {
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC); 
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['status'] = $utilisateur['status']; 
        header('Location: index.php'); 
        exit;
    } else {
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
        <p style="color: red;"><?php echo $message; ?></p> 
    <?php endif; ?>
    <form action="login.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
