<?php
require_once 'dbconfig.php';

function password_exists($param)
{
    global $con;
    $all_pass = $con->query("SELECT password FROM customertb");
    while ($pass = $all_pass->fetch_array()) {
        if (password_verify($param, $pass['password'])) {
            return true;
        }
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btnreg']) == 'Register') {
        $username = $_POST['txtname'];
        $email = $_POST['txtemail'];
        $contact = $_POST['txtcontact'];
        $pass = $_POST['txtpass'];
        $conpass = $_POST['txtconpass'];
        $err_msg = '';
        $conExp="/^[6-9]\d{9}$/";
        if ($username == '' || $email == '' || $contact == '' || $pass == '' || $conpass == '') {
            $err_msg = 'All The Fields Are Required!';
        } else {

            //validating mobile number
            if (preg_match($conExp, $contact)) {
                //validating password
                if (strlen($pass) < 8 || strlen($conpass) < 8) {
                    $err_msg = 'Password can not be less than 8 characters!';
                } else {
                    if (strcmp($pass, $conpass) != 0) {
                        $err_msg = 'Password does not matched!';
                    } else {
                        //we'll fetach all passwords and looping to check if password exists or not
                        if (password_exists($pass)) {
                            $err_msg = 'Please choose different password!';
                        } else {
                            $exist_qry = "SELECT * FROM customertb WHERE name='$username' OR email='$email' OR contactno='$contact'";
                            $user_exist = $con->query($exist_qry);

                            if (!$user_exist) {
                                die("error while checking user!");
                            } else {
                                if ($user_exist->num_rows == 1) {
                                    $err_msg = 'user exist with same username,email or contact!';
                                } else {
                                    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
                                    $qry = "INSERT INTO customertb(custid,name,email,password,contactno) VALUES(null,'$username','$email','$hashed_pass','$contact')";
                                    $res = $con->query($qry);
                                    if ($res) {
                                        if ($con->affected_rows == 1) {
                                            header("Location:login.php");
                                        } else {
                                            $err_msg = 'error while registering, please try again!';
                                        }
                                    } else {
                                        die("error while inserting user!");
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                $err_msg='Please Enter Valid 10 Digit Mobile Number!';
                $con_err=true;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon">
</head>

<body>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-300">
        <div class="flex flex-col bg-white px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full max-w-md">
            <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800">
                Create New Account
            </div>

            <div class="mt-5">
                <form action="" method="POST">
                    <div class="flex flex-col mb-6">
                        <label for="name" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Name:
                        </label>
                        <div class="relative">
                            <input id="name" type="text" name="txtname" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Name" value="<?php if(isset($username)) echo $username; ?>" required />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="email" class="mb-1 text-xs sm:text-sm text-gray-600">
                            E-Mail Address:
                        </label>
                        <div class="relative">
                            <input id="email" type="email" name="txtemail" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="E-Mail Address" value="<?php if(isset($email)) echo $email; ?>" required />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="Contact" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Contact No:
                        </label>
                        <div class="relative">
                            <input id="contact" type="number" name="txtcontact" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2 <?php if(isset($con_err)) echo "border-red-500 outline-red-500"; ?>" placeholder="Enter Contact No" value="<?php if(isset($contact)) echo $contact; ?>" required <?php if(isset($contact)) echo "autofocus"; ?> />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="password" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Password:
                        </label>
                        <div class="relative">
                            <input id="password" type="password" name="txtpass" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Password" required />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="password" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Confirm Password:
                        </label>
                        <div class="relative">
                            <input id="password" type="password" name="txtconpass" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Password" required />
                        </div>
                    </div>

                    <?php
                    if (isset($err_msg) != '')
                        echo "<span class='uppercase text-sm font-bold text-red-500'>", $err_msg, "</span><br>";
                    ?>
                    <div class="mt-8">

                        <div class="flex w-full">
                            <button type="submit" name="btnreg" class="flex items-center justify-center text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-full">
                                REGISTER
                            </button>
                        </div>

                </form>
            </div>
        </div>
        <div class="lognow mt-4">
            Already Having Account? <a href="login.php" style="text-decoration: underline;">Log In Here</a><br>
        </div>
    </div>
</body>

</html>