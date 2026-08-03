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
        
        <div class="edition-area">

            <!-- Section gérant l'affichage des infos de tournoi -->
            <div class="elt-area tournament-area">
                <h3><?= $this->tournament["name"] !== "" ? $this->tournament["name"] : "Aucun nom de tournoi renseigné"; ?></h3>
                <p><strong>Infos du tournoi</strong></p>
                <p>
                    <span><strong>Club :</strong><?= $this->tournament["club"] !== "" ? $this->tournament["club"] : "Aucun club renseigné"; ?></span>
                    <span><strong>Ville :</strong><?= $this->tournament["city"] !== "" ? $this->tournament["city"] : "Aucune ville renseigné"; ?></span>
                </p>
                <p><?= ($this->tournament["start_time"] !== ""
                && $this->tournament["end_time"] !== "")
                ? "Du " . date("d/m/y", intval($this->tournament["start_time"])) . " au " . date("d/m/y", intval($this->tournament["end_time"])) : "Aucune date renseigné" ?></p>
                <button class="button" add-element="tournament"
                data-id_to_display = "<?= $this->tournament["id_to_display"] ?>"
                data-name="<?= $this->tournament["name"] ?>" data-club="<?= $this->tournament["club"] ?>" data-city="<?= $this->tournament["city"] ?>"
                data-start="<?= date("Y-m-d", intval($this->tournament["start_time"])) ?>" data-end="<?= date("Y-m-d", intval($this->tournament["end_time"])) ?>">Éditer</button>
            </div>
        </div>

        <div class="edition-area">
            <!-- Section gérant l'affichage des tableaux du tournoi -->
            <div class="element-Area">
                <div class="element-title">
                    <?php if (empty($this->tournament["draws"]) || count($this->tournament["draws"], COUNT_NORMAL) <= 1): ?>
                        <h2>Tableau</h2>
                    <?php else: ?>
                        <h2>Tableaux</h2>
                    <?php endif; ?>
                    <button class="element-details-button" isOpen="false">❯</button>
                </div>
                <div class="element-details" isOpen="false">
                    <?php if (!empty($this->tournament["draws"]) && count($this->tournament["draws"]) > 1): ?>
                        <?php foreach($this->tournament["draws"] as $draw): ?>
                            <div class="details-infos-area">
                                <p>
                                    <strong><?= isset($draw["title"]) ? $draw["title"] : "" ?> : </strong>
                                    Nombre de participants : <?= isset($draw["size"]) ? $draw["size"] : "" ?>
                                    Format de jeu : <?= isset($draw["type"]) ? $draw["type"] : "" ?>
                                </p>
                            </div>
                            <div class="details-action-buttons">
                                <a class="button" href="./details?tournament=<?= $t["id_to_display"] ?>">Détails</a>
                                <a class="button" href="./edit?tournament=<?= $t["id_to_display"] ?>">Éditer</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Le tournoi ne possède aucun tableau</p>
                    <?php endif; ?>
                    <button class="button" add-element="draw">Ajouter</button>
                </div>
            </div>
        </div>

        <div class="edition-area">

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

        </div>

        <div class="edition-area">

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
    </div>

    
    
    <script> const currentToken = <?= json_encode($_SESSION["token"]); ?>; </script>
    <script src="./assets/scripts/addTournElementOverlay.js" type="module"></Script>
    <script src="./assets/scripts/ElementList.js" type="module"></Script>


    <?php require_once ROOT_PATH . "/src/views/components/footer.php"; ?>
</body>
</html>