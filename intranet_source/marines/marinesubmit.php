<?
  $rank = $HTTP_POST_VARS['rank'];
  $firstname = $HTTP_POST_VARS['firstname'];
  $middlename = $HTTP_POST_VARS['middlename'];
  $lastname = $HTTP_POST_VARS['lastname'];
  $addressone = $HTTP_POST_VARS['addressone'];
  $addresstwo = $HTTP_POST_VARS['addresstwo'];
  $city = $HTTP_POST_VARS['city'];
  $zip = $HTTP_POST_VARS['zip'];
  $dayphone = $HTTP_POST_VARS['dayphone'];
  $fax = $HTTP_POST_VARS['fax'];
  $email = $HTTP_POST_VARS['email'];
  $servicestart = $HTTP_POST_VARS['servicestart'];
  $serviceend = $HTTP_POST_VARS['serviceend'];
  $story = $HTTP_POST_VARS['story'];
  $submit = $HTTP_POST_VARS['submit'];

  if($rank == ""
		|| $firstname == ""
		|| $lastname == ""
		|| $addressone == ""
		|| $city == ""
		|| $zip == ""
		|| $dayphone == ""
		|| $servicestart == ""
		|| $serviceend == "") {
			$isbad = 1;
		}

/*
  if($submit == 'submit' && $isbad != 1){
	  if($handle = fopen("marinelog.xls", "a")){
		  fwrite($handle, $firstname.",".$middlename.",".$lastname.",".$rank.",".$addressone.",".$addresstwo.",".$city.",Delaware,".$zip.",".$dayphone.",".$fax.",".$email.",".$servicestart.",".$serviceend.",".$story);
		  fclose($handle);

		  header("Location: http://www.eportwilmington.com/login/");
	  } else {
		  echo "Internal Error";
		  exit;
	  }
  }
*/

		

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td colspan="2" align="center"><img align="center" border=0 width=180 height=50 src="marinesega2.jpg"></td>
   </tr>
   <tr>
      <td colspan="2"><font size="5"><b>Heritage Foundation - Share your Story:</font></b><br><font size="3">Active duty and retired Delaware United States Marines<br>On November 2006, the National Museum of the Marine Corps will be dedicated in Quantico, Virginia. The Museum is being built as the result of a public-private venture between the Marine Corps and the Marine Corps Heritage Foundation. Once completed, the National Museum of the Marine Corps will be the centerpiece of a campus called the Marine Corps Heritage Center.<br><br>The Museum will tell the story of the Marines "through the eyes of Marines." To that end, we are launching worldwide search for every man and woman who has worn the uniform of the U.S. Marines. The initiative aims to find all Marines and their families, build their awareness for the National Museum of the Marine Corps, and actively involve them in the shaping of the Museum and telling of the history of the Corps.</font></td>
   </tr>
   <tr>
      <td colspan="2"><font size="5"><b>Personal Information:</b></font></td>
   </tr>
   <tr>
      <td width="3%">&nbsp;</td>
	  <td>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="information" action="marinesubmit.php" method="post">
   <tr bgcolor="f0f0f0">
      <td width="20%"><font size="4" color="ff0000">* Rank:</font></td>
	  <td width="80%">
	     <input type="text" name="rank" value="<? echo $rank; ?>" size="20" maxlength="40">
		 <? if($submit == 'submit' && $rank == "") { ?>&nbsp;&nbsp;&nbsp;<font size="4" color="ff0000">> Required Field</font><? } ?>
      </td>
   </tr>
   <tr>
      <td width="20%"><font size="4" color="ff0000">* First Name:</font></td>
	  <td width="80%">
	     <input type="text" name="firstname" value="<? echo $firstname; ?>" size="20" maxlength="30">
		 <? if($submit == 'submit' && $firstname == "") { ?>&nbsp;&nbsp;&nbsp;<font size="4" color="ff0000">> Required Field</font><? } ?>
      </td>
   </tr>
   <tr bgcolor="f0f0f0">
      <td width="20%"><font size="4">Middle Name:</font></td>
	  <td width="80%">
	     <input type="text" name="middlename" value="<? echo $middlename; ?>" size="20" maxlength="30">
	  </td>
   </tr>
   <tr>
      <td width="20%"><font size="4" color="ff0000">* Last Name:</font></td>
	  <td width="80%">
	     <input type="text" name="lastname" value="<? echo $lastname; ?>" size="20" maxlength="30">
		 <? if($submit == 'submit' && $lastname == "") { ?>&nbsp;&nbsp;&nbsp;<font size="4" color="ff0000">> Required Field</font><? } ?>
	  </td>
   </tr>
   <tr bgcolor="f0f0f0">
      <td width="20%"><font size="4" color="ff0000">* Address Line 1:</font></td>
	  <td width="80%">
	     <input type="text" name="addressone" value="<? echo $addressone; ?>" size="40" maxlength="60">
		 <? if($submit == 'submit' && $addressone == "") { ?>&nbsp;&nbsp;&nbsp;<font size="4" color="ff0000">> Required Field</font><? } ?>
	  </td>
   </tr>
   <tr>
      <td width="20%"><font size="4">Address Line 2:</font></td>
	  <td width="80%">
	     <input type="text" name="addresstwo" value="<? echo $addresstwo; ?>" size="40" maxlength="60">
      </td>
   </tr>
   <tr bgcolor="f0f0f0">
      <td width="20%"><font size="4" color="ff0000">* City:</font></td>
	  <td width="80%">
	     <input type="text" name="city" value="<? echo $city; ?>" size="20" maxlength="30">
		 <? if($submit == 'submit' && $city == "") { ?>&nbsp;&nbsp;&nbsp;<font size="4" color="ff0000">> Required Field</font><? } ?>
	  </td>
   </tr>
   <tr>
      <td width="20%"><font size="4">State:</font></td>
	  <td width="80%"><font size="4">Delaware (this page specifically for DE residents)</font>
	  </td>
   </tr>
   <tr bgcolor="f0f0f0">
      <td width="20%"><font size="4" color="ff0000">* 5-digit ZIP:</font></td>
	  <td width="80%">
	     <input type="text" name="zip" value="<? echo $zip; ?>" size="5" maxlength="5">
		 <? if($submit == 'submit' && $zip == "") { ?>&nbsp;&nbsp;&nbsp;<font size="4" color="ff0000">> Required Field</font><? } ?>
	  </td>
   </tr>
   <tr>
      <td width="20%"><font size="4" color="ff0000">* Daytime Telephone:</font></td>
	  <td width="80%">
	     <input type="text" name="dayphone" value="<? echo $dayphone; ?>" size="20" maxlength="15">
		 <? if($submit == 'submit' && $dayphone == "") { ?>&nbsp;&nbsp;&nbsp;<font size="4" color="ff0000">> Required Field</font><? } ?>
	  </td>
   </tr>
   <tr bgcolor="f0f0f0">
      <td width="20%"><font size="4">Fax Number:</font></td>
	  <td width="80%">
	     <input type="text" name="fax" value="<? echo $fax; ?>" size="20" maxlength="15">
	  </td>
   </tr>
   <tr>
      <td width="20%"><font size="4">Email Address:</font></td>
	  <td width="80%">
	     <input type="text" name="email" value="<? echo $email; ?>" size="30" maxlength="50">
	  </td>
   </tr>
   <tr bgcolor="f0f0f0">
      <td width="20%"><font size="4" color="ff0000">* Service Start (MM/YY):</font></td>
	  <td width="80%">
	     <input type="text" name="servicestart" value="<? echo $servicestart; ?>" size="5" maxlength="5">
		 <? if($submit == 'submit' && $servicestart == "") { ?>&nbsp;&nbsp;&nbsp;<font size="4" color="ff0000">> Required Field</font><? } ?>
	  </td>
   </tr>
   <tr>
      <td width="20%"><font size="4" color="ff0000">* Service End (MM/YY):</font></td>
	  <td width="80%">
	     <input type="text" name="serviceend" value="<? echo $serviceend; ?>" size="5" maxlength="5">
		 <? if($submit == 'submit' && $serviceend == "") { ?>&nbsp;&nbsp;&nbsp;<font size="4" color="ff0000">> Required Field</font><? } ?>
	  </td>
   </tr>
   <tr>
      <td colspan="2" align="left"><font color="ff0000" size="1">* (required field)</font><br></td>
   </tr>
   <tr bgcolor="f0f0f0">
      <td width="20%"><font size="4">Your Story:</font></td>
	  <td width="80%"><textarea name="story" cols="60" rows="8"><? echo $story; ?></textarea></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td align="left"><input type="submit" name="submit" value="submit" src="submit.gif"></td>
   </tr>
</form>
</table>
</td></tr></table>