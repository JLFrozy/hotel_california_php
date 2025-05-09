<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
requireRole("directeur"); // Rôle requis pour créer une chambre

include_once '../assets/gestionMessage.php';

// Traitement du formulaire si soumis
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

    // Vérifier si le numéro de chambre existe déjà
    try {
        $conn = openDatabaseConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM chambre WHERE numero = ?");
        $stmt->execute([$numero]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Ce numéro de chambre existe déjà.";
        }
    } catch (PDOException $e) {
        $errors[] = "Erreur lors de la vérification du numéro de chambre : " . $e->getMessage();
    }

    // Si pas d'erreurs, insérer la chambre
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO chambre (numero, capacite, disponibilite) VALUES (?, ?, ?)");
            if ($stmt->execute([$numero, $capacite, $disponibilite])) {
                $encodedMessage = urlencode("SUCCÈS : Chambre ajoutée avec succès.");
                header("Location: listChambres.php?message=$encodedMessage");
                exit;
            } else {
                $errors[] = "Erreur lors de l'ajout de la chambre.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur de base de données : " . $e->getMessage();
        }
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
                <ul class="navbar-nav">
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
        <h1 class="mb-4">Ajouter une Chambre</h1>
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
                <input type="text" class="form-control" id="numero" name="numero" value="<?= isset($_POST['numero']) ? htmlspecialchars($_POST['numero']) : '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="capacite" class="form-label">Capacité (nombre de personnes)</label>
                <input type="number" class="form-control" id="capacite" name="capacite" min="1" value="<?= isset($_POST['capacite']) ? htmlspecialchars($_POST['capacite']) : '' ?>" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="disponibilite" name="disponibilite" value="1" <?= isset($_POST['disponibilite']) && $_POST['disponibilite'] === '1' ? 'checked' : 'checked' ?>>
                <label class="form-check-label" for="disponibilite">Disponible</label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer</button>
            <a href="listChambres.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Retour à la liste</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>