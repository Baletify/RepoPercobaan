<?php 
session_start();

if( !isset($_SESSION["login"])){

  echo "<script>alert('Anda Belum Login!');window.location.replace('login/login.php');</script>;";
}


//menghapus session
$_SESSION = [];
session_unset();
session_destroy();
header("Location: login/login.php");

?>