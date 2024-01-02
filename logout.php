<?php
session_start();
include "database/pdo_connection.php";
$_SESSION = array();
session_regenerate_id(true);
session_destroy();
header("location:login.php");
exit();
?>
