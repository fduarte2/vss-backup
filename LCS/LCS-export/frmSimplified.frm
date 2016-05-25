VERSION 5.00
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "comdlg32.ocx"
Object = "{C932BA88-4374-101B-A56C-00AA003668DC}#1.1#0"; "msmask32.ocx"
Object = "{5E9E78A0-531B-11CF-91F6-C2863C385E30}#1.0#0"; "MSFLXGRD.OCX"
Begin VB.Form frmSimplified 
   BackColor       =   &H00C0C000&
   Caption         =   "Ceridian Export Application"
   ClientHeight    =   5550
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   6705
   LinkTopic       =   "Form1"
   ScaleHeight     =   5550
   ScaleWidth      =   6705
   StartUpPosition =   3  'Windows Default
   Begin VB.Frame fraHistory 
      BackColor       =   &H00808000&
      Height          =   3975
      Left            =   540
      TabIndex        =   12
      Top             =   600
      Visible         =   0   'False
      Width           =   5655
      Begin VB.CommandButton cmdPostOk 
         Caption         =   "Ok"
         Height          =   495
         Left            =   2190
         MaskColor       =   &H8000000F&
         TabIndex        =   13
         Top             =   3240
         Width           =   1335
      End
      Begin MSFlexGridLib.MSFlexGrid grdTable 
         Height          =   2415
         Left            =   360
         TabIndex        =   14
         Top             =   720
         Width           =   4935
         _ExtentX        =   8705
         _ExtentY        =   4260
         _Version        =   393216
         Cols            =   3
      End
      Begin VB.Label Label1 
         BackColor       =   &H80000016&
         BackStyle       =   0  'Transparent
         Caption         =   "Hourly Detail Records That Need To Be Posted To Ceridian"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00E0E0E0&
         Height          =   255
         Left            =   270
         TabIndex        =   15
         Top             =   300
         Width           =   5175
      End
   End
   Begin VB.Frame fraFrame 
      BackColor       =   &H00808000&
      Height          =   2955
      Left            =   780
      TabIndex        =   2
      Top             =   600
      Width           =   5175
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
         Left            =   3390
         TabIndex        =   7
         Top             =   2040
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
         Left            =   240
         TabIndex        =   6
         Top             =   2040
         Value           =   -1  'True
         Width           =   2205
      End
      Begin VB.CommandButton cmdBrowseDate 
         Caption         =   "..."
         Height          =   285
         Left            =   3180
         TabIndex        =   5
         Top             =   720
         Width           =   375
      End
      Begin VB.CommandButton cmdBrowse 
         Caption         =   "..."
         Height          =   285
         Left            =   4440
         TabIndex        =   4
         Top             =   1410
         Width           =   375
      End
      Begin VB.TextBox txtFilename 
         Height          =   285
         Left            =   1980
         TabIndex        =   3
         Top             =   1410
         Width           =   2505
      End
      Begin MSMask.MaskEdBox txtDate 
         Height          =   285
         Left            =   1980
         TabIndex        =   8
         Top             =   720
         Width           =   1215
         _ExtentX        =   2143
         _ExtentY        =   503
         _Version        =   393216
         MaxLength       =   10
         Mask            =   "##/##/####"
         PromptChar      =   "_"
      End
      Begin VB.Label lblFromDate 
         BackStyle       =   0  'Transparent
         Caption         =   "Timesheet Date:"
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
         Left            =   330
         TabIndex        =   10
         Top             =   720
         Width           =   1575
      End
      Begin VB.Label lblFilename 
         BackStyle       =   0  'Transparent
         Caption         =   "Export Filename:"
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
         Left            =   360
         TabIndex        =   9
         Top             =   1440
         Width           =   1695
      End
   End
   Begin VB.CommandButton cmdQuit 
      Caption         =   "Quit"
      Height          =   495
      Left            =   4110
      TabIndex        =   1
      Top             =   3840
      Width           =   1575
   End
   Begin VB.CommandButton cmdOk 
      Caption         =   "Ok"
      Height          =   495
      Left            =   990
      TabIndex        =   0
      Top             =   3840
      Width           =   1575
   End
   Begin MSComDlg.CommonDialog ctlCommonDialog 
      Left            =   3150
      Top             =   3600
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
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
      Left            =   450
      TabIndex        =   11
      Top             =   4980
      Width           =   5775
   End
End
Attribute VB_Name = "frmSimplified"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False

Private Sub setForm()
    Me.txtDate.SetFocus
    Me.cmdOk.Enabled = True
    MN
