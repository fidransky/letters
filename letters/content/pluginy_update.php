<h1>Aktualizace</h1>

<?php
check_user2("admin", true);

if (isSet($_GET["downloaded"])) {
  $alias = $_GET["downloaded"];
  include (PLUGINS_DIR.$alias."/info.php");
  
  if (file_exists(PLUGINS_DIR.$alias."/".$alias."_upgrade.php"))
    include (PLUGINS_DIR.$alias."/".$alias."_upgrade.php");
  
  $save = save_data(array("version" => $plugin["version"]), "pluginy", "alias='".$alias."'");
  if ($save === true) echo "<p class=\"success\">Plugin byl úspěšně aktualizován.</p>";
  else echo "<p class=\"error\">Plugin nebyl aktualizován.</p>";
}


// úvodní zobrazení
if (get_count("pluginy") == 0)
  echo "<p class=\"info\">Nemáte nainstalovaný žádný plugin.</p>";
else {
  $i = 0;
  foreach (get_data("name, alias, version", "pluginy", array("order" => "name, alias"), "assoc") as $plugin) {
    $data = remote_file_get_contents(LETTERS_WEB_URL."/misc/check_update.php?alias=".$plugin["alias"]."&t=plugin");

    if ($data === false)
      echo "<h3>".$plugin["name"]."</h3><p style=\"font-size: 0.8em; font-style: italic; color: red;\">Aktualizace se nepodařilo ověřit.</p>";
    elseif ($data === null or empty($data))
      null;
    else {
      if ($plugin["version"] < $data) {
        echo "<h3>".$plugin["name"]."</h3><p>Je k dispozici novější verze (".$data."). <a href=\"scripts/download.php?f=".$plugin["alias"].".zip&t=plugin&lrs_version=".$lrs["letters_version"]."\">Aktualizovat&hellip;</a></p>";
        $i++;
      }
      elseif ($plugin["version"] > $data) {
        echo "<h3>".$plugin["name"]."</h3><p>Používáte testovací verzi (".$plugin["version"].").</p>";
        $i++;
      }
    }
  }
  
  if ($i == 0) echo "<p class=\"info\">Všechny nainstalované pluginy jsou aktuální.</p>";
}
?>