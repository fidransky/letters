<?php
list($id, $hash) = explode(";", $_COOKIE["permanent_login"], 2);

include ("../config.php");
include ("../../funkce/get_data.php");
include ("../../funkce/save_data.php");

$cookie_path = array_shift(explode("?", str_replace("letters/scripts/autologin.php", null, $_SERVER["REQUEST_URI"])));

$data = get_data("role", "uzivatele", array("where" => "id='".intval($id)."' AND verification_string='".mysql_real_escape_string($hash)."' AND status='schvaleny'"), "row");
if ($data != false) {
  @save_data(array("last_login" => date("Y-m-d H:i:s")), "uzivatele", "id='".$id."'");

  session_start();
  $_SESSION["id"] = $id;
  $_SESSION["log"] = true;
  $_SESSION["role"] = array_shift(array_shift($data));

  setcookie("show_name", $_COOKIE["show_name"], time()+14*24*60*60, $cookie_path);
  setcookie("email", $_COOKIE["email"], time()+14*24*60*60, $cookie_path);
  setcookie("permanent_login", $_COOKIE["permanent_login"], time()+14*24*60*60, $cookie_path, null, false, true);
  
  if (isSet($_GET["return2web"]))
    $header = "../../";
  else
    $header = "../letters.php";
}
else {
  setcookie("permanent_login", null, time()-60, $cookie_path, null, false, true);
  $header = "../?unauthorized";
}

header("Location: ".$header);
exit ("<a href=\"".$header."\" title=\"continue\">&rarr;</a>");
?>