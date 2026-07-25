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

export {createOverlay, removeOverlay};