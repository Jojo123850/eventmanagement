<?php
session_start();
require_once "config/connect.php";
$message = "";


$id_event = $_GET['id_event'] ?? null;

if(!$id_event ){
    $message = "Requete invalide";
}else{

    $requete = "DELETE FROM events
WHERE id_event = :id_event";
    
    try{
        $data = $db->prepare($requete);
        
        $responses= $data->execute([
        ':id_event'=> $id_event,
        
        ]);
           
        if ($data->rowCount() > 0) {
                $message = "Réservation annulée avec succès.";
                echo "Evenement annulée avec succès";
        } else {
                $message = "Aucune réservation trouvée à annuler.";
                echo "Aucun événement a été trouve";
        }
    
    
    }catch (PDOException $e) {
        $message = $e->getMessage();
        echo "Réservation " .$message;
    }
}

?>
<br> <br><a href="createevent.php">Retour à la page événement</a>