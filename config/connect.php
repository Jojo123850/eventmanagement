<?php

// CREATE
// manipulation de données en BDD avec PDO

// avant de pouvoir manipuler une BDD, il faut s'y connecter idéalement, la connexion se fait dans un try, catch statement
try{
    // connexion fait appel à une INSTANCE à la calsse PDO
    $db = new PDO(
        'mysql:host=localhost; dbname=events;charset=utf8',
        'root', //utilisateur
        '', //route a pas de mdp

        [
            PDO::ATTR_ERRMODE => PDO:: ERRMODE_EXCEPTION // active gestion d'erreur
        ]
    );

}catch(Exception $e){
    //echo "Connexion refusée à la base de donnée";
   
    // on peut écrire la vrai erreur dans un log(a faire avant exit)
    echo "Error: .$e -> getMessage()";
    exit();
}