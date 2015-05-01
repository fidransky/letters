<?php
function get_title($lrs, $separator=" &rsaquo; ") {
  echo $lrs["title"];

  if (isSet($_GET["http_response_code"])) $http_response_code = $_GET["http_response_code"];
  else global $http_response_code;
  
  if ($http_response_code == 200 and isSet($_GET["page"])) {
    ob_start();
    $include_plugins = include_plugins("page_".$_GET["page"], array("action" => "title", "separator" => $separator));

    if ($include_plugins == false) {
      $http_response_code = 404;
      ob_end_clean();
    }
    else
      echo $separator.ob_get_clean();
  }

  if ($http_response_code != 200) {
    echo $separator;
    if ($http_response_code == 403) echo "Přístup zamítnut";
    elseif ($http_response_code == 404) echo "Stránka nenalezena";
  }
}
?>