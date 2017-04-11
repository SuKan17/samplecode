<?php
include "header.php" ;

$loginId = $lib->checkLogin($loginId);

$file = file("./ptn/categorymgmt.html");
$Content = join("",$file);

$Message = "";
$page = isset($page)?$page:'1';
$act = isset($act)?$act:'';
$dispstatus = isset($dispstatus)?$dispstatus:'';
$msg = isset($msg)?$msg:'';

if($act == "d" && $catid != ""){
    $db->insert("delete from category where categoryId = '$catid'");
    $db->insert("delete from product where categoryId = '$catid'");
    $msg = "d";
}

if($dispstatus == "1" && $catid != ""){
    $db->insert("update category set activeStatus='0' where categoryId='$catid'");
}
else if($dispstatus == "0" && $catid != ""){
        $db->insert("update category set activeStatus='1' where categoryId='$catid'");
}

$Pat = "/<{Begin}>(.*?)<{End}>/s" ;
preg_match($Pat,$Content,$Output) ;
$SelectedContent = $Output[1] ;

$adjacents = 1;
$getRecord = $db->getRecord("select count(*) as Total from category") ;
$count = $getRecord['Total'];
$records = 10;
$links = "categorymgmt.php?";
if($page == ""){
        $page = 1;
}
$start = ($page-1) * $records;
$Content = $lib->loadPagination($db, $Content, $adjacents, $count, $records, $links, $page);
$mysql = "select categoryId,catName,activeStatus from category order by catName limit $start,$records";
$getRecords = $db->getRecords($mysql);

$Toreplace = "" ;
for($i = 0 ; $i < count($getRecords); $i++)
{
    $id = $getRecords[$i]['categoryId'];
    $catName = html_entity_decode(stripslashes($getRecords[$i]['catName']));
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
$Content = preg_replace($Pat,$Toreplace,$Content);

if($msg == "add"){
    $Message = "Category record added successfully";
}
else if($msg == "edit"){
    $Message = "Category record edited successfully";
}
else if($msg == "d"){
    $Message = "Category record and releated product records deleted successfully";
}

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
	
include "footer.php" ;
?>
