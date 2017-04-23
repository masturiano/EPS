function popup(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, dependant=yes, dialog=yes, modal=yes, unadorned=yes, width='+w+', height='+h+', top='+top+', left='+left);
} 

function FocusModalWins(){
	
	if (newwindow && !newwindow.closed) {
		newwindow.focus();
	}
	if (newwindows && !newwindows.closed) {
		newwindows.focus();
	}
	if (newwindowss && !newwindowss.closed) {
		newwindowss.focus();
	}
	if (newwindowsss && !newwindowsss.closed) {
		newwindowsss.focus();
	}

}
function FocusModalWins(){
	setTimeout(FocusModalWin, 50);
}

function CloseMySelf(sender) {
    try {
        window.opener.HandlePopupResult(sender.getAttribute("result"));
    }
    catch (err) {}
    window.close();
    return false;
}