VERSION 5.00
Object = "{5E9E78A0-531B-11CF-91F6-C2863C385E30}#1.0#0"; "MSFLXGRD.OCX"
Object = "{E8671A8B-E5DD-11CD-836C-0000C0C14E92}#1.0#0"; "SSCALA32.OCX"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "SSDW3B32.OCX"
Begin VB.Form frmMain 
   BackColor       =   &H000000FF&
   Caption         =   "Line Runners Correction Screen"
   ClientHeight    =   10620
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   14925
   FillColor       =   &H00C0C0C0&
   ForeColor       =   &H00000000&
   LinkTopic       =   "Form1"
   ScaleHeight     =   10620
   ScaleWidth      =   14925
   StartUpPosition =   2  'CenterScreen
   Begin VB.CommandButton cmdRetrieve 
      Caption         =   "Retrieve Data"
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
      Left            =   10320
      TabIndex        =   26
      Top             =   330
      Width           =   2535
   End
   Begin VB.Frame fraEntry 
      BackColor       =   &H000000FF&
      Caption         =   "  Line Runners Corrections"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   8415
      Left            =   120
      TabIndex        =   10
      Top             =   1080
      Width           =   5535
      Begin VB.TextBox txtHours 
         Enabled         =   0   'False
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   2280
         TabIndex        =   2
         Top             =   1305
         Width           =   2655
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboComm 
         Height          =   375
         Left            =   2280
         TabIndex        =   8
         Top             =   6660
         Width           =   2655
         DataFieldList   =   "Column 0"
         AllowNull       =   0   'False
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
         FieldSeparator  =   "!"
         RowHeight       =   609
         Columns.Count   =   2
         Columns(0).Width=   3360
         Columns(0).Caption=   "Commodity Code"
         Columns(0).Name =   "Commodity_Code"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   8625
         Columns(1).Caption=   "Commodity Description"
         Columns(1).Name =   "Commodity_name"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         _ExtentX        =   4683
         _ExtentY        =   661
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
      Begin VB.ComboBox cmbExactEnd 
         Enabled         =   0   'False
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
         ItemData        =   "frmMain.frx":0000
         Left            =   2280
         List            =   "frmMain.frx":0002
         TabIndex        =   4
         Top             =   2730
         Width           =   2655
      End
      Begin VB.ComboBox cmbStartTime 
         Enabled         =   0   'False
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
         ItemData        =   "frmMain.frx":0004
         Left            =   2280
         List            =   "frmMain.frx":0006
         TabIndex        =   1
         Top             =   480
         Width           =   2655
      End
      Begin VB.ComboBox cmbEndTime 
         Enabled         =   0   'False
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
         ItemData        =   "frmMain.frx":0008
         Left            =   2280
         List            =   "frmMain.frx":000A
         TabIndex        =   3
         Top             =   2025
         Width           =   2655
      End
      Begin VB.CommandButton cmdDelete 
         Caption         =   "Delete"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   615
         Left            =   2100
         TabIndex        =   18
         Top             =   7440
         Visible         =   0   'False
         Width           =   1335
      End
      Begin VB.CommandButton cmdCancel 
         Caption         =   "Cancel"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   615
         Left            =   3960
         TabIndex        =   17
         Top             =   7440
         Visible         =   0   'False
         Width           =   1335
      End
      Begin VB.CommandButton cmdUpdate 
         Caption         =   "Update"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   615
         Left            =   240
         TabIndex        =   16
         Top             =   7440
         Visible         =   0   'False
         Width           =   1335
      End
      Begin VB.TextBox txtName 
         Enabled         =   0   'False
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
         Left            =   360
         TabIndex        =   13
         Top             =   4365
         Width           =   4575
      End
      Begin VB.ComboBox cmbEarningType 
         Enabled         =   0   'False
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
         ItemData        =   "frmMain.frx":000C
         Left            =   2280
         List            =   "frmMain.frx":000E
         TabIndex        =   6
         Top             =   5175
         Width           =   2655
      End
      Begin VB.TextBox txtEmployee_ID 
         Enabled         =   0   'False
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
         Left            =   2280
         MaxLength       =   10
         TabIndex        =   5
         Top             =   3525
         Width           =   2655
      End
      Begin SSDataWidgets_B.SSDBCombo SSDBCboSrvc 
         Height          =   375
         Left            =   2280
         TabIndex        =   7
         Top             =   5880
         Width           =   2655
         DataFieldList   =   "Column 0"
         AllowNull       =   0   'False
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
         FieldSeparator  =   "!"
         RowHeight       =   609
         Columns.Count   =   2
         Columns(0).Width=   3200
         Columns(0).Caption=   "Service Code"
         Columns(0).Name =   "Service_code"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(1).Width=   8017
         Columns(1).Caption=   "Service Description"
         Columns(1).Name =   "service_name"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         _ExtentX        =   4683
         _ExtentY        =   661
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
      Begin VB.Label Label7 
         BackColor       =   &H008080FF&
         Caption         =   "Hours:"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   360
         TabIndex        =   23
         Top             =   1365
         Width           =   735
      End
      Begin VB.Label Label9 
         BackColor       =   &H008080FF&
         Caption         =   "End Time:"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   360
         TabIndex        =   22
         Top             =   2085
         Width           =   1215
      End
      Begin VB.Label Label8 
         BackColor       =   &H008080FF&
         Caption         =   "Start Time:"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   300
         Left            =   360
         TabIndex        =   21
         Top             =   510
         Width           =   1215
      End
      Begin VB.Label Label11 
         BackColor       =   &H008080FF&
         Caption         =   "Exact End:"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   360
         TabIndex        =   20
         Top             =   2805
         Width           =   1575
      End
      Begin VB.Label Label10 
         BackColor       =   &H008080FF&
         Caption         =   "Commodity:"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   360
         TabIndex        =   19
         Top             =   6720
         Width           =   1695
      End
      Begin VB.Label Label5 
         BackColor       =   &H008080FF&
         Caption         =   "Service:"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   360
         TabIndex        =   14
         Top             =   5970
         Width           =   1695
      End
      Begin VB.Label Label6 
         BackColor       =   &H008080FF&
         Caption         =   "Earnings Type:"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   360
         TabIndex        =   12
         Top             =   5235
         Width           =   1695
      End
      Begin VB.Label Label4 
         BackColor       =   &H008080FF&
         Caption         =   "Employee ID:"
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
         Left            =   360
         TabIndex        =   11
         Top             =   3525
         Width           =   1530
      End
   End
   Begin MSFlexGridLib.MSFlexGrid grdRecords 
      Height          =   8415
      Left            =   5880
      TabIndex        =   15
      Top             =   1080
      Width           =   8895
      _ExtentX        =   15690
      _ExtentY        =   14843
      _Version        =   393216
      Cols            =   12
      BackColor       =   16777215
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "Exit"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   615
      Left            =   6240
      TabIndex        =   0
      Top             =   9840
      Width           =   1695
   End
   Begin SSCalendarWidgets_A.SSDateCombo txtDate 
      Height          =   375
      Left            =   6960
      TabIndex        =   25
      Top             =   330
      Width           =   2655
      _Version        =   65543
      _ExtentX        =   4683
      _ExtentY        =   661
      _StockProps     =   93
      ForeColor       =   -2147483630
      BackColor       =   -2147483633
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty DropDownFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ShowCentury     =   -1  'True
   End
   Begin VB.Label Label1 
      BackColor       =   &H008080FF&
      Caption         =   "Date:"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   315
      Left            =   5880
      TabIndex        =   24
      Top             =   360
      Width           =   735
   End
   Begin VB.Label lblStatus 
      BackColor       =   &H000000FF&
      Height          =   255
      Left            =   8400
      TabIndex        =   9
      Top             =   10320
      Visible         =   0   'False
      Width           =   1695
   End
