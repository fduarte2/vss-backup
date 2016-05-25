desc STORAGE_RATE
;

select * from storage_rate;

CREATE TABLE STORAGE_RATE_TEST as
(
SELECT *
FROM STORAGE_RATE
)
;

COMMIT;

SELECT * FROM STORAGE_RATE_TEST 
ORDER BY SERVICE_CODE, COMMODITY_CODE, START_DAY;

DESC STORAGE_RATE_TEST;

ALTER TABLE STORAGE_RATE ADD (
CUSTOMER_ID NUMBER(6) REFERENCES CUSTOMER_PROFILE(CUSTOMER_ID)
)
;

COMMIT;

/*

-- Create a onstraint -- 
ALTER TABLE STORAGE_RATE_TEST  
	  add CONSTRAINT UNQ_STORAGE_RATE_TEST UNIQUE (???, ...)  
;

COMMIT;

*/



select count(*), commodity_code
from storage_rate
group by commodity_code
;


select *
from storage_rate_test
where commodity_code=3328
order by lr_num, start_day



Check 1: 

    Is There At Least One Entry In The Storage_Rate Table For That Commodity, Vessel And Customer?  

       If The Answer Is Yes And Only One Entry Is Found: Then Use That Rate Value. Done.
	   
	   If The Answer Is Yes And Multiple Entries Are Found: Then Based On How Long Has The Commodity Been
	     In The Warehouse, Determine The Rate Value. Done. 
	   
	   If The Answer Is No Then Go To Check 2.

 
Check 2: 

    Is There At Least One Entry In The Storage_Rate Table For That Commodity And Customer?    

        If The Answer Is Yes And Only One Entry Is Found: Then Use That Rate Value. Done

       	If The Answer Is Yes And Multiple Entries Are Found: Then Based On How Long Has The Commodity Been
	      In The Warehouse, Determine The Rate Value. Done. 

		If The Answer Is No Then Use The Value For The Given Commodity.  Done. 

	


SELECT SERVICE_CODE,COMMODITY_CODE,UNIT, DURATION, DURATION_UNIT, START_DAY, END_DAY, LR_NUM, CUSTOMER_ID, RATE 
FROM STORAGE_RATE_TEST s
ORDER BY SERVICE_CODE, COMMODITY_CODE, UNIT
;



UPDATE STORAGE_RATE_TEST 
SET RATE=3.0 

SELECT LENGTH(LR_NUM)
FROM STORAGE_RATE_TEST
WHERE SERVICE_CODE=3111 
AND COMMODITY_CODE=1172 
AND UNIT='TON' 
AND DURATION=30 
AND DURATION_UNIT='DAY' 
AND START_DAY=1 
AND END_DAY=7300 
--AND LR_NUM=NULL 
--AND CUSTOMER_ID=NULL
;


select count(*)  
from cargo_activity ca
where ca.DATE_OF_ACTIVITY='30-may-2006'
;
 
 
select count(*)
from cargo_tracking ca
where ca.DATE_RECEIVED='31-may-2006'
;



create table billing_test as
(
select *
from billing
)
;

commit;


select count(*) from billing_test;


truncate table billing_test;

commit;

create table cargo_tracking_test as
(
select * 
from cargo_tracking
)
;

commit;

select count(*) from cargo_tracking_test;

drop table cargo_tracking_test;

drop table cargo_tracking_test_p_1;

drop table cargo_tracking_test_p_2;

commit;

create table cargo_tracking_test_p_1 as
(
SELECT * FROM CARGO_TRACKING 
WHERE QTY_RECEIVED > 0 
AND WHSE_RCPT_NUM > 0 
AND COMMODITY_CODE NOT IN (1272, 6172, 8101) 
AND STORAGE_END >= TO_DATE('5/30/2006', 'MM/DD/YYYY') 
AND STORAGE_END <= TO_DATE('5/31/2006', 'MM/DD/YYYY')
)
;

commit;

create table cargo_tracking_test as
(
SELECT * FROM CARGO_TRACKING 
WHERE QTY_RECEIVED > 0 
AND WHSE_RCPT_NUM > 0 
AND COMMODITY_CODE NOT IN (1272, 6172, 8101) 
AND STORAGE_END >= TO_DATE('5/30/2006', 'MM/DD/YYYY') 
AND STORAGE_END <= TO_DATE('5/31/2006', 'MM/DD/YYYY')
)
;

commit;

