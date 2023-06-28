<?php
 $path = $_SERVER['DOCUMENT_ROOT'];

require_once ($path."/config/dbaccess.php");
require_once ($path .'/backend/logic/session.php');
include ($path.'/Archive/navAdmin.php'); // needs to be changed

class admin extends User {
// no need for admin object any more.

}

?>