<?
 $bni_conn = ora_logon("SAG_OWNER@BNI", "SAG");
 if($bni_conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
 }
 $bni_cursor = ora_open($bni_conn);

 $rf_conn = ora_logon("SAG_OWNER@RF", "OWNER");
 if($rf_conn < 1){
    printf("Error logging on to the RF Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
 }
 $rf_cursor = ora_open($rf_conn);

 $lcs_conn = ora_logon("LABOR@LCS", "LABOR");
 if($lcs_conn < 1){
    printf("Error logging on to the LCS Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
 }
 $lcs_cursor = ora_open($lcs_conn);

// $prod_conn = ora_logon("APPS@PROD_BACKUP", "APPS");
 $prod_conn = ora_logon("APPS@PROD", "APPS");
 if($prod_conn < 1){
    printf("Error logging on to the PROD Oracle Server: ");
    printf(ora_errorcode($prod_conn));
    printf("Please try later!");
    exit;
 }
 $prod_cursor = ora_open($prod_conn);

 $arrDB[0] = "BNI";
 $arrDB[1] = "RF";
 $arrDB[2] = "LCS";
 $arrDB[3] = "PROD";

 $arrCursor[0] = $bni_cursor;
 $arrCursor[1] = $rf_cursor;
 $arrCursor[2] = $lcs_cursor;
 $arrCursor[3] = $prod_cursor;


 $query = "select t.tablespace_name, f.free, t.total, round(f.free/t.total *100,2)||'%' as percent from 
(select tablespace_name, round(sum(bytes)/1000000,0) total from dba_data_files group by tablespace_name) t,
(select tablespace_name, round(sum(bytes)/1000000,0) free from DBA_FREE_SPACE group by tablespace_name) f
where t.tablespace_name = f.tablespace_name(+) and f.free/t.total <0.5
order by f.free/t.total";
 for($i = 0; $i < 4; $i++){
	$statement = ora_parse($arrCursor[$i], $query);
 	ora_exec($arrCursor[$i]);
	$body .= $arrDB[$i]."\r\n";
 	while(ora_fetch($arrCursor[$i])){
		$body .= ora_getcolumn($arrCursor[$i], 0)."  ".ora_getcolumn($arrCursor[$i], 1)."/".ora_getcolumn($arrCursor[$i], 2)."/".ora_getcolumn($arrCursor[$i], 3)."\r\n";
		
 	}
	$body .= "\r\n";
 }

 $mailTo ="ithomas@port.state.de.us,";
 $mailTo .="rwang@port.state.de.us";
 $mailsubject = "DB Freespace";
 $mailheaders = "From: " . "hdadmin@port.state.de.us\r\n";
 $mailheaders.= "Bcc: hdadmin@port.state.de.us";
 mail($mailTo, $mailsubject, $body, $mailheaders);

 ora_close($bni_cursor);
 ora_logoff($bni_conn);
 ora_close($rf_cursor);
 ora_logoff($rf_conn);
 ora_close($lcs_cursor);
 ora_logoff($lcs_conn);
 ora_close($prod_cursor);
 ora_logoff($prod_conn);
?>
