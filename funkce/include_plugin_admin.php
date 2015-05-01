<?php
function include_plugin_admin($once=true, $pass=array(), &$global=array(), $skip=array()) {
  global $lrs, $template;
  
  if (!empty($pass))
    foreach ($pass as $index => $value) $$index = $value;

  if (!file_exists(dirname(__FILE__)."/../letters/content/podmenu.txt"))
    return false;
  
  $plugins = array();
  $rows = file(dirname(__FILE__)."/../letters/content/podmenu.txt");
  foreach ($rows as $row) {
    $temp = explode("; ", $row);

    if (!empty($skip) and in_array(strtolower(bez_diakritiky($temp[1])), $skip)) continue;  // přeskočení pluginu pokud není žádoucí ho vkládat znovu
      
    $page = strtolower(bez_diakritiky($temp[2]));
    $co = strtolower(bez_diakritiky($temp[3]));
    if ((!isSet($_GET["page"]) and $page == "nastenka") or (isSet($_GET["page"]) and $_GET["page"] == $page and $_GET["co"] == $co))
      $plugins[] = $temp[1];
  }
  
  $plugins = get_data("alias, active", "pluginy", array("where" => "name='".implode("' OR name='", $plugins)."'", "order" => "id"), "assoc");
  if ($plugins == false) return false;
  
  $i = 0;
  foreach ($plugins as $plugin) {
    if ($plugin["active"] == 0) continue;
    
    $path = PLUGINS_DIR.$plugin["alias"]."/".$plugin["alias"]."_admin.php";
    if ($once == true) $include = @include_once ($path);
    else $include = @include ($path);
    
    if ($include == true) $i++;
  }
  return $i;
}
?>