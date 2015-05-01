document.write('<span id="filter">hledat: <input type="search" id="q" autofocus></span>');

jQuery.fn.reverse = [].reverse;

$('#filter #q').keyup(function(){
  var q = $('#filter #q').val();
  
  if (q == '') {
    $('#select option').first().attr('selected', 'selected');
  }
  else {
    $('#select option').reverse().each(function(){
      if ($(this).text().match(new RegExp('.*'+ q +'.*', 'i'))) {
        $('#select').val($(this).val());
      }
    });
  }
});