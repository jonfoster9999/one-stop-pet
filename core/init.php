<?php 
//host, username, password, database
$db = mysqli_connect("127.0.0.1", "root", "", "onestop");
//check for db connection errors
if(mysqli_connect_errno()) {
	echo 'Database connection failed with following errors: '.mysqli_connect_error();
	die();
}

//absolute path
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/config.php';
require_once BASEURL.'/helpers/helpers.php';


?>
