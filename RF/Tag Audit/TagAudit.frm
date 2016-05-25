VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmTagAudit 
   Caption         =   "Tag Audit"
   ClientHeight    =   7065
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   9885
   BeginProperty Font 
      Name            =   "Times New Roman"
      Size            =   9
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   ScaleHeight     =   7065
   ScaleWidth      =   9885
   StartUpPosition =   3  'Windows Default
   Begin VB.ComboBox cmbCommodity 
      Height          =   345
      Left            =   1800
      TabIndex        =   8
      Top             =   720
      Width           =   2295
   End
   Begin VB.ComboBox Season 
      Height          =   345
      ItemData        =   "TagAudit.frx":0000
      Left            =   6000
      List            =   "TagAudit.frx":0002
      TabIndex        =   7
      Top             =   240
      Width           =   1695
   End
   Begin VB.TextBox txtPltId 
      Height          =   285
      Left            =   1800
      TabIndex        =   1
      Top             =   240
      Width           =   2295
   End
   Begin VB.CommandButton cmdRetrieve 
      Caption         =   "Retrieve"
      Height          =   375
      Left            =   7920
      TabIndex        =   2
      Top             =   240
      Width           =   1455
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   4575
      Left            =   0
      TabIndex        =   0
      Top             =   1920
      Width           =   9660
      _Version        =   196616
      DataMode        =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Col.Count       =   4
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   0
      ForeColorOdd    =   8388608
      RowHeight       =   503
      Columns.Count   =   4
      Columns(0).Width=   4445
      Columns(0).Caption=   "BARCODE"
      Columns(0).Name =   "BARCODE"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   3200
      Columns(1).Caption=   "CUSTOMER"
      Columns(1).Name =   "CUSTOMER"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   4445
      Columns(2).Caption=   "SHIP/TRUCKIN"
      Columns(2).Name =   "SHIP/TRUCKIN"
      Columns(2).Alignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   3916
      Columns(3).Caption=   "DATE RECEIVED"
      Columns(3).Name =   "DATE RECEIVED"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      _ExtentX        =   17039
      _ExtentY        =   8070
      _StockProps     =   79
      Caption         =   "LIST OF PALLETS"
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.Label Label3 
      Alignment       =   1  'Right Justify
      Caption         =   "Commodity:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   11.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   240
      TabIndex        =   9
      Top             =   720
      Width           =   1335
   End
   Begin VB.Label Label2 
      Alignment       =   2  'Center
      Caption         =   "Season:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   11.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   4320
      TabIndex        =   6
      Top             =   240
      Width           =   1455
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   375
      Left            =   0
      TabIndex        =   5
      Top             =   6600
      Width           =   9735
   End
   Begin VB.Label Label6 
      Caption         =   "Click Particular Pallet Below  For Printing Details"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   2640
      TabIndex        =   4
      Top             =   1560
      Width           =   5175
   End
   Begin VB.Label Label1 
      Caption         =   "Barcode:"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   11.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   720
      TabIndex        =   3
      Top             =   240
      Width           =   975
   End
End
Attribute VB_Name = "frmTagAudit"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
    Dim currentRow As Long
    Dim iRecordTotal As Long
    Dim iQtyChange As Double
    Dim iQtyReceived As Double
    Dim sQtyExpc As String
    Dim iQtyChangeTotal As Double
    Dim iTotalLeft As Double
    Dim iTodayTotalLeft As Double
    Dim iRowNum As Long
    Dim iLotNumC As String
    Dim iWeight As Single
    Dim iRowNumP As Long
    Dim iCustPId As Long
    Dim iCustCId As Long
    Dim iLotNumP As String
    Dim iOwnerId As Long
    Dim iCustName As String
    Dim iVesselName As String
    Dim myDate As String
    Dim iPosition As Integer
    Dim iTime As String
    Dim iLineNum As Long
    Dim iPageNum As Long
    Dim iPalletCount As Long
    Dim iPalletCountTotal As Long
    Dim iCommName As String
    Dim iEmpName As String
    Dim iShipNumP As Long
    Dim iShipNumC As Long
    Dim iCommP As Long
    Dim iCommC As Long
    Dim iQtyRcvd As Long
    Dim iRcver As String

Dim iShipOrder As String
Dim iPltId As String
Dim iArrivalNum As String
Dim iCust As String
Dim SqlStmt As String
Dim iRec As Integer
Dim iPos As Integer

Sub ClearScreen()
    grdData.MoveFirst
    grdData.RemoveAll
    
'    txtDescription.Text = ""
'    chkCareOf.Value = 1 'Checked
'    txtLRNum = ""
'    txtVesselName = ""
'    txtCustomerId = ""
'    txtCustomerName = ""
'    grdData.RowHeight = 300
'    txtTotAmt = "0.00"
End Sub

