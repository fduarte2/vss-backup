#!/bin/tcsh

source ~morecraf/.cshrc

# Ship Schedule from DB-generated receipient list
/usa/morecraf/bin/php /web/web_pages/ship_schedule/ShipScheduleDist.php
