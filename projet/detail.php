<?php
session_start(); 

require_once 'config.php';

if (isset($_GET['titre'])) {
    $titre = urldecode($_GET['titre']);
    
    $sql = 'SELECT * FROM diplome WHERE Titre = :titre';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':titre', $titre);
    $stmt->execute();
    
    $diplome = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$diplome) {
        echo "<p>Diplôme non trouvé.</p>";
        exit;
    }
} else {
    echo "<p>Aucun diplôme spécifié.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail du Diplôme</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Détails du Diplôme</h1>
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
        <h2><?php echo htmlspecialchars($diplome['Titre']); ?></h2>
        
        <?php if (!empty($diplome['Image'])): ?>
            <img src="<?php echo htmlspecialchars($diplome['Image']); ?>" alt="<?php echo htmlspecialchars($diplome['Titre']); ?>" />
            <?php else: ?>
            <img src="default-image.jpg" alt="Image non disponible">
        <?php endif; ?>
        
        <p><strong>Description :</strong> <?php echo htmlspecialchars($diplome['Description']); ?></p>
        <p><strong>Prix :</strong> <?php echo htmlspecialchars($diplome['Prix']); ?> €</p>
        
        <form action="panier.php" method="post">
            <input type="hidden" name="diplome_id" value="<?php echo htmlspecialchars($diplome['id']); ?>">
            <button type="submit">Ajouter au Panier</button>
        </form>

    </main>

    <footer>
        <p>&copy; 2024 Vente de Diplômes. Oui c'est du scam et c'est fake, mais respecte la loi. Tous droits réservés.</p>
    </footer>
</body>
</html>
