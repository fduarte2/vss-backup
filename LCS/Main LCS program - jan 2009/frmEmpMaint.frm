VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmEmpMaint 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Employee Maintenance"
   ClientHeight    =   7335
   ClientLeft      =   45
   ClientTop       =   285
   ClientWidth     =   11070
   Icon            =   "frmEmpMaint.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   7335
   ScaleWidth      =   11070
   StartUpPosition =   3  'Windows Default
   Begin VB.TextBox txtEmpId 
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   420
      Left            =   7200
      TabIndex        =   1
      Top             =   1680
      Width           =   3735
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "E&XIT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   9480
      TabIndex        =   19
      ToolTipText     =   "Return Back"
      Top             =   5520
      Width           =   1455
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "DE&LETE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   3720
      TabIndex        =   18
      ToolTipText     =   "Delete Employee Data"
      Top             =   6360
      Width           =   1455
   End
   Begin VB.CommandButton cmdAdd 
      Caption         =   "&ADD"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   120
      TabIndex        =   17
      ToolTipText     =   "Add Employee Data"
      Top             =   6360
      Width           =   1455
   End
   Begin VB.CommandButton cmdEdit 
      Caption         =   "E&DIT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   1920
      TabIndex        =   16
      ToolTipText     =   "Edit Employee Data"
      Top             =   6360
      Width           =   1455
   End
   Begin VB.ListBox lstEmpName 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   4620
      Left            =   120
      TabIndex        =   14
      ToolTipText     =   "Select an Employee to Edit/Delete"
      Top             =   1440
      Width           =   5055
   End
   Begin VB.CheckBox chkTempID 
      Caption         =   "Temporary ID"
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   8880
      TabIndex        =   0
      Top             =   1680
      Visible         =   0   'False
      Width           =   2055
   End
   Begin SSDataWidgets_B.SSDBCombo SSDBCombo1 
      Height          =   420
      Left            =   7200
      TabIndex        =   4
      Top             =   3480
      Width           =   3735
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      AllowInput      =   0   'False
      AllowNull       =   0   'False
      _Version        =   196616
      DataMode        =   2
      Cols            =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns(0).Width=   3200
      _ExtentX        =   6588
      _ExtentY        =   741
      _StockProps     =   93
      ForeColor       =   -2147483640
      BackColor       =   -2147483643
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Enabled         =   0   'False
   End
   Begin VB.TextBox txtSeniority 
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   420
      Left            =   7200
      TabIndex        =   5
      Top             =   4080
      Width           =   3735
   End
   Begin VB.CommandButton cmdUpdate 
      Caption         =   "&UPDATE"
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   5880
      TabIndex        =   6
      ToolTipText     =   "Update Changes"
      Top             =   5520
      Width           =   1455
   End
   Begin VB.CommandButton cmdCancel 
      Caption         =   "&CANCEL"
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   7680
      TabIndex        =   7
      ToolTipText     =   "Cancel Changes"
      Top             =   5520
      Width           =   1455
   End
   Begin VB.TextBox txtFirstName 
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   420
      Left            =   7200
      TabIndex        =   2
      Top             =   2280
      Width           =   3735
   End
   Begin SSDataWidgets_B.SSDBCombo SSDBCombo2 
      Height          =   420
      Left            =   7200
      TabIndex        =   3
      Top             =   2880
      Width           =   3735
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      AllowInput      =   0   'False
      AllowNull       =   0   'False
      _Version        =   196616
      DataMode        =   2
      Cols            =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   6588
      _ExtentY        =   741
      _StockProps     =   93
      BackColor       =   -2147483643
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Enabled         =   0   'False
   End
   Begin VB.Label Label3 
      Caption         =   "Employee Information"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   120
      TabIndex        =   15
      Top             =   1080
      Width           =   3135
   End
   Begin VB.Label Label2 
      Alignment       =   1  'Right Justify
      Caption         =   "Classification"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   5280
      TabIndex        =   13
      Top             =   3480
      Width           =   1740
   End
   Begin MSForms.Image Image1 
      Height          =   735
      Left            =   0
      Top             =   0
      Width           =   855
      BorderStyle     =   0
      SizeMode        =   1
      SpecialEffect   =   2
      Size            =   "1508;1296"
      Picture         =   "frmEmpMaint.frx":0442
   End
   Begin VB.Label Label1 
      Alignment       =   1  'Right Justify
      Caption         =   "Seniority"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   5280
      TabIndex        =   12
      Top             =   4080
      Width           =   1740
   End
   Begin VB.Label Label6 
      Alignment       =   2  'Center
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   18
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   855
      Left            =   960
      TabIndex        =   11
      Top             =   0
      Width           =   10095
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   11040
      Y1              =   960
      Y2              =   960
   End
   Begin VB.Label Label8 
      Alignment       =   1  'Right Justify
      Caption         =   "Name"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   5280
      TabIndex        =   10
      Top             =   2280
      Width           =   1740
   End
   Begin VB.Label Label12 
      Alignment       =   1  'Right Justify
      Caption         =   "Type"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   5280
      TabIndex        =   9
      Top             =   2880
      Width           =   1740
   End
   Begin VB.Label Label4 
      Alignment       =   1  'Right Justify
      Caption         =   "Employee ID"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   5280
      TabIndex        =   8
      Top             =   1680
      Width           =   1740
   End
