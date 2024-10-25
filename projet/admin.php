<?php
session_start();
require_once 'verif_admin.php'; // fichier de vérif d'accès admin
require_once 'config.php'; // config pour la bd

verifierConnexion(); // verif si l'utilisateur est connecté

// traitement ajout d'un diplome
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter'])) {
    $titre = $_POST['titre']; 
    $description = $_POST['description']; 
    $prix = $_POST['prix']; 

    // verif si image téléchargé
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name']; 
        $imagePath = __DIR__ . '/images/' . $image; // def du chemin de stockage

        // deplacement de l'image dans le dossier
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $sql = "INSERT INTO diplome (Titre, Description, Prix, Image) VALUES (:titre, :description, :prix, :image)";
            $stmt = $pdo->prepare($sql); 
            $stmt->execute([
                ':titre' => $titre,
                ':description' => $description,
                ':prix' => $prix,
                ':image' => 'images/' . $image  //chemin relatif
            ]);
        } else {
            echo "Erreur lors du téléchargement de l'image."; 
        }
    }
}

// traitement modif d'un diplome
if (isset($_POST['modifier'])) {
    $diplome_id = $_POST['diplome_id'];
    $titre = $_POST['titre']; 
    $description = $_POST['description']; 
    $prix = $_POST['prix']; 

    // verif si image téléchargé
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $imagePath = __DIR__ . '/images/' . $image;


        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            // requete de mise a jour
            $sql = "UPDATE diplome SET Titre = :titre, Description = :description, Prix = :prix, Image = :image WHERE id = :diplome_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titre' => $titre,
                ':description' => $description,
                ':prix' => $prix,
                ':image' => 'images/' . $image,
                ':diplome_id' => $diplome_id
            ]);
        } else {
            echo "Erreur lors du déplacement de l'image."; 
        }
    } else {
        // cas ou aucune image on update quand meme le reste
        $sql = "UPDATE diplome SET Titre = :titre, Description = :description, Prix = :prix WHERE id = :diplome_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':prix' => $prix,
            ':diplome_id' => $diplome_id
        ]);
    }
}

// traitement suppr d'un diplome
if (isset($_POST['supprimer'])) {
    $diplome_id = $_POST['diplome_id'];

    // prep/exec requete de suppression
    $sql = "DELETE FROM diplome WHERE id = :diplome_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':diplome_id' => $diplome_id]);
}

// recup des diplomes pour affichage
$sql = 'SELECT * FROM diplome';
$diplomes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration des Diplômes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion des Diplômes</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Ajouter un nouveau diplôme</h2>
        <form method="post" action="admin.php" enctype="multipart/form-data">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" placeholder="Titre du diplôme" required><br>

            <label for="description">Description :</label>
            <textarea id="description" name="description" placeholder="Description du diplôme" required></textarea><br>

            <label for="prix">Prix (€) :</label>
            <input type="text" id="prix" name="prix" placeholder="Prix du diplôme" required><br>

            <label for="image">Image :</label>
            <input type="file" id="image" name="image" required><br>

            <button type="submit" name="ajouter">Ajouter</button> 
        </form>

        <h2>Liste des diplômes</h2>
        <?php if (!empty($diplomes)): ?> <!-- verif si il y a des diplomes -->
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($diplomes as $diplome): ?> <!-- boucle pour affichage de tout les diplomes  -->
                        <tr>
                            <td><?php echo $diplome['Titre']; ?></td>
                            <td><?php echo $diplome['Description']; ?></td>
                            <td><?php echo $diplome['Prix']; ?> €</td>
                            <td><img src="<?php echo $diplome['Image']; ?>" alt="Image du diplôme" width="100"></td>
                            <td>
                                <form method="post" action="admin.php" enctype="multipart/form-data">
                                    <!-- id caché-->
                                    <input type="hidden" name="diplome_id" value="<?php echo $diplome['id']; ?>"> 
                                    
                                    <label for="titre">Titre :</label>
                                    <input type="text" name="titre" value="<?php echo $diplome['Titre']; ?>" required><br>

                                    <label for="description">Description :</label>
                                    <textarea name="description" required><?php echo $diplome['Description']; ?></textarea><br>

                                    <label for="prix">Prix (€) :</label>
                                    <input type="text" name="prix" value="<?php echo $diplome['Prix']; ?>" required><br>

                                    <label for="image">Changer d'image :</label>
                                    <input type="file" name="image"><br>

                                    <button type="submit" name="modifier">Modifier</button> 
                                    <button type="submit" name="supprimer">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun diplôme disponible pour le moment.</p> <!-- message si aucun diplome -->
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Vente de Diplômes. Tous droits réservés.</p>
    </footer>
</body>
</html>
