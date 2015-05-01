<?php
if (isSet($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];
  
  // nebyl zadán alespoň jeden z přihlašovacích údajů
  if (empty($username) or empty($password)) {
    header("Location: ../../../letters/index.php?unauthorized");
    exit ("<a href=\"../../../letters/index.php?unauthorized\" title=\"continue\">&rarr;</a>");
  }

  include ("../../../letters/config.php");
  include ("../../../funkce/get_data.php");
  include ("../../../funkce/save_data.php");
  
  $user = get_data("id,password,role,show_name,email,salt,verification_string", "uzivatele", array("where" => "username='".mysql_real_escape_string($username)."' AND status='schvaleny'"), "assoc");
  
  // uživatel neexistuje
  if ($user == false) {
    header("Location: ../../../letters/index.php?no_user");
    exit ("<a href=\"../../../letters/index.php?no_user\" title=\"continue\">&rarr;</a>");
  }
  
  $user = array_shift($user);
  
  // hesla se neshodují
  if (sha1($user["salt"].$password) != $user["password"]) {
    header("Location: ../../../letters/index.php?wrong_password");
    exit ("<a href=\"../../../letters/index.php?wrong_password\" title=\"continue\">&rarr;</a>");
  }
    
  @save_data(array("last_login" => date("Y-m-d H:i:s")), "uzivatele", "id='".$user["id"]."'");

  session_start();
  $_SESSION["id"] = $user["id"];
	$_SESSION["log"] = true;
	$_SESSION["role"] = $user["role"];
  
  $cookie_path = array_shift(explode("?", str_replace("pluginy/uzivatele/scripts/return.php", null, $_SERVER["REQUEST_URI"])));
  setcookie("show_name", $user["show_name"], time()+14*24*60*60, $cookie_path);
  setcookie("email", $user["email"], time()+14*24*60*60, $cookie_path);
  setcookie("permanent_login", $user["id"].";".$user["verification_string"], time()+14*24*60*60, $cookie_path, null, false, true);
  
  if (isSet($_POST["return2web"]))
    $header = (preg_match("/logout/", $_SERVER["HTTP_REFERER"]) ? substr($_SERVER["HTTP_REFERER"], 0, -7) : $_SERVER["HTTP_REFERER"]);
  else
    $header = "../../../letters/letters.php";

  header("Location: ".$header);
  exit ("<a href=\"".$header."\" title=\"continue\">&rarr;</a>");
}

header("Location: ../../../letters/index.php?unauthorized");
exit ("<a href=\"../../../letters/index.php?unauthorized\" title=\"continue\">&rarr;</a>");
?>