<h1>Katalog</h1>

<?php
check_user2("admin", true);

function strankovani($pocet, $page, $order, $limit) {
  $stranek = ($limit != "all" ? ceil($pocet / $limit) : 1);

  $href = "letters.php?page=pluginy&co=katalog";
  if (!empty($order)) $href .= "&order=".$order;
  if (!empty($limit)) $href .= "&limit=".$limit;

  if ($stranek != 1) {
    echo "<noscript><div class=\"strankovani\">";

    if ($page != 1) {
      echo "<a href=\"".$href."&pg=1\" id=\"first\" title=\"první stránka\">&laquo;</a>";
      echo "<a href=\"".$href."&pg=".($page - 1)."\" id=\"previous\" title=\"předchozí stránka\">&lsaquo; předchozí</a>";
    }
    else {
      echo "<span id=\"first\">&laquo;</span>";
      echo "<span id=\"previous\">&lsaquo; předchozí</span>";
    }

    echo "<span title=\"stránka ".$page." ".sklonuj($stranek, "z", "ze", "z", false)." ".$stranek."\">".$page." / ".$stranek."</span>";

    if ($page != $stranek) {
      echo "<a href=\"".$href."&pg=".($page + 1)."\" id=\"next\" title=\"další stránka\">další &rsaquo;</a>";
      echo "<a href=\"".$href."&pg=".$stranek."\" id=\"last\" title=\"poslední stránka\">&raquo;</a>";
    }
    else {
      echo "<span id=\"next\">další &rsaquo;</span>";
      echo "<span id=\"last\">&raquo;</span>";
    }

    echo "</div></noscript>";
  }
}


$page = ((empty($_GET["pg"]) or $_GET["pg"] == 0) ? 1 : intval($_GET["pg"]));
$order = (isSet($_GET["order"]) ? $_GET["order"] : null);
$limit = (isSet($_GET["limit"]) ? $_GET["limit"] : 10);
$offset = ($page - 1) * $limit;

if ($order == null) $order_sel[0] = "selected";
elseif ($order == "stazeno") $order_sel[1] = "selected";
elseif ($order == "vlozeni") $order_sel[2] = "selected";

if ($limit == 10) $limit_sel[0] = "selected";
elseif ($limit == 20) $limit_sel[1] = "selected";
elseif ($limit == 50) $limit_sel[2] = "selected";
elseif ($limit == "all") $limit_sel[3] = "selected";


$search = null;
$text = null;
if (!empty($_GET["search"])) {
  $search = $_GET["search"];
  $text = " k hledanému výrazu &bdquo;".$search."&ldquo;";
}

// stažení přes katalog
if (isSet($_GET["downloaded"])) echo "<p class=\"success\">Plugin byl úspěšně stažen.</p>";
?>

<form method="get" id="formular" style="float: left;">
<input type="hidden" name="page" value="pluginy">
<input type="hidden" name="co" value="katalog">

řadit podle&nbsp;
<select name="order">
  <option value="" <?php echo $order_sel[0]; ?>>jména
  <option value="stazeno" <?php echo $order_sel[1]; ?>>počtu stažení
  <option value="vlozeni" <?php echo $order_sel[2]; ?>>data vložení
</select>&nbsp;

<noscript>
položek&nbsp;
<select name="limit">
  <option value="10" <?php echo $limit_sel[0]; ?>>10
  <option value="20" <?php echo $limit_sel[1]; ?>>20
  <option value="50" <?php echo $limit_sel[2]; ?>>50
  <option value="all" <?php echo $limit_sel[3]; ?>>vše
</select>
</noscript>

<input type="hidden" name="search" value="<?php echo $search; ?>">
<noscript><input type="submit" value="nastavit"></noscript>
</form>

<form method="get" style="float: right;">
<input type="hidden" name="page" value="pluginy">
<input type="hidden" name="co" value="katalog">

<input type="search" name="search" value="<?php echo $search; ?>" required autofocus>
<input type="submit" value="hledat">
</form>
<br class="cleaner">

<script>
$('#formular option').click(function() {
  $('#formular').submit();
});
</script>

<?php
$pocet = 0;

if (function_exists("glob")) { $glob = glob(PLUGINS_DIR."*"); }
else { $glob = glob_alternative(PLUGINS_DIR, "*"); }

foreach ($glob as $plugin) {
  $available[] = str_replace(array(PLUGINS_DIR, ".php"), null, $plugin);
  $pocet++;
}

$not = (!empty($available) ? implode(",", $available) : null);

$data = remote_file_get_contents(LETTERS_WEB_URL."/misc/browser.php?limit=".$limit."&offset=".$offset."&order=".$order."&not=".$not."&search=".$search);

