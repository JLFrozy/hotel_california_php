<?php
function openDatabaseConnection() {
    $host = 'localhost'; 
    $db = 'hotel_california'; 
    $user = 'root'; 
    $pass = ''; 

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

function closeDatabaseConnection($conn) {
    $conn = null;
}
?>