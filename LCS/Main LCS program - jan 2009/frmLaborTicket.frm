VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Begin VB.Form frmLaborTicket 
   Caption         =   "Labor Tickets (Daily)"
   ClientHeight    =   6735
   ClientLeft      =   1335
   ClientTop       =   2520
   ClientWidth     =   12360
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
   ScaleHeight     =   6735
   ScaleWidth      =   12360
   Begin VB.CommandButton Command1 
      Caption         =   "EXIT"
      Height          =   375
      Left            =   7568
      TabIndex        =   10
      Top             =   6210
      Width           =   1455
   End
   Begin VB.ComboBox cmbSuperId 
      Height          =   345
      Left            =   210
      TabIndex        =   2
      Top             =   713
      Width           =   1335
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "PRINT"
      Height          =   375
      Left            =   5498
      TabIndex        =   5
      Top             =   6210
      Width           =   1455
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "SAVE"
      Height          =   375
      Left            =   3338
      TabIndex        =   4
      Top             =   6210
      Width           =   1455
   End
   Begin VB.CommandButton cmdRetrieve 
      Caption         =   "Retrieve"
      Height          =   375
      Left            =   3150
      TabIndex        =   3
      Top             =   698
      Width           =   1455
   End
   Begin VB.TextBox txtTotalHours 
      Alignment       =   2  'Center
      Height          =   330
      Left            =   10680
      TabIndex        =   8
      Top             =   720
      Width           =   975
   End
   Begin MSComCtl2.DTPicker DTPicker1 
      BeginProperty DataFormat 
         Type            =   1
         Format          =   "MM/dd/yyyy"
         HaveTrueFalseNull=   0
         FirstDayOfWeek  =   0
         FirstWeekOfYear =   0
         LCID            =   1033
         SubFormatType   =   3
      EndProperty
      Height          =   375
      Left            =   1710
      TabIndex        =   1
      Top             =   698
      Width           =   1335
      _ExtentX        =   2355
      _ExtentY        =   661
      _Version        =   393216
      Format          =   63504385
      CurrentDate     =   36885
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   4575
      Left            =   0
      TabIndex        =   0
      Top             =   1320
      Width           =   12225
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
      Col.Count       =   8
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
      Columns.Count   =   8
      Columns(0).Width=   4101
      Columns(0).Caption=   "EMPLOYEE"
      Columns(0).Name =   "EMPLOYEE"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   2143
      Columns(1).Caption=   "LR NUM"
      Columns(1).Name =   "LR NUM"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   2143
      Columns(2).Caption=   "CUST ID"
      Columns(2).Name =   "CUST ID"
      Columns(2).Alignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   2461
      Columns(3).Caption=   "SERVICE CODE"
      Columns(3).Name =   "SERVICE CODE"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2223
      Columns(4).Caption=   "COMM CODE"
      Columns(4).Name =   "COMM CODE"
      Columns(4).Alignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   2831
      Columns(5).Caption=   "EARNING TYPE"
      Columns(5).Name =   "EARNING TYPE"
      Columns(5).Alignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   3201
      Columns(6).Caption=   "LABOR TYPE"
      Columns(6).Name =   "LABOR TYPE"
      Columns(6).Alignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1402
      Columns(7).Caption=   "HOURS"
      Columns(7).Name =   "HOURS"
      Columns(7).Alignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      _ExtentX        =   21564
      _ExtentY        =   8070
      _StockProps     =   79
      Caption         =   "LIST HOURS FOR EACH CUSTOMER"
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
   Begin VB.Label Label2 
      Caption         =   "SUPVISOR ID:"
      Height          =   255
      Left            =   270
      TabIndex        =   9
      Top             =   450
      Width           =   1335
   End
   Begin VB.Label Label3 
      Caption         =   "Total Hours:"
      Height          =   255
      Left            =   9480
      TabIndex        =   7
      Top             =   758
      Width           =   1095
   End
   Begin VB.Label Label1 
      Caption         =   "DATE:"
      Height          =   255
      Left            =   1980
      TabIndex        =   6
      Top             =   450
      Width           =   735
   End
End
Attribute VB_Name = "frmLaborTicket"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iCust(10) As Long
Const sMsg As String = "LABOR TICKET"
Dim sqlStmt As String
Dim irec As Integer
Dim iPos As Integer
Sub ClearScreen()
    grdData.MoveFirst
    grdData.RemoveAll
    txtTotalHours.Text = ""
    grdData.Columns(6).Value = ""
    grdData.Columns(6).Style = ssStyleComboBox
    DTPicker1.Value = Format$(Now, "mm/dd/yyyy")
End Sub

