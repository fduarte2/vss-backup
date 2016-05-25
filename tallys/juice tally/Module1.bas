Attribute VB_Name = "Module1"
Option Explicit

Global RFOraSession As Object
Global RFOraDatabase As Object
Global BNIOraSession As Object
Global BNIOraDatabase As Object
Global dsCARGO_ACTIVITY As Object
Global dsSHORT_TERM_DATA As Object
Global dsFUNCTION_RECORDSET As Object
Global strSql As String




Sub Initialize()

'Set BNIOraSession = CreateObject("OracleInProcServer.XOraSession")
'Set BNIOraDatabase = BNIOraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
'If BNIOraDatabase.LastServerErr <> 0 Then
'    MsgBox "BNI Database connection could not be made.  Please Contact TS."
'End If

Set RFOraSession = CreateObject("OracleInProcServer.XOraSession")
Set RFOraDatabase = RFOraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
'Set RFOraDatabase = RFOraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)
If RFOraDatabase.LastServerErr <> 0 Then
    MsgBox "RF Database connection could not be made.  Please Contact TS."
End If


End Sub


Function get_vessel(sLRNum As String) As String

    strSql = "SELECT LR_NUM || '-' || VESSEL_NAME THE_VESSEL FROM VESSEL_PROFILE WHERE LR_NUM = '" & sLRNum & "'"
    Set dsFUNCTION_RECORDSET = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    get_vessel = dsFUNCTION_RECORDSET.Fields("THE_VESSEL").Value

End Function

Function get_customer(sCustNum As String) As String

    strSql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & sCustNum & "'"
    Set dsFUNCTION_RECORDSET = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    get_customer = dsFUNCTION_RECORDSET.Fields("CUSTOMER_NAME").Value

End Function

Function get_commodity(sCommNum As String) As String

    strSql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '" & sCommNum & "'"
    Set dsFUNCTION_RECORDSET = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    get_commodity = dsFUNCTION_RECORDSET.Fields("COMMODITY_NAME").Value

End Function

Function get_checker_name(Barcode As String, cust As String, LR As String, act_num As String) As String
    Dim ActDate As String
    Dim empno As String
    
    strSql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID " _
            & "FROM CARGO_ACTIVITY " _
            & "WHERE PALLET_ID = '" & Barcode & "' " _
            & "AND ARRIVAL_NUM = '" & LR & "' " _
            & "AND CUSTOMER_ID = '" & cust & "' " _
            & "AND ACTIVITY_NUM = '" & act_num & "'"
    Set dsFUNCTION_RECORDSET = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    ActDate = dsFUNCTION_RECORDSET.Fields("THE_DATE").Value
    empno = dsFUNCTION_RECORDSET.Fields("ACTIVITY_ID").Value
    
    If IsNull(empno) Or empno = "" Then
        get_checker_name = "UNKNOWN"
        Exit Function
    End If
    
    strSql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE WHERE CHANGE_DATE >= TO_DATE('" & ActDate & "', 'MM/DD/YYYY')"
    Set dsFUNCTION_RECORDSET = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsFUNCTION_RECORDSET.Fields("THE_COUNT").Value >= 1 Then
        strSql = "SELECT LOGIN_ID THE_EMP FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '" & empno & "'"
    Else
'        get_checker_name = empno
'        Exit Function
        While Len(empno) < 5
            empno = "0" & empno
        Wend
'        get_checker_name = empno
'        Exit Function
        strSql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP FROM EMPLOYEE WHERE SUBSTR(EMPLOYEE_ID, -" & Len(empno) & ") = '" & empno & "'"
    End If
    Set dsFUNCTION_RECORDSET = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    get_checker_name = dsFUNCTION_RECORDSET.Fields("THE_EMP").Value
    
End Function
