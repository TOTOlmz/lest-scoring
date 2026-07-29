<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon">
    <?php // On appelle les styles ?>
    <link rel="stylesheet" href="./assets/styles/mainStyle.css">
    <link rel="stylesheet" href="./assets/styles/elementListStyle.css">
    <link rel="stylesheet" href="./assets/styles/editTournamentStyle.css">
    <link rel="stylesheet" href="./assets/styles/overlayStyle.css">
    <title>LEST-scoring</title>
</head>
<body>

    <?php
        require_once ROOT_PATH . "/src/views/components/header.php";
        require_once ROOT_PATH . "/src/views/components/checks.php";
    ?>


    <div class="centered-div">
        <p><?= $this->userStatus . " ← user status |:| authorisation utilisateur → " . $this->userAuthorisation ?></p>
        <h1>Page d'édition de tournoi</h1>

        <!-- Section gérant l'affichage des courts -->
        <div class="elt-area courts-area">
            <h3>Courts</h3>
            <?php if (!empty($this->tournament["courts"])): ?>
            <?php else: ?>
                <p>Aucun court renseigné. Ajoutez en un via le bouton ci-dessous.</p>
            <?php endif; ?>
            <button class="button" add-element="court">Ajouter</button>
        </div>

        <!-- Section gérant l'affichage des courts -->
        <div class="elt-area players-area">
            <h3>Joueurs</h3>
            <?php if (!empty($this->tournament["players"])): ?>
            <?php else: ?>
                <p>Aucun joueur renseigné. Ajoutez en un via le bouton ci-dessous.</p>
            <?php endif; ?>
            <button class="button" add-element="player">Ajouter</button>
        </div>

        <!-- Section gérant l'affichage des courts -->
        <div class="elt-area matches-area">
            <h3>matchs</h3>
            <?php if (!empty($this->tournament["matches"])): ?>
            <?php else: ?>
                <p>Aucun match renseigné. Ajoutez en un via le bouton ci-dessous.</p>
            <?php endif; ?>
            <button class="button" add-element="match">Ajouter</button>
        </div>

        <!-- Section gérant l'affichage des courts -->
        <div class="elt-area umpire-area">
            <h3>Arbitres</h3>
            <?php if (!empty($this->tournament["umpires"])): ?>
            <?php else: ?>
                <p>Aucun arbitre renseigné. Ajoutez en un via le bouton ci-dessous.</p>
            <?php endif; ?>
            <button class="button" add-element="umpire">Ajouter</button>
        </div>

    </div>

    
    
    <script> const currentToken = <?= json_encode($_SESSION["token"]); ?>; </script>
    <script src="./assets/scripts/addTournElementOverlay.js" type="module"></Script>


    <?php require_once ROOT_PATH . "/src/views/components/footer.php"; ?>
</body>
</html>