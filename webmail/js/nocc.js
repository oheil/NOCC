/**
 * Update "Port" textbox at login page.
 */
function updateLoginPort() {
  var form = document.getElementById("nocc_webmail_login");
  if (form.servtype.options[form.servtype.selectedIndex].value == 'imap') {
    form.port.value = 143;
  }
  else if (form.servtype.options[form.servtype.selectedIndex].value == 'notls') {
    form.port.value = 143;
  }
  else if (form.servtype.options[form.servtype.selectedIndex].value == 'ssl') {
    form.port.value = 993;
  }
  else if (form.servtype.options[form.servtype.selectedIndex].value == 'ssl/novalidate-cert') {
    form.port.value = 993;
  }
  else if (form.servtype.options[form.servtype.selectedIndex].value == 'pop3') {
    form.port.value = 110;
  }
  else if (form.servtype.options[form.servtype.selectedIndex].value == 'pop3/notls') {
    form.port.value = 110;
  }
  else if (form.servtype.options[form.servtype.selectedIndex].value == 'pop3/ssl') {
    form.port.value = 995;
  }
  else if (form.servtype.options[form.servtype.selectedIndex].value == 'pop3/ssl/novalidate-cert') {
    form.port.value = 995;
  }
}

/**
 * Update login page.
 */
function updateLoginPage() {
  var form = document.getElementById("nocc_webmail_login");
  if (form.user.value == "" && form.passwd.value == "") {
    if (form.theme && form.lang) {
      var lang_page = "index.php?theme=" + form.theme[form.theme.selectedIndex].value + "&lang=" + form.lang[form.lang.selectedIndex].value;
      self.location = lang_page;
    }
    if (!form.theme && form.lang) {
      var lang_page = "index.php?lang=" + form.lang[form.lang.selectedIndex].value;
      self.location = lang_page;
    }
    if (form.theme && !form.lang) {
      var lang_page = "index.php?theme=" + form.theme[form.theme.selectedIndex].value;
      self.location = lang_page;
    }
    if (!form.theme && !form.lang) {
      var lang_page = "index.php";
      self.location = lang_page;
    }
  }
}

/**
 * This array is used to remember mark status of rows.
 */
var marked_row = new Array;

/**
 * Enables highlight and marking of rows in inbox table.
 *
 * Based on the PMA_markRowsInit() function from phpMyAdmin <http://www.phpmyadmin.net/>.
 */
function markInboxRowsInit() {
  if (document.getElementById('inboxTable') != null) {
    // for every table row ...
    var rows = document.getElementById('inboxTable').getElementsByTagName('tr');
    for ( var i = 0; i < rows.length; i++ ) {
      // ... with the class 'odd' or 'even' ...
      if ( 'odd' != rows[i].className.substr(0,3) && 'even' != rows[i].className.substr(0,4) ) {
        continue;
      }
      // ... add event listeners ...
      // ... to highlight the row on mouseover ...
      if ( navigator.appName == 'Microsoft Internet Explorer' ) {
        // but only for IE, other browsers are handled by :hover in css
        rows[i].onmouseover = function() {
          this.className += ' hover';
        }
        rows[i].onmouseout = function() {
          this.className = this.className.replace( ' hover', '' );
        }
      }
      // ... and to mark the row on click ...
      rows[i].onmousedown = function() {
        var unique_id;
        var checkbox;

        checkbox = this.getElementsByTagName( 'input' )[0];
        if ( checkbox && checkbox.type == 'checkbox' ) {
          unique_id = checkbox.name + checkbox.value;
        } else if ( this.id.length > 0 ) {
          unique_id = this.id;
        } else {
          return;
        }

        if ( typeof(marked_row[unique_id]) == 'undefined' || !marked_row[unique_id] ) {
          marked_row[unique_id] = true;
        } else {
          marked_row[unique_id] = false;
        }

        if ( marked_row[unique_id] ) {
          this.className += ' marked';
        } else {
          this.className = this.className.replace(' marked', '');
        }

        if ( checkbox && checkbox.disabled == false ) {
          checkbox.checked = marked_row[unique_id];
        }
      }

      // .. and checkbox clicks
      var checkbox = rows[i].getElementsByTagName('input')[0];
      if ( checkbox ) {
        checkbox.onclick = function() {
          // opera does not recognize return false;
          this.checked = ! this.checked;
        }
      }
    }
  }
}
window.onload=markInboxRowsInit;

/**
 * Invert checked messages in inbox table.
 *
 * Based on the markAllRows() and unMarkAllRows() functions from phpMyAdmin <http://www.phpmyadmin.net/>.
 */
function InvertCheckedMsgs() {
  if (document.getElementById('inboxTable') != null) {
    var rows = document.getElementById('inboxTable').getElementsByTagName('tr');
    var unique_id;
    var checkbox;

    for ( var i = 0; i < rows.length; i++ ) {
      checkbox = rows[i].getElementsByTagName( 'input' )[0];
      if ( checkbox && checkbox.type == 'checkbox' ) {
        unique_id = checkbox.name + checkbox.value;
        if ( checkbox.checked == false ) {
          checkbox.checked = true;
          if ( typeof(marked_row[unique_id]) == 'undefined' || !marked_row[unique_id] ) {
            rows[i].className += ' marked';
            marked_row[unique_id] = true;
          }
        } else {
          checkbox.checked = false;
          rows[i].className = rows[i].className.replace(' marked', '');
          marked_row[unique_id] = false;
        }
      }
    }
  }
  return true;
}

/**
 * handle marker for changes in inbox
 */
var nocc_cur_num_msg=0;
var nocc_session="";
var nocc_inbox="";
var nocc_timer=600;   //default 10minutes
var nocc_message="The content of your inbox has changed!";
var nocc_alert=true;
function ShowInboxChangedMarker() {
	els=document.getElementsByClassName('inbox_changed');
	var i;
	for (i = 0; i < els.length; i++) {
		els[i].style.display='inline';
	}
	if( nocc_alert ) {
		alert(nocc_message);
		nocc_alert=false;
	}
	return true;
}
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		cur_num_msg=parseInt(this.responseText);
		if( cur_num_msg != -1 && cur_num_msg != nocc_cur_num_msg ) {
			ShowInboxChangedMarker();
		}
	}
	return true;
};
function GetInboxChangedHandler() {
	xhttp.open("GET","action.php?"+nocc_session+"&action=inbox_changed&num_msg="+nocc_cur_num_msg);
	xhttp.send();
	return true;
}
function InitInboxChangedHandler(cur_num_msg,session,timer,message,show_alert) {
	nocc_session=session;
	nocc_timer=timer;
	nocc_message=message;
	nocc_alert=show_alert;
	nocc_cur_num_msg=cur_num_msg;
	//GetInboxChangedHandler();
	if( timer>0 ) {
		setInterval(GetInboxChangedHandler,timer*1000); //default: every 600 seconds = 10 minutes
	}
	return true;
}








