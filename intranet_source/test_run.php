<?
/*
*			Adam Walter, Oct 2008
*			This page allows OPS to add a Booking#
*			To previously EDI-received
*			Abitibi paper receipts.
******************************************************************/
error_reporting(-1);

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
//	$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
if($conn < 1){
		printf("10ERROR logging on to the RF Oracle Server: ");
		printf($conn);
		printf("END ERROR logging on to the RF Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
//	
	$Short_Term_Cursor = ora_open($conn);


	$sql = "SELECT * FROM WDI_LOAD_DCPO
			WHERE LOAD_NUM = '77030903'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
	echo ora_numrows($Short_Term_Cursor)."<br>";
	ora_fetch_into($Short_Term_Cursor, $short_term_row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
	echo ora_numrows($Short_Term_Cursor)."<br>";


	echo "<br><br>".substr($_SERVER["REQUEST_URI"], 1);
?>
