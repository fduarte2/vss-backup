Attribute VB_Name = "Module1"
Option Explicit       'Rudy

Global DB As String
Global Login As String
Global User As String
Global Pwd As String
Global action As Integer
Global actInsert As Integer
Global actUpdate As Integer
Global RecID As String
Global selGrid As Integer
Global inGrid As Integer
Global outGrid As Integer
Global optFilterApyTo As Integer
Global optBothGrd As Integer
Global optChkInGrd As Integer
Global optChkOutGrd As Integer
Global strCommFilterIn As String
Global strCustFilterIn As String
Global strCommFilterOut As String
Global strCustFilterOut As String
Global ChkInNFSQL As String  '' check in no filter sql statement
Global ChkOutNFSQL As String '' check out no filter sql statement

Global strStatus As String      'Rudy

'' a data type to describe commodity group definition
Type CommGroupDef
    ID As Integer
    GrpDesc As String
End Type

'' a data type to describe commodity profile
Type CommodityProfile
    ID As Integer
    Desc As String
    Group As Integer
End Type

Global arrComGrpDef() As CommGroupDef
Global arrCommodity() As CommodityProfile

Sub InitGlobalVariables()
    Dim OraSession As Object
    Dim OraDataBase As Object
    Dim dsSHORT_TERM_DATA As Object
    Dim strSql As String
    
    DB = "BNI"
    Login = "SAG_OWNER/SAG"
    User = "SAG_OWNER"
    Pwd = "SAG"
    
    'dev below, orig 4 above
