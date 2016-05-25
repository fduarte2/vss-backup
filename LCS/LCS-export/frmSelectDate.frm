VERSION 5.00
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "comdlg32.ocx"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Begin VB.Form frmSelectDate 
   BackColor       =   &H00C0C000&
   Caption         =   "Ceridian Export Application"
   ClientHeight    =   5070
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   6630
   FillColor       =   &H00C0C0C0&
   LinkTopic       =   "Form1"
   ScaleHeight     =   5070
   ScaleWidth      =   6630
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdCeridian 
      Caption         =   "&CERIDIAN ENTRIES"
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
      Left            =   1860
      TabIndex        =   12
      Top             =   3660
      Width           =   2835
   End
   Begin VB.OptionButton optAllRecs 
      BackColor       =   &H00808000&
      Caption         =   "All Records "
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00E0E0E0&
      Height          =   255
      Left            =   3990
      TabIndex        =   11
      Top             =   2910
      Width           =   1395
   End
   Begin VB.OptionButton optNewRecs 
      BackColor       =   &H00808000&
      Caption         =   "Unposted Records"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00E0E0E0&
      Height          =   255
      Left            =   810
      TabIndex        =   10
      Top             =   2910
      Value           =   -1  'True
      Width           =   2205
   End
   Begin VB.CommandButton cmdBrowse 
      Caption         =   "..."
      Height          =   285
      Left            =   5790
      TabIndex        =   9
      Top             =   2070
      Width           =   375
   End
   Begin VB.TextBox txtFilename 
      Height          =   285
      Left            =   3300
      TabIndex        =   8
      Top             =   2070
      Width           =   2505
   End
   Begin VB.CommandButton cmdExport 
      Caption         =   "&EXPORT"
      Default         =   -1  'True
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
      Left            =   450
      MaskColor       =   &H00800000&
      TabIndex        =   3
      ToolTipText     =   "Export for the Selected Date Range"
      Top             =   3660
      Width           =   1335
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
      Left            =   4740
      TabIndex        =   2
      ToolTipText     =   "Return Back"
      Top             =   3660
      Width           =   1455
   End
   Begin SSCalendarWidgets_A.SSDateCombo endDateCombo 
      Height          =   375
      Left            =   3300
      TabIndex        =   0
      ToolTipText     =   "Select Date"
      Top             =   1260
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
   Begin SSCalendarWidgets_A.SSDateCombo startDateCombo 
      Height          =   375
      Left            =   3300
      TabIndex        =   5
      ToolTipText     =   "Select Date"
      Top             =   480
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
      Left            =   300
      Top             =   4380
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
   End
   Begin VB.Label Label3 
      BackStyle       =   0  'Transparent
      Caption         =   "Export File Name"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   375
      Left            =   810
      TabIndex        =   7
      Top             =   2010
      Width           =   2205
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
      TabIndex        =   6
      Top             =   4500
      Width           =   5775
   End
   Begin VB.Label Label2 
      BackStyle       =   0  'Transparent
      Caption         =   "Start Date"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   375
      Left            =   810
      TabIndex        =   4
      Top             =   510
      Width           =   1575
   End
   Begin VB.Label Label1 
      BackStyle       =   0  'Transparent
      Caption         =   "End Date"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00800000&
      Height          =   375
      Left            =   810
      TabIndex        =   1
      Top             =   1290
      Width           =   1575
   End
End
Attribute VB_Name = "frmSelectDate"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False

Private Sub setForm()
    Me.startDateCombo.SetFocus
    Me.cmdExport.Enabled = True
    MN
End Sub

Private Sub cmdBrowse_Click()
    Me.ctlCommonDialog.fileName = Me.txtFilename.Text
    
    ' Prepare settings for common dialog box
    ctlCommonDialog.Filter = "Ceridian Import Files (*.DTA)|*.DTA"
    ' Specify default filter
    ctlCommonDialog.DefaultExt = ".DTA"
    ' Specify initial directory
    ctlCommonDialog.InitDir = "C:\"
    ctlCommonDialog.CancelError = True
    On Error Resume Next
    
    ' Get the filename to save as
    ctlCommonDialog.ShowSave
    If Err.Number = 32755 Then
       ' User pressed "Cancel"
       Exit Sub
    End If
    
    ' Set the filename to the user defined choice
    Me.txtFilename.Text = ctlCommonDialog.fileName
      
    Me.Refresh
