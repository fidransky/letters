<?php
function save_settings($data) {
  foreach ($data as $name => $value) {
    $save = save_data(array("value" => $value), "nastaveni", "name='".$name."'");
    if ($save === false) return false;
  }
  return true;
}
?>