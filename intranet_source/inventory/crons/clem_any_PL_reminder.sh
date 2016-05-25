#!/bin/tcsh
source ~morecraf/.cshrc

# runs job to tell dole what's been happening to their paper
#/usr/bin/php /web/web_pages/TS_Testing/clem_any_PL_reminder.php
/usr/bin/php /web/web_pages/inventory/crons/clem_any_PL_reminder.php
