VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{BDC217C8-ED16-11CD-956C-0000C04E4C0A}#1.1#0"; "TABCTL32.OCX"
Begin VB.Form frmDebitMemo 
   BackColor       =   &H00FFFFC0&
   Caption         =   "Debit Memo"
   ClientHeight    =   8835
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   14655
   LinkTopic       =   "Form1"
   ScaleHeight     =   8835
   ScaleWidth      =   14655
   StartUpPosition =   3  'Windows Default
   Begin TabDlg.SSTab SSTab1 
      Height          =   5565
      Left            =   210
      TabIndex        =   0
      Top             =   1200
      Width           =   13245
      _ExtentX        =   23363
      _ExtentY        =   9816
      _Version        =   393216
      TabOrientation  =   3
      Tabs            =   2
      TabsPerRow      =   2
      TabHeight       =   520
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Arial"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      TabCaption(0)   =   "Tab 0"
      TabPicture(0)   =   "frmDebitMemo.frx":0000
      Tab(0).ControlEnabled=   -1  'True
      Tab(0).Control(0)=   "lblCMInvoiceNum"
      Tab(0).Control(0).Enabled=   0   'False
      Tab(0).Control(1)=   "Label1"
      Tab(0).Control(1).Enabled=   0   'False
      Tab(0).Control(2)=   "lblServiceDate"
      Tab(0).Control(2).Enabled=   0   'False
      Tab(0).Control(3)=   "lblDescription"
      Tab(0).Control(3).Enabled=   0   'False
      Tab(0).Control(4)=   "lblCMNum"
      Tab(0).Control(4).Enabled=   0   'False
      Tab(0).Control(5)=   "lblCMDate"
      Tab(0).Control(5).Enabled=   0   'False
      Tab(0).Control(6)=   "lblServiceCode"
      Tab(0).Control(6).Enabled=   0   'False
      Tab(0).Control(7)=   "lblAssetCode"
      Tab(0).Control(7).Enabled=   0   'False
      Tab(0).Control(8)=   "lblCommCode"
      Tab(0).Control(8).Enabled=   0   'False
      Tab(0).Control(9)=   "lblAmount"
      Tab(0).Control(9).Enabled=   0   'False
      Tab(0).Control(10)=   "ssgrdCM"
      Tab(0).Control(10).Enabled=   0   'False
      Tab(0).Control(11)=   "cmdDone"
      Tab(0).Control(11).Enabled=   0   'False
      Tab(0).Control(12)=   "txtCMInvoiceNum"
      Tab(0).Control(12).Enabled=   0   'False
      Tab(0).Control(13)=   "txtAdjTotal"
      Tab(0).Control(13).Enabled=   0   'False
      Tab(0).Control(14)=   "cmdNewCreateCM"
      Tab(0).Control(14).Enabled=   0   'False
      Tab(0).Control(15)=   "txtServiceDate"
      Tab(0).Control(15).Enabled=   0   'False
      Tab(0).Control(16)=   "txtDescription"
      Tab(0).Control(16).Enabled=   0   'False
      Tab(0).Control(17)=   "cmdPrint"
      Tab(0).Control(17).Enabled=   0   'False
      Tab(0).Control(18)=   "cmdRetrieve"
      Tab(0).Control(18).Enabled=   0   'False
      Tab(0).Control(19)=   "cmdDeleteCancel"
      Tab(0).Control(19).Enabled=   0   'False
      Tab(0).Control(20)=   "cmdExit"
      Tab(0).Control(20).Enabled=   0   'False
      Tab(0).Control(21)=   "txtCMNum"
      Tab(0).Control(21).Enabled=   0   'False
      Tab(0).Control(22)=   "txtCMDate"
      Tab(0).Control(22).Enabled=   0   'False
      Tab(0).Control(23)=   "cmdEditSave"
      Tab(0).Control(23).Enabled=   0   'False
      Tab(0).Control(24)=   "txtServiceCode"
      Tab(0).Control(24).Enabled=   0   'False
      Tab(0).Control(25)=   "txtAssetCode"
      Tab(0).Control(25).Enabled=   0   'False
      Tab(0).Control(26)=   "txtCommCode"
      Tab(0).Control(26).Enabled=   0   'False
      Tab(0).Control(27)=   "txtAmount"
      Tab(0).Control(27).Enabled=   0   'False
      Tab(0).ControlCount=   28
      TabCaption(1)   =   "Tab 1"
      TabPicture(1)   =   "frmDebitMemo.frx":001C
      Tab(1).ControlEnabled=   0   'False
      Tab(1).ControlCount=   0
      Begin VB.TextBox txtAmount 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   4200
         TabIndex        =   17
         Tag             =   "NAmount"
         Top             =   1020
         Width           =   2265
      End
      Begin VB.TextBox txtCommCode 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   4200
         Locked          =   -1  'True
         TabIndex        =   16
         Tag             =   "NCommodity Code"
         Top             =   1410
         Width           =   2265
      End
      Begin VB.TextBox txtAssetCode 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   4350
         MaxLength       =   4
         TabIndex        =   15
         Top             =   2760
         Width           =   2265
      End
      Begin VB.TextBox txtServiceCode 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   4200
         Locked          =   -1  'True
         TabIndex        =   14
         Tag             =   "NService Code"
         Top             =   570
         Width           =   2265
      End
      Begin VB.CommandButton cmdEditSave 
         Caption         =   "&Edit"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   2115
         TabIndex        =   13
         Top             =   3120
         Width           =   1245
      End
      Begin VB.TextBox txtCMDate 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   10290
         Locked          =   -1  'True
         TabIndex        =   12
         Tag             =   "DCredit Memo Date"
         Top             =   120
         Width           =   2115
      End
      Begin VB.TextBox txtCMNum 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Height          =   285
         Left            =   1380
         TabIndex        =   11
         Top             =   120
         Width           =   2265
      End
      Begin VB.CommandButton cmdExit 
         Caption         =   "&Exit"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   9990
         TabIndex        =   10
         Top             =   3120
         Width           =   1245
      End
      Begin VB.CommandButton cmdDeleteCancel 
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
         Height          =   375
         Left            =   5265
         TabIndex        =   9
         Top             =   3120
         Width           =   1245
      End
      Begin VB.CommandButton cmdRetrieve 
         Caption         =   "&Retrieve"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   3690
         TabIndex        =   8
         Top             =   3120
         Width           =   1245
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   6840
         TabIndex        =   7
         Top             =   3120
         Width           =   1245
      End
      Begin VB.TextBox txtDescription 
         Appearance      =   0  'Flat
         Height          =   795
         Left            =   4200
         MaxLength       =   200
         MultiLine       =   -1  'True
         ScrollBars      =   2  'Vertical
         TabIndex        =   6
         Tag             =   "SDescription"
         Top             =   1830
         Width           =   6495
      End
      Begin VB.TextBox txtServiceDate 
         Appearance      =   0  'Flat
         Height          =   285
         Left            =   7995
         Locked          =   -1  'True
         TabIndex        =   5
         Tag             =   "DService Date"
         Top             =   570
         Width           =   2265
      End
      Begin VB.CommandButton cmdNewCreateCM 
         Caption         =   "&Create CM"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   540
         TabIndex        =   4
         Top             =   3120
         Width           =   1245
      End
      Begin VB.TextBox txtAdjTotal 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   9030
         TabIndex        =   3
         Top             =   2730
         Width           =   2265
      End
      Begin VB.TextBox txtCMInvoiceNum 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   285
         Left            =   5790
         TabIndex        =   2
         Top             =   120
         Width           =   2265
      End
      Begin VB.CommandButton cmdDone 
         Caption         =   "D&one"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   8415
         TabIndex        =   1
         Top             =   3120
         Width           =   1245
      End
      Begin SSDataWidgets_B.SSDBGrid ssgrdCM 
         Height          =   1785
         Left            =   120
         TabIndex        =   28
         Top             =   3600
         Width           =   12615
         _Version        =   196616
         DataMode        =   2
         FieldSeparator  =   ","
         AllowUpdate     =   0   'False
         AllowGroupSizing=   0   'False
         AllowGroupMoving=   0   'False
         AllowGroupSwapping=   0   'False
         AllowGroupShrinking=   0   'False
         AllowDragDrop   =   0   'False
         SelectTypeCol   =   0
         SelectTypeRow   =   0
         RowHeight       =   423
         Columns.Count   =   6
         Columns(0).Width=   2566
         Columns(0).Caption=   "Billing Number"
         Columns(0).Name =   "Billing Number"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   2487
         Columns(1).Caption=   "Date"
         Columns(1).Name =   "Date"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(2).Width=   2566
         Columns(2).Caption=   "Service Code"
         Columns(2).Name =   "Service Code"
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   7276
         Columns(3).Caption=   "Description"
         Columns(3).Name =   "Description"
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         Columns(4).Width=   3200
         Columns(4).Caption=   "Status"
         Columns(4).Name =   "Status"
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         Columns(5).Width=   3200
         Columns(5).Caption=   "Amount"
         Columns(5).Name =   "Amount"
         Columns(5).DataField=   "Column 5"
         Columns(5).DataType=   8
         Columns(5).FieldLen=   256
         _ExtentX        =   22251
         _ExtentY        =   3149
         _StockProps     =   79
         Caption         =   "Credit Memo History"
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
      Begin VB.Label lblAmount 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Amount :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   3270
         TabIndex        =   27
         Top             =   2760
         Width           =   735
      End
      Begin VB.Label lblCommCode 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   2535
         TabIndex        =   26
         Top             =   1410
         Width           =   1470
      End
      Begin VB.Label lblAssetCode 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Asset Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   3030
         TabIndex        =   25
         Top             =   1050
         Width           =   975
      End
      Begin VB.Label lblServiceCode 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service Code :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   2865
         TabIndex        =   24
         Top             =   600
         Width           =   1140
      End
      Begin VB.Label lblCMDate 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Date :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   9690
         TabIndex        =   23
         Top             =   150
         Width           =   480
      End
      Begin VB.Label lblCMNum 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Bill Number :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   210
         TabIndex        =   22
         Top             =   150
         Width           =   1095
      End
      Begin VB.Label lblDescription 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Description :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   2985
         TabIndex        =   21
         Top             =   1830
         Width           =   1020
      End
      Begin VB.Label lblServiceDate 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Service Date :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   6660
         TabIndex        =   20
         Top             =   600
         Width           =   1110
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Adjusted Invoice Total :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   7020
         TabIndex        =   19
         Top             =   2760
         Width           =   1860
      End
      Begin VB.Label lblCMInvoiceNum 
         Alignment       =   2  'Center
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Invoice Number :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   4335
         TabIndex        =   18
         Top             =   150
         Width           =   1365
      End
   End
