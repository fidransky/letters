<?php
if ($action == "content") {

if (!$_SESSION["log"]) {
?>

<form action="<?php echo PLUGINS_DIR; ?>uzivatele/scripts/return.php" method="post" class="login" data-label="klasicky">
<p>
<label for="username"><?php echo __("Jméno"); ?>:</label><br>
<input type="text" id="username" name="username" size="20" class="input" required autofocus>
</p>

<p>
<label for="password"><?php echo __("Heslo"); ?>:</label><br>
<input type="password" id="password" name="password" size="20" title="zadejte heslo pro administraci" required>
&nbsp;<small><a href="uzivatele/zapomenute_heslo"><?php echo __("zapomenuté heslo?"); ?></a></small>
</p>

<input type="hidden" name="return2web" value="1" checked>
<input type="submit" name="login" value="<?php echo __("Přihlásit"); ?>">
</form>

<?php
}

}
elseif ($action == "admin") {
?>

<style>
#zapomenute_heslo {
  float: right;
  font-size: 0.8em;
  font-style: italic;
  color: #BBB;
  margin-top: 2px;
}
</style>

<form action="<?php echo PLUGINS_DIR; ?>uzivatele/scripts/return.php" method="post" class="login" data-label="klasicky">
<p>
<label for="username">Jméno:</label><br>
<input type="text" name="username" id="username" size="20" tabindex="1" required autofocus>
</p>

<p>
<label for="password">Heslo:</label><a href="../uzivatele/zapomenute_heslo" id="zapomenute_heslo">zapomenuté heslo?</a><br>
<input type="password" id="password" name="password" size="20" title="zadejte heslo pro administraci" tabindex="2" required>
</p>

<input type="submit" name="login" value="Přihlásit" tabindex="3">
</form>

<?php } ?>