<?
/*	Adu Stephen, August 15th, 2009.
*
*	A cron job to snapshot all previous data at the port, and dump it to 
*	Port Daily Inventory Table.
************************************************************************/

  //  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  
  $cursor = ora_open($conn);

  $sql = "insert into port_daily_inventory 
		(select sysdate, port_daily_inventory_seq.nextval, ct.pallet_id, nt.contract_id, 
				 nt.contract_desc, ct.arrival_num, cp.customer_id, cp.customer_name,
                 ct.commodity_code, c.commodity_name, ct.date_received, ct.cargo_description, 
                 substr(ct.warehouse_location, 1, 1), substr(ct.warehouse_location, 2,1), 
	             substr(ct.warehouse_location, 4), ct.warehouse_location, nt.ship_in_type, nt.ship_out_type, ct.qty_received, ct.qty_in_house, 
                 ct.qty_damaged, ct.shipping_line, ct.fumigation_code, ct.exporter_code,
	             ct.cargo_size, ct.weight, ct.weight_unit, ct.bol, ct.mark, ct.deck, ct.hatch, ct.remark, ct.container_id, ct.source_note,
	             ct.source_user, vp.vessel_name, vp.ship_prefix, v.berth_num, ceil(sysdate - ct.date_received) 
				 from cargo_tracking ct, cargo_activity ca, vessel_profile vp, customer_profile cp, commodity_profile c, voyage v, contract nt
           where ca.PALLET_ID(+) = ct.pallet_id 
           and ct.ARRIVAL_NUM = vp.arrival_num
           and ct.receiver_id = cp.customer_id
           and ct.commodity_code = c.commodity_code
           and ct.arrival_num = to_char(v.lr_num(+))
           and vp.ship_prefix =  nt.CONTRACT_CD(+))";

  ora_parse($cursor, $sql);
  ora_exec($cursor);

?>