Private Sub cmdClear_Click()
    Call ClearScreen
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub
Function fnCustomer(id As Integer) As String
    Dim dsCUSTOMER As Object
    
    sqlStmt = "SELECT CUSTOMER_NAME FROM CUSTOMER WHERE CUSTOMER_ID='" & id & "'"
    Set dsCUSTOMER = OraDatabase.CreateDynaset(sqlStmt, 0&)
    If dsCUSTOMER.RecordCount > 0 Then
        fnCustomer = dsCUSTOMER.Fields("CUSTOMER_NAME").Value
    Else
        fnCustomer = CStr(id)
    End If
    
End Function
Function fnCOMMODITY(id As Integer) As String
    Dim dsCOMMODITY As Object
    
    sqlStmt = "SELECT COMMODITY_NAME FROM COMMODITY WHERE COMMODITY_CODE='" & id & "'"
    Set dsCOMMODITY = OraDatabase.CreateDynaset(sqlStmt, 0&)
    If dsCOMMODITY.RecordCount > 0 Then
        fnCOMMODITY = dsCOMMODITY.Fields("COMMODITY_NAME").Value
    Else
        fnCOMMODITY = CStr(id)
    End If
    
End Function
Function fnVESSEL(id As Long) As String
    Dim dsVESSEL As Object
    
    sqlStmt = "SELECT VESSEL_NAME FROM VESSEL WHERE VESSEL_ID='" & id & "'"
    Set dsVESSEL = OraDatabase.CreateDynaset(sqlStmt, 0&)
    If dsVESSEL.RecordCount > 0 Then
        fnVESSEL = dsVESSEL.Fields("VESSEL_NAME").Value
    Else
        fnVESSEL = CStr(id)
    End If
    
End Function
Function fnEmp(id As String) As String
    Dim dsEMP As Object
    sqlStmt = " SELECT * FROM EMPLOYEE WHERE EMPLOYEE_ID = '" & id & "'"
    Set dsEMP = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If dsEMP.RecordCount > 0 Then
        fnEmp = dsEMP.Fields("EMPLOYEE_NAME").Value
    Else
        fnEmp = CStr(id)
    End If
    
End Function
Private Sub cmdPrint_Click()
    Dim dsHD_Customer As Object
    Dim dsHD_LOCATION As Object
    Dim dsHD_SERVICE As Object
    Dim sLocation As String
    Dim sService As String
    Dim i As Integer
    Dim iFirst As Boolean
    Dim iSerCode As Integer
    Dim bNextPage As Boolean
    Dim iCustId As Integer
    Dim iLrNum As Long
    Dim iComm As Integer
    Dim iSer As Integer
    Dim iRecSer2 As Integer
    Dim iRecCust As Integer
    Dim iRecComm As Integer
    Dim iRecLrNum As Integer
    Dim iRecSer As Integer
    Dim iRecLoc As Integer
    iFirst = True
   
    
    If MsgBox("Did you save any unsaved records ? " & vbCrLf & " If not, please save them before printing", vbQuestion + vbYesNo, "PRINT") = vbNo Then
        Exit Sub
    End If
    
    'CHECK EACH LABOR TYPE COLUMN
