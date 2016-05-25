VERSION 5.00
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "COMDLG32.OCX"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Begin VB.Form frmCeridianEntry 
   BackColor       =   &H00C0C000&
   Caption         =   "Ceridian Entries"
   ClientHeight    =   9345
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   10125
   LinkTopic       =   "Form1"
   ScaleHeight     =   9345
   ScaleWidth      =   10125
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdShowAll 
      Caption         =   "&SHOW ALL"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   8070
      TabIndex        =   15
      Top             =   780
      Width           =   1575
   End
   Begin VB.CommandButton cmdExport 
      Caption         =   "&EXPORT TO FILE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   435
      Left            =   4800
      TabIndex        =   14
      Top             =   8460
      Width           =   2205
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&DELETE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   435
      Left            =   2850
      TabIndex        =   13
      Top             =   8460
      Width           =   1695
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&SAVE"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   435
      Left            =   930
      TabIndex        =   10
      Top             =   8460
      Width           =   1695
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "&EXIT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   435
      Left            =   7260
      TabIndex        =   9
      Top             =   8460
      Width           =   1695
   End
   Begin VB.CommandButton cmdFilter 
      Caption         =   "&FILTER"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   6750
      TabIndex        =   5
      ToolTipText     =   "Filter Employees based on Selected Criteria"
      Top             =   780
      Width           =   1185
   End
   Begin VB.OptionButton optFilterEmp 
      BackColor       =   &H00C0C000&
      Caption         =   "&Emp Name"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Index           =   1
      Left            =   3510
      TabIndex        =   3
      Top             =   750
      Width           =   1515
   End
   Begin VB.OptionButton optFilterEmp 
      BackColor       =   &H00C0C000&
      Caption         =   "&Emp ID"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   360
      Index           =   0
      Left            =   510
      TabIndex        =   1
      Top             =   750
      Width           =   1155
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
      Height          =   6345
      Left            =   240
      TabIndex        =   0
      Top             =   1320
      Width           =   9615
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
      Col.Count       =   8
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowColumnSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      SelectTypeRow   =   1
      BackColorOdd    =   14145280
      RowHeight       =   423
      Columns.Count   =   8
      Columns(0).Width=   2064
      Columns(0).Caption=   "HIRE_DATE"
      Columns(0).Name =   "HIRE_DATE"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(0).Locked=   -1  'True
      Columns(1).Width=   2540
      Columns(1).Caption=   "EMPLOYEE_ID"
      Columns(1).Name =   "EMPLOYEE_ID"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(1).Locked=   -1  'True
      Columns(2).Width=   1958
      Columns(2).Caption=   "PAY_CODE"
      Columns(2).Name =   "CERIDIAN_PAY_CODE"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   2011
      Columns(3).Caption=   "PAY_HOUR"
      Columns(3).Name =   "CERIDIAN_PAY_HOUR"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   4
      Columns(3).FieldLen=   256
      Columns(4).Width=   2196
      Columns(4).Caption=   "RATE_CODE"
      Columns(4).Name =   "CERIDIAN_RATE_CODE"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(4).Locked=   -1  'True
      Columns(5).Width=   1879
      Columns(5).Caption=   "PAY_RATE"
      Columns(5).Name =   "CERIDIAN_PAY_RATE"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(5).Locked=   -1  'True
      Columns(6).Width=   3254
      Columns(6).Caption=   "CERIDIAN_STRING"
      Columns(6).Name =   "CERIDIAN_STRING"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(6).Locked=   -1  'True
      Columns(7).Width=   3200
      Columns(7).Visible=   0   'False
      Columns(7).Caption=   "ROW_NUMBER"
      Columns(7).Name =   "ROW_NUMBER"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      _ExtentX        =   16960
      _ExtentY        =   11192
      _StockProps     =   79
      Caption         =   "CERIDIAN ENTRIES"
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
   Begin SSDataWidgets_B.SSDBCombo empIDCombo 
      Height          =   360
      Left            =   1740
      TabIndex        =   2
      ToolTipText     =   "Select Employee ID"
      Top             =   750
      Width           =   1515
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      AllowNull       =   0   'False
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
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns.Count   =   3
      Columns(0).Width=   2117
      Columns(0).Caption=   "Supervisor_Id"
      Columns(0).Name =   "Supervisor_Id"
      Columns(0).Alignment=   1
      Columns(0).CaptionAlignment=   1
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   3043
      Columns(1).Caption=   "Supervisor_FName"
      Columns(1).Name =   "Supervisor_FName"
      Columns(1).Alignment=   1
      Columns(1).CaptionAlignment=   1
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   3200
      Columns(2).Caption=   "Supervisor_LName"
      Columns(2).Name =   "Supervisor_LName"
      Columns(2).Alignment=   1
      Columns(2).CaptionAlignment=   1
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      _ExtentX        =   2672
      _ExtentY        =   635
      _StockProps     =   93
      BackColor       =   -2147483643
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      DataFieldToDisplay=   "Column 0"
   End
   Begin SSDataWidgets_B.SSDBCombo empNameCombo 
      Height          =   360
      Left            =   5100
      TabIndex        =   4
      ToolTipText     =   "Select Employee Name"
      Top             =   750
      Width           =   1515
      DataFieldList   =   "Column 0"
      MaxDropDownItems=   16
      AllowNull       =   0   'False
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
      FieldSeparator  =   "!"
      RowHeight       =   609
      Columns.Count   =   3
      Columns(0).Width=   2117
      Columns(0).Caption=   "Supervisor_Id"
      Columns(0).Name =   "Supervisor_Id"
      Columns(0).Alignment=   1
      Columns(0).CaptionAlignment=   1
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   3043
      Columns(1).Caption=   "Supervisor_FName"
      Columns(1).Name =   "Supervisor_FName"
      Columns(1).Alignment=   1
      Columns(1).CaptionAlignment=   1
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   3200
      Columns(2).Caption=   "Supervisor_LName"
      Columns(2).Name =   "Supervisor_LName"
      Columns(2).Alignment=   1
      Columns(2).CaptionAlignment=   1
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      _ExtentX        =   2672
      _ExtentY        =   635
      _StockProps     =   93
      BackColor       =   -2147483643
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      DataFieldToDisplay=   "Column 0"
   End
   Begin SSCalendarWidgets_A.SSDateCombo startDateCombo 
      Height          =   375
      Left            =   1710
      TabIndex        =   6
      ToolTipText     =   "Select Date"
      Top             =   60
      Visible         =   0   'False
      Width           =   2415
      _Version        =   65543
      _ExtentX        =   4260
      _ExtentY        =   661
      _StockProps     =   93
      BackColor       =   -2147483633
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ShowCentury     =   -1  'True
   End
   Begin SSCalendarWidgets_A.SSDateCombo endDateCombo 
      Height          =   375
      Left            =   6210
      TabIndex        =   7
      ToolTipText     =   "Select Date"
      Top             =   60
      Visible         =   0   'False
      Width           =   2415
      _Version        =   65543
      _ExtentX        =   4260
      _ExtentY        =   661
      _StockProps     =   93
      BackColor       =   -2147483633
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ShowCentury     =   -1  'True
   End
   Begin MSComDlg.CommonDialog ctlCommonDialog 
      Left            =   210
      Top             =   8670
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
   End
   Begin VB.Label totalHrs 
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   8280
      TabIndex        =   19
      Top             =   7800
      Width           =   1335
   End
   Begin VB.Label Label4 
      BackStyle       =   0  'Transparent
      Caption         =   "Total Work Hours:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   -1  'True
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   6240
      TabIndex        =   18
      Top             =   7800
      Width           =   1935
   End
   Begin VB.Label noPayHrs 
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   4800
      TabIndex        =   17
      Top             =   7800
      Width           =   1335
   End
   Begin VB.Label Label2 
      Alignment       =   2  'Center
      BackStyle       =   0  'Transparent
      Caption         =   "No Pay Hours:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   -1  'True
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3120
      TabIndex        =   16
      Top             =   7800
      Width           =   1575
   End
   Begin VB.Label Label1 
      Alignment       =   2  'Center
      BackStyle       =   0  'Transparent
      Caption         =   "Pay Hours:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   -1  'True
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   480
      TabIndex        =   12
      Top             =   7800
      Width           =   1245
   End
   Begin VB.Label payHrs 
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   1800
      TabIndex        =   11
      Top             =   7800
      Width           =   1275
   End
   Begin VB.Label lblStatus 
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "Book Antiqua"
         Size            =   11.25
         Charset         =   0
         Weight          =   700
         Underline       =   -1  'True
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00008000&
      Height          =   345
      Left            =   390
      TabIndex        =   8
      Top             =   150
      Width           =   9345
   End
