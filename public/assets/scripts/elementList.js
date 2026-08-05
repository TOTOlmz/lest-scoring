/*
SCRIPT GÉRANT LE SYSTÈME D'ONGLETS S'OUVRANTS ET SE FERMANTS
*/

let elList = document.querySelectorAll(".element-Area");

elList.forEach( list => {
    const button = list.querySelector(".element-details-button");
    let courtsArea = list.querySelector(".element-details");
    const titleArea = list.querySelector(".element-title");
    button.addEventListener("click", () => {
        const isOpen = button.getAttribute("isOpen") === "true";
        button.setAttribute("transition", "true");
        courtsArea.setAttribute("isOpen", !isOpen);
        setTimeout(() => {
            button.setAttribute("transition", "false");
            button.setAttribute("isOpen", !isOpen);
        }, 250);

        if (!isOpen) {
            courtsArea.style.maxHeight = (parseInt(courtsArea.scrollHeight + 30)) + "px";
        } else {
            courtsArea.style.maxHeight = "0px";
        }
    });
});
