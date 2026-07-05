<?php
session_start();
require "config/connect.php";
$message = "";

$id_event = $_GET['id_event'] ?? $_POST['id_event'] ?? null;

if (!$id_event) {
    header("Location: createevent.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $place = trim($_POST['place']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];

    $requete = "UPDATE events
                SET title_event = :title_event,
                    description_event = :description_event,
                    place_event = :place_event,
                    date_event = :date_event
                WHERE id_event = :id_event";

    $data = $db->prepare($requete);
    $data->execute([
        ':title_event' => $title,
        ':description_event' => $description,
        ':place_event' => $place,
        ':date_event' => $date,
        ':id_event' => $id_event
    ]);

    $message = "Événement mis à jour.";
}

$data = $db->prepare("SELECT title_event, description_event, place_event, date_event FROM events WHERE id_event = :id_event");
$data->execute([
    ':id_event' => $id_event
]);

$event = $data->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $message = "Événement introuvable.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'événement</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="update-page">

    <div class="update-container">

        <?php if ($event): ?>
            <form method="POST">
                <input type="hidden" name="id_event" value="<?= htmlspecialchars($id_event) ?>">

                <?php if ($message): ?>
                    <p class="message"><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>

                <h3>Modifier votre événement</h3>

                <label for="title">Nom de l'événement :</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($event['title_event']) ?>">

                <label for="description">Description :</label>
                <input type="text" id="description" name="description" value="<?= htmlspecialchars($event['description_event']) ?>">

                <label for="place">Lieu :</label>
                <input type="text" id="place" name="place" value="<?= htmlspecialchars($event['place_event']) ?>">

                <label for="date">Date :</label>
                <input type="date" id="date" name="date" value="<?= htmlspecialchars($event['date_event']) ?>">

                <input type="submit" name="update" value="Mettre à jour l'événement">
                <a href="createevent.php" class="back-link">Retour à l'accueil</a>
            </form>
        <?php else: ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

    </div>

</body>
</html>