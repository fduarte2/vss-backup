#!/bin/tcsh
source ~morecraf/.cshrc

# runs the Clementine Daily Information cron 
/usr/bin/php /web/web_pages/TS_Program/ClemDaily/clementine_nightly_position.php
