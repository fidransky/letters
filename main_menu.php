<?php
function active($current, $id) {
  if ($current == $id) return " class=\"active\"";
  return false;
}

$address = array_shift(explode("?", get_address()));
$current = (substr($address, -1, 1) == "/" ? substr($address, 0, -1) : $address);

foreach (get_data("text, address", "main_menu", array("order" => "`order`, id"), "assoc") as $link) {
  $link["active"] = active($current, (substr($link["address"], -1, 1) == "/" ? substr($link["address"], 0, -1) : $link["address"]));
  echo "<li".$link["active"]."><a href=\"".$link["address"]."\">".__($link["text"])."</a>";
}
?>