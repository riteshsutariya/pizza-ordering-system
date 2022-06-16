<?php
require_once '../dbconfig.php';


function send_email($mail_to, $name, $message)
{
  $to = $mail_to;
  $header = "MIME-Version: 1.0" . "\r\n";
  $header .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
  $subject = "Order Canceled";

  $message = "<h2>Hello $name</h2> <p>Your Order Has Been Canceled.</p> $message";
  if (mail($to, $subject, $message, $header)) {
    return true;
  } else {
    die("mail not sent!!");
  }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  //handling form submit
  if (isset($_GET['btndelete']) == 'Confirm Delete') { {

      //after deleting uploaded image, deleting record from db
      $userQry = $con->query("SELECT * FROM customertb WHERE custid=(SELECT custid FROM ordertb WHERE orderid=" . $_GET['id'] . ")");
      if ($userQry) {
        if ($userQry->num_rows == 1) {
          $r = $userQry->fetch_array();
          $uname = $r['name'];
          $mail_to = $r['email'];
        }
      } else {
        die("Something Went Wrong-userQry");
      }
      $del_qry = "DELETE FROM ordertb WHERE orderid=" . $_GET['id'];
      $res = $con->query($del_qry);
      if ($res) {
        if ($con->affected_rows == 1) {
          $message = "<p>This is to inform you that your order with order id " . $_GET['id'] . " has been canceled due to some reasons.</p>";
          send_email($mail_to, $uname, $message);
          header("Location:orderlist.php");
        } else {
          die("order not deleted successfully!");
        }
      } else {
        die("error occured!");
      }
      // die($del_qry);


    }
  }

  if (isset($_GET['id']) == true) {
    global $pid;
    $oid = $_GET['id'];
    $record = $con->query("SELECT * FROM ordertb where orderid=$oid");

    if ($record) {
      if ($record->num_rows == 1) {
        $order = $record->fetch_array();

        $itemqry = $con->query("SELECT * FROM orderitemstb WHERE orderid=" . $order['orderid']);
        if ($itemqry->num_rows > 0) {
          $item_cnt = $itemqry->num_rows;
        }
        else{
          $item_cnt=0;
        }
        // $precord=$con->query("SELECT * FROM pricetb WHERE pizzaid=$pid");
        // $prices=[];
        // while($price=$precord->fetch_array())
        // {
        //     array_push($prices,(int)$price['price']);
        // }
      } else {
        die("pizza not found!");
      }
    } else {
      die("Error occurred!");
    }
  } else {
    die("pizza id not provided!!");
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Delete Order</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon" />
</head>

<body>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-300">
    <div class="flex flex-col bg-white px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full mt-14 max-w-xl">
      <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800 mb-8">
        Delete Order
      </div>
      <div class='mb-6'>
        <span class="uppercase text-sm font-bold">Order Id: <?php if (isset($order['orderid'])) echo $order['orderid']; ?></span>
      </div>

      <div class='mb-6'>
        <span class="uppercase text-sm font-bold">User Id: <?php if (isset($order['custid'])) echo $order['custid']; ?></span><br />
      </div>

      <div class='mb-6'>
        <?php
        $user = $con->query("SELECT name from customertb WHERE custid=" . $order['custid']);
        if ($user) {
          if ($user->num_rows == 1) {
            $username = $user->fetch_array()['name'];
          } else {
            header("Location:index.php");
          }
        } else {
          die("something went wrong!!");
        }
        ?>
        <span class="uppercase text-sm font-bold">User Name: <?php if (isset($username)) echo $username; ?></span><br />
      </div>

      <div class="mb-6">Items:</div>

      <?php
      if($item_cnt>0)
      {
        while ($item = $itemqry->fetch_array()) {
          $pqry = $con->query("SELECT name,image_url from pizzatb WHERE pizzaid =" . $item['pizzaid']);
          if ($pqry) {
            $pizza = $pqry->fetch_array();
          }
          $prqry = $con->query("SELECT price from pricetb WHERE priceid IN(SELECT priceid FROM orderitemstb WHERE priceid=" . $item['priceid'] . ")");
          if ($prqry) {
            $price = $prqry->fetch_array();
          }
        ?>
          <div class="max-w-sm rounded overflow-hidden shadow-lg mb-4 mx-auto">
            <img class="w-full" src="<?php echo "../" . $pizza['image_url']; ?>" alt="<?php echo $pizza['image_url']; ?>">
            <div class="px-6 py-4">
              <div class="font-bold text-xl mb-2">Name: <?php echo $pizza['name']; ?></div>
              <p class="text-gray-700 text-base mb-4">
                Qty: <?php echo $item['quantity']; ?>
              </p>
              <p class="text-gray-700 text-base mb-4">
                Amount: <?php echo $item['amount']; ?>
              </p>
            </div>
          </div>
        <?php
      }
    }
    else{
      ?>
      <div class="max-w-sm rounded overflow-hidden shadow-lg mb-4 mx-auto text-orange-400">
        No Items Found For This Order!
      </div>
      <?php
    }
      ?>
      
      <div class='mb-6'>
        <span class="uppercase text-sm font-bold">DateTime: <?php if (isset($order['datetime'])) echo $order['datetime']; ?></span><br />
      </div>
      <?php
      if (isset($err_msg) != '')
        echo "<span class='uppercase text-sm font-bold text-red-500'>", $err_msg, "</span><br>";
      ?>

      <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="GET">
        <div>
          <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
          <input type="submit" name="btndelete" class="bg-orange-600 px-3 py-3 text-white mt-1 rounded-lg hover:scale-105 w-full cursor-pointer" value="Confirm Delete">

          <div class="bg-orange-600 px-3 py-3 mt-3 rounded-lg hover:scale-105 w-full text-center">
            <a class="text-white" href="orderlist.php">Don't Delete</a>
          </div>
        </div>
      </form>
    </div>
    <!-- </div> -->
  </div>
  </div>
</body>

<script>
  function doNothing(event) {
    event.preventDefault();
  }

  document.getElementById("prevent").addEventListener("click", function(e) {
    e.preventDefault();
  });
</script>

</html>