<div class='crumble_path'><a href='index.php?section=posts'>Posts</a> - <a href='<?=$_SERVER['PHP_SELF']?>?section=posts&amp;edit_item=<?=$image["id"]?>'><?=$image["title"]?></a> - Afbeelding <?=$i?> bewerken</div>
<? if(isset($msg['notice'])) echo $msg['notice'];?>
<table class='editform'>
<form method="post" enctype="multipart/form-data" name="edit_image_form" action="<?=$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" >
<input type="hidden" name="edit_image" value="edit_image" />
<input type="hidden" name="id" value="$image$i" />
<tr><td class='fields'>Huidige foto:</td></tr>
<tr><td class='blog_image'><img src='../image.php?thumb=<? echo $thumb; ?>&amp;size=<?=$size?>' class='image' alt='' style='padding:20px;margin-left:10px;' /></td></tr>
<tr><td class='fields'><div class='image'>
 <tr><td class='fields'>Nieuwe afbeelding uploaden:<?if(isset($error['image'])){?><span class='error_astx'>*</span><?}?>  </td></tr>
    <tr><td class='fields'><input type="file" class='fields' name="image" value="" /></td><td><? if(isset($error['image'])) echo $error['image'];?></td></tr>
<tr><td>
<tr><td class='fields'><input type="submit" class="submit" value="Bewerken" /></td>
    </form>
</table>



