<?php
// Include database connection file
include 'includes/library.php';
$pdo = connectdb();

// Check if user has logged in
session_start();
if(!isset($_SESSION["username"])) {
    header("location:login.php");
    exit();
}


$www_data = "https://loki.trentu.ca/~kaushiknagtumu/www_data/";
// Check if search term is submitted
if (isset($_GET['search-term'])) {
    // Sanitize search term
    $searchTerm = htmlspecialchars($_GET['search-term']);

    // Prepare and execute query to search for books by title
    $query = $pdo->prepare("SELECT * FROM books WHERE Book_Title = ?");
    $query->execute([$searchTerm]);
}
?>


<!DOCTYPE html>
<html lang="en">
    <?php $page_title="Book Search"; include 'includes/metadata.php'; ?>
    <body>  
        <?php include 'includes/header.php';?>
          <main>
            <section>
              <h2>Book Search: Please enter book title to get its details</h2>
              <br>
              <hr>
              <img src="img/booksearch.png" alt="booksearch" width="1200" height="800">
              <form id="search" name="search" action="search.php" method="get">                
                <label>Search Term:
                  <input type="text" id="search-term" name="search-term">
                  <input type="submit" name="search" value="Search">
                </label>
                <div>
                  <?php 
                  if (isset($_GET['search-term'])) {
                    // Display results in a table
                    echo '<table>';
                    echo '<tr><th>Title</th><th>Author</th><th>Image</th><th>ISBN</th><th>Publish Date</th></tr>';
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                      $www_data2=$www_data.$row['Book_Cover'];
                      echo '<tr><td>' . $row['Book_Title'] . '</td><td>' . $row['Author'] . '</td><td>' . $row['Book_Cover'] . '<img src="<?= $www_data2 ?>" alt="" height="260" width="190">'. '</td><td>' . $row['ISBN'] . '</td><td>' . $row['Publish_date'] . '</td></tr>';
                    }
                    echo '</table>';
                  }
                  ?>
                </div>
              </form>
            </section>
          </main>
        <?php include 'includes/footer.php';?>
    </body>
</html>
