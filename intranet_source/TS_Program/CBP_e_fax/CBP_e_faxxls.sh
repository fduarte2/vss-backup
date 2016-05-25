#!/bin/tcsh
source ~morecraf/.cshrc

# runs the CBP auto-fax-notify email 
#/usr/bin/php /web/web_pages/TS_Testing/CBP_e_faxxls.php $1
/usr/bin/php /var/www/html/TS_Program/CBP_e_fax/CBP_e_faxxls.php $1
