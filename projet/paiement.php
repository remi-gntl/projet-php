<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Votre panier est vide.";
    exit;
}

$erreurs = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['carte'])) {
        $carte = $_POST['carte'];
        if (!preg_match("/^\d{16}$/", $carte)) {
            $erreurs[] = "Le numéro de carte doit comporter 16 chiffres.";
        } elseif ($carte[0] !== $carte[15]) {
            $erreurs[] = "Le dernier chiffre de la carte doit être identique au premier.";
        }
    } else {
        $erreurs[] = "Veuillez entrer le numéro de carte.";
    }

    if (isset($_POST['date_exp'])) {
        $date_exp = $_POST['date_exp'];
        $date_actuelle = new DateTime();
        $date_validite = DateTime::createFromFormat('Y-m-d', $date_exp);
        $date_actuelle->modify('+3 months');

        if ($date_validite < $date_actuelle) {
            $erreurs[] = "La date d'expiration doit être supérieure à la date du jour + 3 mois.";
        }
    } else {
        $erreurs[] = "Veuillez entrer la date d'expiration.";
    }

    if (empty($erreurs)) {
        echo "Paiement validé avec succès !";
        exit;
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
    <form action="" method="post">
        <label for="carte">Numéro de carte (16 chiffres) :</label>
        <input type="text" name="carte" id="carte" required>
        <br>

        <label for="date_exp">Date d'expiration (AAAA-MM-JJ) :</label>
        <input type="date" name="date_exp" id="date_exp" required>
        <br>

        <input type="submit" value="Valider le paiement">
    </form>

</body>
</html>
