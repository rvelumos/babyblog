    <div class='album_top'>
    	<div class='form_titel'>Album overzicht
        </div>     
</div>
<? 
if($page=='admin'){
if($this->isEditor() || $this->isAdmin()){?>
<div class='actions'>
<div class='add'><p><a href='index.php?section=photoalbum&amp;add_album'><img src='../images/plus.png' alt=''/> Album toevoegen</a></p></div>
</div>
<? }
}
?>
        <div class="<?=$classes["class_album"]?>">
            <div class='tekst'>
            <?if(isset($msg['empty'])) echo $msg['empty'];?></div>
            
            <div class='album'>
					<?
					
                    while($album = $result->fetch_array(MYSQLI_BOTH)){
						$album_id = $album['id'];
                        echo "<div class='album_item' style='width:210px;'>";
                        if($page=='admin')
                          echo "<a href='{$_SERVER['PHP_SELF']}?section=photoalbum&amp;album_id={$album['id']}'>";
                         else
                           echo "<a href='https://".$_SERVER['SERVER_NAME']."/babyblog/fotoalbum/{$album['id']}'/>";
                        if($album['thumb']!=''){
                          echo "<img src='../{$album['thumb']}' alt='' />";	  
                          //echo "<img src='image.php?thumb={$album['thumb']}&amp;size=$size' alt='' />";	
                        }else{
							$wpadding = $album_size - 100;
							$hpadding = $album_size - 90;
                            echo "<img src='../images/empty_av.jpg' alt='' style='padding:".$hpadding."px ".$wpadding."px;'/>";	
                        }
						echo "<br />".wordwrap($album['name'],50,"<br/>"). "(".$this->countAlbumImages($album_id).")</a>";
            
                        echo "</div>";	
                        
                    }
                ?>
             </div>  
        </div>

