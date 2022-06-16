<?php

function send_mail($subject, $sub_msg, $msg)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    var_dump($_SESSION);
    $to = $_SESSION['userEmail'];
    $name = $_SESSION['userName'];
    $header = "MIME-Version: 1.0" . "\r\n";
    $header .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
    $subject = "Order success $name";

    $message = "<h2>Hello $name</h2> <p>$sub_msg</p> $msg";
    if (mail($to, $subject, $message, $header)) {
        return true;
    } else {
        die("mail not sent!!");
    }
}

function get_cartId()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include "dbconfig.php";
    $cart_qry = "SELECT cartid FROM carttb WHERE custid=" . $_SESSION['userId'];
    $cart_res=$con->query($cart_qry);
    if($cart_res)
    {
        if($cart_res->num_rows==1)
        {
            $cart_id=$cart_res->fetch_array()['cartid'];
            return $cart_id;
        }
        else{
            return false;
        }
    }
    else{
        die("Error While Fetching Cart!");
    }
}
