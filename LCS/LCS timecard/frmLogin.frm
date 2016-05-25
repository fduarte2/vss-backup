VERSION 5.00
Begin VB.Form frmLogin 
   Caption         =   "Time Card Login Window"
   ClientHeight    =   5160
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   6555
   LinkTopic       =   "Form1"
   ScaleHeight     =   5160
   ScaleWidth      =   6555
   StartUpPosition =   2  'CenterScreen
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
      Height          =   495
      Left            =   4200
      TabIndex        =   8
      Top             =   4320
      Width           =   1815
   End
   Begin VB.CommandButton cmdLogin 
      Caption         =   "Log in"
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
      Left            =   4200
      TabIndex        =   7
      Top             =   3480
      Width           =   1815
   End
   Begin VB.TextBox txtPWD 
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
      IMEMode         =   3  'DISABLE
      Left            =   3960
      TabIndex        =   6
      Top             =   2400
      Width           =   2295
   End
   Begin VB.ListBox lstUsers 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   4380
      Left            =   120
      TabIndex        =   0
      Top             =   480
      Width           =   3735
   End
   Begin VB.Label Label4 
      AutoSize        =   -1  'True
      Caption         =   "Double click on a name to start loggin in"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00FF0000&
      Height          =   240
      Left            =   120
      TabIndex        =   9
      Top             =   120
      Width           =   4155
   End
   Begin VB.Label Label3 
      AutoSize        =   -1  'True
      Caption         =   "PASSWORD"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   240
      Left            =   3960
      TabIndex        =   5
      Top             =   2160
      Width           =   1320
   End
   Begin VB.Label lblName 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
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
      Height          =   495
      Left            =   3960
      TabIndex        =   4
      Top             =   1560
      Width           =   2295
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      Caption         =   "NAME"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   240
      Left            =   3960
      TabIndex        =   3
      Top             =   1320
      Width           =   660
   End
   Begin VB.Label lblUserID 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
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
      Height          =   495
      Left            =   3960
      TabIndex        =   2
      Top             =   720
      Width           =   2295
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      Caption         =   "USER ID"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   240
      Left            =   3960
      TabIndex        =   1
      Top             =   480
      Width           =   930
   End
End
Attribute VB_Name = "frmLogin"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit                 '2853 3/29/07 Rudy:

Sub RetrieveUserInfo()

On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDatabase As Object
    Dim rs As Object
    Dim strSql As String
    
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        
        '' Prepare sql statement
        strSql = "Select USER_ID, USER_NAME, USER_PASSWORD, TIMECARDUSER" & _
                    " From LCS_USER" & _
                    " where status = 'A' and TIMECARDUSER in ('Y', 'A')" & _
                    " Order by User_Name"
                
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        
        If rs.recordcount = 0 Then
            MsgBox "No user inforamtion found"
            Set OraSession = Nothing
            Set OraDatabase = Nothing
            Set rs = Nothing
            End
        End If
        
        ''rs.Fields(0).Value=USER_ID
        ''rs.Fields(1).Value=USER_NAME
        ''rs.Fields(2).Value=USER_PASSWORD
        ''rs.Fields(3).Value=TIMECARDUSER
        
        Set colUserID = Nothing
        Set colUserName = Nothing
        Set colUserPWD = Nothing
        Set colTimeCardUser = Nothing
        
        '' Load values into colUserID, colUserName, colUserPWD
        rs.MoveFirst
        
        Do While Not rs.EOF
            colUserID.Add rs.fields(0).Value
            colUserName.Add rs.fields(1).Value, rs.fields(0).Value
            colUserPWD.Add rs.fields(2).Value, rs.fields(0).Value
            colTimeCardUser.Add rs.fields(3).Value, rs.fields(0).Value
            Me.lstUsers.AddItem rs.fields(0).Value & "-" & rs.fields(1).Value
            rs.MoveNext
        Loop
    
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
        Me.Name & "." & "RetrieveUserInfo"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End
    End If

End Sub

Private Sub cmdExit_Click()
    
    '' End the program
    Unload Me     '2853pt2 4/11/2007 Rudy: better
    End
    
End Sub

Private Sub cmdLogin_Click()
  '2853pt2 4/11/2007 Rudy: if no pwd it errors
  If Len(Trim(txtPWD.Text)) = 0 Then
    MsgBox "Please enter a password"
    Exit Sub
  End If
  
  If (Me.txtPWD.Text = upwd) Then
    frmView.Show
    Unload frmLogin
  Else
    MsgBox "Login Failed"
    Exit Sub
  End If
End Sub

Private Sub Form_Load()

    '' set values for connection variables
    Call iniConnection
    
    '' retrieve user information from LCS.LCS_USER
    Call RetrieveUserInfo
    
    Label4.Caption = "Double click a name to start logging in"
End Sub

Private Sub lstUsers_DblClick()

    Dim i As Integer

    '' Based on the index of slected item
    '' Retrieve values from three collections
    i = Me.lstUsers.ListIndex
    uid = colUserID(i + 1)
    uname = colUserName(uid)
    upwd = colUserPWD(i + 1)
    
    ''MsgBox "ID:" & uid & "PWD:" & upwd & "TIMECARD:" & colTimeCardUser.Item(uid)
    
    '' Display UserID and UserName
    Me.lblUserID.Caption = uid
    Me.lblName.Caption = uname
    
    '' Set Focus to password textbox
    Me.txtPWD.SetFocus
    
End Sub

Private Sub txtPWD_Change()
    txtPWD.PasswordChar = "*"
End Sub
