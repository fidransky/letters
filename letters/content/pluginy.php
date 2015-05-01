<h1>Pluginy</h1>

<?php
check_user2("admin", true);

function lrs_list_plugins_folder($hide_admin_plugins) {
  if (function_exists("glob")) $glob = glob(PLUGINS_DIR."*", GLOB_ONLYDIR);
  else $glob = glob_alternative(PLUGINS_DIR, "*", GLOB_ONLYDIR);
  
  if (empty($glob)) return false;
  
  $return = null;
  foreach ($glob as $dir) {
    $alias = str_replace(PLUGINS_DIR, null, $dir);

    if (get_count("pluginy", "alias='".$alias."'") == 0) {
      include (PLUGINS_DIR.$alias."/info.php");
      if ($hide_admin_plugins == true and preg_match("/admin/", $plugin["category"])) continue;

      $return .= "<option value=\"".$alias."\">".$plugin["name"];
    }
  }
  
  return $return;
}

function add_htaccess($alias) {
  $content = file_get_contents(PLUGINS_DIR.$alias."/".$alias.".htaccess");
  
  if (preg_match("/#LAST/", $content))
    $matched = false;
  else {
    $data = null;
    $matched = false;
    
    foreach (file("../.htaccess") as $row) {
      if ($matched == false and preg_match("/#LAST/", $row)) {
        $data .= $content.PHP_EOL;
        $matched = true;
      }
      $data .= $row;
    }
  }

  if ($matched) {
    $file = fopen("../.htaccess", "w");
    $write = fwrite($file, $data);
  }
  else {
    $file = fopen("../.htaccess", "a");
    $write = fwrite($file, PHP_EOL.$content);
  }
  fclose($file);
  
  return $write;
}

function delete_htaccess($block) {
  $skip = false;
  
  foreach (file("../.htaccess") as $row) {
    if ($skip) {
      if (preg_match("/^\s+$/", $row)) $skip = false;
      continue;
    }
    
    if (preg_match("/".preg_quote($block)."\s+/", $row)) {
      $skip = true;
      continue;
    }

    $data .= $row;
  }

  $file = fopen("../.htaccess", "w");
  $write = fwrite($file, $data);
  fclose($file);
  
  return $write;
}

function delete_rows($plugin, $file) {
  if (!file_exists($file)) return false;
  
  $data = file($file);
  $write = false;
  foreach ($data as $radek) {
    if (preg_match("/".preg_quote($plugin)."/", $radek)) $write = true;
    else $end_data .= $radek;
  }

  if ($write === true) {
    $soubor = fopen($file, "w");
    $zapis = fwrite($soubor, $end_data);
    fclose($soubor);

    return $zapis;
  }
  
  return true;
}



// aktualizace DB
foreach (get_data("alias", "pluginy") as $plugin) {
  // vymazání neexistujících pluginů z DB
  if (!file_exists(PLUGINS_DIR.$plugin["alias"]))
    @delete_data("pluginy", "alias='".$plugin["alias"]."'");
  
  // uložení existujících pluginů do pole
  else
    $plugins[] = $plugin["alias"];
}


// aktivace
if (isSet($_POST["aktivovat"])) {
  $id = $_POST["plugin"];
  list($kategorie, $jedinecny, $vyzaduje) = array_shift(get_data("category, `unique`, `requires`", "pluginy", array("where" => "id='".$id."'"), "row"));

  // kontrola vyžadovaných pluginů
  if (!empty($vyzaduje)) {
    $pocet = 0;
    $vyzaduje_arr = explode(", ", $vyzaduje);
    
    foreach ($vyzaduje_arr as $alias)
      $pocet += get_count("pluginy", "alias='".$alias."' AND active=1");

    if ($pocet != count($vyzaduje_arr)) {
      echo "<p class=\"error\">Plugin nebyl aktivován. Nejsou dostupné některé vyžadované pluginy (".$vyzaduje.").</p>";
      $continue = false;
    }
  }

  if (!isSet($continue)) {
    // kontrola ostatních pluginů z kategorie
    if (!empty($jedinecny)) {
      foreach (explode(", ", $jedinecny) as $alias)
        @save_data(array("active" => 0), "pluginy", "active=1 AND `unique` LIKE '%".$alias."%'");
    }

    $update = save_data(array("active" => 1), "pluginy", "id='".$id."'");
    if ($update === true) echo "<p class=\"success\">Plugin byl úspěšně aktivován.</p>";
    else echo "<p class=\"error\">Plugin nebyl aktivován.</p>";
  }
}

