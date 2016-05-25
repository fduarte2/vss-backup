VERSION 5.00
Begin VB.Form frmVeslEnt 
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Vessel Entry"
   ClientHeight    =   9765
   ClientLeft      =   3315
   ClientTop       =   1605
   ClientWidth     =   9900
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   ScaleHeight     =   9765
   ScaleWidth      =   9900
   Begin VB.ComboBox cboBerthnum 
      Height          =   315
      Left            =   4680
      Sorted          =   -1  'True
      Style           =   2  'Dropdown List
      TabIndex        =   89
      Top             =   2280
      Width           =   1455
   End
   Begin VB.CommandButton cmdTrkLodBill 
      Caption         =   "&Advance Billing"
      Height          =   315
      Left            =   3048
      TabIndex        =   38
      Top             =   9120
      Width           =   1305
   End
   Begin VB.CommandButton cmdClose 
      Caption         =   "&Close"
      Height          =   315
      Left            =   7260
      TabIndex        =   41
      Top             =   9120
      Width           =   1305
   End
   Begin VB.TextBox txtFreetime 
      Height          =   315
      Left            =   7440
      TabIndex        =   6
      Top             =   2280
      Width           =   1215
   End
   Begin VB.TextBox txtTugsout 
      BackColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   480
      MaxLength       =   20
      TabIndex        =   32
      Top             =   8160
      Width           =   3975
   End
   Begin VB.TextBox txtTugsIn 
      BackColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   4560
      MaxLength       =   20
      TabIndex        =   31
      Top             =   7560
      Width           =   3975
   End
   Begin VB.TextBox txtTimeArrived 
      Height          =   315
      Left            =   2880
      MaxLength       =   8
      TabIndex        =   10
      Top             =   3720
      Width           =   1035
   End
   Begin VB.TextBox txtOriginated 
      Height          =   315
      Left            =   480
      MaxLength       =   20
      TabIndex        =   28
      Top             =   6960
      Width           =   3975
   End
   Begin VB.TextBox txtPilotOut 
      Height          =   315
      Left            =   480
      MaxLength       =   20
      TabIndex        =   34
      Top             =   8760
      Width           =   3975
   End
   Begin VB.TextBox txtReportedTo 
      Height          =   315
      Left            =   4560
      MaxLength       =   20
      TabIndex        =   35
      Top             =   8760
      Width           =   3975
   End
   Begin VB.TextBox txtPilotIn 
      Height          =   315
      Left            =   4560
      MaxLength       =   20
      TabIndex        =   33
      Top             =   8160
      Width           =   3975
   End
   Begin VB.TextBox txtDestPort 
      Height          =   315
      Left            =   4560
      MaxLength       =   20
      TabIndex        =   27
      Top             =   6360
      Width           =   3975
   End
   Begin VB.TextBox txtHomeport 
      Height          =   315
      Left            =   480
      MaxLength       =   20
      TabIndex        =   26
      Top             =   6360
      Width           =   3975
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&Print"
      Height          =   315
      Left            =   1644
      TabIndex        =   37
      Top             =   9120
      Width           =   1305
   End
   Begin VB.CommandButton cmdManifest 
      Caption         =   "&Manifest"
      Height          =   315
      Left            =   4452
      TabIndex        =   39
      Top             =   9120
      Width           =   1305
   End
   Begin VB.CommandButton cmdBilling 
      Caption         =   "&Billing"
      Height          =   315
      Left            =   5856
      TabIndex        =   40
      Top             =   9120
      Width           =   1305
   End
   Begin VB.Frame fraWorkingTime 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Working Time"
      Height          =   1935
      Left            =   4680
      TabIndex        =   71
      Top             =   2640
      Width           =   3555
      Begin VB.TextBox txtIdle 
         Height          =   315
         Left            =   960
         TabIndex        =   18
         Top             =   1560
         Width           =   1095
      End
      Begin VB.TextBox txtDays 
         Height          =   315
         Left            =   960
         TabIndex        =   17
         Top             =   1200
         Width           =   1095
      End
      Begin VB.TextBox txtEndTime 
         Height          =   315
         Left            =   2160
         TabIndex        =   16
         Top             =   840
         Width           =   1095
      End
      Begin VB.TextBox txtEndDate 
         Height          =   315
         Left            =   960
         TabIndex        =   15
         Top             =   840
         Width           =   1095
      End
      Begin VB.TextBox txtStartTime 
         Height          =   315
         Left            =   2160
         TabIndex        =   14
         Top             =   480
         Width           =   1095
      End
      Begin VB.TextBox txtStartDate 
         Height          =   315
         Left            =   960
         TabIndex        =   13
         Top             =   480
         Width           =   1095
      End
      Begin VB.Label lblTime 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Time(HH:MM)"
         Height          =   255
         Left            =   2160
         TabIndex        =   81
         Top             =   240
         Width           =   1095
      End
      Begin VB.Label lblDate 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Date(MM/DD/YYYY)"
         Height          =   255
         Left            =   600
         TabIndex        =   80
         Top             =   240
         Width           =   1575
      End
      Begin VB.Label lblIdle 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Idle"
         Height          =   255
         Left            =   120
         TabIndex        =   75
         Top             =   1560
         Width           =   375
      End
      Begin VB.Label lblDays 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Days"
         Height          =   255
         Left            =   120
         TabIndex        =   74
         Top             =   1200
         Width           =   375
      End
      Begin VB.Label lblEndTime 
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         Caption         =   "End Time"
         Height          =   195
         Left            =   120
         TabIndex        =   73
         Top             =   840
         Width           =   675
      End
      Begin VB.Label lblStartTime 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Start Time"
         Height          =   255
         Left            =   120
         TabIndex        =   72
         Top             =   480
         Width           =   735
      End
   End
   Begin VB.TextBox txtNextPort 
      BackColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   480
      MaxLength       =   20
      TabIndex        =   30
      Top             =   7560
      Width           =   3975
   End
   Begin VB.TextBox txtLastPort 
      BackColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   4560
      MaxLength       =   20
      TabIndex        =   29
      Top             =   6960
      Width           =   3975
   End
   Begin VB.ComboBox cboShippingLine 
      Height          =   315
      ItemData        =   "VeslEnt.frx":0000
      Left            =   2040
      List            =   "VeslEnt.frx":0002
      Sorted          =   -1  'True
      Style           =   2  'Dropdown List
      TabIndex        =   2
      Top             =   960
      Width           =   5625
   End
   Begin VB.Frame fraDraft 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Draft"
      Height          =   1545
      Left            =   4680
      TabIndex        =   53
      Top             =   4560
      Width           =   3555
      Begin VB.TextBox txtDraftOutAft 
         Height          =   315
         Left            =   2040
         MaxLength       =   5
         TabIndex        =   25
         Top             =   1050
         Width           =   885
      End
      Begin VB.TextBox txtDraftInAft 
         Height          =   315
         Left            =   2040
         MaxLength       =   5
         TabIndex        =   23
         Top             =   540
         Width           =   885
      End
      Begin VB.TextBox txtDraftOut 
         Height          =   315
         Left            =   780
         MaxLength       =   5
         TabIndex        =   24
         Top             =   1080
         Width           =   885
      End
      Begin VB.TextBox txtDraftIn 
         Height          =   315
         Left            =   780
         MaxLength       =   5
         TabIndex        =   22
         Top             =   570
         Width           =   885
      End
      Begin VB.Label lblAft 
         Alignment       =   2  'Center
         BackColor       =   &H00FFFFC0&
         Caption         =   "Aft"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   2040
         TabIndex        =   55
         Top             =   270
         Width           =   885
      End
      Begin VB.Label lblDraftMOutAft 
         BackColor       =   &H00FFFFC0&
         Caption         =   "m"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   2940
         TabIndex        =   61
         Top             =   1080
         Width           =   165
      End
      Begin VB.Label lblDraftMInAft 
         BackColor       =   &H00FFFFC0&
         Caption         =   "m"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   2940
         TabIndex        =   58
         Top             =   570
         Width           =   165
      End
      Begin VB.Label lblDraftMInFwd 
         BackColor       =   &H00FFFFC0&
         Caption         =   "m"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   1680
         TabIndex        =   57
         Top             =   600
         Width           =   165
      End
      Begin VB.Label lblDraftMOutFwd 
         BackColor       =   &H00FFFFC0&
         Caption         =   "m"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   1680
         TabIndex        =   60
         Top             =   1110
         Width           =   165
      End
      Begin VB.Label lblFwd 
         Alignment       =   2  'Center
         BackColor       =   &H00FFFFC0&
         Caption         =   "Fwd"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   780
         TabIndex        =   54
         Top             =   270
         Width           =   885
      End
      Begin VB.Label lblDraftOut 
         Alignment       =   1  'Right Justify
         BackColor       =   &H00FFFFC0&
         Caption         =   "Out"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   300
         TabIndex        =   59
         Top             =   1110
         Width           =   405
      End
      Begin VB.Label lblDraftIn 
         Alignment       =   1  'Right Justify
         BackColor       =   &H00FFFFC0&
         Caption         =   "In"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   300
         TabIndex        =   56
         Top             =   600
         Width           =   405
      End
   End
   Begin VB.Frame fraDateTime 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Date/Time"
      Height          =   1905
      Left            =   720
      TabIndex        =   49
      Top             =   2640
      Width           =   3345
      Begin VB.TextBox txtTimeDeparted 
         Height          =   315
         Left            =   2160
         MaxLength       =   8
         TabIndex        =   12
         Top             =   1440
         Width           =   1035
      End
      Begin VB.TextBox txtDateDeparted 
         Height          =   315
         Left            =   960
         MaxLength       =   10
         TabIndex        =   11
         Top             =   1440
         Width           =   1035
      End
      Begin VB.TextBox txtDateArrived 
         Height          =   315
         Left            =   960
         MaxLength       =   10
         TabIndex        =   9
         Top             =   1080
         Width           =   1035
      End
      Begin VB.TextBox txtTimeExpected 
         Height          =   315
         Left            =   2160
         MaxLength       =   8
         TabIndex        =   8
         Top             =   720
         Width           =   1035
      End
      Begin VB.TextBox txtDateExpected 
         Height          =   315
         Left            =   960
         MaxLength       =   10
         TabIndex        =   7
         Top             =   720
         Width           =   1035
      End
      Begin VB.Label lblETime 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Time(HH:MM)"
         Height          =   255
         Left            =   2160
         TabIndex        =   85
         Top             =   360
         Width           =   1095
      End
      Begin VB.Label lblDateTime 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Date(MM/DD/YYYY)"
         Height          =   255
         Left            =   480
         TabIndex        =   84
         Top             =   360
         Width           =   1575
      End
      Begin VB.Label lblDateDeparted 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Sailed"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   150
         TabIndex        =   52
         Top             =   1500
         Width           =   795
      End
      Begin VB.Label lblDateArrived 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Arrived"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   150
         TabIndex        =   51
         Top             =   1140
         Width           =   795
      End
      Begin VB.Label lblDateExpected 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Expected"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   150
         TabIndex        =   50
         Top             =   780
         Width           =   795
      End
   End
   Begin VB.ComboBox cboVesselOperatorId 
      Height          =   315
      ItemData        =   "VeslEnt.frx":0004
      Left            =   2040
      List            =   "VeslEnt.frx":0006
      Sorted          =   -1  'True
      Style           =   2  'Dropdown List
      TabIndex        =   3
      Top             =   1380
      Width           =   5625
   End
   Begin VB.ComboBox cboStevedoreId 
      Height          =   315
      ItemData        =   "VeslEnt.frx":0008
      Left            =   2040
      List            =   "VeslEnt.frx":000A
      Sorted          =   -1  'True
      Style           =   2  'Dropdown List
      TabIndex        =   4
      Top             =   1830
      Width           =   5625
   End
   Begin VB.TextBox txtCaptain 
      BackColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   2040
      MaxLength       =   20
      TabIndex        =   5
      Top             =   2280
      Width           =   2115
   End
   Begin VB.Frame fraBilling 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Billing"
      Height          =   1545
      Left            =   720
      TabIndex        =   42
      Top             =   4560
      Width           =   3345
      Begin VB.ComboBox cboVesselBilling 
         Height          =   315
         ItemData        =   "VeslEnt.frx":000C
         Left            =   780
         List            =   "VeslEnt.frx":0016
         Style           =   2  'Dropdown List
         TabIndex        =   19
         Top             =   240
         Width           =   1095
      End
      Begin VB.TextBox txtVesselLength 
         Height          =   315
         Left            =   780
         MaxLength       =   7
         TabIndex        =   21
         Top             =   1020
         Width           =   1095
      End
      Begin VB.TextBox txtVesselNRT 
         Height          =   315
         Left            =   780
         MaxLength       =   12
         TabIndex        =   20
         Top             =   630
         Width           =   1095
      End
      Begin VB.Label lblVesselBilling 
         Alignment       =   1  'Right Justify
         BackColor       =   &H00FFFFC0&
         Caption         =   "Basis"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   120
         TabIndex        =   43
         Top             =   300
         Width           =   585
      End
      Begin VB.Label lblVesselLengthM 
         BackColor       =   &H00FFFFC0&
         Caption         =   "ft"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   1920
         TabIndex        =   47
         Top             =   1080
         Width           =   165
      End
      Begin VB.Label lblVesselNRTTons 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Tons"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   1920
         TabIndex        =   45
         Top             =   690
         Width           =   375
      End
      Begin VB.Label lblVesselLength 
         Alignment       =   1  'Right Justify
         BackColor       =   &H00FFFFC0&
         Caption         =   "Length"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   120
         TabIndex        =   46
         Top             =   1080
         Width           =   585
      End
      Begin VB.Label lblVesselNRT 
         Alignment       =   1  'Right Justify
         BackColor       =   &H00FFFFC0&
         Caption         =   "NRT"
         ForeColor       =   &H00000000&
         Height          =   225
         Left            =   120
         TabIndex        =   44
         Top             =   690
         Width           =   585
      End
   End
   Begin VB.TextBox txtLRNum 
      Height          =   315
      Left            =   2040
      MaxLength       =   7
      TabIndex        =   0
      Top             =   120
      Width           =   1155
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
      Height          =   315
      Left            =   240
      TabIndex        =   36
      Top             =   9120
      Width           =   1305
   End
   Begin VB.TextBox txtVesselName 
      BackColor       =   &H00FFFFFF&
      Height          =   315
      Left            =   2040
      MaxLength       =   40
      TabIndex        =   1
      Top             =   540
      Width           =   4335
   End
   Begin VB.Label Label2 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Free Time Start:"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   6240
      TabIndex        =   88
      Top             =   2280
      Width           =   1155
   End
   Begin VB.Label lblTugsout 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Tugs Out"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   480
      TabIndex        =   87
      Top             =   7920
      Width           =   675
   End
   Begin VB.Label lblTugsIn 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Tugs In"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4560
      TabIndex        =   86
      Top             =   7320
      Width           =   645
   End
   Begin VB.Label lblOrginated 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Originated"
      Height          =   255
      Left            =   480
      TabIndex        =   83
      Top             =   6720
      Width           =   855
   End
   Begin VB.Label lblPilotOut 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Pilots out"
      Height          =   255
      Left            =   480
      TabIndex        =   82
      Top             =   8520
      Width           =   855
   End
   Begin VB.Label lblReported 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Reported By"
      Height          =   255
      Left            =   4560
      TabIndex        =   79
      Top             =   8520
      Width           =   975
   End
   Begin VB.Label lblPilot 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Pilots In"
      Height          =   255
      Left            =   4560
      TabIndex        =   78
      Top             =   7920
      Width           =   855
   End
   Begin VB.Label Label1 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Destination Port"
      Height          =   255
      Left            =   4560
      TabIndex        =   77
      Top             =   6120
      Width           =   1335
   End
   Begin VB.Label lblHomePort 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Home Port"
      Height          =   255
      Left            =   480
      TabIndex        =   76
      Top             =   6120
      Width           =   975
   End
   Begin VB.Label lblLRNum 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Ship Nbr"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   0
      TabIndex        =   70
      Top             =   210
      Width           =   1755
   End
   Begin VB.Label lblVesselName 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Vessel Name"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   0
      TabIndex        =   69
      Top             =   600
      Width           =   1755
   End
   Begin VB.Label lblCaptain 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Captain"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   600
      TabIndex        =   68
      Top             =   2280
      Width           =   1125
   End
   Begin VB.Label lblVesselOperatorId 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Agent"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   630
      TabIndex        =   67
      Top             =   1410
      Width           =   1125
   End
   Begin VB.Label lblStevedoreId 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Stevedore"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   630
      TabIndex        =   66
      Top             =   1890
      Width           =   1125
   End
   Begin VB.Label lblShippingLine 
      Alignment       =   1  'Right Justify
      BackColor       =   &H00FFFFC0&
      Caption         =   "Shipping Line"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   630
      TabIndex        =   65
      Top             =   960
      Width           =   1125
   End
   Begin VB.Label lblPortOfDestination 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Next Port"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   480
      TabIndex        =   63
      Top             =   7320
      Width           =   765
   End
   Begin VB.Label lblPortOfOrigin 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Last Port"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4560
      TabIndex        =   62
      Top             =   6720
      Width           =   645
   End
   Begin VB.Label cv 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Berth"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   4200
      TabIndex        =   48
      Top             =   2280
      Width           =   435
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
      Height          =   285
      Left            =   0
      TabIndex        =   64
      Top             =   9480
      Width           =   8790
   End
