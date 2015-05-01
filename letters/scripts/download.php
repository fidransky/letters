<?php
define("LETTERS_WEB_URL", "http://letters.cz");
include ("../../funkce/remote_file_get_contents.php");

$type = $_GET["t"];
if ($type == "plugin") $dir = "pluginy/";
elseif ($type == "vzhled") $dir = "vzhledy/";

$file = $_GET["f"];

$lrs_version = $_GET["lrs_version"];

parse_str(parse_url($_SERVER["HTTP_REFERER"], PHP_URL_QUERY), $referer);

$foo = null;
$data = remote_file_get_contents(LETTERS_WEB_URL."/misc/download.php?f=".$file."&t=".$type."&letters_version=".$lrs_version."&feature=".$referer["co"]);

if (!empty($data)) {
  $soubor = FOpen("../../cache/".$file, "w");
  @chmod("../../cache/".$file, 0774);
  $save = FWrite($soubor, $data);
  FClose($soubor);

  $alias = substr($file, 0, strrpos($file, "."));
  $ext = substr($file, strrpos($file, ".") +1);
  
  if ($save == true) {
    if ($ext == "zip") {
      $zip = new ZipArchive;
      $res = $zip->open("../../cache/".$file);

      if ($res === true) {
        $zip->extractTo("../../".$dir);
        $dir2 = $zip->getNameIndex(0);
        $zip->close();
        @chmod("../../".$dir.$dir2, 0777);
        $foo = "&downloaded=".$alias;
      }
    }
    elseif ($ext == "php") {
      $res = copy("../../cache/".$file, "../../".$dir.$file);
      $foo = "&downloaded=".$alias;
    }

    @unlink("../../cache/".$file);
  }
}

header("Location: ".$_SERVER["HTTP_REFERER"].$foo);
echo "<a href=\"".$_SERVER["HTTP_REFERER"].$foo."\" title=\"continue\">&rarr;</a>";
?>