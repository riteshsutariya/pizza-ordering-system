<?php
require_once '../dbconfig.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }


if(isset($_SESSION['isAdminLoggedIn']))
{
   header("Location:index.php");
}
else{

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['logbtn']) == 'Log in') {
            $useremail = $_POST['txtuseremail'];
            $password = $_POST['txtpassword'];
            $err_msg = '';
            if ($useremail == '' || $password == '') {
                $err_msg = 'All The Fields Are Required!';
            } else {
                if (strlen($password) < 8) {
                    $err_msg = 'Password can not be less than 8 characters!';
                } else {
                    $err_msg = '';
                   
                    $qry="SELECT * FROM admintb WHERE BINARY email='$useremail' AND BINARY password='$password'";
                    $stmt=$con->query($qry);
                     
                    if($stmt->num_rows==1)
                    {
                           session_start();
                           $user=$stmt->fetch_array();
                           $_SESSION['adminId']=$user['adminid'];
                           $_SESSION['adminName']=$user['name'];
                           $_SESSION['adminEmail']=$user['email'];
                           $_SESSION['isAdminLoggedIn']="1";
                           header("Location:index.php");
                    }
                    else{
                        $err_msg='invalid email address or password!';
                    }
                }
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
    <title>Login</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon">
</head>

<body>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-300">
        <div class="flex flex-col bg-white px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full max-w-md">
            <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800">
                Admin Log In
            </div>

            <div class="mt-5">
                <form action="" method="POST">
                    <div class="flex flex-col mb-6">
                        <label for="email" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Email:
                        </label>
                        <div class="relative">
                            <input id="email" type="email" name="txtuseremail" value="<?php if(isset($useremail)) echo $useremail; ?>"
                            class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2"
                            placeholder="Enter Email" required/>
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="password" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Password:
                        </label>
                        <div class="relative">
                            <input id="password" type="password" name="txtpassword"
                            class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2"
                            placeholder="Enter Password" required/>
                        </div>
                    </div>
                    <?php
                if (isset($err_msg) != '')
                    echo "<div class='uppercase text-sm font-bold text-red-500'>", $err_msg, "</div><br>";
                ?>
                    <div class="flex w-full">
                        <button type="submit"  name="logbtn"
                        class="flex items-center justify-center text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-full">
                        LOGIN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>