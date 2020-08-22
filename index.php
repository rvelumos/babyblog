<?php


$page = 'index';

require('config.inc.php');

require('classes/content_class.php');
require('classes/logging.php');

$content = new Content;

$settings = $content->getSettings();
$title = $settings['head_title'];

define("ALLOWED_HOST", true);

if(($settings['website_accept_hosts']!='2' || (ALLOWED_HOST)) && $settings['website_accept_hosts']!='0'){
	require('templates/top.php');
}else{
//to do	- under cunstruction page
}

if(defined("DEBUG"))
	$content->debug_div();

$content = NULL;

?>