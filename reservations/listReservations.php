<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
requireRole("standard"); // Rôle requis pour voir les réservations
include_once '../assets/gestionMessage.php';

/**
 * Formate une date au format jj/mm/aaaa.
 * @param string $date La date au format ISO (YYYY-MM-DD).
 * @return string La date formatée (jj/mm/aaaa).
 */
function formatDate($date)
{
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

// Récupération des réservations 
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

    <div class="container mt-5">
        <h1>Liste des Réservations</h1>

        <div class="mb-3">
            <a href="createReservation.php" class="btn btn-success"><i class="fas fa-plus me-1"></i> Nouvelle Réservation</a>
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
                                <a href="editReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit me-1"></i> Modifier</a>
                                <a href="deleteReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation?');"><i class="fas fa-trash me-1"></i>
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
