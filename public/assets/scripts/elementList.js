const tournaments = document.querySelectorAll(".element-Area");

tournaments.forEach( tournament => {
    const button = tournament.querySelector(".element-details-button");
    const courtsArea = tournament.querySelector(".element-details");
    button.addEventListener("click", () => {
        const isOpen = button.getAttribute("isOpen") === "true";
        button.setAttribute("isOpen", !isOpen);
        courtsArea.setAttribute("isOpen", !isOpen);
    });
});
