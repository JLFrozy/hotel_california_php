<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';
// Fonction pour formater les dates
function formatDate($date)
{
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}
// Récupération des réservations avec les informations des clients et des chambres
$conn = openDatabaseConnection();
$query = "SELECT r.idReservation AS id, r.dateDebut AS date_arrivee, r.dateFin AS date_depart,
    cl.nom AS client_nom, cl.telephone AS client_telephone, cl.email AS client_email,
    cl.nbPersonnes AS nombre_personnes,
    ch.numero AS chambre_numero, ch.capacite AS chambre_capacite
    FROM reservation r
    JOIN client cl ON r.idClient = cl.idClient
    JOIN chambre ch ON r.idChambre = ch.idChambre
    ORDER BY r.dateDebut DESC";
$stmt = $conn->query($query);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Liste des Réservations</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Liste des Réservations</h1>

        <div class="mb-3">
            <a href="createReservation.php" class="btn btn-success">Nouvelle Réservation</a>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Contact</th>
                    <th>Chambre</th>
                    <th>Personnes</th>
                    <th>Arrivée</th>
                    <th>Départ</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reservations) > 0): ?>
                    <?php foreach ($reservations as $reservation): ?>
                        <?php
                        $aujourd_hui = date('Y-m-d');
                        $statut = '';

                        if ($reservation['date_depart'] < $aujourd_hui) {
                            $statut_class = 'table-danger';
                            $statut = 'Terminée';
                        } elseif (
                            $reservation['date_arrivee'] <= $aujourd_hui &&
                            $reservation['date_depart'] >= $aujourd_hui
                        ) {
                            $statut_class = 'table-success';
                            $statut = 'En cours';
                        } else {
                            $statut_class = 'table-warning';
                            $statut = 'À venir';
                        }
                        ?>
                        <tr>
                            <td><?= $reservation['id'] ?></td>
                            <td><?= htmlspecialchars($reservation['client_nom']) ?></td>
                            <td>
                                <strong>Tél:</strong> <?= htmlspecialchars($reservation['client_telephone']) ?><br>
                                <strong>Email:</strong> <?= htmlspecialchars($reservation['client_email']) ?>
                            </td>
                            <td>N° <?= htmlspecialchars($reservation['chambre_numero']) ?>
                                (<?= $reservation['chambre_capacite'] ?> pers.)</td>
                            <td><?= $reservation['nombre_personnes'] ?></td>
                            <td><?= formatDate($reservation['date_arrivee']) ?></td>
                            <td><?= formatDate($reservation['date_depart']) ?></td>
                            <td class="<?= $statut_class ?>"><?= $statut ?></td>
                            <td>
                                <a href="viewReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-info btn-sm">Voir</a>
                                <a href="editReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <a href="deleteReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation?');">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Aucune réservation trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>