<?php
include 'includes/library.php';
$pdo = connectdb();
include 'includes/functions.php';

//Check if user has logged in
session_start();
if(!isset($_SESSION["username"])) {
    header("location:login.php");
}

if(isset($_POST['request'])){

        $email = $_POST['email'];
        // Check if in the database
        $query = $pdo->prepare("SELECT email FROM createacc_users WHERE email = ?");
        $query->execute([$email]);
        $row = $query->rowCount();
    
        if($row == 1){
            // existing user, proceed with reset password
    
            // generate a random code
            $code = generateRandomString();
    
            // Formulate the link
            $link = 'href="https://loki.trentu.ca/~kaushiknagtumu/3420/assignments/assn3/reset_password.php?email='.$email.'&code='.$code.'"';
            
            $link2 = '<span style="width:100%;"><a style="padding:10px 100px;border-radius:30px;background:#a8edbc;" '.$link.' > Link </a></span>';
    
            //echo $code, $link; 
    
            $query_exist =  $pdo->prepare("SELECT * FROM reset where email = ?");
            $query_exist->execute([$email]);
            $from_reset = $query_exist->fetch();
    
            if(empty($from_reset)){
                // Save code and INSERT email in a database
                $query_insert = $pdo->prepare("INSERT INTO reset(email, code) VALUES (?, ?)");
                $query_insert->execute([$email, $code]);
            } else {
                // Already exist reseting attempt, switch to UPDATE the reset table instead
                $query_insert = $pdo->prepare("UPDATE reset SET code = ? WHERE email = ?");
                $query_insert->execute([$code, $email]);
            }


            //Formulate and send email
            $from = 'no-reply@trentu.ca';
            $to = $email;
            $subject = 'Reset Password';
            $message = '
                <p>Dear '.$email.',</p>
                
                <p>Please click on this link to reset your password:</p> 
                <p>'.$link2.'</p>
    
                Best regards,
                <br>
                <span>Kaushik Nag Tumu</span>
            ';
            
            // Set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: '.$from."\r\n";
    
            mail($to, $subject, $message, $headers);
           
            // Notification
            $msg = '<h4 class="text-success">Please check your email (including spam) to see the password reset link.</h4>';
    
        } else {
            $error = "<h4 class='text-danger'>Email does not exist!";
        }

        
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $page_title = "Forgot Password"; include'includes/metadata.php';?>
</head>
<body>
    <?php include "includes/header.php"?>
        <main>
            <section>
                <hr>
                <h2>Please enter your credetials required and if your account exists we will send you a rest password link</h2>

                <!-- Message & Error -->
                <?php if(isset($msg)){echo $msg;}?>
                <?php if(isset($error)){echo $error;}?>
                <form id ="forgot" name="forgot" action="forgot.php" method="post">
                    <div>
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" required>
                    </div>
                    <br>
                    <div><button type="submit" id="request" name="request">Request Password Reset</button></div>
                </form>
            </section>
        </main>
    <?php include "includes/footer.php"?>
</body>
</html>