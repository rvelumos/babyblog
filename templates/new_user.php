<div class='editform'>
<? if(isset($msg['user'])) echo $msg['user'];?>
<form method="post" enctype="multipart/form-data" name="edit_form" action="<?=$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" >
<input type="hidden" name="new_user" value="new_user" />
				<table style='margin-top:10px; float:left;'><tr><td class='fields'><b>Gebruiker toevoegen:</b></td></tr>
				<tr><td class='fields'>Naam: <? if(isset($error['username'])) echo $error['username'];?></td></tr>
                <tr><td class='fields'><input class='field <? if(isset($error['username'])){?>input_error<?}?>' type="text" name="username" size="18" maxlength="16" value="<?=$_POST['gebruikersnaam']?>"/></td></tr>
                <tr><td class='fields'>Groep: <? if(isset($error['group'])) echo $error['group']?></td><td class='fields'><td class='fields'>
                <select name='groep' class='<? if(isset($error['group'])){?>input_error<?}?>'>
                <option value=''><? if(isset($user['name'])) echo $user['name']?></option>
                <option value=''></option>
                <? while($usergroups = $result->fetch_array(MYSQLI_BOTH)){?>
                     <option value='<?=$usergroups['id']?>'><?=$usergroups['name']?></option>
                <? } ?>
				</select></td></tr>
                <tr><td class='fields'>Inlognaam: <? if(isset($error['loginname']))$error['loginname']?></td>
                <tr><td class='fields'><input class='<? if(isset($error['loginname']){?>input_error<?}?>' type="text" name="loginname" size="18" maxlength="32" value="<?=$_POST['loginnaam']?>"/></td></tr>
                <tr><td class='fields'>Wachtwoord(min. 6 characters): <? if(isset($error['password'])) echo $error['password']?></td>
                <tr><td class='fields'><input class='<? if(isset($error['password'])){?>input_error<?}?>' type="text" name="password" size="18" maxlength="32" value="" /></td></tr>
                <tr><td class='fields'>Status: <? if(isset($error['status'])) echo $error['status']?></td></tr>
                 <tr><td class='fields'><select name='status' class='<? if($error['status']){?>input_error<?}?>'>
                 <?if($_POST['status']==0){?>
                <option value='<?=$_POST['status']; ?>'>Inactief</option>
                <?}else{?>
                <option value='<?=$_POST['status']; ?>'>Actief</option>
                <?}?>
                <option value=' '>&nbsp; </option>
                <option value='0'>Inactief</option>
                <option value='1'>Actief</option>
                </select></td><td></td></tr>
				<tr><td class='fields'><input type="submit" class="submit" value="Wijzigen" /></td></tr></table></form>
</div>