<h1>Nastavení</h1>

<h2>Úvodní strana</h2>

<?php
if (isSet($_POST["uvodni_posted"])) {
  if (isSet($_POST["check_text"]) and !empty($_POST["text"])) {
    $text = $_POST["text"];
    $soubor = FOpen("../uvodni.php", "w");
    $zapis = FWrite($soubor, $text);
    FClose($soubor);
  }
  else {
    @unlink("../uvodni.php");
    $zapis = true;
  }
  
  $type = $_POST["type"];
  if ($type == "none") $type = null;
  elseif ($type == "page") $type = $_POST["page"];
  else $type .= "=".$_POST[$type];
  
  $update = save_settings(array("template_index" => $type));
  if ($update === true) {
    if ($zapis != false) echo "<p class=\"success\">Úvodní strana byla úspěšně změněna.</p>";
    else echo "<p class=\"error\">Úvodní strana nebyla změněna.</p>";
  }
  else
    echo "<p class=\"error\">Úvodní strana nebyla změněna.</p>";
}

// úvodní zobrazení
$check_text = null;
$data = null;
if (file_exists("../uvodni.php")) {
  $check_text = "checked";
  $text = file_get_contents("../uvodni.php");
}

$uvodni = array_shift(get_settings("template_index", "row"));
?>

<p>
Úprava <span class="help" title="úvodní strany">indexu</span> změní hlavní stranu webu.
</p>

<form method="post">
<p>
  <label for="id_check_text">Vložit volitelný text:</label><br>
  <input type="checkbox" name="check_text" id="id_check_text" value="1" onclick="show_hide('id_text', true)" <?php echo $check_text; ?>>
</p>

<p id="id_text" <?php if (empty($check_text)) echo "style=\"display: none;\""; ?>>
  <?php include_plugins("text editor", array("action" => "show-over", "editor" => "forum")); ?>
  <textarea id="text" name="text" style="width: 650px; height: 100px;"><?php echo $text; ?></textarea><br>
  <?php include_plugins("text editor", array("action" => "show-under", "editor" => "forum")); ?>
</p>

<p>
  Zobrazit:<br>
  <input type="radio" name="type" value="none" id="rad_none" <?php if ($uvodni == null) echo "checked"; ?>> <label for="rad_none">nic dalšího</label><br>
  <?php include_plugin_admin(false, array("action" => "radio", "uvodni" => $uvodni)); ?>
  <input type="radio" name="type" value="page" id="rad_page"> <label for="rad_page">stránka</label><br>
</p>

<select name="page" id="page" size="1" style="display: none;">
  <?php include_plugin_admin(false, array("action" => "page options", "uvodni" => $uvodni)); ?>
</select>

<?php include_plugin_admin(false, array("action" => "select", "uvodni" => $uvodni)); ?>

<p>
<input type="submit" name="uvodni_posted" value="Uložit">
</p>
</form>

<script>
$('input[type=radio]').each(function(){
  if ($(this).is(':checked')) return false;

  if ($(this).attr('id') == 'rad_page') {
    $(this).attr('checked', 'checked');
    $('#page').css('display', 'block');
  }
});

$('input[type=radio]').click(function(){
  var val = $(this).val();
  $('#'+ val).show();

  $('input[type=radio]').each(function(){
    if ($(this).val() != val) $('#'+ $(this).val()).hide();
  });
});
</script>