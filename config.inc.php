<?php
setlocale(LC_ALL, 'nl_NL');

$prefix = "blog_";

//global variables
$ip = $_SERVER['REMOTE_ADDR'];
$url = $_SERVER['QUERY_STRING'];
$webhost = gethostbyaddr($ip);
$server = $_SERVER['SERVER_NAME'];
$server_user = $_SERVER['HTTP_USER_AGENT'];
$method = $_SERVER['REQUEST_METHOD'];

define("LOGFILE", TRUE);
define("DEBUG", TRUE);
define("SHOW_ALL_ERRORS", FALSE);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//predefined variables
switch($page){
	default:
	case "index":
		$classes["class_album"] = "album_content";
		break;
	case "admin":
		$classes["class_album"] = "album_content_backend";
		break;
}
?>