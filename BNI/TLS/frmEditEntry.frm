VERSION 5.00
Begin VB.Form frmEditEntry 
   Caption         =   "TLS-Edit Entry"
   ClientHeight    =   6165
   ClientLeft      =   6285
   ClientTop       =   4665
   ClientWidth     =   6810
   LinkTopic       =   "Form1"
   ScaleHeight     =   6165
   ScaleWidth      =   6810
   Begin VB.Frame Frame2 
      Height          =   975
      Left            =   120
      TabIndex        =   16
      Top             =   4560
      Width           =   6015
      Begin VB.CommandButton cmdSaveChanges 
         Caption         =   "Save Changes"
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
         Left            =   960
         TabIndex        =   25
         Top             =   360
         Width           =   2055
      End
      Begin VB.CommandButton cmdClose 
         Caption         =   "Close"
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
         Left            =   3600
         TabIndex        =   17
         Top             =   360
         Width           =   1215
      End
   End
   Begin VB.Frame Frame1 
      Height          =   4335
      Left            =   120
      TabIndex        =   7
      Top             =   120
      Width           =   6015
      Begin VB.TextBox txtComment 
         Height          =   375
         Left            =   4200
         MaxLength       =   100
         TabIndex        =   35
         Text            =   "Comment"
         Top             =   240
         Width           =   1335
      End
      Begin VB.TextBox txtSeal 
         Height          =   375
         Left            =   4200
         TabIndex        =   31
         Text            =   "seal"
         Top             =   2640
         Width           =   1335
      End
      Begin VB.TextBox txtWHSE 
         Height          =   375
         Left            =   4200
         TabIndex        =   6
         Text            =   "WHSE"
         Top             =   3120
         Width           =   1335
      End
      Begin VB.TextBox txtBOL 
         Height          =   375
         Left            =   4200
         TabIndex        =   5
         Text            =   "BOL"
         Top             =   2160
         Width           =   1335
      End
      Begin VB.TextBox txtVsl 
         Height          =   375
         Left            =   4200
         TabIndex        =   4
         Text            =   "VSL"
         Top             =   1680
         Width           =   1335
      End
      Begin VB.TextBox txtCust 
         Height          =   375
         Left            =   4200
         TabIndex        =   3
         Text            =   "CUST"
         Top             =   1200
         Width           =   1335
      End
      Begin VB.TextBox txtCOMM 
         Height          =   375
         Left            =   4200
         TabIndex        =   2
         Text            =   "COMM"
         Top             =   720
         Width           =   1335
      End
      Begin VB.TextBox txtDriver 
         Height          =   405
         Left            =   1320
         TabIndex        =   1
         Text            =   "driver"
         Top             =   3600
         Width           =   1695
      End
      Begin VB.TextBox txtTrkgComp 
         Height          =   375
         Left            =   1320
         TabIndex        =   0
         Text            =   "trkg "
         Top             =   3120
         Width           =   1695
      End
      Begin VB.Label Label2 
         Alignment       =   2  'Center
         Caption         =   "COMMENT"
         Height          =   255
         Left            =   3240
         TabIndex        =   34
         Top             =   240
         Width           =   855
      End
      Begin VB.Label lblStatus 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H00000000&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "Checked Out"
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
         Left            =   1320
         TabIndex        =   33
         Top             =   240
         Width           =   1815
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         Caption         =   "Status:"
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
         Left            =   480
         TabIndex        =   32
         Top             =   240
         Width           =   615
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "Seal #"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   13
         Left            =   3120
         TabIndex        =   30
         Top             =   2640
         Width           =   975
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H00C0C0C0&
         Caption         =   "checked out by"
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
         Index           =   5
         Left            =   1320
         TabIndex        =   29
         Top             =   2640
         Width           =   1695
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H00C0C0C0&
         Caption         =   "checked in by"
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
         Index           =   4
         Left            =   1320
         TabIndex        =   28
         Top             =   2160
         Width           =   1695
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "Chk'd Out By"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   12
         Left            =   120
         TabIndex        =   27
         Top             =   2640
         Width           =   1095
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "Time Out"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   11
         Left            =   120
         TabIndex        =   26
         Top             =   1680
         Width           =   1095
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "WHSE"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   10
         Left            =   3120
         TabIndex        =   24
         Top             =   3120
         Width           =   975
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "P U #"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   9
         Left            =   3120
         TabIndex        =   23
         Top             =   2160
         Width           =   975
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "VSL."
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   8
         Left            =   3120
         TabIndex        =   22
         Top             =   1680
         Width           =   975
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "CUST."
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   7
         Left            =   3120
         TabIndex        =   21
         Top             =   1200
         Width           =   975
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "COMM."
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   6
         Left            =   3240
         TabIndex        =   20
         Top             =   720
         Width           =   855
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "Driver"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   5
         Left            =   120
         TabIndex        =   19
         Top             =   3600
         Width           =   1095
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "Trkg Comp"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   4
         Left            =   120
         TabIndex        =   18
         Top             =   3120
         Width           =   1095
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H00C0C0C0&
         Caption         =   "time out"
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
         Index           =   3
         Left            =   1320
         TabIndex        =   15
         Top             =   1680
         Width           =   1695
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H00C0C0C0&
         Caption         =   "time in"
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
         Index           =   2
         Left            =   1320
         TabIndex        =   14
         Top             =   1200
         Width           =   1695
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H00C0C0C0&
         Caption         =   "id"
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
         Index           =   1
         Left            =   1320
         TabIndex        =   13
         Top             =   720
         Width           =   1695
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         Caption         =   "rec id"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   0
         Left            =   4680
         TabIndex        =   12
         Top             =   3600
         Visible         =   0   'False
         Width           =   615
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "Chk'd In By"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   3
         Left            =   120
         TabIndex        =   11
         Top             =   2160
         Width           =   1095
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "Time In"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   2
         Left            =   120
         TabIndex        =   10
         Top             =   1200
         Width           =   1095
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         Caption         =   "ID"
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   1
         Left            =   120
         TabIndex        =   9
         Top             =   720
         Width           =   1095
      End
      Begin VB.Label lblLabel 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         BorderStyle     =   1  'Fixed Single
         Caption         =   "Rec. ID"
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
         Height          =   255
         Index           =   0
         Left            =   3480
         TabIndex        =   8
         Top             =   3720
         Visible         =   0   'False
         Width           =   975
      End
   End
End
Attribute VB_Name = "frmEditEntry"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit       'Rudy

Private Sub cmdClose_Click()
    Unload Me
End Sub

Private Sub cmdSaveChanges_Click()
    Call SaveEntryChanges
    Call RefreshGrid
    Unload Me
End Sub
