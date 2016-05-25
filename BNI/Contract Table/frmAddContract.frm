VERSION 5.00
Begin VB.Form frmAddContract 
   Caption         =   "Add New Rate"
   ClientHeight    =   4575
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   14190
   LinkTopic       =   "Form1"
   ScaleHeight     =   4575
   ScaleWidth      =   14190
   StartUpPosition =   3  'Windows Default
   Begin VB.TextBox txtEndDate 
      Height          =   375
      Left            =   2520
      TabIndex        =   12
      Top             =   2280
      Width           =   1575
   End
   Begin VB.TextBox txtDescription 
      Height          =   375
      Left            =   2520
      MaxLength       =   100
      TabIndex        =   11
      Top             =   1080
      Width           =   3855
   End
   Begin VB.TextBox txtStartDate 
      Height          =   375
      Left            =   2520
      TabIndex        =   10
      Top             =   1680
      Width           =   1575
   End
   Begin VB.CommandButton cmdClose 
      Caption         =   "Close"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   8880
      TabIndex        =   9
      Top             =   3600
      Width           =   2775
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "Save"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   1800
      TabIndex        =   8
      Top             =   3600
      Width           =   2895
   End
   Begin VB.Label Label24 
      Alignment       =   2  'Center
      Caption         =   "No Optional Fields"
      Height          =   375
      Left            =   7560
      TabIndex        =   13
      Top             =   960
      Width           =   4695
   End
   Begin VB.Label lblVerifyStartDt 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   7
      Top             =   1680
      Width           =   375
   End
   Begin VB.Label lblVerifyEndDt 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   6
      Top             =   2280
      Width           =   375
   End
   Begin VB.Label lblVerifyDesc 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   375
      Left            =   120
      TabIndex        =   5
      Top             =   1080
      Width           =   375
   End
   Begin VB.Label Label8 
      Caption         =   "End Date"
      Height          =   375
      Left            =   600
      TabIndex        =   4
      Top             =   2280
      Width           =   1695
   End
   Begin VB.Label Label4 
      Caption         =   "Start Date"
      Height          =   375
      Left            =   600
      TabIndex        =   3
      Top             =   1680
      Width           =   1695
   End
   Begin VB.Label Label3 
      Alignment       =   2  'Center
      Caption         =   "OPTIONAL:"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   8760
      TabIndex        =   2
      Top             =   120
      Width           =   3015
   End
   Begin VB.Line Line2 
      X1              =   120
      X2              =   13560
      Y1              =   720
      Y2              =   720
   End
   Begin VB.Label Label2 
      Alignment       =   2  'Center
      Caption         =   "REQUIRED:"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   1920
      TabIndex        =   1
      Top             =   120
      Width           =   2895
   End
   Begin VB.Line Line1 
      X1              =   6840
      X2              =   6840
      Y1              =   120
      Y2              =   3000
   End
   Begin VB.Label Label1 
      Caption         =   "Description"
      Height          =   375
      Left            =   600
      TabIndex        =   0
      Top             =   1080
      Width           =   1695
   End
End
Attribute VB_Name = "frmAddContract"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Private Sub cmdClose_Click()
    Unload Me
End Sub


Private Sub cmdsave_Click()
    If Insert_Record() = True Then ' if the insert went through fine
'        If chkResetFields = 1 Then ' if they want to reset fields
            MsgBox "New Contract Entered."
            Call Reset_fields
'        End If
    Else
        ' do nothing
    End If
End Sub

Private Function Insert_Record() As Boolean
    Call ClearXs
    
    ' verify ALL values
    If ValidateDescription(txtDescription.Text) = False Then
        lblVerifyDesc.Caption = "X"
    End If
    If ValidateStartDate(txtStartDate.Text) = False Then
        lblVerifyStartDt.Caption = "X"
    End If
    If ValidateEndDate(txtEndDate.Text) = False Then
        lblVerifyEndDt.Caption = "X"
    End If

    If lblVerifyDesc.Caption = "X" Or lblVerifyStartDt.Caption = "X" Or lblVerifyEndDt = "X" Then
        MsgBox "Un-usable values detected.  Please change any box marked with a red X and resubmit."
        Insert_Record = False
        Exit Function
    End If
    
    SqlStmt = "INSERT INTO CONTRACT (DESCRIPTION, STARTDATE, ENDDATE) VALUES ('" & txtDescription.Text & "', TO_DATE('" & txtStartDate.Text & "', 'MM/DD/YYYY'), TO_DATE('" & txtEndDate.Text & "', 'MM/DD/YYYY'))"
    OraDatabase.ExecuteSQL (SqlStmt)
    Insert_Record = True
    
