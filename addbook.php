<?php

// ensure the user has logged in

session_start();//starting my session
//checking if the user is logged in or not
if (!isset($_SESSION['username'])) {
   header("Location: login.php");
   exit();
}
//including the library function
require "includes/library.php";

// connect to database
$pdo = connectdb();

$errors = array(); //declare empty array to add errors too

//get values from post or set to NULL if doesn't exist
$Book_Cover = $_POST['Book_Cover'] ?? null;
$Genre = $_POST['Genre'] ?? null;
$Book_Title = $_POST['Book_Title'] ?? null;
$Description = $_POST['Description'] ?? null;
$Author = $_POST['Author'] ?? null;
$Book_url=$_POST['cover_image_url']?? null;
$Publish_date=$_POST['pub_date']?? null;
$pages = $_POST['pages'] ?? null;
$ISBN = $_POST['ISBN'] ?? null;
$Book_rating = $_POST['Book_rating'] ?? null;

var_dump($_POST);
var_dump($_FILES);
var_dump($errors);


if (isset($_POST['submit'])) { //only do this code if the form has been submitted
    
/*     if (isset($_FILES['filename'])) {
        $file = $_FILES['filename'];
    
        if ($file['error'] == UPLOAD_ERR_OK) {
            // File upload is successful, validate file size
            if ($file['size'] <= 2400000) {
                // File size is within the limit, continue with code
            } else {
                $errors['Book_Cover'] = "The file size is too large.";
            }
        } else {
            // File upload failed, set error message
            $errors['Book_Cover'] = "There was an error uploading the file.";
        }
    } else {
        // No file was uploaded, set error message
        $errors['Book_Cover'] = "No file was uploaded.";
    }

    //checking the length of book title
    if (empty($Book_Title)) {
        $errors['Book_Title'] = "Please enter a book title.";
    }

    echo "Hello".$Book_url;
    //checking length of my url
    if (empty($Book_url)){
        $errors['Book_url'] = "Please enter a book url";
    }

    //checking length og genre
    if (empty($Genre)) {
        $errors['Genre'] = "Please enter a Genre";
    }
    //checking length of author
    if (empty($Author)) {
        $errors['Author'] = "Please enter an author name";
    }

    //using strip tag so that if there are any tags it should just uplaod the body content
    $Description = filter_var($Description, FILTER_SANITIZE_STRING);

    //validate user has entered ISBN
    if (empty($ISBN)) {
        $errors['ISBN'] = "Please enter an ISBN";
    }

    //validate user has entered a PAGES
    if (empty($pages)) {
        $errors['pages'] = "Please enter pages";
    }

    //VALIDATING THAT USER ENTERED THE DATE 
    if (empty($Publish_date)) {
        $errors['Publish_date'] = "Please enter a publised date of the book";
    }

    var_dump($errors); */
    //when no errors are there
    if (count($errors) === 0) {
        $query = "INSERT INTO books VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?)"; //SQL QUERY TO INSERT INTO TABLE IN MY DATABASE
        $stmt = $pdo->prepare($query);//preparing query to insert data
        $stmt->execute([$_SESSION['username'], $Book_Cover, $Book_Title, $Book_url, $Genre, $Author, $Description,(int)$ISBN, (int)$pages, $Publish_date, $Book_rating]);//PASSING THE FIELD VALUES

        $book_id=$pdo->lastInsertId();
        $filekey = 'filename';//setting file key
        $path = WEBROOT . "www_data/";//the path where the files must be uploaded
        $filename = "cover";//setting image name as cover
        $uniqueID=$book_id;//Getting a unique ID for the Book Id
        
        //Renaming the file provided by the user
        if(isset($_FILES['filename']) && $_FILES['filename']['error'] === UPLOAD_ERR_OK) {
            $filename = $_FILES['filename']['name'];
            $file_tmp = $_FILES['filename']['tmp_name'];
            $file_size = $_FILES['filename']['size'];
        
            if($file_size > 2400000) {
                $errors['file'] = "File is too large";
            } else {
                $newname = createFilename('filename',$filekey,$filename, $uniqueID);//passing new name in the function includes/library.php
                if(move_uploaded_file($file_tmp, $path.$newname)) {
                    $query="UPDATE books SET Book_Cover=? where id=?";//updating table to make the set book cover name
                    $stmt=$pdo->prepare($query);//preparing the query
                    $stmt->execute([$newname,$book_id]);//executing the attributes into sql
                } else {
                    $errors['file'] = "Failed to move your file"; 
                }
            }
        } else {
            $errors['file'] = "File upload failed";
        }
        header("Location: success.php");//redirecting the user to the sucess page to let him know that thhe information has been recoreded
        exit();//Exiting
    }

}
?>