End
Attribute VB_Name = "frmCeridianEntry"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False

Private Sub cmdDelete_Click()
    Dim i As Integer
    Dim rowNum As String, SqlStmt As String
    Dim dur As Single
    
    If SSDBGrid1.Rows <> 0 Then
        oldRows = SSDBGrid1.Rows
        
        rowNum = Trim(SSDBGrid1.Columns(7).Value)
        dur = GetValue(SSDBGrid1.Columns(3).Value, 0)
        
        SSDBGrid1.DeleteSelected
        If oldRows = SSDBGrid1.Rows Then
            Exit Sub
        End If
        
        If rowNum <> "" Then
            OraSession.BeginTrans
            On Error GoTo ErrorHandler
            
            SqlStmt = "DELETE FROM CERIDIAN_PAY_DETAIL WHERE ROW_NUMBER = " & rowNum
            OraDatabase.ExecuteSQL (SqlStmt)
            
            If OraDatabase.LastServerErr = 0 Then
                OraSession.CommitTrans
            Else
                OraSession.Rollback
            End If
        End If
    End If
    
    payHrs.Caption = CStr(CSng(payHrs.Caption) - dur)
    totalHrs.Caption = CStr(CSng(payHrs.Caption) + CSng(noPayHrs.Caption))
   
    statusLabel ("Delete the row successfully ...")
    Exit Sub
    
