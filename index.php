<?php
// Inclusion des fonctions d'authentification et de gestion des messages
require_once 'auth/authFunctions.php';
include_once 'assets/gestionMessage.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Hôtel California</title>
    <!-- Inclusion de Bootstrap pour un design responsive -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Inclusion de Font Awesome pour les icônes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Inclusion du fichier CSS personnalisé -->
    <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
    <!-- Barre de navigation Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Hôtel California</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="chambres/listChambres.php"><i class="fas fa-bed me-1"></i> Chambres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clients/listClients.php"><i class="fas fa-users me-1"></i> Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reservations/listReservations.php"><i class="fas fa-calendar-alt me-1"></i> Réservations</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="auth/logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="auth/login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-4 text-center">
        <?php displayMessage(); ?> <!-- Affichage des messages de succès/erreur -->
        <i class="fa-solid fa-cart-flatbed-suitcase fa-3x"></i>
        <h1 class="mb-4">Système de Gestion de l'Hôtel California</h1>
        <div class="text-center">
            <!-- Image de l'hôtel -->
            <img src="./assets/HotelCalifornia.jpg" alt="Hôtel de nuit" class="img-fluid rounded hotel-image">
        </div>
    </div>

    <!-- Inclusion du script Bootstrap pour les fonctionnalités interactives -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>