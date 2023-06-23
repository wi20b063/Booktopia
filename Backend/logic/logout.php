<?php

include "session.php";

// remove all session variables
session_unset();

// destroy the session
session_destroy();

// unset cookies
setcookie("username", "", time() - 3600); // 86400 = 1 day / secure, http only


//gehe zu Startseite
header('Refresh: 0; URL = ../../Frontend/sites/index.php');

?>