<h1>Nový uživatel</h1>

<?php check_user2("tvorba_uzivatelu", true); ?>

<script src="<?php echo PLUGINS_DIR; ?>uzivatele/scripts/jquery.passwordStrengthMeter.js"></script>
<link href="<?php echo PLUGINS_DIR; ?>uzivatele/scripts/check.css" rel="stylesheet">

<script>
$(document).ready(function() {
  // ověření uživatelského jména
  $('#username').keyup(function(){  
    if ($('#username').val().length >= 3)
      check_availability();  
  });

	// detekce síly hesla  
	$('#username, #password1').keyup(function(){
    $('#result').html(passwordStrength($('#password1').val(), $('#username').val()));
  });
  
  // ověření shodnosti hesel
  $('#password1').keyup(function(){
    $('#same').empty();
  });
  
  $('#password2').keyup(function(){
    if ($(this).val() != $('#password1').val()) $('#same').html('<span class="weak">hesla se neshodují</span>');
    else $('#same').html('<span class="strong">hesla se shodují</span>');
  });
});

function check_availability() {
  var username = $('#username').val();

  $.post("<?php echo PLUGINS_DIR; ?>uzivatele/scripts/username_availability.ajax.php",
  { username: username },
  function(result){
    username2 = $('#username2').val();
    if (result >= 1 && username != username2) { $('#username_availability').removeClass('success').addClass('error').html('Uživatelské jméno <b>'+ username +'</b> je obsazeno. Zvolte si, prosím, jiné.'); }
    else { $('#username_availability').removeClass('error').addClass('success').html('Uživatelské jméno <b>'+ username +'</b> je volné.'); }
  });  
}
</script>

<?php
// ukládání uživatele
if (isSet($_POST["ulozit"])) {
if (empty($_POST["username"]) or empty($_POST["email"]) or empty($_POST["password1"]) or empty($_POST["password1"]))
  echo "<p class=\"error\">Musíte vyplnit všechny povinné údaje.</p>";
else {
  $data["username"] = $_POST["username"];
  
  // ověření délky uživatelského jména
  if (strlen($data["username"]) < 4)
    echo "<p class=\"error\">".__("Uživatelské jméno je příliš krátké.")."</p>";
  else {
  
  // ověření duplicity uživatelského jména
  if (get_count("uzivatele", "username='".mysql_real_escape_string($data["username"])."'") != 0)
    echo "<p class=\"error\">".__("Uživatelské jméno je obsazeno. Zvolte si, prosím, jiné.")."</p>";
  else {

  $data["email"] = $_POST["email"];
  
  // ověření platnosti e-mailu
  if (filter_var($data["email"], FILTER_VALIDATE_EMAIL) === false)
    echo "<p class=\"error\">".__("Zadaný e-mail není platný.")."</p>";
  else {
  
  // ověření správnosti hesla
  if ($_POST["password1"] != $_POST["password2"])
    echo "<p class=\"error\">Zadaná nová hesla navzájem nesouhlasí.</p>";
  else {
    // ukládání dat
    $data["salt"] = generate_password(5);
    $data["password"] = sha1($data["salt"].$_POST["password1"]);
    $data["role"]     = $_POST["role"];
    $data["name"]     = $_POST["name"];
    $data["surname"]  = $_POST["surname"];
    $data["nickname"] = $_POST["nickname"];
    $data["web"]      = $_POST["web"];
    $data["text"]     = $_POST["text"];
    $data["status"]   = "schvaleny";
    $data["registration_date"]    = date("Y-m-j H:i:s", time());
    $data["verification_string"]  = substr(md5(generate_password(5)), 0, 20);

    $save = save_data($data, "uzivatele");
    if ($save === true) {
      echo "<p class=\"success\">Účet byl úspěšně vytvořen.</p>";
      $_POST = array();
    }
    else
      echo "<p class=\"error\">Účet nebyl vytvořen.</p>";
  }}}}
}}
?>

<form method="post" name="formular">

<p>
  Uživatelské jméno: <small>později už nejde změnit</small><br>
  <input type="text" name="username" value="<?php if (isSet($_POST["username"])) echo $_POST["username"]; ?>" size="30" maxlength="50" id="username" class="input" required autofocus><br>
  <span class="small_label" id="username_availability"></span>
</p>

<p>
  Role:<br>
  <select name="role" size="1">
  <?php
  $trans = array("admin" => "administrátor", "author" => "přispěvatel", "reader" => "čtenář");
  foreach (explode(", ", array_shift(get_settings("rights_roles", "row"))) as $role) {
    if ($role == "anonymous") continue;
    echo "<option value=\"".$role."\"".($role == $default_role ? " selected" : null).">".strtr($role, $trans);
  }
  ?>
  </select>
</p>
<input type="submit" name="ulozit" value="Uložit">


<h3>Osobní údaje</h3>
<p>
  Jméno:<br>
  <input type="text" name="name" value="<?php if (isSet($_POST["name"])) echo $_POST["name"]; ?>" size="20" maxlength="50">
</p>

<p>
  Příjmení:<br>
  <input type="text" name="surname" value="<?php if (isSet($_POST["surname"])) echo $_POST["surname"]; ?>" size="20" maxlength="50">
</p>

<p>
  Přezdívka:<br>
  <input type="text" name="nickname" value="<?php if (isSet($_POST["nickname"])) echo $_POST["nickname"]; ?>" size="20" maxlength="50">
</p>

<p>
  Osobní popis:<br>
  <?php include_plugins("text editor", array("action" => "show-over", "editor" => "forum")); ?>
  <textarea name="text" id="text" cols="100" rows="5"><?php if (isSet($_POST["text"])) echo $_POST["text"]; ?></textarea><br>
  <?php include_plugins("text editor", array("action" => "show-under", "editor" => "forum")); ?>
</p>

<input type="submit" name="ulozit" value="Uložit">


<h3>Kontaktní údaje</h3>
<p>
  E-mail: <small>kontaktní e-mail, nebude zobrazen</small><br>
  <input type="email" name="email" value="<?php if (isSet($_POST["email"])) echo $_POST["email"]; ?>" size="30" maxlength="50" required>
</p>

<p>
  Web:<br>
  <input type="url" name="web" value="<?php if (isSet($_POST["web"])) echo $_POST["web"]; ?>" size="30" maxlength="50" placeholder="http://">
</p>

<input type="submit" name="ulozit" value="Uložit">


<h3>Heslo</h3>
<p>
  Heslo:<br>
  <input type="password" name="password1" id="password1" size="20" maxlength="20" required>
  <span id="result"></span>
</p>

<p>
  Heslo znovu: <small>pro kontrolu napište heslo ještě jednou</small><br>
  <input type="password" name="password2" id="password2" size="20" maxlength="20" required>
  <span id="same"></span>
</p>

<input type="submit" name="ulozit" value="Uložit">
</form>