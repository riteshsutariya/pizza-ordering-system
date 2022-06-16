<?php
include_once 'dbconfig.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$order_res=$con->query("SELECT * FROM orderitemstb WHERE orderid=".$_GET['oid']);
if($order_res)
{
   if($order_res->num_rows>0)
   {
       $total_amount=0;
                while ($item = $order_res->fetch_array()) {
                    // //we'll get pizzaid,quantity,priceid
                    // $pizzaqry = $con->query("SELECT * FROM pizzatb WHERE pizzaid=" . $item['pizzaid']);
                    // $pizza = $pizzaqry->fetch_array();
                    $priceqry = $con->query("SELECT * FROM pricetb where priceid=" . $item['priceid']);
                    $price = $priceqry->fetch_array();

                    $ind_amt = $price['price'] * $item['quantity'];
                    $total_amount += $ind_amt;
                }
   }
   else{
       die("something went wrong!");
   }
}
else{
    die("Something Went Wrong!!");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="Paytm_PHP_Sample-master\PaytmKit\pgRedirect.php" method="POST">
        <input type="hidden" name="order_id" value="<?php echo $_GET['oid'];?>">
        <input type="hidden" id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?php echo $_SESSION['userId']; ?>">
        <input type="hidden" id="CHANNEL_ID" tabindex="4" maxlength="12" size="12" name="CHANNEL_ID" autocomplete="off" value="WEB">
        <input type="hidden" name="TXN_AMOUNT" value="<?php echo $total_amount; ?>">
        <input type="hidden" id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off" value="<?php echo $_GET['oid']; ?>">
        <input type="hidden" id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail">
    <input id="btn_pay" name="pmnt_redirect" type="submit" value="make pmnt">
    </form>
</body>
<script>
    let btn=document.getElementById("btn_pay").click();
    // document.forms[0].submit();
</script>
</html>