if ($data === false)
  echo "<p class=\"error\">Katalog doplňků se nepodařilo získat.</p>";
elseif ($data === null or empty($data))
  echo "<p class=\"error\">Katalog doplňků není dostupný.</p>";
else {
  $xml = simplexml_load_string($data);

  if ($xml->pocet == 0)
    echo "<p class=\"info\">Nejsou k dispozici žádné další pluginy.</p>";
  else {
    $pocet = $xml->pocet;

    strankovani($pocet, $page, $order, $limit);

    echo "<p style=\"font-style: italic; color: silver;\">K dispozici ".sklonuj($pocet, "je", "jsou", "je", false)." v této chvíli ".sklonuj($pocet, "další ".$pocet." plugin", "další ".$pocet." pluginy", "dalších ".$pocet." pluginů", false).$text.".</p>";

    foreach ($xml->doplnek as $plugin) {
      $sidebar = ($plugin->sidebar == "true" ? "_sidebar" : null);

      echo "<div class=\"doplnek plugin\">";
      echo "<a name=\"".$plugin->alias."\"></a>";
      echo "<h2>".$plugin->jmeno."</h2>";
      echo "<p>".$plugin->popis."</p>";

      echo "<ul class=\"meta\">";
        echo "<li class=\"link\"><a href=\"".LETTERS_WEB_URL."/pluginy/#".$plugin->alias.$sidebar."\" target=\"_blank\" title=\"zobrazit na webu Letters\">zobrazit</a> na webu Letters";
    
        if (empty($plugin->min_lrs) or $plugin->min_lrs <= $lrs["letters_version"]) { echo "<li class=\"download\"><a href=\"scripts/download.php?f=".$plugin->link."&t=plugin&lrs_version=".$lrs["letters_version"]."\" title=\"stáhnout plugin do Letters\">stáhnout</a>"; }
        else { echo "<li class=\"download\"><span style=\"color: black; font-size: 1em;\" title=\"plugin vyžaduje minimálně Letters verze ".$plugin->min_lrs."\">stáhnout</span>"; }
        if ($plugin->stazeno != 0) { echo "&nbsp;<span>staženo ".$plugin->stazeno."&times;</span>"; }

        if (!empty($plugin->verze) and $plugin->verze != "1.0") { echo "<li class=\"verze\"><span>verze:</span> ".$plugin->verze; }
        if ((!empty($plugin->kategorie) and $plugin->kategorie == "sidebar") or $sidebar == "true") { echo "<li class=\"kategorie\"><span>kategorie:</span> ".$plugin->kategorie; }
        if (!empty($plugin->vyzaduje)) { echo "<li class=\"vyzaduje\"><span>vyžaduje:</span> plugin <a href=\"letters.php?page=pluginy&co=katalog&search=".$plugin->vyzaduje."\">".$plugin->vyzaduje."</a>"; }
        if (!empty($plugin->autor)) { echo "<li class=\"autor\"><span>autor:</span> ".$plugin->autor; }
        if (!empty($plugin->url)) { echo "<li class=\"url\"><span>url projektu:</span> <a href=\"".$plugin->url."\" target=\"_blank\">".$plugin->url."</a>"; }
      echo "</ul>";
      echo "</div>";
    }

    strankovani($pocet, $page, $order, $limit);

    echo "<img src=\"icons/loading.gif\" id=\"loading\" title=\"načítám další položky&hellip;\" style=\"display: none;\">";
    echo "<div id=\"infinite\"></div>";
  }
}
?>

<script>
var page = 2;
var load = true;

$(window).scroll(function(){
  var limit = '<?php echo $limit; ?>';
  var offset = (page - 1) * limit;
  var pocet = '<?php echo $pocet; ?>';

  if (offset < pocet) {   // pokud je stále co načítat
    curOffset = $(window).scrollTop() + $(window).height();
    pageHeight = $('#lrs_wrapper').height() - 100;

    if (curOffset >= pageHeight && load == true) {  // pokud dojedem ke konci stránky a načítání ještě neproběhlo
      load = false;
      $('#loading').show();
      var url = '<?php echo LETTERS_WEB_URL; ?>/misc/browser.php?limit='+ limit +'&offset='+ offset +'&order=<?php echo $order; ?>&not=<?php echo $not; ?>&search=<?php echo $search; ?>';

      $.ajax({
        type: 'GET',
        data: 'lrs=<?php echo $lrs["letters_version"]; ?>&url='+ encodeURIComponent(url),
        dataType: 'html',
        url: 'scripts/infinite.php',
        success: function(data) {
          $('#loading').hide();
          $('#infinite').append(data).show();
          load = true;
          page++;
        }
      });
    }
  }
});
</script>