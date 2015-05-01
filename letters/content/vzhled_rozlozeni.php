<h1>Vzhled</h1>

<h2>Rozložení</h2>

<!--
<?php
list($labels, $split_boxes, $box_order, $footer_area) = get_settings("sidebar_labels, sidebar_split_boxes, sidebar_box_order, footer_area", "row");
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
-->

<?php
echo "<div>";

$where = "category LIKE '%sidebar%' AND active=1";
$where .= ($template["search"] == true ? " AND NOT alias='vyhledavani'" : null);
$where .= " AND NOT id=". implode(" AND NOT id=", explode(", ", $box_order));
$where .= " AND NOT id=". implode(" AND NOT id=", explode(", ", $footer_area));
$plugins = get_data("id, name, alias", "pluginy", array("where" => $where, "order" => "alias"), "assoc");
if ($plugins == false)
  echo "<p class=\"info\">Nejsou aktivní žádné pluginy do sidebaru.</p>";
else {
  echo "<ul id=\"plugins\" class=\"".(isSet($template["sidebar"]) ? ($template["sidebar"] == "right" ? "left" : "right") : "left")."\">";
  foreach ($plugins as $plugin) {
    echo "<li id=\"".$plugin["id"]."\" draggable=\"true\">".$plugin["name"];
    if (file_exists(PLUGINS_DIR.$plugin["alias"]."/".$plugin["alias"]."_admin.php")) {
      ob_start();
      include (PLUGINS_DIR.$plugin["alias"]."/".$plugin["alias"]."_admin.php");
      $temp = ob_get_contents();
      ob_end_clean();
      if (!empty($temp)) {
        echo "<span title=\"zobrazit nastavení\">&#9660;</span>";
        echo "<div style=\"display: none;\">".$temp."</div>";
      }
    }
    echo "</li>";
  }
  echo "</ul>";
}


if (isSet($template["search"]) and $template["search"] != false) {
  echo "<ul id=\"search\" class=\"".(isSet($template["sidebar"]) ? $template["sidebar"] : "right")."\">";
  echo "<li>Vyhledávání</li>";
  echo "</ul>";
}


if (isSet($template["sidebar"]) and $template["sidebar"] != false) {
  $where = "id=". implode(" OR id=", explode(", ", $box_order));
  $order = " (id=". implode(") DESC, (id=", explode(", ", $box_order)) .") DESC,";
  $plugins = get_data("id, name, alias", "pluginy", array("where" => $where, "order" => $order." alias"), "assoc");

  echo "<ul id=\"sidebar\" class=\"".$template["sidebar"]."\">";
  foreach ($plugins as $plugin) {
    echo "<li id=\"".$plugin["id"]."\" draggable=\"true\">".$plugin["name"];
    if (file_exists(PLUGINS_DIR.$plugin["alias"]."/".$plugin["alias"]."_admin.php")) {
      ob_start();
      include (PLUGINS_DIR.$plugin["alias"]."/".$plugin["alias"]."_admin.php");
      $temp = ob_get_contents();
      ob_end_clean();
      if (!empty($temp)) {
        echo "<span title=\"zobrazit nastavení\">&#9660;</span>";
        echo "<div style=\"display: none;\">".$temp."</div>";
      }
    }
    echo "</li>";
  }
  echo "</ul>";
}

echo "</div>";


if (isSet($template["footer-area"]) and $template["footer-area"] != false) {
  $where = "id=". implode(" OR id=", explode(", ", $footer_area));
  $order = " (id=". implode(") DESC, (id=", explode(", ", $footer_area)) .") DESC,";
  $plugins = get_data("id, name, alias", "pluginy", array("where" => $where, "order" => $order." alias"), "assoc");

  echo "<ul id=\"footer-area\">";
  foreach ($plugins as $plugin) {
    echo "<li id=\"".$plugin["id"]."\" draggable=\"true\">".$plugin["name"];
    if (file_exists(PLUGINS_DIR.$plugin["alias"]."/".$plugin["alias"]."_admin.php")) {
      ob_start();
      include (PLUGINS_DIR.$plugin["alias"]."/".$plugin["alias"]."_admin.php");
      $temp = ob_get_contents();
      ob_end_clean();
      if (!empty($temp)) {
        echo "<span title=\"zobrazit nastavení\">&#9660;</span>";
        echo "<div style=\"display: none;\">".$temp."</div>";
      }
    }
    echo "</li>";
  }
  echo "</ul>";
}
?>

<style>
ul {
  list-style: none;
  padding: 20px;
  background: #E9E9E9;
}

li {
  width: 200px;
  min-height: 17px;
  font-size: 1em;
  margin: 0 0 10px 0;
  padding: 10px;
  border: 0 solid silver;
  /* border-width: 1px 0 0 0; */
  background: #F5F5F5;
}

li:last-child {
  margin: 0;
  /* border-width: 1px 0 1px 0; */
}

li[draggable="true"] {
  cursor: move;
}

li span { /* settings toggle */
  float: right;
  color: gray;
  padding: 10px;
  margin: -10px -10px 0 0;
  cursor: pointer;
}

li div { /* settings box */
  cursor: auto;
}

#plugins {
  width: calc(100% - 320px);
}

#search {
  width: 220px;
}

#sidebar {
  width: 220px;
}

.left {
  float: left;
}

.right {
  float: right;
}

#footer-area {
  clear: both;
  width: calc(100% - 40px);
  height: 36px;
}

#footer-area li {
  float: left;
  display: inline-block;
  width: calc(<?php echo number_format(100.0 / $template["footer-area"], 2); ?>% - 20px - 10px);
  margin: 0 10px 0 0;
  /* border-width: 1px 0 1px 1px; */
}

#footer-area li:last-child {
  /* border-width: 1px; */
}
</style>

<script>
if ($('#sidebar').is(':empty')) $('<li></li>').appendTo('#sidebar');
if ($('#footer-area').is(':empty')) $('<li></li>').appendTo('#footer-area');

var temp;

$('#lrs_content').on('dragstart', 'li', function(e){
  console.log('dragging '+ $(this).attr('id'));
  temp = $(this);
});

$('#lrs_content').on('dragover', 'li', function(e){
  e.preventDefault();  
  e.stopPropagation();
});

$('#lrs_content').on('dragleave', 'li', function(e){
  e.preventDefault();  
  e.stopPropagation();
});

$('#lrs_content').on('drop', 'li', function(e){
  console.log('dropped on '+ $(this).parent().attr('id') +', position '+ $(this).parent().children().index(this));
  
  if (temp.parent().children().length == 1 && temp.parent().attr('id') != $(this).parent().attr('id')) {
    $('<li></li>').appendTo(temp.parent());
  }
  
  if ($(this).is(':empty')) $(this).after(temp).remove(); 
  else $(this).before(temp);
  
  if ($(this).parent().attr('id') == 'footer-area') {
    $(this).parent().children().each(function(i){
      if (i >= '<?php echo $template["footer-area"]; ?>') {
        if ($(this).is(':empty')) {
          $(this).remove();
        }
        else {
          console.log('not enough space, move it back to #plugins');

          if ($('#plugins').children().length == 1 && $('#plugins li').is(':empty')) {
            $('#plugins').empty();
          }
        
          $(this).appendTo('#plugins');
        }
      }
    });
  }
});

$('li span').click(function(){
  if ($(this).html() == '▼') $(this).html('▲');
  else $(this).html('▼');

  $(this).next('div').slideToggle(200);
});
</script>