End
Attribute VB_Name = "frmDebitMemo"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit				'2258 04/02/2007 Rudy: NOTE: not currently part of this project
Dim sErrPos As String
Dim sMode As String
Dim sModule As String
Dim lCurrDMNum As Long
Dim lCurrInvNum As Long
Dim dblCurrInvCrTot As Double
Dim dblCurrDMAmt As Double
Dim bIsDisplaying As Boolean
Dim bIsClearing As Boolean
Dim lUser As Long
Dim bChkAddNew As Boolean
Dim bPreDM As Boolean

Private Sub cmdDeleteCancel_Click()
Dim sSql As String
On Error GoTo ErrorHandle
If cmdDeleteCancel.Caption = "&Delete" Then
    stbarDM.SimpleText = "Deleting..."
    'Delete Credit Memo
    If MsgBox("Delete this Record?", vbYesNo, "Pre-Credit Memo") = vbNo Then Exit Sub
    MB
    sSql = "DELETE FROM BILLING WHERE BILLING_NUM = " & lCurrDMNum
    OraDatabase.DbExecuteSQL (sSql)
    MsgBox "Record Deleted."
    'clear screen
    cmdDone_Click
    MN
ElseIf cmdDeleteCancel.Caption = "&Cancel" Then
    stbarDM.SimpleText = "Cancel..."
    If lCurrDMNum <> 0 Then
        sMode = ""
        bIsDisplaying = True
        If Not DispDM(lCurrDMNum) Then
            MsgBox "Unexpected error occured.Contact BNI Administrator"
            sMode = ""
            bIsDisplaying = False
            cmdDone.Value = True
            MN
            Exit Sub
        End If
        ContAfterRetrieve
        bIsDisplaying = False
    
    Else
        If sMode <> "ADD" Then
            MsgBox "Unexpected error occured. Contact BNI Administrator."
        End If
        cmdDone_Click
    End If
End If
MN
Exit Sub
ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If

Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
        gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdDelete_Click"
End Select
MN
End Sub

Private Sub cmdDone_Click()
ClrScr
sMode = ""
'enable buttons
txtInvoiceNum.Locked = False
txtDMNum.Locked = False
DisableAll
cmdNewCreateDM.Enabled = True
cmdNewCreateDM.Caption = "&New"
cmdEditSave.Caption = "&Edit"
cmdDeleteCancel.Caption = "&Delete"
cmdRetrieve.Enabled = True
cmdExit.Enabled = True
lCurrInvNum = 0
dblCurrInvCrTot = 0
lCurrDMNum = 0
dblCurrDMAmt = 0
stbarDM.SimpleText = "Mode: Retrieve"
End Sub

Private Sub cmdEditSave_Click()
If cmdEditSave.Caption = "&Edit" Then
    sMode = "EDIT"
    DisableAll
    cmdEditSave.Enabled = True
    cmdEditSave.Caption = "&Save"
    cmdDeleteCancel.Enabled = True
    cmdDeleteCancel.Caption = "&Cancel"
    txtInvoiceNum.Locked = True
    txtDMNum.Locked = True
    stbarDM.SimpleText = "Mode: Edit"
ElseIf cmdEditSave.Caption = "&Save" Then
    
    SaveDM
End If
End Sub

Private Sub cmdExit_Click()
Unload Me
End Sub

Private Sub cmdNewCreateDM_Click()
Dim lInvNum As Long
Dim sSql As String
Dim i As Integer
Dim bCcds As Boolean
Dim sCustId As String
On Error GoTo ErrorHandle


If cmdNewCreateDM.Caption = "Create DM" Then
    'assign Invoice_num, Invoice_date, Service_status and save
    MB
    lInvNum = fnMaxInvNum()
    sSql = "UPDATE BILLING SET INVOICE_NUM = " & lInvNum & ", INVOICE_DATE = TO_DATE('" & Format(Now, "dd-mmm-yy")
    sSql = sSql & "'), SERVICE_STATUS = 'CREDITMEMO' WHERE BILLING_NUM = " & lCurrDMNum
    OraDatabase.DbExecuteSQL (sSql)
    MsgBox "Credit Memo created."
    ClrScr
    'sMode = ""
    bIsDisplaying = True
    If Not DispDM(lCurrDMNum) Then
        MsgBox "Unexpected error occured.Contact BNI Administrator"
        'sMode = ""
        bIsDisplaying = False
        txtDMNum.SetFocus
        MN
        Exit Sub
    End If
    bIsDisplaying = False
    ContAfterRetrieve
    MN
    'Print
    'chk the customer - Bni or ccds
    sCustId = txtCustomerID.Text
    If Len(sCustId) = 5 And Left(sCustId, 2) = "90" Then
        bCcds = True
    End If
    'Lock all the required tables in exclusive mode, try 10 times
    On Error Resume Next
    For i = 0 To 9
        OraDatabase.LastServerErrReset
        sSql = "LOCK TABLE PREINVOICE IN EXCLUSIVE MODE NOWAIT"
        OraDatabase.EXECUTESQL sSql
        If OraDatabase.LastServerErr = 0 Then Exit For
    Next 'i
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message:" _
             & OraDatabase.LastServerErrText, vbExclamation
        Exit Sub
    End If
    On Error GoTo ErrorHandle
    OraSession.begintrans
    If bCcds Then
        If Not ReadyToPrintCcds() Then
            GoTo ErrorHandle
            Exit Sub
        End If
    Else
        If Not ReadyToPrintBni() Then
            GoTo ErrorHandle
            Exit Sub
        End If
    End If
    
    'crDM.ReportFileName = App.Path & "\PreDM.rpt"
    'crDM.SelectionFormula = "{BILLING.BILLING_NUM} = " & lCurrDMNum
        crDM.ReportFileName = App.Path & "\BNIDM.rpt"
    crDM.Connect = "DSN = BNI;UID = sag_owner;PWD = sag"
    crDM.Destination = crptToPrinter
    crDM.PrintReport
    If crDM.LastErrorNumber <> 0 Then
        MsgBox crDM.LastErrorString, , "frmCreditMemo - cmdPrint_Click - Crystal Report Error"
    End If
    OraSession.rollback
ElseIf cmdNewCreateDM.Caption = "&New" Then
    sMode = "ADD"
    ClrScr
    txtInvoiceNum.Locked = False
    txtDMNum.Locked = True
    DisableAll
    cmdEditSave.Enabled = True
    cmdEditSave.Caption = "&Save"
    cmdDeleteCancel.Enabled = True
    cmdDeleteCancel.Caption = "&Cancel"
    stbarDM.SimpleText = "Mode: Add"
End If
Exit Sub
ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
ElseIf Err <> 0 Then  'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If

Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
        gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdCreateDM_Click"
End Select
OraSession.rollback
MN

End Sub

Private Sub cmdPrint_Click()
Dim i As Integer
Dim sSql As String
Dim sCustId As String
Dim bCcds As Boolean
MB
'chk the customer - Bni or ccds
sCustId = txtCustomerID.Text
If Len(sCustId) = 5 And Left(sCustId, 2) = "90" Then
    bCcds = True
