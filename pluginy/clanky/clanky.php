<?php
if ($kategorie == "head") {
  echo "<link rel=\"search\" href=\"".$lrs["address"]."/vyhledavani\" title=\"".__("Vyhledávání")."\">";
  echo "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"".$lrs["address"]."/rss\" title=\"".__("RSS článků")."\">";

  if (isSet($_GET["kategorie"]) and $_GET["kategorie"] != "vsechny")
    echo "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"".$lrs["address"]."/rss/".$_GET["kategorie"]."\" title=\"".__("RSS kategorie")."\">";
    

  if (isSet($_GET["clanek"])) {
    if (is_numeric($_GET["clanek"])) $where = "id='".intval($_GET["clanek"])."'";
    else $where = "alias='".mysql_real_escape_string($_GET["clanek"])."'";
  
    $article = array_shift(get_data("cas, alias, zverejneno, heslo", "clanky", array("where" => $where), "assoc"));
    
    // neexistující nebo nezveřejněný článek
    if ($article == null or !(($article["zverejneno"] == 1 and $article["cas"] <= date("Y-m-d H:i:s")) or ($article["zverejneno"] == 1 and $article["cas"] > date("Y-m-d H:i:s") and check_user2("upravy_clanku")) or ($article["zverejneno"] == 0 and check_user2("upravy_clanku")))) {
      header("HTTP/1.0 404 Not Found"); // http_response_code(404);
      global $http_response_code;
      $http_response_code = 404;
    }

    // článek s heslem - ověření hesla
    if (isSet($_POST["password"])) {
      if (sha1($_POST["password"]) == $article["heslo"])
        $_SESSION["clanek_".$article["alias"]."_pristup"] = true;
    }
  }
}

elseif ($kategorie == "uvodni") {
  if (preg_match("/kategorie/", $uvodni)) {
    if (preg_match("/kategorie=/", $uvodni)) $kategorie = str_replace("articles=", null, $uvodni);
    include (PLUGINS_DIR."clanky/frontend/category.php");
  }
  elseif (preg_match("/clanek/", $uvodni)) {
    include_once (PLUGINS_DIR."clanky/scripts/get_article.php");

    $identifier = str_replace("clanek=", null, $uvodni); 
    if ($identifier == "posledni") {
      $id = get_data("id", "clanky", array("where" => "zverejneno=1 AND cas<=NOW() AND zobrazit=1", "order" => "cas DESC", "limit" => "1"), "row");
      $identifier = $id[0][0];
    }
    $where = "id='".$identifier."'";

    include (PLUGINS_DIR."clanky/frontend/article.php");
  }
}

else {
  if ($action == "title") {
    if (isSet($_GET["clanek"])) {
      if (is_numeric($_GET["clanek"])) $where = "id='".intval($_GET["clanek"])."'";
      else $where = "alias='".mysql_real_escape_string($_GET["clanek"])."'";

      echo array_shift(array_shift(get_data("nadpis", "clanky", array("where" => $where), "row")));
    }
    elseif (isSet($_GET["kategorie"])) {
      if (empty($_GET["kategorie"])) echo "Kategorie";
      elseif ($_GET["kategorie"] == "vsechny") echo "všechny články";
      else {
        include_once (PLUGINS_DIR."clanky/scripts/list.php");
        echo category_real_name($_GET["kategorie"]);
      }
    }
    elseif (isSet($_GET["q"])) {
      echo "Vyhledávání";
      if (!empty($_GET["q"])) echo ": ".strip_tags($_GET["q"]);
    }
  }
  else {
    include_once (PLUGINS_DIR."clanky/scripts/get_article.php");
    
    if (isSet($_GET["clanek"])) {
      if (is_numeric($_GET["clanek"])) $where = "id='".intval($_GET["clanek"])."'";
      else $where = "alias='".mysql_real_escape_string($_GET["clanek"])."'";

      include (PLUGINS_DIR."clanky/frontend/article.php");
    }
    elseif (isSet($_GET["kategorie"])) {
      if (empty($_GET["kategorie"])) include (PLUGINS_DIR."clanky/frontend/categories.php");
      else include (PLUGINS_DIR."clanky/frontend/category.php");
    }
    elseif (isSet($_GET["q"]))
      include (PLUGINS_DIR."clanky/frontend/search.php");
  }
}
?>