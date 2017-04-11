<?php
include "../oframe/include.php" ;

$getId = ltrim($_GET["target"]);
$return = "";
if($getId != "" && $getId != 0){
    $getRecords = $db->getRecords("select modelId,modelName from modelmaster where activeStatus ='1' and brandId = '$getId'");
    for($i = 0;$i < count($getRecords); $i++){
        $return[$i]['modelId'] = $getRecords[$i]['modelId'];
        $return[$i]['modelName'] = html_entity_decode(stripslashes($getRecords[$i]['modelName']));
    }
}
echo json_encode($return);
?>