<?php
if (!isSet($_GET["q"]) or empty($_GET["q"])) exit;

// konfigurace databáze
(@include ("../../../letters/config.php")) or exit ("<p>The page you requested could not be loaded due to technical issues.<br><small>missing the \"config.php\" file</small></p>");

// definice konstant a vkládání funkcí
define("FCE_DIR", "../../../funkce/");
define("PLUGINS_DIR", "../../");
define("TEMPLATES_DIR", "../../../vzhledy/");

include ("../../../includes.php");


// vyhledávání
$hledano = mysql_real_escape_string($_GET["q"]);

$ft_min_word_len = mysql_result(mysql_query("SHOW VARIABLES LIKE 'ft_min_word_len'"), 0, 1);

$where = "zverejneno=1 AND cas<=NOW() AND zobrazit=1";
if (strlen($hledano) < $ft_min_word_len)
  $where .= " AND (`text` REGEXP '[[:<:]]".$hledano."[[:>:]]' COLLATE 'utf8_general_ci' OR nadpis LIKE '%".$hledano."%' COLLATE 'utf8_general_ci' OR tagy LIKE '%".$hledano."%')";
else
  $where .= " AND MATCH(nadpis, `text`, tagy) AGAINST('".$hledano."' IN BOOLEAN MODE)";

$articles = get_data("*", "clanky", array("where" => $where, "order" => "cas DESC"), "assoc");

if ($articles == false)
  exit;
else {
  list(, $per_page, $from_subcategories, $preview_type, $preview_count, $datetime_format) = get_settings("group=clanky", "row");

  ob_start();
  include ("../frontend/archive.php");
  $data = ob_get_clean();
  
  echo json_encode($data);
}
?>