#!/bin/tcsh
source ~morecraf/.cshrc

# runs job to alert for shorted paper rolls
# /usr/bin/php /web/web_pages/TS_Testing/paper_short_notificattion.php
/usr/bin/php /web/web_pages/inventory/crons/paper_short_notificattion.php
