<?php
function get_template_info() {
  global $lrs;
  
  $lrs["current_template"] = strtolower(bez_diakritiky(array_shift(get_settings("template"))));
  $template["path"] = TEMPLATES_DIR.$lrs["current_template"]."/";
  include ($template["path"]."info.php");
  return $template;
}
?>