End
Attribute VB_Name = "frmMain"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Public CurrentRow As Long
Public Row As Long



Private Sub cmbEarningType_GotFocus()
    SendKeys "%{DOWN}", True
End Sub

Private Sub cmbEarningType_KeyDown(KeyCode As Integer, Shift As Integer)
  If KeyCode = vbKeyReturn Then
     Me.SSDBCboSrvc.SetFocus
  End If
End Sub

Private Sub cmbEarningType_LostFocus()
   Me.cmbEarningType.Text = UCase(Me.cmbEarningType.Text)
End Sub

Private Sub cmbEndTime_KeyDown(KeyCode As Integer, Shift As Integer)
  If KeyCode = vbKeyReturn Then
    Me.cmbExactEnd.SetFocus
  End If
End Sub

'Private Sub cmbEndTime_GotFocus()
'    SendKeys "%{DOWN}", True
'End Sub

Private Sub cmbEndTime_LostFocus()
    ' Now we will calculate how many hours
    Me.txtHours.Text = FindDuration(cmbStartTime.Text, cmbEndTime.Text)
End Sub

Private Sub cmbExactEnd_KeyDown(KeyCode As Integer, Shift As Integer)
  If KeyCode = vbKeyReturn Then
    Me.txtEmployee_ID.SetFocus
  End If
End Sub

Private Sub cmbStartTime_KeyDown(KeyCode As Integer, Shift As Integer)
  If KeyCode = vbKeyReturn Then
    Me.cmbEndTime.SetFocus
  End If
End Sub

'Private Sub cmbStartTime_GotFocus()
'    SendKeys "%{DOWN}", True
'End Sub

Private Sub cmdCancel_Click()
    ' Reset all fields
    Me.cmbStartTime.Text = ""
    Me.cmbEndTime.Text = ""
    Me.cmbEarningType.Text = ""
    Me.cmbExactEnd.Text = ""
    Me.txtHours.Text = ""
    Me.txtEmployee_ID.Text = ""
    Me.txtName.Text = ""
    Me.SSDBCboSrvc.Text = ""
    Me.SSDBCboComm.Text = ""
    Me.cmdCancel.Visible = False
    Me.cmdUpdate.Visible = False
    Me.cmdDelete.Visible = False
    Me.txtDate.SetFocus

End Sub

Private Sub cmdDelete_Click()
    Dim iresponse As Integer, i As Integer, myDelSQL As String, myDelRecCnt As Integer
    Dim strEmployee_Id As String, intRow_Number As Integer
    ' With the user's permission we will delete this entry
    iresponse = MsgBox("Are you sure you want to delete this entry?", vbYesNo, "Line Runners Correction Screen")
    If iresponse = vbYes Then
       ' Lets go and delete this record from LCS
       Me.grdRecords.Col = 2
       strEmployee_Id = Me.grdRecords.Text
       Me.grdRecords.Col = 11
       intRow_Number = CInt(Me.grdRecords.Text)
       ' Before we delete this record we must make sure that it has not already been posted to solomon
       ' If this is the case then we will inform the user and ignore the transaction.
       If Check_If_Posted(strEmployee_Id, intRow_Number, Me.txtDate.Text) Then
          Call MsgBox("This record has already been posted to Solomon!  Delete Denied!", vbCritical + vbOKOnly, "Line Runners Correction Screen")
       Else
           ' All is well - proceed to delete the record from LCS
           OraSession.BeginTrans               'Begin the Transaction
           myDelSQL = "Delete from hourly_detail where Hire_date = to_date('" & Me.txtDate.Text & "','mm/dd/yyyy') and Row_Number = " & intRow_Number & " and employee_id = '" & strEmployee_Id & "'"
           myDelRecCnt = OraDatabase.ExecuteSQL(myDelSQL)
           If myDelRecCnt = 0 Then
              OraSession.Rollback
           End If
           If OraDatabase.LastServerErr = 0 Then
              OraSession.CommitTrans
           Else
              OraSession.Rollback
           End If
        
           If Me.grdRecords.Rows = 2 Then
              ' Last Row - we will just blank out the items
              For i = 0 To 11
                  Me.grdRecords.Col = i
                  Me.grdRecords.Text = ""
              Next i
              Row = 1
              CurrRow = 1
          Else
             grdRecords.Col = 7
             Me.grdRecords.RemoveItem CurrRow
             Me.grdRecords.Refresh
             Row = Me.grdRecords.Rows
             CurrRow = Row - 1
          End If
       End If
    End If
    ' When finished lets clear the text boxes and start from date
    Me.cmbStartTime.Text = ""
    Me.cmbEndTime.Text = ""
    Me.cmbEarningType.Text = ""
    Me.cmbExactEnd.Text = ""
    Me.txtHours.Text = ""
    Me.txtEmployee_ID.Text = ""
    Me.txtName.Text = ""
    Me.SSDBCboSrvc.Text = ""
    Me.SSDBCboComm.Text = ""
    Me.cmdCancel.Visible = False
    Me.cmdUpdate.Visible = False
    Me.cmdDelete.Visible = False
    Me.txtDate.SetFocus
    
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub


