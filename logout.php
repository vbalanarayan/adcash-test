<?php

session_start();
session_destroy();
$_SESSION = array();
setcookie(session_name(),"");

header('Location:login.php');
?>