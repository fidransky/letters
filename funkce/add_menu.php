<?php
function add_menu($plugin, $def_podmenu, $text=null, $flag=false) {
  if (empty($text)) $data = $plugin;
  else $data = $plugin."; ".$text;

  if (file_exists("content/menu.txt")) $old_data = file_get_contents("content/menu.txt");
  if (preg_match("/".$data."/", $old_data)) return true;

  $soubor = FOpen("content/menu.txt", "w");
  $zapis = FWrite ($soubor, $old_data.$data."\r\n");
  FClose ($soubor);
  if ($zapis == false) return false;
  
  // registrace do defaults
  $data = file("content/defaults.php");

  $plugin = strtolower(bez_diakritiky($plugin, false));
  $new_row = "\$def[\"".$plugin."\"] = \"".$def_podmenu."\"; // ".$plugin."\r\n?>";

  $pocet = count($data) - 1;
  foreach ($data as $i => $row) {
    if ($i == $pocet) { $new_data .= $new_row; }       // poslední řádek
    else { $new_data .= $row; }
  }

  $soubor = FOpen("content/defaults.php", "w");
  $zapis = FWrite($soubor, $new_data);
  FClose($soubor);

  if ($zapis == false) return false;

  if ($flag == true)
    set_flag(strtolower(bez_diakritiky(isSet($text) ? $text : $plugin)));

  return true;
}


function add_podmenu($typ, $plugin, $parent, $co=null, $flag=false) {
  if ($typ == "new" and empty($co)) $co = $plugin;

  $data = $typ."; ".$plugin."; ".$parent."; ".$co;

  if (file_exists("content/podmenu.txt")) $staradata = file_get_contents("content/podmenu.txt");
  if (preg_match("/".$data."/", $staradata)) return true;

  $soubor = FOpen("content/podmenu.txt", "w");
  $zapis = FWrite($soubor, $staradata.$data."\r\n");
  FClose($soubor);
  if ($zapis == false) return false;
  
  if ($flag == true)
    set_flag($parent ."->". ($typ == "new" ? strtolower(bez_diakritiky($co)) : $co));

  return true;
}
?>