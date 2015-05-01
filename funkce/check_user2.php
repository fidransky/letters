<?php
function check_user2($foo, $die=false) {
  $role = $_SESSION["role"];
  if (empty($role)) $role = "anonymous";

  $roles = explode(", ", array_shift(get_settings("rights_roles", "row")));

  // pokud je v proměnné 'foo' předána minimální vyžadovaná hodnost
  if (in_array($foo, $roles)) {
    $i = (array_search($role, $roles) !== false ? array_search($role, $roles) : count($roles));
    $access = array_slice($roles, $i);

    if (in_array($foo, $access)) return true;
  }
  
  // pokud je v proměnné 'foo' předán identifikátor určité činnosti
  else {
    $settings = get_settings("rights_".$role, "row");
    if ($settings == false) return true;
    
    parse_str(array_shift($settings), $rights);
    if ($rights[$foo] == 1) return true;
  }

  if ($die == true)
    exit ("<p class=\"error\">Pro zobrazení nemáte dostatečná práva.</p>");

  return false;
}
?>