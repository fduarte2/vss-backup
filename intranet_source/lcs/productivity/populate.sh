#!/bin/tcsh

source ~morecraf/.cshrc

# A script to populate the database on a given day
/usr/bin/php /var/www/html/lcs/productivity/populate.php
