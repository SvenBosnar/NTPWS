<?php
session_start();

session_unset();
session_destroy();

//Redirekcija na login
header("Location: login.php");
exit();
?>