End If

'Lock all the required tables in exclusive mode, try 10 times
On Error Resume Next
For i = 0 To 9
    OraDatabase.LastServerErrReset
    sSql = "LOCK TABLE PREINVOICE IN EXCLUSIVE MODE NOWAIT"
    OraDatabase.EXECUTESQL sSql
    If OraDatabase.LastServerErr = 0 Then Exit For
Next 'i
If OraDatabase.LastServerErr <> 0 Then
    OraDatabase.LastServerErr
    MsgBox "Tables could not be locked. Please try again. Server Message:" _
         & OraDatabase.LastServerErrText, vbExclamation
    MN
    Exit Sub
End If
On Error Resume Next
OraSession.begintrans

If bCcds Then
    If Not ReadyToPrintCcds() Then
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErr & " : " & OraDatabase.LastServerErrText
            OraDatabase.LastServerErrReset
        ElseIf Err <> 0 Then 'Must be some non-Oracle error.
            MsgBox Err & " : " & Error
        End If
        OraSession.rollback
        MN
        Exit Sub
    End If
Else
    If Not ReadyToPrintBni() Then
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErr & " : " & OraDatabase.LastServerErrText
            OraDatabase.LastServerErrReset
        ElseIf Err <> 0 Then  'Must be some non-Oracle error.
            MsgBox Err & " : " & Error
        End If
        OraSession.rollback
        MN
        Exit Sub
    End If

End If
OraSession.COMMITTRANS
crDM.ReportFileName = App.Path & "\BNICM.rpt"

crDM.Connect = "DSN = BNI;UID = sag_owner;PWD = sag"

crDM.PrintReport

MN
If crDM.LastErrorNumber <> 0 Then
    MsgBox crDM.LastErrorString, , "frmCreditMemo - cmdPrint_Click - Crystal Report Error"
End If
End Sub

Private Sub cmdRetrieve_Click()
Dim lDMNum As Long
Dim lOrigInvNum As Long
Dim dsBilling_BillingNum As Object
Dim sSql As String
On Error GoTo ErrorHandle

If sMode <> "" Then Exit Sub
If txtInvoiceNum.Text = "" And txtDMNum.Text = "" Then Exit Sub
If txtInvoiceNum.Text <> "" And IsNumeric(txtInvoiceNum.Text) Then
    'search by Orig invoice num
    lOrigInvNum = txtInvoiceNum.Text
    lCurrInvNum = lOrigInvNum
    dblCurrInvCrTot = 0
    sSql = "SELECT * FROM BILLING WHERE ORIG_INVOICE_NUM = " & lOrigInvNum
    Set dsBilling_BillingNum = OraDatabase.dbcreatedynaset(sSql, 0&)
    If Not dsBilling_BillingNum.eof And Not dsBilling_BillingNum.BOF Then
        'CHECK FOR ONE OR MORE RECORDS
        dsBilling_BillingNum.MoveLast: dsBilling_BillingNum.MoveFirst
        If dsBilling_BillingNum.RecordCount > 1 Then
            'show the Listbox screen
            lDMNum = ConfirmDM(False)
        Else
            lDMNum = dsBilling_BillingNum.fields("BILLING_NUM").Value
        End If
        
    Else    'Not found
        MsgBox "No Credit Memos were issued against this Invoice."
        Exit Sub
    End If
End If
If txtDMNum.Text <> "" And IsNumeric(txtDMNum.Text) And lDMNum = 0 Then
    lDMNum = txtDMNum.Text
End If
'Retrieve
If lDMNum = 0 Then
    MsgBox "Invalid Search Criteria."
    Exit Sub
End If
bIsDisplaying = True
If Not DispDM(lDMNum) Then
    MsgBox "Credit Memo could not be Retrieved. Try again."
    sMode = ""
    bIsDisplaying = False
    txtDMNum.SetFocus
    Exit Sub
End If
bIsDisplaying = False

'handle controls
ContAfterRetrieve
'DisableAll
'cmdEditSave.Enabled = True
'cmdDeleteCancel.Enabled = True
'cmdPrint.Enabled = True
'cmdDone.Enabled = True
'cmdEditSave.Caption = "&Edit"
'cmdNewCreateDM.Caption = "Create DM"
'cmdDeleteCancel.Caption = "&Delete"

Exit Sub
ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If
Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
        gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdRetrieve_Click"

End Select
End Sub

Private Sub SaveDM()
On Error GoTo ErrorHandle
MB
If Not ValidData() Then
    MN
    Exit Sub
End If
If UpdateBilling(sMode) Then
    'MsgBox "Credit Memo Saved"
    'clear screen
    ClrScr
    sMode = ""
    bIsDisplaying = True
    If Not DispDM(lCurrDMNum) Then
        MsgBox "Unexpected error occured.Contact BNI Administrator"
        sMode = ""
        bIsDisplaying = False
        cmdDone.Value = True
        MN
        Exit Sub
    End If
    ContAfterRetrieve
    bIsDisplaying = False
    stbarDM.SimpleText = "Record Saved"
Else
    MsgBox "Save Failed"
    If sMode = "ADD" Then
        cmdDone.Value = True
    ElseIf sMode = "EDIT" Then
        cmdDeleteCancel.Value = True
    End If
End If

MN
Exit Sub
ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If
Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
        gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "cmdSave_Click"
End Select
MN
End Sub

Private Sub Form_Load()
Dim frmREAuth As New frmRateEditAuthenticate
Dim bProceed As Boolean

frmREAuth.Show 1
bProceed = frmREAuth.fbValidUser
lUser = frmREAuth.flUser
Unload frmREAuth
Set frmREAuth = Nothing
If Not bProceed Then
    Unload Me
    Exit Sub
End If
'MsgBox lUser

On Error GoTo ErrorHandle
sModule = App.Title & " - frmCreditMemo - "

CenterForm Me
'on error resume next
Set OraSession = CreateObject("OracleInProcServer.XOraSession")
Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
If OraDatabase.LastServerErr <> 0 Then
    sErrPos = "Form: Credit Memo - Form_Load"
    MsgBox "Database connection could not be made.", , sErrPos
End If

cmdDone.Value = True
Exit Sub
ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If
Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
        gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "Form_Load"
End Select

End Sub


Private Sub ssgrdInvoice_Click()
txtServiceCode.Text = ssgrdInvoice.Columns(1).Value
txtServiceDate.Text = ssgrdInvoice.Columns(0).Value
txtAssetCode.Text = ssgrdInvoice.Columns(4).Value
txtCommCode.Text = ssgrdInvoice.Columns(5).Value
txtDescription.SetFocus
End Sub

Private Sub TabStrip1_Click()

End Sub

Private Sub txtAmount_Change()
Dim sAmt As String
sAmt = txtAmount.Text
If sAmt = "" Then Exit Sub
If Not IsNumeric(sAmt) Then
    MsgBox "Invalid Amount"
    txtAmount.SelStart = 0
    txtAmount.SelLength = Len(sAmt)
    txtAmount.SetFocus
    Exit Sub
End If
CalcBal
End Sub


Private Sub txtInvoiceNum_LostFocus()
On Error GoTo ErrorHandle

Dim lEntInvNum As Long
If txtInvoiceNum.Text = "" Or sMode = "EDIT" Or sMode = "" Then
    Exit Sub
End If

lEntInvNum = txtInvoiceNum.Text
MB
If Not IsNumeric(lEntInvNum) Then
    MsgBox "Invalid Invoice Number."
    txtInvoiceNum.SelStart = 0
    txtInvoiceNum.SelLength = Len(txtInvoiceNum.Text)
    txtInvoiceNum.SetFocus
    Exit Sub
End If
lCurrInvNum = lEntInvNum
dblCurrInvCrTot = 0
lCurrDMNum = 0
dblCurrDMAmt = 0
If ChkExistingDM(lCurrInvNum) Then
    lCurrDMNum = ConfirmDM(True)
End If
If lCurrDMNum = 0 Then
    bIsDisplaying = True
    If Not DispInvoice(lEntInvNum) Then
        MsgBox "Enter Invoice Number again."
        bIsDisplaying = False
        txtInvoiceNum.SelStart = 1
        txtInvoiceNum.SelLength = Len(CStr(lEntInvNum))
        txtInvoiceNum.SetFocus
        MN
        Exit Sub
    End If
    bIsDisplaying = False
    ClrDM
    txtDMDate.Text = Format(Now, "mm/dd/yy")
    
Else
    'Retrieve the Selected DM
    bIsDisplaying = True
    If Not DispDM(lCurrDMNum) Then
        MsgBox "Credit Memo could not be Retrieved. Try again."
        sMode = ""
        bIsDisplaying = False
        txtDMNum.SetFocus
        Exit Sub
    End If
    bIsDisplaying = False
    'handle controls
    ContAfterRetrieve
