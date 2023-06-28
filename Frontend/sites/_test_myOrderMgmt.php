<!DOCTYPE html>

<html lang="EN">

<head>

    <?php 
    
    //path var necessary to overcome include relative path of an included file from different folder
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once ($path."/Frontend/sites/components/head.php");
    //require_once ($path."/Frontend/sites/components/head2.php");
    require_once ($path. '/Backend/models/user.php');
    require_once ($path . '/Backend/logic/services/admin_manageUsers.php');
    require_once ($path . '/Backend/logic/services/userService.php');
    require_once ($path . '/Backend/logic/services/orderService.php');


    class Test extends OrderService{

    }
    /* $myAdminUserObj = new adminUser($con, $tbl_user);
     $myAdminUser = new Test(11,"Frau","Mel", "C", "Hollywood Drive", "11334", "Santa Barbara", "123456", "test@ee.aa", "melc", "melc", 1,0);/* 
     $myAdminUser2 = new Test(99,  'Frau', 'Lucy', 'Lu', 'SunsetDr 22105', '21568', 'Los Angeles', '4548888554321234','lucy@lu.us','lucy', 'lucy' );
     $myAdminUser3 = new Test( 99, 'Frau', 'Macy', 'Mae', 'EastridgeDr 55105', '44568', 'Reno', '454919195543222222',',macy@mae.us','macy', 'macy' ); */
  
   // TEST delete user
   //$res2= $myAdminUserObj->deleteUser(35);
  
      //TEST add user...
      
    //$myAdminUserObj = new adminUser($con, $tbl_user);
    //$res=$myAdminUserObj->addUser($myAdminUser);
    //$res=$myAdminUserObj->addUser($myAdminUser2);
   // $res=$myAdminUserObj->addUser($myAdminUser3);
    // TEST update user
  
    //$myAdminUserObj = new adminUser($con, $tbl_user);
    /* $myAdminUser->email="changedEmail@addy.com";
    $res = $myAdminUserObj->updateUser(34,$myAdminUser); */
  
  //$res=$myAdminUserObj->getUsers();
  //$goshopping=new OrderService($con, $tbl_user);
  //$allItems= $goshopping->getAvailableProducts();
  //print_r($allItems);
  
  //$order=new OrderService($con, $tbl_order, $tbl_book, $tbl_order_details, $tbl_user, $tbl_payment_items);
  //$myOrder1=$order->fetchAllOrders();
  
  //$order2= new OrderService($con, $tbl_order, $tbl_book, $tbl_order_details, $tbl_user, $tbl_payment_items);
  //$myOrder2=$order2->fetchUserOrders(30); //Sample  with 2 Orders
  
    
  

    ?>
    <title>Booktopia | Testplatform-only</title>

</head>

<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include_once ($path."/Frontend/sites/components/navBar.php");?>
    </nav>
    <main>
<body>
<!-- --------This block is inserted by the Ajax result -------- -->
    <div class="component" name="dynComponentDivUsr" id="dynComponentDivUsr" >
         
        
    </div> 
<!-- -------- End of  Ajax result insert block ---------->               
    </main>

    <footer class="py-3 my-4 fixed-bottom">
        <?php include_once ($path."/Frontend/sites/components/footer.php");?>
    </footer>
<script type="text/javascript">


       $(document).ready(function() {
        var users;
        fetchUserOrders(30);
      }); 


function filterUserTable(filter, tableid, col1, col2){
    filter = filter.toUpperCase();
  table = document.getElementById(tableid);
  tr = table.getElementsByTagName("tr");
  len=tr.length;
  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td1 = tr[i].getElementsByTagName("td")[col1];
    td2 = tr[i].getElementsByTagName("td")[col2];
    if (td1 && td2) {
      txtValue1 = td1.textContent || td1.innerText;
      txtValue2 = td2.textContent || td2.innerText;
      if (txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1 ) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
    </script>
</body>

</html>

<?php

// for testing add, delete, modify user...




?>