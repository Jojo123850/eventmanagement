<?php
session_start();
$message = "";


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (empty($_POST['email']) || empty($_POST['pass'])) {

        $message = "Tous les champs doivent être remplis.";

    } else {
        $email = htmlspecialchars(trim ($_POST['email']));
        $pass = htmlspecialchars(trim($_POST['pass']));
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $message = "Email invalide.";
            } else {
                // LE METTRE au moment ou il faut faire la 1ère requete 
                require "config/connect.php";
                $sql = $db->prepare("SELECT id_user,firstname_user, lastname_user, pass_user, fk_id_role FROM users WHERE email_user = :email");

                $sql->execute([
                    'email' => $email
                ]);

               $user = $sql->fetch(PDO::FETCH_ASSOC);

                if($user && password_verify($pass, $user['pass_user'])){
                    // on fait la connexion 
                  
                    $_SESSION['email'] = $email;
                    $_SESSION['firstname'] = $user['firstname_user'];
                    $_SESSION['lastname'] = $user['lastname_user'];
                    $_SESSION['id_user'] = $user['id_user'];
                    $_SESSION['role'] = $user['fk_id_role']; 

                    header("Location: profile.php");
                    exit;

                } else {
                    $message = "Email ou mot de passe incorrect.";
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
    <title>Connexion</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="login-page">

    <div class="login-container">
        <h1>Connexion</h1>
        
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form action="#" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email">

            <label for="pass">Mot de passe :</label>
            <input type="password" id="pass" name="pass">

            <button>Se connecter !</button>
        </form>

        <a href="register.php" class="register-link">Pas encore inscrit ?</a>
    </div>

</body>
</html>