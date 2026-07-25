<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon">
    <?php // On appelle les styles ?>
    <link rel="stylesheet" href="./assets/styles/mainStyle.css">
    <link rel="stylesheet" href="./assets/styles/homeStyle.css">
    <link rel="stylesheet" href="./assets/styles/elementListStyle.css">
    <title>LEST-scoring</title>
</head>
<body>

    <?php
        $theme = $_COOKIE['theme'] ?? 'light';
        require_once ROOT_PATH . "/src/views/components/header.php";
        require_once ROOT_PATH . "/src/views/components/checks.php";
    ?>

    <div class="centered-div">
        <h1>Accueil</h1>
        <pre><?php  // print_r($this->tournaments) ?></pre>

        <?php foreach ($this->tournaments as $t): ?>
            <div class="element-Area">
                <div class="element-title">
                    <h2>
                        <?= $t["club"] ?>, <?= $t["city"] ?> <em>(<?= $t["name"] ?>)</em>
                        <span class="tournament-full-dates">Du <?= $t["start_time"] ?> au <?= $t["end_time"] ?></span>
                        <span class="tournament-short-dates"><?= substr($t["start_time"], 0, 5) ?> → <?= substr($t["end_time"], 0, 5) ?></span>
                    </h2>
                    <button class="element-details-button" isOpen="false">❯</button>
                </div>
                <div class="element-details" isOpen="false">
                    <a class="button" href="./details?tournament=<?= $t["id_to_display"] ?>">Détails</a>
                    <h3>Scores : </h3>
                    <?php include ROOT_PATH . "/src/views/components/MatchesList.php"; ?>
                
                </div>
                
            </div>
        <?php endforeach; ?>

        <script src="./assets/scripts/elementList.js"> /* Script permettant de créer un système d'onglets pour les différents tournois */</script>
    </div>

    <?php require_once ROOT_PATH . "/src/views/components/footer.php"; ?>
</body>
</html>