create table cargo_tracking_test_p_2 as
(
SELECT * 
FROM CARGO_TRACKING 
WHERE QTY_RECEIVED > 0 
AND WHSE_RCPT_NUM > 0 
AND FREE_TIME_END IS NOT NULL 
AND FREE_TIME_END <= TO_DATE('5/31/2006', 'MM/DD/YYYY') 
AND STORAGE_END IS NULL 
AND COMMODITY_CODE NOT IN (1272, 6172, 8101)
)
;

commit;


drop table cargo_tracking_test

create table cargo_tracking_test as
(
SELECT * 
FROM CARGO_TRACKING 
WHERE QTY_RECEIVED > 0 
AND WHSE_RCPT_NUM > 0 
AND FREE_TIME_END IS NOT NULL 
AND FREE_TIME_END <= TO_DATE('5/31/2006', 'MM/DD/YYYY') 
AND STORAGE_END IS NULL 
AND COMMODITY_CODE NOT IN (1272, 6172, 8101)
)
;

commit;



select count(*) from cargo_tracking_test_p_1;

select count(*) from cargo_tracking_test_p_2;

create table cargo_tracking_test as
(
select * from cargo_tracking_test_p_1
union
select * from cargo_tracking_test_p_2
)
;

commit;

select count(*) from cargo_tracking_test;


drop table cargo_tracking_test_01;
 
create table cargo_tracking_test_01 as
(
select * from cargo_tracking_test
)
;

commit;


drop table cargo_tracking_test
;

commit;

select * from cargo_tracking_test_01;


select * from billing_test
;


select * from storage_rate_test
where commodity_code=4260
;


select ct.QTY_RECEIVED
from cargo_tracking_test ct
where ct.LOT_NUM='-902440670488'
;

select sum(ca.QTY_CHANGE)
from cargo_activity ca
where ca.LOT_NUM='-902440670488'
;


select count(*)
from cargo_activity ca
where ca.LOT_NUM='-902440670966'
;


select ctt.LOT_NUM
from cargo_tracking_test_01 ctt
where ctt.COMMODITY_CODE=4260
; 

select *
from storage_rate_test
order by  service_code, commodity_code, start_day
;



select *
from service_category
where service_code in
(
select distinct service_code
from storage_rate_test
)
order by service_code
;


select *
from COMMODITY_PROFILE cp
where cp.COMMODITY_CODE in 
(
select distinct commodity_code
from storage_rate_test
)
order by commodity_code
;


create table invoicedate_test as
(
select *
from invoicedate
)
;

commit;


truncate table invoicedate_test;

select count(*) from invoicedate_test;

select count(*) from billing_test;

create table cargo_tracking_test_00 as
(
select * from cargo_tracking_test
)
;

commit;



select *
from storage_rate_test srt
where srt.CUSTOMER_ID=1032
and srt.LR_NUM  =9259
and srt.COMMODITY_CODE=5031
and srt.SERVICE_CODE=3111
;

--insert into storage_rate_test values (3111, 5031, 2.5, 'PLT', 15, 'DAY', 31, 60, 9259, 1032);

--commit;

SELECT * from STORAGE_RATE_TEST;

select count(*) from billing_test;

select coun
from cargo_tracking_test ctc, cargo_tracking_test_00 ct
where ctc.STORAGE_END <> ct.STORAGE_END
and ctc.LOT_NUM=ct.LOT_NUM
;

SELECT * FROM STORAGE_RATE_TEST SR 
WHERE SR.SERVICE_CODE =3111 
AND SR.COMMODITY_CODE=5031 
AND SR.START_DAY < 42 
AND SR.END_DAY > 42 
AND SR.LR_NUM=9259 
AND SR.CUSTOMER_ID=1032;


SELECT * FROM STORAGE_RATE_TEST SR WHERE SR.SERVICE_CODE =3111 AND SR.COMMODITY_CODE=5031 AND SR.START_DAY < 42 AND SR.END_DAY > 42 AND SR.LR_NUM=9259 AND SR.CUSTOMER_ID=1032;

SELECT * FROM STORAGE_RATE_TEST SR 
WHERE SR.SERVICE_CODE =3111 
AND SR.COMMODITY_CODE=5031 
AND SR.START_DAY IS NULL 
AND SR.END_DAY IS NULL 
AND SR.LR_NUM IS NULL 
AND SR.CUSTOMER_ID IS NULL;