// deaktivace
elseif (isSet($_POST["deaktivovat"])) {
  $id = $_POST["plugin"];

  // deaktivace závislých pluginů
  $alias = array_shift(array_shift(get_data("alias", "pluginy", array("where" => "id='".$id."'"))));
  @save_data(array("active" => 0), "pluginy", "requires LIKE '%".$alias."%'");

  $update = save_data(array("active" => 0), "pluginy", "id='".$id."'");
  if ($update === true) echo "<p class=\"success\">Plugin byl úspěšně deaktivován.</p>";
  else echo "<p class=\"error\">Plugin nebyl deaktivován.</p>";
}



// úvodní zobrazení
if (get_count("pluginy") == 0)
  echo "<p class=\"info\">Nemáte nainstalovaný žádný plugin.</p>";
else {
  (bool)$hide_admin_plugins = array_shift(get_settings("hide_admin_plugins", "row"));
?>

<table id="pluginy">
<thead><tr>
  <th style="width: 130px;">jméno</th>
  <td style="width: 40px; padding: 5px; text-align: center;">verze</td>
  <td style="min-width: 400px;">popis</td>
  <td style="width: 90px; padding: 5px; text-align: center;">aktivita</td>
  <td style="min-width: 120px;">kategorie</td>
</tr></thead>

<?php
foreach (get_data("id, name, version, description, category, active", "pluginy", array("order" => "name, alias"), "assoc") as $plugin) {
  if ($hide_admin_plugins == true and preg_match("/admin/", $plugin["category"])) continue;
  
  $submit = ($plugin["active"] == 0 ? "<input type=\"submit\" name=\"aktivovat\" value=\"aktivovat\">" : "<input type=\"submit\" name=\"deaktivovat\" value=\"deaktivovat\">");

  echo "<tr style=\"height: 40px; vertical-align: middle;\">";
  echo "<td>".$plugin["name"]."</td>";
  echo "<td style=\"text-align: center;\">".$plugin["version"]."</td>";
  echo "<td style=\"min-width: 400px;\">".$plugin["description"]."</td>";
  echo "<td style=\"text-align: center;\"><form method=\"post\" style=\"display: inline;\"><input type=\"hidden\" name=\"plugin\" value=\"".$plugin["id"]."\">".$submit."</form></td>";
  echo "<td>".$plugin["category"]."</td>";
  echo "</tr>";
}
?>
</table>

<?php } ?>


<h2 id="instalace">Instalace</h2>

<?php
if (isSet($_POST["instalovat"])) {
  $alias = $_POST["plugin"];
  $show_readme = true;
  
  if (file_exists(PLUGINS_DIR.$alias."/info.php")) {
    $plugin = array();
    include (PLUGINS_DIR.$alias."/info.php");

    include_once (FCE_DIR."install_plugin.php");
    include_once (FCE_DIR."add_menu.php");
    $install = install_plugin($plugin);

    if ($install === true) {
      if (file_exists(PLUGINS_DIR.$alias."/".$alias."_install.php"))
        include (PLUGINS_DIR.$alias."/".$alias."_install.php");
        
      if (file_exists(PLUGINS_DIR.$alias."/".$alias.".htaccess"))
        add_htaccess($alias);

      echo "<p class=\"success\">Plugin byl úspěšně nainstalován.</p>";

      if ($template["main-menu"] and preg_match("/page_([^,]+)/", $plugin["category"]) and isSet($mainmenu_link))
        echo "<form method=\"post\" action=\"letters.php?page=nastaveni&co=mainmenu\" style=\"display: inline;\"><input type=\"hidden\" name=\"type\" value=\"page\"><input type=\"hidden\" name=\"page\" value=\"".$plugin["name"]."; ".$mainmenu_link."\"><input type=\"submit\" name=\"mainmenu_posted\" value=\"Přidat odkaz\"></form> na ".$plugin["name"]." do main-menu<br>";

      if (preg_match("/sidebar/", $plugin["category"])) {
        $mysql_insert_id = mysql_result(mysql_query("SELECT MAX(`id`) FROM `".DB_PREFIX."pluginy`"), 0);
        mysql_query("UPDATE `".DB_PREFIX."nastaveni` SET `value`=CONCAT(`value`, ', ".$mysql_insert_id."') WHERE `name`='sidebar_box_order'");
      }

      if ($show_readme == true and file_exists(PLUGINS_DIR.$alias."/readme.html"))
        echo "<a href=\"".PLUGINS_DIR.$alias."/readme.html\" target=\"_blank\"><button>Zobrazit nápovědu</button></a><br>";
    }
    else
      echo "<p class=\"error\">Plugin nebyl nainstalován.</p>";
  }
  else
    echo "<p class=\"error\">Plugin nebyl nainstalován.</p>";
}


