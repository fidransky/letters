<?php
if (!function_exists("curl_file_get_contents")) {
function curl_file_get_contents($url) {
  $c = @curl_init();                   // iniciuje práci s curl
  if ($c == false) { return false; }
  
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($c, CURLOPT_URL, $url);
  $contents = curl_exec($c);
  curl_close($c);

  return $contents;
}
}

function logg($text) {
  return "<b>".date('H:i:s').":</b> ".$text."<br>";
}

function move_tree($source) {
  global $dir, $log;
  $log .= logg("přesouvání složky \"".$source."\"");
  foreach (glob($source.'/*') as $entry) {
    // přeskočení některých souborů
    if ($entry == $dir."/.htaccess.php") continue;
    if ($entry == $dir."/letters/content/menu.txt") continue;
    if ($entry == $dir."/letters/content/podmenu.txt") continue;

    $dest = str_replace($dir."/", "../", $entry);
    
    if (is_file($entry)) {
      //$log .= logg($entry." je soubor, přesouvám do ".$dest);
      @rename($entry, $dest);
      chmod($dest, 0777);
    }
    elseif (is_dir($entry)) {
      if (!file_exists($dest)) {
        //$log .= logg("<small>složka \"".$dest."\" neexistuje, tvořím a nastavuji práva</small>");
        $oldumask = umask(0);
        @mkdir($dest, 0777);
        umask($oldumask);
      }
      //$log .= logg($entry." je složka, spouštím se rekurzivně");
      move_tree($entry);
      @rmdir($entry);
    }
  }
  return true;
}

// http://www.bitrepository.com/how-to-remove-a-directory.html
function autoupgrade_delete_dir($dir) {
  if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." and $file != "..") {
        $path = $dir."/".$file;
        if (is_dir($path))
				  if (!@rmdir($path)) delete_dir($path.'/');
			  else 
          @unlink($path);
      }
    }
    closedir($handle);
	  return @rmdir($dir);
  }
}
?>