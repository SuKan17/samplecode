<?php
$file = file("./ptn/footer.html");
$Content = join("",$file);

$Content = preg_replace("/{{(.*?)}}/e","$$1",$Content);
echo $Content;
?>