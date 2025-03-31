<?php
// Inclure le fichier de connexion à la base de données
require_once 'config/db_connect.php';

try {
    // Établir la connexion à la base de données
    $conn = openDatabaseConnection();
    echo "Connexion à la base de données réussie !<br>";

    // Exécuter une requête de test simple
    $stmt = $conn->query("SELECT 1");
    if ($stmt) {
        echo "Requête de test exécutée avec succès.<br>";
        // Optionnel : Afficher le résultat de la requête
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Résultat de la requête : " . $result['1'] . "<br>";
    }

    // Fermer la connexion
    closeDatabaseConnection($conn);
    echo "Connexion à la base de données fermée.";
} catch (Exception $e) {
    // Afficher une erreur si la connexion ou la requête échoue
    echo "Erreur : " . $e->getMessage();
}
?>