Private Sub cmdRetrieve_Click()
   ' Load up the grid with line runners entered for the specified day.
   Dim HourlyRS As Object

   gsSqlStmt = "Select h.*,e.* FROM hourly_detail h,employee e WHERE h.employee_id = e.employee_id and h.hire_date=to_date('" & Format(Me.txtDate.Text, "mm-dd-yyyy") & "','mm-dd-yyyy') and h.remark = 'Line Runners'"
   Set HourlyRS = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
   If HourlyRS.EOF And HourlyRS.BOF Then
      ' No line runners found for this day!
      Call MsgBox("There are no line runners entered for " & Me.txtDate.Text & "!", vbCritical + vbOKOnly, "Line Runners Correction Screen")
      Me.txtDate.SetFocus
      HourlyRS.Close
      Set HourlyRS = Nothing
      Exit Sub
   Else
      Me.grdRecords.Clear
      Me.cmbEndTime.Text = ""
      Me.cmbExactEnd.Text = ""
      Me.txtHours.Text = ""
      Me.txtEmployee_ID = ""
      Me.txtName.Text = ""
      Me.cmbEarningType.Text = ""
      Me.SSDBCboSrvc.Text = ""
      Me.SSDBCboComm.Text = ""
      SetUpGrid
      HourlyRS.MoveFirst
      While Not HourlyRS.EOF
            ' Load up the grid
            Me.grdRecords.Rows = Row + 1
            Me.grdRecords.Row = Row
            Row = Row + 1
            grdRecords.Col = 1
            grdRecords.Text = Me.txtDate.Text
            grdRecords.Col = 2
            grdRecords.Text = HourlyRS.Fields("Employee_ID").Value
            grdRecords.Col = 3
            grdRecords.Text = HourlyRS.Fields("Employee_name").Value
            grdRecords.Col = 4
            grdRecords.Text = HourlyRS.Fields("Service_Code").Value
            grdRecords.Col = 5
            grdRecords.Text = HourlyRS.Fields("Commodity_Code").Value
            grdRecords.Col = 6
            grdRecords.Text = HourlyRS.Fields("Earning_Type_ID").Value
            grdRecords.Col = 7
            grdRecords.Text = HourlyRS.Fields("Duration").Value
            grdRecords.Col = 8
            grdRecords.Text = Format(HourlyRS.Fields("Start_Time").Value, "hh:mm AM/PM")
            grdRecords.Col = 9
            grdRecords.Text = Format(HourlyRS.Fields("End_Time").Value, "hh:mm AM/PM")
            grdRecords.Col = 10
            grdRecords.Text = Format(HourlyRS.Fields("Exact_End").Value, "hh:mm AM/PM")
            grdRecords.Col = 11
            grdRecords.Text = HourlyRS.Fields("Row_Number").Value
            'grdRecords.ColIsVisible(11) = False
            HourlyRS.MoveNext
      Wend
      
   End If
   HourlyRS.Close
   Set HourlyRS = Nothing

End Sub


Private Sub cmdUpdate_Click()
    Dim iresponse As Integer, i As Integer, mySQL As String, myRecCnt As Integer
    Dim strEmployee_Id As String, intRow_Number As Integer, strSrvc_Code As String
    Dim strComm_Code As String, strEarningType As String, intDuration As Double
    Dim dtStart_Time As Date, dtEnd_Time As Date, dtExact_End As Date

    ' Just in case recalc time
    Me.txtHours.Text = FindDuration(cmbStartTime.Text, cmbEndTime.Text)

    If validateRecord = True Then       ' It checks out post the data

        ' With the user's permission we will update this entry
        iresponse = MsgBox("Are you sure you want to update this entry?", vbYesNo, "Line Runners Correction Screen")
        If iresponse = vbYes Then
           ' Lets go and update this record in LCS
           strSrvc_Code = Me.SSDBCboSrvc.Text
           strComm_Code = Me.SSDBCboComm.Text
           strEarningType = Me.cmbEarningType.Text
           intDuration = Val(Me.txtHours.Text)
           dtStart_Time = Format(Me.cmbStartTime.Text, "hh:mm AM/PM")
           dtEnd_Time = Format(Me.cmbEndTime.Text, "hh:mm AM/PM")
           dtExact_End = Format(Me.cmbExactEnd.Text, "hh:mm AM/PM")

           Me.grdRecords.Col = 2
           strEmployee_Id = Me.grdRecords.Text
           Me.grdRecords.Col = 11
           intRow_Number = CInt(Me.grdRecords.Text)
           ' Before we delete this record we must make sure that it has not already been posted to solomon
           ' If this is the case then we will inform the user and ignore the transaction.
           If Check_If_Posted(strEmployee_Id, intRow_Number, Me.txtDate.Text) Then
              Call MsgBox("This record has already been posted to Solomon!  Update Denied!", vbCritical + vbOKOnly, "Line Runners Correction Screen")
           Else
               ' All is well - proceed to update the record in LCS
               
               OraSession.BeginTrans               'Begin the Transaction
               mySQL = "Update hourly_detail set " & _
                       "service_code = '" & strSrvc_Code & "', " & _
                       "commodity_code = " & strComm_Code & ", " & _
                       "earning_type_id = '" & strEarningType & "', " & _
                       "duration = " & intDuration & ", " & _
                       "start_time = to_date('" & Format(Me.txtDate.Text, "mm-dd-yyyy") & ":" & Format(dtStart_Time, "hh:mm AM/PM") & "','mm-dd-yyyy:hh:mi am'), " & _
                       "end_time = to_date('" & Format(Me.txtDate.Text, "mm-dd-yyyy") & ":" & Format(dtEnd_Time, "hh:mm AM/PM") & "','mm-dd-yyyy:hh:mi am'), " & _
                       "exact_end = to_date('" & Format(Me.txtDate.Text, "mm-dd-yyyy") & ":" & Format(dtExact_End, "hh:mm AM/PM") & "','mm-dd-yyyy:hh:mi am') " & _
                       "where Hire_date = to_date('" & Me.txtDate.Text & "','mm/dd/yyyy') and Row_Number = " & intRow_Number & " and employee_id = '" & strEmployee_Id & "'"
               myRecCnt = OraDatabase.ExecuteSQL(mySQL)
               If myRecCnt = 0 Then
                  OraSession.Rollback
               End If
               If OraDatabase.LastServerErr = 0 Then
                  OraSession.CommitTrans
               Else
                  OraSession.Rollback
               End If
            
               ' Update the grid
        
               grdRecords.Col = 4
               grdRecords.Text = Me.SSDBCboSrvc.Text
               grdRecords.Col = 5
               grdRecords.Text = Me.SSDBCboComm.Text
               grdRecords.Col = 6
               grdRecords.Text = Me.cmbEarningType.Text
               grdRecords.Col = 7    ' First back out the old time
               grdRecords.Text = Me.txtHours.Text
               grdRecords.Col = 8
               grdRecords.Text = Me.cmbStartTime.Text
               grdRecords.Col = 9
               grdRecords.Text = Me.cmbEndTime.Text
               grdRecords.Col = 10
               grdRecords.Text = Me.cmbExactEnd.Text
           End If
        End If
        Me.txtDate.SetFocus
        ' When finished lets clear the text boxes and start from date
        Me.cmbEarningType.Text = ""
        Me.SSDBCboSrvc.Text = ""
        Me.SSDBCboComm.Text = ""
        Me.txtHours.Text = ""
        Me.txtEmployee_ID.Text = ""
        Me.txtName.Text = ""
        Me.cmbStartTime.Text = ""
        Me.cmbEndTime.Text = ""
        Me.cmbExactEnd.Text = ""
        
        Me.cmdCancel.Visible = False
        Me.cmdUpdate.Visible = False
        Me.cmdDelete.Visible = False
    End If

