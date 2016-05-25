VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "SSDW3B32.OCX"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCTL.OCX"
Begin VB.Form frmMiscBill 
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Miscellaneous Billing"
   ClientHeight    =   7500
   ClientLeft      =   135
   ClientTop       =   2055
   ClientWidth     =   14565
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   7500
   ScaleWidth      =   14565
   Begin MSComCtl2.DTPicker dtpDate 
      Height          =   315
      Left            =   1800
      TabIndex        =   26
      Top             =   1155
      Width           =   1335
      _ExtentX        =   2355
      _ExtentY        =   556
      _Version        =   393216
      CustomFormat    =   "MM/dd/yyyy"
      Format          =   22806531
      CurrentDate     =   36964
   End
   Begin MSComctlLib.StatusBar StatusBar1 
      Align           =   2  'Align Bottom
      Height          =   330
      Left            =   0
      TabIndex        =   25
      Top             =   7170
      Width           =   14565
      _ExtentX        =   25691
      _ExtentY        =   582
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
         EndProperty
      EndProperty
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.CommandButton cmdClear 
      Caption         =   "&ClearScreen"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   315
      Left            =   9000
      TabIndex        =   23
      Top             =   6480
      Width           =   1335
   End
   Begin VB.CommandButton cmdRetrive 
      Caption         =   "&Retrive"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   315
      Left            =   6120
      TabIndex        =   21
      Top             =   6480
      Width           =   1335
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
      Height          =   4500
      Left            =   120
      TabIndex        =   24
      Top             =   1680
      Width           =   14400
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
      Col.Count       =   3
      HeadFont3D      =   1
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowColumnMoving=   0
      AllowColumnSwapping=   0
      ForeColorEven   =   8388608
      RowHeight       =   503
      ExtraHeight     =   318
      Columns.Count   =   3
      Columns(0).Width=   17674
      Columns(0).Caption=   "DESCRIPTION"
      Columns(0).Name =   "DESCRIPTION"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(0).HasHeadForeColor=   -1  'True
      Columns(0).HeadForeColor=   8388608
      Columns(1).Width=   3519
      Columns(1).Caption=   "TOTAL"
      Columns(1).Name =   "TOTAL"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(1).HasHeadForeColor=   -1  'True
      Columns(1).HeadForeColor=   8388608
      Columns(2).Width=   3200
      Columns(2).Caption=   "BILL #"
      Columns(2).Name =   "BILL #"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(2).HasHeadForeColor=   -1  'True
      Columns(2).HeadForeColor=   8388608
      _ExtentX        =   25400
      _ExtentY        =   7937
      _StockProps     =   79
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
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
      Appearance      =   0  'Flat
      Height          =   315
      Left            =   8400
      TabIndex        =   19
      Top             =   1155
      Width           =   585
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&Delete"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   315
      Left            =   7560
      TabIndex        =   22
      Top             =   6480
      Width           =   1335
   End
   Begin VB.TextBox txtServiceName 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   3300
      MaxLength       =   40
      TabIndex        =   11
      Top             =   735
      Width           =   3435
   End
   Begin VB.CommandButton cmdServiceList 
      Height          =   315
      Left            =   2880
      Picture         =   "MiscBill.frx":0000
      Style           =   1  'Graphical
      TabIndex        =   10
      TabStop         =   0   'False
      Top             =   735
      Width           =   345
   End
   Begin VB.TextBox txtServiceCode 
      Appearance      =   0  'Flat
      Height          =   315
      Left            =   1800
      MaxLength       =   10
      TabIndex        =   9
      Top             =   735
      Width           =   975
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   315
      Left            =   4680
      TabIndex        =   20
      Top             =   6480
      Width           =   1335
   End
   Begin VB.TextBox txtServiceDate 
      Appearance      =   0  'Flat
      Height          =   315
      Left            =   4560
      MaxLength       =   10
      TabIndex        =   17
      Top             =   1230
      Width           =   975
   End
   Begin VB.TextBox txtCommodityName 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   9900
      MaxLength       =   40
      TabIndex        =   15
      Top             =   735
      Width           =   3435
   End
   Begin VB.TextBox txtCommodityCode 
      Appearance      =   0  'Flat
      Height          =   315
      Left            =   8400
      MaxLength       =   12
      TabIndex        =   13
      Top             =   735
      Width           =   975
   End
   Begin VB.CommandButton cmdCommodityList 
      Height          =   315
      Left            =   9480
      Picture         =   "MiscBill.frx":0102
      Style           =   1  'Graphical
      TabIndex        =   14
      TabStop         =   0   'False
      Top             =   735
      Width           =   345
   End
   Begin VB.CommandButton cmdCustomerList 
      Height          =   315
      Left            =   9480
      Picture         =   "MiscBill.frx":0204
      Style           =   1  'Graphical
      TabIndex        =   6
      TabStop         =   0   'False
      Top             =   300
      Width           =   345
   End
   Begin VB.CommandButton cmdVesselList 
      Height          =   315
      Left            =   2880
      Picture         =   "MiscBill.frx":0306
      Style           =   1  'Graphical
      TabIndex        =   2
      TabStop         =   0   'False
      Top             =   300
      Width           =   345
   End
   Begin VB.TextBox txtCustomerId 
      Appearance      =   0  'Flat
      Height          =   315
      Left            =   8400
      MaxLength       =   6
      TabIndex        =   5
      Top             =   300
      Width           =   975
   End
   Begin VB.TextBox txtCustomerName 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   9900
      MaxLength       =   40
      TabIndex        =   7
      Top             =   300
      Width           =   3435
   End
   Begin VB.TextBox txtVesselName 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
      Height          =   315
      Left            =   3300
      MaxLength       =   40
      TabIndex        =   3
      Top             =   300
      Width           =   3435
   End
   Begin VB.TextBox txtLRNum 
      Appearance      =   0  'Flat
      Height          =   315
      Left            =   1800
      MaxLength       =   7
      TabIndex        =   1
      Top             =   300
      Width           =   975
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackColor       =   &H00FFFFC0&
      Caption         =   "Page Num  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   7290
      TabIndex        =   18
      Top             =   1200
      Width           =   990
   End
   Begin VB.Label lblService 
      AutoSize        =   -1  'True
      BackColor       =   &H00FFFFC0&
      Caption         =   "Service  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   930
      TabIndex        =   8
      Top             =   780
      Width           =   765
   End
   Begin VB.Label lblServiceDate 
      AutoSize        =   -1  'True
      BackColor       =   &H00FFFFC0&
      Caption         =   "Date  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   1170
      TabIndex        =   16
      Top             =   1200
      Width           =   525
   End
   Begin VB.Label lblCommodity 
      AutoSize        =   -1  'True
      BackColor       =   &H00FFFFC0&
      Caption         =   "Commodity  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   7215
      TabIndex        =   12
      Top             =   780
      Width           =   1065
   End
   Begin VB.Label lblBillToCust 
      AutoSize        =   -1  'True
      BackColor       =   &H00FFFFC0&
      Caption         =   "Bill To Cust  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   7125
      TabIndex        =   4
      Top             =   360
      Width           =   1155
   End
   Begin VB.Label lblVessel 
      AutoSize        =   -1  'True
      BackColor       =   &H00FFFFC0&
      Caption         =   "Vessel  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   990
      TabIndex        =   0
      Top             =   360
      Width           =   705
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
    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RECORDCOUNT > 0 Then
        While Not dsCOMMODITY_PROFILE.EOF
            frmPV.lstPV.AddItem dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value & " : " & dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value
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
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
        While Not dsCUSTOMER_PROFILE.EOF
            frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.Fields("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
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
    gsSqlStmt = "DELETE FROM BILLING WHERE BILLING_NUM = " & strBillingID(iRow)
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
        MsgBox "Invalid Vessel Code.", vbExclamation, "Retrieve"
        Exit Sub
     End If
     
     'Check valid customer
     If Trim$(txtCustomerId.Text) = "" Then
        MsgBox "Invalid Customer Id.", vbExclamation, "Retrieve"
        Exit Sub
     End If
     
     'Check valid commodity
     If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Invalid Commodity Code.", vbExclamation, "Retrieve"
        Exit Sub
     End If
     
     'Check valid service
     If Trim$(txtServiceCode.Text) = "" Then
        MsgBox "Invalid Service Code.", vbExclamation, "Retrieve"
        Exit Sub
     End If
     
     'Check valid service date
     If Not IsDate(txtServiceDate.Text) Then
        MsgBox "Invalid Service Date.", vbExclamation, "Retrieve"
        Exit Sub
     End If
     Screen.MousePointer = vbHourglass
    
     gsSqlStmt = " SELECT * FROM BILLING WHERE LR_NUM = '" & Trim(txtLRNum.Text) & "'" _
               & " AND CUSTOMER_ID = '" & Trim(txtCustomerId.Text) & "'" _
               & " AND COMMODITY_CODE = '" & Trim(txtCommodityCode.Text) & "'" _
               & " AND SERVICE_CODE = '" & Trim(txtServiceCode.Text) & "'" _
               & " AND SERVICE_DATE = TO_DATE('" & Format(Trim(txtServiceDate), "MM/DD/YYYY") & "', 'MM/DD/YYYY')" _
               & " AND SERVICE_STATUS='PREINVOICE'" _
               & " AND BILLING_TYPE = 'MISC' ORDER BY BILLING_NUM"
    
     Set dsBILLING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
     If OraDatabase.LastServerErr = 0 And dsBILLING.RECORDCOUNT > 0 Then
         txtPageNum.Text = Trim$(dsBILLING.Fields("PAGE_NUM").Value)
         For i = 0 To dsBILLING.RECORDCOUNT - 1
            SSDBGrid1.AddItem dsBILLING.Fields("SERVICE_DESCRIPTION").Value + Chr$(9) + dsBILLING.Fields("SERVICE_AMOUNT").Value + Chr(9) + dsBILLING.Fields("BILLING_NUM").Value
            strBillingID(i) = dsBILLING.Fields("BILLING_NUM").Value
            dsBILLING.MoveNext
         Next i
         
     Else
        
     End If
     
     Screen.MousePointer = vbArrow
     
End Sub

Private Sub cmdRetrive_Click()
        Call GetRecord
End Sub

Private Sub cmdSave_Click()
    Dim i, j As Integer
    Dim iError As Integer
    Dim lRecCount As Long
    Dim rowNum As Integer
  
        'Lock all the required tables in exclusive mode, try 10 times
        On Error Resume Next
        For i = 0 To 9
            OraDatabase.LastServerErrReset
            gsSqlStmt = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
            lRecCount = OraDatabase.ExecuteSQL(gsSqlStmt)
            If OraDatabase.LastServerErr = 0 Then Exit For
        Next 'i
        If OraDatabase.LastServerErr <> 0 Then
           OraDatabase.LastServerErr
           MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
           Exit Sub
        End If
        On Error GoTo 0
    
        iError = False
        
        'Check valid vessel
        If Trim$(txtLRNum.Text) = "" Then
           MsgBox "Invalid Vessel Code. Please enter and save again.", vbExclamation, "Save"
           Exit Sub
        End If
        'Check valid customer
        If Trim$(txtCustomerId.Text) = "" Then
           MsgBox "Invalid Customer Id. Please enter and save again.", vbExclamation, "Save"
           Exit Sub
        End If
        'Check valid commodity
        If Trim$(txtCommodityCode.Text) = "" Then
           MsgBox "Invalid Commodity Code. Please enter and save again.", vbExclamation, "Save"
           Exit Sub
        End If
        'Check valid service
        If Trim$(txtServiceCode.Text) = "" Then
           MsgBox "Invalid Service Code. Please enter and save again.", vbExclamation, "Save"
           Exit Sub
        End If
        'Check valid service date
        If Not IsDate(txtServiceDate.Text) Then
           MsgBox "Invalid Service Date. Please enter and save again.", vbExclamation, "Save"
           Exit Sub
        End If
        'Check valid description
        If SSDBGrid1.Columns(0).Value = "" Then
           MsgBox "Invalid Description. Please enter and save again.", vbExclamation, "Save"
           Exit Sub
        End If
        'Check valid total
        If SSDBGrid1.Columns(1).Value = "" Or Not IsNumeric(SSDBGrid1.Columns(1).Value) Then
           MsgBox "Invalid Service Total. Please enter and save again.", vbExclamation, "Save"
           Exit Sub
        End If
               
        'Begin a transaction
        OraSession.BeginTrans
        gsSqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = " & txtLRNum.Text
        gsSqlStmt = gsSqlStmt & " AND CUSTOMER_ID = " & txtCustomerId.Text
        gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & txtCommodityCode.Text
        gsSqlStmt = gsSqlStmt & " AND SERVICE_CODE = " & txtServiceCode.Text
        gsSqlStmt = gsSqlStmt & " AND SERVICE_DATE = TO_DATE('" & txtServiceDate.Text & "', 'MM/DD/YYYY')"
        gsSqlStmt = gsSqlStmt & " AND SERVICE_STATUS='PREINVOICE'"
        gsSqlStmt = gsSqlStmt & " AND BILLING_TYPE = 'MISC'ORDER BY BILLING_NUM"
        Set dsBILLING_CHECK = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        
        SSDBGrid1.MoveFirst
        For i = 0 To SSDBGrid1.Rows - 1
           
           If dsBILLING_CHECK.RECORDCOUNT >= 1 And Not IsNull(dsBILLING_CHECK.Fields("BILLING_NUM")) Then
              strBillID = dsBILLING_CHECK.Fields("BILLING_NUM").Value
           Else
              gsSqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
              Set dsBILLING_MAX = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
              If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RECORDCOUNT > 0 Then
                 If IsNull(dsBILLING_MAX.Fields("MAX(BILLING_NUM)").Value) Then
                    glBillingNum = 1
                 Else
                    glBillingNum = dsBILLING_MAX.Fields("MAX(BILLING_NUM)").Value + 1
                 End If
              Else
                 glBillingNum = 1
              End If
           End If
           If dsBILLING_CHECK.RECORDCOUNT = 0 Or IsNull(dsBILLING_CHECK.Fields("BILLING_NUM")) Then
              gsSqlStmt = "SELECT * FROM BILLING WHERE BILLING_NUM = " & glBillingNum
           Else
              gsSqlStmt = "SELECT * FROM BILLING WHERE BILLING_NUM = " & strBillID
           End If
           Set dsBILLING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
           If dsBILLING.RECORDCOUNT = 0 Then
              dsBILLING.AddNew
           Else
              dsBILLING.Edit
           End If
           If OraDatabase.LastServerErr <> 0 Then
              MsgBox "Error Occured.", vbExclamation, "Save"
              iError = True
           End If
           If Not iError Then
              dsBILLING.Fields("CUSTOMER_ID").Value = txtCustomerId.Text
              dsBILLING.Fields("SERVICE_CODE").Value = txtServiceCode.Text
              If dsBILLING_CHECK.RECORDCOUNT = 0 Or IsNull(dsBILLING_CHECK.Fields("BILLING_NUM")) Then
                 dsBILLING.Fields("BILLING_NUM").Value = glBillingNum
              Else
                 dsBILLING.Fields("BILLING_NUM").Value = strBillID
              End If
              dsBILLING.Fields("EMPLOYEE_ID").Value = 4
              dsBILLING.Fields("SERVICE_START").Value = txtServiceDate.Text
              dsBILLING.Fields("SERVICE_STOP").Value = txtServiceDate.Text
              dsBILLING.Fields("SERVICE_DESCRIPTION").Value = SSDBGrid1.Columns(0).Text
              dsBILLING.Fields("SERVICE_AMOUNT").Value = SSDBGrid1.Columns(1).Text
              dsBILLING.Fields("SERVICE_STATUS").Value = "PREINVOICE"
              dsBILLING.Fields("LR_NUM").Value = txtLRNum.Text
              dsBILLING.Fields("ARRIVAL_NUM").Value = 1
              dsBILLING.Fields("COMMODITY_CODE").Value = txtCommodityCode.Text
              dsBILLING.Fields("INVOICE_NUM").Value = 0
              'dsBILLING.Fields("REVERSE_DATE").Value = txt.Text
              dsBILLING.Fields("SERVICE_DATE").Value = Format(txtServiceDate.Text, "MM/DD/YYYY")
              dsBILLING.Fields("SERVICE_QTY").Value = 0
              'dsBILLING.Fields("THRESHOLD_TRACK").Value = txt.Text
              dsBILLING.Fields("SERVICE_NUM").Value = 1
              dsBILLING.Fields("THRESHOLD_QTY").Value = 0
              dsBILLING.Fields("LEASE_NUM").Value = 0
              dsBILLING.Fields("SERVICE_UNIT").Value = ""
              dsBILLING.Fields("SERVICE_RATE").Value = ""
              dsBILLING.Fields("LABOR_RATE_TYPE").Value = ""
              dsBILLING.Fields("LABOR_TYPE").Value = ""
              dsBILLING.Fields("PAGE_NUM").Value = Val(txtPageNum.Text)
              dsBILLING.Fields("CARE_OF").Value = 1
              dsBILLING.Fields("BILLING_TYPE").Value = "MISC"
              dsBILLING.Update
              If OraDatabase.LastServerErr <> 0 Then
                 iError = True
              End If
           End If
           If Not IsNull(dsBILLING_CHECK.Fields("BILLING_NUM")) Then
              dsBILLING_CHECK.MoveNext
           End If
              'SSDBGrid1.Row = SSDBGrid1.Row + 1
               SSDBGrid1.MoveNext
        Next i
        If iError Then
           'Rollback transaction
           MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
           OraSession.Rollback
           Exit Sub
        Else
           'Commit transaction
           OraSession.CommitTrans
        End If
        Call ClearScreen
End Sub

Private Sub cmdServiceList_Click()
    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Service List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY ORDER BY SERVICE_CODE"
    Set dsSERVICE_CATEGORY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RECORDCOUNT > 0 Then
        While Not dsSERVICE_CATEGORY.EOF
            frmPV.lstPV.AddItem dsSERVICE_CATEGORY.Fields("SERVICE_CODE").Value & " : " & dsSERVICE_CATEGORY.Fields("SERVICE_NAME").Value
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
    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RECORDCOUNT > 0 Then
        While Not dsVESSEL_PROFILE.EOF
            frmPV.lstPV.AddItem dsVESSEL_PROFILE.Fields("LR_NUM").Value & " : " & dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value
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
    StatusBar1.SimpleText = "Logging to database..."
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    
    itemSelected = False
    Call ClearScreen
    
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    StatusBar1.SimpleText = "Error Occured."
    On Error GoTo 0
    
End Sub

Private Sub SSDBGrid1_Click()
  Call getValue
  
End Sub

Private Sub txtCommodityCode_LostFocus()
    If Trim$(txtCommodityCode) <> "" Then
        gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & txtCommodityCode.Text
        Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RECORDCOUNT > 0 Then
            txtCommodityName.Text = dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value
        Else
            MsgBox "Commodity does not exist.", vbExclamation, "Commodity"
        End If
    End If
End Sub

Private Sub txtLRNum_LostFocus()
    If Trim$(txtLRNum) <> "" And IsNumeric(txtLRNum) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLRNum.Text
        Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RECORDCOUNT > 0 Then
            txtVesselName.Text = dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value
        Else
            MsgBox "Vessel does not exist.", vbExclamation, "Vessel"
        End If
    End If
End Sub
Private Sub txtCustomerId_LostFocus()
    If Trim$(txtCustomerId) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtCustomerId.Text
        Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
            txtCustomerName.Text = dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
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
    txtPageNum.Text = "1"
    'chkCareOf.Value = 1 'Checked
    glBillingNum = -1
End Sub

Private Sub txtPageNum_LostFocus()
    cmdRetrive_Click
End Sub

Private Sub txtServiceCode_LostFocus()
    If Trim$(txtServiceCode) <> "" And IsNumeric(txtServiceCode) Then
        gsSqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " & txtServiceCode.Text
        Set dsSERVICE_CATEGORY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RECORDCOUNT > 0 Then
            txtServiceName.Text = dsSERVICE_CATEGORY.Fields("SERVICE_NAME").Value
        Else
            MsgBox "Service does not exist.", vbExclamation, "Service"
        End If
    End If
End Sub




Private Sub getValue()
    itemSelected = True
    iRow = SSDBGrid1.AddItemRowIndex(SSDBGrid1.Bookmark)
End Sub