End
Attribute VB_Name = "frmVeslEnt"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim iRec As Integer
Private iTimediff As Integer
Private bVesselfound As Boolean

Private Sub cmdBilling_Click()

   frmVeslBill.txtLRNum = Trim("" & txtLRNum)
   frmVeslBill.Show
   frmVeslEnt.Hide
   
End Sub
Private Sub cmdclose_Click()

    End
    
End Sub
Private Sub cmdManifest_Click()

  frmVeslEnt.txtLRNum = Trim("" & txtLRNum)
  frmVeslEnt.Hide
  frmCManData.Show
  
End Sub
Private Sub cmdPrint_Click()

Dim sVesselName As String
Dim SOwner As String
Dim sOwnerStreet As String
Dim sOwnercity As String
Dim sAgent As String
Dim sAgentStreet As String
Dim sAgentCity As String
Dim sMaster As String
Dim iLength As Integer
Dim sTonnage As String
Dim sReportedBy As String
Dim sCommenced As String
Dim iBerth As Integer
Dim sArrivedDate As String
Dim sArrivedTime
Dim sDeptdate As String
Dim sDeptTime As String
Dim iDaysatDock As Integer
Dim idays As Integer
Dim iIdle As Integer
Dim sDraftinFWd As String
Dim sDraftinFWdAft As String
Dim sDraftoutFWd As String
Dim sDraftoutFWdAft As String
Dim sTugsIn As String
Dim sTugsOut As String
Dim sPilotsIn As String
Dim sPilotsOut As String
Dim sHomePort As String
Dim sLastPort As String
Dim sNextPort As String
Dim sFinished As String
Dim iNoOfLines As Integer
Dim ict As Integer
Dim sCustNo As String
Dim sAgentNo As String
Dim dsAGENT_PROFILE As Object
Dim dsCARGO_PROFILE As Object
Dim CRPosition1  As Integer
Dim CRPosition2  As Integer
Dim iNameLenth As Integer
Dim sOwnerstate As String
Dim sOwnerZip As String
Dim sAgentstate As String
Dim sAgentZip As String
Dim sOwnCountryCode As String
Dim sAgentCountryCode As String
Dim sVesselNo As String

'Cargo Variable
Dim sLoadCommodity, sDiscCommodity As String
Dim sLoadTons, sDiscTons As String
Dim SLoadFrom, sInto As String
Dim sDestined, sOrigniated As String
Dim sCargoTransinCom As String
Dim sCargoTransitTons, sDate As String
Dim iNoOFCargo As Integer
Dim i, j, k As Integer
Dim iLenth, iFirstcol As Integer
Dim lWeight As Double
Dim lPlts As Double
Dim lCtn As Double
Dim lTotPlts As Double
Dim sTotalPlts As String
Dim lTotctn As Double
Dim sTotalctn As String
Dim lNetTons As Double
Dim lTOTNetTons As Double
Dim lTotWeight As Double
Dim sTotalWeight As String
Dim sCommodity(50) As String
Dim iMaxRecord As Integer
Dim dsComCode As Object
Dim iNoOfComRec As Integer
Dim sComNameID As Integer
Dim sCommodityName As String
Dim sCustCode As Integer
Dim dsCom_PROFILE As Object
Dim dsVOYAGE_PROFILE As Object
Dim sCargoStatus As String
Dim ipos1 As Integer
Dim ipos2 As Integer
Dim iSecondcol As Integer
Dim iFifthcol As Integer
Dim iForthcol As Integer
Dim iOwnerlength As Integer
Dim iPilotLenth As Integer
Dim sCommodityCode As String
Dim dsCARGOMAN As Object
Dim iNoOfComd As Integer
Dim bExpOver, bImpOver As Boolean
Dim lImpWeight As Double
Dim lImpNetTons As Double
Dim sICommodityCode As String
Dim iNoOfIComd As Integer
Dim lTOTNetImpTons As Double
Dim lTotimpWeight As Double
Dim lEmpWeight As Double
Dim lEmpNetTons As Double
Dim lTotEmpWeight As Double
Dim lTOTNetEmpTons  As Double
Dim sUnit1, sUnit2, sWtUnit As String
Dim iOldCustId As Integer
Dim lTOTALNetImpTons, lTOTALNetEmpTons As String
Dim sForWeight, sForPlts, sForCtn, sTotWeight As String
Dim iOldICmdityCode, iOldECmdityCode As Integer
Dim iLineCount As Integer
Dim bJuice, bNewcustomer, bFirstTime As Boolean
Dim bImpFirstTime, bEXpFirstTime As Boolean

gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Trim$(txtLRNum.Text)
Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'Check vessel Exist or Not
If dsVESSEL_PROFILE.RecordCount = 0 Then
   MsgBox "Ship does not Exist, Please Enter All the Information And Save the Record."
   txtLRNum.SetFocus
   Exit Sub
End If

Printer.FontBold = True
Printer.FontName = "COURIER NEW"
Printer.FontSize = 12
sVesselNo = txtLRNum.Text
For ict = 0 To 4
    Printer.Print ""
Next ict

