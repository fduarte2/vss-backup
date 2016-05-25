<? 
/* iWebCal v1.1
 * Copyright (C) 2003 David A. Feldman.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of version 2 of the GNU General Public License 
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU 
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the Free Software Foundation, 
 * Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA. Or, 
 * visit http://gnu.org.
 * 
 * This file is part of the iWebCal calendar-viewing service. The iWebCal
 * service is available on the Web at http://iWebCal.com, and does not
 * require any programming knowledge or Web server configuration to use.
 * Anyone with an iCal or other .ics calendar file and a place to post
 * it on the Web can view the calendar using iWebCal.
 */
 
 // File version 1.1, last modified April 13, 2003.

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<title><? echo ($title ? $title : "Event Information") ?></title>
		<link href="iWebCal.css" rel="stylesheet" type="text/css" media="all">
	</head>

	<body class="PopupEventInfo" bgcolor="#ffffff" leftmargin="0" marginheight="0" marginwidth="0" topmargin="0">
		<table width="100%" border="0" cellspacing="0" cellpadding="6">
			<tr>
				<td class="Title"><? echo $title ?></td>
			</tr>
			<tr>
				<td class="Content"><? echo $content ?></td>
			</tr>
		</table>
		<p></p>
	</body>

</html>