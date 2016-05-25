VERSION 5.00
Begin VB.Form frmSetFreeTime 
   Caption         =   "Set Free Time"
   ClientHeight    =   3600
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   5430
   LinkTopic       =   "Form1"
   ScaleHeight     =   3600
   ScaleWidth      =   5430
   StartUpPosition =   1  'CenterOwner
   Begin VB.CommandButton cmdDateSailed 
      Caption         =   "..."
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
      Left            =   4680
      TabIndex        =   3
      Top             =   1320
      Width           =   495
   End
   Begin VB.CommandButton cmdVesList 
      Caption         =   "..."
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
      Left            =   3840
      TabIndex        =   1
      Top             =   240
      Width           =   495
   End
   Begin VB.CommandButton cmdCancel 
      Caption         =   "Exit Program"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   2760
      TabIndex        =   8
      Top             =   2400
      Width           =   1815
   End
   Begin VB.CommandButton cmdProcessFT 
      Caption         =   "Process Free Time"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   240
      TabIndex        =   4
      Top             =   2400
      Width           =   2055
   End
   Begin VB.TextBox txtVesNo 
      Height          =   375
      Left            =   2280
      TabIndex        =   2
      Top             =   240
      Width           =   1455
   End
   Begin VB.Label lblStartFTWeekday 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFC0&
      BorderStyle     =   1  'Fixed Single
      ForeColor       =   &H80000008&
      Height          =   375
      Left            =   3360
      TabIndex        =   13
      Top             =   1800
      Width           =   1215
   End
   Begin VB.Label lblSailWeekDay 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFC0&
      BorderStyle     =   1  'Fixed Single
      ForeColor       =   &H80000008&
      Height          =   375
      Left            =   3360
      TabIndex        =   12
      Top             =   1320
      Width           =   1215
   End
   Begin VB.Label lblVesselName 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFC0&
      BorderStyle     =   1  'Fixed Single
      ForeColor       =   &H80000008&
      Height          =   375
      Left            =   240
      TabIndex        =   11
      Top             =   840
      Width           =   4335
   End
   Begin VB.Label lblDashBoard 
      Alignment       =   2  'Center
      Appearance      =   0  'Flat
      BackColor       =   &H00000000&
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H0000FF00&
      Height          =   375
      Left            =   120
      TabIndex        =   10
      Top             =   3000
      Visible         =   0   'False
      Width           =   4455
   End
   Begin VB.Label lblSailedDate 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFC0&
      BorderStyle     =   1  'Fixed Single
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H80000008&
      Height          =   375
      Left            =   2160
      TabIndex        =   5
      Top             =   1320
      Width           =   1215
   End
   Begin VB.Label Label3 
      AutoSize        =   -1  'True
      Caption         =   "Date Sailed"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   195
      Left            =   120
      TabIndex        =   9
      Top             =   1320
      Width           =   1005
   End
   Begin VB.Label lblStartFTDate 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFC0&
      BorderStyle     =   1  'Fixed Single
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H80000008&
      Height          =   375
      Left            =   2160
      TabIndex        =   6
      Top             =   1800
      Width           =   1215
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      Caption         =   "Date of Start Free Time"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   195
      Left            =   120
      TabIndex        =   7
      Top             =   1800
      Width           =   2010
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "Vessel Number"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   195
      Left            =   120
      TabIndex        =   0
      Top             =   240
      Width           =   1275
   End
End
Attribute VB_Name = "frmSetFreeTime"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False

Private Sub cmdCancel_Click()
    
    ' Exit Program
    End
End Sub

Private Sub cmdDateSailed_Click()
    dateOption = 1
    Dim i As Integer
    Dim msg As String
    
    If (ShipSailed) Then
        msg = "Vessel:" & Trim(Me.txtVesNo.Text) & " already sailed. Are you sure you want to change the Date of Sailed?"
        i = MsgBox(msg, vbYesNo)
        If i <> vbYes Then Exit Sub
    End If
    frmCalendar.Show
    
End Sub

