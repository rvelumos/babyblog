<div class='systeem'>

<?
if($this->isEditor() || $this->isAdmin())
?>

<div class='actions'>
<div class='add'><p><a href='admin.php?sectie=systeem&view=users&amp;mode=user_toevoegen'><img src='images/plus.png' alt=''/> Gebruiker toevoegen</a></p></div>
</div>

<?
while($users = mysql_fetch_array($result)){
	if($users['status']=='1'){
		$users_active .= "<tr><td>".ucwords($users['user'])."</td><td>".ucwords($users['naam'])."</td>";
		if($this->isEditor() || $this->isAdmin())
			$users_active .= "<td align='right'><a href='admin.php?sectie=systeem&view=users&amp;edit_user=".$users['user_id']."'><img src='images/edit.png' alt='' /></a> <a href='admin.php?sectie=systeem&view=users&amp;delete_user=".$users['user_id']."'><img src='images/cross.gif' alt='' /></a></td>";
		echo "</tr>";
	}else{
		$users_inactive .= "<tr><td>".ucwords($users['user'])."</td><td>".ucwords($users['naam'])."</td>";
		if($this->isEditor() || $this->isAdmin())
			$users_active .= "<td align='right'><a href='admin.php?sectie=systeem&view=users&amp;edit_user=".$users['user_id']."'><img src='images/edit.png' alt='' /></a> <a href='admin.php?sectie=systeem&view=users&amp;delete_user=".$users['user_id']."'><img src='images/cross.gif' alt='' /></a></td>";
		echo "</tr>";
	}
}

?>
<?if($users_active!=""){?>
<p><b>Actieve gebruikers</b></p>
<form method="post" enctype="multipart/form-data" name="set_db" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<table class='table_data'>
<tr class='top'><td>Naam</td><td>Groep</td><td></td></tr>
<?=$users_active;?>
</table>
<?}?>

<?if($users_inactive!=""){?>
<p><b>Inactieve gebruikers</b></p>
<form method="post" enctype="multipart/form-data" name="import" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<table class='table_data'>
<tr class='top'><td>Naam</td><td></td><td>Groep</td></tr>
<?=$users_inactive;?>
</td></tr>
</table>
</form>
<?}?>
</div>

