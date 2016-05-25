#!/bin/tcsh
source ~morecraf/.cshrc

# runs the vandenburg auto-invoice-notify email 
#/usr/bin/php /web/web_pages/TS_Testing/vandenburg_emails.php "05/03/2011"
/usr/bin/php /web/web_pages/TS_Program/vandenburg_emails.php
#/usr/bin/php /web/web_pages/TS_Program/vberg_email_unscanned.php
/usr/bin/php /web/web_pages/TS_Program/vandenburg_v2_emails.php
