<?php
include_once '../dbconfig.php';

$p_qry = "SELECT * FROM contactustb";
$messages = $con->query($p_qry);
$Empty=false;
if ($messages) {
    if ($messages->num_rows > 0) {
    } else {
        $Empty = true;
    }
} else {
    die('Something went wrong!');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>messages list</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>

<body>  
    <?php include_once 'header.php' ?>
<span class="flex font-bold text-xl justify-center ">
    MESSAGES LIST
</span>

    <div class="flex flex-col px-2">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-4 inline-block min-w-full sm:px-6 lg:px-8">
              <?php
              if($Empty)
              {
                  echo "<p>No Messages Yet!</p>";
              }
              else{
                  ?>
                  <div class="overflow-hidden">
              <table class="min-w-full text-center">
                <thead class="border-2 border-white bg-orange-500"> 
                  <tr>
                    <th scope="col" class="text-base font-medium text-white px-6 py-3">
                      Name
                    </th>
                    <th scope="col" class="text-base font-medium text-white px-6 py-3">
                      E-mail
                    </th>
                    <th scope="col" class="text-base font-medium text-white px-6 py-3">
                      Message
                    </th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                    while($msg=$messages->fetch_array())
                    {
                        ?>
                        <tr class="bg-white border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $msg['name'] ?></td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4">
                    <?php echo $msg['email'] ?>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4">
                    <?php echo $msg['message'] ?>
                    </td>
                  </tr>
                        <?php
                    }
                    ?>
                </tbody>
              </table>

                  <?php
              }
              ?>
            </div>
          </div>
        </div>
      </div>
</body>

</html>