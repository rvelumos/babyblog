
<div class='crumble_path'><a href='<?=$_SERVER['PHP_SELF']?>?section=polls'>Polls</a> &raquo; '<? echo $question['poll_question']?>' bewerken</div>
    <form method="post" enctype="multipart/form-data" name="edit_question_form" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
    <? if(isset($msg['answer'])) echo $msg['answer'];?>
        <table class='editform'>
        <tr><td><input type="hidden" name="edit_question" value="edit_question" /><b>Vraag</b> </td></tr>
         <tr><td><? if(isset($msg['question'])) echo $msg['question']?></td></tr>
        <tr><td class='fields'>Vraag: <? if(isset($error['question'])) echo $error['question'];?> </td></tr>
        <tr><td class='fields'><input type="text" name="question" class='field' value="<? if(isset($question['poll_question'])) echo $question['poll_question']?>" /> <br /><br /></td></tr>
        <tr><td class='fields'><input type="submit" class="submit" value="Opslaan" /></td></tr>
        </table>
    </form>
    
    <form method="post" enctype="multipart/form-data" name="edit_answer_form" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
	<table class='editform'>
    <tr><td><b>Antwoord(en) <img src='../images/plus.png' alt='' onClick='add();'/></b> </td></tr>
     <tr><td><input type="hidden" name="edit_answer" value="edit_answer" /><? if(isset($msg['answer'])) echo $msg['answer']?></td></tr>   
    <? if($result->num_rows>0){ ?>
	<? while($answer = $result->fetch_array(MYSQLI_BOTH)){?>
    	<tr><td class='fields'><input type="hidden" name="hidden_id[]" value="<?=$answer['id']?>" />Antwoord: <? if(isset($error['answer']))echo $error['answer'];?> </td></tr>
		<tr><td class='fields'><input type='text' name="answer[]" class='field' value="<? if(isset($answer['poll_answer'])) echo $answer['poll_answer']?>" /> 
        <? if($this->isEditor() || $this->isAdmin()){?><a href="index.php?section=polls&amp;edit_poll=<?=$_GET['edit_poll']?>&amp;delete_answer=<?=$answer['id']?>"><img src='../images/cross.gif' alt='' /><? } ?></td></tr>
    <? }?>
    <? }else{ ?>
		<tr><td class='fields'><input type="hidden" name="new[]" value="antw" />Antwoord: <? if(isset($error['answer']))$error['answer'];?> </td></tr>
		<tr><td class='fields'><input type='text' name="answer[]" class='field' value="<?=$_POST['poll_answer']?>" /> <img src='../images/plus.png' alt='' onClick='add();'/></td></tr>
	<?}?>

    <tr><td>
    <div id='answer_input'>
    </div></td></tr>
    
    <tr><td>
    <div id='last_field'>
    </div></td></tr>
    
    <tr><td class='fields'><input type="submit" class="submit" value="Opslaan" /></td></tr>
    </table>
</form>

<form method="post" enctype="multipart/form-data" name="edit_status_form" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<table class='status'>
<tr><td class='fields'>Poll status</td></tr>
    <tr><td class='fields'><input type="hidden" name="edit_status" value="edit_status" /><select name='status' onchange="this.form.submit();">
 <? if(isset($question['status']) && $question['status']==0){?>
<option value='<?=$question['status']; ?>'>Inactief</option>
<?}elseif(isset($question['status']) && $question['status']==1){?>
<option value='<?=$question['status']; ?>'>Actief</option>
<?}?>
<option value=' '>&nbsp; </option>
<option value='0'>Inactief</option>
<option value='1'>Actief</option>
</select></td><td><? if(isset($error['status'])) echo $error['status'];?></td></tr></table><br /><br /><br /></form>


