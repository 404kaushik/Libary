<?php 
require('includes/library.php');
$pdo = connectDB();

session_start();

if(isset($_GET['email']) && isset($_GET['code'])){
    $_SESSION['email'] = $_GET['email'];
    $code = $_GET['code'];

    // Check against the database to see if correct link
    $query = $pdo->prepare("SELECT * FROM reset WHERE email = ?");
    $query->execute([$_SESSION['email']]);
    $from_reset = $query->fetch();

    if($code != $from_reset['code']){
        $expired = 'Sorry, your link is invalid or has expired!';
    }

}



if(isset($_POST['reset'])){
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    if($pass1 == $pass2){
        $hashed_password = password_hash($pass1, PASSWORD_DEFAULT); // if both passwords are the same then save the password in the database as a hased password 

    } else {
        $error = 'Passwords do not match!'; //if not then output an error message
    }
    
    $email = $_SESSION['email']; // if the email exists then update the password

    // Update password
    if(empty($error)){
        $query = $pdo->prepare("UPDATE createacc_users SET password = ? WHERE email = ?");
        $query ->execute([$hashed_password, $email]);

        $msg = 'Successfully updated your password! <a class="btn btn-success" href="index.php"> >>Log In</a>';

        session_destroy();
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $page_title="Request Password"; include'includes/metadata.php' ?>    
</head>
<body>
    <?php include"includes/header.php" ?>
        <main>
            <section>
                <div class="container mt-5 mb-5" style="margin: 0 auto; width: 60%;font-size:2rem;">

                <!-- Error Notification -->
                <?php if(isset($error)){echo '<p class="alert-danger rounded p-3">'.$error.'</p>';}?>
                <?php if(isset($expired)){echo '<p class="alert-danger rounded p-3">'.$expired.'</p>';}?>

                <?php if(isset($msg)){echo '<p class="alert-success rounded p-3">'.$msg.'</p>';}?>

                <!-- Reset Form -->

                <?php 
                    if(!isset($expired) && isset($_GET['code'])){
                        echo '
                        <form action="reset_password.php" method="post">
                            <h4>Please enter your new password:</h4>
                            <input type="text" name="pass1" class="form-control" placeholder="New password..." required>
                            <input type="text" name="pass2" class="form-control mt-2" placeholder="Repeat new password..." required>
                            <input type="submit" name="reset" value="Reset Password" class="form-control btn btn-warning mt-2">
                        </form>
                        ';
                    }
                ?>

                </div>
                <div class="container mt-5 mb-5" style="margin: 0 auto; width: 60%; ">
                </div>
            </section>
        </main>
    <?php include 'includes/footer.php';?>
</body>
</html>