<?
	$order_num = $HTTP_POST_VARS['order_num'];
	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

if (!function_exists('hex2bin')) {
	function hex2bin($data) {
		static $old;
		if ($old === null) {
		     $old = version_compare(PHP_VERSION, '5.2', '<');
		}
       $isobj = false;
       
	   if (is_scalar($data) || (($isobj = is_object($data)) && method_exists($data, '__toString'))) {
			if ($isobj && $old) {
                ob_start();
                echo $data;
                $data = ob_get_clean();
            }
            else {
                $data = (string) $data;
            }
        }
        else {
            trigger_error(__FUNCTION__.'() expects parameter 1 to be string, ' . gettype($data) . ' given', E_USER_WARNING);
            return;//null in this case
        }

        $len = strlen($data);
        
		if ($len % 2) {
            trigger_error(__FUNCTION__.'(): Hexadecimal input string must have an even length', E_USER_WARNING);
            return false;
        }
        
		if (strspn($data, '0123456789abcdefABCDEF') != $len) {
            trigger_error(__FUNCTION__.'(): Input string must be hexadecimal string', E_USER_WARNING);
            return false;
        }
        
		return pack('H*', $data);
    }
}

$hex = hex2bin("$_REQUEST[SigField]");
//echo $hex;
$File = $order_num.".bmp";
$FileForUpdate = $order_num.".jpg";
$Handle = fopen("./signatures/".$File, 'w'); 
fwrite($Handle, $hex); 
fclose($Handle); 
// run shell to change file from bmp to jpeg
$sql = "UPDATE ARGENFRUIT_CHECKIN_ID
		SET SIGNATURE = '".$FileForUpdate."'
		WHERE CHECKIN_ID = (SELECT CHECKIN_ID FROM ARGENFRUIT_ORDER_HEADER WHERE ORDER_NUM = '".$order_num."')";
//echo $sql."<br>";
$update = ociparse($rfconn, $sql);
ociexecute($update);
system("bmptoppm <./signatures/".$order_num.".bmp | cjpeg -q 90 > ./signatures/".$order_num.".jpg");
//system("bmptoppm <./signatures/test.bmp | cjpeg -q 90 > ./signatures/testoutput.jpg")
header("Location: ./argen_fruit_bol.php?order_num=".$order_num);
?>
<HTML>
<HEAD>
</HEAD>
<BODY>
<!--<a href="<? echo "./argen_fruit_bol.php?order_num=".$order_num; ?>">Cleck Here For the BoL</a> !-->

</BODY>
</HTML>