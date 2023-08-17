<?php

//get toy request from GET array
$bookInfo = $_GET['Book_Cover'] ?? null;
//make sure it's  valid
if (!$bookInfo) {
    echo 'error';
    exit();
}

//include the library file
require 'includes/library.php';
// create the database connection
$pdo = connectdb();

//query for current requests and qty available
$statement = $pdo->prepare("SELECT Book_Title, Book_url, Author, Genre, Description, ISBN, pages, Publish_date, Book_rating  FROM `books` WHERE Book_Cover = ?");
$statement->execute([$bookInfo]);
$row = $statement->fetch();
//check if there is enough available
if ($row['Book_Cover'] = $row['ID']) {
    echo 'true'; //enough
} else {
    echo 'false'; //not enough
}
exit();