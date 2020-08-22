<div class='crumble_path'><a href='<?=$_SERVER['PHP_SELF']?>?section=photoalbum'>Albums</a> &raquo; album toevoegen</div>
<div class="form_titel">Album toevoegen</div>
<? if(isset($msg['notice'])) echo $msg['notice'];?>
<form method="post" enctype="multipart/form-data" name="add_blog_form" action="<?=$_SERVER['PHP_SELF']."?".htmlentities($_SERVER['QUERY_STRING'])?>" >
<input type="hidden" name="album_form" value="add_blog" />
<table class='editform'>
<tr><td class='fields'><? if(isset($msg['album_exists']))echo $msg['album_exists']?></td></tr>
<? if(isset($error['name'])){ ?><tr><td class='fields'></td></tr> <? }?>
<tr><td class='fields'>Album naam:<? if(isset($error['name'])) echo $error['name']?></td></tr>
<tr><td class='fields'><input type='text' name="name" class='field <? if(isset($error['name'])){ ?>input_error<?}?>' value="<? if(isset($_POST['name'])) echo $_POST['name']?>" /> </td></tr>
<tr><td class='fields'>Omschrijving album:<?if(isset($error['description'])){?>* <? echo $error['description'];?><?}?> </td></tr>
<tr><td class='fields'><textarea class='ta' name="description" rows='5' cols='50'><? if(isset($_POST['description'])) echo $_POST['description']?></textarea></td></tr>
<tr><td class='fields'>Album thumb(max. 200x200):<? if(isset($error['thumb'])){?><span class='error_astx'>*</span><?}?>  </td></tr>
<tr><td class='fields'><input type="file" class='field' name="thumb" value="" /></td><td><? if(isset($error['thumb']))echo $error['thumb'];?></td></tr>
<? if(isset($error['status'])){ ?><tr><td class='fields'><? if(isset($error['status'])) echo $error['status'] ?></td></tr> <? }?>
<tr><td class='fields'>Album actief:</td></tr>
    <tr><td class='fields'><select name='status'>
 
 <?
 
 if(isset($_POST['status']) && $_POST['status']!=""){
	 if($_POST['status']==0){?>
	<option value='<?=$_POST['status']; ?>'>Nee</option>
	<?}else{?>
	<option value='<?=$_POST['status']; ?>'>Ja</option>
	<?}
}?>
<option value=' '> &nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td></td></tr>
<tr><td class='fields'><input type="submit" class="submit" value="Toevoegen" /></td></tr>
</table>
</form>


