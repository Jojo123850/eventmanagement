<?php
session_start();
require_once "config/connect.php";
$message = "";

$id_user = $_SESSION['id_user'] ?? null;
$id_event = $_GET['id_event'] ?? null;

if(!$id_user || !$id_event ){
    $message = "Requete invalide";
}else{

    $requete = "DELETE FROM events_has_users
    WHERE fk_id_user = :id_user
      AND fk_id_event = :id_event;";
    
    try{
        $data = $db->prepare($requete);
        
        $responses= $data->execute([
         ':id_user'=>$id_user ,
        ':id_event'=> $id_event,
        
        ]);
           
        if ($data->rowCount() > 0) {
                $message = "Réservation annulée avec succès.";
                echo "Réservation annulée avec succès";
        } else {
                $message = "Aucune réservation trouvée à annuler.";
                echo "Aucun reservation a été trouve";
        }
    
    
    }catch (PDOException $e) {
        $message = $e->getMessage();
        echo "Réservation " .$message;
    }
}

?>
<br> <a href="profile.php">Retour à la page événement</a>