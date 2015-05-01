<?php
list(, $per_page, $from_subcategories, $preview_type, $preview_count, $datetime_format) = get_settings("group=clanky", "row");

$where = "zverejneno=1 AND cas<=NOW() AND zobrazit=1";
if (isSet($uvodni) or (isSet($_GET["kategorie"]) and $_GET["kategorie"] != "vsechny")) {
  if (isSet($uvodni)) parse_str($uvodni);
  else $kategorie = mysql_real_escape_string($_GET["kategorie"]);
  
  if (!empty($kategorie)) {
    if ($from_subcategories == 1) $where .= " AND kategorie LIKE '".$kategorie."%'";
    else $where .= " AND kategorie='".$kategorie."'";
  }
}

// stránkování
$page = (empty($_GET["pg"]) ? 1 : (int)$_GET["pg"]);
$limit = ($page * $per_page) - $per_page.",".$per_page;
$count = ceil(get_count("clanky", $where) / $per_page);


$articles = get_data("*", "clanky", array("where" => $where, "order" => "cas DESC", "limit" => $limit), "assoc");

if ($articles == false)
  echo "<p>".__("V této kategorii zatím není žádný článek.")."</p>";
else {
  // zobrazení
  include (PLUGINS_DIR."clanky/frontend/archive.php");  

  // stránkování
  if (isSet($uvodni)) parse_str($uvodni);
  else $kategorie = $_GET["kategorie"];
  if (empty($kategorie)) $kategorie = "vsechny";

  echo "<div id=\"pagination\">";
  if ($page != 1)
    echo ("<a href=\"kategorie/".$kategorie.($page != 2 ? "/".($page - 1) : null)."\" id=\"newer\" style=\"float: left;\">&laquo; ".__("novější články")."</a>");
  if ($count > $page)
    echo ("<a href=\"kategorie/".$kategorie."/".($page + 1)."\" id=\"older\" style=\"float: right;\">".__("starší články")." &raquo;</a>");
  echo "<br class=\"cleaner\"></div>";
}
?>