Private Sub cmdClear_Click()
    Call ClearScreen
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub

Private Sub cmdRetrieve_Click()
    Dim iShipOrder As String
    Dim iArrivalNum As String
    Dim iCust As String
    
    
    'GET INFORMATION FROM CARGO_ACTIVITY
    grdData.RemoveAll
    
    If Len(Season.Text) = 0 Then
        MsgBox "Please Select a Year"
        Exit Sub
    End If
    
    
    If Season.Text = Format(DateAdd("m", 4, Now()), "yyyy") Then
        TableName = "CARGO_TRACKING"
        CA_TableName = "CARGO_ACTIVITY"
    Else
        TableName = "CARGO_TRACKING_" & Season.Text
        CA_TableName = "CARGO_ACTIVITY_" & Season.Text
    End If
    
    
    If Trim$(txtPltId.Text) <> "" Then
        SqlStmt = " SELECT * FROM " & TableName & " WHERE PALLET_ID LIKE '%" & Trim$(txtPltId.Text) & "%'"
        SqlStmt = SqlStmt & " AND COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE = '" & cmbCommodity.Text & "')"
        SqlStmt = SqlStmt & " ORDER BY PALLET_ID,RECEIVER_ID,ARRIVAL_NUM"
        Set dsCARGO_TRACKING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        If dsCARGO_TRACKING.RecordCount > 0 Then
            While Not dsCARGO_TRACKING.EOF
                SqlStmt = " SELECT * FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '" & Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value) & "'"
                Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                If dsVESSEL_PROFILE.RecordCount > 0 Then
                    iShipOrder = Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value) & "-" & (dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value)
                Else
                    iShipOrder = Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value) & "-" & "Truck In Cargo"
                End If
                
                grdData.AddItem Trim$(dsCARGO_TRACKING.Fields("PALLET_ID").Value) + Chr(9) + _
                                        Trim$(dsCARGO_TRACKING.Fields("RECEIVER_ID").Value) + Chr(9) + _
                                        Trim$(iShipOrder) + Chr(9) + _
                                        Format$(dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value, "MM/DD/YYYY")
                grdData.Refresh
            dsCARGO_TRACKING.MoveNext
            Wend
        Else
            MsgBox "NO SUCH PALLET. TRY AGAIN.", vbInformation, "PALLET ID"
            txtPltId.SetFocus
            txtPltId.Text = ""
            Exit Sub
        End If
    Else
        MsgBox "PALLET FIELD IS NULL.", vbInformation, "PALLET ID"
        txtPltId.SetFocus
        Exit Sub
    End If
    
End Sub


Private Sub Form_Load()
   
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    lblStatus.Caption = "Form Load Successfully."
    lblStatus.Refresh
    
    Call PopulateSeasons
    Call PopulateCommodity
    Call ClearScreen
End Sub

Private Sub grdData_Click()
    Dim Passthru As String
    'GET ORDER NUM
    'grdData.SelectByCell
    iPos = 0
    iPltId = Trim$(grdData.Columns(0).Value)
    iCust = grdData.Columns(1).Value
    iArrivalNum = Trim$(grdData.Columns(2).Value)
    iPos = InStr(1, iArrivalNum, "-")
    iArrivalNum = Mid$(iArrivalNum, 1, iPos - 1)
'    Call PrintDetail
'    Passthru = "http://intranet/finance/rf/reporting/tag_audit_print.php?pallet_id=" & iPltId & "\&ves=" & iArrivalNum & "\&cust=" & iCust
    Shell ("cmd /c start http://intranet/finance/rf/reporting/tag_audit_print.php?pallet_id=" & iPltId & "^&ves=" & iArrivalNum & "^&cust=" & iCust & "^&season=" & Season.Text)
End Sub

Private Sub PrintDetail()

bIsQC = False

    gsSqlStmt = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_AUDIT WHERE PALLET_ID = '" & iPltId & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(iCust)
    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM  = '" & iArrivalNum & "'"
    gsSqlStmt = gsSqlStmt & " AND WAREHOUSE_LOCATION LIKE '%QC%'"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value > 0 Then
        bIsQC = True
    End If
    
    

    Printer.Orientation = 2
    'iPageNum = 1
