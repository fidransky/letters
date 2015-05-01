<?php
if ($action == "modify") {

include_once (PLUGINS_DIR."oembed/functions.php");
$set = get_settings("oembed_width");

preg_match_all("/[^<>\"]{1}(https?:\/\/[\da-z\.-]+\.[a-z\.]{2,6}[\/\w\?\$\+\.,:;%@&=-]*)[^<>\"]*/", $global["text"], $shody, PREG_SET_ORDER);

if (!empty($shody)) {
foreach ($shody as $url) {
  $url = $url[1];
  
  // HTML kód už je v cache
  if (file_exists("cache/oembed/".rawurlencode($url).".html"))
    $global["text"] = preg_replace("/[^<>\"]{1}".preg_quote($url, "/")."[^<>\"]{1}/", file_get_contents("cache/oembed/".rawurlencode($url).".html"), $global["text"]);

  // načtení HTML embed kódu
  else {
    $domain = str_replace("www.", null, parse_url($url, PHP_URL_HOST));
    $whitelist = array_from_file(PLUGINS_DIR."oembed/whitelist.txt");
    $oembed_url = null;
    $html = null;
    
    $on_whitelist = array_key_exists($domain, $whitelist);
  
    // pokud URL není na whitelistu, ověří se dostupnost oEmbed pomocí tagu v hlavičce
    if ($on_whitelist == false) {
      $oembed_url = check_oembed_support($url);
    
      if ($oembed_url != false) {
        // přidání URL na whitelist
        $file = @FOpen(PLUGINS_DIR."oembed/whitelist.txt", "a");
        @FWrite($file, parse_url($url, PHP_URL_HOST)." => ".str_replace(array("&amp;", rawurlencode($url)), array("&", "{url}"), $oembed_url)."\r\n");
        @FClose($file);
      }
    }
    else
      $oembed_url = $whitelist[$domain];
      
    // pokud je dostupná URL pro oEmbed, převede se odkaz na HTML kód vrácený oEmbed endpointem
    if (isSet($oembed_url) and !empty($oembed_url)) {
      $oembed_url = str_replace(array("{url}", "{width}", "{lang}", "{lrs-adresa}"), array(rawurlencode($url), $set["oembed_width"], LANG, rawurlencode($lrs["address"])), $oembed_url);
      
      $response = json_decode(curl_get_json($oembed_url));
      if (!isSet($response->error)) {
        // zpracování různých typů obsahu
        if ($response->type == "rich" or $response->type == "video" or isSet($response->html)) $html = $response->html;
        elseif ($response->type == "photo") $html = "<img src=\"".$response->url."\" alt=\"".$response->title."\">";
        else $html = "<a href=\"".$url."\">".$url."</a>";
      }
    }

    // nahrazení odkazu v textu HTML kódem a uložení do cache 
    if (isSet($html)) {
      $html = "<div class=\"oembed ".$response->type."\">".$html."</div>";
      $global["text"] = preg_replace("/[^<>\"]{1}".preg_quote($url, "/")."[^<>\"]*/", $html, $global["text"]);

      $file = @FOpen("cache/oembed/".rawurlencode($url).".html", "w");
      @FWrite($file, $html);
      @FClose($file);
    }
    else
      $global["text"] = str_replace($url, "<a href=\"".$url."\">".$url."</a>", $global["text"]);
  }
}}

}
?>