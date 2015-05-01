<h1>Spam</h1>

<?php
check_user2("upravy_komentaru", true);

$where = "k.stav='spam'";
$nothing = "Nemáte žádné spamové komentáře.";

include ("komentare_edit.php");
?>