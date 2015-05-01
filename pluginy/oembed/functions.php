<?php
function curl_get_json($url) {
  $c = curl_init($url);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($c, CURLOPT_BINARYTRANSFER, true);
  curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
  $contents = curl_exec($c);
  curl_close($c);
  
  return $contents;
}

function check_oembed_support($url) {
  $content = remote_file_get_contents($url);
  
  if (preg_match("/<link rel=\"alternate\" type=\"application\/json\+oembed\" href=\"([^\"]+)\".*>/", $content, $links) or preg_match("/<link rel=\"alternate\" href=\"([^\"]+)\" type=\"application\/json\+oembed\".*>/", $content, $links))
    return $links[1];
  
  return false;
}

function array_from_file($file) {
  $pole = file($file);
  foreach ($pole as $radek) {
    $pomocPole = explode(" => ", str_replace("\r\n", null, $radek));
    $return[$pomocPole[0]] = $pomocPole[1];
  }
  
  return $return;
}
?>