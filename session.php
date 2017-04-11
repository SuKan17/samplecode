<?php
include "./oframe/include.php";
$submit = isset($submit)?$submit:'';
session_start();
class Session 
{
    function verifylogin($db,$lib,$oUser,$oPass) 
    {
        $getRecord = $db->getRecord("select * from om_user where userEmail='$oUser' and userStatus='1'");
        $storePass= $getRecord['userPass'];
        if(password_verify($oPass, $storePass))// valid entry
        {
            $loginId=$getRecord["userId"];
            $hash=$lib->hashUserId($loginId);
            setcookie("proId",$hash,0,"/");
            $CurrentTimeStamp=time();
            $db->insert("update om_user set userLastLogin='$CurrentTimeStamp' where userId='$loginId'");
            return $loginId;
        }
        else {
            header("Location:login.php?err=inv");
            exit;
        }
    }
}
$obj=new Session;
if($submit=="Sign In"){
    $userEmail = trim($userEmail);

    if($userEmail != '' && $userPass != '') {
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)){
            header("Location:login.php?err=inv");
            exit;
        }
        else{
            $loginId = $obj->verifylogin($db, $lib, $userEmail, $userPass);
        }
    }
    else {
            header("Location:login.php?err=inv");
            exit;
    }
}
else
{
    $hash = isset($_COOKIE["proId"])?$_COOKIE["proId"]:"";
    $loginId = $lib->getUserId($hash);
    if($loginId == ""){
        session_destroy();
        setcookie("proId","");
    }
}
?>