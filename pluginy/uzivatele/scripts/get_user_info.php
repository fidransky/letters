<?php
function get_user_info($id, $columns=null) {
  if (empty($id)) return false;
  
  if (is_numeric($id)) $where = "id='".intval($id)."'";
  else $where = "username='".mysql_real_escape_string($id)."'";
  
  if (empty($columns)) $columns = "*";

  return array_shift(get_data($columns, "uzivatele", array("where" => $where, "limit" => 1), "assoc"));
}
?>