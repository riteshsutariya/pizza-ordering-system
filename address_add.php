<?php
include_once 'dbconfig.php';

function user_exist($id)
{
    if (isset($con) == false) {
        include 'dbconfig.php';
    }
    $is_exist = "yes";
    $qry = "SELECT COUNT(*) AS cnt FROM addresstb WHERE custid=" . $id;
    $res = $con->query($qry);
    if ($res) {
        $cnt = $res->fetch_array();
        if ($cnt['cnt'] == 1) {
            $is_exist = "yes";
        } else {
            $is_exist = "no";
        }
    } else {
        die("Error Occured is exist!");
    }
    return $is_exist;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['btnsubmit'] == 'Add') {
        session_start();
        $userid = $_SESSION['userId'];
        $pin = $_POST['pincode'];
        $area = $_POST['area'];
        $apartment = $_POST['appartment_street'];
        $houseno = $_POST['houseno'];
        // houseno
        if ($pin == '' || $area == '' || $apartment == '' || $houseno == '') {
            $err_msg = "All The Fields Are Required!";
        } else {
            if (strlen($pin) != 6) {
                $err_msg = "Pincode should be of 6 digits!";
            } else {

                $add_insrt = $con->query("INSERT INTO addresstb VALUES(null,$userid,'$houseno','$apartment','$area',$pin)");
                        if ($add_insrt) {
                            if ($con->affected_rows == 1) {
                                if(isset($_GET['redirect']))
                                {
                                    header("Location:review_order.php");
                                }else{
                                    header("Location:addresses.php");
                                }
                            }
                        } else {
                            die("error occured addressesTb!");
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
                Add Address
            </div>

            <div class="mt-5">
                <form action="" method="POST">
                    <div class="flex flex-col mb-6">
                        <label for="houseno" class="mb-1 text-xs sm:text-sm text-gray-600">
                            House No:
                        </label>
                        <div class="relative">
                            <input id="houseno" type="text" name="houseno" value="<?php if (isset($houseno)) echo $houseno; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter House No" />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="appartment_street" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Appartment/Street:
                        </label>
                        <div class="relative">
                            <input id="appartment_street" type="text" name="appartment_street" value="<?php if (isset($apartment)) echo $apartment; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Appartment/Street" />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="area" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Area:
                        </label>
                        <div class="relative">
                            <input id="area" type="text" name="area" value="<?php if (isset($area)) echo $area; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Area" />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="pincode" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Pincode:
                        </label>
                        <div class="relative">
                            <input id="pincode" type="number" name="pincode" value="<?php if (isset($pin)) echo $pin; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter Pincode" />
                        </div>
                    </div>

                    <?php
                    if (isset($err_msg) != '')
                        echo "<div class='uppercase text-sm font-bold text-red-500'>", $err_msg, "</div><br>";
                    ?>

                    <div class="flex w-full">
                        <input type="submit" name="btnsubmit" value="Add" class="flex items-center justify-center text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-full">
                        <!-- <button type="submit"
                        class="flex items-center justify-center text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-full">
                        Add
                        </button> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>