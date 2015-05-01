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
list(, , $from_subcategories, $preview_type, $preview_count, , , $rss_count, $rss_formatting) = get_settings("group=clanky", "row");

$where = "zverejneno=1 AND cas<=NOW() AND zobrazit=1";
if (isSet($_GET["kategorie"]) and $_GET["kategorie"] != "vsechny") {
  $kategorie = mysql_real_escape_string($_GET["kategorie"]);
  
  if (!empty($kategorie)) {
    if ($from_subcategories == 1) $where .= " AND kategorie LIKE '".$kategorie."%'";
    else $where .= " AND kategorie='".$kategorie."'";
  }
}

$articles = get_data("*", "clanky", array("where" => $where, "order" => "cas DESC", "limit" => "0, ".$rss_count), "assoc");

if ($articles != false) {
  foreach ($articles as $article) {
    $article["nadpis"] = html_entity_decode($article["nadpis"], ENT_QUOTES, "utf-8");
    
    // text
    if (!empty($article["heslo"]))
      $text = "Tento článek je přístupný pouze s heslem.";
    else {
      include_plugins("text editor", array("action" => "modify", "rss" => true), $article);

      $text = null;
      if ($preview_type == "words") {
        foreach (explode(" ", $article["text"]) as $index => $word) {
          if ($index == $preview_count) break;
          $text .= $word." ";
        }

        if ($preview_count < count($pocet_slov)) $text .= "&hellip;";      
      }

      elseif ($preview_type == "paragraphs") {
        foreach (explode("</p>", $article["text"]) as $index => $paragraph) {
          if ($index == $preview_count) break;
          $text .= $paragraph."</p>";
        }
      }
      
      else
        $text = $article["text"];
      
      // HTML formátování
      if ($rss_formatting == 1) $text = "<![CDATA[".$text."]]>";
      else $text = strip_tags($text);
      
      // převod entit
      $text = html_entity_decode($text, ENT_QUOTES, "utf-8"); // ENT_XML1 (PHP 5.4)
    }

    // zobrazení
    echo "<item>";
    echo "<title>".$article["nadpis"]."</title>";
    echo "<link>".$lrs["address"]."/".$article["alias"]."</link>";
    echo "<description>".$text."</description>";
    echo "<pubDate>".date("r", strtotime($article["cas"]))."</pubDate>";
    echo "</item>";
  }
}
?>

</channel>

</rss>