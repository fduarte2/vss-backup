VERSION 5.00
Begin VB.Form frmEntryDetail 
   BackColor       =   &H8000000B&
   Caption         =   "TLS-Entry Detail"
   ClientHeight    =   5205
   ClientLeft      =   5445
   ClientTop       =   5520
   ClientWidth     =   7515
   LinkTopic       =   "Form1"
   ScaleHeight     =   5205
   ScaleWidth      =   7515
   Begin VB.Frame Frame2 
      BackColor       =   &H8000000B&
      Height          =   855
      Left            =   120
      TabIndex        =   29
      Top             =   4080
      Width           =   7095
      Begin VB.CommandButton cmdClose 
         Caption         =   "Close"
         Height          =   495
         Left            =   2640
         TabIndex        =   30
         Top             =   240
         Width           =   1935
      End
   End
   Begin VB.Frame Frame1 
      BackColor       =   &H8000000B&
      Height          =   3855
      Left            =   120
      TabIndex        =   0
      Top             =   120
      Width           =   7095
      Begin VB.Label Label14 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "WHSE"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   3600
         TabIndex        =   28
         Top             =   3240
         Width           =   1095
      End
      Begin VB.Label Label13 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "Seal #"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   3600
         TabIndex        =   27
         Top             =   2760
         Width           =   1095
      End
      Begin VB.Label Label12 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "P.U. #"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   3600
         TabIndex        =   26
         Top             =   2280
         Width           =   1095
      End
      Begin VB.Label Label11 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "Vessel"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   3600
         TabIndex        =   25
         Top             =   1800
         Width           =   1095
      End
      Begin VB.Label Label10 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "Customer"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   3600
         TabIndex        =   24
         Top             =   1320
         Width           =   1095
      End
      Begin VB.Label Label9 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "Commodity"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   3600
         TabIndex        =   23
         Top             =   840
         Width           =   1095
      End
      Begin VB.Label Label8 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "Driver"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   3600
         TabIndex        =   22
         Top             =   360
         Width           =   1095
      End
      Begin VB.Label Label7 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "Trkg Co"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   120
         TabIndex        =   21
         Top             =   3240
         Width           =   1095
      End
      Begin VB.Label Label6 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "Chk'd_Out_By"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   120
         TabIndex        =   20
         Top             =   2760
         Width           =   1095
      End
      Begin VB.Label Label5 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "Chk'd_In_By"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   120
         TabIndex        =   19
         Top             =   2280
         Width           =   1095
      End
      Begin VB.Label Label4 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "Time_Out"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   120
         TabIndex        =   18
         Top             =   1800
         Width           =   1095
      End
      Begin VB.Label Label3 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "Time_In"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   120
         TabIndex        =   17
         Top             =   1320
         Width           =   1095
      End
      Begin VB.Label Label2 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "ID"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   120
         TabIndex        =   16
         Top             =   840
         Width           =   1095
      End
      Begin VB.Label Label1 
         Alignment       =   2  'Center
         Appearance      =   0  'Flat
         BackColor       =   &H8000000B&
         Caption         =   "REC. ID"
         ForeColor       =   &H80000008&
         Height          =   375
         Left            =   120
         TabIndex        =   15
         Top             =   360
         Width           =   1095
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   13
         Left            =   4920
         TabIndex        =   14
         Top             =   3240
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   12
         Left            =   4920
         TabIndex        =   13
         Top             =   2760
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   11
         Left            =   4920
         TabIndex        =   12
         Top             =   2280
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   10
         Left            =   4920
         TabIndex        =   11
         Top             =   1800
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   9
         Left            =   4920
         TabIndex        =   10
         Top             =   1320
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   8
         Left            =   4920
         TabIndex        =   9
         Top             =   840
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   7
         Left            =   4920
         TabIndex        =   8
         Top             =   360
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   6
         Left            =   1440
         TabIndex        =   7
         Top             =   3240
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   5
         Left            =   1440
         TabIndex        =   6
         Top             =   2760
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   4
         Left            =   1440
         TabIndex        =   5
         Top             =   2280
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   3
         Left            =   1440
         TabIndex        =   4
         Top             =   1800
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   2
         Left            =   1440
         TabIndex        =   3
         Top             =   1320
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   1
         Left            =   1440
         TabIndex        =   2
         Top             =   840
         Width           =   1815
      End
      Begin VB.Label lblData 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         ForeColor       =   &H80000008&
         Height          =   375
         Index           =   0
         Left            =   1440
         TabIndex        =   1
         Top             =   360
         Width           =   1815
      End
   End
End
Attribute VB_Name = "frmEntryDetail"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit       'Rudy

Private Sub cmdClose_Click()
    Unload Me
    
End Sub

