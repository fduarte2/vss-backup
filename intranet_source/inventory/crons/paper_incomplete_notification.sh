#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send out emails for incomplete paper orders
/usa/morecraf/bin/php /var/www/html/inventory/crons/paper_incomplete_notification.php
