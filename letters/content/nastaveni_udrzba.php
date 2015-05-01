<h1>Nastavení</h1>

<h2>Údržba</h2>

<h3>Vymazat paměť cache:</h3>
<p class="small_label">vymaže cache pluginů a cache čtečky novinek na nástěnce</p>

<?php
include (FCE_DIR."delete_dir.php");

if (isSet($_POST["delete_cache"])) {
  // mazání cache RSS čtečky z nástěnky
  $del1 = (file_exists("scripts/rss_reader.cache.xml") ? unlink("scripts/rss_reader.cache.xml") : true);
  
  // mazání obsahu složky cache
  $del2 = delete_dir("../cache", false);
  
  if ($del1 == true and $del2 == true) echo "<p class=\"success\">Paměť cache byla úspěšně smazána.</p>";
  else echo "<p class=\"error\">Paměť cache nebyla smazána.</p>";
}
?>

<form method="post">
<input type="submit" name="delete_cache" value="Vymazat cache">
</form>


<h3>MySQL příkaz:</h3>
<?php
if (isSet($_POST["prikaz_posted"])) {
  $prikaz = stripslashes($_POST["prikaz"]);
  $prikaz = mysql_query($prikaz);

  if ($prikaz === true) echo "<p class=\"success\">Příkaz byl úspěšně proveden.</p>";
  else echo "<p class=\"error\">Příkaz nebyl proveden.<br>".mysql_error()."</p>";
}
?>

<p class="small_label">zadejte pouze čistý příkaz bez uvozovek</p>
<form method="post">
<input type="text" name="prikaz" size="70" class="input"><br>
<input type="submit" name="prikaz_posted" value="Odeslat příkaz">
</form>