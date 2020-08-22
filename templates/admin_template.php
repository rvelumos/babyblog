<div class='center_float'>
<div class='main_container'>
<div class='top_container'>

</div>
<div class='left_container'>
<ul class='menu'>
<li><a href='index.php?section=posts'>Blog posts</a></li>
<li><a href='index.php?section=photoalbum'>Foto album</a></li>
<li><a href='index.php?section=polls'>Polls</a></li>
<li><a href='index.php?section=categories'>Categorie&euml;n</a></li>
<li><a href='index.php?section=tags'>Tags</a></li>
<li><a href='index.php?section=stats'>Stats</a></li>
<? if($admin->isAdmin() || $admin->testMode()){ ?>
    <li><a href='index.php?section=security'>Beveiliging</a></li>
    <li><a href='index.php?section=settings'>Instellingen</a></li>
  <!--  <li><a href='index.php?section=system'>Systeem</a></li>-->
    <li><a href='index.php?section=log'>Log</a></li>
<? } ?>
<li><a href='index.php?logout'>Uitloggen</a></li>
</ul>
</div>
<div class='right_container'>

