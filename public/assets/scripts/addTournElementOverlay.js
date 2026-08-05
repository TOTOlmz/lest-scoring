import {createOverlay, removeOverlay} from "./components/overlay.js";

/*
SCRIPT PERMETTANT DE GÉRER L'AFFICHAGE DANS LES OVERLAYS D'AJOUT (BOUTONS AJOUTER) DE LA PAGE edit?tournament=
*/
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


        } else if (buttonElement === "draw") {

            document.getElementById('popupDiv').innerHTML =
            `<h3>Ajouter un tableau : </h3>
            <form action="" method="POST" class="popup-form">
                <input type="text" name="token" value="${currentToken}" hidden/>
                <label>Titre du tableau : <input type="text" name="title" placeholder="ex : Simples Dames / Tounoi des trois raquettes..." required/></label>
                <label>Nombre de joueurs : <input type="number" name="size" placeholder="64" required/></label>
                <label>Format de jeu : <select name="type" required>
                    <option value="1">3 sets à 6 jeux, avantages, 3e set classique (Format 1)</option>
                    <option value="2">2 sets à 6 jeux, avantages, 3e set super jeu décisif (format 2)</option>
                    <option value="3">2 sets à 4 jeux, point décisif, 3e set super jeu décisif (format 3)</option>
                    <option value="4">2 sets à 6 jeux, point décisif, 3e set super jeu décisif (format 4)</option>
                    <option value="5">2 sets à 3 jeux, point décisif, 3e set super jeu décisif (format 5)</option>
                    <option value="6">2 sets à 4 jeux, point décisif, 3e set super jeu décisif (format 6)</option>
                    <option value="7">2 sets à 5 jeux, point décisif, 3e set super jeu décisif (format 7)</option>
                </select></label>
                <button type="submit" name="add-elt" value="draw" class="button">Ajouter</button>
            </form>`;


        }  else if (buttonElement === "court") {

            document.getElementById('popupDiv').innerHTML =
            `<h3>Ajouter un court : </h3>
            <form action="" method="POST" class="popup-form">
                <input type="text" name="token" value="${currentToken}" hidden/>
                <label>Nom : <input type="text" name="name" placeholder="Nom du court" required/></label>
                <label>Mot de passe : <input type="text" name="pass" placeholder="Nécessaire pour l'arbitrage" required/></label>
                <button type="submit" name="add-elt" value="court" class="button">Ajouter</button>
            </form>`;


        } else if (buttonElement === "player") {
            
            document.getElementById('popupDiv').innerHTML =
            `<h3>Ajouter un joueur : </h3>
            <form action="" method="POST" class="popup-form">
                <input type="text" name="token" value="${currentToken}" hidden/>
                <label>Nom : <input type="text" name="lastname" placeholder="Nom du joueur" required/></label>
                <label>Prénom : <input type="text" name="firstname" placeholder="Prénom du joueur" required/></label>
                <label>Nationalité : <input type="text" name="nationality" placeholder="En trois lettres (ex: FRA (france), GER (allemagne)..." required/></label>
                <label>Classement : <input type="text" name="rank" placeholder="au format 12/3 ou 123" required/></label>
                <button type="submit" name="add-elt" value="player" class="button">Ajouter</button>
            </form>`;


        } else if (buttonElement === "match") {

            document.getElementById('popupDiv').innerHTML =
            `<h3>Ajouter un match : </h3>
            <p> Les matchs ajoutés sont considérés comme <em>hors du draw</em>. Ce sont des matchs d'exhibition.</p>
            <p> Si vous souhaitez modifier un match du draw, cliqué sur le bouton d'édition du match directement dans le draw.</p>
            <form action="" method="POST" class="popup-form">
                <input type="text" name="token" value="${currentToken}" hidden/>
                <label>Format de jeu : <select name="type" required>
                    <option value="1">3 sets à 6 jeux, avantages, 3e set classique (Format 1)</option>
                    <option value="2">2 sets à 6 jeux, avantages, 3e set super jeu décisif (format 2)</option>
                    <option value="3">2 sets à 4 jeux, point décisif, 3e set super jeu décisif (format 3)</option>
                    <option value="4">2 sets à 6 jeux, point décisif, 3e set super jeu décisif (format 4)</option>
                    <option value="5">2 sets à 3 jeux, point décisif, 3e set super jeu décisif (format 5)</option>
                    <option value="6">2 sets à 4 jeux, point décisif, 3e set super jeu décisif (format 6)</option>
                    <option value="7">2 sets à 5 jeux, point décisif, 3e set super jeu décisif (format 7)</option>
                </select></label>
                <label>Équipe 1 Joueur 1 : <input type="text" name="TAP1" placeholder="Nom du joueur" required/></label>
                <label>Équipe 1 Joueur 2 : <input type="text" name="TAP2" placeholder="Nom du joueur" required/></label>
                <label>Équipe 2 Joueur 1 : <input type="text" name="TBP1" placeholder="Nom du joueur" required/></label>
                <label>Équipe 2 Joueur 2 : <input type="text" name="TBP2" placeholder="Nom du joueur" required/></label>
                <button type="submit" name="add-elt" value="match" class="button">Ajouter</button>
            </form>`;

            
        } else if (buttonElement === "umpire") {
            
            document.getElementById('popupDiv').innerHTML =
            `<h3>Ajouter un arbitre : </h3>
            <form action="" method="POST" class="popup-form">
                <input type="text" name="token" value="${currentToken}" hidden/>
                <label>Nom : <input type="text" name="lastname" placeholder="Nom de l'arbitre" required/></label>
                <label>Prénom : <input type="text" name="firstname" placeholder="Prénom de l'arbitre" required/></label>
                <label>Pseudo : <input type="text" name="username" placeholder="Utilisé pour arbitrer sur un court" required/></label>
                <button type="submit" name="add-elt" value="umpire" class="button">Ajouter</button>
            </form>`;

        }

        
        removeOverlay();

    });
});
