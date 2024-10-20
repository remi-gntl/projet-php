<?php

$host = 'localhost';
$bdd = 'projet-diplome';
$user = 'remi-gntl';
$password = 'remi-gntl';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('verifierConnexion')) {
    function verifierConnexion() {
        if (!isset($_SESSION['utilisateur_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 1) {
            header('Location: login.php');
            exit;
        }
    }
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$bdd;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
