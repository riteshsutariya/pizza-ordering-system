<?php
include_once 'dbconfig.php';

if($_SERVER['REQUEST_METHOD']=='GET')
{
    if(isset($_GET['id'])){
       if($_GET['id']!=='')
       {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
          }
        $add_id=$_GET['id'];
        $cust_id=$_SESSION['userId'];
        $old_addr=$con->query("SELECT * FROM addresstb where addressid=".$add_id." AND custid=".$cust_id);
        if($old_addr)
        {
             if($old_addr->num_rows==1)
             {
                $old=$old_addr->fetch_array();
                $old_pin = $old['pincode'];
                $old_area = $old['area'];
                $old_apartment = $old['apartment_street'];
                $old_houseno = $old['houseno'];
             }
             else{
                 echo "something went wrong!!";
             }
        }
        else{
            die("error while fetching address~");
        }     
       }
       else{
        die("you must played with url!"); 
       }
    }
    else{
        die("something went wrong or you must played with url!");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['btnsubmit'] == 'Edit Address') {
        session_start();
        $userid = $_SESSION['userId'];
        $pin = $_POST['pincode'];
        $area = $_POST['area'];
        $apartment = $_POST['appartment_street'];
        $houseno = $_POST['houseno'];
        
        $add_id=$_GET['id'];

        // houseno
        if ($pin == '' || $area == '' || $apartment == '' || $houseno == '') {
            $err_msg = "All The Fields Are Required!";
        } else {
            if (strlen($pin) != 6) {
                $err_msg = "Pincode should be of 6 digits!";
            } else {
                        $add_update = $con->query("UPDATE addresstb SET houseno='$houseno',apartment_street='$apartment',area='$area',pincode=$pin WHERE custid=$userid AND addressid=$add_id");
                        if ($add_update) {
                            if ($con->affected_rows == 1) {
                                header("Location:addresses.php");
                            }
                            else{
                                header("Location:addresses.php");
                            }
                        } else {
                            die("error occured addressesTb-update!");
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
    <title>Address</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon">
</head>

<body>
    <?php require_once 'header.php' ?>
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="flex flex-col bg-white px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full max-w-md">
            <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800">
                Edit Address
            </div>

            <div class="mt-5">
                <form action="" method="POST">
                    <div class="flex flex-col mb-6">
                        <label for="houseno" class="mb-1 text-xs sm:text-sm text-gray-600">
                            House No:
                        </label>
                        <div class="relative">
                            <input id="houseno" type="text" name="houseno" value="<?php if (isset($old_houseno)) echo $old_houseno; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter House No" />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="appartment_street" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Appartment/Street:
                        </label>
                        <div class="relative">
                            <input id="appartment_street" type="text" name="appartment_street" value="<?php if (isset($old_apartment)) echo $old_apartment; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Appartment/Street" />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="area" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Area:
                        </label>
                        <div class="relative">
                            <input id="area" type="text" name="area" value="<?php if (isset($old_area)) echo $old_area; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Area" />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="pincode" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Pincode:
                        </label>
                        <div class="relative">
                            <input id="pincode" type="number" name="pincode" value="<?php if (isset($old_pin)) echo $old_pin; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Pincode" />
                        </div>
                    </div>

                    <?php
                    if (isset($err_msg) != '')
                        echo "<div class='uppercase text-sm font-bold text-red-500'>", $err_msg, "</div><br>";
                    ?>

                    <div class="flex w-full">
                        <input type="submit" name="btnsubmit" value="Edit Address" class="flex items-center justify-center text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-full">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>