End
Attribute VB_Name = "frmEmpMaint"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim EmplRS As Object, arrTypeID() As String
Dim arrSubTypeID() As String, arrSeniority() As String

'****************************************
'To Check for Blank Entries
'****************************************
Private Function CheckforNull()
  If Trim(txtEmpId) = "" Or Trim(txtFirstName) = "" Or Trim(SSDBCombo2.Text) = "" Then
    MsgBox "Please make sure that all the Employee details are entered", vbOKOnly + vbInformation, "Insufficient Data"
    CheckforNull = True
  End If
End Function

'****************************************
'To Clear the Text Boxes
'****************************************
Private Sub ClearControls()
  txtEmpId = ""
  chkTempID.Value = 0
  txtFirstName = ""
  txtSeniority = ""
  SSDBCombo1.Text = ""
  SSDBCombo2.Text = ""
End Sub

'****************************************
'To Enable / Disable Command Buttons
'****************************************
Private Sub EnableControls(benableFlag As Boolean)
  cmdDelete.Enabled = benableFlag
  cmdEdit.Enabled = benableFlag
  cmdAdd.Enabled = benableFlag
  cmdUpdate.Enabled = Not benableFlag
  cmdCancel.Enabled = Not benableFlag
  lstEmpName.Enabled = benableFlag
End Sub

'****************************************
'To Add New Employees
'****************************************
Private Sub cmdAdd_Click()
  OraSession.BeginTrans     'Begin the Transaction
  EmplRS.AddNew
  txtEmpId.Enabled = True
  chkTempID.Enabled = True
  txtFirstName.Enabled = True
  txtSeniority.Enabled = True
  SSDBCombo1.Enabled = True
  SSDBCombo2.Enabled = True
  lstEmpName.Selected(0) = True
  Call EnableControls(False)
  ClearControls
  txtEmpId = "E" + Trim(Str(GetEmployeeID))
  txtEmpId.SetFocus
  MaintFlag = "Add"
End Sub

'****************************************
'To Get the last Employee ID and to frame the new Employee ID
'****************************************
Private Function GetEmployeeID() As Long
  Dim mySQL As String, EmpIDRS As Object, maxID As String, NumID As Long
  mySQL = "Select max(Employee_ID) Max from Employee"
  Set EmpIDRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  maxID = EmpIDRS.Fields("Max").Value
  NumID = Mid(maxID, 2)
  NumID = NumID + 1     'Increment the Maximum Number
  GetEmployeeID = NumID
End Function

'****************************************
'To Edit Employee Data
'****************************************
Private Sub cmdEdit_Click()
  If lstEmpName.SelCount = 0 Then
    MsgBox "Select atleast one Employee Name from the List for Editing", vbInformation, "Edit Employee Data"
    Exit Sub
  End If
  'Get the Employee Data
  Dim myEmpSQL As String, myTypeSQL As String, mySubTypeSQL As String
  myEmpSQL = "Select * from Employee where Employee_ID = '" + Trim(txtEmpId) + "'"
  Set EmpEditRS = OraDatabase.DBCreateDynaset(myEmpSQL, 0&)
