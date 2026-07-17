<nav class="navbar">
    <a href="./" class="brand-area">
        <img class="navbar-logo" src="./assets/images/LEST.svg" alt="Logo">
    </a>

    <div class="links-area" id="navbar-menu">
        <?php if ($this->isConnected): ?>
            <?php if (!$this->isInUserSpace): ?>
                <?php if ($this->isAdmin): ?>
                    <a class="button nav-btn" href="./espace-admin">Espace admin</a>
                <?php else: ?>
                    <a class="button nav-btn" href="./mon-espace">Mon espace</a>
                <?php endif; ?>
            <?php endif; ?>
                <form method="POST" action="./deconnexion">
                    <button class="button nav-btn" type="submit">Déconnexion</button>
                </form>
        <?php else: ?>
                <a class="button nav-btn" href="./connexion">Connexion</a>
        <?php endif; ?>
    </div>
</nav>

<?php
    // echo "session : <pre>"; print_r($_SESSION); echo "</pre>";
    // echo "connecté : "; echo $this->isConnected ? "oui" : "non"; echo "<br>";
    // echo "Administrateur : "; echo $this->isAdmin ? "oui" : "non"; echo "<br>";
    // echo "Dans l'espace personnel : "; echo $this->isInUserSpace ? "oui" : "non"; echo "<br>";
?>


<script src="./assets/scripts/faviconAdaptation.js"> /* Script permettant d'adapter la favicon en fonction du thème du navigateur */ </script>