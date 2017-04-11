<?php
include "header.php";

$loginId = $lib->checkLogin($loginId);

$file = file("./ptn/index.html");
$Content = join("",$file);

$getRecord = $db->getRecord("select count(*) as countUser from om_user where userStatus='1'");
@extract($getRecord);
$getRecord = $db->getRecord("select count(*) as countCategory from category where activeStatus='1'");
@extract($getRecord);
$getRecord = $db->getRecord("select count(*) as countProduct from product where activeStatus='1'");
@extract($getRecord);
$getRecord = $db->getRecord("select userEmail from om_user where userId='$loginId'");
$user = @explode("@",$getRecord['userEmail']);
$userEmail = $user[0];

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;
include "footer.php";
?>