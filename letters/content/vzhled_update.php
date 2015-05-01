<h1>Aktualizace</h1>

<?php
check_user2("admin", true);

if (isSet($_GET["downloaded"])) {
  echo "<p class=\"success\">Plugin byl úspěšně aktualizován.</p>";
  // else echo "<p class=\"error\">Plugin nebyl aktualizován.</p>";
}


// úvodní zobrazení
$glob = (function_exists("glob") ? glob(TEMPLATES_DIR."*", GLOB_ONLYDIR) : glob_alternative(TEMPLATES_DIR, "*", GLOB_ONLYDIR));

foreach ($glob as $addon) {
  $available[] = str_replace(TEMPLATES_DIR, null, $addon);
}

if (count($available) == 0)
  echo "<p class=\"info\">Nemáte nainstalovaný žádný vzhled.</p>";
else {
  $i = 0;
  foreach ($available as $alias) {
    include (TEMPLATES_DIR.$alias."/info.php");
  
    $data = remote_file_get_contents(LETTERS_WEB_URL."/misc/check_update.php?alias=".$template["alias"]."&t=vzhled");

    if ($data === false)
      echo "<h3>".$template["name"]."</h3><p style=\"font-size: 0.8em; font-style: italic; color: red;\">Aktualizace se nepodařilo ověřit.</p>";
    elseif ($data === null or empty($data))
      null;
    else {
      if ($template["version"] < $data) {
        echo "<h3>".$template["name"]."</h3><p>Je k dispozici novější verze (".$data."). <a href=\"scripts/download.php?f=".$template["alias"].".zip&t=vzhled&lrs_version=".$lrs["letters_version"]."\">Aktualizovat&hellip;</a></p>";
        $i++;
      }
      elseif ($template["version"] > $data) {
        echo "<h3>".$template["name"]."</h3><p>Používáte testovací verzi (".$template["version"].").</p>";
        $i++;
      }
    }
  }
  
  if ($i == 0) echo "<p class=\"info\">Všechny nainstalované vzhledy jsou aktuální.</p>";
}
?>