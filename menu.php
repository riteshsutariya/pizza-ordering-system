<?php
include_once 'dbconfig.php';

//in next phase 
// hard to sort pizzas based on price because we have different sizes and different prices so there is no strong filter base
//to work this functionality every pizza must have price for small size!


if(isset($_GET['btnsearch'])=="search")
{
    // echo "search...";
    $p_qry = "SELECT * FROM pizzatb where name LIKE '%".$_GET['txtspizza']."%'";
    $pizzas = $con->query($p_qry);
    if ($pizzas) {
        if ($pizzas->num_rows > 0) {
        } else {
            $NotFound = true;
        }
    } else {
        die('Something went wrong!');
    }
}
else{  
    if(isset($_GET['type']))
    {
        if($_GET['type']=='default')
        {
            $p_qry = "SELECT * FROM pizzatb";
        }
        else{
            $p_qry = "SELECT * FROM pizzatb where type='".$_GET['type']."'";
        }
        $pizzas = $con->query($p_qry);
        if ($pizzas) {
            if ($pizzas->num_rows > 0) {
            } else {
                $NotFound = true;
            }
        } else {
            die('Something went wrong!');
        }
    }else
if(isset($_GET['filter']))
{
    // echo "filter...";
    // echo $_GET['filter'];
    if($_GET['filter']==='low to high')
    {
        // $p_qry = "SELECT * FROM pizzatb where pizzaid IN(SELECT pizzaid FROM pricetb WHERE size='small' order by price ASC) order by pizzaid";
        $p_qry="SELECT p.* from pizzatb p,pricetb pt WHERE p.pizzaid=pt.pizzaid AND pt.size='small' ORDER BY Price";
        
        // echo $p_qry;
        $pizzas = $con->query($p_qry);
        if ($pizzas) {
            if ($pizzas->num_rows > 0) {
            } else {
                $NotFound = true;
            }
        } else {
            die('Something went wrong!');
        }
        
    }
    else{
    if($_GET['filter']==='high to low')
    {
        // echo "<br><p style='color:red'>high to low pass</p>";
        $p_qry="SELECT p.* from pizzatb p,pricetb pt WHERE p.pizzaid=pt.pizzaid AND pt.size='small' ORDER BY Price desc";
        // echo $p_qry;
        $pizzas = $con->query($p_qry);
        if ($pizzas) {
            if ($pizzas->num_rows > 0) {
            } else {
                $NotFound = true;
            }
        } else {
            die('Something went wrong!');
        }
        
    }else{
        $p_qry = "SELECT * FROM pizzatb";
        $pizzas = $con->query($p_qry);
        
        if ($pizzas) {
            if ($pizzas->num_rows > 0) {
            } else {
                $Empty = true;
            }
        } else {
            die('Something went wrong!');
        }
        }
    }
}
else{
    // echo "default...";
    $p_qry = "SELECT * FROM pizzatb";
    $pizzas = $con->query($p_qry);
    
    if ($pizzas) {
        if ($pizzas->num_rows > 0) {
        } else {
            $Empty = true;
        }
    } else {
        die('Something went wrong!');
    }
}   
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>

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

     <!-- SEARCH AND Filter And Sort Button -->
     <div class="flex justify-between mt-8 w-full">
        <!-- <div class="flex mt-5"> -->
            <form action="" method="get">
                <div class="flex ml-20">
                <div class="">
                    <!--  class="p-1 w-fit ml-20 border-black border-2 rounded-lg sm:text-sm" -->
               
                    <form action="" method="get">
                    <input type="text" name="txtspizza" id="search-navbar" class="text-sm sm:text-base placeholder-gray-500 pl-2 w-80 rounded-lg border border-gray-400 py-2"
                placeholder="search pizza" required>
                </div>
                <div class="bg-orange-400 p-1.5 rounded-lg border">
                    <input id="sbmt-btn" class="cursor-pointer" name="btnsearch" type="submit" value="search">
                <!-- <button class="w-6 hover:scale-105"><img src="images/search.png"></button> -->
                    </form>
                
                </div>
                </div>
                
            </form>
        <!-- </div> -->

    </div>

    <!-- Filter And Sort -->
    <div class="flex justify-end">
        <!-- Filter -->
        <div class="flex-col mr-10 lg:-mt-12 md:-mt-12">
            <!-- <form action="" id="filters" class="flex-col mr-10 "> -->
               <select name="ptype" id="types" class="mr-10 text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2">
               <option value="default">Types: All</option>
                   <?php 
                   $type=$con->query("SELECT distinct pizzatb.type from pizzatb");
                   if($type)
                   {
                    if($type->num_rows>0)
                    {
                        while($t=$type->fetch_array())
                        {
                            ?>
                            <option value="<?php echo $t['type'] ?>" <?php 
                            if(isset($_GET['type']))
                            {
                                if($_GET['type']===$t['type']) 
                                {
                                    echo "selected";
                                } 
                            } ?>><?php echo $t['type'] ?></option>
                            <?php
                        }
                    }
                   }
                   else{
                   ?>
                   <!-- <option value="all">All</option> -->
                   <?php
                   }
                   ?>
               </select>
            <!-- </form> -->
        </div>

        <!-- Sort  -->
        <div class="flex-col lg:-mt-12 md:-mt-12 mr-24">
               <select  name="price_sort" id="filter" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400 w-full py-2">
               <option value="default">price: All</option>
               <option value="high to low" <?php if(isset($_GET['filter'])){ if($_GET['filter']==='high to low') echo "selected";}else{echo "";}  ?>>High to Low</option>
               <option value="low to high" <?php if(isset($_GET['filter'])){if($_GET['filter']==='low to high') echo "selected";}else{echo "";}  ?>>Low to High</option>
               </select>
        </div>
    </div>

    <?php
    if (isset($Empty)) {
        die("No Items Found!");
    } else if(isset($NotFound)){
       ?>
       <p class="mx-20 my-4">No Pizzas Found For <span class="text-orange-500"><?php echo $_GET['txtspizza']; ?></span> click <a href="menu.php" class="underline">here</a> to refresh</p>
       <?php
    } else{ ?>
        <div class="grid grid-cols-4">
            <?php
            while ($pizza = $pizzas->fetch_array()) {
            ?>
                <div class="m-10">
                    <img src=" <?php echo $pizza['image_url'] ?>" class="w-40 ml-16">
                    <h3 class="uppercase text-center text-xl font-bold mt-3"><?php echo $pizza['name'] ?></h3>
                    <p class="uppercase text-center text-lg font-bold mt-3 price"></p>
                    <p class="text-center text-lg font-bold mt-3 text-gray-500"><?php echo $pizza['description'] ?></p>

                    <select name="pizza size" id="size<?php echo $pizza['pizzaid'];?>" class="text-sm sm:text-base placeholder-gray-500 pl-2 rounded-lg border border-gray-400  py-2 sizes" onchange="priceUpdate()">
                        <?php
                        $price_qry = "SELECT * FROM pricetb where pizzaid=" . $pizza['pizzaid'];
                        $price_res = $con->query($price_qry);
                        while ($row = $price_res->fetch_array()) {
                        ?>
                            <option value=<?php echo $row['price'] ?>><?php echo $row['size'] ?></option>
                        <?php
                        }
                        ?>
                        <!-- <option value="small">Small</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option> -->
                    </select>
                    <button class="w-fit m-5 px-2 py-2 bg-orange-500 rounded-lg hover:scale-105 disabled:bg-gray-300 disabled:hover:scale-100" <?php 
                    if(isset($cart_items))
                    {
                        $cartItms=$con->query("SELECT pizzaid from cartitemstb WHERE cartid=(SELECT cartid FROM carttb WHERE custid=".$_SESSION['userId'].")");
                        if($cartItms)
                        {
                            if($cartItms->num_rows>0)
                            {
                                while($itm=$cartItms->fetch_array())
                                {
                                    if($itm['pizzaid']===$pizza['pizzaid'])
                                    {
                                        echo "disabled";
                                    }
                                }
                            }
                        }
                        else{
                            die("something went wrong!!-disable func");
                        }
                    }
                    ?>
                    onclick="AddItemCart(<?php echo $pizza['pizzaid']?>)">Add To Cart</button>
                </div>
        <?php
            }
        }
        ?>
        </div>

        <script>
            const priceElems = document.getElementsByClassName("price");
            const sizes = document.getElementsByClassName("sizes");

            reloadprices();
            function reloadprices() {
                for (let i = 0; i < priceElems.length; i++) {
                    // select.options[select.selectedIndex].value;
                    // priceElems[i].removeChild(priceElems[i].firstChild);
                    console.log(sizes[i].options[sizes[i].selectedIndex].value);
                    const textnode = document.createTextNode(sizes[i].value + "₹");
                    
                    priceElems[i].appendChild(textnode);
                }
            }

            function priceUpdate() {
                console.log("price update");
                for (let i = 0; i < priceElems.length; i++) {
                    const textnode = document.createTextNode(sizes[i].value + "₹");
                    priceElems[i].replaceChild(textnode,priceElems[i].childNodes[0]);
                }
            }

        const filter = document.querySelector('#filter');
        // const filter_menu = document.querySelector('#filters');

        filter.addEventListener('change', (e) => {
            console.log(e.target.value);
            window.location.replace("menu.php?filter="+e.target.value);
        });

        const types=document.querySelector("#types");

        types.addEventListener('change',(e)=>{
            console.log(e.target.value);
            window.location.replace("menu.php?type="+e.target.value);
        })


        function AddItemCart(pid)
        {
            let e=document.getElementById("size"+pid);
            const size=e.options[e.selectedIndex].text;
            // e.options[e.selectedIndex].text;
            // console.log(inner_price.substring(0,inner_price.length-1));
            // const price= parseInt(inner_price.substring(0,inner_price.length-1));
            console.log(pid,size);
            cid=124846;
           if(window.localStorage.getItem('userid')==null)
            {
                // window.localStorage.setItem('cartid',cid);
                window.location.replace("addtocart.php?&pid="+pid+"&size="+size);
            }
            else{
                sid=window.localStorage.getItem('userid');
                window.location.replace("addtocart.php?&pid="+pid+"&size="+size);
            }
            // console.log("item add to cart with pizzaid "+pid);
        }
        </script>
</body>

</html>