End If
MN
Exit Sub
ErrorHandle:
If OraDatabase.LastServerErr <> 0 Then
    glSaveErr = OraDatabase.LastServerErr
    gsSaveError = OraDatabase.LastServerErrText
    OraDatabase.LastServerErrReset
Else 'Must be some non-Oracle error.
    glSaveErr = Err
    gsSaveError = Error
    Err.Clear
End If
Select Case glSaveErr
    Case 0:
    Case Else:
        gsMsg1 = "Unexpected error in " & sModule
        gsMsg2 = "Error Number: " & glSaveErr & " Error Descr: " & gsSaveError
        gEH.ErrorNotify gsMsg1, _
                       gsMsg2, _
                       glSaveErr, _
                       gsSaveError, _
                       sModule & "txtInvoiceNum_LostFocus"
End Select
MN
End Sub

Private Sub ClrScr()
Dim oCon As Control
bIsClearing = True
For Each oCon In Me.Controls
    If TypeName(oCon) = "TextBox" Then oCon.Text = ""
Next
ssgrdInvoice.RemoveAll
ssgrdDM.RemoveAll
bIsClearing = False
'txtDMNum.Locked = False
End Sub

Private Sub ClrDM()
Dim oCon As Control
bIsClearing = True
For Each oCon In Me.Controls
    If TypeName(oCon) = "TextBox" Then
        If oCon.Container.Name = "Frame2" Then oCon.Text = ""
    End If
Next
bIsClearing = False
End Sub

Private Function DispInvoice(alInvNum As Long) As Boolean
On Error GoTo ErrorHnd
Dim dsBillingDisp As Object
Dim dsInvoiceTotal As Object
Dim dsDMTotal As Object
Dim dsDM As Object
Dim dsVESSEL_PROFILE As Object
Dim dsCUSTOMER_PROFILE As Object
Dim sSql As String
Dim sGrdItem As String
    'check for the validity of invoice num
    sSql = "SELECT * FROM BILLING WHERE INVOICE_NUM = " & alInvNum
    sSql = sSql & " AND SERVICE_STATUS = 'INVOICED'"
    Set dsBillingDisp = OraDatabase.dbcreatedynaset(sSql, 0&)
    If dsBillingDisp.eof And dsBillingDisp.BOF Then
        DispInvoice = False
        Exit Function
    End If
    'Get from Vessel Profile table based on LR Num
    sSql = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " _
              & "'" & dsBillingDisp.fields("LR_NUM").Value & "'"
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sSql, 0&)
    
    'Get from Customer table based on Customer Code
    sSql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " _
              & "'" & dsBillingDisp.fields("CUSTOMER_ID").Value & "'"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sSql, 0&)
    'Get the invoice total
    sSql = "SELECT SUM(SERVICE_AMOUNT) FROM BILLING WHERE INVOICE_NUM = " & alInvNum
    Set dsInvoiceTotal = OraDatabase.dbcreatedynaset(sSql, 0&)
    
     'Get the Credit Memo total
    sSql = "SELECT SUM(SERVICE_AMOUNT) FROM BILLING WHERE ORIG_INVOICE_NUM = " & alInvNum
    Set dsDMTotal = OraDatabase.dbcreatedynaset(sSql, 0&)
    If Not dsDMTotal.eof And Not dsDMTotal.BOF Then
        If Not IsNull(dsDMTotal.fields(0).Value) Then dblCurrInvCrTot = dsDMTotal.fields(0).Value
    End If
    
    'Get Credit Memos
    sSql = "SELECT * FROM BILLING WHERE ORIG_INVOICE_NUM = " & alInvNum
    Set dsDM = OraDatabase.dbcreatedynaset(sSql, 0&)
    
    'Display info
    txtInvoiceDate.Text = dsBillingDisp.fields("INVOICE_DATE").Value
    txtCustomerID.Text = dsBillingDisp.fields("CUSTOMER_ID").Value
    txtCustomerName.Text = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
    txtVesselNum.Text = dsBillingDisp.fields("LR_NUM").Value
    txtVesselName.Text = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
    txtInvoiceTotal.Text = Format(dsInvoiceTotal.fields(0).Value, "###.00")
    'fill up the Inv grid
    ssgrdInvoice.RemoveAll
    While Not dsBillingDisp.eof
        sGrdItem = Format(dsBillingDisp.fields("SERVICE_DATE").Value, "mm/dd/yy") + Chr(9)
        sGrdItem = sGrdItem & dsBillingDisp.fields("SERVICE_CODE").Value + Chr(9)
        sGrdItem = sGrdItem & dsBillingDisp.fields("SERVICE_DESCRIPTION").Value & "" + Chr(9)
        sGrdItem = sGrdItem & Format(dsBillingDisp.fields("SERVICE_AMOUNT").Value, "###.00") + Chr(9)
        sGrdItem = sGrdItem & dsBillingDisp.fields("ASSET_CODE").Value & "" + Chr(9)
        sGrdItem = sGrdItem & dsBillingDisp.fields("COMMODITY_CODE").Value
        ssgrdInvoice.AddItem sGrdItem
    
        dsBillingDisp.MoveNext
    Wend
    'fill up the DM grid
    ssgrdDM.RemoveAll
    If Not dsDM.eof And Not dsDM.BOF Then
        While Not dsDM.eof
            sGrdItem = dsDM.fields("BILLING_NUM").Value & ","
            sGrdItem = sGrdItem & Format(dsDM.fields("SERVICE_DATE").Value, "mm/dd/yy") & ","
            sGrdItem = sGrdItem & dsDM.fields("SERVICE_CODE").Value & ","
            sGrdItem = sGrdItem & dsDM.fields("SERVICE_DESCRIPTION").Value & ","
            sGrdItem = sGrdItem & dsDM.fields("SERVICE_STATUS").Value & ","
            sGrdItem = sGrdItem & Format(Abs(dsDM.fields("SERVICE_AMOUNT").Value), "###.00")
'            sGrdItem = dsDM.fields("BILLING_NUM").Value & ","
'            sGrdItem = sGrdItem & Format(dsDM.fields("SERVICE_DATE").Value, "mm/dd/yy") + Chr(9)
'            sGrdItem = sGrdItem & dsDM.fields("SERVICE_CODE").Value + Chr(9)
'            sGrdItem = sGrdItem & dsDM.fields("SERVICE_DESCRIPTION").Value + Chr(9)
'            sGrdItem = sGrdItem & dsDM.fields("SERVICE_STATUS").Value + Chr(9)
'            sGrdItem = sGrdItem & dsDM.fields("SERVICE_AMOUNT").Value
            ssgrdDM.AddItem sGrdItem
        
            dsDM.MoveNext
        Wend
    End If
    DispInvoice = True
Exit Function
ErrorHnd:
DispInvoice = False
End Function

Private Function UpdateBilling(ByVal asAction As String) As Boolean
    Dim i, j As Integer
    Dim lRecCount As Long
    Dim RowNum As Integer
    Dim iCol As Integer
    Dim bAddNew As Boolean
    Dim sSql As String
    Dim dsBILLING As Object
    Dim dsBILLING_MAX As Object
    
    
    bAddNew = False
    
    'Lock all the required tables in exclusive mode, try 10 times
    On Error Resume Next
    
    For i = 0 To 9
        OraDatabase.LastServerErrReset
        sSql = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
        lRecCount = OraDatabase.EXECUTESQL(sSql)
        If OraDatabase.LastServerErr = 0 Then Exit For
    Next 'i
        
    If OraDatabase.LastServerErr <> 0 Then
        OraDatabase.LastServerErr
        MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
        Exit Function
    End If
    
    On Error GoTo 0
    
    On Error GoTo lerrHand
    OraSession.begintrans
    
    If asAction = "ADD" And lCurrDMNum = 0 Then
        bAddNew = True
    End If
    If bAddNew Then
        sSql = " SELECT * FROM BILLING"
        Set dsBILLING = OraDatabase.dbcreatedynaset(sSql, 0&)
        dsBILLING.AddNew
        sSql = "SELECT MAX(BILLING_NUM) FROM BILLING"
        Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(sSql, 0&)
        Dim lCurrBillingNum As Long
        
        If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
            If IsNull(dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value) Then
                dsBILLING.fields("BILLING_NUM").Value = 1
                lCurrBillingNum = 1
            Else
                dsBILLING.fields("BILLING_NUM").Value = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
                lCurrBillingNum = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
            End If
        Else
            dsBILLING.fields("BILLING_NUM").Value = 1
            lCurrBillingNum = 1
        End If
    
    Else
        sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & lCurrDMNum
        Set dsBILLING = OraDatabase.dbcreatedynaset(sSql, 0&)
        dsBILLING.Edit
        lCurrBillingNum = lCurrDMNum
    End If
              
    dsBILLING.fields("LR_NUM").Value = Trim(txtVesselNum.Text)
    dsBILLING.fields("CUSTOMER_ID").Value = Trim(txtCustomerID.Text)
    dsBILLING.fields("COMMODITY_CODE").Value = Trim(txtCommCode.Text)
    dsBILLING.fields("SERVICE_CODE").Value = Trim(txtServiceCode.Text)
    dsBILLING.fields("SERVICE_DATE").Value = Trim(txtServiceDate.Text)
    dsBILLING.fields("SERVICE_DESCRIPTION").Value = UCase(Trim(txtDescription.Text))
    dsBILLING.fields("SERVICE_AMOUNT").Value = "-" & Trim(txtAmount.Text)
    'dsBILLING.Fields("PAGE_NUM").Value = Val(grdData.Columns(7).Value)
    dsBILLING.fields("EMPLOYEE_ID").Value = lUser
    dsBILLING.fields("SERVICE_STATUS").Value = "PRECREDIT"
    dsBILLING.fields("ARRIVAL_NUM").Value = 1
    
    dsBILLING.fields("INVOICE_NUM").Value = 0
    dsBILLING.fields("INVOICE_DATE").Value = txtDMDate.Text
    dsBILLING.fields("ORIG_INVOICE_NUM").Value = Trim(txtInvoiceNum.Text)
    
