<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/signup.css">
        
    <title>Create Account</title>
    <style>
        .container{
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>
<body>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';



session_start();
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";
/*session_start();

$_SESSION["user"]="";
$_SESSION["usertype"]="";
*/ 


// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');

$_SESSION["date"]=$date;


//import database
include("connection.php");





if($_POST){

    $result= $database->query("select * from webuser");

    $fname=$_SESSION['personal']['fname'];
    $lname=$_SESSION['personal']['lname'];
    $name=$fname." ".$lname;
    $address=$_SESSION['personal']['address'];
    $nic=$_SESSION['personal']['nic'];
    $dob=$_SESSION['personal']['dob']; 


    /*
    if (isset($_SESSION['personal'])) {
        $fname = $_SESSION['personal']['fname'];
        $lname = $_SESSION['personal']['lname'];
        $name = $fname . " " . $lname;
        $address = $_SESSION['personal']['address'];
        $nic = $_SESSION['personal']['nic'];
        $dob = $_SESSION['personal']['dob'];
    } else {
        // If the user didn't come from the previous step, go back to signup
        header('Location: signup.php');
        exit;
    }
    */


    $email=$_POST['newemail'];
    $tele=$_POST['tele'];
    $newpassword=$_POST['newpassword'];
    $cpassword=$_POST['cpassword'];
    
    if ($newpassword==$cpassword){
        $sqlmain= "select * from webuser where email=?;";
        $stmt = $database->prepare($sqlmain);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows==1){
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
        }else{
            //TODO
            $database->query("insert into patient(pemail,pname,ppassword, paddress, pnic,pdob,ptel) values('$email','$name','$newpassword','$address','$nic','$dob','$tele');");
            $database->query("insert into webuser values('$email','p')");

            //print_r("insert into patient values($pid,'$email','$fname','$lname','$newpassword','$address','$nic','$dob','$tele');");
            $_SESSION["user"]=$email;
            $_SESSION["usertype"]="p";
            $_SESSION["username"]=$fname;

            








        

            
            include("connection.php");
            


            
            if ($_POST) {
                $result = $database->query("select * from webuser");
            
                $fname = $_SESSION['personal']['fname'];
                $lname = $_SESSION['personal']['lname'];
                $name = $fname . " " . $lname;
                $address = $_SESSION['personal']['address'];
                $nic = $_SESSION['personal']['nic'];
                $dob = $_SESSION['personal']['dob'];
                $email = $_POST['newemail'];
                $tele = $_POST['tele'];
                $newpassword = $_POST['newpassword'];
                $cpassword = $_POST['cpassword'];
            
                if ($newpassword == $cpassword) {
                    $sqlmain = "select * from webuser where email=?;";
                    $stmt = $database->prepare($sqlmain);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                        $error = '<label class="form-label" style="color:red;text-align:center;">Already have an account for this Email address.</label>';
                    } else {
                        $database->query("insert into patient(pemail, pname, ppassword, paddress, pnic, pdob, ptel) values('$email','$name','$newpassword','$address','$nic','$dob','$tele');");
                        $database->query("insert into webuser values('$email','p')");
            
                        // Send notification email using Titan SMTP
                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.titan.email';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'admin@yourdomain.com'; // Change this
                            $mail->Password = 'your_password_here';   // Change this
                            $mail->SMTPSecure = 'ssl';
                            $mail->Port = 465;
            
                            $mail->setFrom('admin@yourdomain.com', 'Appointment Booking'); // Change this
                            $mail->addAddress($email, $name);
            
                            $mail->isHTML(true);
                            $mail->Subject = 'User Registration Complete';
                            $mail->Body    = "Hi <b>$name</b>,<br><br>Your registration was successful.<br>Welcome to our system!";
            
                            $mail->send();
                        } catch (Exception $e) {
                            // Optionally handle errors
                            // echo "Mailer Error: " . $mail->ErrorInfo;
                        }
            
                        $_SESSION["user"] = $email;
                        $_SESSION["usertype"] = "p";
                        $_SESSION["username"] = $fname;
            
                        header('Location: patient/index.php');
                        exit;
                    }
                } else {
                    $error = '<label class="form-label" style="color:red;text-align:center;">Password Confirmation Error! Please retype password.</label>';
                }
            } else {
                $error = '<label class="form-label"></label>';
            }
        
            

















            header('Location: patient/index.php');
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>';
        }
        
    }else{
        $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Conformation Error! Reconform Password</label>';
    }



    
}else{
    //header('location: signup.php');
    $error='<label for="promter" class="form-label"></label>';
}

?>


    <center>
    <div class="container">
        <table border="0" style="width: 69%;">
            <tr>
                <td colspan="2">
                    <p class="header-text">Let's Get Started</p>
                    <p class="sub-text">It's Okey, Now Create User Account.</p>
                </td>
            </tr>
            <tr>
                <form action="" method="POST" >
                <td class="label-td" colspan="2">
                    <label for="newemail" class="form-label">Email: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="email" name="newemail" class="input-text" placeholder="Email Address" required>
                </td>
                
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="tele" class="form-label">Mobile Number: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="tel" name="tele" class="input-text"  placeholder="ex: 0712345678" pattern="[0]{1}[0-9]{9}" >
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="newpassword" class="form-label">Create New Password: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="newpassword" class="input-text" placeholder="New Password" required>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="cpassword" class="form-label">Conform Password: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="cpassword" class="input-text" placeholder="Conform Password" required>
                </td>
            </tr>
     
            <tr>
                
                <td colspan="2">
                    <?php echo $error ?>

                </td>
            </tr>
            
            <tr>
                <td>
                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >
                </td>
                <td>
                    <input type="submit" value="Sign Up" class="login-btn btn-primary btn">
                </td>

            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                    <a href="login.php" class="hover-link1 non-style-link">Login</a>
                    <br><br><br>
                </td>
            </tr>

                    </form>
            </tr>
        </table>

    </div>
</center>
</body>
</html>