SELECT * FROM STORAGE_RATE_TEST SR WHERE SR.SERVICE_CODE =3111 AND SR.COMMODITY_CODE=5031 AND SR.START_DAY < 42 AND SR.END_DAY > 42 AND SR.LR_NUM=9259 AND SR.CUSTOMER_ID=1032;

SELECT * FROM STORAGE_RATE_TEST SR WHERE SR.SERVICE_CODE =3111 AND SR.COMMODITY_CODE=5031 AND SR.START_DAY < 42 AND SR.END_DAY > 42 AND SR.LR_NUM IS NULL AND SR.CUSTOMER_ID IS NULL;



SELECT * FROM STORAGE_RATE_TEST SR WHERE SR.SERVICE_CODE =3121 AND SR.COMMODITY_CODE=4260 AND SR.START_DAY < 164 AND SR.END_DAY > 164 AND SR.LR_NUM=9116 AND SR.CUSTOMER_ID=1916;


SELECT * FROM STORAGE_RATE_TEST SR WHERE SR.SERVICE_CODE =3121 AND SR.COMMODITY_CODE=4260 AND SR.START_DAY < 164 AND SR.END_DAY > 164 AND SR.LR_NUM=9116 AND SR.CUSTOMER_ID IS NULL
;


UPDATE STORAGE_RATE_TEST SR
SET RATE=2.75 
WHERE SR.SERVICE_CODE =3121 
AND SR.COMMODITY_CODE=4260 
AND SR.START_DAY < 164 
AND SR.END_DAY > 164 
AND SR.LR_NUM IS NULL 
AND SR.CUSTOMER_ID IS NULL;

COMMIT;

SELECT * FROM STORAGE_RATE_TEST SR WHERE SR.SERVICE_CODE =3121 AND SR.COMMODITY_CODE=4260 AND SR.START_DAY < 164 AND SR.END_DAY > 164 AND SR.LR_NUM IS NULL AND SR.CUSTOMER_ID IS NULL;


SELECT * FROM STORAGE_RATE_TEST SR WHERE SR.SERVICE_CODE =3111 AND SR.COMMODITY_CODE=5031 AND SR.START_DAY < 42 AND SR.END_DAY > 42 AND SR.LR_NUM=9259 AND SR.CUSTOMER_ID=1032;


desc billing_test;


select count(*) 
from billing_test, cargo_tracking_test
where billing_test.LOT_NUM=cargo_tracking_test.LOT_NUM

select lot_num, free_time_end, storage_end 
from cargo_tracking_test
where cargo_tracking_test.LOT_NUM
in
(
select LOT_NUM
from billing_test
)
order by lot_num
;




select *
from cargo_tracking_test_00
where lot_num=-902440664164
;


select sum(ca.QTY_CHANGE)
from cargo_activity ca
where ca.LOT_NUM='-902440664164'
;

select *
from cargo_tracking ct
where ct.lot_num='-902440664164'
;


select *
from billing_test bt
where bt.LOT_NUM='-902440664164'
;


select distinct commodity_code
from billing_test
; 



SELECT count(*) 
FROM CARGO_TRACKING 
WHERE QTY_RECEIVED > 0 
AND WHSE_RCPT_NUM > 0 
AND FREE_TIME_END IS NOT NULL 
AND FREE_TIME_END <= TO_DATE('5/31/2006', 'MM/DD/YYYY') 
AND STORAGE_END IS NULL 
AND COMMODITY_CODE NOT IN (1272, 6172, 8101);


select ca.DATE_OF_ACTIVITY
from cargo_activity ca
where ca.LOT_NUM='-902440664057';

select sum(ca.QTY_CHANGE)
from cargo_activity ca
where ca.LOT_NUM='-902440664057';

select ct.QTY_RECEIVED 
from cargo_tracking ct
where ct.LOT_NUM='-902440664057'



drop table invoicedate_test;

create table invoicedate_test as
(
select *
from invoicedate
)
;

commit;

select count(*)
from billing_test
;



select * from storage_rate_test;



select count(*)
from cargo_tracking_test
;

select count(*)
from cargo_tracking_test t
where t.COMMODITY_CODE=4260
;

/* update rate for 4260 from 0 to 2.85 to generate bill */
update storage_rate_test s
set s.RATE=2.85
where s.COMMODITY_CODE=4260
;

commit;



select a.LOT_NUM, a.STORAGE_END  
from cargo_tracking_test a;

drop table cargo_total_qty_change;

commit;

