<?php
require_once 'dbconfig.php';
// session_start();
if (isset($_GET['sendMsgBtn']) == 'Send Message') {
  $name = $_GET['txtname'];
  $email = $_GET['txtemail'];
  $msg = $_GET['txtmsg'];
  $success_msg = '';
  $fail_msg = '';
  $ins_qry = "INSERT INTO contactustb(id,name,email,message) VALUES(null,'$name','$email','$msg')";

  if ($name == '' || $email == '' || $msg == '') {
    $fail_msg = 'All The Fields Are Required!';
  } else {
    $resp = $con->query($ins_qry);
    if (!$resp) {
      die("error while inserting record!");
    } else {
      if ($con->affected_rows == 1) {
        $_GET = array();
        $success_msg = 'your message recieved successfully.';
        header("Location:index.php");
      } else {
        $fail_msg = 'message not recieved successfully!';
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
  <title>PizzaWala</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon">
</head>

<body>  
    <div class="content" id="content">
    <?php include_once 'header.php' ?>
    
    <!-- TEXT AND IMAGE IN HOME PAGE -->
       <div class="flex">

      <div>
        <h1 class="text-8xl ml-24 mt-40 font-bold">Craving for Pizzas?</h1>
        <button class="bg-orange-400 rounded-xl text-xl font-bold h-16 w-40 ml-24 p-5 mt-4 inline-flex items-center hover:scale-110"><a href="menu.php">Order Now</a></button>
      </div>
      <img  src="./images/10219-removebg.png" class="lg:w-2/4" >
    </div>

    <!--WHY CHOOSE US?-->
      <div class="bg-orange-400">
      <p class="text-6xl text-center p-10 text-white font-bold">Why Choose Us?</p>
      <div class="md:flex justify-around">
        <div class="text-white font-bold ml-40">
          <img src="./images/smartphone.png" class="w-2/4 ml-8 hover:scale-110" >
          <p class="text-3xl p-4">Easy To Order</p>
        </div >
  
        <div class="text-white font-bold ml-40">
          <img src="./images/delivery.png" class="w-2/4 hover:scale-110" >
          <p class="text-3xl p-4">Fast Delivery</p>
        </div>
  
        <div class="text-white font-bold ml-40">
          <img src="./images/dinner.png" class="w-2/4 hover:scale-110" >
          <p class="text-3xl p-4 pb-10">Best Quality</p>
        </div>
    </div>

      <!-- ABOUT US -->
      <div class="bg-white" id="about">
      <p class="text-6xl text-center p-10 font-bold">About Us</p>
      <div class="md:flex">
        <img src="./images/guy.png" class="w-80 -mt-10">
        <p class="lg:text-4xl p-10 text-justify">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatem quos, odit, incidunt quo repudiandae voluptates ratione veritatis mollitia quas in pariatur libero minima dolorem. Id veritatis nisi voluptas molestiae voluptatibus quas corrupti omnis libero. Aspernatur debitis magnam ipsum exercitationem sit est quae maiores saepe asperiores similique architecto sint magni</p>
      </div>
    </div>
      <!-- Contact Us -->

      <div class="" id="contact">
        <p class="text-6xl text-center p-10 text-white font-bold">Contact Us</p>
        <div class="flex justify-around">
          <div class="left">
            <img src="./images/cw.png" alt="" srcset="">
          </div>

          <div class="right w-1/3">
            <form action="" method="get">
              <div>
                <span class="uppercase text-sm text-white font-bold">Full Name</span>
                <input name="txtname" class="w-full text-gray-900 mt-2 p-3 rounded-lg outline-0" type="text" placeholder="" required>
              </div>
              <div class="mt-8">
                <span class="uppercase text-sm text-white font-bold">Email</span>
                <input name="txtemail" class="w-full text-gray-900 mt-2 p-3 rounded-lg outline-0" type="email" required>
              </div>
              <div class="mt-8">
                <span class="uppercase text-sm text-white font-bold">Message</span>
                <textarea name="txtmsg" class="w-full h-32 text-gray-900 mt-2 p-3 rounded-lg outline-0" required></textarea>
              </div>
              <?php
              if (isset($err_msg) != '')
                echo "<span class='uppercase text-sm font-bold text-red-500'>", $err_msg, "</span><br>";
              else if (isset($success_msg) != '')
                echo "<span class='uppercase text-sm font-bold text-green-400'>", $success_msg, "</span><br>";
              ?>
              <div class="mt-8">
                <input class="uppercase text-sm font-bold tracking-wide bg-indigo-500 text-gray-100 p-3 rounded-lg w-full" name="sendMsgBtn" type="submit" value="Send Message">
              </div>
            </form>
          </div>
        </div>
      </div>

      <div style="display: none;" class="loaderWrapper">
        <div id="loader">
          <img src="./images/327.gif">
        </div>
      </div>
      
</body>
</html>

