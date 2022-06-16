<?php
include_once '../dbconfig.php';

if (isset($_GET['searchbtn'])) {
  if ($_GET['searchbtn'] == 'Search') {
    $start_date = $_GET['dateFrom'];
    $end_date = $_GET['dateTo'];
    if ($start_date !== '' && $end_date !== '') {
      $s_dateObj = date_create($start_date." 0:0:0")->format('Y-m-d H:i:s');
      $e_dateObj = date_create($end_date." 24:0:0")->format('Y-m-d H:i:s');

      $order_qry = "SELECT * FROM ordertb WHERE datetime BETWEEN '$s_dateObj' AND '$e_dateObj' order by orderid";
      $orders = $con->query($order_qry);
      if ($orders) {
        $orders_cnt = $orders->num_rows;
      } else {
        echo "error while fetching order-admin~";
      }
    } else {
      $err_msg = 'Please Provide Both Dates!';
    }
  }
} else {
  $orders = $con->query("SELECT * FROM ordertb order by orderid desc");
  if ($orders) {
    $orders_cnt = $orders->num_rows;
  } else {
    echo "error while fetching order-admin~";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin(Order List)</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>

<body>
  <?php require_once 'header.php' ?>
  <span class="flex font-bold text-xl justify-center ">
    ORDERS LIST
  </span>

  <div class="search-ele">
    <form class="ml-4" action="" method="get">
      <div class="flex">
        <div class="from flex">
          <h2 class="mr-4">From</h2>
          <input type="date" name="dateFrom" max="01-01-2050" id="mindate" class="bg-gray-50 border border-gray-300 pl-10 p-2.5 text-gray-900 sm:text-sm rounded-lg" required>
        </div>
        <div class="to flex">

          <h2 class="mx-4">To</h2>
          <input type="date" name="dateTo" max="01-01-2050" id="maxdate" class="bg-gray-50 border border-gray-300 pl-10 p-2.5 text-gray-900 sm:text-sm rounded-lg" required>
        </div>

        <div style="text-align:center;" class="cursor-pointer bg-orange-500 mx-4 p-2.5 w-24 rounded-lg border">
          <input type="submit" class="cursor-pointer text-white" value="Search" name="searchbtn">
        </div>

      </div>

      <?php
      if (isset($err_msg) != '')
        echo "<div class='uppercase text-sm mt-4 font-bold text-red-500'>", $err_msg, "</div><br>";
      ?>
      <!-- <input datepicker type="date" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date"> -->

      <!-- <div inline-datepicker data-date="02/25/2022"></div> -->
    </form>

  </div>

  <div class="flex flex-col px-2 md:w-screen">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-4 inline-block min-w-full sm:px-6 lg:px-8">
        <div class="overflow-hidden">
          <?php
          if ($orders_cnt > 0) {
          ?>
            <table class="min-w-full text-center">
              <thead class="border-2 border-white bg-orange-500">

                <tr>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Order Id
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    E-Mail
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    User Id
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Amount
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Date-Time
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Order Status
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Payment Status
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody>

                <?php
                while ($order = $orders->fetch_array()) {
                  $email_qry = $con->query("SELECT email from customertb WHERE custid=" . $order['custid']);
                  $email = $email_qry->fetch_array()['email'];

                  //amount of order
                  $amount_qry=$con->query("SELECT amount from paymenttb where orderid=".$order['orderid']);
                  if($amount_qry->num_rows==1)
                  {
                    $amount=$amount_qry->fetch_array()['amount'];
                  }
                  else{
                    $amount="NA";
                  }
                ?>
                  <tr class="bg-white border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $order['orderid']; ?></td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <?php echo $email; ?>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <?php echo $order['custid']; ?>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <?php echo $amount ?>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <?php echo $order['datetime']; ?>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <span class="<?php if($order['order_status']=='Delivered') echo "bg-green-500";
                       if($order['order_status']=='Order Placed') echo "bg-orange-400";
                       if($order['order_status']=="Can't Delivered") echo "bg-red-500"; ?> p-2 text-white">
                       <?php echo $order['order_status']; ?>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <span class="<?php if($order['payment_status']=='Success') echo "bg-green-500";
                       if($order['payment_status']=='Pending') echo "bg-orange-500";
                       if($order['payment_status']=='Failed') echo "bg-red-500"; ?> p-2 text-white">
                      <?php echo $order['payment_status']; ?>
                    </span>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap space-x-2">
                      <button class="rounded inline-flex items-center hover:scale-110">
                        <a href="deleteorder.php?id=<?php echo $order['orderid']; ?>">
                          <img src="../images/deleteicon.jpeg" class="h-5 w-5">
                        </a>
                      </button>
                      <button class="rounded inline-flex items-center hover:scale-110">
                        <a href="updateorder.php?id=<?php echo $order['orderid']; ?>">
                          <img src="../images/updateicon.jpeg" class="h-5 w-5">
                        </a>
                      </button>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
            <?php
          } else {
            if (isset($_GET['dateFrom']) && isset($_GET['dateTo'])) {
            ?>
              <p>No Orders Found Between <?php $sDate=new DateTime($_GET['dateFrom']);$eDate=new DateTime($_GET['dateTo']); echo date_format($sDate,"F j, Y")." To ".date_format($eDate,"F j, Y"); ?></p>
            <?php
            } else {
            ?>
              <p>No Orders Found!</p>
          <?php
            }
          }
          ?>

        </div>
      </div>
    </div>
  </div>
</body>

<script>
  const maxdt = document.getElementById("maxdate");
  const mindt = document.getElementById("mindate");


  //NOTE: getDate() function returns months from index 0, so if decemnber is month then it will return 11 same as 0 for january.

  //today
  const today = new Date();

  //today-90 days
  const tdate = new Date();
  tdate.setDate(tdate.getDate() - 90);

  //mindate for to
  const mdate = new Date();
  mdate.setDate(mdate.getDate() - 89);

  let tmday = mdate.getDate();
  let tmmonth = mdate.getMonth() + 1;
  let tmyear = mdate.getFullYear();
  if (tmmonth < 10) {
    tmmonth = '0' + tmmonth;
  }
  if (tmday < 10) {
    tmday = '0' + tmday;
  }

  //  console.log(tdate);
  //  console.log();
  let day = today.getDate();
  let month = today.getMonth() + 1;
  let year = today.getFullYear();

  if (month < 10) {
    month = '0' + month;
  }
  if (day < 10) {
    day = '0' + day;
  }

  //before 3 months
  let mday = tdate.getDate();
  let mmonth = tdate.getMonth() + 1;
  let myear = tdate.getFullYear();
  if (mmonth < 10) {
    mmonth = '0' + mmonth;
  }
  if (mday < 10) {
    mday = '0' + mday;
  }

  console.log(`${year}-${day}-${month}`);
  console.log(`${myear}-${mday}-${mmonth}`);

  //setting mindate to today-3 months in from field
  mindt.min = `${myear}-${mmonth}-${mday}`;
  mindt.max = `${year}-${month}-${day}`;
  //setting maxdate to today in to field
  maxdt.max = `${year}-${month}-${day}`;
  maxdt.min = `${tmyear}-${tmmonth}-${tmday}`;
</script>

</html>