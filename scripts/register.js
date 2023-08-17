"use strict";
window.addEventListener("DOMContentLoaded", () => {

    /* CHECKING PASSWORD STRENGTH */

    
    // Get the element with the id "password", "message" and "strength" and store it in the variables
    var pass = document.getElementById("password");
    var msg = document.getElementById("message");
    var str = document.getElementById("strength");

    pass.addEventListener('input', () => {
        if(pass.value.length > 0){        
            msg.style.display = "block";
        } 
        else{
            msg.style.display = "none";
        }
        if(pass.value.length < 4){
            str.innerHTML = "WEAK";
            pass.style.borderColor = "red";
            msg.style.color = "red";
        }
        else if(pass.value.length >= 4 && pass.value.length <8){
            str.innerHTML = "MEDIUM";
            pass.style.borderColor = "#FFC300";
            msg.style.color = "#FFC300";

        }
        else if(pass.value.length >= 8){
            str.innerHTML = "STRONG";
            pass.style.borderColor = "green";
            msg.style.color = "green";
        }
    })


    // Checking if the uasername exists in the database or not
    const username = document.getElementById("username");
    const usererror = username.nextElementSibling;

    username.addEventListener("change", () => {
        
        const qtySpan = document.getElementById('qty');
        if (qtySpan) {
        qtySpan.remove();
        }
    
    // Make an AJAX request to check_username.php using the username as a parameter to check if the username exists in the databse 
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `check_username.php?username=${username.value}`);
    xhr.addEventListener("load", () => {
        if (xhr.status === 200) {
            if (xhr.responseText === "true") {
                username.insertAdjacentHTML("afterend", "<span id='qty' style='font-weight: bold; color: red;'>Username already exists, Please enter a different username</span>");
            } else if (xhr.responseText === "false") {
            } else {
             username.insertAdjacentHTML("afterend", "<span id='qty' style='font-weight: bold; color: green;'>Username is Available</span>");
            }
            } else {
                username.insertAdjacentHTML("afterend", "<span id='qty'>Please enter a Username</span>");
            }
        });
        xhr.send();
    }); 
 

    // Get references to the form and its input fields
    const form = document.getElementById('requestform');
    const username2 = document.getElementById('username');
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const password2 = document.getElementById('password2');
    
    // Get references to the submit and reset buttons
    const submitBtn = document.getElementById('submit');
    const resetBtn = document.getElementById('reset');

    // Get references to all the error messages
    const errorMessages = document.getElementsByClassName('error');

    // Reset all error messages to their initial state (hidden)
    function resetErrors() {
        for (let i = 0; i < errorMessages.length; i++) {
            errorMessages[i].style.display = 'none';
        }
    }

    // Show the error message for a specific input element
    function showError(element) {
        element.parentNode.querySelector('.error').style.display = 'block';
    }

    // Listen for the form submit event
    form.addEventListener('submit', function (e) {
        let hasError = false;
        resetErrors();

        // Validating the username for errors
        if (username2.value.length < 3 || !/^[a-zA-Z0-9]+$/.test(username.value)) {
            showError(username);
            hasError = true;
        }

        // Validating the name for errors
        if (name.value.trim() === '' || !/^[a-zA-Z\s]+$/.test(name.value.trim())) {
            showError(name);
            hasError = true;
        }

        // Validating the email for errors 
        if (email.value.trim() === '' || !/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(email.value.trim())) {
            showError(email);
            hasError = true;
        }

        // Validating the password
        if (password.value.length < 8) {
            showError(password);
            hasError = true;
        }

        // Validating the check password to check if they are the same
        if (password.value !== password2.value) {
            showError(password2);
            hasError = true;
        }

        // If any errors occurred, prevent the form from submitting
        if (hasError) {
            e.preventDefault();
        }
    });

    // Listen for the reset button click event
    resetBtn.addEventListener('click', function () {
        resetErrors();
    });

    
});

