function cardEdition (button, draw, players) {
    if (!button.hasAttribute("round") || !button.hasAttribute("match") || !button.hasAttribute("match-index")) {
        console.log("Inpossible de récupérer les infos concernant le match");
        return;
    }

    const matchPos = button.getAttribute("match");
    const matchIndex = button.getAttribute("match-index");
    const roundPos = button.getAttribute("round");

    if (draw && draw.rounds && draw.rounds[roundPos] && draw.rounds[roundPos][matchPos]) {
        const matchToEdit = draw.rounds[roundPos][matchPos];
        createCardForm(matchToEdit, players);
    } else {
        console.log("impossible de récupérer le match demandé.");
    }

    
}

function createCardForm (matchData, players) {
    let drawContainer = document.querySelector("div.draw-container");
    
    let formArea = document.createElement("div");
    formArea.classList.add("match-edition-form");
    let htmlCode = `
    <label>Liste des joueurs existants :
        <select data-match-card-input="exist">`;

    players.forEach(p => {
        htmlCode += `<option value="${p["id_to_display"]}">${p["firstname"]} ${p["lastname"]}</option>`
    });
    
            
    htmlCode += `</select>
    </label>
    <label>Prénom :
    <input type="text" data-match-card-input="fname"/></label>
    <label>Nom :
    <input type="text" data-match-card-input="lname"/></label>
    <label>Nationalité :
    <input type="text" data-match-card-input="nat"/></label>
    <label>Classement :
    <input type="text" data-match-card-input="rank"/></label>
    <div class="data-match-card-btn">
        <button class="button data-match-card-btn" data-match-card-update>Valider</button>
        <button class="button data-match-card-btn" data-match-card-remove>Annuler</button>
    </div>`;

    formArea.innerHTML = htmlCode;
    drawContainer.appendChild(formArea);
    //** ATEJOIFBOIFbnoieznvoierbnveoibguoidfbnvoiribnveoiinveroignsdofiihnzieucnieh,rfiscrhcgzuirehiezcruhf */
    FETCH a Faire lorsque l'on valide. Suppression de la div quand on clique en dehors ou sur annuler. et modif dans la base de donnée : AJOUT tourn_id a player et type (singles doubles) à la place de format dans draw
}

export {cardEdition};