ErrorHandler:
    OraSession.Rollback
End Sub

Private Sub cmdExit_Click()
    Unload Me
    frmSelectDate.Show
    'End
End Sub

Private Sub cmdExport_Click()
    MsgBox "This function no longer supported.  Please use the Intranet Applications instead."
'    Dim startDate As Date, endDate As Date
'    Dim fileName As String
'    Dim iRowCount As Long
'
'    startDate = getStartDate
'    endDate = getEndDate
'
'    With ctlCommonDialog
'        .CancelError = True
'        .DialogTitle = "Select File To Open"
'        .fileName = "C:\LCS" & Format(startDate, "mm") & Format(startDate, "dd") & "_" & Format(endDate, "mm") & Format(endDate, "dd") & ".DTA"
'        .Filter = "Ceridian Import Files (*.DTA)|*.DTA"
'        .DefaultExt = ".DTA"
'        .InitDir = "C:\"
'    End With
'
'    On Error GoTo DialogError
'
'    ' Get the filename to save as
'    ctlCommonDialog.ShowSave
'
'    MB
'    ' Set the filename to the user defined choice
'    fileName = ctlCommonDialog.fileName
'
'    iRowCount = importToFile(fileName, startDate, endDate)
'
'    MsgBox "File " & fileName & " Created Successfully!", vbInformation + vbOKOnly, "Payroll Export"
'    Call printResult(iRowCount, fileName, startDate, endDate)
'    MN
'
DialogError:
    If Err.Number = 32755 Then
       ' User pressed "Cancel"
       Exit Sub
    End If
End Sub

Private Sub cmdFilter_Click()
    Dim empID As String
    
    If optFilterEmp(0).Value = False And optFilterEmp(1).Value = False Then
      MsgBox "Please Select ID / Name option!", vbInformation, "Filter Clause Required"
      Exit Sub
    End If
    
    If optFilterEmp(0).Value = True Then
        If Trim(empIDCombo.Text) = vbNullString Then
          MsgBox "Please Select Employee ID", vbInformation, "Data Required"
          Exit Sub
        Else                        'Only Employee ID is Checked and has Value
            empID = empIDCombo.Text
        End If
    ElseIf optFilterEmp(1).Value = True Then
        If Trim(empNameCombo.Text) = vbNullString Then
            MsgBox "Please Select Employee Name", vbInformation, "Data Required"
            Exit Sub
        Else                        'Only Employee Name is Checked and has Value
            empID = empNameCombo.Text
        End If
    End If
    
    Call getEmpCeridianEntries(getStartDate, getEndDate, empID)
End Sub

