#!/bin/tcsh
source ~morecraf/.cshrc

# runs job to tell dole what's been happening to their paper
/usr/bin/php /web/web_pages/inventory/crons/DT_FTP_POReport.php
