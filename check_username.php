<?php

//get toy request from GET array
$username = $_GET['username'] ?? null;
//make sure it's  valid
if (!$username) {
    echo 'error';
    exit();
}

//include the library file
require 'includes/library.php';
// create the database connection
$pdo = connectdb();

//query for current requests and qty available
$statement = $pdo->prepare("SELECT username, id FROM `createacc_users` WHERE username = ?");
$statement->execute([$username]);
$row = $statement->fetch();
//check if there is enough available
if ($row['username'] = $row['id']) {
    echo 'true'; //enough
} else {
    echo 'false'; //not enough
}
exit();