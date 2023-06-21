<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once ($path . '/backend/logic/session.php');
include_once ($path . '/backend/logic/services/admin_manageUsers.php');
include_once ($path . '/backend/logic/services/userService.php');
include_once ($path . '/backend/logic/services/orderService.php');
include_once ($path . '/backend/models/user.php');

// for testing add, delete, modify user...
class Test extends User{

  }
  $myAdminUserObj = new adminUser($con, $tbl_user);
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

$res=$myAdminUserObj->getUsers();
$goshopping=new OrderService($con, $tbl_user);
$allItems= $goshopping->getAvailableProducts();
print_r($allItems);




print_R($res);
  
  



?>