<div class='editform'>
<? if(isset($msg['ip'])) echo $msg['ip'];?>
<form method="post" enctype="multipart/form-data" name="edit_form" action="<?=$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" >
<input type="hidden" name="edit_ip" value="edit_ip" />
				<table style='margin-top:10px; float:left;'><tr><td class='fields'><b>Host details wijzigen:</b></td></tr>
				<tr><td class='fields'>IP : <? if(isset($error['ip'])) echo $error['ip'];?></td></tr>
                <tr><td class='fields'><input class='field <? if(isset($error['ip'])){?>input_error<?}?>' type="text" name="ip" size="18" maxlength="16" value="<?=$rs['ip']?>"/></td></tr>
                <tr><td class='fields'>Naam :</td><td class='fields'><input class='field' type="text" name="name" value="<?=$rs['name']?>"/></td></tr>
				<tr><td class='fields'><input type="submit" class="submit" value="Wijzigen" /></td></tr></table></form>
</div>