'Print the Heading
Printer.Print Tab(30); "DOCKAGE AND WHARFAGE REPORT"
Printer.FontSize = 8
Printer.Print Tab(57); "PORT OF WILMINGTON"
Printer.FontSize = 11
Printer.Print Tab(38); "WILMINGTON, DELAWARE"
Printer.FontSize = 10
Printer.FontBold = True
Printer.Print Tab(50); sVesselNo
Printer.Print Tab(49); "VESSEL"
Printer.FontBold = False
Printer.Print ""
Printer.Print ""
If Trim$(txtLRNum) <> "" And IsNumeric(txtLRNum) Then
   If Not IsNull(dsVESSEL_PROFILE.fields("VESSEL_NAME")) Then
      CRPosition1 = 1
      CRPosition2 = InStr(CRPosition1, dsVESSEL_PROFILE.fields("VESSEL_NAME").Value, "-")
      iNameLenth = CRPosition2 + 1
      sVesselName = Mid$(dsVESSEL_PROFILE.fields("VESSEL_NAME").Value, iNameLenth)
   Else
      sVesselName = ""
   End If
   
   gsSqlStmt = "SELECT * FROM FLEET WHERE LR_NUM = " & txtLRNum.Text
   Set dsFLEET = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
   If Not IsNull(dsFLEET.fields("CUSTOMER_ID")) Then
      sCustNo = dsFLEET.fields("CUSTOMER_ID").Value
   Else
      sCustNo = ""
   End If
   
   gsSqlStmt = "SELECT * FROM VOYAGE WHERE LR_NUM = " & txtLRNum.Text
   Set dsVOYAGE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
   If Not IsNull(dsVOYAGE.fields("VESSEL_OPERATOR_ID")) Then
      sAgentNo = dsVOYAGE.fields("VESSEL_OPERATOR_ID").Value
   Else
      sAgentNo = ""
   End If
   
   If sCustNo <> "" Then
      gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID=" & sCustNo
      Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
      If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME")) Then
         CRPosition1 = 1
         CRPosition2 = InStr(CRPosition1, dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, "-")
         iNameLenth = CRPosition2 + 1
         SOwner = Mid$(dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, iNameLenth)
      Else
         SOwner = ""
      End If
      If IsNull(dsCUSTOMER_PROFILE.fields("COUNTRY_CODE")) Then
         sOwnCountryCode = ""
      Else
         sOwnCountryCode = dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value
      End If
      If IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_CITY")) Then
         sOwnercity = ""
      Else
         sOwnercity = dsCUSTOMER_PROFILE.fields("CUSTOMER_CITY").Value
         sOwnercity = sOwnercity & "," & sOwnCountryCode
      End If
      If IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1")) Then
         sOwnerStreet = ""
      Else
         sOwnerStreet = dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value
      End If
      If IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_STATE")) Then
         sOwnerstate = ""
      Else
         sOwnerstate = dsCUSTOMER_PROFILE.fields("CUSTOMER_STATE").Value
      End If
      If IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ZIP")) Then
         sOwnerZip = ""
      Else
         sOwnerZip = dsCUSTOMER_PROFILE.fields("CUSTOMER_ZIP").Value
      End If
   Else
      sOwnCountryCode = ""
      sOwnercity = ""
      sOwnerStreet = ""
      sOwnerstate = ""
      sOwnerZip = ""
   End If
   If sAgentNo <> "" Then
      gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID=" & sAgentNo
      Set dsAGENT_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
      If Not IsNull(dsAGENT_PROFILE.fields("CUSTOMER_NAME")) Then
         CRPosition1 = 1
         CRPosition2 = InStr(CRPosition1, dsAGENT_PROFILE.fields("CUSTOMER_NAME").Value, "-")
         iNameLenth = CRPosition2 + 1
         sAgent = Mid$(dsAGENT_PROFILE.fields("CUSTOMER_NAME").Value, iNameLenth)
      Else
         sAgent = ""
      End If
      If IsNull(dsAGENT_PROFILE.fields("COUNTRY_CODE")) Then
         sAgentCountryCode = ""
      Else
         sAgentCountryCode = dsAGENT_PROFILE.fields("COUNTRY_CODE").Value
      End If
      If IsNull(dsAGENT_PROFILE.fields("CUSTOMER_CITY")) Then
         sAgentCity = ""
      Else
         sAgentCity = dsAGENT_PROFILE.fields("CUSTOMER_CITY").Value
         sAgentCity = sAgentCity & "," & sAgentCountryCode
      End If
      If IsNull(dsAGENT_PROFILE.fields("CUSTOMER_ADDRESS1")) Then
         sAgentStreet = ""
      Else
         sAgentStreet = dsAGENT_PROFILE.fields("CUSTOMER_ADDRESS1").Value
      End If
      If IsNull(dsAGENT_PROFILE.fields("CUSTOMER_STATE")) Then
         sAgentstate = ""
      Else
         sAgentstate = dsAGENT_PROFILE.fields("CUSTOMER_STATE").Value
      End If
      If IsNull(dsAGENT_PROFILE.fields("CUSTOMER_ZIP")) Then
         sAgentZip = ""
      Else
         sAgentZip = dsAGENT_PROFILE.fields("CUSTOMER_ZIP").Value
      End If
   Else
      sAgentCountryCode = ""
      sAgentCity = ""
      sAgentStreet = ""
      sAgentstate = ""
      sAgentZip = ""
   End If
   
   '**************************************************
   gsSqlStmt = "SELECT * FROM VOYAGE WHERE LR_NUM = " & Trim$(txtLRNum.Text)
   Set dsVOYAGE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
   If Not IsNull(dsVOYAGE.fields("DATE_ARRIVED")) Then
      sArrivedDate = Format$(dsVOYAGE.fields("DATE_ARRIVED").Value, "MM/DD/YYYY") 'CHANGE FORMAT FROM DD/MM TO MM/DD ON 07/01/99
      sArrivedTime = Format$(dsVOYAGE.fields("DATE_ARRIVED").Value, "HH:NN:SS")
   Else
     sArrivedDate = ""
     sArrivedTime = ""
   End If
   If Not IsNull(dsVOYAGE.fields("DATE_DEPARTED")) Then
      sDeptdate = Format$(dsVOYAGE.fields("DATE_DEPARTED").Value, "MM/DD/YYYY")
      sDeptTime = Format$(dsVOYAGE.fields("DATE_DEPARTED").Value, "HH:NN:SS")
   Else
      sDeptdate = ""
      sDeptTime = ""
   End If
   iBerth = NVL(dsVOYAGE.fields("BERTH_NUM").Value, "")
   If Not IsNull(dsVOYAGE.fields("WORKING_DAYS")) Then
      idays = dsVOYAGE.fields("WORKING_DAYS").Value
   Else
      idays = 0
   End If
   If Not IsNull(dsVOYAGE.fields("IDLE_DAYS")) Then
      iIdle = dsVOYAGE.fields("IDLE_DAYS").Value
   Else
      iIdle = 0
   End If
   iDaysatDock = idays + iIdle ' (DateDiff("d", txtDateArrived, txtDateDeparted)) + 1
   sDraftinFWd = Format$(NVL(dsVOYAGE.fields("DRAFT_IN").Value, 0), "0.00")
   sDraftoutFWd = Format$(NVL(dsVOYAGE.fields("DRAFT_OUT").Value, 0), "0.00")
   sDraftinFWdAft = Format$(NVL(dsVOYAGE.fields("DRAFT_IN_AFT").Value, 0), "0.00")
   sDraftoutFWdAft = Format$(NVL(dsVOYAGE.fields("DRAFT_OUT_AFT").Value, 0), "0.00")
   sLastPort = NVL(dsVOYAGE.fields("LAST_PORT").Value, "")
   sNextPort = NVL(dsVOYAGE.fields("NEXT_PORT").Value, "")
   sPilotsIn = NVL(dsVOYAGE.fields("PILOTS_IN").Value, "")
   sPilotsOut = NVL(dsVOYAGE.fields("PILOTS_OUT").Value, "")
   sMaster = NVL(dsVOYAGE.fields("CAPTAIN").Value, "")
   sTonnage = Format(NVL(dsVESSEL_PROFILE.fields("VESSEL_NRT").Value, 0), "##,##0")
   iLenth = NVL(dsVESSEL_PROFILE.fields("VESSEL_LENGTH").Value, 0)
   sTugsIn = NVL(dsVOYAGE.fields("TUGS_IN").Value, "")
   sTugsOut = NVL(dsVOYAGE.fields("TUGS_OUT").Value, "")
   sOrigniated = NVL(dsVOYAGE.fields("PORT_OF_ORIGIN").Value, "")
   sHomePort = NVL(dsVOYAGE.fields("HOME_PORT").Value, "")
   sReportedBy = NVL(dsVOYAGE.fields("REPORTER").Value, "")
   sCommenced = Format$(dsVOYAGE.fields("DATE_STARTED").Value, "MM/DD/YYYY")
   sFinished = Format$(dsVOYAGE.fields("DATE_FINISHED").Value, "MM/DD/YYYY")
   If Not IsNull(dsVOYAGE.fields("PORT_OF_ORIGIN").Value) Then
      sOrigniated = dsVOYAGE.fields("PORT_OF_ORIGIN").Value
   End If
   sDate = Format$(dsVOYAGE.fields("DATE_FINISHED").Value, "MM/DD/YYYY")
   If Not IsNull(dsVOYAGE.fields("PORT_OF_DESTINATION")) Then
      sDestined = dsVOYAGE.fields("PORT_OF_DESTINATION").Value
   Else
      sDestined = ""
   End If
   iFirstcol = 8
   iPilotLenth = Len(sPilotsIn)
   iForthcol = 55
   iFifthcol = 81
   
   Printer.Print Tab(iFirstcol); "Name:" & " " & sVesselName; Tab(iForthcol); "Berth:" & " " & iBerth
   Printer.Print Tab(iFirstcol); "Owner:" & " " & SOwner; Tab(iForthcol); "Arrived-Date:"; Tab(69); sArrivedDate; Tab(iFifthcol); "Time:" & " " & sArrivedTime
   Printer.Print Tab(iFirstcol); "Street:" & " " & sOwnerStreet; Tab(iForthcol); "Departed-Date:"; Tab(70); sDeptdate; Tab(iFifthcol); "Time:" & " " & sDeptTime
   Printer.Print Tab(iFirstcol); "City:" & " " & sOwnercity; Tab(iForthcol); "Days at Dock:" & " " & iDaysatDock
   Printer.Print Tab(iFirstcol); "Agent:" & " " & sAgent; Tab(iForthcol); "Draft in-Fwd:" & " " & sDraftinFWd; Tab(iFifthcol); "Aft:" & " " & sDraftinFWdAft
   Printer.Print Tab(iFirstcol); "Street:" & " " & sAgentStreet; Tab(iForthcol); "Draft Out-Fwd:" & " " & sDraftoutFWd; Tab(iFifthcol); "Aft:" & " " & sDraftoutFWdAft
   Printer.Print Tab(iFirstcol); "City:" & " " & sAgentCity; Tab(iForthcol); "Tugs-In:" & " " & sTugsIn; Tab(iFifthcol); "Out:" & " " & sTugsOut
   Printer.Print Tab(iFirstcol); "Master:" & " " & sMaster; Tab(iForthcol); "Pilots-In:" & " " & sPilotsIn; Tab(iFifthcol); "Out:" & " " & sPilotsOut
   Printer.Print Tab(iFirstcol); "Length:" & " " & iLenth; Tab(iForthcol); "Home Port:" & " " & sHomePort
   Printer.Print Tab(iFirstcol); "N.R.Tonnage:" & " " & sTonnage; Tab(iForthcol); "Last Port:"; " " & sLastPort
   Printer.Print Tab(iFirstcol); "Reported by:" & " " & sReportedBy; Tab(iForthcol); "Next Port:" & " " & sNextPort
   Printer.Print Tab(iFirstcol); "Commenced:" & " " & sCommenced; Tab(iForthcol); "Finished:" & " " & sFinished
   
   'Check Manifest is exist or not
   gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text
   Set dsCARGO_PROFCHECK = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
   If dsCARGO_PROFCHECK.RecordCount = 0 Then
      Printer.FontBold = True
      Printer.Print ""
      Printer.Print ""
      Printer.Print Tab(49); "CARGO"
      Printer.Print Tab(20); "LOADED", Tab(70); "DISCHARGED"
      Printer.Print ""
      Printer.FontBold = False
      Printer.Print Tab(iFirstcol); "Commodity:"; Tab(iForthcol); "Commodity:"
      Printer.Print Tab(iFirstcol); "Tons:"; Tab(iForthcol); "Tons:"
      Printer.Print Tab(iFirstcol); "From:"; Tab(iForthcol); "Into:"
      Printer.Print Tab(iFirstcol); "Destined:"; Tab(iForthcol); "Originated:"
      Printer.Print Tab(iFirstcol); "Cargo in Transit-Com:"; Tab(iForthcol); "Reported by:"
      Printer.Print Tab(iFirstcol); "Cargo in Transit-Com:"; Tab(iForthcol); "Date:"
      Printer.Print ""
      Printer.Print ""
      Printer.FontBold = True
      Printer.Print Tab(44); "ACCOUNTING RECORD"
      Printer.FontBold = False
      Printer.Print ""
      Printer.Print ""
      Printer.Print Tab(iFirstcol); "Charge to ............................"; Tab(iForthcol); "Dockage Rate ....................per day"
      Printer.Print Tab(iFirstcol); "Street................................"; Tab(iForthcol); "Dockage Amount $........................"
      Printer.Print Tab(iFirstcol); "City ................................."; Tab(iForthcol); "Wharfage Rate....................per 2000 lb."
      Printer.Print Tab(iFirstcol); "Dockage Number........................"; Tab(iForthcol); "Wharfage Amount$........................."
      Printer.Print Tab(iFirstcol); "Invoice Number........................"; Tab(iForthcol); "Running Lines  $........................."
      Printer.Print Tab(iForthcol); "Total Amount   $........................."
   Else
     'If exist find out the Import and export details for that ship and check for more than one commodity
      iNoOfComd = 0
      iNoOfIComd = 0
      iOldICmdityCode = 0
      iOldECmdityCode = 0
      gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text
      gsSqlStmt = gsSqlStmt & " AND IMPEX = 'E'ORDER BY COMMODITY_CODE "
      Set dsCARGO_EMPMANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
      If dsCARGO_EMPMANIFEST.RecordCount >= 1 Then
         sCommodityCode = dsCARGO_EMPMANIFEST.fields("COMMODITY_CODE").Value
         If IsNull(dsCARGO_EMPMANIFEST.fields("CARGO_LOCATION")) Then
            SLoadFrom = ""
         Else
            SLoadFrom = dsCARGO_EMPMANIFEST.fields("CARGO_LOCATION").Value
         End If
         Do While Not dsCARGO_EMPMANIFEST.eof
            If (iOldECmdityCode <> dsCARGO_EMPMANIFEST.fields("COMMODITY_CODE").Value) Then
                iOldECmdityCode = dsCARGO_EMPMANIFEST.fields("COMMODITY_CODE").Value
                iNoOfComd = iNoOfComd + 1
            End If
                dsCARGO_EMPMANIFEST.MoveNext
         Loop
         If iNoOfComd > 1 Then
            bExpOver = True
         Else
            bExpOver = False
            gsSqlStmt = "SELECT CARGO_WEIGHT CARGO_WEIGHT FROM CARGO_MANIFEST"
            gsSqlStmt = gsSqlStmt & " WHERE LR_NUM =" + txtLRNum.Text + " AND IMPEX = 'E'AND COMMODITY_CODE=" & sCommodityCode
            Set dsCARGO_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
              Do While Not dsCARGO_PROFILE.eof
                 If IsNull(dsCARGO_PROFILE.fields("CARGO_WEIGHT")) Then
                    lEmpWeight = 0
                 Else
                    lEmpWeight = dsCARGO_PROFILE.fields("CARGO_WEIGHT").Value
                    lEmpNetTons = (lEmpWeight / 2000)
                    lTOTNetEmpTons = lTOTNetEmpTons + lEmpNetTons
                    lTOTALNetEmpTons = Format(lTOTNetEmpTons, "##,##0.0000")
                 End If
                    dsCARGO_PROFILE.MoveNext
              Loop
         End If
         gsSqlStmt = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE=" & sCommodityCode
         Set dsCom_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
         ipos1 = 1
         ipos1 = InStr(ipos1, dsCom_PROFILE.fields("COMMODITY_NAME").Value, "-")
         iNameLenth = ipos1 + 1
         sLoadCommodity = Mid$(dsCom_PROFILE.fields("COMMODITY_NAME").Value, iNameLenth)
      End If
      gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text
      gsSqlStmt = gsSqlStmt & " AND IMPEX = 'I'ORDER BY COMMODITY_CODE"
      Set dsCARGO_IMPMANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
      If dsCARGO_IMPMANIFEST.RecordCount >= 1 Then
         sICommodityCode = dsCARGO_IMPMANIFEST.fields("COMMODITY_CODE").Value
         If IsNull(dsCARGO_IMPMANIFEST.fields("CARGO_LOCATION")) Then
            sInto = ""
         Else
            sInto = dsCARGO_IMPMANIFEST.fields("CARGO_LOCATION").Value
         End If
         Do While Not dsCARGO_IMPMANIFEST.eof
            If (iOldICmdityCode <> dsCARGO_IMPMANIFEST.fields("COMMODITY_CODE").Value) Then
               iOldICmdityCode = dsCARGO_IMPMANIFEST.fields("COMMODITY_CODE").Value
               iNoOfIComd = iNoOfIComd + 1
            End If
               dsCARGO_IMPMANIFEST.MoveNext
         Loop
         If iNoOfIComd > 1 Then
            bImpOver = True
         Else
            bImpOver = False
            gsSqlStmt = "SELECT CARGO_WEIGHT CARGO_WEIGHT FROM CARGO_MANIFEST"
            gsSqlStmt = gsSqlStmt & " WHERE LR_NUM =" + txtLRNum.Text + " AND  IMPEX = 'I' AND COMMODITY_CODE=" & sICommodityCode
            Set dsCARGO_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            Do While Not dsCARGO_PROFILE.eof
               If IsNull(dsCARGO_PROFILE.fields("CARGO_WEIGHT").Value) Then
                  lImpWeight = 0
               Else
                  lImpWeight = dsCARGO_PROFILE.fields("CARGO_WEIGHT").Value
                  lImpNetTons = (lImpWeight / 2000)
                  lTOTNetImpTons = lTOTNetImpTons + lImpNetTons
                  lTOTALNetImpTons = Format(lTOTNetImpTons, "##,##0.0000")
               End If
                  dsCARGO_PROFILE.MoveNext
            Loop
         End If
         gsSqlStmt = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE=" & sICommodityCode
         Set dsCom_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
         ipos1 = 1
         ipos1 = InStr(ipos1, dsCom_PROFILE.fields("COMMODITY_NAME").Value, "-")
         iNameLenth = ipos1 + 1
         sDiscCommodity = Mid$(dsCom_PROFILE.fields("COMMODITY_NAME").Value, iNameLenth)
      End If
      'if more than One commodity then Tons should be over and print the summury in next page
      If dsCARGO_EMPMANIFEST.RecordCount >= 1 And bExpOver = True Then
         sLoadTons = "OVER"
      ElseIf dsCARGO_EMPMANIFEST.RecordCount >= 1 And bExpOver = False Then
         sLoadTons = lTOTALNetEmpTons
      Else
         sLoadCommodity = ""
         sLoadTons = ""
         SLoadFrom = ""
         sDestined = ""
      End If
      If dsCARGO_IMPMANIFEST.RecordCount >= 1 And bImpOver = True Then
         sDiscTons = "OVER"
      ElseIf dsCARGO_IMPMANIFEST.RecordCount >= 1 And bImpOver = False Then
         sDiscTons = lTOTALNetImpTons
      Else
         sDiscCommodity = ""
         sDiscTons = ""
         sInto = ""
         sOrigniated = ""
         sReportedBy = ""
         sDate = ""
      End If
      Printer.FontBold = True
      Printer.Print ""
      Printer.Print ""
      Printer.Print Tab(49); "CARGO"
      Printer.Print Tab(20); "LOADED", Tab(70); "DISCHARGED"
      Printer.Print ""
      Printer.FontBold = False
      Printer.Print Tab(iFirstcol); "Commodity:" & " " & sLoadCommodity; Tab(iForthcol); "Commodity:" & " " & sDiscCommodity
      Printer.Print Tab(iFirstcol); "Tons:" & " " & sLoadTons; Tab(iForthcol); "Tons:" & " " & sDiscTons
      Printer.Print Tab(iFirstcol); "From:" & " " & SLoadFrom; Tab(iForthcol); "Into:" & " " & sInto
      Printer.Print Tab(iFirstcol); "Destined:" & " " & sDestined; Tab(iForthcol); "Originated:" & " " & sOrigniated
      Printer.Print Tab(iFirstcol); "Cargo in Transit-Com:"; Tab(iForthcol); "Reported by:" & " " & sReportedBy
      Printer.Print Tab(iFirstcol); "Cargo in Transit-Com:"; Tab(iForthcol); "Date:" & " " & sDate
      Printer.Print ""
      Printer.Print ""
      Printer.FontBold = True
      Printer.Print Tab(44); "ACCOUNTING RECORD"
      Printer.FontBold = False
      Printer.Print ""
      Printer.Print ""
      Printer.Print Tab(iFirstcol); "Charge to ............................"; Tab(iForthcol); "Dockage Rate ....................per day"
      Printer.Print Tab(iFirstcol); "Street................................"; Tab(iForthcol); "Dockage Amount $........................"
      Printer.Print Tab(iFirstcol); "City ................................."; Tab(iForthcol); "Wharfage Rate....................per 2000 lb."
      Printer.Print Tab(iFirstcol); "Dockage Number........................"; Tab(iForthcol); "Wharfage Amount$........................."
      Printer.Print Tab(iFirstcol); "Invoice Number........................"; Tab(iForthcol); "Running Lines  $........................."
      Printer.Print Tab(iForthcol); "Total Amount   $........................."
      If dsCARGO_PROFCHECK.RecordCount >= 1 Then
         If bExpOver = True Or bImpOver = True Then
            Printer.NewPage
         End If
      End If
      iLineCount = 0
      'If More than One commodity then print the summury
      If (bImpOver = True) Or (bExpOver = True) Then
         For i = 0 To 5
             Printer.Print ""
         Next i
         Printer.FontBold = True
         Printer.FontSize = 14
         Printer.Print Tab(29); "VESSEL SUMMARY"
         Printer.Print Tab(29); "--------------"
         Printer.Print ""
         'Printer.Print ""
         Printer.FontSize = 10
         'Check for Juice
         iOldCustId = 0
         bJuice = False
         bNewcustomer = False
         bFirstTime = True
         bEXpFirstTime = True
         bImpFirstTime = True
         'Check for Juice
         gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM =" & txtLRNum.Text
         gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE IN('5031', '5033')ORDER BY COMMODITY_CODE"
         Set dsCARGO_JUICE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
         If dsCARGO_JUICE.RecordCount >= 1 Then
            gsSqlStmt = "SELECT SUM(CARGO_WEIGHT)CARGO_WEIGHT,IMPEX,SUM(QTY2_EXPECTED)QTY2_EXPECTED,QTY2_UNIT,RECIPIENT_ID,COMMODITY_CODE"
            gsSqlStmt = gsSqlStmt & " FROM CARGO_MANIFEST"
            gsSqlStmt = gsSqlStmt & " WHERE LR_NUM =" + txtLRNum.Text + " GROUP BY IMPEX,RECIPIENT_ID,COMMODITY_CODE,QTY2_UNIT ORDER BY IMPEX,RECIPIENT_ID,COMMODITY_CODE,QTY2_UNIT"
            Set dsCARGO_PROFSUMMURY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            bJuice = True
            Do While Not dsCARGO_PROFSUMMURY.eof
               If dsCARGO_PROFSUMMURY.fields("CARGO_WEIGHT").Value <> 0 Then
                  sCustCode = dsCARGO_PROFSUMMURY.fields("RECIPIENT_ID").Value
                  sComNameID = dsCARGO_PROFSUMMURY.fields("COMMODITY_CODE").Value
                  gsSqlStmt = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE =" & sComNameID
                  Set dsComeCode = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                  sCommodityName = dsComeCode.fields("COMMODITY_NAME").Value
                  Printer.FontBold = True
                  'Check for IMPORT and EXPORT
                  If dsCARGO_PROFSUMMURY.fields("IMPEX").Value = "E" And bEXpFirstTime = True Then
                      Printer.FontSize = 14
                      Printer.Print Tab(32); "EXPORT"
                      Printer.FontSize = 10
                      Printer.Print Tab(44); "*********"
                      bEXpFirstTime = False
                  ElseIf dsCARGO_PROFSUMMURY.fields("IMPEX").Value = "I" And bImpFirstTime = True Then
                      Printer.Print ""
                      Printer.FontSize = 14
                      Printer.Print Tab(33); "IMPORT"
                      Printer.FontSize = 10
                      Printer.Print Tab(45); "**********"
                      bImpFirstTime = False
                      bFirstTime = True
                  End If
                  If (iOldCustId <> dsCARGO_PROFSUMMURY.fields("RECIPIENT_ID").Value) Then
                     iOldCustId = dsCARGO_PROFSUMMURY.fields("RECIPIENT_ID").Value
                     Printer.Print ""
                     If bFirstTime = False Then
                        Printer.Print Tab(23); "-------------------------------------------------------"
                     End If
                     Printer.Print Tab(24); "CUSTOMER" & " " & sCustCode
                     bNewcustomer = True
                  Else
                     bNewcustomer = False
                     Printer.Print ""
                    'Don't print Customer ID
                  End If
                  bFirstTime = False
                  Printer.Print Tab(31); "COMMODITY" & " " & sCommodityName
                  Printer.Print ""
                  'Printer.Print Tab(2); "QTY1"; Tab(11); "UNIT1"; Tab(18); "QTY2"; Tab(25); "UNIT2"; Tab(31); "WEIGHT"; Tab(41); "UNIT"; Tab(51); "NET TON"
                  If bNewcustomer = True Then
                     Printer.Print Tab(24); "QTY2"; Tab(37); "UNIT2"; Tab(47); "WEIGHT"; Tab(57); "UNIT"; Tab(67); "NET TON"
                  End If
                  Printer.FontBold = False
                  If IsNull(dsCARGO_PROFSUMMURY.fields("QTY2_EXPECTED")) Then
                     lCtn = 0
                  Else
                     lCtn = dsCARGO_PROFSUMMURY.fields("QTY2_EXPECTED").Value
                     sForCtn = Format(lCtn, "##,##0")
                  End If
                  If IsNull(dsCARGO_PROFSUMMURY.fields("QTY2_UNIT")) Then
                     sUnit2 = ""
                  Else
                     sUnit2 = dsCARGO_PROFSUMMURY.fields("QTY2_UNIT").Value
                  End If
                  If IsNull(dsCARGO_PROFSUMMURY.fields("CARGO_WEIGHT")) Then
                     lWeight = 0
                  Else
                     lWeight = dsCARGO_PROFSUMMURY.fields("CARGO_WEIGHT").Value
                     sForWeight = Format(lWeight, "##,##0")
                  End If
                     lNetTons = (lWeight / 2000)
                     lTotctn = lCtn + lTotctn
                     sTotalctn = Format(lTotctn, "##,##0")
                     lTotWeight = lWeight + lTotWeight
                     sTotalWeight = Format(lTotWeight, "##,##0")
                     lTOTNetTons = lTOTNetTons + lNetTons
                     Printer.Print Tab(24); sForCtn; Tab(37); sUnit2; Tab(47); sForWeight; Tab(57); "LB"; Tab(67); lNetTons
               End If
                  iLineCount = iLineCount + 1
                  dsCARGO_PROFSUMMURY.MoveNext
            Loop
      Else
            gsSqlStmt = "SELECT SUM(CARGO_WEIGHT)CARGO_WEIGHT,IMPEX,SUM(QTY_EXPECTED)QTY_EXPECTED,RECIPIENT_ID,COMMODITY_CODE"
            gsSqlStmt = gsSqlStmt & " FROM CARGO_MANIFEST"
            gsSqlStmt = gsSqlStmt & " WHERE LR_NUM =" + txtLRNum.Text + " GROUP BY IMPEX,RECIPIENT_ID,COMMODITY_CODE ORDER BY IMPEX,RECIPIENT_ID,COMMODITY_CODE"
            Set dsCARGO_PROFSUMMURY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            Do While Not dsCARGO_PROFSUMMURY.eof
               sCustCode = dsCARGO_PROFSUMMURY.fields("RECIPIENT_ID").Value
               sComNameID = dsCARGO_PROFSUMMURY.fields("COMMODITY_CODE").Value
               gsSqlStmt = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE =" & sComNameID
               Set dsComeCode = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
               sCommodityName = dsComeCode.fields("COMMODITY_NAME").Value
               Printer.FontBold = True
               If dsCARGO_PROFSUMMURY.fields("IMPEX").Value = "E" And bEXpFirstTime = True Then
                  Printer.FontSize = 14
                  Printer.Print Tab(32); "EXPORT"
                  Printer.FontSize = 10
                  Printer.Print Tab(44); "*********"
                  bEXpFirstTime = False
               ElseIf dsCARGO_PROFSUMMURY.fields("IMPEX").Value = "I" And bImpFirstTime = True Then
                  Printer.Print ""
                  Printer.FontSize = 14
                  Printer.Print Tab(33); "IMPORT"
                  Printer.FontSize = 10
                  Printer.Print Tab(45); "**********"
                  bImpFirstTime = False
                  bFirstTime = True
               End If
               If (iOldCustId <> dsCARGO_PROFSUMMURY.fields("RECIPIENT_ID").Value) Then
                  iOldCustId = dsCARGO_PROFSUMMURY.fields("RECIPIENT_ID").Value
                  Printer.Print ""
                  If bFirstTime = False Then
                     Printer.Print Tab(23); "---------------------------------------------------------"
                  Else
                     Printer.Print ""
                  End If
                  Printer.Print Tab(24); "CUSTOMER" & " " & sCustCode
                  bNewcustomer = True
               Else
                  bNewcustomer = False
                  Printer.Print ""
                  'Don't print Customer ID
               End If
               bFirstTime = False
               
               Printer.Print Tab(31); "COMMODITY" & " " & sCommodityName
               Printer.Print ""
               'Printer.Print Tab(2); "QTY1"; Tab(11); "UNIT1"; Tab(18); "QTY2"; Tab(25); "UNIT2"; Tab(31); "WEIGHT"; Tab(41); "UNIT"; Tab(51); "NET TON"
               If bNewcustomer = True Then
                  Printer.Print Tab(24); "QTY1"; Tab(41); "WEIGHT"; Tab(58); "UNIT"; Tab(72); "NET TON"
               End If
               Printer.FontBold = False
               If IsNull(dsCARGO_PROFSUMMURY.fields("QTY_EXPECTED")) Then
                   lPlts = 0
               Else
                   lPlts = dsCARGO_PROFSUMMURY.fields("QTY_EXPECTED").Value
                   sForPlts = Format(lPlts, "##,##0")
               End If
               If IsNull(dsCARGO_PROFSUMMURY.fields("CARGO_WEIGHT")) Then
                   lWeight = 0
               Else
                   lWeight = dsCARGO_PROFSUMMURY.fields("CARGO_WEIGHT").Value
                   sForWeight = Format(lWeight, "##,##0")
               End If
               sWtUnit = "LB"
               lNetTons = (lWeight / 2000)
               lTotPlts = lPlts + lTotPlts
               sTotalPlts = Format(lTotPlts, "##,##0")
               lTotWeight = lWeight + lTotWeight
               sTotalWeight = Format(lTotWeight, "##,##0")
               lTOTNetTons = lTOTNetTons + lNetTons
               Printer.Print Tab(24); sForPlts; Tab(41); sForWeight; Tab(58); sWtUnit; Tab(72); lNetTons
               dsCARGO_PROFSUMMURY.MoveNext
            Loop
      End If
      Printer.Print ""
      Printer.FontBold = True
      Printer.Print Tab(23); "---------------------------------------------------------"
      If bJuice = True Then
         Printer.Print Tab(24); sTotalctn; Tab(47); sTotalWeight; Tab(67); lTOTNetTons
      Else
         Printer.Print Tab(24); sTotalPlts; Tab(41); sTotalWeight; Tab(72); lTOTNetTons
      End If
      Printer.FontBold = False
   End If
 End If
 Printer.EndDoc
 
