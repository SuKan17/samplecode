<?php
include "header.php";
$file = file("./ptn/contact.html");
$Content = join("",$file);
$errorMsg = "";

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;
include "footer.php";
?>