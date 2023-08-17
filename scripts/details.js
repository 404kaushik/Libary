"use strict";

/* // Event listener for book image click
document.querySelectorAll('.image').forEach((image) => {
image.addEventListener('click', (event) => {
    const bookId = event.target.dataset.bookId;
    displayBookDetails(bookId);
});
});

// Display book details in the modal
async function displayBookDetails(bookId) {
const bookDetails = await fetchBookDetailsById(bookId);
const bookDetailsElement = document.getElementById('details_modal');
bookDetailsElement.innerHTML = `
    <h3>${bookDetails.title}</h3>
    <p>Author: ${bookDetails.author}</p>
    <p>Published: ${bookDetails.published}</p>
    <p>Description: ${bookDetails.description}</p>
`;
showModal();
}

// Fetch book details by ID
// Replace this with your API call to fetch book details
async function fetchBookDetailsById(bookId) {
return {
    id: bookId,
    title: 'Example Book Title',
    author: 'John Doe',
    published: '2022-01-01',
    description: 'This is an example book description.',
};
}

// Show and hide the modal
const modal = document.getElementById('myModal');
const closeModal = document.getElementsByClassName('close')[0];

function showModal() {
modal.style.display = 'block';
}

function hideModal() {
modal.style.display = 'none';
}

closeModal.onclick = hideModal;
window.onclick = function (event) {
if (event.target == modal) {
    hideModal();
}
}; 

 */

let popup = document.getElementById("popup");

function openPopup(){
    popup.classList.add("open-popup");
}


function closePopup(){
    popup.classList.remove("open-popup");
}


// Make an AJAX request to checkqty.php using the primary toy choice as a parameter
const xhr = new XMLHttpRequest();
xhr.open("GET", `bookdetails_popup.php?Book_Cover=${bookInfo.value}`);
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

    xhr.send();
});