'    dsBILLING.Fields("SERVICE_QTY").Value = 0
    dsBILLING.fields("SERVICE_NUM").Value = 1
'    dsBILLING.Fields("THRESHOLD_QTY").Value = 0
'    dsBILLING.Fields("LEASE_NUM").Value = 0
'    dsBILLING.Fields("SERVICE_UNIT").Value = ""
    dsBILLING.fields("SERVICE_RATE").Value = 0
'    dsBILLING.Fields("LABOR_RATE_TYPE").Value = ""
'    dsBILLING.Fields("LABOR_TYPE").Value = ""
    dsBILLING.fields("CARE_OF").Value = 1
    dsBILLING.fields("BILLING_TYPE").Value = "DM"
    dsBILLING.fields("SERVICE_START").Value = txtDMDate.Text
    dsBILLING.fields("SERVICE_STOP").Value = txtDMDate.Text
    dsBILLING.fields("SERVICE_DATE").Value = txtDMDate.Text
    
    'Added this field for Asset Coding.  06.21.2001 LJG
    
    dsBILLING.fields("ASSET_CODE").Value = Trim(txtAssetCode.Text)
    dsBILLING.Update
    lCurrDMNum = lCurrBillingNum
    dblCurrDMAmt = CDbl(txtAmount.Text) * -1

lerrHand:
    If OraDatabase.LastServerErr <> 0 Then
        MsgBox "Error in Credit Memo # " & lCurrDMNum & vbCrLf & vbCrLf & Err.Description, vbExclamation, "frmCreditMemo - UpdateBilling"
        OraSession.rollback
        OraDatabase.LastServerErrReset
        Exit Function
    Else
        OraSession.COMMITTRANS
        MsgBox "SAVE SUCCESSFUL", vbInformation, "SAVE"
        UpdateBilling = True
    End If
End Function

Public Function fnMaxInvNum() As Long
    Dim SqlStmt As String
    Dim dsBILLING_INV As Object
    
    SqlStmt = "SELECT MAX(INVOICE_NUM) MAX_NUM FROM BILLING"
    
    Set dsBILLING_INV = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    
    
    If dsBILLING_INV.RecordCount > 0 Then
        If dsBILLING_INV.fields("MAX_NUM").Value < 980200000 Then
            fnMaxInvNum = 980200001

        Else
            fnMaxInvNum = dsBILLING_INV.fields("MAX_NUM").Value + 1

        End If

    Else
        fnMaxInvNum = 1
    End If

    
    
End Function

Private Function DispDM(alDMNum As Long) As Boolean
Dim sSql As String
Dim dsBILLING As Object
Dim lEntInvNum As Long
On Error GoTo errHandle
sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & alDMNum & " AND SERVICE_STATUS IN ('PRECREDIT','CREDITMEMO')"
Set dsBILLING = OraDatabase.dbcreatedynaset(sSql, 0&)
If dsBILLING.BOF And dsBILLING.eof Then
    MsgBox "Credit Memo # " & alDMNum & "not found."
    DispDM = False
    Exit Function
End If

If Not IsNull(dsBILLING.fields("ORIG_INVOICE_NUM").Value) Then
    lEntInvNum = dsBILLING.fields("ORIG_INVOICE_NUM").Value
Else
    MsgBox "The Credit Memo is not pointing to any Invoice"
    DispDM = False
    Exit Function
End If

'display invoice
If Not DispInvoice(lEntInvNum) Then
    MsgBox "Unexpected Error displaying Invoice # " & lEntInvNum
    DispDM = False
    Exit Function
End If

lCurrInvNum = lEntInvNum
txtInvoiceNum.Text = lEntInvNum
'display DM
txtDMInvoiceNum.Text = dsBILLING.fields("INVOICE_NUM").Value & ""
txtDMNum.Text = dsBILLING.fields("BILLING_NUM").Value
If Not IsNull(dsBILLING.fields("INVOICE_DATE").Value) Then
    txtDMDate.Text = dsBILLING.fields("INVOICE_DATE").Value
End If

If Not IsNull(dsBILLING.fields("SERVICE_CODE").Value) Then
    txtServiceCode.Text = dsBILLING.fields("SERVICE_CODE").Value
End If
    
If Not IsNull(dsBILLING.fields("SERVICE_DATE").Value) Then
    txtServiceDate.Text = dsBILLING.fields("SERVICE_DATE").Value
End If
    
If Not IsNull(dsBILLING.fields("ASSET_CODE").Value) Then
    txtAssetCode.Text = dsBILLING.fields("ASSET_CODE").Value
End If
    
If Not IsNull(dsBILLING.fields("COMMODITY_CODE").Value) Then
    txtCommCode.Text = dsBILLING.fields("COMMODITY_CODE").Value
End If
    
If Not IsNull(dsBILLING.fields("SERVICE_DESCRIPTION").Value) Then
    txtDescription.Text = dsBILLING.fields("SERVICE_DESCRIPTION").Value
End If
    
If Not IsNull(dsBILLING.fields("SERVICE_AMOUNT").Value) Then
    dblCurrDMAmt = dsBILLING.fields("SERVICE_AMOUNT").Value
    txtAmount.Text = Format(Abs(dsBILLING.fields("SERVICE_AMOUNT").Value), "###.00")
End If

lCurrDMNum = alDMNum

'Check status field and set Enabled property of buttons
If dsBILLING.fields("SERVICE_STATUS").Value = "PRECREDIT" Then
    bPreDM = True
Else
    bPreDM = False
End If
DispDM = True
Exit Function
errHandle:
If OraDatabase.LastServerErr <> 0 Then
    MsgBox "Error occured while displaying DM #" & alDMNum & vbCrLf & OraDatabase.LastServerErrText
Else 'Must be some non-Oracle error.
    MsgBox "Error occured while displaying DM #" & alDMNum & vbCrLf & "VB:" & Err & " " & Error(Err)
End If
DispDM = False

End Function

Private Function ReadyToPrintBni() As Boolean
Dim dsPrtDM As Object
Dim dsCUSTOMER_PROFILE As Object

Dim sSql As String
Dim sCityStateZip As String
Dim iline As Integer
Dim iCustId As Long
Dim iLRNUM As Integer
Dim sServStatus As String
Dim iRow As Long
Dim iNum As Integer
Dim sInvoiceNum As String
Dim sInvDt As String
Dim iPos1 As Integer
Dim sBillNo As String
Dim i As Integer
Dim sDesp As String
Dim sFLine As String

On Error GoTo ErrHndl
Dim iPos2 As Integer
Dim sBegStr As String
iCustId = 0
iLRNUM = 0
iline = 0
iNum = 0

sSql = "DELETE FROM PREINVOICE"
OraDatabase.EXECUTESQL sSql

stbarDM.SimpleText = "PROCESSING CREDIT MEMO..."

sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & lCurrDMNum
          
Set dsPrtDM = OraDatabase.dbcreatedynaset(sSql, 0&)
If Not dsPrtDM.eof And Not dsPrtDM.BOF Then
    dsPrtDM.MoveFirst
    sBillNo = dsPrtDM.fields("billing_num").Value
    'Get from Customer table based on Customer Code
    sSql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " _
              & "'" & dsPrtDM.fields("CUSTOMER_ID").Value & "'"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sSql, 0&)

    'Check to see if we need to change the invoice number and headings on the page
            
    
    iCustId = dsPrtDM.fields("CUSTOMER_ID").Value
    iLRNUM = dsPrtDM.fields("LR_NUM").Value
    sServStatus = dsPrtDM.fields("SERVICE_STATUS").Value
    sInvoiceNum = dsPrtDM.fields("INVOICE_NUM").Value & ""
    iNum = 0
    iNum = iNum + 1
    iRow = iRow + 1
    If sServStatus = "CREDITMEMO" Then
        Call PreInv_AddNew(iRow, Space(227) & sInvoiceNum & "C", 1, 0)
    Else
        Call PreInv_AddNew(iRow, "", 1, 0)
    End If
