// Last modified: 05-04-04

function checkForm(f) {
	var msg = "Please fix these errors:\n";
	var errors = false;
	
	if (f.fname.value == "") {
		msg+="-First name is required\n";
		errors = true;
	}
	if (f.lname.value == "") {
		msg+="-Last name is required\n";
		errors = true;
	}
	if (f.phone.value == "") {
		msg+="-Phone number is required\n";
		errors = true;
	}
	if (f.institution.value == "") {
		msg+="-Institution is required\n";
		errors = true;
	}
	if ( (f.email.value == "") || ( f.email.value.indexOf('@') == -1) ) {
		msg+="-Valid email is required\n";
		errors = true;
	}
	if (errors) {
		window.alert(msg);
		return false;
	}
		
	return true;
}

function verifyEdit() {
	var msg = "Please fix these errors:\n";
	var errors = false;
	
	if ( (document.register.email.value != "") && ( document.register.email.value.indexOf('@') == -1) ) {
		msg+="-Valid email is required\n";
		errors = true;
	}
	if ( (document.register.password.value != "") && (document.register.password.value.length < 6) ) {
		msg+="-Min 6 character password is required\n";
		errors = true;
	}
	if ( (document.register.password.value != "") && (document.register.password.value != document.register.password2.value) ) {
		msg+=("-Passwords to not match\n");
		errors = true;
	}
	if (errors) {
		window.alert(msg);
		return false;
	}
		
	return true;
}

function checkBrowser() {
	if ( (navigator.appName.indexOf("Netscape") != -1) && ( parseFloat(navigator.appVersion) <= 4.79 ) ) {
		newWin = window.open("","message","height=200,width=300");
		newWin.document.writeln("<center><b>This system is optimized for Netscape version 6.0 or higher.<br>" +
					"Please visit <a href='http://channels.netscape.com/ns/browsers/download.jsp' target='_blank'>Netscape.com</a> to obtain an update.");
		newWin.document.close();
	}
}

function help(file) {    
		window.open("help.php#" + file ,"","width=500,height=500,scrollbars");    
		void(0);    
}      

function reserve(type, machid, ts, resid, scheduleid, read_only) {  
		w = (type == 'r') ? 600 : 425;
		h = (type == 'm') ? 600 : 500;

		nurl = "reserve.php?type=" + type + "&machid=" + machid + "&ts=" + ts + "&resid=" + resid + '&scheduleid=' + scheduleid + "&is_blackout=0&read_only=" + read_only;    
		window.open(nurl,"reserve","width=" + w + ",height=" + h + ",scrollbars,resizable=no,status=no");     
		void(0);    
}

function blackout(type, machid, ts, resid, scheduleid) {  
		w = (type == 'r') ? 600 : 425;
		h = (type == 'm') ? 450 : 370;

		nurl = "reserve.php?type=" + type + "&machid=" + machid + "&ts=" + ts + "&resid=" + resid + '&scheduleid=' + scheduleid + "&is_blackout=1";    
		window.open(nurl,"reserve","width=" + w + ",height=" + h + ",scrollbars,resizable=no,status=no");     
		void(0);    
}

function checkDate() {
	var formStr = document.jumpWeek;
	var dayNum = new Array();
	dayNum = [31,28,31,30,31,30,31,31,30,31,30,31];
	
	if ( (formStr.jumpMonth.value > 12) || (formStr.jumpDay.value > dayNum[formStr.jumpMonth.value-1]) ) {
		alert("Please enter valid date value");
		return false;
	}
	
	for (var i=0; i< formStr.elements.length-1; i++) {
		if (formStr.elements[i].type == "text" || formStr.elements[i].type == "textbox" ) {			
			if ( (formStr.elements[i].value <= 0) || (formStr.elements[i].value.match(/\D+/) != null) ) {
					alert("Please enter valid date value");
					formStr.elements[i].focus();
					return false;
			}
		}
	}
}

function verifyTimes(f) {
	if (f.del && f.del.checked) {
		return confirm("Delete this reservation?");
	}
	if (parseFloat(f.startTime.value) < parseFloat(f.endTime.value)) {
		return true;
	}
	else {
		window.alert("End time must be later than start time\nCurrent start time: " + f.startTime.value + " Current end time: " + f.endTime.value);
		return false;
	}
}

function checkAdminForm() {
	var f = document.forms[0];
	for (var i=0; i< f.elements.length; i++) {
		if ( (f.elements[i].type == "checkbox") && (f.elements[i].checked == true) )
			return confirm('This will delete all reservations and permission information for the checked items!\nContinue?');
	}
	alert("No boxes have been checked!");	
	return false;
}

