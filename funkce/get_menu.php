<?php
function get_menu() {
  if (!file_exists(dirname(__FILE__)."/../letters/content/menu.txt")) return false;

  $rows = file(dirname(__FILE__)."/../letters/content/menu.txt");
  foreach ($rows as $row) {
    $temp = explode("; ", $row);
    $plugin = trim($temp[0]);
    
    $aktivni = array_shift(array_shift(get_data("active", "pluginy", array("where" => "name='".$plugin."'"), "row")));
    if ($aktivni == 1) {
      $plugin = strtolower(bez_diakritiky($plugin));
      $text = (isSet($temp[1]) ? trim($temp[1]) : $plugin);
      $menu = strtolower(bez_diakritiky($text));
    
      global $def;  // načtení pole, kde jsou uloženy hodnoty výchozích stránek (hodnota proměnné $co v adrese)
    
      echo "<a href=\"letters.php?page=".$menu."&co=".$def[$menu]."\" class=\"button ".active($menu)."\" id=\"menu_".$menu."\">";
      flag($menu);
      echo $text;
      echo "<img src=\"".PLUGINS_DIR.$plugin."/".$menu.".png\" class=\"icon\">";
      echo "</a>";

      $page = (isSet($_GET["page"]) ? $_GET["page"] : null);
      
      if (empty($page) or $page != $menu) echo "<nav class=\"".$menu."\" style=\"display: none\">";
      else echo "<nav class=\"".$menu."\">";
      get_podmenu($menu);
      echo "</nav>";
    }
  }
  
  return false;
}


function get_podmenu($page) {
  if (!file_exists(dirname(__FILE__)."/../letters/content/podmenu.txt")) return false;
  
  $rows = file(dirname(__FILE__)."/../letters/content/podmenu.txt");
  foreach ($rows as $row) {
    $temp = explode("; ", $row);
    $kam = trim($temp[0]);
    
    if ($kam == "new") {
      $parent = trim(strtolower($temp[2]));
      if ($page == $parent) {   // pokud se má odkaz vkládat do aktuálního menu
        $plugin = trim($temp[1]);
        $text = trim($temp[3]);
        $co = strtolower(bez_diakritiky($text));
      
        $aktivni = array_shift(array_shift(get_data("active", "pluginy", array("where" => "name='".$plugin."'"), "row")));
        if ($aktivni == 1) {
          echo "<a href=\"letters.php?page=".$parent."&co=".$co."\">";
          flag($parent."->".$co);
          echo $text."</a>";
        }
      }
    }
  }
  
  return false;
}
?>