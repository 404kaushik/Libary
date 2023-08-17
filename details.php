<?php
include('includes/library.php');
$pdo=connectdb();

session_start();

if(!isset($_SESSION['username']))//checking if the user is logged in
{
 header("Location:login.php");//otherwise sending the user to login
 exit();
}

$x = 0;
$source1="https://loki.trentu.ca/~kaushiknagtumu/www_data/";
$query = "SELECT * FROM books";
$stmt = $pdo->prepare($query);

if(!$stmt->fetch()){
    $x=1;
} else{
    $x=2;
}
$user = $_SESSION['username'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php $page_title="Book Details"; include 'includes/metadata.php'?>
</head>
<body>
    <?php include 'includes/header.php';?>
        <main>
            <section>
                <h2>Book Details For <?php echo $_SESSION['username'] ?></h2>
                <br>
                <form enctype="multipart/form-data" action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   novalidate>
                <?php if($x==1):?>
                    <?php
                        //selecting every detail of the particular book
                        $query="select * from books WHERE username=? and id=?";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$_SESSION['username'], $_GET['id']]);
                    ?>
                    <div>
                        <?php foreach ($stmt as $row): ?>
                        
                        <?php $source2=$source1.$row['Book_Cover']?>
                        <img src="<?= $source2 ?>" alt="" height="260" width="190">
                        <!-- displaying the data for the particular book-->

                        <h3>Book Title: <?= $row['Book_Title'] ?></h3>
                        <br>
                        <h3>Book Genre: <?= $row['Genre'] ?></h3>
                        <br>
                        <h3>Book url: <?= $row['Book_url'] ?></h3>
                        <br>
                        <h3>Author: <?= $row['Author'] ?></h3>
                        <br>
                        <h3>ISBN: <?= $row['ISBN'] ?></h3>
                        <br>
                        <h3>Description: <?= $row['Description'] ?></h3>
                        <br>
                        <h3>Rating: <?php echo str_repeat("&#9733;", $row['Book_rating']); ?><?php echo str_repeat("&#9734;", 5 - $row['Book_rating']); ?></h3>
                        <br>
                        <h3>pages: <?= $row['pages'] ?></h3>
                        <br>
                        <h3>Publish_date: <?= $row['Publish_date'] ?></h3>
                        
                    
                        <?php endforeach; ?>
                        <?php elseif($x==2):?>
                        <div>
                            <!--otherwise adding a new book-->
                            <h3>Add a Book</h3>
                            <a href ="addbook.php"><button id="addbook" name="login"
                            class="button" type="button">Add A New Book</button></a>
                        </div>
                        <?php endif?>
                    </div>
                </form>
            </section>
        </main>
    <?php include "includes/footer.php"; ?>
</body>
</html>