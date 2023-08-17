"use strict";

document.querySelector("#delbook").addEventListener("click", function(event) {
    if (!confirm("Are you sure you want to delete the book?")) {
        event.preventDefault();
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "deletebook.php?Book_title=" + document.querySelector("#Book_title").value);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            alert("Book has been deleted");
        }
    };
    xhr.send();
});