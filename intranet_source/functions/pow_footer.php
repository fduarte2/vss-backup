<!-- END Page CONTENT -->
          </td>
        </tr>
      </table>
    </td>
  <tr>
 </table>

<!-- Bottom Links -->
<table width="100%" border="0" cellpadding="6">
  <tr> 
    <td align="center" background="/images/powbg.jpg">
    <a href="/index.php" style="color: #000000; font-size: 12px; text-decoration: none;"><b>Home</a><font color="#0b85e2">
<!--    <a href="/fourm/faq.php" style="color: #000000; font-size: 12px; text-decoration: none;">About</a> | 
    <a href="/fourm/search.php" style="color: #000000; font-size: 12px; text-decoration: none;">Search</a> | 
    <a href="/fourm/index.php" style="color: #000000; font-size: 12px; text-decoration: none;">Forum Index</a> | 
    <?
      if( $userdata['session_logged_in'] ){
        $sid = $userdata['session_id'];
        printf("<a href=\"/fourm/login.php?logout=true&sid=$sid\" style=\"color: #000000 ; font-size: 12px; text-decoration: none;\">Logout</a>");
      }
      else{
        printf("<a href=\"/fourm/login.php\" style=\"color: #000000; font-size: 12px; text-decoration: none;\">Login</a>");
      }
    ?>
    </td></font> !-->
   </td>
  </tr> </table><div align="center">

<!-- Finish the page -->
<table width="580" border="0" cellspacing="8" cellpadding="1" align="center">
 <tr>
    <td>
      <p align="center"><font size="1" color="#000000">
      Questions?  Please consult <a href="/legal.php">Legal Notices</a>.  Port Of Wilmington &copy; <?= date('Y') ?>; All rights reserved.
      </p>
    </td>
 </tr>
</table></div>
</body>
</html>
