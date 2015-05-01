<?php
function get_data($columns, $table, $other=array(), $return="assoc") {
  if (is_array($columns)) $columns = implode(", ", $columns);
  
  $join = null;
  if (array_key_exists("join", $other)) $join = " JOIN ". $other["join"];
  
  $where = null;
  if (array_key_exists("where", $other)) {
    if (is_array($other["where"])) {
      $where = array();
      foreach ($other["where"] as $column => $condition) $where[] = $column."='".(is_string($condition) ? mysql_real_escape_string($condition) : $condition)."'";
      $where = " WHERE ". implode(" AND ", $where);
    }
    elseif (is_string($other["where"]))
      $where = " WHERE ". $other["where"];
    else
      $where = null;
  }
  
  $order = null;
  if (array_key_exists("order", $other)) $order = " ORDER BY ". $other["order"];
  
  $limit = null;
  if (array_key_exists("limit", $other)) $limit = " LIMIT ". $other["limit"];
  
  $select = mysql_query("SELECT ".$columns." FROM ".DB_PREFIX.$table.$join.$where.$order.$limit);
  
  if (mysql_num_rows($select) > 0) {
    if ($return == "assoc") while ($array = mysql_fetch_assoc($select)) $data[] = $array;
    else while ($array = mysql_fetch_row($select)) $data[] = $array;
    
    return $data;
  }
  
  return false;
}
?>