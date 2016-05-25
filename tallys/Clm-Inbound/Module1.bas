Attribute VB_Name = "Module1"
Global tmp As String
Global arrTmp() As String
Global TotalRecCount As Integer
Global TotalPage As Integer
Global sqlStmtVslName As String
Global sqlStmtCusName As String
Global sqlStmtOrderDetail As String
Global sqlStmtTotalCTN As String
Global sqlStmtTotalPLT As String
Global sqlStartEndTime As String
Global sqlGetChecker As String

Global rs As ADODB.Recordset
Global order As String
Global cusID As String
Global cusName As String
Global chkName As String
Global vslID As String
Global vslName As String
Global totalPlt As Integer
Global totalCTN As Integer
Global RecPerPage As Integer
Global startTime As String
Global endTime As String


Type orderDetail
    orderNum As String
    Barcode As String
    pkgHse As String
    dc As String
    wt As String
    qty_rcvd As String
    qty_dmg As String
    checker As String
End Type
Global MyOrderDetail() As orderDetail



Sub setupData()
    
    Dim i As Integer
    ''Dim ub As Integer
    
    
    TotalRecCount = 85
    TotalPage = Int(TotalRecCount / RecPerPage) + 1
    
    ReDim arrTmp(1 To TotalRecCount)
    For i = 1 To TotalRecCount
        arrTmp(i) = i & tmp
    Next i

End Sub
    
    

Sub PrintTally(pn As Integer)
    
    Dim i As Integer
    Dim index As Integer
    
    
    If LCase$(Environ$("USERNAME")) = "tally" Then
        Printer.Copies = 3
    Else
        Printer.Copies = 1
    End If
    
    '' Page Header
    Printer.Font = "Arial"
    Printer.FontBold = True
    Printer.Print ""
    Printer.FontSize = 11
    Printer.FontBold = True
    Printer.Print Tab(35); "PORT OF WILMINGTON IN-BOUND TALLY"
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(10); "START TIME:  " & startTime;
    Printer.Print Tab(10); "END TIME: " & endTime
    Printer.Print ""
    Printer.Print Tab(10); "CUSTOMER:" & cusName
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 10
    Printer.FontBold = False
    Printer.Print Tab(5); "ORDER NUM"; Tab(25); "BARCODE"; Tab(43); "DESCRIPTION"; Tab(75); "QTY_RCVD"; Tab(95); "QTY_DMG"; Tab(115); "SCANNER"
    Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"

    '' Detail Section
    index = lastIndex(RecPerPage * (pn), totalPlt)
    For i = 1 + (pn - 1) * RecPerPage To index
        Printer.Print Tab(5); MyOrderDetail(i).orderNum; Tab(25); MyOrderDetail(i).Barcode; Tab(43); MyOrderDetail(i).pkgHse; Tab(53); MyOrderDetail(i).dc; Tab(61); MyOrderDetail(i).wt; Tab(77); MyOrderDetail(i).qty_rcvd; Tab(97); MyOrderDetail(i).qty_dmg; Tab(115); MyOrderDetail(i).checker;
    Next i
    
    '' Detail Summary
    If (pn = TotalPage) Then
    Printer.Print Tab(1); "====================================================================================================================================================="
    Printer.FontBold = True
    Printer.Print Tab(50); "Total Ctns: "; Tab(71); totalCTN;
    Printer.Print Tab(50); "Total Pallets:"; Tab(71); totalPlt;
    Printer.FontBold = False
    Printer.Print ""
    End If
    
    '' Page Footer
    Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"
    Printer.FontBold = True
    
    Printer.Print Tab(10); fmtStr30("Page No: " & pn & "/" & TotalPage)
    Printer.FontBold = False
    
    ''
    Printer.EndDoc
    

End Sub


Function fmtStr15(oldString As String) As String

    fmtStr15 = Format(oldString, "!@@@@@@@@@@@@@@@")

End Function

Function fmtStr15r(oldString As String) As String

    fmtStr15r = Format(oldString, "@@@@@@@@@@@@@@@")

End Function
Function fmtStr25(oldString As String) As String

    fmtStr25 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@")

End Function

Function fmtStr20(oldString As String) As String

    fmtStr20 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@")

End Function
Function fmtStr18(oldString As String) As String

    fmtStr18 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@")

End Function

Function fmtStr8(oldString As String) As String

    fmtStr8 = Format(oldString, "!@@@@@@@@")

End Function
Function fmtStr5r(oldString As String) As String

    fmtStr5r = Format(oldString, "@@@@@")

End Function

Function fmtStr5(oldString As String) As String

    fmtStr5 = Format(oldString, "!@@@@@")

End Function

