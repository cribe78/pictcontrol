<?php
// Set default values and load installation specific values 
// for application  
$g_db_user = 'rttt';
$g_db_pass = 'tothetop';
$g_db_host = '127.0.0.1';
$g_db_schema = 'rttt';
$g_title = 'unknown';

if (file_exists("config.local")) {
    include("config.local");
}

?>
