<h1>Uživatelé</h1>

<?php
check_user2("upravy_uzivatelu", true);

// schválení uživatele
if (isSet($_POST["schvalit"])) {
  $update = save_data(array("status" => "schvaleny"), "uzivatele", "id='".$_POST["id"]."'");
  if ($update === true) echo "<p class=\"success\">Účet uživatele byl úspěšně schválen.</p>";
  else echo "<p class=\"error\">Účet uživatele nebyl schválen.</p>";
}

// mazání uživatele
elseif (isSet($_POST["smazat"])) {
  $delete = delete_data("uzivatele", "id='".$_POST["id"]."'");
  if ($delete === true) echo "<p class=\"success\">Účet uživatele byl úspěšně smazán.</p>";
  else echo "<p class=\"error\">Účet uživatele nebyl smazán.</p>";
}

// ukládání uživatele
elseif (isSet($_POST["ulozit"])) {
if ((isSet($_POST["username"]) and empty($_POST["username"])) or empty($_POST["username2"]) or empty($_POST["email"]))
  echo "<p class=\"error\">Musíte vyplnit všechny povinné údaje.</p>";
else {
  $id = $_POST["id"];
  
  $data["username"]     = (isSet($_POST["username"]) ? $_POST["username"] : $_POST["username2"]);
  $data["role"]         = (isSet($_POST["role"]) ? $_POST["role"] : $_POST["role2"]);
  $data["nickname"]     = $_POST["nickname"];
  $data["name"]         = $_POST["name"];
  $data["surname"]      = $_POST["surname"];
  $data["show_name"]    = $_POST["show_name"];
  $data["text"]         = $_POST["text"];
  $data["email"]        = $_POST["email"];
  $data["web"]          = $_POST["web"];
  
  // nové heslo
  if (!empty($_POST["password1"]) and !empty($_POST["password2"])) {
    if ($_POST["password1"] != $_POST["password2"])
      echo "<p class=\"error\">Zadaná nová hesla navzájem nesouhlasí.</p>";
    else {
      include_once (PLUGINS_DIR."uzivatele/scripts/get_user_info.php");
      $user = get_user_info($id, "salt");
      $data["password"] = sha1($user["salt"].$_POST["password1"]);
    }
  }

  include_plugin_admin(false, array("action" => "save"), $data, array("uzivatele"));
  
  $update = save_data($data, "uzivatele", "id='".$id."'");
  if ($update === true) echo "<p class=\"success\">Uživatelská data byla úspěšně uložena.</p>";
  else echo "<p class=\"error\">Uživatelská data nebyla uložena.</p>";
}}


// úvodní zobrazení
if (get_count("uzivatele", "NOT id='".$_SESSION["id"]."'") == 0)
  echo "<p class=\"info\">Žádný uživatel není zaregistrován.</p>";
else {

// schvalování nových uživatelů
if (get_count("uzivatele", "NOT id='".$_SESSION["id"]."' AND status='cekajici'") != 0) {
?>
<h3>Schválit uživatele</h3>

<form method="post">
<select name="id" size="1">
<?php
foreach (get_data("id,username", "uzivatele", array("where" => "NOT id='".$_SESSION["id"]."' AND status='cekajici'"), "assoc") as $user)
  echo "<option value=\"".$user["id"]."\">".$user["username"];
?>
</select>

<input type="submit" name="schvalit" value="schválit">
</form>

<h3>Uživatelé</h3>

<?php } ?>


<form method="post">
<select name="id" size="1">
<?php
foreach (get_data("id,username,show_name", "uzivatele", array("where" => "NOT id='".$_SESSION["id"]."'"), "assoc") as $user)
  echo "<option value=\"".$user["id"]."\">".$user["show_name"]." [".$user["username"]."]";
?>
</select>

<input type="submit" name="upravit" value="upravit">
<input type="submit" name="smazat" value="smazat">
</form>

<?php
}

// úpravy uživatele
if (isSet($_REQUEST["upravit"])) {
  $id = $_REQUEST["id"];

  include (PLUGINS_DIR."uzivatele/admin/uzivatele_edit.php");
}
?>