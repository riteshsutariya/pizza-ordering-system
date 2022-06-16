<?php
/*
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
*/
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$cust_id = $_SESSION['userId'];
// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

include "../../functions.php";
include "../../dbconfig.php";



if (isset($_POST['pmnt_redirect'])) {
	if ($_POST['pmnt_redirect'] == 'make pmnt') {
		$checkSum = "";
		$paramList = array();
		// var_dump($_POST);
		// die();
		// var_dump($_POST);
		// die($_POST['order_id']);
		$ORDER_ID = $_POST['order_id'];
		$CUST_ID = $_POST["CUST_ID"];
		$INDUSTRY_TYPE_ID = $_POST["INDUSTRY_TYPE_ID"];
		$CHANNEL_ID = $_POST["CHANNEL_ID"];
		$TXN_AMOUNT = $_POST["TXN_AMOUNT"];
		// die("hello");
		// $ADDR_ID=$_POST["addresses"];
		// Create an array having all required parameters for creating checksum.
		$paramList["MID"] = PAYTM_MERCHANT_MID;
		$paramList["ORDER_ID"] = $ORDER_ID;
		$paramList["CUST_ID"] = $CUST_ID;
		$paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
		$paramList["CHANNEL_ID"] = $CHANNEL_ID;
		$paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
		$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
		// $paramList['ADDRESS_ID']=$ADDR_ID;
		$paramList["CALLBACK_URL"] = "http://localhost/pizza-php/Paytm_PHP_Sample-master/PaytmKit/pgResponse.php";

		/*
										$paramList["CALLBACK_URL"] = "http://localhost/PaytmKit/pgResponse.php";
										$paramList["MSISDN"] = $MSISDN; //Mobile number of customer
										$paramList["EMAIL"] = $EMAIL; //Email ID of customer
										$paramList["VERIFIED_BY"] = "EMAIL"; //
										$paramList["IS_USER_VERIFIED"] = "YES"; //
										*/

		//Here checksum string will return by getChecksumFromArray() function.
		$checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);
		// header("Location:Paytm_PHP_Sample-master\PaytmKit\pgRedirect.php");
	}
}else
if (isset($_POST['orderbtn'])) {
	if ($_POST['orderbtn'] == "Make Payment") {
		$cart_res = $con->query("SELECT * FROM cartitemstb WHERE cartid=(
			SELECT cartid FROM carttb WHERE custid=" . $_SESSION['userId'] . ")");
		$addr_id = $_POST['addresses'];
		if ($cart_res) {
			if ($cart_res->num_rows > 0) {

				$datetime = date_create()->format('Y-m-d H:i:s');
				$order_qry = "INSERT INTO ordertb values(null,$cust_id,$addr_id,'$datetime','Pending','Order Placed')";
				$order_res = $con->query($order_qry);
				if ($order_res) {
					if ($con->affected_rows == 1) {
						//fetching order id
						$oid_res = $con->query("SELECT orderid FROM ordertb WHERE custid=$cust_id AND datetime='$datetime' AND payment_status='Pending'");
						// die("SELECT orderid FROM ordertb WHERE custid=$cust_id AND datetime='$datetime' AND payment_status='Pending'");
						if ($oid_res) {
							$order_id = $oid_res->fetch_array()['orderid'];
							while ($item = $cart_res->fetch_array()) {
								//we'll get pizzaid,quantity,priceid
								$pizza_id = $item['pizzaid'];
								$price_id = $item['price_id'];
								$quantity = $item['quantity'];
								$pizzaqry = $con->query("SELECT * FROM pizzatb WHERE pizzaid=" . $item['pizzaid']);
								$pizza = $pizzaqry->fetch_array();
								$name = $pizza['name'];
								$priceqry = $con->query("SELECT * FROM pricetb where priceid=" . $item['price_id']);
								$price = $priceqry->fetch_array();
								$ind_price = $price['price'];
								// var_dump($quantity);
								// var_dump($price);
								$amount = ($quantity) * ($ind_price);
								// $datetime = date_create()->format('Y-m-d H:i:s');
								$order_qry = "INSERT INTO orderitemstb values(null,$order_id,$pizza_id,$price_id,$quantity,$amount)";
								$order_res = $con->query($order_qry);
								if ($order_res) {
									if ($con->affected_rows == 1) {

										$is_success = true;
									} else {
										$is_success = false;
										echo "can't place order right now~";
									}
								} else {
									die($order_qry);
								}
							}
							if ($is_success === true) {
								//empty cart
								$cart_id = get_cartId();
								$del_cart = $con->query("DELETE FROM cartitemstb WHERE cartid=$cart_id");
								if ($del_cart) {
									if ($con->affected_rows > 0) {
										$checkSum = "";
										$paramList = array();
										// var_dump($_POST);
										// die();
										$ORDER_ID = $order_id;
										$CUST_ID = $_POST["CUST_ID"];
										$INDUSTRY_TYPE_ID = $_POST["INDUSTRY_TYPE_ID"];
										$CHANNEL_ID = $_POST["CHANNEL_ID"];
										$TXN_AMOUNT = $_POST["TXN_AMOUNT"];
										// $ADDR_ID=$_POST["addresses"];
										// Create an array having all required parameters for creating checksum.
										$paramList["MID"] = PAYTM_MERCHANT_MID;
										$paramList["ORDER_ID"] = $ORDER_ID;
										$paramList["CUST_ID"] = $CUST_ID;
										$paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
										$paramList["CHANNEL_ID"] = $CHANNEL_ID;
										$paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
										$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
										// $paramList['ADDRESS_ID']=$ADDR_ID;
										$paramList["CALLBACK_URL"] = "http://localhost/pizza-php/Paytm_PHP_Sample-master/PaytmKit/pgResponse.php";

										/*
										$paramList["CALLBACK_URL"] = "http://localhost/PaytmKit/pgResponse.php";
										$paramList["MSISDN"] = $MSISDN; //Mobile number of customer
										$paramList["EMAIL"] = $EMAIL; //Email ID of customer
										$paramList["VERIFIED_BY"] = "EMAIL"; //
										$paramList["IS_USER_VERIFIED"] = "YES"; //
										*/

										//Here checksum string will return by getChecksumFromArray() function.
										$checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);
										//header("Location:Paytm_PHP_Sample-master\PaytmKit\pgRedirect.php");
									} else {
										echo "cartitems not deleted!";
									}
								} else {
									die("Error while deleting cart~");
								}
							} else {
								echo "something went wrong!";
							}
						} else {
							die("orderid not found!");
						}
					}
				} else {
					die("Something Went Wrong-outer!!");
				}
			} else {
				die("cart is Empty");
			}
		} else {
			die("something went wrong-paymentCartFetch");
		}
	}
}

?>
<html>

<head>
	<title>Merchant Check Out Page</title>
</head>

<body>
	<center>
		<h1>Please do not refresh this page...</h1>
	</center>
	<form method="post" action="<?php echo PAYTM_TXN_URL ?>" name="f1">
		<table border="1">
			<tbody>
				<?php
				// var_dump($paramList);
				// die();
				foreach ($paramList as $name => $value) {
					echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
				}
				?>
				<input type="hidden" name="addr_id" value="<?php if(isset($_POST['addresses'])) echo $_POST['addresses']; ?>">
				<input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">
			</tbody>
		</table>
		<script type="text/javascript">
			document.f1.submit();
		</script>
	</form>
</body>

</html>