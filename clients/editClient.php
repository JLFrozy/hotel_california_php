<?php
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';
requireRole("manager");
include_once '../assets/gestionMessage.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: listClients.php?message=" . urlencode("ERREUR : ID de client invalide."));
    exit;
}

try {
    $conn = openDatabaseConnection();

    // Récupérer les informations du client
    $stmt = $conn->prepare("SELECT * FROM client WHERE idClient = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        header("Location: listClients.php?message=" . urlencode("ERREUR : Client non trouvé."));
        exit;
    }

    // Traitement du formulaire de modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $nbPersonnes = (int)$_POST['nbPersonnes'];

        $errors = [];
        if (empty($nom)) {
            $errors[] = "Le nom est obligatoire.";
        }
        if (empty($telephone)) {
            $errors[] = "Le numéro de téléphone est obligatoire.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse email n'est pas valide.";
        }
        if ($nbPersonnes <= 0) {
            $errors[] = "Le nombre de personnes doit être positif.";
        }

        if (!empty($errors)) {
            // Les erreurs seront affichées sur le formulaire
        } else {
            $stmt = $conn->prepare("UPDATE client SET nom = ?, telephone = ?, email = ?, nbPersonnes = ? WHERE idClient = ?");
            if ($stmt->execute([$nom, $telephone, $email, $nbPersonnes, $id])) {
                closeDatabaseConnection($conn);
                header("Location: listClients.php?message=" . urlencode("SUCCÈS : Client modifié avec succès."));
                exit;
            } else {
                $encodedMessage = urlencode("ERREUR : Erreur lors de la modification du client.");
                header("Location: listClients.php?message=$encodedMessage");
                exit;
            }
        }
    }

    closeDatabaseConnection($conn);
} catch (PDOException $e) {
    closeDatabaseConnection($conn);
    die("Erreur lors de la modification du client : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Client</title>
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
            <h1 class="mb-4">Modifier un Client</h1>
            <form method="post">
                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-0"><?= $error ?></p>
                        <?php endforeach; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($client['telephone']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="nbPersonnes" class="form-label">Nombre de Personnes</label>
                    <input type="number" class="form-control" id="nbPersonnes" name="nbPersonnes" min="1" value="<?= htmlspecialchars($client['nbPersonnes']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer</button>
                <a href="listClients.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Retour à la liste</a>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
    </html>