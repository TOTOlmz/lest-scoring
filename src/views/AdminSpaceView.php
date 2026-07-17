<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon">
    <?php // On appelle les styles ?>
    <link rel="stylesheet" href="./assets/styles/mainStyle.css">
    <link rel="stylesheet" href="./assets/styles/adminStyle.css">
    <title>LEST-scoring</title>
</head>
<body>

    <?php
        require_once ROOT_PATH . "/src/views/components/header.php";
        require_once ROOT_PATH . "/src/views/components/checks.php";
    ?>

    <div class="centered-div">
        <h1>Espace Administrateur</h1>
        <pre><?php  // print_r($this->tournaments) ?></pre>

        <div class="admin-forms-block">
            <form class="admin-form" method="post">
                <h3>Créer un compte directeur permanent : </h3>
                <input type="text" name="token" value="<?= isset($_SESSION["token"]) ? htmlspecialchars($_SESSION["token"]) : ""; ?>" hidden required>
                <input type="email" name="tournament-director" placeholder="Email du directeur" required />
                <button class="button admin-button" type="submit" name="add-director">Ajouter</button>
            </form>

            <form class="admin-form" method="post">
                <h3>Créer un compte tournoi : </h3>
                <input type="text" name="token" value="<?= isset($_SESSION["token"]) ? htmlspecialchars($_SESSION["token"]) : ""; ?>" hidden required>
                <input type="text" name="tournament-name" placeholder="Nom du tournoi" required />
                <input type="email" name="director-email" placeholder="Email du directeur (facultatif)" />
                <button class="button admin-button" type="submit" name="create-tournament">Ajouter</button>
            </form>
        </div>

        <div class="admin-space-block">
            <h3>Recherche (tournoi ou directeur) : </h3>
            <form class="admin-search-form" method="post">
                <input type="text" name="token" value="<?= isset($_SESSION["token"]) ? htmlspecialchars($_SESSION["token"]) : ""; ?>" hidden required>
                <input type="text" name="search-field" placeholder="Valeur à chercher" required />
                <select name="search-category" required>
                    <option value="" hidden selected>Catégorie</option>
                    <option value="name">Nom</option>
                    <option value="city">Ville</option>
                    <option value="club">Club</option>
                    <option value="email">Email</option>
                </select>
                <button class="button admin-button" type="submit" name="search-form">Rechercher</button>
            </form>
            <div class="admin-search-results">
                <?php if (isset($this->result["searching"]) && $this->result["searching"] === true): ?>
                    <?php require ROOT_PATH . "/src/views/components/researchResults.php" ?>
                <?php elseif (isset($this->result["searching"]) && $this->result["searching"] === false):  ?>
                    Aucun résultat trouvé.
                <?php else: ?>
                    Aucune recherche lancée.
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
        require_once ROOT_PATH . "/src/views/components/footer.php";
    ?>
</body>
</html>