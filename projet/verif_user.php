
<?php
// Vérifie si l'utilisateur est connecté et a un statut différent de 0
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['status'] == 0) {
    header('Location: login.php'); // Redirige vers la page de connexion
    exit;
}

// Configuration de la base de données
$host = 'lakartxela.iutbayonne.univ-pau.fr';
$bdd = 'rgentil_bd';
$user_admin = 'rgentil_bd';
$password = 'rgentil_bd';
?>
