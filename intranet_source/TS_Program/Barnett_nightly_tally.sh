#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send Barnett cargo movement
/usa/morecraf/bin/php /web/web_pages/TS_Program/Barnett_nightly_tally_IB.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/Barnett_nightly_tally_OB.php

