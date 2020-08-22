<div class='crumble_path'><a href='https://<?=$_SERVER['SERVER_NAME']?>/babyblog/fotoalbum/'>Albums</a> &raquo; <a href='https://<?=$_SERVER['SERVER_NAME']?>/babyblog/fotoalbum/<?=$image_details['album_id']?>'><?=$album['name']?></a> &raquo; <?=$image_details['name']?></div>
<? 
if(isset($page) && $page=='admin'){
if($this->isEditor() || $this->isAdmin()){?>
<div class='actions'>
<div class='add'><p><a href='index.php?section=photoalbum&amp;album_id=<?=$_GET['album_id']?>&amp;view=<?=$_GET['view']?>&amp;mode=edit_image'><img src='../images/edit.png' alt=''/> Foto bewerken</a></p></div><div class='remove'><p><a href='index.php?section=photoalbum&amp;album_id=<?=$_GET['album_id']?>&amp;view=<?=$_GET['view']?>&amp;mode=delete_image'><img src='../images/cross.gif' alt=''/> Foto verwijderen</a></p></div>
</div>
<?
}
}
?>
<div class='<?=$classes["class_album"]?>'>
        <div class='tekst'>
        <? if(isset($msg['invalid']))$msg['invalid'];?></div>
		<div class='album_items'>
            <div class='image_content'><img src='https://www.ronald-designs.nl/babyblog/images/plak.png' class='tape_top'/>
				<div class="image_holder">
          <img src='https://www.ronald-designs.nl/babyblog/<?=$image_details['image']?>' alt=""/><br /><small><a href='https://www.ronald-designs.nl/babyblog/<?=$image_details['image']?>' rel="prettyPhoto">Bekijk afbeelding op volledige grootte</a></small><br /><br />
                <!--<img src='../image.php?image_details=<?=$image_details['image']?>&amp;width=<?=$image_details['width']?>&amp;height=<?=$image_details['height']?>&page=<?=$page?>' alt=""/><br /><small><a href='../<?=$image_details['image']?>' rel="prettyPhoto">Bekijk afbeelding op volledige grootte</a></small><br /><br />
                -->
                <?
				 $nummer = $image_details['id']; 
				 if($image_details['description']!=""){
						echo "<div class='omschrijving'><b>Omschrijving:</b><br />".$image_details['description']."</div>";
				}

				if($settings['reactions']==1 && $_SESSION['admin_name']==""){?>
				
					<div class='reacties'>
					Reacties: (<? $this->countReactions($nummer,$sectie); ?>)
					</div>
					
				
					<div class='line'>
					</div>
				<?
			//		$this->getReactions($nummer,$sectie);
				}
			
				?>
                </div>
                
                <? if($settings['show_image_info']==1){ ?>
                    <div class="image_details">
                    
                    <?

                    echo $this->imageViewed($auth_key);?>x bekeken
                        <p><b>Informatie:</b></p>
                        <?=$image_details['date_added']?><br />
                        <?=$image_details['width']?> x <?=$image_details['height']?> pixels<br />
						            <?=$image_details['filesize']?> bytes<br />
                        <?
                         if($settings['show_camera_data']==1 &&true==false){
                         $image_path = $image_details['image'];
                         $camera_data = $this->image_camera_data($image_path);
                         ?><p></p>
                          <b>Camera:</b> <?=$camera_data['model']?><br />
                          <b>Sluitertijd:</b> <?=$camera_data['exposure']?><br />
                          <b>Diafragma:</b> <?=$camera_data['aperture']?><br />
                          <b>Brandpuntsafstand:</b> <?=$camera_data['bpa']?><br />
                          <b>ISO:</b> <?=$camera_data['iso']?><br />
                        <?}?>
                    </div>   
                <?}?>         
            
            </div>
            </div>
</div>



