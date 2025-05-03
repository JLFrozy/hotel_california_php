<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
requireRole("manager"); // Rôle requis pour modifier une chambre

// Récupérer l'ID de la chambre à modifier
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $encodedMessage = urlencode("ERREUR : ID de chambre invalide.");
    header("Location: listChambres.php?message=$encodedMessage");
    exit;
}
$idChambre = $_GET['id'];

$conn = openDatabaseConnection();

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = (int)$_POST['capacite'];
    $disponibilite = isset($_POST['disponibilite']) ? 1 : 0;

    // Validation des données (similaire à createChambre.php)
    $errors = [];
    if (empty($numero)) {
        $errors[] = "Le numéro de chambre est obligatoire.";
    }
    if ($capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

    if (!empty($errors)) {
        $encodedMessage = urlencode("ERREUR : " . implode("<br>", $errors));
        header("Location: editChambre.php?id=$idChambre&message=$encodedMessage");
        exit;
    }

    $stmt = $conn->prepare("UPDATE chambre SET numero = ?, capacite = ?, disponibilite = ? WHERE idChambre = ?");
    if ($stmt->execute([$numero, $capacite, $disponibilite, $idChambre])) {
        $encodedMessage = urlencode("SUCCÈS : Chambre mise à jour avec succès.");
        header("Location: listChambres.php?message=$encodedMessage");
        exit;
    } else {
        $encodedMessage = urlencode("ERREUR : Erreur lors de la mise à jour de la chambre.");
        header("Location: editChambre.php?id=$idChambre&message=$encodedMessage");
        exit;
    }
}

// Récupérer les informations de la chambre pour affichage dans le formulaire
$stmt = $conn->prepare("SELECT * FROM chambre WHERE idChambre = ?");
$stmt->execute([$idChambre]);
$chambre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chambre) {
    $encodedMessage = urlencode("ERREUR : Chambre non trouvée.");
    header("Location: listChambres.php?message=$encodedMessage");
    exit;
}

closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Chambre</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Modifier la Chambre N° <?= htmlspecialchars($chambre['numero']) ?></h1>
        <form method="post">
            <input type="hidden" name="idChambre" value="<?= $chambre['idChambre'] ?>">
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro de Chambre</label>
                <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($chambre['numero']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="capacite" class="form-label">Capacité (nombre de personnes)</label>
                <input type="number" class="form-control" id="capacite" name="capacite" min="1" value="<?= $chambre['capacite'] ?>" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="disponibilite" name="disponibilite" value="1" <?= $chambre['disponibilite'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="disponibilite">Disponible</label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer les modifications</button>
            <a href="listChambres.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Retour à la liste</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>