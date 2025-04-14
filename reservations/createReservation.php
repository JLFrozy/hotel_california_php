<?php
require_once '../config/db_connect.php';

$conn = openDatabaseConnection();
$clients = $conn->query("SELECT idClient, nom FROM client")->fetchAll(PDO::FETCH_ASSOC);
$chambres = $conn->query("SELECT idChambre, numero FROM chambre")->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idClient = $_POST['idClient'];
    $idChambre = $_POST['idChambre'];
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];

    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("INSERT INTO reservation (idClient, idChambre, dateDebut, dateFin) VALUES (?, ?, ?, ?)");
    $stmt->execute([$idClient, $idChambre, $dateDebut, $dateFin]);
    closeDatabaseConnection($conn);

    header('Location: listReservations.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Créer une Réservation</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>Créer une Réservation</h1>
        <form method="post">
            <div class="mb-3">
                <label for="idClient" class="form-label">Client</label>
                <select name="idClient" id="idClient" class="form-select">
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['idClient'] ?>"><?= htmlspecialchars($client['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="idChambre" class="form-label">Chambre</label>
                <select name="idChambre" id="idChambre" class="form-select">
                    <?php foreach ($chambres as $chambre): ?>
                        <option value="<?= $chambre['idChambre'] ?>">N° <?= $chambre['numero'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="dateDebut" class="form-label">Date de Début</label>
                <input type="date" name="dateDebut" id="dateDebut" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="dateFin" class="form-label">Date de Fin</label>
                <input type="date" name="dateFin" id="dateFin" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>