<?php
list($footer_area) = get_settings("footer_area", "row");

if (!empty($footer_area)) {
  $where = "(id=".str_replace(", ", " OR id=", $footer_area).")";
  
  $order = null;
  foreach (explode(", ", $footer_area) as $id)
    $order .= " (id=".$id.") DESC,";

  // vkládání pluginů
  foreach (get_data("id, alias", "pluginy", array("where" => "active=1 AND ".$where, "order" => $order." alias"), "assoc") as $plugin) {
    echo "<div class=\"panel cleaner\">";
    $kategorie = "sidebar";
    include (PLUGINS_DIR.$plugin["alias"]."/".$plugin["alias"].".php");
    echo "</div>";
  }
}
?>