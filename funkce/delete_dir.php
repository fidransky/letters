<?php
function delete_dir($dir, $self=true) {
  $glob = (function_exists("glob") ? glob($dir."/*") : glob_alternative($dir."/", "*"));

  foreach ($glob as $path) {
    $del = (is_dir($path) ? delete_dir($path, $self) : unlink($path));
    if ($del == false) return false;
  }
  
  if ($self == true) return rmdir($dir);
  return true;
}
?>