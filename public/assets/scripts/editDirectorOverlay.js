import {createOverlay, removeOverlay} from "./components/overlay.js";

let edoDirectorsBtns = document.querySelectorAll("button.edit-director");

edoDirectorsBtns.forEach(button => {
    const dirId = button.getAttribute("director-id");
    const dirName = button.getAttribute("director-name");
    const dirEmail = button.getAttribute("director-email");
    const dirRole = button.getAttribute("director-role");
    let dirPerm = button.getAttribute("director-permanent");
    let dirSusp = button.getAttribute("director-suspended");

    dirPerm == "1" ? dirPerm = "checked" : "";
    dirSusp == "1" ? dirSusp = "checked" : "";

    button.addEventListener("click", () => {

        createOverlay();

        document.getElementById('popupDiv').innerHTML =
        `<h3>Edition d'un directeur : </h3>
        <form action="" method="POST" class="popup-form">
            <input type="text" name="token" value="${currentToken}" hidden/>
            <input type="text" name="public-id" value="${dirId}" hidden/>
            <label>Nom : <input type="text" name="name" value="${dirName}" required/></label>
            <label>Email : <input type="text" name="email" value="${dirEmail}" required/></label>
            <label>Role : 
                <select name="role" required>
                    <option value="${dirRole}" hidden selected>${dirRole}</option>
                    <option value="DIRECTOR">DIRECTOR</option>
                    <option value="UMPIRE">UMPIRE</option>
                    <option value="USER">USER</option>
                </select>
            </label>
            <div class="checkboxes">
                <label class="checkbox-label">Permanent : <input type="checkbox" name="permanent" ${dirPerm}/></label>
                <label class="checkbox-label">Suspendu : <input type="checkbox" name="suspended" ${dirSusp}/></label>
            </div>
            <button type="submit" name="edit-director" class="button">Éditer</button>
        </form>`;

        
        removeOverlay();

    });


});
