<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); 
define('DB_NAME', 'mail_platform');
define('DB_PORT', '3307');

// define('DB_SERVER', 'mysql-mkmmaily.alwaysdata.net');
// define('DB_USERNAME', 'mkmmaily_mkm');
// define('DB_PASSWORD', 'mariqueen'); 
// define('DB_NAME', 'mkmmaily_mailplatform');
// define('DB_PORT', '3307');


function db_connect() {
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
    if($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    return $link;
}


?> 