End If

End Sub
Private Sub cmdSave_Click()

    Dim i As Integer
    Dim iError As Integer
    Dim lRecCount As Long
    Dim iPos As Integer
    
    'Check for valid data
    'If Not VAlidateFields Then
        'Exit Sub
    'End If
    If Not ValidateData Then
        Exit Sub
    End If
   'Lock all the required tables in exclusive mode, try 10 times
    'On Error Resume Next
    'For i = 0 To 9
    '    OraDatabase.LastServerErrReset
    '    gsSqlStmt = "LOCK TABLE VESSEL_PROFILE IN EXCLUSIVE MODE NOWAIT"
    '    gsSqlStmt = "LOCK TABLE VOYAGE IN EXCLUSIVE MODE NOWAIT"
    '    gsSqlStmt = "LOCK TABLE FLEET IN EXCLUSIVE MODE NOWAIT"
    '    lRecCount = OraDatabase.ExecuteSql(gsSqlStmt)
    '    If OraDatabase.LastServerErr = 0 Then Exit For
    'Next 'i
    'If OraDatabase.LastServerErr <> 0 Then
    '    OraDatabase.LastServerErr
    '    MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
    ''    Exit Sub
    'End If
    On Error GoTo 0
    
    iError = False
            
    'Begin a transaction
    OraSession.BeginTrans
    
    'Save vessel
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = '" & Trim$(txtLRNum.Text) & "'"
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If dsVESSEL_PROFILE.RecordCount = 0 Then
            dsVESSEL_PROFILE.AddNew
            dsVESSEL_PROFILE.fields("LR_NUM").Value = CLng(txtLRNum.Text)
        Else
            dsVESSEL_PROFILE.Edit
        End If
        
        
        dsVESSEL_PROFILE.fields("VESSEL_NAME").Value = txtVesselName.Text
        'If cboPortOfRegistry.ListIndex >= 1 Then
        '    dsVESSEL_PROFILE.Fields("PORT_OF_REGISTRY").Value = CLng(cboPortOfRegistry.List(cboPortOfRegistry.ListIndex))
        'End If
        'If cboVesselStatus.ListIndex >= 0 Then
        '    dsVESSEL_PROFILE.Fields("VESSEL_STATUS").Value = cboVesselStatus.List(cboVesselStatus.ListIndex)
        'End If
        'If cboVesselFlag.ListIndex >= 0 Then
        '    iPos = InStr(cboVesselFlag.List(cboVesselFlag.ListIndex), " : ")
        '    If iPos > 0 Then
        '        dsVESSEL_PROFILE.Fields("VESSEL_FLAG").Value = Left$(cboVesselFlag.List(cboVesselFlag.ListIndex), iPos - 1)
        '    End If
        'End If
        If cboVesselBilling.ListIndex = 0 Then
            dsVESSEL_PROFILE.fields("VESSEL_BILLING").Value = "N"
        ElseIf cboVesselBilling.ListIndex = 1 Then
            dsVESSEL_PROFILE.fields("VESSEL_BILLING").Value = "L"
        End If
        If Trim$(txtVesselNRT.Text) <> "" Then
           dsVESSEL_PROFILE.fields("VESSEL_NRT").Value = CLng(txtVesselNRT.Text)
        Else
           dsVESSEL_PROFILE.fields("VESSEL_NRT").Value = ""
        End If
        If Trim$(txtVesselLength.Text) <> "" Then
           dsVESSEL_PROFILE.fields("VESSEL_LENGTH").Value = CDbl(txtVesselLength.Text)
        Else
           dsVESSEL_PROFILE.fields("VESSEL_LENGTH").Value = ""
        End If
        'If Trim$(txtPreferredBerth1.Text) <> "" Then
        '    dsVESSEL_PROFILE.Fields("PREFERRED_BERTH1").Value = CLng(txtPreferredBerth1.Text)
        'End If
        'If Trim$(txtPreferredBerth2.Text) <> "" Then
        '    dsVESSEL_PROFILE.Fields("PREFERRED_BERTH2").Value = CLng(txtPreferredBerth2.Text)
        'End If

        dsVESSEL_PROFILE.Update
        
        If OraDatabase.LastServerErr <> 0 Then
            iError = True
        End If
    Else
        iError = True
    End If
    
    'Save Voyage
    gsSqlStmt = "SELECT * FROM VOYAGE WHERE LR_NUM = " & Trim$(txtLRNum.Text)
    Set dsVOYAGE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If dsVOYAGE.RecordCount = 0 Then
            dsVOYAGE.AddNew
            dsVOYAGE.fields("LR_NUM").Value = CLng(txtLRNum.Text)
        Else
            dsVOYAGE.Edit
        End If
        
        
        dsVOYAGE.fields("ARRIVAL_NUM").Value = 1
        dsVOYAGE.fields("VOYAGE_NUM").Value = 1
        'If cboVoyageStatus.ListIndex >= 0 Then
        '    dsVOYAGE.Fields("VOYAGE_STATUS").Value = cboVoyageStatus.List(cboVoyageStatus.ListIndex)
        'End If
        dsVOYAGE.fields("CAPTAIN").Value = txtCaptain.Text
        'If Trim$(cboBerthnum.Text) <> "" Then
            dsVOYAGE.fields("BERTH_NUM").Value = Trim(cboBerthnum.Text)
        'End If
        If cboStevedoreId.ListIndex >= 0 Then
            iPos = InStr(cboStevedoreId.List(cboStevedoreId.ListIndex), "-")
            If iPos > 0 Then
                dsVOYAGE.fields("STEVEDORE_ID").Value = Trim(Mid(cboStevedoreId.List(cboStevedoreId.ListIndex), iPos + 1)) 'Left$(cboStevedoreId.List(cboStevedoreId.ListIndex), iPos - 1)
            End If
        End If
        If cboVesselOperatorId.ListIndex >= 0 Then
            iPos = InStr(cboVesselOperatorId.List(cboVesselOperatorId.ListIndex), "-")
            If iPos > 0 Then
                dsVOYAGE.fields("VESSEL_OPERATOR_ID").Value = Trim(Mid(cboVesselOperatorId.List(cboVesselOperatorId.ListIndex), iPos + 1)) 'Left$(cboVesselOperatorId.List(cboVesselOperatorId.ListIndex), iPos - 1)
            End If
        End If
        If Trim$(txtDateExpected.Text) <> "" Then
           dsVOYAGE.fields("DATE_EXPECTED").Value = Format$(CDate(txtDateExpected.Text & " " & txtTimeExpected.Text), "MM/DD/YYYY HH:MM:SS")
        Else
           dsVOYAGE.fields("DATE_EXPECTED").Value = ""
        End If
        If Trim$(txtDateArrived.Text) <> "" Then
           dsVOYAGE.fields("DATE_ARRIVED").Value = Format$(CDate(txtDateArrived.Text & " " & txtTimeArrived.Text), "MM/DD/YYYY HH:MM:SS")
        Else
           dsVOYAGE.fields("DATE_ARRIVED").Value = ""
        End If
        If Trim$(txtDateDeparted.Text) <> "" Then
           dsVOYAGE.fields("DATE_DEPARTED").Value = Format$(CDate(txtDateDeparted.Text & " " & txtTimeDeparted.Text), "MM/DD/YYYY HH:MM:SS")
        Else
           dsVOYAGE.fields("DATE_DEPARTED").Value = ""
        End If
        '***************************************
        'Added New Fields
        
        If Trim$(txtStartDate.Text) <> "" Then
           dsVOYAGE.fields("DATE_STARTED").Value = Format$(CDate(txtStartDate.Text & " " & txtStartTime.Text), "MM/DD/YYYY HH:MM:SS")
        Else
           dsVOYAGE.fields("DATE_STARTED").Value = ""
        End If
        If Trim$(txtEndDate.Text) <> "" Then
           dsVOYAGE.fields("DATE_FINISHED").Value = Format$(CDate(txtEndDate.Text & " " & txtEndTime.Text), "MM/DD/YYYY HH:MM:SS")
        Else
           dsVOYAGE.fields("DATE_FINISHED").Value = ""
        End If
        If Trim$(txtDays.Text) <> "" Then
            dsVOYAGE.fields("WORKING_DAYS").Value = txtDays.Text
        Else
            dsVOYAGE.fields("WORKING_DAYS").Value = ""
        End If
        If Trim$(txtIdle.Text) <> "" Then
           dsVOYAGE.fields("IDLE_DAYS").Value = txtIdle.Text
        Else
           dsVOYAGE.fields("IDLE_DAYS").Value = ""
        End If
       
        If Trim$(txtTugsIn.Text) <> "" Then
            dsVOYAGE.fields("TUGS_IN").Value = txtTugsIn.Text
        Else
            dsVOYAGE.fields("TUGS_IN").Value = ""
        End If
        If Trim$(txtTugsout.Text) <> "" Then
            dsVOYAGE.fields("TUGS_OUT").Value = txtTugsout.Text
        Else
            dsVOYAGE.fields("TUGS_OUT").Value = ""
        End If
        If Trim$(txtPilotIn.Text) <> "" Then
           dsVOYAGE.fields("PILOTS_IN").Value = txtPilotIn.Text
        Else
           dsVOYAGE.fields("PILOTS_IN").Value = ""
        End If
        If Trim$(txtPilotOut.Text) <> "" Then
            dsVOYAGE.fields("PILOTS_OUT").Value = txtPilotOut.Text
        Else
            dsVOYAGE.fields("PILOTS_OUT").Value = ""
        End If
        If Trim$(txtDestPort.Text) <> "" Then
           dsVOYAGE.fields("PORT_OF_DESTINATION").Value = txtDestPort.Text
        Else
           dsVOYAGE.fields("PORT_OF_DESTINATION").Value = ""
        End If
        If Trim$(txtHomeport.Text) <> "" Then
           dsVOYAGE.fields("HOME_PORT").Value = txtHomeport.Text
        Else
           dsVOYAGE.fields("HOME_PORT").Value = ""
        End If
        If Trim$(txtOriginated.Text) <> "" Then
           dsVOYAGE.fields("PORT_OF_ORIGIN").Value = txtOriginated.Text
        Else
           dsVOYAGE.fields("PORT_OF_ORIGIN").Value = ""
        End If
        If Trim$(txtReportedTo.Text) <> "" Then
           dsVOYAGE.fields("REPORTER").Value = txtReportedTo.Text
        Else
           dsVOYAGE.fields("REPORTER").Value = ""
        End If
               
        'If Trim$(txtDateFinished.Text) <> "" Then
        '    dsVOYAGE.Fields("DATE_FINISHED").Value = CDate(txtDateFinished.Text & " " & txtTimeFinished.Text)
        'End If
        If Trim$(txtDraftIn.Text) <> "" Then
           dsVOYAGE.fields("DRAFT_IN").Value = CDbl(txtDraftIn.Text)
        Else
           dsVOYAGE.fields("DRAFT_IN").Value = ""
        End If
        If Trim$(txtDraftOut.Text) <> "" Then
           dsVOYAGE.fields("DRAFT_OUT").Value = CDbl(txtDraftOut.Text)
        Else
           dsVOYAGE.fields("DRAFT_OUT").Value = ""
        End If
        If Trim$(txtDraftInAft.Text) <> "" Then
           dsVOYAGE.fields("DRAFT_IN_AFT").Value = CDbl(txtDraftInAft.Text)
        Else
           dsVOYAGE.fields("DRAFT_IN_AFT").Value = ""
        End If
        If Trim$(txtDraftOutAft.Text) <> "" Then
           dsVOYAGE.fields("DRAFT_OUT_AFT").Value = CDbl(txtDraftOutAft.Text)
        Else
           dsVOYAGE.fields("DRAFT_OUT_AFT").Value = ""
        End If
        If Trim$(txtLastPort.Text) <> "" Then
           dsVOYAGE.fields("LAST_PORT").Value = txtLastPort.Text
        Else
           dsVOYAGE.fields("LAST_PORT").Value = ""
        End If
        If Trim$(txtNextPort.Text) <> "" Then
           dsVOYAGE.fields("NEXT_PORT").Value = txtNextPort.Text
         Else
           dsVOYAGE.fields("NEXT_PORT").Value = ""
        End If
        If Trim$(txtFreetime.Text) <> "" Then
           dsVOYAGE.fields("FREE_TIME_START").Value = Format$(txtFreetime.Text, "MM/DD/YYYY")
        Else
           dsVOYAGE.fields("FREE_TIME_START").Value = ""
        End If
     '***************************************
        dsVOYAGE.Update
        
        If OraDatabase.LastServerErr <> 0 Then
            iError = True
        End If
    Else
        iError = True
    End If
    
    'Save Fleet
    gsSqlStmt = "SELECT * FROM FLEET WHERE LR_NUM = " & Trim$(txtLRNum.Text)
    Set dsFLEET = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If dsFLEET.RecordCount = 0 Then
            dsFLEET.AddNew
        Else
            dsFLEET.Edit
        End If
        
        dsFLEET.fields("LR_NUM").Value = CLng(txtLRNum.Text)
        
        If cboShippingLine.ListIndex >= 0 Then
            iPos = InStr(cboShippingLine.List(cboShippingLine.ListIndex), "-")
            If iPos > 0 Then
                dsFLEET.fields("CUSTOMER_ID").Value = Trim(Mid(cboShippingLine.List(cboShippingLine.ListIndex), iPos + 1)) 'Left$(cboShippingLine.List(cboShippingLine.ListIndex), iPos - 1)
            End If
        End If
        
        dsFLEET.Update
        
        If OraDatabase.LastServerErr <> 0 Then
            iError = True
        End If
    Else
        iError = True
    End If
    
    'Complete transaction
    If iError Then
        'Rollback transaction
        MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
        OraSession.Rollback
        Exit Sub
    Else
        'Commit transaction
        OraSession.CommitTrans
        'MsgBox "The Ship Information has Successfully Saved.", vbInformation, "Save"
    End If
    
    'txtLRNum.Text = ""
    Call ClearVessel
    Call ClearVoyage
    Call ClearShippingLine
    'Call ShipNumber
    'cmdPrint.Enabled = False
    
