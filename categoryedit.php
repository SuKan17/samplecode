<?php
include "header.php";

$loginId = $lib->checkLogin($loginId);

$file = file("./ptn/categoryedit.html");
$Content = join("",$file);

$Message = "";
$metaKeyword = isset($metaKeyword)?$metaKeyword:'';
$catName = isset($catName)?$catName:'';
$catDescription = isset($catDescription)?$catDescription:'';
$metaDescription = isset($metaDescription)?$metaDescription:'';

if($submit == "Submit")
{
    $editedDate = time();
    $catName = htmlentities(addslashes(trim($catName)),ENT_QUOTES);
    $catDescription = htmlentities(addslashes(trim($catDescription)),ENT_QUOTES);
    $metaKeyword = htmlentities(addslashes(trim($metaKeyword)),ENT_QUOTES);
    $metaDescription = htmlentities(addslashes(trim($metaDescription)),ENT_QUOTES);
    
    $get = $db->getRecord("select count(*) as total from category where catName = '$catName' and categoryId != '$idval'");
    if($get['total'] > 0){
        $dup = 1;
        $catid = $idval;
    }
    else{
        $dup = 0;
    }

    if($frameToken == $_SESSION['feedtoken'] && $catName != "" && $catDescription != "" && $dup == 0){
        unset($_SESSION["feedtoken"]);

        $set = "catName = '$catName'";
        $set .= ",catDescription = '$catDescription'";
        $set .= ",metaKeyword = '$metaKeyword'";
        $set .= ",metaDescription = '$metaDescription'";
        $set .= ",editedDate = '$editedDate'";
        $set .= ",editedBy = '$loginId'";

        if($_FILES['catImageName']['tmp_name'] != "" && $_FILES['catImageName']['tmp_name'] != "null"){
            $fpath = $_FILES['catImageName']['tmp_name'];
            $fname = $_FILES['catImageName']['name'];
            $getext = substr(strrchr($fname, '.'), 1);
            $ext = strtolower($getext); //suppose if the file extension is in upper case,it coverts to lower case								
        }
        if ($ext=="jpg" ||  $ext=="gif" || $ext=="png" ||  $ext=="jpeg"){
            $Imgname = $idval.".".$ext;
            $set .= ",catImageName = '$Imgname'";
            $des = "./data/category/$Imgname";
            move_uploaded_file($fpath,$des);
            chmod($des,0777);
        }
        $db->insert("update category set $set where categoryId='$idval'") ;
        header("Location:categorymgmt.php?msg=edit&page=$page") ;
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

$getRecord = $db->getRecord("select * from category where categoryId='$catid'");
$catName = html_entity_decode(stripslashes($getRecord['catName']));
$catDescription = html_entity_decode(stripslashes($getRecord['catDescription']));
$metaKeyword = html_entity_decode(stripslashes($getRecord['metaKeyword']));
$metaDescription = html_entity_decode(stripslashes($getRecord['metaDescription']));
$catImageName = $getRecord['catImageName'];
if($catImageName != ""){
        $DisplayImage = "<div class='col-xs-2'>"
                . "<a href='./data/category/$catImageName' target='new' class='thumbnail'>"
                . "<img src='./data/category/$catImageName' class='img-thumbnail'></a>"
                . "</div>";
}
else{
        $DisplayImage = 'No Image Uploaded';
}

/*************************************************/
$_SESSION['feedtoken'] = md5(session_id() . time());
$frameToken = $_SESSION['feedtoken'];
/*************************************************/

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content) ;
echo $Content ;
include "footer.php" ;
?>