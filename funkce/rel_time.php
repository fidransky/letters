<?php
function rel_time($cas) {
  $now = date("Y-m-d H:i:s", time());
  $diff = abs(strtotime($now) - strtotime($cas));

  $units = array(
    "year"   => 31536000, // 365 days
    "month"  => 2592000,  // 30 days
    "week"   => 604800,
    "day"    => 86400,
    "hour"   => 3600,
    "minute" => 60,
    "second" => 1
  );

  $sklonuj = array(
    "year"   => array("před rokem", "lety", "lety"),
    "month"  => array("před měsícem", "měsíci", "měsíci"),
    "week"   => array("před týdnem", "týdny", "týdny"),
    "day"    => array("včera", "dny", "dny"),
    "hour"   => array("před hodinou", "hodinami", "hodinami"),
    "minute" => array("před minutou", "minutami", "minutami"),
    "second" => array("před sekundou", "vteřinami", "vteřinami")
  );

  foreach ($units as $index => $unit) {
    $r[$index] = floor($diff / $unit);
    if ($r[$index] > 0) { $diff = $diff % $unit; }

    if ($r[$index] != 0) { return sklonuj($r[$index], $sklonuj[$index][0], "před ".$r[$index]." ".$sklonuj[$index][1], "před ".$r[$index]." ".$sklonuj[$index][2], false); }
    else { continue; }
  }
  return date("j. n. Y v H:i", strtotime($cas));
}
?>