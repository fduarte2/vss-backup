Attribute VB_Name = "modGlobal"
Option Explicit
Global OraSession As Object
Global OraDatabase As Object
Global dsCargo_Tracking_Global As Object
Global dsCargo_Tracking As Object
Global dsCARGO_ACTIVITY As Object
Global dsVESSEL_PROFILE As Object
Global dsVOYAGE_CARGO As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsSHORT_TERM_DATA As Object
Global gsPVItem As String
Global dsCARGO_ACTIVITY_ARCHIVE As Object
Global bPwd As Boolean
Global gsOrderNum As String
Global giCustId As Integer
Global gsLotNum As String
'***
Global gsServiceName() As String
Global bCancel As Boolean
Global sPwd As String

Sub Main()
'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
    'Set OraDatabase = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)

    If OraDatabase.lastservererr = 0 Then
        frmDetlsOption.Show
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
    End If
End Sub

Function get_checker_name(Barcode As String, cust As String, LR As String, act_num As String) As String
    Dim ActDate As String
    Dim empno As String
    Dim strSql As String
    
    strSql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID " _
            & "FROM CARGO_ACTIVITY " _
            & "WHERE PALLET_ID = '" & Barcode & "' " _
            & "AND ARRIVAL_NUM = '" & LR & "' " _
            & "AND CUSTOMER_ID = '" & cust & "' " _
            & "AND ACTIVITY_NUM = '" & act_num & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(strSql, 0&)
    
    If (dsSHORT_TERM_DATA.recordcount > 0) Then
        ActDate = dsSHORT_TERM_DATA.fields("THE_DATE").Value
        empno = dsSHORT_TERM_DATA.fields("ACTIVITY_ID").Value
    Else
        ActDate = Format(Now, "m/d/Y")
        empno = ""
    End If
    
    If IsNull(empno) Or empno = "" Then
        get_checker_name = "NONE"
        Exit Function
    End If
    
    strSql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE WHERE CHANGE_DATE >= TO_DATE('" & ActDate & "', 'MM/DD/YYYY')"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(strSql, 0&)
    
    If dsSHORT_TERM_DATA.fields("THE_COUNT").Value >= 1 Then
        strSql = "SELECT LOGIN_ID THE_EMP FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '" & empno & "'"
    Else
'        get_checker_name = empno
'        Exit Function
        While Len(empno) < 5
            empno = "0" & empno
        Wend
        strSql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP FROM EMPLOYEE WHERE SUBSTR(EMPLOYEE_ID, -" & Len(empno) & ") = '" & empno & "'"
    End If
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(strSql, 0&)
    
    get_checker_name = dsSHORT_TERM_DATA.fields("THE_EMP").Value
    
End Function

Function get_checker_DB_value(checker As String) As String
    Dim strSql As String
    
    ' check old table
    strSql = "SELECT EMPLOYEE_ID FROM PER_OWNER.PERSONNEL WHERE LOGIN_ID = '" & checker & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(strSql, 0&)
    If dsSHORT_TERM_DATA.recordcount > 0 Then
        get_checker_DB_value = dsSHORT_TERM_DATA.fields("EMPLOYEE_ID").Value
    Else
        ' check new table
        strSql = "SELECT SUBSTR(EMPLOYEE_ID, -5) THE_EMPLOYEE FROM EMPLOYEE WHERE SUBSTR(EMPLOYEE_NAME, 0, " & Len(checker) & ") = '" & checker & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(strSql, 0&)
        get_checker_DB_value = dsSHORT_TERM_DATA.fields("THE_EMPLOYEE").Value
    End If
End Function