Private Sub cmdSave_Click()
    Dim i As Integer
    Dim SqlStmt As String, rowNum As String
    Dim iRS As Object
    Dim total As Single, dur As Single
     
    If dataValidation() = False Then
        Exit Sub
    End If
    
    OraSession.BeginTrans
    On Error GoTo ErrorHandler
    
    SSDBGrid1.MoveFirst
    For i = 0 To SSDBGrid1.Rows - 1
        rowNum = Trim(SSDBGrid1.Columns(7).Value)
        If rowNum <> "" Then
            SqlStmt = "Select * from Ceridian_Pay_Detail " _
                    & "Where row_number = " & rowNum
            Set iRS = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
            iRS.Edit
        Else
            rowNum = CStr(getMaxRowNumber() + 1)
            SSDBGrid1.Columns(7).Value = rowNum
            SqlStmt = "Select * from Ceridian_Pay_Detail "
            Set iRS = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
            iRS.AddNew
        End If
        
        iRS.Fields("HIRE_DATE").Value = Format(SSDBGrid1.Columns(0).Value, "mm/dd/yyyy")
        iRS.Fields("EMPLOYEE_ID").Value = SSDBGrid1.Columns(1).Value
        iRS.Fields("CERIDIAN_PAY_CODE").Value = SSDBGrid1.Columns(2).Value
        iRS.Fields("CERIDIAN_PAY_HOUR").Value = SSDBGrid1.Columns(3).Value
        iRS.Fields("CERIDIAN_RATE_CODE").Value = SSDBGrid1.Columns(4).Value
        iRS.Fields("CERIDIAN_PAY_RATE").Value = SSDBGrid1.Columns(5).Value
        iRS.Fields("CERIDIAN_SERVICE_CODE").Value = SSDBGrid1.Columns(6).Value
        iRS.Fields("PROCESS_DATE").Value = Now
        iRS.Fields("ROW_NUMBER").Value = rowNum
        iRS.Update
        
        dur = GetValue(SSDBGrid1.Columns(3).Value, 0)
        total = total + dur
        
        SSDBGrid1.MoveNext
    Next
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
    Else
        GoTo ErrorHandler
    End If
    
    iRS.Close
    Set iRS = Nothing
     
    payHrs.Caption = CStr(total)
    totalHrs.Caption = CStr(CSng(payHrs.Caption) + CSng(noPayHrs.Caption))
       
    statusLabel ("Save the changes successfully ...")
    
    Exit Sub
    
ErrorHandler:
    OraSession.Rollback
    MsgBox "Unable to save now! Please try later!", vbOKOnly, "Ceridian Entries Adjustment"
End Sub

Private Sub cmdShowAll_Click()
    Dim startDate As String, endDate As String
    startDate = getStartDate
    endDate = getEndDate
    
    MB
    Call getCeridianEntries(startDate, endDate)
    MN
End Sub

Private Sub Form_Load()
    Dim lsunday As Date
    Dim startDate As String, endDate As String
       
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    'If Trim(startDateCombo.Text) = "" Or Trim(endDateCombo.Text) = "" Then
    lsunday = findLastSunday(Date)
    startDateCombo.Text = Format(lsunday - 6, "mm/dd/yyyy")
    endDateCombo.Text = Format(lsunday, "mm/dd/yyyy")
    'End If
    
    startDate = getStartDate
    endDate = getEndDate
    
    MB
    lblStatus.Caption = "Logging on to database..."
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    If OraDatabase.LastServerErr = 0 Then
        statusLabel ("You are viewing the data from " + startDate + " to " + endDate + " ...")
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        statusLabel ("Logon Failed...")
        Unload Me
    End If
      
    Call getEmployeeIDs(startDate, endDate)
    Call getEmployeeNames(startDate, endDate)
    Call getNoPayHours(startDate, endDate)
    Call getCeridianEntries(startDate, endDate)
    
    MN
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Payroll Export"
    statusLabel ("Error Occured...")
    On Error GoTo 0
End Sub

