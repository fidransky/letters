<h1>Komentáře</h1>

<?php
check_user2("upravy_komentaru", true);

$where = null;
$nothing = "Zatím nemáte žádné komentáře.";

include ("komentare_edit.php");
?>