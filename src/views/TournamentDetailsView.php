<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon">
    <?php // On appelle les styles ?>
    <link rel="stylesheet" href="./../assets/styles/mainStyle.css">
    <title>LEST-scoring</title>
</head>
<body>

    <?php
        require_once ROOT_PATH . "/src/views/components/header.php";
        require_once ROOT_PATH . "/src/views/components/checks.php";
    ?>

    <div class="centered-div">
        <h1><?= $this->tournament["name"] ?></h1>
        <pre><?php  print_r($this->tournament) ?></pre>


    <?php
        require_once ROOT_PATH . "/src/views/components/footer.php";
    ?>
</body>
</html>