'    grd Data.MoveFirst
'    For i = 0 To grd Data.Rows - 1
'        'For iCol = 0 To grd Data.Cols - 2
'            If grd Data.Columns(6).Value = "" Then
'                MsgBox "Fill the information for labor type", vbInformation, "LABOR TYPE"
'                Exit Sub
'            End If
'        'Next iCol
'        grd Data.MoveNext
'    Next i
'
'    grd Data.MoveFirst
   
               
    sqlStmt = "SELECT DISTINCT CUSTOMER_ID,VESSEL_ID,COMMODITY_CODE FROM HOURLY_DETAIL WHERE " _
            & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
            & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
            & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL ORDER BY CUSTOMER_ID,VESSEL_ID,COMMODITY_CODE"
        
    Set dsHD_Customer = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    For irec = 1 To dsHD_Customer.RecordCount
                   
        iCustId = dsHD_Customer.Fields("CUSTOMER_ID").Value
        iLrNum = dsHD_Customer.Fields("VESSEL_ID").Value
        iComm = dsHD_Customer.Fields("COMMODITY_CODE").Value
                            
               
        sqlStmt = " SELECT DISTINCT SUBSTR(SERVICE_CODE,1,3) SERVICE_CODE  FROM HOURLY_DETAIL WHERE " _
                & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "' AND CUSTOMER_ID='" & iCustId & "'" _
                & " AND COMMODITY_CODE='" & iComm & "' AND VESSEL_ID='" & iLrNum & "'" _
                & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL "
        Set dsHD_SERVICE = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        For iRecSer = 1 To dsHD_SERVICE.RecordCount
                    
            sLocation = ""
            sService = ""
            
            If iFirst = False Then
                Printer.NewPage
            End If
            iFirst = False
            Printer.FontSize = 14
            Printer.Print ""
            Printer.Print ""
            Printer.FontBold = True
            Printer.Print Tab(37); "LABOR TICKET"
            Printer.FontBold = False
            Printer.Print ""
            Printer.Print ""
            Printer.Print ""
            Printer.FontSize = 11
            Printer.Print Tab(5); "Date"; Tab(30); ":"; Tab(35); Format$(DTPicker1.Value, "MM/DD/YYYY")
            Printer.Print ""
            Printer.Print Tab(5); "Customer"; Tab(30); ":"; Tab(35); fnCustomer(iCustId)
            Printer.Print ""
            Printer.Print Tab(5); "Ship"; Tab(30); ":"; Tab(35); fnVESSEL(iLrNum)
            Printer.Print ""
            Printer.Print Tab(5); "Commodity"; Tab(30); ":"; Tab(35); fnCOMMODITY(iComm)
            Printer.Print ""
            
            iSer = dsHD_SERVICE.Fields("SERVICE_CODE").Value
            
            sqlStmt = " SELECT DISTINCT LOCATION_ID FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "' AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "' AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND LOCATION_ID IS NOT NULL "
                
                                                
            Set dsHD_LOCATION = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            For iRecLoc = 1 To dsHD_LOCATION.RecordCount
                If sLocation = "" Then
                    sLocation = dsHD_LOCATION.Fields("LOCATION_ID").Value
                Else
                    sLocation = sLocation & "," & dsHD_LOCATION.Fields("LOCATION_ID").Value
                End If
                dsHD_LOCATION.MoveNext
            Next iRecLoc
            
            Printer.Print Tab(5); "Area Worked"; Tab(30); ":"; Tab(35); sLocation
            Printer.Print ""
            
            Printer.Print Tab(5); "Supervisor"; Tab(30); ":"; Tab(35); fnEmp(Trim$(cmbSuperId.Text)) & "       _____________________________________________"
            Printer.FontSize = 9
            Printer.Print Tab(90); "(signature)"
            Printer.FontSize = 11
            Printer.Print
            Printer.Print
            Printer.Print
                    
            Printer.Print Tab(5); "--------------------------------------------------------------------------------------------" _
                                ; "--------------------------------------------------------------------------------------------"
            Printer.Print Tab(10); "Employee Type"; Tab(46); "No."; Tab(55); "Total Hours"; Tab(70); "Labor Type"; Tab(100); "Time"
            Printer.Print Tab(5); "--------------------------------------------------------------------------------------------" _
                                ; "--------------------------------------------------------------------------------------------"
            Printer.Print ""
            
            
             sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT,SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('CONT','SUPE','HARB')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                                        
            Set dsHOURLY_DETAIL_SUPERVISOR = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL_SUPERVISOR.Fields("SUM_HOURS").Value) Then
                Printer.Print Tab(10); "SUPERVISOR";
                For i = 1 To dsHOURLY_DETAIL_SUPERVISOR.RecordCount
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME," _
'                        & " MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME FROM HOURLY_DETAIL " _
'                        & " WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND LABOR_TYPE IN('CONT','SUPE','HARB') "
'
'                Set dsHOURLY_DETAIL_SUPER_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
            
                    Printer.Print Tab(47); dsHOURLY_DETAIL_SUPERVISOR.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL_SUPERVISOR.Fields("SUM_HOURS").Value; _
                          Tab(70); dsHOURLY_DETAIL_SUPERVISOR.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL_SUPERVISOR.Fields("STIME").Value & " - " & _
                          dsHOURLY_DETAIL_SUPERVISOR.Fields("ETIME").Value
                    dsHOURLY_DETAIL_SUPERVISOR.MoveNext
                Next i
                Printer.Print
            End If
                    
                    
                    
            'FOR LIFT TRUCKS
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS ,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL " _
                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID ='" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND LABOR_TYPE IN('DUMP','OPER','CRAN','LIFT','PAYL',',RAYG','POPR','WATR','CASO','L300','FOPR','TLIF','TYAR')" _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                    
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                Printer.Print Tab(10); "LIFT TRUCK OPERATORS";
                For i = 1 To dsHOURLY_DETAIL.RecordCount
                 
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME,MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME " _
'                        & " FROM HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND LABOR_TYPE IN('DUMP','OPER','CRAN','LIFT','PAYL',',RAYG','POPR','WATR','CASO','L300','FOPR','TLIF','TYAR')"
                                            
                'Set dsHOURLY_DETAIL_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
                
                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; _
                                  Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If
             '******************************
             'FOR XLIF
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS ,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL " _
                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID ='" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND LABOR_TYPE IN('XLIF')" _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                    
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                Printer.Print Tab(10); "EXTRA LIFT TRUCK OPERATORS";
                For i = 1 To dsHOURLY_DETAIL.RecordCount
                 
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME,MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME " _
'                        & " FROM HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND LABOR_TYPE IN('DUMP','OPER','CRAN','LIFT','PAYL',',RAYG','POPR','WATR','CASO','L300','FOPR','TLIF','TYAR')"
                                            
                'Set dsHOURLY_DETAIL_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
                
                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; _
                                  Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If
             
             
             
             
             
             
             
             
             
             
             '************
                    
                    
                    
                    
                    
                    
                    
                    
            'FOR CHECKERS
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM " _
                    & " HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('CHEC','SWEE','CASC','BULL','TCHE','BFOP')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                                        
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                Printer.Print Tab(10); "CHECKERS";
                For i = 1 To dsHOURLY_DETAIL.RecordCount
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME,MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME " _
'                        & " FROM HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND LABOR_TYPE IN('CHEC','SWEE','CASC','BULL','TCHE','BFOP')"
'
'                Set dsHOURLY_DETAIL_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
                                    
                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If

            'FOR LABOR
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('CASL','LABO','FLAB')" _
                    & " GROUP BY LABOR_TYPE, TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                                        
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                Printer.Print Tab(10); "LABORERS";
                For i = 1 To dsHOURLY_DETAIL.RecordCount
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME,MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME FROM HOURLY_DETAIL " _
'                        & " WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND LABOR_TYPE IN('CASL','LABO','FLAB')"
'
'                Set dsHOURLY_DETAIL_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
                                    
                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If

            'FOR OFFICE CLERK
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('OFFI')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                                        
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                 Printer.Print Tab(10); "OFFICE CLERK";
                 For i = 1 To dsHOURLY_DETAIL.RecordCount
                 
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME,MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME FROM HOURLY_DETAIL " _
'                        & " WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND LABOR_TYPE IN('OFFI')"
'
'                Set dsHOURLY_DETAIL_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(90); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If
            
            'WEIGHTMASTER
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
                    & " TO_CHAR(END_TIME,'HH24:MI')ETIME FROM HOURLY_DETAIL " _
                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('WEIG')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                                        
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                Printer.Print Tab(10); "WEIGHMASTER";
                For i = 1 To dsHOURLY_DETAIL.RecordCount
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME,MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME FROM HOURLY_DETAIL" _
'                        & " WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND LABOR_TYPE IN('WEIG')"
'
'                Set dsHOURLY_DETAIL_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If
            
            'SECURITY OFFICER
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('SECU')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                                        
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                 Printer.Print Tab(10); "SECURITY OFFICER";
                 For i = 1 To dsHOURLY_DETAIL.RecordCount
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME,MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME FROM " _
'                        & " HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND LABOR_TYPE IN('SECU')"
'
'                Set dsHOURLY_DETAIL_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If
            
            'MECHANIC
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS ,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME," _
                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('MECH')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                                        
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                  Printer.Print Tab(10); "MECHANIC";
                  For i = 1 To dsHOURLY_DETAIL.RecordCount
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME,MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME FROM " _
'                        & " HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND LABOR_TYPE IN('MECH')"
'
'                 Set dsHOURLY_DETAIL_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL_TIME.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If
                                
            'WELDER
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI') STIME, " _
                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('WELD')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                                        
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                 Printer.Print Tab(10); "WELDER";
                 For i = 1 To dsHOURLY_DETAIL.RecordCount
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME,MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME FROM " _
'                        & " HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND LABOR_TYPE IN('WELD')"
'
'                Set dsHOURLY_DETAIL_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If
                                
            'FLAT
            sqlStmt = " SELECT COUNT(DISTINCT(EMPLOYEE_ID)) MY_COUNT, SUM(DURATION) SUM_HOURS,LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI')STIME," _
                    & " TO_CHAR(END_TIME,'HH24:MI') ETIME FROM HOURLY_DETAIL " _
                    & " WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " AND TO_CHAR(START_TIME,'HH24:MI')<>TO_CHAR(END_TIME,'HH24:MI')" _
                    & " AND LABOR_TYPE IN('FLAT')" _
                    & " GROUP BY LABOR_TYPE,TO_CHAR(START_TIME,'HH24:MI'),TO_CHAR(END_TIME,'HH24:MI')"
                                        
            Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If Not IsNull(dsHOURLY_DETAIL.Fields("SUM_HOURS")) Then
                Printer.Print Tab(10); "FLATED";
                For i = 1 To dsHOURLY_DETAIL.RecordCount