'  If Trim(EmpEditRS.Fields("Employee_Type_Id").Value) = vbNullString Or IsNull(EmpEditRS.Fields("Employee_Type_Id").Value) Then
'    SSDBCombo2.Text = ""
'  Else
'    SSDBCombo2.Text = EmpEditRS.Fields("Employee_Type_Id").Value
'  End If
'
  If Trim(EmpEditRS.Fields("CERIDIAN_TYPE_ID").Value) = vbNullString Or IsNull(EmpEditRS.Fields("CERIDIAN_TYPE_ID").Value) Then
    SSDBCombo2.Text = ""
  Else
    SSDBCombo2.Text = EmpEditRS.Fields("CERIDIAN_TYPE_ID").Value
  End If
  
  If Trim(EmpEditRS.Fields("Employee_Sub_Type_ID").Value) = vbNullString Or IsNull(EmpEditRS.Fields("Employee_Sub_Type_ID").Value) Then
    SSDBCombo1.Text = ""
  Else
    SSDBCombo1.Text = EmpEditRS.Fields("Employee_Sub_Type_ID").Value
  End If
  If Trim(EmpEditRS.Fields("Seniority").Value) = vbNullString Or IsNull(EmpEditRS.Fields("Seniority").Value) Then
    txtSeniority = ""
  Else
    txtSeniority = EmpEditRS.Fields("Seniority").Value
  End If

  txtEmpId.Enabled = False
  chkTempID.Enabled = False
  If UCase(Left(Trim(txtEmpId), 1)) = "T" Then
    chkTempID.Value = 1
  Else
    chkTempID.Value = 0
  End If
  txtFirstName.Enabled = True
  txtSeniority.Enabled = True
  SSDBCombo1.Enabled = True
  SSDBCombo2.Enabled = True
  Call EnableControls(False)
  MaintFlag = "Edit"
End Sub

'****************************************
'To Delete Employee Data
'****************************************
Private Sub cmdDelete_Click()
  If lstEmpName.SelCount = 0 Then
    MsgBox "Select atleast one Employee Name from the List for Deleting", vbInformation, "Delete Employee Data"
    Exit Sub
  End If
  txtEmpId.Enabled = False
  chkTempID.Enabled = False
  txtFirstName.Enabled = False
  txtSeniority.Enabled = False
  SSDBCombo1.Enabled = False
  SSDBCombo2.Enabled = False
  Call EnableControls(False)
  MaintFlag = "Delete"
End Sub

'****************************************
'To Close the Current Form
'****************************************
Private Sub cmdExit_Click()
  Unload Me
End Sub

'****************************************
'To Update the Changes to DB
'****************************************
Private Sub cmdUpdate_Click()
  On Error GoTo ErrHandler
  Dim blnData As Boolean
  frmEmpMaint.MousePointer = vbHourglass
  blnData = CheckforNull
  If blnData = True And MaintFlag = "Add" Then
    txtEmpId.SetFocus
    frmEmpMaint.MousePointer = vbDefault
    Exit Sub
  ElseIf blnData = True And MaintFlag = "Edit" Then
    txtFirstName.SetFocus
    frmEmpMaint.MousePointer = vbDefault
    Exit Sub
  Else
    If MaintFlag = "Edit" Then
      OraSession.BeginTrans   'Begin the transaction
      'Edit the data
      EmpEditRS.Edit
      EmpEditRS.Fields("Employee_Name").Value = txtFirstName
'      EmpEditRS.Fields("Employee_Type_ID").Value = SSDBCombo2.Text
      If Trim$(SSDBCombo2.Text) = "CAS" Then
        EmpEditRS.Fields("Employee_Type_ID").Value = "CASC"
        EmpEditRS.Fields("CERIDIAN_TYPE_ID").Value = SSDBCombo2.Text
      Else
        EmpEditRS.Fields("Employee_Type_ID").Value = SSDBCombo2.Text
        EmpEditRS.Fields("CERIDIAN_TYPE_ID").Value = SSDBCombo2.Text
      End If
      
      If Trim(txtSeniority) <> vbNullString Then
        EmpEditRS.Fields("Seniority").Value = txtSeniority
      Else
        EmpEditRS.Fields("Seniority").Value = 0
      End If
      If Trim(SSDBCombo1.Text) <> vbNullString Then
        EmpEditRS.Fields("Employee_Sub_Type_ID").Value = SSDBCombo1.Text
      Else
        EmpEditRS.Fields("Employee_Sub_Type_ID").Value = ""
      End If
      EmpEditRS.Update
      
      'Commit or Rollback the Transaction
      If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
      Else
        OraSession.Rollback
      End If
    ElseIf MaintFlag = "Delete" Then    'Delete Mode
      Dim ConfirmDel As Integer
      ConfirmDel = MsgBox("Are you sure to Delete this Employee ? ", vbQuestion + vbYesNo, "Confirm Delete")
      If ConfirmDel = vbYes Then
        Dim myEmpSQL As String
        myEmpSQL = "Select * from Employee where Employee_ID = '" + Trim(txtEmpId) + "'"
        Set EmpEditRS = OraDatabase.DBCreateDynaset(myEmpSQL, 0&)
        EmpEditRS.Delete
        EmpEditRS.Update
        ClearControls
      End If
    ElseIf MaintFlag = "Add" Then 'Add Mode
      EmplRS.Fields("Employee_id").Value = txtEmpId
      
      'Update the Array to Store Employee ID also - used in duplicate check
      maxarrrec = maxarrrec + 1
      Dim MaxSize As Integer
      MaxSize = UBound(arrEmplID)
      ReDim Preserve arrEmplID(MaxSize + 1) As String
      arrEmplID(MaxSize) = txtEmpId
      
      EmplRS.Fields("Employee_Name").Value = txtFirstName