'    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, "", 0, 0)
    
'    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    If sServStatus = "CREDITMEMO" Then
        Call PreInv_AddNew(iRow, Space(227) & Format(dsPrtDM.fields("INVOICE_DATE").Value, "mm/dd/yy"), 0, 0)
    Else
        Call PreInv_AddNew(iRow, Space(227) & Format(Now, "mm/dd/yy"), 0, 0)
    End If
    Dim iCtr As Integer
    
'    If sServStatus = "CREDITMEMO" Then
        iCtr = 7
'    Else
'        iRow = iRow + 1
'        iNum = iNum + 1
'        Call PreInv_AddNew(iRow, Space(200) & "BILLING NO. " & lCurrDMNum, 0, 0)
'
'        iCtr = 6
'    End If
    For iline = 1 To iCtr
        
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, "", 0, 0)
    Next iline
    
       
    If Not IsNull(dsPrtDM.fields("CARE_OF")) Then
        If (dsPrtDM.fields("CARE_OF").Value = "1") Or (dsPrtDM.fields("CARE_OF").Value = "Y") Then
'            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(7) & fnVesselName(iLRNUM), 0, 0)

'            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(7) & "C/O " & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
        Else
'            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(7) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
        End If
    Else
'        If iNum = 54 Then Call NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, Space(7) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
    End If
    
    
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value) Then
'        If iNum = 54 Then Call NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, Space(7) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value, 0, 0)
    End If
    
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) Then
        If Trim(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) <> "" Then
'        If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(7) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value, 0, 0)
        End If
    End If
    sCityStateZip = ""
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value) Then
        sCityStateZip = dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value
    End If
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value) Then
        sCityStateZip = sCityStateZip & ", " & dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value
    End If
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value) Then
        sCityStateZip = sCityStateZip & " - " & dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value
    End If
'    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, Space(7) & sCityStateZip, 0, 0)
    
    If dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value <> "US" Then
'        If iNum = 54 Then Call NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, Space(7) & fnCountryName(dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value), 0, 0)
    End If

    For iline = 1 To 6
'        If iNum = 54 Then Call NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, "", 0, 0)
    Next iline
    
    sBillNo = dsPrtDM.fields("Billing_Num").Value
    iNum = iNum + 1
    iRow = iRow + 1
    If sServStatus = "CREDITMEMO" Then
        Call PreInv_AddNew(iRow, Space(110) & "**CREDIT MEMO**", 0, 0)
    Else
        Call PreInv_AddNew(iRow, Space(100) & "**DRAFT CREDIT MEMO**", 0, 0)
    End If
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, "", 0, 0)
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, "", 0, 0)
    iNum = iNum + 1
    iRow = iRow + 1
    sFLine = "Please be advised that we have issued your account a credit for Invoice #" & _
    dsPrtDM.fields("ORIG_INVOICE_NUM").Value
'    If Len(sFLine) > 55 Then
'
'        iPos1 = InStr(55, sFLine, " ")
'
'        If iPos1 = 0 Then
''            If iNum = 54 Then Call NEW_PAGE
'            iNum = iNum + 1
'            iRow = iRow + 1
'            Call PreInv_AddNew(iRow, Space(44) & sFLine, 0, 0)
'        Else
''            If iNum = 54 Then Call NEW_PAGE
'            iNum = iNum + 1
'            iRow = iRow + 1
'            Call PreInv_AddNew(iRow, Space(44) & Trim(Mid$(sFLine, 1, iPos1)), 0, 0)
'
''            If iNum = 54 Then Call NEW_PAGE
'            iNum = iNum + 1
'            iRow = iRow + 1
'            Call PreInv_AddNew(iRow, Space(44) & Mid$(sFLine, iPos1 + 1), 0, 0)
'        End If
'    Else
''        If iNum = 54 Then Call NEW_PAGE
'        iNum = iNum + 1
'        iRow = iRow + 1
'        Call PreInv_AddNew(iRow, Space(44) & Trim(sFLine), 0, 0)
'    End If
    Call PreInv_AddNew(iRow, Space(44) & sFLine, 0, 0)
    For iline = 1 To 3
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, "", 0, 0)
    Next iline
    If sServStatus = "CREDITMEMO" Then
        sBegStr = Space(16)
    Else
        sBegStr = Space(12) & "Bill No." & sBillNo & Space(4)
    End If
    If Len(dsPrtDM.fields("SERVICE_DESCRIPTION").Value) > 55 Then
        
        iPos1 = InStr(55, dsPrtDM.fields("SERVICE_DESCRIPTION").Value, " ")
        
        If iPos1 = 0 Then
