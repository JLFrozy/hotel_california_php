<?php

function displayMessage() {
    if (isset($_GET['message'])) {
        $message = urldecode($_GET['message']);
        if (strpos($message, 'SUCCÈS') === 0) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            echo substr($message, 8); // Supprime le préfixe "SUCCÈS : "
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
        } elseif (strpos($message, 'ERREUR') === 0) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            echo substr($message, 7); // Supprime le préfixe "ERREUR : "
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
        }
    }
}

?>