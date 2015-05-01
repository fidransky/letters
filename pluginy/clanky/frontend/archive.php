<?php
$first = true;
$odd = true;
foreach ($articles as $i => $article) {
  $article["meta"]["permalink"] = $lrs["address"]."/".$article["alias"];
  
  $article["meta"]["cas"] = date($datetime_format, strtotime($article["cas"]));
    
  if (!empty($article["kategorie"])) {
    include_once (PLUGINS_DIR."clanky/scripts/list.php");
    $article["meta"]["kategorie"] = "<a href=\"kategorie/".$article["kategorie"]."\">".category_real_name($article["kategorie"])."</a>";
  }
  else
    $article["meta"]["kategorie"] = "&ndash;";
    
  $article["meta"]["tagy"] = (empty($article["tagy"]) ? "&ndash;" : $article["tagy"]);

  $article["meta"]["pocet_slov"] = count(explode(" ", $article["text"]));
  
  include_plugins("meta", array(), $article);
  
  $meta = $article["meta"];
  unset($article["meta"]);


  // speciální CSS třídy
  $class = null;

  if ($first === true) {  // první článek
    $class .= "first ";
    $first = false;
  }

  if ($odd === true) {  // lichý článek
    $class .= "odd ";
    $odd = false;
  }
  else {
    $class .= "even ";
    $odd = true;
  }
  
  // text
  if (!empty($article["heslo"]) and !check_user2("clanky_s_heslem") and !isSet($_SESSION["clanek_".$article["alias"]."_pristup"]))
    $article["text"] = "<p class=\"error\">".__("Tento článek je přístupný pouze s heslem.")."</p><form method=\"post\" action=\"".$permalink."\" class=\"locked-article\"><input type=\"password\" name=\"password\" size=\"15\" title=\"".__("zadejte heslo pro přístup")."\" class=\"input\"><input type=\"submit\" value=\"".__("Pokračovat")."\"></form>";
  else {
    include_plugins("text editor", array("action" => "modify"), $article);
    
    $text = null;
    if ($preview_type == "words") {
      foreach (explode(" ", $article["text"]) as $index => $word) {
        if ($index == $preview_count) break;
        $text .= $word." ";
      }

      if ($preview_count < count(explode(" ", $text))) $text .= "&hellip;";      
      $article["text"] = $text;
    }

    elseif ($preview_type == "paragraphs") {
      foreach (explode("</p>", $article["text"]) as $index => $paragraph) {
        if ($index == $preview_count) break;
        $text .= $paragraph."</p>";
      }
      $article["text"] = $text;
    }
  }


  // zobrazení
  if (file_exists($template["path"]."category.php")) include ($template["path"]."category.php");
  else var_dump($article);
}
?>