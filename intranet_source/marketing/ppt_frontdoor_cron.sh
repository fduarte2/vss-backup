#!/bin/tcsh
source ~morecraf/.cshrc

# runs job to alert for damaged paper rolls
/usr/bin/php /web/web_pages/marketing/ppt_frontdoor_cron.php
