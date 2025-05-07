<?php
// Inclusion des fichiers nécessaires
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
requireRole("manager"); // Rôle requis pour modifier une chambre
include_once '../assets/gestionMessage.php';

// Méthode GET : Récupérer l'ID de la chambre demandée
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier si l'ID est valide
if ($id <= 0) {
    $encodedMessage = urlencode("ERREUR : ID de chambre invalide.");
    header("Location: listChambres.php?message=$encodedMessage");
    exit;
}

$conn = openDatabaseConnection();

// Méthode POST : Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'] ?? '';
    $capacite = isset($_POST['capacite']) ? (int)$_POST['capacite'] : 0;
    $disponibilite = isset($_POST['disponibilite']) && $_POST['disponibilite'] === '1' ? 1 : 0;

    // Validation des données
    $errors = [];

    if (empty($numero)) {
        $errors[] = "Le numéro de chambre est obligatoire.";
    }

    if ($capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

    // Vérifier si le numéro de chambre existe déjà (sauf pour la chambre actuelle)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM chambre WHERE numero = ? AND idChambre != ?");
    $stmt->execute([$numero, $id]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = "Ce numéro de chambre existe déjà.";
    }

    // Si pas d'erreurs, mettre à jour les données
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("UPDATE chambre SET numero = ?, capacite = ?, disponibilite = ? WHERE idChambre = ?");
            $stmt->execute([$numero, $capacite, $disponibilite, $id]);
            closeDatabaseConnection($conn);
            $encodedMessage = urlencode("SUCCÈS : Chambre modifiée avec succès.");
            header("Location: listChambres.php?message=$encodedMessage");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Erreur de base de données : " . $e->getMessage();
        }
    }
} else {
    // Méthode GET : Récupérer les données de la chambre
    try {
        $stmt = $conn->prepare("SELECT * FROM chambre WHERE idChambre = ?");
        $stmt->execute([$id]);
        $chambre = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si la chambre n'existe pas, rediriger
        if (!$chambre) {
            closeDatabaseConnection($conn);
            $encodedMessage = urlencode("ERREUR : Chambre non trouvée.");
            header("Location: listChambres.php?message=$encodedMessage");
            exit;
        }
    } catch (PDOException $e) {
        closeDatabaseConnection($conn);
        $encodedMessage = urlencode("ERREUR : Erreur de base de données : " . $e->getMessage());
        header("Location: listChambres.php?message=$encodedMessage");
        exit;
    }
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Hôtel California</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="listChambres.php"><i class="fas fa-bed me-1"></i> Chambres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../clients/listClients.php"><i class="fas fa-users me-1"></i> Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../reservations/listReservations.php"><i class="fas fa-calendar-alt me-1"></i> Réservations</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../auth/logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../auth/login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Modifier la Chambre N° <?= htmlspecialchars($chambre['numero']) ?></h1>
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php foreach ($errors as $error): ?>
                    <p class="mb-0"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro de Chambre</label>
                <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars(isset($_POST['numero']) ? $_POST['numero'] : $chambre['numero']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="capacite" class="form-label">Capacité (nombre de personnes)</label>
                <input type="number" class="form-control" id="capacite" name="capacite" min="1" value="<?= htmlspecialchars(isset($_POST['capacite']) ? $_POST['capacite'] : $chambre['capacite']) ?>" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="disponibilite" name="disponibilite" value="1" <?= (isset($_POST['disponibilite']) && $_POST['disponibilite'] === '1') || (!isset($_POST['disponibilite']) && $chambre['disponibilite']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="disponibilite">Disponible</label>
            </div>
            <div class="actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer les modifications</button>
                <a href="listChambres.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Annuler</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>