'            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(5) & Format(dsPrtDM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
            & sBegStr _
            & Trim(dsPrtDM.fields("SERVICE_DESCRIPTION").Value), 0, Format(dsPrtDM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
        Else
'            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(5) & Format(dsPrtDM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
                & sBegStr _
                & Trim(Mid$(dsPrtDM.fields("SERVICE_DESCRIPTION").Value, 1, iPos1)), 0, Format(dsPrtDM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
            
'            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(44) & Mid$(dsPrtDM.fields("SERVICE_DESCRIPTION").Value, iPos1 + 1), 0, 0)
        End If
    Else
'        If iNum = 54 Then Call NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, Space(5) & Format(dsPrtDM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
                & sBegStr _
                & Trim(dsPrtDM.fields("SERVICE_DESCRIPTION").Value), 0, Format(dsPrtDM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
    End If
    
Else
    MsgBox "Credit Memo not found !", vbInformation + vbExclamation, "CREDIT MEMO"
    ReadyToPrintBni = False
    Exit Function
End If
    
For iline = 1 To 2
'    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, "", 0, 0)
Next iline
            
'If iNum = 54 Then Call NEW_PAGE
iNum = iNum + 1
iRow = iRow + 1
Call PreInv_AddNew(iRow, Space(5) & "-------------------------------------------" _
& "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
                                
'If iNum = 54 Then Call NEW_PAGE
iNum = iNum + 1
iRow = iRow + 1
Call PreInv_AddNew(iRow, Space(140) & "CREDIT TOTAL : ", 0, Format(dsPrtDM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
               
'If iNum = 54 Then Call NEW_PAGE
iNum = iNum + 1
iRow = iRow + 1
Call PreInv_AddNew(iRow, Space(5) & "-------------------------------------------" _
& "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)

AddInvDt IIf(sServStatus = "CREDITMEMO", sInvoiceNum, sBillNo), sServStatus
ReadyToPrintBni = True

Exit Function

ErrHndl:
ReadyToPrintBni = False
End Function

Public Function fnVesselName(LrNum As Integer) As String
    Dim SqlStmt As String
    Dim dsVESSEL_PROFILE As Object
    
    SqlStmt = "SELECT Vessel_Name FROM Vessel_Profile where lr_num=" & LrNum
    
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsVESSEL_PROFILE.RecordCount > 0 Then
        If Not IsNull(dsVESSEL_PROFILE.fields("VESSEL_NAME").Value) Then
            fnVesselName = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
        Else
            fnVesselName = ""
        End If
    Else
        fnVesselName = ""
    End If
End Function

Sub PreInv_AddNew(RowNum As Long, Row_Text As String, eof As Integer, Amt As Double)
Dim dsPreInv As Object
Set dsPreInv = OraDatabase.dbcreatedynaset("SELECT * FROM PREINVOICE", 0&)
    With dsPreInv
        .AddNew
        .fields("Row_Num").Value = RowNum
        .fields("Text").Value = Row_Text
        .fields("eof").Value = eof
        .fields("AMT").Value = Amt
        .Update
    End With
End Sub

Sub CCDS_PreInv_AddNew(RowNum As Long, Row_Text As String, eof As Integer, Amt As Double, Rate As Double)
Dim dsPreInv As Object
Set dsPreInv = OraDatabase.dbcreatedynaset("SELECT * FROM PREINVOICE", 0&)
    With dsPreInv
        .AddNew
        .fields("Row_Num").Value = RowNum
        .fields("Text").Value = Row_Text
        .fields("eof").Value = eof
        .fields("AMT").Value = Amt
        .fields("RATE").Value = Rate
        .Update
    End With
End Sub

Public Sub SubPreInv()
    Dim SqlStmt As String
    Dim dsPreInv As Object
    SqlStmt = "SELECT * FROM PreInvoice"
    
    Set dsPreInv = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If dsPreInv.RecordCount <> 0 Then
'        raSession.begintrans
        OraDatabase.DbExecuteSQL ("DELETE FROM PreInvoice")
'        OraSession.committrans
    End If

End Sub

Public Function fnCountryName(Country_Code As String) As String
'Get from Customer table based on Customer Code
    
    Dim dsCountry As Object
    Dim SqlStmt As String
    
    SqlStmt = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE =" _
                & "'" & Country_Code & "'"
    Set dsCountry = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsCountry.RecordCount > 0 Then
        If Not IsNull(dsCountry.fields("Country_Name").Value) Then
            fnCountryName = dsCountry.fields("Country_Name").Value
        Else
            fnCountryName = ""
        End If
    Else
        fnCountryName = ""
    End If

    
End Function

Public Sub CalcBal()
Dim dInvAmt As Double
Dim dDMAmt As Double
Dim dAdjAmt As Double
If IsNumeric(txtInvoiceTotal.Text) Then
    dInvAmt = txtInvoiceTotal.Text
    dDMAmt = txtAmount.Text
    dAdjAmt = dInvAmt + dblCurrInvCrTot - dblCurrDMAmt - dDMAmt
    txtAdjTotal.Text = Format(dAdjAmt, "#.00")
End If
End Sub

Private Sub DisableAll()
cmdNewCreateDM.Enabled = False
cmdEditSave.Enabled = False
cmdRetrieve.Enabled = False
cmdDeleteCancel.Enabled = False
cmdPrint.Enabled = False
cmdDone.Enabled = False
cmdExit.Enabled = False


End Sub

Private Sub ContAfterRetrieve()
DisableAll
txtInvoiceNum.Locked = True
txtDMNum.Locked = True

cmdEditSave.Caption = "&Edit"
cmdDeleteCancel.Caption = "&Delete"
cmdNewCreateDM.Caption = "Create DM"
If bPreDM Then
    cmdNewCreateDM.Enabled = True
    cmdEditSave.Enabled = True
    cmdDeleteCancel.Enabled = True
Else
    cmdNewCreateDM.Enabled = False
    cmdEditSave.Enabled = False
    cmdDeleteCancel.Enabled = False
End If

cmdPrint.Enabled = True
cmdDone.Enabled = True

stbarDM.SimpleText = ""
sMode = ""
End Sub

Private Function ChkExistingDM(alOrigInvNum As Long) As Boolean
Dim sSql As String
Dim dsDMCount As Object
Dim frmPV As frmDMPV
On Error GoTo errHandleChkExistingDM
sSql = "SELECT COUNT(*) FROM BILLING WHERE ORIG_INVOICE_NUM = " & alOrigInvNum
Set dsDMCount = OraDatabase.dbcreatedynaset(sSql, 0&)
If Not dsDMCount.BOF And Not dsDMCount.eof Then
    If Not IsNull(dsDMCount.fields(0).Value) Then
        If dsDMCount.fields(0).Value > 0 Then
            ChkExistingDM = True
        End If
    End If
End If


Exit Function
errHandleChkExistingDM:
    MsgBox "Error occured while checking for Credit Memos", , "- frmCreditMemo - ChkExistingDM"
    MN
End Function

Private Function ConfirmDM(abDisableAdd As Boolean) As Long
Dim sSql As String
Dim frmPV As frmDMPV
'DISPLAY
sSql = "SELECT BILLING_NUM, SERVICE_DATE, SERVICE_AMOUNT, SERVICE_STATUS "
sSql = sSql & "FROM BILLING WHERE ORIG_INVOICE_NUM = " & lCurrInvNum
Set frmPV = New frmDMPV
frmPV.fsSql = sSql
frmPV.fbDisableAdd = abDisableAdd
frmPV.fbCancel = True
frmPV.Show 1

If frmPV.fbCancel Then
    Unload frmPV
    Set frmPV = Nothing
    Exit Function
End If

If frmPV.fbNew Then
    bChkAddNew = True
    Unload frmPV
    Set frmPV = Nothing

Else
    ConfirmDM = CLng(frmPV.fsVal)
    Unload frmPV
    Set frmPV = Nothing
End If
End Function

Private Function ReadyToPrintCcds() As Boolean
Dim dsPrtDM As Object
Dim dsCUSTOMER_PROFILE As Object

Dim sSql As String
Dim sCityStateZip As String
Dim iline As Integer
Dim iCustId As Long
Dim iLRNUM As Integer
Dim sServStatus As String
Dim iRow As Long
Dim iNum As Integer
Dim sInvoiceNum As String
Dim sInvDt As String
Dim iPos1 As Integer
Dim sBillNo As String
Dim i As Integer
Dim sDesp As String
On Error GoTo ErrHndl
Dim iPos2 As Integer
Dim iAdd As Integer
Dim sFLine As String

iCustId = 0
iLRNUM = 0
iline = 0
iNum = 0

sSql = "DELETE FROM PREINVOICE"
OraDatabase.EXECUTESQL sSql

stbarDM.SimpleText = "PROCESSING CREDIT MEMO..."

sSql = "SELECT * FROM BILLING WHERE BILLING_NUM = " & lCurrDMNum
          
Set dsPrtDM = OraDatabase.dbcreatedynaset(sSql, 0&)
If Not dsPrtDM.eof And Not dsPrtDM.BOF Then
    dsPrtDM.MoveFirst
    sBillNo = dsPrtDM.fields("billing_num").Value
    'Get from Customer table based on Customer Code
    sSql = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " _
              & "'" & dsPrtDM.fields("CUSTOMER_ID").Value & "'"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sSql, 0&)

    'Check to see if we need to change the invoice number and headings on the page
    iCustId = dsPrtDM.fields("CUSTOMER_ID").Value
    iLRNUM = dsPrtDM.fields("LR_NUM").Value
    sInvoiceNum = dsPrtDM.fields("INVOICE_NUM").Value & ""
    sServStatus = dsPrtDM.fields("SERVICE_STATUS").Value
    
    iNum = 0
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, "", 1, 0, 0)
    
    For iline = 1 To 3
'        If iNum = 54 Then Call CCDS_NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    Next iline
    
'    If iNum = 54 Then Call CCDS_NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    If sServStatus = "CREDITMEMO" Then
        Call CCDS_PreInv_AddNew(iRow, Space(185) & "  Invoice No:  " & sInvoiceNum & "C", 0, 0, 0)
    Else
        Call CCDS_PreInv_AddNew(iRow, Space(188) & "  Invoice No:  ", 0, 0, 0)
    End If
'    If iNum = 54 Then Call CCDS_NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    
'    If iNum = 54 Then Call CCDS_NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    If sServStatus = "CREDITMEMO" Then
        Call CCDS_PreInv_AddNew(iRow, Space(190) & "Invoice Date: " & Format(dsPrtDM.fields("INVOICE_DATE").Value, "mm/dd/yy"), 0, 0, 0)
    Else
        Call CCDS_PreInv_AddNew(iRow, Space(190) & "Invoice Date: " & Format(Now, "mm/dd/yy"), 0, 0, 0)
    End If
    For iline = 1 To 6
'        If iNum = 54 Then Call CCDS_NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    Next iline
        
    iAdd = 0
    If Not IsNull(dsPrtDM.fields("CARE_OF")) Then
        If (dsPrtDM.fields("CARE_OF").Value = "1") Or (dsPrtDM.fields("CARE_OF").Value = "Y") Then
'            If iNum = 54 Then Call CCDS_NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(4) & fnVesselName(iLRNUM), 0, 0, 0)

'            If iNum = 54 Then Call CCDS_NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            'Call CCDS_PreInv_AddNew(iRow, Space(4) & iCustId & " " & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0, 0)
            Call CCDS_PreInv_AddNew(iRow, Space(4) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value & "   " & iCustId, 0, 0, 0)
        Else
'            If iNum = 54 Then Call CCDS_NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(4) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0, 0)
            iAdd = iAdd + 1
        End If
    Else
'        If iNum = 54 Then Call CCDS_NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(4) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0, 0)
        iAdd = iAdd + 1
    End If
    
    
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value) Then
'        If iNum = 54 Then Call CCDS_NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(4) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value, 0, 0, 0)
    Else
        iAdd = iAdd + 1
    End If
    
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) Then
'        If iNum = 54 Then Call CCDS_NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(4) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value, 0, 0, 0)
    Else
        iAdd = iAdd + 1
    End If
    sCityStateZip = ""
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value) Then
        sCityStateZip = dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value
    End If
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value) Then
        sCityStateZip = sCityStateZip & ", " & dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value
    End If
    If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value) Then
        sCityStateZip = sCityStateZip & " - " & dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value
    End If
'    If iNum = 54 Then Call CCDS_NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, Space(4) & sCityStateZip, 0, 0, 0)
    
    If dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value <> "US" Then
