
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
            
            <?php 
                require_once ROOT_PATH . "/src/views/components/checks.php"; 
            ?>
            
            
            <h2>Créer un compte</h2>
            <form class="connection-form" method="post" action="">
                <input type="text" id="name" name="name" placeholder="Nom" required>
                <input type="email" id="email" name="email" placeholder="email" required>
                <input type="password" id="password" name="password" placeholder="mot de passe" required>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmation du mot de passe" required>
                <input type="text" name ="token" value="<?= $_SESSION["token"] ?? "" ?>" hidden>
                <button type="submit" id="submit-button" name="registration" class="button">S'inscrire</button>
                <div class="password-requirements">
                    <p> Le mot de passe doit contenir au moins :</p>
                    <span class="pass pass-length">8 caractères</span>
                    <span class="pass pass-upper">Une majuscule</span>
                    <span class="pass pass-lower">Une minuscule</span>
                    <span class="pass pass-number">Un chiffre</span>
                    <span id="passconf-label" class="pass">Les mots de passe ne correspondent pas.</span>
                </div>        
            </form>
            <div>
                <p> Déjà un compte ? <a href="./connexion">cliquez ici</a></p>
            </div>
        </div>
        <div class="form-container">
            
            

        </div>
    

    <script src="./assets/scripts/AuthRegistration.js"></script>
</body>
</html>

