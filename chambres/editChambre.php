<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: listChambres.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = (int)$_POST['capacite'];
    $disponibilite = isset($_POST['disponibilite']) ? 1 : 0;

    $errors = [];
    if (empty($numero)) {
        $errors[] = "Le numéro de chambre est obligatoire.";
    }
    if ($capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE chambres SET numero = ?, capacite = ?, disponibilite = ? WHERE idChambre = ?");
        $stmt->execute([$numero, $capacite, $disponibilite, $id]);
        header("Location: listChambres.php?success=1");
        exit;
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM chambres WHERE idChambre = ?");
    $stmt->execute([$id]);
    $chambre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chambre) {
        header("Location: listChambres.php");
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
                        <a class="nav-link" href="../chambres/listChambres.php"><i class="fas fa-bed me-1"></i> Chambres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../clients/listClients.php"><i class="fas fa-users me-1"></i> Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../reservations/listReservations.php"><i class="fas fa-calendar-alt me-1"></i> Réservations</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Modifier une Chambre</h1>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p class="mb-0"><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro de Chambre</label>
                <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($chambre['numero']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="capacite" class="form-label">Capacité (nombre de personnes)</label>
                <input type="number" class="form-control" id="capacite" name="capacite" value="<?= $chambre['capacite'] ?>" min="1" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="disponibilite" name="disponibilite" value="1" <?= $chambre['disponibilite'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="disponibilite">Disponible</label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer les modifications</button>
            <a href="listChambres.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>