function checkBoxes() {
	var f = document.train;
	for (var i=0; i< f.elements.length; i++) {
		if (f.elements[i].type == "checkbox")
			f.elements[i].checked = true;
	}
	void(0);
}

function viewUser(user) {
	window.open("userInfo.php?user="+user,"UserInfo","width=400,height=400,scrollbars,resizable=no,status=no");     
		void(0);    
}

function checkAddResource(f) {
	var msg = "";
	minRes = (parseInt(f.minH.value) * 60) + parseInt(f.minM.value);
	maxRes = (parseInt(f.maxH.value) * 60) + parseInt(f.maxM.value);
	
	if (f.name.value=="")
		msg+="-Resource name is required.\n";
	if (parseInt(minRes) > parseInt(maxRes))
		msg+="-Minimum reservaion time must be less than or equal to maximum";
	if (msg!="") {
		alert("You have the following errors:\n\n"+msg);
		return false;
	}
	
	return true;
}

function checkAddSchedule() {
	var f = document.addSchedule;
	var msg = "";
	
	if (f.scheduleTitle.value=="")
		msg+="-Schedule title is required.\n";
	if (parseInt(f.dayStart.value) > parseInt(f.dayEnd.value))
		msg+="-Invalid start/end times.\n";
	if (f.viewDays.value == "" || parseInt(f.viewDays.value) <= 0)
		msg+="Invalid view days.\n";
	if (f.dayOffset.value == "" || parseInt(f.dayOffset.value) < 0)
		msg+="Invalid day offset.\n";
	if (f.adminEmail.value == "")
		msg+="Admin email is required.\n";

	if (msg!="") {
		alert("You have the following errors:\n\n"+msg);
		return false;
	}
	
	return true;
}

function checkAllBoxes(box) {
    var f = document.forms[0];
	
	for (var i = 0; i < f.elements.length; i++) {
		if (f.elements[i].type == "checkbox" && f.elements[i].name != "notify_user")
			f.elements[i].checked = box.checked;
	}

	void(0);
}

function check_reservation_form(f) {
	
	var recur_ok = false;
	var days_ok = false;
	var is_repeat = false;
	var msg = "";
	
	if (f.interval.value != "none") {
		is_repeat = true;
		if (f.interval.value == "week" || f.interval.value == "month_day") {
			for (var i=0; i < f.elements["repeat_day[]"].length; i++) {
				if (f.elements["repeat_day[]"][i].checked == true)
					days_ok = true;
			}
		}
		else {
			days_ok = true;
		}
		
		if (f.repeat_until.value == "") {
			msg += "- Please choose an ending date\n";
			recur_ok = false;
		}
	}
	else {
		recur_ok = true;
		days_ok = true;
	}
	
	if (days_ok == false) {
		recur_ok = false;
		msg += "- Please select days to repeat on";
	}
	
	if (msg != "")
		alert(msg);
		
	return (msg == "");
}

function check_for_delete(f) {
	if (f.del && f.del.checked == true)
		return confirm('Delete this reservation?');
}

function toggle_fields(box) {
	document.forms[0].elements["table," + box.value + "[]"].disabled = (box.checked == true) ? false : "disabled";
}

function search_user_lname(letter) {
	var frm = isIE() ? document.name_search : document.forms['name_search'];
	frm.firstName.value = "";
	frm.lastName.value=letter;
	frm.submit();
}

function isIE() {
	return document.all;
}

function changeDate(month, year) {
	var frm = isIE() ? document.changeMonth : document.forms['changeMonth'];
	frm.month.value = month;
	frm.year.value = year;
	frm.submit();
}

// Function to change the Scheduler on selected date click
function changeScheduler(m, d, y, isPopup, scheduleid) {
	newDate = m + '-' + d + '-' + y;
	keys = new Array();
	vals = new Array();

	// Get everything up to the "?" (if it even exists)
	var queryString = (isPopup) ? window.opener.document.location.search.substring(0): document.location.search.substring(0);
	var pairs = queryString.split('&');
	var url = (isPopup) ? window.opener.document.URL.split('?')[0] : document.URL.split('?')[0];
	
	if (scheduleid == "") {
	
		for (var i=0;i<pairs.length;i++)
		{
			var pos = pairs[i].indexOf('=');
			if (pos >= 0)
			{
				var argname = pairs[i].substring(0,pos);
				var value = pairs[i].substring(pos+1);
				keys[keys.length] = argname;
				vals[vals.length] = value;		
			}
		}
		
		for (i = 0; i < keys.length; i++) {
			if (keys[i] == "scheduleid")
				scheduleid = vals[i];
		}
	}
	
	if (isPopup)
		window.opener.location = url + "?date=" + newDate + "&scheduleid=" + scheduleid;
	else
		document.location.href = url + "?date=" + newDate + "&scheduleid=" + scheduleid;
	
}