Public Sub getEmpCeridianEntries(startDate As String, endDate As String, empID As String)
    Dim gsSqlStmt As String
    Dim rsDETAIL As Object
    Dim myrec As String
    Dim hireDate As String, payCode As String, rateCode As String, servString As String
    Dim payHour As String, payRate As String
    Dim rowNum As String
    Dim hour As Single
    
    gsSqlStmt = " SELECT * from ceridian_pay_detail WHERE " _
                & " HIRE_DATE >= TO_DATE('" & startDate & "', 'mm/dd/yyyy') " _
                & " AND HIRE_DATE <= TO_DATE('" & endDate & "', 'mm/dd/yyyy') " _
                & " AND EMPLOYEE_ID = '" & empID & "'" _
                & " ORDER BY HIRE_DATE, row_number"
    Set rsDETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)

    SSDBGrid1.RemoveAll
    rsDETAIL.MoveFirst
    While Not rsDETAIL.EOF
        hireDate = rsDETAIL.Fields("HIRE_DATE")
        payCode = rsDETAIL.Fields("CERIDIAN_PAY_CODE")
        payHour = GetValue(rsDETAIL.Fields("CERIDIAN_PAY_HOUR"), "")
        rateCode = rsDETAIL.Fields("CERIDIAN_RATE_CODE")
        payRate = GetValue(rsDETAIL.Fields("CERIDIAN_PAY_RATE"), "")
        servString = rsDETAIL.Fields("CERIDIAN_SERVICE_CODE")
        rowNum = rsDETAIL.Fields("ROW_NUMBER")
        myrec = hireDate + Chr(9) + empID + Chr(9) + payCode + Chr(9) + CStr(payHour) + Chr(9) + rateCode + Chr(9) + CStr(payRate) + Chr(9) + servString + Chr(9) + rowNum
        SSDBGrid1.AddItem "" + myrec

        hour = hour + CSng(GetValue(rsDETAIL.Fields("CERIDIAN_PAY_HOUR"), 0))

        rsDETAIL.MoveNext
    Wend
    rsDETAIL.Close
    Set rsDETAIL = Nothing
    
    payHrs.Caption = CStr(hour)
    totalHrs.Caption = CStr(CSng(payHrs.Caption) + CSng(noPayHrs.Caption))
End Sub

Public Sub getCeridianEntries(startDate As String, endDate As String)
    Dim gsSqlStmt As String
    Dim rsDETAIL As Object
    Dim myrec As String
    Dim hireDate As String, empID As String, payCode As String, rateCode As String, servString As String
    Dim payHour As String, payRate As String, rowNum As String
    Dim hour As Single
    
    gsSqlStmt = "SELECT * from ceridian_pay_detail WHERE " _
                & " HIRE_DATE >= TO_DATE('" & startDate & "', 'mm/dd/yyyy') " _
                & " AND HIRE_DATE <= TO_DATE('" & endDate & "', 'mm/dd/yyyy') " _
                & " ORDER BY HIRE_DATE, EMPLOYEE_ID"
    Set rsDETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)

    SSDBGrid1.RemoveAll
    rsDETAIL.MoveFirst
    While Not rsDETAIL.EOF
        hireDate = rsDETAIL.Fields("HIRE_DATE")
        empID = rsDETAIL.Fields("EMPLOYEE_ID")
        payCode = rsDETAIL.Fields("CERIDIAN_PAY_CODE")
        payHour = GetValue(rsDETAIL.Fields("CERIDIAN_PAY_HOUR"), "")
        rateCode = rsDETAIL.Fields("CERIDIAN_RATE_CODE")
        payRate = GetValue(rsDETAIL.Fields("CERIDIAN_PAY_RATE"), "")
        servString = rsDETAIL.Fields("CERIDIAN_SERVICE_CODE")
        rowNum = rsDETAIL.Fields("ROW_NUMBER")
        myrec = hireDate + Chr(9) + empID + Chr(9) + payCode + Chr(9) + CStr(payHour) + Chr(9) + rateCode + Chr(9) + CStr(payRate) + Chr(9) + servString + Chr(9) + rowNum
        SSDBGrid1.AddItem "" + myrec

        hour = hour + CSng(GetValue(rsDETAIL.Fields("CERIDIAN_PAY_HOUR"), 0))

        rsDETAIL.MoveNext
    Wend
    rsDETAIL.Close
    Set rsDETAIL = Nothing
    
    payHrs.Caption = CStr(hour)
    totalHrs.Caption = CStr(CSng(payHrs.Caption) + CSng(noPayHrs.Caption))
End Sub

