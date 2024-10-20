<?php
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['status'] == 0) {
    header('Location: login.php');
    exit;
}

$host = 'localhost';
$bdd = 'projet-diplome';
$user_admin = 'remi-gntl';
$password = 'remi-gntl';


?>