function showSummary(object, e, text) {
	myLayer = document.getElementById(object);
	myLayer.innerHTML = text;
	
	w = parseInt(myLayer.style.width);
	h = parseInt(myLayer.style.height);

    if (e != '') {
        if (isIE()) {
            x = e.clientX;
            y = e.clientY;
            browserX = document.body.offsetWidth - 25;
			x += document.body.scrollLeft;			// Adjust for scrolling on IE
    		y += document.body.scrollTop;
        }
        if (!isIE()) {
            x = e.pageX;
            y = e.pageY;
            browserX = window.innerWidth - 35;
        }
    }
	
	x1 = x;		// Move out of mouse pointer
	y1 = y + 20;
	
	// Keep box from going off screen
	if (x1 + w > browserX)
		x1 = browserX - w;
    
    myLayer.style.left = parseInt(x1)+ "px";
    myLayer.style.top = parseInt(y1) + "px";
	myLayer.style.visibility = "visible";
}

function moveSummary(object, e) {

	myLayer = document.getElementById(object);
	w = parseInt(myLayer.style.width);
	h = parseInt(myLayer.style.height);

    if (e != '') {
        if (isIE()) {
            x = e.clientX;
            y = e.clientY;
			browserX = document.body.offsetWidth -25;
			x += document.body.scrollLeft;
			y += document.body.scrollTop;
        }
        if (!isIE()) {
            x = e.pageX;
            y = e.pageY;
			browserX = window.innerWidth - 30;
        }
    }

	x1 = x;	// Move out of mouse pointer	
	y1 = y + 20;
	
	// Keep box from going off screen
	if (x1 + w > browserX)
		x1 = browserX - w;

    myLayer.style.left = parseInt(x1) + "px";
    myLayer.style.top = parseInt(y1) + "px";
}

function hideSummary(object) {
	myLayer = document.getElementById(object);
	myLayer.style.visibility = 'hidden';
}

function resOver(cell, color) {
	cell.style.backgroundColor=color;
	cell.style.cursor='hand'
}

function resOut(cell, color) {
	cell.style.backgroundColor = color;
}

function showHideDays(opt) {
	e = document.getElementById("days");
	
	if (opt.options[2].selected == true || opt.options[4].selected == true) {
		e.style.visibility = "visible";
		e.style.display = isIE() ? "inline" : "table";
	}
	else {
		e.style.visibility = "hidden";
		e.style.display = "none";
	}
	
	e = document.getElementById("week_num")
	if (opt.options[4].selected == true) {
		e.style.visibility = "visible";
		e.style.display = isIE() ? "inline" : "table";
	}
	else {
		e.style.visibility = "hidden";
		e.style.display = "none";
	}
}

function chooseDate(input_box, m, y) {
	var file = "recurCalendar.php?m=" + m + "&y="+ y;
	if (isIE()) {
		yVal = "top=" + 200;
		xVal = "left=" + 500;
	}
	if (!isIE()) {
		yVal = "screenY=" + 200;
		xVal = "screenX=" + 500
	}
	window.open(file, "calendar",yVal + "," + xVal + ",height=270,width=220,resizable=no,status=no,menubar=no");
	void(0);
}

function selectRecurDate(m, d, y, isPopup) {
	f = window.opener.document.forms[0];
	f._repeat_until.value = m + "/" + d + "/" + y;
	f.repeat_until.value = f._repeat_until.value;
	window.close();
}

function setSchedule(sid) {
	f = document.getElementById("setDefaultSchedule");
	f.scheduleid.value = sid;
	f.submit();
}

function changeSchedule(sel) {
	var url = document.URL.split('?')[0];
	document.location.href = url + "?scheduleid=" + sel.options[sel.selectedIndex].value;
}

function showHideCpanelTable(element) {
	var expires = new Date();
	var time = expires.getTime() + 2592000000;
	expires.setTime(time);
	var showHide = "";
	if (document.getElementById(element).style.display == "none") {
		document.getElementById(element).style.display='block';
		showHide = "show";
	} else {
		document.getElementById(element).style.display='none';
		showHide = "hide";
	}
	
	document.cookie = element + "=" + showHide + ";expires=" + expires.toGMTString();
}