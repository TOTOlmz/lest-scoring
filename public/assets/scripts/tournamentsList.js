const tournaments = document.querySelectorAll(".tournament-Area");

tournaments.forEach( tournament => {
    const button = tournament.querySelector(".tournament-details-button");
    const courtsArea = tournament.querySelector(".tournament-details");
    button.addEventListener("click", () => {
        const isOpen = button.getAttribute("isOpen") === "true";
        button.setAttribute("isOpen", !isOpen);
        courtsArea.setAttribute("isOpen", !isOpen);
    });
});
