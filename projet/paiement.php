<?php
session_start(); 
require_once 'config.php'; 

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php'); 
    exit;
}

// vérif si le panier est vide 
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Votre panier est vide."; 
    exit;
}

$erreurs = []; // tableau pour stocker les erreurs de validation
$paiement_valide = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // validation du numéro de carte
    if (empty($_POST['carte'])) {
        $erreurs[] = "Veuillez entrer le numéro de carte."; 
    } else {
        $carte = $_POST['carte'];
        // vérif si le numéro de carte est composé de 16 chiffres
        if (!preg_match("/^\d{16}$/", $carte)) {
            $erreurs[] = "Le numéro de carte doit comporter 16 chiffres.";
        } elseif ($carte[0] !== $carte[15]) { // vérif si le premier et dernier chiffre sont identiques
            $erreurs[] = "Le dernier chiffre de la carte doit être identique au premier.";
        }
    }
    
    // validation de la date d'expiration
    if (empty($_POST['date_exp'])) {
        $erreurs[] = "Veuillez entrer la date d'expiration."; 
    } else {
        $date_exp = $_POST['date_exp'];
        // vérifie le format de la date d'expiration
        if (!preg_match("/^\d{2}-\d{2}-\d{4}$/", $date_exp)) {
            $erreurs[] = "Veuillez entrer la date d'expiration au format JJ-MM-AAAA.";
        } else {
            $date_actuelle = new DateTime(); // date actuelle
            $date_validite = DateTime::createFromFormat('d-m-Y', $date_exp); // création de l'objet DateTime
            $date_actuelle->modify('+3 months'); // ajoute 3 mois à la date actuelle

            // verifie que la date d'expiration est valide
            if ($date_validite < $date_actuelle) {
                $erreurs[] = "La date d'expiration doit être supérieure à la date du jour + 3 mois.";
            }
        }
    }

    if (empty($erreurs)) {
        $paiement_valide = true;

        unset($_SESSION['panier']); // vider le panier après un paiement valide
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
</head>
<body>
    <h1>Validation du panier</h1>

    <?php if ($paiement_valide): ?>
        <p style="color: green;">Paiement validé avec succès !</p> 
        <a href="index.php">Retour à l'accueil</a>
    <?php else: ?>
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <div style="color: red;">
                <ul>
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?php echo $erreur; ?></li> 
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <label for="carte">Numéro de carte (16 chiffres) :</label>
            <input type="text" name="carte" id="carte" value="<?php echo isset($_POST['carte']) ? $_POST['carte'] : ''; ?>" required>
            <br>

            <label for="date_exp">Date d'expiration (JJ-MM-AAAA) :</label>
            <input type="text" name="date_exp" id="date_exp" placeholder="JJ-MM-AAAA" value="<?php echo isset($_POST['date_exp']) ? $_POST['date_exp'] : ''; ?>" required>
            <br>

            <input type="submit" value="Valider le paiement">
        </form>
    <?php endif; ?>
</body>
</html>
