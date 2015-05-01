function show_hide(id, efect) {
  var el = $('#'+ id);
  if (el.css('display') == 'none') {
    if (efect == true) el.slideDown();
    else el.show();
  }
  else {
    if (efect == true) el.slideUp();
    else el.hide();
  }
}

function show(id) {
  $('#'+ id).show();
}

function hide(id) {
  $('#'+ id).hide();
}