End Sub
Private Sub cmdVesselList_Click()

    Dim iPos As Integer
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Vessel List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RecordCount > 0 Then
        While Not dsVESSEL_PROFILE.eof
            frmPV.lstPV.AddItem dsVESSEL_PROFILE.fields("LR_NUM").Value & " : " & dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
            dsVESSEL_PROFILE.MoveNext
        Wend
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtLRNum.Text = Left$(gsPVItem, iPos - 1)
            txtVesselName.Text = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtLRNum.SetFocus
    
End Sub
Private Sub cmdTrkLodBill_Click()
    
    If Trim(txtLRNum) = "" Then Exit Sub
    
    If MsgBox("Are you sure to generate the Advance Invoices for the Vessel : " & txtLRNum, vbQuestion + vbYesNo, "ADVANCE TRUCK BILLING") = vbYes Then
        frmAdTrkLodBill.Show
    Else
        Exit Sub
    End If
    
End Sub
Private Sub Form_Load()

    Dim i As Integer
   
    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    Me.Enabled = False
    lblStatus.Caption = "Logging to database..."
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
    'Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)
    'Set OraDatabase2 = OraSession.OpenDatabase("PROD", "APPS/APPS", 0&)

    'And OraDatabase2.LastServerErr = 0
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
'        Else
'            MsgBox "Error " & OraDatabase2.LastServerErrText & " occured.", vbExclamation, "Logon"
        End If
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    Call ShipNumber
        
    lblStatus.Caption = "Loading countries..."
    DoEvents
    Call LoadCountry
    
    lblStatus.Caption = "Loading customers..."
    DoEvents
    Call LoadCustomer
         
    lblStatus.Caption = "Loading Berth Number..."
    DoEvents
    Call LoadBerthnum
     
    
    lblStatus.Caption = "Ready"
    DoEvents
    
    'txtLRNum.Text = ""
    Call ClearVessel
    Call ClearVoyage
    Call ClearShippingLine
    
    On Error GoTo 0
     
    Me.Enabled = True
    Exit Sub
   
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    lblStatus.Caption = "Error Occured."
    Me.Enabled = True
    On Error GoTo 0
    
