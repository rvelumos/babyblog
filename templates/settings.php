    
<form method="post" enctype="multipart/form-data" name="settings_form" action="<?=$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" >
<input type="hidden" name="settings" value="edit_settings" />
<div class="form_titel">Settings blog</div>
<ul id="setting_tabs" class="shadetabs">
<li><a href="#" rel="setting1" class="selected">Algemeen</a></li>
<li><a href="#" rel="setting2">Blog items</a></li>
<li><a href="#" rel="setting3">Fotoalbums</a></li>
<li><a href="#" rel="setting4">Polls</a></li>
<li><a href="#" rel="setting5">Stats</a></li>
<li><a href="#" rel="setting6">Uploads</a></li>
</ul>

<!-- setting 1 -->
<div id='setting1' class='tab'>
<table class='hidden_settings'>
<tr><td class='fields'>Naam website:<? if(isset($error['website_name'])) echo $error['website_name'];?> </td></tr>
<tr><td class='fields'><input type="text" class='long' name="website_name" value="<?=$settings['website_name']?>" /></td></tr>
<tr><td class='fields'>Website bar tekst: <? if(isset($error['head_title'])) echo $error['head_title'];?> </td></tr>
<tr><td class='fields'><input type="text" class='long' name="head_title" value="<?=htmlentities($settings['head_title'])?>"  /></td></tr>
<tr><td class='fields'>prettyPhoto gebruiken:</td></tr>
    <tr><td class='fields'><select name='img_mode'>
 <?if($settings['img_mode']==0){?>
<option value='<?=$settings['img_mode']; ?>'>Nee</option>
<?}else{?>
<option value='<?=$settings['img_mode']; ?>'>Ja</option>
<?}?>
<option value=' '> &nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['img_mode'])) echo $error['img_mode'];?></td></tr>

<tr><td class='fields'>Zoekfunctie gebruiken:</td></tr>
    <tr><td class='fields'><select name='searchfield'>
 <?if($settings['searchfield']==0){?>
<option value='<?=$settings['searchfield']; ?>'>Nee</option>
<?}else{?>
<option value='<?=$settings['searchfield']; ?>'>Ja</option>
<?}?>
<option value=' '>&nbsp; </option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['searchfield'])) echo $error['searchfield'];?></td></tr>

    <tr><td class='fields'>Blog status:</td></tr>
    <tr><td class='fields'><select name='website_status'>
 <?if($settings['website_status']==0){?>
<option value='0'>Gesloten</option>
<?}elseif($settings['website_status']==1){?>
<option value='1'>In onderhoud</option>
<?}else{?>
<option value='2'>Open</option>
<?}?>
<option value=' '>&nbsp;</option>
<option value='0'>Gesloten</option>
<option value='1'>In onderhoud</option>
<option value='2'>Open</option>
</select></td><td><?  if(isset($error['website_status'])) echo $error['website_status'];?></td></tr>

<tr><td class='fields'><input type="submit" class="submit" value="Wijzigen" /></td></tr>
</table>
</div>

<!-- setting 2 -->
<div id='setting2' class='tab'>
<table class='hidden_settings'>
<tr><td class='fields'>Maximum posts per pagina: <? if(isset($error['max_items'])) echo $error['max_items'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="max_items" maxlength="3" size="3" value="<?=$settings['max_items']?>"  /></td></tr>
<tr><td class='fields'>Thumbnail maximum grootte: <? if(isset($error['thumb_size'])) echo $error['thumb_size'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="thumb_size" maxlength="4" value="<? if(isset($settings['thumb_size'])) echo $settings['thumb_size']?>"  /> px</td></tr>
<tr><td class='fields'>Reacties inschakelen</td></tr>
    <tr><td class='fields'><select name='reactions'>
 <?if($settings['reactions']==0){?>
<option value='0'>Nee</option>
<?}else{?>
<option value='1'>Ja</option>
<?}?>
<option value=' '> &nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['reactions'])) echo $error['reactions'];?></td></tr>

<tr><td class='fields'>HTML toestaan in reactie(s):</td></tr>
    <tr><td class='fields'><select name='allow_html'>
 <?if($settings['allow_html']==0){?>
<option value='0'>Nee</option>
<?}else{?>
<option value='1'>Ja</option>
<?}?>
<option value=' '> &nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['allow_html'])) echo $error['allow_html'];?></td></tr>

