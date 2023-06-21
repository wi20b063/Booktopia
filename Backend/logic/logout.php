<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once ($path .'/backend/logic/session.php');

// remove all session variables
session_unset();

// destroy the session
session_destroy();

//gehe zu Startseite
header('Refresh: 0; URL = ../index.php');

?>