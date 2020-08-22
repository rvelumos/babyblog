<?php
class Content{
	
	public function __construct() {
		//$this->link = new MySQLi('localhost', 'root', 'b_b123', "babyblog");
		$this->link = new MySQLi('localhost', 'deb4597_blus123', 'voqQzO79', "deb4597_blog");
	}
	
	public function getSettings(){
		global $prefix, $settings;

		$sql = "SELECT * FROM ".$prefix."settings";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		$settings = $result->fetch_array();
		
		$result->free();
		
		return($settings);
	}

	public function getMenuItems(){
		global $prefix,$menu_items, $ip;
		
		$sql = "SELECT * FROM ".$prefix."menu";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
			
		while($menu_item = $result->fetch_array(MYSQLI_BOTH)){
			if($menu_item['name'] != 'Admin')
				echo "<p class='menu_link'><a href='https://".$_SERVER['SERVER_NAME']."/babyblog/".urlencode(strtolower($menu_item['name']))."/' >".$menu_item['name']."</a></p>";	
			else
				echo "<p class='menu_link'><a href='https://".$_SERVER['SERVER_NAME']."/babyblog/admin/'>".$menu_item['name']."</a></p>";	
		}
		$result->free();

		return $menu_items;
	}
	
	
  function parse_path() {
	  $path = array();
	  if (isset($_SERVER['REQUEST_URI'])) {
		$request_path = explode('?', $_SERVER['REQUEST_URI']);
	
		$path['base'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
		$path['call_utf8'] = substr(urldecode($request_path[0]), strlen($path['base']) + 1);
		$path['call'] = utf8_decode($path['call_utf8']);
		if ($path['call'] == basename($_SERVER['PHP_SELF'])) {
		  $path['call'] = '';
		}
		
		if ( ! isset($request_path[1])) {
   			$request_path[1] = null;
		}
		
		$path['call_parts'] = explode('/', $path['call']);
	
		$path['query_utf8'] = urldecode($request_path[1]);
		$path['query'] = utf8_decode(urldecode($request_path[1]));
		$vars = explode('&', $path['query']);
		foreach ($vars as $var) {
		  $t = explode('=', $var);
		  if(!isset($t[1]))$t[1]=null;
		  $path['query_vars'][$t[0]] = $t[1];
		}
	  }
	return $path;
	}
	
	
	public function loadContent(){	
		global $prefix, $page, $settings, $classes;
		
    $path_info = $this->parse_path();
		$page = strtolower(str_replace("/", "", $_SERVER['REQUEST_URI']));

    if($page=="")
			$page="home";
		else
			$page=$path_info['call_parts'][0];
		
    $level0_ok="";
    $level1_ok="";
    $level2_ok="";
    $level3_ok="";
    
		if(isset($path_info['call_parts'][0])){
			$sub_level_0 = $path_info['call_parts'][0];           
      
      $allowed_sub_level0_items = array("blog","fotoalbum", "admin");
      if(in_array($sub_level_0, $allowed_sub_level0_items)){ //only continue if there are no strange values
        $level0_ok=TRUE;
        if($sub_level_0=='fotoalbum'){
            if(isset($path_info['call_parts'][1]))$sub_level_1 = $path_info['call_parts'][1];
            if(isset($path_info['call_parts'][2]))$sub_level_3 = $path_info['call_parts'][3]; // fotoalbum/nummer/view/hash/ 
        }else{
          if(isset($path_info['call_parts'][1])){                  
            $sub_level_1 = $path_info['call_parts'][1];
            $allowed_sub_level1_items = array("archief","categorie", "tag", "pagina", "zoek","post");

            if(in_array($sub_level_1, $allowed_sub_level1_items)){ //only continue if there are no strange values
              $level1_ok=TRUE;

              if($sub_level_1=='archief'){              
                if(isset($path_info['call_parts'][2]) && $this->is_year($path_info['call_parts'][2])){ // check if value has correct year          
                  $level2_ok=TRUE;
                  $sub_level_2 = $path_info['call_parts'][2]; // blog/archief/jaar/
                }

                if(isset($path_info['call_parts'][3]) && $this->is_month($path_info['call_parts'][3])){ //check if value has correct month
                   $level3_ok=TRUE;
                   $sub_level_3 = $path_info['call_parts'][3]; // blog/archief/jaar/maand/          
                }
              }elseif($sub_level_1=='pagina'){
                $pagenum=$path_info['call_parts'][2];     
              }else{              
                if(isset($path_info['call_parts'][2]))$sub_level_2 = $path_info['call_parts'][2]; // blog/item/nummer/          
                if(isset($path_info['call_parts'][3]) && ($sub_level_0=='fotoalbum' || $sub_level_2=='categorie'))$sub_level_3 = $path_info['call_parts'][3];   // fotoalbum/xx/view/hash
               // echo "het is $sub_level_1 en $sub_level_2";
              }
            }
         }
        }
      }
		}
		
		$album_size = $settings["album_thumb_size"];
		$size = $settings['thumb_size'];

		//laad de content van het onderdeel
		switch($sub_level_0){
			default:
			case 'blog':
				if(isset($sub_level_2) && $level1_ok == TRUE && $sub_level_1=='post'){
         
          $extra="";
          if(isset($search))$extra=" AND description LIKE '%$search%'";
					$sql = "SELECT * FROM ".$prefix."posts WHERE id=$sub_level_2 $extra AND status = 1";

					if(!$result = $this->link->query($sql))
						$this->db_message($sql);
					$blog_item = $result->fetch_array(MYSQLI_BOTH);
					
					require('templates/blogitems.php'); 
		
					$result->free();		
				}else{
					$month="";
					$year="";
					$search="";
					$category="";

					if(isset($sub_level_1) && $sub_level_1=='categorie')$category = $sub_level_2;
          if(isset($sub_level_2) && $sub_level_2=='zoeken')$search = $sub_level_3;
					
					$sql = "SELECT * FROM ".$prefix."posts WHERE status = '1'";
					
					if(isset($sub_level_3) && $sub_level_3!=""){
						switch($sub_level_3){
							case "januari" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '01'"; break;
							case "februari" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '02'"; break;
							case "maart" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '03'"; break;
							case "april" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '04'"; break;
							case "mei" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '05'";break;
							case "juni" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '06'";break;
							case "juli" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '07'";break;
							case "augustus" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '08'";break;
							case "september" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '09'";break;
							case "oktober" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '10'";break;
							case "november" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '11'";break;
							case "december" : $sql .= " AND EXTRACT(MONTH FROM date_added) = '12'";break;
						}
					}
					
					if(isset($sub_level_2) && $sub_level_1=="archief"){
						$sql .= " AND EXTRACT(YEAR FROM date_added) = '$sub_level_2'";
					}
					
          $search="";
          if(isset($_GET['zoekterm']))$search= strip_tags(mysqli_real_escape_string($this->link,$_GET['zoekterm']));
					if($search!=""){
						$sql .= " AND description LIKE '%". $search ."%'";	
						$this->updateSearchwords($search);
					}

					if($category!="")            
						$sql .= " AND category LIKE '%".mysqli_real_escape_string($this->link,$category)."%'";	   
          //die($sql);
				
          if(!$result = $this->link->query($sql))
						$this->db_message($sql);
					//die($sql);
					$amount = $result->num_rows;
					
					$result->free();
					
					if(!isset($pagenum))$pagenum=1;
					
					$max = $settings['max_items'];
					$pages = ceil($amount/$max);
					$pagelimit = " limit " .($pagenum - 1) * $max ."," .$max;		
					
					//nooit lager dan 1 of hoger dan maximum 
					if ($pagenum < 1)  
						$pagenum  = 1; 
					elseif ($pagenum  > $pages)
						$pagenum = $pages; 
					
					$sql = "$sql ORDER BY date_added DESC $pagelimit ";
					//die($sql);
					
					if(!$result = $this->link->query($sql))
						$this->db_message($sql);
					
          if($amount > 0){
            while($blog_item = $result->fetch_array(MYSQLI_BOTH)){
              $size = $settings['thumb_size'];
              require('templates/blogitems.php');
            }
          }else{
            if(isset($search) && $search!="")
              echo $this->message("ERROR","Geen resultaten gevonden. Je zocht op <b>\"$search\"</b>. Probeer een andere zoekterm.");	
            if(isset($sub_level_1) && $sub_level_1=='archief') 
              echo $this->message("ERROR","Geen resultaten gevonden voor de gekozen periode. ");	
          }
					
					echo "<div class='bottom_nav'>";
					if(($amount > $max)&&($pagenum < $pages)){
						$page_next = $pagenum + 1;
						echo "<div class='older'><a href='https://".$_SERVER['SERVER_NAME']."/babyblog/blog/";
						if(isset($sub_level_2))//year
							echo $sub_level_2."/";
            if(isset($sub_level_3))//month
							echo $sub_level_3."/";
						echo "pagina/$page_next/'>&laquo; Oudere berichten</a></div>";
					}
					if($pagenum > 1){
						$page_prev = $pagenum - 1 ;
						echo "<div class='newer'><a href='https://".$_SERVER['SERVER_NAME']."/babyblog/blog/pagina/$page_prev/'>Nieuwere berichten &raquo;</a></div>";
					}
					echo "</div>";
				}
				break;
				
				case 'fotoalbum':

        if(isset($sub_level_1) && $sub_level_1 !=""){           
						$album_id = floatval($sub_level_1);
						if(isset($sub_level_3)){
							$auth_key = $sub_level_3;
							$this->imageDetails($auth_key, $size);
						}else{
							$this->fetchAlbumImages($album_id);
						}
					}else{
						$sql = "SELECT * FROM ".$prefix."album WHERE status = 1 order by id DESC";
						if(!$result = $this->link->query($sql)){
							$this->db_message($sql);
						}else{
							if($result->num_rows > 0){
								$size = $settings['album_thumb_size'];
								
						//		if($settings['album_left_menu']==1)
							//		require('templates/image_leftmenu.php');
								require('templates/album_overview.php');
							}else{
								$msg['empty'] = "Geen albums gevonden. ";	
							}
						}
					}
				break;
		}
	}
	public function updateSearchwords($search){
		global $prefix, $settings, $ip;
    
    $amount=1;
    
		$sql = "SELECT * FROM ".$prefix."search WHERE searchphrase = '$search'";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		
    if($result->num_rows>0){
      $rs=$result->fetch_array(MYSQLI_BOTH);
      $amount = intval($rs['amount']) + 1;

      $sql = "UPDATE ".$prefix."search SET 
        searchphrase = '$search',
        amount = '$amount',
        ip = '$ip' 
      WHERE searchphrase = '$search'";		
      if(!$result = $this->link->query($sql))
        $this->db_message($sql);
		}else{
			$sql ="INSERT INTO ".$prefix."search(searchphrase, ip, amount) VALUES('$search', '$ip', '$amount')";
			if(!$result = $this->link->query($sql))
				$this->db_message($sql);
		}
	}
	
	public function loadRightMenu(){
		global $prefix, $page, $settings, $ip;
		
    $content='';
    $path_info = $this->parse_path();

    if($path_info['call_parts'][0]=='blog' ||  $path_info['call_parts'][0]==""){
      //date part
      $content = "<div class='archive'><div class='titel'>Archief</div><br />";

      $sql = "SELECT EXTRACT(YEAR FROM date_added) as year FROM ".$prefix."posts GROUP BY year DESC";
      if(!$result = $this->link->query($sql))
        $this->db_message($sql);

      while($years = $result->fetch_array(MYSQLI_BOTH)){
       // echo "ddd";
        $content .= "<div class='entry'><a onClick='hideYear(\"".$years['year']."\", \"arrow_".$years['year']."\");'><img src='https://".$_SERVER['SERVER_NAME']."/babyblog/images/arrow.gif' id='arrow_".$years['year']."' alt=''/>&nbsp;<b>".$years['year']."</b></a></div>";

        $sql = "SELECT EXTRACT(MONTH FROM date_added) as month FROM ".$prefix."posts WHERE EXTRACT(YEAR FROM date_added) = '".$years['year']."' GROUP BY month DESC";
        if(!$result2 = $this->link->query($sql))
          $this->db_message($sql);

        $content .= "<div class='menu_show' id=".$years['year']."><ul>";
        while($data = $result2->fetch_array(MYSQLI_BOTH)){
          //extract nog eens aanpassen voor 2 digits
          $data['month'] = sprintf("%02d", $data['month']);

          $sql = "SELECT COUNT(*) AS amount FROM ".$prefix."posts WHERE EXTRACT(MONTH FROM date_added) = '".$data['month']."' AND EXTRACT(YEAR FROM date_added) = '".$years['year']."' ";
          if(!$result3 = $this->link->query($sql))
            $this->db_message($sql);
          $count = $result3->fetch_array(MYSQLI_BOTH);

            switch($data['month']){
              case '01' : $month = 'januari'; break;
              case '02' : $month = 'februari'; break;
              case '03' : $month = 'maart'; break;
              case '04' : $month = 'april'; break;
              case '05' : $month = 'mei'; break;
              case '06' : $month = 'juni'; break;
              case '07' : $month = 'juli'; break;
              case '08' : $month = 'augustus'; break;
              case '09' : $month = 'september'; break;
              case '10' : $month = 'oktober'; break;
              case '11' : $month = 'november'; break;
              case '12' : $month = 'december'; break;
            }
          $content .= "<li><a href='https://".$_SERVER['SERVER_NAME']."/babyblog/blog/archief/{$years['year']}/$month'>".ucwords($month)."</a> (".$count['amount'].")</li>";

        }			
        $content .= "</ul></div>";
      }
        if($settings['poll']=="1")
          $this->getPoll();
      $content .= "</div>";

      //categorie gedeelte
      $content .= "<div class='category'><div class='titel'>Categorie&euml;n</div><br />";

      $sql = "SELECT * FROM ".$prefix."categories WHERE status = 1 ORDER BY name ASC";	
      if(!$result = $this->link->query($sql))
        $this->db_message($sql);
      while($category = $result->fetch_array(MYSQLI_BOTH)){
        $content .= "<a href='https://".$_SERVER['SERVER_NAME']."/babyblog/blog/categorie/".strtolower($category['name'])."'>".$category['name']."</a><br />";	
      }

      $content .= "</div>";

    if($ip=='78.27.63.121'){
      //tags
      $content .= "<div class='tags'><div class='titel'>Tags</div><br />";

      $sql = "SELECT * FROM ".$prefix."posts i
         INNER JOIN ".$prefix."tags_items pt ON i.id = pt.item_id
         INNER JOIN ".$prefix."tags t ON t.tag_id = pt.tag_id
       WHERE t.name = '$tag'";

      if(!$result = $this->link->query($sql))
        $this->db_message($sql);

      while($category = $result->fetch_array(MYSQLI_BOTH)){
        $content .= "<a href='https://".$_SERVER['SERVER_NAME']."/babyblog/blog/categorie/".strtolower($category['name'])."'>".$category['name']."</a><br />";	
      }

      $content .= "</div>";
      }
    }
		return $content;
	}

	public function getPoll(){
		global $prefix, $settings, $ip;

		$sql = "SELECT * FROM ".$prefix."pollquestion WHERE status = '1' ";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		$question = $result->fetch_array(MYSQLI_BOTH);
		
		$result->free();

		$sql = "SELECT * FROM ".$prefix."pollquestion WHERE poll_id = '{$question['id']}'";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		
		$content = "<p class='poll_title'>Poll:</p> ".$question['poll_question']."</div><div class='poll'><div class='poll_top'>
		<div class='poll_content'></div>";
		
		if(isset($_POST['send_vote'])){
			if($_POST['answer']==""){
				$content .= "<p class='error'>Maak aub een keuze</p>";
				$content .= "<form method='POST' action='".htmlentities($_SERVER['PHP_SELF'])."'> ";
				$content .= "<input type='hidden' name='send_vote' value='true' />";
				while($answer = $result->fetch_array(MYSQLI_BOTH)){
					$content .= "<div class='poll_answer'><input type='radio' id='".$answer['id']."' name='answer' class='poll' value='".$answer['id']."'>".$answer['poll_antwoord']."</div>";
				}
				$content .= "<div class='poll_bottom'><input type='submit' value='Stem' class='submit' /><a href='".htmlentities($_SERVER['PHP_SELF'])."?view_results'>Bekijk resultaten</a></form></div>";
			}else{
				$sql = "SELECT * FROM ".$prefix."pollvoted WHERE ip = '".$_SERVER['REMOTE_ADDR']."' ";
				if(!$result = $this->link->query($sql))
					$this->db_message($sql);
				$amount = $result->num_rows;
				
				$result->free();
				
				if($amount < 1){
					$sql = "INSERT INTO ".$prefix."pollvoted(pollid,ip) values ('".$question['id']."','$ip')";
					if(!$result = $this->link->query($sql))
						$this->db_message($sql);
					else{
						$sql = "SELECT aantal FROM ".$prefix."pollanswer WHERE id = '".$_POST['answer']."'";
						if(!$result = $this->link->query($sql))
							$this->db_message($sql);
						$amount = $result->fetch_array(MYSQLI_BOTH);

						$vote = $amount['amount'] + 1;
						$result->free();

						$sql = "UPDATE ".$prefix."pollanswer SET amount = '$vote' WHERE id = '".$_POST['answer']."'";
						if(!$result = $this->link->query($sql))
							$this->db_message($sql);
						else{					
							$content .= "<p class='middle'>Je stem is toegevoegd!</p>";
							$content .= "<a href='".$_SERVER['PHP_SELF']."?view_results'>Bekijk resultaten</a></p>";
						}
					}
					
				}else{
					$content .= "<p class='middle'>Je mag maximaal 1x stemmen!<br /><br />";
					$content .= "<a href='".$_SERVER['PHP_SELF']."?view_results'>Bekijk resultaten</a></p>";
				}
			}
		}else{
			if(isset($_GET['view_results'])){
				$sql = "SELECT SUM(amount) AS aw_amount FROM ".$prefix."pollanswer";	
				if(!$result = $this->link->query($sql))
					$this->db_message($sql);
				$rs = $result->fetch_array(MYSQLI_BOTH);
				$amount = $rs['aw_amount'];

				$result->free();
				
				$sql = "SELECT * FROM ".$prefix."pollanswer";
				if(!$result = $this->link->query($sql))
							$this->db_message($sql);
				
				$content = "Aantal stemmen: $totaal";
				while($score = $result->fetch_array(MYSQLI_BOTH)){
					if($score['amount'] > 0)
						$percentage = round(($score['aantal'] / $total) * 100) ."%"; 
					else
						$percentage = "0%";
					
					if($score['poll_answer']=='Jongen'){
						$border = "#8c9cea";
						$color = "#D8EEFA";
					}else{
						$border = "#ea8ccc";
						$color = "#fad7fa";
					}
					$content .= "<div class='poll_option'><span class='answer_left'>".$score['poll_answer']."</span><span class='pct_right'>$percentage</span></div>";
					$content .= "<div class='line' style='background-color:$color; border: 1px solid $border; width:$percentage;'></div>";
				}
				$result->free();
			}else{
				
				$content .= "<form method='post' action='".$_SERVER['PHP_SELF']."'> ";
				$content .= "<input type='hidden' name='send_vote' value='true' />";
				while($anwers = $result->fetch_array(MYSQLI_BOTH)){
					$conten .= "<div class='poll_answer'><input type='radio' id='a".$answer['id']."' name='answer' class='poll' value='".$answer['id']."' />".$answer['poll_answer']."</div>";
				}
				$content .= "<div class='poll_bottom'><input type='submit' value='Stem' class='submit' /><a href='".$_SERVER['PHP_SELF']."?view_results'>Bekijk resultaten</a></div></form>";
				
				$result->free();
			}			
		}
		$content .= "</div>";
		return $content;
	}
	
	public function countReactions($number,$section){
		global $prefix;
		
		$sql = "SELECT COUNT(*) AS reactions FROM ".$prefix."reactions WHERE item = $number AND section = '$section'";

		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		$rs = $result->fetch_array(MYSQLI_BOTH);
		$content = $rs['reactions'];

		$result->free();
		return $content;
	}
	
	public function getReactions($number, $section){
		global $prefix, $ip, $settings;
		
			$sql = "SELECT * FROM ".$prefix."reactions WHERE item = $number AND section = '$section' ORDER BY date";
			if(!$result = $this->link->query($sql))
				$this->db_message($sql);
		
			if($result->num_rows > 0){
				while($reaction = $result->fetch_array(MYSQLI_BOTH)){
					echo "<div class='reactie_holder'><div class='reactie_top'><i><p class='name'>".$reaction['name']."</p></i><p class='time'>".$reaction['date']."</p></div><div class='reactie_midden'>";	
					echo stripslashes($reaction['message']);
					echo "</div></div>";
				}
		}
		$result->free();

		if(isset($_POST['post_reaction'])){
			$name = mysqli_real_escape_string($this->link, htmlentities($_POST['name']));
			if($settings['allow_html']=='1')
				$message = mysqli_real_escape_string($this->link,$_POST['message']);
			else
				$message = mysqli_real_escape_string($this->link,htmlentities($_POST['message']));
			
			if($name != '' && $message != ''){
				$sql = "INSERT INTO ".$prefix."reactions (name, message, ip, date, section, item) VALUES ('$name', '$message', '$ip', NOW(), '$section', '$number')";
       // die($sql);
				if(!$result = $this->link->query($sql))
					$this->db_message($sql);
				else
					echo "<p style='clear:both'>Je reactie is toegevoegd.</p>";	
				
			}else{
				if($name=='')
					$error['name'] = "<p class='field_error'>* Vul de naam in</p>";
				if($message=='')
					$error['message'] = "<p class='field_error'>* Vul het bericht in</p>";
			}
		}
		require('templates/reacties.php');
	}
	
	public function countAlbumImages($album_id){
		global $prefix, $settings;
		
		$sql = "SELECT * FROM ".$prefix."albumphotos WHERE album_id = '$album_id' AND STATUS = '1'";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		$amount = $result->num_rows;
		
		$result->free();
		
		return $amount;
	}
		
	public function fetchAlbumImages($album_id){
		global $prefix, $settings, $ip, $classes;
		
		$size = $settings['preview_size'];
    $pagelimit="";
		
		$sql = "SELECT * FROM ".$prefix."album WHERE id = '$album_id' AND STATUS = '1'";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		if($result->num_rows > 0){
			$rs = $result->fetch_array(MYSQLI_BOTH);
			$album_name = $rs['name'];
			
			$sql = "SELECT * FROM ".$prefix."albumphotos WHERE album_id = '$album_id' AND status = '1'";
			if(!$result = $this->link->query($sql))
				$this->db_message($sql);
			
			//pagina's opbouwen
			if (!isset($_GET['pagina'])){ 
				$pagenum = 1; 
			}else{
				$pagenum = intval($_GET['pagina']); 
			}
			$aantal = $result->num_rows;
			if($aantal>0){
        
        $result->free();
			
        $max = 15;

        $pages = ceil($aantal/$max);

        $pagelimit = 'limit ' .($pagenum - 1) * $max .',' .$max;		

        //nooit lager dan 1 of hoger dan maximum 
        if ($pagenum < 1) { 
          $pagenum  = 1; 
        }elseif ($pagenum  > $pages){ 
          $pagenum = $pages; 
        }
      }
			
			$sql= "SELECT * FROM ".$prefix."albumphotos WHERE album_id = '$album_id' AND status = '1' ORDER BY date_added ASC $pagelimit";
			
			if(!$result = $this->link->query($sql))
				$this->db_message($sql);
			if($result->num_rows  < 1)
				$msg['empty'] = "<p>Geen afbeeldingen gevonden in dit album.</p>";	
		}else{
			$msg['empty'] = 'Ongeldig album. Indien deze boodschap blijft verschijnen, neem dan contact op.';
		}
				
//		if($settings['album_left_menu']==1)
//			require('templates/image_leftmenu.php');

		require('templates/album_images.php');
		
		$result->free();
	}
	public function imageViewed($viewed){
		global $settings, $prefix;
		
    $update_views=1;
    
		$sql = "SELECT viewed FROM ".$prefix."albumphotos WHERE auth_key = '$viewed' AND status = '1' ";
		if(!$result = $this->link->query($sql))
			$this->db_message($sql);
		if($result->num_rows > 0){
      $views = $result->fetch_array(MYSQLI_BOTH);
		
		  $update_views = $views['viewed'] + 1;
    }
		if($update_views > 0){
			$sql = "UPDATE ".$prefix."albumphotos SET viewed = '$update_views' WHERE auth_key = '$viewed'";
			if(!$result = $this->link->query($sql))
				$this->db_message($sql);
		}
		
		return $update_views;
	}
	public function imageDetails($auth_key){
		global $prefix, $settings, $classes;	
		
		$sql = "SELECT * FROM ".$prefix."albumphotos WHERE auth_key = '$auth_key' AND status = '1' ";
   
		if(!$result = $this->link->query($sql))
				$this->db_message($sql);
    
		if($result->num_rows > 0){
			$image_details = $result->fetch_array(MYSQLI_BOTH);
			
			$sql = "SELECT * FROM ".$prefix."album WHERE id = '{$image_details['album_id']}' ";
			if(!$result = $this->link->query($sql))
				$this->db_message($sql);
			$album = $result->fetch_array(MYSQLI_BOTH);
			
			$result->free();
			
			require('templates/image_details.php');
		}else{
			echo "Ongeldige image. Probeer het opnieuw.";	
		}
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
	
	public function debug_div(){
		$content = "<pre>hh";
		$content .= "<div style='background-color:green'><h1>POST</h1>";
		if(isset($_POST))print_r($_POST);
		$content .= "</div>";
		
		$content .= "<div style='background-color:blue'><h1>GET</h1>";
		if(isset($_GET))print_r($_GET);
		$content .= "</div>";	
		
		$content .= "<div style='background-color:red'><h1>ERROR</h1>";
		if(isset($error))print_r($error);
		$content .= "</div>";
		
		return $content;
	}

  public function is_month($month){
    
    $allowed_months=array("januari", "februari","maart","april","mei","juni","juli","augustus","september","oktober","november","december");
    if(in_array($month,$allowed_months))
      return TRUE;
    else
      return FALSE;
  }

  public function is_year($year){
    if(intval($year) > 1970 && intval($year) <= date("Y"))
      return TRUE;
    else
      return FALSE;
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