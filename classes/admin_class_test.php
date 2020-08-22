<?php

class Admin{
	
	public function __construct() {
		$this->link = new MySQLi('localhost', 'xx', 'xx', "xx");
	}
	
	public function getPolls(){
		global $prefix, $settings;
		
		if(isset($_GET['edit_poll'])){
			$id = $_GET['edit_poll'];
			$this->editPoll($id);	
		}elseif(isset($_GET['delete_poll'])){
			$id = $_GET['delete_poll'];
			$this->deletePoll($id);
		}elseif(isset($_GET['poll_stats'])){
			$id = $_GET['poll_stats'];
			echo $this->pollStats($id);	
		}elseif(isset($_GET['mode']) && $_GET['mode']=='add_poll'){
			$this->addPoll();
		}else{
			$sql = "SELECT * FROM ".$prefix."pollquestion ";
			if(!$result = $this->link->query($sql))
				$this->db_message($sql);
			
			echo "<div class='polls'><div class='form_titel'>Polls</div>";
			if($this->isEditor() || $this->isAdmin())
				echo "<div class='actions'><div class='add'><a href='index.php?section=polls&amp;mode=add_poll'><img src='../images/plus.png' alt='' /> Poll toevoegen</a></div></div><br />";
				echo "<br /><br /><p><b>Actieve pol:</b></p><table class='table_data'>
					<tr class='top'><td>Poll ID</td><td>Vraag</td><td></td></tr>";
				while($a_polls = $result->fetch_array(MYSQLI_BOTH)){
					if($a_polls['status']==1){
						echo "<tr><td>".$a_polls['id']."</td><td>".$a_polls['poll_question']."</td><td><a href='index.php?section=polls&amp;poll_stats=".$a_polls['id']."'><img src='../images/stats.png' alt='' /></a>";
						if($this->isEditor() || $this->isAdmin())
							echo "<a href='index.php?section=polls&amp;edit_poll=".$a_polls['id']."'><img src='../images/edit.png' alt='' /></a>  <a href='index.php?section=polls&amp;delete_poll=".$a_polls['id']."'><img src='../images/cross.gif' alt='' /></a>";
						echo "</td></tr>";
					}
				echo "</table>";
				
				echo "<p style='float:left; clear:both'><b>Inactieve polls:</b></p>
				<table class='table_data'>
					<tr class='top'>
						<td>Poll ID</td><td>Vraag</td><td></td>
					</tr>";
					
					while($i_polls = $result->fetch_array(MYSQLI_BOTH)){
						echo "<tr><td>".$i_polls['id']."</td><td>".$i_polls['poll_question']."</td><td>";
						if($this->isEditor() || $this->isAdmin())
							echo "<a href='index.php?section=polls&amp;edit_poll=".$i_polls['id']."'><img src='../images/edit.png' alt='' /></a> <a href='index.php?section=polls&amp;poll_stats=".$i_polls['id']."'><img src='../images/stats.png' alt='' /></a> <a href='index.php?section=polls&amp;delete_poll=".$i_polls['id']."'><img src='../images/cross.gif' alt='' /></a>";
						echo "</td></tr>";
					}
					echo "</table>";
			}
			echo "</div>";
		}
	}
	
	public function addPoll(){
		global $prefix, $settings;
		
		if(isset($_POST['add_poll'])){
			if(!isset($_SESSION['section'])){
				$question = trim(mysqli_real_escape_string($this->link, $_POST['question']));
				
				if($question != ""){
					$sql = "INSERT INTO ".$prefix."pollquestion(poll_question, status) VALUES('$question','1') ";
					if(!$result = $this->link->query($sql))
						$this->db_message($sql);
					
					$_SESSION['question'] = $question;
					$_SESSION['section'] = "answer";
				}else{
					$error['question']= $this->message("FIELD_ERROR","Gelieve een vraag in te voeren.");	
				}
			}else{
				if(isset($_POST['answer'])){
          $answer = $_POST['answer'];
				
          $sql = "SELECT id FROM ".$prefix."pollquestion WHERE poll_question = '{$_SESSION['question']}'";
          if(!$result = $this->link->query($sql))
              $this->db_message($sql);

            if($result->num_rows > 0){
              $rs = $result->fetch_array(MYSQLI_BOTH);
              $poll_id = $rs['id'];

              foreach($answer as $key => $value){
                $value= trim(mysqli_real_escape_string($this->link, $value));
                if($value != ""){                  
                  $sql = "INSERT INTO ".$prefix."pollanswer(poll_id, poll_answer) VALUES('$poll_id', '$value') ";
                  if(!$result = $this->link->query($sql))
                    $this->db_message($sql);
                  else
                    $answer = TRUE;
                }else{
                  $error['answer'] = $this->message("FIELD_ERROR","Gelieve een antwoord in te vullen.");
                }
              }
              //save the answer(s) first, then show the message
              if($answer == TRUE){
                echo $this->message("NOTICE","De poll is toegevoegd.");
                $_SESSION['section'] = "";
                $_SESSION['question'] = "";
                die();
              }

            }else{
              //should not be possible
              echo $this->message("ERROR", "Fout bij toevoegen van antwoorden. Probeer het opnieuw");
              unset($_SESSION['section']);
            }
        }
			}
		}
		require('../templates/newpollitem.php');
	}
	
	public function editPoll($id){
		global $prefix;
		
		$sql = "SELECT * FROM ".$prefix."pollquestion WHERE id = '$id' ";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		
		if($result->num_rows > 0){
			$question = $result->fetch_array(MYSQLI_BOTH);
				if(isset($_POST['edit_question'])){
					$new_question = trim(mysqli_real_escape_string($this->link, $_POST['question']));
	
					if($new_question != ""){
						//if old and new question are equal, do nothing
						if($new_question != $question['poll_question']){
							$sql = "UPDATE ".$prefix."pollquestion SET poll_question = '$new_question' WHERE id = '$id' ";
							if(!$result = $this->link->query($sql))
								$this->db_message($sql);
							
							$msg['question'] = "<i>Het antwoord is succesvol opgeslagen.</i>";
							$_SESSION['question'] = $question;
							$_SESSION['poll']['section'] = "answer";
							$question['poll_question'] = trim($_POST['question']);
						}
					}else{
							$error['question']= $this->message("ERROR","Gelieve een vraag in te voeren.");	
					}
				}
				
				if(isset($_GET['delete_answer'])){
					$answer = $_GET['delete_answer'];
					
					$sql = "SELECT * FROM ".$prefix."pollanswer WHERE id = '$answer'";
					if(!$result = $this->link->query($sql))
								$this->db_message($sql);
					if($result->num_rows > 0){
						$sql = "DELETE FROM ".$prefix."pollanswer WHERE id = '$answer'";
						if(!$result = $this->link->query($sql))
								$this->db_message($sql);
						else{
							echo $this->message("NOTICE","Antwoord is verwijderd...");
							echo "<meta http-equiv=\"refresh\" content=\"1;URL=index.php?section=polls&amp;edit_poll=".$_GET['edit_poll']."\" />";
							die();
						}
					}else{
						$msg['answer'] = $this->message("ERROR","Fout bij verwijderen van antwoord. ID niet gevonden in de database.");
					}
				}
				
				if(isset($_POST['edit_answer'])){
					$answer = $_POST['answer'];
					
					$i = 0;
					foreach($answer as $key => $value){
						$answer_id = $_POST['hidden_id'][$i];
            $value = trim(mysqli_real_escape_string($this->link, $value));
						if($value != ""){
							//save a new answer
							if($answer_id==""){
								$sql = "INSERT INTO ".$prefix."pollanswer(poll_id, poll_answer) VALUES('$id', '$value') ";

								if(!$result = $this->link->query($sql))
									$this->db_message($sql);
								$msg['answer'] = "<i>Antwoord(en) succesvol opgeslagen</i>";               
							}else{
                //update an old answer
								$sql = "UPDATE ".$prefix."pollanswer SET poll_id = '$id', poll_answer = '$value' WHERE id = '$answer_id'";
								if(!$result = $this->link->query($sql))
									$this->db_message($sql);
								$msg['answer'] = "<i>Antwoord(en) succesvol opgeslagen</i>";
							}
						}else{
							$error['answer'] = $this->message("ERROR","Gelieve een antwoord in te vullen.");	
						}
						$i++;
					}
					//alle values eerst opslaan dan pas message
					if($answer == TRUE){
						echo $this->message("NOTICE","De poll is aangepast.");
						$_SESSION['section'] = "";
						$_SESSION['question'] = "";
						die();
					}
					$result->free();
				}
				
				if(isset($_POST['edit_status'])){
					$status = $_POST['status'];
	
					if($status != ""){
						$sql = "SELECT * FROM ".$prefix."pollquestion WHERE status = '1'";
						if(!$result = $this->link->query($sql))
								$this->db_message($sql);
						
						if($result->num_rows < 1){
							$question['status'] = $status;
							$sql = "UPDATE ".$prefix."pollquestion SET status = '{$question['status']}' WHERE id = '$id' ";
							if(!$result = $this->link->query($sql))
								$this->db_message($sql);
						}else{
							$error['status']=$this->message("ERROR","Er is al 1 actieve poll. Zet deze eerst op inactief.");	
						}
					}else{
						$error['status']=$this->message("ERROR","Gelieve een status in te voeren.");	
					}
				}
			
			$sql = "SELECT * FROM ".$prefix."pollanswer WHERE poll_id = '$id' ";
			if(!$result = $this->link->query($sql))
					$this->db_message($sql);
			
			require('../templates/editpollitem.php');
		}else{
			echo $this->message("ERROR", "De poll bestaat niet. Probeer het opnieuw.</p>");
		}
	}
	
	public function deletePoll($id){
		global $settings, $prefix;
		
		$sql = "SELECT * FROM ".$prefix."pollquestion WHERE id = '$id' ";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		
    //delete related answers first
		if($result->num_rows > 0){
			$sql = "DELETE FROM ".$prefix."pollanswer WHERE poll_id = '$id' ";
			if(!$result = $this->link->query($sql))
					$this->db_message($sql);
			else{
				$sql = "DELETE FROM ".$prefix."pollquestion WHERE id = '$id' ";
				if(!$result = $this->link->query($sql))
					$this->db_message($sql);
			}
			
			$content = $this->message("NOTICE","De poll is verwijderd, moment geduld...");
			$content .= "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?section=polls\" />";
		}else{
			$content = $this->message("ERROR", "De poll id bestaat niet. Probeer het opnieuw");
		}
		return $content;
	}
	
	public function pollStats($id){
		global $prefix, $settings;
				$sql = "SELECT SUM(amount) AS total FROM ".$prefix."pollanswer WHERE poll_id = '$id'";
				if(!$result = $this->link->query($sql))
					$this->db_message($sql);
				
				$rs = $result->fetch_array(MYSQLI_BOTH);
				$total = $rs['total'];

				$result->free();
				
				$sql = "SELECT * FROM ".$prefix."pollanswer WHERE poll_id = '$id'";
				if(!$result = $this->link->query($sql))
					$this->db_message($sql);
				
				$content = "<div class='crumble_path'><a href='index.php?section=polls'>Polls</a> - Poll resultaten</div>
				<div class='poll_content'>Aantal stemmen: $total <br /><br />";
				while($poll_result = $result->fetch_array(MYSQLI_BOTH)){
					if($poll_result['amount'] > 0)
						$percentage = round(($poll_result['amount'] / $total) * 100) ."%"; 
					else
						$percentage = "0%";
					
					if($poll_result['poll_answer']=='Jongen'){
						$border = "#8c9cea";
						$color = "#D8EEFA";
					}else{
						$border = "#ea8ccc";
						$color = "#fad7fa";
					}
					$content .= "<div class='poll_option'><div class='left'><span class='answer_left'>".$poll_result['poll_answer']."</span></div><div class='right'><span class='pct_right'>$percentage</span>";
					$content .= "<div class='line' style='background-color:$color; border: 1px solid $border; width:$percentage;'></div></div>";
				}
		$content .= "</div>";
		$result->free();
		
		return $content;
	}
	
