<?php
include("session.php");
$loginId = $lib->checkLogin($loginId);
if($loginId != ""){
    setcookie("proId","",0,"/");
    session_destroy();
}
header("Location:login.php");
exit;
?>