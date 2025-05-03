<?php
require_once '../config/db_connect.php';

$id = $_GET['id'];

try {
    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("DELETE FROM reservation WHERE idReservation = ?");
    if ($stmt->execute([$id])) {
        closeDatabaseConnection($conn);
        header('Location: listReservations.php?message=' . urlencode('SUCCÈS : Réservation supprimée avec succès.'));
        exit;
    } else {
        closeDatabaseConnection($conn);
        header('Location: listReservations.php?message=' . urlencode('ERREUR : Erreur lors de la suppression de la réservation.'));
        exit;
    }
} catch (PDOException $e) {
    closeDatabaseConnection($conn);
    die("Erreur lors de la suppression de la réservation : " . $e->getMessage());
}
?>