<tr><td class='fields'>TINYmce gebruiken:</td></tr>
    <tr><td class='fields'><select name='htmleditor'>
 <?if($settings['htmleditor']==0){?>
<option value='<?=$settings['htmleditor']; ?>'>Nee</option>
<?}else{?>
<option value='<?=$settings['htmleditor']; ?>'>Ja</option>
<?}?>
<option value=' '> &nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['htmleditor'])) echo $error['htmleditor'];?></td></tr>
<tr><td class='fields'>
  <input type="submit" class="submit" value="Wijzigen"  /></td></tr>
</table>
</div>

<!-- setting 3 -->
<div id='setting3' class='tab'>
<table class='hidden_settings'>
<tr><td class='fields'>Max quota (in mb, 0=ongelimiteerd): <? if(isset($error['img_quota'])) echo $error['img_quota'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="img_quota" maxlength="4" size='4' value="<? if(isset($settings['img_quota'])) echo $settings['img_quota']?>"  /></td></tr>
<tr><td class='fields'>Aantal foto's per pagina: <? if(isset($error['amount_photos'])) echo $error['amount_photos'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="amount_photos" maxlength="3" size='3' value="<? if(isset($settings['amount_photos'])) echo $settings['amount_photos']?>"  /></td></tr>
<tr><td class='fields'>Foto maximum max. breedte: <? if(isset($error['max_width'])) echo $error['max_width'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="max_width" maxlength="4" size='3' value="<? if(isset($settings['max_width'])) echo $settings['max_width']?>"  /></td></tr>
<tr><td class='fields'>Foto maximum max. hoogte: <? if(isset($error['max_height']))$error['max_height'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="max_height" maxlength="4" size='3' value="<? if(isset($settings['max_height'])) echo $settings['max_height']?>"  /></td></tr>
<tr><td class='fields'>Fotonaam veranderen toestaan</td></tr>
    <tr><td class='fields'><select name='rename_photo'>
 <?if($settings['rename_photo']==0){?>
<option value='0'>Nee</option>
<?}else{?>
<option value='1'>Ja</option>
<?}?>
<option value=' '> &nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['rename_photo'])) echo $error['rename_photo'];?></td></tr>
<tr><td class='fields'>Foto informatie tonen</td></tr>
    <tr><td class='fields'><select name='show_image_info'>
 <?if($settings['show_image_info']==0){?>
<option value='0'>Nee</option>
<?}else{?>
<option value='1'>Ja</option>
<?}?>
<option value=' '> &nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['show_image_info'])) echo $error['show_image_info'];?></td></tr>
<tr><td class='fields'>Camera informatie tonen</td></tr>
    <tr><td class='fields'><select name='show_camera_data'>
 <?if($settings['show_camera_data']==0){?>
<option value='0'>Nee</option>
<?}else{?>
<option value='1'>Ja</option>
<?}?>
<option value=' '> &nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['show_camera_data'])) echo error['show_camera_data'];?></td></tr>
<tr><td class='fields'>Preview foto grootte: <? if(isset($error['preview_size'])) echo $error['preview_size'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="preview_size" maxlength="4" size='3' value="<? if(isset($settings['preview_size'])) echo $settings['preview_size']?>"  /> px</td></tr>
<tr><td class='fields'>Niet lege albums verwijderen toestaan:</td></tr>
    <tr><td class='fields'><select name='delete_non_empty'>
 <?if($settings['delete_non_empty']==0){?>
<option value='<?=$settings['delete_non_empty']; ?>'>Nee</option>
<?}else{?>
<option value='<?=$settings['delete_non_empty']; ?>'>Ja</option>
<?}?>
<option value=' '>&nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['delete_non_empty'])) echo $error['delete_non_empty'];?></td></tr>
<tr><td class='fields'><input type="submit" class="submit" value="Wijzigen" /></td></tr>
</table>
</div>

<!-- setting 4 -->
<div id='setting4' class='tab'>
<table class='hidden_settings'>
<tr><td class='fields'>Poll gebruiken:</td></tr>
<tr><td class='fields'><select name='poll'>
 <?if($settings['poll']==0){?>
<option value='<?=$settings['poll']; ?>'>Nee</option>
<?}else{?>
<option value='<?=$settings['poll']; ?>'>Ja</option>
<?}?>
<option value=' '>&nbsp; </option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['poll'])) echo $error['poll'];?></td></tr>
<tr><td class='fields'>Meerdere polls actief: <? if(isset($error['multiple_polls'])) echo $error['multiple_polls'];?> </td></tr>
<tr><td class='fields'><select name='multiple_polls'>
 <?if($settings['multiple_polls']==0){?>
<option value='0'>Nee</option>
<?}else{?>
<option value='1'>Ja</option>
<?}?>
<option value=' '> &nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td><? if(isset($error['multiple_polls']))$error['multiple_polls'];?></td></tr>
<tr><td class='fields'><input type="submit" class="submit" value="Wijzigen" /></td></tr>
</table>
</div>


