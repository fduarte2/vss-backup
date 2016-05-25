#!/bin/tcsh
source ~morecraf/.cshrc

# walmart cyclecheck reminder if not yet done 
#/usr/bin/php /web/web_pages/TS_Testing/WM_weekly_audit_reminder.php
/usr/bin/php /web/web_pages/inventory/crons/WM_weekly_audit_reminder.php
