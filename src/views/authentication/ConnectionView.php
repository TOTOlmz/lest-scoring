<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon">
    <?php // On appelle les styles ?>
    <link rel="stylesheet" href="./assets/styles/mainStyle.css">
    <link rel="stylesheet" href="./assets/styles/authStyle.css">
    <title>LEST-scoring</title>
</head>
<body>
    
    <?php 
        require_once ROOT_PATH . "/src/views/components/header.php";
    ?>  

    <div class="connection">
        <?php require_once ROOT_PATH . '/src/Views/components/checks.php'; ?>
        <form class="connection-form" action="" method="POST">
            <h2>Connexion</h2>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            <input type="text" name="token" value="<?= isset($_SESSION["token"]) ? htmlspecialchars($_SESSION["token"]) : ""; ?>" hidden required>

            <button type="submit" name="login" class="button">Se connecter</button>
        </form>
        <p>Première connexion ? <a href="./inscription">Cliquez ici</a></p>
    </div>


<script>
    let button = document.querySelector('.connection-form button[type="submit"]');
    let input = document.querySelector('.connection-form input');
    button.style.width = input.offsetWidth - 10 + 'px';
    button.style.textAlign = 'center';
</script>
    

</body>
</html>