End Sub

Private Sub cmdCeridian_Click()
    'frmCeridianEntry.startDateCombo.Text = Me.startDateCombo.Text
    'frmCeridianEntry.endDateCombo.Text = Me.endDateCombo.Text
    Me.Hide
    frmCeridianEntry.Show
End Sub

Private Sub cmdExit_Click()
    Unload Me
    End
End Sub

Private Sub cmdExport_Click()
    Dim myStartDate As Date, myEndDate As Date, myDate As Date
    
    Dim gsMyFileName As String, gsTextDirectory As String
    Dim oFileSys As New FileSystemObject
    Dim iRowCount As Long
  
    myStartDate = Format(Me.startDateCombo, "mm/dd/yyyy")
    myEndDate = Format(Me.endDateCombo, "mm/dd/yyyy")
    myDate = myStartDate
    gsTextDirectory = App.Path
    gsMyFileName = Me.txtFilename.Text
  
    ' Lets do some final checking before continuing
    iresponse = MsgBox("Did you check the timesheet from " & myStartDate & " to " & myEndDate & "?", vbQuestion + vbYesNo, "Payroll Export")  ' One more chance to quit
    If iresponse = vbNo Then Exit Sub
    
    ' gsMyFilename already exist?
    If oFileSys.FileExists(gsMyFileName) Then
       iresponse = MsgBox(gsMyFileName & " already exists!  Do you want to overwrite it?", vbExclamation + vbYesNo, "Payroll Export") ' One more chance to quit
       If iresponse = vbNo Then Exit Sub
    End If

    ' Before processing lets make sure that the date is valid
    If (Not IsDate(myStartDate)) Or (Not IsDate(myEndDate)) Or (CDate(myStartDate) > CDate(myEndDate)) Then
       MsgBox "You Must Enter a Valid Start Date and End Date!", vbCritical + vbOKOnly, "ERROR"
       Me.startDateCombo.SetFocus
       Exit Sub
    End If
    
    ' Disable Ok button while processing
    Me.cmdExport.Enabled = False
    
    MB  ' Mouse Busy
    
    ' Before we start lets delete the old log file
    If oFileSys.FileExists(gsTextDirectory & "\PROBLEM.LOG") Then
       oFileSys.DeleteFile gsTextDirectory & "\PROBLEM.LOG", True
    End If
    
    OraSession.BeginTrans
    On Error GoTo ErrorHandler
    
    Open gsMyFileName For Output As #1
    
    'Clean the ceridian export entries
    If Me.optAllRecs = True Then
        Call removeCeridianEntries(myStartDate, myEndDate)
    End If
    
    RowNumber = getMaxRowNumber()
    
    While (myDate <= myEndDate)
        If Me.optNewRecs = True Then
            gsSqlStmt = "UPDATE HOURLY_DETAIL SET TO_SOLOMON = 'U' WHERE HIRE_DATE = TO_DATE('" & Format(myDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy') AND (TO_SOLOMON <> 'P' or TO_SOLOMON is null)"
        Else
            gsSqlStmt = "UPDATE HOURLY_DETAIL SET TO_SOLOMON = 'U' WHERE HIRE_DATE = TO_DATE('" & Format(myDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy')"
        End If
        
        iRowCount = OraDatabase.ExecuteSQL(gsSqlStmt)
        If OraDatabase.LastServerErr = 0 And iRowCount = 0 Then
            MsgBox "No records found for " & myDate & "!", vbInformation + vbOKOnly, "Payroll Export"
        Else
            'Read from hourly_detail and write to the file
            If writeDailyPay(myDate, gsMyFileName) = False Then
                GoTo ErrorHandler
            End If
            
            If Weekday(CDate(myDate)) = vbSunday Then
                Dim hazardFileName As String
                If writeHazardPay(gsMyFileName, myDate) = False Then
                    GoTo ErrorHandler
                End If
            End If
        End If
        
        myDate = Format((CDate(myDate) + 1), "mm/dd/yyyy")
    Wend
    
    Close #1
    
    ' If there were any errors then we will warn the user then display a report
    ' detailing all problems.
    If oFileSys.FileExists(gsTextDirectory & "\PROBLEM.LOG") Then
       MsgBox "There were errors in processing this file.  Press 'Ok' for error report!", vbOKOnly, "Payroll Export"
       Call Shell("NOTEPAD.EXE " & gsTextDirectory & "\PROBLEM.LOG", 3)   ' Display problem log
       GoTo ErrorHandler
    End If
   
    ' Notify user of filename
    MsgBox "File " & gsMyFileName & " Created Successfully!" & vbCrLf & vbCrLf _
        & "Complete import process with Ceridian Utility - Transaction Import." & vbCrLf _
        & vbCrLf & "Press 'Ok' after you have imported the file into Ceridian." _
        , vbOKOnly, "Payroll Export"
    
    ' With the user's permission we will mark this timesheet as "Posted To Solomon"
    iresponse = MsgBox("Mark these timesheet entries as 'Posted To Ceridian'?", vbQuestion + vbYesNo, "Payroll Export")
    If iresponse = vbYes Then
        If blExcludeErrors = True Then
           gsSqlStmt = "UPDATE HOURLY_DETAIL SET TO_SOLOMON = 'P' WHERE TO_SOLOMON = 'U'"
        Else
           gsSqlStmt = "UPDATE HOURLY_DETAIL SET TO_SOLOMON = 'P' WHERE TO_SOLOMON IN ('U','X')"
        End If
        iRowCount = OraDatabase.ExecuteSQL(gsSqlStmt)
        
        Call printResult(iRowCount, gsMyFileName, myStartDate, myEndDate)
       
        If OraDatabase.LastServerErr = 0 Then
            OraSession.CommitTrans
        Else
            GoTo ErrorHandler
        End If
        Me.lblStatus.Caption = "Post successfully ..."
        Call setForm
        Call cmdCeridian_Click
    Else
        ' Reset the records so they will be exported again
        OraSession.Rollback
        Me.lblStatus.Caption = "Give up posting successfully ..."
        Call setForm
    End If
    
    Exit Sub
    
ErrorHandler:
    OraSession.Rollback
    Call setForm
    Me.lblStatus.Caption = "Unable to post to ceridian ..."
    MsgBox "Unable to post to Ceridian now! Please try later!", vbOKOnly, "Payroll Export"
End Sub

Private Sub Form_Activate()
  If App.PrevInstance Then
     ' Make sure only one copy at a time is running!
     End
  End If
End Sub

Private Sub Form_Load()
    Dim lsunday As Date
    lsunday = findLastSunday(Date)
    
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    startDateCombo.Text = Format(lsunday - 6, "mm/dd/yyyy")
    endDateCombo.Text = Format(lsunday, "mm/dd/yyyy")
    Dim str As String
    str = "LCS" & Format(startDateCombo.Text, "mm") & Format(startDateCombo.Text, "dd") & "_" & Format(endDateCombo.Text, "mm") & Format(endDateCombo.Text, "dd") & ".DTA"
    ChDrive "C"
    ChDir ("\")
    Me.txtFilename.Text = CurDir & str
    
    MB
    lblStatus.Caption = "Logging on to database ..."
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful ..."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed ...."
        Unload Me
    End If
    MN
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Payroll Export"
    lblStatus.Caption = "Error Occured ..."
    On Error GoTo 0
End Sub

Private Sub TransRollBack()
    OraSession.Rollback
    MsgBox "Unable to post to ceridian now. Please try again later.", , "Error"
End Sub

' If we are processing Sunday's work then we will figure out the Guards Hazard pay
' After all processing is complete we will determine the hazardous duty pay for the guards
' The business rule is as follows:
' Guards are paid an additional amount depending on the maximum shift they worked for the week.
'   1st shift   -   $25.00
'   2nd shift   -   $29.00
'   3rd shift   -   $31.00
' This record will be added in Solomon under the pay code of "HAZARD"
' In order to determine the proper shift, all of the records for that day for a particular guard
' must be scanned in order to figure out the latest shift worked.
' For example if a guard worked both 2nd and 3rd shift they must be paid hazard pay for the 3rd shift.
Private Function writeHazardPay(fileName As String, myDate As Date) As Boolean
    On Error GoTo ErrHandler
    
    writeHazardPay = True
    'Open fileName For Output As #1     ' Open export file
    
    ' Perform a select query on the recordset for guards only and then order it by employee_id,shift descending order
    ' Only 1 record per day for each guard that worked.
    gsSqlStmt = "SELECT DISTINCT MAX(TO_NUMBER(H.SPECIAL_CODE)) AS Shift, H.EMPLOYEE_ID,E.EMPLOYEE_NAME FROM HOURLY_DETAIL H,EMPLOYEE E " _
                & "WHERE E.EMPLOYEE_TYPE_ID = 'GUARD' AND H.EMPLOYEE_ID = E.EMPLOYEE_ID AND " _
                & "H.HIRE_DATE >= TO_DATE('" & Format(myDate - 6, "mm/dd/yyyy") & "', 'mm/dd/yyyy') AND " _
                & "H.HIRE_DATE <= TO_DATE('" & Format(myDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy') " _
                & "GROUP BY H.EMPLOYEE_ID,E.EMPLOYEE_NAME"
    Set rsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And rsHOURLY_DETAIL.RecordCount > 0 Then
        Dim record As New hazardPay
        While Not rsHOURLY_DETAIL.EOF
            With record
                .HireDate = Format(myDate, "dd-mmm-yyyy")
                .EmployeeID = rsHOURLY_DETAIL.Fields("EMPLOYEE_ID").Value
                .ShiftCode = GetValue(CStr(rsHOURLY_DETAIL.Fields("SHIFT").Value), "")
                'strEmployeeName = GetValue(rsHOURLY_DETAIL.Fields("EMPLOYEE_NAME").Value, "")
            End With
            
            gsSqlStmt = "SELECT * FROM HAZARD_PAY WHERE SHIFT = '0" & Val(record.ShiftCode) & "'"
            Set rsHAZARD_PAY = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
            
            If OraDatabase.LastServerErr = 0 And rsHAZARD_PAY.RecordCount > 0 Then
              record.PayRate = Format(GetValue(rsHAZARD_PAY.Fields("AMOUNT").Value, 0), "#0.00")
            Else
              record.PayRate = "0.00"
            End If
           
            If record.PayRate <> "0.00" Then
                Print #1, record.getCeridianString
                If record.writeCeridianPay = False Then
                    GoTo ErrHandler
                End If
            End If
        
           rsHOURLY_DETAIL.MoveNext        ' Next Record
       Wend
    End If
    
    'Close #1  ' Close Export File
    Set record = Nothing
   
    Exit Function
    
ErrHandler:
    writeHazardPay = False
    Close #1  ' Close Export File
    Set record = Nothing
End Function

Private Function writeDailyPay(myDate As Date, fileName As String) As Boolean
    On Error GoTo ErrHandler
    
    writeDailyPay = True
    
    Dim gsSqlStmt As String
    Dim rsHOURLY_DETAIL As Object

    gsSqlStmt = "SELECT H.*,E.* FROM HOURLY_DETAIL H,EMPLOYEE E WHERE H.EMPLOYEE_ID = E.EMPLOYEE_ID " _
                & " AND H.TO_SOLOMON='U' " _
                & " AND H.EARNING_TYPE_ID <> 'NP' " _
                & " AND HIRE_DATE = TO_DATE('" & Format(myDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy') " _
                & " ORDER BY H.EMPLOYEE_ID"

'    gsSqlStmt = "SELECT H.*,E.* FROM HOURLY_DETAIL H,EMPLOYEE E WHERE H.EMPLOYEE_ID = E.EMPLOYEE_ID " _
'                & " AND H.EARNING_TYPE_ID <> 'NP' " _
'                & " AND HIRE_DATE = TO_DATE('" & Format(myDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy') " _
'                & " AND H.EMPLOYEE_ID='E408186'" _
'                & " ORDER BY H.EMPLOYEE_ID"
                
    Set rsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)

    ' Lets start at the top of the file again and create the export file
    rsHOURLY_DETAIL.MoveFirst
        
    'Open fileName For Output As #1     ' Open export file
    
    Dim record As New employeeHourlyRecord
    Dim ceridianStr() As String
    
    While Not rsHOURLY_DETAIL.EOF
        With record
            .HireDate = rsHOURLY_DETAIL.Fields("HIRE_DATE").Value
            .EmployeeID = rsHOURLY_DETAIL.Fields("EMPLOYEE_ID").Value
            .EmployeeType = GetValue(rsHOURLY_DETAIL.Fields("CERIDIAN_TYPE_ID").Value, "")
            .EmployeeSubType = GetValue(rsHOURLY_DETAIL.Fields("EMPLOYEE_SUB_TYPE_ID").Value, "")
            .EarningType = GetValue(rsHOURLY_DETAIL.Fields("EARNING_TYPE_ID").Value, "")
            .VesselID = GetValue(rsHOURLY_DETAIL.Fields("VESSEL_ID").Value, 0)
            .ServiceCode = GetValue(rsHOURLY_DETAIL.Fields("SERVICE_CODE").Value, 0)
            .CommodityCode = GetValue(rsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value, 0)
            .EquipmentID = GetValue(rsHOURLY_DETAIL.Fields("EQUIPMENT_ID").Value, 0)
            .LocationID = GetValue(rsHOURLY_DETAIL.Fields("LOCATION_ID").Value, "")
            .SpecialCode = GetValue(rsHOURLY_DETAIL.Fields("SPECIAL_CODE").Value, "")
            .Duration = GetValue(rsHOURLY_DETAIL.Fields("DURATION").Value, 0)
            .EmployeeName = GetValue(rsHOURLY_DETAIL.Fields("EMPLOYEE_NAME").Value, "")
        End With
        
        If record.validRecord = True Then
            ceridianStr = record.getCeridianString
            Print #1, ceridianStr(1)
            If (ceridianStr(2) <> "") Then
                Print #1, ceridianStr(2)
            End If
            
            If record.writeCeridianPay = False Then
                GoTo ErrHandler
            End If
        End If
        rsHOURLY_DETAIL.MoveNext
    Wend
    
    'Close #1    ' Close Export File
    Set record = Nothing
   
    Exit Function
    
ErrHandler:
    writeDailyPay = False
    Close #1
    Set record = Nothing
End Function

Public Function getHazardPayFileName(fileName As String, myDate As String) As String
    Dim iPosition As Integer
    Dim post As String
    
    If Trim(myDate) = "" Then
        post = "HAZARD.DAT"
    Else
        post = "HAZARD" & Mid$(myDate, 1, 2) & Mid$(myDate, 4, 2) & ".DTA"
    End If
    
    If Trim(fileName) <> "" Then
        iPosition = InStrRev(fileName, "\")
        getHazardPayFileName = Left$(fileName, iPosition) & post
    Else
        getHazardPayFileName = "C:\" & post
    End If
End Function

Private Sub removeCeridianEntries(startDate As Date, endDate As Date)
    Dim SqlStmt As String
    Dim iRS As Integer
    
    SqlStmt = " DELETE FROM CERIDIAN_PAY_DETAIL WHERE " _
            & " HIRE_DATE >= TO_DATE('" & Format(startDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy')" _
            & " AND HIRE_DATE <= TO_DATE('" & Format(endDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy')"
            
    iRS = OraDatabase.ExecuteSQL(SqlStmt)
End Sub



