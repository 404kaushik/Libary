<?php
//code for deleting a particular book for his or her account
require_once 'includes/library.php';
session_start();//starting up my session for this page
if (!isset($_SESSION['username'])) {
    header("Location:login.php");//other wise redirecting towards the login.php
    exit(); }
    $pdo = connectdb();//connecting to the databse
    $Book_title = $_POST['Book_title'] ?? null;//setting up the book title
    if (isset($_POST['delbook'])) {
        if (strlen($Book_title) !== 0) {
            $stmt = $pdo->prepare("DELETE FROM books WHERE username = ? and Book_title = ?");// deleting that particular book
            $stmt->execute([$_SESSION['username'], $Book_title]);
        //redirecting after insertion
        header("Location:success_delbook.php");//redirecting towards the del book
        exit();
    }
    else {
        printf("Error occured");// otherwise printing an error
    }
}
?>

<!-- starting up the html-->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $page_title = "Delete Book"; include 'includes/metadata.php' ?>
    <script defer src = "scripts/deletebook.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>
        <main>
            <section>
                <h2> Delete Book </h2>
                <form id="deletebook" enctype="multipart/form-data" action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" novalidate>
                <div>
                    <label for="Book_title"> Book Title </label>
                    <input type="text" id="Book_title" name = "Book_title" placeholder="Book Title">
                    <span class="error <?= !isset($errors['Book_title'])?>">Please enter book title</span>
                </div>
                <div class="Display">
                    <button id="delbook" name="delbook" type="submit"> Delete Book</button>
                </div>
                </form>
            </section>
        </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