'                SqlStmt = " SELECT MIN(TO_CHAR(START_TIME,'HH24:MI')) MIN_TIME,MAX(TO_CHAR(END_TIME,'HH24:MI')) MAX_TIME FROM " _
'                        & " HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'                        & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
'                        & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
'                        & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
'                        & " AND CUSTOMER_ID = '" & iCustId & "'" _
'                        & " AND VESSEL_ID = '" & iLrNum & "'" _
'                        & " AND COMMODITY_CODE = '" & iComm & "'" _
'                        & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
'                        & " AND LABOR_TYPE IN('FLAT')"
'
'                Set dsHOURLY_DETAIL_TIME = OraDatabase.DBCreateDynaset(SqlStmt, 0&)

                    Printer.Print Tab(47); dsHOURLY_DETAIL.Fields("MY_COUNT").Value; Tab(57); dsHOURLY_DETAIL.Fields("SUM_HOURS").Value; _
                                  Tab(70); dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value; Tab(100); dsHOURLY_DETAIL.Fields("STIME").Value & " - " & dsHOURLY_DETAIL.Fields("ETIME").Value
                    dsHOURLY_DETAIL.MoveNext
                Next i
                Printer.Print ""
            End If
                                
            Printer.Print Tab(5); "--------------------------------------------------------------------------------------------" _
                                ; "--------------------------------------------------------------------------------------------"
            
            Printer.Print ""
            Printer.Print ""
            
            'GET SERVICE DESCRIPTION
            sqlStmt = " SELECT DISTINCT SERVICE_CODE FROM HOURLY_DETAIL WHERE " _
                    & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
                    & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
                    & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL " _
                    & " AND CUSTOMER_ID = '" & iCustId & "'" _
                    & " AND VESSEL_ID = '" & iLrNum & "'" _
                    & " AND COMMODITY_CODE = '" & iComm & "'" _
                    & " AND SUBSTR(SERVICE_CODE,1,3)='" & iSer & "' " _
                    & " ORDER BY SERVICE_CODE"
            Set dsHOURLY_DETAIL_SERVICE = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            Printer.Print Tab(5); "Job Description"; Tab(30); ":";
            For iRecSer2 = 1 To dsHOURLY_DETAIL_SERVICE.RecordCount
                
                sqlStmt = " SELECT * FROM SERVICE WHERE SERVICE_CODE = " & dsHOURLY_DETAIL_SERVICE.Fields("SERVICE_CODE").Value
                Set dsService = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
                
                
                
                If Not IsNull(dsService.Fields("SERVICE_NAME").Value) Then
                    If sService = "" Then
                        Printer.Print Tab(35); dsService.Fields("SERVICE_NAME").Value
                    Else
                        Printer.Print Tab(35); dsService.Fields("SERVICE_NAME").Value
                    End If
                Else
                    If sService = "" Then
                       Printer.Print Tab(35); dsService.Fields("SERVICE_CODE").Value
                    Else
                       Printer.Print Tab(35); dsService.Fields("SERVICE_CODE").Value
                    End If
                End If
                dsHOURLY_DETAIL_SERVICE.MoveNext
            Next iRecSer2
            
            
            
            For i = 0 To 5
                Printer.Print ""
            Next i
            
            Printer.Print Tab(5); "Customer Approval : _______________________________"; Tab(80); " Date : _____________________"
                                
                                    
            dsHD_SERVICE.MoveNext
        Next iRecSer
        dsHD_Customer.MoveNext
                
    Next irec
    
    Printer.EndDoc
    Call ClearScreen
    
