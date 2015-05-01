<?php
function lang($text, $lang=LANG, $pocet=null, $kontext=null) {
  if (empty($lang)) return $text;
  if ($lang == "cs") return $text;

  $where = null;
  if (!empty($pocet)) {
    if ($pocet >= 2 and $pocet <= 4) $pocet = 2;
    elseif ($pocet >= 5) $pocet = 5;
    $where .= " AND pocet='".$pocet."'";
  }

  if (!empty($kontext))
    $where .= " AND kontext='".$kontext."'";
  
  // pokud se jedná o neobvyklý překlad (množné číslo, jiný kontext apod.)
  if (!empty($where)) {
    $sel = mysql_query("SELECT preklad FROM preklady WHERE jazyk='".$lang."' AND original='".$text."'".$where);
    if (@mysql_num_rows($sel) == 0)
      return $text;
    else
      while ($radek = mysql_fetch_row($sel)) return $radek[0];
  }

  // načtení statického pole se všemi překlady
  static $preklady;
  if (!isset($preklady)) {
    $preklady = array();
    $data = @get_data("original, preklad", "preklady", array("where" => "jazyk='".$lang."'"), "assoc");
    if ($data != false) {
      foreach ($data as $preklad)
        $preklady[$preklad["original"]] = $preklad["preklad"];
      @mysql_free_result($sel);
    }
  }
  
  // pokud se jedná o obvyklý překlad
  return (isset($preklady[$text]) ? $preklady[$text] : $text);
}

function __($text) {
  $pocet = null;
  $kontext = null;

  if (func_num_args() > 1) {
    foreach (func_get_args() as $index => $arg) {
      if ($index == 0) continue;

      if (is_numeric($arg)) $pocet = (int)$arg;
      else $kontext = (string)$arg;
    }
  }
  
  return lang($text, LANG, $pocet, $kontext);
}
?>