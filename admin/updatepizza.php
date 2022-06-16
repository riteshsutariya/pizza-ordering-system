<?php
require_once '../dbconfig.php';

if($_SERVER['REQUEST_METHOD']=='GET')
{
    if(isset($_GET['id'])==true)
    {
        $pid=$_GET['id'];
        $record=$con->query("SELECT * FROM pizzatb where pizzaid=$pid");
        if($record)
        {
            if($record->num_rows==1)
            {
                $pizza=$record->fetch_array();
                $precord=$con->query("SELECT * FROM pricetb WHERE pizzaid=$pid");
                $prices=[];
                while($price=$precord->fetch_array())
                {
                    array_push($prices,(int)$price['price']);
                }
            }
            else{
                die("pizza not found!");
            }
        }
        else{
            die("Error occurred!");
        }
    }
    else{
        die("pizza id not provided!!");
    }
}


//handling form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $pid=$_POST['id'];
  $check=false;
  // var_dump($_POST);
  // die();
  if (isset($_POST['btnupdate'])) {
    $pname = $_POST['txtpname'];
    $pdesc = $_POST['txtpdesc'];
    $ptype = $_POST['txtptype'];
    $sprice=(int)$_POST['txtsprice'];
    $mprice=(int)$_POST['txtmprice'];
    $lprice=(int)$_POST['txtlprice'];

    if($pname==''||$pdesc==''||$ptype==''||$sprice==''||$mprice==''||$lprice=='')
    {
        $err_msg='All The Fields Are Required!';
    }
    else{
      if(!isset($_FILES['imageToUpload']) || $_FILES['imageToUpload']['error'] == UPLOAD_ERR_NO_FILE) {
        $sql="UPDATE pizzatb SET name='$pname',description='$pdesc',type='$ptype' WHERE pizzaid=$pid";

          $res=$con->query($sql);
          if($res)
          {
            if($con->affected_rows===1)
            {
              $price_qry="UPDATE pricetb SET price=$sprice WHERE pizzaid=$pid AND size='small'";
              $pres=$con->query($price_qry);
              $price_qry="UPDATE pricetb SET price=$mprice WHERE pizzaid=$pid AND size='medium'";
              $pres=$con->query($price_qry);
              $price_qry="UPDATE pricetb SET price=$sprice WHERE pizzaid=$pid AND size='small'";
              $pres=$con->query($price_qry);
              if($pres)
              {
                header("Location:pizzalist.php");
              }
              else{
                die("something went wrong!!");
              }
            }
          }
          else{
            $err_msg='Error occured!-2';
          }
    } else {
      $target_dir = '../images/uploads/';
      $target_file = $target_dir . basename($_FILES["imageToUpload"]["name"]);
      $uploadOk = 1;
  
      if (file_exists($target_file)) {
        $err_msg = "Sorry, file already exists.";
        $uploadOk = 0;
      } else 
      {
        $check = getimagesize($_FILES["imageToUpload"]["tmp_name"]);
        $err_msg = '';
        if ($check !== false) {
          if($_FILES['imageToUpload']['size']>512000)
          {
          $err_msg = 'File is is too large(should less than 500kb)!';
          $uploadOk = 0;
          }
        } else {
          $err_msg = 'File is not an image!';
          $uploadOk = 0;
        }
      }
      if($uploadOk!==0)
      {
        if (move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file)) {
          $file_location="./images/uploads/".basename( $_FILES["imageToUpload"]["name"]);
          $sql="UPDATE pizzatb SET name='$pname',description='$pdesc',type='$ptype',image_url='$file_location' WHERE pizzaid=$pid";

          $res=$con->query($sql);
          if($res)
          {
            if($con->affected_rows===1)
            {
              $price_qry="UPDATE pricetb SET price=$sprice WHERE pizzaid=$pid AND size='small'";
              $pres=$con->query($price_qry);
              $price_qry="UPDATE pricetb SET price=$mprice WHERE pizzaid=$pid AND size='medium'";
              $pres=$con->query($price_qry);
              $price_qry="UPDATE pricetb SET price=$sprice WHERE pizzaid=$pid AND size='small'";
              $pres=$con->query($price_qry);

              if($pres)
              {
                header("Location:pizzalist.php");
              }
              else{
                die("something went wrong!!");
              }
            }
          }
          else{
            $err_msg='Error occured!-2';
          }
        } else {
          echo "Sorry, there was an error uploading your file.";
        }
      }
    }
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pizza Update</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="\css\style.css" />
  <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon" />
</head>

<body style="font-family: 'Montserrat'; scroll-behavior: smooth">
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-300">
    <div class="flex flex-col bg-white px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full mt-14 max-w-xl">
      <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800 mb-8">
        Update Pizza Details
      </div>

      <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>">
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Pizza Name</span><br />
          <input class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" type="text" value="<?php if(isset($pizza['name'])) echo $pizza['name']; ?>" name="txtpname" placeholder="Enter Pizza Name" />
        </div>
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Pizza Description</span><br />
      
          <textarea name="txtpdesc" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" id="" cols="30" rows="10" placeholder="Enter Pizza Description"><?php if(isset($pizza['description'])) echo $pizza['description'];?>
          </textarea>
        </div>
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Pizza Image</span><br />
          
              <?php
              if(isset($pizza['image_url'])) 
              {
              ?>
              <div class="my-3">
              <img src=".<?php echo $pizza['image_url']?>" alt="img">
              </div>
              <?php
              }
              ?>
          <input type="file" name="imageToUpload" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" id="imageToUpload" accept="image/png, image/jpeg" />
        </div>

        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Pizza Type</span><br />
          <select name="txtptype" id="type_v" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2">
            <?php
            if(isset($pizza['type']))
            {
            ?>
            <option value="veg" <?php if($pizza['type']==='veg') echo "selected" ?>>Veg</option>
            <option value="nonveg" <?php if($pizza['type']==='non veg') echo "selected" ?>>Non Veg</option>
            <?php
            }
            ?>
          </select>
        </div>

        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Small size price</span><br />
          <input class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" value="<?php if(isset($prices[0])) echo $prices[0]; ?>" type="number" min="49" max="1999"  name="txtsprice" placeholder="Enter small size price" />
        </div>
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Medium size price</span><br />
          <input class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" value="<?php if(isset($prices[1])) echo $prices[1]; ?>" type="number" min="49" max="2999" name="txtmprice" placeholder="Enter medium size price" />
        </div>
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Large size price</span><br />
          <input class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" value="<?php if(isset($prices[2])) echo $prices[2]; ?>" type="number" min="49" max="3999"  name="txtlprice" placeholder="Enter large size price" />
        </div>
        <?php
                if (isset($err_msg) != '')
                    echo "<span class='uppercase text-sm font-bold text-red-500'>", $err_msg, "</span><br>";
                ?>
        <div class="mt-8">
          <input type="submit" name="btnupdate" class="bg-orange-600 px-3 py-3 mt-1 rounded-lg hover:scale-105 w-full" value="Update">
        </div>
      </form>
      <!-- </div> -->
    </div>
  </div>
</body>

</html>