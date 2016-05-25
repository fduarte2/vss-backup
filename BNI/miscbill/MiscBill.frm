VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmMiscBill 
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Miscellaneous Billing"
   ClientHeight    =   5025
   ClientLeft      =   135
   ClientTop       =   2055
   ClientWidth     =   14505
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   5025
   ScaleWidth      =   14505
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&Print"
      Height          =   315
      Left            =   8760
      TabIndex        =   26
      Top             =   4200
      Width           =   1335
   End
   Begin VB.CommandButton cmdClear 
      Caption         =   "&ClearScreen"
      Height          =   315
      Left            =   7200
      TabIndex        =   23
      Top             =   4200
      Width           =   1335
   End
   Begin VB.CommandButton cmdRetrive 
      Caption         =   "&Retrive"
      Height          =   315
      Left            =   4080
      TabIndex        =   21
      Top             =   4200
      Width           =   1335
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
      Height          =   2535
      Left            =   -120
      TabIndex        =   25
      Top             =   1320
      Width           =   15265
      _Version        =   196616
      DataMode        =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Col.Count       =   4
      HeadFont3D      =   1
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowColumnMoving=   0
      AllowColumnSwapping=   0
      RowHeight       =   423
      Columns.Count   =   4
      Columns(0).Width=   17674
      Columns(0).Caption=   "DESCRIPTION"
      Columns(0).Name =   "DESCRIPTION"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(0).HasHeadForeColor=   -1  'True
      Columns(0).HeadForeColor=   16711680
      Columns(1).Width=   3519
      Columns(1).Caption=   "TOTAL"
      Columns(1).Name =   "TOTAL"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(1).HasHeadForeColor=   -1  'True
      Columns(1).HeadForeColor=   16711680
      Columns(2).Width=   1640
      Columns(2).Caption=   "ASSET"
      Columns(2).Name =   "ASSET"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(2).HasHeadForeColor=   -1  'True
      Columns(2).HasForeColor=   -1  'True
      Columns(2).HeadForeColor=   16711680
      Columns(3).Width=   2117
      Columns(3).Caption=   "GL CODE"
      Columns(3).Name =   "GL CODE"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(3).HasHeadForeColor=   -1  'True
      Columns(3).HasForeColor=   -1  'True
      Columns(3).HeadForeColor=   16711680
      _ExtentX        =   26926
      _ExtentY        =   4471
      _StockProps     =   79
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
   Begin VB.TextBox txtPageNum 
      Height          =   315
      Left            =   3480
      TabIndex        =   19
      Top             =   870
      Width           =   585
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&Delete"
      Height          =   315
      Left            =   5640
      TabIndex        =   22
      Top             =   4200
      Width           =   1335
   End
   Begin VB.TextBox txtServiceName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   2700
      MaxLength       =   40
      TabIndex        =   11
      Top             =   450
      Width           =   3435
   End
   Begin VB.CommandButton cmdServiceList 
      Height          =   315
      Left            =   2280
      Picture         =   "MiscBill.frx":0000
      Style           =   1  'Graphical
      TabIndex        =   10
      TabStop         =   0   'False
      Top             =   450
      Width           =   345
   End
   Begin VB.TextBox txtServiceCode 
      Height          =   315
      Left            =   1260
      MaxLength       =   10
      TabIndex        =   9
      Top             =   450
      Width           =   975
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
      Height          =   315
      Left            =   2520
      TabIndex        =   20
      Top             =   4200
      Width           =   1335
   End
   Begin VB.TextBox txtServiceDate 
      Height          =   315
      Left            =   1260
      MaxLength       =   10
      TabIndex        =   17
      Top             =   870
      Width           =   975
   End
   Begin VB.TextBox txtCommodityName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   8820
      MaxLength       =   40
      TabIndex        =   15
      Top             =   450
      Width           =   3435
   End
   Begin VB.TextBox txtCommodityCode 
      Height          =   315
      Left            =   7380
      MaxLength       =   12
      TabIndex        =   13
      Top             =   450
      Width           =   975
   End
   Begin VB.CommandButton cmdCommodityList 
      Height          =   315
      Left            =   8400
      Picture         =   "MiscBill.frx":0102
      Style           =   1  'Graphical
      TabIndex        =   14
      TabStop         =   0   'False
      Top             =   450
      Width           =   345
   End
   Begin VB.CommandButton cmdCustomerList 
      Height          =   315
      Left            =   8400
      Picture         =   "MiscBill.frx":0204
      Style           =   1  'Graphical
      TabIndex        =   6
      TabStop         =   0   'False
      Top             =   60
      Width           =   345
   End
   Begin VB.CommandButton cmdVesselList 
      Height          =   315
      Left            =   2280
      Picture         =   "MiscBill.frx":0306
      Style           =   1  'Graphical
      TabIndex        =   2
      TabStop         =   0   'False
      Top             =   60
      Width           =   345
   End
   Begin VB.TextBox txtCustomerId 
      Height          =   315
      Left            =   7380
      MaxLength       =   6
      TabIndex        =   5
      Top             =   60
      Width           =   975
   End
   Begin VB.TextBox txtCustomerName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   8820
      MaxLength       =   40
      TabIndex        =   7
      Top             =   60
      Width           =   3435
   End
   Begin VB.TextBox txtVesselName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   2700
      MaxLength       =   40
      TabIndex        =   3
      Top             =   60
      Width           =   3435
   End
   Begin VB.TextBox txtLRNum 
      BeginProperty DataFormat 
         Type            =   1
         Format          =   ""
         HaveTrueFalseNull=   0
         FirstDayOfWeek  =   0
         FirstWeekOfYear =   0
         LCID            =   1033
         SubFormatType   =   0
      EndProperty
      Height          =   315
      Left            =   1260
      MaxLength       =   7
      TabIndex        =   1
      Top             =   60
      Width           =   975
   End
   Begin VB.Label Label1 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Page Num"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   2370
      TabIndex        =   18
      Top             =   930
      Width           =   1035
   End
   Begin VB.Label lblService 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Service"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   90
      TabIndex        =   8
      Top             =   510
      Width           =   1095
   End
   Begin VB.Label lblServiceDate 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Date"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   90
      TabIndex        =   16
      Top             =   930
      Width           =   1095
   End
   Begin VB.Label lblCommodity 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Commodity"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   6210
      TabIndex        =   12
      Top             =   510
      Width           =   1095
   End
   Begin VB.Label lblBillToCust 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Bill To Cust"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   6210
      TabIndex        =   4
      Top             =   120
      Width           =   1095
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   285
      Left            =   0
      TabIndex        =   24
      Top             =   4680
      Width           =   13245
   End
   Begin VB.Label lblVessel 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Vessel"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   90
      TabIndex        =   0
      Top             =   120
      Width           =   1095
   End