End Sub

Private Sub cmdSave_Click()
    Dim iResponse As Integer
    Dim lBillingNum As Long
    Dim i As Integer
    Dim iError As Integer
    Dim iRecSaved As Integer
    Dim lRecCount As Long
    Dim iCol As Integer
    
    If grdData.rows = 0 Then Exit Sub
    
    'Lock all the required tables in exclusive mode, try 10 times
    On Error Resume Next
    For i = 0 To 9
        OraDatabase.LastServerErrReset
        sqlStmt = "LOCK TABLE HOURLY_DETAIL IN EXCLUSIVE MODE NOWAIT"
        lRecCount = OraDatabase.ExecuteSQL(sqlStmt)
        If OraDatabase.LastServerErr = 0 Then Exit For
    Next 'i
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Labor Type"
        Exit Sub
    End If
    On Error GoTo 0
    
    iError = False
        
    'CHECK EACH LABOR TYPE COLUMN
    grdData.MoveFirst
    For irec = 0 To grdData.rows - 1
        If grdData.Columns(6).Value = "" Then
            MsgBox "Fill the information for labor type", vbInformation, "LABOR TYPE"
            Exit Sub
        End If
        grdData.MoveNext
    Next irec
    
    grdData.MoveFirst
    
    sqlStmt = " SELECT * FROM HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
            & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
            & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL ORDER BY SERVICE_CODE,EMPLOYEE_ID"
            
    Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If dsHOURLY_DETAIL.RecordCount <= 0 Then
        MsgBox "No Records", vbExclamation, "Save"
        Exit Sub
    End If
    
     'Begin a transaction
    OraSession.BeginTrans
    While Not dsHOURLY_DETAIL.EOF
        dsHOURLY_DETAIL.Edit
        dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value = grdData.Columns(6).Value
        dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value = grdData.Columns(3).Value
        dsHOURLY_DETAIL.Fields("CUSTOMER_ID").Value = grdData.Columns(2).Value
        dsHOURLY_DETAIL.Update
        dsHOURLY_DETAIL.MoveNext
        grdData.MoveNext
    Wend
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        MsgBox "Saved Changes Successfully.", vbInformation, "LABOR TICKET"
    Else
        MsgBox "Error occured while saving to labor type into hourly detail. try again.", vbExclamation, "Save"
        OraSession.Rollback
        Exit Sub
    End If
    grdData.Columns(6).Style = ssStyleComboBox
    
