<?php
function generate_password($znaku=8) {
  $str = str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
  return substr($str, 0, $znaku);
}
?>