<?php
function delete_data($table, $where=null) {
  if (!empty($where)) $where = " WHERE ".$where;

  return mysql_query("DELETE FROM ".DB_PREFIX.$table.$where);
}
?>