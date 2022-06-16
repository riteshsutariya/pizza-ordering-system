<?php
require_once '../dbconfig.php';
// $pid;

if($_SERVER['REQUEST_METHOD']=='GET')
{
    
//handling form submit
if(isset($_GET['btndelete'])=='Confirm Delete')
{
  {
      //delete image that was uploaded 
    $fetchImgTitleName = $_GET['img_url']; 
    $createDeletePath = ".".$fetchImgTitleName;
    if(unlink($createDeletePath))
    {
      //after deleting uploaded image, deleting record from db
      $del_qry="DELETE FROM pizzatb WHERE pizzaid=".$_GET['id'];
      $res=$con->query($del_qry);
      if($res)
      {
        if($con->affected_rows==1)
     {      
         header("Location:pizzalist.php");
     }
     else{
         die("pizza not deleted successfully!");
     }
      }
      else{
          die("error occured!");
      }
      // die($del_qry);
    }
    else
    {
      $displayErrMessage = "Unable to delete Image";
    }
    
  }       
}

    if(isset($_GET['id'])==true)
    {
        global $pid;
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
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Delete pizza</title>
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
        Delete Pizza 
      </div>
      <!-- <p class="text-4xl -mt-12 ml-[550px] absolute"></p> -->
      <!-- <div class="container border-2 border-slate-500 w-fit p-10 mt-32 ml-[550px]"> -->
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Pizza Name: <?php if(isset($pizza['name'])) echo $pizza['name']; ?></span>
          
          <!-- <input class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" type="text" value="" name="txtpname" placeholder="Enter Pizza Name" /> -->
        </div>
       
        <div class='mb-6'>
          <span class="uppercase text-sm font-bold">Pizza Image</span><br />
          
              <?php
              if(isset($pizza['image_url'])) 
              {
              ?>
              <div class="my-3">
              <img src=".<?php echo $pizza['image_url']; $GLOBALS['img_url']=$pizza['image_url'];?>" alt="img">
              </div>
              <?php
              }
              ?>
          <!-- <input type="file" name="imageToUpload" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2" id="imageToUpload" accept="image/png, image/jpeg" /> -->
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
          <span class="uppercase text-sm font-bold">Pizza Type: <?php
            if(isset($pizza['type']))
            {
                echo $pizza['type'];
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
          <input type="hidden" name="img_url" value="<?php echo $pizza['image_url']; ?>">
          <input type="submit" name="btndelete" class="bg-orange-600 px-3 py-3 text-white mt-1 rounded-lg hover:scale-105 w-full" value="Confirm Delete">
          <button class="bg-orange-600 px-3 py-3 mt-3 rounded-lg hover:scale-105 w-full"><a class="text-white" href="pizzalist.php">Don't Delete</a></button>
        </div>
      </form>
      <!-- </div> -->
    </div>
  </div>
</body>

</html>