<?php
include "header.php";

$loginId = $lib->checkLogin($loginId) ;

$file = file("./ptn/register.html");
$Content = join("",$file);
$frmToken = isset($frmToken)?$frmToken:'';
$submit = isset($submit)?$submit:'';
$userEmail = isset($userEmail)?$userEmail:'';
$errorMsg = "";

if($submit == "Create New User"){
    $userEmail = trim($userEmail);
    if($userEmail != ""){
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Please Enter proper email id.<br>";
        }
    }
    else{
        $errorMsg .= "Please enter email id.<br>";
    }
    if($userPass1 != "" && strlen($userPass1) > 5 && $userPass2 != "" && strlen($userPass2) > 5){
        if($userPass1 !== $userPass2){
            $errorMsg .= "Password does not match.<br>";
        }
    }
    else{
        $errorMsg .= "Please enter password minimum 6 characters.<br>";
    }
    $getRecord = $db->getRecord("select count(*) as total from om_user where userEmail ='$userEmail'");
    if($getRecord['total'] > 0){
        $errorMsg .= "Email id is already registered.";
        $userEmail = "";
    }
    if($frmToken == $_SESSION['feedtoken']) {
        unset($_SESSION["feedtoken"]);
        $set = "";
        $pass = password_hash($userPass1, PASSWORD_DEFAULT);
        $set = "userEmail = '$userEmail'";
        $set .= ",userPass = '$pass'";
        $set .=  ",userLastLogin = '0'";
        $set .= ",createDate = '" . time() ."'";
        $set .= ",userStatus = '1'";
        if($errorMsg === ""){
          $db->insert("insert into om_user set $set");  
          header("location:usermaster.php?act=add");
          exit;
        }
    }
}

$_SESSION['feedtoken'] = md5(session_id() . time());
$frmToken = $_SESSION['feedtoken'];
$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;
include "footer.php";
?>