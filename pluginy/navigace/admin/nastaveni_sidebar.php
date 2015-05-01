<h3>Navigace:</h3>

<?php
if (isSet($_POST["navigace_posted"])) {
  $data["navigace_show_categories"] = (int)isSet($_POST["show_categories"]);
  $data["navigace_show_count"] = (int)isSet($_POST["show_count"]);
  $data["navigace_hide_empty"] = (int)isSet($_POST["hide_empty"]);

  $save = save_settings($data);
  if ($save === true) echo "<p class=\"success\">Nastavení bylo úspěšně uloženo.</p>";
  else echo "<p class=\"error\">Nastavení nebylo uloženo.</p>";
}


// úvodní zobrazení
$set = get_settings("group=navigace", "assoc");
?>

<form method="post">
<p>
<label for="show_categories">Zobrazovat kategorie:</label><br>
<input type="checkbox" id="show_categories" name="show_categories" value="1" <?php if ($set["navigace_show_categories"] == 1) echo "checked"; ?>>
</p>

<p>
<label for="show_count">Zobrazit počty článků v kategoriích:</label><br>
<input type="checkbox" id="show_count" name="show_count" value="1" <?php if ($set["navigace_show_count"] == 1) echo "checked"; ?>>
</p>

<p>
<label for="hide_empty">Skrýt prázdné kategorie:</label><br>
<input type="checkbox" id="hide_empty" name="hide_empty" value="1" <?php if ($set["navigace_hide_empty"] == 1) echo "checked"; ?>>
</p>

<input type="submit" value="Uložit" name="navigace_posted">
</form>