create table cargo_total_qty_change as
(
select a.LOT_NUM,sum(a.QTY_CHANGE) Total_QTY
from cargo_activity a
where a.LOT_NUM 
in
(
select b.LOT_NUM
from cargo_tracking_test b
)
group by a.LOT_NUM
)
;

commit;

select count(*) from cargo_total_qty_change;


select b.LOT_NUM, a.COMMODITY_CODE, a.QTY_RECEIVED, b.TOTAL_QTY
from cargo_tracking_test a, cargo_total_qty_change b
where a.LOT_NUM=b.LOT_NUM
and a.QTY_RECEIVED <> b.TOTAL_QTY
and a.COMMODITY_CODE not in (4261, 1299)
order by b.LOT_NUM
; 



select commodity_code ,rate
from storage_rate_test a
where a.COMMODITY_CODE in
(
select distinct a.COMMODITY_CODE
from cargo_tracking_test a, cargo_total_qty_change b
where a.LOT_NUM=b.LOT_NUM
and a.QTY_RECEIVED <> b.TOTAL_QTY
)
; 


select count(*)
from billing_test;


select count(*)
from cargo_tracking_test;



select count(*)
from billing_test bt
where bt.LOT_NUM
in
(
select b.LOT_NUM
from cargo_tracking_test a, cargo_total_qty_change b
where a.LOT_NUM=b.LOT_NUM
and a.QTY_RECEIVED <> b.TOTAL_QTY

)
;


select count(*)
from billing_test a, cargo_total_qty_change b
where a.LOT_NUM=b.LOT_NUM
;



select count(distinct bt.LOT_NUM) 
from billing_test bt
;




select *
from billing_test bt
;

select bt.LOT_NUM
from billing_test bt
minus


select count(*)
from cargo_activity ca
where ca.LOT_NUM in
(
select LOT_NUM 
from billing_test
where LOT_NUM
in
(
select b.LOT_NUM
from cargo_tracking_test a, cargo_total_qty_change b
where a.LOT_NUM=b.LOT_NUM
and a.QTY_RECEIVED <> b.TOTAL_QTY
and a.COMMODITY_CODE not in (4261, 1299)
)
)
;


select count(*) 
from billing_test
where billing_test.LOT_NUM
in
(
select a.LOT_NUM 
from cargo_tracking_test a
minus
select b.LOT_NUM 
from cargo_total_qty_change b
)
;


select count(*)
from cargo_activity a
where a.LOT_NUM='-902440667042'





select a.LOT_NUM 
from cargo_tracking_test a
minus
select b.LOT_NUM
from billing_test b



select count(*)
from cargo_tracking_test;






select a.LOT_NUM, sum(a.QTY_CHANGE )
from cargo_activity a
where a.LOT_NUM in
(
select bt.LOT_NUM
from billing_test bt
)
group by a.LOT_NUM
order by a.LOT_NUM
;



select cargo_tracking_test.LOT_NUM, cargo_tracking_test.QTY_RECEIVED
from cargo_tracking_test
where cargo_tracking_test.LOT_NUM in
(
select bt.LOT_NUM
from billing_test bt
)
order by cargo_tracking_test.LOT_NUM
;




select count(*)
from cargo_activity ca
where ca.LOT_NUM in
(
select ct.LOT_NUM 
from cargo_tracking_test ct
)
group by ca.LOT_NUM
;


select count(*)
from cargo_total_qty_change
;


select bt.LOT_NUM 
from billing_test bt
order by bt.LOT_NUM
;



select count(*)
from billing_test a , cargo_total_qty_change b
where a.LOT_NUM=b.LOT_NUM
;



select count(*)
from cargo_tracking_test;



select ca.LOT_NUM 
from cargo_activity ca
where ca.LOT_NUM in
(
select distinct ctt.LOT_NUM
from cargo_tracking_test ctt
)
group by ca.LOT_NUM
;




select *
from storage_rate_test
where commodity_code in
(
select distinct commodity_code
from cargo_tracking_test
)
;



select count(*) from cargo_tracking_test;

select count(*) from billing_test;

select count(*) from invoicedate_test;

select * from storage_rate_test;



select ca.LOT_NUM, sum(ca.QTY_CHANGE) 
from cargo_activity ca
where ca.LOT_NUM in
(
select bt.LOT_NUM
from billing_test bt
)
group by ca.LOT_NUM
order by ca.LOT_NUM
;


select a.LOT_NUM, a.QTY_RECEIVED 
from cargo_tracking_test a
where a.LOT_NUM
in
(
select bt.LOT_NUM
from billing_test bt
) 
;

