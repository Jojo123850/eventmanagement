<?php
    $message = "";
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // récuperer les infos des formulaires
        // on clean des données htmlspecialschars et trim
        $firstname = htmlspecialchars(trim ($_POST['firstname']));
        $lastname = htmlspecialchars(trim ($_POST['lastname']));
        $email = htmlspecialchars(trim ($_POST['email']));
        $pass = htmlspecialchars(trim($_POST['pass']));

        // on fait les controles sur les données
        // on vérifie que les champs ne sont pas vides
        $isFormOK = true;
        if(empty($firstname) || empty($lastname) || empty($email) || empty($pass)){
            $message = "Tous les champs doivent etre renseignés";
            $isFormOK = false; 
        }
        //on vérifie si le mail est bien un mail
        if($isFormOK && !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $message = "Le mail n'est pas valide";
            $isFormOK = false;

        }
        // on hash le mdp
        $hash = password_hash($pass, PASSWORD_DEFAULT);
          
        // faire en sorte que prénom et nom -> 1ère lettre en maj
        $firstname = ucfirst($firstname);
        $lastname = ucfirst($lastname);
        
        //si aucun message d'erreur
        if($isFormOK){
            // faire une comparaison dans la BDD pour voir si mail unique
            require_once "config/connect.php";

            // si mail déjà présent bdd -> afficher un message
            $sql = "SELECT COUNT(email_user) AS nbEmail FROM  users WHERE email_user = :email";
            $data = $db -> prepare($sql);
            $data -> execute([
                'email' => $email
            ]);
            // si mail déjà présent bdd -> afficher un messgae
            $nbEmail = $data->fetch();
            if($nbEmail[0] == 1){
                $message = "Vous ne pourvez pas mettre cette adresse email";
                $isFormOK = false;

            }
        // si on arrive ici, tout est au vert, on peut inscrire l'utilisateur
        if($isFormOK){
            // insérer les données dans la BDD
            $sql = "INSERT INTO users(firstname_user, lastname_user, email_user, pass_user, fk_id_role) VALUES (:firstname, :lastname, :email, :pass, :id_role)";

            $req = $db-> prepare($sql);

           $success =  $req-> execute([
        'firstname' => $firstname,

        'lastname' => $lastname,

        'email' => $email,

        'pass' => $hash,
        'id_role'=>2
         ]);

         if($success && $req-> rowCount() > 0){

            header('Location:signin.php');
            exit;
         }
         if(!$success){
            $message = "Quelque chose s'est mal passé";
         }
  
        }

        } 

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription au site</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="register-page">
    <?php require "nav.php"; ?>
    <main>
        <div class="register-container">
            <h1>Inscription</h1>

            <?php if ($message): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <form action="#" method="post">
                <label for="firstname">Prénom :</label>
                <input type="text" id="firstname" name="firstname">

                <label for="lastname">Nom :</label>
                <input type="text" id="lastname" name="lastname">

                <label for="email">Email :</label>
                <input type="email" id="email" name="email">

                <label for="pass">Mot de passe :</label>
                <input type="password" id="pass" name="pass">

                <button>S'inscrire !</button>
            </form>

            <p class="login-link">Déjà un compte ? <a href="signin.php">Se connecter</a></p>
        </div>
    </main>
</body>
</html>