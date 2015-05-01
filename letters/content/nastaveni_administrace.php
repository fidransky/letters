<h1>Nastavení</h1>

<h2>Administrace</h2>

<?php
if (isSet($_POST["administrace_posted"])) {
  $data["hide_admin_plugins"] = (int)isSet($_POST["hide_admin_plugins"]);
  
  include_plugin_admin(false, array("action" => "save"), $data);
  
  $save_settings = save_settings($data);
  if ($save_settings === true) echo "<p class=\"success\">Nastavení bylo úspěšně uloženo.</p>";
  else echo "<p class=\"error\">Nastavení nebylo uloženo.</p>";
}

// úvodní zobrazení
list($hide_admin_plugins) = get_settings("hide_admin_plugins", "row");
?>

<form method="post">
<!--
Stránka po příhlášení
Pořadí položek menu
Zobrazit logo letters
-->

<p>
  <label for="hide_admin_plugins">Skrýt základní doplňky:</label><br>
  <input type="checkbox" name="hide_admin_plugins" id="hide_admin_plugins" value="1" <?php if ($hide_admin_plugins == 1) echo "checked"; ?>>
</p>

<?php
/*
$categories = array();

foreach (get_data("category", "pluginy", array(), "row") as $category)
  $categories = array_merge($categories, explode(", ", $category[0]));

foreach ($categories as $i => $category) {
  if (preg_match("/page_/", $category))
    unset($categories[$i]);
}

var_dump(array_unique($categories));
*/
?>

<?php include_plugin_admin(false, array("action" => "show")); ?>

<input type="submit" name="administrace_posted" value="Uložit">
</form>