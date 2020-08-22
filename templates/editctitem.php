<div class='crumble_path'><a href='<?=$_SERVER['PHP_SELF']?>?section=<?=$pre?>'><?=ucwords($title)?></a> - <?=ucwords($short)?> '<?=$ct_item['name']?>' bewerken</div>
    <form method="post" enctype="multipart/form-data" name="edit_ct_form" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
    <? if(isset($msg['ct'])) echo $msg['ct'];?>
        <table class='editform'>
        <tr><td><input type="hidden" name="edit_ct" value="edit_ct" /> </td></tr>
         <tr><td><? if(isset($msg['cat'])) echo $msg['cat'];?></td></tr>
        <tr><td class='fields'><?=ucwords($short)?>:<? if(isset($error['ct'])){ echo $error['ct']; }?> </td></tr>
        <tr><td class='fields'><input type='text' name="name" class='field <? if(isset($error['ct'])){?>input_error<?}?>' value="<? if(isset($ct_item['name'])) echo $ct_item['name']?>" /></td></tr>
<? if(isset($error['status'])){ ?><tr><td class='fields'><? echo $error['status'] ?></td></tr> <? }?>
<tr><td class='fields'><?=ucwords($short)?> actief:</td></tr>
    <tr><td class='fields'><select name='status'>
 <?
 if($ct_item['status']['status']!=""){
	 if($ct_item['status']['status']==0){?>
	<option value='<?=$ct_item['status']; ?>'>Nee</option>
	<?}else{?>
	<option value='<?=$ct_item['status']; ?>'>Ja</option>
	<?}
}?>
<option value=' '> &nbsp;</option>
<option value='0'>Nee</option>
<option value='1'>Ja</option>
</select></td><td></td></tr>
        <tr><td class='fields'><input type="submit" class="submit" value="Opslaan" /></td></tr>
        </table>
    </form>