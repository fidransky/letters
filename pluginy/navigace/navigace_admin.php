<?php
if ($_GET["page"] == "clanky")
  include (PLUGINS_DIR."navigace/admin/clanky.php");
elseif ($_GET["page"] == "nastaveni")
  include (PLUGINS_DIR."navigace/admin/nastaveni_sidebar.php");
elseif ($_GET["page"] == "vzhled")
  include (PLUGINS_DIR."navigace/admin/nastaveni_sidebar_new.php");
?>