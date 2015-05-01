<?php
function set_flag($page) {
  $file = fopen("flag.html", "a");
  fwrite($file, $page."\r\n");
  fclose($file);
}

function check_flag() {
  if (!file_exists("flag.html")) return false;
  
  foreach (file("flag.html") as $row) {
    $row = str_replace("\r\n", null, $row);
    if (!empty($row)) setcookie("lrs_flag_".$row, true, time()+24*60*60, "/letters/");
  }

  @unlink("flag.html");
  @header("Location: letters.php");
}

function del_flag($page=null) {
  if (!empty($page)) {
    if (isSet($_COOKIE["lrs_flag_".$page])) setcookie("lrs_flag_".$page, false, time()-60, "/letters/");
  }
  else {
    if (isSet($_GET["page"])) {
      $page = $_GET["page"];
      if (isSet($_COOKIE["lrs_flag_".$page])) setcookie("lrs_flag_".$page, false, time()-60, "/letters/");
      
      if (isSet($_GET["co"])) $page .= "->".$_GET["co"];
      if (isSet($_COOKIE["lrs_flag_".$page])) setcookie("lrs_flag_".$page, false, time()-60, "/letters/");
    }
  }
}

function flag($page) {
  if (isSet($_COOKIE["lrs_flag_".$page]) and $page != $_GET["page"] and $page != $_GET["page"]."->".$_GET["co"])
    echo "<img src=\"icons/flag.png\" class=\"flag\" title=\"nové položky\">";
}
?>