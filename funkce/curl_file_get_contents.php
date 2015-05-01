<?php
function curl_file_get_contents($url) {
  $c = @curl_init();                   // iniciuje práci s curl
  if ($c == false) { return false; }
  
  curl_setopt($c, CURLOPT_URL, $url);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($c, CURLOPT_FAILONERROR, 1);
  $contents = curl_exec($c);
  curl_close($c);

  return $contents;
}
?>