End
Attribute VB_Name = "frmMiscBill"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private strBillingID(100), strBillID As String
Private iRow As Integer
Private isRetrive As Boolean
Private Sub cmdClear_Click()

  Call ClearScreen
  
End Sub
Private Sub cmdCommodityList_Click()

    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Commodity List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE"
    Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RecordCount > 0 Then
        While Not dsCOMMODITY_PROFILE.EOF
            frmPV.lstPV.AddItem dsCOMMODITY_PROFILE.fields("COMMODITY_CODE").Value & " : " & dsCOMMODITY_PROFILE.fields("COMMODITY_NAME").Value
            dsCOMMODITY_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
           txtCommodityCode.Text = Left$(gsPVItem, iPos - 1)
           txtCommodityName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtCommodityCode.SetFocus
    
End Sub
Private Sub cmdCustomerList_Click()

    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Customer List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
        While Not dsCUSTOMER_PROFILE.EOF
            frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.fields("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
            dsCUSTOMER_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCustomerId.Text = Left$(gsPVItem, iPos - 1)
            txtCustomerName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtCustomerId.SetFocus
    Exit Sub
    
    'Old Code
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
        While Not dsCUSTOMER_PROFILE.EOF
            frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.fields("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
            dsCUSTOMER_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCustomerId.Text = Left$(gsPVItem, iPos - 1)
            txtCustomerName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtCustomerId.SetFocus
    
End Sub
Private Sub cmdDelete_Click()

    Dim iResponse As Integer
    Dim lRecCount As Long
    
    'If glBillingNum < 0 Then
        'MsgBox "Please load the record first.", vbInformation, "Delete"
        'Exit Sub
    'End If
    If SSDBGrid1.Rows <= 0 Then
       MsgBox "Please load the record first.", vbInformation, "Delete"
       Exit Sub
    End If
    
    If itemSelected And SSDBGrid1.Row >= 0 Then
        'DO NOTHING
    Else
        MsgBox "Please select an item.", vbExclamation, "Delete"
        Exit Sub
    End If
    
    iResponse = MsgBox("Are you sure you want to delete the current record?", vbQuestion + vbYesNo, "Delete")
    If iResponse <> vbYes Then
        Exit Sub
    End If
    
    'Added the billing type check so it won't mess up with other types  -- LFW, 3/16/04
    gsSqlStmt = "DELETE FROM BILLING WHERE BILLING_NUM = " & strBillingID(iRow) & " AND BILLING_TYPE = 'MISC'"
    lRecCount = OraDatabase.ExecuteSQL(gsSqlStmt)
    
    If SSDBGrid1.Rows <= 0 Then
       Call ClearScreen
    Else
       SSDBGrid1.RemoveAll
       Call GetRecord
       SSDBGrid1.Refresh
    End If
    
End Sub
Private Sub GetRecord()

    Dim i As Integer
    
     SSDBGrid1.RemoveAll
     'Check valid vessel
     If Trim$(txtLRNum.Text) = "" Then
        MsgBox "Invalid Vessel Code. Please enter and try again.", vbExclamation, "Retrieve"
        Exit Sub
     End If
     'Check valid customer
     If Trim$(txtCustomerId.Text) = "" Then
        MsgBox "Invalid Customer Id. Please enter and try again.", vbExclamation, "Retrieve"
        Exit Sub
     End If
     'Check valid commodity
     If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Invalid Commodity Code. Please enter and try again.", vbExclamation, "Retrieve"
        Exit Sub
     End If
     'Check valid service
     If Trim$(txtServiceCode.Text) = "" Then
        MsgBox "Invalid Service Code. Please enter and try again.", vbExclamation, "Retrieve"
        Exit Sub
     End If
     'Check valid service date
     If Not IsDate(txtServiceDate.Text) Then
        MsgBox "Invalid Service Date. Please enter and try again.", vbExclamation, "Retrieve"
        Exit Sub
     End If
     Screen.MousePointer = vbHourglass
    
     gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = " & txtLRNum.Text
     gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & txtCustomerId.Text
     gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & txtCommodityCode.Text
     gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = " & txtServiceCode.Text
     gsSqlStmt = gsSqlStmt & " AND SERVICE_DATE = TO_DATE('" & txtServiceDate.Text & "', 'MM/DD/YYYY')"
     gsSqlStmt = gsSqlStmt & " AND SERVICE_STATUS='PREINVOICE'"
     gsSqlStmt = gsSqlStmt & " AND BILLING_TYPE = 'MISC' ORDER BY BILLING_NUM"
    
     Set dsBILLING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
     isRetrive = True
     If OraDatabase.LastServerErr = 0 And dsBILLING.RecordCount > 0 Then
         txtPageNum.Text = Trim$(dsBILLING.fields("PAGE_NUM").Value)
         For i = 0 To dsBILLING.RecordCount - 1
         
            If Not IsNull(dsBILLING.fields("ASSET_CODE")) And Not IsNull(dsBILLING.fields("GL_CODE")) Then
                SSDBGrid1.AddItem dsBILLING.fields("SERVICE_DESCRIPTION").Value + Chr$(9) + dsBILLING.fields("SERVICE_AMOUNT").Value + Chr$(9) + dsBILLING.fields("ASSET_CODE").Value + Chr$(9) + dsBILLING.fields("GL_CODE").Value
            Else
                SSDBGrid1.AddItem dsBILLING.fields("SERVICE_DESCRIPTION").Value + Chr$(9) + dsBILLING.fields("SERVICE_AMOUNT").Value + Chr$(9) + "" + Chr$(9) + ""
            End If
            
            strBillingID(i) = dsBILLING.fields("BILLING_NUM").Value
            dsBILLING.MoveNext
         Next i
     Else
        MsgBox "No more records found.", vbInformation, "Retrieve"
     End If
        Screen.MousePointer = vbArrow
End Sub

Private Sub cmdPrint_Click()

    Dim i As Integer
    Dim cName As String
    Dim cAddress1 As String
    Dim cAddress2 As String
    Dim cCity As String
    Dim cState As String
    Dim cZip As String
    Dim dsMiscBILLING As Object
    Dim total As Double

    'Check valid vessel
    If Trim$(txtLRNum.Text) = "" Then
        MsgBox "Invalid Vessel Code. Please enter again.", vbExclamation, "Print"
        Exit Sub
    End If
    'Check valid customer
    If Trim$(txtCustomerId.Text) = "" Then
        MsgBox "Invalid Customer Id. Please enter again.", vbExclamation, "Print"
        Exit Sub
    End If
    'Check valid commodity
    If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Invalid Commodity Code. Please enter again.", vbExclamation, "Print"
        Exit Sub
    End If
    'Check valid service
    If Trim$(txtServiceCode.Text) = "" Then
        MsgBox "Invalid Service Code. Please enter again.", vbExclamation, "Print"
        Exit Sub
    End If
    'Check valid service date
    If Not IsDate(txtServiceDate.Text) Then
        MsgBox "Invalid Service Date. Please enter again.", vbExclamation, "Print"
        Exit Sub
    End If
    
    SSDBGrid1.MoveFirst
    For i = 0 To SSDBGrid1.Rows - 1
        
     If Trim$(SSDBGrid1.Columns(0).Value) = "" And Trim$(SSDBGrid1.Columns(1).Value) = "" And Trim$(SSDBGrid1.Columns(2).Value) = "" And Trim$(SSDBGrid1.Columns(3).Value) = "" Then
    
     Else
        'Check valid description
        If Trim$(SSDBGrid1.Columns(0).Value) = "" Then
            MsgBox "Invalid Description. Please enter again.", vbExclamation, "Print"
            Exit Sub
        End If
        'Check valid total
        If Trim$(SSDBGrid1.Columns(1).Value) = "" Or Not IsNumeric(Trim$(SSDBGrid1.Columns(1).Value)) Then
            MsgBox "Invalid Service Total. Please enter again.", vbExclamation, "Print"
            Exit Sub
        End If
        'Check valid asset code
        If Len(Trim$(SSDBGrid1.Columns(2).Text)) > 4 Or Len(Trim$(SSDBGrid1.Columns(2).Text)) < 4 Then
            MsgBox "Invalid Asset Code. Asset Code must be 4 characters long.", vbExclamation, "Save"
            Exit Sub
        End If
        'Check valid gl code
        If Len(Trim$(SSDBGrid1.Columns(3).Text)) > 4 Or Len(Trim$(SSDBGrid1.Columns(3).Text)) < 4 Then
            MsgBox "Invalid GL Code. GL Code must be 4 characters long.", vbExclamation, "Save"
            Exit Sub
        End If
        
     End If
        SSDBGrid1.MoveNext
    Next i
    
    gsSqlStmt = "SELECT CUSTOMER_NAME, CUSTOMER_ADDRESS1, CUSTOMER_ADDRESS2, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID =" & txtCustomerId.Text
    Set dsMiscBILLING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsMiscBILLING.RecordCount > 0 Then
        If IsNull(dsMiscBILLING.fields("CUSTOMER_NAME").Value) Then
            cName = ""
        Else
            cName = Trim$(dsMiscBILLING.fields("CUSTOMER_NAME").Value)
        End If
        If IsNull(dsMiscBILLING.fields("CUSTOMER_ADDRESS1").Value) Then
            cAddress1 = ""
        Else
            cAddress1 = Trim$(dsMiscBILLING.fields("CUSTOMER_ADDRESS1").Value)
        End If
        If IsNull(dsMiscBILLING.fields("CUSTOMER_ADDRESS2").Value) Then
            cAddress2 = ""
        Else
            cAddress2 = Trim$(dsMiscBILLING.fields("CUSTOMER_ADDRESS2").Value)
        End If
        If IsNull(dsMiscBILLING.fields("CUSTOMER_CITY").Value) Then
            cCity = ""
        Else
            cCity = Trim$(dsMiscBILLING.fields("CUSTOMER_CITY").Value)
        End If
        If IsNull(dsMiscBILLING.fields("CUSTOMER_STATE").Value) Then
            cState = ""
        Else
            cState = Trim$(dsMiscBILLING.fields("CUSTOMER_STATE").Value)
        End If
        If IsNull(dsMiscBILLING.fields("CUSTOMER_ZIP").Value) Then
            cZip = ""
        Else
            cZip = Trim$(dsMiscBILLING.fields("CUSTOMER_ZIP").Value)
        End If
    End If
         
    'Print
    Printer.FontSize = 10
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    
    Printer.Print Tab(20); txtVesselName.Text
    Printer.Print Tab(20); "C/O " & cName
    Printer.Print Tab(20); cAddress1
    If cAddress2 <> "" Then
        Printer.Print Tab(20); cAddress2
    End If
    Printer.Print Tab(20); cCity; ", " & cState; " " & cZip
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
        
    total = 0
    SSDBGrid1.MoveFirst
    For i = 0 To SSDBGrid1.Rows - 1
    If Trim$(SSDBGrid1.Columns(0).Value) <> "" And Trim$(SSDBGrid1.Columns(1).Value) <> "" And Trim$(SSDBGrid1.Columns(2).Value) <> "" And Trim$(SSDBGrid1.Columns(3).Value) <> "" Then
        Printer.Print ""
        Printer.Print Tab(2); txtServiceDate.Text; Tab(20); txtServiceName.Text; "   " & txtCommodityName.Text; Tab(120); SSDBGrid1.Columns(1).Value
        Printer.Print Tab(20); SSDBGrid1.Columns(0).Value
          total = total + SSDBGrid1.Columns(1).Value
        Printer.Print ""
        Printer.Print Tab(0); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
     End If
        SSDBGrid1.MoveNext
    Next i
    
    If SSDBGrid1.Rows > 0 Then
        Printer.Print Tab(90); "TOTAL"; Tab(120); total
        Printer.Print Tab(0); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
    End If
    
    Printer.EndDoc
End Sub

Private Sub cmdRetrive_Click()

        Call GetRecord
        
End Sub
Private Sub cmdSave_Click()
  
  Dim bInvalid As Boolean
  Dim sQuery As String
  Dim rs As Object
  
  'Validate Data before trying to save!!
  bInvalid = False
  If Trim$(txtVesselName.Text) = "" Then bInvalid = True
  If Trim$(txtCustomerName.Text) = "" Then bInvalid = True
  If Trim$(txtServiceName.Text) = "" Then bInvalid = True
  If Trim$(txtCommodityName.Text) = "" Then bInvalid = True
  
  If bInvalid Then
    MsgBox "Please fill in header information"
  Else
    'MJP: Check Service Code against PROD db
'    sQuery = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005836' and flex_value='" & txtServiceCode.Text & "' and enabled_flag='Y'"
'    Set rs = OraDatabase.dbcreatedynaset(sQuery, 0&)
'    If OraDatabase.LastServerErr <> 0 Or rs.RecordCount = 0 Then
'      MsgBox "Service Code [" & txtServiceCode.Text & "] not found in Oracle."
'      bInvalid = True
'    End If
'    Set rs = Nothing
    
    'MJP: Check Commodity Code against PROD db
'    sQuery = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005837' and flex_value='" & txtCommodityCode.Text & "' and enabled_flag='Y'"
'    Set rs = OraDatabase.dbcreatedynaset(sQuery, 0&)
'    If OraDatabase.LastServerErr <> 0 Or rs.RecordCount = 0 Then
'      MsgBox "Commodity Code [" & txtCommodityCode.Text & "] not found in Oracle."
'      bInvalid = True
'    End If
'    Set rs = Nothing
    
    'MJP: Check Customer Id against PROD db
'    sQuery = "SELECT c.customer_id,a.address_id FROM ra_customers c, ra_addresses_all a where a.bill_to_flag in ('Y','P') and " _
'             & " c.status = 'A' and c.customer_id = a.customer_id and c.customer_number = " & txtCustomerId.Text
'    Set rs = OraDatabase.dbcreatedynaset(sQuery, 0&)
'    If OraDatabase.LastServerErr <> 0 Or rs.RecordCount = 0 Then
'      MsgBox "Customer Id [" & txtCustomerId.Text & "] not found in Oracle."
'      bInvalid = True
'    End If
'    Set rs = Nothing

    
    If Not bInvalid Then SaveBill
  End If
  
End Sub

Private Sub SaveBill()
    
    Dim i, j As Integer
    Dim lRecCount As Long
    Dim rowNum As Integer
    Dim sQuery As String
    Dim rs As Object
  
    Dim lStartBillNum As Long
    Dim lEndBillNum As Long
    
    'The lock is unnecessary since billing number need not to be unique  -- LFW, 3/12/04
    'Lock all the required tables in exclusive mode, try 10 times
'    On Error Resume Next
'    For i = 0 To 9
'        OraDatabase.LastServerErrReset
'        gsSqlStmt = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
'        lRecCount = OraDatabase.ExecuteSQL(gsSqlStmt)
'        If OraDatabase.LastServerErr = 0 Then Exit For
'    Next 'i
'    If OraDatabase.LastServerErr <> 0 Then
'       OraDatabase.LastServerErr
'       MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
'       Exit Sub
'    End If
    
    'Begin a transaction
    OraSession.BeginTrans
    
    On Error GoTo errHandler
    
    glBillingNum = -1
    lStartBillNum = 0
    lEndBillNum = 0
        
    'Check valid vessel
    If Trim$(txtLRNum.Text) = "" Then
       MsgBox "Invalid Vessel Code. Please enter and save again.", vbExclamation, "Save"
       OraSession.Rollback
       Exit Sub
    End If
    
    'Check valid customer
    If Trim$(txtCustomerId.Text) = "" Then
       MsgBox "Invalid Customer Id. Please enter and save again.", vbExclamation, "Save"
       OraSession.Rollback
       Exit Sub
    End If
    
    'Check valid commodity
    If Trim$(txtCommodityCode.Text) = "" Then
       MsgBox "Invalid Commodity Code. Please enter and save again.", vbExclamation, "Save"
       OraSession.Rollback
       Exit Sub
    End If
    
    'Check valid service
    If Trim$(txtServiceCode.Text) = "" Then
       MsgBox "Invalid Service Code. Please enter and save again.", vbExclamation, "Save"
       OraSession.Rollback
       Exit Sub
    End If
    
    'Check valid service date
    If Not IsDate(txtServiceDate.Text) Then
       MsgBox "Invalid Service Date. Please enter and save again.", vbExclamation, "Save"
       OraSession.Rollback
       Exit Sub
    End If
    
    'update on 10/31/2001 rw
    SSDBGrid1.MoveFirst
    For i = 0 To SSDBGrid1.Rows - 1
    
     If Trim$(SSDBGrid1.Columns(0).Value) = "" And Trim$(SSDBGrid1.Columns(1).Value) = "" And Trim$(SSDBGrid1.Columns(2).Value) = "" And Trim$(SSDBGrid1.Columns(3).Value) = "" Then
     
     Else
        'Check valid description
        If Trim$(SSDBGrid1.Columns(0).Value) = "" Then
            MsgBox "Invalid Description. Please enter and save again.", vbExclamation, "Save"
            OraSession.Rollback
            Exit Sub
        End If
        'Check valid total
        If Trim$(SSDBGrid1.Columns(1).Value) = "" Or Not IsNumeric(Trim$(SSDBGrid1.Columns(1).Value)) Then
            MsgBox "Invalid Service Total. Please enter and save again.", vbExclamation, "Save"
            OraSession.Rollback
            Exit Sub
        End If
        'Check valid asset code
        If Len(Trim$(SSDBGrid1.Columns(2).Text)) > 4 Or Len(Trim$(SSDBGrid1.Columns(2).Text)) < 4 Then
            MsgBox "Invalid Asset Code. Asset Code must be 4 characters long.", vbExclamation, "Save"
            OraSession.Rollback
            Exit Sub
        Else
          'MJP: Check asset Code against PROD db
'          sQuery = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005838' and flex_value='" & Trim$(SSDBGrid1.Columns(2).Text) & "' and enabled_flag='Y'"
'          Set rs = OraDatabase.dbcreatedynaset(sQuery, 0&)
'          If OraDatabase.LastServerErr <> 0 Or rs.RecordCount = 0 Then
'            MsgBox "Asset Code [" & SSDBGrid1.Columns(2).Text & "] not found in Oracle."
'            OraSession.Rollback
'            Exit Sub
'          End If
'          Set rs = Nothing
        End If
        
        'Check valid gl code
        If Len(Trim$(SSDBGrid1.Columns(3).Text)) > 4 Or Len(Trim$(SSDBGrid1.Columns(3).Text)) < 4 Then
            MsgBox "Invalid GL Code. GL Code must be 4 characters long.", vbExclamation, "Save"
            OraSession.Rollback
            Exit Sub
        Else
          'MJP: Check gl Code against PROD db
'          sQuery = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005835' and flex_value='" & Trim$(SSDBGrid1.Columns(3).Text) & "' and enabled_flag='Y'"
'          Set rs = OraDatabase.dbcreatedynaset(sQuery, 0&)
'          If OraDatabase.LastServerErr <> 0 Or rs.RecordCount = 0 Then
'            MsgBox "GL Code [" & SSDBGrid1.Columns(3).Text & "] not found in Oracle."
'            OraSession.Rollback
'            Exit Sub
'          End If
'          Set rs = Nothing
        End If
     End If
     
        SSDBGrid1.MoveNext
    Next i
    SSDBGrid1.MoveFirst
    
    gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = " & txtLRNum.Text
    gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & txtCustomerId.Text
    gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & txtCommodityCode.Text
    gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = " & txtServiceCode.Text
    gsSqlStmt = gsSqlStmt & " AND SERVICE_DATE = TO_DATE('" & txtServiceDate.Text & "', 'MM/DD/YYYY')"
    gsSqlStmt = gsSqlStmt & " AND SERVICE_STATUS= 'PREINVOICE'"
    gsSqlStmt = gsSqlStmt & " AND BILLING_TYPE = 'MISC' ORDER BY BILLING_NUM"
    Set dsBILLING_CHECK = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    
    For i = 0 To SSDBGrid1.Rows - 1
    
      If Trim$(SSDBGrid1.Columns(0).Value) = "" And Trim$(SSDBGrid1.Columns(1).Value) = "" And Trim$(SSDBGrid1.Columns(2).Value) = "" And Trim$(SSDBGrid1.Columns(3).Value) = "" Then

      Else

        If dsBILLING_CHECK.RecordCount >= 1 And Not IsNull(dsBILLING_CHECK.fields("BILLING_NUM")) Then
           strBillID = dsBILLING_CHECK.fields("BILLING_NUM").Value
           gsSqlStmt = "SELECT * FROM BILLING WHERE BILLING_NUM = " & strBillID & " AND BILLING_TYPE = 'MISC'"
        Else
            gsSqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
            Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            
            If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
                If IsNull(dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value) Then
                   glBillingNum = 1
                Else
                   glBillingNum = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
                End If
            Else
                glBillingNum = 1
            End If
            
            If (lStartBillNum = 0) Then
                lStartBillNum = glBillingNum
            End If
           
            gsSqlStmt = "SELECT * FROM BILLING WHERE BILLING_NUM = " & glBillingNum & " AND BILLING_TYPE = 'MISC'"
        End If
        
        Set dsBILLING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        
        If dsBILLING.RecordCount = 0 Then
           dsBILLING.AddNew
        Else
           dsBILLING.Edit
        End If
       
        If OraDatabase.LastServerErr <> 0 Then
            'Rollback transaction
            MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
            OraSession.Rollback
            Exit Sub
        End If
     
 
        dsBILLING.fields("CUSTOMER_ID").Value = txtCustomerId.Text
        dsBILLING.fields("SERVICE_CODE").Value = txtServiceCode.Text
        
        If dsBILLING_CHECK.RecordCount = 0 Or IsNull(dsBILLING_CHECK.fields("BILLING_NUM")) Then
           dsBILLING.fields("BILLING_NUM").Value = glBillingNum
        Else
           dsBILLING.fields("BILLING_NUM").Value = strBillID
        End If
        
        dsBILLING.fields("EMPLOYEE_ID").Value = 4
        dsBILLING.fields("SERVICE_START").Value = txtServiceDate.Text
        dsBILLING.fields("SERVICE_STOP").Value = txtServiceDate.Text
        dsBILLING.fields("SERVICE_DESCRIPTION").Value = SSDBGrid1.Columns(0).Text
        dsBILLING.fields("SERVICE_AMOUNT").Value = SSDBGrid1.Columns(1).Text
        dsBILLING.fields("ASSET_CODE").Value = Trim$(SSDBGrid1.Columns(2).Text)
        dsBILLING.fields("GL_CODE").Value = Trim$(SSDBGrid1.Columns(3).Text)

        
        'Update on 08/10/01....
        'If Len(SSDBGrid1.Columns(2).Text) > 4 Or Len(SSDBGrid1.Columns(2).Text) < 4 Then
          'MsgBox "Asset Code must be 4 characters long.", vbInformation, "Save"
          'Exit Sub
        'Else
          'dsBILLING.Fields("ASSET_CODE").Value = SSDBGrid1.Columns(2).Text
        'End If
        
        dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
        dsBILLING.fields("LR_NUM").Value = txtLRNum.Text
        dsBILLING.fields("ARRIVAL_NUM").Value = 1
        dsBILLING.fields("COMMODITY_CODE").Value = txtCommodityCode.Text
        dsBILLING.fields("INVOICE_NUM").Value = 0
        dsBILLING.fields("SERVICE_DATE").Value = Format(txtServiceDate.Text, "MM/DD/YYYY")
        dsBILLING.fields("SERVICE_QTY").Value = 0
        dsBILLING.fields("SERVICE_NUM").Value = 1
        dsBILLING.fields("THRESHOLD_QTY").Value = 0
        dsBILLING.fields("LEASE_NUM").Value = 0
        dsBILLING.fields("SERVICE_UNIT").Value = ""
        dsBILLING.fields("SERVICE_RATE").Value = ""
        dsBILLING.fields("LABOR_RATE_TYPE").Value = ""
        dsBILLING.fields("LABOR_TYPE").Value = ""
        dsBILLING.fields("PAGE_NUM").Value = Val(txtPageNum.Text)
        dsBILLING.fields("CARE_OF").Value = 1
        dsBILLING.fields("BILLING_TYPE").Value = "MISC"
        
        'Added for Asset Coding.  06.21.2001 LJG
        'Update on 08/10/2k1........
        'If Len(txtAssetCode.Text) > 4 Or Len(txtAssetCode.Text) < 4 Then
          'MsgBox "Asset Code must be 4 characters long.", vbInformation, "Save"
        'Else
          'dsBILLING.Fields("ASSET_CODE").Value = txtAssetCode.Text
        'End If
        '........
        
        dsBILLING.Update
        
        If OraDatabase.LastServerErr <> 0 Then
            'Rollback transaction
            MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
            OraSession.Rollback
            Exit Sub
        End If
       
        If Not IsNull(dsBILLING_CHECK.fields("BILLING_NUM")) Then
          dsBILLING_CHECK.MoveNext
        End If
     End If
     
        SSDBGrid1.MoveNext
    Next i
    
    lEndBillNum = glBillingNum
    
    'log to invoicedate table if generated prebills
    If lEndBillNum >= lStartBillNum Then
        Call AddNewInvDt("Miscellaneous", CStr(lStartBillNum), CStr(lEndBillNum))
    End If
    
    If OraDatabase.LastServerErr <> 0 Then
       'Rollback transaction
       MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
       OraSession.Rollback
       Exit Sub
    Else
       'Commit transaction
       OraSession.CommitTrans
    End If
    
    Call ClearScreen
    
    Exit Sub

errHandler:
     
    If OraDatabase.LastServerErr = 0 Then
         MsgBox "Error Occured. Unable to Process MISC Bills!", vbExclamation, "MISC Bill"
    Else
         MsgBox "Error " & OraDatabase.LastServerErrText & " occured." & vbCrLf & _
                "Unable to Process MISC Prebills!", vbExclamation, "MISC Bill"
    End If
         
    OraSession.Rollback
    OraDatabase.LastServerErrReset
        
End Sub

Private Sub AddNewInvDt(sType As String, sStBillNo As String, sEdBillNo As String)
    Dim dsINVDATE As Object
    Dim dsID As Object
    Dim SqlStmt As String
    Dim DtID As Long
    
    SqlStmt = "SELECT MAX(ID) MAXID FROM INVOICEDATE"
    Set dsID = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsID.RecordCount > 0 Then
        If Not IsNull(dsID.fields("MAXID").Value) Then
            DtID = dsID.fields("MAXID").Value + 1
        Else
            DtID = 0
        End If
    Else
        DtID = 0
    End If
    
    SqlStmt = "SELECT * FROM INVOICEDATE"
    Set dsINVDATE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        With dsINVDATE
            .AddNew
            .fields("ID").Value = DtID
            .fields("RUN_DATE").Value = Format(Now, "MM/DD/YYYY HH:MM:SS")
            .fields("BILL_TYPE").Value = "B"
            .fields("TYPE").Value = sType
            .fields("START_INV_NO").Value = sStBillNo
            .fields("END_INV_NO").Value = sEdBillNo
            .Update
        End With
    Else
        MsgBox OraSession.LastServerErrText, vbInformation, "MISC Bill"
    End If
End Sub


Private Sub cmdServiceList_Click()

    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Service List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY ORDER BY SERVICE_CODE"
    Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RecordCount > 0 Then
        While Not dsSERVICE_CATEGORY.EOF
            frmPV.lstPV.AddItem dsSERVICE_CATEGORY.fields("SERVICE_CODE").Value & " : " & dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value
            dsSERVICE_CATEGORY.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtServiceCode.Text = Left$(gsPVItem, iPos - 1)
            txtServiceName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtServiceCode.SetFocus
    
End Sub
Private Sub cmdVesselList_Click()

    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Vessel List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RecordCount > 0 Then
        While Not dsVESSEL_PROFILE.EOF
            frmPV.lstPV.AddItem dsVESSEL_PROFILE.fields("LR_NUM").Value & " : " & dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
            dsVESSEL_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtLRNum.Text = Left$(gsPVItem, iPos - 1)
            txtVesselName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtLRNum.SetFocus
    
End Sub
Private Sub cmFirst_Click()

End Sub
Private Sub Form_Load()

    Dim i As Integer
   
    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    'Call SetDataGrid
    lblStatus.Caption = "Logging to database..."
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
'    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    'MJP: Create the OraPROD Object (to PROD database for asset checking)
    ' REMOVED, Adam Walter, March2012.  New oracle version does not work with APPS@PROD, moving related tables.
'    Set OraPROD = OraSession.OpenDatabase("PROD", "APPS/APPS", 0&)
'    If OraPROD.LastServerErr = 0 Then
'        lblStatus.Caption = "Logon Successful."
'    Else
'        MsgBox "Error " & OraPROD.LastServerErrText & " occured.", vbExclamation, "Logon"
'        lblStatus.Caption = "PROD Logon Failed."
'        Unload Me
'    End If
    
    itemSelected = False
    Call ClearScreen
    
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0
                
End Sub
Private Sub SSDBGrid1_Click()

  Call getValue
  
End Sub

Private Sub SSDBGrid1_GotFocus()
'MsgBox "SSDBGrid1 got focus.", vbInformation, "Retrieve"
    If Not isRetrive Then
        cmdRetrive_Click
        cmdSave.SetFocus
    End If
End Sub


Private Sub txtCommodityCode_LostFocus()
  Dim Ccode_Query As String
  Dim rs As Object

    If Trim$(txtCommodityCode) <> "" And IsNumeric(txtCommodityCode.Text) Then
    
       'MJP: Check Commodity Code against PROD db
'         Ccode_Query = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005837' and flex_value='" & txtCommodityCode.Text & "' and enabled_flag='Y'"
'         Set rs = OraDatabase.dbcreatedynaset(Ccode_Query, 0&)
'          If OraDatabase.LastServerErr <> 0 Or rs.RecordCount = 0 Then
'            MsgBox "Commodity Code [" & txtCommodityCode.Text & "] not found in Oracle."
'            txtCommodityCode.Text = ""
'            txtCommodityName.Text = ""
'            Exit Sub
'          Else
            'Get the Commodity Name against BNI
            gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & txtCommodityCode.Text
            Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
              If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RecordCount > 0 Then
                txtCommodityName.Text = dsCOMMODITY_PROFILE.fields("COMMODITY_NAME").Value
              Else
                MsgBox "Commodity does not exist.", vbExclamation, "Commodity"
                txtCommodityCode.Text = ""
                txtCommodityName.Text = ""
              End If
'          End If
    End If
    
End Sub

Private Sub txtLRNum_LostFocus()

    If Trim$(txtLRNum) <> "" And IsNumeric(txtLRNum) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLRNum.Text
        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RecordCount > 0 Then
            txtVesselName.Text = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
        Else
            MsgBox "Vessel does not exist.", vbExclamation, "Vessel"
        End If
    End If
    
End Sub
Private Sub txtCustomerId_LostFocus()
  Dim Ccode_Query As String
  Dim rs As Object
   
    If Trim$(txtCustomerId) <> "" And IsNumeric(txtCustomerId.Text) Then
'
'      'MJP: Check Customer Code against PROD db
'
'         Ccode_Query = "SELECT c.customer_id,a.address_id FROM ra_customers c, ra_addresses_all a where a.bill_to_flag in ('Y','P') and " _
'                       & " c.status = 'A' and c.customer_id = a.customer_id and c.customer_number = " & txtCustomerId.Text
'
'         Set rs = OraDatabase.dbcreatedynaset(Ccode_Query, 0&)
'        If OraDatabase.LastServerErr <> 0 Or rs.RecordCount = 0 Then
'            MsgBox "Customer Code [" & txtCustomerId.Text & "] not found in Oracle."
'            txtCustomerId.Text = ""
'            txtCustomerName.Text = ""
'            Exit Sub
'        Else
          'Get the Customer Name against BNI
           gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtCustomerId.Text
           Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
             If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
               txtCustomerName.Text = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
             Else
               MsgBox "Customer does not exist.", vbExclamation, "Customer"
                txtCustomerId.Text = ""
                txtCustomerName.Text = ""
             End If
'        End If
    End If
    
End Sub
Public Sub ClearScreen()

    Dim i As Integer
    
    txtLRNum.Text = ""
    txtVesselName.Text = ""
    txtCustomerId.Text = ""
    txtCustomerName.Text = ""
    txtCommodityCode.Text = ""
    txtCommodityName.Text = ""
    txtServiceCode.Text = ""
    txtServiceName.Text = ""
    txtServiceDate.Text = Format$(Now, "mm/dd/yyyy")
    'txtDescription.Text = ""
    'txtServiceAmount.Text = ""
    SSDBGrid1.RemoveAll
    SSDBGrid1.Refresh
    txtPageNum.Text = "1"
    'chkCareOf.Value = 1 'Checked
    glBillingNum = -1
    
    isRetrive = False
        
End Sub
Private Sub txtPageNum_LostFocus()

    cmdRetrive_Click
    
End Sub
Private Sub txtServiceCode_LostFocus()

  Dim Scode_Query As String
  Dim rs As Object


    If Trim$(txtServiceCode) <> "" And IsNumeric(txtServiceCode) Then
    
'        'MJP: Check Service Code against PROD db
'          Scode_Query = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005836' and flex_value='" & txtServiceCode.Text & "' and enabled_flag='Y'"
'          Set rs = OraDatabase.dbcreatedynaset(Scode_Query, 0&)
'         If OraDatabase.LastServerErr <> 0 Or rs.RecordCount = 0 Then
'            MsgBox "Service Code [" & txtServiceCode.Text & "] not found in Oracle."
'            txtServiceCode.Text = ""
'            txtServiceName.Text = ""
'            Exit Sub
'         Else
           'Get the Service Name against BNI db
            gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " & txtServiceCode.Text
            Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
              If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RecordCount > 0 Then
                txtServiceName.Text = dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value
              Else
                MsgBox "Service does not exist.", vbExclamation, "Service"
                txtServiceCode.Text = ""
                txtServiceName.Text = ""
              End If
'        End If
    End If
    
End Sub
Private Sub getValue()

    itemSelected = True
    iRow = SSDBGrid1.AddItemRowIndex(SSDBGrid1.Bookmark)
    
End Sub

