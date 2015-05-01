<?php
function sklonuj($pocet, $zadny, $dva, $pet, $visible=true) {
  $text = null;

  if ($pocet == 0) {
    if ($visible) { $text = __("žádný")." "; }
    $text .= $zadny;
  }
  elseif ($pocet == 1) {
    if ($visible) { $text = __("jeden")." "; }
    $text .= $zadny;
  }
  elseif ($pocet >= 2 and $pocet <= 4) {
    if ($visible) { $text = $pocet." "; }
    $text .= $dva;
  }
  elseif ($pocet >= 5) {
    if ($visible) { $text = $pocet." "; }
    $text .= $pet;
  }

  return $text;
}
?>