Private Sub cmdProcessFT_Click()
    
    Dim i As Integer
    
    If Len(Me.txtVesNo.Text) = 0 Then
        MsgBox "Please provide a vessel number"
        Me.txtVesNo.SetFocus
        Exit Sub
    End If
    
    If (Me.lblDashBoard.Visible = True) Then
    
        Me.lblDashBoard.Visible = False
        
    End If
    Dim msg As String
    msg = "It will start setting free time. Please be patient." & _
        "You will see 'Process Completed' when finished" & Chr(13) & _
        "Click on OK to continue, Cancel to Exit"
    
    i = MsgBox(msg, vbOKCancel)
    If i <> vbOK Then Exit Sub
    
    Screen.MousePointer = vbHourglass

    '' Regualr vessels
    If (Not IsAutoRun(Me.txtVesNo.Text)) Then
    
        If Len(Me.lblStartFTDate.Caption) = 0 Then
            MsgBox "Please provide a date of start free time"
            Exit Sub
        End If
     
        '' Step-1: Update FREE_TIME_START in VOYAGE
        Call UpdateVoyage(Int(Val(Me.txtVesNo.Text)), Me.lblSailedDate.Caption, Me.lblStartFTDate.Caption)
        
        '' Step-2: Update CARGO_TRACKING
        Call UpdateCargoTracking(Int(Val(Me.txtVesNo.Text)))
        
    Else
    
    '' Trucked In Cargos
        Call UpdateTruckedInCargo(Me.txtVesNo.Text)
        
    End If
    
    Me.lblDashBoard.Visible = True
    Me.lblDashBoard.Caption = "Process Completed"
    Screen.MousePointer = vbDefault
    
    
End Sub

Private Sub UpdateVoyage(lr_num As Integer, depDate As String, startFT As String)

On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDatabase As Object
    Dim rs As Object
    Dim strSql As String
    
    depDate = "'" & depDate & "'"
    startFT = "'" & startFT & "'"
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        
        '' Prepare sql statement
        strSql = "update VOYAGE v" & _
                " set v.FREE_TIME_START=TO_DATE(" & startFT & ", 'mm/dd/yyyy')" & _
                " where v.LR_NUM=" & lr_num
                
        '' Begin Transaction
        OraSession.BeginTrans
        
        '' Execute SQL statement
        OraDatabase.ExecuteSQL (strSql)
        
        '' Commmit Transaction
        OraSession.CommitTrans
        
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
    
    
    Else
        MsgBox "Error:" & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    Exit Sub
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        Me.Name & "." & "UpdateVoyage"
        OraSession.RollBack
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End
        
    End If

End Sub

Private Sub cmdVesList_Click()
    
    frmVesselList.Show
End Sub



Private Sub Form_Activate()

    If Load4FirstTime = True Then
        Me.txtVesNo.SetFocus
        Load4FirstTime = False
    End If
End Sub

Private Sub Form_Load()
    
    '' Initialize variables
    Call iniVariables
    
    '' Retrieve Vessel Numbers/Names and load them into a Collection
    Call RetrieveVessels
    
    '' Get Free Days for ALL Trucked-In Vessels From FREE_TIME table
    Call GetTruckVesselFreeDays
    
    Load4FirstTime = True
    ShipSailed = False
    
    
End Sub

Private Sub lblSailedDate_Change()
    
    If Len(lblSailedDate.Caption) = 10 Then
        Me.lblSailWeekDay = Format(Me.lblSailedDate.Caption, "dddd")
        Me.lblStartFTDate.Caption = calStartFT(Me.lblSailedDate.Caption)
    End If
End Sub

Private Sub lblStartFTDate_Change()

If Len(lblStartFTDate.Caption) = 10 Then
    Me.lblStartFTWeekday.Caption = Format(Me.lblStartFTDate.Caption, "dddd")
End If
End Sub

Private Sub txtVesNo_LostFocus()
    
    
    If Len(frmSetFreeTime.txtVesNo.Text) = 0 Then Exit Sub
    If Len(frmSetFreeTime.txtVesNo.Text) <> 0 Then
        frmSetFreeTime.lblVesselName.Caption = colVesselName.Item(frmSetFreeTime.txtVesNo.Text)
    End If
    
    '' Retrieve Date_Departured and Start_Free_Time
    If (Not IsAutoRun(Me.txtVesNo.Text)) Then
        Call RetrieveStartFT(Int(Val(Me.txtVesNo.Text)))
    End If
    
End Sub

Private Function FindFreeTime(commodity As String, cus_id As String, lr_num As String) As Integer

