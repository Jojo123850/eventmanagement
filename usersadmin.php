<?php
require "config/connect.php";
$message = "";

$sql = $db-> prepare("SELECT id_user, lastname_user, firstname_user, email_user
                    FROM users
                    ORDER BY lastname_user ASC;");
$sql -> execute();
$results = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les participants</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <main>
        <h1>Liste des participants</h1> 
        <?php if(empty($results)): ?>
        <p>Aucune donnée pour le moment</p>
        <?php else:?>
            <h2>Les participants</h2>
            <?php foreach ($results as $row): ?>
            <article>
          <h4><?= htmlspecialchars($row['lastname_user']) . " " . htmlspecialchars($row['firstname_user']) ?></h4>
                <p><?=  htmlspecialchars($row['email_user'])  ?></p>     
            </article>
        <?php endforeach; ?>
        <a href="admin.php">Retour</a>
    </main>
    <?php endif ?>
</body>
</html>