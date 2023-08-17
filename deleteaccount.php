<?php
session_start();
require_once('includes/library.php');
$pdo = connectdb();

if (isset($_POST['confirm'])) {
  // Get the username from the session variable
  $username = $_SESSION['username'];

  // Prepare the SQL query to delete the user's account
  $query = "DELETE FROM createacc_users WHERE username = :username";
  $stmt = $pdo->prepare($query);

  // Bind the parameter
  $stmt->bindParam(':username', $username);

  // Execute the query
  $stmt->execute();

  // Destroy the session and redirect to the login page
  session_destroy();
  header("Location: login.php");
  exit();
} elseif (isset($_POST['cancel'])) {
  // If the user cancels the deletion, redirect back to the edit account page
  header("Location: editaccount.php");
  exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<?php $page_title="Delete Account"; include 'includes/metadata.php' ?>
   <body>
        <?php include 'includes/header.php'?>

            <section>
                <!-- starting the main page element-->
                <h3>Delete Account</h3>
                <form method="post" action="deleteaccount.php">
                    <input type="submit" name="confirm" value="Yes, delete my account">
                    <input type="submit" name="cancel" value="No, cancel">
                </form>
            </section>
        <?php include "includes/footer.php"?>
    </body>
</html>