	public function getCtItems(){
		global $prefix, $settings;
		
    //switch between tags or categories
    
		$section="";
		if(isset($_GET['section']))$section = $_GET['section'];

		switch($section){
			case "tags":
				$pre = "tags";
				$short = "tag";
				$title = "Tags";
				$cnt_table = "tags";
				$col_id = "tag_id";
			break;
			case "categories":
				$pre = "categories";
				$short = "category";
				$title = "Categorie&euml;n";
				$cnt_table = "posts";
				$col_id = "id";
			break;
		}
		
		if(isset($_GET['edit_ct'])){
			$id = $_GET['edit_ct'];
			$this->editCt($id, $short, $pre, $cnt_table, $title, $col_id);	
		}elseif(isset($_GET['delete_ct'])){
			$id = $_GET['delete_ct'];
			$this->deleteCt($id, $short, $pre, $col_id, $title);
		}elseif(isset($_GET['details'])){
			$details = $_GET['details'];
			$this->ctDetails($details, $pre, $title, $cnt_table, $short);	
		}elseif(isset($_GET['mode']) && $_GET['mode']=='add_ct'){
			$this->addCt($pre, $cnt_table, $short, $title);
		}else{
			$sql = "SELECT * FROM ".$prefix.$pre;
			if(!$result = $this->link->query($sql))
					$this->db_message($sql);
			$sql2 = "SELECT category FROM ".$prefix."posts ";
			if(!$result2 = $this->link->query($sql2))
					$this->db_message($sql2);
			$sum_cts = $result2->num_rows;
			
			require('../templates/ct_overview.php');
		}
	}
	
	public function addCt($pre, $cnt_table, $short, $title){
			global $prefix, $settings;
    
			if(isset($_POST['add_ct']) && $_POST['add_ct']!=""){
				$ct_item['name'] = trim(mysqli_real_escape_string($this->link, $_POST['name']));
				$ct_item['status'] = $_POST['status'];
				
				if($ct_item['name'] != "" ){
					$sql = "SELECT name FROM ".$prefix.$pre." WHERE name = '".$ct_item['name']."' ";
					if(!$result = $this->link->query($sql))
						$this->db_message($sql);
					
					if($result->num_rows < 1){	
						$sql = "INSERT INTO ".$prefix.$pre." (name, status) VALUES ('".$ct_item['name']."', '".$ct_item['status']."') ";
						if(!$result = $this->link->query($sql))
							$this->db_message($sql);
						else
							$msg['info'] = $this->message("NOTICE","De $short is succesvol toegevoegd.");
						unset($album_item);
					}else{
						$error['exists'] = $this->message("ERROR", ucwords($short)." bestaat al. Probeer een ander.");	
					}
				}else{
					if($ct_item['name']=="")
						$error['name']= $this->message("FIELD_ERROR","Geef een naam op.");
				}
			}
      require('../templates/newct.php');
			
		}	

	public function editCt($id, $short, $pre, $cnt_table, $title, $col_id){
		global $prefix, $settings;
		
		$sql = "SELECT * FROM ".$prefix.$pre." WHERE $col_id = '$id' ";
    if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		
		if($result->num_rows > 0){
			$rs = $result->fetch_array(MYSQLI_BOTH);
				if(isset($_POST['edit_ct'])){
					if(trim($_POST['name'])!=""){
							$new_ct = mysqli_real_escape_string($this->link, $_POST['name']);
							$new_status = $_POST['status'];
							$sql = "UPDATE ".$prefix.$pre." SET name = '$new_ct', status = '$new_status' WHERE $col_id = '$id' ";
           
              if(!$result = $this->link->query($sql))
                $this->db_message($sql);
							else
							  $msg['ct'] = $this->message("NOTICE","De $short is succesvol bewerkt.");
					}else{
							$error['ct']=$this->message("FIELD_ERROR","Gelieve een $short in te voeren.");	
					}
				}

			$sql = "SELECT * FROM ".$prefix.$pre." WHERE $col_id = '$id' ";			
      if(!$result = $this->link->query($sql))
				$this->db_message($sql);
			$ct_item = $result->fetch_array(MYSQLI_BOTH);

			require('../templates/editctitem.php');
		}else{
			$this->message("ERROR", "Deze $short komt niet voor in de database.");	
		}
	}
	
	public function deleteCt($id, $short, $pre, $col_id, $title){
		global $settings, $prefix;
		
		if($short == "tag"){
			$sql_c = "SELECT * FROM ".$prefix."tags t
 					INNER JOIN ".$prefix."tags_items ti ON t.tag_id = ti.tag_id
 					WHERE t.tag_id = '$id'
					";

		}else{			
			$sql_c = "SELECT * FROM ".$prefix."posts i
						INNER JOIN ".$prefix."categories c ON i.category = c.name
						WHERE c.id = '$id'
						";
    
		}
    if(!$result = $this->link->query($sql_c))
				$this->db_message($sql_c);

		echo "<div class='crumble_path'><a href='".$_SERVER['PHP_SELF']."?section=$pre'>$title</a> &raquo; $short verwijderen</div>";
	
		if($result->num_rows < 1){
			$sql = "DELETE FROM ".$prefix.$pre." WHERE $col_id = '$id' ";
			 if(!$result = $this->link->query($sql))
				$this->db_message($sql);
      
			echo $this->message("NOTICE","De categorie is verwijderd, moment geduld...");
			echo "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?section=$pre\" />";
		}else{
			$aantal = $result->num_rows;
			echo $this->message("ERROR", "Verwijderen mislukt: Er zijn $aantal item(s) gekoppeld aan deze categorie.");

		}
	}
	
