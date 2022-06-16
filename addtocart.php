<?php
include_once 'dbconfig.php';
session_start();
function user_exist($id)
{
    if (isset($con) == false) {
        include 'dbconfig.php';
    }
    $id = $_SESSION['userId'];
    $is_exist = "true";
    $qry = "SELECT COUNT(*) AS cnt FROM carttb WHERE custid=" . $id;
    echo $qry;
    $res = $con->query($qry);
    if ($res) {
        $cnt = $res->fetch_array();
        if ($cnt['cnt'] == 1) {
            $is_exist = "true";
        } else {
            $is_exist = "false";
        }
    } else {
        die("Error Occured is exist!");
    }
    return $is_exist;
}
if (isset($_SESSION['isLoggedIn'])) {
    $cust_id = $_SESSION['userId'];
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //getting pizzaid and its priceid(for specific size)
        if (isset($_GET['pid'])) {
            if ($_GET['pid'] !== '') {
                $pid = $_GET['pid'];
                if (isset($_GET['size'])) {
                    if ($_GET['size'] !== '') {
                        $size = $_GET['size'];
                        $pres = $con->query("SELECT priceid from pricetb where pizzaid=$pid AND size='$size'");
                        if ($pres) {
                            if ($pres->num_rows === 1) {
                                $price_id = $pres->fetch_array()['priceid'];

                                //after getting priceid and pizzaid we will check if cart is there for user?
                                if (user_exist($_SESSION['userId']) == 'true') {
                                    echo "Direct entry in cartitemtb";
                                    //fetching cartid from db
                                    $cart = $con->query("SELECT cartid from carttb where custid=" . $_SESSION['userId']);
                                    if ($cart) {
                                        if ($cart->num_rows == 1) {
                                            $cr = $cart->fetch_array();
                                            $cart_id = $cr['cartid'];
                                            //after getting cart id we will do entry in cartitemtb
                                            $item_insert = $con->query("INSERT INTO cartitemstb VALUES($cart_id,$pid,1,$price_id)");
                                            if ($item_insert) {
                                                if ($con->affected_rows === 1) {
                                                    header("Location:menu.php");
                                                }
                                            } else {
                                                die("error occured while adding item to cart!!");
                                            }
                                        } else {
                                            die("something went wrong!!");
                                        }
                                    } else {
                                        die("something went wrong in backend!");
                                    }
                                } else {
                                    $datetime = date_create()->format('Y-m-d H:i:s');
                                    $cart_insert = $con->query("INSERT INTO carttb VALUES(null,$cust_id,'$datetime')");
                                    if ($cart_insert) {
                                        if ($con->affected_rows == 1) {
                                            $cart_extract = $con->query("SELECT cartid from carttb where custid=" . $cust_id);
                                            if ($cart_extract) {
                                                if ($cart_extract->num_rows == 1) {
                                                    $cart = $cart_extract->fetch_array();
                                                    $_SESSION['cartId'] = $cart['cartid'];
                                                    $cart_id = $_SESSION['cartId'];
                                                } else {
                                                    die('cart not found!');
                                                }
                                            }
                                            //after creating cart, doing entry in cartitemtb
                                            $cart = $con->query("SELECT cartid from carttb where custid=" . $_SESSION['userId']);
                                            if ($cart) {
                                                if ($cart->num_rows == 1) {
                                                    $cr = $cart->fetch_array();
                                                    $_SESSION['cartId'] = $cr['cartid'];
                                                    //after getting cart id we will do entry in cartitemtb
                                                    $item_insert = $con->query("INSERT INTO cartitemstb VALUES($cart_id,$pid,1,$price_id)");
                                                    if ($item_insert) {
                                                        if ($con->affected_rows === 1) {
                                                            header("Location:menu.php");
                                                        }
                                                    } else {
                                                        die("error occured while adding item to cart!!");
                                                    }
                                                } else {
                                                    die("something went wrong!!");
                                                }
                                            } else {
                                                die("something went wrong in backend!");
                                            }
                                        } else {
                                            echo "cart not created!!";
                                        }
                                    } else {
                                        die("something went wrong in backend-cart!");
                                    }
                                }
                            } else {
                                die("pizza not available or something went wrong! <a href='menu.php'>click here</a> to refresh.");
                            }
                        } else {
                            die("something went wrong!!");
                        }
                    } else {
                        die("you had played with URL!");
                    }
                }
            } else {
                die("you had played with URL!");
            }
        }
    }
} else {
    header("Location:login.php");
}

