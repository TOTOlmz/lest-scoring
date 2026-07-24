/* ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    Script ajustant les barres des draws 
    pour faire de belles arborescences
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */

// On récupère tous les draws du tableau
let daDraws = document.querySelectorAll(".draw-area");

// Pour chaque draw, 
daDraws.forEach(draw => {
    // On récupère toutes les colonnes "rounds" et leur nombre
    let rounds = draw.querySelectorAll(".draw-column");
    
    // Et pour chaque round, on récupère son attribut "data-round"
    rounds.forEach(round => {
        const roundPos = round.getAttribute("data-round") - 1;

        let vBranchs = round.querySelectorAll(".match-card .draw-v-branch");

        vBranchs.forEach( vBranch => {
            let vBranchHeight =vBranch.clientHeight;
            vBranch.style.height = vBranchHeight * parseInt(rounds[roundPos].getAttribute("data-round")) + "px";
        });
    });
});