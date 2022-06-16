<!-- NAVBAR -->
<?php
include_once 'dbconfig.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

?>
<nav class="flex justify-between bg-#F6E7D0 mb-2">
  <a href="index.php"> <img src="./images/PizzaWala.png" class="ml-20 mt-6 w-48 pt-3 hover:scale-110"></a>
  <ul class="px-28 py-4 flex space-x-11 justify-end">
    <li class="cursor-pointer mt-8 font-bold text-xl hover:scale-110"><a href="menu.php">Menu</a></li>
    <li class="cursor-pointer mt-8 font-bold text-xl hover:scale-110"><a href="index.php#about">About Us</a></li>
    <li class="cursor-pointer mt-8 font-bold text-xl hover:scale-110"><a href="index.php#contact">Contact Us</a></li>
    <?php if (isset($_SESSION['cartId'])) {
      $cart_cnt_h = $con->query("SELECT COUNT(*) AS icnt FROM cartitemstb WHERE cartid=(SELECT cartid FROM carttb WHERE custid=" . $_SESSION['userId'] . ")");
      if ($cart_cnt_h) {
        if($cart_cnt_h->num_rows === 1) {
          $row = $cart_cnt_h->fetch_array();
          $cart_items = $row['icnt'];
        } else {
          $cart_items = 0;
        }
      } else {
        $cart_items = 0;
        die("something went wrong!");
      }
    }
    ?>
    <?php
    if (isset($_SESSION['isLoggedIn'])) {
    ?>
      <li class="cursor-pointer mt-8 font-bold text-xl hover:scale-110"><a href="cart.php">Cart(<?php if(isset($cart_items)){echo $cart_items;}else{echo "0";} ?>)</a></li>
      <li class='cursor-pointer mt-8 font-bold text-xl hover:scale-110'><a href='logout.php'>Log Out</a></li>
      <li class='cursor-pointer mt-8 font-bold text-xl hover:scale-110'><a href='account.php'> <span class="flex"><img class="-mt-2 mr-1" src="./images/user_icon.svg" alt=""><?php echo strtok($_SESSION['userName'], " "); ?></span></a></li>
    <?php
    } else {
      echo "<li class='cursor-pointer mt-8 font-bold text-xl hover:scale-110'><a href='login.php'>Log In</a></li>";
    }
    ?>

  </ul>
</nav>