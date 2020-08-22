<?php
	echo "<div class='categories'><div class='form_titel'>$title</div>";
	if($this->isEditor() || $this->isAdmin())
		echo "<div class='actions'><div class='add'><a href='index.php?section=$pre&amp;mode=add_ct'><img src='../images/plus.png' alt='' />".ucwords($short)." toevoegen</a></div></div><br />";
	echo "<table class='table_data'>
		<tr class='top'><td>".ucwords($pre)."</td><td>Aantal x gebruikt</td><td>&nbsp;</td></tr>";
		while($ct_items = $result->fetch_array(MYSQLI_BOTH)){
				echo "<tr>
					<td>".$ct_items['name']."</td>
					<td>";
					if(isset($ct_items["id"]))
            $id = $ct_items["id"];
					//if($ct_items['id']=="")
						//$ct_items['id']=$id;
					
					if($short=="tag"){
            $id = $ct_items["tag_id"];
            $ct_items['id'] = $ct_items["tag_id"];
					 	$details=$id;
          }else
					 	$details=strtolower($ct_items['name']);
						
					echo $this->countCtUsed($ct_items["name"], $sum_cts, $short, $cnt_table, $id);
					echo "</td>
					<td align='right'>
					<a href='index.php?section=$pre&amp;details=".$details."'><img src='../images/view.gif' alt='' /></a>";
					if($this->isEditor() || $this->isAdmin()){
						echo "<a href='index.php?section=$pre&amp;edit_ct=".$ct_items['id']."'><img src='../images/edit.png' alt='' /></a> 
							  <a href='index.php?section=$pre&amp;delete_ct=".$ct_items['id']."'><img src='../images/cross.gif' alt='' /></a>";
					}
					echo "</td>
				</tr>";
		}
		echo "</table>";
	echo "</div>";
            
 ?>