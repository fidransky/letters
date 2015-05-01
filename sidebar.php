<?php
list($menu_popisky, $split_boxes, $box_order) = array_values(get_settings("sidebar_labels, sidebar_split_boxes, sidebar_box_order", "row"));

if ($split_boxes == 0) echo "<div class=\"panel cleaner\">";

// řazení pluginů
$order = null;
foreach (explode(", ", $box_order) as $id)
  $order .= " (id='".$id."') DESC,";

// vkládání pluginů
foreach (get_data("id, alias", "pluginy", array("where" => "category LIKE '%sidebar%' AND active=1", "order" => $order." alias"), "assoc") as $plugin) {
  if (!in_array($plugin["id"], explode(", ", $box_order))) continue;
  if ($plugin["alias"] == "vyhledavani" and $template["search"] == true) continue;

  if ($split_boxes == 1) echo "<div class=\"panel cleaner\">";
    $kategorie = "sidebar";
    include (PLUGINS_DIR.$plugin["alias"]."/".$plugin["alias"].".php");
  if ($split_boxes == 1) echo "</div>";
}

if ($split_boxes == 0) echo "</div>";
?>