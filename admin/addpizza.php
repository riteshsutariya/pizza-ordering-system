<?php
require_once '../dbconfig.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $check=false;
  if (isset($_POST['btnadd']) == 'Add') {
    // var_dump($_POST);
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
          // echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
          $file_location="./images/uploads/".basename( $_FILES["imageToUpload"]["name"]);
          $sql="INSERT INTO pizzatb(pizzaid,name,description,type,image_url) VALUES(null,'$pname','$pdesc','$ptype','$file_location')";

          $res=$con->query($sql);
          if($res)
          {
            if($con->affected_rows===1)
            {
              $pidqry=$con->query("SELECT pizzaid from pizzatb where name='$pname'");
          $row=$pidqry->fetch_array();
          $pid=$row['pizzaid'];
              $price_qry="INSERT INTO pricetb (priceid,pizzaid,size,price) VALUES(null,$pid,'small',$sprice),
              (null,$pid,'medium',$mprice),(null,$pid,'large',$lprice)";
              $pres=$con->query($price_qry);
              if($pres)
              {
                    if($con->affected_rows===3)
                    {
                      header("Location:pizzalist.php");
                    }
                    else{
                      echo "Sorry, there was an error while setting price!";
                    }
              }
              else{
                $err_msg='Error occured-1!';
                echo mysqli_error($con);
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
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pizza Insert</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon" />
</head>

<body>
  <?php
  include_once 'header.php';
  ?>
  <div style="margin-top: -68px;" class="min-h-screen flex flex-col items-center justify-center">
    <div class="flex flex-col bg-white px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full mt-14 max-w-xl">
      <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800 mb-8">
        Insert Pizza
      </div>
      <!-- <p class="text-4xl -mt-12 ml-[550px] absolute"></p> -->
      <!-- <div class="container border-2 border-slate-500 w-fit p-10 mt-32 ml-[550px]"> -->
      <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST" enctype="multipart/form-data">
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Pizza Name</span><br />
          <input class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" type="text" name="txtpname" placeholder="Enter Pizza Name" />
        </div>
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Pizza Description</span><br />
          <!-- <input
            class="w-80 mt-1 p-3 text-gray-900 border-2 border-black rounded-lg transition ease-in-out m-0 "
            type="textarea"
            id="formFile"
          /> -->
          <textarea name="txtpdesc" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" id="" cols="30" rows="10" placeholder="Enter Pizza Description"></textarea>
        </div>
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Pizza Image</span><br />
          <input type="file" name="imageToUpload" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" id="imageToUpload" accept="image/png, image/jpeg" />
        </div>
        <!-- <div>
          <span class="uppercase text-sm font-bold">Pizza Category</span><br />
          <select name="pizza category" id="category" class="w-80 mt-1 p-3 border-2 border-black rounded-lg text-gray-900">
            <option value="first">First</option>
            <option value="second">Second</option>
            <option value="third">Third</option>
          </select>
        </div> -->
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Pizza Type</span><br />
          <select name="txtptype" id="type_v" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2">
            <option value="veg">Veg</option>
            <option value="nonveg">Non Veg</option>
          </select>
        </div>

        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Small size price</span><br />
          <input class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" type="number" min="49" max="1999"  name="txtsprice" placeholder="Enter small size price" />
        </div>
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Medium size price</span><br />
          <input class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" type="number" min="49" max="2999" name="txtmprice" placeholder="Enter medium size price" />
        </div>
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Large size price</span><br />
          <input class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" type="number" min="49" max="3999"  name="txtlprice" placeholder="Enter large size price" />
        </div>
        <?php
                if (isset($err_msg) != '')
                    echo "<span class='uppercase text-sm font-bold text-red-500'>", $err_msg, "</span><br>";
                ?>
        <div class="mt-8">
          <input type="submit" name="btnadd" class="bg-orange-600 px-3 py-3 mt-1 rounded-lg hover:scale-105 w-full" value="Add">
        </div>
      </form>
      <!-- </div> -->
    </div>
  </div>
</body>

</html>