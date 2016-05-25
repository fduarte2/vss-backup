#!/bin/tcsh
source ~morecraf/.cshrc

# runs the nightly "trucked in cargo" email 
#/usr/bin/php /web/web_pages/TS_Testing/nightly_chilean_truckin_comms.php
/usr/bin/php /web/web_pages/inventory/crons/nightly_chilean_truckin_comms.php