'      EmplRS.Fields("Employee_Type_ID").Value = SSDBCombo2.Text
      
      If Trim$(SSDBCombo2.Text) = "CAS" Then
        EmplRS.Fields("Employee_Type_ID").Value = "CASC"
        EmplRS.Fields("CERIDIAN_TYPE_ID").Value = SSDBCombo2.Text
      Else
        EmplRS.Fields("Employee_Type_ID").Value = SSDBCombo2.Text
        EmplRS.Fields("CERIDIAN_TYPE_ID").Value = SSDBCombo2.Text
      End If
      
      If Trim(txtSeniority) <> vbNullString Then
        EmplRS.Fields("Seniority").Value = txtSeniority
      End If
      If Trim(SSDBCombo1.Text) <> vbNullString Then
        EmplRS.Fields("Employee_Sub_Type_ID").Value = SSDBCombo1.Text
      End If
      EmplRS.Update
      
      
      'Rudy 5/2/2007 Need ability to add SUPV to the LCS_USER table, currently a manual step
      
      
      'Commit or Rollback the Transaction
      If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
      Else
        OraSession.Rollback
      End If
    End If
   End If
   Call EnableControls(True)
  
   frmEmpMaint.MousePointer = vbDefault
   lstEmpName.Clear
   OpenRec
   PopulateEmpDetail
   
   Exit Sub
ErrHandler:
   'MsgBox Err.Description, vbInformation, "Up dation Failure"
   MsgBox Err.Description, vbInformation, "Update Failed"
   Me.MousePointer = vbDefault
   Call EnableControls(True)
   lstEmpName.SetFocus
   ClearControls
   OraSession.Rollback
End Sub

'****************************************
'To Cancel the Changes
'****************************************
Private Sub cmdCancel_Click()
  On Error Resume Next
  EmplRS.CancelUpdate
  OraSession.Rollback         'Rollback the Transaction
  Call EnableControls(True)
  CloseRec
End Sub

Private Sub Form_Load()
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
  OpenRec                 'To Open the Employee Recordset
  PopulateEmpDetail
  StoreinArray            'To Store the Employee Details in Array
End Sub

'****************************************
'To Close the Current Form and Show the Previous One
'****************************************
Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  frmHiring.Show
  Unload Me
End Sub

'****************************************
'To Close the Employee Recordset and to Set it free
'****************************************
Private Sub Form_Terminate()
  Set EmplRS = Nothing
End Sub

'****************************************
'To Open the Employee Recordset
'****************************************
Private Sub OpenRec()
  Set EmplRS = OraDatabase.DBCreateDynaset("Select * from Employee order by Employee_Name", 0&)
End Sub

'****************************************
'To Close the Recordset object
'****************************************
Private Sub CloseRec()
  EmplRS.Close
End Sub

'****************************************
'To Show the Details on List Box Click
'****************************************
Private Sub lstEmpName_Click()
  txtEmpId = arrEmployeeID(lstEmpName.ListIndex)
  txtFirstName = arrFirstName(lstEmpName.ListIndex)
 ' Dim EmpNMRS As Object
 ' Set EmpNMRS = OraDatabase.DBCreateDynaset("Select Employee_Name from Employee where Upper(Employee_ID) = '" + UCase(Trim(arrEmployeeID(lst EmpName.ListIndex))) + "'", 0&)
 ' If EmpNMRS.BOF And EmpNMRS.EOF Then
 '   'Do Nothing
 ' Else
 '   txtFirstName = EmpNMRS.Fields("Employee_Name").Value
 ' End If
 ' EmpNMRS.Close
 ' Set EmpNMRS = Nothing
  SSDBCombo2.Text = arrTypeID(lstEmpName.ListIndex)
  SSDBCombo1.Text = arrSubTypeID(lstEmpName.ListIndex)
  txtSeniority = arrSeniority(lstEmpName.ListIndex)
