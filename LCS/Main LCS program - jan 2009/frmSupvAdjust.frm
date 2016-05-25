VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Begin VB.Form frmSupvAdjust 
   Caption         =   "Supervisor Weekly Adjustment"
   ClientHeight    =   6465
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   9540
   LinkTopic       =   "Form1"
   ScaleHeight     =   6465
   ScaleWidth      =   9540
   StartUpPosition =   3  'Windows Default
   Begin VB.TextBox paidOtHrsTxt 
      BeginProperty DataFormat 
         Type            =   1
         Format          =   "0"
         HaveTrueFalseNull=   0
         FirstDayOfWeek  =   0
         FirstWeekOfYear =   0
         LCID            =   1033
         SubFormatType   =   1
      EndProperty
      Height          =   315
      Left            =   3300
      MaxLength       =   4
      TabIndex        =   13
      Top             =   4410
      Width           =   1095
   End
   Begin VB.TextBox paidRegHrsTxt 
      BeginProperty DataFormat 
         Type            =   1
         Format          =   "0"
         HaveTrueFalseNull=   0
         FirstDayOfWeek  =   0
         FirstWeekOfYear =   0
         LCID            =   1033
         SubFormatType   =   1
      EndProperty
      Height          =   315
      Left            =   3300
      MaxLength       =   4
      TabIndex        =   11
      Top             =   3990
      Width           =   1095
   End
   Begin VB.TextBox paidAllHrsTxt 
      BeginProperty DataFormat 
         Type            =   1
         Format          =   "0"
         HaveTrueFalseNull=   0
         FirstDayOfWeek  =   0
         FirstWeekOfYear =   0
         LCID            =   1033
         SubFormatType   =   1
      EndProperty
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000080&
      Height          =   315
      Left            =   7350
      Locked          =   -1  'True
      TabIndex        =   9
      Top             =   4170
      Width           =   1095
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "EXIT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   525
      Left            =   5400
      TabIndex        =   7
      Top             =   5580
      Width           =   2685
   End
   Begin VB.CommandButton cmdAdjust 
      Caption         =   "MAKE ADJUSTMENT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   525
      Left            =   1260
      TabIndex        =   6
      Top             =   5580
      Width           =   3075
   End
   Begin SSDataWidgets_B.SSDBCombo supvIDCombo 
      Height          =   360
      Left            =   3210
      TabIndex        =   1
      ToolTipText     =   "Select Employee ID"
      Top             =   1800
      Width           =   2445
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
      _ExtentX        =   4313
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
   Begin SSCalendarWidgets_A.SSDateCombo endDateCombo 
      Height          =   375
      Left            =   3210
      TabIndex        =   17
      ToolTipText     =   "Select Date"
      Top             =   1260
      Width           =   2610
      _Version        =   65543
      _ExtentX        =   4604
      _ExtentY        =   661
      _StockProps     =   93
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.Label Label10 
      BackStyle       =   0  'Transparent
      Caption         =   "NO PAY Hour:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   4650
      TabIndex        =   20
      Top             =   3330
      Width           =   2205
   End
   Begin VB.Label acturalNpHrsLabel 
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000080&
      Height          =   345
      Left            =   6960
      TabIndex        =   19
      Top             =   3330
      Width           =   1185
   End
   Begin VB.Label Label8 
      BackStyle       =   0  'Transparent
      Caption         =   "Week Ending Date"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   570
      TabIndex        =   18
      Top             =   1260
      Width           =   2475
   End
   Begin VB.Label acturalOtHrsLabel 
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000080&
      Height          =   345
      Left            =   6960
      TabIndex        =   16
      Top             =   2940
      Width           =   1185
   End
   Begin VB.Label acturalRegHrsLabel 
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000080&
      Height          =   345
      Left            =   6960
      TabIndex        =   15
      Top             =   2520
      Width           =   1185
   End
   Begin VB.Label acturalHrsLabel 
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000080&
      Height          =   345
      Left            =   2880
      TabIndex        =   14
      Top             =   2790
      Width           =   1185
   End
   Begin VB.Label Label7 
      BackStyle       =   0  'Transparent
      Caption         =   "PAID OT HOUR"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   570
      TabIndex        =   12
      Top             =   4440
      Width           =   2205
   End
   Begin VB.Label Label6 
      BackStyle       =   0  'Transparent
      Caption         =   "PAID REG HOUR"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   540
      TabIndex        =   10
      Top             =   4020
      Width           =   2595
   End
   Begin VB.Label Label5 
      BackStyle       =   0  'Transparent
      Caption         =   "PAID WORK HOUR"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   4680
      TabIndex        =   8
      Top             =   4200
      Width           =   2415
   End
   Begin VB.Label Label4 
      BackStyle       =   0  'Transparent
      Caption         =   "OT Hour:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   4650
      TabIndex        =   5
      Top             =   2940
      Width           =   2205
   End
   Begin VB.Label Label3 
      BackStyle       =   0  'Transparent
      Caption         =   "REGULAR Hour:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   4650
      TabIndex        =   4
      Top             =   2520
      Width           =   2205
   End
   Begin VB.Label Label2 
      BackStyle       =   0  'Transparent
      Caption         =   "Total Work Hour:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   570
      TabIndex        =   3
      Top             =   2790
      Width           =   2205
   End
   Begin VB.Label labTitle 
      Alignment       =   2  'Center
      BackColor       =   &H00FFFFFF&
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   15.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   735
      Left            =   1530
      TabIndex        =   2
      Top             =   90
      Width           =   6765
   End
   Begin VB.Image Image1 
      BorderStyle     =   1  'Fixed Single
      Height          =   885
      Left            =   120
      Picture         =   "frmSupvAdjust.frx":0000
      Stretch         =   -1  'True
      Top             =   0
      Width           =   975
   End
   Begin VB.Line Line1 
      X1              =   60
      X2              =   9330
      Y1              =   990
      Y2              =   990
   End
   Begin VB.Label Label1 
      BackStyle       =   0  'Transparent
      Caption         =   "Supervisor List:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   540
      TabIndex        =   0
      Top             =   1890
      Width           =   2055
   End