	public function security(){
		global $prefix, $ip;
		
    $view='';
		if(isset($_GET['view']))$view = $_GET['view'];
		
		//overview 
		echo "<div class='form_titel'>Beveiliging</div>
		<div class='interval'><div class='option'><a href='".$_SERVER['PHP_SELF']."?section=security&amp;view=hosts'>Hosts</a></div><div class='option'><a href='".$_SERVER['PHP_SELF']."?section=security&amp;view=blacklist'>Blacklist</a></div></div>";
		
		switch($view){
			default:
			case "hosts":
				if(isset($_GET['edit_ip'])){
					$sql = "SELECT * FROM ".$prefix."known_hosts WHERE id = '".$_GET['edit_ip']."'";
					if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
          
					if($result->num_rows>0){
						$rs =$result->fetch_array(MYSQLI_BOTH);
						$result->free();
						
						if(isset($_POST['edit_ip'])){
							$rs['ip'] = trim($_POST['ip']);	
							$rs['name'] = trim($_POST['name']);
							$ip = trim($_POST['ip']);	
							
							if($ip != "" && $this->check_valid_ip($ip)){
								$sql = "UPDATE ".$prefix."known_hosts SET
									ip = '".$rs['ip']."',
									name = '".$rs['name']."' 
								WHERE id = '".$_GET['edit_ip']."'";
								if(!$result = $this->link->query($sql))
				          $this->db_message($sql);
								
								$msg['ip'] = $this->message("NOTICE","Succesvol opgeslagen");
							}else{
								$error['ip'] = $this->message("FIELD_ERROR","Ongeldig IP, probeer het opnieuw.");
							}
						}
						require('../templates/edit_host.php');
					}else{
						$this->message("ERROR","Fout bij wijzigen, ip is niet in de database gevonden.");
					}
					
				}else{
					if(isset($_POST['hostform'])){
						$rs['ip'] = trim($_POST['ip']);
						$ip = trim($_POST['ip']);
						if(isset($rs['name']))$rs['name'] =trim($_POST['name']);
						
						$sql="SELECT * FROM ".$prefix."known_hosts WHERE ip = '".$rs['ip']."'";
            if(!$result = $this->link->query($sql))
			      	$this->db_message($sql);
						
						if($result->num_rows<1){
							if($rs['ip']!="" && $this->check_valid_ip($ip)){
								$result="INSERT INTO ".$prefix."known_hosts (ip, naam)VALUES('".$rs['ip']."','".$rs['name']."')";
                if(!$result = $this->link->query($sql))
				          $this->db_message($sql);
								
								$msg['ip'] = $this->message("NOTICE","Het ip is toegevoegd op de lijst.");
							}else{
								$error['ip'] = $this->message("ERROR","Geef een juist ip op.");	
							}
						}else{
							$msg['ip'] = $this->message("ERROR","Het ip staat al in de lijst");	
						}
					}
					
					if(isset($_GET['delete_ip'])){
						$delete_id = $_GET['delete_ip'];
						$sql = "SELECT * FROM ".$prefix."known_hosts WHERE id = '".$delete_id."'";
            if(!$result = $this->link->query($sql))
				      $this->db_message($sql);
					
						if($result->num_rows>0){
							$sql = "DELETE FROM ".$prefix."known_hosts WHERE id = '".$delete_id."'";
              if(!$result = $this->link->query($sql))
				        $this->db_message($sql);
                 
							echo $this->message("NOTICE","Ip is verwijderd, een moment geduld...");
							echo "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?section=beveiliging&view=hosts\" />";
							die();
						}else{
							echo $this->message("ERROR","Fout bij verwijderen, ip is niet in de database gevonden.");	
						}
					}
					
					echo "<p style='clear:both'><b>Bekende ip's</p></b><small>IP Filter - Toegevoegde gebruikers</small><table class='log'><tr><th><b>IP</b></th><th><b>Gebruiker</b></th><th>&nbsp;</th></tr>";
					$sql ="SELECT * FROM ".$prefix."known_hosts";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);       
                 
					while($hosts = $result->fetch_array(MYSQLI_BOTH)){
						echo "<tr><td>".$hosts['ip']."</td><td>".$hosts['name']."</td><td align='right'>";
						if($this->isEditor() || $this->isAdmin())
							echo "<a href='index.php?section=security&view=hosts&edit_ip=".$hosts['id']."'><img src='../images/edit.png' /></a>&nbsp;&nbsp;<a href='index.php?section=security&view=hosts&delete_ip=".$hosts['id']."'><img src='../images/cross.gif' /></a>";
						echo "</td></tr>";
					}
					echo "</table>";
					
					$result->free();
					
					if(isset($msg['ip']))echo $msg["ip"];
					echo "<form method=\"post\" enctype=\"multipart/form-data\" name=\"add_host_form\" action=".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']." >
<input type=\"hidden\" name=\"hostform\" value=\"add_host\" />
					<table style='margin-top:10px; float:left;clear:both;'>";
          if(isset($error['ip']))
					  echo "<tr><td>".$error['ip']."</td></tr>";
					echo "<tr><td class='fields'>Ip toevoegen:</td><td>Naam:</td></tr>
					<tr><td class='fields'><input type='text' name='ip' ";
					if(isset($error['ip']))
						echo "class=\"input_error\"";
					echo "/></td><td><input type='text' name='naam' /><input type=\"submit\" class=\"submit\" value=\"Toevoegen\" src='../images/plus.png' /></td></tr></table></form>";
					
					if(isset($_POST['accept'])){
						$settings['website_accept_hosts'] = intval($_POST['website_accept_hosts']);
						
							$sql = "UPDATE ".$prefix."settings SET
									website_accept_hosts = '".$settings['website_accept_hosts']."' ";
							if(!$result = $this->link->query($sql))
				        $this->db_message($sql);
					}
					
					$sql = "SELECT * FROM ".$prefix."settings";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
					
					$rs = $result->fetch_array(MYSQLI_BOTH);
					$settings['website_accept_hosts'] = $rs['website_accept_hosts'];
					
					require('../templates/hosts_overview.php');
				}
			break;
			
			case "blacklist":
				if(isset($_GET['edit_ip'])){
					$sql ="SELECT * FROM ".$prefix."blacklist WHERE id = '".$_GET['edit_ip']."'";				
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
          
					if($result->num_rows>0){
						$rs = $result->fetch_array(MYSQLI_BOTH);
						$result->free();
						
						if(isset($_POST['edit_black_ip'])){
							$rs['ip'] = trim($_POST['ip']);	
							$ip = $rs['ip'];
							$rs['reason'] = trim($_POST['reason']);
							
							if($ip!="" && $this->check_valid_ip($ip)){
								$sql = "UPDATE ".$prefix."blacklist SET
									ip = '".$ip."',
									reason = '".$rs['reason']."' 
								WHERE id = '".$_GET['edit_ip']."'";
								if(!$result = $this->link->query($sql))
				          $this->db_message($sql);							
              }else{
								$error['ip'] = $this->message("FIELD_ERROR", "Vul een juist ip in.");
							}
							
							$msg['ip'] = $this->message("NOTICE",'Succesvol opgeslagen');
						}
						require('../templates/edit_blacklist.php');
					}else{
						echo $this->message("ERROR", "Fout bij wijzigen, ip is niet in de database gevonden.");
					}
					
				}else{
					if(isset($_GET['delete_ip'])){
						$sql = "SELECT * FROM ".$prefix."blacklist WHERE id = '".$_GET['delete_ip']."'";		
            if(!$result = $this->link->query($sql))
				      $this->db_message($sql);
						if($result->num_rows>0){
							$sql = "DELETE FROM ".$prefix."blacklist WHERE id = '".$_GET['delete_ip']."'";
              if(!$result = $this->link->query($sql))
				        $this->db_message($sql);
              else
							  $msg["ip"] = $this->message("NOTICE","Ip is verwijderd.");
						}else{
							$error["ip"] = $this->message("ERROR","Fout bij verwijderen, ip is niet in de database gevonden.");	
						}
					}
					
					if(isset($_POST['blacklist'])){
            $bl_ip='';
            $reason="";
						if(isset($_POST['ip']))
            {
              $rs['ip'] = $_POST['ip'];
						  $bl_ip = $_POST['ip'];
            }
            if(isset($_POST['reason']))
						  $rs['reason'] = trim(mysqli_real_escape_string($this->link, $_POST['reason']));
						
						$sql="SELECT * FROM ".$prefix."blacklist WHERE ip = '$bl_ip'";						
            if(!$result = $this->link->query($sql))
				       $this->db_message($sql);
            
						if($result->num_rows<1){
							if($bl_ip!="" && $this->check_valid_ip($bl_ip)){
								$sql="INSERT INTO ".$prefix."blacklist (ip, reason)VALUES('$bl_ip','".$rs["reason"]."')";
								if(!$result = $this->link->query($sql))
				          $this->db_message($sql);
                else
								  $msg['ip'] = $this->message("NOTICE","Het ip is toegevoegd op de lijst.");
							}else{
								$error['ip'] = $this->message("ERROR","Geef een juist ip op.");	
							}
						}else{
							$error['ip'] = $this->message("ERROR","Het ip staat al op de blacklist");	
						}
					}
					
					$sql = "SELECT * FROM ".$prefix."blacklist";					
					if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
          //$rs = $result->fetch_array(MYSQLI_BOTH);
					
					echo "<p style='clear:both'><b>Verbindingen</p></b><small>Blacklist - geblokkeerde gebruikers</small><table class='log'><tr><th><b>IP</b></th><th><b>Reden</b></th><th></th></tr>";
					if($result->num_rows>0){
						while($hosts = $result->fetch_array(MYSQLI_BOTH)){
							echo "<tr><td>".$hosts['ip']."</td><td>".$hosts['reason']."</td><td align='right'>";
							if($this->isEditor() || $this->isAdmin())
								echo "<a href='index.php?section=security&view=blacklist&edit_ip=".$hosts['id']."'><img src='../images/edit.png' /></a>&nbsp;&nbsp;<a href='index.php?section=security&view=blacklist&delete_ip=".$hosts['id']."'><img src='../images/cross.gif' /></a>";
							echo "</td></tr>";
						}
					}else{
							echo "<tr><td>Geen ip adressen toegevoegd</td><td></td><td></td></tr>";
					}
					echo "</table>";
					
					$result->free();
					
					//$settings['website_use_blacklist'] = $rs['website_use_blacklist'];
					if(isset($msg['ip']))echo $msg['ip'];
					echo "<form method=\"post\" enctype=\"multipart/form-data\" name=\"add_blacklist_form\" action=".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']." >
	<input type=\"hidden\" name=\"blacklist\" value=\"add_bl\" />
					<table style='float:left;clear:both;'>";
            if(isset($error['ip']))
              echo "<tr><td><span class='error'>".$error['ip']."</span></td></tr>";
					echo "<tr><td class='fields'>Ip toevoegen:</td><td>Reden:</td></tr>
					<tr><td class='fields'><input type='text' name='ip' value='";
          if(isset($_POST['ip']))
            echo $_POST['ip'];
          echo "' /></td><td><input type='text' name='reason' value='";
          if(isset($_POST['reason']))
            echo $_POST['reason'];
           echo "'/><input type=\"submit\" class=\"submit\" value=\"Toevoegen\" src='../images/plus.png' /></td></tr></table></form>";
					
					echo "<form method=\"post\" enctype=\"multipart/form-data\" name=\"blacklist_form\" action=".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']." >
	<input type=\"hidden\" name=\"blacklist\" value=\"edit_bl\" />
					<table style='margin-top:10px; float:left; clear:both;'><tr><td class='fields'>Blacklist actief:</td></tr>
					<tr><td class='fields'><select name='website_use_blacklist'>";
					if($settings['website_use_blacklist']==0){
						echo "<option value='0'>Nee</option>";
					}else{
						echo "<option value='1'>Ja</option>";
					}
					echo "<option value=' '>&nbsp;</option>
					<option value='0'>Nee</option>
					<option value='1'>Ja</option>
					</select></td><td>";
          if(isset($error['website_use_blacklist']))
            echo $error['website_use_blacklist'];
          echo "</td></tr>
					<tr><td class='fields'><input type=\"submit\" class=\"submit\" value=\"Wijzigen\" /></td></tr></table></form>";	
				}
			break;
		}
	}
	
	public function getSettings(){
		global $prefix;
			$sql = "SELECT * FROM ".$prefix."settings";
			if(!$result = $this->link->query($sql))
					$this->db_message($sql);
			$settings = $result->fetch_array(MYSQLI_BOTH);
			
			$result->free();
			
			return($settings);
	}
	
	public function setSettings(){
		global $prefix, $settings;
			
			if(isset($_POST['settings'])){
				$settings['head_title'] = $_POST['head_title'];
				$settings['max_items'] = intval($_POST['max_items']);
				$settings['website_name'] = $_POST['website_name'];
				$settings['thumb_size'] = intval($_POST['thumb_size']);
				$settings['allow_html'] = intval($_POST['allow_html']);
				$settings['website_status'] = intval($_POST['website_status']);
				$settings['htmleditor'] = intval($_POST['htmleditor']);
				$settings['img_mode'] = intval($_POST['img_mode']);
				$settings['reactions'] = intval($_POST['reactions']);
				$settings['searchfield'] = intval($_POST['searchfield']);
				$settings['poll'] = intval($_POST['poll']);
				$settings['preview_size'] = intval($_POST['preview_size']);
				$settings['rename_photo'] = intval($_POST['rename_photo']);
				$settings['delete_non_empty'] = intval($_POST['delete_non_empty']);
				$settings['max_width'] = intval($_POST['max_width']);
				$settings['max_height'] = intval($_POST['max_height']);
				$settings['img_quota'] = $_POST['img_quota'];
				$settings['allowed_img_tags'] = $_POST['allowed_img_tags'];
				$settings['max_img_size'] = intval($_POST['max_img_size']);
				$settings['max_img_height'] = intval($_POST['max_img_height']);
				$settings['max_img_width'] = intval($_POST['max_img_width']);
				$settings['image_path'] = $_POST['image_path'];
				$settings['amount_photos'] = intval($_POST['amount_photos']);
				$settings['multiple_poll'] = intval($_POST['multiple_poll']);
				$settings['day_interval'] = intval($_POST['day_interval']);
				$settings['month_interval'] = intval($_POST['month_interval']);
				$settings['year_interval'] = intval($_POST['year_interval']);
				$settings['color_hits_bar'] = htmlentities($_POST['color_hits_bar']);
				$settings['color_hits_bar_border'] = htmlentities($_POST['color_hits_bar_border']);
				
				if($this->isAdmin || $this->isEditor()){
					if($settings['head_title'] != "" && $settings['max_items'] != "" && $settings['website_name'] != "" && $settings['thumb_size']!=""){
						$sql = " UPDATE ".$prefix."settings SET
									head_title = '".$settings['head_title']."', 
									max_items = '".$settings['max_items']."', 
									website_name = '".$settings['website_name']."', 
									website_status = '".$settings['website_status']."', 
									reactions = '".$settings['reactions']."', 
									allow_html = '".$settings['allow_html']."', 
									htmleditor = '".$settings['htmleditor']."', 
									img_mode = '".$settings['img_mode']."', 
									searchfield = '".$settings['searchfield']."', 
									preview_size = '".$settings['preview_size']."', 
									rename_photo = '".$settings['rename_photo']."',
									delete_non_empty = '".$settings['delete_non_empty']."',
									max_hoogte = '".$settings['max_hoogte']."',
									allowed_img_tags = '".$settings['allowed_img_tags']."',
									img_quota = '".$settings['img_quota']."',
									max_img_size = '".$settings['max_img_size']."',
									max_img_height = '".$settings['max_img_height']."',
									max_img_width = '".$settings['max_img_width']."',
									image_path = '".$settings['image_path']."',
									multiple_poll = '".$settings['multiple_poll']."',
									max_width = '".$settings['max_width']."',
									amount_photos = '".$settings['amount_photos']."', 
									poll = '".$settings['poll']."', 
									day_interval = '".$settings['day_interval']."',
									month_interval = '".$settings['month_interval']."',
									year_interval = '".$settings['year_interval']."',
									color_hits_bar = '".$settings['color_hits_bar']."',
									color_hits_bar_border = '".$settings['color_hits_bar_border']."',
									thumb_size = '".$settings['thumb_size']."'";
						
						if(!$result = $this->link->query($sql))
				       $this->db_message($sql);
            else
							echo $this->message("NOTICE", "Instellingen zijn gewijzigd");
					}else{
						if($settings["head_title"] == "")
							$error["head_title"] = $this->message("FIELD_ERROR","Voer een titel in voor de browserbalk");
						if($settings["max_items"] == "")
							$error["max_items"] = $this->message("FIELD_ERROR","Geef het maximum items per page op.");
						if($settings["website_name"] == "")
							$error["website_name"] = $this->message("FIELD_ERROR","Voer een naam in voor de browserbalk");
						if($settings["thumb_size"] == "")
							$error["thumb_size"] = $this->message("FIELD_ERROR","Voer een resolutie in voor de thumbnails");
					}
				}
			}
			if($this->testMode())
				$disabled = " DISABLED='disabled'";
			
			require('../templates/settings.php');	
	}	
	
	function authLogin(){
			global $prefix, $ip;

			if(isset($_POST['admin_login'])){
				$data['password'] = $_POST['password'];
				$data['loginname'] = $_POST['loginname'];

        $password = hash_hmac('sha512', $data['password'], "a4af4e5fab43fab24589ad124");

				if($data['loginname'] == '' || $data['password'] == ''){
					if($data['loginname'] == '')
						$error['un'] = $this->message("FIELD_ERROR","Gebruikersnaam is niet ingevuld");
					if($data['password'] == '')
						$error['pw'] = $this->message("FIELD_ERROR","Wachtwoord is niet ingevuld");

					require('../templates/login_template.php');
				}else{
					$sql ="SELECT * FROM blog_users WHERE loginname='".mysqli_real_escape_string($this->link, $data['loginname'])."' && password='".mysqli_real_escape_string($this->link, $password)."' ";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
					if($result->num_rows==0){
						$_SESSION['admin_login']=FALSE;
						$error['auth'] = $this->message("ERROR","Ongeldige gegevens.");
						require('../templates/login_template.php');

						$this->authFail();
						return $error;
					}else{           
						$rs = $result->fetch_array(MYSQLI_BOTH);
            if($rs['expiration_date'] > date("Y-m-d")){
              $_SESSION['admin_name'] = $rs['loginname'];
              $_SESSION['admin_rights'] = $rs['group'];
              $_SESSION['admin_status'] = $rs['status'];
              $_SESSION['admin_section'] = 'babyblog';
              $_SESSION['auth_admin_login'] = true;

              echo "<p class='notify_login'>Welkom terug, je wordt nu doorgestuurd....</p>";
              echo "<meta http-equiv=\"refresh\" content=\"2;URL=index.php\" />";
              }
            else{
              $error['auth'] = $this->message("ERROR","Het account is verlopen.");
              require('../templates/login_template.php');
            }
					}
					$result->free();
				}
			}else{
				require('../templates/login_template.php');
			}
	}

	public function authFail(){
		global $ip, $prefix, $page, $url, $webhost, $server, $server_user, $method, $referer, $protocol;

			$sql = "SELECT * FROM ".$prefix."log WHERE ip = '$ip' AND message= 'BAD_LOGIN'";
      if(!$result = $this->link->query($sql))
				  $this->db_message($sql);
			$aantal = $result->num_rows;
			if($aantal <= 5){
				$post = serialize($_POST);

				try{
					$rslt = "INSERT INTO ".$prefix."log (ip, datum, page, gebruiker, url, browser, webhost, os_server, os_user, referrer, protocol, method, message, extra) VALUES('".mysqli_real_escape_string($this->link, $ip)."', now(), '$page', '', '".mysqli_real_escape_string($this->link, $url)."','','$webhost','$server','$server_user','$referer','$protocol','$method','BAD_LOGIN', '$post')";			
				}catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
			}else{
				$this->addToBlacklist($message);
			}
	}

	public function isAdmin(){
		global $settings;
		
		if($_SESSION['admin_rights']=='3')
			return true;
	}
	
	public function isEditor(){
		global $settings;
		
		if($_SESSION['admin_rights']=='2')
			return true;
	}
	
	public function testMode(){
		global $settings;
		
		if($_SESSION['admin_rights']=='1')
			return true;
	}
	
	public function addToBlacklist($message){
		global $ip, $part, $prefix;

		$sql = "SELECT * FROM ".$prefix."blacklist WHERE ip = '$ip'";
    if(!$result = $this->link->query($sql))
			 $this->db_message($sql);

		if($result->num_rows > 0){
			try{
				$rslt = "INSERT INTO ".$prefix."blacklist (ip, reason) VALUES ('".mysqli_real_escape_string($this->link, $ip)."', '".mysqli_real_escape_string($this->link, $message)."')";
        if(!$result = $this->link->query($sql))
				  $this->db_message($sql);
			}catch (Exception $e){
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}	
		}
	}

	public function countReactions($nummer){
		global $prefix;
		
		$sql = "SELECT COUNT(*) AS reactions FROM ".$prefix."reactions WHERE item = $nummer";
    if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		$rs = $result->fetch_array(MYSQLI_BOTH);

		echo $rs['reactions'];
	}
		
		public function getPosts(){
			global $prefix,$page,$settings;

			if(isset($_GET['delete_item'])){
				$id = intval($_GET['delete_item']);
				$this->deleteBlogItem($id);	
			}elseif(isset($_GET['edit_item'])){
				$id = intval($_GET['edit_item']);
				$this->editBlogItem($id);
			}elseif(isset($_GET['nieuw_item'])){
				$this->newBlogItem();
			}else{				
				$sql = "SELECT * FROM ".$prefix."posts ORDER BY date_added";					
        if(!$result = $this->link->query($sql))
				  $this->db_message($sql);
				$aantal = $result->num_rows;
				
				//page's opbouwen
				if (!isset($_GET['page'])){ 
					$pagenum = 1; 
				}else{
					$pagenum = intval($_GET['page']); 
				}
				$max = $settings['max_items'];
	
				$pages = ceil($aantal/$max);
				$pagelimit = 'limit ' .($pagenum - 1) * $max .',' .$max;		
				
				//nooit lager dan 1 of hoger dan maximum 
				if ($pagenum < 1) { 
					$pagenum  = 1; 
				}elseif ($pagenum  > $pages){ 
					$pagenum = $pages; 
				} 

				$sql = "SELECT * FROM ".$prefix."posts ORDER BY date_added DESC $pagelimit";
        if(!$result = $this->link->query($sql))
				  $this->db_message($sql);
        
				if($this->isEditor() || $this->isAdmin())
					echo "<div class='actions'><div class='add'><p><a href='index.php?nieuw_item'><img src='../images/plus.png' alt=''/> Item toevoegen</a></p></div></div>";
				while($blog_item = $result->fetch_array(MYSQLI_BOTH)){
					$size = $settings['thumb_size'];
					require('../templates/blogitems.php');	
				}
				echo "<div class='bottom_nav'>";
				if(($aantal > $max)&&($pagenum < $pages)){
					$page_next = $pagenum + 1;
					echo "<div class='older'><a href='index.php?section=posts";
					echo "&amp;page=$page_next'>&laquo; Oudere berichten</a></div>";
				}
				if($pagenum > 1){
					$page_prev = $pagenum - 1 ;
					echo "<div class='newer'><a href='index.php?section=posts&amp;page=$page_prev'>Nieuwere berichten &raquo;</a></div>";
				}
				echo "</div>";
			}
		}
		
		public function newBlogItem(){
			global $prefix,$settings;
			if(isset($_POST['new_blogitem'])){
				$blogitem = array();
				$blogitem['title'] = trim($_POST['title']);
				$blogitem['description'] = trim($_POST['description']);
				$blogitem['status'] = $_POST['status'];
				$category = trim($_POST['category']);
				$create_time = date('Y-m-d H:i:s');
				if($_FILES['images']['name']!='')
					if(isset($_FILES['image']))$image = $_FILES['image'];
					
					if(($blogitem['title'] != "") && ($blogitem['description'] != "") && ($category != "") && ($blogitem['status'] != "")){
            
            $image='';
            $image2='';
            $image3='';
            $image4='';
            $image5='';
            $image6='';
            $image7='';
            $image8='';
            $image9='';
						if(isset($_FILES['images'])){
              $images = $_FILES['images'];
              $i=1;		

              foreach ($_FILES['images']['name'] as $key => $value){		
                  if(!empty($value)){ 
                    $file = $value;
                    $filename = $images['name'];
                    $filetype = $images['type'];
                    $filesize = $_FILES['images']['size'][$key];
                    $temp = $_FILES['images']['tmp_name'][$key];

                    //$ext = explode(".",$filename);
                    //$ext = $ext[count($ext)-1];
                    $path = "../".$settings['image_path'].strtolower(date("F"))."_".date("Y")."/";
                    //die($path);
                    if(!is_dir($path)){
                      mkdir($path, 0777, true);
                    }


                    list($width, $height) = getimagesize($temp);
                    if($i==1)
                      $image = $path.$file;
                    if($i==2)
                      $image2 = $path.$file;
                    if($i==3)
                      $image3 = $path.$file;
                    if($i==4)
                      $image4 = $path.$file;
                    if($i==5)
                      $image5 = $path.$file;
                    if($i==6)
                      $image6 = $path.$file;
                    if($i==7)
                      $image7 = $path.$file;
                    if($i==8)
                      $image8 = $path.$file;
                    if($i==9)
                      $image9 = $path.$file;
                    $save = $path.$file; 

                    if($width < 9500 && $height < 9500 && $filesize < 9500000){
                      if (!copy($temp, $save))
                      {
                        return $file."----".$images;

                        {
                        unlink($file);
                        if(!is_writable($path))
                            return "{$file} Geen rechten:{$image}";	
                        else
                          return "{$file} Wel rechten:{$image}";						
                          return "";
                        }
                      }
                      unlink($temp);
                    }else{
                        echo message("ERROR", "Het plaatje mag maximaal 2500x2500 pixels en maximaal 9,5mb groot zijn!");
                    }
                  
                }
                $i++;
              }
						}
						
						$sql = " INSERT INTO blog_posts (title, description, image1, image2,image3,image4,image5,image6,image7,image8,image9,date_added, category, status, uploader) 
						VALUES ('".$blogitem['title']."', '".mysqli_real_escape_string($this->link, $blogitem['description'])."', '$image', '$image2', '$image3', '$image4','$image5','$image6','$image7','$image8','$image9', '$create_time', '$category', '".$blogitem['status']."', '".$_SESSION['admin_name']."')";
			
						if(!$result = $this->link->query($sql))
				      $this->db_message($sql);
						else{
							unset($blogitem);
							echo $this->message("NOTICE", "Het verhaal is succesvol toegevoegd.");
						}
						
					}else{
							if($blogitem['title'] == "")
								$error['title'] = $this->message("FIELD_ERROR", "De titel is niet ingevuld.");
							if($blogitem['description'] == "")
								$error['description'] = $this->message("FIELD_ERROR","Geef de inhoud op.");
							if($blogitem['status'] == "")
								$error['status'] = $this->message("FIELD_ERROR","Geef de status op.");
							if($category == "")
								$error['category'] = $this->message("FIELD_ERROR","Geef de categorie op.</span>");
					}
				}
				
				echo "<div>";
						include('../templates/newblogitem.php');
				echo "</div>";
		}
		
		public function editBlogItem($id){
			global $prefix, $settings;
			if(isset($_POST['edit_blogitem'])){
				$blogitem = array();
				$blogitem['title'] = trim($_POST['title']);
				$blogitem['category'] = trim($_POST['category']);

				$new_cat = trim($_POST['new_cat']);
				if($new_cat!=""){
					$this->addNewCategory($new_cat);
					$blogitem['category'] = $new_cat;
				}
				$blogitem['description'] = $_POST['description'];
				$blogitem['status'] = $_POST['status'];
				$create_time = date('Y-m-d H:m:s');
				if(($blogitem['title'] != "") && ($blogitem['description']) != "" && ($blogitem['category'] != "") && ($blogitem['status'] != "")){
						$images = $_FILES['images'];
						$i=1;
						if($images != ""){
                foreach ($_FILES['images']['name'] as $key => $value){								
								if(!empty($value)){ 
									$file = $value;
									$filename = $images['name'];
									$filetype = $images['type'];
									$filesize = $_FILES['images']['size'][$key];
									$temp = $_FILES['images']['tmp_name'][$key];
		
								//$ext = explode(".",$filename);
								//$ext = $ext[count($ext)-1];
								$path = "../".$settings['image_path'].strtolower(date("F"))."_".date("Y")."/";
								if(!is_dir($path)){
									mkdir($path, 0777, true);
								}
		
								list($width, $height) = getimagesize($temp);
									if($i==1)
									{
										$image = $path.$file;
										$image_columns = "image1 = '$image'";
									}
									if($i==2)
									{
										$image2 = $path.$file;
										$image_columns .= ", image2 = '$image2'";
									}
									if($i==3)
									{
										$image3 = $path.$file;
										$image_columns .= ", image3 = '$image3'";
									}
									if($i==4)
									{
										$image4 = $path.$file;
										$image_columns .= ", image4 = '$image4'";
									}
									if($i==5)
									{
										$image5 = $path.$file;
										$image_columns .= ", image5 = '$image5'";
									}
									if($i==6)
									{
										$image6 = $path.$file;
										$image_columns .= ", image6 = '$image6'";
									}
									if($i==7)
									{
										$image7 = $path.$file;
										$image_columns .= ", image7 = '$image7'";
									}
									if($i==8)
									{
										$image8 = $path.$file;
										$image_columns .= ", image8 = '$image8'";
									}
									if($i==9)
									{
										$image9 = $path.$file;
										$image_columns .= ", image9 = '$image9'";
									}
									
									$save = $path.$file; 
									//$thumb = $path."thumb_".$filename.".".$ext;
			
									if($width < 9500 && $height < 9500 && $filesize < 9500000){
										if (!copy($temp, $save))
										{
											return $file."----".$image;
										
											{
											unlink($file);
											if(!is_writable($path))
													return "{$file} Geen rechten:{$image}";	
											else
												return "{$file} Wel rechten:{$image}";						
												return "";
											}
										}
										
									}else{
											echo $this->message("ERROR","Het plaatje mag maximaal 2500x2500 pixels en maximaal 9.5mb groot zijn!");
									}
								}
								$i++;
								
								if(isset($image_columns) && $image_columns != ''){
									$sql ="UPDATE blog_posts SET $image_columns WHERE id = '$id'";
                  if(!$result = $this->link->query($sql))
				            $this->db_message($sql);
                }
							}
						}
						
						$sql = " UPDATE blog_posts SET
							title = '".mysqli_real_escape_string($this->link, $blogitem['title'])."', 
							description = '".mysqli_real_escape_string($this->link, $blogitem['description'])."',
							category = '".$blogitem['category']."',
							status = '".$blogitem['status']."'
						 WHERE id = '$id'";
						
						if(!$result = $this->link->query($sql))
				      $this->db_message($sql);
						else{
							unset($temp);
							echo $this->message("NOTICE","Het verhaal is succesvol aangepast.");
						}
						
				}else{
						if($blogitem['title'] == "")
							$error['title'] = $this->message("FIELD_ERROR","De titel is niet ingevuld.");
						if($blogitem['description'] == "")
							$error['description'] = $this->message("FIELD_ERROR","Geef de description op.");
						if($blogitem['status'] == "")
							$error['status'] = $this->message("FIELD_ERROR","Geef de status op.");
						if($blogitem['category'] == "")
							$error['category'] = $this->message("FIELD_ERROR","Geef de categorie op.</span>");
				}
			}
			$size = $settings['thumb_size'];
			
			if(isset($_GET['edit_image'])){
				$i = intval($_GET['edit_image']);
				$size=$size*2;
				$result ="SELECT image$i, titel, id, MONTHNAME(date_added) as monthname, YEAR(date_added) as year FROM blog_posts WHERE id = '$id'";
        if(!$result = $this->link->query($sql))
				  $this->db_message($sql);
				
				if($result->num_rows>0){
					$image = $result->fetch_array(MYSQLI_BOTH);
					
					if(isset($_POST['id'])){
						$column = intval($_POST['id']);
						$new_image = $_FILES['image'];

						if($new_image["tmp_name"]!=""){
							$path = $settings['image_path'].strtolower($image["monthname"])."_".$image["year"]."/";
							
							if(!is_dir($path))
								mkdir($path, 0777, true);
							$save = $path.$new_image['name'];

							$this->upload_image($new_image,$save,"posts","image$i",FALSE,$i);	
							
							if($_SESSION['valid_image'] = TRUE){
								//eerst oude image verwijderen
								unlink($image["afbeelding$i"]);
								
								echo $this->message("NOTICE", "Afbeelding is succesvol gewijzigd.");	
								$image["image$i"] = $save;
								unset($_SESSION['valid_image']);
							}
						}else{
							$msg["image"] = $this->message("ERROR", "Er is geen afbeelding toegevoegd.");	
						}
					}
					
					require('../templates/editpostimage.php');
				}else{
					echo $this->message("ERROR","Deze afbeelding komt niet voor in de database. Probeer het opnieuw.");	
				}
			}else{
				if(isset($_GET['delete_image'])){
					$post = $_GET['edit_item'];
					$image = "image".$_GET['delete_image'];
					
					$sql ="SELECT $image FROM blog_posts WHERE id = '$post'";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
					$rs = $result->fetch_array(MYSQLI_BOTH);
					$file = $rs["$image"];

					if(unlink($file)){
						$sql = "UPDATE blog_posts SET $image = '' WHERE id = '$post'";
            if(!$result = $this->link->query($sql))
				      $this->db_message($sql);								
						else
							$msg['blog_item'] = $this->message("NOTICE", "$image is verwijderd.");
					}else{
						$msg['blog_item'] = $this->message("ERROR", "Fout bij verwijderen van bestand, bestand is niet gevonden.");	
					}
				}

				$sql = "SELECT * FROM blog_posts WHERE id = '$id'";
        if(!$result = $this->link->query($sql))
				      $this->db_message($sql);	
				$blogitem = $result->fetch_array(MYSQLI_BOTH);
				
				if($result->num_rows>0){
					echo "<div>";
						include('../templates/editblogitem.php');
					echo "</div>";
				}else{
					echo $this->message("ERROR","Item is niet gevonden. Probeer het opnieuw.");	
				}
			}
		}
		
		public function deleteBlogItem($id){
			$sql = "DELETE FROM blog_posts WHERE id = '".$id."'";
      if(!$result = $this->link->query($sql))
				$this->db_message($sql);
      else{
						echo $this->message("NOTICE","Het bericht is succesvol verwijderd, een moment geduld...");
						echo "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?section=posts\" />";
				}									
		}
		
		public function addNewCategory($new_cat){
			global $prefix;
			
			$sql="INSERT INTO ".$prefix."categories(name) VALUES('$new_cat')";
      if(!$result = $this->link->query($sql))
				$this->db_message($sql);
		}
		
		public function getCategoryItems(){
			global $prefix;
			
			$sql = "SELECT * FROM ".$prefix."categories ORDER BY name ASC";	
      
      if(!$result = $this->link->query($sql))
				$this->db_message($sql);
			
			if(isset($category) && $category != "")
				echo "<option value='".ucwords($category)."'>".$category."</option>";
			
			echo "<option value=''>&nbsp;</option>";
			while($cat_item = $result->fetch_array(MYSQLI_BOTH)){
				echo "<option value='".ucwords($cat_item['name'])."'>".$cat_item['name']."</option>";	
			}	
		}
		
		public function ctDetails($details, $pre, $title, $cnt_table, $short){
			global $prefix;
			
			if($short=="tag"){
				$sql = "SELECT * FROM ".$prefix."tags_items ti
 					INNER JOIN ".$prefix."tags t ON ti.tag_id = t.tag_id
					INNER JOIN ".$prefix."posts p ON ti.item_id = p.id
 					WHERE t.tag_id = '$details'
					";
				$sql = "SELECT name FROM ".$prefix.$pre." WHERE tag_id = '$details'";
				if(!$result = $this->link->query($sql))
				  $this->db_message($sql);
				$rs = $result->fetch_array(MYSQLI_BOTH);
				$details = $rs['name'];
			}else{
				$sql = "SELECT * FROM ".$prefix.$cnt_table." WHERE $short = '$details'";
        if(!$result = $this->link->query($sql))
				  $this->db_message($sql);
			}
			
			echo "<div class='form_titel'>Details '$details'</div><p></p>
			<small>Hieronder vind je alle page's die gekoppeld zijn aan de $short: <b><u>$details</u></b>.</small>
			<table class='table_data'>";
			if($result->num_rows>0){
				while($ct_item = $result->fetch_array(MYSQLI_BOTH)){
					echo "<tr><td>".ucwords($ct_item['title'])."</td><td align='right'>";
					if($this->isEditor() || $this->isAdmin())
						echo "<a href='index.php?section=posts&amp;edit_item=".$ct_item['id']."'><img src='../images/edit.png' alt='' /></a>";
					echo "</td></tr>";	
				}
			}else{
				echo $this->message("EMPTY","Er zijn geen items gekoppeld aan <b><u>$details</u></b>.");	
			}	
			echo "</table>";
		}
		
		public function albumOverview(){
			global $prefix,$settings, $classes, $page;
			
			if(isset($_GET['album_id'])){
				$album_id = floatval($_GET['album_id']);
        if(isset($_GET['mode']))
         {
          if(($_GET['mode']=='upload_images')){
            $this->uploadAlbumImages($album_id);
          }elseif($_GET['mode']=='delete_image'){
            $image_key = $_GET['view'];
            $this->deleteImage($image_key, $album_id);
          }elseif($_GET['mode']=='delete_album'){
            $this->deleteAlbum($album_id);
          }
				}else{
					if(isset($_GET['view'])){
						$auth_key = $_GET['view'];
						$this->imageDetails($image, $auth_key);
					}else{
						$this->fetchAlbumImages($album_id);
					}
				}
			}elseif(isset($_GET['add_album'])){
				$this->addAlbum();
			}else{
				$sql = "SELECT * FROM ".$prefix."album ORDER BY id";
        if(!$result = $this->link->query($sql))
				  $this->db_message($sql);
        
				if($result->num_rows>0){
					$size = $settings['album_thumb_size'];
				
					require('../templates/album_overview.php');
				}else{
					$msg['empty'] = $this->message("EMPTY","Geen albums gevonden. Klik rechtsboven op 'Album toevoegen' om een nieuw album aan te maken.");	
				}
				
			}
		}
		
		public function fetchAlbumImages($album_id){
			global $prefix, $settings, $classes, $valid_image, $page;

			$size = $settings['preview_size'];
			
			$sql = "SELECT * FROM ".$prefix."album WHERE id = '$album_id' ";
      if(!$result = $this->link->query($sql))
				$this->db_message($sql);
			
				if(isset($_GET['mode']) && $_GET['mode']=='edit_album'){
					$album_details = $result->fetch_array(MYSQLI_BOTH);
					if($result->num_rows>0){
							if($_POST['edit_album']!=""){
								$old_path = $settings['image_path']."albums/{$album_details['name']}/";
								$album_details['description'] = $_POST['description'];
								$album_details['status'] = $_POST['status'];

								if($album_details['name'] !=  $_POST['name']){
									$album_details['name'] = $_POST['name'];
									$sql = "SELECT naam FROM ".$prefix."album WHERE naam='{$album_details['name']}' ";
                  if(!$result = $this->link->query($sql))
				            $this->db_message($sql);
									
									if($result->num_rows < 1){
										$new_path = $settings['image_path']."albums/{$album_details['name']}/";
										$old_thumb = $old_path . $album_details['thumb'];
										$new_thumb = $new_path . $album_details['thumb'];
									}else{
										$error['name']=$this->message("ERROR","De naam bestaat al, verzin een andere.");	
									}
								}
								$thumb = $_FILES['afbeelding'];
			
								if($thumb['tmp_name']!=""){
			
									$path = $settings['image_path']."albums/".$album_details['name']."/thumb/";
									if(!is_dir($path))
										mkdir($path, 0777, true);
									$save = $path.$thumb['name'];

									$this->upload_image($thumb, $save, "album", "thumb", NULL, $album_id);	

									if($_SESSION['valid_image'] == FALSE){
									 	$error['image']=$this->message("ERROR","Het plaatje mag maximaal 350 pixels breed + 350 pixels hoog zijn en maximaal 1,5mb groot zijn!");
										$bad_image = TRUE;
									}
								}
								
								//naam is verplicht
								if($album_details['name'] != "" && $bad_image!=TRUE){
									$sql = "UPDATE ".$prefix."album SET ";
									$sql .= "name = '{$album_details['name']}', ";
									$sql .= "status = '{$album_details['status']}', ";
									$sql .= "description = '{$album_details['description']}', ";
									$sql .= $name . $image;
									$sql .= " edit_date = NOW() ";
									$sql .= "WHERE id = '$album_id'";

									if(!$result = $this->link->query($sql))
				            $this->db_message($sql);
									
									$msg['notice']=$this->message("NOTICE","Het album is aangepast.");
									$album_details['thumb'] = $save;
									
									if($new_path!=""){
										$new_name = $album_details['name'];
										$this->moveAlbumImagesNewDir($new_name, $album_id);
									}
									unset($_SESSION['valid_image']);
								}else{
									if($album_details['name'] == "")
										$error['name']=$this->message("FIELD_ERROR","Geef een naam op.");
								}
							}
						require('../templates/editalbum.php');
						
						}else{
							echo $this->message("ERROR","Album bestaat niet. Probeer het opnieuw.");				
						}	

			}else{
				if($result->num_rows>0){
					$rs = $result->fetch_array(MYSQLI_BOTH);
					
					//page's opbouwen
					if (!isset($_GET['page'])){ 
						$pagenum = 1; 
					}else{
						$pagenum = intval($_GET['page']); 
					}
					$sql = "SELECT * FROM ".$prefix."albumphotos WHERE album_id = '$album_id' AND STATUS = '1'";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
					
					$aantal = $result->num_rows;
            
					$max = $settings['amount_photos'];
		
					$pages = ceil($aantal/$max);
					$pagelimit = 'limit ' .($pagenum - 1) * $max .',' .$max;		
					
					//nooit lager dan 1 of hoger dan maximum 
					if ($pagenum < 1) { 
						$pagenum  = 1; 
					}elseif ($pagenum  > $pages){ 
						$pagenum = $pages; 
					} 
	
					$album_name = $rs['name'];
					
					$sql="SELECT * FROM ".$prefix."albumphotos WHERE album_id = '$album_id' AND status = '1' ORDER BY date_added DESC $pagelimit";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
          
					if($result->num_rows < 1){
						$msg['empty'] = "<p>Geen afbeeldingen gevonden in dit album.</p>";	
					}
					require('../templates/album_images.php');
				}else{
					echo "<p class='error'>Ongeldig album. </p>";
				}
			}
		}
		
		public function addAlbum(){
			global $prefix;
			
			if(isset($_POST['album_form'])){

				$album_item['name'] = trim(mysqli_real_escape_string($this->link, $_POST['name']));
				$album_item['status'] = $_POST['status'];
        $album_item['description'] = trim(mysqli_real_escape_string($this->link, $_POST['description']));
				$image = $_FILES['thumb'];
				
				if($album_item['name'] != "" && $album_item['status'] != ""){
					$sql = "SELECT name FROM ".$prefix."album WHERE name = '".$album_item['name']."' ";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
					
					if($result->num_rows < 1){
						
						if($image['name'] != ""){
							$filename = $image['name'];
							$filetype = $image['type'];
							$filesize = $image['size'];
	
							$ext = explode(".",$filename);
							$ext = $ext[count($ext)-1];
							$path = "upload/";
							//$datetime = NOW();
	
							$hash = substr(md5(date("D-m-Y:h-s")),0,10);
	
							list($width, $height) = getimagesize($image['tmp_name']);
	
							$image = $path.$album_item['name']."_".$hash.".".$ext;
	
							if(($width < '800') || ($height < 600)){
								if (!copy($image['tmp_name'], $image))
								{
									return $file."----".$image;
								
									{
									unlink($image);
									if(!is_writable($path))
											return "{$image} Geen rechten:{$image}";	
									else
										return "{$file} Wel rechten:{$image}";						
										return "";
									}
								}
								unlink($image['tmp_name']);
								//$image = "upload/".$film['name'].".".$ext;					
	//die($sql);
							}else{
								echo $this->message("ERROR","Het thumb plaatje mag maximaal 800x600 pixels groot zijn!");
							}
						}		
						
						$sql = "INSERT INTO ".$prefix."album (name, description, thumb, status) VALUES ('".$album_item['name']."', '".$album_item['description']."','".$image."','".$album_item['status']."') ";
            if(!$result = $this->link->query($sql))
				      $this->db_message($sql);
						
						$msg['notice']= $this->message("NOTICE","Het album is succesvol toegevoegd.");
						unset($album_item);
					}else{
						$msg['album_exists'] = $this->message("ERROR",'Album naam bestaat al. Probeer een andere naam.');	
					}
				}else{
					if($album_item['name']=="")
						$error['name']= $this->message("FIELD_ERROR","Geef een naam op.");
					if($album_item['status']=="")
						$error['status']=$this->message("FIELD_ERROR", "Kies een status voor het album.");
					}
			}	
			require('../templates/newalbumitem.php');
		}
		
		public function moveAlbumImagesNewDir($album_id, $new){
			// to do: move files to new folder when there is a rename
		}
		
		public function uploadAlbumImages($album_id){
			global $prefix,$settings;	

			$sql = "SELECT name FROM ".$prefix."album WHERE id = '$album_id' ";	
      //die($sql);
      if(!$result = $this->link->query($sql))
				 $this->db_message($sql);
			$rs = $result->fetch_array(MYSQLI_BOTH);
			$album_name = $rs['name'];

			if(isset($_POST['upload_images'])){
          $msg['image']="";
          foreach ($_FILES['images']['name'] as $key => $value){
								if(!empty($value)){ 
									$file = $value;
									$filename = $_FILES['images']['name'][$key];
									$filetype = $_FILES['images']['type'][$key];
									$filesize = $_FILES['images']['size'][$key];
									$temp = $_FILES['images']['tmp_name'][$key];
			
									$ext = explode(".",$filename);
									$ext = $ext[count($ext)-1];
									$path = "../".$settings['image_path']."albums/$album_name/";
									if(!is_dir($path)){
										mkdir($path, 0777, true);
									}
	
	
									list($width, $height) = getimagesize($temp);
									$image = $path.$file;

									$save = $path.$file; 
									//$thumb = $path."thumb_".$filename.".".$ext;
									$hash = md5(date("d-m-Y:h-i-s").$temp);
									
									$sql = "SELECT * FROM ".$prefix."albumphotos WHERE name = '$file'";
                  if(!$result = $this->link->query($sql))
				            $this->db_message($sql);
									if($result->num_rows < 1){
										if($width < 9500 && $height < 9500 && $filesize < 9500000){
											if (!copy($temp, $save))
											{
												return $file."----".$image;
											
												{
												unlink($file);
												if(!is_writable($path))
														return "{$file} Geen rechten:{$image}";	
												else
													return "{$file} Wel rechten:{$image}";						
													return "";
												}
											}
											unlink($temp);
											
											$sql = "INSERT INTO ".$prefix."albumphotos (name, image, width, height, type, filesize, uploader, date_added, album_id, status, auth_key)VALUES('$filename', '$save', '$width', '$height', '$filetype', '$filesize', '{$_SESSION['admin_name']}', NOW(), '$album_id', '1', '$hash')";
                      if(!$result = $this->link->query($sql))
				                $this->db_message($sql);
											$msg['image'] .= $this->message("NOTICE","Image $filename is succesvol toegevoegd.<br />");
										}else{
											$msg['image'] .= $this->message("ERROR","Het uploaden van $filename is mislukt. Het plaatje mag maximaal 2500x2500 pixels en maximaal 1mb groot zijn!");
										}
									}else{
										echo $this->message("ERROR","De bestandsnaam bestaat al. Verander de naam.");
									}
								}else{
									$msg['image'] .= $this->message("ERROR", "Uploaden mislukt. Er is geen afbeelding toegevoegd.");	
								}
					}
			}
			require('../templates/uploadimages.php');
		}
		
		public function imageDetails($image_id, $auth_key){
			global $prefix, $settings, $classes, $msg, $page;	
			
				$sql ="SELECT * FROM ".$prefix."albumphotos WHERE auth_key = '$auth_key' AND status = '1' ";		
        if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
				if($result->num_rows>0){
					$image_details = $result->fetch_array(MYSQLI_BOTH);
					
					$sql = "SELECT * FROM ".$prefix."album WHERE id = '{$image_details['album_id']}' ";				
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
					$album = $result->fetch_array(MYSQLI_BOTH);
					
					if(isset($_GET['mode']) && $_GET['mode']=='edit_image'){
						if($_POST['edit_image']!=""){
							$image_details['description'] = trim(mysqli_real_escape_string($this->link,$_POST['description']));
							
							//check duplicates
							if($image_details['name'] !=  $_POST['name']){
								$image_details['name'] = $_POST['name'];

								$sql = "SELECT name FROM ".$prefix."albumphotos WHERE name='{$image_details['name']}' ";							
                if(!$result = $this->link->query($sql))
				          $this->db_message($sql);
								if($result->num_rows < 1){
									$new_path = $settings['image_path']."albums/{$album['name']}/{$image_details['name']}";
									if(rename("{$image_details['image']}" ,$new_path)==TRUE){
										$image = "image = '$new_path',";
										$name = "name = '{$image_details['name']}',";
									}else{

									}
								}else{
									$error['name']=$this->message("ERROR","De naam bestaat al, verzin een andere.");	
								}
							}
							$new_image = $_FILES['image'];
							
							if($new_image['tmp_name']!="")							
								$this->upload_image($new_image, $save, "albumphotos", "image", NULL, "");	
							
							//name is always mandatory
							
							if($image_details['name'] != ""){
								$sql = "UPDATE ".$prefix."albumphotos SET ";
								$sql .= "description = '{$image_details['description']}', ";
								$sql .= $name . $image;
								$sql .= " edit_date = NOW() ";
								$sql .= "WHERE auth_key = '{$_GET['view']}' ";
								//die($sql);
								if(!$result = $this->link->query($sql))
				          $this->db_message($sql);
								
								$msg['notice'] = $this->message("NOTICE","De afbeelding is aangepast.");
								
							}else{
								if($image_details['name'] == "")
									$error['name']=$this->message("FIELD_ERROR","Geef een naam op.");
							}							
						}						
						require('../templates/editimage.php');	
					}else{				
						require('../templates/image_details.php');
					}
				}else{
					echo $this->message("ERROR","Ongeldige image. Probeer het opnieuw.");	
				}
				$result->free();
		}
		
	public function countAlbumImages($album_id){
		global $prefix, $settings;
		
		$sql = "SELECT * FROM ".$prefix."albumphotos WHERE album_id = '$album_id' AND STATUS = '1'";
    if(!$result = $this->link->query($sql))
				 $this->db_message($sql);
		$amount = $result->num_rows;
		
		return $amount;
	}

	public function imageViewed($viewed){
		global $settings, $prefix;
		
		$sql = "SELECT viewed FROM ".$prefix."albumphotos WHERE auth_key = '$viewed' AND status = '1' ";
    if(!$result = $this->link->query($sql))
				 $this->db_message($sql);
		$views = $result->fetch_array(MYSQLI_BOTH);

		$update_views = $views['viewed'] + 1;

		if($update_views > 0)
			$sql = "UPDATE ".$prefix."albumphotos SET viewed = '$update_views' WHERE auth_key = '$viewed'";
      if(!$result = $this->link->query($sql))
				 $this->db_message($sql);

		return $update_views;
	}
		
	public function deleteImage($image_key,$album_id){
		global $prefix, $settings;
			
			$sql = "SELECT * FROM ".$prefix."albumphotos WHERE auth_key = '$image_key' ";
      if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
			if($result->num_rows>0){
				$image = $result->fetch_array(MYSQLI_BOTH);
				
				//verwijder het fysieke plaatje
				if(unlink($image['image'])){
					$sql = "DELETE FROM ".$prefix."albumphotos WHERE auth_key = '$image_key'";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
					
					echo $this->message("NOTICE","De foto is verwijderd. Een moment geduld...");
					echo "<meta http-equiv=\"refresh\" content=\"1; url={$_SERVER['PHP_SELF']}?section=photoalbum&amp;album_id=$album_id\">";
				}
			}else{
				echo $this->message("ERROR","Je probeert een niet bestaande afbeelding te verwijderen.");	
			}			
			$result->free();
		}
		
		public function deleteAlbum($album_id){
			global $prefix, $settings;
			
			$sql = "SELECT * FROM ".$prefix."albumphotos WHERE album_id = '$album_id' ";
      if(!$result = $this->link->query($sql))
				  $this->db_message($sql);
			
			if($result->num_rows < 1 || $settings['delete_non_empty']==1){
				$images = $result->fetch_array(MYSQLI_BOTH);;
				
				//indien het is toegestaan een album te verwijderen met plaatjes erin
				if($settings['delete_non_empty']==1){
					//verwijder plaatjes
					foreach($images as $image){
						unlink($image['image']);
					}
					
					$sql = "DELETE FROM ".$prefix."albumphotos WHERE album_id = '$abum_id'";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
				}
				
				//verwijder album
				$sql = "DELETE FROM ".$prefix."album WHERE id = '$album_id'";					
        if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
				
				echo $this->message("NOTICE","Het album is verwijderd, een moment geduld...");
				echo "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?section=photoalbum\" />";
			}else{
				echo $this->message("ERROR","Het is niet toegestaan een album te verwijderen met fotos erin. Controleer de instellingen.");	
			}
			
			$result->free();
		}
	
	public function countCtUsed($name, $sum_cts, $short, $cnt_table, $id){
		global $prefix, $ip, $settings;
    
    //an overview of the used categories and tags. You can see which pages are coupled to it
    
		if($short=="category")
			$sql = "SELECT $short FROM ".$prefix.$cnt_table." WHERE $short = '$name'";
		else
			$sql = "SELECT * FROM ".$prefix."tags_items WHERE tag_id = '$id'";
 //   die($sql);
		if(!$result = $this->link->query($sql))
			 $this->db_message($sql);
		
		$amount = $result->num_rows;
		
		$max_width = "300";
		$total = round(($amount / $sum_cts) * 100) ."%";
		
		$color = array('#d58e8e', '#a8abd9', '#a8d9be', '#a8d9d4', '#c7d9a8', '#d9c6a8', '#e08a01',
				'#5d4f4c', '#0d5bad', '#ff1919', '#7c8e08', '#54d761', '#191eff', '#b32299', '#ff722c');
		
		echo "<div class='line_holder'><div style='width:$total; float:left; margin:6px 6px 0px 0px; height: 5px; background-color:".$color[rand(0,14)].";' ></div> <small style='float:left;'>".$amount."x ($total)</small></div>";
	}
		
	public function upload_image($image, $save, $table, $column, $empty, $id){
		global $prefix, $settings;

		$filename = $image['name'];
		$filetype = $image['type'];
		$filesize = $image['size'];
		$temp = $image['tmp_name'];
		
		$ext = explode(".",$filename);

		$allowed_ext = array('jpeg', 'jpg', 'bmp', 'png', 'gif');
		
		list($width, $height) = getimagesize($temp);
		
		if($table=="album"){
			$column = "thumb";
			$max_height = 350;
			$max_width = 350;
			$max_file_size = 100000;
		}else{
			$max_height = 3500;
			$max_width = 3500;
			$max_file_size = 2000000;
		}
		
		if($width < $max_width && $height < $max_height && $filesize < $max_file_size){
			if (!copy($temp, $save))
			{
				return $file."----".$images;
			
				{
				unlink($file);
				if(!is_writable($path))
						return "{$file} Geen rechten:{$image}";	
				else
					return "{$file} Wel rechten:{$image}";						
					return "";
				}
			}
			unlink($temp);
			$blog_id = intval($_GET['edit_item']);

			if($empty)
				$sql = "INSERT INTO ".$prefix.$table."($column) VALUES('$save')";
			else
				$sql = "UPDATE ".$prefix.$table." SET  $column = '$save' WHERE id = '$id'";

			if(!$result = $this->link->query($sql))
				 $this->db_message($sql);
			$_SESSION['valid_image'] = TRUE;
		}else{
			 //$msg['image'] = $this->message("ERROR","Het plaatje mag maximaal $max_width pixels breed + $max_height hoog zijn en maximaal 1,5mb groot zijn!");
			 $_SESSION['valid_image'] = FALSE;
		}
	}
	public function getStats(){
		global $ip, $prefix, $settings;
		
    // To do, needs rebuild
    
    $interval="";
    $exclude_ip="";
    
		if(isset($_GET['overview']))$interval = $_GET['overview'];
		// dont include own ip
		// if($settings['exclude_host']==1){
		//	$exlude_ip = "AND ip NOT IN ('78.27.63.xx', '83.163.18.xx') ";	
		//}
		
		/**** Page hits ****/
		switch($interval){
			default:
			case 'day':
				//voor query
				$substring = "substr(date,1,10)";
				$timeline = $settings['day_interval'];
				$interval = "DAY";
				
				$day = "<div class='option'>Dag</div>";
				$month = "<div class='option'><a href='".htmlentities($_SERVER['PHP_SELF'])."?section=stats&amp;overview=month'>Maand</a></div>";
				$year = "<div class='option'><a href='".htmlentities($_SERVER['PHP_SELF'])."?section=stats&amp;overview=year'>Jaar</a></div>";
			break;
			case 'month':
				//voor query
				$substring = "month";
				$timeline = $settings['month_interval'];
				$interval = "MONTH";
				
				$day = "<div class='option'><a href='".htmlentities($_SERVER['PHP_SELF'])."?section=stats&amp;overview=day'>Dag</a></div>";
				$month = "<div class='option'>Maand</div>";
				$year = "<div class='option'><a href='".htmlentities($_SERVER['PHP_SELF'])."?section=stats&amp;overview=year'>Jaar</a></div>";
			break;
			case 'year':
				//voor query
				$substring = "year";
				$timeline = $settings['year_interval'];
				$interval = "YEAR";
				
				$day = "<div class='option'><a href='".htmlentities($_SERVER['PHP_SELF'])."?section=stats&amp;overview=day'>Dag</a></div>";
				$month = "<div class='option'><a href='".htmlentities($_SERVER['PHP_SELF'])."?section=stats&amp;overview=month'>Maand</a></div>";
				$year = "<div class='option'>Jaar</div>";
			break;
		}
		
		$sql_max = "SELECT 
					COUNT(date) AS max_hits, 
					MONTH(date) as month,
					YEAR(date) as year
				FROM 
					".$prefix."log 
				WHERE 
					date > (DATE_SUB(curdate(), 
					INTERVAL $timeline $interval)) 
					AND page = 'index' 
					$exclude_ip
				GROUP BY 
					$substring 
				ORDER BY 
					max_hits
				DESC LIMIT 1" ;
		if(!$result = $this->link->query($sql_max))
				 $this->db_message($sql);
		$max = $result->fetch_array(MYSQLI_BOTH);
		
    $most_height=0;
		//$most_height = $max['max_hits'];
		
		//dutch
		$sql = 'SET lc_time_names = "nl_NL"';
	  if(!$result = $this->link->query($sql))
				    $this->db_message($sql);

		$sql = 'SELECT @@lc_time_names';
		if(!$result = $this->link->query($sql))
				$this->db_message($sql);;

		$sql = "SELECT 
					COUNT(date) AS hit, 
					DAYNAME(date) as dayname,
					DAY(date) as day,
					MONTHNAME(date) as monthname,
					MONTH(date) as month,
					YEAR(date) as year
				FROM 
					".$prefix."log 
				WHERE 
					date > (DATE_SUB(curdate(), 
					INTERVAL $timeline $interval)) 
					AND page = 'index' 
					$exclude_ip
				GROUP BY 
					$substring 
				ORDER BY 
					$substring 
				ASC" ;
		if(!$result = $this->link->query($sql))
				$this->db_message($sql);

		$max_height = "300";
		
		echo "<div class='form_title'>Statistieken</div><div class='interval'>$day $month $year</div>";
		echo "<div class='last_date'><b>page hits</b></div>";
		echo "<table class='stats'>
				<tr>
					<td class='left'>
						<div class='top'><small>$most_height</small></div>
						<div class='bottom'><small>0</small></div>
					</td>";
		$i=1;
		while($hits = $result->fetch_array(MYSQLI_BOTH)){
			$ratio = $most_height / $hits['hit'];
			$line_height = round($max_height / $ratio)."px"; 
			if($_GET['overview']=='year')
				$date = $hits['year'];
			elseif($_GET['overview']=='month')
				$date = $hits['monthname']." ".$hits['year'];
			else
				$date = $hits['dayname']." ".$hits['day']."-".$hits['month']."-".$hits['year'];
			echo "<td class='stats_bar'><div onmouseover='ShowText(\"message".$i."\")' onmouseout='HideText(\"message".$i."\")' style='background-color:#".$settings['color_hits_bar']."; height: $line_height; border: 1px solid #".$settings['color_hits_bar_border'].";' />
			<div class='box' id='message".$i."'><small><b>Datum:</b> ".$date."<br /><b>Aantal hits:</b> ".$hits['hit']."</small></div></td>";
			$i++;
		}
		echo "</tr></table>";
		
		if(isset($settings['show_unique_visitors']) && $settings['show_unique_visitors']==1){
			echo "<div class='last_date'><b>Unieke bezoekers</b></div>";
			echo "<table class='stats'>
					<tr>
						<td class='left'>
							<div class='top'><small>$most_height_unique</small></div>
							<div class='bottom'><small>0</small></div>
						</td>";
			
			/***** unique visitors *****/
		
			$sql = "SELECT 
						COUNT(ip) AS hit, 
						DAYNAME(date) as dayname,
						DAY(date) as day,
						MONTHNAME(date) as monthname,
						MONTH(date) as month,
						YEAR(date) as year
					FROM 
						".$prefix."log 
					WHERE 
						date > (DATE_SUB(curdate(), 
						INTERVAL $timeline $interval)) 
						AND page = 'index' 
						$exlude_ip
					GROUP BY 
						day
					ORDER BY 
						$substring
					ASC" ;
			if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
			$i=1;
			while($uniques = $result->fetch_array(MYSQLI_BOTH)){
				//$fixed_ratio = $height / 100;
				//$line_height = round(($max_height / height) * 100)."px"; 
				$ratio = $most_height / $uniques['hit'];
				$line_height = round($max_height / $ratio)."px"; 
				if($_GET['overview']=='year')
					$date = $uniques['year'];
				elseif($_GET['overview']=='month')
					$date = $uniques['monthname']." ".$uniques['year'];
				else
					$date = $uniques['dayname']." ".$uniques['day']."-".$uniques['month']."-".$uniques['year'];
				//$date = date_create_from_format("l d-m-Y", $input);
				echo "<td class='stats_bar'><div onmouseover='ShowText(\"message".$i."\")' onmouseout='HideText(\"message".$i."\")' style='background-color:".$settings['color_unique_bar']."; height: $line_height; border: 1px solid ".$settings['color_unique_bar_border'].";' />
				<div class='box' id='message".$i."'><small><b>Datum:</b> ".$date."<br /><b>Aantal hits:</b> ".$uniques['hit']."</small></div></td>";
				$i++;
			}
			echo "</tr></table>";
		}

			echo "<div class='last_date'><b>Meestgebruikte browsers</b></div>";
			echo "<table class='small_stats'>
				<tr><td></td></tr>";
			$sql = "SELECT COUNT(DISTINCT ip) as amount,browser FROM blog_log WHERE browser <> '' GROUP BY browser  ASC LIMIT 5";
      if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
			while($rs = $result->fetch_array(MYSQLI_BOTH)){
				echo "<tr><td>".$rs['browser']."</td><td align='right'>".$rs['amount']."</td></tr>";	
			}
	}
	
	public function logOverview(){
		global $prefix, $settings;

		echo "<div class='form_titel'>Log</div><div class='interval'><div class='option'><a href='".$_SERVER['PHP_SELF']."?section=log&amp;type=admin'>Admin log</a></div><div class='option'><a href='".$_SERVER['PHP_SELF']."?section=log&amp;type=user'>User log</a></div>";
		if($this->isEditor() || $this->isAdmin())
			echo "<div class='option_red'><a href='".$_SERVER['PHP_SELF']."?section=log&clear=logs'>Logs legen</a></div>";
		echo "</div>";
		if($settings['search_log']==1&& !isset($_GET['details']) && !isset($_GET['delete']))
			echo "<div class='search'><a href='".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&amp;mode=search'>Geavanceerd zoeken</a></div>";
		if(isset($_GET['details'])){
			$id = intval($_GET['details']);
			$this->logDetails($id);	
		}elseif(isset($_GET['delete'])){
			$id = intval($_GET['delete']);
			$this->deleteLogItem($id);
		}else{
			$sql = "SELECT * FROM ".$prefix."log";
      if(!$result = $this->link->query($sql))
				 $this->db_message($sql);
			$aantal = $result->num_rows;
				if($aantal > 0){
					if($_GET['mode']=='search')
						$this->searchForm();
					if($_POST['name']!="")
						$search = " AND user LIKE '%".$_POST['name']."%'";
					if($_POST['date']!="")
						$search .= " AND date LIKE '%".$_POST['datum']."%'";
					if($_POST['ip']!="")
						$search .= " AND ip LIKE '%".$_POST['ip']."%'";	
					if($_POST['exclude_ip']!="" || isset($_GET['exlude_ip']))	
						$search .= "AND ip NOT IN ('78.27.63.xx', '83.163.18.xx') ";
					
					//resultaten per page
					$page_rows = $settings['max_log_items'];
					$pages = ceil($aantal/$page_rows);
	
					//nooit lager dan 1 of hoger dan maximum 
					if (!isset($_GET['page']))
						$pagenum = 1; 
					else
						$pagenum = intval($_GET['page']); 
					
					$type = $_GET['type'];
					switch($type){
						case "admin":
							$page = 'admin';
							break;
						case "user":
							$page = 'index';
							break;
						default:
							$page = 'admin';
							break;	
					}
					$max = 'limit ' .($pagenum - 1) * $page_rows .',' .$page_rows;
	
					$sql = "SELECT * FROM  ".$prefix."log WHERE page = '$page' $search ORDER BY id DESC $max";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
					echo "<table class='log'><tr><th><b>Datum</b></th><th><b>Gebruiker</b></th><th><b>Aktie</b></th><th><b>Melding</b></th><th>&nbsp;</th></tr>";
					while($rl = $result->fetch_array(MYSQLI_BOTH)){
						if($rl['message']!=""){
							$trclass="bad";
								}else{
							$trclass="record";
						}
						echo "<tr class='$trclass'>
						<td>".$rl["date"]."</td>";
						
						$user_ip = $rl['ip'];
						
						echo "<td class='user'>".$this->getUserHost($user_ip)."</td>";
						if(strlen($rl['url']) > 28)
							$rl["url"] = substr($rl["url"],0,28)."... ";
							
						echo "<td class='url'>".htmlentities($rl["url"])."</td><td class='url'>".htmlentities($rl["message"])."</td>
						<td align='right'><a href='".htmlentities($_SERVER['PHP_SELF'])."?section=log&amp;details=".$rl["id"]."'><img src='../images/view.gif' alt='' /></a>";
						if($this->isEditor() || $this->isAdmin())
							echo "<a href='".htmlentities($_SERVER['PHP_SELF'])."?section=log&amp;delete=".$rl['id']." '><img src='../images/cross.gif' alt='Verwijder log' /></a>";
						echo "</td></tr>";
					}
					echo "</table><div class='navigate'>";
					//op welke page nu

					if(($aantal > $max)&&($pagenum < $pages)){
							$page_next = $pagenum + 1;
							echo "<div class='older'><a href='".$_SERVER['PHP_SELF']."?section=log&amp;type=$type&amp;page=$page_next";
							if($_POST['exclude_ip']!="" || isset($_GET['exlude_ip']))	
								echo "&amp;exlude_ip=1";						
							echo "'>&laquo; Ouder</a></div>";
						}
						if($pagenum > 1){
							$page_prev = $pagenum - 1 ;
							echo "<div class='newer'><a href='".$_SERVER['PHP_SELF']."?section=log&amp;type=$type&amp;page=$page_prev";
							if($_POST['exclude_ip']!="" || isset($_GET['exlude_ip']))	
								echo "&amp;exlude_ip=1";
							echo "'>Nieuwer &raquo;</a></div>";
					}
	
					echo "</div>";
					}else{
						echo $this->message("NOTICE","Log is leeg");
					}
		}
	}
	
	public function searchForm(){
		global $prefix, $settings;		
		
		echo "<form method='POST' action='".htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])."'><table class='searchform'><tr class='head'><td colspan='2'>Zoeken</td></tr>
		<tr><td>Naam</td><td><input type='text' name='name' class='small'/></td></tr>
		<tr><td>Datum</td><td><input type='text' name='date' class='small'/></td></tr>
		<tr><td>IP</td><td><input type='text' name='ip' class='small'/></td></tr>
		<tr><td><input type='checkbox' name='exclude_ip' value='true'/> Eigen ip negeren</td><td></td></tr>
		<tr><td><input type='submit' class='submit' value='Zoek' /></td><td>&nbsp;</td></tr>
		</table></form>";
	}
	
	public function logDetails($id){
		global $settings, $prefix;
		
		$sql = "SELECT * FROM ".$prefix."log WHERE id = '$id'";
    if(!$result = $this->link->query($sql))
			 $this->db_message($sql);
		
		if($result->num_rows>0){
			$detailed_info = $result->fetch_array(MYSQLI_BOTH);
			echo "<table class='log_details'><tr class='head'><th colspan='1' class='arrow'> <a href='javascript:history.go(-1);'><img src='../images/arrow_left.png' alt='' /></a></th><th> Overzicht log entry $log_details</th><th>"; 
				if($this->isEditor() || $this->isAdmin())
					echo "<a href='".htmlentities($_SERVER['PHP_SELF'])."?section=log&amp;delete=$id'><img src='../images/cross.gif' alt='' style='float:right;'/></a>";
				echo "</th></tr>
				<tr><td class='title'>Datum</td><td>".$detailed_info["date"]."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Ip</td><td>".$detailed_info["ip"]."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Webhost</td><td>".$detailed_info["webhost"]."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Gebruiker</td><td>".$detailed_info["user"]."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Browser</td><td>".$detailed_info["browser"]."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Systeem server</td><td>".$detailed_info["os_server"]."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Systeem gebruiker</td><td>".$detailed_info["os_user"]."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Referral</td><td>".wordwrap(htmlentities($detailed_info["referrer"]), 70, "\n", TRUE)."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Protocol</td><td>".$detailed_info["protocol"]."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Method</td><td>".$detailed_info["method"]."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Melding</td><td>".$detailed_info["melding"]."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Data opgestuurd</td><td>".wordwrap($detailed_info["extra"], 70, "\n", true)."</td><td>&nbsp;</td></tr>
				<tr><td class='title'>Query URL</td><td>".wordwrap(htmlentities($detailed_info["url"]), 70, "\n", true)."</td><td>&nbsp;</td></tr></table>";
		}else{
			echo $this->message("ERROR","Log item niet gevonden, onjuist id.");	
		}
		$result->free();
	}
	
	public function getUserHost($user_ip){
		global $ip, $prefix;
		
		$sql = "SELECT * FROM ".$prefix."known_hosts WHERE ip = '$user_ip'";
    if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		$rs = $result->fetch_array(MYSQLI_BOTH);	
		
		$name = $rs['name'];
		return($name);
	}
	
	public function deleteLogItem($id){
		global $prefix, $settings;
		
		$sql = "SELECT id FROM ".$prefix."log WHERE id='$id' ";
    if(!$result = $this->link->query($sql))
			 $this->db_message($sql);
		if($result->num_rows>0){		
		
			$sql = "DELETE FROM ".$prefix."log WHERE id='$id' ";
      if(!$result = $this->link->query($sql))
				 $this->db_message($sql);

			echo $this->message("NOTICE","Het logitem is verwijderd, een moment geduld...");	
			echo "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?section=log\" />";
		}else{
			echo $this->message("ERROR","Logitem bestaat niet en kan dus niet verwijderd worden.");	
		}
	}
	
	public function message($type, $text=FALSE){
		global $settings;
		
		if($text != FALSE){
			switch($type){
				case "ERROR": $message = "<p class='error'>$text</p>"; break;
				case "FIELD_ERROR": $message = "<span class='field_error'>* $text</span>"; break;	
				case "NOTICE": $message = "<p class='notify'>$text</p>"; break;	
				case "SUCCESS": $message = "<p class='success'>$text</p>"; break;
				case "EMPTY": $message = "<p>$text</p>"; break;	
			}
		}else{
			switch($type){
				case "ERROR": $message = "<p class='error'>Het item kon niet worden gevonden. Probeer het opnieuw.</p>"; break;	
				case "FIELD_ERROR": $message = "<span class='field_error'>* Gelieve dit veld in te vullen.</span>"; break;
				case "NOTICE": $message = "<p class='notify'>Het item is succesvol bewerkt.</p>"; break;	
				case "SUCCESS": $message = "<p class='success'>Het item is succesvol opgeslagen.</p>"; break;
				case "EMPTY": $message = "<p>Er zijn geen items gevonden op deze page.</p>"; break;	
			}
		}
		return($message);
	}
	
	public function check_valid_ip($ip){
		global $settings;
		
		//if(ereg('^([0-9]{1,3}\.){3}[0-9]{1,3}$',$ip))
    
    //check if valid ipv4 or ipv6 address
    if (filter_var($ip, FILTER_VALIDATE_IP) || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) 
			$valid = TRUE;
			
		return($valid); 
	}
	
	public function image_camera_data($image){
		global $settings, $prefix;
		
		$exif_ifd0 = @read_exif_data($image,'IFD0' ,0);      
    $exif_exif = @read_exif_data($image,'EXIF' ,0);
		
		$img_details['make'] = "N/A";
		$img_details['model'] = "N/A";
		$img_details['exposure'] = "N/A";
		$img_details['aperture'] = "N/A";
		$img_details['iso'] = "N/A";
		$img_details['bpa'] = "N/A";
		
		if($exif_ifd0['Make']!="")
			$img_details['make'] = $exif_ifd0['Make']; 	
		if($exif_ifd0['Model']!="")
			$img_details['model'] = $exif_ifd0['Model'];
		if($exif_ifd0['ExposureTime']!="")
			$img_details['exposure'] = $exif_ifd0['ExposureTime'];
		if($exif_ifd0['COMPUTED']['ApertureFNumber'] != "")
			$img_details['aperture'] = $exif_ifd0['COMPUTED']['ApertureFNumber'];
		if($exif_exif['ISOSpeedRatings']!= "")
			$img_details['iso'] = $exif_exif['ISOSpeedRatings'];	
		if($exif_exif['FocalLength']!= "")
			$img_details['bpa'] = $exif_exif['FocalLength'];	
		
		return($img_details);
	}
	
	public function image_quota(){
		global $settings, $prefix;
		
		$sql = "SELECT image FROM ".$prefix."albumphotos ";	
    if(!$result = $this->link->query($sql))
			 $this->db_message($sql);
		
    $quota=0;
		while($image = $result->fetch_array(MYSQLI_BOTH)){
			$filesize = filesize("../".$image["image"]);
			$filesize = round($filesize/1024/1024, 2); //mb	
			$quota=$quota+$filesize;
		}
		if($settings['img_quota']<=$quota)
			return FALSE;
		else
			return TRUE;
	}
	
	public function image_quota_status(){
		global $settings, $prefix;
    
    $quota=0;
		$sql = "SELECT image FROM ".$prefix."albumphotos ";
    if(!$result = $this->link->query($sql))
			 $this->db_message($sql);
		while($image = $result->fetch_array(MYSQLI_BOTH)){
			$filesize = filesize("../".$image["image"]);
			$filesize = round($filesize/1024/1024, 2); //mb	
			$quota=$quota+$filesize;
		}
		$percentage = round(($quota / $settings["img_quota"]) * 100) ."%";
		if($percentage >= 100) {
			$border = "#da3a3a";
			$color = "#fbcaca";
			$width = "100%";
			$message = $this->message("ERROR","Het maximum quota is gehaald. Het is niet mogelijk om nog afbeeldingen te uploaden. Verwijder eerst andere afbeeldingen.");
		}elseif($percentage >= 90) {
			$border = "#cd8351";
			$color = "#f1cfb8";
			$width = $percentage;
			$message = $this->message("ERROR","Let op, maximum quota is bijna gehaald. Verwijder eventueel andere afbeeldingen.");
		}else{
			$border = "#8c9cea";
			$color = "#D8EEFA";
			$width = $percentage;
		}
		echo "<div class='quota_bar'><div class='left'><small>Quota afbeeldingen</small></div><div class='bar' style='border: 1px solid $border;'>";
		echo "<div class='line' style='background-color:$color; width:$width;'><p>$percentage ($quota van ".$settings["img_quota"]. " mb verbruikt)</p></div></div></div>";
		if(isset($message))echo $message;
	}
	
	public function systemOverview(){
		global $settings, $prefix;
		
		echo "<div class='form_titel'>Systeem</div>";
		echo "<div class='interval'><div class='option'><a href='".$_SERVER['PHP_SELF']."?section=system&amp;view=database'>Database beheer</a></div><div class='option'><a href='".$_SERVER['PHP_SELF']."?section=systeem&amp;view=quota'>Bestandsbeheer</a></div><div class='option'><a href='".$_SERVER['PHP_SELF']."?section=systeem&amp;view=users'>Gebruikersbeheer</a></div></div>";
		$view = $_GET["view"];
		switch($view){
			case "database":
				require('../templates/db_main.php');
			break;
			case "quota":
				require('../templates/quota.php');
			break;
			case "users":
				$sql = "SELECT * FROM ".$prefix."usergroups";
        if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
				if(isset($_GET['edit_user'])){
					$user_id = $_GET['edit_user'];
					
					$sql = "SELECT * FROM ".$prefix."users u
						INNER JOIN ".$prefix."usergroups ug ON u.group = ug.id
						WHERE u.id = '$user_id'
					";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
					$user = $result->fetch_array(MYSQLI_BOTH);
				
					if($_POST['edit_user']!=""){
						$user['username'] = trim($_POST['username']);
						$user['loginname'] = trim($_POST['loginname']);	
						$user['password'] = trim($_POST['password']);		
						$user['group'] = $_POST['group'];
						$user['status'] = $_POST['status'];	

						if($user['username'] != "" && $user['username']!=""){
							if($user['password']!="")
								$extra_query = ", password = '".$user['password']."'";
							if($user['group']!="")
								$extra_query .= ", group = '".$user['group']."'";
								
							$result = "UPDATE ".$prefix."users SET 
								username = '".$user['username']."',
								status = '".$user['status']."' 
								$extra_query
							WHERE
								id = '$user_id'
							";			
						}else{
							if($user['username'] == "")
								$error['username'] = $this->message("FIELD_ERROR","Geef een gebruikersnaam op");
							if($user['loginname'] == "")
								$error['loginname'] = $this->message("FIELD_ERROR","Geef een loginnaam op");
							if($user['status'] == "")
								$error['status'] = $this->message("FIELD_ERROR","Geef de status op");	
						}
					}
					require('../templates/edit_user.php');	
					
				}elseif($_GET['mode']=='add_user'){
					if($_POST['new_user']!=""){
						$user['username'] = trim($_POST['username']);
						$user['loginname'] = trim($_POST['loginname']);	
						$user['password'] = md5(trim($_POST['password']));		
						$user['group'] = $_POST['group'];
						$user['status'] = $_POST['status'];	
						
						if($user['username'] != "" && $user['loginname']!="" && $user['status'] != "" && $user['password'] != "" && $user['group'] != ""){
							$sql = "INSERT INTO ".$prefix."users(username, loginname, status, password, group) VALUES('".$user['username']."','".$user['loginname']."','".$user['status']."','".$user['wachtwoord']."','".$user['groep']."')";	
              if(!$result = $this->link->query($sql))
				        $this->db_message($sql);
							
							$msg['user'] = $this->message("NOTICE","De gebruiker is succesvol toegevoegd, een moment geduld...");
							echo "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?section=systeem&view=users\" />";
						}else{
							if($user['username'] == "")
								$error['username'] = $this->message("FIELD_ERROR","Geef een gebruikersnaam op");
							if($user['password'] == "" || strlen($user['password']) < 6)
								$error['password'] = $this->message("FIELD_ERROR","Wachtwoord moet minimaal 6 karakters bevatten.");
							if($user['loginname'] == "")
								$error['loginname'] = $this->message("FIELD_ERROR","Geef een loginnaam op");
							if($user['status'] == "")
								$error['status'] = $this->message("FIELD_ERROR","Geef de status op");	
							if($user['group'] == "")
								$error['group'] = $this->message("FIELD_ERROR","Geef de groep op");
						}
					}
					
					require('../templates/new_user.php');
					
				}else{
					$sql = "SELECT u.id as user_id, u.username as user, u.status, u.group FROM ".$prefix."users u
						INNER JOIN ".$prefix."usergroups ug ON groep = ug.id";
          if(!$result = $this->link->query($sql))
				    $this->db_message($sql);
				
					require('../templates/user_layout.php');
				}
			break;	
			
			default:
				require('../templates/db_main.php');
				break;
		}
	}
	public function db_message($sql){
		global $prefix;
		
		echo "<div class='db_error'><p>";
		printf("Fout in query: %s\n", $this->link->error);
		echo "</p></div>";	
		
		//$sql=str_replace("'", "\'", $sql);
		$sql = mysqli_real_escape_string ( $this->link , $sql );
		//$sql = str_replace("'", "\'",$sql);
		
		$date=time();
		
		$sql = "INSERT INTO ".$prefix."mysql_log(user, date, message) VALUES('', '".$date."', '".$sql."')";
		if(!$result = $this->link->query($sql))
			die('Fout bij opslaan log in mysql_log, details: '.$sql);
	}
}

?>