End Sub

Private Sub Form_Activate()
  If App.PrevInstance Then
     ' Make sure only one copy at a time is running!
     End
  End If
End Sub

Private Sub Form_Load()
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("LCS", "LABOR/LABOR", 0&)
    'Set OraDatabase = OraSession.OpenDatabase("ISD", "LABOR/LABOR", 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    ' Now Lets Get The Grid Setup
    grdRecords.ColWidth(0) = 200
    grdRecords.ColWidth(1) = 1000
    grdRecords.ColWidth(2) = 1000
    grdRecords.ColWidth(3) = 3200
    grdRecords.ColWidth(4) = 600
    grdRecords.ColWidth(5) = 600
    grdRecords.ColWidth(6) = 1100
    grdRecords.ColWidth(7) = 600
    grdRecords.ColWidth(8) = 1000
    grdRecords.ColWidth(9) = 1000
    grdRecords.ColWidth(10) = 1000

    SetUpGrid
    
    ' Load up the earnings type combo box
    cmbEarningType_Load
    
    LoadTime        ' Load time combo boxes
    
    LoadServiceCodes        ' Load service code combo box
    LoadCommodityCodes      ' Load commodity code combo box
    
    Me.cmbStartTime.Text = ""

    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "LCS Adjustments"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0

End Sub


Private Sub Form_Paint()
    Me.txtDate.SetFocus
End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
    Unload Me
End Sub


Private Sub grdRecords_Click()
    ' If the user chooses to select a blank row then do nothing
    
    Me.grdRecords.Col = 1
    If Me.grdRecords.Text = "" Then Exit Sub
    Me.txtDate.Text = Me.grdRecords.Text
    Me.grdRecords.Col = 2
    Me.txtEmployee_ID.Text = Me.grdRecords.Text
    txtEmployee_ID_LostFocus        ' To give valid choices back to the earning type per employee

    Me.grdRecords.Col = 3
    Me.txtName.Text = Me.grdRecords.Text
    Me.grdRecords.Col = 4
    Me.SSDBCboSrvc.Text = Me.grdRecords.Text
    Me.grdRecords.Col = 5
    Me.SSDBCboComm.Text = Me.grdRecords.Text
    Me.grdRecords.Col = 6
    Me.cmbEarningType.Text = Me.grdRecords.Text
    Me.grdRecords.Col = 7
    Me.txtHours.Text = Me.grdRecords.Text
    Me.grdRecords.Col = 8
    Me.cmbStartTime.Text = Me.grdRecords.Text
    Me.grdRecords.Col = 9
    Me.cmbEndTime.Text = Me.grdRecords.Text
    Me.grdRecords.Col = 10
    Me.cmbExactEnd.Text = Me.grdRecords.Text
    
    CurrRow = Me.grdRecords.Row
    Me.cmbStartTime.Enabled = True
    Me.cmbEarningType.Enabled = True
    Me.cmbEndTime.Enabled = True
    Me.cmbExactEnd.Enabled = True
    Me.txtHours.Enabled = True
    Me.SSDBCboComm.Enabled = True
    Me.SSDBCboSrvc.Enabled = True
    Me.cmdCancel.Visible = True
    Me.cmdUpdate.Visible = True
    Me.cmdDelete.Visible = True

End Sub


Private Sub SSDBCboComm_KeyDown(KeyCode As Integer, Shift As Integer)
  If KeyCode = vbKeyReturn Then
     If Me.cmdUpdate.Visible Then
        Me.cmdUpdate.Enabled = True
        Me.cmdUpdate.SetFocus
     End If
  End If
End Sub


Private Sub SSDBCboComm_LostFocus()
    If Not CheckCode("Select * FROM COMMODITY WHERE COMMODITY_CODE = '" & Me.SSDBCboComm.Text & "'", "Commodity Code", Me.SSDBCboComm.Text) Then
       'Me.SSDBCboComm.SetFocus
    End If
    If Me.cmdUpdate.Visible Then
       Me.cmdUpdate.Enabled = True
       Me.cmdUpdate.SetFocus
    End If
End Sub



Private Sub SSDBCboSrvc_KeyDown(KeyCode As Integer, Shift As Integer)
  If KeyCode = vbKeyReturn Then
     Me.SSDBCboComm.SetFocus
  End If
End Sub

Private Sub SSDBCboSrvc_LostFocus()
    If Not CheckCode("Select * FROM SERVICE WHERE SERVICE_CODE = '" & Me.SSDBCboSrvc.Text & "'", "Service Code", Me.SSDBCboSrvc.Text) Then
       'Me.SSDBCboSrvc.SetFocus
    End If
End Sub

Private Sub txtDate_Change()
    If Me.cmdUpdate.Visible Then
       Me.cmdUpdate.Enabled = True
    End If
End Sub

Private Sub txtDate_KeyDown(KeyCode As Integer, Shift As Integer)
  If KeyCode = vbKeyReturn Then
     Me.cmdRetrieve.SetFocus
  End If
End Sub

Private Sub txtDate_Validate(Cancel As Boolean)
  If Me.cmdUpdate.Visible Then
     Me.cmdUpdate.Enabled = True
  End If
End Sub

Private Sub txtEmployee_ID_Change()
    If Me.cmdUpdate.Visible Then
       Me.cmdUpdate.Enabled = True
    End If
End Sub

Private Sub txtEmployee_ID_KeyDown(KeyCode As Integer, Shift As Integer)
  If KeyCode = vbKeyReturn Then
     Me.cmbEarningType.SetFocus
  End If
End Sub

