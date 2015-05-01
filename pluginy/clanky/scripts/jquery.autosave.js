function autosave(ajaxUrl) {
  var id = $('#id').val();
  var nadpis = $('#nadpis').val();
  var kategorie = $('#kategorie').val();
  var text = $('#text').val();
  var tagy = $('#tagy').val();
  var cas_zverejneni = $('#cas_zverejneni').val();
  
  if (nadpis == '' || text == '') return false;

  var dataString = '&id='+ id +'&nadpis='+ nadpis +'&kategorie='+ kategorie +'&text='+ text +'&tagy='+ tagy +'&cas_zverejneni='+ cas_zverejneni;
  
  $.ajax({
    type: 'POST',
    url: ajaxUrl,
    data: 'autosave=true'+ dataString,
    success: function() {
      datum = new Date()
      with (datum){
        h = getHours();
        m = getMinutes() +'';
      }
      // opatření protí jednocifernému zobrazení
      if (h < 10) { h = '0'+ h; }
      if (m < 10) { m = '0'+ m; }

      $('#cancel').attr('style', 'display: inline;');
      $('#autosave_msg').show().html('<a href="../' + id + '" target="_blank">Koncept</a> byl automaticky uložen v '+ h +':'+ m +'.');
      nadpis=''; kategorie=''; text=''; tagy='';
    }
  });
  return false;
}