<?
  /* Solid defines for E-Loads */

  // Database
  define(RF, "SAG_OWNER@RF");
  define(PASS, "OWNER");

  define(OPPY_NUMBER, "(xxx) xxx-xxxx");
  // Fine Stats
  define(fileStart, "A");
  define(fileCreate, "C");
  define(fileSent, "S");
  define(fileRecv, "R");
  define(fileFinalUpdateReq, "F");
  define(fileFinalUpdateCreate, "Y");
  define(fileFinalSent, "Z");
  define(fileRejc, "N");
  define(fileUnknown, "U");

  // Colors
  define(startColor, "#f20ef2");
  define(createColor, "#f20e9a");
  define(sentColor, "#f2f20e");
  define(unknownColor, "#f24b0e");
  define(receivedColor, "#63f40e");
  define(completedColor, "#0ef4c6");
  
  // E-Mail
  define(ediEmail, "edi@port.state.de.us");
  define(oppsEmail, "bdempsey@port.state.de.us, optemp@port.state.de.us");
  define(operatorEmail, "edi@port.state.de.us");

  // Paths
  define(OPP_PATH, "/web/web_pages/inventory/eloads/files/outbox/");
  define(OPP_EDI_PATH, OPP_PATH . "edi-request/");
  define(OPP_PICK_IN_PATH, OPP_PATH . "pick-in/");
  define(OPP_SHIP_OUT_PATH, OPP_PATH . "ship-out/");
  define(OPP_CONF_PATH, OPP_PATH . "conf/");
  define(PORT_PROCESSED_PATH, "/web/web_pages/inventory/eloads/files/processed/");
  define(PORT_PICK_IN_PATH, PORT_PROCESSED_PATH . "pick_in/");
  define(PORT_CONF_PATH, PORT_PROCESSED_PATH . "conf/");


/*---------------------------- Functions ------------------------------------*/

// Function to check the return value from Oracle- also gets a message from
// the caller to pass on to the user to be meaningful
function database_check($conn, $oracle_return, $message){
  $mailheaders = "From: " . "MailServer@port.state.de.us\r\n";
  $mailsubject = "Eloads Error!";
  if(!$oracle_return){
    // Here, we have encountered an error!
    $body .= $message;
    $body .= "\nTS --- Oracle Error: " . ora_errorcode($conn);
    $body .= "\nUser --- THIS JOB HAS FAILED!\n";
    ora_rollback($conn);
    $body .= "TRANSACTION HAS BEEN ROLLED BACK!\n";
    mail(operatorEmail, $mailsubject, $body, $mailheaders);
    exit;
  }
}

?>