End Sub

'****************************************
'To Fill the Combo with Employee Sub Type Details
'****************************************
Private Sub SSDBCombo1_InitColumnProps()
  SSDBCombo1.Columns.RemoveAll
  SSDBCombo1.Columns.add 0
  SSDBCombo1.Columns.add 1
  SSDBCombo1.Columns(0).Caption = "Class ID"
  SSDBCombo1.Columns(1).Caption = "Class Description"
  SSDBCombo1.Columns(1).Width = 3000
  Dim SubTypeRS As Object
  Set SubTypeRS = OraDatabase.DBCreateDynaset("Select * From Employee_Sub_Type Order by Employee_Sub_Type_ID", 0&)
  If SubTypeRS.EOF And SubTypeRS.BOF Then
    'Do Nothing
  Else
    SubTypeRS.MoveFirst
    Do While Not SubTypeRS.EOF
      SSDBCombo1.AddItem SubTypeRS.Fields("Employee_Sub_Type_ID").Value & "!" & SubTypeRS.Fields("Sub_Type_Description").Value
      SubTypeRS.MoveNext
    Loop
  End If
End Sub

'****************************************
'To Fill the Combo with Employee Type Details
'****************************************
Private Sub SSDBCombo2_InitColumnProps()
  SSDBCombo2.Columns.RemoveAll
  SSDBCombo2.Columns.add 0
  SSDBCombo2.Columns.add 1
  SSDBCombo2.Columns(0).Caption = "Type ID"
  SSDBCombo2.Columns(1).Caption = "Type Description"
  SSDBCombo2.Columns(1).Width = 3000
  Dim TypeRS As Object
  Set TypeRS = OraDatabase.DBCreateDynaset("Select * From Employee_Type Order by Employee_Type_ID", 0&)
  If TypeRS.EOF And TypeRS.BOF Then
    'Do Nothing
  Else
    TypeRS.MoveFirst
    Do While Not TypeRS.EOF
      SSDBCombo2.AddItem TypeRS.Fields("Employee_Type_ID").Value & "!" & TypeRS.Fields("Type_Description").Value
      TypeRS.MoveNext
    Loop
  End If
End Sub

'****************************************
'To Place the Default Value for Te mporary ID and to Validate
'****************************************
Private Sub chkTempID_Click()
    If chkTempID.Value = 1 Then
    If Trim(txtEmpId) <> vbNullString Then
      'Te mporary Employee ID should start with T as the first character
      If UCase(Left(Trim(txtEmpId), 1)) <> "T" Then
        MsgBox "Temporary Employee ID should start with T as the first character", vbInformation, "Employee ID Invalid"
        txtEmpId = ""
      End If
    Else  'Default Value for Te mporary Employee ID
      Dim mySequenceRS As Object, mySequenceSQL As String
      mySequenceSQL = "Select TEMPID.NEXTVAL from Dual"
      Set mySequenceRS = OraDatabase.DBCreateDynaset(mySequenceSQL, 0&)
      txtEmpId = "T" + mySequenceRS.Fields("NextVal").Value
      mySequenceRS.Close
    End If
  End If
End Sub

'****************************************
'To Validate Text Box Entry
'****************************************
Private Sub txtEmpId_LostFocus()
  Dim found As Boolean, indxCtr As Integer
  If Trim(txtEmpId) <> vbNullString Then
    'Te mporary Employee ID should start with T as the first character
    If chkTempID.Value = 1 And UCase(Left(Trim(txtEmpId), 1)) <> "T" Then
      MsgBox "Temporary Employee ID should start with T as the first character", vbInformation, "Employee ID Invalid"
      txtEmpId = ""
      txtEmpId.SetFocus
    End If
  End If
  If Trim(txtEmpId) <> "" And MaintFlag = "Add" Then
    'Check for Duplicate Record
    For indxCtr = 0 To maxarrrec - 1
      If UCase(Trim(txtEmpId)) = UCase(Trim(arrEmplID(indxCtr))) Then
        found = True
        Exit For
      End If
    Next
    If found = True Then
      MsgBox "Employee ID Already exists", vbOKOnly + vbInformation, "Duplicate Data"
      txtEmpId = ""
      txtEmpId.SetFocus
    End If
  End If
