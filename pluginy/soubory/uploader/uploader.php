<?php
$files = $_FILES["soubory"];
$count = count($files["tmp_name"]);

if (isSet($_GET["ajax"])) {
  $cesta = "../../";
  define("PLUGINS_DIR", $cesta);
  include (dirname(__FILE__)."/../../../letters/config.php");
  include (dirname(__FILE__)."/../../../funkce/get_data.php");
  include (dirname(__FILE__)."/../../../funkce/bez_diakritiky.php");
  include (dirname(__FILE__)."/../../../funkce/include_plugin_admin.php");
  $sort = $_GET["sort"];
  $koncovky = explode(", ", $_GET["allowed_types"]);
}
else {
  $sort = $set["files_sort"];
  $koncovky = explode(", ", $set["files_allowed_types"]);
}

for ($i = 0; $i < $count; $i++) {
  if (empty($files["tmp_name"][$i])) continue;
  
  if ($i == 5) {
    echo "<p class=\"error\">Je povoleno nahrát pouze pět souborů najednou.</p>";
    break;
  }
  
  // údaje o aktuálním souboru
  if (!isset($_GET["base64"])) {
    $tmp_jmeno = $files["tmp_name"][$i];
    $jmeno = $files["name"][$i];
    $typ = $files["type"][$i];
    $velikost = $files["size"][$i];
  }
  else {
  	$headers = getallheaders();
  	$jmeno = $headers["X-Filename"];
  	$typ = $headers["X-Type"];
  	$velikost = $headers["X-Size"];
  }
  $up_type = $_REQUEST["up_type"];
  
	// kontrola velikosti
  if ($up_type != "system" and (isSet($max_size) and $velikost > ($max_size * 1024))) {
    echo "<p class=\"error\">".sprintf("Soubor &bdquo;%s&ldquo; je příliš velký.", $jmeno)."</p>";
    continue;
  }
  
  $jmeno = strtolower(bez_diakritiky($jmeno, false));
  $koncovka = substr($jmeno, strrpos($jmeno, ".") + 1);

  // kontrola typu souboru
  if ($up_type != "system" and !in_array($koncovka, $koncovky)) {
    echo "<p class=\"error\">".sprintf("Soubory typu %s jsou zakázány.", strtoupper($koncovka))."</p>";
    continue;
  }
  
	// pouze upload
  if ($up_type == "onlyup") {
    $slozka = "../soubory";

    if ($sort == "datum") {
      if (!file_exists($cesta.$slozka."/".date("Y"))) { mkdir($cesta.$slozka."/".date("Y"), 0777); } 
      if (!file_exists($cesta.$slozka."/".date("Y")."/".date("n"))) { mkdir($cesta.$slozka."/".date("Y")."/".date("n"), 0777); }
      $slozka .= "/".date("Y")."/".date("n");
    }
  }
  
  // systémové soubory
  elseif ($up_type == "system") {
    $as_what = $_REQUEST["as_what"];

    if ($as_what == "plugin") $slozka = PLUGINS_DIR;
    
    elseif ($as_what == "template") $slozka = TEMPLATES_DIR;
    
    elseif ($as_what == "gravatar") {
      $slozka = "../soubory";
      $jmeno = "gravatar.jpg";
    }
    elseif ($as_what == "favicon") {
      if ($typ_souboru != "image/ico")
        echo "<p class=\"error\">FavIcon musí být typu \".ico\".</p>";
      else {
        $slozka = "../";
        $jmeno = "favicon.ico";
      }
    }
    else $slozka = "../cache";
  }
  
  // pluginy - action upload
  $pass = array("action" => "upload", "up_type" => $up_type, "jmeno" => $jmeno);
  if (isSet($_GET["ajax"])) {
    $pass["cesta"] = get_include_path();
    $_GET = array_merge($_GET, array("page" => "soubory", "co" => "novy_soubor"));
  }
  $global["slozka"] = $slozka;

  include_plugin_admin(false, $pass, $global, array("soubory"));
  $slozka = $global["slozka"];
  
  if (!isSet($slozka) or empty($slozka)) continue;
  $cesta = dirname(__FILE__)."/../../".$slozka."/".$jmeno;

  // soubor existuje
  if (file_exists($cesta)) {
    echo "<p class=\"error\">".sprintf("Soubor &bdquo;%s&ldquo; už existuje.", $jmeno)."</p>";
    continue;
  }

  // nahrávání - vše kromě doplňků
  if ($up_type != "system" or ($up_type == "system" and $as_what != "plugin" and $as_what != "template")) {
    if (!isset($_GET["base64"]))
      $copy = move_uploaded_file($tmp_jmeno, $cesta);
    else {
      $content = base64_decode(file_get_contents('php://input'));
      $copy = file_put_contents($cesta, $content);
    }
  
    // soubor úspěšně nahrán
    if ($copy !== false) {
      @chmod($cesta, 0777);
      echo "<p class=\"success\">".sprintf("Soubor &bdquo;%s&ldquo; byl úspěšně nahrán", $jmeno)." (<a href=\"".$slozka."/".$jmeno."\">cesta</a>).</p>";
    }
    else
      echo "<p class=\"error\">".sprintf("Soubor &bdquo;%s&ldquo; nebyl nahrán.", $jmeno)."</p>";
  }
  
  // nahrávání - pluginy, vzhledy
  else {
  if ($koncovka == "zip") {
    if (phpversion() < "5.2.0") {
      echo "<p class=\"error\">Pro nahrávání ZIP archivu je vyžadována verze PHP 5.2.0 a vyšší.</p>";
      break;
    }
    
    if ($as_what == "plugin") $foo = "Plugin";
    elseif ($as_what == "template") $foo = "Vzhled";

    if (!isset($_GET["base64"])) $content = $tmp_jmeno;
    else $content = base64_decode(file_get_contents('php://input'));
    
    $zip = new ZipArchive;
    $zip->open($content);
    $result = $zip->extractTo($slozka);
    $plugin_jmeno = $zip->getNameIndex(0);
    $zip->close();

    if ($result == true) {
      @chmod ($slozka.$plugin_jmeno, 0777);
      echo "<p class=\"success\">".sprintf($foo." &bdquo;%s&ldquo; byl úspěšně nahrán.", $jmeno)."</p>";
    }
    else
      echo "<p class=\"error\">".$foo." nelze zkopírovat nebo rozbalit do cílového adresáře.</p>";
  }
  elseif (in_array($koncovka, array("php", "php4", "php5", "phtml"))) {
    if (!isset($_GET["base64"]))
      $copy = move_uploaded_file($tmp_jmeno, PLUGINS_DIR.$jmeno);
    else {
      $content = base64_decode(file_get_contents('php://input'));
      $copy = file_put_contents(PLUGINS_DIR.$jmeno, $content);
    }
    
    if ($copy === true) {
      @chmod (PLUGINS_DIR.$jmeno, 0777);
      echo "<p class=\"success\">".sprintf("Plugin &bdquo;%s&ldquo; byl úspěšně nahrán.", $jmeno)."</p>";
    }
    else
      echo "<p class=\"error\">Plugin nelze zkopírovat do cílového adresáře.</p>";
  }
  else
    echo "<p class=\"error\">".sprintf("Soubor typu &bdquo;%s&ldquo; není plugin.", strtoupper($koncovka))."</p>";
  
  }
}


// pluginy - action after_upload
if (isSet($_GET["ajax"])) {
  $i = $_GET["i"];
  $count = $_GET["count"] - 1;
}

if ($i == $count or $i == 4) {
  $pass = array("action" => "after_upload", "up_type" => $up_type, "slozka" => $slozka);
  if (isSet($_GET["ajax"])) {
    $pass["cesta"] = get_include_path();
    $_GET = array_merge($_GET, array("page" => "soubory", "co" => "novy_soubor"));
  }
  $global = array();
  
  include_plugin_admin(false, $pass, $global, array("soubory"));
}
?>