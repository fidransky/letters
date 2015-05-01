var html = '';
var i = 0;
$('.login').each(function(index){
  if (index != 0) $(this).hide();
  html += '<span>'+ $(this).attr('data-label') +'</span>';
  i++;
});

if (i > 1) {
  $('#login_switch').prepend('přihlásit '+ html);

  $('#login_switch span').css({'display': 'inline-block', 'margin-right': '5px', 'text-decoration': 'underline', 'cursor': 'pointer'}).first().hide();
  $('#login_switch span').click(function(){
    $('#login_switch span').css('display', 'inline-block');
    $(this).hide();
    $('.login').hide();
    $('.login[data-label="'+ $(this).html() +'"]').show();
  });
}