<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function verifierConnexion() {
    if (!isset($_SESSION['utilisateur_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 1) {
        header('Location: login.php');
        exit;
    }
}


$host = 'localhost';
$bdd = 'projet-diplome';
$user_admin = 'remi-gntl';
$password = 'remi-gntl';


?>