On Error GoTo Err_Handler

    Dim OraSession As Object
    Dim OraDatabase As Object
    Dim rs As Object
    Dim strSql As String
    Dim retVal As Integer

    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)

    '' Connect to database successfully
    If OraDatabase.LastServerErr = 0 Then

        '' Prepare sql statement- 1st check
        strSql = "select f.FREE_DAYS" & _
                    " from free_time f" & _
                    " where f.COMMODITY_CODE =" & commodity & _
                    " and f.CUSTOMER_ID=" & cus_id & _
                    " and f.LR_NUM=" & lr_num
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)

        If rs.RecordCount = 1 Then
            retVal = Int(Val(rs.Fields(0).Value))
            Set OraSession = Nothing
            Set OraDatabase = Nothing
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If

        '' Prepare sql statement- 2nd check
        strSql = "select f.FREE_DAYS" & _
            " from free_time f" & _
            " where f.COMMODITY_CODE =" & commodity & _
            " and f.CUSTOMER_ID=" & cus_id & _
            " and f.LR_NUM IS NULL"
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        If rs.RecordCount = 1 Then
            retVal = Int(Val(rs.Fields(0).Value))
            Set OraSession = Nothing
            Set OraDatabase = Nothing
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If


        '' Prepare sql statement- 3rd check
        strSql = "select f.FREE_DAYS" & _
                    " from free_time f" & _
                    " where f.COMMODITY_CODE =" & commodity & _
                    " and f.CUSTOMER_ID IS NULL" & _
                    " and f.LR_NUM IS NULL"

        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        If rs.RecordCount = 1 Then
            retVal = Int(Val(rs.Fields(0).Value))
            Set OraSession = Nothing
            Set OraDatabase = Nothing
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If


        '' Set variables to Nothing-Nothing found after 3 checks
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        retVal = -1
        FindFreeTime = retVal
        Exit Function

    Else
        
        '' Fail to connect to database
        MsgBox "Error:" & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End

    End If

Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        Me.Name & "." & "FindFreeTime"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        retVal = -1
        FindFreeTime = retVal
    End If

End Function

Private Sub UpdateCargoTracking(lr_num As Integer)

On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDatabase As Object
    Dim rs As Object
    Dim strSql As String
    Dim strSqlUpdate As String

    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        
        '' Prepare sql statement
        strSql = "select t.LOT_NUM,t.commodity_code,t.owner_id,t.Start_FREE_TIME,t.FREE_DAYS,t.FREE_TIME_END" & _
                " from cargo_tracking t" & _
                " where t.LOT_NUM in" & _
                " (select c.CONTAINER_NUM" & _
                " from cargo_manifest c" & _
                " Where c.lr_num =" & lr_num & ")"
                
        ''line below only for testing
        ''strSql = "SELECT * FROM cargo_ft_02"
                
        '' Create Recordset
         Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        ''rs.Fields(0)-LOT_NUM
        ''rs.Fields(1)-COMMODITY_CODE
        ''rs.Fields(2)-OWNER_ID
        ''rs.Fields(3)-START_FREE_TIME
        ''rs.Fields(4)-FREE_DAYS
        ''rs.Fields(5)-FREE_TIME_END
        rs.MoveFirst
        
        '' Date addition
        Dim sDate As Date
        Dim eDate As Date
        Dim fd As Integer
        Dim strLOTNum As String
        Dim strStartDate As String
        Dim strEndDate As String

        Do While Not rs.EOF
        
            fd = FindFreeTime(Str(rs.Fields(1).Value), Str(rs.Fields(2).Value), Trim(frmSetFreeTime.txtVesNo.Text))
            If fd < 0 Then
                Err.Raise 901 + vbObject, App.Title, "Free Days Not Found in FREE_TIME table"
            End If
            
            sDate = Format(Me.lblStartFTDate.Caption, "mm/dd/yyyy")
            eDate = DateAdd("d", fd, sDate)
            
            ''rs.Edit
            ''rs.Fields(3).Value = sDate
            ''rs.Fields(4).Value = fd
            ''rs.Fields(5).Value = eDate
            ''rs.Update
            strLOTNum = "'" & rs.Fields(0).Value & "'"
            strStartDate = "'" & sDate & "'"
            strEndDate = "'" & eDate & "'"
            
            strSqlUpdate = "UPDATE CARGO_TRACKING c" & _
                            " SET C.START_FREE_TIME=TO_DATE(" & strStartDate & " , 'mm/dd/yyyy')," & _
                            " c.FREE_DAYS=" & Str(fd) & "," & _
                            " c.FREE_TIME_END=TO_DATE(" & strEndDate & ", 'mm/dd/yyyy')" & _
                            " WHERE C.LOT_NUM=" & strLOTNum
            
            OraDatabase.ExecuteSQL (strSqlUpdate)
            
            rs.MoveNext
              
        Loop
        
    
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        
        ''MsgBox "Set Free Time Completed"
    
    Else
        
        MsgBox "Error:" & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End
        
    End If
    
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        Me.Name & "." & "UpdateCargoTracking"
        OraSession.RollBack
        Me.lblDashBoard.Visible = False
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End
        
    End If

End Sub
