Attribute VB_Name = "modGlobal"
Option Explicit


Global OraSession As Object
Global OraDatabase As Object
Global dsBILLING As Object
Global dsSTORAGE_RATE As Object
Global dsCARGO_TRACKING As Object
Global dsCARGO_ORIGINAL_MANIFEST As Object
Global dsCARGO_ACTIVITY_SUM As Object
Global dsCARGO_MANIFEST As Object
Global dsUNITS As Object
Global dsUNIT_CONVERSION As Object
Global dsBILLING_MAX As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsSERVICE_CATEGORY As Object
Global dsVESSEL_PROFILE As Object
Global dsLOCATION_CATEGORY As Object
Global dsAssetProfile As Object

Global gsPVItem As String
Global gsSqlStmt As String

'Billing number
Global glBillingNum As Long


Sub findSRRecordset(cus As String, lr As String, com As String, sc As String, dt As Integer)

On Error GoTo errHandler:
    
    '' The dwell time will be
    '' at least 2 (for 1st phase: ON_GOING STORAGE) or
    '' -1         (for 2nd phase: PUT INTO STORAGE)
    
    '' For 1st phase: On-going storage
    If (dt > 0) Then
    
        '' Check 1
        '' Based on STORAGE_CUS_ID, LR_NUM, COMMODITY_CODE, SERVICE_CODE and Dwell Time
        gsSqlStmt = "SELECT *" & _
                " FROM storage_rate SR" & _
                " WHERE SR.SERVICE_CODE =" & sc & _
                " AND SR.COMMODITY_CODE=" & com & _
                " AND SR.START_DAY <= " & dt & _
                " AND SR.END_DAY >= " & dt & _
                " AND SR.LR_NUM=" & lr & _
                " AND SR.CUSTOMER_ID=" & cus
                
        Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If dsSTORAGE_RATE.RecordCount = 1 Then Exit Sub
        
        '' Check 2
        '' Based on STORAGE_CUS_ID, COMMODITY_CODE, SERVICE_CODE and Dwell Time
        gsSqlStmt = "SELECT *" & _
                " FROM storage_rate SR" & _
                " WHERE SR.SERVICE_CODE =" & sc & _
                " AND SR.COMMODITY_CODE=" & com & _
                " AND SR.START_DAY <= " & dt & _
                " AND SR.END_DAY >= " & dt & _
                " AND SR.LR_NUM IS NULL" & _
                " AND SR.CUSTOMER_ID=" & cus
                
        Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If dsSTORAGE_RATE.RecordCount = 1 Then Exit Sub
        
        '' Check 3
        '' Based on COMMODITY_CODE, SERVICE_CODE and Dwell Time
        gsSqlStmt = "SELECT *" & _
                " FROM STORAGE_RATE SR" & _
                " WHERE SR.SERVICE_CODE =" & sc & _
                " AND SR.COMMODITY_CODE=" & com & _
                " AND SR.START_DAY <= " & dt & _
                " AND SR.END_DAY >= " & dt & _
                " AND SR.LR_NUM IS NULL" & _
                " AND SR.CUSTOMER_ID IS NULL"

        Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If dsSTORAGE_RATE.RecordCount = 1 Then Exit Sub
        
'        '' Check 4
'        '' Based on COMMODITY_CODE and SERVICE_CODE
'        gsSqlStmt = "SELECT *" & _
'                " FROM STORAGE_RATE SR" & _
'                " WHERE SR.SERVICE_CODE =" & sc & _
'                " AND SR.COMMODITY_CODE=" & com & _
'                " AND SR.START_DAY IS NULL" & _
'                " AND SR.END_DAY IS NULL" & _
'                " AND SR.LR_NUM IS NULL" & _
'                " AND SR.CUSTOMER_ID IS NULL"
'        Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'        If dsSTORAGE_RATE.RecordCount = 1 Then Exit Sub
        
        
        '' Raise Error if process gets to this point
        Err.Raise 9999, , "Storage rate not found for SERVICE CODE:" & sc & _
                            " COMMODITY CODE:" & com & _
                            " VESSEL NUMBER:" & lr & _
                            " CUSTOMER NUMBER:" & cus & _
                            " STORAGE PERIOD:" & dt
                            
    

    Else
    
        '' For 2nd phase: PUT INTO STORAGE
        
        '' Check 1
        '' Based on STORAGE_CUS_ID, LR_NUM, COMMODITY_CODE, SERVICE_CODE and START_DAY=1
        gsSqlStmt = "SELECT *" & _
                " FROM STORAGE_RATE SR" & _
                " WHERE SR.SERVICE_CODE =" & sc & _
                " AND SR.COMMODITY_CODE=" & com & _
                " AND SR.START_DAY=1" & _
                " AND SR.LR_NUM=" & lr & _
                " AND SR.CUSTOMER_ID=" & cus
                
        Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If dsSTORAGE_RATE.RecordCount = 1 Then Exit Sub
        
        '' Check 2
        '' Based on STORAGE_CUS_ID, LR_NUM, COMMODITY_CODE, SERVICE_CODE and START_DAY=1
        gsSqlStmt = "SELECT *" & _
                " FROM STORAGE_RATE SR" & _
                " WHERE SR.SERVICE_CODE =" & sc & _
                " AND SR.COMMODITY_CODE=" & com & _
                " AND SR.START_DAY = 1" & _
                " AND SR.LR_NUM IS NULL" & _
                " AND SR.CUSTOMER_ID=" & cus
                
        Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If dsSTORAGE_RATE.RecordCount = 1 Then Exit Sub
        
        
        '' Check 3
        '' Based on COMMODITY_CODE, SERVICE_CODE and START_DAY=1
        gsSqlStmt = "SELECT *" & _
                " FROM STORAGE_RATE SR" & _
                " WHERE SR.SERVICE_CODE =" & sc & _
                " AND SR.COMMODITY_CODE=" & com & _
                " AND SR.START_DAY = 1" & _
                " AND SR.LR_NUM IS NULL" & _
                " AND SR.CUSTOMER_ID IS NULL"
                
        Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If dsSTORAGE_RATE.RecordCount = 1 Then Exit Sub
        
'        '' Check 4
'        '' Based on COMMODITY_CODE, SERVICE_CODE
'        gsSqlStmt = "SELECT *" & _
'                " FROM STORAGE_RATE SR" & _
'                " WHERE SR.SERVICE_CODE =" & sc & _
'                " AND SR.COMMODITY_CODE=" & com & _
'                " AND SR.START_DAY IS NULL" & _
'                " AND SR.END_DAY IS NULL" & _
'                " AND SR.LR_NUM IS NULL" & _
'                " AND SR.CUSTOMER_ID IS NULL"
'        Set dsSTORAGE_RATE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'        If dsSTORAGE_RATE.RecordCount = 1 Then Exit Sub
                
        
        '' Raise Error if process gets to this point.
        Err.Raise 9999, , "Storage rate not found for SERVICE CODE:" & sc & _
                            " COMMODITY CODE:" & com & _
                            " VESSEL NUMBER:" & lr & _
                            " CUSTOMER NUMBER:" & cus

    
    End If

errHandler:
  
    
    If Err.Number <> 0 Then
        MsgBox "An error occurred in " & App.Title & "." & _
        "findSRRecordset." & Chr(13) & "Error Description:" & Err.Description
        OraSession.RollBack
        End
        
    End If
    
    
End Sub
