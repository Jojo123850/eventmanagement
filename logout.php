<?php
session_start();
// Vider toutes les variables de session
$_SESSION =[];

// on détruit la séssion
session_destroy();

// ça nous redirige
header ("Location: index.php");
exit();

?> 
