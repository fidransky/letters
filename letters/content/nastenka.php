<h1>Nástěnka</h1>

<?php
if (file_exists("../instalace.php"))
  @unlink("../instalace.php");
?>

<div style="width: 50%; float: left;">

<!-- box s informací o nainstalované verzi a možných aktualizacích -->
<div class="box">
<?php
echo "Používáte Letters ".$lrs["letters_version"]." ".sprintf("se vzhledem %s", "<a href=\"letters.php?page=vzhled&co=tema\">".$template["name"]."</a>").".<br>";

$data = remote_file_get_contents(LETTERS_WEB_URL."/misc/aktualni_verze.txt");
if ($data === false)
  echo "Aktualizace se nepodařilo ověřit.";
else {
  if ($lrs["letters_version"] < $data) 
    echo ("Je dostupná nová verze (".$data."). <a href=\"".LETTERS_WEB_URL."/misc/download.php?f=letters_".$data.".zip&t=letters\">Stáhnout&hellip;</a>");
  if ($lrs["letters_version"] > $data)
    echo "<em>testovací verze</em>";
}
?>
</div>


<!-- box s novinkami kolem vývoje Letters -->
<div class="box" id="rss">
<span class="loading">načítání&hellip;</span>
<script>
$(document).ready(function() {
  $.ajax({
  url: 'scripts/rss_reader.ajax.php',
  data: 'url=<?php echo LETTERS_WEB_URL; ?>/misc/news_rss.xml',
  success: function(data) {
    $('#rss').html(data);
  }
  });
});
</script>
</div>

</div>


<div style="width: 50%; float: left;">
<?php include_plugin_admin(false, array("def" => $def)); ?>
</div>