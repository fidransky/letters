<h1>Můj profil</h1>

<?php
// ukládání uživatele
if (isSet($_POST["ulozit"])) {
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
$id = $_SESSION["id"];
include ("uzivatele_edit.php");

// smazání účtu
echo "<h3 id=\"smazat\">Smazat účet</h3>";

if (isSet($_POST["smazat"])) {
  $hash = substr(md5(generate_password(5)), 0, 20);
  $save = save_data(array("verification_string" => $hash), "uzivatele", "id='".$user["id"]."'");

  if ($save == true) {
    require (FCE_DIR."class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->SetFrom("noreply@".str_replace("www.", null, $_SERVER["SERVER_NAME"]), $lrs["title"]);
    $mail->AddAddress($user["email"]);
    $mail->CharSet = "UTF-8";
    $mail->Subject = "Smazání účtu na ".$lrs["title"];
    $mail->MsgHTML("<p>Dobrý den,<br>na webu <a href=\"".$lrs["address"]."\" target=\"_blank\">".$lrs["title"]."</a> jste požádal/a o smazání účtu. Akce bude dokončena na <a href=\"".$lrs["address"]."/uzivatele/?delete=".$user["id"]."&hash=".$hash."\" target=\"_blank\">tomto odkazu</a>.<br>Pokud se vás žádost netýká, ignorujte ji prosím.</p><p><small>Tato zpráva byla vygenerována automaticky, neodpovídejte na ni prosím.</small></p>");
    $result = $mail->Send();

    if ($result === true) echo "<p class=\"success\">Ověřovací e-mail byl úspěšně odeslán.</p><p>Na adresu &bdquo;".$user["email"]."&ldquo; vám přijde zpráva s odkazem pro dokončení akce.</p>";
    else echo "<p class=\"error\">Ověřovací e-mail nebyl odeslán.</p>";
  }
  else
    echo "<p class=\"error\">Ověřovací e-mail nebyl odeslán.</p>";
}
?>

<form method="post" action="#smazat">
<p>
<input type="submit" name="smazat" value="Smazat" <?php if ($user["role"] == "admin" and get_count("uzivatele", "role='admin'") == 1) echo "title=\"před smazáním svého účtu musíte zvolit jiného administrátora\" disabled"; ?>>
</p>
</form>