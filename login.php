<?php
include "header.php";
$file = file("./ptn/login.html");
$Content = join("",$file);

$err = isset($err)?$err:"";
$errorMsg = "";
if($err == "inv"){
    $errorMsg = "Your password and email do not match. Please try again.";
}

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;
include "footer.php";
?>