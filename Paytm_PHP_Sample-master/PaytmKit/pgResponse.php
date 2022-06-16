<?php

header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");


if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

include '../../dbconfig.php';
include '../../functions.php';

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if ($isValidChecksum == "TRUE") {
	echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		echo "<b>Transaction status is success</b>" . "<br/>";
		//set status success to payment status

		$order_id = $_POST['ORDERID'];
		$datetime = $_POST['TXNDATE'];
		$amount = $_POST['TXNAMOUNT'];
		$tid = $_POST['TXNID'];

		//reset session
		$user=$con->query("SELECT * FROM customertb WHERE custid=(SELECT custid FROM ordertb WHERE orderid=$order_id)");
		if($user)
		{
            if($user->num_rows==1)
			{
				$u=$user->fetch_array();
				if (session_status() === PHP_SESSION_NONE) {
					session_start();
				}
				$_SESSION['userId']=$u['custid'];
                $_SESSION['userName']=$u['name'];
                $_SESSION['userEmail']=$u['email'];
                $_SESSION['userContact']=$u['contactno'];
                $_SESSION['isLoggedIn']=true;
				$cart=$con->query("SELECT cartid from carttb where custid=".$u['custid']);
                       if($cart)
                       {
                         if($cart->num_rows==1)
                         {
                             $cr=$cart->fetch_array();
                            $_SESSION['cartId']=$cr['cartid'];
                         }
                         else{

                         }
                       }
                       else{
                         die("something went wrong in backend!");
                       }
			}
		}
		else{
			die("Something Went Wrong!!");
		}

		$set_status = $con->query("UPDATE ordertb set payment_status='Success' WHERE orderid=" . $order_id);
		if ($set_status) {
			if ($con->affected_rows == 1) {
				//entry in payment table
				$payment = $con->query("INSERT INTO paymenttb VALUES(null,$order_id,'$datetime',$amount,'$tid')");
				echo ("INSERT INTO paymenttb VALUES(null,$order_id,'$datetime',$amount,'$tid'");
	
				if ($payment) {
					if ($con->affected_rows == 1) {
						//send confirmation mail

						$order_items = $con->query("SELECT * FROM orderitemstb where orderid=$order_id");
						if ($order_items) {
							$table = "<table width: 600px;text-align:right'>
                <thead>
                    <tr style='padding-top: 12px;
                    padding-bottom: 12px;
                    text-align: left;
                    background-color: #04AA6D;
                    color: white;'>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>";
							while ($item = $order_items->fetch_array()) {
								$pizzaqry = $con->query("SELECT * FROM pizzatb WHERE pizzaid=" . $item['pizzaid']);
								$pizza = $pizzaqry->fetch_array();

								$priceqry = $con->query("SELECT * FROM pricetb where priceid=" . $item['priceid']);
								$price = $priceqry->fetch_array();
								
								$name = $pizza['name'];
								$quantity = $item['quantity'];
								$amount = $price['price'];
								$table .= "<tr>
								<td>$name</td>
								<td>$quantity</td>
								<td>$amount</td>
						        </tr>";
							}
							$table .= "</tbody>
							</table>";

							if (session_status() === PHP_SESSION_NONE) {
								session_start();
							}
							$to = $_SESSION['userEmail'];
							$name = $_SESSION['userName'];
							$header = "MIME-Version: 1.0" . "\r\n";
							$header .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
							$subject = "Order success $name";

							$message = "<h2>Hello $name</h2> <p>This Is To Inform You That Your Order Has Placed Successully, Pizza Is Cooking For You. You Will Get It Soon...</p> $table";
							if (mail($to, $subject, $message, $header)) {
								
							} else {
								die("mail not sent!!");
							}
						} else {
							die("Error-orderitem mail");
						}
						
						header("Location:http://localhost/pizza-php/account.php");
					} else {
						echo "could not enter transaction record!";
					}
				} else {
					die("Error-payment entry!");
				}
			} else {
				echo "couldn't set payment status!";
			}
		} else {
			die("Error-set succcess pstatus");
		}
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
	} else {
		$order_id = $_POST['ORDERID'];
		$set_status = $con->query("UPDATE ordertb set payment_status='Failed' WHERE orderid=" . $order_id);
		if ($set_status) {
			if ($con->affected_rows == 1) {
				
			}
		}
		echo "<b>Transaction status is failure</b>" . "<br/>";
	}

	if (isset($_POST) && count($_POST) > 0) {
		foreach ($_POST as $paramName => $paramValue) {
			echo "<br/>" . $paramName . " = " . $paramValue;
		}
	}
} else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}
