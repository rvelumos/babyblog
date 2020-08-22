<p><b>Overzicht systeemgebruik</b></p>
<table class='editform'>
    <tr><td class='fields'>Mappen: <?=$error['naam'];?> </td></tr>
	<tr><td class='fields'><input name="naam" class='field <?if($error['naam']){?>input_error<?}?>' value="" /> </td></tr>
<? if($error['status']){ ?><tr><td class='fields'></td></tr> <? }?>
<tr><td class='fields'>Categorie actief:</td></tr>
</td><td></td></tr>
</td></tr>
    </table>