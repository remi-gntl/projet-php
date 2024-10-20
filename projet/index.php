<?php
session_start(); 
require_once 'config.php'; 


if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php'); 
    exit;
}



try {
    $pdo = new PDO("mysql:host=$host;dbname=$bdd;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$sql = 'SELECT * FROM diplome'; 
$stmt = $pdo->query($sql);
$diplomes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($diplomes)) {
    echo "<p>Aucun diplôme trouvé dans la base de données.</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vente de Diplômes</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <header>
        <h1>Bienvenue à la Vente de Diplômes</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
                <li><a href="panier.php">Panier</a></li>
                <li><a href="admin.php">Administration</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Diplômes Disponibles</h2>

        <?php if (!empty($diplomes)): ?>
        <div class="diplomes-container">
            <?php foreach ($diplomes as $diplome): ?>
                <div class="diplome">
                    <h3><?php echo htmlspecialchars($diplome['Titre']); ?></h3>
                    <a href="detail.php?titre=<?php echo urlencode($diplome['Titre']); ?>">
                         <?php if (!empty($diplome['Image'])): ?>
                            <img src="<?php echo htmlspecialchars($diplome['Image']); ?>" alt="<?php echo htmlspecialchars($diplome['Titre']); ?>" />
                        <?php else: ?>
                            <img src="default-image.jpg" alt="Image non disponible" />
                        <?php endif; ?>
                    </a>
                    <p>Prix : <?php echo htmlspecialchars($diplome['Prix']); ?> €</p>

                    <form action="panier.php" method="post">
                        <input type="hidden" name="diplome_id" value="<?php echo htmlspecialchars($diplome['id']); ?>">
                        <button type="submit">Ajouter au Panier</button>
                    </form>


                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p>Aucun diplôme disponible pour le moment.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Vente de Diplômes. Oui c'est du scam et c'est fake, mais respecte la loi. Tous droits réservés.</p>
    </footer>
</body>
</html>
