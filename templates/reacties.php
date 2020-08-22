<?				
echo "<form name='reactie_form' method='post' action=''/>
		<input type='hidden' name='post_reaction' />
		<table class='reactie_form'>
			<tr><td><p class='medium_titel'>Reactie plaatsen</p></td></tr>
			<tr><td>Naam:</td><td>";
			if(isset($error['name']))echo $error['name'];
			
echo "</td></tr>
			<tr><td><input type='text' class='form_field";
			if(isset($error['name']))
				echo " input_error";
			echo "'name='name' /></td></tr>
			<tr><td>Bericht:</td><td>";
			if(isset($error['message']))echo $error['message'];
			
echo "</td></tr>
			<tr><td><textarea class='ta_field";
			if(isset($error['message']))
				echo " input_error";
			echo "' name='message' ></textarea></td></tr>
			<tr><td><input type='submit' value='Versturen' class='submit' /></td></tr>
		</table>
";
?>