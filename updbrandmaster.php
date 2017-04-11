<?php
include "header.php" ;

$loginId = $lib->checkLogin($loginId) ;

$file = file("./ptn/updbrandmaster.html") ;
$Content = join(" ",$file) ;

$Message  = isset($Message)?$Message:'';
$act = isset($act)?$act:'';
$upd = isset($upd)?$upd:'1';
$id = isset($id)?$id:'';

$brandName  = isset($brandName)?$brandName:'';

if($submit == "Submit") {
    $brandName = trim($brandName);
    $brandName = htmlentities(strtoupper(addslashes($brandName)),ENT_QUOTES);

    if($brandName != '') {
        if($upd == 1)
            $checkstatus = $db->checkField("brandmaster","brandName",$brandName);
        else if($upd == 2){
            $get = $db->getRecord("select count(*) as total from brandmaster where brandId != '$idval' and brandName = '$brandName'");
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
            $set = "brandName = '$brandName'";
            
            $AddedDate = time();
            if($upd == 1) {
                $set .= ",addedBy = '$loginId'";
                $set .= ",activeStatus = '1'"; // For New Record Alone
                $set .= ",createDate = '$AddedDate'";

                if($frameToken == $_SESSION['feedtoken']) {
                    unset($_SESSION["feedtoken"]);
                    $db->insert("insert into brandmaster set $set");
                }
                else {
                    //Avoid Duplicate Entry
                    header("location:brandmaster.php");
                    exit;
                }
                $act = "add";
            }
            else if($upd == 2) {
                $set .= ",editedBy = '$loginId'";
                $db->insert("update brandmaster set $set where brandId='$idval'");
                $act = "upd";
            }
            header("Location:brandmaster.php?act=$act");
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
    $Message="Brand Name exist already.";
}

$frameToken = isset($frameToken)?$frameToken:'';
if($upd == 2) {
    $UpdateText = "Update";
    $GetBrandData = $db->getRecord("select * from brandmaster where brandId ='$id'") ;
    @extract($GetBrandData) ;
    if($activeStatus == 0) {
        header("location:brandmaster.php");
        exit;
    }
    $brandName = html_entity_decode(stripslashes($brandName));
}
else if($upd == 1) {
    $UpdateText = "Add";

    /*************************************************/
    $_SESSION['feedtoken'] = md5(session_id() . time());
    $frameToken = $_SESSION['feedtoken'];
    /*************************************************/

}
$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;

include "footer.php";
?>