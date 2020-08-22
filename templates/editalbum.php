<div class='crumble_path'><a href='admin.php?sectie=fotoalbum'>Albums</a> - <?=$album_details['name']?> bewerken</div>
<? if(isset($msg['image'])) echo $msg['image'];?>
<? if(isset($msg['notice'])) echo $msg['notice'];?>
<form method="post" enctype="multipart/form-data" name="edit_album_form" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<table class='editform'>
<tr><td><input type="hidden" name="edit_album" value="edit_album" /></td></tr>
<tr><td class='fields'>Album naam:<?if(isset($error['name'])){?><?=$error['name'];?><?}?> </td></tr>
<tr><td class='fields'><input type='text' name="naam" class='field <? if(isset($error['name'])){?>input_error<?}?>' value="<? if(isset($album_details['name'])) echo $album_details['name']?>" /> </td></tr>
<tr><td class='fields'>Omschrijving album:<?if(isset($error['description'])){?>* <?=$error['description'];?><?}?> </td></tr>
<tr><td class='fields'><textarea class='ta' name="description" rows='5' cols='50'><? if(isset($album_details['description'])) echo $album_details['description']?></textarea></td></tr>
<? if(isset($album_details['thumb']) && $album_details['thumb'] != ""){?>
<tr><td class='fields'>Huidige album afbeelding: </td></tr>
<tr><td class='fields'><img src='../<?=$album_details['thumb']?>' /> </td></tr>
<? }?>
<tr><td><? if(isset($error['image'])) echo $error['image'];?></td></tr>
<tr><td class='fields'>Upload album afbeelding: </td></tr>
<tr><td class='fields'><input type="file" name="image" class='field' value="<? if(isset($album_details['thumb'])) echo $album_details['thumb']?>" /> </td></tr>
<tr><td>
<div id='image_input'>
</div></td></tr>

<tr><td>
<div id='last_field'>
</div></td></tr>
<tr><td class='fields'>Status</td></tr>
    <tr><td class='fields'><select name='status'>
 <?if(isset($album_details['status']) && $album_details['status']==0){?>
<option value='<?=$album_details['status']; ?>'>Niet openbaar</option>
<?}elseif(isset($album_details['status']) && $album_details['status']==1){?>
<option value='<?=$album_details['status']; ?>'>Openbaar</option>
<?}?>
<option value=' '> </option>
<option value='0'>Niet openbaar</option>
<option value='1'>Openbaar</option>
</select><? if(isset($error['status'])) echo $error['status'];?></td></tr>
<tr><td class='fields'><input type="submit" class="submit" value="Toevoegen" /></td></tr>
</table>
</form>



