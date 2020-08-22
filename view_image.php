<?
ob_start();
error_reporting(E_ALL);
ini_set('display_errors','On');


$key = $_GET['view'];

if($_GET['thumb']!=""){
	$thumb = $_GET['thumb'];
}else{
	$thumb = $_GET['image_details'];
}

require('config.inc.php');
require('classes/db_class.php');

$image = new cropImage;
$image->getsettings();
$blob = $image->getImage($key);

if($_GET['thumb'] != ""){
	$image->createThumb($size);	
}else{
	$image->createfromBlob($blob);
}

class cropImage{
var $imgSrc,$myImage,$cropHeight,$cropWidth,$x,$y,$thumb,$width,$height;  

public function getSettings(){
		global $prefix, $settings;

		$sql = "SELECT * FROM ".$prefix."settings";
		$result = mysql_query($sql) or die(mysql_error());

		$settings = mysql_fetch_array($result);
		
		mysql_free_result($result);
		
		return($settings);
}

public function getImage($key){
		global $prefix, $settings;

		$sql = "SELECT data FROM ".$prefix."albumfotos WHERE auth_key = '$key'";
		$result = mysql_query($sql) or die(mysql_error());

		$image = mysql_fetch_array($result);
		$blob_data = $image['data']; 
		
		mysql_free_result($result);
		
		return($blob_data);
}

function createfromBlob($blob_data){
	$desired_width = 500;
	$desired_height = 500;
		if($blob_data !== FALSE){	
			$im = imagecreatefromstring($blob_data);
			//$new = imagecreatetruecolor($desired_width, $desired_height);
			 
			//$x = imagesx($im);
			//$y = imagesy($im);
		 
			//imagecopyresampled($new, $im, 0, 0, 0, 0, $desired_width, $desired_height, $x, $y);
		 
			//imagedestroy($im);
			
			header("Content-type: image/jpeg");
		 
			imagejpeg($im);
		}else{
			echo "errorrrr";	
		}
}


}   
ob_end_flush();
?>