<?php
require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = $_POST['capacite'];

    $conn = connectDatabase();
    $stmt = $conn->prepare("INSERT INTO chambres (numero, capacite) VALUES (?, ?)");
    $stmt->execute([$numero, $capacite]);
    closeDatabaseConnection($conn);

    header("Location: listChambres.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Chambre</title>
    </head>
<body>
    <h1>Ajouter une Chambre</h1>

    <form method="post">
        <div>
            <label for="numero">Numéro:</label>
            <input type="text" id="numero" name="numero" required>
        </div>
        <br>
        <div>
            <label for="capacite">Capacité:</label>
            <input type="number" id="capacite" name="capacite" min="1" required>
        </div>
        <br>
        <div>
            <input type="submit" value="Enregistrer">
        </div>
    </form>

    <p><a href="listChambres.php">Retour à la liste</a></p>
</body>
</html>