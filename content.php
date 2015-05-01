<?php
include_plugins("top");

if (isSet($_GET["http_response_code"])) $http_response_code = $_GET["http_response_code"];

if ($http_response_code == 200) {
  // úvodní stránka včetně pluginů
  if (!isSet($_GET["page"])) {
    echo "<div class=\"uvodni\">";

    if (file_exists("uvodni.php")) {
      $text = file_get_contents("uvodni.php");
      include_plugins("text editor", array("action" => "modify"));
      echo $text;
    }

    $uvodni = array_shift(get_settings("template_index", "row"));
  
    if (!empty($uvodni))
      include_plugins("uvodni", array("uvodni" => $uvodni));

    echo "</div>";
  }

  // ostatní stránky
  else {
    if (!isSet($page)) $page = $_GET["page"];
    include_plugins("page_".$page);
  }
}

// error stránky
else {
  if (file_exists($template["path"]."error".$http_response_code.".php")) include ($template["path"]."error".$http_response_code.".php");
  else var_dump("error ".$http_response_code);
  include_plugins("page_error", array("http_response_code" => $http_response_code));
}
?>