End
Attribute VB_Name = "frmSupvAdjust"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit          '5/2/2007 HD2759 Rudy:

Private Function validateData() As Boolean
    validateData = False
    If Trim(paidRegHrsTxt.Text) = "" Then
        DataError ("Please Enter the Regular Hour for the supervisor.")
        paidRegHrsTxt.SetFocus
        Exit Function
    End If
    
    If Trim(paidOtHrsTxt.Text) = "" Then
        paidOtHrsTxt.Text = "0"
    End If
    
    Dim otHrs As Single, regHrs As Single, totalHrs As Single, oldTotal As Single
    otHrs = CSng(Trim(paidOtHrsTxt.Text))
    regHrs = CSng(Trim(paidRegHrsTxt.Text))
    oldTotal = CSng(Trim(Me.acturalHrsLabel.Caption))
    totalHrs = otHrs + regHrs
    
    If otHrs < 0 Or regHrs < 0 Then
        DataError ("Adjustment Hour can't be less than 0.")
        paidRegHrsTxt.SetFocus
        Exit Function
    End If
    
    If (totalHrs > oldTotal) Then
        DataError ("Adjustment Hour is more than actural work hour.")
        Exit Function
    End If
    
    validateData = True
End Function

Private Sub cmdAdjust_Click()
Dim iResponse As Integer       '5/2/2007 HD2759 Rudy:
    
    If Not validateData Then
        Exit Sub
    End If
    
    Dim otHrs As Single, regHrs As Single, totalHrs As Single
    Dim oldReg As Single, oldOt As Single, oldNP As Single, oldTotal As Single
    Dim empId As String
    
    otHrs = CSng(Trim(paidOtHrsTxt.Text))
    regHrs = CSng(Trim(paidRegHrsTxt.Text))
    totalHrs = otHrs + regHrs
    oldReg = CSng(Trim(Me.acturalRegHrsLabel.Caption))
    oldOt = CSng(Trim(Me.acturalOtHrsLabel.Caption))
    oldNP = CSng(Trim(Me.acturalNpHrsLabel.Caption))
    oldTotal = oldReg + oldOt + oldNP
    empId = supvIDCombo.Text
    
    paidAllHrsTxt.Text = CStr(totalHrs)
    
    ' Lets do some final checking before continuing
    iResponse = MsgBox("Are you sure you want to make the changes to the supervisor " & supvIDCombo.Text & "?", vbQuestion + vbYesNo, "Hourly Detail Adjustment") ' One more chance to quit
    If iResponse = vbNo Then Exit Sub
    
    Dim result As Boolean
    result = adjustHourlyDetail(oldReg, oldOt, oldNP, regHrs, otHrs, empId)
    If result = True Then
        MsgBox "Successfully make the adjustment!", vbInformation + vbOKOnly, "Hourly Detail Adjustment"
    End If

    Call supvIDCombo_Click
