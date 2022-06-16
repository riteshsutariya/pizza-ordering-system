<?php
include_once "dbconfig.php";
if(isset($_GET['action']))
{
    if(isset($_GET['pid']))
    {
        if(isset($_GET['cid']))
        {
            if(isset($_GET['c_qty']))
            {
                
                if($_GET['pid']!==''&&$_GET['cid']!==''&&$_GET['action']!==''&&$_GET['c_qty']!=='')
                {
                    $action=$_GET['action'];
                    $cart_id=$_GET['cid'];
                    $pizza_id=$_GET['pid'];
                    $current_quantity=$_GET['c_qty'];

                   if($action==='dec')
                   {
                       if($current_quantity==='1')
                       {
                           echo "gonna delete the item from cart!";
                           $del_qry="DELETE FROM cartitemstb WHERE cartid=".$cart_id." AND pizzaid=".$pizza_id;
                           $res=$con->query($del_qry);
                           if($res)
                           {
                              if($con->affected_rows==1)
                              {
                                  header("Location:cart.php");
                              }
                           }
                           else{
                               die("something went wrong!!");
                           }
                       }
                       else{
                        // echo ("need to decrease quantity by 1 of pizzaid ".$_GET['pid']." of cartid ".$_GET['cid']." which's current quantity is ".$_GET['c_qty']);
                        $dec_qry="UPDATE cartitemstb SET quantity=(quantity-1) WHERE cartid=".$cart_id." AND pizzaid=".$pizza_id;
                        $res=$con->query($dec_qry);
                           if($res)
                           {
                              if($con->affected_rows==1)
                              {
                                  header("Location:cart.php");
                              }
                           }
                           else{
                               die("something went wrong!!");
                           }   
                    }
                   }
                   else if($action==='inc')
                   {              
                    if($current_quantity==='10')
                    {
                        echo "we will not allow to increase size anymore!";
                        header("Location:cart.php");
                    }else{   
                        $inc_qry="UPDATE cartitemstb SET quantity=(quantity+1) WHERE cartid=".$cart_id." AND pizzaid=".$pizza_id;
                        $res=$con->query($inc_qry);
                           if($res)
                           {
                              if($con->affected_rows==1)
                              {
                                  header("Location:cart.php");
                              }
                           }
                           else{
                               die("something went wrong!!");
                           }
                       echo ("need to increase quantity by 1 of pizzaid ".$_GET['pid']." of cartid ".$_GET['cid']." which's current quantity is ".$_GET['c_qty']);
                    }
                    } 
                   else{
                       header("Location:cart.php");
                   }    
                }
                else{
                    header("Location:cart.php");
                }
            }
        }
        else{
            header("Location:cart.php");
        }
    }
    else{
        header("Location:cart.php");
    }
}
else{
    header("Location:cart.php");
}
?>