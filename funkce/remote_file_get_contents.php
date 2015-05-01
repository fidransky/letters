<?php
function remote_file_get_contents($url) {
  $data = false;

  // cURL
  if (function_exists('curl_init')) {
    $c = @curl_init();
    if ($c == false) { $data = false; }
    else {
      curl_setopt($c, CURLOPT_URL, $url);
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($c, CURLOPT_FAILONERROR, 1);
      $data = curl_exec($c);
      if (curl_errno($c)) { $data = false; }
      curl_close($c);
    }
  }
  
  // když není cURL podporován hostingem (např. ic.cz), zkusí načíst klasicky; většinou končí chybou 'URL file-access is disabled'
  if ($data === false and ini_get('allow_url_fopen')) { $data = @file_get_contents($url); }

  if ($data === false) { return false; }
  return $data;
}
?>