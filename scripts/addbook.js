"use strict";

window.addEventListener("DOMContentLoaded", () => {

  // Description plot checker
  let plotSummaryField = document.getElementById('description');
  let plotSummaryCounter = document.getElementById('plot-summary-counter');

  plotSummaryField.addEventListener('input', function() {
    let remainingChars = plotSummaryField.maxLength - plotSummaryField.value.length;
    plotSummaryCounter.textContent = remainingChars + ' characters left';
  });


  // JavaScript for the book rating
  const stars = document.getElementById('Book_rating');

  
  stars.forEach(star => {
    star.addEventListener('click', () => {
      
      const rating = parseInt(star.getAttribute('data-rating'));

      
      for (let i = 0; i < rating; i++) {
        stars[i].classList.add('active');
      }

      
      for (let i = rating; i < stars.length; i++) {
        stars[i].classList.remove('active');
      }
    });
  });



  const form = document.getElementById("requestform");
  const title = document.getElementById("Book_Title");
  const author = document.getElementById("Author");
  const description = document.getElementById("description");
  const pubDate = document.getElementById("pub_date");
  const isbn = document.getElementById("isbn");
  const pages = document.getElementsByName("pages")[0];
  const genre = document.getElementById("Genre");
  const bookFormat = document.getElementById("book_format");



  // Add error message containers for each field
  const titleError = createErrorElement("title-error");
  const authorError = createErrorElement("author-error");
  const descriptionError = createErrorElement("description-error");
  const isbnError = createErrorElement("isbn-error");
  const pagesError = createErrorElement("pages-error");
  const pubDateError = createErrorElement("pub-date-error");
  const genreError = createErrorElement("genre-error");
  const bookFormatError = createErrorElement("book-format-error");

  


  title.parentNode.appendChild(titleError);
  author.parentNode.appendChild(authorError);
  description.parentNode.appendChild(descriptionError);
  isbn.parentNode.appendChild(isbnError);
  pages.parentNode.appendChild(pagesError);
  pubDate.parentNode.appendChild(pubDateError);
  genre.parentNode.appendChild(genreError);
  bookFormat.parentNode.appendChild(bookFormatError);



  function createErrorElement(id) {
    const errorElement = document.createElement("div");
    errorElement.id = id;
    errorElement.className = "error";
    errorElement.style.color = "red";
    return errorElement;
  }

  function showError(element, message) {
    element.textContent = message;
  }

  function hideError(element) {
    element.textContent = "";
  }

  function isValidDate(d) {
    if (Object.prototype.toString.call(d) === "[object Date]") {
      return !isNaN(d.getTime());
    }
    return false;
  }

  form.addEventListener("submit", function(event) {
    let hasError = false;
    
    // validating book title
    if (!title.value.trim()) {
      showError(titleError, "Please enter a book title.");
      hasError = true;
    } else {
      hideError(titleError);
    }

    // validating book author
    if (!author.value.trim()) {
      showError(authorError, "Please enter an author.");
      hasError = true;
    } else {
      hideError(authorError);
    }

    // validating book description
    if (!description.value.trim()) {
      showError(descriptionError, "Please enter a description.");
      hasError = true;
    } else {
      hideError(descriptionError);
    }


    // validating book publish date
    const dateRegEx = /^\d{2}-\d{2}-\d{4}$/;
    if (!pubDate.value.match(dateRegEx)) {
        showError(pubDateError, "Please enter a valid date format (MM-DD-YYYY).");
        hasError = true;
    } else {
      const [month, day, year] = pubDate.value.split("-");
      const parsedDate = new Date(`${year}-${month}-${day}`);
      if (!isValidDate(parsedDate)) {
        showError(pubDateError, "Please enter a valid date.");
        hasError = true;
      } else {
        hideError(pubDateError);
      }
    }

    // validating book genre
    if (genre.value == 'chooseone') {
      showError(genreError, "Please select a genre.");
      hasError = true;
    } else {
      hideError(genreError);
    }

    // validating book format
    if (bookFormat.value == 'chooseone') {
      showError(bookFormatError, "Please select a book format.");
      hasError = true;
    } else {
      hideError(bookFormatError);
    }

    // validating book ISBN
    if (!isbn.value) {
      showError(isbnError, "Please enter an ISBN (e.g., 978-3-16-148410-0).");
      hasError = true;
    } else if (isbn.value.length < 10 || isbn.value.length > 13) {
      showError(isbnError, "ISBN must be between 10 and 13 characters (e.g., 978-3-16-148410-0).");
      hasError = true;
    } else {
      hideError(isbnError);
    }

    // validating book pages
    if (!pages.value.trim() || isNaN(pages.value) || parseInt(pages.value) < 1) {
      showError(pagesError, "Please enter a valid number of pages.");
      hasError = true;
    } else {
      hideError(pagesError);
    }

    if (!hasError) {
      console.log("Form submitted successfully");
    } 

    if(hasError) event.preventDefault();

  });
});
    
