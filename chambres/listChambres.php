<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
requireRole("standard"); // Rôle requis pour la liste des chambres

// Inclusion du fichier gestionMessage.php
include_once '../assets/gestionMessage.php';

$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM chambre ORDER BY numero");
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Chambres</title>
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
                        <a class="nav-link active" href="../clients/listClients.php""><i class="fas fa-users me-1"></i> Clients</a>
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
        <h1>Liste des Chambres</h1>
        <div class="mb-3">
            <a href="createChambre.php" class="btn btn-success"><i class="fas fa-plus me-1"></i> Ajouter une Chambre</a>
        </div>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Capacité</th>
                    <th>Disponibilité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($chambres) > 0): ?>
                    <?php foreach ($chambres as $chambre): ?>
                        <tr>
                            <td><?= $chambre['idChambre'] ?></td>
                            <td><?= htmlspecialchars($chambre['numero']) ?></td>
                            <td><?= $chambre['capacite'] ?></td>
                            <td><?= $chambre['disponibilite'] ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>' ?></td>
                            <td>
                                <a href="editChambre.php?id=<?= $chambre['idChambre'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit me-1"></i> Modifier</a>
                                <a href="deleteChambre.php?id=<?= $chambre['idChambre'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette chambre ?');"><i class="fas fa-trash me-1"></i> Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">Aucune chambre trouvée.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