Private Sub txtEmployee_ID_LostFocus()
   Dim myType As String, emprs As Object
   Debug.Print CurrRow, Me.grdRecords.Row
   
   Me.txtEmployee_ID.Text = UCase(Me.txtEmployee_ID.Text)
   gsSqlStmt = "Select * FROM EMPLOYEE WHERE EMPLOYEE_ID = '" & UCase(Me.txtEmployee_ID.Text) & "'"
   Set emprs = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
   If emprs.EOF And emprs.BOF Then
      ' Employee does not exist!
      Call MsgBox("Invalid Employee ID!", vbCritical + vbOKOnly, "Line Runners")
      Me.txtEmployee_ID.SelStart = 1   ' set selection start and
      Me.txtEmployee_ID.SelLength = Len(Me.txtEmployee_ID.Text)   ' set selection length.
      Me.txtEmployee_ID.SetFocus
      emprs.Close
      Set emprs = Nothing
      Exit Sub
   Else
      emprs.MoveFirst
      Me.txtName.Text = emprs.Fields("Employee_name").Value
      myType = emprs.Fields("Employee_type_id").Value

   End If
   emprs.Close
   Set emprs = Nothing

End Sub


'****************************************
'To Find the Number of Hours between Start and End Time
'****************************************
Private Function FindDuration(myStart As String, myEnd As String) As String
  '******************************************************
  'Case     Start       End     Result
  '1        #AM         #AM     E - S
  '         #PM         #PM
  '2        12AM        #AM     E
  '         12PM        #PM
  '3        #AM         #PM     12 - S + E
  '         #PM         #AM
  '4        #AM         12PM    E - S
  '         #PM         12AM
  '5        12PM        #AM     S + E
  '         12AM        #PM
  '6        12AM        12PM    12
  '         12PM        12AM
  '7        12AM        12AM    0
  '         12PM        12PM
  '8        #AM         12AM    12 - S + E
  '         #PM         12PM
  '******************************************************
  
  Dim TZ1 As String, TZ2 As String
  Dim HR1 As Integer, HR2 As Integer
  Dim MN1 As Integer, MN2 As Integer
  Dim FindMinutes As String, LessHrs As Boolean, FindDur As String
  
  On Error GoTo Err_CheckTime
  
  If GetValue(myStart, "") = "" Or GetValue(myEnd, "") = "" Then
     FindDuration = "0"
     Exit Function
  End If
  
  TZ1 = UCase(Right(Trim(myStart), 2))
  TZ2 = UCase(Right(Trim(myEnd), 2))
  HR1 = CInt(Left(Trim(myStart), 2))
  HR2 = CInt(Left(Trim(myEnd), 2))
  MN1 = CInt(Mid(Trim(myStart), 4, 2))
  MN2 = CInt(Mid(Trim(myEnd), 4, 2))
  
  If HR1 = 8 And MN1 = 0 And HR2 = 5 And MN2 = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    FindDuration = "8.0"
    Exit Function
  End If
  If HR1 = 8 And MN1 = 0 And HR2 = 6 And MN2 = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    FindDuration = "9.0"
    Exit Function
  End If
  If HR1 = 7 And MN1 = 0 And HR2 = 6 And MN2 = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    FindDuration = "10.0"
    Exit Function
  End If
  If HR1 = 7 And MN1 = 0 And HR2 = 5 And MN2 = 0 And TZ1 = "AM" And TZ2 = "PM" Then
    FindDuration = "9.0"
    Exit Function
  End If
  
  'add condition for taking out the lunch time
'  If HR1 <= 10 And HR2 >= 2 And HR2 < 12 And TZ1 = "AM" And TZ2 = "PM" Then
'     'add condition for taking out the lunch time
'     FindDur = Str(12 - HR1 + HR2 - 1)
'  Else
     'Difference in Hours
     If TZ1 = TZ2 And HR1 = HR2 And MN1 = 30 And MN2 = 0 Then
       FindDur = "23"
     ElseIf TZ1 = TZ2 And HR1 = HR2 And MN1 = MN2 Then
       FindDur = "0"
     ElseIf TZ1 = "AM" And TZ2 = "AM" And HR1 <> 12 And HR2 <> 12 Then 'Case 1
       If HR1 > HR2 Then
         FindDur = Str(12 + 12 - HR1 + HR2)
       Else
         FindDur = Str(HR2 - HR1)
       End If
     ElseIf TZ1 = "PM" And TZ2 = "PM" And HR1 <> 12 And HR2 <> 12 Then 'Case 1
       FindDur = Str(HR2 - HR1)
     ElseIf TZ1 = "AM" And TZ2 = "AM" And HR1 = 12 And HR2 <> 12 Then 'Case 2
       'FindDur = Str(HR2)
       If HR2 = 1 And MN1 > MN2 Then
         FindDur = "0"
       ElseIf HR2 = 1 And MN1 <= MN2 Then
         FindDur = Str(HR2)
       Else
         If MN1 > MN2 Then
            FindDur = Str(HR2) - 1
         Else
            FindDur = Str(HR2)
         End If
       End If
       
     ElseIf TZ1 = "PM" And TZ2 = "PM" And HR1 = 12 And HR2 <> 12 Then 'Case 2
       If HR2 = 1 And MN1 > MN2 Then
         FindDur = "0"
       ElseIf HR2 = 1 And MN1 <= MN2 Then
         FindDur = Str(HR2)
       Else
         FindDur = Str(HR2)
       End If
     ElseIf TZ1 = "PM" And TZ2 = "AM" And HR1 <> 12 And HR2 <> 12 Then  'Case 3
       FindDur = Str(12 - HR1 + HR2)
     ElseIf TZ1 = "AM" And TZ2 = "PM" And HR1 <> 12 And HR2 <> 12 Then  'Case 3
       FindDur = Str(12 - HR1 + HR2)
     ElseIf TZ1 = "AM" And TZ2 = "PM" And HR2 = 12 And HR1 <> 12 Then  'Case 4
       FindDur = Str(HR2 - HR1)
     ElseIf TZ1 = "PM" And TZ2 = "AM" And HR2 = 12 And HR1 <> 12 Then  'Case 4
       FindDur = Str(HR2 - HR1)
     ElseIf TZ1 = "AM" And TZ2 = "PM" And HR1 = 12 And HR2 <> 12 Then  'Case 5
       FindDur = Str(HR1 + HR2)
     ElseIf TZ1 = "PM" And TZ2 = "AM" And HR1 = 12 And HR2 <> 12 Then  'Case 5
       FindDur = Str(HR1 + HR2)
     ElseIf TZ1 = "PM" And TZ2 = "AM" And HR1 = 12 And HR2 = 12 Then  'Case 6
       FindDur = "12"
     ElseIf TZ1 = "AM" And TZ2 = "PM" And HR1 = 12 And HR2 = 12 Then  'Case 6
       FindDur = "12"
     ElseIf TZ1 = "PM" And TZ2 = "PM" And HR1 = 12 And HR2 = 12 Then 'Case 7
       FindDur = "0"
     ElseIf TZ1 = "AM" And TZ2 = "AM" And HR1 = 12 And HR2 = 12 Then 'Case 7
       FindDur = "0"
     ElseIf TZ1 = "AM" And TZ2 = "AM" And HR1 <> 12 And HR2 = 12 Then  'Case 8
       FindDur = Str(12 - HR1 + HR2)
     ElseIf TZ1 = "PM" And TZ2 = "PM" And HR1 <> 12 And HR2 = 12 Then  'Case 8
       FindDur = Str(12 - HR1 + HR2)
     End If
    
    