Function fmtStr10(oldString As String) As String

    fmtStr10 = Format(oldString, "!@@@@@@@@@@")

End Function

Function fmtStr10r(oldString As String) As String

    fmtStr10r = Format(oldString, "@@@@@@@@@@")

End Function

Function fmtStr30(oldString As String) As String

    fmtStr30 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")

End Function

Function fmtStr35(oldString As String) As String

    fmtStr35 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")

End Function

Function fmtStr40(oldString As String) As String

    fmtStr40 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")

End Function


Function fmtStr50(oldString As String) As String

    fmtStr50 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")

End Function


Function lastIndex(curIndex As Integer, maxIndex As Integer) As Integer

    Dim retVal As Integer
    
        If curIndex <= maxIndex Then
            retVal = curIndex
        Else
            retVal = maxIndex
        End If
        
        lastIndex = retVal
        


End Function

Sub iniVariables()

    
    RecPerPage = 50
    
    ''-- Vessel Name --1 replace
    sqlStmtVslName = "select a.LR_NUM || '-' || a.VESSEL_NAME VSL_NAME" & _
                    " from vessel_profile a" & _
                    " Where a.LR_NUM = MY_LR_NUM"
    
    ''-- Customer Name --1 replace
    sqlStmtCusName = "select a.CUSTOMER_NAME" & _
                    " from customer_profile a" & _
                    " Where a.CUSTOMER_ID = MY_CUSTOMER_ID"

    ''-- Order Detail -3 replaces
'    sqlStmtOrderDetail = "select distinct PLT_ID, PKG_HS, DC, WEIGHT, QTY, ORDER_NUM , COMMENTS from (" & _
'                        "select a.ORDER_NUM, SUBSTR(a.PALLET_ID, 19, 6) PLT_ID, SUBSTR(a.PALLET_ID, 14,5) PKG_HS, DECODE(b.DC, NULL, '   ', b.DC ) DC, DECODE(b.WEIGHT, NULL, '     ',b.WEIGHT || ' KG') WEIGHT, QTY_CHANGE QTY, DECODE(SUBSTR(a.ACTIVITY_DESCRIPTION, 9,1), '0', '  ', a.ACTIVITY_DESCRIPTION) COMMENTS" & _
'                        " from cargo_tracking a, clm_sizemap b" & _
'                        " where UPPER(a.CONTAINER_ID)='MY_ORDER_NUM'" & _
'                        " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
'                        " and a.RECEIVER_ID=MY_CUSTOMER_ID" & _
'                        " and SUBSTR(a.PALLET_ID, 10, 4)=b.FFM (+)" & _
'                        " ORDER BY PKG_HS" & _
'                        ")" & _
'                        "ORDER BY PKG_HS, DC,PLT_ID"

' , EMPLOYEE p      , NVL(p.SUBSTR(EMPLOYEE_NAME, 0, 8), 'NONE') THE_LOGIN      " and TO_CHAR(t.activity_id) = SUBSTR(p.employee_id(+), -4)"
    sqlStmtOrderDetail = "select a.container_ID, nvl(substr(a.PALLET_ID, 18,6), a.PALLET_ID) PLT_ID, a.EXPORTER_CODE PKG_HOUSE, a.QTY_RECEIVED, NVL(a.QTY_DAMAGED, 0) THE_QTY, a.CARGO_SIZE, a.WEIGHT || a.WEIGHT_UNIT WEIGHT, ACTIVITY_NUM" & _
                    " from cargo_tracking a, cargo_activity t" & _
                    " where a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
                    " and a.CONTAINER_ID='MY_ORDER_NUM'" & _
                    " and a.RECEIVER_ID=MY_CUSTOMER_ID" & _
                    " and a.COMMODITY_CODE like '560%'" & _
                    " and a.DATE_RECEIVED IS NOT NULL" & _
                    " and a.pallet_id = t.pallet_id" & _
                    " and a.receiver_id = t.customer_id" & _
                    " and a.container_id = t.order_num" & _
                    " and t.service_code = '8'" & _
                    " ORDER BY a.EXPORTER_CODE, a.CARGO_SIZE, WEIGHT, a.PALLET_ID"
    
    '' -- Start/End Time -3 replaces
    sqlStartEndTime = "select NVL(To_CHAR(MIN(a.DATE_RECEIVED), 'MM/DD/YYYY HH:MI AM'), 'N / A') START_TIME, NVL(TO_CHAR(MAX(a.DATE_RECEIVED), 'MM/DD/YYYY HH:MI AM'), 'N / A') END_TIME" & _
                        " from cargo_tracking a" & _
                        " where UPPER(a.CONTAINER_ID) ='MY_ORDER_NUM'" & _
                        " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
                        " and a.RECEIVER_ID=MY_CUSTOMER_ID" & _
                        " and a.commodity_code like '560%'"
                        