End Sub

Public Function adjustHourlyDetail(oldReg As Single, oldOt As Single, oldNP As Single, newReg As Single, newOt As Single, empId As String) As Boolean
    Dim dur As Single
    
    OraSession.BeginTrans
    On Error GoTo ErrorHandler
    
    If oldReg > newReg Then
        Call adjustHourlyDetailRecord("REG", newReg, "NP", empId)
        'Less Regular Time, More OT Time
        If oldOt < newOt Then
            dur = oldNP + oldReg - newReg - (newOt - oldOt)
            Call adjustHourlyDetailRecord("NP", dur, "OT", empId)
        End If
    ElseIf oldReg = newReg Then
        If oldOt < newOt Then
            dur = oldNP + oldOt - newOt
            Call adjustHourlyDetailRecord("NP", dur, "OT", empId)
        End If
    End If
    
    If oldOt > newOt Then
        Call adjustHourlyDetailRecord("OT", newOt, "NP", empId)
        'More Regular Time, Less OT Time
        If oldReg < newReg Then
            dur = oldNP + oldOt - newOt - (newReg - oldReg)
            Call adjustHourlyDetailRecord("NP", dur, "REG", empId)
        End If
    ElseIf oldOt = newOt Then
        If oldReg < newReg Then
            dur = oldNP + oldReg - newReg
            Call adjustHourlyDetailRecord("NP", dur, "REG", empId)
        End If
    End If
    
    If oldOt < newOt And oldReg < newReg Then
        dur = oldNP + oldReg - newReg
        Call adjustHourlyDetailRecord("NP", dur, "REG", empId)
        dur = dur + oldOt - newOt
        Call adjustHourlyDetailRecord("NP", dur, "OT", empId)
    End If
    
    If OraDatabase.LastServerErr = 0 Then
        adjustHourlyDetail = True
        OraSession.CommitTrans
    Else
        adjustHourlyDetail = False
        GoTo ErrorHandler
    End If
    
    Exit Function
    
ErrorHandler:
    OraSession.Rollback
    MsgBox "Unable to make the adjustment now! Please try later!", vbExclamation + vbOKOnly, "Hourly Detail Adjustment"
End Function