'  End If
  

  'Difference in Minutes
  If MN1 = MN2 Then
    'FindMinutes = "00"  'Duration in 1/2 an Hour
    FindMinutes = "0"
  ElseIf MN1 < MN2 Then
    FindMinutes = Str(MN2 - MN1)
  Else
    FindMinutes = Str(60 - MN1 + MN2)
    If TZ1 = TZ2 And TZ1 = "PM" And HR1 <> 12 Then
      'Do Nothing
       LessHrs = True
    ElseIf TZ1 = TZ2 And TZ1 = "AM" And HR1 = HR2 And HR1 = 12 Then
      'Do Nothing
    Else
      LessHrs = True 'Duration in 1/2 an Hour
    End If
  End If

  If Trim(FindMinutes) = "30" Then
    FindMinutes = "5"
  End If
  
  'Concatenate Minutes with Hours and return the Value
  If LessHrs = True Then  'Less 1 from Hour
    'add
    If HR1 = 12 Then
        FindDur = Trim(FindDur)
    Else
        FindDur = Trim(Str(CInt(FindDur) - 1))
    End If
    'FindDur = Trim(Str(CInt(FindDur) - 1))
  Else
    FindDur = Trim(FindDur)
  End If
  
  
  ''If Start in AM and End in PM, just deduct 1 from Duration - Not for William Stansbury
  'If UserID <> "E405833" And TZ1 = "AM" And TZ2 = "PM" Then FindDur = Trim(Str(CInt(FindDur) - 1))

  FindDuration = Trim(Str(CSng(FindDur) + CSng(FindMinutes) / 10))
  If Len(Trim(FindDuration)) = 1 Then FindDuration = FindDuration + ".0"
  On Error GoTo 0
  Exit Function
  
Err_CheckTime:

    Call MsgBox("Invalid Start and End Time!", vbCritical + vbOKOnly, "Line Runners")
    FindDuration = "0"
    On Error GoTo 0

End Function
Private Function LoadTime()
  Dim i As Integer, j As Integer, arrMin(2) As String, tz As String
  'Duration in 1/2 an Hour
  arrMin(0) = "00"
  arrMin(1) = "30"
  For i = 1 To 12
    For j = 0 To 1
      If i = 12 Then tz = "PM" Else tz = "AM"
      If i < 10 Then
        cmbStartTime.AddItem "0" + Trim(Str(i)) + ":" + arrMin(j) + tz
        cmbEndTime.AddItem "0" + Trim(Str(i)) + ":" + arrMin(j) + tz
        cmbExactEnd.AddItem "0" + Trim(Str(i)) + ":" + arrMin(j) + tz
      Else
        cmbStartTime.AddItem Trim(Str(i)) + ":" + arrMin(j) + tz
        cmbEndTime.AddItem Trim(Str(i)) + ":" + arrMin(j) + tz
        cmbExactEnd.AddItem Trim(Str(i)) + ":" + arrMin(j) + tz
      End If
  Next j, i
  For i = 1 To 12
    For j = 0 To 1
      If i = 12 Then tz = "AM" Else tz = "PM"
      If i < 10 Then
        cmbStartTime.AddItem "0" + Trim(Str(i)) + ":" + arrMin(j) + tz
        cmbEndTime.AddItem "0" + Trim(Str(i)) + ":" + arrMin(j) + tz
        cmbExactEnd.AddItem "0" + Trim(Str(i)) + ":" + arrMin(j) + tz
      Else
        cmbStartTime.AddItem Trim(Str(i)) + ":" + arrMin(j) + tz
        cmbEndTime.AddItem Trim(Str(i)) + ":" + arrMin(j) + tz
        cmbExactEnd.AddItem Trim(Str(i)) + ":" + arrMin(j) + tz
      End If
  Next j, i

End Function

