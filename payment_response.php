<?php
include "functions.php";
if($_SERVER['REQUEST_METHOD']=='POST')
{
    if($_POST['RESPCODE']=='01'&&$_POST['RESPMSG']=='Txn success'&&$_POST['TXNID']!=''&&$_POST['BANKTXNID']!='')
    {
         $txn_id=$_POST['TXNID'];
         $txn_amount=$_POST['TXNAMOUNT'];
         

    }
    else{
        echo "Your Payment Has Been Failed!!,You Will Redirected To Order Page";

        //sleep for 5 seconds
        sleep(5);
        header("Location:review_order.php");
    }
}
?>