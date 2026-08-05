<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon">
    <?php // On appelle les styles ?>
    <link rel="stylesheet" href="./assets/styles/mainStyle.css">
    <link rel="stylesheet" href="./assets/styles/elementListStyle.css">
    <link rel="stylesheet" href="./assets/styles/detailsStyle.css">
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
        
        <h1><?= $this->tournament["club"] ?>, <?= $this->tournament["city"] ?> <em>(<?= $this->tournament["name"] ?>)</em></h1>

        <!-- MATCHS -->
        <div class="element-Area">
            <div class="element-title">
                <h2>Matchs</h2>
                <button class="element-details-button" isOpen="false">❯</button>
            </div>
            <div class="element-details" isOpen="false">
                <!-- pour chaque draw (dans l'éventualité d'un double et d'un simple ) -->
                <?php $t = $this->tournaments[0]; ?>
                <?php require ROOT_PATH . "/src/views/components/MatchesList.php"; ?>

            </div>
        </div>

        <!-- DRAWS -->
        <div class="element-Area">
            <div class="element-title">
                <h2>Arborescence</h2>
                <button class="element-details-button" isOpen="false">❯</button>
            </div>
            <div class="element-details" isOpen="false">
                <!-- pour chaque draw (dans l'éventualité d'un double et d'un simple ) -->
                <?php foreach ($this->tournament["draws"] as $draw): ?>
                    
                    <!-- pour chaque colonne du draw -->
                    <h3><?= $draw["title"] ?></h3>
                    <div class="draw-area">

                        <?php foreach ($draw["rounds"] as $rkey => $round): ?>
                            <div class="draw-column" data-round="<?= $rkey ?>">
                                <?php foreach ($round as $mkey => $match): ?>
                                <div class="match-card">
                                    <p data-winner="<?= $match["winner"] === "TA" ? "true" : "false" ?>">
                                        <?= $match["teamAP1_name"] ?><?= (isset($match["teamAP2_name"]) && $match["teamAP2_name"] !== "") ? " / " . $match["teamAP2_name"] : "" ?>
                                    </p>
                                    <p><?= $match["final_score"] ?></p>
                                    <p data-winner="<?= $match["winner"] === "TB" ? "true" : "false" ?>">
                                        <?= $match["teamBP1_name"] ?><?= (isset($match["teamBP2_name"]) && $match["teamBP2_name"] !== "") ? " / " . $match["teamBP2_name"] : "" ?>
                                    </p>
                                    <?php if (count($round) > 1): ?>
                                        <span class="draw-h-start-branch"></span>  
                                    <?php endif; ?>                                          
                                    <?php if ($mkey % 2 === 0): ?>
                                        <span class="draw-v-branch">
                                            <span class="draw-h-end-branch"></span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>

                        <?php endforeach; ?>
                    </div>

                <?php endforeach; ?>
                <script type="module">
                    import {drawAdaptation} from "./assets/scripts/components/drawAdaptation.js"; 
                    drawAdaptation();
                </script>
            </div>
        </div>


        <!-- JOUEURS -->
        <div class="element-Area">
            <div class="element-title">
                <h2>Joueurs</h2>
                <button class="element-details-button" isOpen="false">❯</button>
            </div>
            <div class="element-details" isOpen="false">
                <?php foreach ($this->tournament["players"] as $player): ?>
                    <div class="player-card">
                        <div class="player-infos">
                            <div>
                                <p><?= ucfirst($player["firstname"]) ?> <?= strtoupper($player["lastname"]) ?></p>
                            </div>
                            <div>
                            <?php if ($player["nationality"] && $player["nationality"] !== ""): ?>
                                <img class="player-flag" src="./assets/images/flags/<?= strtolower($player["nationality"]) ?>.svg" alt="<?= $player["nationality"] ?>">
                            <?php endif; ?>
                            <?php if ($player["rank"] && $player["rank"] !== ""): ?>
                                <p><?= $player["rank"] ?></p>
                            <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($this->userAuthorisation): ?>
                            <div class="player-actions">
                                <button class="button edit-player-btn" data-id="<?= $player["id_to_display"] ?>" 
                                player-fname="<?= $player["firstname"] ?>" player-lname="<?= $player["lastname"] ?>" 
                                player-nat="<?= $player["nationality"] ?>" player-rank="<?= $player["rank"] ?>" 
                                overlay-call="edit" data-element="player">✎</button>

                                <button class="button delete-player-btn" data-id="<?= $player["id_to_display"] ?>" 
                                player-fname="<?= $player["firstname"] ?>" player-lname="<?= $player["lastname"] ?>"
                                overlay-call="delete" data-element="player"
                                player-id="<?= $player["id_to_display"] ?>">✖</button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- COURTS -->
        <div class="element-Area">
            <div class="element-title">
                <h2>Courts</h2>
                <button class="element-details-button" isOpen="false">❯</button>
            </div>
            <div class="element-details" isOpen="false">
                <?php foreach ($this->tournament["courts"] as $court): ?>
                    <div class="court-card">
                        <div class="court-infos">
                        
                        <p><?= ucfirst($court["name"]) ?></p>
                        </div>
                        <?php if ($this->userAuthorisation): ?>
                            <div class="court-actions">
                                <button class="button edit-court-btn" data-id="<?= $court["id_to_display"] ?>"
                                court-name="<?= $court["name"] ?>" court-pass="<?= $court["password"] ?>" 
                                overlay-call="edit" data-element="court">✎</button>

                                <button class="button delete-court-btn" data-id="<?= $court["id_to_display"] ?>" court-name="<?= $court["name"] ?>" 
                                overlay-call="delete" data-element="court">✖</button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- ARBITRES -->
        <div class="element-Area">
            <div class="element-title">
                <h2>Arbitres</h2>
                <button class="element-details-button" isOpen="false">❯</button>
            </div>
            <div class="element-details" isOpen="false">
                <?php foreach ($this->tournament["umpires"] as $umpire): ?>
                    <div class="umpire-card">
                        <div class="umpire-infos">

                        <p><?= ucfirst($umpire["firstname"]) ?> <?= strtoupper($umpire["lastname"]) ?> (<?= $umpire["username"] ?>)</p>
                        </div>
                        <?php if ($this->userAuthorisation): ?>
                            <div class="umpire-actions">
                                <button class="button edit-umpire-btn" data-id="<?= $umpire["id_to_display"] ?>" 
                                umpire-uname="<?= $umpire["username"] ?>"
                                umpire-fname="<?= $umpire["firstname"] ?>" umpire-lname="<?= $umpire["lastname"] ?>"
                                overlay-call="edit" data-element="umpire">✎</button>

                                <button class="button delete-umpire-btn" data-id="<?= $umpire["id_to_display"] ?>" 
                                umpire-uname="<?= $umpire["username"] ?>"
                                umpire-fname="<?= $umpire["firstname"] ?>" umpire-lname="<?= $umpire["lastname"] ?>"
                                overlay-call="delete" data-element="umpire">✖</button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script src="./assets/scripts/elementList.js"> /* Script permettant de créer un système d'onglets pour les différents tournois */</script>

    </div>

    <script> const currentToken = <?= json_encode($_SESSION["token"]); ?>; </script>
    <script src="./assets/scripts/tournamentDetailsOverlay.js" type="module"></script>
    <?php require_once ROOT_PATH . "/src/views/components/footer.php"; ?>
</body>
</html>