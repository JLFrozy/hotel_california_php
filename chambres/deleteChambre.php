<?php
require_once '../db_connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $conn = connectDatabase();
    $stmt = $conn->prepare("DELETE FROM chambres WHERE idChambre = ?");
    $stmt->execute([$id]);
    closeDatabaseConnection($conn);
}

header("Location: listChambres.php");
exit;
?>