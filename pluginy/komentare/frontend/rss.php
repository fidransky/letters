<?php
header("Content-Type: text/xml; charset=utf-8");

// konfigurace databáze
(@include ("../../../letters/config.php")) or exit ("<p>The page you requested could not be loaded due to technical issues.<br><small>missing the \"config.php\" file</small></p>");

// definice konstant a vkládání funkcí
define("FCE_DIR", "../../../funkce/");
define("PLUGINS_DIR", "../../");
define("TEMPLATES_DIR", "../../../vzhledy/");

include ("../../../includes.php");


// generování RSS
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";  // kvůli short_open_tag je lepší vypsat přes PHP
?>
<rss version="2.0">

<channel>
<title><?=$lrs["title"]?></title>
<link><?=$lrs["address"]?></link>
<description><?=$lrs["description"]?></description>
<language>cs</language>
<generator>Letters <?=$lrs["letters_version"]?></generator>

<?php
list($order) = get_settings("comments_order", "row");

$where = "stav='schvaleny'";
if (isset($_GET["clanek"])) {
  list($id) = array_shift(get_data("id", "clanky", array("where" => "alias='".mysql_real_escape_string($_GET["clanek"])."'"), "row"));
  $where .= " AND id_clanku='".$id."'";
}

$data = get_data("k.id, k.jmeno, k.cas, k.text, c.nadpis, c.alias", "komentare k", array("join" => "clanky c ON id_clanku=c.id", "where" => $where, "order" => "cas ".$order), "assoc");
foreach ($data as $comment) {
  include_plugins("text editor", array("action" => "modify", "rss" => true), $comment);
  $comment["text"] = html_entity_decode(strip_tags($comment["text"]), ENT_QUOTES, "utf-8"); // ENT_XML1 (PHP 5.4)

  // zobrazení
  echo "<item>";
  echo "<title>".$comment["jmeno"]." ke článku ".$comment["nadpis"]."</title>";
  echo "<link>".$lrs["address"]."/".$comment["alias"]."#".$comment["id"]."</link>";
  echo "<description>".$comment["text"]."</description>";
  echo "<pubDate>".date("r", strtotime($comment["cas"]))."</pubDate>";
  echo "</item>";
}
?>

</channel>

</rss>