truncate table billing_test;

truncate table invoicedate_test;

commit;



-- Phase 1 --
select ca.LOT_NUM, ca.DATE_OF_ACTIVITY, sum(ca.QTY_CHANGE) 
from cargo_activity ca
where ca.LOT_NUM='-902440670488'
and ca.DATE_OF_ACTIVITY <= '30-may-2006'
group by ca.LOT_NUM, ca.DATE_OF_ACTIVITY
order by ca.LOT_NUM, ca.DATE_OF_ACTIVITY
;


-- Phase 2 --
select a.LOT_NUM, a.QTY_RECEIVED 
from cargo_tracking a
where a.LOT_NUM=''


select b.COMMODITY_CODE,  b.RATE 
from storage_rate_test b
where b.COMMODITY_CODE
in
(
select distinct a.COMMODITY_CODE 
from cargo_tracking_test a
)
;

select count(*) from cargo_tracking_test;

select * from billing_test;

select 


select * from Storage_Rate_test a
where a.SERVICE_CODE=3111
and a.COMMODITY_CODE=5031
;


update storage_rate_test a
set a.START_DAY=1, a.END_DAY=7300
where a.SERVICE_CODE=3111
and a.COMMODITY_CODE=5031
;

commit;


create table voyage_0608 as
(
select *
from voyage
)
;

commit;


select *
from invoicedate
order
;

select count(*)
from billing
;

select count(*)
from billing_0608
;


select


select * 
from storage_rate
where storage_rate.COMMODITY_CODE in
(
SELECT DISTINCT CARGO_TRACKING.COMMODITY_CODE 
FROM CARGO_TRACKING WHERE QTY_RECEIVED > 0 
AND WHSE_RCPT_NUM > 0 
AND COMMODITY_CODE NOT IN (1272, 6172, 8101)
AND STORAGE_END >= TO_DATE('05/08/2006', 'MM/DD/YYYY')
AND STORAGE_END <= TO_DATE('05/14/2006', 'MM/DD/YYYY')
)
;


select cargo_tracking.WHSE_RCPT_NUM 
from cargo_tracking
where cargo_tracking.LOT_NUM
in
(
select a.CONTAINER_NUM
from cargo_manifest a
where a.LR_NUM=9284
)
;

select a.DATE_DEPARTED
from voyage a
where a.LR_NUM=9305



SELECT CARGO_TRACKING.LOT_NUM 
FROM CARGO_TRACKING WHERE QTY_RECEIVED > 0 
AND WHSE_RCPT_NUM > 0 
AND COMMODITY_CODE NOT IN (1272, 6172, 8101)
AND STORAGE_END >= TO_DATE('05/08/2006', 'MM/DD/YYYY')
AND STORAGE_END <= TO_DATE('05/14/2006', 'MM/DD/YYYY')


select max(cargo_activity.QTY_CHANGE)
from cargo_activity
where cargo_activity.LOT_NUM='-902440665220'
and cargo_activity.DATE_OF_ACTIVITY >= '8-may-2006' 
and cargo_activity.DATE_OF_ACTIVITY <= '14-may-2006'



SELECT * 
FROM CARGO_TRACKING 
WHERE QTY_RECEIVED > 0 
AND WHSE_RCPT_NUM > 0 
AND COMMODITY_CODE NOT IN (1272, 6172, 8101) 
AND STORAGE_END >= TO_DATE('5/8/2006', 'MM/DD/YYYY') 
AND STORAGE_END <= TO_DATE('5/14/2006', 'MM/DD/YYYY') ORDER BY STORAGE_END

SELECT * 
FROM CARGO_TRACKING 
WHERE QTY_RECEIVED > 0 
AND WHSE_RCPT_NUM > 0 
AND FREE_TIME_END IS NOT NULL 
AND FREE_TIME_END <= TO_DATE('5/14/2006', 'MM/DD/YYYY') 
AND STORAGE_END IS NULL 
AND COMMODITY_CODE NOT IN (1272, 6172, 8101)
;


select * from invoice
;

select *
from cargo_manifest a
where a.LR_NUM=9284
and a.CARGO_BOL='9284-22'
;

select *
from storage_rate
order by service_code, commodity_code;


DROP TABLE STORAGE_RATE_CHANGE_LOG;