// instalace - formulář pro výběr
$list_plugins_folder = lrs_list_plugins_folder($hide_admin_plugins);
if ($list_plugins_folder == false)
  echo "<p>Žádný plugin k instalaci.</p>";
else {
?>

<form method="post" action="letters.php?page=pluginy&co=prehled#instalace">
<p>
<select name="plugin">
  <?php echo $list_plugins_folder; ?>
</select>

<input type="submit" name="instalovat" value="Instalovat">
</p>
</form>

<?php } ?>


<h2 id="odinstalace">Odinstalace</h2>

<?php
if (isSet($_POST["odinstalovat"])) {
  $id = $_POST["plugin"];
  $data = get_data("name, alias", "pluginy", array("where" => "id='".$id."'"), "row");
  if ($data !== false) {
    list($name, $alias) = array_shift($data);

    // mazání menu a podmenu
    delete_rows($name, "content/menu.txt");
    delete_rows($name, "content/podmenu.txt");
    delete_rows($alias, "content/defaults.php");
  
    // mazání příslušných bloků z htaccess
    if (file_exists(PLUGINS_DIR.$alias."/".$alias.".htaccess")) {
      $content = file_get_contents(PLUGINS_DIR.$alias."/".$alias.".htaccess");
      preg_match_all("/#(LAST|) .+/", $content, $matches);
      
      foreach ($matches[0] as $block)
        delete_htaccess(trim($block));
    }
    
    // mazání nastavení z databáze
    @mysql_query("DELETE FROM ".DB_PREFIX."nastaveni WHERE `group`='".$alias."'");

    // případné další mazání dat 
    if (file_exists(PLUGINS_DIR.$alias."/".$alias."_uninstall.php")) {
      include_once (FCE_DIR."delete_dir.php");
      include (PLUGINS_DIR.$alias."/".$alias."_uninstall.php");
    }

    $delete = delete_data("pluginy", "id='".$id."'");
    if ($delete === true) echo "<p class=\"success\">Plugin byl úspěšně odinstalován.</p>";
    else echo "<p class=\"error\">Plugin nebyl odinstalován.</p>";
  }
  else
    echo "<p class=\"error\">Plugin nebyl odinstalován.</p>";
}

// odinstalace - formulář pro výběr
if (get_count("pluginy") == 0)
  echo "<p>Žádný plugin k odinstalaci.</p>";
else {
?>

<form method="post" action="letters.php?page=pluginy&co=prehled#odinstalace">
<p>
<select name="plugin">
<?php
foreach (get_data("id, name, alias, category", "pluginy", array("order" => "name, alias"), "assoc") as $plugin) {
  if ($hide_admin_plugins == true and preg_match("/admin/", $plugin["category"])) continue;
  
  if (file_exists(PLUGINS_DIR.$plugin["alias"]))
    echo "<option value=\"".$plugin["id"]."\">".$plugin["name"];
}
?>
</select>

<input type="submit" name="odinstalovat" value="Odinstalovat">
</p>
</form>

<?php } ?>