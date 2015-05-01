<?php
function save_data($data, $table, $where=null) {
  if (!is_array($data)) return false;
  
  foreach ($data as $index => $value) {
    if (empty($where)) {
      $indexes[] = "`".$index."`";
      $values[] = "'".mysql_real_escape_string($value)."'";
    }
    else
      $set[] = "`".$index."`='".mysql_real_escape_string($value)."'";
  }
  
  if (empty($where))
    $result = mysql_query("INSERT INTO ".DB_PREFIX.$table."(".implode(",", $indexes).") VALUES (".implode(",", $values).")");
  else
    $result = mysql_query("UPDATE ".DB_PREFIX.$table." SET ".implode(",", $set)." WHERE ".$where);
    
  return $result;
}
?>