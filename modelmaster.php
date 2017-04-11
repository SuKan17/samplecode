<?php
include "header.php" ;

$loginId = $lib->checkLogin($loginId) ;

$file = file("./ptn/modelmaster.html") ;
$Content = join(" ",$file) ;

$act = isSet($act) ? $act : '' ;
$id = isSet($id) ? $id : '' ;
$status = isSet($status) ? $status : '' ;
$Message = isSet($Message) ? $Message : '' ;
$DisplayEditLink = '';
$bd = isSet($bd) ? $bd : '0' ;
$FlrQry = "";
$noOfProd=0;
if($status == "1" && $id != "") {
    $db->insert("update modelmaster set activeStatus='0' where modelId='$id'");
    header("location:modelmaster.php?bd=$bd");
    exit ;
}
else if($status == "0" && $id != "") {
    $db->insert("update modelmaster set activeStatus='1' where modelId='$id'");
    header("location:modelmaster.php?bd=$bd");
    exit ;
}
else if($status == "3" && $id != "") {
    $db->insert("delete from modelmaster where modelId='$id'");
    $db->insert("delete from productmaster where modelId='$id'");
    header("location:modelmaster.php?act=del");
    exit ;
}

if($bd != 0){
    $FlrQry = "where brandId='$bd'";
}

$Pat = "/<{Begin}>(.*?)<{End}>/s" ;
preg_match($Pat,$Content,$Output) ;
$SelectedContent = $Output[1] ;
$Toreplace = "" ;

$getRecords=$db->getRecords("select * from modelmaster $FlrQry order by modelName");
if(count($getRecords)==0)
    $Message="No Record found";

for($i = 0 ; $i < count($getRecords) ; $i++) {
    @extract($getRecords[$i]) ;
    $idvalue = $modelId ;
    $modelName = html_entity_decode(stripslashes($modelName));

    $getBrandRecord = $db->getRecord("select brandName from brandmaster where brandId ='$brandId'");
    @extract($getBrandRecord) ;
    $brandName = html_entity_decode(stripslashes($brandName));

    if($activeStatus == '0'){
        $StatusTitle = "Click to Active";
        $DisplayStatus = "btn btn-danger";
        $DisplayEditLink = '- -';
    }
    else if($activeStatus == '1'){
        $StatusTitle = "Click to InActive";
        $DisplayStatus = "btn btn-success";
        $DisplayEditLink = "<a href='updmodelmaster.php?upd=2&bd=$bd&id=$idvalue' onclick='return confirm(\"Are you sure to edit?\");' title='Edit' class='btn btn-info'>Edit</a>";
    }

    $slno = $i + 1 ;
    $Toreplace .= preg_replace("/{{(.*?)}}/e","$$1",$SelectedContent) ;
}

$Content = preg_replace($Pat,$Toreplace,$Content) ;

$BrandList = "<option value='0'>- - All Brand Names - -</option>";
$rs = $db->getRecords("select brandId,brandName from brandmaster order by brandName");
for($i = 0; $i < count($rs) ; $i++){
    $dropid = $rs[$i]['brandId'];
    $dropval = $rs[$i]['brandName'];
    if($dropid == $bd)
        $select="selected";
    else
        $select="";

    $BrandList .="<option value=\"$dropid\" $select>$dropval</option>";
}

if($act == "add")
    $Message = "Model Master record added Successfully" ;
else if($act == "upd")
    $Message = "Model Master record updated Successfully" ;
else if($act == "del")
    $Message = "Model Master record and related records deleted Successfully" ;


$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;

include "footer.php";
?>
