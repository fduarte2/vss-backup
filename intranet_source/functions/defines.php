<?
 // Defines for our database

 // CCD_USER
 define(USERNAME, 0);
 define(PASSWORD, 1);
 define(EMAIL, 2);
 define(TYPE, 3);
 define(ACTIVE, 4);
 define(ACCESS, 6);

 // CCD_RECEIVING
 define(LR_NUM, 0);
 define(LOT_ID, 1);
 define(LOT, 1);
 define(MARK, 2);
 define(PO_NUM, 3);
 define(VESSEL, 4);
 define(VOYAGE, 5);
 define(CCD_LOT_ID, 6);
 define(TRACKING_NUM, 6);
 define(CUSTOMER_ID, 7);
 define(CUST, 7);
 define(LOCATION, 8);
 define(CASES_EXPECTED, 9);
 define(CASES_RECEIVED, 10);
 define(CASES_REJECTED, 11);
 define(CASES_IN_STORAGE, 12);
 define(PALLETS_EXPECTED, 13);
 define(PALLETS_RECEIVED, 14);
 define(PALLETS_EXPECTED, 15);
 define(PALLETS_IN_STORAGE, 16);
 define(GROSS_WEIGHT, 17);
 define(GW, 17);
 define(NET_WEIGHT, 18);
 define(NW, 18);
 define(CUT, 19);
 define(PRODUCT, 20);
 define(BRAND, 21);
 define(CONTAINER, 22);
 define(ESTABLISHMENT, 23);
 define(ESTAB, 23); 
 define(ORIGIN, 24);
 define(USDA, 25);
 define(X410, 26);
 define(INSPECTION_RESULT, 27);
 define(INS_RES, 27);
 define(COMMENTS, 28);
 define(COMMENT, 28);
 define(BLOCK, 29);
 define(REJECTION_CODE, 30);
 define(SHIP_TYPE, 31);
 define(MANIFEST_DATE, 32);
 define(RECEIVING_DATE, 33);
 define(PRODUCTION_DATE, 34);
 define(INSPECTION_DATE, 35);
 define(INS_DATE, 35);
 define(FREE_TIME_END, 36);
 define(FREE_DATE, 36);
 define(RELEASE_DATE, 37);
 define(READY_TO_PRINT, 38);
 define(PRINTED, 39);
 define(VERIFIED, 40);
 define(VERIFY, 40);
 define(INSPECTED, 41);
 define(VPRINTED, 42);
 define(IN_EXPEDITING, 43);
 define(STANDPACK, 44);
 define(USDA_LOT, 45);
 define(HEALTH, 46);
 define(SHIPPER, 47);
 define(REC_LAST, 48);

 // CCD_CUSTOMER
 define(CCD_CUSTOMER_ID, 0);
 define(CUSTOMER_NAME, 1);
 define(LONG_NAME, 2); 
 define(CONTACT, 3);
 define(ADDRESS, 4);
 define(CITY, 5);
 define(STATE, 6);
 define(ZIP, 7);
 define(COUNTRY, 8);
 define(FAX_NO, 9);
 define(PHONE_NO, 10);
 define(EMAIL_ADDRESS, 11);
 define(ACTIVE_DATE, 12);
 define(INACTIVE_DATE, 13);
 define(ORACLE_ID, 14);
 define(ADDRESS2, 15);
 define(ADDRESS3, 16);
 define(EMAIL2, 17);
 define(AUTO_EMAIL, 18);

 // RATE_MATRIX
 define(CS_LOADING, 1);
 define(CS_LOADING_BILL2, 2);
 define(CS_PARTIAL, 3);
 define(CS_PARTIAL_BILL2, 4);
 define(CS_INSPECTION, 5);
 define(CS_INSPECTION_BILL2, 6);
 define(CS_SHRINK, 7);
 define(CS_SHRINK_BILL2, 8);
 define(CS_SHRINK_EXEMPT, 9);
 define(CS_STORAGE, 10);
 define(CS_STORAGE_BILL2, 11);
 define(CS_DRILL, 12);
 define(CS_DRILL_BILL2, 13);
 define(CS_RESTACK, 14);
 define(CS_RESTACK_BILL2, 15);
 define(CS_DP, 16);
 define(CS_DP_BRILL2, 17);
 define(CS_TIS, 18);
 define(CS_TIS_BILL2, 19);
 define(CS_MANIP, 20);
 define(CS_MANIP_BILL2, 21);
 define(CS_NON_LOADOUT, 22);
 define(CS_NON_LOADOUT_BILL2, 23);
 define(CS_NON_VESSELS, 24);
 define(CS_NON_LOADOUT_BILL2_AFTER_VESSELS, 25);
 define(KY_LOADING, 26);
 define(KY_LOADING_BILL2, 27);
 define(KY_PARTIAL, 28);
 define(KY_PARTIAL_BILL2, 29);
 define(KY_INSPECTION, 30);
 define(KY_INSPECTION_BILL2, 31);
 define(KY_SHRINK, 32);
 define(KY_SHRINK_BILL2, 33);
 define(KY_SHRINK_EXEMPT, 34);
 define(KY_STORAGE, 35);
 define(KY_STORAGE_BILL2, 36);
 define(KY_DRILL, 37);
 define(KY_DRILL_BILL2, 38);
 define(KY_RESTACK, 39);
 define(KY_RESTACK_BILL2, 40);
 define(KY_DP, 41);
 define(KY_DP_BRILL2, 42);
 define(KY_TIS, 43);
 define(KY_TIS_BILL2, 44);
 define(KY_MANIP, 45);
 define(KY_MANIP_BILL2, 46);
 define(KY_NON_LOADOUT, 47);
 define(KY_NON_LOADOUT_BILL2, 48);
 define(KY_NON_VESSELS, 49);
 define(KY_NON_LOADOUT_BILL2_AFTER_VESS, 50);
 define(CS_NON_STORAGE, 51);
 define(KY_NON_STORAGE, 52);
 define(KY_NON_STORAGE_AFTER30, 53);
 define(KY_JUICE_STORAGE, 54);
 define(KY_PARTIAL_INSPECTION, 55);
 define(CS_PARTIAL_INSPECTION, 56);
 define(KY_SB_LOADOUT, 57);
 define(KY_KIWI_LOADOUT, 58);
 define(KY_APPLE_LOADOUT, 59);

 // CCD_ACTIVITY
 define(ACT_TRACKING_NUM, 0);
 define(ACT_ORDER_NUM, 1);
 define(ACT_TRANS_DATE, 2);
 define(ACT_TRANSACTION, 3);
 define(ACT_TRANS_STATUS, 4);
 define(ACT_EXEC_DATE, 5);
 define(ACT_STRG_DATE, 6);
 define(ACT_FROM_CUST, 7);
 define(ACT_TO_CUST, 8);
 define(ACT_CASES, 9);
 define(ACT_PALLETS, 10);
 define(ACT_GW, 11);
 define(ACT_NW, 12);
 define(ACT_ORDER_PO, 13);
 define(ACT_CARRIER, 14);
 define(ACT_LOACTION, 15);
 define(ACT_COMMENTS, 16);
 define(ACT_SEAL, 17);
 define(ACT_BOL_NOTES, 18);
 define(ACT_SHIP_INS, 19);
 define(ACT_TEMP, 20);
 define(ACT_RECEIVER, 21);
 define(ACT_REC_ADDR, 22);
 define(ACT_REC_CITY, 23);
 define(ACT_REC_COUNTRY, 24);
 define(READY_TO_BILL, 25);
 define(ACT_BILLED, 26);
 define(BILLING_NUM, 27);
 define(ORIG_PICKUP_DATE, 28);
 define(ACT_LAST, 29);

 // INVENTORY_HISTORY
 define(CHANGE_DATE, 0);
 define(CHANGE_ID, 1);
 define(HISTORY_CCD_LOT_ID, 2);
 define(HISTORY_CUSTOMER_ID, 3);
 define(HISTORY_ORDER_NUM, 4);
 define(HISTORY_CASES, 5);
 define(HISTORY_PALLETS, 6);
 define(HISTORY_GW, 7);
 define(HISTORY_NW, 8);
 define(HISTORY_LAST, 12);
?>