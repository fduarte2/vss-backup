#!/bin/tcsh
source ~morecraf/.cshrc

# runs job to alert for damaged paper rolls
#/usr/bin/php /web/web_pages/TS_Testing/paper_dmg_notification.php
/usr/bin/php /web/web_pages/inventory/crons/paper_dmg_notification.php
