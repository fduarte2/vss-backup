<?
//MAIN WEBSITE CONTROL LOGIC --<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-<-
// this code handles the setting of cookies, logins and logouts, of the whole site
// (what with this file being included on every page and all)
//global $HTTP_COOKIE_VARS, $HTTP_GET_VARS;

$temp_http_referer = explode("?", substr($_SERVER["REQUEST_URI"], 1));
$http_referer_noargs = $temp_http_referer[0];
$temp_http_referer_noargs = explode("/", substr($_SERVER["REQUEST_URI"], 1));
$http_referer = $temp_http_referer_noargs[sizeof($temp_http_referer_noargs) - 1];

	$cookiename = "dspcintranet";
	$cookiepath = "/";
	$cookiedomain = "";
	$cookiesecure = "0";


	// get the current session id and assorted data, if they exist.
	if (isset($HTTP_COOKIE_VARS[$cookiename . '_data']) )
	{
		$userdata = unserialize(stripslashes($HTTP_COOKIE_VARS[$cookiename . '_data']));
		setcookie($cookiename . '_data', serialize($userdata), 0, $cookiepath, $cookiedomain, $cookiesecure);
	}
	else
	{
		$userdata = array();
	}

	if($login == "login"){
		$loginname = $HTTP_POST_VARS['loginname'];
		$password = $HTTP_POST_VARS['password'];
		$password = md5($password);
		$login_conn = ora_logon("SAG_OWNER@BNI", "SAG");
		//$login_conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
		if($login_conn < 1){
			printf("Error logging on to the BNI Oracle Server: ");
			printf(ora_errorcode($login_conn));
			printf("Please try later!");
			exit;
		}
		$login_cursor = ora_open($login_conn);

		$sql = "SELECT PERMISSIONS, PASSWORD, EMAIL_ADDRESS 
				FROM INTRANET_USERS
				WHERE USERNAME = '".$loginname."'";
		ora_parse($login_cursor, $sql);
		ora_exec($login_cursor);
		if(!ora_fetch_into($login_cursor, $login_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$login_message = "No user ".$loginname." found in Intranet system.<br>";
		} elseif($login_row['PASSWORD'] != $password){
			$login_message = "Invalid Pasword.<br>";
		} else {
			$userdata['username'] = $loginname;
			$userdata['user_type'] = $login_row['PERMISSIONS'];
			$userdata['user_email'] = $login_row['EMAIL_ADDRESS'];
			setcookie($cookiename . '_data', serialize($userdata), 0, $cookiepath, $cookiedomain, $cookiesecure);
//			echo "cookie:  ".print_r($userdata)."<br>".print_r(unserialize(stripslashes(serialize($userdata))));
//			echo $cookiename . "_data"."  ".serialize($userdata)."  ".$cookiepath."  ".$cookiedomain."  ".$cookiesecure."<br>";
		}
		ora_close($login_cursor);
	} elseif($HTTP_GET_VARS['logout'] == true){
		setcookie($cookiename . '_data', '', time() - 31536000, $cookiepath, $cookiedomain, $cookiesecure);
		$userdata = array();
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
"http://www.w3.org/TR/REC-html40/loose.dtd">

<!--                   ****** NOTICE ******
     Take CARE when changing this file as it is linked to EVERY page 
     the Intranet hosts.  Look into the embedded php comments under the 
     user management section for help on user / section management.
 -->
<html>
<head>
<meta name="DESCRIPTION" content="Port of Wilmington, Delaware." />
<meta NAME="KEYWORDS" CONTENT="ports, port, wilmington, delaware, state of delaware, port of wilmington" />
<?
// as this file is on EVERY PAGE, we need to keep track of which page we were on/came from.
// very useful for logging in and out, at the very least.

  if($title){
   printf("<title>Port of Wilmington - $title</title>");
  }
  else{
   printf("<title>Port of Wilmington</title>");
  }


?>

<link rel="SHORTCUT ICON" href="/favicon.ico" />
<style type="text/css">

/*
  The original subSilver Theme for phpBB version 2+
  Created by subBlue design
  http://www.subBlue.com

  NOTE: These CSS definitions are stored within the main page body so that you can use the phpBB2
  theme administration centre. When you have finalised your style you could cut the final CSS code
  and place it in an external file, deleting this section to save bandwidth.
*/

/* General font families for common tags */
font,th,td,p { font-family: Verdana, Arial, Helvetica, sans-serif }
a:link,a:active,a:visited { color : #006699; }
a:hover		{ text-decoration: underline; color : #DD6900; }
hr	{ height: 0px; border: solid #D1D7DC 0px; border-top-width: 1px;}

/* This is the outline round the main forum tables */
.forumline	{ background-color: #FFFFFF; border: 2px #006699 solid; }

/* Header cells - the blue and silver gradient backgrounds 
th	{
	color: #FFA34F; font-size: small; font-weight : bold;
	background-color: #006699; height: 25px;
	background-image: url(/fourm/templates/subSilver/images/cellpic3.gif);
}*/

td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
			background-image: url(/fourm/templates/subSilver/images/cellpic1.gif);
			background-color:#D1D7DC; border: #FFFFFF; border-style: solid; height: 28px;
}

/*
  Setting additional nice inner borders for the main table cells.
  The names indicate which sides the border will be on.
  Don't worry if you don't understand this, just ignore it :-)
*/
td.cat,td.catHead,td.catBottom {
	height: 29px;
	border-width: 0px 0px 0px 0px;
}
th.thHead,th.thSides,th.thTop,th.thLeft,th.thRight,th.thBottom,th.thCornerL,th.thCornerR {
	font-weight: bold; border: #FFFFFF; border-style: solid; height: 28px;
}
td.row3Right,td.spaceRow {
	background-color: #D1D7DC; border: #FFFFFF; border-style: solid;
}

th.thHead,td.catHead { font-size: 12px; border-width: 1px 1px 0px 1px; }
th.thSides,td.catSides,td.spaceRow	 { border-width: 0px 1px 0px 1px; }
th.thRight,td.catRight,td.row3Right	 { border-width: 0px 1px 0px 0px; }
th.thLeft,td.catLeft	  { border-width: 0px 0px 0px 1px; }
th.thBottom,td.catBottom  { border-width: 0px 1px 1px 1px; }
th.thTop	 { border-width: 1px 0px 0px 0px; }
th.thCornerL { border-width: 1px 0px 0px 1px; }
th.thCornerR { border-width: 1px 1px 0px 0px; }

/* The largest text used in the index page title and toptic title etc. */
.maintitle	{
	font-weight: bold; font-size: 22px; font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif;
	text-decoration: none; line-height : 120%; color : #000000;
}

/* General text */
.gen { font-size : 12px; }
.genmed { font-size : 11px; }
.gensmall { font-size : 10px; }
.gen,.genmed,.gensmall { color : #000000; }
a.gen,a.genmed,a.gensmall { color: #006699; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover	{ color: #DD6900; text-decoration: underline; }

/* The register, login, search etc links at the top of the page */
.mainmenu		{ font-size : 11px; color : #000000 }
a.mainmenu		{ text-decoration: none; color : #006699;  }
a.mainmenu:hover{ text-decoration: underline; color : #DD6900; }

/* Forum category titles */
.cattitle		{ font-weight: bold; font-size: 12px ; letter-spacing: 1px; color : #006699}
a.cattitle		{ text-decoration: none; color : #006699; }
a.cattitle:hover{ text-decoration: underline; }

/* Forum title: Text and link to the forums used in: index.php */
.forumlink		{ font-weight: bold; font-size: 12px; color : #006699; }
a.forumlink 	{ text-decoration: none; color : #006699; }
a.forumlink:hover{ text-decoration: underline; color : #DD6900; }

/* Used for the navigation text, (Page 1,2,3 etc) and the navigation bar when in a forum */
.nav			{ font-weight: bold; font-size: 11px; color : #000000;}
a.nav			{ text-decoration: none; color : #006699; }
a.nav:hover		{ text-decoration: underline; }

/* titles for the topics: could specify viewed link colour too */
a.topictitle:link   { text-decoration: none; color : #006699; }
a.topictitle:visited { text-decoration: none; color : #5493B4; }
a.topictitle:hover	{ text-decoration: underline; color : #DD6900; }

/* Name of poster in viewmsg.php and viewtopic.php and other places */
.name			{ font-size : 11px; color : #000000;}

/* Location, number of posts, post date etc */
.postdetails		{ font-size : 10px; color : #000000; }

/* The content of the posts (body of text) */
.postbody { font-size : 12px; line-height: 18px}
a.postlink:link	{ text-decoration: none; color : #006699 }
a.postlink:visited { text-decoration: none; color : #5493B4; }
a.postlink:hover { text-decoration: underline; color : #DD6900}

/* Copyright and bottom info */
.copyright		{ font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #444444; letter-spacing: -1px;}
a.copyright		{ color: #444444; text-decoration: none;}
a.copyright:hover { color: #000000; text-decoration: underline;}



</STYLE>

<link href="http://intranet/stylesheet.css" rel="stylesheet" type="text/css" />
</head>

<body link="#336633" vlink="#999999" alink="#999999">

<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td width="100%" align="center"><a href="/index.php"><img src="/images/application_access.jpg" border="0" height="88" width="100%"></a></td>
 </tr>
</table>

<!-- Side Link Bar -->
 <table width="100%" border="0" cellspacing="0" cellpadding="0" id="leftnav"> 
  <tr> 
   <td width="120" bgcolor="#CCCCCC" align="left" valign="top" nowrap>
     <table>
   <?
//      $sid = $userdata['session_id'];

   $user_type = $userdata['user_type'];
   // user_type is in the format TYPE-TYPE-TYPE.... a user can have any number of types
   // First we split the data into an array
   //$user_types = array();
   $user_types = split("-", $user_type);
   //print_r($user_types);
   // Then, we use array_search('NEEDLE', haystack) to search through it

      // Here we check for the user- if they are logged in or not
      if ($userdata['username'] != "") {
        $user = $userdata['username'];
        printf("<tr><td><font style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px;\">Welcome <a href=\"/fourm/profile.php?mode=editprofile\" style=\"text-decoration: none\"><b><font color=\"black\">$user</b>!</font><br />");
        //print_r($userdata);
//        $image = $userdata['user_avatar'];
        $image = "";
	
        if($image != ""){
          printf("<img src=\"/fourm/images/avatars/gallery/$image\" border=\"0\"></a></td></tr>");
        } else{
          printf("</a></td></tr>");
        }

        printf("<tr><td><a href=\"/index.php?logout=true\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Logout</font></a></td></tr>");
      } else {
        printf("<form action=\"".$http_referer."\" method=\"post\"><input type=\"hidden\" name=\"login\" value=\"login\"><tr><td><font style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px;\">Username:<br /></font><input type=\"text\" name=\"loginname\" size=\"12\" maxlength=\"20\"></td></tr><tr><td><font style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px;\">Password:<br /></font><input type=\"password\" name=\"password\" size=\"12\" maxlength=\"20\">&nbsp;<input name=\"login\" value=\"login\" type=\"image\" src=\"/images/login.gif\" size=\"18,18\" border=\"0\"></td></tr></form>");

        //printf("<tr><td><a href=\"/fourm/profile.php?mode=register\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Request a Login</a></td></tr>");
      }

      printf("</table>");
      printf("<hr color=\"#000000\">");
      printf("<table>");

   /****************************** USER MAMAGEMENT CODE *******************/

   /*************  AREA_TYPE **********************************************
    * Here, we expect $area_type to be one of:
    * ROOT: Main Page - Show user all areas they have access to
    *
    * CCDS: Show main CCDS Links only
    *
    * INVE: Show main links to Inventory
    *   Subsets:
    *   HOLE: Show Holmen links
    *   ELOA: E-Loads links
    *
    * FINA: Financial Applications
    *
    * ACCT: Accounting pages
    *
    * HRMS: Human Resources
    *
    * CLAI: Claims System
    *
    * DIRE: Directors Page
    *
    * LCS: LCS Application
    *
    * SUPV: Supervisors Page
    *
    * TECH: Technology Solutions Page
    *
    * RPTS: General Port Reports
    *
    */

   /****************************** USER TYPE ****************************
    * Variable found in $userdata['user_type']
    * Is a | separated list describing what type the user is...
    * Can be any one of:
    * 
    * ROOT: System administrator has access to everything
    *
    * CCDS: User can access the CCDS System
    *
    * INVE: User can access Inventory
    * 
    * CLR: Cargo Load Release
    * 
    * DIRE: User can access Director pages
    *
    * CLAI: User can access the claims system
    *
    * FINA: Finance user
    *
    * ACCT: Accounting user
    *
    * TECH: Tech user
    *
    * LCS / SUPV: Supervisor
    *
    */

   // First print all the main links for all users
      printf("<tr><td><a href=\"/index.php\" style=\"color: #000000; font-family: Arial,
 Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Home</a></td></tr>");

      printf("<tr><td><a href=\"/documents/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Documents / Forms</a></td></tr>");

//      printf("<tr><td><a href=\"/documents/forms.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Forms</a></td></tr>");

//      printf("<tr><td><a href=\"/fourm/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Forum (New HD)</a></td></tr>");
/*      printf("<tr><td><a href=\"/fourm/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Forum</a></td></tr>");

      if($userdata['user_level'] == 1){
        printf("<tr><td><a href=\"/fourm/admin/index.php?sid=$sid\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Forum Admin</a></td></tr>");
      }
*/
//      printf("<tr><td><a href=\"/help_desk/ts_help.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Help Desk (HD)</a></td></tr>");
      printf("<tr><td><a href=\"/HelpDeskNew/HD_list.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Help Desk (HD)</a></td></tr>");

//      printf("<tr><td><a href=\"/request/radio_repair/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Radios</a></td></tr>");

      printf("</table>");
      printf("<hr color=\"#000000\">");
      printf("<table>");
   
      // Place the links the user can accesss here
      if((array_search('ACCT', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/accounting/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Accounting</a></td></tr>");
      }
/*      
      if((array_search('CCDS', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/ccds/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">CCDS</a></td></tr>");
      }
*/    
      if((array_search('CLAI', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE) || (array_search('DIRE', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/claims/\" style=\"color: #000000; font-family:
 Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Claims</a></td></tr>");
   }

      if((array_search('DIRE', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/director/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Directors</a></td></tr>");
      }
      
      if((array_search('FINA', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/finance/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Finance</a></td></tr>");
      }

      if((array_search('MKTG', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/marketing/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Marketing</a></td></tr>");
      }

      if((array_search('HRMS', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/hr/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">HRMS</a></td></tr>");
      }
      
      if((array_search('INVE', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/inventory/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Inventory</a></td></tr>");
      }
      
      if((array_search('CLR', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/CLR/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Ocean Manifest</a></td></tr>");
      }
      
      if((array_search('LCS', $user_types) !== FALSE) || (array_search('DIRE', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE) || (array_search('SUPV', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/lcs/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">LCS</a></td></tr>");
	printf("<tr><td><a href=\"/supervisors/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Supervisors</a></td></tr>");
      }
   
      if((array_search('TECH', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/tech/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Tech Solutions</a></td></tr>");
      }
      
      if((array_search('RPTS', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/reports/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Records</a></td></tr>");
      }
      
      if((array_search('TELR', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	printf("<tr><td><a href=\"/front_window/index_teller.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Front Window</a></td></tr>");
      }
      
      printf("</table>");
      printf("<hr color=\"#000000\">");
      printf("<table>");
   
      // Switch based on $area_type to make sure the user has access to the
      // area they are in!
      switch($area_type){

      case 'ACCT':
	// Accounting
	if ((array_search('ACCT', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
	  printf("<tr><td><a href=\"/accounting/adp/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">ADP Import</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/accounting/forklift/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Forklift Import</a></td></tr>");
	  
	  printf("<tr><td><a href=\"http://dspc-s17.dspc:8000/OA_HTML/US/ICXINDEX.htm\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Oracle Applications</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/accounting/payroll/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Payroll Accrual</a></td></tr>");

          printf("<tr><td><a href=\"/director/olap/solomon.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Solomon GL</a></td></tr>");
	} else {
	  $access_denied = TRUE;
	}
	
	break;

      case 'CCDS':
	// CCDS
	if((array_search('CCDS', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	  printf("<tr><td><a href=\"/ccds/receiving/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Receiving</a></td></tr>");

	  printf("<tr><td><a href=\"/ccds/expediting/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Expediting</a></td></tr>");

	  printf("<tr><td><a href=\"/ccds/reporting/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Reporting</a></td></tr>");

	  printf("<tr><td><a href=\"/ccds/maintain/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Maintain</a></td></tr>");
	}
	else{
	  // Need to tell the user they cannot access this area!
	  $access_denied = TRUE;
	}
	
	break;
      
      case 'CLAI':
	// Claims
	if ((array_search('CLAI', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
	  printf("<tr><td><a href=\"/claims/add.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Add Claim</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/claims/edit.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Edit Claim</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/claims/delete.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Delete Claim</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/claims/finalize.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Finalize Claim</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/claims/final_notes.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Final Notes</a></td></tr>");
/*          printf("<tr><td><a href=\"/claims/meat_claim/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">CCDS Claim</a></td></tr>"); */
          printf("<tr><td><a href=\"/claims/fruit_claim/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Fruit Claim</a></td></tr>");
	  printf("<tr><td><a href=\"/claims/report.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Reports</a></td></tr>"); 
	  
	  printf("<tr><td><a href=\"/claims/customer.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Customer List</a></td></tr>");
	} else {
	  $access_denied = TRUE;
	}

	break;

      case 'DIRE':
	// Directors
	if ((array_search('DIRE', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
	  printf("<tr><td><a href=\"/director/cargo_volumes\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Cargo Volumes</a></td></tr>");

	  printf("<tr><td><a href=\"/director/olap/fina.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Financials</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/director/library/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Library</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/director/hotlist_agenda_combo/hotlist_agenda_combo.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Agenda/Hot-List</a></td></tr>");
          printf("<tr><td><a href=\"/supervisors/temp_change/current_temp.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Temp Status</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/director/data_warehouse/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Reports</a></td></tr>");
          printf("<tr><td><a href=\"/director/data_warehouse/arch_report.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Archived Reports</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/director/flow_chart/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Work Flow</a></td></tr>");
	  
	  // printf("<a href=\"/director/car/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Car Reservation</a></td></tr>");
	} else {
	  $access_denied = TRUE;
	}

	break;
	
      case 'ELOA':
	// Eloads
	if ((array_search('INVE', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
        printf("<tr><td><a href=\"/inventory/eloads/loads/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Pending Loads</a></td></tr>");

        printf("<tr><td><a href=\"/inventory/eloads/loads/old_loads.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Completed Loads</a></td></tr>");

        printf("<tr><td><a href=\"/inventory/eloads/orders/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Pending Orders</a></td></tr>");

        printf("<tr><td><a href=\"/inventory/eloads/orders/old_loads.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Completed Orders</a></td></tr>");

        printf("<tr><td><a href=\"/inventory/fruit/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Fruit Reports</a></td></tr>");
      } else {
        // user cannot access this area!
        $access_denied = TRUE;
      }

      break;

     case 'FINA':
       // Financials
       if ((array_search('FINA', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
	 printf("<tr><td><a href=\"/finance/bni/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">BNI</a></td></tr>");

	 printf("<tr><td><a href=\"/finance/rf/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">RF</a></td></tr>");

	 printf("<tr><td><a href=\"/finance/invoice/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Load Invoices</a></td></tr>");

	 printf("<tr><td><a href=\"/finance/gl/index.php/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">GL Allocation</a></td></tr>");

	 printf("<tr><td><a href=\"/finance/storage/screens/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Storage Pages</a></td></tr>");

       } else {
        // user cannot access this area!
        $access_denied = TRUE;
      }

      break;

      case 'FORM':
      case 'ROOT':
      case 'GATE':
	// Non-Working Group
	// put some generic links here for these non-work pages
	// Security Gate
	if (array_search('GATE', $user_types) !== FALSE) {
	  printf("<tr><td><a href=\"/hr/security_gate/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">View Security Gate Requests</a></td></tr>");

	  printf("<tr><td><a href=\"file://dspc-236/gate fax\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Gate Faxes</a></td></tr>");

	} else {
	  $access_denied = TRUE;
	}
	break;

      case 'HOLE':
	// Inventory - Holmen
	if ((array_search('INVE', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
	  printf("<tr><td><a href=\"/inventory/holmen/receiving/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Receiving</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/inventory/holmen/expediting/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Expediting</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/inventory/holmen/reporting/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Reporting</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/inventory/holmen/maintain/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Maintain</a></td></tr>");
	} elseif (array_search('FINA', $user_types) !== FALSE) {
	  printf("<tr><td><a href=\"/inventory/holmen/reporting/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Holmen Reporting</a></td></tr>");
	} else {
	  // user cannot access this area!
	  $access_denied = TRUE;
	}
	
	break;
	

      case 'HRMS':
	// Human Resources
	if ((array_search('HRMS', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
	  printf("<tr><td><a href=\"/hr/adp/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Export to ADP</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/hr/users/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">PDA Users</a></td></tr>");
	  
//	  printf("<tr><td><a href=\"/hr/security_location/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Security Locations</a></td></tr>");
//          printf("<tr><td><a href=\"/hr/scan_report/upload.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Security Log Upload</a></td></tr>");
	  
//	  printf("<tr><td><a href=\"/hr/scan_report/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Security Log Report</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/hr/pipescan_report/PipeScannerUpload.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Pipescanner Report</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/hr/unemployment/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Unemployment</a></td></tr>");

	  printf("<tr><td><a href=\"/hr/security_gate/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Visitor Report</a></td></tr>");

	  printf("<tr><td><a href=\"/hr/safety/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Safety Stats</a></td></tr>");

	  printf("<tr><td><a href=\"/hr/ppe/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">PPE</a></td></tr>");

	  printf("<tr><td><a href=\"file://dspc-236/gate fax\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Gate Faxes</a></td></tr>");

	} else {
	  $access_denied = TRUE;
	}
	
	break;

      case 'INVE':
	  case 'NPSA':
	// Inventory
	if((array_search('INVE', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){
	  printf("<tr><td><a href=\"/inventory/holmen/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Holmen Paper</a></td></tr>");

          printf("<tr><td><a href=\"/inventory/invreprint/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Re-Print Invoices</a></td></tr>");

	  printf("<tr><td><a href=\"/inventory/fruit/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Fruit System</a></td></tr>");

	  printf("<tr><td><a href=\"/inventory/manifest_location/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Manifest Location</a></td></tr>");

	  printf("<tr><td><a href=\"/inventory/ship_schedule/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Schedule Upload</a></td></tr>");

	  printf("<tr><td><a href=\"/inventory/set_receiving/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Set Date Received</a></td></tr>");

	  printf("<tr><td><a href=\"/director/olap/whs.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Whse Inventory</a></td></tr>");

	  printf("<tr><td><a href=\"/inventory/warehouse_lease/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Whse Lease</a></td></tr>");

//	  printf("<tr><td><a href=\"/inventory/confirm/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Whse Utilization</a></td></tr>");

          printf("<tr><td><a href=\"/inventory/recon/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">On Hold Pallets</a></td></tr>");

          printf("<tr><td><a href=\"/inventory/order_supervisor/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Assign Order</a></td></tr>");
          printf("<tr><td><a href=\"/inventory/misc_billing/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Import Misc Billing</a></td></tr>");
          printf("<tr><td><a href=\"/inventory/harbor_master/date_departed.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Harbor Master</a></td></tr>");
          printf("<tr><td><a href=\"/inventory/bni_customer/print_customer.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">View Bni Customer</a></td></tr>");

//          printf("<tr><td><a href=\"/inventory/clementine_pages/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Clementine Functions</a></td></tr>");

          printf("<tr><td><a href=\"/inventory/location_update/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Update Warehouse Locations</a></td></tr>");          
/*
          printf("<tr><td><a href=\"/inventory/modify_customer/modify_customer.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Modify Customer Information</a></td></tr>");          
*/
          printf("<tr><td><a href=\"/supervisors/productivity_report_pages/OPS_vessel_prod_info.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Enter Vessel Productivity Data</a></td></tr>");          

          printf("<tr><td><a href=\"/inventory/user_guides/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">User Guides</a></td></tr>");

//          printf("<tr><td><a href=\"/inventory/exporter_profiles/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Exporter Profiles</a></td></tr>");


	}else{
	  // user cannot access this area!
	  $access_denied = TRUE;
	}
	
	break;

	  case 'CLR':
	// Cargo Load Release
	if ((array_search('CLR', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
//	  printf("<tr><td><a href=\"/CLR/seal_change.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Seal Change</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/CLR/push_to_CLR.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">CLR-EDI review</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/CLR/push_to_CLR_confirm.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">CLR-EDI push</a></td></tr>");
	  
	  printf("<tr><td><a href=\"/CLR/lloyd_to_LR.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">LLoyd Match</a></td></tr>");

	  printf("<tr><td><a href=\"/CLR/ignore_lloyd.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">LLoyd Ignore</a></td></tr>");

	  printf("<tr><td><a href=\"/CLR/CLR_main_upload.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Manual Entry</a></td></tr>");

	  printf("<tr><td><a href=\"/CLR/EDI_codes.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">CLR-EDI 350 Codes</a></td></tr>");
	} else {
	  // user cannot access this area!
	  $access_denied = TRUE;
	}
	
	break;
	

     case 'LCS':
     case 'SUPV':
       // LCS Application
       if ((array_search('LCS', $user_types) !== FALSE) || (array_search('DIRE', $user_types) !== FALSE) || 
	   (array_search('SUPV', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {

		printf("<tr><td><a href=\"http://dspc-s16/director/olap/lcs.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">LCS Hourly Detail</a></td></tr>");
        
        printf("<tr><td><a href=\"http://dspc-s16/director/olap/productivity.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Productivity Detail</a></td></tr>");
        
        printf("<tr><td><a href=\"http://dspc-s16/director/olap/prod_sum.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Productivity Summary</a></td></tr>");


	 printf("<tr><td><a href=\"/lcs/commodity/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Budget (Plts/Hr)</a></td></tr>");
	 
	 printf("<tr><td><a href=\"/lcs/commodity/budget.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Budget (Tons/Hr)</a></td></tr>");
	 
	 printf("<tr><td><a href=\"/lcs/hire_plan/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Hire Plan</a></td></tr>");
	 
	 printf("<tr><td><a href=\"/lcs/hire_review/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Hire Review</a></td></tr>");

         printf("<tr><td><a href=\"/supervisors/projection/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Commodity</a></td></tr>");

         printf("<tr><td><a href=\"/supervisors/projection/productivity.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Activity</a></td></tr>");	 
	 
	 printf("<tr><td><a href=\"/supervisors/labor_ticket/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">O/S Labor Tickets</a></td></tr>");
	 
	 printf("<tr><td><a href=\"/lcs/productivity/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Productivity</a></td></tr>");
	 
	printf("<tr><td><a href=\"/lcs/productivity/wk_prod_data.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Weekly Productivity Data</a></td></tr>");
	 
	printf("<tr><td><a href=\"/supervisors/temp_change/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Temp Change</a></td></tr>");

//	 printf("<tr><td><a href=\"/supervisors/temp_change/display_current_temp.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Temp Monitor</a></td></tr>");
	 printf("<tr><td><a href=\"http://172.22.15.98/temp-monitor.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Temp Monitor</a></td></tr>");

	 printf("<tr><td><a href=\"/supervisors/warehouse_status/box_comments.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">E&M Comments</a></td></tr>");

	 printf("<tr><td><a href=\"/supervisors/confirm/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Discharge Confirm</a></td></tr>");

	 printf("<tr><td><a href=\"/supervisors/Truck_To_Ship_Conversion.php/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Ship to Truck Conversion</a></td></tr>");

	 printf("<tr><td><a href=\"/supervisors/reports/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Reports</a></td></tr>");

	 printf("</table>");
	 printf("<hr>");
	 printf("<table>");
       } else {
	 $access_denied = TRUE;
       }
       
       break;
 
      case 'TECH':
	// TECH
	if ((array_search('TECH', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
//	  printf("<tr><td><a href=\"/timesheet/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Time Sheets</a></td></tr>");

	  // printf("<tr><td><a href=\"/tech/logs/log.html\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Computer Logs</a></td></tr>");
	  
	  // printf("<tr><td><a href=\"/tech/audit/\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Computer Record</a></td></tr>");

	  // printf("<tr><td><a href=\"/tech/scanners\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Scanner Inventory</a></td></tr>");

	  // printf("<tr><td><a href=\"/tech/equipment\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Equipment Tracking</a></td></tr>");

         // printf("<tr><td><a href=\"/tech/upload_csloc/upload_query.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Upload C&S Loc</a></td></tr>");

         // printf("<tr><td><a href=\"/tech/lot_entry/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Add/Edit a Lot Id</a></td></tr>");

         // printf("<tr><td><a href=\"/supervisors/temp_change/commodity_list/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Add/Edit Commodity</a></td></tr>");

         // printf("<tr><td><a href=\"/tech/vessel/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Populate Query</a></td></tr>");

         printf("<tr><td><a href=\"/tech/cutoffday.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Set LCS Cutoff Day</a></td></tr>");

         // printf("<tr><td><a href=\"/tech/magazines/listing.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Magazines</a></td></tr>");

	  // printf("<tr><td><a href=\"/tech/wk_prod_data.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Weekly Productivity Report Data</a></td></tr>");

	  printf("<tr><td><a href=\"/tech/clementine_pics/listing.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Clementine Pictures</a></td></tr>");

	  // printf("<tr><td><a href=\"/tech/import_duplicate_check/index.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">Import Duplication Check</a></td></tr>");

	  printf("<tr><td><a href=\"/tech/TS_RF_vessel.php\" style=\"color: #000000; font-family: Arial, Helvetica, Geneva; font-size: 12px; text-decoration: none;\">RF Vessel Data</a></td></tr>");

        } else {
	  $access_denied = TRUE;
	}
	break;
	
      case 'MKTG':
	if ((array_search('MKTG', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
		// no sidelinks for now
        } else {
	  $access_denied = TRUE;
	}

	break;
	
      case 'RPTS':
	if ((array_search('RPTS', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
		// no sidelinks for now
        } else {
	  $access_denied = TRUE;
	}

	break;
	
      case 'TELR':
	if ((array_search('TELR', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
		// no sidelinks for now
        } else {
	  $access_denied = TRUE;
	}

	break;

	  case 'CBE1':
	if ((array_search('CBE1', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)) {
		// no sidelinks for now
        } else {
	  $access_denied = TRUE;
	}

	break;
	
      default:
	// Otherwise...
	$filename = $_SERVER['PHP_SELF'];
	printf("Area Type Not Defined in $filename - Please contact TS!");
	break;

   } // end switch
?>
   </table>
       </td>
   <td width="5" bgcolor="#CCCCCC" align="left" valign="top" nowrap><img src="/images/clear.gif" width="4" height="10"></td>
   <td width="17" align="left" valign="top" nowrap><img src="/images/curve.jpg" width="19" height="39"></td>
   <td width="100%" valign="top">
      <!-- Main page content -->
      <table width="99%" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td align="left" valign="left">

<!--  ---------------------END CONTENT---------------------------------- -->
