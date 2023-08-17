<?php

session_start();
include 'includes/library.php';
$pdo = connectdb();

if(isset($_POST['editbook']))
{
    $Book_Title = $_POST['Book_Title'];
    $Genre = $_POST['Genre'];
    $Author = $_POST['Author'];
    $Publish_date = $_POST['Publish_date'];


    $query = "UPDATE books SET Book_Title=?, Genre=?, Author=?, Publish_date=? WHERE username=? LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$Book_Title,$Genre,$Author,$Publish_date,$_SESSION['username']]);

    header("location: success.php");
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $page_title = "Edit Book"; include 'includes/metadata.php'; ?>  ?>
</head>
<body>
    <?php include 'includes/header.php';?>
        <section>
            <h3>Edit Book</h3>
            <img src="img/create_acc.png" alt="create_acc" width="180" height="200"> 
            <?php
            if(isset($_GET['id']))
            {
                $book_id = $_GET['id'];

                $query = "SELECT * FROM books WHERE  id=:bookid";
                $stmt = $pdo->prepare($query);
                $data = [':bookid' => $book_id];
                $stmt->execute($data);

                $result = $stmt->fetch(PDO::FETCH_OBJ);
            }
            ?>
            <form name="editbook" method="POST" enctype="multipart/form-data" >                
                <p>Please fill in this form to edit book.</p>
                <br>
                <div>
                    <label for="Book_Title">Enter New Book Title:</label>
                    <input type="text" id="Book_Title" name="Book_Title" placeholder="Enter Book Title" required value="<?= $result->Book_Title; ?>"><br>
                    <span class="error <?=!isset($errors['Book_Title']) ?'hidden' : "";?>">Please enter a Book Title</span>
                </div>
                <br>
                <div>
                    <label for="Genre">Genre:</label>
                    <input type="text" id="Genre" name="Genre" placeholder="Enter Genre" value = "<?= $result->Genre ?>"><br>
                    <span class="error <?=!isset($errors['Genre']) ? 'hidden' : "";?>">Please enter a Genre</span>
                </div>
                <br>
                <div>
                    <label for="Author">Author:</label>
                    <input type="text" id="Author" name="Author" placeholder="Enter Author" value = "<?= $result->Author ?>" ><br>
                    <span class="error <?=!isset($errors['email']) ? 'hidden' : "";?>">Please enter an Author</span>
                </div>
                <br>
                <div>
                    <label for="Publish_date">Published Date:</label>
                    <input type="date" id="Publish_date" name="Publish_date" placeholder="Enter Publish Date" value="<?= $result->Publish_date ?>"><br>
                    <span class="error <?=!isset($errors['Publish_date']) ? 'hidden' : "";?>">Please enter a Publish date</span>            
                </div>
                <div>
                    <button id="editbook" name="editbook" type="submit">Edit Book</button>
                </div>
            </form>
        </section>
    <?php include 'includes/footer.php';?>
</body>
</html>