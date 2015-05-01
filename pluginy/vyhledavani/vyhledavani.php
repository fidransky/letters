<?php if ($menu_popisky == 1) echo ("<h3><a href=\"vyhledavani/\">".__("Vyhledávání")."</a></h3>"); ?>

<form method="get" action="vyhledavani/" id="search">

<input type="search" name="q" value="<?php if (isSet($_GET["q"])) echo strip_tags($_GET["q"]); ?>" size="20" id="search-text">

<input type="submit" value="<?php echo __("Hledat"); ?>" id="search-submit">
</form>