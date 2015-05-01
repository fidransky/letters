<?php
function check_alias($alias, $table) {
  global $i;

  if (get_count($table, "alias='".$alias."'") == 0)
    return $alias;
  else {
    if (!isSet($i)) $i = 1;
    else $alias = str_replace($i, null, $alias);
    $i++;
    return check_alias($alias.$i, $table);
  }
}
?>