<?php
function options_articles($return="alias", $where, $order="cas DESC", $selected=null) {
  $razeni = array_shift(get_settings("articles_admin_order", "row"));
  
  if ($razeni == "rok")
    $select = "DATE_FORMAT(cas,'%Y') AS rok";
  elseif ($razeni == "mesic")
    $select = "DATE_FORMAT(cas,'%b %Y') AS mesic";
  else {
    $select = $razeni." AS ".$razeni;
    $order .= ", ".$razeni." ASC";
  }

  $articles = get_data("id,nadpis,alias,".$select, "clanky", array("where" => $where, "order" => $order), "assoc");
  if ($articles == false) return false;
  
  foreach ($articles as $article) {
    if ($razeni == "mesic")
      $article["mesic"] = strtr($article["mesic"], array("Jan" => "leden", "Feb" => "únor", "Mar" => "březen", "Apr" => "duben", "May" => "květen", "Jun" => "červen", "Jul" => "červenec", "Aug" => "srpen", "Sep" => "září", "Oct" => "říjen", "Nov" => "listopad", "Dec" => "prosinec"));
    
    if ($article[$razeni] != $used) {
      if (isSet($used)) echo "</optgroup>";
      echo "<optgroup label=\"".$article[$razeni]."\">";
    }
    echo "<option value=\"".$article[$return]."\"".($article[$return] == $selected ? " selected" : null).">".$article["nadpis"];

    $used = $article[$razeni];
  }
  echo "</optgroup>";
}


function category_real_name($alias, $separator="&rsaquo;") {  // vybere skutečné jméno kategorie
  foreach (explode("/", $alias) as $alias)
    $jmeno[] = array_shift(array_shift(get_data("jmeno", "kategorie", array("where" => "alias='".$alias."'"), "row")));

  return implode(" ".$separator." ", $jmeno);
}

function options_categories($return="alias", $selected=null, $skip=null, $parents=null) {
  $categories = get_data("id,jmeno,alias,parents", "kategorie", array("where" => "parents='".$parents."'"), "assoc");
  if ($categories == false) return false;
  
  foreach ($categories as $category) {
    if (!empty($parents)) {
      $category["alias"] = $category["parents"]."/".$category["alias"];
      $category["jmeno"] = category_real_name($category["alias"]);
    }

    if ($category["alias"] == $skip)
      continue;

    echo "<option value=\"".$category[$return]."\"".($category[$return] == $selected ? " selected" : null).">".$category["jmeno"];

    options_categories($return, $selected, $skip, $category["alias"]);
  }
}
?>