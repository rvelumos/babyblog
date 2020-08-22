<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <!-- copyright ronald v eijsden -->
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin gedeelte</title>
    <? if((isset($_GET['mode']) && $_GET['mode']=='upload_images') || isset($_GET['nieuw_item'])){?>
        <script language="javascript" type="text/javascript">
			var fields = 0;
			function add() {
				if (fields < 9) {
					document.getElementById('image_input').innerHTML += "<table><tr><td class='fields'>Afbeelding: </td></tr><td class='fields'><input type='file' class='field' name='afbeeldingen[]' value='' /><img src='images/plus.png' alt='' onClick='add();'/></td></tr></table>";
					fields += 1;
				} else {
					
					document.getElementById('last_field').innerHTML = "<br />Het maximum aantal velden is bereikt.";
					//document.form.add.disabled=true;
				}
			}
		</script>
    <?}?>
       <? if(isset($_GET['section']) && $_GET['section']=='polls'){?>
        <script language="javascript" type="text/javascript">
			var fields = 0;
			function add() {
				if (fields < 9) {
					document.getElementById('answer_input').innerHTML += "<table><tr><td class='fields'>Antwoord</td></tr><td class='fields'><input type='text' class='field' name='antwoord[]' value='' /></td></tr></table>";
					fields += 1;
				} else {
					
					document.getElementById('last_field').innerHTML = "<br />Het maximum aantal velden is bereikt.";
					//document.form.add.disabled=true;
				}
			}
		</script>
    <?}?>
    
    <script type="text/javascript" src="../js/tabcontent.js"></script>
	<script type="text/javascript" src="../js/jscolor.js"></script>
    <script type="text/javascript" src="../js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script> 
    <script type="text/javascript" language="JavaScript"> 
var cX = 0; var cY = 0; var rX = 0; var rY = 0; 
function UpdateCursorPosition(e){ cX = e.pageX; cY = e.pageY;} 
function UpdateCursorPositionDocAll(e){ cX = event.clientX; cY = event.clientY;} 
if(document.all) { document.onmousemove = UpdateCursorPositionDocAll; } 
else { document.onmousemove = UpdateCursorPosition; } 
function AssignPosition(d) { 
if(self.pageYOffset) { 
rX = self.pageXOffset; 
rY = self.pageYOffset; 
} 
else if(document.documentElement && document.documentElement.scrollTop) { 
rX = document.documentElement.scrollLeft; 
rY = document.documentElement.scrollTop; 
} 
else if(document.body) { 
rX = document.body.scrollLeft; 
rY = document.body.scrollTop; 
} 
if(document.all) { 
cX += rX; 
cY += rY; 
} 
d.style.left = (cX+10) + "px"; 
d.style.top = (cY+10) + "px"; 
} 
function HideText(d) { 
if(d.length < 1) { return; } 
document.getElementById(d).style.display = "none"; 
} 
function ShowText(d) { 
if(d.length < 1) { return; } 
var dd = document.getElementById(d); 
AssignPosition(dd); 
dd.style.display = "block"; 
} 
function ReverseContentDisplay(d) { 
if(d.length < 1) { return; } 
var dd = document.getElementById(d); 
AssignPosition(dd); 
if(dd.style.display == "none") { dd.style.display = "block"; } 
else { dd.style.display = "none"; } 
} 
//--> 
</script>
        <? if($settings['img_mode']==1){ ?>
    <script src="http://www.google.com/jsapi" type="text/javascript"></script>
		<script type="text/javascript" charset="utf-8">
			google.load("jquery", "1.6");
		</script>
        <script src="js/jquery.js" type="text/javascript" charset="utf-8"></script>
        <link rel="stylesheet" href="../css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
		<script src="../js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
		
        <script type="text/javascript" charset="utf-8">
	  $(document).ready(function(){
		$("a[rel^='prettyPhoto']").prettyPhoto();
	  });
  </script>
  	<? } ?>
<? if($settings['htmleditor']=='1'){?>
<script type="text/javascript"> 
tinyMCE.init({ 
        // General options 
        mode : "textareas", 
        theme : "advanced", 
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template", 
 
        // Theme options 
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect", 
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,preview,|,forecolor,backcolor", 
        //theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen", 
        //theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage", 
        theme_advanced_toolbar_location : "top", 
        theme_advanced_toolbar_align : "left", 
        theme_advanced_statusbar_location : "bottom", 
        theme_advanced_resizing : true, 
 
        // Skin options 
        skin : "o2k7", 
        skin_variant : "silver", 
 
        // Example content CSS (should be your site CSS) 
        content_css : "../css/example.css", 
 
        // Drop lists for link/image/media/template dialogs 
        template_external_list_url : "../js/template_list.js", 
        external_link_list_url : "../js/link_list.js", 
        external_image_list_url : "../js/image_list.js", 
        media_external_list_url : "../js/media_list.js", 
 
        // Replace values for the template plugin 
        template_replace_values : { 
                username : "Some User", 
                staffid : "991234" 
        } 
}); 
</script> 
<? }?>
<link href="../css/admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class='bg_holder'>
</div>
