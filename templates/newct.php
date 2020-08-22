<div class='crumble_path'><a href='<?=$_SERVER['PHP_SELF']?>?section=<?=$pre?>'><?=ucwords($title)?></a> &raquo; <?=ucwords($short)?> toevoegen</div>
<div class="form_titel"><?=ucwords($short)?> toevoegen</div>
<? if(isset($error['exists'])) echo $error['exists'];?>
<? if(isset($msg['info'])) echo $msg['info'];?>
<form method="post" enctype="multipart/form-data" name="add_ct_form" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<input type="hidden" name="add_ct" value="add_ct" />
<table class='editform'>
    <tr><td class='fields'>Naam: <? if(isset($error['name'])) echo $error['name'];?> </td></tr>
	<tr><td class='fields'><input type='text' name="name" class='field <? if(isset($error['name'])){?>input_error<?}?>' value="" /> </td></tr>
<? if(isset($error['status'])){ ?><tr><td class='fields'><? if(isset($error['status'])) echo $error['status'] ?></td></tr> <? }?>
<tr><td class='fields'><?=ucwords($short)?> actief:</td></tr>
    <tr><td class='fields'><select name='status'>
 
 <?
 if(isset($_POST['status']) && $_POST['status']!=""){
	 if($_POST['status']==0){?>
	<option value='<?=$_POST['status']; ?>'>Nee</option>
	<?}elseif(isset($_POST['status']) && $_POST['status']!=""){?>
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