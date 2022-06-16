<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="\css\style.css">
    <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon">
</head>
<body>
    <div>
        user details here
    </div>
    <div>
        addresses here
        <a href="address_add.php">add address</a>
        <a href="addresses.php">see addresses</a>
    </div>
</body>
</html> -->

<?php

include_once 'dbconfig.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//fetching orders
$orders = $con->query("SELECT * FROM ordertb WHERE custid=" . $_SESSION['userId']);
if ($orders) {
    $orders_cnt = $orders->num_rows;
    // if($orders_cnt>0)
    // {

    // }
    // else{

    // }
} else {
    die("error while fetching orders!");
}

if (isset($_GET['edtbtn'])) {
    if ($_GET['edtbtn'] == 'EDIT') {
        $username = $_GET['txtname'];
        $email = $_GET['txtemail'];
        $contact = $_GET['txtcontact'];

        $conExp = "/^[6-9]\d{9}$/";

        if ($username == '' || $email == '' || $contact == '') {
            $err_msg = "All fields are required!";
        } else {
            if (preg_match($conExp, $contact)) {
                $qry = "UPDATE customertb set name='$username',email='$email',contactno='$contact' WHERE custid=" . $_SESSION['userId'];
                $res = $con->query($qry);
                if ($res) {
                    if ($con->affected_rows == 1) {
                        header("Location:account.php");
                    } else {
                        header("Location:account.php");
                    }
                } else {
                    die("error while updating details!");
                }
            } else {
                $err_msg = "Please enter valid 10 digit mobile number!";
            }
        }
    }
}
$user_det = $con->query("SELECT * FROM customertb where custid=" . $_SESSION['userId']);
if ($user_det) {
    if ($user_det->num_rows == 1) {
        $user = $user_det->fetch_array();
    } else {
        die("no user found!");
    }
} else {
    die("something went wrong!!");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon">

</head>

<body>
    <?php include_once 'header.php'; ?>

    <!-- User Information -->
    <div class="flex">
        <div>
            <h1 class="ml-20 mt-5 text-3xl font-bold"><?php if (isset($user['name'])) echo $user['name']; ?></h1>
            <h1 class="ml-20 text-xl font-semibold"><?php if (isset($user['email'])) echo $user['email']; ?></h1>
            <h1 class="ml-20 text-lg font-semibold"><?php if (isset($user['contactno'])) echo "+91 " . $user['contactno']; ?></h1>
        </div>
        <button id="edit_btn" class="px-5 py-3 h-fit absolute right-32 top-36 border-2 rounded-lg hover:bg-orange-300">Edit Profile</button>
    </div>

    <div class="flex">
        <aside class="w-64" aria-label="Sidebar">
            <div class="ml-20 mt-5 overflow-y-auto absolute z-10 py-4 pr-10 pl-3 bg-gray-200 rounded h-96">
                <ul class="space-y-2">
                    <li>
                        <a href="account.php" class="flex items-center p-2 pr-10 text-base font-normal text-gray-900 rounded-lg bg-gray-100">
                            <span class="ml-3">Order History</span>
                        </a>
                    </li>

                    <li>
                        <a href="addresses.php" class="flex items-center p-2 text-base cursor-pointer font-normal text-gray-900 rounded-lg hover:bg-gray-100">
                            <span class="flex-1 ml-3">Address</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!--Order History-->


        <?php
        if ($orders_cnt > 0) {
        ?>
            <div class="flex-col w-fit -ml-52 mt-10 relative">
                <?php
                while ($order = $orders->fetch_array()) {
                    
                    $order_items = $con->query("SELECT * FROM orderitemstb WHERE orderid=" . $order['orderid']);
                ?>
                    <div class="mb-8">
                        <div class="w-fit ml-72 mb-4">
                            <h4 class="font-xl mt-2">Order Id: <?php echo $order['orderid']; ?></h4>
                            <h1 class="font-xl mt-2">ordered on <?php echo $order['datetime']; ?></h1>
                            <h1 class="font-xl mt-2">Total Items: <?php echo $order_items->num_rows; ?></h1>
                        </div>
                        <?php
                        if ($order_items) {
                            if ($order_items->num_rows > 0) {
                                while ($item = $order_items->fetch_array()) {
                                    $pizzaqry = $con->query("SELECT * FROM pizzatb WHERE pizzaid=" . $item['pizzaid']);
                                    $pizza = $pizzaqry->fetch_array();
                                    $priceqry = $con->query("SELECT * FROM pricetb where priceid=" . $item['priceid']);
                                    $price = $priceqry->fetch_array();
                                    // $amount=$order['amount'];
                        ?>
                                    <div class="flex justify-around items-center w-fit mb-4">
                                        <img src="<?php echo $pizza['image_url']; ?>" class="w-32 ml-72">
                                        <div class="mr-64 ml-8">
                                            <h1 class="font-bold text-lg w-52"><?php echo $pizza['name']; ?></h1>
                                            <span class="text-sm"><?php echo $price['size']; ?></span>

                                        </div>
                                        <span class="pr-60 font-bold text-lg"><?php echo $item['quantity']; ?> pcs</span>
                                        <span class="mr-52 font-bold text-lg"><?php echo $item['amount']; ?> ₹</span>
                                    </div>
                                    <?php
                                }?>
                                <div class="w-fit ml-72 mb-4">
                                <h4 class="font-xl mt-2">Order Status: <?php echo $order['order_status']; ?></h4>
                                <h1 class="font-xl mt-2">Payment Status: <?php echo $order['payment_status']; ?></h1>
                                <?php
                                if($order['payment_status']==='Pending')
                                {
                                    ?>
                                    <button class="font-xl mt-2 bg-orange-400 p-2 rounded-md"> <a href="pending_clear.php?oid=<?php echo $order['orderid'];?>">Pay Now</a></button>
                                    <?php
                                }
                                ?>
                                </div>
                                    <?php
                                    } else {
                                        //no order for orderid
                                    }
                                } else {
                                    die("order items not found!!");
                                }
                                        ?>
                    </div>
                    <hr>
                <?php
                }
            } else {
                ?>
                <div class="no-orders flex-col m-auto w-fit justify-center">
                    <p class="mb-8">No Orders Yet!</p>
                    <a href="menu.php">
                        <button class="text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-40 mb-8">
                            Order Now</button>
                    </a>

                </div>
            <?php
            }
            ?>
            </div>

            <div id="edit_frm" class="w-64 absolute right-20 top-56 border-2 p-5 bg-white <?php if (isset($err_msg)) {
                                                                                        if ($err_msg != '') {
                                                                                        } else {
                                                                                            echo "hidden";
                                                                                        }
                                                                                    } else {
                                                                                        echo "hidden";
                                                                                    }
                                                                                    ?>">
                <form  action="" method="GET">
                    <div class="flex flex-col mb-6">
                        <label for="name" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Name:
                        </label>
                        <div class="relative">
                            <input id="name" type="text" name="txtname" value="<?php if (isset($user['name'])) echo $user['name']; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter New Name" />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="email" class="mb-1 text-xs sm:text-sm text-gray-600">
                            E-Mail Address:
                        </label>
                        <div class="relative">
                            <input id="email" type="email" name="txtemail" value="<?php if (isset($user['email'])) echo $user['email']; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter New E-Mail Address" />
                        </div>
                    </div>

                    <div class="flex flex-col mb-6">
                        <label for="Contact" class="mb-1 text-xs sm:text-sm text-gray-600">
                            Contact No:
                        </label>
                        <div class="relative">
                            <input id="contact" type="number" name="txtcontact" value="<?php if (isset($user['contactno'])) echo $user['contactno']; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" placeholder="Enter New Contact No" />
                        </div>
                    </div>

                    <?php
                    if (isset($err_msg) != '')
                        echo "<div class='mb-2'><span class='uppercase text-sm font-bold text-red-500 mb-2'>", $err_msg, "</span></div>";
                    ?>

                    <div class="flex w-full">
                        <input type="submit" name="edtbtn" class="flex items-center justify-center text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-full" value="EDIT">
                    </div>
                </form>
            </div>

            <script>
                const edit_btn = document.querySelector('#edit_btn');
                const edit_menu = document.querySelector('#edit_frm');

                edit_btn.addEventListener('click', () => {
                    edit_menu.classList.toggle('hidden');
                });
            </script>

</body>

</html>