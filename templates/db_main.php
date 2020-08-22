<div class='systeem'>
<p><b>Export</b></p>
<form method="post" enctype="multipart/form-data" name="set_db" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<table class='editform'>
<tr><td><input type="hidden" name="set_db" value="set_db" /></td></tr>
<tr><td class='fields'></td></tr>
<tr><td class='fields'>Selecteer hier de tabel(len): </div></td></tr>
<tr><td>
    <table><tr><td valign="top">
    <select name='db_tables' multiple="multiple" size='15'>
    <?=
    $query=mysql_list_tables("deb4597_blog");
    
    $i=1;
    while($table_name = mysql_fetch_array($query)){
        $name=mysql_tablename($query,$i);
        echo "<option value='".$name."'>$name</option>";
        $i++;
    }?>
    </select>
    </td>
    <td valign="top">
    <div class='holder'>
    <b>Content</b><br />
    <input type='radio' name='contents' value='1' />Alleen data<br/>
    <input type='radio' name='contents' value='2' />Alleen structuur<br/>
    <input type='radio' name='contents' value='3' />Data en structuur<br/>
    </div>
    <div class='holder'>
    <b>Opmaak</b><br />
    <input type='radio' name='db_type' value='1' />Tekst<br/>
    <input type='radio' name='db_type' value='2' />GZIP<br/>
    <input type='radio' name='db_type' value='3' />SQL<br/>
    <input type='radio' name='db_type' value='4' />CSV<br/>
    </div>
    <div class='holder'>
    <b>Extra statements toevoegen</b><br />
    <input type="checkbox" name='drop_prod' value=''/>DROP TABLE / VIEW / PROCEDURE / FUNCTION <br />
    <input type="checkbox" name='create_prod' value=''/>CREATE PROCEDURE / FUNCTION<br />
     <input type="checkbox" name='not_exists' value=''/>CREATE TABLE IF NOT Exists<br />
     <input type="checkbox" name='auto_incr' value=''/>CREATE TABLE AUTO_INCREMENT<br />
     </div>
    </td></tr></table>
</td>
</tr>
<tr><td class='fields'><input type="submit" class="submit" value="Exporteren" <?=$disabled?> /></td></tr>
</table>

<p><b>Import</b></p>
<form method="post" enctype="multipart/form-data" name="import" action="<?=htmlentities($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'])?>" >
<tr><td><input type="hidden" name="import_db" value="import_db" /></td></tr>
<table class='editform'>
<tr><td class='fields'></td></tr>
<tr><td class='fields'>Karakterset van het bestand: <select id="charset_of_file" name="charset_import" size="1">
<option value="iso-8859-1">iso-8859-1</option>
<option value="iso-8859-2">iso-8859-2</option>
<option value="iso-8859-3">iso-8859-3</option>
<option value="iso-8859-4">iso-8859-4</option>
<option value="iso-8859-5">iso-8859-5</option>
<option value="iso-8859-6">iso-8859-6</option>
<option value="iso-8859-7">iso-8859-7</option>
<option value="iso-8859-8">iso-8859-8</option>
<option value="iso-8859-9">iso-8859-9</option>
<option value="iso-8859-10">iso-8859-10</option>
<option value="iso-8859-11">iso-8859-11</option>
<option value="iso-8859-12">iso-8859-12</option>
<option value="iso-8859-13">iso-8859-13</option>
<option value="iso-8859-14">iso-8859-14</option>
<option value="iso-8859-15">iso-8859-15</option>
<option value="windows-1250">windows-1250</option>
<option value="windows-1251">windows-1251</option>
<option value="windows-1252">windows-1252</option>
<option value="windows-1256">windows-1256</option>
<option value="windows-1257">windows-1257</option>
<option value="utf-16">utf-16</option>
<option value="utf-8" selected="selected">utf-8</option>
<option value="utf-7">utf-7</option>
 </select></td></tr>

<tr><td class='fields'><input type="file" name='db_file'  /> (Max. 8mb)</td></tr>
<tr><td class='fields'><input type="submit" class="submit" value="Importeren" <?=$disabled?> /></td></tr>
</table>
</form>
</div>

