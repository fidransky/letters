<?php
if ($action == "show") {
  if (!isSet($article["autori"]))
    $autori = $_SESSION["id"];
  else {
    $autori = $article["autori"];
    if (!preg_match("/".$_SESSION["id"]."/", $article["autori"]))
      $autori .= ", ".$_SESSION["id"];
  }
  
  echo "<input type=\"hidden\" name=\"autori\" value=\"".$autori."\">";
}

elseif ($action == "save")
  $data["autori"] = $_POST["autori"];
?>