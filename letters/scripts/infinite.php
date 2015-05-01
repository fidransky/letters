<?php
define("LETTERS_WEB_URL", "http://letters.cz");
include ("../../funkce/remote_file_get_contents.php");

$lrs["verze"] = $_GET["lrs"];
$url = $_GET["url"];
$data = remote_file_get_contents($url);

$xml = simplexml_load_string($data);

foreach ($xml->doplnek as $plugin) {
  if ($plugin->sidebar == "true") { $sidebar = "_sidebar"; }
  else { $sidebar = null; }
  
  if (substr($plugin->min_lrs, 0, 1) == "v") { $plugin->min_lrs = substr($plugin->min_lrs, 1); }    // přechod na nový formát verze Letters

  echo "<div class=\"doplnek plugin\">";
  echo "<a name=\"".$plugin->alias."\"></a>";
  echo "<h2>".$plugin->jmeno."</h2>";
  echo "<p>".$plugin->popis."</p>";

  echo "<ul class=\"meta\">";
    echo "<li class=\"link\"><a href=\"".LETTERS_WEB_URL."/pluginy/#".$plugin->alias.$sidebar."\" target=\"_blank\" title=\"zobrazit na webu Letters\">zobrazit</a> na webu Letters";

    if (empty($plugin->min_lrs) or $plugin->min_lrs <= $lrs["verze"]) { echo "<li class=\"download\"><a href=\"scripts/download.php?f=".$plugin->link."&t=plugin&lrs_version=".$lrs["verze"]."\" title=\"stáhnout plugin do Letters\">stáhnout</a>"; }
    else { echo "<li class=\"download\"><span style=\"color: black; font-size: 1em;\" title=\"plugin vyžaduje minimálně Letters verze ".$plugin->min_lrs."\">stáhnout</span>"; }
    if ($plugin->stazeno != 0) { echo "&nbsp;<span>staženo ".$plugin->stazeno."&times;</span>"; }

    if (!empty($plugin->verze) and $plugin->verze != "1.0") { echo "<li class=\"verze\"><span>verze:</span> ".$plugin->verze; }
    if ((!empty($plugin->kategorie) and $plugin->kategorie == "sidebar") or $sidebar == "true") { echo "<li class=\"kategorie\"><span>kategorie:</span> ".$plugin->kategorie; }
    if (!empty($plugin->vyzaduje)) { echo "<li class=\"vyzaduje\"><span>vyžaduje:</span> plugin <a href=\"letters.php?page=pluginy&co=katalog&search=".$plugin->vyzaduje."\">".$plugin->vyzaduje."</a>"; }
    if (!empty($plugin->autor)) { echo "<li class=\"autor\"><span>autor:</span> ".$plugin->autor; }
    if (!empty($plugin->url)) { echo "<li class=\"url\"><span>url projektu:</span> <a href=\"".$plugin->url."\" target=\"_blank\">".$plugin->url."</a>"; }
  echo "</ul>";
  echo "</div>";
}
?>