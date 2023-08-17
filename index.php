<?php 
include 'includes/library.php';
$pdo = connectdb();

session_start();
if(!isset($_SESSION["username"])) {
    header("location:login.php");
}

if(!isset($_SESSION['username']))//checking if the user is logged in
{
 header("Location:login.php");//otherwise sending the user to login
 exit();
}

$image = 0;
$source1 = "https://loki.trentu.ca/~kaushiknagtumu/www_data/";//letting the php know the path for the book cover
$query = "select * from books";//selecting all the data 
$stmt = $pdo->prepare($query);//preparing the query


//if there is a record, then x=1 otherwise x=2
if(!$stmt->fetch()) //fetching if there is any record otherwise 
{
    $image=1;
    
}
else
{
    $image=2;
}
$user=$_SESSION['username'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php $page_title="Main Page"; include'includes/metadata.php'; ?>
  <script defer src="scripts/details.js"></script>
</head>
<body>
    <?php include 'includes/header.php';?>
        <main>
            <section>
                    <ul>
                        <li>
                            <?php
                                //selecting every detail of the particular book
                                $query="select * from books";
                                $stmt = $pdo->prepare($query);
                                $stmt->execute();
                            ?>
                            <?php foreach ($stmt as $row); ?>   
                            <a href="editaccount.php?id=<?= $row['id'];?> " class="edit-user">
                                <script src="https://cdn.lordicon.com/ritcuqlt.js"></script>
                                <lord-icon
                                    src="https://cdn.lordicon.com/bhfjfgqz.json"
                                    trigger="hover"
                                    style="width:80px;height:100px;position:absolute;top: 0;right:0;margin-right:80px;">
                                </lord-icon>
                            </a>
                            <a href="login.php" class="logout">
                                <script src="https://cdn.lordicon.com/ritcuqlt.js"></script>
                                <lord-icon
                                    src="https://cdn.lordicon.com/bmnlikjh.json"
                                    trigger="hover"
                                    style="width:80px;height:100px;position:absolute;top:0;right:0;"
                                    >
                                </lord-icon> 
                            </a>
                        </li>
                    </ul>
                    <div>
                        <img src="img/home_acc.png" alt="home_acc" width="375px" height="500px">
                    </div>
                    <br>
                    <div>
                        <h1> Welcome <?php echo $_SESSION["username"];?></h1>
                    </div>
                    <br>
                    <div>
                        <nav>
                            <a href="addbook.php" class="add-book">Add Book</a>
                        </nav>
                    </div>
                    <br>
                    <div class="display-options">
                        <h1>Display Options</h1>
                        <div class="sort-by">
                            <label for="sort-by">Sort by:</label>
                            <select id="sort-by" name="sort-by">
                            <option value="dateadded">Date Added</option> 
                            <option value="2023/01/26">2023/01/26</option>
                            <option value="2023/01/27">2023/01/27</option>
                            <option value="2023/01/28">2023/01/28</option>
                            </select>
                        </div>
                        <br>
                        <div class="itemsperpage">
                            <label for="itemsperpage">Items Per Page: </label>
                            <select name="itemsperpage" id="itemsperpage">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                            <input type="submit" value="Submit">
                        </div>
                    </div>
                    <br>
                </header>
                <form id="adding picture" enctype="multipart/form-data" action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post"   novalidate>
                    <?php if($image==1):?>
                    <?php
                    //selecting every detail of the particular book
                    $query="select * from books WHERE username=?";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$_SESSION['username']]);
                    ?>
                    <div>
                        <!-- The Modal -->
                        <div class = "popup" id="popup">
                            <img src = "img/details_book.png">
                            <h2> Book Details </h2>
                            <button type = "button"  onclick="closePopup()">OK</button>
                        </div>
                        <?php foreach ($stmt as $row): ?>
                        <?php $source2 = $source1.$row['Book_Cover']?>
                        <img src="<?= $source2 ?>" alt="" height="260"
                        width="190" name="image" class="image" onclick="openPopup()">
                        <!-- displaying the data for the particular book-->
                        <br>
                        <h3>Book ID: <?= $row['id'] ?> </h3> 
                        <br>
                        <h3>Book Title: <?= $row['Book_Title'] ?></h3>
                        <br>
                        <h3>Book Genre: <?= $row['Genre'] ?></h3>
                        <br>
                        <h3>Description: <?= $row['Description'] ?></h3>
                        <br>
                        <h3>Author: <?= $row['Author'] ?></h3>
                        <br>
                        <nav>
                            <a href="editbook.php?id=<?= $row['id']; ?>">Edit your book details</a>
                            <a href="details.php?id=<?= $row['id'];?>">Book Details</a>
                            <a href="deletebook.php?id=<?= $row['id'];?>">Delete Book</a>
                        </nav>
                        <?php endforeach; ?>
                        <?php elseif($image==2):?>
                        <div>
                            <!--otherwise adding a new book-->
                            <h3>Add a Book</h3>
                            <a href ="addbook.php"><button id="addbook" name="login" class="button" type="button">Add A New Book</button></a>
                        </div>
                        <?php endif?>
                    </div>
                </form>
            </section>
        </main>
    <?php include 'includes/footer.php';?>
</body>
</html>