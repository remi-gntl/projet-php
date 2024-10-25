<?php
session_start(); 
require_once 'config.php'; 

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php'); // Redirige vers la page de connexion si pas connecté
    exit;
}

// Récupération de tous les diplômes
$sql = 'SELECT * FROM diplome'; 
$stmt = $pdo->query($sql);
$diplomes = $stmt->fetchAll(PDO::FETCH_ASSOC); 

// Si aucun diplôme n'est trouvé
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
    <script src="main.js" defer></script> 
</head>
<body>
    <header>
        <h1>Bienvenue à la Vente de Diplômes</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="panier.php">Panier (<span id="panier-compteur"><?php echo isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0; ?></span>)</a></li> 
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 1): ?>
                    <li><a href="admin.php">Administration</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Diplômes Disponibles</h2>

        <?php if (isset($_GET['ajout']) && $_GET['ajout'] == 'success'): ?>
            <p style="color: green;">Diplôme ajouté au panier avec succès !</p> <!-- Message de succès d'ajout panier -->
        <?php endif; ?>

        <?php if (!empty($diplomes)): ?>
        <div class="diplomes-container">
            <?php foreach ($diplomes as $diplome): ?> <!-- Boucle pour chaque diplôme -->
                <div class="diplome">
                    <h3><?php echo $diplome['Titre']; ?></h3>
                    <a href="detail.php?titre=<?php echo $diplome['Titre']; ?>">
                         <?php if (!empty($diplome['Image'])): ?>
                            <img src="<?php echo $diplome['Image']; ?>" alt="<?php echo $diplome['Titre']; ?>" />
                        <?php else: ?>
                            <img src="default-image.jpg" alt="Image non disponible" />
                        <?php endif; ?>
                    </a>
                    <p>Prix : <?php echo $diplome['Prix']; ?> €</p>

                    <form action="panier.php" method="post"> 
                        <input type="hidden" name="diplome_id" value="<?php echo $diplome['id']; ?>">
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
