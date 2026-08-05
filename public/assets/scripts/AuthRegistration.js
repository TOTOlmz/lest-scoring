

/*
SCRIPT PERMETTANT DE GÉRER L'AFFICHAGE SUR LA PAGE DE CONNEXION
*/

// Validation du mot de passe côté client
const form = document.querySelector('form');
form.querySelector('#submit-button').style.display = 'none';
const password = document.getElementById('password');
const passwordConfirm = document.getElementById('confirm-password');


document.querySelector('#passconf-label').style.display = 'none'; 

password.addEventListener('input', checkForm);
passwordConfirm.addEventListener('input', checkForm);

function checkForm() {
    if (validatePassword()) {
        document.querySelector('.password-requirements .pass-length').style.display = 'none';
        document.querySelector('.password-requirements .pass-upper').style.display = 'none';
        document.querySelector('.password-requirements .pass-lower').style.display = 'none';
        document.querySelector('.password-requirements .pass-number').style.display = 'none';
        document.querySelector('.password-requirements p').style.display = 'none';
        document.querySelectorAll('.password-requirements .pass1').forEach(item => item.style.display = 'none');
        let confirmP = password.value === passwordConfirm.value;
        form.querySelector('#submit-button').style.display = confirmP ? 'block' : 'none';
        document.querySelector('#passconf-label').style.display = 'block';
        document.querySelector('#passconf-label').style.backgroundColor = confirmP ? '#196e44' : '#a10000';    
        document.querySelector('#passconf-label').textContent = confirmP ? 'Les mots de passe correspondent.' : 'Les mots de passe ne correspondent pas.'; 
        document.querySelector('#passconf-label').style.display = confirmP ? 'none' : 'block'; 

    } 
}

function validatePassword(){
    document.querySelector('.password-requirements').style.display = 'grid';
    let passValue = password.value;
    let validLength = passValue.length >= 8;
    let validUpper = passValue.toLowerCase() !== passValue;
    let validLower = passValue.toUpperCase() !== passValue;
    let validNumber = passValue.search(/[0-9]/) !== -1;
    document.querySelector('.pass-length').style.display = validLength ? 'none' : 'block';
    document.querySelector('.pass-upper').style.display = validUpper ? 'none' : 'block';
    document.querySelector('.pass-lower').style.display = validLower ? 'none' : 'block';
    document.querySelector('.pass-number').style.display = validNumber ? 'none' : 'block';
    return validLength && validUpper && validLower && validNumber;
};

// Script simple pour ajuster la largueur d'un bouton en fonction des inputs
let button = document.querySelector('.connection-form button[type="submit"]');
let input = document.querySelector('.connection-form input');
button.style.width = input.offsetWidth - 10 + 'px';
button.style.textAlign = 'center';