'****************************************
'To Update the Data to DB
'****************************************
Private Sub UpdateData(myRow As Integer)
    Dim mySQL As String, HourlyRS As Object, iRowCount As Integer
    Dim iError As Integer, myRowNumber As Integer, rsDailyHire As Object
    Dim strDate As String, strEmployee_Id As String, iDuration As Double
    Dim strEarningType As String, strServiceCode As String, strCommodityCode As String
    Dim strExactEnd As String, strStartTime As String, strEndTime As String

    Me.grdRecords.Row = myRow
    Me.grdRecords.Col = 1
    strDate = Me.grdRecords.Text
    Me.grdRecords.Col = 2
    strEmployee_Id = Me.grdRecords.Text
    Me.grdRecords.Col = 4
    strServiceCode = Me.grdRecords.Text
    Me.grdRecords.Col = 5
    strCommodityCode = Me.grdRecords.Text
    Me.grdRecords.Col = 6
    strEarningType = Me.grdRecords.Text
    Me.grdRecords.Col = 7
    iDuration = Val(Me.grdRecords.Text)
    Me.grdRecords.Col = 8
    strStartTime = Me.grdRecords.Text
    Me.grdRecords.Col = 9
    strEndTime = Me.grdRecords.Text
    Me.grdRecords.Col = 10
    strExactEnd = Me.grdRecords.Text
    
    ' Check and see if this employee is in the daily_hire_list table.
    ' If he is not then add a record in the daily_hire_list table for that day.
    mySQL = "select hire_date from daily_hire_list where employee_id = '" & strEmployee_Id & "' and hire_date=to_date('" & Format(strDate, "MM/DD/yyyy") & "','mm-dd-yyyy')"
    Set rsDailyHire = OraDatabase.DBCreateDynaset(mySQL, 0&)
    
    If OraDatabase.LastServerErr = 0 And rsDailyHire.RecordCount = 0 Then
       ' We have to add this record to the daily_hire_list table
       mySQL = "insert into daily_hire_list (hire_date,employee_id,time_in,user_id) values(to_date('" & Format(strDate, "MM/DD/yyyy") & "','mm-dd-yyyy'),'" & strEmployee_Id & "',to_date('" & Format(strDate, "MM/DD/yyyy") & ":" & Format(strStartTime, "hh:mm:ss") & "','mm/dd/yyyy:hh24:mi:ss'),'E407612')"
       iRowCount = OraDatabase.ExecuteSQL(mySQL)
       If iRowCount = 0 Then
          MsgBox "Error occured while saving to LCS Daily Hire List!  Employee - " & strEmployee_Id & _
          " " & strDate & " " & iDuration & " " & strEarningType & " " & strServiceCode & _
          "-" & strCommodityCode & "   Row - " & myRowNumber, vbExclamation, "Post Batch"
       End If
    End If
    
    myRowNumber = CheckRowNo(strEmployee_Id, strDate)

    mySQL = "insert into hourly_detail (row_number,vessel_id,customer_id,hire_date,employee_id,start_time,end_time,exact_end,duration,earning_type_id,billing_flag,service_code,equipment_id,commodity_code,location_id,user_id,special_code,exception,remark,Time_Entry) values(" & _
    myRowNumber & ",0,0,to_date('" & Format(strDate, "MM/DD/yyyy") & "','mm/dd/yyyy'),'" & strEmployee_Id & "',to_date('" & Format(strDate, "MM/DD/yyyy") & ":" & Format(strStartTime, "hh:mm:ss") & "','mm/dd/yyyy:hh24:mi:ss')," & _
    "to_date('" & Format(strDate, "MM/DD/yyyy") & ":" & Format(strEndTime, "hh:mm:ss") & "','mm/dd/yyyy:hh24:mi:ss')" & "," & _
    "to_date('" & Format(strDate, "MM/DD/yyyy") & ":" & Format(strExactEnd, "hh:mm:ss") & "','mm/dd/yyyy:hh24:mi:ss')" & "," & iDuration & _
    ",'" & strEarningType & "','N'," & Val(strServiceCode) & ",0," & Val(strCommodityCode) & ",'E407612','E407612','0'," & _
    "'N','Line Runners',to_date('" & Format(Now, "MM/DD/yyyy") & ":" & Format(Now, "hh:mm:ss") & "','mm/dd/yyyy:hh24:mi:ss'))"

    iRowCount = OraDatabase.ExecuteSQL(mySQL)
    If iRowCount = 0 Then
        MsgBox "Error occured while saving to LCS!  Employee - " & strEmployee_Id & _
        " " & strDate & " " & iDuration & " " & strEarningType & " " & strServiceCode & _
        "-" & strCommodityCode & "   Row - " & myRowNumber, vbExclamation, "Post Batch"
    End If

    
End Sub

Function CheckRowNo(myEmployee As String, myDate As String) As Integer
    Dim rsMaxRow As Object
    gsSqlStmt = "select max(row_number) As MAXROW from hourly_detail where employee_id='" & myEmployee & "' and hire_date=to_date('" & myDate & "','mm-dd-yyyy')"
    Set rsMaxRow = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And rsMaxRow.RecordCount > 0 Then
       CheckRowNo = GetValue(rsMaxRow.Fields("MAXROW").Value, 0) + 1
    Else
       CheckRowNo = 1
    End If
    
    rsMaxRow.Close
    Set rsMaxRow = Nothing
End Function

Function CheckCode(SqlStmt As String, strTitle As String, myCode As String)
    Dim checkRS As Object
    
    If myCode = "0000" Then
       CheckCode = True
       Exit Function
    End If
    
    On Error GoTo Err_CheckCode
    
    Set checkRS = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
    
    If checkRS.EOF And checkRS.BOF Then
       ' service code does not exist!
       Call MsgBox("Invalid " & strTitle & "!", vbCritical + vbOKOnly, "Line Runners")
       CheckCode = False
    Else
       CheckCode = True
    End If
    checkRS.Close
    Set checkRS = Nothing
    
    On Error GoTo 0
    Exit Function
Err_CheckCode:

    Call MsgBox("Invalid Service/Commodity Codes!", vbCritical + vbOKOnly, "Line Runners")

    CheckCode = False
    On Error GoTo 0
    
End Function

Private Sub cmbEarningType_Load()
    Me.cmbEarningType.AddItem "REG"
    Me.cmbEarningType.AddItem "OT"
    'Added by Inigo Thomas 3/9/2000. Requested by R.Mangini
    Me.cmbEarningType.AddItem "DT"
End Sub

Private Function validateRecord() As Boolean
    Dim strCode As String
    ' Validate all of the fields
    If Val(Me.txtHours.Text) = 0 Then
       Call MsgBox("Hours cannot be equal to 0!", vbOKOnly, "Line Runners")
       validateRecord = False
       Exit Function
    End If
    
    ' Valid employee?
    If Me.txtName.Text = "" Then
       Call MsgBox("Invalid Employee Number!", vbOKOnly, "Line Runners")
       Me.txtEmployee_ID.SetFocus
       validateRecord = False
       Exit Function
    End If
    
    ' Valid earnings type?
    Select Case Me.cmbEarningType.Text
        Case "REG", "OT", "PERS", "HOL-REG", "HOL-OT", "BIRTH", "BIRTH-OT", "ST", "DT"
             ' Okay
        Case Else
             Call MsgBox("Invalid Earnings Type!", vbOKOnly, "Line Runners")
             Me.cmbEarningType.SetFocus
             validateRecord = False
             Exit Function
    End Select

    
    ' Check for valid service code
    
    If Not CheckCode("Select * FROM SERVICE WHERE SERVICE_CODE = '" & Me.SSDBCboSrvc.Text & "'", "Service Code", Me.SSDBCboSrvc.Text) Then
       Me.SSDBCboSrvc.SetFocus
       validateRecord = False
       Exit Function
    End If
    
    strCode = Me.SSDBCboComm.Text       ' Commodity Code
    If Not CheckCode("Select * FROM COMMODITY WHERE COMMODITY_CODE = '" & strCode & "'", "Commodity Code", strCode) Then
       Me.SSDBCboComm.SetFocus
       validateRecord = False
       Exit Function
    End If
   
    validateRecord = True
    
End Function

Private Sub SetUpGrid()
    grdRecords.Row = 0
    grdRecords.Col = 1
    grdRecords.Text = "   Date"
    grdRecords.Col = 2
    grdRecords.Text = "Employee ID"
    grdRecords.Col = 3
    grdRecords.Text = "Employee Name"
    grdRecords.Col = 4
    grdRecords.Text = "Srvc"
    grdRecords.Col = 5
    grdRecords.Text = "Comm"
    grdRecords.Col = 6
    grdRecords.Text = "Earning Type"
    grdRecords.Col = 7
    grdRecords.Text = " Hours"
    grdRecords.Col = 8
    grdRecords.Text = " Start Time"
    grdRecords.Col = 9
    grdRecords.Text = " End Time"
    grdRecords.Col = 10
    grdRecords.Text = "Exact End"
   
    
    grdRecords.Rows = 1
    Row = 1

