<?php
include "../oframe/include.php" ;

$getHead=ltrim($_GET["target"]);
$result = -1;
if($getHead != ""){
    $getRecord = $db->getRecord("select count(*) as total from om_user where userEmail ='$getHead'");
    $result = $getRecord['total'];
}
$returnObj = '{"dataExist":'.$result.'}';
echo $returnObj;
?>