'    DB = "BNITEST"
'    Login = "SAG_OWNER/BNITEST238"
'    User = "SAG_OWNER"
'    Pwd = "BNITEST238"
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    
    'Create the OraDatabase Object
    Set OraDataBase = OraSession.OpenDatabase(DB, Login, 0&)


    
    actInsert = 1
    actUpdate = 2
    inGrid = 3
    outGrid = 4
    optChkInGrd = 5
    optChkOutGrd = 6
    optBothGrd = 7
    strCommFilterIn = "ALL"
    strCustFilterIn = "ALL"
    strCommFilterOut = "ALL"
    strCustFilterOut = "ALL"
    
    'userlist population
    strSql = "SELECT USER_EMAIL_NAME FROM TLS_USER ORDER BY USER_EMAIL_NAME"
    Set dsSHORT_TERM_DATA = OraDataBase.DbCreateDynaset(strSql, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        frmDataEntry.dcboCheckInBy.AddItem (dsSHORT_TERM_DATA.Fields("USER_EMAIL_NAME").Value)
        dsSHORT_TERM_DATA.MoveNext
    Wend

    'commlist population
    strSql = "SELECT COMMODITY_CODE FROM TLS_COMMODITY_PROFILE ORDER BY COMMODITY_CODE"
    Set dsSHORT_TERM_DATA = OraDataBase.DbCreateDynaset(strSql, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        frmDataEntry.dcboComm.AddItem (dsSHORT_TERM_DATA.Fields("COMMODITY_CODE").Value)
        dsSHORT_TERM_DATA.MoveNext
    Wend

    'customer population
    strSql = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsSHORT_TERM_DATA = OraDataBase.DbCreateDynaset(strSql, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        frmDataEntry.dcboCust.AddItem (dsSHORT_TERM_DATA.Fields("CUSTOMER_ID").Value)
        dsSHORT_TERM_DATA.MoveNext
    Wend
    
    'vessel population
    strSql = "SELECT LR_NUM FROM VOYAGE ORDER BY LR_NUM"
    Set dsSHORT_TERM_DATA = OraDataBase.DbCreateDynaset(strSql, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        frmDataEntry.dcboVsl.AddItem (dsSHORT_TERM_DATA.Fields("LR_NUM").Value)
        dsSHORT_TERM_DATA.MoveNext
    Wend
    
    
    ChkInNFSQL = frmDataEntry.dcCheckInTruck.RecordSource
    ChkOutNFSQL = frmDataEntry.dcCheckOutTruck.RecordSource
    
    strStatus = ""    'Rudy
        
End Sub
Sub CheckTruckIn()

On Error GoTo Err_Handler:
 
    Dim OraSession As Object
    Dim OraDataBase As Object
    Dim rs As Object
    Dim strSql As String
    Dim rCount As Long

    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    
    'Create the OraDatabase Object
    Set OraDataBase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDataBase.LastServerErr = 0 Then
    
        Dim CheckInBy As String
        Dim TruckCompany As String
        Dim Driver As String
        Dim Com As String
        Dim Cust As String
        Dim VSL As String
        Dim BOL As String
        Dim WHS As String
        Dim DailyRowNum As Long
        Dim ComDesc As String
        
        CheckInBy = "'" & Trim(frmDataEntry.txtCheckInBy.Text) & "'"
        CheckInBy = "'" & Trim(frmDataEntry.dcboCheckInBy.Text) & "'"
        TruckCompany = "'" & UCase(Trim(frmDataEntry.txtTruckCompany.Text)) & "'"
        Driver = "'" & UCase(Trim(frmDataEntry.txtDriver.Text)) & "'"
        Com = Trim(frmDataEntry.dcboComm.Text)
        Cust = Trim(frmDataEntry.dcboCust.Text)
        VSL = Trim(frmDataEntry.dcboVsl.Text)
        BOL = "'" & UCase(Trim(frmDataEntry.txtBOL.Text)) & "'"
        WHS = "'" & UCase(Trim(frmDataEntry.cboWHSE.Text)) & "'"
        ComDesc = "'" & UCase(Trim(frmDataEntry.lblCommDesc.Caption)) & "'"
        
        DailyRowNum = GetDailyRowNum
        If (DailyRowNum < 0) Then
            Err.Raise 901, , "Unable to generate Daily Row Number"
        End If
        '' Note:
        '' Check_In_Time and Check_Out_time are set by the same value
        '' Check_Out_Time will be updated to another value when the truck is checked out
        '' Seal Number is set to 'NA'
        '' CHECK_OUT_BY is set to 'NA'
        strSql = "Insert into TLS_TRUCK_LOG(RECORD_ID,DAILY_ROW_NUM, TIME_IN, CHECKED_IN_BY, TRUCKING_COMPANY, DRIVER_NAME, COMMODITY_CODE, CUSTOMER_CODE, LR_NUM, BOL,  WAREHOUSE, SEAL_NUM, TIME_OUT, CHECKED_OUT_BY, COMMODITY_NAME)" & _
            " values (TLS_TRUCK_LOG_SEQ.NextVal," & Str(DailyRowNum) & ", TO_DATE('" & Now() & "', 'MM/DD/YYYY HH:MI:SS AM')," & _
            CheckInBy & "," & TruckCompany & "," & Driver & "," & Com & "," & Cust & "," & VSL & "," & _
            BOL & "," & WHS & "," & "'NA', TO_DATE('" & Now() & "', 'MM/DD/YYYY HH:MI:SS AM'), 'NA'," & ComDesc & ")"

        
        '' Create Recordset
        rCount = OraDataBase.ExecuteSQL(strSql)
            
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
    
    Else
        MsgBox "Error:" & OraDataBase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    Exit Sub
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        "CheckTruckIn()"
        OraSession.Rollback
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
        End
    End If

End Sub

Sub RetrieveRecord()

    If (frmDataEntry.grdCheckInTruck.SelBookmarks.count > 0) Then
       
        action = actUpdate
        Dim varBmk As Variant
    ''SELECT
    ''A.RECORD_ID
    ''A.DAILY_ROW_NUM,
    ''A.TIME_IN,
    ''A.CHECKED_IN_BY,
    ''A.TRUCKING_COMPANY,
    ''A.DRIVER_NAME,
    ''A.COMMODITY_CODE,
    ''A.COMMODITY_NAME,
    ''A.CUSTOMER_CODE,
    ''A.LR_NUM,
    ''A.BOL,
    ''A.WAREHOUSE
    
    
        For Each varBmk In frmDataEntry.grdCheckInTruck.SelBookmarks
            
            frmDataEntry.dcCheckInTruck.Recordset.Bookmark = varBmk
            RecID = frmDataEntry.dcCheckInTruck.Recordset.Fields("RECORD_ID").Value
            frmDataEntry.txtCheckInBy.Text = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("CHECKED_IN_BY").Value
            frmDataEntry.dcboCheckInBy.Text = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("CHECKED_IN_BY").Value
            frmDataEntry.txtTruckCompany.Text = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("TRUCKING_COMPANY").Value
            frmDataEntry.txtDriver.Text = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("DRIVER_NAME").Value
            frmDataEntry.dcboComm.Text = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("COMMODITY_CODE").Value
            frmDataEntry.lblCommDesc.Caption = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("COMMODITY_NAME").Value
            frmDataEntry.dcboCust.Text = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("CUSTOMER_CODE").Value
            frmDataEntry.dcboVsl.Text = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("LR_NUM").Value
            frmDataEntry.txtBOL.Text = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("BOL").Value
            frmDataEntry.cboWHSE.Text = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("WAREHOUSE").Value
             
        Next
        Call iniUI(action)
    
    Else
        MsgBox "Please select a record to retrieve"
    End If

End Sub


Sub iniUI(action As Integer)

    frmDataEntry.lblDateTime.Caption = Format(Now(), "mm/dd/yyyy hh:mm:ss AMPM")
    frmDataEntry.lblCommFilterIn.Caption = strCommFilterIn
    frmDataEntry.lblCustFilterIn.Caption = strCustFilterIn
    frmDataEntry.lblCommFilterOut.Caption = strCommFilterOut
    frmDataEntry.lblCustFilterOut.Caption = strCustFilterOut
    
    
    If (action = actInsert) Then
        
        ''&HC0FFC0
        
        frmDataEntry.txtCheckInBy.BackColor = &HFF80FF
        frmDataEntry.txtCheckInBy.Enabled = True
        
        frmDataEntry.dcboCheckInBy.BackColor = &HFF80FF
        frmDataEntry.dcboCheckInBy.Enabled = True
        
        
       
        frmDataEntry.txtTruckCompany.BackColor = &HFF80FF
        frmDataEntry.txtTruckCompany.Enabled = True
       
       
        frmDataEntry.txtDriver.BackColor = &HFF80FF
        frmDataEntry.txtDriver.Enabled = True
   
        frmDataEntry.dcboComm.BackColor = &HFF80FF
        frmDataEntry.dcboComm.Enabled = True
       
        frmDataEntry.lblCommDesc.BackColor = &HFF80FF
       
        frmDataEntry.dcboCust.BackColor = &HFF80FF
        frmDataEntry.dcboCust.Enabled = True
    
        frmDataEntry.dcboVsl.BackColor = &HFF80FF
        frmDataEntry.dcboVsl.Enabled = True
       
       
        frmDataEntry.txtBOL.BackColor = &HFF80FF
        frmDataEntry.txtBOL.Enabled = True
       
       
        frmDataEntry.cboWHSE.BackColor = &HFF80FF
        frmDataEntry.cboWHSE.Enabled = True
      
        frmDataEntry.txtSealNo.BackColor = &HE0E0E0
        frmDataEntry.txtSealNo.Enabled = False
       
        frmDataEntry.txtCheckOutBy.BackColor = &HE0E0E0
        frmDataEntry.txtCheckOutBy.Enabled = False
       
        frmDataEntry.dcboCheckOutBy.BackColor = &HE0E0E0
        frmDataEntry.dcboCheckOutBy.Enabled = False
        
        frmDataEntry.cmdDML.Caption = "Check this Truck In"

    
    ElseIf (action = actUpdate) Then
       
        frmDataEntry.txtCheckInBy.BackColor = vbWhite
        ''frmDataEntry.txtCheckInBy.ForeColor = vbRed
        frmDataEntry.txtCheckInBy.Enabled = False
        
        frmDataEntry.dcboCheckInBy.BackColor = vbWhite
        frmDataEntry.dcboCheckInBy.Enabled = False
       
        frmDataEntry.txtTruckCompany.BackColor = vbWhite
        frmDataEntry.txtTruckCompany.Enabled = False
       
       
        frmDataEntry.txtDriver.BackColor = vbWhite
        frmDataEntry.txtDriver.Enabled = False
   
        frmDataEntry.dcboComm.BackColor = vbWhite
        frmDataEntry.dcboComm.Enabled = False
       
        frmDataEntry.lblCommDesc.BackColor = vbWhite
        
       
        frmDataEntry.dcboCust.BackColor = vbWhite
        frmDataEntry.dcboCust.Enabled = False
    
        frmDataEntry.dcboVsl.BackColor = vbWhite
        frmDataEntry.dcboVsl.Enabled = False
       
       
        frmDataEntry.txtBOL.BackColor = vbWhite
        frmDataEntry.txtBOL.Enabled = False
       
       
        frmDataEntry.cboWHSE.BackColor = vbWhite
        frmDataEntry.cboWHSE.Enabled = False
      
        frmDataEntry.txtSealNo.BackColor = &HFF80FF
        frmDataEntry.txtSealNo.Enabled = True
       
        frmDataEntry.txtCheckOutBy.BackColor = &HFF80FF
        frmDataEntry.txtCheckOutBy.Enabled = True
       
        
        frmDataEntry.dcboCheckOutBy.BackColor = &HFF80FF
        frmDataEntry.dcboCheckOutBy.Enabled = True
        
        
        
        frmDataEntry.txtSealNo.SetFocus
       
        frmDataEntry.cmdDML.Caption = "Check this Truck Out"
   
    
    End If
    
End Sub

Sub CheckTruckOut(RecordID As String, SealNo As String, Operator As String)

On Error GoTo Err_Handler:
 
    Dim OraSession As Object
    Dim OraDataBase As Object
    Dim rs As Object
    Dim strSql As String
    Dim rCount As Integer

    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    
    'Create the OraDatabase Object
    Set OraDataBase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDataBase.LastServerErr = 0 Then
    
        SealNo = "'" & UCase(SealNo) & "'"
        Operator = "'" & Operator & "'"
        
        strSql = "Update TLS_TRUCK_LOG a" & _
            " Set a.TIME_OUT= TO_DATE('" & Now() & "' , 'MM/DD/YYYY HH:MI:SS AM')," & _
            " a.CHECKED_OUT_BY=" & Operator & "," & _
            " a.SEAL_NUM=" & SealNo & _
            " Where a.RECORD_ID =" & RecordID

    
        '' Create Recordset
        rCount = OraDataBase.ExecuteSQL(strSql)
            
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
    

    
    Else
        MsgBox "Error:" & OraDataBase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    Exit Sub
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        "CheckTruckOut()"
        OraSession.Rollback
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
        End
    End If



End Sub

Sub ClearControls()

    frmDataEntry.txtCheckInBy.Text = ""
    frmDataEntry.dcboCheckInBy.Text = ""
    frmDataEntry.txtTruckCompany.Text = ""
    frmDataEntry.txtDriver.Text = ""
    frmDataEntry.dcboComm.Text = ""
    frmDataEntry.lblCommDesc.Caption = ""
    frmDataEntry.dcboCust.Text = ""
    frmDataEntry.dcboVsl.Text = ""
    frmDataEntry.txtBOL.Text = ""
    frmDataEntry.cboWHSE.Text = ""
    frmDataEntry.txtSealNo.Text = ""
    frmDataEntry.txtCheckOutBy.Text = ""
    frmDataEntry.dcboCheckOutBy.Text = ""
    
End Sub

Sub RefreshDateTime()
    frmDataEntry.lblDateTime.Caption = Format(Now(), "mm/dd/yyyy hh:mm:ss AMPM")
 '   frmDataEntry.lblDateTime.Caption = "10/08/2014 10:07:49 AM"

End Sub

Sub RefreshGrid()
    
    frmDataEntry.dcCheckInTruck.Refresh
    frmDataEntry.dcCheckOutTruck.Refresh
    
    frmDataEntry.lblCheckInTruck.Caption = "Count:" & Str(frmDataEntry.dcCheckInTruck.Recordset.RecordCount)
    frmDataEntry.lblCheckOutTruck.Caption = "Count:" & Str(frmDataEntry.dcCheckOutTruck.Recordset.RecordCount)

End Sub

Function ValidateInputs(myAction As Integer) As Boolean

    Dim retValue As Boolean
    
    retValue = False

    If myAction = actInsert Then
    
        If Len(frmDataEntry.dcboCheckInBy.Text) = 0 Then
            MsgBox "Please select a name from 'Check In By' list"
            ValidateInputs = retValue
            Exit Function
        End If
        
        If Len(frmDataEntry.txtTruckCompany.Text) = 0 Then
            MsgBox "Please provide the name of Trucking Company"
            ValidateInputs = retValue
            Exit Function
        End If
        
        If Len(frmDataEntry.txtDriver.Text) = 0 Then
            MsgBox "Please provide driver's name"
            ValidateInputs = retValue
            Exit Function
        End If
        
        If Len(frmDataEntry.dcboComm.Text) = 0 Then
            MsgBox "Please select a commodity from the list"
            ValidateInputs = retValue
            Exit Function
        End If
        
        If Len(frmDataEntry.dcboCust.Text) = 0 Then
            MsgBox "Please select a customer from the list"
            ValidateInputs = retValue
            Exit Function
        End If
        
        If Len(frmDataEntry.dcboVsl.Text) = 0 Then
            MsgBox "Please select a vessel from the list"
            ValidateInputs = retValue
            Exit Function
        End If
        
        If Len(frmDataEntry.txtBOL.Text) = 0 Then
            MsgBox "Please enter a BOL"
            ValidateInputs = retValue
            Exit Function
        End If
        
        If Len(frmDataEntry.cboWHSE.Text) = 0 Then
            MsgBox "Please enter a warehouse location"
            ValidateInputs = retValue
            Exit Function
        End If
        
        retValue = True
        ValidateInputs = retValue
    
    Else
    
        If Len(frmDataEntry.dcboCheckOutBy.Text) = 0 Then
            MsgBox "Please select a name from 'Check Out By' list"
            ValidateInputs = retValue
            Exit Function
        End If
        
        retValue = True
        ValidateInputs = retValue
    
    End If

End Function

Sub RetrieveSingleEntry()
    
    If (frmDataEntry.grdCheckOutTruck.SelBookmarks.count > 0) Then
       
        frmEntryDetail.Show
        Dim varBmk As Variant
    
        For Each varBmk In frmDataEntry.grdCheckOutTruck.SelBookmarks
            
            frmEntryDetail.lblData(0).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(0).Value
            frmEntryDetail.lblData(1).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(1).Value
            frmEntryDetail.lblData(2).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(2).Value
            frmEntryDetail.lblData(3).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(3).Value
            frmEntryDetail.lblData(4).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(4).Value
            frmEntryDetail.lblData(5).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(5).Value
            frmEntryDetail.lblData(6).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(6).Value
            frmEntryDetail.lblData(7).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(7).Value
            frmEntryDetail.lblData(8).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(8).Value
            frmEntryDetail.lblData(9).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(9).Value
            frmEntryDetail.lblData(10).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(10).Value
            frmEntryDetail.lblData(11).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(11).Value
            frmEntryDetail.lblData(12).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(12).Value
            frmEntryDetail.lblData(13).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields(13).Value
            
        Next
        
    
    Else
        MsgBox "Please hilight a record in 'Checked Out Truck' grid to retrieve"
    End If
End Sub

Function GetDailyRowNum() As Long

    On Error GoTo Err_Handler:
 
    Dim OraSession As Object
    Dim OraDataBase As Object
    Dim rs As Object
    Dim strSql As String
    Dim rCount As Integer
    Dim retValue As Long
    
    retValue = -1
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    
    'Create the OraDatabase Object
    Set OraDataBase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDataBase.LastServerErr = 0 Then
    
        strSql = "SELECT MAX(a.DAILY_ROW_NUM) " & _
                " FROM TLS_TRUCK_LOG a" & _
                " WHERE TO_CHAR(a.TIME_IN, 'mm/dd/yyyy')=" & _
                " (select TO_CHAR(sysdate, 'mm/dd/yyyy') from DUAL)"

        
        '' Create Recordset
        Set rs = OraDataBase.CreateDynaset(strSql, 0&)
        
        rs.MoveFirst
        If IsNull(rs.Fields(0).Value) Then
           retValue = 1
        Else
            retValue = rs.Fields(0).Value + 1
        End If
        
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
    
        GetDailyRowNum = retValue
    
    Else
        MsgBox "Error:" & OraDataBase.LastServerErrText & " occured.", vbExclamation, "Logon"
        GetDailyRowNum = retValue
        
    End If
    
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        "GetDailyRowNum()"
        OraSession.Rollback
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
        GetDailyRowNum = retValue
    End If


End Function

Sub EditLogEntry()

    
    If ((frmDataEntry.grdCheckInTruck.SelBookmarks.count + _
        frmDataEntry.grdCheckOutTruck.SelBookmarks.count) = 0) Then
        MsgBox "Please hilight one record to be edited"
        Exit Sub
    End If
    
    If (frmDataEntry.grdCheckInTruck.SelBookmarks.count > 0) And _
        (frmDataEntry.grdCheckOutTruck.SelBookmarks.count > 0) Then
        MsgBox "Multiple records are being hilighted. Only one record can be edited at one time"
        Exit Sub
    End If
        

    If (frmDataEntry.grdCheckInTruck.SelBookmarks.count > 0) Then ' And selGrid = inGrid
        selGrid = inGrid
        ''MsgBox "Edit an entry"
        frmEditEntry.Show
        '' Status
        frmEditEntry.lblStatus.Caption = "Checked In"
        '' Rec ID
        frmEditEntry.lblData(0).Caption = frmDataEntry.dcCheckInTruck.Recordset.Fields("RECORD_ID").Value
        '' Daily ID
        frmEditEntry.lblData(1).Caption = frmDataEntry.dcCheckInTruck.Recordset.Fields("DAILY_ROW_NUM").Value
        '' Time In
        frmEditEntry.lblData(2).Caption = Format(frmDataEntry.dcCheckInTruck.Recordset.Fields("TIME_IN").Value, "HH:MM AMPM")
        '' Time Out
        frmEditEntry.lblData(3).Caption = "--"
        '' Checked In By
        frmEditEntry.lblData(4).Caption = frmDataEntry.dcCheckInTruck.Recordset.Fields("CHECKED_IN_BY").Value
        '' Checked Out By
        frmEditEntry.lblData(5).Caption = "--"
        '' Trucking Company
        frmEditEntry.txtTrkgComp.Text = frmDataEntry.dcCheckInTruck.Recordset.Fields("TRUCKING_COMPANY").Value
        '' Driver
        frmEditEntry.txtDriver.Text = frmDataEntry.dcCheckInTruck.Recordset.Fields("DRIVER_NAME").Value
        '' Commodity
        frmEditEntry.txtCOMM.Text = frmDataEntry.dcCheckInTruck.Recordset.Fields("COMMODITY_CODE").Value
        '' Customer
        frmEditEntry.txtCust.Text = frmDataEntry.dcCheckInTruck.Recordset.Fields("CUSTOMER_CODE").Value
        '' Vessel
        frmEditEntry.txtVsl.Text = frmDataEntry.dcCheckInTruck.Recordset.Fields("LR_NUM").Value
        '' BOL
        frmEditEntry.txtBOL.Text = frmDataEntry.dcCheckInTruck.Recordset.Fields("BOL").Value
        '' Comments
        frmEditEntry.txtComment.Text = "" & frmDataEntry.dcCheckInTruck.Recordset.Fields("COMMENTS").Value
        '' Seal
        frmEditEntry.txtSeal.Text = "--"
        frmEditEntry.txtSeal.Appearance = 0
        frmEditEntry.txtSeal.BorderStyle = 0
        frmEditEntry.txtSeal.BackColor = &HC0C0C0
        frmEditEntry.txtSeal.Locked = True
        '' WHSE
        frmEditEntry.txtWHSE.Text = frmDataEntry.dcCheckInTruck.Recordset.Fields("WAREHOUSE").Value
    End If
    
    If (frmDataEntry.grdCheckOutTruck.SelBookmarks.count > 0) Then ' And selGrid = outGrid
        selGrid = outGrid
        ''MsgBox "Edit an entry"
        frmEditEntry.Show
        '' Status
        frmEditEntry.lblStatus.Caption = "Checked Out"
        '' Rec ID
        frmEditEntry.lblData(0).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields("RECORD_ID").Value
        '' Daily ID
        frmEditEntry.lblData(1).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields("DAILY_ROW_NUM").Value
        '' Time In
        frmEditEntry.lblData(2).Caption = Format(frmDataEntry.dcCheckOutTruck.Recordset.Fields("TIME_IN").Value, "HH:MM AMPM")
        '' Time Out
        frmEditEntry.lblData(3).Caption = Format(frmDataEntry.dcCheckOutTruck.Recordset.Fields("TIME_OUT").Value, "HH:MM AMPM")
        '' Checked In By
        frmEditEntry.lblData(4).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields("CHECKED_IN_BY").Value
        '' Checked Out By
        frmEditEntry.lblData(5).Caption = frmDataEntry.dcCheckOutTruck.Recordset.Fields("CHECKED_OUT_BY").Value
        '' Trucking Company
        frmEditEntry.txtTrkgComp.Text = frmDataEntry.dcCheckOutTruck.Recordset.Fields("TRUCKING_COMPANY").Value
        '' Driver
        frmEditEntry.txtDriver.Text = frmDataEntry.dcCheckOutTruck.Recordset.Fields("DRIVER_NAME").Value
        '' Commodity
        frmEditEntry.txtCOMM.Text = frmDataEntry.dcCheckOutTruck.Recordset.Fields("COMMODITY_CODE").Value
        '' Customer
        frmEditEntry.txtCust.Text = frmDataEntry.dcCheckOutTruck.Recordset.Fields("CUSTOMER_CODE").Value
        '' Vessel
        frmEditEntry.txtVsl.Text = frmDataEntry.dcCheckOutTruck.Recordset.Fields("LR_NUM").Value
        '' BOL
        frmEditEntry.txtBOL.Text = frmDataEntry.dcCheckOutTruck.Recordset.Fields("BOL").Value
        '' Seal
        frmEditEntry.txtSeal.Text = frmDataEntry.dcCheckOutTruck.Recordset.Fields("SEAL_NUM").Value
        '' WHSE
        frmEditEntry.txtWHSE.Text = frmDataEntry.dcCheckOutTruck.Recordset.Fields("WAREHOUSE").Value
        '' Comments
        frmEditEntry.txtComment.Text = "" & frmDataEntry.dcCheckOutTruck.Recordset.Fields("COMMENTS").Value
    End If
End Sub

Sub SaveEntryChanges()

On Error GoTo Err_Handler:
 
    Dim OraSession As Object
    Dim OraDataBase As Object
    Dim rs As Object
    Dim strSql As String
    Dim rCount As Integer

    Dim TruckComp As String
    Dim Driver As String
    Dim Com As String
    Dim Cust As String
    Dim VSL As String
    Dim BOL As String
    Dim Seal As String
    Dim WHSE As String
    Dim Comment As String
    
    Dim RecordID As String    'Rudy
    
    RecordID = frmEditEntry.lblData(0).Caption
    TruckComp = "'" & Trim(frmEditEntry.txtTrkgComp.Text) & "'"
    Driver = "'" & Trim(frmEditEntry.txtDriver.Text) & "'"
    Com = Trim(frmEditEntry.txtCOMM.Text)
    Cust = Trim(frmEditEntry.txtCust.Text)
    VSL = Trim(frmEditEntry.txtVsl.Text)
    BOL = "'" & Trim(frmEditEntry.txtBOL.Text) & "'"
    Seal = "'" & Trim(frmEditEntry.txtSeal.Text) & "'"
    WHSE = "'" & Trim(frmEditEntry.txtWHSE.Text) & "'"
    Comment = "'" & Trim(frmEditEntry.txtComment.Text) & "'"
    
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    
    'Create the OraDatabase Object
    Set OraDataBase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDataBase.LastServerErr = 0 Then
    

        
'        strSql = "Update TLS_TRUCK_LOG a" & _
'            " Set a.TIME_OUT= TO_DATE('" & Now() & "' , 'MM/DD/YYYY HH:MI:SS AM')," & _
'            " a.CHECKED_OUT_BY=" & Operator & "," & _
'            " a.SEAL_NUM=" & SealNo & _
'            " Where a.RECORD_ID =" & RecordID

        If (selGrid = inGrid) Then
        
            strSql = "UPDATE TLS_TRUCK_LOG a" & _
                    " SET a.TRUCKING_COMPANY=" & TruckComp & _
                    " , a.DRIVER_NAME=" & Driver & _
                    " , a.COMMODITY_CODE=" & Com & _
                    " , a.CUSTOMER_CODE=" & Cust & _
                    " , a.LR_NUM=" & VSL & _
                    " , a.BOL=" & BOL & _
                    " , a.WAREHOUSE=" & WHSE & _
                    " , a.COMMENTS=" & Comment & _
                    " WHERE a.RECORD_ID =" & RecordID
        
        ElseIf (selGrid = outGrid) Then
        
            strSql = "UPDATE TLS_TRUCK_LOG a" & _
                    " SET a.TRUCKING_COMPANY=" & TruckComp & _
                    " , a.DRIVER_NAME=" & Driver & _
                    " , a.COMMODITY_CODE=" & Com & _
                    " , a.CUSTOMER_CODE=" & Cust & _
                    " , a.LR_NUM=" & VSL & _
                    " , a.BOL=" & BOL & _
                    " , a.SEAL_NUM=" & Seal & _
                    " , a.WAREHOUSE=" & WHSE & _
                    " , a.COMMENTS=" & Comment & _
                    " WHERE a.RECORD_ID =" & RecordID
        
        End If

    
        '' Create Recordset
        rCount = OraDataBase.ExecuteSQL(strSql)
            
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
    

    
    Else
        MsgBox "Error:" & OraDataBase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    Exit Sub
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        "SaveEntryChanges()"
        OraSession.Rollback
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
        End
    End If

End Sub

'Rudy make them funcs, so know ran properly
'Sub RetrieveCommList()
Function RetrieveCommList() As Boolean

On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDataBase As Object
    Dim rs As Object
    Dim strSql As String
    Dim i As Integer
    RetrieveCommList = False      'rudy
    
    strStatus = "RetrieveCommList Set OraSession = CreateObject(OracleInProcServer.XOraSession) next"    'Rudy
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    
    strStatus = "RetrieveCommList Set OraDataBase = OraSession.OpenDatabase(DB, Login, 0&) next"    'Rudy
    'Create the OraDatabase Object
    Set OraDataBase = OraSession.OpenDatabase(DB, Login, 0&)
        
        
    If OraDataBase.LastServerErr = 0 Then
        
        strStatus = "RetrieveCommList If OraDataBase.LastServerErr = 0 prior"    'Rudy
        '' Prepare sql statement
        strSql = "select * from TLS_COMMODITY_PROFILE"
                
        '' Create Recordset
        Set rs = OraDataBase.CreateDynaset(strSql, 0&)
        
        If rs.RecordCount = 0 Then
            
            Err.Raise 1004, , "No Commodity Record Found"
        Else
        
            '' Redim the array
            ReDim arrCommodity(0 To rs.RecordCount - 1)
            rs.MoveFirst
            For i = 0 To rs.RecordCount - 1
                arrCommodity(i).ID = rs.Fields("COMMODITY_CODE").Value
                arrCommodity(i).Desc = rs.Fields("COMMODITY_NAME").Value
                arrCommodity(i).Group = rs.Fields("COMMODITY_GROUP").Value
                rs.MoveNext
            Next i
              
        End If
        
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
    
    
    Else
        strStatus = "RetrieveCommList If OraDataBase.LastServerErr = 0 ELSE IF prior"    'Rudy
        MsgBox "Error:" & OraDataBase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    strStatus = "RetrieveCommList exit sub normally next"    'Rudy
    RetrieveCommList = True      'rudy
    Exit Function
Err_Handler:
    strStatus = "RetrieveCommList in error handler now"    'Rudy
    If OraDataBase.LastServerErr <> 0 Then
      strStatus = strStatus & "Oracle error number: " & OraDataBase.LastServerErr & ", Oracle error text: " & OraDataBase.LastServerErrText
      OraDataBase.LastServerErrReset
    End If
    If Err.Number <> 0 Then
'        MsgBox Err.Description & " occurred in " & App.Title & "." & _
'        "RetrieveCommList"
        MsgBox Err.Description & " occurred in " & App.Title & "." & "RetrieveCommList.  " & strStatus
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
        End
    End If
End Function

'Rudy make them funcs, so know ran properly
'Sub RetrieveCommGrpDef()
Function RetrieveCommGrpDef() As Boolean

On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDataBase As Object
    Dim rs As Object
    Dim strSql As String
    Dim i As Integer
    RetrieveCommGrpDef = False      'rudy
    
    strStatus = "RetrieveCommGrpDef Set OraSession = CreateObject(OracleInProcServer.XOraSession) next"    'Rudy
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    
    strStatus = "RetrieveCommGrpDef Set OraDataBase = OraSession.OpenDatabase(DB, Login, 0&) next"    'Rudy
    'Create the OraDatabase Object
    Set OraDataBase = OraSession.OpenDatabase(DB, Login, 0&)
        
    
    If OraDataBase.LastServerErr = 0 Then
        strStatus = "RetrieveCommGrpDef If OraDataBase.LastServerErr = 0 prior"    'Rudy
        '' Prepare sql statement
        strSql = "select * from TLS_COMMODITY_GROUP"
                
        '' Create Recordset
        Set rs = OraDataBase.CreateDynaset(strSql, 0&)
        
        
        If rs.RecordCount = 0 Then
            Err.Raise 1002, , "No Commodity Group Record Found"
        Else
            
            '' DeDim the size of array
            ReDim arrComGrpDef(0 To rs.RecordCount - 1)
            
            rs.MoveFirst
            For i = 0 To rs.RecordCount - 1
                arrComGrpDef(i).ID = rs.Fields("COMMODITY_GROUP_ID").Value
                arrComGrpDef(i).GrpDesc = rs.Fields("COMMODITY_GROUP_NAME").Value
                rs.MoveNext
            Next i
            
            '' Set variables to Nothing
            Set OraSession = Nothing
            Set OraDataBase = Nothing
            Set rs = Nothing
       
        End If
    Else
        strStatus = "RetrieveCommGrpDef If OraDataBase.LastServerErr = 0, the else"    'Rudy
        MsgBox "Error:" & OraDataBase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    strStatus = "RetrieveCommGrpDef exit sub normally next"    'Rudy
    RetrieveCommGrpDef = True      'rudy
    Exit Function
Err_Handler:
    strStatus = "RetrieveCommGrpDef in error handler now"    'Rudy
    If OraDataBase.LastServerErr <> 0 Then
      strStatus = strStatus & "Oracle error number: " & OraDataBase.LastServerErr & ", Oracle error text: " & OraDataBase.LastServerErrText
      OraDataBase.LastServerErrReset
    End If
    If Err.Number <> 0 Then
'        MsgBox Err.Description & " occurred in " & App.Title & "." & _
'        "Retrieve CommList"
        'Frank, could be erroring here, 2 Retrieve CommList in project
        MsgBox Err.Description & " occurred in " & App.Title & "." & "RetrieveCommGrpDef.  " & strStatus
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set rs = Nothing
        End
    End If
    
End Function

Function getComDesc(CommNum As Integer) As String

    Dim i As Integer
    Dim retVal As String
    
    retVal = "UNKNOWN"
        
    For i = 0 To UBound(arrCommodity)
    
        If (arrCommodity(i).ID = CommNum) Then
        
            retVal = arrCommodity(i).Desc
            Exit For
        
        End If
    
    Next i

    getComDesc = retVal


End Function


Sub ApplyFilters(TargetOption As Integer)

    Dim CommFilter As String
    Dim CustFilter As String
    
    If (frmFilterSetting.optFilterOn.Value) And (frmFilterSetting.chkByCommodity.Value = 0) And (frmFilterSetting.chkByCustomer.Value = 0) Then
        MsgBox "Please select at least one filter criterion"
        Exit Sub
    End If
    
    
    If frmFilterSetting.optFilterOff.Value Then  '' turn off filter
        
        If TargetOption = optChkInGrd Then
            strCommFilterIn = "ALL"
            strCustFilterIn = "ALL"
            frmDataEntry.lblCommFilterIn.Caption = strCommFilterIn
            frmDataEntry.lblCustFilterIn.Caption = strCustFilterIn
            frmDataEntry.dcCheckInTruck.RecordSource = ChkInNFSQL
        End If
        
        If TargetOption = optChkOutGrd Then
            strCommFilterOut = "ALL"
            strCustFilterOut = "ALL"
            frmDataEntry.lblCommFilterOut.Caption = strCommFilterOut
            frmDataEntry.lblCustFilterOut.Caption = strCustFilterOut
            frmDataEntry.dcCheckOutTruck.RecordSource = ChkOutNFSQL
        End If
        
       If TargetOption = optBothGrd Then
            strCommFilterIn = "ALL"
            strCustFilterIn = "ALL"
            frmDataEntry.lblCommFilterIn.Caption = strCommFilterIn
            frmDataEntry.lblCustFilterIn.Caption = strCustFilterIn
            frmDataEntry.dcCheckInTruck.RecordSource = ChkInNFSQL
            strCommFilterOut = "ALL"
            strCustFilterOut = "ALL"
            frmDataEntry.lblCommFilterOut.Caption = strCommFilterOut
            frmDataEntry.lblCustFilterOut.Caption = strCustFilterOut
            frmDataEntry.dcCheckOutTruck.RecordSource = ChkOutNFSQL
        End If
        
        
        
        
        
    ElseIf frmFilterSetting.optFilterOn.Value Then '' filter on
        
        If frmFilterSetting.chkByCommodity.Value = 0 Then '' NOT by commodity group
            
            If TargetOption = optChkInGrd Then '' chk-in
                strCommFilterIn = "ALL"
                frmDataEntry.lblCommFilterIn.Caption = strCommFilterIn
                CommFilter = ""
            End If
            
            If TargetOption = optChkOutGrd Then '' chk-out
                strCommFilterOut = "ALL"
                frmDataEntry.lblCommFilterOut.Caption = strCommFilterOut
                CommFilter = ""
            End If
            
            If TargetOption = optBothGrd Then
                strCommFilterIn = "ALL"
                frmDataEntry.lblCommFilterIn.Caption = strCommFilterIn
                CommFilter = ""
                strCommFilterOut = "ALL"
                frmDataEntry.lblCommFilterOut.Caption = strCommFilterOut
                CommFilter = ""
            End If
        
        
        
        End If
        
        If frmFilterSetting.chkByCommodity.Value = 1 Then   '' by commodity group
            
            If TargetOption = optChkInGrd Then '' chk-in
                strCommFilterIn = UCase(frmFilterSetting.cboComGrp.Text)
                frmDataEntry.lblCommFilterIn.Caption = strCommFilterIn
            End If
            
            If TargetOption = optChkOutGrd Then '' chk-out
                strCommFilterOut = UCase(frmFilterSetting.cboComGrp.Text)
                frmDataEntry.lblCommFilterOut.Caption = strCommFilterOut
            End If
            
            
            If TargetOption = optBothGrd Then
                strCommFilterIn = UCase(frmFilterSetting.cboComGrp.Text)
                frmDataEntry.lblCommFilterIn.Caption = strCommFilterIn
                strCommFilterOut = UCase(frmFilterSetting.cboComGrp.Text)
                frmDataEntry.lblCommFilterOut.Caption = strCommFilterOut
            End If
            
            
            
            
            Dim GrpID As Integer
            
            GrpID = GetGroupID(Trim(frmFilterSetting.cboComGrp.Text))
            
            If (GrpID < 0) Then
                MsgBox "Commodity Group:" & frmFilterSetting.cboComGrp.Text & " Not Defined"
                Exit Sub
            Else
                CommFilter = "AND B.COMMODITY_GROUP=" & Str(GrpID)
            End If
        
        End If
        
        If frmFilterSetting.chkByCustomer.Value = 0 Then  '' NOT by customer
            
            If TargetOption = optChkInGrd Then '' chk-in
                strCustFilterIn = "ALL"
                frmDataEntry.lblCustFilterIn.Caption = strCustFilterIn
                CustFilter = ""
            End If
            
            If TargetOption = optChkOutGrd Then '' chk-out
                strCustFilterOut = "ALL"
                frmDataEntry.lblCustFilterOut.Caption = strCustFilterOut
                CustFilter = ""
            End If
            
            If TargetOption = optBothGrd Then
                strCustFilterIn = "ALL"
                frmDataEntry.lblCustFilterIn.Caption = strCustFilterIn
                CustFilter = ""
                strCustFilterOut = "ALL"
                frmDataEntry.lblCustFilterOut.Caption = strCustFilterOut
                CustFilter = ""
            
            End If
        
        End If
        
        If frmFilterSetting.chkByCustomer.Value = 1 Then '' by customer
            
            If TargetOption = optChkInGrd Then '' chk-in
                strCustFilterIn = UCase(frmFilterSetting.dcboCust.Text)
                frmDataEntry.lblCustFilterIn.Caption = strCustFilterIn
            End If
            
            If TargetOption = optChkOutGrd Then '' chk-out
                strCustFilterOut = UCase(frmFilterSetting.dcboCust.Text)
                frmDataEntry.lblCustFilterOut.Caption = strCustFilterOut
            End If
            
            If TargetOption = optBothGrd Then
                strCustFilterIn = UCase(frmFilterSetting.dcboCust.Text)
                frmDataEntry.lblCustFilterIn.Caption = strCustFilterIn
                strCustFilterOut = UCase(frmFilterSetting.dcboCust.Text)
                frmDataEntry.lblCustFilterOut.Caption = strCustFilterOut
            End If
            
            CustFilter = " AND A.CUSTOMER_CODE=" & frmFilterSetting.dcboCust.Text
        
        End If
            
        Dim tmp As String
        Dim arrTmp() As String
    
        
        If TargetOption = optChkInGrd Then '' chk-in
            arrTmp = Split(UCase(ChkInNFSQL), "ORDER BY")
            tmp = arrTmp(0) & CommFilter & CustFilter & " ORDER BY " & arrTmp(1)
            frmDataEntry.dcCheckInTruck.RecordSource = tmp
       
        End If
        
        If TargetOption = optChkOutGrd Then '' chk-out
            arrTmp = Split(UCase(ChkOutNFSQL), "ORDER BY")
            tmp = arrTmp(0) & CommFilter & CustFilter & " ORDER BY " & arrTmp(1)
            frmDataEntry.dcCheckOutTruck.RecordSource = tmp
        
        End If
    
    
        If TargetOption = optBothGrd Then
            arrTmp = Split(UCase(ChkInNFSQL), "ORDER BY")
            tmp = arrTmp(0) & CommFilter & CustFilter & " ORDER BY " & arrTmp(1)
            frmDataEntry.dcCheckInTruck.RecordSource = tmp
            arrTmp = Split(UCase(ChkOutNFSQL), "ORDER BY")
            tmp = arrTmp(0) & CommFilter & CustFilter & " ORDER BY " & arrTmp(1)
            frmDataEntry.dcCheckOutTruck.RecordSource = tmp
    
        End If
    
    
    
    
    
    
    End If
    
    
    '' Refresh grids
    If TargetOption = optChkInGrd Then '' chk-in
         frmDataEntry.dcCheckInTruck.Refresh
         frmDataEntry.grdCheckInTruck.Refresh
        frmDataEntry.lblCheckInTruck.Caption = "Count:" & Str(frmDataEntry.dcCheckInTruck.Recordset.RecordCount)
   
    End If
     
    If TargetOption = optChkOutGrd Then '' chk-out
        frmDataEntry.dcCheckOutTruck.Refresh
        frmDataEntry.grdCheckOutTruck.Refresh
        frmDataEntry.lblCheckOutTruck.Caption = "Count:" & Str(frmDataEntry.dcCheckOutTruck.Recordset.RecordCount)
     
    End If
    
    If TargetOption = optBothGrd Then
         frmDataEntry.dcCheckInTruck.Refresh
         frmDataEntry.grdCheckInTruck.Refresh
        frmDataEntry.lblCheckInTruck.Caption = "Count:" & Str(frmDataEntry.dcCheckInTruck.Recordset.RecordCount)
        frmDataEntry.dcCheckOutTruck.Refresh
        frmDataEntry.grdCheckOutTruck.Refresh
        frmDataEntry.lblCheckOutTruck.Caption = "Count:" & Str(frmDataEntry.dcCheckOutTruck.Recordset.RecordCount)
    
    
    End If

   

End Sub

Function GetGroupID(GroupDesc As String) As Integer

Dim i As Integer
Dim retVal As Integer

    retVal = -1
    
    For i = 0 To UBound(arrComGrpDef)
    
        If UCase(arrComGrpDef(i).GrpDesc) = UCase(GroupDesc) Then
            retVal = Int(Val(arrComGrpDef(i).ID))
            Exit For
        End If
    Next i

    GetGroupID = retVal

End Function


Sub PrtDailySummaryByGrp(myDate As String)

On Error GoTo ErrHandler:
    
    Dim OraSession As Object
    Dim OraDataBase As Object
    Dim ds As Object
    Dim sqlStmt As String
    Dim gsSqlStmt As String         'Rudy
    Dim count As Integer
    Dim strTextLine As String
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    
    'Create the OraDatabase Object
    Set OraDataBase = OraSession.OpenDatabase(DB, Login, 0&)
    
    
    gsSqlStmt = "SELECT B.COMMODITY_GROUP, C.COMMODITY_GROUP_NAME, count(a.TIME_IN) NUM_OF_TRUCK, TRUNC(SUM((A.TIME_OUT-A.TIME_IN)*24*60)/COUNT(A.TIME_IN),0) AVERAGE_TURN_TIME " & _
                " FROM TLS_TRUCK_LOG A, TLS_COMMODITY_PROFILE B, TLS_COMMODITY_GROUP C" & _
                " Where A.TIME_IN <> A.TIME_OUT" & _
                " AND A.COMMODITY_CODE=B.COMMODITY_CODE" & _
                " AND B.COMMODITY_GROUP=C.COMMODITY_GROUP_ID" & _
                " AND TO_CHAR(a.TIME_IN, 'mm/dd/yyyy')= '" & myDate & "'" & _
                " GROUP BY B.COMMODITY_GROUP, C.COMMODITY_GROUP_NAME" & _
                " ORDER BY B.COMMODITY_GROUP"
    Set ds = OraDataBase.DbCreateDynaset(gsSqlStmt, 0&)
    
    If (OraDataBase.LastServerErr = 0) Then
        If (ds.RecordCount = 0) Then
            MsgBox "No Truck Log Found for " & myDate
            Exit Sub
        Else
            '' Start Printing
            Printer.FontBold = True
            Printer.FontName = "COURIER NEW"
            Printer.Orientation = vbPRORLandscape
            ''Printer.PaperSize = vbPRPSLetter
            ''Printer.PaperBin = 7
            
            Dim ict As Integer        'rudy
            For ict = 0 To 3
                Printer.Print ""
            Next ict
            
            'Print the Heading
            Printer.FontBold = True
            Printer.FontSize = 14
            Printer.Print Tab(35); "PORT OF WILMINGTON"
            Printer.FontSize = 12
            Printer.Print Tab(34); "DAILY TRUCK LOG SUMMARY REPORT"
            Printer.Print Tab(46); myDate
            Printer.Print ""
            Printer.FontSize = 12
            Printer.FontBold = False
            Printer.Print Tab(12); fmtString("COMMODITY GROUP") & fmtString("TRUCK COUNT") & fmtString("AVERAGE TURNTIME (MINUTES)")
            Printer.Print Tab(12); "---------------------------------------------------------------------------------------"
            ''Printer.Print Tab(12); fmtString("ARG JUICE") & fmtString("9999") & fmtString("999")
            ds.MoveFirst
            For count = 0 To ds.RecordCount - 1
                strTextLine = fmtString(ds.Fields("COMMODITY_GROUP_NAME").Value) & fmtString(ds.Fields("NUM_OF_TRUCK").Value) & fmtString(ds.Fields("AVERAGE_TURN_TIME").Value)
                Printer.Print Tab(12); strTextLine
                ds.MoveNext
            Next count
            Printer.Print Tab(12); "---------------------------------------------------------------------------------------"
            Printer.Print ""
            Printer.Print Tab(12); "Printed on:" & Now()
            Printer.EndDoc
        
        End If
        
    
    Else
    
        
    End If
    
    
    
ErrHandler:
    
    If Err.Number <> 0 Then
        MsgBox Err.Number & " " & Err.Description
        Set OraSession = Nothing
        Set OraDataBase = Nothing
        Set ds = Nothing
        Exit Sub
    End If
    

End Sub


Function fmtString(oldString As String) As String

    fmtString = Format(oldString, "!@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")

End Function

