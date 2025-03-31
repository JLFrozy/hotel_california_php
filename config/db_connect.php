<?php
function openDatabaseConnection() {
    $host = 'localhost'; // Remplace par l'hôte de ton serveur MySQL si différent
    $db = 'hotel_california'; // Nom de la base de données
    $user = 'root'; // Remplace par ton utilisateur MySQL
    $pass = ''; // Remplace par ton mot de passe MySQL

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch (PDOException $e) {
        // Afficher une erreur détaillée pour le débogage
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

function closeDatabaseConnection($conn) {
    $conn = null; // Ferme la connexion en la définissant à null
}
?>