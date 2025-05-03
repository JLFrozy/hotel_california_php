<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
requireRole("directeur"); // Rôle requis pour créer une chambre

include_once '../assets/gestionMessage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = (int)$_POST['capacite'];
    $disponibilite = isset($_POST['disponibilite']) ? 1 : 0;

    // Validation des données
    $errors = [];
    if (empty($numero)) {
        $errors[] = "Le numéro de chambre est obligatoire.";
    }
    if ($capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

    if (!empty($errors)) {
        $encodedMessage = urlencode("ERREUR : " . implode("<br>", $errors));
        header("Location: listChambres.php?message=$encodedMessage");
        exit;
    }

    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("INSERT INTO chambre (numero, capacite, disponibilite) VALUES (?, ?, ?)");
    if ($stmt->execute([$numero, $capacite, $disponibilite])) {
        $encodedMessage = urlencode("SUCCÈS : Chambre ajoutée avec succès.");
        header("Location: listChambres.php?message=$encodedMessage");
        exit;
    } else {
        $encodedMessage = urlencode("ERREUR : Erreur lors de l'ajout de la chambre.");
        header("Location: listChambres.php?message=$encodedMessage");
        exit;
    }
    closeDatabaseConnection($conn);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Chambre</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Ajouter une Chambre</h1>
        <form method="post">
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro de Chambre</label>
                <input type="text" class="form-control" id="numero" name="numero" required>
            </div>
            <div class="mb-3">
                <label for="capacite" class="form-label">Capacité (nombre de personnes)</label>
                <input type="number" class="form-control" id="capacite" name="capacite" min="1" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="disponibilite" name="disponibilite" value="1" checked>
                <label class="form-check-label" for="disponibilite">Disponible</label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer</button>
            <a href="listChambres.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Retour à la liste</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>