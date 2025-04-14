<?php
require_once '../config/db_connect.php';

$id = $_GET['id'];
$conn = openDatabaseConnection();
$stmt = $conn->prepare("DELETE FROM reservation WHERE idReservation = ?");
$stmt->execute([$id]);
closeDatabaseConnection($conn);

header('Location: listReservations.php');
exit;
?><?php
require_once '../config/db_connect.php';

$id = $_GET['id'];
$conn = openDatabaseConnection();
$stmt = $conn->prepare("DELETE FROM reservation WHERE idReservation = ?");
$stmt->execute([$id]);
closeDatabaseConnection($conn);

header('Location: listReservations.php');
exit;
?>