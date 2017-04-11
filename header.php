<?php
include "session.php";
$file = file("./ptn/header.html");
$Content = join("",$file);
ob_start();

$adminHideStart = "";
$adminHideStop = "";
$redirect = 0;

if($loginId != "" && $loginId != 0){
    $loginURL = "logout.php";
    $loginText = "Logout";
}
 else {
    $loginURL = "login.php";
    $loginText = "Login";
 }

$Pat = "/<{adminHideStart}>(.*?)<{adminHideStop}>/s" ;
preg_match($Pat,$Content,$Output) ;
$SelectedContent = $Output[1] ;
$getUser = $db->getRecord("select userType from om_user where userId = '$loginId'");
$userType = $getUser['userType'];
if($userType != '1'){
    $Content = preg_replace($Pat,"<li><a href=\"logout.php\">Login as SuperAdmin to acces UserManagement</a></li>",$Content);
    $redirect = 1;
}

 /* Find Active Link */
 /* Work on Online Server */
 /* In Local you have to change the script */
 //Begin find active url link
 $scriptName = $_SERVER['SCRIPT_NAME'];
 $scriptName = @explode("/", $scriptName);
 $scriptName = @explode(".php", $scriptName[2]);
 $userActive = $brandActive = $modelActive = $contactActive = "";
 $aboutActive = $homeActive = $productActive = $categoryActive = "";
 if($scriptName[0] === "usermaster" || $scriptName[0] === 'register'){
     $userActive = "class = 'active'";
     if($redirect == 1){
         header("Location:index.php");
         exit;
     }
 }else if($scriptName[0] === "brandmaster" || $scriptName[0] === 'updbrandmaster'){
     $brandActive = "class='active'";
 }else if($scriptName[0] === "modelmaster" || $scriptName[0] === 'updmodelmaster'){
     $modelActive = "class = 'active'";
 }else if($scriptName[0] === "contact"){
     $contactActive = "class = 'active'";
 }else if($scriptName[0] === "index"){
     $homeActive = "class = 'active'";
 }else if($scriptName[0] === "about"){
     $aboutActive = "class = 'active'";
 }else if($scriptName[0] === "categorymgmt" || $scriptName[0] === 'categoryedit' || $scriptName[0] === 'categoryadd'){
     $categoryActive = "class = 'active'";
 }else if($scriptName[0] === "productmgmt" || $scriptName[0] === 'productedit' || $scriptName[0] === 'productadd'){
     $productActive = "class = 'active'";
 }
 //End find active url link
$Content = preg_replace("/<{(.*?)}>/e","$$1",$Content); 
$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;
?>