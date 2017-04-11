<?php
class Lib 
{
    function hashgen() 
    {
        $hash = md5(uniqid(rand(),1));
        return $hash;
    }
    function hashUserId($userId)
    {
        $genFirst = mt_rand(1,9) . mt_rand(1,9) . mt_rand(1,9) . mt_rand(1,9);
        $genLast = mt_rand(1,9) . mt_rand(1,9) . mt_rand(1,9) . mt_rand(1,9);
        return ($genFirst.$userId.$genLast);
    }
    function getUserId($hash)
    {
        $remFirst = substr($hash, 4);
        $remLast = substr($remFirst, 0, -4);
        return((int)$remLast);
    }
    function checkLogin($userId)
    {
        if($userId == 0 || $userId == ""){
            header("Location:login.php");
            exit;
        }
        else
        {
            return $userId;
        }
    }
    function pageBreak($db_object,$return_content,$count,$records,$links,$fPage) 
    {
        $pages = ceil($count/$records);
        $pattern = "/<{page_loopstart}>(.*?)<{page_loopend}>/s";
        preg_match($pattern,$return_content,$out);
        $out[1] = isset($out[1])?$out[1]:'';
        $myvar = $out[1];
        $str = "";
        for($i = 1; $i <= $pages; $i++){
            $link = $links . "page=$i";
            $page = $i;
            if($page == $fPage){
                $page = $i;
                $str .= preg_replace("/<{(.*?)}>/e","$$1",$myvar);
            }
            else{
                $page = "<a href=\"$link\">$page</a>";
                $str .= preg_replace("/<{(.*?)}>/e","$$1",$myvar);
            }
        }
        $return_content = preg_replace($pattern,$str,$return_content);
        return $return_content;
    }
    function loadPagination($db_object,$return_content,$adjacents,$total_pages,$limit,$targetpage,$page) {
        $lastpage = ceil($total_pages/$limit);
        if($page == 0){
            $page = 1;
        }
        $prev = $page - 1;
        $next = $page + 1;
        
        $lpm1 = $lastpage - 1;
        $targetpage = $targetpage."page";
        $pagination = "";
        if($lastpage > 1) {
            $pagination .= "<ul class=\"pagination pagination-sm\">";
            if ($page > 1)
                $pagination.= "<li><a href=\"$targetpage=$prev\">&laquo; previous</a></li>";
            else
                $pagination.= "<li><span class=\"disabled\">&laquo; previous</span></li>";
            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><span class=\"current\">$counter</span></li>";
                    else
                        $pagination.= "<li><a href=\"$targetpage=$counter\">$counter</a></li>";
                }
            }
            elseif($lastpage > 5 + ($adjacents * 2)) {
                if($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination.= "<li><span class=\"current\">$counter</span></li>";
                        else
                            $pagination.= "<li><a href=\"$targetpage=$counter\">$counter</a></li>";
                    }
                    $pagination.= "<li><span>...</span></li>";
                    $pagination.= "<li><a href=\"$targetpage=$lpm1\">$lpm1</a></li>";
                    $pagination.= "<li><a href=\"$targetpage=$lastpage\">$lastpage</a></li>";
                }
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination.= "<li><a href=\"$targetpage=1\">1</a></li>";
                    //$pagination.= "<a href=\"$targetpage=2\">2</a>";
                    $pagination.= "<li><span>...</span></li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination.= "<li><span class=\"current\">$counter</span></li>";
                        else
                            $pagination.= "<li><a href=\"$targetpage=$counter\">$counter</a></li>";
                    }
                    $pagination.= "<li><span>...</span></li>";
                    $pagination.= "<li><a href=\"$targetpage=$lpm1\">$lpm1</a></li>";
                    $pagination.= "<li><a href=\"$targetpage=$lastpage\">$lastpage</a></li>";
                }
                //close to end; only hide early pages
                else {
                    $pagination.= "<li><a href=\"$targetpage=1\">1</a></li>";
                    $pagination.= "<li><a href=\"$targetpage=2\">2</a></li>";
                    $pagination.= "<li><span>...</span></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination.= "<li><span class=\"current\">$counter</span></li>";
                        else
                            $pagination.= "<li><a href=\"$targetpage=$counter\">$counter</a></li>";
                    }
                }
            }
            if ($page < $counter - 1)
                $pagination.= "<li><a href=\"$targetpage=$next\">next &raquo;</a></li>";
            else
                $pagination.= "<li><span class=\"disabled\">next &raquo;</span></li>";
            $pagination.= "</ul>\n";
        }
        $return_content=str_replace ( "<{pagination}>", $pagination, $return_content);
        return $return_content;
    }
}
while(list($key,$value)=@each($_POST)) {
    $$key=$value;
}
while(list($key,$value)=@each($_GET)) {
    $$key=$value;
}
?>
