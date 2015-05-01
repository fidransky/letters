<?php
function rss_parser($url) {
  include_once ("../../funkce/remote_file_get_contents.php");

  function write_cache_file($url, $file) {
    $data = remote_file_get_contents($url);
    if ($data === false) { return $url; }

    $soubor = FOpen($file, "w");
    $zapis = FWrite($soubor, $data);
    FClose($soubor);

    if ($zapis != false) { return $file; }
    return $url;
  }
  
  function show_item($item) {
    $cas = date("Y-m-d H:i:s", strtotime($item->pubDate));
    if (empty($item->link)) { echo $item->title."<br>"; }
    else { echo "<a href=\"".$item->link."\" target=\"_blank\">".$item->title."</a><br>"; }
    echo "<span class=\"small_label\" style=\"float: right; color: #BFBFBF;\" title=\"".str_replace(" ", "T", $cas)."\">".date("j. n. Y, H:i", strtotime($cas))."</span><br>";
  }


  $file = "rss_reader.cache.xml";
  if (file_exists($file)) {
    $timediff = time() - filemtime($file);

    if ($timediff > 3*3600) { write_cache_file($url, $file); }     // rozdíl větší než 3 hodiny
    $data = file_get_contents($file);
  }
  else {
    write_cache_file($url, $file);
    $data = file_get_contents($file);
  }

  if (empty($data)) { echo "<span style=\"font-style: italic; color: gray;\">Nejsou k dispozici žádné novinky.</span>"; return false; }
  if (!function_exists("simplexml_load_string")) { echo "<span style=\"font-style: italic; color: gray;\">Server nesplňuje požadavky pro čtení novinek.</span>"; return false; }

  $xml = simplexml_load_string($data);
  $array = get_object_vars($xml);
  $array = get_object_vars($array["channel"]);


  if (is_array($array["item"])) {
    foreach ($array["item"] as $index => $item) {
      show_item($item);
      if ($index == 3) break;
    }
  }
  else { show_item($array["item"]); }
}

$url = addslashes($_GET["url"]);
rss_parser($url);
?>