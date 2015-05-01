<?php
function get_address() {
  if ($_SERVER['SERVER_PORT'] != 80) { $uri = $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI']; }
  else { $uri = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; }
  return "http://".$uri;
}
?>