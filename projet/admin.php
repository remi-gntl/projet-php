<?php
session_start();
require_once 'verif_admin.php'; 
require_once 'config.php';
verifierConnexion();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter'])) {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $image = $_POST['image'];

    $sql = 'INSERT INTO diplome (Titre, Description, Prix, Image) VALUES (:titre, :description, :prix, :image)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':prix', $prix);
    $stmt->bindParam(':image', $image);
    $stmt->execute();
}

if (isset($_POST['supprimer'])) {
    $diplome_id = $_POST['diplome_id'];
    
    $sql = 'DELETE FROM diplome WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $diplome_id);
    $stmt->execute();
}

$sql = 'SELECT * FROM diplome';
$stmt = $pdo->query($sql);
$diplomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <form method="post" action="admin.php">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" placeholder="Titre du diplôme" required><br>

            <label for="description">Description :</label>
            <textarea id="description" name="description" placeholder="Description du diplôme" required></textarea><br>

            <label for="prix">Prix (€) :</label>
            <input type="number" id="prix" name="prix" placeholder="Prix du diplôme" required><br>

            <label for="image">URL de l'image :</label>
            <input type="text" id="image" name="image" placeholder="URL de l'image" required><br>

            <button type="submit" name="ajouter">Ajouter</button>
        </form>

        <h2>Liste des diplômes</h2>
        <?php if (!empty($diplomes)): ?>
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
                    <?php foreach ($diplomes as $diplome): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($diplome['Titre']); ?></td>
                            <td><?php echo htmlspecialchars($diplome['Description']); ?></td>
                            <td><?php echo htmlspecialchars($diplome['Prix']); ?> €</td>
                            <td><img src="<?php echo htmlspecialchars($diplome['Image']); ?>" alt="Image du diplôme" width="100"></td>
                            <td>
                                <form method="post" action="admin.php">
                                    <input type="hidden" name="diplome_id" value="<?php echo $diplome['id']; ?>">
                                    <button type="submit" name="supprimer">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun diplôme disponible pour le moment.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Vente de Diplômes. Oui c'est du scam et c'est fake, mais respecte la loi. Tous droits réservés.</p>
    </footer>
</body>
</html>
