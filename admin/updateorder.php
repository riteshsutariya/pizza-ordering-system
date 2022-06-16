<?php

include '../functions.php';
include "../dbconfig.php";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        if ($_GET['id'] != '') {
            $orderqry = $con->query("SELECT * FROM ordertb WHERE orderid=" . $_GET['id']);
            if ($orderqry) {
                if ($orderqry->num_rows == 1) {
                    $order = $orderqry->fetch_array();
                    // var_dump($order);
                } else {
                    echo "order may be deleyed!";
                }
            } else {
                die("something went wrong!!");
            }
        } else {
            echo "odrer id missing!";
        }
    } else {
        die("something went wrong!");
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btnupdate'])) {
        $order_s = $_POST['txt_order_status'];
        $payment_s = $_POST['txt_payment_status'];

        $update_qry = $con->query("UPDATE ordertb SET payment_status='$payment_s',order_status='$order_s' WHERE orderid=" . $_POST['id']);
        if ($update_qry) {
            if ($con->affected_rows == 1) {
                $userQ = $con->query("SELECT name,email FROM customertb WHERE custid=(SELECT custid FROM ordertb WHERE orderid=" . $_POST['id'] . ")");
                
                if ($userQ) {
                    if ($userQ->num_rows == 1) {
                        $user = $userQ->fetch_array();
                        $name = $user['name'];
                        $email = $user['email'];
                        $order_id = $_POST['id'];
                        $to = $email;
                        $name = $name;
                        $header = "MIME-Version: 1.0" . "\r\n";
                        $header .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
                        if ($order_s == 'Delivered') {

                            $subject = "Order Delivered";

                            $message = "<h1>Order Delivered.</h1><h3>$name, Your Order Has Delivered.</h3> 
                    <p>This Is To Inform You That Your Order With Order Id $order_id Has Been Delivered Successfullly.</p>
                    <p>Hope You Will Enjoy Our Pizza</p>
                    <small style='text-align:left;'>Thank You.</small>";
                            if (mail($to, $subject, $message, $header)) {
                                // return true;
                            } else {
                                die("mail not sent!!");
                            }
                        } else if ($order_s == "Can\'t Delivered") {
                            $subject = "Order Can Not Delivered";

                            $message = "<p>We regret that we are unable to deliver Your Order With Order Id $order_id from our side due to some reasons.</p>
                    <p>We Will Assure That This Type Of Mistakes Never Happend Next Time.</p>
                    <p>Hope You Will Accept Our Appology.</p>
                    <small style='text-align:left;'>Thank You.</small>";
                            if (mail($to, $subject, $message, $header)) {
                                // return true;
                                // header("Location:orderlist.php");
                            } else {
                                die("mail not sent!!");
                            }
                        }
                    } else {
                        echo ("user not found, can be deleted!");
                    }
                } else {
                    // echo $userQ;
                    // SELECT name,email FROM customertb WHERE custid=(SELECT custid FROM ordertb WHERE orderid=45)
                    die("error occurred!");
                }
                header("Location:orderlist.php");
            } else {
                // echo "can't update status!";
                header("Location:orderlist.php");
            }
        } else {
            // $update_qry;
            die("UPDATE ordertb SET payment_status='$payment_s',order_status='$order_s' WHERE orderid=" . $_POST['id']);
            // die("something went wrong!!");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Status Update</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="\css\style.css" />
    <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon" />
</head>

<body style="font-family: 'Montserrat'; scroll-behavior: smooth">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-300">
        <div class="flex flex-col bg-white px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full mt-14 max-w-xl">
            <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800 mb-8">
                Update Order Statuses
            </div>

            <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                <input type="hidden" name="id" value="<?php if (isset($_GET['id'])) echo $_GET['id']; ?>">
                <div class='mb-6'>
                    <span class="uppercase text-sm font-bold">Order Status</span><br />
                    <select name="txt_order_status" id="type_v" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2">
                        <option value="Order Placed" <?php if ($order['order_status'] == 'Order Placed') echo "selected"; ?>>Order Placed</option>
                        <option value="Delivered" <?php if ($order['order_status'] == 'Delivered') echo "selected"; ?>>Delivered</option>
                        <option value="Can\'t Delivered" <?php if ($order['order_status'] == "Can't Delivered") echo "selected"; ?>>Can't Delivered</option>
                    </select>
                </div>

                <div class='mb-6'>
                    <span class="uppercase text-sm font-bold">Payment Status</span><br />
                    <select name="txt_payment_status" id="type_v" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2">
                        <option value="Pending" <?php if ($order['payment_status'] == 'Pending') echo "selected"; ?>>Pending</option>
                        <option value="Success" <?php if ($order['payment_status'] == 'Success') echo "selected"; ?>>Success</option>
                        <option value="Failed" <?php if ($order['payment_status'] == "Failed") echo "selected"; ?>>Failed</option>
                    </select>
                </div>

                <div class="mt-8">
                    <input type="submit" name="btnupdate" class="bg-orange-600 px-3 py-3 mt-1 rounded-lg hover:scale-105 w-full" value="Update">
                </div>
            </form>
            <!-- </div> -->
        </div>
    </div>
</body>

</html>