End Sub

Private Sub cmdRetrieve_Click()
    Dim sDesc As String
    Dim iLr As String
    Dim iCust As String
    Dim iService As String
    Dim iComm As String
    Dim iLaborCat As String
    Dim iCustName As String
    
    iLr = ""
    iCust = ""
    iService = ""
    iComm = ""
    iLaborCat = ""
    iLaborCat = ""
    
    grdData.RemoveAll
     
    If Trim(cmbSuperId) = "" Then
        MsgBox " Supervisor Id FIELD IS EMPTY", vbInformation, "LABOR TICKET"
        Exit Sub
    End If
    
    sqlStmt = " SELECT SUM(DURATION) SUM_HOURS FROM HOURLY_DETAIL WHERE " _
            & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
            & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
            & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL"
            
    Set dsHOURLY_DETAIL_TOTAL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    sqlStmt = " SELECT * FROM HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') +1 " _
            & " AND USER_ID = '" & Trim$(cmbSuperId.Text) & "'" _
            & " AND BILLING_FLAG = 'Y' AND TO_BNI IS NULL ORDER BY SERVICE_CODE,EMPLOYEE_ID"
            
    Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        If dsHOURLY_DETAIL.RecordCount > 0 Then
            For irec = 1 To dsHOURLY_DETAIL.RecordCount
                If Not IsNull(dsHOURLY_DETAIL.Fields("VESSEL_ID")) Then
                    iLr = Trim$(dsHOURLY_DETAIL.Fields("VESSEL_ID").Value)
                Else
                    MsgBox "VESSEL ID FILED IS EMPTY FOR " & dsHOURLY_DETAIL.Fields("EMPLOYEE_ID").Value & ". PLEASE CHECK HOURLY DETAIL.", vbInformation, "VESSEL ID"
                    ClearScreen
                    Exit Sub
                End If
                
                If Not IsNull(dsHOURLY_DETAIL.Fields("CUSTOMER_ID")) Then
                    iCust = Trim$(dsHOURLY_DETAIL.Fields("CUSTOMER_ID").Value)
                Else
                    MsgBox "CUSTOMER ID FILED IS EMPTY FOR " & dsHOURLY_DETAIL.Fields("EMPLOYEE_ID").Value & ". PLEASE CHECK HOURLY DETAIL.", vbInformation, "CUSTOMER ID"
                    ClearScreen
                    Exit Sub
               End If
                
                If Not IsNull(dsHOURLY_DETAIL.Fields("SERVICE_CODE")) Then
                    iService = Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value)
                Else
                    MsgBox "SERVICE CODE FILED IS EMPTY FOR " & dsHOURLY_DETAIL.Fields("EMPLOYEE_ID").Value & ". PLEASE CHECK HOURLY DETAIL.", vbInformation, "SERVICE CODE"
                    ClearScreen
                    Exit Sub
                End If
                
                If Not IsNull(dsHOURLY_DETAIL.Fields("COMMODITY_CODE")) Then
                    iComm = Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value)
                Else
                    MsgBox "COMMODITY CODE FILED IS EMPTY FOR " & dsHOURLY_DETAIL.Fields("EMPLOYEE_ID").Value & ". PLEASE CHECK HOURLY DETAIL.", vbInformation, "COMMODITY CODE"
                    ClearScreen
                    Exit Sub
                End If
                
