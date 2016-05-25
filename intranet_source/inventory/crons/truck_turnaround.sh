#!/bin/tcsh
source ~morecraf/.cshrc

# runs job to notify about rapdicool daily start
#/usr/bin/php /web/web_pages/TS_Testing/truck_turnaround.php
/usr/bin/php /web/web_pages/inventory/crons/truck_turnaround.php
