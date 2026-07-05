<?php
$role = $_SESSION['role'] ?? null;
?>
<header>
    <nav>
        <ul>
            <?php if (!$role): ?>
                <li><a href="signin.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
            <?php else: ?>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="events.php">Evenements</a></li>

                <?php if ($role == 2 || $role == 1): ?>
                    <li><a href="createevent.php">Créer un événement</a></li>
                <?php endif; ?>

                <?php if ($role == 1): ?>
                    <li><a href="admin.php">Administration</a></li>
                <?php endif; ?>

                <li><a href="index.php">Déconnexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>