'                If Not IsNull(dsHOURLY_DETAIL.Fields("LABOR_TYPE")) Then
'                    iLaborCat = Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value)
'                Else
'                    iLaborCat = ""
'                End If
                   
                If IsNull(dsHOURLY_DETAIL.Fields("DURATION")) Then
                    MsgBox "Either  Start time or end Time is empty for the employee  " & dsHOURLY_DETAIL.Fields("EMPLOYEE_ID").Value, vbInformation, "COMMODITY CODE"
                    ClearScreen
                    Exit Sub
                End If
                
                 If Not IsNull(dsHOURLY_DETAIL.Fields("LABOR_TYPE")) Then
                    iLaborCat = dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value
                Else
                    sqlStmt = "SELECT * FROM SERVICE_LABOR_RATE_TYPE WHERE SERVICE_CODE ='" & iService & "'"
                    Set dsService = OraDatabaseBNI.CreateDynaset(sqlStmt, 0&)
                    If OraDatabaseBNI.LastServerErr = 0 And dsService.RecordCount > 0 Then
                    
                        iLaborCat = "" & dsService.Fields("LABOR_TYPE").Value
                    Else
                        iLaborCat = ""
                    End If
                End If
                                 
                 
                grdData.AddItem dsHOURLY_DETAIL.Fields("EMPLOYEE_ID").Value + Chr(9) + _
                                iLr + Chr(9) + _
                                iCust + Chr(9) + _
                                iService + Chr(9) + _
                                iComm + Chr(9) + _
                                dsHOURLY_DETAIL.Fields("EARNING_TYPE_ID").Value + Chr(9) + _
                                iLaborCat + Chr(9) + _
                                dsHOURLY_DETAIL.Fields("DURATION").Value
                grdData.Refresh
                
                dsHOURLY_DETAIL.MoveNext
            Next irec
        Else
            MsgBox "No Records For That Day.", vbInformation, "LABOR TICKET"
            Exit Sub
        End If
        
    End If
    
    If dsHOURLY_DETAIL.RecordCount > 0 Then
        txtTotalHours.Text = dsHOURLY_DETAIL_TOTAL.Fields("SUM_HOURS").Value
    Else
        txtTotalHours.Text = ""
    End If
    
    
End Sub



Private Sub Command1_Click()
    Unload Me
End Sub

Private Sub DTPicker1_LOSTFOCUS()
    DTPicker1.Value = Format$(DTPicker1.Value, "MM/DD/YYYY")
End Sub

Private Sub Form_Load()
    On Error GoTo Err_FormLoad
    
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    Call ClearScreen
    
    'PREPARE TO FILL CMBSUPERID
'    SqlStmt = "SELECT * FROM LCS_USER ORDER BY USER_ID"
'    Set dsLCS_USER = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
'    While Not dsLCS_USER.EOF
'        cmbSuperId.AddItem Trim$(dsLCS_USER.Fields("USER_ID").Value)
'        dsLCS_USER.MoveNext
'    Wend
    cmbSuperId.AddItem UserID
    DTPicker1.Value = Format(frmGrpHrDetail.DTPDate.Value, "MM/DD/YYYY")
    
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Labor Tickets"
    
    On Error GoTo 0
    
End Sub

Private Sub grdData_AfterColUpdate(ByVal ColIndex As Integer)
    Dim dsLABOR As Object
    
    Select Case ColIndex
        Case 0 'Employee

        
        Case 1   'Lr Num
                    
'             If Trim$(grd Data.Columns(ColIndex).Value) <> "" And IsNumeric(grd Data.Columns(ColIndex).Value) Then
'                SqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Trim$(grd Data.Columns(ColIndex).Value)
'                Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'                If dsCOMMODITY_PROFILE.RECORDCOUNT = 0 Then
'                    MsgBox "Invalid Commodity Code.", vbExclamation, "Commodity"
'                    grd Data.Columns(1).Value = ""
'                    Exit Sub
'                End If
'            End If
                        
        Case 2   'customer ID
             
            
        Case 3   'Service Code
                
                If grdData.Columns(3).Value = "" Then Exit Sub

                sqlStmt = " SELECT * FROM SERVICE_LABOR_RATE_TYPE  WHERE SERVICE_CODE ='" & Trim(grdData.Columns(3).Value) & "'"
                Set dsService = OraDatabaseBNI.DBCreateDynaset(sqlStmt, 0&)
                If dsService.RecordCount = 0 Then
                    MsgBox "InValid Service Code", vbInformation, "SERVICE CODE"
                    grdData.Columns(3) = ""
                    grdData.SelectByCell = True
                    Exit Sub
                Else
                    grdData.Columns(6).Value = dsService("LABOR_TYPE").Value
                End If
