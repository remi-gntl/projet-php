
<?php
// verif si la session est déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérif la connexion de l'utilisateur
function verifierConnexion() {
    // verif si l'utilisateur est connecté et a un statut d'admin
    if (!isset($_SESSION['utilisateur_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 1) {
        header('Location: login.php'); // redirige vers la page de connexion
        exit;
    }
}

$host = 'lakartxela.iutbayonne.univ-pau.fr';
$bdd = 'rgentil_bd';
$user_admin = 'rgentil_bd';
$password = 'rgentil_bd';
?>
