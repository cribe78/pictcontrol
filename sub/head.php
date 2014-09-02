<?php
include("config.php");
$mysqli  = mysqli_connect("127.0.0.1", "pict", "DWCONTROL", "pictcontrol");
if (mysqli_connect_errno($mysqli)) {
    error_log("Failed to connect to MySQL: " . mysqli_connect_error());
}

require("auth.php"); 

// Use $ctx to determine what kind of document we will be returning
if (! isset($ctx)) {
    $ctx = "html";
    if (preg_match("/\.json$/", $_SERVER['PHP_SELF'])) {
        $ctx = "json";
    }
}

if (! isset($page_title)) {
  $page_title = "PICT Control";
}


require("controller-lib.php");

if ($ctx == "html") {
?>
<!DOCTYPE html> 
<html> 
<head> 
    <title><?=$page_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <link rel="stylesheet" href="css/excite-bike/jquery-ui-1.10.3.custom.css" />    
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<?
    if (isset($js_includes) && is_array($js_includes)) {
        foreach($js_includes as $js) {  
?>            <script type="text/javascript" src="<?=$js?>"></script> <?
        }
    }
    if (isset($css_includes) && is_array($css_includes)) {
        foreach($css_includes as $css) {
?>          <link type="text/css" href="<?=$css?>" rel="Stylesheet" />  <?
        }
    }
?>
    </head>
<?
}
if ($ctx === "json") {
    // Define the $resp global array
    $resp = array("status" => 0);  
}

?>