End Sub


Private Sub LoadCommodityCodes()
  Dim i As Integer
  SSDBCboComm.Columns.RemoveAll
  For i = 0 To 1
    SSDBCboComm.Columns.Add i
  Next
  Dim CommRS As Object
  SSDBCboComm.Columns(1).Width = 3500
  SSDBCboComm.Columns(0).Caption = "Commodity Code"
  SSDBCboComm.Columns(1).Caption = "Commodity Name"
 
  Set CommRS = OraDatabase.DBCreateDynaset("Select * from Commodity Order by Commodity_Code", 0&)
  If CommRS.EOF And CommRS.BOF Then
    Exit Sub
  Else
    CommRS.MoveFirst
    Do While Not CommRS.EOF
      Me.SSDBCboComm.AddItem CommRS.Fields("Commodity_Code").Value & "!" & CommRS.Fields("Commodity_Name").Value
      CommRS.MoveNext
    Loop
  End If
  CommRS.Close
  Set CommRS = Nothing
End Sub

Private Sub LoadServiceCodes()
  Dim i As Integer
  SSDBCboSrvc.Columns.RemoveAll
  For i = 0 To 1
    SSDBCboSrvc.Columns.Add i
  Next
  Dim ServRS As Object
  SSDBCboSrvc.Columns(1).Width = 4000
  SSDBCboSrvc.Columns(0).Caption = "Service Code"
  SSDBCboSrvc.Columns(1).Caption = "Service Name"
 
  Set ServRS = OraDatabase.DBCreateDynaset("Select * from Service Order by Service_Code", 0&)
  If ServRS.EOF And ServRS.BOF Then
    Exit Sub
  Else
    ServRS.MoveFirst
    Do While Not ServRS.EOF
      Me.SSDBCboSrvc.AddItem ServRS.Fields("Service_Code").Value & "!" & ServRS.Fields("Service_Name").Value
      ServRS.MoveNext
    Loop
  End If
  ServRS.Close
  Set ServRS = Nothing
End Sub

Private Sub txtHours_LostFocus()
    If Val(Me.txtHours.Text) <= 0 Then
       Call MsgBox("Hours cannot be less than or equal to 0!", vbCritical + vbOKOnly, "Line Runners")
       Exit Sub
    End If
    If Val(Me.txtHours.Text) > 24 Then
       Call MsgBox("You cannot have more than 24 Hours in a day!", vbCritical + vbOKOnly, "Line Runners")
       Exit Sub
    End If
    Me.cmbEndTime.Text = FindEndTime
End Sub

'****************************************
'To Find the End Time from Duration and Start Time
'****************************************
Private Function FindEndTime() As String
  Dim TZ1 As String, TotalHr As Integer, TotalMn As Integer
  Dim HR1 As Integer, HR2 As Integer, Pos As Integer
  Dim MN1 As Integer, MN2 As Integer, NewEnd As String
  Dim LineEarn As String, LineDur As String, LineSvc As String
  
  If Trim(Me.cmbStartTime.Text) = vbNullString Then
    Exit Function
  End If
  TZ1 = UCase(Right(Trim(Me.cmbStartTime.Text), 2))

  HR1 = CInt(Left(Trim(Me.cmbStartTime.Text), 2))
  MN1 = CInt(Mid(Trim(Me.cmbStartTime.Text), 4, 2))
  
  If InStr(Me.txtHours.Text, ".") = 0 Then Me.txtHours.Text = Me.txtHours.Text + ".0"
  Pos = InStr(Me.txtHours.Text, ".")
  HR2 = CInt(Left(Trim(Me.txtHours.Text), Pos - 1))
  MN2 = CInt(Mid(Trim(Me.txtHours.Text), Pos + 1))
  
  'Find Total Hours
  TotalHr = HR1 + HR2
  If TotalHr > 12 Then
    TotalHr = TotalHr - 12
    If HR1 = 12 Then    'Don't Change AM/PM if already Start Time is 12
    'Do Nothing
    Else
      If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
    End If
  ElseIf TotalHr = 12 And HR1 <> 12 Then
    If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
  End If
  
  'Find Total Minutes
  If MN2 = 5 Then
     MN2 = 30
  End If
  TotalMn = MN1 + MN2
  If TotalMn >= 60 Then
    TotalHr = TotalHr + 1
    If TotalHr > 12 Then TotalHr = TotalHr - 12
    If TotalHr = 12 Then
      If TZ1 = "AM" Then TZ1 = "PM" Else TZ1 = "AM"
    End If
    TotalMn = TotalMn - 60
  End If
  
  If Len(Trim(Str(TotalHr))) = 1 And Len(Trim(Str(TotalMn))) = 1 Then
    FindEndTime = "0" + Trim(Str(TotalHr)) + ":0" + Trim(Str(TotalMn)) + TZ1
  ElseIf Len(Trim(Str(TotalHr))) = 1 And Len(Trim(Str(TotalMn))) = 2 Then
    FindEndTime = "0" + Trim(Str(TotalHr)) + ":" + Trim(Str(TotalMn)) + TZ1
  ElseIf Len(Trim(Str(TotalHr))) = 2 And Len(Trim(Str(TotalMn))) = 1 Then
    FindEndTime = Trim(Str(TotalHr)) + ":0" + Trim(Str(TotalMn)) + TZ1
  Else
    FindEndTime = Trim(Str(TotalHr)) + ":" + Trim(Str(TotalMn)) + TZ1
  End If
End Function

Function Check_If_Posted(myEmployee_Id As String, myRow_Number As Integer, myDate As Date) As Boolean
   Dim emprs As Object
   
   Check_If_Posted = False
   gsSqlStmt = "Select * FROM hourly_detail WHERE EMPLOYEE_ID = '" & myEmployee_Id & "' and Row_Number = " & myRow_Number & " and hire_date = to_date('" & Format(myDate, "mm-dd-yyyy") & "','mm-dd-yyyy')"
   Set emprs = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
   If emprs.EOF And emprs.BOF Then
      ' Record does not exist!
   Else
      emprs.MoveFirst
      If emprs.Fields("TO_SOLOMON").Value = "P" Then
         Check_If_Posted = True
      End If
   End If
   emprs.Close
   Set emprs = Nothing

End Function
