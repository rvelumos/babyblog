<?
			
	echo   "<form method='post' action='".$_SERVER['PHP_SELF']."' name='loginform' id='loginform' onsubmit='login();return false;'><div class='login_container'><div class='top'><img src='images/lock.png' alt='' /><p>Login</p></div>
        ";
			echo "<table class='inlogform'>
			<tr><td>
            <input type='hidden' name='admin_login' value='user_loginform' />
            <input type='hidden' name='action' value='login' /></td></tr>
	    <tr><td>";
      if(isset($error['auth']))
        echo $error['auth'];
      echo "</td></tr>
			<tr><td class='indent'>Gebruikersnaam ";
			if(isset($error['un'])){
				echo $error['un'];
			}
			echo "</td></tr>
			<tr><td><input size='20' name='loginname' class='leftlogin ";
			if(isset($error['un'])){
				echo "input_error";
			}
			echo "'value='".$_POST['inlognaam']."'  /></td></tr>";
			echo "<tr><td class='indent'>Wachtwoord ";
			if(isset($error['pw'])){
				echo $error['pw'];
			}
			echo "</td></tr>
			<tr><td><input size='20' type='password' class='leftlogin ";
			if(isset($error['pw'])){
				echo "input_error";
			}
			echo "' name='password'  value=''  /></td></tr>		
			<tr><td><input type='submit' class='submit' onclick='login();return false;' value='Verzenden'/></td></tr>
			</table>
			</div>
			</form>
			</body></html>";
?>



