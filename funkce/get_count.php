<?php
function get_count($table, $where=null) {
  $count = get_data("COUNT(*)", $table, (!empty($where) ? array("where" => $where) : array()), "row");
  return $count[0][0];
}
?>