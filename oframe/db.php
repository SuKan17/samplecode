<?php
class Database 
{
    var $dbObj;
    function __construct()
    {
        include("routing.php");
        $DB_HOST = $host; // hostname
        $DB_NAME = $dbname; // database name
        $DB_USER = $dbuser; // database username
        $DB_PASS = $dbpass; // database password
        if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
            echo 'We don\'t have mysqli!!!';
            exit;
        }
        $this->dbObj = mysqli_connect ($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    }
    function getRecords($query)
    {
        $records = array() ;
        if (! ($result = mysqli_query ($this->dbObj,$query))){
            //Error Handling
            exit;
        }
        else {
            $temp = 0 ;
            while ( $row = mysqli_fetch_array ($result) ) {
                $records[$temp] = $row;
                $temp++ ;
            }
            mysqli_free_result($result);
            return $records;
        }
    }
    //=====================================================================================
    function getRecord($query)
    {
        if(!($result = mysqli_query ($this->dbObj,$query))){
           //Error Handling
            exit;
        }
        $record = mysqli_fetch_array ($result);
        mysqli_free_result ($result);
        return $record;
    }
    function insert($query) 
    {
        if (!(mysqli_query ($this->dbObj,$query))){
            //Error Handling
            exit;
        }
    }
    function insertDataId($query) 
    {
        if (!(mysqli_query ($this->dbObj,$query))) {
            //Error Handling
            exit;
        }
        $id = mysqli_insert_id($this->dbObj);
        return $id;
    }
    function checkField($table,$column,$v1) {
        if (! $result=mysqli_query ($this->dbObj,"select * from $table where $column ='$v1'")) {
            //Error Handling
            exit();
        }
        $row=mysqli_fetch_array ($result);
        mysqli_free_result ($result);
        if ($row[0]){
            $var =  1;
        }
        else{
            $var =  0;
        }
        return $var;
    }
    function __destruct()
    {
        mysqli_close($this->dbObj);
    }
}
?>
