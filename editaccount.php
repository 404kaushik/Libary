<?php
/*****************************************
 * Put session stuff to check for login here
 ***********************************************/
session_start();
if (!isset($_SESSION['username'])) {
    header("Location:login.php");
    exit(); 
}
require_once "includes/library.php";
/* Array for errors*/
$errors = array();

/*  Making the database connection*/
$pdo = connectdb();

/* Extracting records based on session id */
$query = "SELECT * FROM createacc_users WHERE username = ?";
$stmt = $pdo->prepare($query); //run query
$stmt->execute([$_SESSION['username']]);
$results = $stmt->fetch(); //get all the data

//getting all the data and giving the fields
$name = $results['name'] ?? null;
$username = $results['username'] ?? null;
$email = $results['email'] ?? null;
$password = $results['password'] ?? null;

    
if (isset($_POST['save'])) {
    // To make the form actually sticky after form submission
    $name = $_POST['name'] ?? null;
    $username = $_POST['username'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    
    /*Validating the name field for errors*/
    if (strlen($name) === 0) {
        $errors['name'] = true;
    }
    
    // Validating the username for errors
    if (strlen($username) === 0) {
        $errors['username'] = true;
    }
    
    // validating the email field
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = true;
    }

    // validating the password field
    if (empty($password))
    {
        $errors['password'] = "Please enter a password";
    }

    $query = "UPDATE createacc_users SET name=?, email=?, username=? WHERE username=?";
    $stmt = $pdo->prepare($query); //run query
    $stmt->execute([$name, $email, $username, $_SESSION['username']]);

    header("location: success.php");
    exit();

}
?>
            
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $page_title = "Edit Account";include 'includes/metadata.php' ?>
    <title>My Account</title>
</head>
   <body>
        <?php include 'includes/header.php'?>
            <section>
                <!-- starting the main page element-->
                <h3>My Account</h3>
                <form id="register" name="create_account" method="post">
                    <div>
                        <label for="name">New Name</label>
                        <input type ="text" name="name" placeholder="Enter New Name" value="<?= $name; ?>"> 
                    </div>    
                    <div>
                        <label for="username">Username: </label><!--getting the username-->
                        <input type="text" name="username" id="username" placeholder="Username" value="<?= $username;?>"/>
                        <span class="errorform <?= !isset($errors['username']) ? 'hidden' : "" ?>"> Please enter your username</span>
                        <span class="errorform <?= !isset($errors['exists']) ? 'hidden' : "" ?>">This username  already exists.</span>
                    </div> 
                    <div>
                        <label for="email">Email Address: </label>
                        <input type="email" name="email" id="email" placeholder="Email" value="<?= $email ?>" />
                        <span class="errorform <?= !isset($errors['email']) ? 'hidden' : "" ?>">Please enter your email</span>
                    </div> 
                    <div>
                        <label for="password">Current Password: </label><!--getting the password-->
                        <input type="password" name="password" id="password" placeholder="Password" />
                        <span class="errorform <?= !isset($errors['password']) ? 'hidden' : "" ?>">Please Enter password</span>
                        <span class="errorform <?= !isset($errors['passwordweak']) ? 'hidden' :"" ?>">Password should be at least 8 characters in length and should include atleast one upper case letter, one number, and one special character.</span>
                    </div>
                    <nav>
                        <button type="submit" name="save" id="save">Save Changes</button><!--saving the changes at the end-->
                    </nav>
                </form>
            </section>
        <?php include 'includes/footer.php' ?>
   </body>
</html>