
<div class='crumble_path'><a href='<?=$_SERVER['PHP_SELF']?>?section=polls'>Polls</a> &raquo; poll toevoegen</div>
<form method="post" enctype="multipart/form-data" name="add_poll_form" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<input type="hidden" name="add_poll" value="add_poll" />
<table class='editform'>
<? if(!isset($_SESSION['question'])){?>
    <tr><td><b>Voeg de vraag toe hieronder</b> </td></tr>
    <tr><td class='fields'>Vraag: <? if(isset($error['question']))$error['question'];?> </td></tr>
    <tr><td class='fields'><input name="question" type="text" class='field <?if($error['question']){?>input_error<?}?>' value="<? if(isset($_POST['question'])) echo $_POST['question']?>" /> <br /><br /></td></tr>
<? }else{ ?>
	<tr><td><b>Antwoord(en)</b> </td></tr>
    <tr><td class='fields'>Antwoord: <? if(isset($error['answer']))$error['answer'];?> </td></tr>
	<tr><td class='fields'><input type='text' name="answer[]" class='field <?if(isset($error['answer'])){?>input_error<?}?>' value="" /> <a href='#' onclick="add();"><img src='../images/plus.png' alt=''/></a></td></tr>
<? }?>
    <tr><td>
    <div id='answer_input'>
    </div></td></tr>
    
    <tr><td>
    <div id='last_field'>
    </div></td></tr>
    
    <tr><td class='fields'><input type="submit" class="submit" value="Toevoegen" /></td></tr>
    </table>
</form>


