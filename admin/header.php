<?php
session_start();
if (isset($_SESSION['isAdminLoggedIn']) == "1") {
    //    echo "admin loggedin.";
    // var_dump($_SESSION);
} else {
    // echo "admin not logged in!";
    header("Location:login.php");
}
?>

<nav class="flex justify-between bg-#F6E7D0">
    <a href="index.php"> <img src="../images/PizzaWala.png" class="mt-2 ml-20 w-48 pt-3 hover:scale-110"></a>
    <ul class="px-28 py-4 flex space-x-11 justify-end">
        <div class="flex lg:flex-grow items-center" id="example-navbar-info">
            <ul class="flex flex-col lg:flex-row list-none ml-auto">
                <li>
                    <a class="px-3 py-1 flex items-center text-sm uppercase font-bold leading-snug text-black hover:underline" href="index.php">
                        user list
                    </a>
                </li>
                <li>
                    <a class="px-3 py-1 flex items-center text-sm uppercase font-bold leading-snug text-black hover:underline" href="pizzalist.php">
                        pizza list
                    </a>
                </li>
                <li>
                    <a class="px-3 py-1 flex items-center text-sm uppercase font-bold leading-snug text-black hover:underline" href="addpizza.php">
                        add pizza
                    </a>
                </li>
                <li>
                    <a class="px-3 py-1 flex items-center text-sm uppercase font-bold leading-snug text-black hover:underline" href="orderlist.php">
                        order list
                    </a>
                </li>
                <li>
                    <a class="px-3 pr-2 py-1 flex items-center text-sm uppercase font-bold leading-snug text-black hover:underline" href="messages.php">
                        messages
                    </a>
                </li>
            </ul>
            <ul class="px-28 py-4 flex space-x-6 justify-end">
                <li class="cursor-pointer font-bold text-xl hover:scale-110"><?php echo $_SESSION['adminName']; ?></li>
                <li class="cursor-pointer font-bold text-xl hover:scale-110"><a href="logout.php">log out</a></li>
            </ul>
        </div>


    </ul>
</nav>