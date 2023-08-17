<?php

// getting username and passwords
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$errors = [];

// connecting to database
include 'includes/library.php';
$pdo = connectDB();

if (isset($_POST['submit'])) {

    $query = "SELECT * FROM `createacc_users` WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt -> execute([$username]);
    $result = $stmt->fetch(); // feting the user data to work with

    if ($result === false){
        $errors ['user'] = true; // this iwll happen if the user is not logged in 
    }
    else {
        if (password_verify($password, $result['password'])){
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $result['id'];
            header ("Location:index.php");
            exit();
        }
        else 
            $errors['login'] = true; // we will then pass it though the login page
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
 <?php $page_title = "Login"; include "includes/metadata.php" ?>
  <script defer src="scripts/showpass.js"></script>
</head>
<body>
    <main>
        <section>
            <div class="form-image">
                <script src="https://cdn.lordicon.com/ritcuqlt.js"></script>
                <lord-icon
                    src="https://cdn.lordicon.com/bhfjfgqz.json"
                    trigger="hover"
                    style="width:500px;height:600px;">
                </lord-icon>
            </div>
            <h2>Login</h2>
            <form id="login" name="login" action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="post" autocomplete="off">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username">
                </div>
                <br>
                <div class="password">
                    <label for="password">Password:</label>
                    <input type="password" id="show" name="password">
                    <label>
                        <input type="checkbox" id="show-password" name="show-password" onclick="myFunction()">Show Password
                    </label>
                </div>
                <br>
                <div>
                    <nav>
                        <a href="register.php" name = "creat_acc" id="creat_acc">Create Account</a>
                        <a href="forgot.php" id = "forgot_pwd">Forgot Password</a>
                    </nav>
                </div>
                <br>
                <div>
                    <label>
                        <input type="checkbox" checked="checked" name="remember"> Remember me
                    </label>
                </div> 
                <br>
                <div><button type="submit" name="submit">Submit</button></div>
            </form>
        </section>
    <?php include 'includes/footer.php';?>
</body>
</html>