End Sub

'****************************************
'To populate the list box with Employee Name
'****************************************
Private Sub PopulateEmpDetail()
  Dim arrCnt As Integer, TotalRec As Integer
  If EmplRS.BOF And EmplRS.EOF Then
    MsgBox "No Employee records to display.", vbInformation, "Data Unavailable"
    Exit Sub
  Else
    EmplRS.MoveLast
    TotalRec = EmplRS.RecordCount
    EmplRS.MoveFirst
    arrCnt = 0
    ReDim arrEmployeeID(TotalRec) As String, arrFirstName(TotalRec) As String
    ReDim arrTypeID(TotalRec) As String, arrSubTypeID(TotalRec) As String, arrSeniority(TotalRec) As String
    
    Do While Not EmplRS.EOF
      arrFirstName(arrCnt) = EmplRS.Fields("Employee_Name").Value
      arrEmployeeID(arrCnt) = EmplRS.Fields("Employee_id").Value
'      arrTypeID(arrCnt) = EmplRS.Fields("Employee_Type_ID").Value
      arrTypeID(arrCnt) = EmplRS.Fields("CERIDIAN_TYPE_ID").Value
      If IsNull(EmplRS.Fields("Employee_Sub_Type_ID").Value) Then
        arrSubTypeID(arrCnt) = ""
      Else
        arrSubTypeID(arrCnt) = EmplRS.Fields("Employee_Sub_Type_ID").Value
      End If
      If IsNull(EmplRS.Fields("Seniority").Value) Then
        arrSeniority(arrCnt) = ""
      Else
        arrSeniority(arrCnt) = EmplRS.Fields("Seniority").Value
      End If
      If arrSubTypeID(arrCnt) = "" Then
        lstEmpName.AddItem "(" + arrEmployeeID(arrCnt) + ") " + arrFirstName(arrCnt) + " :" + arrTypeID(arrCnt)
        'lst EmpName.AddItem "(" + arrEmployeeID(arrCnt) + ") " + EmplRS.Fields("Employee_Name").Value + " :" + arrTypeID(arrCnt)
      Else
        If arrSeniority(arrCnt) = "" Then
          lstEmpName.AddItem "(" + arrEmployeeID(arrCnt) + ") " + arrFirstName(arrCnt) + " :" + arrTypeID(arrCnt) + "-" + arrSubTypeID(arrCnt)
          'lst EmpName.AddItem "(" + arrEmployeeID(arrCnt) + ") " + EmplRS.Fields("Employee_Name").Value + " :" + arrTypeID(arrCnt) + "-" + arrSubTypeID(arrCnt)
        Else
          lstEmpName.AddItem "(" + arrEmployeeID(arrCnt) + ") " + arrFirstName(arrCnt) + " :" + arrTypeID(arrCnt) + "-" + arrSubTypeID(arrCnt) + ":" + arrSeniority(arrCnt)
          'lst EmpName.AddItem "(" + arrEmployeeID(arrCnt) + ") " + EmplRS.Fields("Employee_Name").Value + " :" + arrTypeID(arrCnt) + "-" + arrSubTypeID(arrCnt) + ":" + arrSeniority(arrCnt)
        End If
      End If
      arrCnt = arrCnt + 1
      EmplRS.MoveNext
    Loop
  End If
  maxarr = arrCnt
End Sub

'****************************************
'To Store Employee details in Array - Used to Display Employee First & Last Name
'****************************************
Private Sub StoreinArray()
  Dim mySQL As String, arrCnt As Integer, TotalEmpRec As Integer
  mySQL = "Select * from Employee "
  Set EmplRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If EmplRS.BOF And EmplRS.EOF Then
    Exit Sub
  Else
    EmplRS.MoveLast
    TotalEmpRec = EmplRS.RecordCount
    EmplRS.MoveFirst
    ReDim arrEmplID(TotalEmpRec) As String
    'ReDim arrFName(TotalEmpRec) As String
    arrCnt = 0
    Do While Not EmplRS.EOF
     ' arrFName(arrCnt) = EmplRS.Fields("Employee_Name").Value
      arrEmplID(arrCnt) = EmplRS.Fields("Employee_id").Value
      arrCnt = arrCnt + 1
      EmplRS.MoveNext
    Loop
  End If
  maxarrrec = arrCnt
End Sub