-- cratae Storage_Rate_Change_Log --
create table Storage_Rate_Change_Log (
RATE_CHANGE_ID number PRIMARY KEY,
CHANGED_BY varchar2(30) NOT NULL,  
SQL_STMT varchar2(4000) NOT NULL,
USER_NAME varchar2(20) NOT NULL,
DATE_CHANGED date NOT NULL
)
;


COMMIT;

-- Create a sequence to generate Rate_Change_ID --
CREATE sequence RateChangeID increment by 1 start with 1;

COMMIT;


-- cratae FREE_TIME_CHANGE_LOG --
DROP TABLE FREE_TIME_CHANGE_LOG;

CREATE TABLE FREE_TIME_CHANGE_LOG (
FREE_TIME_CHANGE_ID number PRIMARY KEY,
CHANGED_BY varchar2(30) NOT NULL,  
SQL_STMT varchar2(4000) NOT NULL,
USER_NAME varchar2(20) NOT NULL,
DATE_CHANGED date NOT NULL
)
;


COMMIT;

-- Create a sequence to generate FREE_TIME_CHANAGE_ID --
CREATE SEQUENCE FREE_TIME_CHANGE_ID increment by 1 start with 1;

COMMIT;



insert into Storage_Rate_Change_Log values (RateChangeID.NextVal, 'BNI SET STORAGE RATE', 'update storage_rate', 'paulwu', '09-jun-2006');

commit;

select * from STORAGE_RATE_CHANGE_LOG;
 

insert into Storage_Rate_Change_Log values (RateChangeID.NextVal, 'BNI SET STORAGE RATE','DELETE FROM STORAGE_RATE WHERE SERVICE_CODE=3216 AND COMMODITY_CODE=5033 AND UNIT=''PLT'' AND DURATION=15 AND DURATION_UNIT=''DAY'' AND START_DAY=7305 AND END_DAY=7350 AND LR_NUM IS NULL  AND CUSTOMER_ID IS NULL  AND RATE=6.85','paulwu',TO_DATE('6/9/2006 2:25:30 PM', 'MM/DD/YYYY HH:MI:SS AM'));

commit;



insert into Storage_Rate_Change_Log values (RateChangeID.NextVal, 
'BNI SET STORAGE RATE',
'DELETE FROM STORAGE_RATE WHERE SERVICE_CODE=3216 AND COMMODITY_CODE=5033 AND UNIT=''PLT'' AND DURATION=15 AND DURATION_UNIT=''DAY'' AND START_DAY IS NULL  AND END_DAY IS NULL  AND LR_NUM IS NULL  AND CUSTOMER_ID IS NULL  AND RATE=4.5',
'paulwu',TO_DATE(6/9/2006 2:53:21 PM,'MM/DD/YYYY HH:MI:SS AM')
)



delete from storage_rate_change_log A
where a.RATE_CHANGE_ID=3

commit;

SELECT * FROM STORAGE_RATE WHERE SERVICE_CODE=3111 AND COMMODITY_CODE=1172 AND UNIT='TON' AND DURATION=25 AND DURATION_UNIT='DAY' AND START_DAY=1 AND END_DAY=7325 AND LR_NUM IS NULL  AND CUSTOMER_ID IS NULL  AND RATE=2.5;


create table cargo_tracking_0612 as
(
select * from cargo_tracking
)
;

commit;

create table billing_0612 as
(
select * from billing
)
;

commit;

create table invoicedate_0612 as
(
select * from invoicedate
)
;

commit;

create table storage_rate_0612 as
(
select * from storage_rate
)
;

commit;

select * from Storage_rate_change_log; 

select * from free_time_change_log;


select * from free_time_0619
where AUTO_RUN='N'
order by commodity_code;

select f.COMMODITY_CODE, f.LR_NUM VESSEL_NUM, f.CUSTOMER_ID, f.FREE_DAYS
from free_time f
order by f.COMMODITY_CODE;



drop table free_time;

commit;

create table free_time_0620 as
(
select * from free_time
)
;

commit;

select * from free_time;

alter table free_time modify
(
AUTO_RUN default 'N'
)
;

commit;

update free_time a
set a.AUTO_RUN='N'
where a.COMMODITY_CODE=9298
;

commit;

select *
from user_tablespaces
;

select	OWNER,
	SEGMENT_NAME,
	SEGMENT_TYPE,
	TABLESPACE_NAME,
	BYTES
from 	dba_segments
where	TABLESPACE_NAME = 'SYSTEM'
and	OWNER not in ('SYS','SYSTEM')
order 	by OWNER, SEGMENT_NAME
;