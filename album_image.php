<?

class cropImage{
var $imgSrc,$myImage,$cropHeight,$cropWidth,$x,$y,$thumb;  
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

function renderImage()
{
global $ext;
                     
   header('Content-type: image/jpeg');
   imagejpeg($this->thumb);
   imagedestroy($this->thumb); 
}  


}
$thumb = $_GET['thumb'];
$size = $_GET['size'];

$image = new cropImage;
$image->setImage($thumb);
$image->createThumb($size);
$image->renderImage();    

?>