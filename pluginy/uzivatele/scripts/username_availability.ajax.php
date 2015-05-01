<?php
include ("../../../letters/config.php");

$sel_pocet = mysql_query("SELECT COUNT(*) FROM ".DB_PREFIX."uzivatele WHERE username='".mysql_real_escape_string($_POST["username"])."'");
list($pocet) = mysql_fetch_row($sel_pocet);
echo intval($pocet);
?>