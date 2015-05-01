function show_hide(id, efect) {
  if (efect == true) $('#'+ id).slideToggle();
  else $('#'+ id).toggle();
}

function show(id) {
  $('#'+ id).show();
}

function hide(id) {
  $('#'+ id).hide();
}