<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon">
    <?php // On appelle les styles ?>
    <link rel="stylesheet" href="./assets/styles/mainStyle.css">
    <link rel="stylesheet" href="./assets/styles/homeStyle.css">
    <title>LEST-scoring</title>
</head>
<body>

    <?php
        $theme = $_COOKIE['theme'] ?? 'light';
        require_once ROOT_PATH . "/src/views/components/header.php";
        require_once ROOT_PATH . "/src/views/components/checks.php";
    ?>

    <div class="centered-div">
        <h1>Accès non autorisé</h1>
        <pre><?php  // print_r($this->tournaments) ?></pre>

        <p>
            Vous n'êtes pas autorisé à accéder à cette page.
        </p>
        <p>
            Pour retourner à l'accueil <a href="./" class="button">Cliquez ici</a>
        </p>
    </div>

    <?php require_once ROOT_PATH . "/src/views/components/footer.php"; ?>
</body>
</html>