End Sub

Private Sub txtDateArrived_LostFocus()

  If Trim$(txtDateArrived.Text) <> "" Then
    If Not IsDate(txtDateArrived.Text) Then
       MsgBox "Date must be mm/dd/yyyy format", vbInformation, "Date format"
       txtDateArrived.Text = ""
    End If
  End If
  
End Sub
Private Sub txtDateDeparted_LostFocus()

   If Trim$(txtDateDeparted.Text) <> "" Then
      If Not IsDate(txtDateDeparted.Text) And Trim$(txtDateArrived.Text) <> "" Then
         MsgBox "Date must be mm/dd/yyyy format", vbInformation, "Date format"
         txtDateDeparted.Text = ""
      ElseIf (DateDiff("d", txtDateArrived.Text, txtDateDeparted.Text)) < 0 Then
        MsgBox "Sailed Date Must be Greater Than Arrived Date", vbInformation, "Date format"
        txtDateDeparted.SetFocus
        Exit Sub
      End If
   End If
   
End Sub
Private Sub txtDateExpected_LOSTFOCUS()
    
    If Not IsDate(txtDateExpected.Text) Then
        MsgBox "Date must be mm/dd/yyyy format", vbInformation, "Date format"
        txtDateExpected.Text = ""
    End If
    
End Sub
Private Sub txtDays_LostFocus()

Dim idays As Integer
   If Trim$(txtStartDate.Text) = "" Or Trim$(txtEndDate.Text) = "" Then
      'Do nothing
   Else
      idays = DateDiff("d", txtStartDate.Text, txtEndDate.Text) '& ":" & iTimediff
   End If
   If idays = 0 Then
      idays = 1
   Else
      idays = idays + 1
   End If
   If Trim$(txtStartDate.Text) = "" Or Trim$(txtEndDate.Text) = "" Then
      idays = 0
   End If
   txtDays.Text = idays
   
End Sub
Private Sub txtDraftIn_LostFocus()

   If Trim$(txtDraftIn.Text) <> "" Then
       If Val(txtDraftIn.Text) >= 100 Then
           MsgBox "The value should greater than 100.", vbInformation
           txtDraftIn.SetFocus
           Exit Sub
       End If
   End If
   
End Sub
Private Sub txtDraftInAft_LostFocus()

   If Trim$(txtDraftInAft.Text) <> "" Then
       If Val(txtDraftInAft.Text) >= 100 Then
           MsgBox "The value should greater than 100.", vbInformation
           txtDraftInAft.SetFocus
           Exit Sub
       End If
   End If
   
End Sub
Private Sub txtDraftOut_LostFocus()

   If Trim$(txtDraftOut.Text) <> "" Then
       If Val(txtDraftOut.Text) >= 100 Then
           MsgBox "The value should greater than 100.", vbInformation
           txtDraftOut.SetFocus
           Exit Sub
       End If
   End If
   
