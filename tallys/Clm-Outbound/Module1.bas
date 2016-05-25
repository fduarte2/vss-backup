Attribute VB_Name = "Module1"
Option Explicit
Global strSql As String
Global dsMAIN_DATA As Object
Global dsSHORT_TERM_DATA As Object

Global strVesselName As String
Global strVesselNameOutput As String
Global strCommodityName As String
Global strStartTime As String
Global strEndTime As String
Global strOrderNum As String
Global strCustName As String
Global sPath As String

Global OraSession As Object
Global OraDatabase As Object

'
'
'
'
'
'
'
'
'
'
'
'
'
'
'
'
'
'
'Global tmp As String
'Global arrTmp() As String
'Global TotalRecCount As Integer
'Global TotalPage As Integer
'Global sqlStmtVslName As String
'Global sqlStmtCusName As String
'Global sqlStmtOrderDetail As String
'Global sqlStmtTotalCTN As String
'Global sqlStmtTotalPLT As String
'Global sqlStartEndTime As String
'Global sqlCommodity As String
'Global sqtStmtOther As String
'Global ds
'
'Global rs As New ADODB.Recordset
'Global order As String
'Global cusID As String
'Global cusName As String
'Global vslID As String
'Global vslName As String
'Global totalPlt As Integer
'Global totalCTN As Integer
'Global RecPerPage As Integer
'Global startTime As String
'Global endTime As String
'Global comm As String
'Global sPath As String
'
'Global alert_flag As Boolean    ' set in GetOrderDetail(), used in PrintTally() to determine if there was an override, and a signature line needs to be printed.
'
'Type orderDetail
'    orderNum As String
'    barCode As String
'    pkgHse As String
'    dc As String
'    wt As String
'    qty As String
'    qtyOrg As String
'    cmt As String
'    comm As String
'    status As String
'End Type
'Global MyOrderDetail() As orderDetail
'
'
'
'Sub setupData()
'
'    Dim i As Integer
'    ''Dim ub As Integer
'
'
'    TotalRecCount = 85
'    TotalPage = Int(TotalRecCount / RecPerPage) + 1
'
'    ReDim arrTmp(1 To TotalRecCount)
'    For i = 1 To TotalRecCount
'        arrTmp(i) = i & tmp
'    Next i
'
'End Sub
'
'
'
'Sub PrintTally(pn As Integer)
'
'    Dim i As Integer
'    Dim index As Integer
'
'    If frmCLMChkTally.txtFile.Text <> "" Then
'        If LCase$(Right(frmCLMChkTally.txtFile.Text, 3)) <> "xls" Then
'            MsgBox "Supplied filename is not a XLS type file.  File output cancelled."
'            Exit Sub
'        End If
'
'        ' if CommandArgs(3) is supplied, then write this to file
'        ' else, print to printer.
'        ' It was requested that I write a function by which the program would know which case it was and "print" to the correct
'        ' area, but I have yet to figure out how to do that, and so am going with an if-else statement.
'        Open frmCLMChkTally.txtFile.Text For Output As #1
'
'        Print #1, ""
'        Print #1, ""
'        Print #1, fmtStr20(" ") & "PORT OF WILMINGTON TALLY"
'        Print #1, ""
'        Print #1, ""
'        Print #1, "START TIME:  " & startTime
'        Print #1, "END TIME:  " & endTime
'        Print #1, ""
'        If vslID = "440" And cusID = "440" Then
'            Print #1, fmtStr50(" ") & "CUSTOMER:  " & cusName
'        Else
'            Print #1, fmtStr50("VESSEL:  " & vslName) & "CUSTOMER:  " & cusName
'        End If
'        Print #1, ""
'        Print #1, ""
'        Print #1, fmtStr40(" ") & fmtStr15("DESCRIPTION") & fmtStr10("QTY") & "RGR/"
'        Print #1, fmtStr35("BARCODE") & fmtStr5("PKG") & fmtStr8("WEIGHT") & fmtStr5("SIZE") & fmtStr5("ORIG") & fmtStr8("ACTUAL") _
'        & fmtStr5("HSP") & fmtStr8("DAMAGE") & fmtStr15("CONTAINER NUM")
'        Print #1, "--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
'
'        For i = 1 To totalPlt
'            Print #1, fmtStr35("*" & MyOrderDetail(i).barCode & "*") & fmtStr5(MyOrderDetail(i).pkgHse) & fmtStr8(MyOrderDetail(i).wt) & fmtStr5(MyOrderDetail(i).dc) _
'            & fmtStr5(MyOrderDetail(i).qtyOrg) & fmtStr8(MyOrderDetail(i).qty) & fmtStr5(MyOrderDetail(i).status) & fmtStr8(MyOrderDetail(i).cmt) _
'            & fmtStr15(MyOrderDetail(i).orderNum)
'        Next i
'        Print #1, ""
'        Print #1, fmtStr50(" ") & fmtStr8("TOTAL") & fmtStr8("CASES:") & totalCTN
'        Print #1, fmtStr50(" ") & fmtStr8("TOTAL") & fmtStr8("PALLETS:") & totalPlt
'
'        Close #1
'
'    Else
'
'
'        Printer.Copies = 3
'
'        '' Page Header
'        Printer.Font = "Arial"
'        Printer.FontBold = True
'        Printer.Print ""
'        Printer.FontSize = 11
'        Printer.FontBold = True
'        Printer.Print Tab(40); "PORT OF WILMINGTON TALLY"
'        Printer.Print ""
'        Printer.Print ""
'        Printer.Print Tab(10); "START TIME: " & startTime
'        Printer.Print Tab(10); "END TIME: " & endTime; Tab(55); "ORDER NUMBER: " & order
'        ''Printer.Print ""
'        ''Printer.Print Tab(10); fmtStr50("COMMODITY: " & comm)
'        Printer.Print ""
'        If vslID = "440" And cusID = "440" Then
'            Printer.Print Tab(55); fmtStr50("CUSTOMER:" & cusName)
'        Else
'            Printer.Print Tab(10); "VESSEL:" & vslName; Tab(55); "CUSTOMER:" & cusName
'        End If
'        Printer.Print ""
'        Printer.Print ""
'        Printer.FontSize = 10
'        Printer.FontBold = False
'        Printer.Print Tab(46); "DESCRIPTION"; Tab(71); "QTY"; Tab(85); "RGR/"
'        Printer.Print "BARCODE"; Tab(43); "PKG"; Tab(50); "WEIGHT"; Tab(60); "SIZE"; Tab(67); "ORIG"; Tab(74); "ACTUAL"; Tab(85); "HSP"; Tab(93); "DAMAGE"; Tab(106); "CONTAINER NUM"
'    '    If vslID = "440" And cusID = "440" Then
'    '        Printer.Print Tab(10); fmtStr27("CONTAINER NUM") & fmtStr30("BARCODE") & fmtStr30("DESCRIPTION") & fmtStr15("QTY") & fmtStr15("COMMENTS");
'    '    Else
'    '        Printer.Print Tab(10); fmtStr30("ORDER NUM") & fmtStr20("BARCODE") & fmtStr30("DESCRIPTION") & fmtStr15("       QTY") & fmtStr25("RGR/HSP   COMMENTS");
'    '    End If
'
'        Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"
'
'        '' Detail Section
'        index = lastIndex(RecPerPage * (pn), totalPlt)
'        For i = 1 + (pn - 1) * RecPerPage To index
'
'            Printer.Print MyOrderDetail(i).barCode; Tab(43); MyOrderDetail(i).pkgHse; Tab(50); MyOrderDetail(i).wt; Tab(60); MyOrderDetail(i).dc; _
'                Tab(67); MyOrderDetail(i).qtyOrg; Tab(74); MyOrderDetail(i).qty; Tab(85); MyOrderDetail(i).status; Tab(97); MyOrderDetail(i).cmt _
'                ; Tab(106); MyOrderDetail(i).orderNum
'
'
'    '        Printer.Print Tab(10); fmtStr35(MyOrderDetail(i).orderNum) & _
'    '        fmtStr15(MyOrderDetail(i).comm & "-" & MyOrderDetail(i).barCode) & _
'    '        fmtStr15(MyOrderDetail(i).pkgHse) & _
'    '        fmtStr10(MyOrderDetail(i).dc) & _
'    '        fmtStr20(MyOrderDetail(i).wt); Tab(90); fmtStr5(MyOrderDetail(i).qty); ; Tab(98); fmtStr5(MyOrderDetail(i).status); Tab(110); MyOrderDetail(i).cmt
'    '
'        Next i
'        '' Detail Summary
'        If (pn = TotalPage) Then
'        Printer.Print Tab(1); "====================================================================================================================================================="
'        Printer.FontBold = True
'        Printer.Print Tab(45); "Total Cases:"; Tab(65); totalCTN
'        Printer.Print Tab(45); "Total Pallets:"; Tab(65); totalPlt
'
'    '    Printer.Print Tab(40); fmtStr30(" ") & fmtStr30(" ") & fmtStr30("Total Ctns: " & totalCTN)
'    '    Printer.Print Tab(40); fmtStr30(" ") & fmtStr30(" ") & fmtStr30("Total Pallets: " & totalPlt)
'        Printer.FontBold = False
'        Printer.Print ""
'        End If
'
'        '' Page Footer
'        Printer.Print Tab(1); "_____________________________________________________________________________________________________________________________________________________"
'        Printer.FontBold = True
'
'        Printer.Print Tab(10); fmtStr30("Page No: " & pn & "/" & TotalPage)
'        Printer.FontBold = False
'
'        ''
'        Printer.EndDoc
'
'    End If
'
'
'End Sub
'
'
'Function fmtStr15(oldString As String) As String
'
'    fmtStr15 = Format(oldString, "!@@@@@@@@@@@@@@@")
'
'End Function
'
'Function fmtStr15r(oldString As String) As String
'
'    fmtStr15r = Format(oldString, "@@@@@@@@@@@@@@@")
'
'End Function
'Function fmtStr25(oldString As String) As String
'
'    fmtStr25 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@")
'
'End Function
'
'Function fmtStr20(oldString As String) As String
'
'    fmtStr20 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@")
'
'End Function
'Function fmtStr18(oldString As String) As String
'
'    fmtStr18 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@")
'
'End Function
'
'Function fmtStr8(oldString As String) As String
'
'    fmtStr8 = Format(oldString, "!@@@@@@@@")
'
'End Function
'Function fmtStr5r(oldString As String) As String
'
'    fmtStr5r = Format(oldString, "@@@@@")
'
'End Function
'
'Function fmtStr5(oldString As String) As String
'
'    fmtStr5 = Format(oldString, "!@@@@@")
'
'End Function
'
'Function fmtStr10(oldString As String) As String
'
'    fmtStr10 = Format(oldString, "!@@@@@@@@@@")
'
'End Function
'
'Function fmtStr10r(oldString As String) As String
'
'    fmtStr10r = Format(oldString, "@@@@@@@@@@")
'
'End Function
'
'Function fmtStr30(oldString As String) As String
'
'    fmtStr30 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")
'
'End Function
'
'Function fmtStr35(oldString As String) As String
'
'    fmtStr35 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")
'
'End Function
'Function fmtStr27(oldString As String) As String
'
'    fmtStr27 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@@@")
'
'End Function
'
'
'Function fmtStr40(oldString As String) As String
'
'    fmtStr40 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")
'
'End Function
'
'
'Function fmtStr50(oldString As String) As String
'
'    fmtStr50 = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")
'
'End Function
'
'
'Function lastIndex(curIndex As Integer, maxIndex As Integer) As Integer
'
'    Dim retVal As Integer
'
'        If curIndex <= maxIndex Then
'            retVal = curIndex
'        Else
'            retVal = maxIndex
'        End If
'
'        lastIndex = retVal
'
'
'
'End Function
'
'Sub iniVariables()
'
'
'    RecPerPage = 50
'
'    ''-- Vessel Name --1 replace
'    sqlStmtVslName = "select a.LR_NUM || '-' || a.VESSEL_NAME VSL_NAME" & _
'                    " from vessel_profile a" & _
'                    " Where a.LR_NUM = MY_LR_NUM"
'
'    ''-- Customer Name --1 replace
'    sqlStmtCusName = "select a.CUSTOMER_NAME" & _
'                    " from customer_profile a" & _
'                    " Where a.CUSTOMER_ID = MY_CUSTOMER_ID"
'
'    '' -- Commodity -- 3 replaces
'    sqlCommodity = "select distinct a.COMMODITY_CODE, c.COMMODITY_NAME" & _
'                    " from cargo_tracking a, cargo_activity b, commodity_profile c" & _
'                    " where a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
'                    " and a.RECEIVER_ID=MY_CUSTOMER_ID" & _
'                    " and a.ARRIVAL_NUM=b.ARRIVAL_NUM" & _
'                    " and a.RECEIVER_ID=b.CUSTOMER_ID" & _
'                    " and a.PALLET_ID=b.PALLET_ID" & _
'                    " and a.COMMODITY_CODE=c.COMMODITY_CODE" & _
'                    " and b.ORDER_NUM='MY_ORDER_NUM'"
'
'    ''-- Order Detail -3 replaces
'    If (Trim(frmCLMChkTally.txtCustNum.Text) = "440") And (Trim(frmCLMChkTally.txtVslNum.Text) = "440") Then
'
''        sqlStmtOrderDetail = "select ORDER_NUM, CONTAINER_ID, SUBSTR(PALLET_ID, 19,6) BARCODE, PKG_HSE, DC, WEIGHT || ' KG' WEIGHT, QTY_CHANGE QTY, DECODE(SUBSTR(ACTIVITY_DESCRIPTION, 9,1), '0', '  ', ACTIVITY_DESCRIPTION) COMMENTS from" & _
''                            " (select a.QTY_CHANGE,a.ACTIVITY_DESCRIPTION, a.PALLET_ID PALLET_ID, b.CONTAINER_ID CONTAINER_ID, a.ORDER_NUM ORDER_NUM, a.DATE_OF_ACTIVITY DATE_OF_ACTIVITY, b.EXPORTER_CODE PKG_HSE, m.DC DC, m.WEIGHT WEIGHT" & _
''                            " from cargo_activity a, cargo_tracking b, clm_sizemap m" & _
''                            " Where a.ARRIVAL_NUM = b.ARRIVAL_NUM" & _
''                            " and a.CUSTOMER_ID=b.RECEIVER_ID" & _
''                            " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
''                            " and a.SERVICE_CODE=6" & _
''                            " and b.COMMODITY_CODE=5602" & _
''                            " and a.PALLET_ID=b.PALLET_ID" & _
''                            " and a.CUSTOMER_ID=MY_CUSTOMER_ID" & _
''                            " and substr(b.PALLET_ID, 10, 4)=m.FFM(+)" & _
''                            " and a.ORDER_NUM='MY_ORDER_NUM')" & _
''                            " order by PKG_HSE, DC, BARCODE"
'
'        sqlStmtOrderDetail = "select THE_WEIGHT WEIGHT, ORDER_NUM, NVL(BATCH_ID, '0') THE_ORG, CONTAINER_ID, PALLET_ID BARCODE, PKG_HSE, DC, QTY_CHANGE QTY, COMMENTS, COMM_CODE from" & _
'                            " (select b.WEIGHT || b.WEIGHT_UNIT THE_WEIGHT, a.QTY_CHANGE, b.BATCH_ID, DECODE(SUBSTR(a.ACTIVITY_DESCRIPTION, 9,1), '0', '  ', a.ACTIVITY_DESCRIPTION) COMMENTS, a.PALLET_ID PALLET_ID, b.CONTAINER_ID CONTAINER_ID, a.ORDER_NUM ORDER_NUM, a.DATE_OF_ACTIVITY DATE_OF_ACTIVITY, b.EXPORTER_CODE PKG_HSE, CARGO_SIZE DC, DECODE(b.COMMODITY_CODE, NULL, ' ',b.COMMODITY_CODE ) COMM_CODE" & _
'                            " from cargo_activity a, cargo_tracking b" & _
'                            " Where a.ARRIVAL_NUM = b.ARRIVAL_NUM" & _
'                            " and a.CUSTOMER_ID=b.RECEIVER_ID" & _
'                            " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
'                            " and a.SERVICE_CODE IN (6, 7, 13)" & _
'                            " and a.QTY_CHANGE > 0" & _
'                            " and a.PALLET_ID=b.PALLET_ID" & _
'                            " and a.ACTIVITY_DESCRIPTION != 'VOID'" & _
'                            " and a.CUSTOMER_ID=MY_CUSTOMER_ID" & _
'                            " and a.ORDER_NUM='MY_ORDER_NUM')" & _
'                            " order by PKG_HSE, DC, BARCODE"
'
'
'
'
'
'
'    Else
'
''        sqlStmtOrderDetail = "select distinct PLT_ID, PKG_HS, DC, WEIGHT, QTY, ORDER_NUM , COMMENTS from (" & _
''                            "select a.ORDER_NUM, SUBSTR(a.PALLET_ID, 19, 6) PLT_ID, SUBSTR(a.PALLET_ID, 14,5) PKG_HS, DECODE(b.DC, NULL, '   ', b.DC ) DC, DECODE(b.WEIGHT, NULL, '     ',b.WEIGHT || ' KG') WEIGHT, QTY_CHANGE QTY, DECODE(SUBSTR(a.ACTIVITY_DESCRIPTION, 9,1), '0', '  ', a.ACTIVITY_DESCRIPTION) COMMENTS" & _
''                            " from cargo_activity a, clm_sizemap b" & _
''                            " where UPPER(a.ORDER_NUM)='MY_ORDER_NUM'" & _
''                            " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
''                            " and a.SERVICE_CODE=6" & _
''                            " and a.QTY_CHANGE > 0" & _
''                            " and a.CUSTOMER_ID=MY_CUSTOMER_ID" & _
''                            " and SUBSTR(a.PALLET_ID, 10, 4)=b.FFM (+)" & _
''                            " ORDER BY PKG_HS" & _
''                            ")" & _
''                            "ORDER BY PKG_HS, DC,PLT_ID"
'
'        sqlStmtOrderDetail = "select distinct THE_WEIGHT WEIGHT, PLT_ID, NVL(BATCH_ID, '0') THE_ORG, PKG_HS, DC, QTY, ORDER_NUM , COMMENTS, COMM_CODE, STATUS, CONTAINER_ID" & _
'                            " from" & _
'                            " (" & _
'                            " select c.WEIGHT || c.WEIGHT_UNIT THE_WEIGHT, a.ORDER_NUM, a.PALLET_ID PLT_ID, c.BATCH_ID, c.EXPORTER_CODE PKG_HS, c.CARGO_SIZE DC, QTY_CHANGE QTY, DECODE(SUBSTR(a.ACTIVITY_DESCRIPTION, 9,1), '0', '  ', a.ACTIVITY_DESCRIPTION) COMMENTS, DECODE(c.COMMODITY_CODE, NULL, ' ',c.COMMODITY_CODE ) COMM_CODE, c.CARGO_STATUS STATUS, CONTAINER_ID" & _
'                            " from cargo_activity a, cargo_tracking c" & _
'                            " where UPPER(a.ORDER_NUM)='MY_ORDER_NUM'" & _
'                            " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
'                            " and a.SERVICE_CODE IN (6, 7, 13)" & _
'                            " and a.QTY_CHANGE > 0" & _
'                            " and a.CUSTOMER_ID=MY_CUSTOMER_ID" & _
'                            " and a.PALLET_ID=c.PALLET_ID" & _
'                            " and a.ACTIVITY_DESCRIPTION != 'VOID'" & _
'                            " and a.ARRIVAL_NUM=c.ARRIVAL_NUM" & _
'                            " and a.CUSTOMER_ID=c.RECEIVER_ID" & _
'                            " )" & _
'                            " ORDER BY PKG_HS, DC,PLT_ID"
'
'    End If
'
'    '' -- Start/End Time -3 replaces
'    If (Trim(frmCLMChkTally.txtCustNum.Text) = "440") And (Trim(frmCLMChkTally.txtVslNum.Text) = "440") Then
''            sqlStartEndTime = "select TO_CHAR(MIN(a.DATE_OF_ACTIVITY), 'mm/dd/yyyy hh:mi:ss AM') START_TIME, TO_CHAR(MAX(a.DATE_OF_ACTIVITY),'mm/dd/yyyy hh:mi:ss AM') END_TIME" & _
''                " from cargo_activity a, cargo_tracking b, clm_sizemap m" & _
''                " Where a.ARRIVAL_NUM = b.ARRIVAL_NUM" & _
''                " and a.CUSTOMER_ID=b.RECEIVER_ID" & _
''                " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
''                " and a.SERVICE_CODE=6" & _
''                " and b.COMMODITY_CODE=5602" & _
''                " and a.PALLET_ID=b.PALLET_ID" & _
''                " and a.CUSTOMER_ID=MY_CUSTOMER_ID" & _
''                " and substr(b.PALLET_ID, 10, 4)=m.FFM(+)" & _
''                " and a.ORDER_NUM='MY_ORDER_NUM'"
'
'            sqlStartEndTime = "select TO_CHAR(MIN(a.DATE_OF_ACTIVITY), 'mm/dd/yyyy hh:mi:ss AM') START_TIME, TO_CHAR(MAX(a.DATE_OF_ACTIVITY),'mm/dd/yyyy hh:mi:ss AM') END_TIME" & _
'            " from cargo_activity a, cargo_tracking b, clm_sizemap m" & _
'            " Where a.ARRIVAL_NUM = b.ARRIVAL_NUM" & _
'            " and a.CUSTOMER_ID=b.RECEIVER_ID" & _
'            " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
'            " and a.SERVICE_CODE=6" & _
'            " and a.PALLET_ID=b.PALLET_ID" & _
'            " and a.CUSTOMER_ID=MY_CUSTOMER_ID" & _
'            " and substr(b.PALLET_ID, 10, 4)=m.FFM(+)" & _
'            " and a.ORDER_NUM='MY_ORDER_NUM'"
'
'
'    Else
'        sqlStartEndTime = "select To_CHAR(MIN(a.DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(a.DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') END_TIME" & _
'                            " from cargo_activity a, clm_sizemap b" & _
'                            " where UPPER(a.ORDER_NUM) ='MY_ORDER_NUM'" & _
'                            " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
'                            " and a.CUSTOMER_ID=MY_CUSTOMER_ID"
'    End If
''
''    '-- Total Carton Count -3 replaces
''    sqlStmtTotalCTN = "select SUM(To_NUMBER(substr(a.PALLET_ID, 25, 4))) TOTAL_CTN_COUNT" & _
''                            " from cargo_activity a" & _
''                                " where UPPER(a.ORDER_NUM)='MY_ORDER_NUM'" & _
''                                " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
''                                " and a.CUSTOMER_ID=MYCUSTOMER_ID"
'
'
'
'
'End Sub
'
'
'Sub GetVslName()
'
'    Dim tmpStmt As String
'
'    '' Modify pre-defined sql stmt
'    tmpStmt = Replace(sqlStmtVslName, "MY_LR_NUM", vslID)
'
'    '' Assign dc recordset and refresh
'    frmCLMChkTally.dc.RecordSource = tmpStmt
'    frmCLMChkTally.dc.Refresh
'
'    '' Get Recordset
'    Set rs = frmCLMChkTally.dc.Recordset
'
'    If rs.RecordCount = 0 Then
'        MsgBox "Invalid vessel number was entered"
'        Set rs = Nothing
'        Exit Sub
'
'    End If
'
'
'    '' Get Value
'    vslName = rs.Fields("VSL_NAME").Value
'
'    '' Set rs to nothing
'    Set rs = Nothing
'
'End Sub
'
'
'Sub GetOrderDetail()
'
''     sqlStmtOrderDetail = "select a.ORDER_NUM, SUBSTR(a.PALLET_ID, 19, 6) PLT_ID, SUBSTR(a.PALLET_ID, 14,5) PKG_HS, b.DC, b.WEIGHT, To_NUMBER(substr(a.PALLET_ID, 25, 4)) QTY" & _
''                        " from cargo_activity a, clm_sizemap b" & _
''                        " where a.ORDER_NUM='MY_ORDER_NUM'" & _
''                        " and a.ARRIVAL_NUM='MY_ARRIVAL_NUM'" & _
''                        " and a.CUSTOMER_ID=MY_CUSTOMER_ID" & _
''                        " and SUBSTR(a.PALLET_ID, 10, 4)=b.FFM (+)" & _
''                        " ORDER BY PKG_HS"
''
'    Dim tmpStmt As String
'    Dim i As Integer
'    Dim temp() As String
'
'    '' Modify pre-defined sql stmt
'    tmpStmt = Replace(sqlStmtOrderDetail, "MY_ORDER_NUM", order)
'    tmpStmt = Replace(tmpStmt, "MY_ARRIVAL_NUM", vslID)
'    tmpStmt = Replace(tmpStmt, "MY_CUSTOMER_ID", cusID)
'
'    '' Assign dc recordset and refresh
'    frmCLMChkTally.dc.RecordSource = tmpStmt
'    frmCLMChkTally.dc.Refresh
'
'    '' Get Recordset
'    Set rs = frmCLMChkTally.dc.Recordset
'
'    If rs.RecordCount = 0 Then
'        MsgBox "No Order Records Found"
'        Set rs = Nothing
'    End If
'
'    totalPlt = rs.RecordCount
'
'
'    ''ReDim MyOrderDetail
'    ReDim MyOrderDetail(1 To rs.RecordCount)
'
'
'rs.MoveFirst
'
'    totalCTN = 0
'
'    For i = 1 To rs.RecordCount
'
'        "SELECT * FROM (SELECT DO.ORDERNUM THE_ORDER, PACKINGHOUSE, SIZEHIGH, SIZELOW FROM DC_ORDERDETAIL DO, DC_PICKLIST DP WHERE " _
'                & "DO.ORDERNUM = DP.ORDERNUM AND DO.ORDERDETAILID = DP.ORDERDETAILID AND DO.ORDERNUM = '" & order & "') T2 " _
'                & "WHERE T2.THE_ORDER = '" & order & "' AND T2.PACKINGHOUSE = '" & rs.Fields("PKG_HSE").Value & "' AND TO_NUMBER(SIZEHIGH) >= " _
'                & Val("" & rs.Fields("DC").Value) & " AND TO_NUMBER(SIZELOW) <= " & Val("" & rs.Fields("DC").Value)
'
'
'
'        If (vslID = "440") And (cusID = "440") Then
'            MyOrderDetail(i).orderNum = rs.Fields("CONTAINER_ID").Value
'            MyOrderDetail(i).barCode = rs.Fields("BARCODE").Value
'            MyOrderDetail(i).pkgHse = rs.Fields("PKG_HSE").Value
'            MyOrderDetail(i).dc = rs.Fields("DC").Value
'            MyOrderDetail(i).wt = rs.Fields("WEIGHT").Value
'            MyOrderDetail(i).qty = rs.Fields("QTY").Value
'            MyOrderDetail(i).qtyOrg = rs.Fields("THE_ORG").Value
'            If IsNull(rs.Fields("COMMENTS").Value) Then
'                MyOrderDetail(i).cmt = " "
'            Else
'                temp = Split(rs.Fields("COMMENTS").Value, ":")
'                MyOrderDetail(i).cmt = temp(1)
'            End If
'            MyOrderDetail(i).comm = rs.Fields("COMM_CODE").Value
'            totalCTN = totalCTN + Val(rs.Fields("QTY").Value)
'        Else
'
'            If IsNull(rs.Fields("CONTAINER_ID").Value) Then
'                MyOrderDetail(i).orderNum = ""
'            Else
'                MyOrderDetail(i).orderNum = rs.Fields("CONTAINER_ID").Value
'            End If
'
'            MyOrderDetail(i).barCode = rs.Fields("PLT_ID").Value
'            MyOrderDetail(i).pkgHse = rs.Fields("PKG_HS").Value
'            MyOrderDetail(i).dc = rs.Fields("DC").Value
'            MyOrderDetail(i).wt = rs.Fields("WEIGHT").Value
'            MyOrderDetail(i).qty = rs.Fields("QTY").Value
'            MyOrderDetail(i).qtyOrg = rs.Fields("THE_ORG").Value
'            MyOrderDetail(i).comm = rs.Fields("COMM_CODE").Value
'
'
'            If IsNull(rs.Fields("COMMENTS").Value) Then
'                MyOrderDetail(i).cmt = " "
'            Else
'                temp = Split(rs.Fields("COMMENTS").Value, ":")
'                MyOrderDetail(i).cmt = temp(1)
'            End If
'            totalCTN = totalCTN + Val(rs.Fields("QTY").Value)
'
'
'            If IsNull(rs.Fields("STATUS").Value) Then
'                MyOrderDetail(i).status = " "
'            Else
'                MyOrderDetail(i).status = rs.Fields("STATUS").Value
'            End If
'
'        End If
'
'
'        rs.MoveNext
'
'
'    Next i
'
'
'    '' Set rs to nothing
'    Set rs = Nothing
'
'End Sub
'
'
'Sub GetCusName()
'
'    Dim tmpStmt As String
'
'    '' Modify pre-defined sql stmt
'    tmpStmt = Replace(sqlStmtCusName, "MY_CUSTOMER_ID", cusID)
'
'    '' Assign dc recordset and refresh
'    frmCLMChkTally.dc.RecordSource = tmpStmt
'    frmCLMChkTally.dc.Refresh
'
'    '' Get Recordset
'    Set rs = frmCLMChkTally.dc.Recordset
'
'    If rs.RecordCount = 0 Then
'        MsgBox "Invalid customer number was entered"
'        Set rs = Nothing
'    End If
'
'
'    '' Get Value
'    cusName = rs.Fields("CUSTOMER_NAME").Value
'
'    '' Set rs to nothing
'    Set rs = Nothing
'
'End Sub
'
'
'Sub ClearAllFields()
'
'    frmCLMChkTally.txtCustNum.Text = ""
'    frmCLMChkTally.txtOrderNum.Text = ""
'    frmCLMChkTally.txtVslNum.Text = ""
'
'End Sub
'
'Sub GetStartEndTime()
'
'    Dim tmpStmt As String
'    Dim i As Integer
'
'
'    startTime = ""
'    endTime = ""
'
'    '' Modify pre-defined sql stmt
'    tmpStmt = Replace(sqlStartEndTime, "MY_ORDER_NUM", order)
'    tmpStmt = Replace(tmpStmt, "MY_ARRIVAL_NUM", vslID)
'    tmpStmt = Replace(tmpStmt, "MY_CUSTOMER_ID", cusID)
'
'    '' Assign dc recordset and refresh
'    frmCLMChkTally.dc.RecordSource = tmpStmt
'    frmCLMChkTally.dc.Refresh
'
'    '' Get Recordset
'    Set rs = frmCLMChkTally.dc.Recordset
'
'    startTime = rs.Fields("START_TIME").Value
'    endTime = rs.Fields("END_TIME").Value
'
'    '' Set rs to nothing
'    Set rs = Nothing
'
'
'End Sub
'
'
'Sub GetComm()
'
'Dim tmpStmt As String
'Dim tmpCommCode As String
'Dim tmpCommName As String
'
'    '' Modify pre-defined sql stmt
'    tmpStmt = Replace(sqlCommodity, "MY_ORDER_NUM", order)
'    tmpStmt = Replace(tmpStmt, "MY_ARRIVAL_NUM", vslID)
'    tmpStmt = Replace(tmpStmt, "MY_CUSTOMER_ID", cusID)
'
'    '' Assign dc recordset and refresh
'    frmCLMChkTally.dc.RecordSource = tmpStmt
'    frmCLMChkTally.dc.Refresh
'
'    '' Get Recordset
'    Set rs = frmCLMChkTally.dc.Recordset
'
'    If (rs.RecordCount > 1) Then
'        MsgBox "Multiple Commodity Found !! Contact Office"
'    ElseIf (rs.RecordCount = 1) Then
'
'        If (IsNull(rs.Fields("COMMODITY_CODE").Value)) Then
'            tmpCommCode = " "
'        Else
'            tmpCommCode = rs.Fields("COMMODITY_CODE").Value
'        End If
'
'        If (IsNull(rs.Fields("COMMODITY_NAME").Value)) Then
'            tmpCommName = " "
'        Else
'            tmpCommName = rs.Fields("COMMODITY_NAME").Value
'        End If
'
'        comm = tmpCommCode & "-" & tmpCommName
'
'    End If
'
'    '' Set rs to nothing
'    Set rs = Nothing
'
'End Sub
