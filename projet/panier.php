<?php
session_start(); 
require_once 'config.php'; 
verifierConnexion();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['diplome_id'])) {
    $diplome_id = $_POST['diplome_id'];

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    if (!in_array($diplome_id, $_SESSION['panier'])) {
        $_SESSION['panier'][] = $diplome_id;
    }

    header('Location: panier.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer_id'])) {
    $supprimer_id = $_POST['supprimer_id'];

    if (isset($_SESSION['panier'])) {
        $key = array_search($supprimer_id, $_SESSION['panier']);
        if ($key !== false) {
            unset($_SESSION['panier'][$key]); 
            $_SESSION['panier'] = array_values($_SESSION['panier']); 
        }
    }

    header('Location: panier.php');
    exit;
}

$panierDiplomes = [];

if (!empty($_SESSION['panier'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['panier']), '?'));
    $stmt = $pdo->prepare("SELECT * FROM diplome WHERE id IN ($placeholders)");
    $stmt->execute($_SESSION['panier']);
    $panierDiplomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre Panier</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <header>
        <h1>Votre Panier</h1>
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
        <h2>Articles dans votre panier</h2>

        <?php if (!empty($panierDiplomes)): ?>
            <div class="panier-container">
                <?php foreach ($panierDiplomes as $diplome): ?>
                    <div class="diplome">
                        <h3><?php echo htmlspecialchars($diplome['Titre']); ?></h3>
                        <?php if (!empty($diplome['Image'])): ?>
                            <img src="<?php echo htmlspecialchars($diplome['Image']); ?>" alt="<?php echo htmlspecialchars($diplome['Titre']); ?>" />
                            <?php else: ?>
                            <img src="default-image.jpg" alt="Image non disponible">
                        <?php endif; ?>                        <p><?php echo htmlspecialchars($diplome['Description']); ?></p>
                        <p>Prix : <?php echo htmlspecialchars($diplome['Prix']); ?> €</p>
                        <form action="panier.php" method="post">
                            <input type="hidden" name="supprimer_id" value="<?php echo htmlspecialchars($diplome['id']); ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <form action="paiement.php" method="post">
                <button type="submit">Valider le Panier</button>
            </form>
        <?php else: ?>
            <p>Votre panier est vide.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Vente de Diplômes. Oui c'est du scam et c'est fake, mais respecte la loi. Tous droits réservés.</p>
    </footer>
</body>
</html>
