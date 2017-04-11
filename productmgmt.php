<?php
include "header.php" ;

$loginId = $lib->checkLogin($loginId);

$file = file("./ptn/productmgmt.html");
$Content = join("",$file);

$Message = "";
$page = isset($page)?$page:'1';
$act = isset($act)?$act:'';
$dispstatus = isset($dispstatus)?$dispstatus:'';
$msg = isset($msg)?$msg:'';

if($act == "d" && $id != ""){
    $db->insert("delete from product where productId = '$id'");
    $msg = "d";
}

if($dispstatus == "1" && $id != ""){
    $db->insert("update product set activeStatus='0' where productId='$id'");
}
else if($dispstatus == "0" && $id != ""){
        $db->insert("update product set activeStatus='1' where productId='$id'");
}

$Pat = "/<{Begin}>(.*?)<{End}>/s" ;
preg_match($Pat,$Content,$Output) ;
$SelectedContent = $Output[1] ;

$adjacents = 1;
$getRecord = $db->getRecord("select count(*) as Total from product") ;
$count = $getRecord['Total'];
$records = 10;
$links = "productmgmt.php?";
if($page == ""){
        $page = 1;
}
$start = ($page-1) * $records;
$Content = $lib->loadPagination($db, $Content, $adjacents, $count, $records, $links, $page);
$mysql = "select productId,productName,productPrice,activeStatus from product order by productName limit $start,$records";
$getRecords=$db->getRecords($mysql);

$Toreplace = "" ;
for($i = 0 ; $i < count($getRecords); $i++)
{
    $id = $getRecords[$i]['productId'];
    $productName = $getRecords[$i]['productName'];
    $productPrice = $getRecords[$i]['productPrice'];
    $activeStatus = $getRecords[$i]['activeStatus'];
    if($activeStatus == '0'){
        $StatusTitle = "Click to Active";
        $DisplayStatus = "btn btn-danger";
    }
    else if($activeStatus == '1'){
        $StatusTitle = "Click to InActive";
        $DisplayStatus = "btn btn-success";
    }
    $slno = $start + 1;
    $start=$slno;
    $Toreplace .= preg_replace("/{{(.*?)}}/e","$$1",$SelectedContent);
}
$Content = preg_replace($Pat,$Toreplace,$Content) ;

if($msg == "add"){
    $Message = "Product record added successfully";
}
else if($msg == "edit"){
    $Message = "Product record edited successfully";
}
else if($msg == "d"){
    $Message = "Product record deleted successfully";
}

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
	
include "footer.php" ;
?>
