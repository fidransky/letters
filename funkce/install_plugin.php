<?php
function install_plugin($plugin) {
  global $lrs;

  // ověření minimální verze Letters
  if (!empty($plugin["min_lrs"]) and ($lrs["letters_version"] < $plugin["min_lrs"])) {
    echo "<p class=\"error\">Plugin nebyl nainstalován. Jsou vyžadována minimálně Letters verze ".$plugin["min_lrs"].".</p>";
    return false;
  }

  // ověření jedinečnosti
  if (!empty($plugin["unique"])) {
    $data = array();
    foreach (explode(", ", $plugin["unique"]) as $alias) {
      $merge = get_data("name", "pluginy", array("where" => "active=1 AND `unique` LIKE '%".$alias."%'"), "row");
      if (!empty($merge)) $data = array_merge($data, $merge);
    }
    
    if (!empty($data)) {
      if (count($data) == 1)
        $plugins = "plugin ".$data[0][0];
      else {
        foreach ($data as $plugin)
          $plugins[] = $plugin[0];
        $plugins = "pluginy ".implode(", ", $plugins);
      }

      echo "<p class=\"error\">Doplněk potřebuje ke svému běhu deaktivovat ".$plugins.".</p>";
      return false;
    }
  }

  // ověření vyžadovaných pluginů
  if (!empty($plugin["requires"])) {
    foreach (explode(", ", $plugin["requires"]) as $alias)
      if (get_count("pluginy", "alias='".$alias."'") == 0) $required[] = $alias;
    
    if (!empty($required)) {
      echo "<p class=\"error\">Plugin potřebuje ke svému běhu nainstalované pluginy ".implode(", ", $required).".</p>";
      return false;
    }
  }

  $plugin["alias"] = strtolower(bez_diakritiky($plugin["name"]));
  $plugin["active"] = 1;
  
  // ukládání nastavení pluginu
  if (array_key_exists("settings", $plugin)) {
    parse_str($plugin["settings"], $settings);
  
    foreach ($settings as $key => $value)
      $values[] = "('".$plugin["alias"]."_".$key."', '".$value."', '".$plugin["alias"]."')";
      
    $save_settings = mysql_query("INSERT INTO `".DB_PREFIX."nastaveni` (`name`, `value`, `group`) VALUES ".implode(", ", $values));
    if ($save_settings === false) {
      echo "<p class=\"error\">Plugin nebyl nainstalován. Nastavení nebyla uložena.</p>";
      return false;
    }
  
    unset($plugin["settings"]);
  }
    
  // instalace pluginu
  $save = save_data($plugin, "pluginy");
  if ($save === true) return true;

  return false;
}
?>