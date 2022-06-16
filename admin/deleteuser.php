<?php
require_once '../dbconfig.php';
// $pid;

function send_email($mail_to,$name)
{
    $to = $mail_to;
    $header = "MIME-Version: 1.0" . "\r\n";
    $header .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
    $subject = "Account Deleted";

    $message="<h2>Hello $name</h2> <p>Your Account Has Been Deleted Due To Some Reason.</p>";
    if (mail($to, $subject, $message, $header)) {
        return true;
    } else {
        die("mail not sent!!");
    }
}

if($_SERVER['REQUEST_METHOD']=='GET')
{
    
//handling form submit
if(isset($_GET['btndelete'])=='Confirm Delete')
{
  $userQry=$con->query("SELECT * FROM customertb WHERE custid=".$_GET['id']);
      if($userQry)
      {
          if($userQry->num_rows==1)
          {
              $r=$userQry->fetch_array();
              $uname=$r['name'];
              $mail_to=$r['email'];
          }
      }
      else{
        
      }
    $del_qry="DELETE FROM customertb WHERE custid=".$_GET['id'];
    $res=$con->query($del_qry);
    if($res)
    {
       if($con->affected_rows==1)
       {
           send_email($mail_to,$uname);
           header("Location:index.php");
       }
       else{
           die("customer not deleted successfully!");
       }
    }
    else{
        die("error occured!");
    }
    // die($del_qry);
}

    if(isset($_GET['id'])==true)
    {
        $cid=$_GET['id'];
        $record=$con->query("SELECT * FROM customertb where custid=$cid");
        
        if($record)
        {
            if($record->num_rows==1)
            {
                $customer=$record->fetch_array();
                // $precord=$con->query("SELECT * FROM pricetb WHERE pizzaid=$pid");
                // $prices=[];
                // while($price=$precord->fetch_array())
                // {
                    // array_push($prices,(int)$price['price']);
                // }
            }
            else{
                die("customer not found!");
            }
        }
        else{
            die("Error occurred!");
        }
    }
    else{
        die("customer id not provided!!");
    }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Delete Customer</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon" />
</head>

<body>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-300">
    <div class="flex flex-col bg-white px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full mt-14 max-w-xl">
      <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800 mb-8">
        Delete Customer 
      </div>
      <!-- <p class="text-4xl -mt-12 ml-[550px] absolute"></p> -->
      <!-- <div class="container border-2 border-slate-500 w-fit p-10 mt-32 ml-[550px]"> -->
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">customer Name: <?php if(isset($customer['name'])) echo $customer['name']; ?></span>
          
          <!-- <input class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" type="text" value="" name="txtpname" placeholder="Enter Pizza Name" /> -->
        </div>
       
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">customer email: <?php
            if(isset($customer['email']))
            {
                echo $customer['email'];
            }
            ?></span>
        </div>

        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">contact number: <?php
            if(isset($customer['contactno']))
            {
                echo $customer['contactno'];
            }
            ?></span>
        </div>
        <?php
                if (isset($err_msg) != '')
                    echo "<span class='uppercase text-sm font-bold text-red-500'>", $err_msg, "</span><br>";
                ?>
                
      <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="GET">
        <div>
          <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
          <input type="submit" name="btndelete" class="bg-orange-600 px-3 py-3 mt-1 rounded-lg hover:scale-105 w-full" value="Confirm Delete">
          <button class="bg-orange-600 px-3 py-3 mt-3 rounded-lg hover:scale-105 w-full"><a href="index.php">Don't Delete</a></button>
        </div>
      </form>
      <!-- </div> -->
    </div>
  </div>
</body>

</html>