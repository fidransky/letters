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
// výběr současných dat z databáze
include (PLUGINS_DIR."uzivatele/scripts/get_user_info.php");
$user = get_user_info($id);
?>

<form method="post" name="formular">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="username2" id="username2" value="<?php echo $user["username"]; ?>">
<input type="hidden" name="role2" value="<?php echo $user["role"]; ?>">
<input type="hidden" name="show_name2" value="<?php echo $user["show_name"]; ?>">

<p>
  Uživatelské jméno: <?php if ($user["username"] != "admin") echo "<small>nejde změnit</small>"; ?><br>
  <input type="text" name="username" value="<?php echo $user["username"]; ?>" size="30" maxlength="50" id="username" class="input" required <?php if ($user["username"] != "admin") { echo ("disabled"); } ?>><br>
  <span class="small_label" id="username_availability"></span>
</p>

<?php if (check_user2("admin")) { ?>

<p>
  IP adresa:<br>
  <span style="color: gray;">
  <?php echo (!empty($user["ip"]) ? $user["ip"] : "<em>není zaznamenána</em>"); ?>
  </span>
</p>

<p>
  Role:<br>
  <select name="role" size="1" <?php if ($user["role"] == "admin" and get_count("uzivatele", "role='admin'") == 1) echo "title=\"před změnou své hodnosti musíte zvolit jiného administrátora\" disabled"; ?>>
  <?php
  $pole = array("admin" => "administrátor", "author" => "přispěvatel", "reader" => "čtenář");
  foreach ($pole as $key => $value)
    echo ("<option value=\"".$key."\" ".($user["role"] == $key ? "selected" : "").">".$value);
  ?>
  </select>
</p>

<?php } ?>

<input type="submit" name="ulozit" value="Uložit">


<h3>Osobní údaje</h3>
<p>
  Jméno:<br>
  <input type="text" name="name" value="<?php echo $user["name"]; ?>" size="20" maxlength="50">
</p>

<p>
  Příjmení:<br>
  <input type="text" name="surname" value="<?php echo $user["surname"]; ?>" size="20" maxlength="50">
</p>

<p>
  Přezdívka:<br>
  <input type="text" name="nickname" value="<?php echo $user["nickname"]; ?>" size="20" maxlength="50">
</p>

<p>
  Zobrazit ve formátu: <small>jak chcete jméno zobrazit na webu</small><br>
  <select name="show_name" size="1">
<?php
switch ($user["show_name"]) {
  case $user["nickname"]:
    $s1 = "selected";
    break;
  case $user["name"]:
    $s2 = "selected";
    break;
  case $user["name"]." ".$user["surname"]:
    $s3 = "selected";
    break;
  case $user["surname"]." ".$user["name"]:
    $s4 = "selected";
    break;
  default:
    $s0 = "selected";
}

echo ("<option value=\"".$user["username"]."\" ".$s0.">".$user["username"]);
if (!empty($user["nickname"]))
  echo ("<option value=\"".$user["nickname"]."\" ".$s1.">".$user["nickname"]);
if (!empty($user["name"]))
  echo ("<option value=\"".$user["name"]."\" ".$s2.">".$user["name"]);
if (!empty($user["name"]) and !empty($user["surname"])) {
  echo ("<option value=\"".$user["name"]." ".$user["surname"]."\" ".$s3.">".$user["name"]." ".$user["surname"]);
  echo ("<option value=\"".$user["surname"]." ".$user["name"]."\" ".$s4.">".$user["surname"]." ".$user["name"]);
}
?>
  </select>
</p>

<p>
  Osobní popis:<br>
  <?php include_plugins("text editor", array("action" => "show-over", "editor" => "forum")); ?>
  <textarea name="text" id="text" cols="100" rows="5"><?php echo $user["text"]; ?></textarea><br>
  <?php include_plugins("text editor", array("action" => "show-under", "editor" => "forum")); ?>
</p>

<input type="submit" name="ulozit" value="Uložit">


<h3>Kontaktní údaje</h3>
<p>
  E-mail: <small>kontaktní e-mail, nebude zobrazen</small><br>
  <input type="email" name="email" value="<?php echo $user["email"]; ?>" size="30" maxlength="50" required>
</p>

<p>
  Web:<br>
  <input type="url" name="web" value="<?php echo $user["web"]; ?>" size="30" maxlength="50">
</p>

<input type="submit" name="ulozit" value="Uložit">

<h3 id="connected_accounts">Propojené účty</h3>
<?php
$global = array();
$i = include_plugin_admin(false, array("action" => "show", "user" => $user), $global, array("uzivatele"));
if ($i == 0) echo "(žádné propojené účty)";
?>

<h3 class="cleaner">Nové heslo</h3>
<p>
  Nové heslo:<br>
  <input type="password" name="password1" id="password1" size="20" maxlength="20">
  <span id="result"></span>
</p>

<p>
  Nové heslo znovu: <small>pro kontrolu napište nové heslo ještě jednou</small><br>
  <input type="password" name="password2" id="password2" size="20" maxlength="20">
  <span id="same"></span>
</p>

<input type="submit" name="ulozit" value="Uložit">
</form>