if (user_exist($_SESSION['userId'])) {
    $qry = "SELECT * FROM customertb WHERE BINARY name='$username'";
    $stmt = $con->query($qry);
    if ($stmt->num_rows == 1) {
        $user = $stmt->fetch_array();
        $hashed_pass = $user['password'];
        if (password_verify($password, $hashed_pass)) {
            session_start();
            /*$cart=$con->query("SELECT cartid from carttb where custid=".$user['custid']);
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
           }*/
            $_SESSION['userId'] = $user['custid'];
            $_SESSION['userName'] = $user['name'];
            $_SESSION['userEmail'] = $user['email'];
            $_SESSION['userContact'] = $user['contactno'];
            $_SESSION['isLoggedIn'] = true;
            header("Location:index.php");
        } else {
            $err_msg = 'invalid password!';
        }
    }
}

if (isset($_SESSION['isLoggedIn'])) {
    $cust_id = $_SESSION['userId'];
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['pid'])) {
            if ($_GET['pid'] !== '') {
                $pid = $_GET['pid'];
                if (isset($_GET['size'])) {
                    if ($_GET['size'] !== '') {
                        $size = $_GET['size'];
                        $pres = $con->query("SELECT priceid from pricetb where pizzaid=$pid AND size='$size'");
                        // var_dump($pres);
                        if ($pres) {
                            if ($pres->num_rows === 1) {
                                $price_id = $pres->fetch_array()['priceid'];

                                if (isset($_SESSION['cartId'])) {
                                    $cart_id = $_SESSION['cartId'];
                                } else {
                                    $datetime = date_create()->format('Y-m-d H:i:s');
                                    $cart_insert = $con->query("INSERT INTO carttb VALUES(null,$cust_id,'$datetime')");
                                    if ($cart_insert) {
                                        if ($con->affected_rows == 1) {
                                            $cart_extract = $con->query("SELECT cartid from carttb where custid=" . $cust_id);
                                            if ($cart_extract) {
                                                if ($cart_extract->num_rows == 1) {
                                                    $cart = $cart_extract->fetch_array();
                                                    $_SESSION['cartId'] = $cart['cartid'];
                                                    $cart_id = $_SESSION['cartId'];
                                                } else {
                                                    die('cart not found!');
                                                }
                                            }
                                            //    $cart_extract=$con->query("SELECT");
                                        } else {
                                            echo "cart not created!!";
                                        }
                                    } else {
                                        die("something went wrong in backend-cart!");
                                    }
                                }

                                if (isset($cart_id)) {
                                    $item_insert = $con->query("INSERT INTO cartitemstb VALUES($cart_id,$pid,1,$price_id)");
                                    if ($item_insert) {
                                        if ($con->affected_rows === 1) {
                                            header("Location:menu.php");
                                        }
                                    } else {
                                        unset($_SESSION['cartid']);
                                        // die("error occured while adding item to cart!!");
                                    }
                                } else {
                                    echo "something Went Wrong-cartid not set yet!!";
                                }
                            } else {
                                die("pizza not available or something went wrong!");
                            }
                        } else {
                            die("something went wrong!!");
                        }
                    } else {
                        die("you had played with URL!");
                    }
                }
            } else {
                die("you had played with URL!");
            }
        }
    }
} else {
    header("Location:login.php");
}
