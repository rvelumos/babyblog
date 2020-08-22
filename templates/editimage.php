<div class='crumble_path'><a href='admin.php?sectie=fotoalbum'>Albums</a> - <a href='<?=$_SERVER['PHP_SELF']?>?sectie=fotoalbum&amp;album_id=<?=$image_details['album_id']?>'><?=$album['naam']?></a> � <?=$image_details['naam']?> bewerken</div>
<? if(isset($msg['notice'])) echo $msg['notice'];?>
<table class='editform'>
<form method="post" enctype="multipart/form-data" name="edit_image_form" action="<?=$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" >
<input type="hidden" name="edit_image" value="edit_image" />
<tr><td></td></tr>
<tr><td class='fields'>Foto naam: <? if(isset($error['name'])) echo $error['name'];?> </td></tr>
<tr><td class='fields'><input type='text' name="name" <? if($settings['rename_photo']=='0'){?>disabled="disabled"<? }?> class='field <?if(isset($error['name'])){?>input_error<?}?>' value="<? if(isset($image_details['name'])) echo $image_details['name']?>" /> </td></tr>
<tr><td class='fields'>Omschrijving foto: </td></tr>
<tr><td class='fields'><input type='text' name="description" class='field' value="<? if(isset($image_details['description'])) echo $image_details['description']?>" /> </td></tr>
<tr><td>
<div id='image_input'>
</div></td></tr>

<tr><td>
<div id='last_field'>
</div></td></tr>

<tr><td class='fields'><input type="submit" class="submit" value="Toevoegen" /></td>
    </form>
</table>