Public Sub adjustHourlyDetailRecord(oldEarnType As String, newTime As Single, newEarnType As String, empId As String)
    Dim startDate As String, endDate As String
    Dim sqlStmt As String, order As String
    Dim rs As Object
    Dim hr As Single, dur As Single, rdur As Single
    Dim breakTime As Date, endTime As Date, startTime As Date, twelvePM As Date
    Dim payLunch As String, payDinner As String
    Dim row_number As Integer, hire_date As Date
    Dim sf_row_number, service_code, equipment_id, commodity_code, location_id As String
    Dim vessel_id, special_code, customer_id As String, remark As String
    Dim supervisor_id As String     '5/2/2007 HD2759 Rudy:
    
    endDate = Format(endDateCombo.Text, "mm/dd/yyyy")
    startDate = Format(CDate(endDate) - 6, "mm/dd/yyyy")
    
    If UCase(oldEarnType) = "OT" Then
        order = " desc"
    Else
        order = ""
    End If
    sqlStmt = " Select * from hourly_detail where " _
            & " employee_id = '" & empId & "'" _
            & " AND hire_date >= to_date('" & startDate & "', 'mm/dd/yyyy')" _
            & " And hire_date <= to_date('" & endDate & "', 'mm/dd/yyyy')" _
            & " And earning_type_id = '" & oldEarnType & "'" _
            & " Order by hire_date" & order & ", start_time"
    Set rs = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            
    rs.MoveFirst
    While Not rs.EOF
        'hr = hr + rs.Fields("Duration").Value
        dur = rs.Fields("Duration").Value
        
        If hr < newTime And hr + dur > newTime Then
            row_number = rs.Fields("row_number").Value
            sf_row_number = rs.Fields("sf_row_number").Value
            hire_date = Format(rs.Fields("Hire_date").Value, "mm/dd/yyyy")
            service_code = rs.Fields("SERVICE_CODE").Value
            equipment_id = rs.Fields("Equipment_Id").Value
            commodity_code = rs.Fields("Commodity_Code").Value
            location_id = rs.Fields("Location_Id").Value
            vessel_id = rs.Fields("Vessel_Id").Value
            customer_id = rs.Fields("Customer_Id").Value
            supervisor_id = rs.Fields("User_Id").Value
            remark = GetValue(rs.Fields("Remark").Value, "")
        
            startTime = rs.Fields("Start_Time").Value
            endTime = rs.Fields("End_Time").Value
            twelvePM = Format(CStr(hire_date) + " 12:00PM", "mm/dd/yyyy hh:mmAM/PM")
            rdur = Duration(startTime, endTime)
            
            If dur = rdur Then
                payLunch = "Y"
                payDinner = "Y"
            ElseIf dur = rdur - 1 Then
                If startTime <= twelvePM Then
                    payLunch = "N"
                    payDinner = "Y"
                Else
                    payLunch = "Y"
                    payDinner = "N"
                End If
            ElseIf dur = rdur - 2 Then
                payLunch = "N"
                payDinner = "N"
            End If
            breakTime = getBreakTime(startTime, newTime - hr, payLunch, payDinner)
            
            rs.Edit
            rs.Fields("Duration").Value = newTime - hr
            rs.Fields("End_Time").Value = Format(breakTime, "mm/dd/yyyy hh:mmAM/PM")
            rs.Update
            
            rs.AddNew
            rs.Fields("ROW_NUMBER").Value = row_number
            rs.Fields("SF_ROW_NUMBER").Value = sf_row_number
            rs.Fields("HIRE_DATE").Value = hire_date
            rs.Fields("EMPLOYEE_ID").Value = empId
            rs.Fields("START_TIME").Value = Format(breakTime, "mm/dd/yyyy hh:mmAM/PM")
            rs.Fields("END_TIME").Value = Format(endTime, "mm/dd/yyyy hh:mmAM/PM")
            rs.Fields("earning_type_id").Value = newEarnType
            rs.Fields("DURATION").Value = dur - (newTime - hr)
            rs.Fields("SERVICE_CODE").Value = service_code
            rs.Fields("EQUIPMENT_ID").Value = equipment_id
            rs.Fields("COMMODITY_CODE").Value = commodity_code
            rs.Fields("LOCATION_ID").Value = location_id
            rs.Fields("VESSEL_ID").Value = vessel_id
            rs.Fields("CUSTOMER_ID").Value = customer_id
            rs.Fields("USER_ID").Value = supervisor_id
            rs.Fields("SPECIAL_CODE").Value = special_code
            rs.Fields("TIME_ENTRY").Value = Now
            rs.Fields("TIME_UPDATE").Value = Now
            rs.Fields("REMARK").Value = remark
            rs.Update
        ElseIf hr >= newTime Then
            rs.Edit
            rs.Fields("Earning_type_id").Value = newEarnType
            rs.Update
        End If
        hr = hr + dur
        rs.MoveNext
    Wend
    
    rs.Close
    Set rs = Nothing
