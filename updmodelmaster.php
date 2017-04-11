<?php
include "header.php" ;

$loginId = $lib->checkLogin($loginId) ;

$file = file("./ptn/updmodelmaster.html") ;
$Content = join(" ",$file) ;

$Message  = isset($Message)?$Message:'';
$act = isset($act)?$act:'';
$upd = isset($upd)?$upd:'1';
$id = isset($id)?$id:'';
$modelName  = isset($modelName)?$modelName:'';
$brandId = isset($brandId)?$brandId:'';
$BrandList = '';
$selectBrandId = $bd;

if($submit == "Submit") {
    $modelName = trim($modelName);
    $modelName = htmlentities(strtoupper(addslashes($modelName)),ENT_QUOTES);

    if($modelName != '') {
        if($upd == 1){
            $get = $db->getRecord("select count(*) as total from modelmaster where brandId = '$brandId' and modelName = '$modelName'");
            if($get['total'] > 0)
                $checkstatus = 1;
            else {
                $checkstatus = 0;
            }
        }
        else if($upd == 2){
            $get = $db->getRecord("select count(*) as total from modelmaster where brandId = '$brandId' and modelId != '$idval' and modelName = '$modelName'");
            if($get['total'] > 0)
                $checkstatus = 1;
            else {
                $checkstatus = 0;
            }
        }
        else{
            $checkstatus = 0;
        }
        
        if($checkstatus == 0) {
            $set = "modelName = '$modelName'";
            $set .= ",brandId = '$brandId'";

            $AddedDate = time();
            if($upd == 1) {
                $set .= ",createDate = '$AddedDate'";
                $set .= ",addedBy = '$loginId'";
                $set .= ",activeStatus = '1'"; // For New Record Alone

                if($frameToken == $_SESSION['feedtoken']) {
                    unset($_SESSION["feedtoken"]);
                    $db->insert("insert into modelmaster set $set");
                }
                else {
                    //Avoid Duplicate Entry
                    header("location:modelmaster.php");
                    exit;
                }
                $act = "add";
            }
            else if($upd == 2) {
                $set .= ",editedBy = '$loginId'";
                $db->insert("update modelmaster set $set where modelId='$idval'");
                $act = "upd";
            }
            header("Location:modelmaster.php?act=$act&bd=$bd");
            exit;
        }
        else {
            $act="err";
        }
    }
    else {
        $id = $idval;
        $Message = "Input Fields Marked With * are compulsory";
    }
}
if($act=='err') {
    $id = $idval;
    $Message="Model Name exist already.";
}

$frameToken = isset($frameToken)?$frameToken:'';
if($upd == 2) {
    $UpdateText = "Update";
    $getRecord = $db->getRecord("select * from modelmaster where modelId ='$id'") ;
    @extract($getRecord) ;
    if($activeStatus == 0) {
        header("location:modelmaster.php?bd=$bd");
        exit;
    }
    $selectBrandId = $brandId;
    $modelName = html_entity_decode(stripslashes($modelName));

}
else if($upd == 1) {
    $UpdateText = "Add";

    /*************************************************/
    $_SESSION['feedtoken'] = md5(session_id() . time());
    $frameToken = $_SESSION['feedtoken'];
    /*************************************************/

}

$BrandList = "<option value='0'>- - All Brand Names - -</option>";
$rs = $db->getRecords("select brandId,brandName from brandmaster order by brandName");
for($i = 0; $i < count($rs) ; $i++){
    $dropid = $rs[$i]['brandId'];
    $dropval = $rs[$i]['brandName'];
    if($dropid == $selectBrandId)
        $select="selected";
    else
        $select="";

    $BrandList .="<option value=\"$dropid\" $select>$dropval</option>";
}

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;

include "footer.php";
?>