

<?php /* Section permettant d'accéder au formulaire de contact */ ?>
<link rel="stylesheet" href="./assets/styles/contactOverlayStyle.css">

<div class="overlay contact-overlay">
    <div class="pop-up">
        <h3>Contactez-nous</h3>
        <form action="" method="POST" class="contact-form">
            <input type="text" name="token" value="<?= isset($_SESSION["token"]) ? htmlspecialchars($_SESSION["token"]) : ""; ?>" hidden required>
            <?php if (isset($_SESSION["user_email"])): ?>
            <input type="text" id="name" name="name" value="<?= $_SESSION["user_name"] ?>" required>
            <input type="email" id="email" name="email" value="<?= $_SESSION["user_email"] ?>" required>
            <?php else: ?>
            <input type="text" id="name" name="name" placeholder="Votre nom" required>
            <input type="email" id="email" name="email" placeholder="Votre email" required>
            <?php endif; ?>
            <textarea id="message" name="message" rows="4" placeholder="Votre message" required></textarea>

            <button type="submit" name="contact-form" class="button">Envoyer</button>
        </form>
    </div>
</div>



<footer class="footer">
    <a href="./legal">mentions légales</a>
    |
    <a class="contact-form-calling-btn">Contactez-nous</a>
</footer>




<script>

    // script pour faire apparaitre les quizzes dans une div overlay
    const btns = document.querySelectorAll('.contact-form-calling-btn');    // Utilisation de queryAll a cause des mentions légales
    let contactOverlay = document.querySelector('.contact-overlay');
    contactOverlay.style.display = 'none';

    btns.forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.stopPropagation();
            console.log('omg he clicked me');
            contactOverlay.style.display = 'flex';
        });
    });
    
    
    contactOverlay.addEventListener('click', function(event) {
        if (event.target === contactOverlay) {
            contactOverlay.style.display = 'none';
        }
    });
</script>