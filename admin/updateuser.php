<?php
require_once '../dbconfig.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  
    if (isset($_GET['btnUpdate']) == "Update user") {
        $username = $_GET['txtname'];
        $email = $_GET['txtemail'];
        $contact = $_GET['txtcontact'];
        $cid = $_GET['id'];
        $err_msg = '';
    
        $qry = "UPDATE customertb  SET name='$username',email='$email',contactno='$contact' WHERE custid=$cid";
        $res = $con->query($qry);
        if ($res) {
            if ($con->affected_rows == 1) {
                header("Location:index.php");
            } else {
                $err_msg = 'error while updating, please try again!';
            }
        } else {
            die("error while updating user!");
        }
    }
    if (isset($_GET['id']) == true) {
        $cid = $_GET['id'];
        $record = $con->query("SELECT * FROM customertb where custid=$cid");
        if ($record) {
            if ($record->num_rows == 1) {
                $cust = $record->fetch_array();
            } else {
                die("customer not found!");
            }
        } else {
            die("Error occurred!");
        }
    } else {
        die("customer id not provided!!");
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pizza Update</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon" />
</head>

<body>

    <body style="font-family: 'Montserrat'; scroll-behavior: smooth">
        <div class="min-h-screen flex flex-col items-center justify-center bg-gray-300">
            <div class="flex flex-col bg-white px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full max-w-md">
                <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800">
                    Update user details
                </div>

                <div class="mt-5">
                    <form action="" method="GET">
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                        <div class="flex flex-col mb-6">
                            <label for="name" class="mb-1 text-xs sm:text-sm text-gray-600">
                                Name:
                            </label>
                            <div class="relative">
                                <input id="name" type="text" name="txtname" value="<?php if (isset($cust['name'])) echo $cust['name']; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Name" required />
                            </div>
                        </div>

                        <div class="flex flex-col mb-6">
                            <label for="email" class="mb-1 text-xs sm:text-sm text-gray-600">
                                E-Mail Address:
                            </label>
                            <div class="relative">
                                <input id="email" type="email" name="txtemail" value="<?php if (isset($cust['email'])) echo $cust['email']; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="E-Mail Address" required />
                            </div>
                        </div>

                        <div class="flex flex-col mb-6">
                            <label for="Contact" class="mb-1 text-xs sm:text-sm text-gray-600">
                                Contact No:
                            </label>
                            <div class="relative">
                                <input id="contact" type="number" name="txtcontact" value="<?php if (isset($cust['contactno'])) echo $cust['contactno']; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Contact No" required />
                            </div>
                        </div>
                </div>

                <?php
                if (isset($err_msg) != '')
                    echo "<span class='uppercase text-sm font-bold text-red-500'>", $err_msg, "</span><br>";
                ?>
                <div class="mt-8">

                    <div class="flex w-full">
                        <input name="btnUpdate" type="submit" value="Update user" class="flex cursor-pointer items-center justify-center text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-full">
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </body>

</html>