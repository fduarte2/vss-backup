#!/bin/tcsh
source ~morecraf/.cshrc

# runs job to alert for Dole9722 Audit
#/usr/bin/php /web/web_pages/TS_Testing/Dole9722monthlyaudit.php
/usr/bin/php /web/web_pages/inventory/crons/Dole9722monthlyaudit.php
