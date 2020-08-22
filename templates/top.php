<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <META NAME="AUTHOR" CONTENT="R.p. van Eijsden">
    <META NAME="COPYRIGHT" CONTENT="&copy; Babyblog Ronald-designs 2011 - <?=date("Y");?>">
    <META NAME="DESCRIPTION" CONTENT="Wesley's persoonlijke blog">
    <META NAME="ROBOTS" CONTENT="NONE"> 
    <META NAME="GOOGLEBOT" CONTENT="NOARCHIVE"> 
    
    <title><?php echo $title;?></title>
    <link href="https://<?=$_SERVER['SERVER_NAME']?>/babyblog/css/css.css" rel="stylesheet" type="text/css" />
	<script src="https://<?=$_SERVER['SERVER_NAME']?>/babyblog/js/incl_js.js" type="text/javascript"></script>
    
    <?php if($settings['img_mode']==1){ ?>
    <script src="http://www.google.com/jsapi" type="text/javascript"></script>
		<script type="text/javascript" charset="utf-8">
			google.load("jquery", "1.6");
		</script>
        <script src="https://<?=$_SERVER['SERVER_NAME']?>/babyblog/js/jquery-1.4.4.min.js" type="text/javascript" charset="utf-8"></script>
        <link rel="stylesheet" href="https://<?=$_SERVER['SERVER_NAME']?>/babyblog/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
		<script src="https://<?=$_SERVER['SERVER_NAME']?>/babyblog/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
		
        <script type="text/javascript" charset="utf-8">
	  $(document).ready(function(){
		$("a[rel^='prettyPhoto']").prettyPhoto();
	  });
  </script>
  	<?php } ?>
</head>
<body>
<div class='bg_holder'>
</div>
<div class='main_container'>
<div class='extra_container'>
    <div class='top_container'>
    	<div class='menu_content'><?=$content->getMenuItems();?><?php if($settings['searchfield']==1) include('zoekform.php'); ?></div>
    </div>
    <div class='left_container'>
        <div class='content_items'>
   		<?php echo $content->loadContent();?>
    	</div>
    </div>
    
   
    <div class='right_container'>
    	<?php 
        echo $content->loadRightMenu();
      ?>
    </div>

</div>
</div>
<div class='bottom_content'>

</div>
</body>
</html>