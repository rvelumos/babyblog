<?
$max_no_img=6; 
?>

<form method="post" enctype="multipart/form-data" name="edit_blogitem_form" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<input type="hidden" name="edit_blogitem" value="edit_blog" />
<table class='editform'>
<tr><td class='fields'><div class="form_titel">Bericht aanpassen</div></td></tr>
<tr><td class='fields'><? if(isset($msg['blog_item']))echo $msg['blog_item']?></td></tr>
<tr><td class='fields'>Titel: <? if(isset($error['title']))echo $error['title']?> </td></tr>
<tr><td class='fields'><input type="text" class='field' name="title" value="<? if(isset($blogitem['title'])){ echo  $blogitem['title']; }?>" /></td></tr>
<tr><td class='fields'>Beschrijving: <? if(isset($error['description']))echo $error['description'];?> </td></tr>
<tr><td class='fields'><textarea class='ta' name="description" rows='20' cols='60' ><?=$blogitem['description']?></textarea></td></tr>
<tr><td class='fields'>Huidige foto's:</td></tr>
<tr><td class='fields'><div class='image'>

<? 
for($i=1; $i<=9; $i++){
	if(isset($blogitem["image$i"]) && $blogitem["image$i"] != ''){ 
		echo "<table class='blog_image'><tr><td align='right'>";
		if($this->isEditor() || $this->isAdmin())
			echo "<a href='index.php?section=posts&amp;edit_item=".$blogitem['id']."&amp;edit_image=$i'><img src='../images/edit.png' /></a>&nbsp;&nbsp;<a href='index.php?section=posts&amp;edit_item=".$blogitem['id']."&amp;delete_image=$i'><img src='../images/cross.gif' /></a>";
		echo "</td></tr>";
		echo "<tr><td><img src='../image.php?thumb={$blogitem["image$i"]}&amp;size=$size' alt='' /></td></tr></table>";
		$max_no_img = $max_no_img - 1;
	}
}
?>
</div>
</td></tr>
<? for($i=1; $i<=$max_no_img; $i++){?>
    <tr><td class='fields'>Afbeelding (max. 1680x1050):<?if(isset($error['image'])){?><span class='error_astx'>*</span><?}?>  </td></tr>
    <tr><td class='fields'><input type="file" class='fields' name="images[]" value="" /></td><td><?if(isset($error['image'])){ echo $error['image']; }?></td></tr>
<? } ?>
<tr><td class='fields'>Categorie</td></tr>
    <tr><td class='fields'><select name='category'>
 <?if(isset($blogitem['category'])){?>
 <option value='<?=strtolower($blogitem['category'])?>'><?=$blogitem['category']?></option>
 <?}?> 
 <?=$this->getCategoryItems();?>
 </select> Of nieuwe categorie: <input type="text" value="<? if(isset($_POST['new_cat'])) echo $_POST['new_cat'];?>" name="new_cat" /><? if(isset($error['category']))echo $error['category'];?></td></tr>
    <tr><td class='fields'>Bericht zichtbaar?</td></tr>
    <tr><td class='fields'><select name='status'>
 <?if(isset($blogitem['status']) && $blogitem['status']==0){?>
<option value='<?=$blogitem['status']; ?>'>Alleen opslaan</option>
<?}elseif(isset($blogitem['status']) && $blogitem['status']==1){?>
<option value='<?=$blogitem['status']; ?>'>Opslaan en publiceren</option>
<?}?>
<option value=' '> </option>
<option value='0'>Alleen opslaan</option>
<option value='1'>Opslaan en publiceren</option>
</select><? if(isset($error['status']))$error['status'];?></td></tr>
<tr><td class='fields'><input type="submit" class="submit" value="Wijzigen" /></td></tr>
</table>
</form>



