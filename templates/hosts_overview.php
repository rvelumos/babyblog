
<form method="post" enctype="multipart/form-data" name="accept_form" action="<?=$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" >
<input type="hidden" name="accept" value="edit_accept" />
				<table style='margin-top:10px; float:left; clear:left;'><tr><td class='fields'>Gebruikers toestaan blog te lezen:</td></tr>
				<tr><td class='fields'><select name='website_accept_hosts'>
				<?
				if($settings['website_accept_hosts']==0){
					echo "<option value='0'>Niemand</option>";
				}elseif($settings['website_accept_hosts']==1){
					echo "<option value='1'>Alleen bekenden van lijst</option>";
				}else{
					echo "<option value='2'>Iedereen</option>";
				}
				echo "<option value=' '>&nbsp;</option>
				<option value='0'>Niemand</option>
				<option value='1'>Alleen bekenden van lijst</option>
				<option value='2'>Iedereen</option>
				</select></td><td>".$error['website_accept_hosts']."</td></tr>
				<tr><td class='fields'><input type=\"submit\" class=\"submit\" value=\"Wijzigen\" /></td></tr></table></form>";	
				?>