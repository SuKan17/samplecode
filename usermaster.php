<?php
include "header.php";

$loginId = $lib->checkLogin($loginId);

$file = file("./ptn/usermaster.html");
$Content = join(" ",$file);

$act = isSet($act) ? $act : '';
$id = isSet($id) ? $id : '';
$status = isSet($status) ? $status : '';
$Message = isSet($Message) ? $Message : '';
$DisplayEditLink = '';

if($status == "1" && $id != "") {
    $db->insert("update om_user set userStatus='0' where userId='$id'");
    header("location:usermaster.php");
    exit ;
}
else if($status == "0" && $id != "") {
    $db->insert("update om_user set userStatus='1' where userId='$id'");
    header("location:usermaster.php");
    exit ;
}

$Pat = "/<{Begin}>(.*?)<{End}>/s" ;
preg_match($Pat,$Content,$Output) ;
$SelectedContent = $Output[1] ;
$Toreplace = "" ;

$getRecords=$db->getRecords("select * from om_user order by userEmail");
if(count($getRecords)==0)
    $Message="No record found.";

for($i = 0 ; $i < count($getRecords) ; $i++) {
    @extract($getRecords[$i]) ;
    $idvalue = $userId ;
   
    if($userStatus == '0'){
        $StatusTitle = "Click to Active";
        $DisplayStatus = "btn btn-danger";
    }
    else if($userStatus == '1'){
        $StatusTitle = "Click to InActive";
        $DisplayStatus = "btn btn-success";
    }

    $slno = $i + 1;
    $Toreplace .= preg_replace("/{{(.*?)}}/e","$$1",$SelectedContent);
}

$Content = preg_replace($Pat,$Toreplace,$Content);

if($act == "add")
    $Message = "User record added successfully.";

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;

include "footer.php";
?>