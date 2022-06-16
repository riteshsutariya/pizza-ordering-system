<?php
include_once 'dbconfig.php';

if($_SERVER['REQUEST_METHOD']=='GET')
{
    if(isset($_GET['id'])){
       if($_GET['id']!=='')
       {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
          }
        $add_id=$_GET['id'];
        $cust_id=$_SESSION['userId'];
        $del_addr=$con->query("DELETE FROM addresstb where addressid=".$add_id." AND custid=".$cust_id);
        if($del_addr)
        {
             if($con->affected_rows==1)
             {
                header("Location:addresses.php");
             }
             else{
                 echo "couldn't delete address!!";
             }
        }
        else{
            echo "can't delete address, reason can be it's given for previous order.<a href='account.php'>click here</a> to go back.";
            // echo mysqli_error($con);
        }     
       }
       else{
        die("you must played with url!"); 
       }
    }
    else{
        die("something went wrong or you must played with url!");
    }
}
