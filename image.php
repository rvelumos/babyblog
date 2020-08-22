<?php
session_start();

if(isset($_GET['thumb']) && $_GET['thumb']!=""){
	$thumb = $_GET['thumb'];
}else{
	$thumb = $_GET['image_details'];
}

//require('config.inc.php');
//require('classes/db_class.php');
$size = $_GET['size'];
//$img_width = $_GET['width'];
//$img_height = $_GET['height'];

$image = new cropImage;
//$image->getsettings();
$image->setImage($thumb);

if($_GET['thumb'] && $_GET['thumb'] != ""){
	$image->createThumb($size);	
}else{
	$image->createAlbumImage($img_width, $img_height);
}
$image->renderImage();  


class cropImage{
var $imgSrc,$myImage,$cropHeight,$cropWidth,$x,$y,$thumb,$width,$height;  

public function getSettings(){
		global $prefix, $settings;

		$sql = "SELECT * FROM ".$prefix."settings";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);

		$settings = $result->fetch_array(MYSQLI_BOTH);
		
		$result->free();
		
		return($settings);
}

function setImage($image)
{

//Your Image
   $this->imgSrc = $image; 
                     
//getting the image dimensions
   list($width, $height) = getimagesize($this->imgSrc); 
                     
	$ext = explode(".",$image);
	$ext = strtolower($ext[count($ext)-1]);

//create image from the jpeg/gif/png
if($ext =='jpeg' || $ext =='jpg'){
 $this->myImage = imagecreatefromjpeg($this->imgSrc) or die("Error: Cannot find image!"); 
}elseif($ext == 'gif'){
 $this->myImage = imagecreatefromgif($this->imgSrc) or die("Error: Cannot find image!"); 
}elseif($ext == 'png'){
 $this->myImage = imagecreatefrompng($this->imgSrc) or die("Error: Cannot find image!"); 
}
            
       if($width > $height) $biggestSide = $width; //find biggest length
       else $biggestSide = $height; 
                     
//The crop size will be half that of the largest side 
   $cropPercent = .5; // This will zoom in to 50% zoom (crop)
   $this->cropWidth   = $biggestSide*$cropPercent; 
   $this->cropHeight  = $biggestSide*$cropPercent; 
                     
                     
//getting the top left coordinate
   $this->x = ($width-$this->cropWidth)/2;
   $this->y = ($height-$this->cropHeight)/2;
             
}  

function createThumb($size)
{
  global $settings;

  $thumbSize = $size; // database stores reso
  $this->thumb = imagecreatetruecolor($thumbSize, $thumbSize); 

  imagecopyresampled($this->thumb, $this->myImage, 0, 0,$this->x, $this->y, $thumbSize, $thumbSize, $this->cropWidth, $this->cropHeight); 
}  

function createAlbumImage($img_width, $img_height)
{
  global $settings,$page;
  
	//layout admin cms is kleiner 
	if($_GET['page'] && $_GET['page']=="admin")
		$settings['max_width'] = "400";
	else
		$settings['max_width'] = "550";
		
	//is het plaatje breder dan het maximum, dan verkleinen
  	if($settings['max_width'] < $img_width){
  		$max_width = $settings['max_width'];
		
		//landscape formaat - breed
		if($img_width > $img_height){
			$ratio = $img_width / $settings['max_width'];
			$max_height = $img_height / $ratio;
		//portrait formaat - hoog
		}else{
			$ratio = $img_width / $settings['max_width'];
			$max_height = $img_height / $ratio;
		}
		$resize = TRUE;
		
	}else{
		$max_width = $img_width;
		$max_height = $img_height;
	}
	
  $this->thumb = imagecreatetruecolor($max_width, $max_height); 
   
  //resizen zonder een gedeelte te croppen..
  if($resize == TRUE){
	  imagecopyresampled($this->thumb, $this->myImage, 0, 0,0, 0, $max_width, $max_height, $img_width, $img_height); 
  }else{
	  //geen resize dus gewoon outputten
	  imagejpeg($this->myImage);  
  }
}  

function renderImage()
{
global $ext;                
  //die();
   header('Content-type: image/jpeg');
   imagejpeg($this->thumb);
   imagedestroy($this->thumb); 
}  


}   

?>