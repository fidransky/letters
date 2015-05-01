<?php
function links_categories($set, $parents=null) {
  $categories = get_data("jmeno, alias, popis, parents", "kategorie", array("where" => "parents='".$parents."'"), "assoc");
  if ($categories == false) return false;
  
  if (!empty($parents)) echo "<ul>";

  foreach ($categories as $category) {
    if (!empty($parents))
      $category["alias"] = $category["parents"]."/".$category["alias"];

    $echo = "<li".(empty($parents) ? " class=\"root\"" : null)."><a href=\"kategorie/".$category["alias"]."\" title=\"".__($category["popis"], "category")."\">".__($category["jmeno"], "category")."</a>";

    if ($set["navigace_show_count"] == 1 or $set["navigace_hide_empty"] == 1)
      $count = get_count("clanky", "(kategorie='".$category["alias"]."' OR kategorie REGEXP '".$category["alias"]."[[:>:]]') AND zverejneno='1' AND cas<='".date("Y-m-d H:i:s", time())."' AND zobrazit='1'");
      
    if ($set["navigace_show_count"] == 1)
      $echo .= " <span class=\"count\">{".$count."}</span>";

    if ($set["navigace_hide_empty"] == 0)
      echo $echo;
    else
      if ($count != 0) echo $echo;

    if (preg_match("/".addcslashes($category["alias"], "/")."/", $_GET["kategorie"]))
      links_categories($set, $category["alias"]);
  }

  if (!empty($parents)) echo "</ul>";
  return true;
}


// zobrazení
if ($menu_popisky == 1)
  echo "<h3>".__("Navigace")."</h3>";

$set = get_settings("group=navigace");

echo "<ul id=\"links\">";
echo "<li><a href=\"".$lrs["address"]."\" title=\"".__("úvodní strana")."\"><strong>".__("úvodní strana")."</strong></a></li>";
echo "<li><a href=\"kategorie/vsechny\" title=\"".__("všechny články")."\">".__("všechny články")."</a></li>";

if ($set["navigace_show_categories"] == 1)
  links_categories($set);
echo "</ul>";

$articles = get_data("nadpis,alias", "clanky", array("where" => "zverejneno='1' AND cas<='".date("Y-m-d H:i:s", time())."' AND menu='1'", "order" => "cas DESC"), "assoc");
if ($articles != false) {
  echo "<ul id=\"articles\">";
  foreach ($articles as $article)
    echo "<li><a href=\"".$article["alias"]."\">".$article["nadpis"]."</a></li>";
  echo "</ul>";
}
?>