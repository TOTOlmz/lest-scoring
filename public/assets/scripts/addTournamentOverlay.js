import {createOverlay, removeOverlay} from "./components/overlay.js";

/*
SCRIPT PERMETTANT DE GÉRER L'AFFICHAGE DANS L'OVERLAY D'AJOUT D'UN TOURNOI DE LA PAGE edit?tournament=
*/

let addTournBtn = document.querySelector("button.add-tournament");

addTournBtn.addEventListener("click", () => {

    createOverlay();

    document.getElementById('popupDiv').innerHTML =
    `<h3>Créer un tournoi : </h3>
    <form action="" method="POST" class="popup-form">
        <input type="text" name="token" value="${currentToken}" hidden/>
        <input type="text" name="director" value="${dirId}" hidden/>
        <label>Nom : <input type="text" name="name" placeholder="Nom du tournoi" required/></label>
        <label>Ville : <input type="text" name="city" placeholder="Ville du tournoi / club" required/></label>
        <label>Club : <input type="text" name="club" placeholder="Club organisateur" required/></label>
        <label>Nombre de joueurs : 
            <select name="draw-size" required>
                <option value="4">4</option>
                <option value="8">8</option>
                <option value="16">16</option>
                <option value="32" selected>32</option>
                <option value="64">64</option>
                <option value="128">128</option>
            </select>
        </label>
        <div class="dates">
            <label class="dates-label">Début : <input type="date" name="start" required/></label>
            <label class="dates-label">Fin : <input type="date" name="end" required/></label>
        </div>
        <button type="submit" name="add-tournament" class="button">Éditer</button>
    </form>`;

    
    removeOverlay();

});
