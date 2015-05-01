<h1>Nastavení</h1>

<h2>Vzhled</h2>

<h3>Aktuální vzhled:</h3>
<?php
if (isSet($_POST["posted"])) {
  $data["template"] = $_POST["template"];

  if (isSet($_POST["template_settings"]))
    $data["template_settings"] = $_POST["template_settings"];

  $save_template = save_settings($data);
  if ($save_template === true) {
    echo "<p class=\"success\">Vzhled webu byl úspěšně změněn.</p>";
    $template = get_template_info();
  }
  else
    echo "<p class=\"error\">Vzhled nebyl změněn.</p>";
}

// úvodní zobrazení
echo "<div class=\"template current\">";
echo "<a href=\"".$lrs["address"]."\" target=\"_blank\"><img src=\"".$template["path"].$template["thumbnail"]."\"></a>";
  
echo "<p>".$template["name"];
echo "<br>autor: ".$template["autor"];
if (!empty($template["url"])) { echo " (<a href=\"".$template["url"]."\" target=\"_blank\">url</a>)"; }
echo "<br><span class=\"desc\">".$template["description"]."</span>";

if (!empty($template["release-date"]))
  echo "<br><span style=\"font-size: 0.9em;\">vydán: ".date("j. n. Y", strtotime($template["release-date"]))."</span>";

if (!empty($template["license"])) {
  $license = (strpos($template["license"], "http://") === false ? $template["path"].$template["license"] : $template["license"]);
  echo "<br><a href=\"".$license."\" style=\"font-size: 0.9em;\">licence</a>";
}

if (file_exists($template["path"]."settings.php"))
  echo "<br><a onclick=\"show_hide('template_settings', true)\" style=\"cursor: pointer;\">nastavení &raquo;</a>";

echo "</p></div>";
  
if (file_exists($template["path"]."settings.php")) {
  echo "<div id=\"template_settings\"".(!isSet($_POST["template_settings_posted"]) ? "style=\"display: none;\"" : null).">";

  // ukládání nastavení
  if (isSet($_POST["template_settings_posted"])) {
    $data["template_settings"] = http_build_query($_POST["settings"]);

    $update = save_settings($data);
    if ($update == true) echo "<p class=\"success\">Nastavení vzhledu bylo úspěšně změněno.</p>";
    else echo "<p class=\"error\">Nastavení vzhledu nebylo změněno.</p>";
  }
  
  // úvodní zobrazení
  parse_str(urldecode(array_shift(get_settings("template_settings", "row"))), $settings);
  include ($template["path"]."settings.php");

  echo "</div>";
}


echo "<h3 class=\"cleaner\">Volitelné vzhledy:</h3>";

$template = null;
$slozka = "../vzhledy/";
$class = "left";
$i = 0;

$glob = (function_exists("glob") ? glob($slozka."*", GLOB_ONLYDIR) : glob_alternative($slozka, null, GLOB_ONLYDIR));

foreach ($glob as $dir) {
  $template = null;
  $name = strtolower(str_replace($slozka, null, $dir));
  if ($name == $lrs["current_template"]) continue;
  $i++;
  
  include ($dir."/info.php");
  
  $url = (!empty($template["preview"]) ? $template["preview"] : LETTERS_WEB_URL."/soubory/vzhledy/".$template["name"].".png");
  
  echo "<div class=\"template box ".$class."\">";
  echo "<a href=\"".$url."\" target=\"_blank\"><img src=\"".$dir."/".$template["thumbnail"]."\"></a>";
  echo "<p>".$template["name"];
  echo "<br>autor: ".(!empty($template["url"]) ? "<a href=\"".$template["url"]."\" target=\"_blank\">".$template["autor"]."</a>" : $template["autor"]);
  echo "<br><span class=\"desc\">".$template["description"]."</span>";

  echo "</p><form method=\"post\" style=\"float: right;\"><input type=\"hidden\" name=\"template\" value=\"".$template["name"]."\">";
  if (isSet($template["settings"])) echo "<input type=\"hidden\" name=\"template_settings\" value=\"".$template["settings"]."\">";
  echo "<input type=\"submit\" name=\"posted\" value=\"Aktivovat\"></form></div>";
  
  if ($class == "left")
    $class = "right";
  else {
    $class = "left";
    echo "<br class=\"cleaner\">";
  }
}

if ($i == 0) echo "<p class=\"info\">Není k dispozici žádný vzhled.</p>";
?>