<h3>oEmbed:</h3>

<?php
// ukládání nastavení
if (isSet($_POST["oembed_posted"])) {
  include_once (FCE_DIR."delete_dir.php");
  @delete_dir("../cache/oembed", false);   // vymaže cache, protože obsahovala kódy pro původně nastavenou šířku
   
  $update = save_settings(array("oembed_width" => $_POST["oembed_width"]));
  if ($update === true) echo "<p class=\"success\">Nastavení bylo úspěšně uloženo.</p>";
  else echo "<p class=\"error\">Nastavení nebylo uloženo.</p>";
}

// mazání cache
if (isSet($_POST["oembed_delete_cache"])) {
  include_once (FCE_DIR."delete_dir.php");
  $delete = delete_dir("../cache/oembed", false);
  if ($delete == true) echo "<p class=\"success\">Paměť cache byla úspěšně smazána.</p>";
  else echo "<p class=\"error\">Paměť cache nebyla smazána.</p>";
}


// úvodní zobrazení
$set = get_settings("oembed_width");
?>

<form method="post">
<p>
Maximální velikost vkládaných objektů:<br>
<input type="number" name="oembed_width" value="<?php echo $set["oembed_width"]; ?>" size="5" step="25" required>
</p>

<input type="submit" name="oembed_posted" value="Uložit">

<p>
<input type="submit" name="oembed_delete_cache" value="Vymazat cache oEmbed">
</p>
</form>