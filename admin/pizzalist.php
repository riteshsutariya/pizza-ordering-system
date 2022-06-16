<?php
include_once '../dbconfig.php';

$p_qry = "SELECT * FROM pizzatb";
$pizzas = $con->query($p_qry);
$Empty = false;
if ($pizzas) {
  if ($pizzas->num_rows > 0) {
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
  <title>Admin(Pizza List)</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>

<body>
  <?php include_once 'header.php' ?>
  <span class="flex font-bold text-xl justify-center ">
    PIZZAS LIST
  </span>

  <div class="flex flex-col px-2">

    <?php
    if ($Empty) {
      echo "<p>No pizza found!";
    } else {
    ?>

      <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-4 inline-block min-w-full sm:px-6 lg:px-8">
          <div class="overflow-hidden">
            <table class="min-w-full text-center">
              <thead class="border-2 border-white bg-orange-500">

                <tr>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Pizza Id
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Name
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Size
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Prices
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Category
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Image
                  </th>
                  <th scope="col" class="text-base font-medium text-white px-6 py-3">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody>

                <?php
                while ($pizza = $pizzas->fetch_array()) {
                ?>
                  <tr class="bg-white border-b">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $pizza['pizzaid'] ?></td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <?php echo $pizza['name'] ?>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <select name="" class="sizes" id="" onchange="priceUpdate()">
                        <?php
                        $price_qry = "SELECT * FROM pricetb where pizzaid=" . $pizza['pizzaid'];
                        $price_res = $con->query($price_qry);
                        while ($row = $price_res->fetch_array()) {
                        ?>
                          <option value=<?php echo $row['price'] ?>><?php echo $row['size'] ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </td>

                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <p class="price"></p>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <?php echo $pizza['type']; ?>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                      <img src=".<?php echo $pizza['image_url'] ?>" width="250" alt="img">
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap space-x-2">
                      <button class="rounded inline-flex items-center hover:scale-110">
                        <a href="deletepizza.php?id=<?php echo $pizza['pizzaid']; ?>">
                          <img src="../images/deleteicon.jpeg" class="h-5 w-5">
                        </a>
                      </button>
                      <button class="rounded inline-flex items-center hover:scale-110">
                        <a href="updatepizza.php?id=<?php echo $pizza['pizzaid']; ?>">
                          <img src="../images/updateicon.jpeg" class="h-5 w-5">
                        </a>
                      </button>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
  </div>

  <script>
    const priceElems = document.getElementsByClassName("price");
    const sizes = document.getElementsByClassName("sizes");

    function reloadprices() {
      // console.log("reload prices");
      for (let i = 0; i < priceElems.length; i++) {
        // select.options[select.selectedIndex].value;
        // priceElems[i].removeChild(priceElems[i].firstChild);
        console.log(sizes[i].options[sizes[i].selectedIndex].value);
        const textnode = document.createTextNode(sizes[i].value + "₹");

        priceElems[i].appendChild(textnode);
      }
    }

    reloadprices();

    function priceUpdate() {
      console.log("price update");
      for (let i = 0; i < priceElems.length; i++) {
        const textnode = document.createTextNode(sizes[i].value + "₹");
        priceElems[i].replaceChild(textnode, priceElems[i].childNodes[0]);
      }
    }
  </script>
</body>

</html>