<!DOCTYPE html>
<html lang="en">
<?php $page_title = "Add Book"; include "includes/metadata.php" ?>
<script defer src="scripts/addbook.js"></script>
<body>
    <?php include 'includes/header.php';?>
        <main>
            <section>
                <div>
                    <h2>Add Book</h2>
                </div>
                <br>
                <div>
                    <img src="img/addbook.png" alt="addbook" width="280" height="300">
                </div>
                <br>
                <form id="requestform" method="post" enctype="multipart/form-data" novalidate>
                    <div>
                        <label for="Book_Title">Book Title:</label>
                        <input type="text" id="Book_Title" name="Book_Title" placeholder="Title">
                        <span class="error" style="display: none;">Please enter a book title.</span> <!-- Outputting error message --> 
                    </div>
                    <br>
                    <div>
                        <label for="Author">Author:</label>
                        <input type="text" id="Author" name="Author" placeholder="Author">
                        <span class="error" style="display: none;">Please enter a book author.</span> <!-- Outputting error message --> 
                    </div>
                    <br>
                    <div>
                        <label for="Description">Description:</label>
                        <textarea id="description" name="Description" rows="4" cols="50" placeholder="Enter your text here..." maxlength="2500"></textarea>
                        <span class="error" style="display: none;">Please enter a book description.</span> <!-- Outputting error message --> 
                        <div id="plot-summary-counter">2500 charecters left</div>
                    </div>
                    <br>
                    <div>
                        <label for="pub_date">Publication Date:</label>
                        <input type="text" id="pub_date" name="pub_date" required>
                        <span class="error" style="display: none;">Please enter the book's publication date.</span> <!-- Outputting error message --> 
                    </div>
                    <br>
                    <div>
                        <label for="Book_rating">Rate this book:</label>
                        <div class="star-rating" >
                            <input type="radio" id="star1" name="Book_rating" value="5">
                            <label for="star1"></label>
                            <input type="radio" id="star2" name="Book_rating" value="4">
                            <label for="star2"></label>
                            <input type="radio" id="star3" name="Book_rating" value="3">
                            <label for="star3"></label>
                            <input type="radio" id="star4" name="Book_rating" value="2">
                            <label for="star4"></label>
                            <input type="radio" id="star5" name="Book_rating" value="1">
                            <label for="star5"></label>
                        </div>
                        <span class="error" style="display: none;">Please enter the book rating.</span> <!-- Outputting error message --> 
                    </div>
                    <br>
                    <div>
                        <label for="Genre">Genre:</label>
                        <select id="Genre" name="Genre">
                            <option value="chooseone">Choose One</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Non-Fiction">Non-Fiction</option>            
                            <option value="Mystery">Mystery</option>
                            <option value="Fantasy">Fantasy</option>
                            <option value="Science Fiction">Science Fiction</option>
                            <option value="Thriller">Thriller</option>
                            <option value="Romance">Romance</option>
                        </select>
                        <span class="error" style="display: none;">Please enter the book's genre.</span> <!-- Outputting error message --> 
                    </div>
                    <br>
                    <div>
                        <label for="book_format">Book Format:</label>
                        <select id="book_format" name="book_format">
                            <option value="chooseone">Choose One</option>
                            <option value="Mobi">Mobi</option>
                            <option value="Hardcover">Hardcover</option>
                            <option value="Paperback">Paperback</option>
                            <option value="PDF">PDF</option>
                            <option value="EPub">EPub</option>
                        </select>
                        <span class="error" style="display: none;">Please enter a book format.</span> <!-- Outputting error message --> 
                    </div>
                    <br>
                    <div>
                        <label for="ISBN">ISBN:</label>
                        <input type="text" name="ISBN" id="isbn" placeholder="ISBN" pattern=".{10,13}" title="Must be atleast 10 to 13 charecters">
                        <span class="error" style="display: none;">Please enter a book ISBN.</span> <!-- Outputting error message --> 
                    </div>
                    <br>
                    <div>
                        <label for="pages">Pages:</label>
                        <input type="text" name="pages">
                        <span class="error" style="display: none;">Please enter the book pages.</span> <!-- Outputting error message --> 
                    </div>
                    <br>
                    <div>
                        <label for="filename">Browse for Book Cover:</label>
                        <input type="file" id="filename" name="filename"> 
                        <span class="error" style="display: none;">Please enter a book cover.</span> <!-- Outputting error message --> 
                    </div>
                    <br>
                    <div>
                        <label for="Book_url">Cover Image (URL):</label>
                        <input type="url" id="Book_url" name="cover_image_url" required>
                        <span class="error" style="display: none;">Please enter a book url.</span> <!-- Outputting error message --> 
                    </div>
                    <br>
                    <div><button id="submit" name="submit" id="submit">Add Book</button></div>                  
                </form>
            </section>
        </main>  
    <?php include 'includes/footer.php';?>
</body>
</html>



