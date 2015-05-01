<!-- <![CDATA[
function addtext(text) {
  textarea = document.getElementById('text');

  // pro IE a jiné prohlížeče, které podporují "document.selection"
  if (document.selection) {
    textarea.focus();
    vyber = document.selection.createRange();
    
    vyber.moveStart('character', -textarea.value.length);
		pos = vyber.text.length;
    
    vyber.text = text;
  }

  // pro prohlížeče postavené na jádře Gecko
  else if (textarea.selectionStart || textarea.selectionStart == 0) {
    startPos = textarea.selectionStart;
    endPos = textarea.selectionEnd;
    
    pos = textarea.selectionStart;
    
    textarea.value = textarea.value.substring(0, startPos) + text + textarea.value.substring(endPos, textarea.value.length);
  }

  else textarea.value += text;
  

  // přesun kurzoru za vložený text
  pos += text.length;

	if (textarea.setSelectionRange) {
		textarea.focus();
		textarea.setSelectionRange(pos,pos);
	}
	else if (textarea.createTextRange) {
		var range = textarea.createTextRange();
		range.collapse(true);
		range.moveEnd('character', pos);
		range.moveStart('character', pos);
		range.select();
	}
}
//]]> -->