End Sub

Private Sub cmdExit_Click()
    Unload Me
    frmHiring.Show
End Sub

Private Sub endDateCombo_Click()
    Call endDateCombo_Change
End Sub
Private Sub endDateCombo_Change()
    Call setupForm
End Sub

Private Sub setupForm()
    Me.acturalHrsLabel.Caption = ""
    Me.acturalOtHrsLabel.Caption = ""
    Me.acturalRegHrsLabel.Caption = ""
    Me.acturalNpHrsLabel.Caption = ""
    Me.paidAllHrsTxt.Text = ""
    Me.paidOtHrsTxt.Text = ""
    Me.paidRegHrsTxt.Text = ""
    Me.cmdAdjust.Enabled = False
End Sub

Private Sub Form_Load()
    labTitle.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
        
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    endDateCombo.Text = Format(findLastSunday(Date), "mm/dd/yyyy")
    
    Call ShowSupervisors
End Sub

Private Sub ShowSupervisors()
    Dim gsSqlStmt As String
    Dim rsDETAIL As Object
 
    gsSqlStmt = "SELECT employee_id, employee_name from employee " _
                & " WHERE EMPLOYEE_TYPE_ID='SUPV' " _
                & " ORDER BY EMPLOYEE_ID"
    Set rsDETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)

    rsDETAIL.MoveFirst
    
    supvIDCombo.Columns.RemoveAll
    supvIDCombo.Columns(0).Caption = "Supervisor ID"
    supvIDCombo.Columns(1).Caption = "Supervisor Name"
  
    While Not rsDETAIL.EOF
        supvIDCombo.AddItem rsDETAIL.Fields("Employee_ID").Value & "!" & rsDETAIL.Fields("Employee_Name").Value
        rsDETAIL.MoveNext
    Wend
    
    supvIDCombo.Columns(1).Width = 3500
    
    rsDETAIL.Close
    Set rsDETAIL = Nothing
End Sub

Private Sub Form_Unload(Cancel As Integer)
    Call cmdExit_Click
End Sub


Private Sub paidOtHrsTxt_Validate(Cancel As Boolean)
    On Error GoTo ErrorHandler
    Dim hr As Single
    
    If paidOtHrsTxt.Text = "" Then
        Exit Sub
    ElseIf Not IsNumeric(paidOtHrsTxt.Text) Then
        GoTo ErrorHandler
    End If
    
    If paidRegHrsTxt.Text = "" Then
        hr = 0
    Else
        hr = CSng(paidRegHrsTxt.Text)
    End If
    
    paidAllHrsTxt.Text = CStr(CSng(paidOtHrsTxt.Text) + hr)
    Exit Sub
    
ErrorHandler:
    DataError ("Please enter the adjustment hour as a number!")
    paidOtHrsTxt.Text = ""
    paidOtHrsTxt.SetFocus
End Sub

Private Sub paidRegHrsTxt_Validate(Cancel As Boolean)
    Dim hr As Single
    
    If paidRegHrsTxt.Text = "" Then
        Exit Sub
    ElseIf Not IsNumeric(paidRegHrsTxt.Text) Then
        GoTo ErrorHandler
    End If
    
    If paidOtHrsTxt.Text = "" Then
        hr = 0
    Else
        hr = CSng(paidOtHrsTxt.Text)
    End If
    
    paidAllHrsTxt.Text = CStr(CSng(paidRegHrsTxt.Text) + hr)
    Exit Sub
    
