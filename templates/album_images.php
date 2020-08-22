
<?php

if(isset($page) && $page=='admin'){
if($this->isEditor() || $this->isAdmin()){?>
<div class="crumble_path"><a href='<?php echo $_SERVER['PHP_SELF']?>?section=photoalbum'>Albums</a> &raquo; <?php echo $album_name?></div>
<div class='actions'>
<div class='add'><p><a href='index.php?section=photoalbum&amp;album_id=<?php echo $_GET['album_id']?>&amp;mode=upload_images'><img src='../images/plus.png' alt=''/> Foto's toevoegen</a></p></div><div class='remove'><p><a href='index.php?section=photoalbum&amp;album_id=<?php echo $_GET['album_id']?>&amp;mode=delete_album'><img src='../images/cross.gif' alt=''/> Album verwijderen</a></p></div><div class='edit'><p><a href='index.php?section=photoalbum&amp;album_id=<?php echo $_GET['album_id']?>&amp;mode=edit_album'><img src='../images/edit.png' alt=''/> Album bewerken</a></p></div>
</div>
<?php }
}else{?>
<div class="crumble_path"><a href='https://<?=$_SERVER['SERVER_NAME']?>/babyblog/fotoalbum/'>Albums</a> &raquo; <?php echo $album_name?></div>
<? } ?>
<span class='album_details'>
<?php echo $rs['description'];?>
</span>
<div class='<?php echo $classes["class_album"]?>'>
        <div class='tekst'>
        <?php if(isset($msg["empty"]))echo $msg['empty'];?></div>
		<div class='album_items'>
		<?php

		$section="photoalbum";
		if($album_name != "" && !isset($msg['empty'])){
			while($images = $result->fetch_array(MYSQLI_BOTH)){
				switch($settings['image_layout']){
					case 1:
						echo "<div class='album_item' style='margin: 15px;'><div class='ieshadow1'><div class='protectfromblur'>";
						if(isset($page) && $page=='admin')
              echo "<a href='{$_SERVER['PHP_SELF']}?".htmlentities($_SERVER['QUERY_STRING'])."&amp;view={$images['auth_key']}'><img src='../image.php?thumb={$images['image']}&amp;size=$size' alt=''/></a>";
            else
              echo "<a href='https://".$_SERVER['SERVER_NAME']."/babyblog/fotoalbum/$album_id/view/{$images['auth_key']}'><img src='../image.php?thumb={$images['image']}&amp;size=$size' alt=''/></a>";
						echo "</div></div></div>";		
					break;
					
					case 2:
					//bekijk image als popup
					break;
				}
			}
			echo "</div><div class='nav_bottom'>";
			$max_page_numbs = 3; //daarna krijg je ...
			
			for($i=1; $i<=$pages; $i++){
				if($i != $pagenum){
          if(isset($page) && $page=='admin')       
					  echo "<a href='{$_SERVER['PHP_SELF']}?section=photoalbum&amp;album_id={$_GET['album_id']}&pagina=$i'><span class='number'>$i</span></a>";
          else
            echo "<a href='https://".$_SERVER['SERVER_NAME']."/babyblog/fotoalbum/$album_id/pagina/$i'><span class='number'>$i</span></a>";
				}else{
					echo "<span class='number'>$i</span>";
				}
			}
			
			
		}
		?>
        </div>
</div>



