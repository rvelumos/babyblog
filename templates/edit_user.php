<div class='editform'>
<?=$msg['ip']?>
<form method="post" enctype="multipart/form-data" name="edit_form" action="<?=$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" >
<input type="hidden" name="edit_user" value="edit_user" />
				<table style='margin-top:10px; float:left;'><tr><td class='fields'><b>Gebruiker wijzigen:</b></td></tr>
				<tr><td class='fields'>Naam: <? if(isset($error['username'])) echo $error['username'];?></td></tr>
                <tr><td class='fields'><input class='field <? if(isset($error['username'])){?>input_error<?}?>' type="text" name="username" size="18" maxlength="16" value="<? if(isset($user['username'])) echo $user['username']?>"/></td></tr>
                <tr><td class='fields'>Groep:</td><td class='fields'><td class='fields'>
                <select name='groep'>
                <option value=''><?if isset($user['name'])) echo $user['name'];?></option>
                <option value=''></option>
                <? while($usergroups = $result2->fetch_array(MYSQLI_BOTH)){?>
                     <option value='<?=$usergroups['id']?>'><?=$usergroups['name']?></option>
                <? } ?>
				</select></td></tr>
                <tr><td class='fields'>Inlognaam: <?if(isset($error['username'])) echo $error['username']?></td>
                <tr><td class='fields'><input class='<? if(isset($error['loginname'])){?>input_error<?}?>' type="text" name="loginnaam" size="18" maxlength="32" value="<? if(isset($user['loginname'])) echo $user['loginname']?>"/></td></tr>
                <tr><td class='fields'>Wachtwoord(minimaal 6 characters):</td>
                <tr><td class='fields'><input class='<? if(isset($error['password'])){?>input_error<?}?>' type="text" name="password" size="18" maxlength="32" value="" /></td></tr>
                <tr><td class='fields'>Status:</td></tr>
                 <tr><td class='fields'><input type="hidden" name="edit_status" value="edit_status" /><select name='status' onchange="this.form.submit();">
                 <?if(isset($user['status']) && $user['status']==0){?>
                <option value='<?=$user['status']; ?>'>Inactief</option>
                <?}elseif(isset($user['status']) && $user['status']==0){?>
                <option value='<?=$user['status']; ?>'>Actief</option>
                <?}?>
                <option value=' '>&nbsp; </option>
                <option value='0'>Inactief</option>
                <option value='1'>Actief</option>
                </select></td><td><? if(isset($error['status'])) echo $error['status'];?></td></tr>
				<tr><td class='fields'><input type="submit" class="submit" value="Wijzigen" /></td></tr></table></form>
</div>