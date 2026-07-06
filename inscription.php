<?php
session_start();
require "config/connect.php";


if (!isset($_SESSION["id_user"])) {
    header("Location: index.php");
    exit();
}

if (($_SESSION['role'] ?? null) != 2) {
    header("Location: index.php");
    exit;
}

$id_event = $_GET['id_event'] ?? null;

if (!$id_event) {
    header("Location: createevent.php");
    exit;
}

$sql = $db->prepare("SELECT DISTINCT firstname_user, lastname_user, email_user, title_event 
    FROM users 
    INNER JOIN events_has_users eu ON id_user = fk_id_user 
    INNER JOIN events ON eu.fk_id_event = id_event 
    WHERE events.fk_id_user = :id_user AND events.id_event = :id_event");

$sql->execute([
    ':id_user' => $_SESSION['id_user'],
    ':id_event' => $id_event
]);

$results = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription événement</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <main>
        <?php require "nav.php"; ?>
        <h1>Liste des inscrits</h1>

        <?php if (empty($results)): ?>
            <p>Aucun inscrit pour le moment.</p>
        <?php else: ?>
            <ul>
            <?php foreach ($results as $r): ?>
                <li>
                    <?= htmlspecialchars($r['firstname_user']) ?>
                    <?= htmlspecialchars($r['lastname_user']) ?>
                    (<?= htmlspecialchars($r['email_user']) ?>)
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a href="createevent.php">Retour</a>
    </main>
</body>
</html>