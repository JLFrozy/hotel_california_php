<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
requireRole("manager");
include_once '../assets/gestionMessage.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier si l'ID est valide
if ($id <= 0) {
    header("Location: listClients.php?message=" . urlencode("ERREUR : ID de client invalide."));
    exit;
}

try {
    $conn = openDatabaseConnection();

    // Vérifier si le client existe
    $stmt = $conn->prepare("SELECT * FROM client WHERE idClient = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        header("Location: listClients.php?message=" . urlencode("ERREUR : Client non trouvé."));
        exit;
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM reservation WHERE idClient = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();

    $hasReservations = ($count > 0);

    // Traitement de la suppression si confirmée
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        // Si le client a des réservations et que l'utilisateur souhaite les supprimer aussi
        if ($hasReservations && isset($_POST['delete_reservations']) && $_POST['delete_reservations'] === 'yes') {
            $stmt = $conn->prepare("DELETE FROM reservation WHERE idClient = ?");
            $stmt->execute([$id]);
        } elseif ($hasReservations) {
            // Si le client a des réservations mais l'utilisateur ne veut pas les supprimer
            header("Location: listClients.php?message=" . urlencode("ERREUR : Impossible de supprimer : le client a des réservations associées."));
            exit;
        }

        // Supprimer le client
        $stmt = $conn->prepare("DELETE FROM client WHERE idClient = ?");
        if ($stmt->execute([$id])) {
            // Rediriger vers la liste des clients avec un message de succès
            header("Location: listClients.php?message=" . urlencode("SUCCÈS : Client supprimé avec succès."));
            exit;
        } else {
            // Rediriger avec un message d'erreur si la suppression échoue
            header("Location: listClients.php?message=" . urlencode("ERREUR : Erreur lors de la suppression du client."));
            exit;
        }
    }

    closeDatabaseConnection($conn);
} catch (PDOException $e) {
    closeDatabaseConnection($conn);
    die("Erreur lors de la suppression du client : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Supprimer un Client</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .warning-box {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #ffeeba;
        }
        .danger-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #f5c6cb;
        }
        .form-check {
            margin: 10px 0;
        }
    </style>
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
                        <a class="nav-link active" href="../clients/listClients.php"><i class="fas fa-users me-1"></i> Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../reservations/listReservations.php"><i class="fas fa-calendar-alt me-1"></i> Réservations</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Supprimer un Client</h1>

        <div class="warning-box">
            <p><strong>Attention :</strong> Vous êtes sur le point de supprimer le client <?= htmlspecialchars($client['nom']) ?>.</p>
        </div>

        <?php if ($hasReservations): ?>
            <div class="danger-box">
                <p><strong>Ce client est associé à <?= $count ?> réservation(s).</strong></p>
                <p>La suppression de ce client affectera les réservations existantes.</p>
            </div>
        <?php endif; ?>

        <form method="post">
            <?php if ($hasReservations): ?>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="delete_reservations" name="delete_reservations" value="yes">
                    <label class="form-check-label" for="delete_reservations">Supprimer également les <?= $count ?> réservation(s) associée(s) à ce client</label>
                </div>
            <?php endif; ?>

            <p>Êtes-vous sûr de vouloir supprimer ce client ?</p>

            <div class="actions">
                <input type="hidden" name="confirm" value="yes">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Confirmer la suppression</button>
                <a href="listClients.php" class="btn btn-primary"><i class="fas fa-arrow-left me-1"></i> Annuler</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>