End Sub
Private Sub txtDraftOutAft_LostFocus()

   If Trim$(txtDraftOutAft.Text) <> "" Then
       If Val(txtDraftOutAft.Text) >= 100 Then
           MsgBox "The value should greater than 100.", vbInformation
           txtDraftOutAft.SetFocus
           Exit Sub
       End If
   End If
   
End Sub
Private Sub txtEndDate_LostFocus()

   If Trim$(txtEndDate.Text) <> "" And Trim$(txtStartDate.Text) <> "" Then
      If Not IsDate(txtEndDate.Text) Then
        MsgBox "Date must be mm/dd/yyyy format", vbInformation, "Date format"
        Exit Sub
      ElseIf (DateDiff("d", txtStartDate.Text, txtEndDate.Text)) < 0 Then
        MsgBox "End Date Must be Greater Than start Date", vbInformation, "Date format"
        txtEndDate.SetFocus
        Exit Sub
      ElseIf (DateDiff("d", txtEndDate.Text, txtDateDeparted.Text)) < 0 Then
        MsgBox "End Date Must be Less Than or Equal to Sailed Date", vbInformation, "Date format"
        txtEndDate.SetFocus
        Exit Sub
      End If
   End If
      Call txtDays_LostFocus
      
End Sub
Private Sub txtEndTime_LostFocus()

   If Trim$(txtEndTime.Text) <> "" Then
      If Not IsValidTime(txtEndTime.Text) Then
         MsgBox "End Time is not valid. Please enter in HH:MM (24 Hour) format.", vbExclamation, "Invalid Start Hour"
         Exit Sub
      End If
   End If
   
End Sub
Private Sub txtFreetime_LostFocus()

  If Trim$(txtFreetime.Text) <> "" Then
    If Not IsDate(txtFreetime.Text) Then
       MsgBox "Date must be mm/dd/yyyy format", vbInformation, "Date format"
       txtFreetime.Text = ""
    End If
  End If
  
End Sub
Private Sub txtLRNum_LostFocus()

    If Trim$(txtLRNum) <> "" And IsNumeric(txtLRNum) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLRNum.Text
        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 Then
            If dsVESSEL_PROFILE.RecordCount > 0 Then
                lblStatus.Caption = "Vessel Found"
                bVesselfound = True
                 
                'Vessel
                txtVesselName.Text = NVL(dsVESSEL_PROFILE.fields("VESSEL_NAME").Value, "")
                'If IsNull(dsVESSEL_PROFILE.Fields("PORT_OF_REGISTRY").Value) Then
                '    cboPortOfRegistry.ListIndex = 0
                'Else
                '    cboPortOfRegistry.ListIndex = ComboIndex(cboPortOfRegistry, CStr(dsVESSEL_PROFILE.Fields("PORT_OF_REGISTRY").Value), False)
                'End If
                'cboVesselStatus.ListIndex = ComboIndex(cboVesselStatus, NVL(dsVESSEL_PROFILE.Fields("VESSEL_STATUS").Value, ""), False)
                'cboVesselFlag.ListIndex = ComboIndex(cboVesselFlag, NVL(dsVESSEL_PROFILE.Fields("VESSEL_FLAG").Value, ""), True)
                If NVL(dsVESSEL_PROFILE.fields("VESSEL_BILLING").Value, "") = "N" Then
                    cboVesselBilling.ListIndex = 0
                ElseIf NVL(dsVESSEL_PROFILE.fields("VESSEL_BILLING").Value, "") = "L" Then
                    cboVesselBilling.ListIndex = 1
                Else
                    cboVesselBilling.ListIndex = -1
                End If
                txtVesselNRT.Text = NVL(dsVESSEL_PROFILE.fields("VESSEL_NRT").Value, 0)
                txtVesselLength.Text = NVL(dsVESSEL_PROFILE.fields("VESSEL_LENGTH").Value, 0)
                'txtPreferredBerth1.Text = NVL(dsVESSEL_PROFILE.Fields("PREFERRED_BERTH1").Value, 0)
                'txtPreferredBerth2.Text = NVL(dsVESSEL_PROFILE.Fields("PREFERRED_BERTH2").Value, 0)

                'Voyage
                gsSqlStmt = "SELECT * FROM VOYAGE WHERE LR_NUM = " & txtLRNum.Text
                Set dsVOYAGE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsVOYAGE.RecordCount > 0 Then
                    'txtVoyageNum.Text = NVL(dsVOYAGE.Fields("VOYAGE_NUM").Value, "")
                    txtCaptain.Text = NVL(dsVOYAGE.fields("CAPTAIN").Value, "")
                    'cboVoyageStatus.ListIndex = ComboIndex(cboVoyageStatus, NVL(dsVOYAGE.Fields("VOYAGE_STATUS").Value, Space(10)), False)
'                    txtBerthNum.Text = NVL(dsVOYAGE.fields("BERTH_NUM").Value, "")
                    cboStevedoreId.ListIndex = ComboIndex(cboStevedoreId, CStr(NVL(dsVOYAGE.fields("STEVEDORE_ID").Value, 0)), True)
                    cboVesselOperatorId.ListIndex = ComboIndex(cboVesselOperatorId, CStr(NVL(dsVOYAGE.fields("VESSEL_OPERATOR_ID").Value, 0)), True)
                    cboBerthnum.ListIndex = ComboIndex(cboBerthnum, NVL(dsVOYAGE.fields("BERTH_NUM").Value, ""), 0)
                                                     
                    If Not IsNull(dsVOYAGE.fields("DATE_EXPECTED").Value) Then
                        txtDateExpected.Text = Format$(dsVOYAGE.fields("DATE_EXPECTED").Value, "MM/DD/YYYY") 'CHANGE FORMAT FROM DD/MM TO MM/DD ON 07/01/99
                        txtTimeExpected.Text = Format$(dsVOYAGE.fields("DATE_EXPECTED").Value, "HH:NN:SS")
                    Else
                        txtDateExpected.Text = ""
                        txtTimeExpected.Text = ""
                    End If
                    If Not IsNull(dsVOYAGE.fields("DATE_ARRIVED")) Then
                        txtDateArrived.Text = Format$(dsVOYAGE.fields("DATE_ARRIVED").Value, "MM/DD/YYYY")
                        txtTimeArrived.Text = Format$(dsVOYAGE.fields("DATE_ARRIVED").Value, "HH:NN:SS")
                    Else
                        txtDateArrived.Text = ""
                        txtTimeArrived.Text = ""
                    End If
                    If Not IsNull(dsVOYAGE.fields("DATE_DEPARTED")) Then
                        txtDateDeparted.Text = Format$(dsVOYAGE.fields("DATE_DEPARTED").Value, "MM/DD/YYYY")
                        txtTimeDeparted.Text = Format$(dsVOYAGE.fields("DATE_DEPARTED").Value, "HH:NN:SS")
                    Else
                        txtDateDeparted.Text = ""
                        txtTimeDeparted.Text = ""
                    End If
                    '************************
                    'New fields Addedd on 10/07/1999
                    
                    If Not IsNull(dsVOYAGE.fields("DATE_STARTED")) Then
                        txtStartDate.Text = Format$(dsVOYAGE.fields("DATE_STARTED").Value, "MM/DD/YYYY")
                        txtStartTime.Text = Format$(dsVOYAGE.fields("DATE_STARTED").Value, "HH:NN:SS")
                    Else
                        txtStartDate.Text = ""
                        txtStartTime.Text = ""
                    End If
                  
                    If Not IsNull(dsVOYAGE.fields("DATE_FINISHED")) Then
                        txtEndDate.Text = Format$(dsVOYAGE.fields("DATE_FINISHED").Value, "MM/DD/YYYY")
                        txtEndTime.Text = Format$(dsVOYAGE.fields("DATE_FINISHED").Value, "HH:NN:SS")
                    Else
                        txtEndDate.Text = ""
                        txtEndTime.Text = ""
                    End If
                    
                    If Not IsNull(dsVOYAGE.fields("FREE_TIME_START")) Then
                        txtFreetime = Format$(dsVOYAGE.fields("FREE_TIME_START").Value, "MM/DD/YYYY")
                    Else
                        txtFreetime = ""
                    End If
                   '*******************************
                    txtDraftIn.Text = Format$(NVL(dsVOYAGE.fields("DRAFT_IN").Value, 0), "0.00")
                    txtDraftOut.Text = Format$(NVL(dsVOYAGE.fields("DRAFT_OUT").Value, 0), "0.00")
                    txtDraftInAft.Text = Format$(NVL(dsVOYAGE.fields("DRAFT_IN_AFT").Value, 0), "0.00")
                    txtDraftOutAft.Text = Format$(NVL(dsVOYAGE.fields("DRAFT_OUT_AFT").Value, 0), "0.00")
                    txtHomeport.Text = NVL(dsVOYAGE.fields("HOME_PORT").Value, "")
                    txtLastPort.Text = NVL(dsVOYAGE.fields("LAST_PORT").Value, "")
                    txtNextPort.Text = NVL(dsVOYAGE.fields("NEXT_PORT").Value, "")
                    txtDestPort.Text = NVL(dsVOYAGE.fields("PORT_OF_DESTINATION").Value, "")
                    txtOriginated.Text = NVL(dsVOYAGE.fields("PORT_OF_ORIGIN").Value, "")
                    txtTugsIn.Text = NVL(dsVOYAGE.fields("TUGS_IN").Value, "")
                    txtTugsout.Text = NVL(dsVOYAGE.fields("TUGS_OUT").Value, "")
                    txtPilotIn.Text = NVL(dsVOYAGE.fields("PILOTS_IN").Value, "")
                    txtPilotOut.Text = NVL(dsVOYAGE.fields("PILOTS_OUT").Value, "")
                    txtDays.Text = NVL(dsVOYAGE.fields("WORKING_DAYS").Value, "")
                    txtIdle.Text = NVL(dsVOYAGE.fields("IDLE_DAYS").Value, "")
                    txtReportedTo = NVL(dsVOYAGE.fields("REPORTER").Value, "")
                Else
                    Call ClearVoyage
                End If
                
                'Shipping Line
                gsSqlStmt = "SELECT * FROM FLEET WHERE LR_NUM = " & txtLRNum.Text
                Set dsFLEET = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsFLEET.RecordCount > 0 Then
                    cboShippingLine.ListIndex = ComboIndex(cboShippingLine, CStr(NVL(dsFLEET.fields("CUSTOMER_ID").Value, 0)), True)
                Else
                    Call ClearShippingLine
                End If
            Else
                Call ClearVessel
                Call ClearVoyage
                Call ClearShippingLine
                lblStatus.Caption = "New Vessel"
                txtVesselName.Text = txtLRNum.Text & "-"
                txtVesselName.SelStart = Len(txtVesselName.Text)
            End If
        End If
    Else
        If Trim$(txtLRNum.Text) <> "" And Not IsNumeric(txtLRNum.Text) Then
            MsgBox "Please enter a numeric Lloyd's Registration.", vbExclamation, "Invalid LR Number"
            txtLRNum.SetFocus
            Exit Sub
        End If
        Call ClearVessel
        Call ClearVoyage
        Call ClearShippingLine
    End If
        'cmdPrint.Enabled = True
        
End Sub
Public Sub ClearVessel()

    txtVesselName.Text = ""
    'cboPortOfRegistry.ListIndex = 0
    'cboVesselStatus.ListIndex = 0
    'cboVesselFlag.ListIndex = -1
    cboVesselBilling.ListIndex = 0
    txtVesselNRT.Text = ""
    txtVesselLength.Text = ""
    'txtPreferredBerth1.Text = "1"
    'txtPreferredBerth2.Text = "1"
    
End Sub
Public Sub LoadCountry()

    'gsSqlStmt = "SELECT * FROM COUNTRY ORDER BY COUNTRY_CODE"
    'Set dsCOUNTRY = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    'If OraDatabase.LastServerErr = 0 And dsCOUNTRY.RecordCount > 0 Then
    '    While Not dsCOUNTRY.EOF
    '        cboVesselFlag.AddItem dsCOUNTRY.Fields("COUNTRY_CODE").Value & " : " & NVL(dsCOUNTRY.Fields("COUNTRY_NAME").Value, "")
    '        dsCOUNTRY.MoveNext
    '    Wend
    'End If
    
End Sub
Public Sub ClearVoyage()

    'txtVoyageNum.Text = ""
    txtCaptain.Text = ""
    'cboVoyageStatus.ListIndex = 0
