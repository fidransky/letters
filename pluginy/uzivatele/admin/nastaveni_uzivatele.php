<h1>Nastavení</h1>

<h2>Uživatelé</h2>

<?php
if (isSet($_POST["uzivatele_posted"])) {
  $data["users_registration"] = (int)isSet($_POST["registration"]);
  $data["users_approve"] = (int)isSet($_POST["approve"]);
  $data["users_default_role"] = $_POST["default_role"];
  $data["users_check_via_email"] = (int)isSet($_POST["check_via_email"]);

  $save = save_settings($data);
  if ($save === true) echo "<p class=\"success\">Nastavení bylo úspěšně uloženo.</p>";
  else echo "<p class=\"error\">Nastavení nebylo uloženo.</p>";
}

// úvodní zobrazení
list($registration, $approve, $default_role, $check_via_email) = get_settings("users_registration, users_approve, users_default_role, users_check_via_email", "row");
?>

<form method="post">
<p>
<label for="registration">Povolit registraci:</label><br>
<input type="checkbox" id="registration" name="registration" value="1" <?php if ($registration == 1) echo "checked"; ?>>
</p>

<p>
<label for="check_via_email">Ověřovat registraci přes e-mail:</label><br>
<input type="checkbox" id="check_via_email" name="check_via_email" value="1" <?php if ($check_via_email == 1) echo "checked"; ?>>
</p>

<p>
<label for="approve">Schvalovat nové uživatele:</label><br>
<input type="checkbox" id="approve" name="approve" value="1" <?php if ($approve == 1) echo "checked"; ?>>
</p>

<p>
<label for="default_role">Výchozí role nových uživatelů:</label><br>
<select id="default_role" name="default_role" size="1">
  <?php
  $trans = array("admin" => "administrátor", "author" => "přispěvatel", "reader" => "čtenář");
  foreach (explode(", ", array_shift(get_settings("rights_roles", "row"))) as $role) {
    if ($role == "anonymous") continue;
    echo "<option value=\"".$role."\"".($role == $default_role ? " selected" : null).">".strtr($role, $trans);
  }
  ?>
</select>
</p>

<input type="submit" name="uzivatele_posted" value="Uložit">
</form>


<h3 id="rights">Práva:</h3>

<?php
// ukládání
if (isSet($_POST["rights_posted"])) {
  $data["rights_".$_POST["role"]] = http_build_query($_POST["rights"]);
  
  $save = save_settings($data);
  if ($save === true) echo "<p class=\"success\">Nastavení bylo úspěšně uloženo.</p>";
  else echo "<p class=\"error\">Nastavení nebylo uloženo.</p>";
}
?>

<form method="post" action="#rights">
<select name="role">
  <?php
  $trans = array("admin" => "administrátor", "author" => "přispěvatel", "reader" => "čtenář", "anonymous" => "anonymní");
  foreach (explode(", ", array_shift(get_settings("rights_roles", "row"))) as $role)
    echo "<option value=\"".$role."\"".($role == $_POST["role"] ? " selected" : null).">".strtr($role, $trans);
  ?>
</select>

<input type="submit" name="edit_rights" value="upravit">
</form>

<?php
if (isSet($_POST["edit_rights"])) {
  $role = $_POST["role"];
  parse_str(array_shift(get_settings("rights_".$role, "row")), $rights);
  
  foreach ($rights as $key => $value)
    $rights[$key] = ($value == 1 ? "checked" : null);
?>

<form method="post" id="permissions">
<fieldset>
<h4>Obecné</h4>
<label for="administrace">vstup do administrace:</label> <input type="checkbox" id="administrace" name="rights[administrace]" value="1" <?php echo $rights["administrace"]; ?>> <small>povolí vstup do administračního rozhraní</small><br>
<label for="nastaveni">úprava nastavení:</label> <input type="checkbox" id="nastaveni" name="rights[nastaveni]" value="1" <?php echo $rights["nastaveni"]; ?>><br>
</fieldset>

<fieldset>
<h4>Uživatelé</h4>
<label for="tvorba_uzivatelu">tvorba nových uživatelů:</label> <input type="checkbox" id="tvorba_uzivatelu" name="rights[tvorba_uzivatelu]" value="1" <?php echo $rights["tvorba_uzivatelu"]; ?>><br>
<label for="upravy_uzivatelu">úpravy uživatelů:</label> <input type="checkbox" id="upravy_uzivatelu" name="rights[upravy_uzivatelu]" value="1" <?php echo $rights["upravy_uzivatelu"]; ?>><br>
<label for="zobrazeni_profilu">zobrazení profilů:</label> <input type="checkbox" id="zobrazeni_profilu" name="rights[zobrazeni_profilu]" value="1" <?php echo $rights["zobrazeni_profilu"]; ?>><br>
</fieldset>

<?php
$global = array();
include_plugin_admin(false, array("action" => "show", "rights" => $rights), $global, array("uzivatele"));
?>

<input type="hidden" name="role" value="<?php echo $role; ?>">

<input type="submit" value="Uložit" name="rights_posted">
</form>

<script>
$('fieldset h4').attr('title', 'kliknutím zaškrtnete všechny').click(function(){
  children = $(this).parent('fieldset').children('input[type=checkbox]');
  
  if (children.prop('checked') == false) children.prop('checked', true);
  else children.prop('checked', false);
});
</script>

<?php } ?>