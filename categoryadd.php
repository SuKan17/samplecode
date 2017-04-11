<?php
include "header.php";

$loginId = $lib->checkLogin($loginId);

$file = file("./ptn/categoryadd.html");
$Content = join("",$file);

$Message = "";
$metaKeyword = isset($metaKeyword)?$metaKeyword:'';
$catName = isset($catName)?$catName:'';
$catDescription = isset($catDescription)?$catDescription:'';
$metaDescription = isset($metaDescription)?$metaDescription:'';

if($submit == "Submit")
{
    $createdtime = time();
    $catName = htmlentities(addslashes(trim($catName)),ENT_QUOTES);
    $catDescription = htmlentities(addslashes(trim($catDescription)),ENT_QUOTES);
    $metaKeyword = htmlentities(addslashes(trim($metaKeyword)),ENT_QUOTES);
    $metaDescription = htmlentities(addslashes(trim($metaDescription)),ENT_QUOTES);
    
    $get = $db->getRecord("select count(*) as total from category where catName = '$catName'");
    if($get['total'] > 0){
        $dup = 1;
    }
    else{
        $dup = 0;
    }
    
    $set = "catName = '$catName'";
    $set .= ",catDescription = '$catDescription'";
    $set .= ",metaKeyword = '$metaKeyword'";
    $set .= ",metaDescription = '$metaDescription'";
    $set .= ",addedDate = '$createdtime'";
    $set .= ",addedBy = '$loginId'";
    
    if($frameToken == $_SESSION['feedtoken'] && $catName != "" && $catDescription != "" && $dup == 0){
        unset($_SESSION["feedtoken"]);
        $getDataId = $db->insertDataId("insert into category set $set");

        if($_FILES['catImageName']['tmp_name'] != "" && $_FILES['catImageName']['tmp_name'] != "null"){
            $fpath = $_FILES['catImageName']['tmp_name'];
            $fname = $_FILES['catImageName']['name'];
            $getext = substr(strrchr($fname, '.'), 1);
            $ext = strtolower($getext); //suppose if the file extension is in upper case,it coverts to lower case								
        }
        if ($ext=="jpg" ||  $ext=="gif" || $ext=="png" ||  $ext=="jpeg"){
            $Imgname = $getDataId.".".$ext;
            $db->insert("update category set catImageName = '$Imgname' where categoryId='$getDataId'");
            $des = "./data/category/$Imgname";
            move_uploaded_file($fpath,$des);
            chmod($des,0777);
        }
        header("Location:categorymgmt.php?msg=add&page=$page") ;
        exit ;
    }
    else {
        //Avoid Duplicate Entry
        $Message = "Input fields are missing.";
        if($dup == 1){
            $Message = "Category name already exists.";
        }
    }
}

/*************************************************/
$_SESSION['feedtoken'] = md5(session_id() . time());
$frameToken = $_SESSION['feedtoken'];
/*************************************************/

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
include "footer.php" ;
?>
