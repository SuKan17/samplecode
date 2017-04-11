<?php
include "header.php";

$loginId = $lib->checkLogin($loginId);

$file = file("./ptn/brandmaster.html");
$Content = join(" ",$file);

$act = isSet($act) ? $act : '';
$id = isSet($id) ? $id : '';
$status = isSet($status) ? $status : '';
$Message = isSet($Message) ? $Message : '';
$DisplayEditLink = '';

if($status == "1" && $id != "") {
    $db->insert("update brandmaster set activeStatus='0' where brandId='$id'");
    header("location:brandmaster.php");
    exit ;
}
else if($status == "0" && $id != "") {
    $db->insert("update brandmaster set activeStatus='1' where brandId='$id'");
    header("location:brandmaster.php");
    exit ;
}
else if($status == "3" && $id != "") {
    $db->insert("delete from brandmaster where brandId='$id'");
    $db->insert("delete from modelmaster where brandId='$id'");
    $db->insert("delete from productmaster where brandId='$id'");
    header("location:brandmaster.php?act=del");
    exit ;
}

$Pat = "/<{Begin}>(.*?)<{End}>/s";
preg_match($Pat,$Content,$Output);
$SelectedContent = $Output[1];
$Toreplace = "";

$getRecords=$db->getRecords("select * from brandmaster order by brandName");
if(count($getRecords)==0)
    $Message="No record found.";

for($i = 0 ; $i < count($getRecords) ; $i++) {
    @extract($getRecords[$i]);
    $idvalue = $brandId;
    $brandName = html_entity_decode(stripslashes($brandName));
   
    if($activeStatus == '0'){
        $StatusTitle = "Click to Active";
        $DisplayStatus = "btn btn-danger";
        $DisplayEditLink = '- -';
    }
    else if($activeStatus == '1'){
        $StatusTitle = "Click to InActive";
        $DisplayStatus = "btn btn-success";
        $DisplayEditLink = "<a href='updbrandmaster.php?upd=2&id=$idvalue' onclick='return confirm(\"Are you sure to edit?\");' title='Edit' class='btn btn-info'>Edit</a>";
    }

    $slno = $i + 1;
    $Toreplace .= preg_replace("/{{(.*?)}}/e","$$1",$SelectedContent);
}

$Content = preg_replace($Pat,$Toreplace,$Content);

if($act == "add")
    $Message = "Brand Master record added successfully.";
else if($act == "upd")
    $Message = "Brand Master record updated successfully.";
else if($act == "del")
    $Message = "Brand Master record and releated records deleted successfully.";

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;

include "footer.php";
?>
