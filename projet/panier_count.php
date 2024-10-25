<?php
session_start(); 
header('Content-Type: application/json');
// compte le nbr d'articles dans le panier
$panierCount = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;

echo json_encode(['count' => $panierCount]); // renvoie le nbr au format JSON
?>