'
'                If grd Data.Columns(3).Value <> "" Then
'                    SqlStmt = " SELECT * FROM LABOR_RATE WHERE RATE_TYPE='" & dsSERVICE.Fields("RATE_TYPE").Value & "'" _
'                            & " AND LABOR_TYPE='" & Trim(grd Data.Columns(6).Value) & "'"
'                    Set dsLABOR = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'                    If dsLABOR.RecordCount = 0 Then
'                        MsgBox " Invalid Service Code for this Labor Type", vbInformation, "LABOR TYPE"
'                        grd Data.Columns(3).Value = ""
'                        Exit Sub
'                    End If
'                End If
                
        
        Case 4   ' Comm Code
            
            
        Case 5   'Earning Type
           
                
        Case 6   'labor type
'            If Trim$(grd Data.Columns(ColIndex).Value) <> "" Then
'                grd Data.Columns(ColIndex).Value = Left(Trim$(grd Data.Columns(ColIndex).Value), 4)
'            End If
'
'            If grd Data.Columns(3).Value <> "" And grd Data.Columns(3).Value <> "" Then
'                SqlStmt = " SELECT * FROM SERVICE_LABOR_RATE_TYPE  WHERE SERVICE_CODE ='" & Trim(grd Data.Columns(3).Value) & "'"
'                Set dsSERVICE = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'                If dsSERVICE.RecordCount > 0 Then
'
'                    SqlStmt = " SELECT * FROM LABOR_RATE WHERE RATE_TYPE='" & dsSERVICE.Fields("RATE_TYPE").Value & "'" _
'                            & " AND LABOR_TYPE='" & Trim(grd Data.Columns(6).Value) & "'"
'                    Set dsLABOR = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'                    If dsLABOR.RecordCount = 0 Then
'                        MsgBox " Invalid Service Code for this Labor Type", vbInformation, "LABOR TYPE"
'                        grd Data.Columns(6).Value = ""
'                        Exit Sub
'                    End If
'                End If
'            End If
                
            grdData.Columns(6).Value = Left(Trim(grdData.Columns(6).Value), 4)
                  
        Case 7   'hours
            
        
            
    End Select

End Sub


Private Sub grdData_BeforeDelete(Cancel As Integer, DispPromptMsg As Integer)
    DispPromptMsg = 0
    If grdData.Columns(9).Value = "" Then
        grdData.DeleteSelected
    Else
        sqlStmt = "DELETE FROM BILLING WHERE BILLING_NUM='" & grdData.Columns(9).Value & "'"
        
        OraDatabase.ExecuteSQL (sqlStmt)
        If OraDatabase.LastServerErr = 0 Then
            grdData.DeleteSelected
        Else
            MsgBox OraDatabase.LastServerErrText, vbCritical, sMsg
            Exit Sub
        End If
    End If
    
End Sub




Private Sub grdData_Click()
    Dim dsLABOR_CATEGORY As Object
    
    'If grd Data.Col = 6 And Len(Trim(grd Data.Columns(6).Value)) < 1 Then
    If grdData.Col = 6 Then
        grdData.Columns(6).Style = ssStyleComboBox
        grdData.Columns(6).RemoveAll
        grdData.Columns(6).Value = ""
        sqlStmt = "SELECT  * FROM LABOR_CATEGORY ORDER BY LABOR_TYPE"
        Set dsLABOR_CATEGORY = OraDatabaseBNI.DBCreateDynaset(sqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsLABOR_CATEGORY.RecordCount > 0 Then
            For irec = 1 To dsLABOR_CATEGORY.RecordCount
                grdData.Columns(6).AddItem dsLABOR_CATEGORY.Fields("LABOR_TYPE").Value & "-" & dsLABOR_CATEGORY.Fields("LABOR_DESCRIPTION").Value
                dsLABOR_CATEGORY.MoveNext
            Next irec
        End If
    End If
End Sub

Private Sub grdData_LostFocus()
    If grdData.Col = 6 And Len(Trim(grdData.Columns(6).Value)) > 1 Then
       grdData.Columns(6).Value = Left(Trim(grdData.Columns(6).Value), 4)
    End If
End Sub
