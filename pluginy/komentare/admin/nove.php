<h1>Nové</h1>

<?php
check_user2("upravy_komentaru", true);

$where = "k.id>'".$_COOKIE["comments_last_checked"]."' AND NOT k.stav='spam'";
$nothing = "Nemáte žádné nové komentáře.";

include ("komentare_edit.php");

// přepsání pozice posledního přečteného komentáře
if (isSet($comments_last_checked)) {
  $update = save_settings(array("comments_last_checked" => $comments_last_checked));
  if ($update === false) echo "<p style=\"error\"><small>Nebyla obnovena pozice posledního přečteného komentáře.</small></p>";
}
?>