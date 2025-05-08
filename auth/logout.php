<?php
require_once 'authFunctions.php';
require_once '../config/db_connect.php';
logoutUser(); // Appelle la fonction de déconnexion
error_log("Hotel_California_PHP : disconnect user");
$encodedMessage = urlencode("SUCCES: Vous êtes maintenant déconnecté.");
header("Location: /Hotel_California_PHP/index.php?message=$encodedMessage");
?>