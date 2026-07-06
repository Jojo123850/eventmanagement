<?php
session_start();

$message = "";

require "config/connect.php";
 require "nav.php"; 

 if (!isset($_SESSION["id_user"])) {
    header("Location: index.php");
    exit();
}


$id_user = $_SESSION['id_user'];
$id_event = $_GET['id_event'] ?? null;
$booking_date_event_user = date('Y-m-d');

if (!$id_event) {
    die("Aucun événement sélectionné.");
}

$doublon = $db->prepare("SELECT * FROM events_has_users WHERE fk_id_user = :id_user AND fk_id_event = :id_event");
$doublon->execute([
    ':id_user' => $id_user,
    ':id_event' => $id_event
]);


if ($doublon->rowCount() > 0) {
    $message = "Vous êtes déjà inscrit à cet événement.";
} else {
    
    try {
    
        $sql = $db->prepare("INSERT INTO events_has_users (fk_id_user, fk_id_event, booking_date_event_user) VALUES (:id_user, :id_event, :booking_date_event_user)
        ");
    
        $sql->execute([
            'id_user' => $id_user,
            'id_event' => $id_event,
            'booking_date_event_user' => $booking_date_event_user
        ]);
    
        $message = "Inscription faite avec succès.";

    
    } catch (PDOException $e) {
        $message = $e->getMessage();
  
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserver un événement</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
   
    <main>
        <?php require "nav.php"; ?>
        <p><?= htmlspecialchars($message) ?></p>
        <a href="events.php">Retour à la liste d'événement</a> <br> <br>
        <a href="profile.php">Voir mes événements</a>
    </main>
    
</body>
</html>