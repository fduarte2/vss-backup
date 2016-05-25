VERSION 5.00
Begin VB.Form frm_Database 
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "Select a database"
   ClientHeight    =   3195
   ClientLeft      =   2760
   ClientTop       =   3750
   ClientWidth     =   6030
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   3195
   ScaleWidth      =   6030
   ShowInTaskbar   =   0   'False
   Begin VB.ComboBox cmb_Database 
      Height          =   315
      ItemData        =   "frm_database.frx":0000
      Left            =   1440
      List            =   "frm_database.frx":0007
      TabIndex        =   8
      Text            =   "Combo1"
      Top             =   840
      Width           =   2055
   End
   Begin VB.CommandButton cmd_connect 
      Caption         =   "Connect"
      Height          =   375
      Left            =   4080
      TabIndex        =   7
      Top             =   1590
      Width           =   1575
   End
   Begin VB.CommandButton cmd_disconnect 
      Caption         =   "Disconnect"
      Height          =   375
      Left            =   4080
      TabIndex        =   6
      Top             =   840
      Width           =   1575
   End
   Begin VB.TextBox txt_user 
      Alignment       =   1  'Right Justify
      Height          =   285
      Left            =   1440
      TabIndex        =   2
      Text            =   "user"
      Top             =   1320
      Width           =   2055
   End
   Begin VB.TextBox txt_password 
      Alignment       =   1  'Right Justify
      Height          =   285
      IMEMode         =   3  'DISABLE
      Left            =   1440
      PasswordChar    =   "~"
      TabIndex        =   1
      Text            =   "pass"
      Top             =   1680
      Width           =   2055
   End
   Begin VB.CommandButton OKButton 
      Caption         =   "Close"
      Height          =   375
      Left            =   4080
      TabIndex        =   0
      Top             =   2400
      Width           =   1575
   End
   Begin VB.Label lblDB 
      Alignment       =   1  'Right Justify
      Caption         =   "Database"
      Height          =   255
      Index           =   0
      Left            =   240
      TabIndex        =   5
      Top             =   870
      Width           =   1095
   End
   Begin VB.Label lblDB 
      Alignment       =   1  'Right Justify
      Caption         =   "User:"
      Height          =   255
      Index           =   3
      Left            =   240
      TabIndex        =   4
      Top             =   1350
      Width           =   1095
   End
   Begin VB.Label lblDB 
      Alignment       =   1  'Right Justify
      Caption         =   "Pasword:"
      Height          =   255
      Index           =   4
      Left            =   240
      TabIndex        =   3
      Top             =   1710
      Width           =   1095
   End
End
Attribute VB_Name = "frm_Database"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False

Option Explicit

Private Sub CancelButton_Click()
    Unload Me
End Sub

Private Sub cmd_connect_Click()
 ' Database text fields
    str_DatabaseName = cmb_Database.text
    str_user = txt_user.text
    str_UPass = txt_password
    ' connect
    Call Initialize_DB(True)
        
    If ordb.State = adStateOpen Then
        frm_LocationUpdater.sb_Status.Panels(1).text = "LIVE: " & str_DatabaseName
        frm_LocationUpdater.sb_Status.Panels(3).text = "Connected."
        frm_LocationUpdater.sb_Status.Panels(3).Tag = True
    End If
End Sub

Private Sub cmd_disconnect_Click()
 'Destroy the OraDatabase Object
        If ordb.State = adStateOpen Then ordb.Close
        frm_LocationUpdater.sb_Status.Panels(3).text = "Disconnected."
        frm_LocationUpdater.sb_Status.Panels(3).Tag = False
End Sub



Private Sub Form_Load()
    cmb_Database.Clear
    cmb_Database.AddItem "RF.WORLD"
    cmb_Database.AddItem "RF_NEW.WORLD"
    cmb_Database.text = str_DatabaseName
    txt_user.text = str_user
    txt_password = str_UPass
End Sub

Private Sub OKButton_Click()
    Unload Me
End Sub
