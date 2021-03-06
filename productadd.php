<?php
include "header.php";

$loginId = $lib->checkLogin($loginId);

$file = file("./ptn/productadd.html");
$Content = join("",$file);

$Message = "";
$brandId = isset($brandId)?$brandId:'';
$id = isset($id)?$id:'';
$productPrice = isset($productPrice)?$productPrice:'';
$availableStockQty = isset($availableStockQty)?$availableStockQty:'';
$categoryId = isset($categoryId)?$categoryId:'';
$metaKeyword = isset($metaKeyword)?$metaKeyword:'';
$productName = isset($productName)?$productName:'';
$productDescription = isset($productDescription)?$productDescription:'';
$metaDescription = isset($metaDescription)?$metaDescription:'';


if($submit == "Submit")
{
    $createdtime = time();
    $productName = htmlentities(addslashes(trim($productName)),ENT_QUOTES);
    $productDescription = htmlentities(addslashes(trim($productDescription)),ENT_QUOTES);
    $metaKeyword = htmlentities(addslashes(trim($metaKeyword)),ENT_QUOTES);
    $metaDescription = htmlentities(addslashes(trim($metaDescription)),ENT_QUOTES);
    
    $get = $db->getRecord("select count(*) as total from product where productName = '$productName'");
    if($get['total'] > 0){
        $dup = 1;
    }
    else{
        $dup = 0;
    }
    
    $set = "productName = '$productName'";
    $set .= ",productDescription = '$productDescription'";
    $set .= ",productPrice = '$productPrice'";
    $set .= ",availableStockQty = '$availableStockQty'";
    $set .= ",brandId = '$brandId'";
    $set .= ",modelId = '$modelId'";
    $set .= ",categoryId = '$categoryId'";
    $set .= ",metaKeyword = '$metaKeyword'";
    $set .= ",metaDescription = '$metaDescription'";
    $set .= ",addedDate = '$createdtime'";
    $set .= ",addedBy = '$loginId'";
    $set .= ",activeStatus = '1'";
    
    if($frameToken == $_SESSION['feedtoken'] && $productName != "" && $productDescription != "" && $productPrice != "" && $availableStockQty != "" && $dup == 0){
        unset($_SESSION["feedtoken"]);
        $getDataId = $db->insertDataId("insert into product set $set");

        if($_FILES['productImageName']['tmp_name'] != "" && $_FILES['productImageName']['tmp_name'] != "null"){
            $fpath = $_FILES['productImageName']['tmp_name'];
            $fname = $_FILES['productImageName']['name'];
            $getext = substr(strrchr($fname, '.'), 1);
            $ext = strtolower($getext); //suppose if the file extension is in upper case,it coverts to lower case								
        }
        if ($ext=="jpg" ||  $ext=="gif" || $ext=="png" ||  $ext=="jpeg"){
            $Imgname = $getDataId.".".$ext;
            $db->insert("update product set productImageName = '$Imgname' where productId='$getDataId'");
            $des = "./data/product/$Imgname";
            move_uploaded_file($fpath,$des);
            chmod($des,0777);
        }
        header("Location:productmgmt.php?msg=add&page=$page") ;
        exit ;
    }
    else {
        //Avoid Duplicate Entry
        $Message = "Input fields are missing.";
        if($dup == 1){
            $Message = "Product name already exists.";
        }
    }
}

$BrandList = "";
$ModelList = "";
$CategoryList = "";

$BrandList = "<option value='0'>- - Brand Name Not Specific - -</option>";
$rs = $db->getRecords("select brandId,brandName from brandmaster where activeStatus='1' order by brandName");
for($i = 0; $i < count($rs) ; $i++){
    $dropid = $rs[$i]['brandId'];
    $dropval = html_entity_decode(stripslashes($rs[$i]['brandName']));
    if($dropid == $brandId)
        $select="selected";
    else
        $select="";
    $BrandList .="<option value=\"$dropid\" $select>$dropval</option>";
}

$ModelList = "<option value='0'>- - Model Name Not Specific - -</option>";
if($brandId != ""){
    $rs = $db->getRecords("select modelId,modelName from modelmaster where brandId = '$brandId' and activeStatus='1' order by modelName");
    for($i = 0; $i < count($rs) ; $i++){
        $dropid = $rs[$i]['modelId'];
        $dropval = html_entity_decode(stripslashes($rs[$i]['modelName']));
        if($dropid == $modelId)
            $select="selected";
        else
            $select="";
        $ModelList .="<option value=\"$dropid\" $select>$dropval</option>";
    }
}

$CategoryList = "<option value='0'>- - Category Name Not Specific - -</option>";
$rs = $db->getRecords("select categoryId,catName from category where activeStatus='1'  order by catName");
for($i = 0; $i < count($rs) ; $i++){
    $dropid = $rs[$i]['categoryId'];
    $dropval = html_entity_decode(stripslashes($rs[$i]['catName']));
    if($dropid == $categoryId)
        $select="selected";
    else
        $select="";
    $CategoryList .="<option value=\"$dropid\" $select>$dropval</option>";
}

/*************************************************/
$_SESSION['feedtoken'] = md5(session_id() . time());
$frameToken = $_SESSION['feedtoken'];
/*************************************************/

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
include "footer.php" ;
?>
