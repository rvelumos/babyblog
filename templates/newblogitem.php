<?
$max_no_img=1; 
?>
<div class='crumble_path'><a href='<?=$_SERVER['PHP_SELF']?>?section=posts'>Posts</a> &raquo; item toevoegen</div>
<div class="form_titel">Item toevoegen</div>
<form method="post" enctype="multipart/form-data" name="add_blog_form" action="<?=$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" >
<input type="hidden" name="new_blogitem" value="add_blog" />
<table class='editform'>
<tr><td class='fields'>Titel: <? if(isset($error['title'])) echo $error['title'];?> </td></tr>
<tr><td class='fields'><input type='text' name="title" class='field <? if(isset($error['title'])){?>input_error<?}?>' value="<? if(isset($_POST['title'])) echo $_POST['title']?>" /> </td></tr>
<tr><td class='fields'>Beschrijving: <? if(isset($error['description'])) echo $error['description'];?> </td></tr>
<tr><td class='fields'><textarea class='ta <? if(isset($error['description'])){?>input_error<?}?>' name="description" ><? if(isset($_POST['description'])) echo $_POST['description']?></textarea></td></tr>
<? for($i=1; $i<=$max_no_img; $i++){?>
    <tr><td class='fields'>Afbeelding (max. 1680x1050):<?if(isset($error['image'])){?><span class='error_astx'>*</span><?}?>  </td></tr>
    <tr><td class='fields'><input type="file" class='field' name="images[]" value="" /><img src='../images/plus.png' alt='' onclick="add()"/></td><td><? if(isset($error['image'])) echo $error['image'];?></td></tr>
<? } ?>
<tr><td>
<div id='image_input'>
</div></td></tr>

<tr><td>
<div id='last_field'>
</div></td></tr>
<tr><td class='fields'>Categorie: <? if(isset($error['category']))$error['category'];?></td></tr>
    <tr><td class='fields'><select name='category' <? if(isset($error['category'])){?>class="input_error"<? }?>>
 <?=$this->getCategoryItems();?>
 </select>
 Of nieuwe categorie: <input type="text" value="<? if(isset($_POST['new_cat'])) echo $_POST['new_cat']?>" name="new_cat" <? if(isset($error['category'])){?>class="input_error"<? }?> /><? if(isset($error['category'])) echo $error['category'];?></td></tr>
</td></tr>
    <tr><td class='fields'>Berichtstatus: <? if(isset($error['status'])) echo $error['status'];?></td></tr>
    <tr><td class='fields'><select name='status' <? if(isset($error['category'])){?>class="input_error"<? }?>>
 <? if(isset($blogitem['status']) && $blogitem['status']==0){?>
<option value='<?=$blogitem['status']; ?>'>Alleen opslaan</option>
<?}elseif(isset($blogitem['status']) && $blogitem['status']==1){?>
<option value='<?=$blogitem['status']; ?>'>Opslaan en publiceren</option>
<?}?>
<option value=' '> </option>
<option value='0'>Alleen opslaan</option>
<option value='1'>Opslaan en publiceren</option>
</select></td></tr>
<tr><td class='fields'><input type="submit" class="submit" value="Toevoegen" /></td></tr>
</table>
</form>