ErrorHandler:
    DataError ("Please enter the adjustment hour as a number!")
    paidRegHrsTxt.Text = ""
    paidRegHrsTxt.SetFocus
End Sub

Private Sub supvIDCombo_Click()
    Dim gsSqlStmt As String
    Dim rsDETAIL As Object
    Dim totalHrs As Single, regHrs As Single, otHrs As Single, npHrs As Single
    Dim startDate As String, endDate As String
    Dim processed As Boolean
    
    endDate = Format(endDateCombo.Text, "mm/dd/yyyy")
    startDate = Format(CDate(endDate) - 6, "mm/dd/yyyy")
    processed = False
 
    gsSqlStmt = " SELECT earning_type_id, sum(Duration) as total, max(TO_SOLOMON) as process from Hourly_Detail " _
                & " WHERE employee_id='" & supvIDCombo.Text & "' " _
                & " AND HIRE_DATE <= to_date('" & endDate & "', 'mm/dd/yyyy') " _
                & " AND HIRE_DATE >= to_date('" & startDate & "', 'mm/dd/yyyy') " _
                & " group by earning_type_id"
    Set rsDETAIL = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)

    rsDETAIL.MoveFirst
    While Not rsDETAIL.EOF
        If processed = False Then
            If UCase(rsDETAIL.Fields("process").Value) = "P" Then
                processed = True
            End If
        End If
        If UCase(rsDETAIL.Fields("earning_type_id").Value) = "REG" Then
            regHrs = rsDETAIL.Fields("total").Value
        ElseIf UCase(rsDETAIL.Fields("earning_type_id").Value) = "OT" Then
            otHrs = rsDETAIL.Fields("total").Value
        ElseIf UCase(rsDETAIL.Fields("earning_type_id").Value) = "NP" Then
            npHrs = rsDETAIL.Fields("total").Value
        End If
        rsDETAIL.MoveNext
    Wend
    
    rsDETAIL.Close
    Set rsDETAIL = Nothing
    
    totalHrs = regHrs + otHrs + npHrs
    acturalHrsLabel.Caption = CStr(totalHrs)
    acturalRegHrsLabel.Caption = CStr(regHrs)
    acturalOtHrsLabel.Caption = CStr(otHrs)
    acturalNpHrsLabel.Caption = CStr(npHrs)
    
    If processed = True Then
        Me.cmdAdjust.Enabled = False
    ElseIf totalHrs > 0 Then
        Me.cmdAdjust.Enabled = True
    Else
        Me.cmdAdjust.Enabled = False
    End If
End Sub

Private Function getBreakTime(dStartTime As Date, timeDiff As Single, payLunch As String, payDinner As String) As Date
    Dim time, lunchTime, dinnerTime As Date
    lunchTime = Format(CStr(DateValue(dStartTime)) + " 12:00PM", "mm/dd/yyyy hh:mmAM/PM")
    dinnerTime = Format(CStr(DateValue(dStartTime)) + " 6:00PM", "mm/dd/yyyy hh:mmAM/PM")
    
    time = DateAdd("n", timeDiff * 60, dStartTime)
    
    If DateDiff("n", dStartTime, lunchTime) >= 0 And DateDiff("n", lunchTime, time) > 0 Then
        If payLunch = "N" Then
            time = DateAdd("n", 60, time)
        End If
    ElseIf dStartTime <= dinnerTime And time > dinnerTime Then
        If payDinner = "N" Then
            time = DateAdd("n", 60, time)
        End If
    End If
    
    getBreakTime = time
End Function

Private Function Duration(sTime As Date, eTime As Date) As Single
    Dim mins As Single
    mins = DateDiff("n", sTime, eTime)
    
    If mins < 0 Then
        mins = 24 * 60 + mins
    End If
     
    Duration = mins / 60
End Function

Private Function DataError(mesg As String)
    MsgBox mesg, vbCritical + vbOKOnly, "Data Error"
End Function