End Function

Private Sub ClearXs()
    lblVerifyDesc = ""
    lblVerifyStartDt = ""
    lblVerifyEndDt = ""
End Sub
Private Sub Reset_fields()
    txtDescription.Text = ""
    txtStartDate.Text = ""
    txtEndDate.Text = ""
End Sub


Private Sub Form_Load()
    
    Call Reset_fields
'    SqlStmt = "SELECT CONTRACTID FROM CONTRACT ORDER BY CONTRACTID"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'    While Not dsSHORT_TERM_DATA.EOF
'        cboContractID.AddItem dsSHORT_TERM_DATA.Fields("CONTRACTID").Value
'        dsSHORT_TERM_DATA.MoveNext
'    Wend
'    cboContractID.ListIndex = 0
'
'    cboWeekends.ListIndex = 0
'
'    cboHolidays.ListIndex = 0
'
'    cboBillDurationUnit.ListIndex = 0
'
'    cboServiceCode.ListIndex = 0
'
'    SqlStmt = "SELECT UOM FROM UNITS ORDER BY UOM"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'    While Not dsSHORT_TERM_DATA.EOF
'        cboBillByUnit.AddItem dsSHORT_TERM_DATA.Fields("UOM").Value
'        dsSHORT_TERM_DATA.MoveNext
'    Wend
'    cboBillByUnit.ListIndex = 0
'
'    cboXFERCredit.ListIndex = 0
'
'    cboArrivalType.ListIndex = 0
'
'    SqlStmt = "SELECT COMMODITY_CODE FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'    While Not dsSHORT_TERM_DATA.EOF
'        cboCommodity.AddItem dsSHORT_TERM_DATA.Fields("COMMODITY_CODE").Value
'        dsSHORT_TERM_DATA.MoveNext
'    Wend
'    cboCommodity.ListIndex = 0
'
'    cboCustomer.AddItem ""
'    SqlStmt = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'    While Not dsSHORT_TERM_DATA.EOF
'        cboCustomer.AddItem dsSHORT_TERM_DATA.Fields("CUSTOMER_ID").Value
'        dsSHORT_TERM_DATA.MoveNext
'    Wend
'    cboCustomer.ListIndex = 0
'
'    cboFromShipping.AddItem ""
'    SqlStmt = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'    While Not dsSHORT_TERM_DATA.EOF
'        cboFromShipping.AddItem dsSHORT_TERM_DATA.Fields("CUSTOMER_ID").Value
'        dsSHORT_TERM_DATA.MoveNext
'    Wend
'    cboFromShipping.ListIndex = 0
'
'    cboToShippingLine.AddItem ""
'    SqlStmt = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'    While Not dsSHORT_TERM_DATA.EOF
'        cboToShippingLine.AddItem dsSHORT_TERM_DATA.Fields("CUSTOMER_ID").Value
'        dsSHORT_TERM_DATA.MoveNext
'    Wend
'    cboToShippingLine.ListIndex = 0
'
'    cboWarehouse.AddItem ""
'    cboWarehouse.AddItem "A"
'    cboWarehouse.AddItem "B"
'    cboWarehouse.AddItem "C"
'    cboWarehouse.AddItem "D"
'    cboWarehouse.AddItem "E"
'    cboWarehouse.AddItem "F"
'    cboWarehouse.AddItem "G"
'    cboWarehouse.AddItem "H"
'    cboWarehouse.ListIndex = 0
'
'    cboBox.AddItem ""
'    cboBox.AddItem "1"
'    cboBox.AddItem "2"
'    cboBox.AddItem "3"
'    cboBox.AddItem "4"
'    cboBox.AddItem "5"
'    cboBox.AddItem "6"
'    cboBox.AddItem "7"
'    cboBox.AddItem "8"
'    cboBox.ListIndex = 0
'
'    cboStacking.AddItem ""
'    cboStacking.AddItem "S"
'    cboStacking.ListIndex = 0
'
'    cboBillToCust.AddItem ""
'    SqlStmt = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
'    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
'    While Not dsSHORT_TERM_DATA.EOF
'        cboBillToCust.AddItem dsSHORT_TERM_DATA.Fields("CUSTOMER_ID").Value
'        dsSHORT_TERM_DATA.MoveNext
'    Wend
'    cboBillToCust.ListIndex = 0



End Sub

