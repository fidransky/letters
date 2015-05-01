<?php
function get_settings($columns, $return="assoc") {
  if (substr($columns, 0, 6) == "group=")
    $where = "`group`='".str_replace("group=", null, $columns)."'";
  else {
    if (!is_array($columns)) $columns = explode(", ", $columns);
    $where = "name='". implode("' OR name='", $columns) ."'";
  }
    
  $select = mysql_query("SELECT ".($return == "assoc" ? "name," : null)."value FROM ".DB_PREFIX."nastaveni WHERE ".$where);
  
  if ($return == "assoc")
    while (list($name, $value) = mysql_fetch_row($select)) $data[$name] = $value;
  else
    while (list($value) = mysql_fetch_row($select)) $data[] = $value;
  
  return $data;          
}
?>