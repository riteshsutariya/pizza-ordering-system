<?php
require_once 'dbconfig.php';
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
    <title>User(Address)</title>

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
            <h1 class="lg:ml-20 mt-5 text-3xl font-bold"><?php if (isset($user['name'])) echo $user['name']; ?></h1>
            <h1 class="lg:ml-20 text-xl font-semibold"><?php if (isset($user['email'])) echo $user['email']; ?></h1>
            <h1 class="lg:ml-20 text-lg font-semibold"><?php if (isset($user['contactno'])) echo "+91 " . $user['contactno']; ?></h1>
        </div>
        <!--    <button id="edit_btn" class="px-5 py-3 h-fit absolute right-32 top-36 border-2 rounded-lg hover:bg-orange-300">Edit Profile</button> -->
    </div>

    <div class="flex">
        <aside class="w-64" aria-label="Sidebar">
            <div class="lg:ml-20 mt-5 overflow-y-auto absolute z-10 py-4 pr-10 pl-3 bg-gray-200 rounded h-96">
                <ul class="space-y-2">
                    <li>
                        <a href="account.php" class="flex items-center p-2 pr-10 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100">
                            <span class="ml-3">Order History</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="flex items-center p-2 text-base cursor-pointer font-normal text-gray-900 rounded-lg bg-gray-100">
                            <span class="flex-1 ml-3">Address</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>


        <?php
        if ($total_addresses > 0) {
        ?>
            <div class="grid grid-cols-2 mt-5">
                <?php
                $cnt = 0;
                while ($address = $addresses->fetch_array()) {
                    $cnt++;
                ?>

                    <div class="lg:ml-36 mt-5 bg-white shadow cursor-pointer rounded-xl px-12">
                        <div class="flex">
                            <div class="flex-1 py-5 pl-5 overflow-hidden">
                                <ul>
                                    <li class="text-xs text-gray-600 uppercase ">address <?php if (isset($cnt)) echo $cnt; ?></li>
                                    <li><?php if (isset($address['houseno'])) echo $address['houseno']; ?></li>
                                    <li><?php if (isset($address['apartment_street'])) echo $address['apartment_street']; ?></li>
                                    <li><?php if (isset($address['area'])) echo $address['area']; ?></li>
                                    <li><?php if (isset($address['pincode'])) echo $address['pincode']; ?></li>
                                </ul>
                            </div>
                            <div class="flex pt-2.5 pl-1">
                                <div class="btnedt">
                                    <button type="button" class="px-2 py-2 font-medium tracking-wide text-black capitalize transition duration-300 ease-in-out transform rounded-xl hover:bg-gray-300 focus:outline-none active:scale-95">
                                        <a href="edit_address.php?id=<?php echo $address['addressid']; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none"></path>
                                                <path d="M5 18.08V19h.92l9.06-9.06-.92-.92z" opacity=".3"></path>
                                                <path d="M20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.2-.2-.45-.29-.71-.29s-.51.1-.7.29l-1.83 1.83 3.75 3.75 1.83-1.83zM3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM5.92 19H5v-.92l9.06-9.06.92.92L5.92 19z"></path>
                                            </svg>
                                        </a>
                                    </button>
                                </div>

                                <div class="btndel">
                                    <button type="button" class="px-2 py-2 font-medium tracking-wide text-black capitalize transition duration-300 ease-in-out transform rounded-xl hover:bg-gray-300 focus:outline-none active:scale-95">

                                        <a href="delete_address.php?id=<?php echo $address['addressid']; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink" height="24px" version="1.1" viewBox="0 0 20 20" width="24px">
                                                <title />
                                                <desc />
                                                <defs />
                                                <g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1">
                                                    <g fill="#000000" id="Core" transform="translate(-2.000000, -380.000000)">
                                                        <g id="remove-circle-outline" transform="translate(2.000000, 380.000000)">
                                                            <path d="M5,9 L5,11 L15,11 L15,9 L5,9 L5,9 Z M10,0 C4.5,0 0,4.5 0,10 C0,15.5 4.5,20 10,20 C15.5,20 20,15.5 20,10 C20,4.5 15.5,0 10,0 L10,0 Z M10,18 C5.6,18 2,14.4 2,10 C2,5.6 5.6,2 10,2 C14.4,2 18,5.6 18,10 C18,14.4 14.4,18 10,18 L10,18 Z" id="Shape" />
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>

                                        </a>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>

            <button class="text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-40 h-full mb-8">
                <a href="address_add.php">Add Address</a>
            </button>
        <?php
        } else {
            echo "<p class='ml-36'>No Addresses Found! <a href='address_add.php'>click <span class='underline'>here</span></a> to add</p>";
        }
        ?>
    </div>
    </div>
</body>

</html>