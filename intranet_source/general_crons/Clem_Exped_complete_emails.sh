#!/bin/tcsh
source ~morecraf/.cshrc

# sends emails for clem orders that POW expedites 
/usr/bin/php /web/web_pages/general_crons/Clem_Exped_complete_emails.php
