function cardEdition (button) {
    if (!button.hasAttribute("round") || !button.hasAttribute("match") || !button.hasAttribute("match-index")) {
        console.log("Inpossible de récupérer les infos concernant le match");
        return;
    }

    const matchPos = button.getAttribute("match");
    const matchIndex = button.getAttribute("match-index");
    const roundPos = button.getAttribute("round");

    console.log(matchPos, matchIndex, roundPos);
}

export {cardEdition};