'    iPosition = 0
'    iPosition = InStr(1, Trim$(txtPlt.Text), "%")
'    If iPosition <> 0 Then
'        gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM like '" & Trim$(txtPlt.Text) & "'"
'    Else
'        gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & Trim$(txtPlt.Text) & "'"
'    End If
'
    lblStatus.Caption = "Printing Pallet " & iPltId & " - " & iCust & " - " & iArrivalNum & "..."
    lblStatus.Refresh
    
    gsSqlStmt = "SELECT * FROM " & TableName & " WHERE PALLET_ID = '" & iPltId & "'"
    gsSqlStmt = gsSqlStmt & " AND RECEIVER_ID = " & Val(iCust)
    gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM  = '" & iArrivalNum & "'"
    Set dsCARGO_TRACKING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.RecordCount > 0 Then
    
        'get receiver
        ' EDIT Adam Walter, Jan 2010.
        ' The recent re-write of the entire Chilean scanner program necessitates a new way to determine this.
'        gsSqlStmt = "SELECT * FROM ACTIVITY_LOG WHERE PALLET_ID = '" & iPltId & "'"
'        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(iCust)
'        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM  = '" & iArrivalNum & "'"
'        gsSqlStmt = gsSqlStmt & " AND TRANSACTION_ID IN ('SR','SRC','SRX','SRD','TR','TRC','TRX','TFF','TFX')"
'        Set dsPERSONNEL_CHECK = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'        If dsPERSONNEL_CHECK.RecordCount > 0 Then
'            If Not IsNull(dsPERSONNEL_CHECK.Fields("CHECKER_NAME")) Then
'                iRcver = Trim$(dsPERSONNEL_CHECK.Fields("CHECKER_NAME").Value)
'            Else
'                iRcver = ""
'            End If
'        Else
'            iRcver = ""
'        End If
        iRcver = get_checker_name(iPltId, Val(iCust), iArrivalNum, "1")
'        gsSqlStmt = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = "
'        gsSqlStmt = gsSqlStmt & " (SELECT ACTIVITY_ID FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & iPltId & "'"
'        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(iCust)
'        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM  = '" & iArrivalNum & "'"
'        gsSqlStmt = gsSqlStmt & " AND ACTIVITY_NUM = '1')"
'        Set dsPERSONNEL_CHECK = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'        If dsPERSONNEL_CHECK.RecordCount > 0 Then
'            If Not IsNull(dsPERSONNEL_CHECK.Fields("LOGIN_ID")) Then
'                iRcver = Trim$(dsPERSONNEL_CHECK.Fields("LOGIN_ID").Value)
'            Else
'                iRcver = ""
'            End If
'        Else
'            iRcver = ""
'        End If
        
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '" & Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value) & "'"
        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If dsVESSEL_PROFILE.RecordCount > 0 Then
            iVesselName = dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value
        Else
            iVesselName = "Truck In" & "-" & Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value)
        End If
        
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID  = " & dsCARGO_TRACKING.Fields("RECEIVER_ID").Value
        Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        iCustName = Trim$(dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value)
                
        SqlStmt = "SELECT * FROM CARGO_TRACKING_ADDITIONAL_DATA WHERE pallet_id ='" & iPltId & "' AND ARRIVAL_NUM = '" & Trim$(dsCARGO_TRACKING.Fields("ARRIVAL_NUM").Value) & "' AND RECEIVER_ID = '" & dsCARGO_TRACKING.Fields("RECEIVER_ID").Value & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        If dsSHORT_TERM_DATA.RecordCount > 0 Then
          sQtyExpc = dsSHORT_TERM_DATA.Fields("QTY_EXPECTED").Value
        Else
          sQtyExpc = "N/A"
        End If
                
                
        gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE  = " & Val(Trim$(dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value))
        Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If Not IsNull(dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value) Then
            iCommName = dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value
        Else
            iCommName = "Unknown"
        End If
        
        gsSqlStmt = "SELECT * FROM " & CA_TableName & " WHERE PALLET_ID = '" & iPltId & "'"
        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & Val(iCust)
        gsSqlStmt = gsSqlStmt & " AND ARRIVAL_NUM  = '" & iArrivalNum & "'"
        gsSqlStmt = gsSqlStmt & " ORDER BY ACTIVITY_NUM"
        
        Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.RecordCount > 0 Then
            
            iQtyRcvd = dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
            Call printPageTitle
            While Not dsCARGO_ACTIVITY.EOF
                If dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value <> 1 Then
                    iEmpName = get_checker_name(iPltId, Val(iCust), iArrivalNum, CStr(dsCARGO_ACTIVITY.Fields("ACTIVITY_NUM").Value))
