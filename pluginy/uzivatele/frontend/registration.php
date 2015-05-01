<h1><?php echo __("Registrace"); ?></h1>

<?php
$result = false;

list($registration, $approve, $default_role, $check_via_email) = get_settings("users_registration, users_approve, users_default_role, users_check_via_email", "row");


// registrace uživatele
if (isSet($_POST["registration_posted"])) {
if (empty($_POST["username"]) or empty($_POST["email"]) or empty($_POST["heslo"])) {
  if (empty($_POST["username"]))
    echo "<p class=\"error\">".__("Nevyplnil/a jste uživatelské jméno.")."</p>";
  elseif (empty($_POST["email"]))
    echo "<p class=\"error\">".__("Nevyplnil/a jste e-mail.")."</p>";
  elseif (empty($_POST["heslo"]))
    echo "<p class=\"error\">".__("Nevyplnil/a jste heslo.")."</p>";
}
else {
  // ověření délky uživatelského jména
  $data["username"] = $_POST["username"];
  if (strlen($data["username"]) < 4)
    echo "<p class=\"error\">".__("Uživatelské jméno je příliš krátké.")."</p>";
  else {
  
  // ověření duplicity uživatelského jména
  if (get_count("uzivatele", "username='".mysql_real_escape_string($data["username"])."'") != 0)
    echo "<p class=\"error\">".__("Uživatelské jméno je obsazeno. Zvolte si, prosím, jiné.")."</p>";
  else {

  // ověření platnosti e-mailu
  $data["email"] = $_POST["email"];
  if (filter_var($data["email"], FILTER_VALIDATE_EMAIL) === false)
    echo "<p class=\"error\">".__("Zadaný e-mail není platný.")."</p>";
  else {
    include_once (FCE_DIR."save_data.php");
    
    // ukládání dat
    $data["salt"] = generate_password(5);
    $data["password"] = sha1($data["salt"].$_POST["heslo"]);
    $data["role"] = $default_role;
    $data["show_name"] = $data["username"];
    $data["registration_date"] = date("Y-m-j H:i:s", time());
    $data["ip"] = $_SERVER["REMOTE_ADDR"];
    $data["verification_string"] = substr(md5(generate_password(5)), 0, 20);
    
    if ($check_via_email == 1)
      $data["status"] = "check_via_email";
    else
      $data["status"] = ($approve == 1 ? "cekajici" : "schvaleny");

    $result = save_data($data, "uzivatele");
    if ($result === true) {
      $id = mysql_insert_id();
      
      // odeslání ověřovacího e-mailu
      if ($check_via_email == 1) {
        require (FCE_DIR."class.phpmailer.php");
        $mail = new PHPMailer();
        $mail->SetFrom("noreply@".str_replace("www.", null, $_SERVER["SERVER_NAME"]), $lrs["title"]);
        $mail->AddAddress($data["email"]);
        $mail->CharSet = "UTF-8";
        $mail->Subject = "Ověření registrace na ".$lrs["title"];
        $mail->MsgHTML("<p>Dobrý den,<br>na webu <a href=\"".$lrs["address"]."\" target=\"_blank\">".$lrs["title"]."</a> byla zaznamenána žádost o registraci. Registrace bude dokončena na <a href=\"".$lrs["address"]."/uzivatele/registrace/?id=".$id."&hash=".$data["verification_string"]."\" target=\"_blank\">tomto odkazu</a>.<br>Pokud se vás žádost netýká, ignorujte ji prosím.</p><p><small>Tato zpráva byla vygenerována automaticky, neodpovídejte na ni prosím.</small></p>");
        $result = $mail->Send();
      }

      // výpis výsledku registrace
      if ($result === true) {
        echo "<p class=\"success\">".__("Účet byl úspěšně vytvořen.")."</p>";
        if ($check_via_email == 1)
          echo "<p>".__("Na uvedenou e-mailovou adresu vám přijde zpráva s odkazem pro ověření.")."</p>";
      }
      else {
        include_once (FCE_DIR."delete_data.php");
        @delete_data("uzivatele", "id='".$id."'");
        echo "<p class=\"error\">".__("Účet nebyl vytvořen.")."</p>";
      }
    }
    else
      echo "<p class=\"error\">".__("Účet nebyl vytvořen.")."</p>";
  }}}
}}


// ověření uživatele při registraci
if (isSet($_GET["id"]) and isSet($_GET["hash"])) {
if (empty($_GET["id"]) or empty($_GET["hash"]))
  echo "<p class=\"error\">".__("Chybí údaje pro dokončení registrace.")."</p>";
else {
  $id = intval($_GET["id"]);
  $hash = $_GET["hash"];

  $data = get_data("status, verification_string", "uzivatele", array("where" => "id='".$id."'"), "row");
  if ($data == false)
    echo "<p class=\"error\">".__("Uživatel se zadaným ID není v databázi.")."</p>";
  else {
    list($status, $verification_string) = array_shift($data);
  
    if ($hash != $verification_string)
      echo "<p class=\"error\">".__("Ověřovací řetězce navzájem nesouhlasí.")."</p>";
    else {
      include_once (FCE_DIR."save_data.php");
    
      $data["status"] = ($approve == 1 ? "cekajici" : "schvaleny");
      
      $update_user = save_data($data, "uzivatele", "id='".$id."'");
      if ($update_user === true)
        echo "<p class=\"success\">".__("Registrace byla úspěšně dokončena.")."</p>";
      else
        echo "<p class=\"error\">".__("Registrace nebyla dokončena.")."</p>";
    }
  }
}}


// úvodní zobrazení
if ($result == false) {
  if ($registration == 0)
    echo ("<p class=\"error\">".__("Registrace nových uživatelů v současnosti není možná.")."</p>");
  else {

  if ($approve == 1)
    echo ("<p>".__("Po registraci bude muset účet projít schvalovacím procesem. Přihlásit se tedy nebude možné ihned po registraci.")."</p>");
?>

<form method="post" id="registration">
<p>
<?php echo __("Uživatelské jméno"); ?>:<br>
<input type="text" name="username" value="<?php if (isSet($_POST["username"])) echo $_POST["username"]; else echo strtolower(bez_diakritiky($_COOKIE["show"], false)); ?>" size="30" required autofocus>
</p>

<p>
<?php echo __("E-mail"); ?>: <small><?php echo __("kontaktní e-mail, nebude zobrazen"); ?></small><br>
<input type="email" name="email" value="<?php if (isSet($_POST["email"])) echo $_POST["email"]; else echo $_COOKIE["email"]; ?>" size="30" maxlength="50" required>
</p>

<p>
<?php echo __("Heslo"); ?>:<br>
<input type="password" name="heslo" size="20" maxlength="20" required>
</p>

<input type="submit" name="registration_posted" value="<?php echo __("Vytvořit účet"); ?>"><br>
</form>

<?php }} ?>