<?php
require_once 'config.php'; 

$titre = isset($_GET['titre']) ? $_GET['titre'] : '';

if ($titre) {
    $stmt = $pdo->prepare('SELECT Image FROM diplome WHERE Titre = :titre');
    $stmt->execute(['titre' => $titre]);
    $diplome = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($diplome && file_exists($diplome['Image'])) {
        $source_image = $diplome['Image'];
        
        $image_info = getimagesize($source_image);
        $image_type = $image_info[2];  

        switch ($image_type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($source_image);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($source_image);
                break;
            default:
                die("Format d'image non supporté.");
        }

        $new_width = 150;  
        $new_height = ($image_info[1] / $image_info[0]) * $new_width;  

        $thumb = imagecreatetruecolor($new_width, $new_height);

        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $image_info[0], $image_info[1]);

        header('Content-Type: ' . $image_info['mime']);
        imagejpeg($thumb); 

        imagedestroy($thumb);
        imagedestroy($source);
    } else {
        die("Diplôme ou image non trouvé.");
    }
} else {
    die("Titre du diplôme manquant.");
}
?>