'        If iNum = 54 Then Call CCDS_NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(4) & fnCountryName(dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value), 0, 0, 0)
    Else
        iAdd = iAdd + 1
    End If

    For iline = 1 To 4 + iAdd
'        If iNum = 54 Then Call CCDS_NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    Next iline
    
    sBillNo = dsPrtDM.fields("BILLING_NUM").Value
    iNum = iNum + 1
    iRow = iRow + 1
    If sServStatus = "CREDITMEMO" Then
        Call CCDS_PreInv_AddNew(iRow, Space(105) & "**CREDIT MEMO**", 0, 0, 0)
    Else
        Call CCDS_PreInv_AddNew(iRow, Space(95) & "**DRAFT CREDIT MEMO**", 0, 0, 0)
    End If
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    
    sFLine = "Please be advised that we have issued your account a credit for invoice #" & _
    dsPrtDM.fields("ORIG_INVOICE_NUM").Value

'    iNum = iNum + 1
'    iRow = iRow + 1
'    Call CCDS_PreInv_AddNew(iRow, Space(41) & sFLine, 0, 0, 0)
    If Len(sFLine) > 40 Then
        
        iPos1 = InStr(40, sFLine, " ")
        
        If iPos1 = 0 Then
'            If iNum = 54 Then Call CCDS_NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(41) & sFLine, 0, 0, 0)
        Else
'            If iNum = 54 Then Call CCDS_NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(41) & Trim(Mid$(sFLine, 1, iPos1)), 0, 0, 0)
            
            Do While Len(Mid$(sFLine, iPos1 + 1)) > 40
                iPos2 = InStr(40, Mid$(sFLine, iPos1 + 1), " ")
                If iPos2 = 0 Then
                    Exit Do
                End If
'                If iNum = 54 Then Call CCDS_NEW_PAGE
                iNum = iNum + 1
                iRow = iRow + 1
                Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sFLine, iPos1 + 1, iPos2), 0, 0, 0)
                iPos1 = iPos1 + iPos2
            Loop
       
'            If iNum = 54 Then Call CCDS_NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sFLine, iPos1 + 1), 0, 0, 0)
        End If
    Else
'        If iNum = 54 Then Call CCDS_NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(41) & Trim(sFLine), 0, 0, 0)
    End If
    For i = 1 To 3
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
    Next
    If sServStatus = "CREDITMEMO" Then
        sDesp = dsPrtDM.fields("SERVICE_DESCRIPTION").Value
    Else
        sDesp = "Bill No." & sBillNo & "   " & dsPrtDM.fields("SERVICE_DESCRIPTION").Value
    End If
    
    iPos1 = 0
    iPos2 = 0
    
    If Len(sDesp) > 40 Then
        
        iPos1 = InStr(40, sDesp, " ")
        
        If iPos1 = 0 Then
'            If iNum = 54 Then Call CCDS_NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(5) & Format(dsPrtDM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
            & Space(22) & sDesp, 0, Format(dsPrtDM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), Format(dsPrtDM.fields("SERVICE_RATE").Value, "##,###,###,##0.000"))
        Else
'            If iNum = 54 Then Call CCDS_NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(5) & Format(dsPrtDM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
                & Space(22) & Trim(Mid$(sDesp, 1, iPos1)), 0, Format(dsPrtDM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), Format(dsPrtDM.fields("SERVICE_RATE").Value, "##,###,###,##0.000"))
            
            Do While Len(Mid$(sDesp, iPos1 + 1)) > 40
                iPos2 = InStr(40, Mid$(sDesp, iPos1 + 1), " ")
                If iPos2 = 0 Then
                    Exit Do
                End If
'                If iNum = 54 Then Call CCDS_NEW_PAGE
                iNum = iNum + 1
                iRow = iRow + 1
                Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sDesp, iPos1 + 1, iPos2), 0, 0, 0)
                iPos1 = iPos1 + iPos2
            Loop
       
'            If iNum = 54 Then Call CCDS_NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call CCDS_PreInv_AddNew(iRow, Space(41) & Mid$(sDesp, iPos1 + 1), 0, 0, 0)
        End If
    Else
'        If iNum = 54 Then Call CCDS_NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call CCDS_PreInv_AddNew(iRow, Space(5) & Format(dsPrtDM.fields("SERVICE_DATE").Value, "MM/DD/YY") _
                & Space(22) & Trim(sDesp), 0, Format(dsPrtDM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), Format(dsPrtDM.fields("SERVICE_RATE").Value, "##,###,###,##0.000"))
    End If
Else
    
       
    MsgBox "No Records Found For MISC_CCDS PREBILL !", vbInformation + vbExclamation, "CREDIT MEMO"
    ReadyToPrintCcds = False
    Exit Function
End If
    
For iline = 1 To 2
'    If iNum = 54 Then Call CCDS_NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    Call CCDS_PreInv_AddNew(iRow, "", 0, 0, 0)
Next iline
                                            
'If iNum = 54 Then Call CCDS_NEW_PAGE
iNum = iNum + 1
iRow = iRow + 1
Call CCDS_PreInv_AddNew(iRow, Space(155) & "CREDIT TOTAL : ", 0, Format(dsPrtDM.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"), 0)

AddInvDt IIf(sServStatus = "CREDITMEMO", sInvoiceNum, sBillNo), sServStatus
ReadyToPrintCcds = True

Exit Function

ErrHndl:
ReadyToPrintCcds = False
End Function

Private Sub AddInvDt(ByVal asNum As String, ByVal asNumType As String)
Dim sSql As String
Dim dsID As Object
Dim dsChk As Object
Dim sID As String

sSql = "SELECT * FROM INVOICEDATE WHERE TYPE = 'DM' AND START_INV_NO = " & asNum
Set dsChk = OraDatabase.dbcreatedynaset(sSql, 0&)
If dsChk.eof And dsChk.BOF Then
    sSql = "SELECT MAX(ID) MAXID FROM INVOICEDATE"
    Set dsID = OraDatabase.dbcreatedynaset(sSql, 0&)
    If dsID.RecordCount > 0 Then
        If Not IsNull(dsID.fields("MAXID").Value) Then
            sID = dsID.fields("MAXID").Value
        Else
            sID = 0
        End If
    Else
        sID = 0
    End If
    sID = sID + 1
    Call AddNewInvDt(CInt(sID), Format(Now, "MM/DD/YYYY"), IIf(asNumType = "CREDITMEMO", "I", "B"), "DM", "", "", asNum, asNum)
End If
End Sub

Sub AddNewInvDt(id As Integer, RnDt As Date, BType As String, sType As String, stDt As String, EdDt As String, sStInvNo As String, sEdInvNo As String)
    Dim dsINVDATE As Object
    Dim SqlStmt As String
    
    SqlStmt = "SELECT * FROM INVOICEDATE"
    Set dsINVDATE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        With dsINVDATE
            .AddNew
            .fields("ID").Value = id
            .fields("RUN_DATE").Value = RnDt
            .fields("BILL_TYPE").Value = sType
            .fields("TYPE").Value = BType
            If stDt <> "" Then .fields("START_DATE").Value = CDate(stDt)
            If EdDt <> "" Then .fields("CUTOFF_DATE").Value = CDate(EdDt)
            .fields("START_INV_NO").Value = sStInvNo
            .fields("END_INV_NO").Value = sEdInvNo
            .Update
        End With
    Else
        MsgBox OraSession.LastServerErrText, vbInformation, "BILLING"
    End If
End Sub

Private Function ValidData() As Boolean
    Dim oCon As Control
    Dim sConVal As String
    Dim sConTag As String
    Dim bError As Boolean
    bError = False
    If txtInvoiceNum.Text = "" Then
        MsgBox "Enter a Invoice Number"
        ValidData = False
        Exit Function
    End If
    For Each oCon In Me.Controls
        If TypeName(oCon) = "TextBox" Then
            sConVal = oCon.Text
            sConTag = oCon.Tag
            Select Case Left(sConTag, 1)
            Case "D"
                If sConVal = "" Then
                    MsgBox "Enter " & Mid(sConTag, 2)
                    bError = True
                Else
                    If Not IsDate(sConVal) Then
                        MsgBox "Enter Valid " & Mid(sConTag, 2)
                        bError = True
                    End If
                End If
            Case "N"
                If sConVal = "" Then
                    MsgBox "Enter " & Mid(sConTag, 2)
                    bError = True
                Else
                    If Not IsNumeric(sConVal) Then
                        MsgBox "Enter Valid " & Mid(sConTag, 2)
                        bError = True
                    End If
                End If
                
            Case "S"
                If sConVal = "" Then
                    MsgBox "Enter " & Mid(sConTag, 2)
                    bError = True
                End If
            
            End Select
        End If
        If bError Then Exit For
    Next
    If bError Then
        ValidData = False
        Exit Function
    Else
        ValidData = True
    End If

End Function


