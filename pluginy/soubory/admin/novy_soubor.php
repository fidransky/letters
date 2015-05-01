<h1>Nový soubor</h1>

<?php check_user2("upload_souboru", true); ?>

<style>
#progress {
  width: 500px;
  border: 1px solid #C6D880;
}

#progress div {
  max-width: 490px;
  color: #529214;
  padding: 5px;
  background-color: #E6EFC2;
}

.dropbox {
  width: 100%;
  height: 120px;
  text-align: center;
  line-height: 1.5;
  margin-bottom: 20px;
  padding-top: 40px;
  border: 2px dashed silver;
  border-radius: 10px;
}

.dropbox:first-line { font: 1.5em calibri; }

.dropbox.hover { border-color: gray; }
</style>

<?php
$set = get_settings("group=soubory");

// nahrávání
if (isset($_POST["upload"]))
  include (PLUGINS_DIR."soubory/uploader/uploader.php");
?>

<div id="info" style="display: none;">
  <div id="progress"><div></div></div>
  <div id="result"></div>
</div>


<p>
<?php echo sprintf("Maximální velikost nahrávaného souboru je %s kB.", $set["files_max_size"]); ?><br>
</p>


<form method="post" enctype="multipart/form-data">
<p>
  <input type="radio" name="up_type" value="onlyup" id="rad_onlyup" checked> <label for="rad_onlyup">pouze upload</label><br>
  <input type="radio" name="up_type" value="system" id="rad_system"> <label for="rad_system">systémový soubor</label><br>
  <?php
  $global = array();
  include_plugin_admin(false, array("action" => "radio"), $global, array("soubory"));
  ?>
</p>


<!-- pouze upload -->
<div id="onlyup">

<script>
// test for drag'n'drop and multiple file upload support
if (('draggable' in document.createElement('span')) && ('multiple' in document.createElement('input')))
  document.write('<div class="dropbox"><?php echo "Přetáhněte soubory sem"; ?><br>nebo<br><input type="file" name="soubory[]" size="30" multiple></div>');

// fallback for older browsers and IE < 10
else
  document.write('<input type="file" name="soubory[]" size="30"><br>');
</script>
<noscript>
  <input type="file" name="soubory[]" size="30"><br>
</noscript>
</div>


<!-- systémové soubory -->
<div id="system" style="display: none;">

<h4>Nahrát jako&hellip;</h4>
<p>
<label for="rad_plugin">Plugin:</label> <input type="radio" id="rad_plugin" name="as_what" value="plugin" checked> <small>nahrávejte celý stažený &bdquo;zip&ldquo; archiv</small><br>
</p>

<p>
<label for="rad_template">Vzhled:</label> <input type="radio" id="rad_template" name="as_what" value="template"> <small>nahrávejte celý stažený &bdquo;zip&ldquo; archiv</small><br>
</p>

<p>
<label for="rad_gravatar">Výchozí gravatar:</label> <input type="radio" id="rad_gravatar" name="as_what" value="gravatar"> <small>obrázek zobrazený, pokud komentátor není zaregistrován na <a href="http://www.gravatar.com" target="_blank">Gravatar.com</a></small><br>
<?php
if (file_exists("../soubory/gravatar.jpg"))
  echo "<span class=\"success small_label\"><a href=\"../soubory/gravatar.jpg\" target=\"_blank\">Gravatar</a> už existuje</span><br>";
?>
</p>

<p>
<label for="rad_favicon">FavIcon</label>: <input type="radio" id="rad_favicon" name="as_what" value="favicon"> <small>můžete využít online editor <a href="http://www.degraeve.com/favicon/" target="_blank">DeGraeve</a></small><br>
<?php
if (file_exists("../favicon.ico"))
  echo "<span class=\"success small_label\"><a href=\"../favicon.ico\" target=\"_blank\">FavIcon</a> už existuje</span><br>";
?>
</p>

<script>
if ('draggable' in document.createElement('span'))
  document.write('<div class="dropbox"><?php echo "Přetáhněte soubor sem"; ?><br>nebo<br><input type="file" name="soubory[]" size="30"></div>');
else
  document.write('<input type="file" name="soubory[]" size="30"><br>');
</script>
<noscript>
  <input type="file" name="soubory[]" size="30"><br>
</noscript>
</div>


<!-- pluginy -->
<?php
$global = array();
include_plugin_admin(false, array("action" => "show"), $global, array("soubory"));
?>


<input type="submit" name="upload" value="Nahrát">
</form>

<script>
$('input[type=radio][name=up_type]').click(function(){
  var val = $(this).val();
  $('#'+ val).show();
  
  $('input[type=radio][name=up_type]').each(function(){
    if ($(this).val() != val) $('#'+ $(this).val()).hide();
  });
});
</script>

<script src="<?=PLUGINS_DIR?>soubory/uploader/jquery.html5uploader.js"></script>
<script>
$(document).ready(function(){
  $(".dropbox").html5uploader({
    'pluginsDir': '<?php echo PLUGINS_DIR; ?>',
    'appendToUrl': '&sort=<?php echo $set["files_sort"]; ?>&allowed_types=<?php echo $set["files_allowed_types"]; ?>',
    'maxFileSize': '<?php echo $set["files_max_size"]; ?>',
    'allowedFileExtensions': '<?php echo $set["files_allowed_types"]; ?>'
  });
});
</script>