Private Sub getEmployeeIDs(startDate As String, endDate As String)
    Dim gsSqlStmt As String
    Dim rsDETAIL As Object
 
    gsSqlStmt = "SELECT DISTINCT cp.employee_id, e.employee_name from ceridian_pay_detail cp, employee e " _
                & " WHERE cp.employee_id = e.employee_id " _
                & " AND HIRE_DATE >= TO_DATE('" & startDate & "', 'mm/dd/yyyy') " _
                & " AND HIRE_DATE <= TO_DATE('" & endDate & "', 'mm/dd/yyyy') " _
                & " ORDER BY cp.EMPLOYEE_ID"
    Set rsDETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)

    rsDETAIL.MoveFirst
    
    empIDCombo.Columns.RemoveAll
    empIDCombo.Columns(0).Caption = "Employee ID"
    empIDCombo.Columns(1).Caption = "Employee Name"
  
    While Not rsDETAIL.EOF
        empIDCombo.AddItem rsDETAIL.Fields("Employee_ID").Value & "!" & rsDETAIL.Fields("Employee_Name").Value
        rsDETAIL.MoveNext
    Wend
    
    empIDCombo.Columns(1).Width = 3500
    
    rsDETAIL.Close
    Set rsDETAIL = Nothing
End Sub

Private Sub getEmployeeNames(startDate As String, endDate As String)
    Dim gsSqlStmt As String
    Dim rsDETAIL As Object
 
    gsSqlStmt = "SELECT DISTINCT cp.employee_id, e.employee_name from ceridian_pay_detail cp, employee e " _
                & " WHERE cp.employee_id = e.employee_id " _
                & " AND HIRE_DATE >= TO_DATE('" & startDate & "', 'mm/dd/yyyy') " _
                & " AND HIRE_DATE <= TO_DATE('" & endDate & "', 'mm/dd/yyyy') " _
                & " ORDER BY e.EMPLOYEE_NAME"
    Set rsDETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)

    rsDETAIL.MoveFirst
    
    empNameCombo.Columns.RemoveAll
    empNameCombo.Columns(0).Caption = "Employee ID"
    empNameCombo.Columns(1).Caption = "Employee Name"
  
    While Not rsDETAIL.EOF
        empNameCombo.AddItem rsDETAIL.Fields("Employee_ID").Value & "!" & rsDETAIL.Fields("Employee_Name").Value
        rsDETAIL.MoveNext
    Wend
    
    empNameCombo.Columns(1).Width = 3500
    
    rsDETAIL.Close
    Set rsDETAIL = Nothing
End Sub

Public Function getStartDate() As String
    getStartDate = Format(startDateCombo.Text, "mm/dd/yyyy")
End Function

Public Function getEndDate() As String
    getEndDate = Format(Me.endDateCombo.Text, "mm/dd/yyyy")
End Function

Private Sub SSDBGrid1_BeforeColUpdate(ByVal ColIndex As Integer, ByVal OldValue As Variant, Cancel As Integer)
    If ColIndex = 3 Or ColIndex = 5 Then
        If SSDBGrid1.Columns(ColIndex).Value = vbNullString Then
            SSDBGrid1.Columns(ColIndex).Value = OldValue
        End If
    End If
End Sub

Private Sub SSDBGrid1_Click()
    Dim hireDate As String, employee_id As String
    
    If SSDBGrid1.Row > 0 And SSDBGrid1.Col = 0 And SSDBGrid1.Columns(0).Value = "" Then
        SSDBGrid1.Row = SSDBGrid1.Row - 1
        hireDate = SSDBGrid1.Columns(0).Value
        employee_id = SSDBGrid1.Columns(1).Value
        
        SSDBGrid1.Row = SSDBGrid1.Row + 1
        SSDBGrid1.Columns(0).Value = hireDate
        SSDBGrid1.Columns(1).Value = employee_id
        
        SSDBGrid1.Columns(0).Locked = False
        SSDBGrid1.Columns(1).Locked = False
        SSDBGrid1.Columns(2).Locked = False
        SSDBGrid1.Columns(3).Locked = False
        SSDBGrid1.Columns(4).Locked = False
        SSDBGrid1.Columns(5).Locked = False
        SSDBGrid1.Columns(6).Locked = False
    End If
End Sub
    
