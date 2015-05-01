<?php
function get_article($where=null) {
  $article = get_data("*", "clanky", array("where" => $where), "assoc");
  return $article[0];
}
?>