<?
  // All POW files need this session file included
  include("pow_session.php");
/*
  // Define some vars for the skeleton page
  $title = "Inventory System - Walmart";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }
*/
	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);

	$sql = "DROP SEQUENCE SAG_OWNER.WDI_TRUCK_SEQ";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);

	$sql = "CREATE SEQUENCE SAG_OWNER.WDI_TRUCK_SEQ
				START WITH 1
				MAXVALUE 99999999
				MINVALUE 0
				NOCYCLE
				NOCACHE
				NOORDER";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);

?>