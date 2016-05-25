<?
   include("connect.php");


   $conn_bni = ora_logon("SAG_OWNER@$bni", "SAG");
   if($conn_bni < 1){
        printf("Error logging on to the BNI Oracle Server: ");
        printf(ora_errorcode($conn_bni));
        printf("Please try later!");
        exit;
   }
   $cursor_bni = ora_open($conn_bni);

   $table = "tonnage_detail";

   $eDate = date('m/d/Y');
//   $sDate = date('m/d/Y',mktime(0,0,0,date("m"),date("d") - 30 ,date("Y")));
   $sDate = "07/01/2004";
   //clearing database
   $sql = "delete from $table
           where date_time >=to_date('$sDate','mm/dd/yyyy') and date_time <=to_date('$eDate','mm/dd/yyyy')";
   $statement = ora_parse($cursor_bni, $sql);
   ora_exec($cursor_bni);
echo $sql."\r\n";

   $i = 0;

   $vTime = strtotime($sDate);
   $eTime = strtotime($eDate);

   while ($vTime < $eTime){
        $vDate = date('m/d/Y',mktime(0,0,0,date("m",strtotime($sDate)),date("d",strtotime($sDate)) + $i ,date("Y",strtotime($sDate))));
        $i++;
        $vTime = strtotime($vDate);
echo $vDate."\r\n";

        $sql = "insert into $table (date_time, commodity, lr, weight, weight_unit)
                select to_date('$vDate','mm/dd/yyyy'), commodity_code, lr_num,
                decode(upper(cargo_weight_unit),'LB',sum(cargo_weight)/2000,'LBS',sum(cargo_weight)/2000,
                'KG', sum(cargo_weight)/0.454/2000, 'MBF', sum(cargo_weight)*3/2,sum(cargo_weight)),
                decode(upper(cargo_weight_unit),'LB','TONS','LBS','TONS','KG','TONS','MBF','TONS',upper(cargo_weight_unit))
                from cargo_manifest m
                where lr_num > 1000  and lr_num in
                (select lr_num from voyage where to_char(date_departed, 'mm/dd/yyyy') =  '$vDate')
                group by commodity_code, lr_num, upper(cargo_weight_unit)";

        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        $sql = "insert into $table (date_time, commodity, lr, weight, weight_unit)
                select to_date('$vDate','mm/dd/yyyy'), m.commodity_code, lr_num,
                decode(upper(cargo_weight_unit), 'LB',sum(cargo_weight)/2000,'LBS',sum(cargo_weight)/2000,
                'KG', sum(cargo_weight)/0.454/2000, 'MBF', sum(cargo_weight)*3/2, sum(cargo_weight)),
                decode(upper(cargo_weight_unit),'LB','TONS','LBS','TONS','KG','TONS','MBF', 'TONS',upper(cargo_weight_unit))
                from cargo_tracking t, cargo_manifest m
                where lr_num < 10 and
                t.lot_num = m.container_num and to_char(date_received, 'mm/dd/yyyy') ='$vDate' and
                (m.commodity_code <>'1272' and m.commodity_code <>'1299')
                group by m.commodity_code, lr_num, upper(m.cargo_weight_unit)";

        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        $sql = "update $table set itg =
                (select itg from commodity_itg where commodity_code = to_char(commodity) and itg <>'PacSea')
                where date_time = to_date('$vDate','mm/dd/yyyy')";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        $sql = "update $table set commodity_name =
                (select commodity_name from commodity_profile where commodity_code = commodity)
                where date_time = to_date('$vDate','mm/dd/yyyy')";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        $sql = "update $table set commodity_name = commodity
                where commodity_name is null and date_time = to_date('$vDate','mm/dd/yyyy')";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        $sql = "update $table set lr_name =
                (select vessel_name from vessel_profile where lr_num = lr)
                where date_time = to_date('$vDate','mm/dd/yyyy')";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

        $sql = "update $table set lr_name = 'N/A'
                where (lr is null or lr in (0, -1, -2, -3)) and date_time = to_date('$vDate','mm/dd/yyyy')";
        $statement = ora_parse($cursor_bni, $sql);
        ora_exec($cursor_bni);

}
?>
