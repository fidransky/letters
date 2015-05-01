<?php
$article = get_article($where);


$article["meta"]["permalink"] = $lrs["address"]."/".$article["alias"];

list($datetime_format) = get_settings("articles_datetime_format", "row");
$article["meta"]["cas"] = date($datetime_format, strtotime($article["cas"]));

if (!empty($article["kategorie"])) {
  include_once (PLUGINS_DIR."clanky/scripts/list.php");
  $article["meta"]["kategorie"] = "<a href=\"kategorie/". $article["kategorie"] ."\">".category_real_name($article["kategorie"])."</a>";
}
else
  $article["meta"]["kategorie"] = "&ndash;";

$article["meta"]["tagy"] = (empty($article["tagy"]) ? "&ndash;" : $article["tagy"]);

$article["meta"]["pocet_slov"] = count(explode(" ", $article["text"]));

include_plugins("meta", array(), $article);

$meta = $article["meta"];
unset($article["meta"]);


// text
if (!empty($article["heslo"]) and !check_user2("clanky_s_heslem") and !isSet($_SESSION["clanek_".$article["alias"]."_pristup"])) {
  $article["text"] = "<p class=\"error\">";
  if (isSet($_POST["password"]) and sha1($_POST["password"]) != $article["heslo"]) $article["text"] .= __("Bylo zadáno špatné heslo.");
  else $article["text"] .= __("Tento článek je přístupný pouze s heslem.");
  $article["text"] .= "</p><form method=\"post\" action=\"".$permalink."\" class=\"locked\"><input type=\"password\" name=\"password\" size=\"15\" title=\"".__("zadejte heslo pro přístup")."\" class=\"input\"><input type=\"submit\" value=\"".__("Pokračovat")."\"></form>";
}
else
  include_plugins("text editor", array("action" => "modify"), $article);


// zobrazení
if (file_exists($template["path"]."article.php")) include ($template["path"]."article.php");
else var_dump($article);

include_plugins("pod textem", array("article" => $article));
?>