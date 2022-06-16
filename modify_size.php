<?php
include_once "dbconfig.php";

    if(isset($_GET['pid']))
    {
        if(isset($_GET['cid']))
        {
            if(isset($_GET['size']))
            {
                 if($_GET['pid']!==''&&$_GET['cid']!==''&&$_GET['size']!=='')
                {
                    $cart_id=$_GET['cid'];
                    $pizza_id=$_GET['pid'];
                    $new_size=$_GET['size'];
                      
                    $pid_res=$con->query("SELECT priceid from pricetb where pizzaid=".$pizza_id." AND size='".$new_size."'");
                    if($pid_res)
                    {
                        if($pid_res->num_rows==1)
                        {
                            $price_id=$pid_res->fetch_array()['priceid'];
                            //update priceid
                            $update_qry="UPDATE cartitemstb SET price_id=".$price_id." WHERE cartid=".$cart_id." AND pizzaid=".$pizza_id;
                            $update_res=$con->query($update_qry);
                            if($update_res)
                            {
                                 if($con->affected_rows==1)
                                 {
                                     header("Location:cart.php");
                                 }
                                 else{
                                    // echo("price not updated!");
                                     header("Location:cart.php");
                                 }
                            }
                            else{
                                die("something went wrong - update priceid");
                            }
                        }
                        else{
                            echo("inappropriate priceid or not found");
                        }
                    }
                    else{
                        die("something wrong!-priceid");
                    }
                    // $pid=;
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

?>