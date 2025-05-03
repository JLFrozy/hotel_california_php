<?php
require_once '../config/db_connect.php';

$id = $_GET['id'];

try {
    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("SELECT * FROM reservation WHERE idReservation = ?");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        header('Location: listReservations.php?message=' . urlencode('ERREUR : Réservation non trouvée.'));
        exit;
    }

    $clients = $conn->query("SELECT idClient, nom FROM client ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
    $chambres = $conn->query("SELECT idChambre, numero, capacite FROM chambre ORDER BY numero")->fetchAll(PDO::FETCH_ASSOC);
    closeDatabaseConnection($conn);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idClient = $_POST['idClient'];
        $idChambre = $_POST['idChambre'];
        $dateDebut = $_POST['dateDebut'];
        $dateFin = $_POST['dateFin'];

        $errors = [];
        if (empty($dateDebut) || empty($dateFin)) {
            $errors[] = "Les dates de début et de fin sont obligatoires.";
        }
        if ($dateDebut >= $dateFin) {
            $errors[] = "La date de début doit être antérieure à la date de fin.";
        }

        if (empty($errors)) {
            $conn = openDatabaseConnection();
            $stmt = $conn->prepare("UPDATE reservation SET idClient = ?, idChambre = ?, dateDebut = ?, dateFin = ? WHERE idReservation = ?");
            if ($stmt->execute([$idClient, $idChambre, $dateDebut, $dateFin, $id])) {
                closeDatabaseConnection($conn);
                header('Location: listReservations.php?message=' . urlencode('SUCCÈS : Réservation modifiée avec succès.'));
                exit;
            } else {
                closeDatabaseConnection($conn);
                header('Location: listReservations.php?message=' . urlencode('ERREUR : Erreur lors de la modification de la réservation.'));
                exit;
            }
        } else {
            $encodedMessage = urlencode("ERREUR : " . implode("<br>", $errors));
            header("Location: editReservation.php?id=$id&message=$encodedMessage&idClient=$idClient&idChambre=$idChambre&dateDebut=$dateDebut&dateFin=$dateFin");
            exit;
        }
    }

    // Récupérer le message d'erreur s'il existe
    if (isset($_GET['message'])) {
        $message = urldecode($_GET['message']);
        $alertClass = (strpos($message, 'ERREUR') !== false) ? 'alert-danger' : 'alert-info';
        echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">';
        echo htmlspecialchars($message);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }

    // Récupérer les valeurs des champs en cas d'erreur de validation
    $selectedClientId = $_GET['idClient'] ?? $reservation['idClient'];
    $selectedChambreId = $_GET['idChambre'] ?? $reservation['idChambre'];
    $dateDebutValue = $_GET['dateDebut'] ?? $reservation['dateDebut'];
    $dateFinValue = $_GET['dateFin'] ?? $reservation['dateFin'];

} catch (PDOException $e) {
    closeDatabaseConnection($conn);
    die("Erreur lors de la récupération des informations de la réservation : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modifier une Réservation</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <a class="nav-link active" aria-current="page" href="../reservations/listReservations.php"><i class="fas fa-calendar-alt me-1"></i> Réservations</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Modifier une Réservation</h1>
        <form method="post">
            <div class="mb-3">
                <label for="idClient" class="form-label">Client</label>
                <select name="idClient" id="idClient" class="form-select" required>
                    <option value="">Sélectionner un client</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['idClient'] ?>" <?= $selectedClientId == $client['idClient'] ? 'selected' : '' ?>><?= htmlspecialchars($client['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="idChambre" class="form-label">Chambre</label>
                <select name="idChambre" id="idChambre" class="form-select" required>
                    <option value="">Sélectionner une chambre</option>
                    <?php foreach ($chambres as $chambre): ?>
                        <option value="<?= $chambre['idChambre'] ?>" <?= $selectedChambreId == $chambre['idChambre'] ? 'selected' : '' ?>>N° <?= $chambre['numero'] ?> (<?= $chambre['capacite'] ?> pers.)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="dateDebut" class="form-label">Date de Début</label>
                <input type="date" name="dateDebut" id="dateDebut" class="form-control" value="<?= $dateDebutValue ?>" required>
            </div>
            <div class="mb-3">
                <label for="dateFin" class="form-label">Date de Fin</label>
                <input type="date" name="dateFin" id="dateFin" class="form-control" value="<?= $dateFinValue ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Modifier</button>
            <a href="listReservations.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Annuler</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>