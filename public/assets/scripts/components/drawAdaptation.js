/* ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    Script ajustant les barres des draws 
    pour faire de belles arborescences
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| */

function drawAdaptation() {
    
    // On récupère tous les draws du tableau et pour chacun
    document.querySelectorAll(".draw-area").forEach(draw => {
        // On récupère toutes les colonnes "rounds" et leur nombre
        let rounds = draw.querySelectorAll(".draw-column");

        
        // Et pour chaque round, on récupère son attribut "data-round"
        rounds.forEach(round => {
            const cards = round.querySelectorAll(".match-card");

            cards.forEach((card, i) => {

                if (i % 2 === 0) return; // La branche verticale n'existe que sur les cartes paires

                
                const vBranch = card.querySelector(".draw-v-branch");
                const nextCard = cards[i - 1];

                if (!vBranch || !nextCard) return; // Si on a raté un élément, tant pis, on arrête

                const centerA = card.offsetTop + card.offsetHeight / 2;
                const centerB = nextCard.offsetTop + nextCard.offsetHeight / 2;

                vBranch.style.height = (Math.abs(centerB - centerA) - 2) + "px";

            });
        });
    });
}



export {drawAdaptation};