'                    gsSqlStmt = "SELECT * FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID  = " & dsCARGO_ACTIVITY.Fields("ACTIVITY_ID").Value
'                    Set dsPERSONNEL = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'                    If Not IsNull(dsPERSONNEL.Fields("LOGIN_ID").Value) Then
'                        iEmpName = dsPERSONNEL.Fields("LOGIN_ID").Value
'                    Else
'                        iEmpName = dsCARGO_ACTIVITY.Fields("ACTIVITY_ID").Value
'                    End If
                    
                    gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE  = " & dsCARGO_ACTIVITY.Fields("SERVICE_CODE").Value
                    Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                    Printer.Print Tab(2); dsCARGO_ACTIVITY.Fields("ORDER_NUM").Value; Tab(30); dsCARGO_ACTIVITY.Fields("DATE_OF_ACTIVITY").Value; Tab(60); dsCARGO_ACTIVITY.Fields("CUSTOMER_ID").Value; Tab(83); dsCARGO_ACTIVITY.Fields("QTY_CHANGE").Value; Tab(110); dsCARGO_ACTIVITY.Fields("ACTIVITY_NUM").Value; Tab(130); dsSERVICE_CATEGORY.Fields("SERVICE_NAME").Value; Tab(150); iEmpName
                End If
                dsCARGO_ACTIVITY.MoveNext
            Wend
        
        Else
            If Not IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED")) Then
                iQtyRcvd = dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value
            Else
                iQtyRcvd = 0
            End If
            Call printPageTitle
            Printer.Print Tab(2); "NO ACTIVITY"
        End If
        
        
        Printer.EndDoc
        lblStatus.Caption = "Printing Pallet " & iPltId & " - " & iCust & " - " & iArrivalNum & " Done!"
        lblStatus.Refresh
        Exit Sub
    
    End If
    
      
End Sub
Private Sub printPageTitle()
    Printer.FontSize = 18
    Printer.Print Tab(2); " ";
    Printer.Print Tab(2); " ";
    Printer.Print Tab(2); " ";
    
    Printer.Print Tab(40); " TAG AUDIT"
    Printer.FontSize = 12
    'Printer.Print Tab(80); "By Date"
    Printer.Print Tab(50); "";
    Printer.Print Tab(10); "Pallet Num: " & iPltId; Tab(45); "Date Received: " & dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value; Tab(90); "Commodity: " & iCommName; Tab(120); "Printed On: " & Date
    Printer.Print Tab(50); ""
    If iQtyRcvd = 0 Then
        Printer.Print Tab(2); "Owner: " & dsCARGO_TRACKING.Fields("RECEIVER_ID").Value & " " & iCustName
        Printer.Print Tab(2); "Ship: " & iVesselName; Tab(50); "QTY Rcvd: " & iQtyRcvd; Tab(70); "QTY Dmgd: " & dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value; Tab(90); "QTY Expected: " & sQtyExpc; Tab(120); "QTY in House: 0"
        If iRcver <> "" Then
            Printer.Print Tab(2); "Receiver: " & iRcver
        End If
    Else
        Printer.Print Tab(2); "Owner: " & dsCARGO_TRACKING.Fields("RECEIVER_ID").Value & " " & iCustName
        Printer.Print Tab(2); "Ship: " & iVesselName; Tab(50); "QTY Rcvd: " & iQtyRcvd; Tab(70); "QTY Dmgd: " & dsCARGO_TRACKING.Fields("QTY_DAMAGED").Value; Tab(90); "QTY Expected: " & sQtyExpc; Tab(120); "QTY in House: " & dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value
        If iRcver <> "" Then
            Printer.Print Tab(2); "Receiver: " & iRcver
        End If
    End If
    
    Printer.Print Tab(50); ""
    Printer.Print Tab(2); "Order Num"; Tab(27); "Date of Activity"; Tab(50); "Customer"; Tab(70); "Cases"; Tab(90); "Activity Num"; Tab(110); "Service Type"; Tab(130); "Employee"
    Printer.Print Tab(2); "----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
    Printer.FontSize = 10
    If bIsQC = True Then
        Printer.Print Tab(80); "***QC PALLET***";
    End If
    Printer.Print Tab(2); ""
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
    
    If dsSHORT_TERM_DATA.RecordCount > 0 Then
        ActDate = dsSHORT_TERM_DATA.Fields("THE_DATE").Value
        empno = dsSHORT_TERM_DATA.Fields("ACTIVITY_ID").Value
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
    
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value >= 1 Then
        strSql = "SELECT LOGIN_ID THE_EMP FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '" & empno & "'"
    Else
        While Len(empno) < 5
            empno = "0" & empno
        Wend
'        get_checker_name = empno
'        Exit Function
        strSql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP FROM EMPLOYEE WHERE SUBSTR(EMPLOYEE_ID, -" & Len(empno) & ") = '" & empno & "'"
    End If
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(strSql, 0&)
    
    get_checker_name = dsSHORT_TERM_DATA.Fields("THE_EMP").Value
    
End Function

