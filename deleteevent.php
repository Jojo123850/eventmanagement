<?php
session_start();
require_once "config/connect.php";
$message = "";


$id_event = $_GET['id_event'] ?? null;
$id_user = $_SESSION['id_user'] ?? null;

if(!$id_event || !$id_user){
    $message = "Requete invalide";
}else{

    $requete = "DELETE FROM events
                WHERE id_event = :id_event AND fk_id_user = :id_user";
    
    try{
        $data = $db->prepare($requete);
        
        $responses= $data->execute([
        ':id_event'=> $id_event,
        ':id_user' => $id_user
        
        ]);
           
        if ($data->rowCount() > 0) {
                $message = "Evénement supprimé avec succès.";
                echo "Evénement supprimé a avec succès";
        } else {
                $message = "Aucun événemntsupprimé.";
                echo "Aucun événemnt supprimé";
        }
    
    
    }catch (PDOException $e) {
        $message = $e->getMessage();
        echo "Réservation " .$message;
    }
}

?>
<br> <br><a href="createevent.php">Retour à la page événement</a>