Private Function importToFile(fileName As String, startDate As Date, endDate As Date) As Integer
    Dim gsSqlStmt As String
    Dim rsDETAIL As Object
    Dim rowCount As Long
    Dim record As New ceridianPay
    Dim oFileSys As New FileSystemObject
    
    ' file already exist?
    If oFileSys.FileExists(fileName) Then
       iresponse = MsgBox(fileName & " already exists!  Do you want to overwrite it?", vbExclamation + vbYesNo, "Payroll Export") ' One more chance to quit
       If iresponse = vbNo Then Exit Function
    End If

    ' Before processing lets make sure that the date is valid
    If (Not IsDate(startDate)) Or (Not IsDate(endDate)) Or (CDate(startDate) > CDate(endDate)) Then
       dataError ("You Must Enter a Valid Start Date and End Date!")
       Exit Function
    End If
    
    On Error GoTo ErrorHandler
    
    Open fileName For Output As #1
    
    gsSqlStmt = " SELECT * from ceridian_pay_detail WHERE " _
                & " HIRE_DATE >= TO_DATE('" & Format(startDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy') " _
                & " AND HIRE_DATE <= TO_DATE('" & Format(endDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy') " _
                & " ORDER BY HIRE_DATE, row_number"
    Set rsDETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)

    rsDETAIL.MoveFirst
    While Not rsDETAIL.EOF
        With record
            .EmployeeID = Right(rsDETAIL.Fields("EMPLOYEE_ID"), 4)
            .payCode = rsDETAIL.Fields("CERIDIAN_PAY_CODE")
            .payHour = GetValue(rsDETAIL.Fields("CERIDIAN_PAY_HOUR"), "")
            .rateCode = rsDETAIL.Fields("CERIDIAN_RATE_CODE")
            .payRate = GetValue(rsDETAIL.Fields("CERIDIAN_PAY_RATE"), "")
            .ServShiftEquipCode = rsDETAIL.Fields("CERIDIAN_SERVICE_CODE")
        End With
        
        Print #1, record.getCeridianString
        rowCount = rowCount + 1

        rsDETAIL.MoveNext
    Wend
    rsDETAIL.Close
    Set rsDETAIL = Nothing
    Close #1
    
    statusLabel ("Write to the file " + fileName + " successfully ...")
    importToFile = rowCount
    Exit Function
    
ErrorHandler:
    statusLabel ("Unable to write to the file ...")
    MsgBox "Unable to write to the file now! Please try later!", vbOKOnly, "Payroll Export"
End Function

Private Sub statusLabel(content As String)
    Me.lblStatus.Caption = content
End Sub

Private Function dataValidation() As Boolean
    Dim i As Integer
    
    dataValidation = False
    
    SSDBGrid1.MoveFirst
    For i = 0 To SSDBGrid1.Rows - 1
        If Trim(SSDBGrid1.Columns(0).Value) = "" Then
            dataError ("Please Enter Hire Date")
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        
        If Trim(SSDBGrid1.Columns(1).Value) = "" Then
            dataError ("Please Enter Employee ID")
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        
        If Trim(SSDBGrid1.Columns(2).Value) = "" Then
            dataError ("Please Enter Ceridian Pay Code")
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        
        If Trim(SSDBGrid1.Columns(4).Value) = "" Then
            dataError ("Please Enter Ceridian Rate Code")
            SSDBGrid1.SelectByCell = True
            Exit Function
        End If
        SSDBGrid1.MoveNext
    Next
    
    dataValidation = True
End Function

Private Function dataError(mesg As String)
    MsgBox mesg, vbCritical + vbOKOnly, "Data Error"
End Function

Public Sub getNoPayHours(startDate As String, endDate As String)
    Dim SqlStmt As String
    Dim rsDETAIL As Object
    
    SqlStmt = "select sum(DURATION) As noPay from HOURLY_DETAIL " _
            & "where EARNING_TYPE_ID = 'NP' and " _
            & "HIRE_DATE >= TO_DATE('" & startDate & "', 'mm/dd/yyyy') " _
            & "AND HIRE_DATE <= TO_DATE('" & endDate & "', 'mm/dd/yyyy') "
            
    Set rsDETAIL = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And rsDETAIL.RecordCount > 0 Then
        noPayHrs.Caption = CStr(Val("" & rsDETAIL.Fields("noPay").Value))
    Else
        noPayHrs.Caption = 0
    End If
    
    rsDETAIL.Close
    Set rsDETAIL = Nothing
End Sub
