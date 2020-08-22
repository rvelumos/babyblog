<?
$max_no_img=1; 
?>

<div class='crumble_path'><a href='index.php?section=photoalbum'>Albums</a> &raquo; <a href='index.php?section=photoalbum&amp;album_id=<?=$_GET['album_id']?>'><?=$album_name?></a> &raquo; Foto's toevoegen</div>
<? 
if($settings['img_quota']!=0)
	echo $this->image_quota_status();
$quota = $this->image_quota();

if($quota==FALSE)
	$disabled = "disabled = disabled ";
else
  $disabled="";
                                                                                     

if(isset($msg['image']))echo "<br />".$msg['image'];

?>
<form method="post" enctype="multipart/form-data" name="add_blog_form" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<table class='editform'>
<tr><td><input type="hidden" name="upload_images" value="add_blog" /></td></tr>

<? for($i=1; $i<=$max_no_img; $i++){?>
    <tr><td class='fields'>Afbeelding:<?if(isset($error['image'])){?><span class='error_astx'>*</span><?}?>  </td></tr>
    <tr><td class='fields'><input type="file" class='field' name="images[]" value="" <?=$disabled?> /><img src='../images/plus.png' onclick="add()" alt='Rij toevoegen'/></td><td><? if(isset($error['image'])) echo $error['image'];?></td></tr>
<? } ?>
<tr><td>
<div id='image_input'>
</div></td></tr>

<tr><td>
<div id='last_field'>
</div></td></tr>

<tr><td class='fields'><input type="submit" class="submit" value="Toevoegen" <?=$disabled?> /></td></tr>
</table>
</form>



