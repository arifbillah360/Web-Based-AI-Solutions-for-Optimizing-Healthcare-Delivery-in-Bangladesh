<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>
<body>
<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set timezone
date_default_timezone_set('Asia/Dhaka');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

// Import DB
include("connection.php");

// Mail sending function
function sendLoginEmail($email, $usertype) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shihabhasa685@gmail.com';
        $mail->Password = 'dajw cnnc lvvt izek';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('shihabhasa685@gmail.com', 'Your System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Login Notification';
        $mail->Body = "Dear $usertype,<br><br>You have successfully logged in at <strong>" . date("Y-m-d H:i:s") . "</strong>.<br><br>If this wasn't you, please contact support.";
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        $mail->send();
        echo "Email sent successfully!";
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}


if ($_POST) {
    $email = $_POST['useremail'];
    $password = $_POST['userpassword'];
    $error = '<label for="promter" class="form-label"></label>';

    $result = $database->query("SELECT * FROM webuser WHERE email='$email'");
    if ($result->num_rows == 1) {
        $utype = $result->fetch_assoc()['usertype'];

        if ($utype == 'p') {
            $checker = $database->query("SELECT * FROM patient WHERE pemail='$email' AND ppassword='$password'");
            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'p';
                sendLoginEmail($email, "Patient");
                header('Location: patient/index.php');
                exit;
            } else {
                $error = '<label class="form-label" style="color:red;text-align:center;">Invalid email or password</label>';
            }
        } elseif ($utype == 'a') {
            $checker = $database->query("SELECT * FROM admin WHERE aemail='$email' AND apassword='$password'");
            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'a';
                sendLoginEmail($email, "Admin");
                header('Location: admin/index.php');
                exit;
            } else {
                $error = '<label class="form-label" style="color:red;text-align:center;">Invalid email or password</label>';
            }
        } elseif ($utype == 'd') {
            $checker = $database->query("SELECT * FROM doctor WHERE docemail='$email' AND docpassword='$password'");
            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'd';
                sendLoginEmail($email, "Doctor");
                header('Location: doctor/index.php');
                exit;
            } else {
                $error = '<label class="form-label" style="color:red;text-align:center;">Invalid email or password</label>';
            }
        }
    } else {
        $error = '<label class="form-label" style="color:red;text-align:center;">No account found for this email</label>';
    }
} else {
    $error = '<label class="form-label">&nbsp;</label>';
}
?>

<center>
    <div class="container">
        <form action="" method="POST">
        <table border="0" style="margin: 0;padding: 0;width: 60%;">
            <tr><td><p class="header-text">Welcome Back!</p></td></tr>
            <tr><td><p class="sub-text">Login with your details to continue</p></td></tr>
            <tr><td class="label-td"><label for="useremail" class="form-label">Email:</label></td></tr>
            <tr><td class="label-td"><input type="email" name="useremail" class="input-text" placeholder="Email Address" required></td></tr>
            <tr><td class="label-td"><label for="userpassword" class="form-label">Password:</label></td></tr>
            <tr><td class="label-td"><input type="password" name="userpassword" class="input-text" placeholder="Password" required></td></tr>
            <tr><td><br><?php echo $error ?></td></tr>
            <tr><td><input type="submit" value="Login" class="login-btn btn-primary btn"></td></tr>
            <tr>
                <td>
                    <br>
                    <label class="sub-text" style="font-weight: 280;">Don't have an account?</label>
                    <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a><br><br><br>
                </td>
            </tr>
        </table>
        </form>
    </div>
</center>
</body>
</html>