<!-- setting 5 -->
<div id='setting5' class='tab'>
<table class='hidden_settings'>
<tr><td class='fields'>Max aantal dagen geschiedenis: <? if(isset($error['day_interval'])) echo $error['day_interval'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="day_interval" maxlength="3" size='3' value="<? if(isset($settings['day_interval'])) echo $settings['day_interval']?>"  /></td></tr>
<tr><td class='fields'>Max aantal maanden geschiedenis: <? if(isset($error['month_interval'])) echo  $error['month_interval'];?></td></tr>
<tr><td class='fields'><input type="text" class='field' name="month_interval" maxlength="2" size='2' value="<? if(isset($settings['month_interval'])) echo $settings['month_interval']?>"  /></td></tr>
<tr><td class='fields'>Max aantal jaren geschiedenis: <? if(isset($error['year_interval'])) echo $error['year_interval'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="year_interval" maxlength="1" size='1' value="<? if(isset($settings['year_interval'])) echo $settings['year_interval']?>"  /></td></tr>
<tr><td class='fields'>Kleur status balk: <? if(isset($error['color_hitsbar'])) echo $error['color_hits_bar'];?></td></tr>
<tr><td class='fields'><input type="text" class='color field' maxlength="7" size='7' name="color_hits_bar" value="<? if(isset($settings['color_hits_bar'])) echo $settings['color_hits_bar']?>" /></td></tr>
<tr><td class='fields'>Kleur status balk border: <? if(isset($error['color_hits_bar_border'])) echo $error['color_hits_bar_border'];?> </td></tr>
<tr><td class='fields'><input type="text" class='color field' maxlength="7" size='7' name="color_hits_bar_border" value="<? if(isset($settings['color_hits_bar_border'])) echo $settings['color_hits_bar_border']?>" /></td></tr>
<tr><td class='fields'><input type="submit" class="submit" value="Wijzigen" /></td></tr>
</table>
</div>

<!-- setting 6 -->
<div id='setting6' class='tab'>
<table class='hidden_settings'>
<tr><td class='fields'>Toegestane extensies (komma gescheiden) <? if(isset($error['allowed_img_tags'])) echo $error['allowed_img_tags'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="allowed_img_tags" maxlength="30" size='30' value="<? if(isset($settings['allowed_img_tags'])) echo $settings['allowed_img_tags']?>"  /></td></tr>
<tr><td class='fields'>Maximum grootte (kb) <?  if(isset($error['max_img_size'])) echo$error['max_img_size'];?></td></tr>
<tr><td class='fields'><input type="text" class='field' name="max_img_size" maxlength="10" size='10' value="<? if(isset($settings['max_img_size'])) echo $settings['max_img_size']?>"  /></td></tr>
<tr><td class='fields'>Maximum hoogte (in px): <?  if(isset($error['max_img_height'])) echo $error['max_img_height'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="max_img_height" maxlength="4" size='4' value="<? if(isset($settings['max_img_height'])) echo $settings['max_img_height']?>"  /></td></tr>
<tr><td class='fields'>Maximum breedte (in px): <?  if(isset($error['max_img_width'])) echo $error['max_img_width'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="max_img_width" maxlength="4" size='4' value="<? if(isset($settings['max_img_width'])) echo $settings['max_img_width']?>"  /></td></tr>
<tr><td class='fields'>Image path: <?  if(isset($error['image_path'])) echo $error['image_path'];?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="image_path" value="<? if(isset($settings['image_path'])) echo $settings['image_path']?>" /></td></tr>
<tr><td class='fields'><input type="submit" class="submit" value="Wijzigen" /></td></tr>
</table>
</div>

</form>
    <script type="text/javascript">
 
		var mysetting=new ddtabcontent("setting_tabs") //enter ID of Tab Container
		mysetting.setpersist(true) //toogle persistence of the tabs' state
		mysetting.setselectedClassTarget("link") //"link" or "linkparent"
		mysetting.init()
 
	</script>