'    txtBerthNum.Text = ""
    cboStevedoreId.ListIndex = -1
    cboVesselOperatorId.ListIndex = -1
    cboBerthnum.ListIndex = -1
    txtDateExpected.Text = ""
    txtTimeExpected.Text = ""
    txtDateArrived.Text = ""
    txtTimeArrived.Text = ""
    txtDateDeparted.Text = ""
    txtTimeDeparted.Text = ""
    'txtDateFinished.Text = ""
    'txtTimeFinished.Text = ""
    txtDraftIn.Text = ""
    txtDraftOut.Text = ""
    txtDraftInAft.Text = ""
    txtDraftOutAft.Text = ""
    txtLastPort.Text = ""
    txtNextPort.Text = ""
    txtPilotIn.Text = ""
    txtTugsIn.Text = ""
    txtTugsout.Text = ""
    txtHomeport.Text = ""
    txtDestPort.Text = ""
    txtReportedTo.Text = ""
    txtStartDate.Text = ""
    txtStartTime.Text = ""
    txtEndDate.Text = ""
    txtEndTime.Text = ""
    txtDays.Text = ""
    txtIdle.Text = ""
    txtOriginated.Text = ""
    txtPilotOut.Text = ""
    txtFreetime = ""
    
End Sub
Public Sub LoadCustomer()

   Dim sCust As String
   Dim sCustId As String
   Dim iPos As Integer
   Dim iCustId As Long
   
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_NAME"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
        For iRec = 1 To dsCUSTOMER_PROFILE.RecordCount
            
            iPos = InStr(1, dsCUSTOMER_PROFILE.fields("customer_name").Value, "-")
            If iPos > 0 Then
                sCustId = Trim(Mid(dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 1, iPos - 1))
                If (IsNumeric(sCustId)) Then
                    iCustId = Trim(Mid(dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 1, iPos - 1))
                    sCust = Trim(Mid(dsCUSTOMER_PROFILE.fields("customer_name").Value, iPos + 1))
                    cboStevedoreId.AddItem sCust & " - " & iCustId
                    cboVesselOperatorId.AddItem sCust & " - " & iCustId
                    cboShippingLine.AddItem sCust & " - " & iCustId
                End If
            End If
            dsCUSTOMER_PROFILE.MoveNext
        Next iRec
    End If
    
End Sub
Public Sub ClearShippingLine()

    cboShippingLine.ListIndex = -1
    
End Sub
Public Function ValidateData() As Integer

Dim iDatediff As Integer
Dim iStartDatediff As Integer
    If Trim$(txtLRNum.Text) = "" Or Not IsNumeric(txtLRNum.Text) Then
        MsgBox "Invalid Vessel Code. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If

    'If Trim$(txtPreferredBerth1.Text) <> "" And Not IsNumeric(txtPreferredBerth1.Text) Then
    '    MsgBox "Invalid Preferred Berth 1. Please enter and save again.", vbExclamation, "Save"
    '   ValidateData = False
    '    Exit Function
    'End If

    'If Trim$(txtPreferredBerth2.Text) <> "" And Not IsNumeric(txtPreferredBerth2.Text) Then
    '    MsgBox "Invalid Preferred Berth 2. Please enter and save again.", vbExclamation, "Save"
    '    ValidateData = False
    '    Exit Function
    'End If

    If Trim$(txtVesselNRT.Text) <> "" And Not IsNumeric(txtVesselNRT.Text) Then
        MsgBox "Invalid Billing NRT. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If

    If Trim$(txtVesselLength.Text) <> "" And Not IsNumeric(txtVesselLength.Text) Then
        MsgBox "Invalid Billing Length. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If

    'If Trim$(txtVoyageNum.Text) <> "" And Not IsNumeric(txtVoyageNum.Text) Then
    '    MsgBox "Invalid Voyage #. Please enter and save again.", vbExclamation, "Save"
    '    ValidateData = False
    '   Exit Function
    'End If

    If Trim$(cboBerthnum.Text) <> "" And Not IsNumeric(cboBerthnum.Text) Then
        MsgBox "Invalid Voyage Berth. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If
    
     If Trim$(cboBerthnum.Text) = "" And Trim$(txtDateDeparted.Text) <> "" Then
        MsgBox "You should enter Voyage Berth. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If
    

    If Trim$(txtDateExpected.Text) <> "" And Not IsDate(txtDateExpected.Text) Then
        MsgBox "Invalid Date Expected. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If

    If Trim$(txtTimeExpected.Text) <> "" And Not IsDate(txtDateExpected.Text & " " & txtTimeExpected.Text) Then
        MsgBox "Invalid Time Expected. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If
    If Trim$(txtDateArrived.Text) <> "" Then
       If Not IsDate(txtDateArrived.Text) Then
          MsgBox "Invalid Date Arrived. Please enter and save again.", vbExclamation, "Save"
          ValidateData = False
          Exit Function
       End If
    End If
    If Trim$(txtTimeArrived.Text) <> "" Then
       If Not IsDate(txtDateArrived.Text & " " & txtTimeArrived.Text) Then
          MsgBox "Invalid Time Arrived. Please enter and save again.", vbExclamation, "Save"
          ValidateData = False
          Exit Function
       End If
    End If
    If Trim$(txtDateDeparted.Text) <> "" Then
       If Not IsDate(txtDateDeparted.Text) Then
          MsgBox "Invalid Date Sailed. Please enter and save again.", vbExclamation, "Save"
          ValidateData = False
          Exit Function
       End If
    End If
    If Trim$(txtTimeDeparted.Text) <> "" Then
        If Not IsDate(txtDateDeparted.Text & " " & txtTimeDeparted.Text) Then
           MsgBox "Invalid Time Sailed. Please enter and save again.", vbExclamation, "Save"
           ValidateData = False
           Exit Function
        End If
    End If
    If Trim$(txtDraftIn.Text) <> "" And Not IsNumeric(txtDraftIn.Text) Then
        MsgBox "Invalid Fwd Draft In. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If

    If Trim$(txtDraftOut.Text) <> "" And Not IsNumeric(txtDraftOut.Text) Then
        MsgBox "Invalid Fwd Draft Out. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If

    If Trim$(txtDraftInAft.Text) <> "" And Not IsNumeric(txtDraftInAft.Text) Then
        MsgBox "Invalid Aft Draft In. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If

    If Trim$(txtDraftOutAft.Text) <> "" And Not IsNumeric(txtDraftOutAft.Text) Then
        MsgBox "Invalid Aft Draft Out. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If
    If Trim$(txtDays.Text) <> "" And Not IsNumeric(txtDays.Text) Then
        MsgBox "Invalid Days. Please enter and save again.", vbExclamation, "Save"
        ValidateData = False
        Exit Function
    End If
    If (txtDateArrived.Text <> "") And (txtDateDeparted.Text <> "") Then
      iDatediff = DateDiff("d", txtDateArrived.Text, txtDateDeparted.Text)
    End If
    If iDatediff <= -1 Then
       MsgBox "The Arrived Date should be greater than Departure Date", vbExclamation, "Save"
       ValidateData = False
       Exit Function
    End If
    If Trim$(txtStartDate.Text) <> "" Then
       If Not IsDate(txtStartDate.Text) Then
          MsgBox "Date must be MM/DD/YYYY format", vbExclamation, "Save"
          ValidateData = False
          Exit Function
       End If
    End If
    If Trim$(txtStartDate.Text) <> "" And Trim$(txtEndDate.Text) <> "" Then
       iStartDatediff = DateDiff("d", txtStartDate.Text, txtEndDate.Text)
    End If
    If iStartDatediff <= -1 Then
       MsgBox "The End Date should be greater than or Equal to Start Date", vbExclamation, "Save"
       ValidateData = False
       Exit Function
    End If
    If Trim$(txtEndDate.Text) <> "" Then
       If Not IsDate(txtEndDate.Text) Then
          MsgBox "Date must be MM/DD/YYYY format", vbExclamation, "Save"
          ValidateData = False
          Exit Function
       ElseIf (DateDiff("d", txtEndDate.Text, txtDateDeparted.Text)) < 0 Then
          MsgBox "End Date Must be Less Than or Equal to Sailed Date", vbInformation, "Date format"
          ValidateData = False
          Exit Function
       End If
    End If
    If Trim$(txtFreetime.Text) <> "" Then
       If Not IsDate(txtFreetime.Text) Then
          MsgBox "Date must be MM/DD/YYYY format", vbExclamation, "Save"
          ValidateData = False
          Exit Function
       End If
    End If
    ValidateData = True
    
End Function
Private Sub txtStartDate_GotFocus()

  txtStartDate.Text = Format$(txtDateArrived.Text, "MM/DD/YYYY")
  
End Sub
Private Sub txtStartDate_LostFocus()

  If Trim$(txtStartDate.Text) <> "" Then
    If Not IsDate(txtStartDate.Text) Then
        MsgBox "Date must be mm/dd/yyyy format", vbInformation, "Date format"
        Exit Sub
    ElseIf (DateDiff("d", txtDateArrived.Text, txtStartDate.Text)) < 0 Then
        MsgBox "Start Date Must be greater Than or Equal to Arrived Date", vbInformation, "Date format"
        txtStartDate.SetFocus
        Exit Sub
    End If
  End If
  
  End Sub
Private Sub txtStartTime_LostFocus()

  If Trim$(txtStartTime.Text) <> "" Then
    If Not IsValidTime(txtStartTime.Text) Then
       MsgBox "Start Time is not valid. Please enter in HH:MM (24 Hour) format.", vbExclamation, "Invalid Start Hour"
       Exit Sub
    End If
  End If
  
End Sub
Private Sub ShipNumber()
     ' update the condition check and no adding 1 to vessel # and get vessel information  -- LFW, 4/15/05
     gsSqlStmt = "SELECT MAX(LR_NUM) MAX_LRNUM FROM VESSEL_PROFILE WHERE LENGTH(LR_NUM) < 6"
     Set dsSHIPNO_MAX = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
     
     lSHIPNO = dsSHIPNO_MAX.fields("MAX_LRNUM").Value
     txtLRNum.Text = lSHIPNO
End Sub
Private Sub txtTimeArrived_LostFocus()

   If Trim$(txtTimeArrived.Text) <> "" Then
      If Not IsValidTime(txtTimeArrived.Text) Then
         MsgBox "Arrived Time is not valid. Please enter in HH:MM (24 Hour) format.", vbExclamation, "Invalid Start Hour"
         Exit Sub
      End If
   End If
   
End Sub
Private Sub txtTimeDeparted_LostFocus()

   If Trim$(txtTimeDeparted.Text) <> "" Then
      If Not IsValidTime(txtTimeDeparted.Text) Then
         MsgBox "Departure Time is not valid. Please enter in HH:MM (24 Hour) format.", vbExclamation, "Invalid Start Hour"
         Exit Sub
      End If
   End If
   
End Sub
Private Sub txtTimeExpected_LostFocus()

  If Not IsValidTime(txtTimeExpected.Text) Then
    MsgBox "Expected Time is not valid. Please enter in HH:MM (24 Hour) format.", vbExclamation, "Invalid Start Hour"
    Exit Sub
  End If
  
End Sub
Private Sub txtVesselName_GotFocus()

  If bVesselfound = True Then
    'DO NOTHING
  Else
    txtVesselName.Text = txtLRNum.Text & "-"
  End If
  
End Sub
Public Function VAlidateFields() As Boolean

    If cboShippingLine.Text = "" Then
       MsgBox "Invalid Owner Name.", vbExclamation, "Print"
       VAlidateFields = False
       Exit Function
    End If
    If cboVesselOperatorId.Text = "" Then
       MsgBox "Invalid Agent Name.", vbExclamation, "Print"
       VAlidateFields = False
       Exit Function
    End If
    VAlidateFields = True
    
End Function

Public Sub LoadBerthnum()
  Dim berth_num As String
    
    gsSqlStmt = "SELECT BERTH_NUM FROM BERTH_DETAIL ORDER BY BERTH_NUM"
    Set dsBERTH_NUM = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBERTH_NUM.RecordCount > 0 Then
        cboBerthnum.AddItem ""
        For iRec = 1 To dsBERTH_NUM.RecordCount
            berth_num = NVL(dsBERTH_NUM.fields("BERTH_NUM").Value, "")
            cboBerthnum.AddItem berth_num
            dsBERTH_NUM.MoveNext
        Next iRec
    End If
End Sub

