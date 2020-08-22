<?php $sectie='blog'; ?>
<div class='blog_item'>
    <div class='blog_top'>
    	<div class='top_titel'><a href='https://<?=$_SERVER['SERVER_NAME']?>/babyblog/blog/post/<?=$blog_item['id'];?>'><?=$blog_item['title']?> </a>
        <?php if($page=='admin' && ($this->isEditor() || $this->isAdmin())){?>
        <a href="index.php?section=posts&amp;edit_item=<?=$blog_item['id']; ?>"><img src='../images/edit.png' alt='Bericht aanpassen' /></a>  <a href="index.php?section=posts&amp;delete_item=<?=$blog_item['id']; ?>"><img src='../images/cross.gif' alt='Bericht verwijderen' /></a>
        <?}?>
        </div><div class='top_datum'><i>Toegevoegd op <?=$blog_item['date_added']?> door: <?=$blog_item['uploader']?> - categorie: <?=ucwords($blog_item['category'])?></i>
    </div>

    <div class='blog_middle'>
        <div class='blog_content'>
            <div class='tekst'>
            	<?=stripslashes($blog_item['description']);?>
            </div>
        	<div class='image'>
                    <?
                    for($i=1; $i<=9; $i++){
                        if(isset($blog_item["image$i"]) && $blog_item["image$i"]!=''){
                            echo "<div class='ieshadow1'><div class='protectfromblur'><a href='https://{$_SERVER['SERVER_NAME']}/babyblog/".$blog_item["image$i"]."' rel='prettyPhoto'><img alt='' src='https://{$_SERVER['SERVER_NAME']}/babyblog/image.php?thumb=".$blog_item["image$i"]."&amp;size=$size'  /></a></div></div>";
                        }
                    }
                    ?>
    		</div>
            
            <?
            $nummer = $blog_item['id']; 
			

            
            ?>	
          <div class='reacties'>
            	<a href='https://<?=$_SERVER['SERVER_NAME']?>/babyblog/blog/post/<?=$blog_item['id'];?>'>Reacties: (<?=$this->countReactions($nummer,$sectie); ?>)</a>
            	</div>
                
                <div class='line'>
            	</div>
            
			<? if(isset($sub_level_0) && $sub_level_0=='blog' && $sub_level_1=='post'){
            	echo $this->getReactions($nummer,$sectie);
            
            }?>
            
        </div>
    </div>
</div>
</div>
