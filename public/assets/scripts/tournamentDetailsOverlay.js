



let osButtons = document.querySelectorAll('button[overlay-call]');
osButtons.forEach(button => {
    button.addEventListener('click', () => {

        console.log("overlay called");
        // On récupère le type de données souhaité
        let actionType = button.getAttribute("overlay-call");
        let dataType = button.getAttribute("data-element");

        // on crée les divs de l'overlay et de la pop-up
        createOverlay();
        
        /* |||||||||||||||||||||||||||||| */
        /* SECTION CONCERNANT LES JOUEURS */ 
        /* |||||||||||||||||||||||||||||| */
        if (dataType === 'player') {

            const playerId = button.getAttribute("data-id") ?? "";
            if (!playerId) { console.log("Impossible d'éditer ce joueur. Identifiant public manquant."); return; }

            if (actionType === "edit") {

                const playerFname = button.getAttribute("player-fname") ?? "";
                const playerLname = button.getAttribute("player-lname") ?? "";
                const playerNat = button.getAttribute("player-nat") ?? "";
                const playerRank = button.getAttribute("player-rank") ?? "";

                document.getElementById('popupDiv').innerHTML =
                `<h3>Edition d'un joueur : </h3>
                <form action="" method="POST" class="popup-form">
                    <input type="text" name="token" value="${currentToken}" hidden/>
                    <input type="text" name="public-id" value="${playerId}" hidden/>
                    <label>Prénom : <input type="text" name="player-fname" value="${playerFname}" required/></label>
                    <label>Nom : <input type="text" name="player-lname" value="${playerLname}" required/></label>
                    <label>Nationalité : <input type="text" name="player-nat" value="${playerNat}" required/></label>
                    <label>Classement : <input type="text" name="player-rank" value="${playerRank}"/></label>
                    <button type="submit" name="edit-player" class="button">Éditer</button>
                </form>`;

            } else if (actionType === "delete") {

                const playerFname = button.getAttribute("player-fname") ?? "";
                const playerLname = button.getAttribute("player-lname") ?? "";
                
                document.getElementById('popupDiv').innerHTML =
                `<h3>Effacement d'un joueur : </h3>
                <form action="" method="POST" class="popup-form">
                    <input type="text" name="token" value="${currentToken}" hidden/>
                    <p>Voulez-vous retirer<strong> ${playerFname} ${playerLname} </strong>du tournoi ?</p>
                    <input type="text" name="public-id" value="${playerId}" hidden/>
                    <button type="submit" name="delete-player" class="button">Supprimer</button>
                </form>`;

            }


        /* ||||||||||||||||||||||||||||| */
        /* SECTION CONCERNANT LES COURTS */ 
        /* ||||||||||||||||||||||||||||| */
        } else if (dataType === 'court') {
            
            let courtId = button.getAttribute("data-id") ?? "";
            if (!courtId) { console.log("Impossible d'éditer ce court."); return; }

            if (actionType === "edit") {
                let courtName = button.getAttribute("court-name") ?? "";
                let courtPass = button.getAttribute("court-pass") ?? "";

                document.getElementById('popupDiv').innerHTML =
                `<h3>Édition d'un court : </h3>
                <form action="" method="POST" class="popup-form">
                    <input type="text" name="token" value="${currentToken}" hidden/>
                    <input type="text" name="public-id" value="${courtId}" hidden/>
                    <label>Nom : <input type="text" name="court-name" value="${courtName}" required/></label>
                    <label>Mot de passe : <input type="text" name="court-pass" value="${courtPass}" required/></label>
                    <button type="submit" name="edit-court" class="button">Éditer</button>
                </form>`;

            } else if (actionType === "delete") {
                
                let courtName = button.getAttribute("court-name") ?? "";

                document.getElementById('popupDiv').innerHTML =
                `<h3>Suppression d'un court : </h3>
                <form action="" method="POST" class="popup-form">
                    <input type="text" name="token" value="${currentToken}" hidden/>
                    <p>Voulez-vous supprimer le court <strong> ${courtName} </strong> du tournoi ?</p>
                    <input type="text" name="public-id" value="${courtId}" hidden/>
                    <button type="submit" name="delete-court" class="button">Supprimer</button>
                </form>`;
            }

        
        /* ||||||||||||||||||||||||||||||| */
        /* SECTION CONCERNANT LES ARBITRES */
        /* ||||||||||||||||||||||||||||||| */
        } else if (dataType === 'umpire') {
            
            let umpireId = button.getAttribute("data-id") ?? "";
            console.log("umpire id : " + umpireId);
            if (!umpireId) { console.log("Impossible d'éditer cet arbitre."); return; }

            if (actionType === "edit") {
                let umpireUName = button.getAttribute("umpire-uname") ?? "";
                let umpireFName = button.getAttribute("umpire-fname") ?? "";
                let umpireLName = button.getAttribute("umpire-lname") ?? "";

                document.getElementById('popupDiv').innerHTML =
                `<h3>Édition d'un arbitre : </h3>
                <form action="" method="POST" class="popup-form">
                    <input type="text" name="token" value="${currentToken}" hidden/>
                    <input type="text" name="public-id" value="${umpireId}" hidden/>
                    <label>Pseudo : <input type="text" name="umpire-uname" value="${umpireUName}" required/></label>
                    <label>Prénom : <input type="text" name="umpire-fname" value="${umpireFName}" required/></label>
                    <label>Nom : <input type="text" name="umpire-lname" value="${umpireLName}" required/></label>
                    <button type="submit" name="edit-umpire" class="button">Éditer</button>
                </form>`;

            } else if (actionType === "delete") {
                
                let umpireUName = button.getAttribute("umpire-uname") ?? "";
                let umpireFName = button.getAttribute("umpire-fname") ?? "";
                let umpireLName = button.getAttribute("umpire-lname") ?? "";

                document.getElementById('popupDiv').innerHTML =
                `<h3>Suppression d'un Arbitre : </h3>
                <form action="" method="POST" class="popup-form">
                    <p>Voulez-vous retirer l'arbitre <strong> ${umpireFName} ${umpireLName} (${umpireUName}) </strong> du tournoi ?</p>
                    <input type="text" name="token" value="${currentToken}" hidden/>
                    <input type="text" name="public-id" value="${umpireId}" hidden/>
                    <button type="submit" name="delete-umpire" class="button">Supprimer</button>
                </form>`;
            }
        }
        removeOverlay();
    });
});


// Fonction créant la div overlay (voile gris en arrière plan) et celle pop-up (contenant le texte)
function createOverlay() {
    // On crée l'overlay
    let overlayDiv = document.createElement('div');
    overlayDiv.id = 'overlayDiv';
    // On crée la fenêtre pop-up
    let popupDiv = document.createElement('div');
    popupDiv.id = 'popupDiv';
    overlayDiv.appendChild(popupDiv);
    document.body.appendChild(overlayDiv);
}

// Fonction permettant de fermer l'overlay en cliquant en dehors de la pop-up
function removeOverlay() {
    const overlayDiv = document.getElementById('overlayDiv');
    overlayDiv.addEventListener('click', (e) => {
        if (e.target === overlayDiv) {
            document.body.removeChild(overlayDiv);
        }
    });
}