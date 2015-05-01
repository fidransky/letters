<?php
function include_plugins($kategorie, $pass=array(), &$global=array()) {
  global $lrs, $template;
  
  if (!empty($pass))
    foreach ($pass as $index => $value) $$index = $value;
    
  $plugins = get_data("alias", "pluginy", array("where" => "active=1 AND category LIKE '%".mysql_real_escape_string($kategorie)."%'", "order" => "id"), "assoc");
  if ($plugins == false) return false;
  
  $i = 0;
  foreach ($plugins as $plugin) {
    $include = @include (PLUGINS_DIR.$plugin["alias"]."/".$plugin["alias"].".php");
    if ($include == true) $i++;
  }
  return $i;
}
?>