<?php
include_once 'dbconfig.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$item_count = 0;
//  $cart_res=$con->query("SELECT pizzaid FROM cartitemstb WHERE cartid=(
//   SELECT cartid FROM carttb WHERE custid=".$_SESSION['userId'].")");
$cart_res = $con->query("SELECT * FROM cartitemstb WHERE cartid=(
  SELECT cartid FROM carttb WHERE custid=" . $_SESSION['userId'] . ")");

// var_dump($cart_cnt);
if ($cart_res) {
  if ($cart_res->num_rows > 0) {
    $item_count = $cart_res->num_rows;
    //  $pizzas=$con->query("SELECT * from pizzatb where pizzaid IN(SELECT pizzaid FROM cartitemstb WHERE cartid=(
    // SELECT cartid FROM carttb WHERE custid=".$_SESSION['userId']."))");
    //  var_dump($item_count);
  }
} else {
  die("error while fetching cart!");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Ubuntu:wght@700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="shortcut icon" href="\images\favicon.ico" type="image/x-icon">

</head>

<body>  
  <?php include_once 'header.php' ?>
  <?php
  if ($item_count > 0) {
  ?>
    <div class="flex justify-between mt-5">
      <div class="flex-col w-fit   bg-white shadow rounded-xl ml-12">
        <div class="flex w-fit">
          <h1 class="text-2xl font-semibold lg:ml-20 ml-5 mt-5">Pizzas</h1>
          <div class="mt-5 ml-72">
            <p class="text-gray-600">Total (<?php echo $item_count; ?> items)</p>
            <p class="font-bold text-lg"><span class="tot-amt"></span>₹</p>
          </div>
        </div>
        <hr class="border-slate-900 lg:ml-20 mr-36">

        <?php
        while ($item = $cart_res->fetch_array()) {
          //get pizzaid,quantity,priceid
          $pizzaqry = $con->query("SELECT * FROM pizzatb WHERE pizzaid=" . $item['pizzaid']);
          $pizza = $pizzaqry->fetch_array();
          $priceqry = $con->query("SELECT * FROM pricetb where priceid=" . $item['price_id']);
          $price = $priceqry->fetch_array();
        ?>
          <div class="flex items-center mt-10 w-fit">
            <img src="<?php echo $pizza['image_url']; ?>" class="w-32 h-16 lg:ml-20 ml-5">
            <div class="flex-col ml-56">
              <h1 style="max-width: 224px;" class="font-bold text-lg"><?php echo $pizza['name']; ?></h1>
              <select name="pizza_size" id="size<?php echo $pizza['pizzaid']; ?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400  py-2 sizes" onchange="priceUpdate(<?php echo $pizza['pizzaid']; ?>,<?php echo $item['cartid'] ?>)">
                <?php
                $price_qry = "SELECT * FROM pricetb where pizzaid=" . $pizza['pizzaid'];
                $price_res = $con->query($price_qry);
                while ($row = $price_res->fetch_array()) {

                  echo $price['size'];
                  echo "<br/>" . $row['size'];
                ?>
                  <option value=<?php
                                echo $row['price'] ?> <?php if (strcmp($row['size'], $price['size']) == 0) {
                                                  echo "selected";
                                                } ?>><?php echo $row['size'] ?></option>
                <?php
                }
                ?>
              </select>
              <p class="pr-60 font-bold text-gray-600"><button class="px-1 text-xl" onclick="decreaseQuantity(<?php echo $item['cartid'] ?>,<?php echo $item['pizzaid']; ?>,<?php echo $item['quantity']; ?>)">-</button>Qty <span class="quantities"><?php echo $item['quantity']; ?></span><button class="px-1 text-2xl" onclick="increaseQuantity(<?php echo $item['cartid'] ?>,<?php echo $item['pizzaid']; ?>,<?php echo $item['quantity']; ?>)">+</button></p>

              <!-- <p class="mr-52 font-bold text-lg price">₹<span><?php echo $price['price']; ?></span></p> -->
              <p class="mr-52 text-lg font-bold"><span class="price"><?php echo $price['price']*$item['quantity']; ?></span> ₹</p>
            </div>
          </div>
        <?php
        }
        ?>
        <span class="lg:ml-20 mr-36 mt-10"></span>
      </div>

      <div class="flex-col w-fit mr-24">
        <div class="bg-slate-100 p-6 bg-white shadow rounded-xl">
          <p class="text-xl font-bold text-center">Payment Details</p>
          <div class="flex space-x-2">
            <div class="mt-5 px-14">
              <p>Total Amount: <span class="tot-amt"></span>₹</p>
            </div>
          </div>
        </div>

        <a href="review_order.php">
        <button class="px-4 py-2 mt-4 ml-20 h-fit bg-orange-500 font-bold rounded-lg hover:scale-105">Place Order</button>
        </a>
        
      </div>

    </div>
  <?php
  } else {
  ?>
    <div class="flex justify-center flex-col mt-16">
      <div class="img flex justify-center mb-16">
        <img style="object-fit: none;" class="sm:scale-100" src="./images/empty.png" alt="">
      </div>
      <div class="flex justify-center">
        <button class="text-white text-sm bg-orange-500 hover:scale-105 rounded py-2 w-40 mb-8">
          <a href="menu.php">Buy Now</a>
        </button>
      </div>
    </div>
  <?php
  }
  ?>
</body>
<script>
  const priceElems = document.getElementsByClassName("price");
  const sizes = document.getElementsByClassName("sizes");
  const totEle=document.getElementsByClassName("tot-amt");
  const qtys=document.getElementsByClassName("quantities");

  // reloadprices();
  getTotal();

 /* function reloadprices() {
    for (let i = 0; i < priceElems.length; i++) {
      // select.options[select.selectedIndex].value;
      // priceElems[i].removeChild(priceElems[i].firstChild);
      console.log(sizes[i].options[sizes[i].selectedIndex].value);
      const textnode = document.createTextNode(sizes[i].value + "₹");

      priceElems[i].appendChild(textnode);
    }
  }
*/
  function priceUpdate(pid,cid) {
    // console.log("size"+pid);
    const Ele=document.getElementById("size"+pid);
    const size=Ele.options[Ele.selectedIndex].text;
    // console.log(Ele.options[Ele.selectedIndex].text);
    window.location.replace("modify_size.php?cid="+cid+"&pid="+pid+"&size="+size);
    /*
    console.log("price update");
    for (let i = 0; i < priceElems.length; i++) {
      const textnode = document.createTextNode(sizes[i].value + "₹");
      priceElems[i].replaceChild(textnode, priceElems[i].childNodes[0]);
    }
    reloadTotal();*/
  }

  function reloadTotal() {
    let tot = 0;
    for (let i = 0; i < priceElems.length; i++) {
      let indPrice = parseInt(sizes[i].options[sizes[i].selectedIndex].value);
      tot += indPrice; 
    }
    for(let i=0;i<totEle.length;i++)
    {
      const txtNode=document.createTextNode(tot);
      console.log(txtNode);
      totEle[i].replaceChild(txtNode,totEle[i].childNodes[0]);
    }
    console.log(tot);
  }

  function getTotal() {
    let tot = 0;
    for (let i = 0; i < priceElems.length; i++) {
      let indPrice = parseInt(sizes[i].options[sizes[i].selectedIndex].value);
      let indQty=parseInt(qtys[i].innerHTML);
      let indtot=indPrice*indQty;
      tot += indtot; 
    }
    for(let i=0;i<totEle.length;i++)
    {
      const txtNode=document.createTextNode(tot);
      console.log(txtNode);
      totEle[i].appendChild(txtNode);
    }
    console.log(tot);
  }

  function increaseQuantity(cid,pid,qty)
  {
    console.log("increase Quantity for cartid "+cid+"and pizzaid "+pid);
    window.location.replace("modify_quantity.php?action=inc&cid="+cid+"&pid="+pid+"&c_qty="+qty);
  }
  function decreaseQuantity(cid,pid,qty)
  {
    console.log("increase Quantity for cartid "+cid+"and pizzaid "+pid+"&c_qty="+qty);
    window.location.replace("modify_quantity.php?action=dec&cid="+cid+"&pid="+pid+"&c_qty="+qty);
  }
</script>

</html>