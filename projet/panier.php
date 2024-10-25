<?php
session_start(); 
require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$bdd;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// gestion de l'ajout d'un diplome au panier
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['diplome_id'])) {
    $diplome_id = $_POST['diplome_id']; 

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // ajoute le diplome au panier s'il n'y est pas déja
    if (!in_array($diplome_id, $_SESSION['panier'])) {
        $_SESSION['panier'][] = $diplome_id;
    }

    // redirige vers la page d'accueil avec un message de succès
    header('Location: index.php?ajout=success');
    exit;
}

// gestion de supp d'un diplôme du panier
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer_id'])) {
    $supprimer_id = $_POST['supprimer_id']; 

    // Vérifie si le panier existe
    if (isset($_SESSION['panier'])) {
        // cherche l'id dans le panier et le supprime s'il est trouvé
        $key = array_search($supprimer_id, $_SESSION['panier']);
        if ($key !== false) {
            unset($_SESSION['panier'][$key]); 
            $_SESSION['panier'] = array_values($_SESSION['panier']);
        }
    }
    header('Location: panier.php');
    exit;
}

// recup des diplomes dans le panier
$panierDiplomes = [];

if (!empty($_SESSION['panier'])) {
    // cree des placeholders pour la requête SQL
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
    <script src="main.js" defer></script> 
</head>
<body>
    <header>
        <h1>Votre Panier</h1>
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
        <h2>Articles dans votre panier</h2>

        <?php if (!empty($panierDiplomes)): ?>
            <div class="panier-container">
                <?php foreach ($panierDiplomes as $diplome): ?>
                    <div class="diplome">
                        <h3><?php echo $diplome['Titre']; ?></h3>
                        <?php if (!empty($diplome['Image'])): ?>
                            <img src="<?php echo $diplome['Image']; ?>" alt="<?php echo $diplome['Titre']; ?>" />
                        <?php else: ?>
                            <img src="default-image.jpg" alt="Image non disponible"> 
                        <?php endif; ?>
                        <p><?php echo $diplome['Description']; ?></p>
                        <p>Prix : <?php echo $diplome['Prix']; ?> €</p>
                        <form action="panier.php" method="post">
                            <input type="hidden" name="supprimer_id" value="<?php echo $diplome['id']; ?>">
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
