<h1><?php echo __("Zapomenuté heslo"); ?></h1>

<?php
// zpracování formuláře
if (isSet($_POST["form_posted"])) {
if (empty($_POST["data"]))
  echo "<p class=\"error\">".__("Vyplňte, prosím, formulář.")."</p>";
else {
  $id = mysql_real_escape_string($_POST["data"]);
  
  if (filter_var($id, FILTER_VALIDATE_EMAIL) === false) $where = "username='".$id."'";
  else $where = "email='".$data."'";
  
  $data = get_data("id, email", "uzivatele", array("where" => $where), "row");
  if ($data === false)
    echo "<p class=\"error\">".__("Uživatel se zadaným údajem není v databázi.")."</p>";
  else {
    list($id, $email) = array_shift($data);

    $hash = substr(md5(generate_password(5)), 0, 20);
    $save = save_data(array("verification_string" => $hash), "uzivatele", "id='".$id."'");

    if ($save == true) {
      require (FCE_DIR."class.phpmailer.php");
      $mail = new PHPMailer();
      $mail->SetFrom("noreply@".str_replace("www.", null, $_SERVER["SERVER_NAME"]), $lrs["title"]);
      $mail->AddAddress($email);
      $mail->CharSet = "UTF-8";
      $mail->Subject = "Obnovení hesla k účtu na ".$lrs["title"];
      $mail->MsgHTML("<p>Dobrý den,<br>na webu <a href=\"".$lrs["address"]."\" target=\"_blank\">".$lrs["title"]."</a> jste požádal/a o obnovení hesla k vašemu účtu. Nové heslo vám bude přiděleno na <a href=\"".$lrs["address"]."/uzivatele/zapomenute_heslo?id=".$id."&hash=".$hash."\" target=\"_blank\">tomto odkazu</a>.<br>Pokud se vás žádost netýká, ignorujte ji prosím.</p><p><small>Tato zpráva byla vygenerována automaticky, neodpovídejte na ni prosím.</small></p>");
      $result = $mail->Send();

      if ($result === true) echo "<p class=\"success\">".__("Ověřovací e-mail byl úspěšně odeslán.")."</p><p>Na adresu &bdquo;".$email."&ldquo; vám přijde zpráva s odkazem pro dokončení akce.</p>";
      else echo "<p class=\"error\">".__("Ověřovací e-mail nebyl odeslán.")."</p>";
    }
    else echo "<p class=\"error\">".__("Ověřovací e-mail nebyl odeslán.")."</p>";
  }
}}

// ověření uživatele
if (isSet($_GET["id"]) and isSet($_GET["hash"])) {
if (empty($_GET["id"]) or empty($_GET["hash"]))
  echo "<p class=\"error\">".__("Chybí údaje pro nastavení nového hesla.")."</p>";
else {
  $id = intval($_GET["id"]);
  $hash = mysql_real_escape_string($_GET["hash"]);

  $data = get_data("username, verification_string", "uzivatele", array("where" => "id='".$id."'"), "row");
  if ($data === false)
    echo "<p class=\"error\">".__("Uživatel se zadaným ID není v databázi.")."</p>";
  else {
    list($username, $overeni) = array_shift($data);
    
    if ($hash != $overeni)
      echo "<p class=\"error\">".__("Ověřovací řetězce navzájem nesouhlasí.")."</p>";
    else {
      $password = generate_password();
      $data["salt"] = generate_password(5);
      $data["password"] = sha1($data["salt"].$password);
      
      $save = save_data($data, "uzivatele", "id='".$id."'");
      if ($save === true)
        echo "<p class=\"success\">".__("Heslo bylo úspěšně obnoveno.")."</p><p>Vaše nové přihlašovací údaje jsou:<ul><li><b>přihlašovací jméno:</b> &bdquo;".$username."&ldquo;<li><b>heslo:</b> &bdquo;".$password."&ldquo;</ul></p>";
      else
        echo "<p class=\"error\">".__("Heslo nebylo obnoveno.")."</p>";
    }
  }
}}


// úvodní zobrazení
echo "<p>".__("Pro obnovení hesla zadejte Váš registrovaný e-mail nebo uživatelské jméno.")."</p>";
?>

<form method="post" id="lost-password">
<p>
<input type="text" name="data" value="<?php echo $_POST["data"]; ?>" size="30" required autofocus>
<input type="submit" name="form_posted" value="<?php echo __("Odeslat"); ?>">
</p>

</form>