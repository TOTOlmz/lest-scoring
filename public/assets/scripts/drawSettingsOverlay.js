import {createOverlay, removeOverlay} from "./components/overlay.js";
import {drawAdaptation} from "./components/drawAdaptation.js";
/*
SCRIPT PERMETTANT DE GÉRER L'AFFICHAGE DANS LES OVERLAYS D'ÉDITION ET VISUALISATION DES TABLEAUX DE LA PAGE edit?tournament=
*/

console.log(currentTournament);

const dsoActionButtons = document.querySelectorAll("button[draw-action]");

dsoActionButtons.forEach(dsoButton => {

    dsoButton.addEventListener("click", () => {

        if (dsoButton.hasAttribute("draw-key") === false) {
            console.log("Action impossible, aucune information sur le draw récupérée.");
            return;
        }

        createOverlay();
        // On réupère la position du draw dans le tableau et l'action demandée.
        const drawNb = dsoButton.getAttribute("draw-key");
        const actionAsked = dsoButton.getAttribute("draw-action");

        // On s'assure de pouvoir récupérer les infos du draw avant d'appeler les fonctions (que ce soit bien un tableau et qu'il ait du contenu)
        let drawToDisplay = currentTournament["draws"][drawNb];
        if (typeof drawToDisplay !== "object" || Object.keys(drawToDisplay).length === 0) {
            document.getElementById("popupDiv").innerHTML = "Impossible de récupérer les informations du tableau";
            console.log(drawToDisplay);

        // Si le draw a bien des données, on appelle la fonction adéquate 
        } else {
            if (actionAsked === "details") {
                displayDetails(drawToDisplay);
            } else if (actionAsked === "edit") {
                displayEdit(drawToDisplay);
            }
        }

        removeOverlay();
    });
    
});


// Fonction permettant d'afficher les détails d'un tableau
function displayDetails(draw) {


    let htmlCode = `
    <h3>${draw["title"]}</h3>
    <div class="draw-container">
    <div class="draw-area">`;

    const arrayDraw = Object.entries(draw.rounds);
    arrayDraw.forEach(round => {
        htmlCode += `<div class="draw-column" data-round="${round[0]}">`;

        const roundValues = Object.entries(round[1]);

        console.log("round values : ", roundValues);
        roundValues.forEach((match) => {
            htmlCode += `
                <div class="match-card">
                <p data-winner="${match[1].winner === "TA" ? "true" : "false"}">
                ${match[1].teamAP1_name !== null ? match[1].teamAP1_name : ""} ${match[1].teamAP2_name !== null ? " / " + match[1].teamAP2_name : ""}
                </p>
                <p>${match[1].final_score !== null ? match[1].final_score : ""}</p>
                <p data-winner="${match[1].winner === "TB" ? "true" : "false"}">
                ${match[1].teamBP1_name !== null ? match[1].teamBP1_name : ""} ${match[1].teamBP2_name !== null ? " / " + match[1].teamBP2_name : ""}
                </p>`;
            
            if (roundValues.length > 1) {
                htmlCode += `<span class="draw-h-start-branch"></span>`;
            }
            if (parseInt(match[0]) % 2 === 0) {
                htmlCode += `
                    <span class="draw-v-branch">
                        <span class="draw-h-end-branch"></span>
                    </span>`;
            }

            htmlCode += "</div>";
        });
        
        htmlCode += "</div>";
    });

    htmlCode += "</div></div>";
    document.getElementById("popupDiv").innerHTML = htmlCode;
    drawAdaptation();
    return;
}


// Fonction permettant d'afficher les options d'édition d'un tableau
function displayEdit(draw) {

    console.log(draw);
    return;
}
