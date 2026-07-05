<?php
session_start();
require "config/connect.php";

$sql = $db->prepare("SELECT * FROM events");
$sql->execute();

$results = $sql->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Événements</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <?php require "nav.php"; ?>
    
    <main>
        <h1>Les événements disponible</h1>
    
        <?php foreach ($results as $row):

            ?>
            <article>
                <h2><?=  htmlspecialchars($row['title_event']) ?></h2>
                <h3> <?= htmlspecialchars($row['description_event']) ?></h3>
                <p> <?= date('d/m/Y', strtotime($row['date_event'])) ?> </p>
                <p> <?= "Lieu:" . htmlspecialchars($row['place_event']) ?></p>
               <a href="reservation.php?id_event=<?= $row['id_event'] ?>">
                    Réserver cet événement
                </a>
            </article>
        <?php endforeach; ?>
    </main>
</body>
</html>