'
'    '-- Total Carton Count -3 replaces
'    sqlStmtTotalCTN = "select SUM(To_NUMBER(substr(a.PALLET_ID, 25, 4))) TOTAL_CTN_COUNT" & _
'                            " from cargo_activity a" & _
'                                " where UPPER(a.ORDER_NUM)='MY_ORDER_NUM'" & _
'                                " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
'                                " and a.CUSTOMER_ID=MYCUSTOMER_ID"
'    sqlGetChecker = "SELECT TRIM(EMPLOYEE_LAST_NAME) || ', ' || TRIM(EMPLOYEE_FIRST_NAME) THE_NAME FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = " _
                    & "(SELECT MIN(ACTIVITY_ID) FROM CARGO_ACTIVITY WHERE ORDER_NUM = 'MY_ORDER_NUM')"
    sqlGetChecker = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_NAME FROM EMPLOYEE WHERE SUBSTR(EMPLOYEE_ID, -5) = " _
                    & "(SELECT TO_CHAR(MIN(ACTIVITY_ID)) FROM CARGO_ACTIVITY WHERE ORDER_NUM = 'MY_ORDER_NUM')"




End Sub


Sub GetVslName()

    Dim tmpStmt As String
    
    '' Modify pre-defined sql stmt
    tmpStmt = Replace(sqlStmtVslName, "MY_LR_NUM", vslID)
    
    '' Assign dc recordset and refresh
    frmCLMChkTally.dc.RecordSource = tmpStmt
    frmCLMChkTally.dc.Refresh
    
    '' Get Recordset
    Set rs = frmCLMChkTally.dc.Recordset
    
    If rs.RecordCount = 0 Then
        MsgBox "Invalid vessel number was entered"
        Set rs = Nothing
        Exit Sub
        
    End If
    
  
    '' Get Value
    vslName = rs.Fields("VSL_NAME").Value
    
    '' Set rs to nothing
    Set rs = Nothing

End Sub


Sub GetOrderDetail()

'     sqlStmtOrderDetail = "select a.ORDER_NUM, SUBSTR(a.PALLET_ID, 19, 6) PLT_ID, SUBSTR(a.PALLET_ID, 14,5) PKG_HS, b.DC, b.WEIGHT, To_NUMBER(substr(a.PALLET_ID, 25, 4)) QTY" & _
'                        " from cargo_activity a, clm_sizemap b" & _
'                        " where a.ORDER_NUM='MY_ORDER_NUM'" & _
'                        " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
'                        " and a.CUSTOMER_ID=MY_CUSTOMER_ID" & _
'                        " and SUBSTR(a.PALLET_ID, 10, 4)=b.FFM (+)" & _
'                        " ORDER BY PKG_HS"
'
    Dim tmpStmt As String
    Dim i As Integer
    
    '' Modify pre-defined sql stmt
    tmpStmt = Replace(sqlStmtOrderDetail, "MY_ORDER_NUM", order)
    tmpStmt = Replace(tmpStmt, "MY_ARRIVAL_NUM", vslID)
    tmpStmt = Replace(tmpStmt, "MY_CUSTOMER_ID", cusID)

    '' Assign dc recordset and refresh
    frmCLMChkTally.dc.RecordSource = tmpStmt
    frmCLMChkTally.dc.Refresh
    
    '' Get Recordset
    Set rs = frmCLMChkTally.dc.Recordset
    
    If rs.RecordCount = 0 Then
        MsgBox "No Order Records Found"
        Set rs = Nothing
    End If
    
    totalPlt = rs.RecordCount
    
    
    ''ReDim MyOrderDetail
    ReDim MyOrderDetail(1 To rs.RecordCount)


rs.MoveFirst
    
    totalCTN = 0
    For i = 1 To rs.RecordCount
        
        MyOrderDetail(i).orderNum = rs.Fields("CONTAINER_ID").Value
        MyOrderDetail(i).Barcode = rs.Fields("PLT_ID").Value
        MyOrderDetail(i).pkgHse = rs.Fields("PKG_HOUSE").Value
        MyOrderDetail(i).dc = rs.Fields("CARGO_SIZE").Value
        MyOrderDetail(i).wt = rs.Fields("WEIGHT").Value
        MyOrderDetail(i).qty_rcvd = rs.Fields("QTY_RECEIVED").Value
        MyOrderDetail(i).qty_dmg = rs.Fields("THE_QTY").Value
