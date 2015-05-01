<h1>Editor</h1>

<?php
check_user2("admin", true);

function scan($slozka=PLUGINS_DIR, $files=false) {
  $filter = "*";
  if ($files == false) $flag = GLOB_ONLYDIR;

  $return = array();
  $glob = (function_exists("glob") ? glob($slozka.$filter, $flag) : glob_alternative($slozka, $filter, $flag));
  foreach ($glob as $foo) {
    if ($files != false and is_dir($foo)) $return = array_merge($return, scan($foo."/", $files));
    else $return[] = $foo;
  }
  
  return $return;
}

// uložení dat
if (isSet($_POST["save"])) {
  $data = $_POST["code"];
  $file = $_POST["file"];
  
  $soubor = FOpen($file, "w");
  $zapis = FWrite($soubor, $data);
  FClose($soubor);
  
  if ($zapis !== false) echo "<p class=\"success\">Soubor pluginu byl úspěšně uložen.</p>";
  else echo "<p class=\"error\">Soubor pluginu nebyl uložen.</p>";
}

// výběr pluginu 
$plugins = scan();

if (empty($plugins))
  echo "<p>Žádný plugin k editaci.</p>";
else {
?>

<form method="post">
<p>
Plugin:<br>
<select name="plugin">
<?php
foreach ($plugins as $path) {
  $alias = str_replace(PLUGINS_DIR, null, $path);

  if (file_exists(PLUGINS_DIR.$alias."/info.php")) {
    include (PLUGINS_DIR.$alias."/info.php");
    $echo = $plugin["name"];
  }
  else
    $echo = ucfirst($alias);
  
  echo "<option value=\"".$path."\" ".((isSet($_POST["plugin"]) and $_POST["plugin"] == $path) ? "selected" : null).">".$echo;
}
?>
</select>

<input type="submit" name="edit" value="Zvolit">
</p>
</form>
<?php
}

// výběr souboru
if (isSet($_POST["edit"])) {
  $files = scan($_POST["plugin"]."/", true);
?>
<form method="post">
<p>
Soubor pluginu:<br>
<select name="file">
  <?php
  foreach ($files as $file)
    echo "<option value=\"".$file."\">".str_replace($_POST["plugin"]."/", null, $file);
  ?>
</select>

<input type="hidden" name="edit" value="true">
<input type="hidden" name="plugin" value="<?php echo $_POST["plugin"]; ?>">

<input type="submit" name="edit2" value="Editovat">
</p>
</form>
<?php
}

// výběr dat
if (isSet($_POST["edit2"])) {
  $text = file_get_contents($_POST["file"]);
?>

<link rel="stylesheet" href="scripts/codemirror/codemirror.css">
<script src="scripts/codemirror/codemirror.js"></script>
<script src="scripts/codemirror/matchbrackets.js"></script>
<script src="scripts/codemirror/htmlmixed.js"></script>
<script src="scripts/codemirror/xml.js"></script>
<script src="scripts/codemirror/javascript.js"></script>
<script src="scripts/codemirror/css.js"></script>
<script src="scripts/codemirror/clike.js"></script>
<script src="scripts/codemirror/php.js"></script>

<form method="post">
<p>
<textarea id="code" name="code" wrap="off"><?php echo stripslashes(htmlspecialchars($text)); ?></textarea>
</p>

<input type="hidden" name="edit" value="true">
<input type="hidden" name="plugin" value="<?php echo $_POST["plugin"]; ?>">

<input type="hidden" name="file" value="<?php echo $_POST["file"]; ?>">
<input type="submit" name="save" value="Uložit">
</form>

<script>
var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
  mode: "application/x-httpd-php",
  tabSize: 2,
  indentWithTabs: true,
  lineNumbers: true,
  autofocus: true,
  matchBrackets: true,
  enterMode: "keep",
  tabMode: "shift"
});
</script>

<?php } ?>