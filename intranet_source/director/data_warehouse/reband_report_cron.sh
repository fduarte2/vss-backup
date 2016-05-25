#!/bin/tcsh
source ~morecraf/.cshrc

# runs the reband report cron 
/usr/bin/php /web/web_pages/director/data_warehouse/reband_report_cron.php
