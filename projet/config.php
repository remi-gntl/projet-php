<?php
$host = 'lakartxela.iutbayonne.univ-pau.fr';
$bdd = 'rgentil_bd';
$user = 'rgentil_bd'; 
$password = 'rgentil_bd'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('verifierConnexion')) {
    function verifierConnexion() {
        // verif si user connecté avec id et status
        if (!isset($_SESSION['utilisateur_id']) || !isset($_SESSION['status']) || $_SESSION['status'] != 1) {
            header('Location: login.php'); // redirect page de connexion
            exit; 
        }
    }
}

// connexion a la bd
try {
    $pdo = new PDO("mysql:host=$host;dbname=$bdd;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage()); // erreur de connexion
}
?>
