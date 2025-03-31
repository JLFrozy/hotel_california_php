<?php
require_once '../db_connect.php';

$conn = connectDatabase();

// Vérifier si l'ID est présent dans l'URL (méthode GET)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    if ($id <= 0) {
        header("Location: listChambres.php");
        exit;
    }

    // Traitement du formulaire de modification (méthode POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $numero = $_POST['numero'];
        $capacite = $_POST['capacite'];

        $stmt = $conn->prepare("UPDATE chambres SET numero = ?, capacite = ? WHERE idChambre = ?");
        $stmt->execute([$numero, $capacite, $id]);

        // Rediriger vers la liste des chambres avec un message de succès
        header("Location: listChambres.php?success=1");
        exit;
    } else {
        // Récupérer les données de la chambre pour affichage dans le formulaire
        $stmt = $conn->prepare("SELECT * FROM chambres WHERE idChambre = ?");
        $stmt->execute([$id]);
        $chambre = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si la chambre n'existe pas, rediriger
        if (!$chambre) {
            header("Location: listChambres.php");
            exit;
        }
    }
} else {
    // Si l'ID n'est pas valide, rediriger vers la liste
    header("Location: listChambres.php");
    exit;
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Chambre</title>
    </head>
<body>
    <h1>Modifier une Chambre</h1>

    <form method="post">
        <input type="hidden" name="id" value="<?php echo $chambre['idChambre']; ?>">

        <div>
            <label for="numero">Numéro de Chambre:</label>
            <input type="text" id="numero" name="numero" value="<?php echo $chambre['numero']; ?>" required>
        </div>
        <br>
        <div>
            <label for="capacite">Capacité (nombre de personnes):</label>
            <input type="number" id="capacite" name="capacite" min="1" value="<?php echo $chambre['capacite']; ?>" required>
        </div>
        <br>
        <div>
            <input type="submit" value="Enregistrer les modifications">
            <a href="listChambres.php">Annuler</a>
        </div>
    </form>
</body>
</html>