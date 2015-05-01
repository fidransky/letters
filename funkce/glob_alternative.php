<?php
function glob_alternative($directory, $filter=null, $flag=null) {
  $dir = dir($directory);
  $array = array();

  while ($file = $dir->read()) {
    if ($file != "." and $file != "..") {
      $path = $directory.$file;

      if ($flag != GLOB_BRACE and is_dir($path)) {
        $array[] = $path;
      }
      elseif ($flag != GLOB_ONLYDIR and is_file($path)) {
        if (empty($filter) or $filter = "*") {
          $array[] = $path;
        }
        else {
          $ext = substr($path, strrpos($path, ".") +1);
          if (preg_match("/\b".$ext."\b/i", $filter)) { $array[] = $path; }
        }
      }
    }
  }
  $dir->close();

  return $array;
}
?>