'        MyOrderDetail(i).checker = rs.Fields("THE_LOGIN").Value
        MyOrderDetail(i).checker = get_checker_name(rs.Fields("PLT_ID").Value, cusID, vslID, rs.Fields("ACTIVITY_NUM").Value)
        totalCTN = totalCTN + Val(rs.Fields("QTY_RECEIVED").Value)
        rs.MoveNext
    Next i
    
    
    '' Set rs to nothing
    Set rs = Nothing

End Sub


Sub GetCusName()

    Dim tmpStmt As String
    
    '' Modify pre-defined sql stmt
    tmpStmt = Replace(sqlStmtCusName, "MY_CUSTOMER_ID", cusID)
    
    '' Assign dc recordset and refresh
    frmCLMChkTally.dc.RecordSource = tmpStmt
    frmCLMChkTally.dc.Refresh
    
    '' Get Recordset
    Set rs = frmCLMChkTally.dc.Recordset
    
    If rs.RecordCount = 0 Then
        MsgBox "Invalid customer number was entered"
        Set rs = Nothing
    End If
    
  
    '' Get Value
    cusName = rs.Fields("CUSTOMER_NAME").Value
    
    '' Set rs to nothing
    Set rs = Nothing

End Sub


Sub ClearAllFields()

    frmCLMChkTally.txtCustNum.Text = ""
    frmCLMChkTally.txtOrderNum.Text = ""
    frmCLMChkTally.txtVslNum.Text = ""
    
End Sub

Sub GetStartEndTime()

    Dim tmpStmt As String
    Dim i As Integer
    
    
    startTime = ""
    endTime = ""
    
    '' Modify pre-defined sql stmt
    tmpStmt = Replace(sqlStartEndTime, "MY_ORDER_NUM", order)
    tmpStmt = Replace(tmpStmt, "MY_ARRIVAL_NUM", vslID)
    tmpStmt = Replace(tmpStmt, "MY_CUSTOMER_ID", cusID)

    '' Assign dc recordset and refresh
    frmCLMChkTally.dc.RecordSource = tmpStmt
    frmCLMChkTally.dc.Refresh
    
    '' Get Recordset
    Set rs = frmCLMChkTally.dc.Recordset
    
    startTime = rs.Fields("START_TIME").Value
    endTime = rs.Fields("END_TIME").Value
    
    '' Set rs to nothing
    Set rs = Nothing


End Sub

Sub GetCheckName()

    Dim tmpStmt As String
    
    '' Modify pre-defined sql stmt
    tmpStmt = Replace(sqlGetChecker, "MY_ORDER_NUM", order)
    
    '' Assign dc recordset and refresh
    frmCLMChkTally.dc.RecordSource = tmpStmt
    frmCLMChkTally.dc.Refresh
    
    '' Get Recordset
    Set rs = frmCLMChkTally.dc.Recordset
    
    If rs.RecordCount = 0 Then
        MsgBox "Invalid customer number was entered"
        Set rs = Nothing
    End If
    
  
    '' Get Value
    chkName = rs.Fields("THE_NAME").Value
    
    '' Set rs to nothing
    Set rs = Nothing

End Sub

Function get_checker_name(Barcode As String, cust As String, LR As String, act_num As String) As String
    Dim ActDate As String
    Dim empno As String
    Dim dsSHORT_TERM_DATA As Object
    Dim OraSession As Object
    Dim OraDatabase As Object
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Set RFOraDatabase = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)
    Set RFOraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/owner", 0&)
    If RFOraDatabase.LastServerErr <> 0 Then
        If txtFile.Text = "" Then
             MsgBox "Database connection could not be made.  Please Contact TS."
        End If
        End
    End If
    
    
    
    strSql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID " _
            & "FROM CARGO_ACTIVITY " _
            & "WHERE nvl(substr(PALLET_ID, 18,6), PALLET_ID) = '" & Barcode & "' " _
            & "AND ARRIVAL_NUM = '" & LR & "' " _
            & "AND CUSTOMER_ID = '" & cust & "' " _
            & "AND ACTIVITY_NUM = '" & act_num & "'"
    Set dsSHORT_TERM_DATA = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    ActDate = dsSHORT_TERM_DATA.Fields("THE_DATE").Value
    empno = dsSHORT_TERM_DATA.Fields("ACTIVITY_ID").Value
    
    If IsNull(empno) Or empno = "" Then
        get_checker_name = "UNKNOWN"
        Exit Function
    End If
    
    strSql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE WHERE CHANGE_DATE >= TO_DATE('" & ActDate & "', 'MM/DD/YYYY')"
    Set dsSHORT_TERM_DATA = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value >= 1 Then
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
    Set dsSHORT_TERM_DATA = RFOraDatabase.DbCreateDynaset(strSql, 0&)
    
    get_checker_name = dsSHORT_TERM_DATA.Fields("THE_EMP").Value
    
End Function


