<h1><?php echo __("Vyhledávání"); ?></h1>

<?php
list(, $per_page, $from_subcategories, $preview_type, $preview_count, $datetime_format, $search_type) = get_settings("group=clanky", "row");
?>

<form method="get" action="vyhledavani/" id="single-search">
  <input type="search" name="q" id="q" value="<?php echo $_GET["q"]; ?>" size="45" required autofocus>

  <?php if ($search_type == "classic") { ?>
  <input type="submit" value="<?php echo __("Hledat"); ?>">
  <?php } else { ?>
  <noscript><input type="submit" value="<?php echo __("Hledat"); ?>"></noscript>
  <?php } ?>
</form>

<?php if ($search_type == "instant") { ?>

<div id="results"></div>

<script src="<?=FCE_DIR?>jquery.min.js"></script>
<script>
var runningRequest = false;
var request;

$('input#q').keyup(function(){
  // e.preventDefault();
  var thiz = $(this);

  if (thiz.val() == ''){
    $('div#results').html('');
    return false;
  }
  
  if (thiz.val().length < 3) {
    $('div#results').html('<p class="error"><?php echo __("Hledaný řetězec je příliš krátký."); ?></p>');
    return false;
  }
  else {
    var final_data = '<p id="searched"><?php echo __("Hledaný výraz"); ?>: <strong>'+ thiz.val() +'</strong></p>';
          
    // abort opened requests to speed it up
    if (runningRequest) request.abort();

    runningRequest = true;
    request = $.getJSON(
      '<?=PLUGINS_DIR?>clanky/scripts/search_instant.php',
      { q:thiz.val() },
      function(data){
        if (data == null) final_data += '<p><?php echo __("Žádný článek nebyl nalezen."); ?></p>';        
        else final_data += data; //data.replace(thiz.val(), '<span style="background-color: yellow;">'+ thiz.val() +'</span>'); }
        
        $('div#results').html(final_data);
      }
    );
    
    runningRequest = false;
  }
});
</script>

<?php
}

else {
if (!empty($_GET["q"])) {
if (strlen($_GET["q"]) < 3)
  echo "<p class=\"error\">".__("Hledaný výraz je příliš krátký.")."</p>";
else {
  $hledano = mysql_real_escape_string($_GET["q"]);
  echo "<p id=\"searched\">".__("Hledaný výraz").": <strong>".$hledano."</strong></p>";

  // minimální délka hledaného výrazu pro použití fulltextu
  $ft_min_word_len = mysql_result(mysql_query("SHOW VARIABLES LIKE 'ft_min_word_len'"), 0, 1);

  $where = "zverejneno=1 AND cas<=NOW() AND zobrazit=1";
  if (strlen($hledano) < $ft_min_word_len)
    $where .= " AND (`text` REGEXP '[[:<:]]".$hledano."[[:>:]]' COLLATE 'utf8_general_ci' OR nadpis LIKE '%".$hledano."%' COLLATE 'utf8_general_ci' OR tagy LIKE '%".$hledano."%')";
  else
    $where .= " AND MATCH(nadpis, `text`, tagy) AGAINST('".$hledano."' IN BOOLEAN MODE)";

  $articles = get_data("*", "clanky", array("where" => $where, "order" => "cas DESC"), "assoc");
    
  if ($articles == false)
    echo "<p>".__("Žádný článek nebyl nalezen.")."</p>";
  else {
    // zobrazení
    include (PLUGINS_DIR."clanky/frontend/archive.php");
  }
}}}
?>