<?php
include_once 'dbconfig.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    $item_count = 0;
$cart_res = $con->query("SELECT * FROM cartitemstb WHERE cartid=(
  SELECT cartid FROM carttb WHERE custid=" . $_SESSION['userId'] . ")");

if ($cart_res) {
    if ($cart_res->num_rows > 0) {
        $item_count = $cart_res->num_rows;
    }
} else {
    die("error while fetching cart!");
}

//getting addresses
if (isset($_SESSION['userId'])) {
} else {
    session_start();
}
$addresses = $con->query("SELECT * FROM addresstb WHERE custid=" . $_SESSION['userId']);
if ($addresses) {
    $total_addresses = $addresses->num_rows;
} else {
    die("Error Occured while fetching addresses!");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Review</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon">
</head>

<body>
    <form action="Paytm_PHP_Sample-master\PaytmKit\pgRedirect.php" method="post">
    <input type="hidden" id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?php echo $_SESSION['userId'];?>">
    <input type="hidden" id="CHANNEL_ID" tabindex="4" maxlength="12"
						size="12" name="CHANNEL_ID" autocomplete="off" value="WEB">  
    <?php include_once 'header.php' ?>
        <?php
        if ($item_count > 0) {
        ?>
            <h1 class="mx-60 my-5 text-2xl font-bold border-b-2">Order Summary</h1>
            <div>
                <?php
                $total_amount = 0;
                while ($item = $cart_res->fetch_array()) {

                    //we'll get pizzaid,quantity,priceid
                    $pizzaqry = $con->query("SELECT * FROM pizzatb WHERE pizzaid=" . $item['pizzaid']);
                    $pizza = $pizzaqry->fetch_array();
                    $priceqry = $con->query("SELECT * FROM pricetb where priceid=" . $item['price_id']);
                    $price = $priceqry->fetch_array();

                    $ind_amt = $price['price'] * $item['quantity'];
                    $total_amount += $ind_amt;
                ?>
                    <div class="flex justify-around items-center mt-10">
                        <img src="<?php echo $pizza['image_url']; ?>" class="w-32 ml-48">
                        <div class="mr-64">
                            <h1 class="font-bold text-lg w-52"><?php echo $pizza['name']; ?></h1>
                            <span class="text-sm"><?php echo $price['size']; ?></span>
                        </div>
                        <span class="pr-60 font-bold text-lg w-32"><?php echo $item['quantity']; ?> pcs</span>
                        <span class="mr-52 font-bold text-lg"><?php echo $ind_amt; ?> ₹</span>
                    </div>
                <?php
                }
                ?>
                <hr class="mx-60 border-b-2 mt-5">
                    <div class="text-center">
                        <h1 class="absolute right-60 text-xl font-bold">Total Amount:<span class="text-3xl pt-4 font-bold text-orange-500"> <?php echo $total_amount; ?>₹</span></h1>
                        <input type="hidden" name="TXN_AMOUNT" value="<?php echo $total_amount; ?>">
                    </div>
            </div>

            <?php
            if ($total_addresses > 0) {
            ?>
                <h1 class="mx-60 my-5 text-2xl font-bold border-b-2 mt-24">Select Address</h1>

                <div class="grid grid-cols-2 mx-60 mt-10">
                    <?php
                    $cnt = 0;
                    while ($addr = $addresses->fetch_array()) {
                        $cnt++;
                    ?>
                        <div class="mt-5 bg-white shadow rounded-xl px-12 mr-8">
                            <div class="flex">
                                <div class="flex-1 py-5 pl-5 overflow-hidden">
                                    <ul>
                                        <!-- <input id="address-1" type="radio" name="addresses" value="first" class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600" checked> -->

                                        <input type="radio" name="addresses" value="<?php if (isset($addr['addressid'])) echo $addr['addressid']; ?>" class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600" <?php if ($cnt == 1) echo "checked"; ?>>
                                        <li class="text-xl text-gray-600 uppercase ">Address <?php if (isset($cnt)) echo $cnt; ?></li>
                                        <li><?php if (isset($addr['houseno'])) echo $addr['houseno']; ?></li>
                                        <li><?php if (isset($addr['apartment_street'])) echo $addr['apartment_street']; ?></li>
                                        <li><?php if (isset($addr['area'])) echo $addr['area']; ?></li>
                                        <li><?php if (isset($addr['pincode'])) echo $addr['pincode']; ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

                <input type="hidden" id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off" value="<?php echo  "ORDS" . rand(10000, 99999999) ?>">
                <input type="hidden" id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail">
                <!-- <input type="hidden" name="ORDER_ID" value="OBKLDFK43FD"> -->


                <div class="btn mb-36">
                    <input type="submit" class="cursor-pointer bg-orange-600 w-fit px-6 py-2 text-white mt-8 rounded-full hover:scale-105 absolute right-60" name="orderbtn" value="Make Payment">
                    <!-- <button class="bg-orange-600 w-fit px-6 py-2 text-white mt-8 rounded-full hover:scale-105 absolute right-60">ORDER NOW</button> -->
                </div>

    </form>
<?php
            } else {
?>
    <div class="addressbtn ml-52 mt-8">
        <a id="redirect_link" href="address_add.php?redirect=review" class="text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 px-6 w-40 mb-8">
            Add Address
        </a>
    </div>

<?php
            }
?>
<?php
        } else {
?>
    <div class="flex justify-center flex-col mt-16">
        <div class="img flex justify-center mb-16">
            <img style="object-fit: none;" class="sm:scale-100" src="./images/empty.png" alt="">
        </div>
        <div class="flex justify-center">
            <button class="text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-40 mb-8">
                <a href="menu.php">Buy Now</a>
            </button>
        </div>
    </div>

<?php
        }

?>
<!-- <p class="h-52"></p> -->
</body>

<script>
    document.getElementById("redirect_link").onclick(() => {
        window.location.replace("adress_add.php");
    });
</script>

</html>