End Sub
Private Sub cmdOk_Click()
    Dim myDate As String
    Dim gsMyFileName As String, gsTextDirectory As String, allFileNames As String
    Dim oFileSys As New FileSystemObject
    Dim iRowCount As Long
    
    myDate = Me.txtDate.Text
    gsTextDirectory = App.Path
    gsMyFileName = Me.txtFilename.Text
    allFileNames = gsMyFileName
    
    ' Lets do some final checking before continuing
    iresponse = MsgBox("Did you check the timesheet for " & Me.txtDate & "?", vbYesNo, "Payroll Export")   ' One more chance to quit
    If iresponse = vbNo Then Exit Sub
    
    ' gsMyFilename already exist?
    If oFileSys.FileExists(gsMyFileName) Then
       iresponse = MsgBox(gsMyFileName & " already exists!  Do you want to overwrite it?", vbYesNo, "Payroll Export")   ' One more chance to quit
       If iresponse = vbNo Then Exit Sub
    End If

    ' Before processing lets make sure that the date is valid
    If Not IsDate(myDate) Then
       MsgBox ("You Must Enter a Valid Timesheet Date!")
       Me.txtDate.SetFocus
       Exit Sub
    End If
    
    ' Disable Ok button while processing
    Me.cmdOk.Enabled = False
    
    MB  ' Mouse Busy
    
    ' Before we start lets delete the old log file
    If oFileSys.FileExists(gsTextDirectory & "\PROBLEM.LOG") Then
       oFileSys.DeleteFile gsTextDirectory & "\PROBLEM.LOG", True
    End If
    
    OraSession.BeginTrans
    'On Error GoTo ErrorHandler
    
    If Me.optNewRecs = True Then
        gsSqlStmt = "UPDATE HOURLY_DETAIL SET TO_SOLOMON = 'U' WHERE HIRE_DATE = TO_DATE('" & Format(myDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy') AND (TO_SOLOMON <> 'P' or TO_SOLOMON is null)"
    Else
        gsSqlStmt = "UPDATE HOURLY_DETAIL SET TO_SOLOMON = 'U' WHERE HIRE_DATE = TO_DATE('" & Format(myDate, "mm/dd/yyyy") & "', 'mm/dd/yyyy')"
    End If
    
    iRowCount = OraDatabase.ExecuteSQL(gsSqlStmt)
    If OraDatabase.LastServerErr = 0 And iRowCount = 0 Then
        MsgBox "No records found for " & myDate & "!", vbOKOnly, "Payroll Export"
        Call setForm
        Exit Sub
    End If
    
    'Read from hourly_detail and write to the file
    If writeDailyPay(myDate, gsMyFileName) = False Then
        GoTo ErrorHandler
    End If
    
    If Weekday(CDate(Me.txtDate.Text)) = vbSunday Then
        Dim hazardFileName As String
        hazardFileName = getHazardPayFileName(gsMyFileName, myDate)
        allFileNames = allFileNames & " AND " & hazardFileName
        If writeHazardPay(hazardFileName, myDate) = False Then
            'GoTo ErrorHandler
        End If
    End If
    
    ' If there were any errors then we will warn the user then display a report
    ' detailing all problems.
    If oFileSys.FileExists(gsTextDirectory & "\PROBLEM.LOG") Then
       MsgBox "There were errors in processing this file.  Press 'Ok' for error report!", vbOKOnly, "Payroll Export"
       Call Shell("NOTEPAD.EXE " & gsTextDirectory & "\PROBLEM.LOG", 3)   ' Display problem log
       'GoTo ErrorHandler
    End If
   
    ' Notify user of filename
    MsgBox "File " & allFileNames & " Created Successfully!" & vbCrLf & vbCrLf _
        & "Complete import process with Ceridian Utility - Transaction Import." & vbCrLf _
        & vbCrLf & "Press 'Ok' after you have imported the file into Ceridian." _
        , vbOKOnly, "Payroll Export"
    
    ' With the user's permission we will mark this timesheet as "Posted To Solomon"
    iresponse = MsgBox("Mark these timesheet entries as 'Posted To Ceridian'?", vbYesNo, "Payroll Export")
    If iresponse = vbYes Then
        If blExcludeErrors = True Then
           gsSqlStmt = "UPDATE HOURLY_DETAIL SET TO_SOLOMON = 'P' WHERE TO_SOLOMON = 'U'"
        Else
           gsSqlStmt = "UPDATE HOURLY_DETAIL SET TO_SOLOMON = 'P' WHERE TO_SOLOMON IN ('U','X')"
        End If
        iRowCount = OraDatabase.ExecuteSQL(gsSqlStmt)
        
        Call printResult(iRowCount, allFileNames)
       
        If OraDatabase.LastServerErr = 0 Then
            OraSession.CommitTrans
        Else
            OraSession.Rollback
            'GoTo ErrorHandler
        End If
        Me.lblStatus.Caption = "Post successfully ..."
    Else
        ' Reset the records so they will be exported again
        OraSession.Rollback
        Me.lblStatus.Caption = "Give up posting successfully ..."
    End If
    
    Call setForm
    
    Exit Sub
    
ErrorHandler:
    OraSession.Rollback
    Call setForm
    Me.lblStatus.Caption = "Unable to post to ceridian ..."
    MsgBox "Unable to post to Ceridian now! Please try later!", vbOKOnly, "Payroll Export"
End Sub

Private Sub printResult(count As Long, file As String)
    Printer.Print
    Printer.Print
    Printer.Print Space(30) & "Ceridian Export Process For " & Me.txtDate & " Complete!"
    Printer.Print
    Printer.Print Space(30) & "No. of records processed = " & count
    Printer.Print
    Printer.Print Space(30) & "Ceridian Import Filename = " & file
    Printer.Print
    Printer.Print Space(30) & "Created on " & Now
    Printer.EndDoc
End Sub

Private Sub TransRollBack()
    OraSession.Rollback
    MsgBox "Unable to post to ceridian now. Please try again later.", , "Error"
End Sub

Private Sub cmdPostOk_Click()
    Dim myDate As String

    Me.grdTable.Row = 1
    Me.grdTable.Col = 1
    myDate = Me.grdTable.Text
    setPostFrame (myDate)
End Sub

Private Sub Form_Activate()
  If App.PrevInstance Then
     ' Make sure only one copy at a time is running!
     End
  End If
End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    MB
    lblStatus.Caption = "Logging on to database..."
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful..."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    ' We need to figure out the how many records from the hourly_detail table need to be processed
    ' We will display the hire_date and the number of unposted records for that day in a grid for
    ' informational purposes.
    Call cmdBrowseDate_Click
    MN
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Payroll Export"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0

End Sub

Private Sub cmdBrowseDate_Click()
    Dim gsSqlStmt As String
    
    ' We will need to figure out how many records from the hourly_detail table need to be processed
    ' We will display the hire_date and the number of unposted records for that day in a grid for
    ' informational purposes.

    MB  ' Mouse Busy

    ' Get the number of unposted records
    ' gsSqlStmt = "select hire_date, count(hire_date) as NoRecs from hourly_detail where (to_solomon is null or to_solomon <> 'P') and duration+reg_hrs+ot_hrs > 0 group by hire_date"
    gsSqlStmt = "select hire_date, count(hire_date) as NoRecs from hourly_detail where (to_solomon is null or to_solomon <> 'P') group by hire_date"
    Set rsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And rsHOURLY_DETAIL.RecordCount > 0 Then
        ' We have records so lets set up the grid
        Me.grdTable.ColWidth(0) = 390
        Me.grdTable.ColWidth(1) = 2100
        Me.grdTable.ColWidth(2) = 2100
        Me.grdTable.Row = 0
        Me.grdTable.Col = 1
        Me.grdTable.Text = "    Timesheet Date"
        Me.grdTable.Col = 2
        Me.grdTable.Text = "    No. Records "
        Me.grdTable.Row = 1
        Row = 1
        
        While Not rsHOURLY_DETAIL.EOF
            ' Lets load up the values from each record and populate the grid
            Me.grdTable.Rows = Row + 1
            Me.grdTable.Row = Me.grdTable.Rows - 1
            Me.grdTable.Col = 1
            Me.grdTable.Text = Format(rsHOURLY_DETAIL.Fields("HIRE_DATE").Value, "mm/dd/yyyy")
            Me.grdTable.Col = 2
            Me.grdTable.Text = GetValue(rsHOURLY_DETAIL.Fields("NoRecs").Value, 0)
            Row = Row + 1
            
            rsHOURLY_DETAIL.MoveNext
        Wend
        Row = 2
        Me.fraHistory.Visible = True
        MN  ' Mouse Normal
    Else
       MN   ' Mouse Normal
       MsgBox "Currently there are no unposted records in the database!", vbOKOnly, "Payroll Export"
       Exit Sub
    End If

End Sub

Private Sub grdTable_Click()
    Dim myDate As String
    Me.grdTable.Col = 1
    myDate = Me.grdTable.Text
    setPostFrame (myDate)
End Sub

Private Sub setPostFrame(myDate As String)
    Dim gsMyFileName As String
    
    Me.fraHistory.Visible = False
    Me.txtDate.Text = Format(myDate, "mm/dd/yyyy")
    
    ChDrive "C"
    ChDir ("\")
    gsMyFileName = CurDir & "LCS" & Mid$(Format(myDate, "mm/dd/yyyy"), 1, 2) & Mid$(Format(myDate, "mm/dd/yyyy"), 4, 2) & ".DTA"
    Me.txtFilename.Text = gsMyFileName
    Me.txtDate.SetFocus
End Sub

Private Sub cmdQuit_Click()
    Unload Me
    End
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

Private Sub optAllRecs_Click()
    MsgBox "All Records for " & Me.txtDate & " will be exported to Solomon including those records that have previously been marked as 'Exported to Solomon'!", vbInformation, "Payroll Export"
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
Private Function writeHazardPay(fileName As String, myDate As String) As Boolean
    On Error GoTo ErrHandler
    
    writeHazardPay = True
    Open fileName For Output As #1     ' Open export file
    
    ' Perform a select query on the recordset for guards only and then order it by employee_id,shift descending order
    ' Only 1 record per day for each guard that worked.
    gsSqlStmt = "SELECT DISTINCT MAX(H.SPECIAL_CODE) AS Shift, H.EMPLOYEE_ID,E.EMPLOYEE_NAME FROM HOURLY_DETAIL H,EMPLOYEE E " _
                & "WHERE E.EMPLOYEE_TYPE_ID = 'GUARD' AND H.EMPLOYEE_ID = E.EMPLOYEE_ID AND " _
                & "H.HIRE_DATE >= TO_DATE('" & Format(CDate(myDate) - 6, "mm/dd/yyyy") & "', 'mm/dd/yyyy') AND " _
                & "H.HIRE_DATE <= TO_DATE('" & Format(CDate(myDate), "mm/dd/yyyy") & "', 'mm/dd/yyyy') " _
                & "GROUP BY H.EMPLOYEE_ID,E.EMPLOYEE_NAME"
    Set rsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And rsHOURLY_DETAIL.RecordCount > 0 Then
        Dim record As New hazardPay
        While Not rsHOURLY_DETAIL.EOF
            With record
                .hireDate = Format(Me.txtDate.Text, "dd-mmm-yyyy")
                .EmployeeID = rsHOURLY_DETAIL.Fields("EMPLOYEE_ID").Value
                .ShiftCode = GetValue(rsHOURLY_DETAIL.Fields("SHIFT").Value, "")
                'strEmployeeName = GetValue(rsHOURLY_DETAIL.Fields("EMPLOYEE_NAME").Value, "")
            End With
            
            gsSqlStmt = "SELECT * FROM HAZARD_PAY WHERE SHIFT = '0" & Val(record.ShiftCode) & "'"
            Set rsHAZARD_PAY = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
            
            If OraDatabase.LastServerErr = 0 And rsHAZARD_PAY.RecordCount > 0 Then
              record.payRate = Format(GetValue(rsHAZARD_PAY.Fields("AMOUNT").Value, 0), "#0.00")
            Else
              record.payRate = "0.00"
            End If
           
            If record.payRate <> "0.00" Then
                Print #1, record.getCeridianString
                If record.writeCeridianPay = False Then
                    GoTo ErrHandler
                End If
            End If
        
           rsHOURLY_DETAIL.MoveNext        ' Next Record
       Wend
    End If
    
    Close #1  ' Close Export File
    Set record = Nothing
   
    Exit Function
    
ErrHandler:
    writeHazardPay = False
    Close #1  ' Close Export File
    Set record = Nothing
End Function

Private Function writeDailyPay(myDate As String, fileName As String) As Boolean
    On Error GoTo ErrHandler
    
    writeDailyPay = True
    
    Dim gsSqlStmt As String
    Dim rsHOURLY_DETAIL As Object
    
    gsSqlStmt = "SELECT H.*,E.* FROM HOURLY_DETAIL H,EMPLOYEE E WHERE H.EMPLOYEE_ID = E.EMPLOYEE_ID " _
                & " AND H.TO_SOLOMON='U' " _
                & " AND HIRE_DATE = TO_DATE('" & myDate & "', 'mm/dd/yyyy') " _
                & " ORDER BY E.EMPLOYEE_TYPE_ID,H.EMPLOYEE_ID"
    Set rsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)

    ' Lets start at the top of the file again and create the export file
    rsHOURLY_DETAIL.MoveFirst
        
    Open fileName For Output As #1     ' Open export file
    
    Dim record As New employeeHourlyRecord
    Dim ceridianStr() As String
    
    While Not rsHOURLY_DETAIL.EOF
        With record
            .hireDate = rsHOURLY_DETAIL.Fields("HIRE_DATE").Value
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
    
    Close #1    ' Close Export File
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

 
