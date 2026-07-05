<?php
session_start();
require "config/connect.php";



$sql = $db->prepare("
    SELECT
        e.id_event, 
        e.title_event, 
        e.description_event, 
        e.date_event, 
        e.place_event 
    FROM events e 
    INNER JOIN events_has_users e_u ON e.id_event = e_u.fk_id_event
    WHERE e_u.fk_id_user = :id_user
");

$sql->execute([
    ':id_user' => $_SESSION['id_user']
]);

$results = $sql->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <?php require "nav.php"; ?>
    <main>
        <h1><?= "Bonjour " .$_SESSION['firstname'] ?></h1>
        <h1>Des solutions indispensables pour organiser un événement </h1><br> <br>

        <a href="events.php">Voir toutes les événements disponible</a>

        <h2>Les listes d'événements auquels vous vous etes inscrits</h2>

        <?php foreach ($results as $row): ?>
            <article>
                <h2><?=  htmlspecialchars($row['title_event']) ?></h2>
                <h3> <?= htmlspecialchars($row['description_event']) ?></h3>
                <p> <?= date('d/m/Y', strtotime($row['date_event'])) ?> </p>
                <p> <?= "Lieu:" . htmlspecialchars($row['place_event']) ?></p>
                <a href="delete.php?id_event=<?= $row['id_event'] ?>">Annuler ma réservation</a>
            </article>
        <?php endforeach; ?>
    </main>    
</body>
</html>