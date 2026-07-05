<?php
session_start();
require "config/connect.php";
 require "nav.php";

$message = "";

$sql = $db->prepare("SELECT COUNT(*) AS nb_participants, title_event FROM events e INNER JOIN events_has_users eu ON 
fk_id_event = id_event 
GROUP BY id_event; ");

$sql-> execute();

$results = $sql->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <main>
       
        <h1>Popularité des événements</h1> 
        <?php if(empty($results)): ?>
        <p>Aucune donnée pour le moment</p>
        <?php else:?>
            <h1>Nombre de partipants</h1>
            <?php foreach ($results as $row): ?>
            <article>
                <h4><?=  htmlspecialchars($row['title_event'])  ?></h4>
                <p> <?= "Nombre de participant: " . htmlspecialchars($row['nb_participants']) ?></p>  
            </article>
        <?php endforeach; ?>
        <a href="usersadmin.php">Voir les participants</a>
        <a href="events.php">Retour</a>
    </main>
    <?php endif ?>
</body>
</html>