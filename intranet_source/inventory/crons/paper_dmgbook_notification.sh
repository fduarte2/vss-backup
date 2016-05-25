#!/bin/tcsh
source ~morecraf/.cshrc

# runs job to alert for dmg paper rolls
#/usr/bin/php /web/web_pages/TS_Testing/paper_dmgbook_notification.php
/usr/bin/php /web/web_pages/inventory/crons/paper_dmgbook_notification.php
