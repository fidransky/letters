<h1>Nastavení</h1>

<h2>Sidebar</h2>

<?php
if (isSet($_POST["sidebar_posted"])) {
  $data["sidebar_labels"] = (int)isSet($_POST["labels"]);
  $data["sidebar_split_boxes"] = (int)isSet($_POST["split_boxes"]);
  
  $save_settings = save_settings($data);
  if ($save_settings === true) echo "<p class=\"success\">Nastavení bylo úspěšně uloženo.</p>";
  else echo "<p class=\"error\">Nastavení nebylo uloženo.</p>";
}

// úvodní zobrazení
list($labels, $split_boxes, $box_order) = get_settings("sidebar_labels, sidebar_split_boxes, sidebar_box_order", "row");
?>

<form method="post" name="form">
<p>
<label for="popisky">Zobrazit popisky panelů:</label> <small>popisky zobrazené nad jednotlivými panely</small><br>
<input type="checkbox" id="popisky" name="labels" value="1" <?php if ($labels == 1) echo "checked"; ?>>
</p>

<p>
<label for="oddelit">Oddělit panely:</label> <small>oddělí od sebe jednotlivé panely podle obsahu</small><br>
<input type="checkbox" id="oddelit" name="split_boxes" value="1" <?php if ($split_boxes == 1) echo "checked"; ?>>
</p>

<input type="submit" value="Uložit" name="sidebar_posted">
</form>

<?php include_plugin_admin(); ?>

<h3>Změna pořadí pluginů:</h3>

<style>
#categories {
	list-style: none;
	padding: 0;
}

#categories li {
  position: relative;
	display: block;
	width: 200px; height: 14px;
	font-size: 14px;
  margin-bottom: 3px;
  padding: 5px 10px 12px 8px;
  background-color: #efefef;
}

#categories li img.move {
  position: relative; top: 3px;
	margin-right: 8px;
	padding: 0;
  cursor: n-resize;
}

#categories li input.remove {
  position: absolute; top: 7px; right: 8px;
	margin-left: 8px;
	padding: 0;
}
</style>

<script src="scripts/sortable/jquery-ui.custom.min.js"></script>
<script>
$(document).ready(function() {
  $('#categories li .remove').click(function(){
    if ($(this).is(':checked'))
      $(this).parent().css('opacity', '0.6').removeAttr('id');
    else
      $(this).parent().css('opacity', '1').attr('id', 'id_'+ $(this).parent().attr('data-id'));
    
    $('#categories').sortable();
    $.get('scripts/sortable/process-sortable.php', $('#categories').sortable('serialize'));
  });
  
  $('#categories').sortable({
    axis: 'y',
    distance: 25,
    handle: '.move',
    update: function(data){
      $('#categories').sortable();
      $.get('scripts/sortable/process-sortable.php', $('#categories').sortable('serialize'));
    }
  });
});
</script>


<?php
$order = null;
foreach (explode(", ", $box_order) as $panel)
  $order .= " (id='".$panel."') DESC,";

$plugins = get_data("id, name, alias", "pluginy", array("where" => "category LIKE '%sidebar%' AND active=1".($template["search"] == true ? " AND NOT alias='vyhledavani'" : null), "order" => $order." alias"), "assoc");
if ($plugins == false)
  echo "<p class=\"info\">Nejsou aktivní žádné pluginy do sidebaru.</p>";
else {
  echo "<ul id=\"categories\">";
  foreach ($plugins as $plugin) {
    if ($plugin["alias"] == "vyhledavani" and $template["search"] == true) continue;
        
    echo "<li ".(in_array($plugin["id"], explode(", ", $box_order)) ? "id=\"id_".$plugin["id"]."\"" : "style=\"opacity: 0.6;\"")."data-id=\"".$plugin["id"]."\">";
    echo "<img src=\"scripts/sortable/handle.png\" alt=\"move\" class=\"move\">";
    echo $plugin["name"];
    if (count($plugins) != 1) echo "<input type=\"checkbox\" title=\"skrýt\" class=\"remove\"".(!in_array($plugin["id"], explode(", ", $box_order)) ? "checked" : null).">";
    echo "</li>";
  }
  echo "</ul>";
}
?>