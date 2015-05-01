<?php if ($action == "show") { ?>

<fieldset>
<h4>Soubory</h4>
<label for="upload_souboru">upload souborů:</label> <input type="checkbox" id="upload_souboru" name="rights[upload_souboru]" value="1" <?php echo $rights["upload_souboru"]; ?>><br>
<label for="zobrazeni_souboru">zobrazení souborů:</label> <input type="checkbox" id="zobrazeni_souboru" name="rights[zobrazeni_souboru]" value="1" <?php echo $rights["zobrazeni_souboru"]; ?>><br>
</fieldset>

<?php } ?>