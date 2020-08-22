
<form method="post" enctype="multipart/form-data" name="add_blog_form" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<table class='editform'>
<tr><td><input type="hidden" name="upload_images" value="add_blog" /></td></tr>

<? for($i=1; $i<=$max_no_img; $i++){?>
    <tr><td class='fields'>Afbeelding:<?if($error['afbeelding']){?><span class='error_astx'>*</span><?}?>  </td></tr>
    <tr><td class='fields'><input type="file" class='field' name="afbeeldingen[]" value="" <?=$disabled?> /><img src='images/plus.png' onclick="add()" alt='Rij toevoegen'/></td><td><?=$error['afbeelding'];?></td></tr>
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



