<?
/*
*
*
*	Adam Walter, March 2009
*
*	Script to send test emails.
*	Cron determiens when.
*
*********************************************************************/

	$mailto = "sadu@port.state.de.us,kumas12@live.com,fduarte@testeportde.com,noreplies@testeportde.com";
	$mailsubject = "Automatic Email Test";
	$mailheaders = "From:  AutoEmail@AutoEmail.com\r\n";
	$mailheaders .= "Cc: ithomas@port.state.de.us,ixthomas@gmail.com";

	mail($mailto, $mailsubject, "", $mailheaders);
?>