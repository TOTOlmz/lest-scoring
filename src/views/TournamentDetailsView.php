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
    <title>LEST-scoring</title>
</head>
<body>

    <?php
        require_once ROOT_PATH . "/src/views/components/header.php";
        require_once ROOT_PATH . "/src/views/components/checks.php";
    ?>

    <div class="centered-div">
        
        <h1><?= $this->tournament["club"] ?>, <?= $this->tournament["city"] ?> <em>(<?= $this->tournament["name"] ?>)</em></h1>

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
                        <div class="draw-area">

                            <?php foreach ($draw["rounds"] as $round): ?>
                                <div class="draw-column">
                                    <?php foreach ($round as $match): ?>
                                    <div class="match-card">
                                            <p data-winner="<?= $match["winner"] === "TA" ? "true" : "false" ?>">
                                                <?= $match["teamAP1_name"] ?><?= (isset($match["teamAP2_name"]) && $match["teamAP2_name"] !== "") ? " / " . $match["teamAP2_name"] : "" ?>
                                            </p>
                                            <p><?= $match["final_score"] ?></p>
                                            <p data-winner="<?= $match["winner"] === "TB" ? "true" : "false" ?>">
                                                <?= $match["teamBP1_name"] ?><?= (isset($match["teamBP2_name"]) && $match["teamBP2_name"] !== "") ? " / " . $match["teamBP2_name"] : "" ?>
                                            </p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                            <?php endforeach; ?>
                        </div>

                <?php endforeach; ?>
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
                        <?php if ($player["nationality"] && $player["nationality"] !== ""): ?>
                            <img class="player-flag" src="./assets/images/flags/<?= strtolower($player["nationality"]) ?>.svg" alt="<?= $player["nationality"] ?>">
                        <?php endif; ?>
                        <p><?= ucfirst($player["firstname"]) ?> <?= strtoupper($player["lastname"]) ?> <?= $player["rank"] ?></p>
                        </div>
                        <div class="player-actions">
                            <button class="button edit-player-btn" data-id="<?= $player["id_to_display"] ?>">✎</button>
                            <button class="button delete-player-btn" data-id="<?= $player["id_to_display"] ?>">✖</button>
                        </div>
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
                        <div class="court-actions">
                            <button class="button edit-court-btn" data-id="<?= $court["id_to_display"] ?>">✎</button>
                            <button class="button delete-court-btn" data-id="<?= $court["id_to_display"] ?>">✖</button>
                        </div>
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
                        <div class="umpire-actions">
                            <button class="button edit-umpire-btn" data-id="<?= $umpire["id_to_display"] ?>">✎</button>
                            <button class="button delete-umpire-btn" data-id="<?= $umpire["id_to_display"] ?>">✖</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script src="./assets/scripts/elementList.js"> /* Script permettant de créer un système d'onglets pour les différents tournois */</script>
        <pre><?php  print_r($this->tournament) ?></pre>

    </div>


    <div class="overlay" id="overlay"></div>

    <?php require_once ROOT_PATH . "/src/views/components/footer.php"; ?>
</body>
</html>