<?php

function openDatabaseConnection() {
    $host = 'localhost'; // Hôte de la base de données
    $db = 'hotel_california'; // Nom de la base de données
    $user = 'root'; // Nom d'utilisateur MySQL
    $pass = ''; // Mot de passe MySQL (vide ici pour l'environnement local)

    try {
        // Création d'une instance PDO pour la connexion
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        // Configuration des attributs PDO pour gérer les erreurs et le mode de récupération
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch (PDOException $e) {
        // Arrêt du script avec un message d'erreur en cas d'échec
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

function closeDatabaseConnection($conn) {
    $conn = null; // Destruction de l'objet PDO pour libérer la connexion
}
?>