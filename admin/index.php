<?php
session_start();

$title = 'Administrator gedeelte website';
$page = 'admin';

require('../config.inc.php');
//require('classes/db_class.php');
require('../classes/admin_class.php');
include("../classes/browser.php");
//require('../classes/logging.php');
//menu items laden
$admin = new Admin;
$settings = $admin->getSettings();

require('../templates/admin_layout.php');

if (isset($_SESSION['auth_admin_login']) && $_SESSION['admin_section']=='babyblog'){
	
	if(isset($_GET['logout'])){
		session_destroy();
		echo "<meta http-equiv=\"refresh\" content=\"2;URL=index.php\" />";
		echo "<p class='notify_login'>Je bent nu uitgelogd, moment geduld...</p>";
		die();
	}
	
	include('../templates/admin_template.php');	

  $section="";
  if(isset($_GET['section']))$section=$_GET['section'];
  
	switch($section){
		
		case "settings": 
			$admin->setSettings($settings);
		break;
		
		case "polls": 
			$admin->getPolls();
		break;
		
		case "photoalbum": 
			$admin->albumOverview();
		break;
		
		case "categories": 
			$admin->getCtItems();
		break;
		
		case "tags": 
			$admin->getCtItems();
		break;
		
		case "stats": 
			$admin->getStats();
		break;

		case "security": 
			$admin->security();
		break;
		
		//case "system": 
			//$admin->systemOverview();
		//break;
		
		case "log": 
			$admin->logOverview();
		break;
		
		case "posts": 
		default:
			$admin->getPosts();
		break;
	}
	
require('../templates/admin_bottom.php');
}else{
	$admin->authLogin();
}

$admin = NULL;

?>