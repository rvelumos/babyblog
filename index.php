<?php


$page = 'index';

require('config.inc.php');
//require('classes/db_class.php');
require('classes/content_class.php');
//include("classes/browser.php");
require('classes/logging.php');
//menu items laden
$content = new Content;

$settings = $content->getSettings();
$title = $settings['head_title'];

//$sql = "SELECT ip FROM ".$prefix."known_hosts WHERE ip = '$ip'";
//$result = mysql_query($sql) or die(mysql_error());

//if(mysql_num_rows($result)>0)
define("ALLOWED_HOST", true);

//2 = iedereen of ip staat in known_hosts tabel
if(($settings['website_accept_hosts']!='2' || (ALLOWED_HOST)) && $settings['website_accept_hosts']!='0'){
	require('templates/top.php');
}else{
//to do	
}

if(defined("DEBUG"))
	$content->debug_div();

$content = NULL;

?>