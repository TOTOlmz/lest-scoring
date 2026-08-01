import {createOverlay, removeOverlay} from "./components/overlay.js";

let addEltBtns = document.querySelectorAll("button[add-element]");

addEltBtns.forEach(addButton => {

    addButton.addEventListener("click", () => {

        // On récupère l'élément de add-tournament pour appeler le bon formulaire en fonction
        const buttonElement = addButton.getAttribute("add-element");
        createOverlay();
        console.log("Overlay appelé : " + buttonElement);

        if (buttonElement === "tournament") {

            const idToDisplay = addButton.getAttribute("data-id_to_display");
            const name = addButton.getAttribute("data-name");
            const club = addButton.getAttribute("data-club");
            const city = addButton.getAttribute("data-city");
            const start = addButton.getAttribute("data-start");
            const end = addButton.getAttribute("data-end");
            console.log(start, end);
        
            document.getElementById('popupDiv').innerHTML =
            `<h3>Éditer le tournoi : </h3>
            <form action="" method="POST" class="popup-form">
                <input type="text" name="token" value="${currentToken}" hidden/>
                <input type="text" name="id_to_display" value="${idToDisplay}" hidden/>
                <label>Nom : <input type="text" name="name" value="${name}" required/></label>
                <label>Club : <input type="text" name="club" value="${club}" required/></label>
                <label>Ville : <input type="text" name="city" value="${city}" required/></label>
                <label>Date de début : <input type="date" name="start" value="${start}" required/></label>
                <label>Date de fin : <input type="date" name="end" value="${end}" required/></label>
                <button type="submit" name="edit-tourn" class="button">Éditer</button>
            </form>`;


        } else if (buttonElement === "court") {

            document.getElementById('popupDiv').innerHTML =
            `<h3>Ajouter un court : </h3>
            <form action="" method="POST" class="popup-form">
                <input type="text" name="token" value="${currentToken}" hidden/>
                <label>Nom : <input type="text" name="name" placeholder="Nom du court" required/></label>
                <label>Mot de passe : <input type="text" name="pass" placeholder="Nécessaire pour l'arbitrage" required/></label>
                <button type="submit" name="add-court" class="button">Éditer</button>
            </form>`;


        } else if (buttonElement === "player") {
            
            document.getElementById('popupDiv').innerHTML =
            `<h3>Ajouter un joueur : </h3>
            <form action="" method="POST" class="popup-form">
                <input type="text" name="token" value="${currentToken}" hidden/>
                <label>Nom : <input type="text" name="name" placeholder="Nom du joueur" required/></label>
                <label>Prénom : <input type="text" name="name" placeholder="Prénom du joueur" required/></label>
                <label>Nationalité : <input type="text" name="name" placeholder="En trois lettres (ex: FRA (france), GER (allemagne)..." required/></label>
                <label>Classement : <input type="text" name="rank" placeholder="au format 12/3 ou 123" required/></label>
                <button type="submit" name="add-player" class="button">Éditer</button>
            </form>`;


        } else if (buttonElement === "match") {

            document.getElementById('popupDiv').innerHTML =
            `<h3>Ajouter un match : </h3>
            <p> Les matchs ajoutés sont considérés comme <em>hors du draw</em>. Ce sont des matchs d'exhibition.</p>
            <p> Si vous souhaitez modifier un match du draw, cliqué sur le bouton d'édition du match directement dans le draw.</p>
            <form action="" method="POST" class="popup-form">
                <input type="text" name="token" value="${currentToken}" hidden/>
                <label>Nom : <input type="text" name="name" placeholder="Nom du joueur" required/></label>
                <label>Prénom : <input type="text" name="name" placeholder="Prénom du joueur" required/></label>
                <label>Nationalité : <input type="text" name="name" placeholder="En trois lettres (ex: FRA (france), GER (allemagne)..." required/></label>
                <label>Classement : <input type="text" name="rank" placeholder="au format 12/3 ou 123" required/></label>
                <button type="submit" name="add-match" class="button">Éditer</button>
            </form>`;

            
        } else if (buttonElement === "umpire") {
            
            document.getElementById('popupDiv').innerHTML =
            `<h3>Ajouter un arbitre : </h3>
            <form action="" method="POST" class="popup-form">
                <input type="text" name="token" value="${currentToken}" hidden/>
                <label>Nom : <input type="text" name="name" placeholder="Nom de l'arbitre" required/></label>
                <label>Prénom : <input type="text" name="name" placeholder="Prénom de l'arbitre" required/></label>
                <label>Pseudo : <input type="text" name="name" placeholder="Utilisé pour arbitrer sur un court" required/></label>
                <button type="submit" name="add-umpire" class="button">Éditer</button>
            </form>`;

        }

        
        removeOverlay();

    });
});
