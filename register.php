<?php

session_start();

require "includes/library.php";
$pdo = connectdb();

$errors=array();

// Get the form data
$name = $_POST["name"]??null; 
$username = $_POST["username"]??null;
$email = $_POST["email"]??null;
$password = $_POST["password"]??null;
$password2 = $_POST["password2"]??null;


if(isset($_POST['submit'])) {

    /* // Validate username
    if (!isset($username) || strlen($username) < 3 || !ctype_alnum($username)) {
        $errors['username'] = true;
    }       

    // Validate name
    if (empty(trim($_POST['name']))) {
        $errors['name'] = true;
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', trim($_POST['name']))) {
        $errors['name'] = true;
    } else {
        $name = trim($_POST['name']);
    }

    // Validate email
    if (empty(trim($_POST['email']))) {
        // If email field is empty, add an error message to $errors array
        $errors['email'] = true;
    } elseif (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        // If email is not a valid format, add an error message to $errors array
        $errors['email'] = true;
    } else {
        // If email is valid, store the value in $email variable
        $email = trim($_POST['email']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        // If password field is empty, add an error message to $errors array
        $errors['password'] = true;
    } elseif (strlen(trim($_POST['password'])) < 8) {
        // If password is less than 8 characters, add an error message to $errors array
        $errors['password'] = true;
    } else {
        // If password is valid, store the value in $password variable
        $password = trim($_POST['password']);
    }

    // Validate password
    if ($password !== $password2) {
        $errors['passwords_dont_match'] = true;
    }

    // Print the errors for debugging purposes
 */
    if(count($errors) === 0){

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare a SQL statement to insert the user data into the database
        $stmt = $pdo->prepare("INSERT INTO createacc_users (name, email, username, password) VALUES (:name, :email, :username, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        
        // Execute the SQL statement
        if ($stmt->execute()) {
            // Redirect to login page
            header('location: success.php');
            exit;
        } else {
            echo 'Oops! Something went wrong. Please try again later.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php $page_title = "Register"; include 'includes/metadata.php'; ?>   <!-- Including metadata -->
<script defer src ="scripts/register.js"></script>
<body>
    <?php include 'includes/header.php';?> <!-- Including the header tag -->
        <section>
            <h2>Create account</h2>
            <img src="img/create_acc.png" alt="create_acc" width="180" height="200"> 
            <form method="POST" id="requestform" autocomplete="off" novalidate>                
                <h2>Please fill in this form to create an account.</h2>
                <br>
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter Username" required><br>
                    <span class="error" style="display: none;">Please enter a valid username of atlaest 3 characters, or choose a different one</span> <!-- Outputting error message -->
                </div>
                <br>
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter Name" required><br>
                    <span class="error " style="display: none;">Please enter a Name</span>
                </div>
                <br>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter Email" required><br>
                    <span class="error" style="display: none;">Please enter an Email</span> <!-- Outputting error message -->
                </div>
                <br>
                <div>
                    <div class="input-box">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Enter Password" required><br>
                        <span class="error" style="display: none;">Please enter a Password</span> <!-- Outputting error message -->
                        <p id="message">Password is <span id="strength"></span></p>    
                    </div>
                </div>
                <br>
                <div>
                    <label for="password2">Re-enter Password:</label>
                    <input type="password" id="password2" name="password2" placeholder="Confirm Password" required><br>
                    <span class="error" style="display: none;">Password Do not match</span> <!-- Outputting error message -->
                </div>
                <br>     
                <div><button type="reset" name="reset" id="reset">Reset</button></div>   
                <br>
                <div><button id="submit" name="submit" id="submit">Create Account</button></div>                  
            </form>
        </section>
    <?php include 'includes/footer.php';?> <!-- including the footer -->
</body>
</html>