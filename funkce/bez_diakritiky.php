<?php
// latrine.dgx.cz/odstraneni-diakritiky-z-ruznych-kodovani
// php.vrana.cz/vytvoreni-pratelskeho-url.php

function bez_diakritiky($text, $url=true) {
  setlocale(LC_ALL, "cs_CZ"); // záleží na použitém systému

  //odstranění znaků diakritiky
  $text = iconv("utf-8", "us-ascii//TRANSLIT", $text);

  // odstranění zbytků po 'iconv' a nahrazení mezer podtržítkem
  $pole = array("'" => null, "\"" => null, "^" => null, " " => "_");
  $text = strtr($text, $pole);

  if ($url == true) {
    $text = strtolower($text);
    $text = preg_replace("~[^a-z0-9_-]+~", null, $text);
  }
  
  return $text;
}
?>