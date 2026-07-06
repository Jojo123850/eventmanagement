<?php
session_start();
require "config/connect.php";

$message = "";

if (!isset($_SESSION["id_user"])) {
    header("Location: index.php");
    exit();
}

if (($_SESSION['role'] ?? null) != 2) {
    header("Location: index.php");
    exit;
}

 $sql = $db->prepare("SELECT * FROM events WHERE fk_id_user = :id_user");
 $sql->execute([
    ':id_user' => $_SESSION['id_user']
]);
$results = $sql->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'add'){

    if (
        empty($_POST['title_event']) ||
        empty($_POST['description_event']) ||
        empty($_POST['date_event']) ||
        empty($_POST['place_event'])
    ) {
        $message = "Tous les champs doivent être remplis.";
    } else {

        $title = trim($_POST['title_event']);
        $description = trim($_POST['description_event']);
        $date = $_POST['date_event'];
        $place = trim($_POST['place_event']);

  
        $doublon = $db->prepare("SELECT * FROM events WHERE fk_id_user = :id_user AND title_event = :title_event AND date_event = :date_event");
        $doublon->execute([
            ':id_user' => $_SESSION['id_user'],
            ':title_event' => $title,
            ':date_event' => $date
        ]);

        if ($doublon->rowCount() > 0) {
            $message = "Vous avez déja ajouter cet événement.";
        } else {
            try {
                $sql = $db->prepare("INSERT INTO events (title_event, description_event, date_event, place_event, fk_id_user) VALUES (:title_event, :description_event, :date_event, :place_event, :id_user)");
                $sql->execute([
                    ':title_event' => $title,
                    ':description_event' => $description,
                    ':date_event' => $date,
                    ':place_event' => $place,
                    ':id_user' => $_SESSION['id_user']
                ]);

                $message = "Événement ajouté avec succès.";
                header("Location: createevent.php");
                exit;

            } catch (PDOException $e) {
                $message = $e->getMessage();
                echo "Réservation " . $message;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Organisateur</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
     <?php require "nav.php"; ?>
    <main>
        <h1>Bienvenue <?= htmlspecialchars($_SESSION['firstname']) ?></h1>

        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <h2>Mes événements</h2>

        <?php foreach ($results as $result): ?>

        <article>

            <h3><?= htmlspecialchars($result['title_event']) ?></h3>
            <p><?= htmlspecialchars($result['description_event']) ?></p>
            <p>Date :<?= date('d/m/Y', strtotime($result['date_event'])) ?></p>
            <p>Lieu :<?= htmlspecialchars($result['place_event']) ?></p>

            <a href="update.php?id_event=<?= $result['id_event'] ?>">Modifier</a>
            <a href="deleteevent.php?id_event=<?= $result['id_event']?>">Supprimer</a>
            <a href="inscription.php?id_event=<?= $result['id_event'] ?>">Voir les inscrits</a>
        </article>

        <hr>

        <?php endforeach; ?>


        <h2>Ajouter un événement</h2>

        <form method="POST">

            <input type="hidden" name="action" value="add">

            <label>Titre :</label><br>
            <input type="text" name="title_event"><br><br>

            <label>Description :</label><br>
            <textarea name="description_event"></textarea><br><br>

            <label>Date :</label><br>
            <input type="date" name="date_event"><br><br>

            <label>Lieu :</label><br>
            <input type="text" name="place_event"><br><br>

            <input type="submit" value="Ajouter">

        </form>

    </main>
</body>
</html>