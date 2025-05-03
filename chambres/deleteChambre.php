<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
requireRole("directeur"); // Rôle requis pour supprimer une chambre

include_once '../assets/gestionMessage.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idChambre = $_GET['id'];

    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("DELETE FROM chambre WHERE idChambre = ?");

    if ($stmt->execute([$idChambre])) {
        $encodedMessage = urlencode("SUCCÈS : Chambre supprimée avec succès.");
        header("Location: listChambres.php?message=$encodedMessage");
        exit;
    } else {
        $encodedMessage = urlencode("ERREUR : Erreur lors de la suppression de la chambre.");
        header("Location: listChambres.php?message=$encodedMessage");
        exit;
    }
    closeDatabaseConnection($conn);
} else {
    $encodedMessage = urlencode("ERREUR : ID de chambre invalide.");
    header("Location: listChambres.php?message=$encodedMessage");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer une Chambre</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Supprimer une Chambre</h1>
        <p class="alert alert-danger">
            <strong>Attention !</strong> Vous êtes sur le point de supprimer une chambre. Cette action est irréversible.
        </p>
        <?php displayMessage(); // Affichage du message de succès ou d'erreur ?>
        <p>
            <a href="listChambres.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Retour à la liste des chambres</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>