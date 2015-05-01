// vypis formulare
document.write('<span id="filter">hledat: <input type="text" id="q"></span>');
$('#filter #q').focus();
if (!jQuery.browser.msie || jQuery.browser.version < 10)
  $('#filter #q').after('<img src="icons/cross.png" id="delete" title="vymazat">');
else
  $('#filter #q').css('padding-right', '0');
var button = $('#delete');

jQuery.fn.reverse = [].reverse;

// funkce na vymazani inputu a skryti tlacitka
function erase() {
  $('#select option').first().attr('selected', 'selected');
  $('#delete').hide();
  $('#filter #q').val('').focus();
}

// filtr
$('#filter #q').keyup(function(){
  var q = $('#filter #q').val();

  if (q == '') {
    button.hide();
    $('#select option').first().attr('selected', 'selected');
  }
  else {
    button.show();
    $('#select option').reverse().each(function(){
      var current = $(this).html();
      if (current.match(RegExp('.*'+ q +'.*', 'i'))) $(this).attr('selected', 'selected');
    });
  }
});

// spousteni vymazani 
button.click(erase);
$(document).keyup(function(e){
  if (e.keyCode == 27) { erase(); }
});