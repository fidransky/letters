<?php if ($action == "show") { ?>

<fieldset>
<h4>Komentáře</h4>
<label for="psani_komentaru">psaní komentářů:</label> <input type="checkbox" id="psani_komentaru" name="rights[psani_komentaru]" value="1" <?php echo $rights["psani_komentaru"]; ?>><br>
<label for="upravy_komentaru">úpravy komentářů:</label> <input type="checkbox" id="upravy_komentaru" name="rights[upravy_komentaru]" value="1" <?php echo $rights["upravy_komentaru"]; ?>><br>
</fieldset>

<?php } ?>