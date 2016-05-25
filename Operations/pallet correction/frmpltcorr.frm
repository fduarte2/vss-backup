VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmPltCorrection 
   AutoRedraw      =   -1  'True
   BackColor       =   &H00FFFFC0&
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Pallet Correction"
   ClientHeight    =   11835
   ClientLeft      =   795
   ClientTop       =   435
   ClientWidth     =   14085
   BeginProperty Font 
      Name            =   "Times New Roman"
      Size            =   9
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   ForeColor       =   &H000000FF&
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   ScaleHeight     =   11835
   ScaleWidth      =   14085
   Begin VB.TextBox txtPartialBC 
      Appearance      =   0  'Flat
      Height          =   375
      Left            =   11640
      MaxLength       =   32
      TabIndex        =   58
      Top             =   1680
      Width           =   2295
   End
   Begin VB.CommandButton cmdBCFill 
      Caption         =   "Partial Barcode &Search"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   435
      Left            =   11640
      TabIndex        =   57
      Top             =   2280
      Width           =   2295
   End
   Begin VB.CommandButton cmdTrans 
      Caption         =   "&Transfers"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   1800
      TabIndex        =   36
      Top             =   11310
      Visible         =   0   'False
      Width           =   1320
   End
   Begin VB.CommandButton cmdClear 
      Caption         =   "C&lear"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   4755
      TabIndex        =   35
      Top             =   11310
      Width           =   1320
   End
   Begin VB.Frame Frame1 
      BackColor       =   &H00FFFFC0&
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   5535
      Left            =   1643
      TabIndex        =   18
      Top             =   120
      Width           =   9855
      Begin VB.TextBox txtACTloc 
         Appearance      =   0  'Flat
         Height          =   375
         Left            =   6960
         MaxLength       =   12
         TabIndex        =   63
         Top             =   4920
         Width           =   2295
      End
      Begin VB.TextBox txtWHSloc 
         Appearance      =   0  'Flat
         Height          =   375
         Left            =   2160
         MaxLength       =   12
         TabIndex        =   62
         Top             =   4920
         Width           =   1815
      End
      Begin VB.TextBox txtTmShipped 
         Appearance      =   0  'Flat
         Height          =   375
         Left            =   8160
         TabIndex        =   61
         Top             =   4440
         Width           =   1095
      End
      Begin VB.TextBox txtDtShipped 
         Appearance      =   0  'Flat
         Height          =   375
         Left            =   6960
         TabIndex        =   60
         Top             =   4440
         Width           =   1095
      End
      Begin VB.TextBox txtWtUnt 
         Appearance      =   0  'Flat
         Height          =   375
         Left            =   3360
         MaxLength       =   2
         TabIndex        =   56
         Top             =   4440
         Width           =   615
      End
      Begin VB.TextBox txtWT 
         Appearance      =   0  'Flat
         Height          =   375
         Left            =   2160
         MaxLength       =   9
         TabIndex        =   55
         Top             =   4440
         Width           =   975
      End
      Begin VB.CommandButton cmdCommPopup 
         Caption         =   "?"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   9360
         TabIndex        =   53
         Top             =   1800
         Width           =   375
      End
      Begin VB.TextBox txtRecType 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   4080
         Locked          =   -1  'True
         TabIndex        =   52
         Top             =   3480
         Width           =   5175
      End
      Begin VB.ComboBox cmbRecType 
         Appearance      =   0  'Flat
         ForeColor       =   &H00000000&
         Height          =   345
         ItemData        =   "frmpltcorr.frx":0000
         Left            =   2160
         List            =   "frmpltcorr.frx":0011
         Style           =   2  'Dropdown List
         TabIndex        =   51
         Top             =   3480
         Width           =   1215
      End
      Begin VB.CommandButton cmdUnReceive 
         Caption         =   "Unreceive"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   335
         Left            =   7560
         TabIndex        =   38
         Top             =   3000
         Width           =   1695
      End
      Begin VB.CommandButton cmdDeleteCT 
         Caption         =   "DELETE"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   315
         Left            =   4560
         TabIndex        =   37
         Top             =   240
         Visible         =   0   'False
         Width           =   1365
      End
      Begin VB.CommandButton CMDPrevoius 
         Caption         =   "&Prev"
         Enabled         =   0   'False
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   315
         Left            =   6240
         TabIndex        =   33
         Top             =   600
         Width           =   615
      End
      Begin VB.CommandButton CMDNext 
         Caption         =   "&Next"
         Enabled         =   0   'False
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   315
         Left            =   5400
         TabIndex        =   32
         Top             =   600
         Width           =   675
      End
      Begin VB.TextBox txtCrgDes 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   2160
         MaxLength       =   60
         TabIndex        =   31
         Top             =   3960
         Width           =   5055
      End
      Begin VB.TextBox txtOwner 
         Appearance      =   0  'Flat
         Enabled         =   0   'False
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   6360
         TabIndex        =   29
         TabStop         =   0   'False
         Top             =   2400
         Width           =   2895
      End
      Begin VB.TextBox txtComm 
         Appearance      =   0  'Flat
         Enabled         =   0   'False
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   4080
         TabIndex        =   28
         TabStop         =   0   'False
         Top             =   1800
         Width           =   5175
      End
      Begin VB.TextBox txtVessel 
         Appearance      =   0  'Flat
         Enabled         =   0   'False
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   4080
         TabIndex        =   27
         TabStop         =   0   'False
         Top             =   1200
         Width           =   5175
      End
      Begin VB.TextBox txtPltNum 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   2160
         MaxLength       =   32
         TabIndex        =   0
         Tag             =   "NotToBeClear"
         Top             =   600
         Width           =   3015
      End
      Begin VB.TextBox txtVesselNo 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   2160
         TabIndex        =   1
         Top             =   1215
         Width           =   1215
      End
      Begin VB.TextBox txtQtyRecvd 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00FF0000&
         Height          =   330
         Left            =   2160
         TabIndex        =   5
         Top             =   2400
         Width           =   1215
      End
      Begin VB.TextBox txtQtyInHouse 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   2160
         TabIndex        =   7
         Top             =   3000
         Width           =   1215
      End
      Begin VB.TextBox txtOwnerId 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   5130
         TabIndex        =   6
         Top             =   2400
         Width           =   1095
      End
      Begin VB.TextBox txtDtRecd 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   5130
         TabIndex        =   8
         Top             =   3000
         Width           =   1095
      End
      Begin VB.TextBox txtIntls 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   8520
         TabIndex        =   10
         Tag             =   "NotToBeClear"
         Top             =   3960
         Width           =   735
      End
      Begin VB.TextBox txtCommCode 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   2160
         TabIndex        =   3
         Top             =   1815
         Width           =   1215
      End
      Begin VB.CommandButton cmdVessel 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   315
         Left            =   3570
         Picture         =   "frmpltcorr.frx":0023
         Style           =   1  'Graphical
         TabIndex        =   2
         Top             =   1215
         Width           =   375
      End
      Begin VB.CommandButton cmdCommCode 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   315
         Left            =   3570
         Picture         =   "frmpltcorr.frx":0125
         Style           =   1  'Graphical
         TabIndex        =   4
         Top             =   1815
         Width           =   375
      End
      Begin VB.TextBox txtTmRecd 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   6360
         TabIndex        =   9
         Top             =   3000
         Width           =   975
      End
      Begin VB.Label Label11 
         Alignment       =   1  'Right Justify
         BackColor       =   &H00FFFFC0&
         Caption         =   "Confirmed Loc :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   5280
         TabIndex        =   65
         Top             =   4950
         Width           =   1575
      End
      Begin VB.Label Label10 
         Alignment       =   1  'Right Justify
         BackColor       =   &H00FFFFC0&
         Caption         =   "Expected Loc :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   375
         Left            =   360
         TabIndex        =   64
         Top             =   4950
         Width           =   1575
      End
      Begin VB.Label Label9 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Ship Out Time:"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   5400
         TabIndex        =   59
         Top             =   4480
         Width           =   1455
      End
      Begin VB.Label Label8 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Weight:"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   1200
         TabIndex        =   54
         Top             =   4480
         Width           =   855
      End
      Begin VB.Label Label7 
         Alignment       =   1  'Right Justify
         BackColor       =   &H00FFFFC0&
         Caption         =   "Receive Type  :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   450
         TabIndex        =   50
         Top             =   3510
         Width           =   1455
      End
      Begin VB.Label lblQtyExpc 
         BackColor       =   &H00FFFFC0&
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   9000
         TabIndex        =   43
         Top             =   840
         Width           =   735
      End
      Begin VB.Label Label3 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Orig QTY Expc :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   7440
         TabIndex        =   42
         Top             =   840
         Width           =   1455
      End
      Begin VB.Label lblQC 
         Alignment       =   2  'Center
         BackColor       =   &H00FFFFC0&
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   7560
         TabIndex        =   41
         Top             =   240
         Width           =   1575
      End
      Begin VB.Label lblQtyDmg 
         BackColor       =   &H00FFFFC0&
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   9000
         TabIndex        =   40
         Top             =   480
         Width           =   735
      End
      Begin VB.Label Label2 
         BackColor       =   &H00FFFFC0&
         Caption         =   "QTY Dmg  :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   7920
         TabIndex        =   39
         Top             =   480
         Width           =   975
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         BackStyle       =   0  'Transparent
         Caption         =   "Cargo Description:"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   240
         Left            =   210
         TabIndex        =   30
         Top             =   3960
         Width           =   1755
      End
      Begin VB.Label lblCommCode 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         BackStyle       =   0  'Transparent
         Caption         =   "Commodity Code  :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   330
         TabIndex        =   26
         Top             =   1860
         Width           =   1575
      End
      Begin VB.Label lblIntls 
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         BackStyle       =   0  'Transparent
         Caption         =   "Initials  :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   7530
         TabIndex        =   25
         Top             =   3990
         Width           =   690
      End
      Begin VB.Label lblRecdDate 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         BackStyle       =   0  'Transparent
         Caption         =   "Date Received  :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   3555
         TabIndex        =   24
         Top             =   3060
         Width           =   1350
      End
      Begin VB.Label lblOwnerId 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         BackStyle       =   0  'Transparent
         Caption         =   "Owner Id  :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   4005
         TabIndex        =   23
         Top             =   2460
         Width           =   900
      End
      Begin VB.Label lblQtyInHouse 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         BackStyle       =   0  'Transparent
         Caption         =   "Qty Inhouse  :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   765
         TabIndex        =   22
         Top             =   3060
         Width           =   1140
      End
      Begin VB.Label lblOrgQtyRecvd 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         BackStyle       =   0  'Transparent
         Caption         =   "Qty Received  :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   660
         TabIndex        =   21
         Top             =   2460
         Width           =   1245
      End
      Begin VB.Label lblVesselNo 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         BackStyle       =   0  'Transparent
         Caption         =   "Vessel No  :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   915
         TabIndex        =   20
         Top             =   1260
         Width           =   990
      End
      Begin VB.Label lblPltNum 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackColor       =   &H00FFFFC0&
         BackStyle       =   0  'Transparent
         Caption         =   "Pallet Barcode  :"
         BeginProperty Font 
            Name            =   "Arial"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   525
         TabIndex        =   19
         Top             =   660
         Width           =   1380
      End
   End
   Begin VB.CommandButton cmdADD 
      Caption         =   "&Add"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   9210
      TabIndex        =   15
      Top             =   11310
      Visible         =   0   'False
      Width           =   1320
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&Print"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   10695
      TabIndex        =   16
      Top             =   11310
      Width           =   1320
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&Delete"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   7725
      Style           =   1  'Graphical
      TabIndex        =   14
      Top             =   11310
      Width           =   1320
   End
   Begin VB.CommandButton CmdExit 
      Cancel          =   -1  'True
      Caption         =   "&Exit"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   12180
      Style           =   1  'Graphical
      TabIndex        =   17
      Top             =   11310
      Width           =   1320
   End
   Begin VB.CommandButton cmdCancel 
      Caption         =   "&Cancel"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   12600
      Style           =   1  'Graphical
      TabIndex        =   13
      Top             =   4080
      Visible         =   0   'False
      Width           =   1320
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "C&ommit"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   3285
      Style           =   1  'Graphical
      TabIndex        =   12
      Top             =   11310
      Width           =   1320
   End
   Begin VB.CommandButton cmdOrdDtls 
      Caption         =   "&Order Details"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   300
      Style           =   1  'Graphical
      TabIndex        =   11
      Top             =   11310
      Width           =   1320
   End
   Begin SSDataWidgets_B.SSDBGrid GrdPlt 
      Height          =   5115
      Left            =   120
      TabIndex        =   34
      Top             =   6000
      Width           =   13815
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
      Col.Count       =   14
      ForeColorEven   =   8388608
      RowHeight       =   423
      ExtraHeight     =   1958
      Columns.Count   =   14
      Columns(0).Width=   2143
      Columns(0).Caption=   "DATE"
      Columns(0).Name =   "DATE"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   2064
      Columns(1).Caption=   "TIME"
      Columns(1).Name =   "TIME"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   3995
      Columns(2).Caption=   "ACTION"
      Columns(2).Name =   "ACTION"
      Columns(2).Alignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   2990
      Columns(3).Caption=   "CHECKER"
      Columns(3).Name =   "CHECKER"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(3).Locked=   -1  'True
      Columns(4).Width=   3096
      Columns(4).Caption=   "ORDER"
      Columns(4).Name =   "ORDER"
      Columns(4).Alignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   2170
      Columns(5).Caption=   "CUST"
      Columns(5).Name =   "CUSTOMER"
      Columns(5).Alignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(5).Locked=   -1  'True
      Columns(6).Width=   1984
      Columns(6).Caption=   "QTY"
      Columns(6).Name =   "QUANTITY"
      Columns(6).Alignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   3200
      Columns(7).Visible=   0   'False
      Columns(7).Caption=   "ACTIVITY"
      Columns(7).Name =   "ACTIVITY"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   3200
      Columns(8).Visible=   0   'False
      Columns(8).Caption=   "SERVICE_CODE"
      Columns(8).Name =   "SERVICE_CODE"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   3200
      Columns(9).Visible=   0   'False
      Columns(9).Caption=   "DESCRIPTION"
      Columns(9).Name =   "DESCRIPTION"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   3200
      Columns(10).Visible=   0   'False
      Columns(10).Caption=   "BILLED"
      Columns(10).Name=   "BILLED"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   3200
      Columns(11).Visible=   0   'False
      Columns(11).Caption=   "ARRIVAL_NUM"
      Columns(11).Name=   "ARRIVAL_NUM"
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   2170
      Columns(12).Caption=   "QTY LEFT"
      Columns(12).Name=   "QTY LEFT"
      Columns(12).Alignment=   2
      Columns(12).CaptionAlignment=   2
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   1958
      Columns(13).Caption=   "MISCBILL"
      Columns(13).Name=   "MISCBILL"
      Columns(13).Alignment=   2
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   1
      _ExtentX        =   24368
      _ExtentY        =   9022
      _StockProps     =   79
      Caption         =   "PALLET DETAILS"
      ForeColor       =   -2147483630
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.Label Label6 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Rec Notes:"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   120
      TabIndex        =   49
      Top             =   1320
      Width           =   1335
   End
   Begin VB.Label Label5 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Rec Type:"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   120
      TabIndex        =   48
      Top             =   120
      Width           =   1215
   End
   Begin VB.Label lblActDesc 
      BackColor       =   &H00FFFFC0&
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   975
      Left            =   240
      TabIndex        =   47
      Top             =   1680
      Width           =   1215
   End
   Begin VB.Label lblRecType 
      BackColor       =   &H00FFFFC0&
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   495
      Left            =   240
      TabIndex        =   46
      Top             =   480
      Width           =   1215
   End
   Begin VB.Label lblRecBy 
      BackColor       =   &H00FFFFC0&
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   255
      Left            =   11760
      TabIndex        =   45
      Top             =   720
      Width           =   1935
   End
   Begin VB.Label Label4 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Received By:"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   11760
      TabIndex        =   44
      Top             =   240
      Width           =   1095
   End
End
Attribute VB_Name = "frmPltCorrection"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Const sMsg As String = "PALLET CORRECTION"
Dim iRec As Integer
Dim sqlstmt As String
Dim OldDt As Date
Dim OldTime As Date
Dim iOldCustId As Integer
Dim iOldQty As Integer
Dim iQtyLeft As Integer
Dim sLRNUM As String
Dim short_term_loopcounter As Integer

Dim sCurVes As String
Dim iCurComm As Integer
Dim iCurQtyRec As Integer
Dim iCurQtyInHouse As Integer
Dim iCurOwner As Integer
Dim lCurWt As Double
Dim sCurDateRec As String
Dim sCurTimeRec As String
Dim sCurCrgDes As String
Dim sCurRecType As String
Dim sCurWtUnt As String
Dim sCurDateShip As String
Dim sCurTimeShip As String
Dim sCurWHSloc As String
Dim sCurACTloc As String


Dim sCommType As String

Private Sub cmbRecType_LostFocus()
    If cmbRecType.List(cmbRecType.ListIndex) <> sCurRecType Then
        cmbRecType.ForeColor = vbBlue
    Else
        cmbRecType.ForeColor = vbBlack
    End If

    sqlstmt = "SELECT SERVICE_NAME FROM SERVICE_CATEGORY WHERE SERVICE_CODE = '" & cmbRecType.ItemData(cmbRecType.ListIndex) & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If dsSHORT_TERM_DATA.recordcount = 0 Then
        txtRecType.Text = "UNKNOWN.  Please Change"
    Else
        txtRecType.Text = dsSHORT_TERM_DATA.fields("SERVICE_NAME").Value
    End If

    If txtRecType.Text <> lblRecType.Caption Then
        lblRecType.ForeColor = vbBlue
    Else
        lblRecType.ForeColor = vbBlack
    End If

End Sub

Private Sub cmdBCFill_Click()
    Dim iPos As Integer
    load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Partial Barcode Search"
    frmPV.lstPV.Clear
    
    sqlstmt = "SELECT DISTINCT PALLET_ID FROM CARGO_TRACKING WHERE PALLET_ID LIKE '%" & txtPartialBC.Text & "%' ORDER BY PALLET_ID"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
    If OraDatabase.lastservererr = 0 And dsSHORT_TERM_DATA.recordcount > 0 Then
        For iRec = 1 To dsSHORT_TERM_DATA.recordcount
            frmPV.lstPV.AddItem dsSHORT_TERM_DATA.fields("PALLET_ID").Value
            dsSHORT_TERM_DATA.MoveNext
        Next iRec
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        txtPltNum.Text = gsPVItem
    End If
    txtPltNum.SetFocus

End Sub

Private Sub cmdCommPopup_Click()
    MsgBox "This program can be used to view any barcode's history, but is only qualified to EDIT the following types of cargo: " _
    & vbCrLf & vbCrLf & "CHILEAN" & vbCrLf & "BOOKING" & vbCrLf & "BRAZILIAN" & vbCrLf & "PERUVIAN" _
    & vbCrLf & "ARG FRUIT" & vbCrLf & "ARG JUICE" & vbCrLf & "STEEL" & vbCrLf & "CLEMENTINES"
End Sub

Private Sub Form_Load()    'checked
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    Me.Refresh
    
    GrdPlt.RowHeight = 300
    
'    If gsLotNum <> "" Then
'        txtPltNum = gsLotNum
'        txtPltNum_LostFocus
'        dsCargo_Tracking_Global.DbRefresh
'        Call SHOW_RECORDS(Trim$(txtPltNum))
'    End If
   
'    bPwd = False
'    CMDNext.Enabled = False
'    CMDPrevoius.Enabled = False
    
    Exit Sub
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & _
            " occurred while processing.", vbExclamation, sMsg
    
    Me.MousePointer = vbDefault
    On Error GoTo 0
End Sub

Private Sub cmdADD_Click()
    frmPwdAdd.Show vbModal
    Call SHOW_RECORDS(Trim$(txtPltNum), "first")
End Sub


'Private Sub cmdCancel_Click()
'    Call SHOW_RECORDS(Trim$(txtPltNum))
'End Sub

Private Sub cmdClear_Click()
    Dim Ctl As Control
    
    For Each Ctl In frmPltCorrection
        If TypeOf Ctl Is TextBox And Ctl.Tag <> "NotToBeClear" Then
            Ctl.Text = ""
        End If
    Next Ctl
    GrdPlt.RemoveAll
End Sub




Private Sub txtCrgDes_LostFocus()
    If sCurCrgDes <> txtCrgDes Then
        txtCrgDes.ForeColor = vbBlue
    Else
        txtCrgDes.ForeColor = vbBlack
    End If

End Sub

Private Sub txtDtShipped_lostfocus()
'    If txtDtShipped = "" Then Exit Sub
    If sCurDateShip <> txtDtShipped Then
        txtDtShipped.ForeColor = vbBlue
    Else
        txtDtShipped.ForeColor = vbBlack
    End If

End Sub

Private Sub txtPltNum_LostFocus()
    
   If Trim(txtPltNum) = "" Then Exit Sub
   
   Call SHOW_RECORDS(Trim$(txtPltNum), "first")
         
End Sub

Private Sub CMDNext_Click()
'   CMDPrevoius.Enabled = True
'   dsCargo_Tracking_Global.MoveNext
'   If dsCargo_Tracking_Global.EOF Then
'      dsCargo_Tracking_Global.MovePrevious
'      CMDNext.Enabled = False
'   End If
'
'   dsCargo_Tracking_Global.MoveNext
'   If dsCargo_Tracking_Global.EOF Then CMDNext.Enabled = False
'   dsCargo_Tracking_Global.MovePrevious
   
   Call SHOW_RECORDS(txtPltNum.Text, "next")
    
End Sub

Private Sub CMDPrevoius_Click()
'   CMDNext.Enabled = True
'    dsCargo_Tracking_Global.MovePrevious
'       If dsCargo_Tracking_Global.BOF Then
'            dsCargo_Tracking_Global.MoveNext
'            CMDPrevoius.Enabled = False
'       End If
'   dsCargo_Tracking_Global.MovePrevious
'   If dsCargo_Tracking_Global.BOF Then CMDPrevoius.Enabled = False
'   dsCargo_Tracking_Global.MoveNext
   
     Call SHOW_RECORDS(txtPltNum.Text, "prev")

End Sub

Sub SHOW_RECORDS(sPlt_id As String, sAction As String)
    
   Dim sqlstmt As String
    
   Call cmdClear_Click ' blank out the screen in preparation of pallet data
   GrdPlt.RowHeight = 300
    
   If sPlt_id = "" Then
      MsgBox "Not a valid Pallet", vbInformation, "Pallet"
      Exit Sub
   End If

    ' here we do something different based on which of the 3 methods called this function.
    If sAction = "first" Then ' either program just started, or changes have been made that need a screen refresh, perform main SQL
        sqlstmt = "SELECT CT.ARRIVAL_NUM, VP.VESSEL_NAME, CT.COMMODITY_CODE, NVL(COMP.COMMODITY_NAME, 'NONE') THE_COMM, CT.QTY_IN_HOUSE, RECEIVING_TYPE, WAREHOUSE_LOCATION, ACTUAL_LOCATION, " _
            & "CT.QTY_RECEIVED, CT.RECEIVER_ID, NVL(CUSP.CUSTOMER_NAME, 'NONE') THE_CUST, CT.QTY_IN_HOUSE, NVL(TO_CHAR(DATE_RECEIVED, 'MM/DD/YYYY'), '') REC_DATE, NVL(TO_CHAR(SHIPOUTTIME, 'MM/DD/YYYY'), '') SHIP_DATE, " _
            & "NVL(TO_CHAR(DATE_RECEIVED, 'HH24:MI:SS'), '') REC_TIME, NVL(TO_CHAR(SHIPOUTTIME, 'HH24:MI:SS'), '') SHIP_TIME, NVL(CT.CARGO_DESCRIPTION, '') CARG_DESC, NVL(CT.QTY_DAMAGED, '0') THE_DMG, CTAD.QTY_EXPECTED, WEIGHT, WEIGHT_UNIT, COMMODITY_TYPE " _
            & "FROM CARGO_TRACKING CT, COMMODITY_PROFILE COMP, CUSTOMER_PROFILE CUSP, VESSEL_PROFILE VP, CARGO_TRACKING_ADDITIONAL_DATA CTAD " _
            & "WHERE CT.PALLET_ID ='" & txtPltNum.Text & "'" _
            & "AND CT.COMMODITY_CODE = COMP.COMMODITY_CODE(+) AND CT.RECEIVER_ID = CUSP.CUSTOMER_ID(+) AND CT.ARRIVAL_NUM = TO_CHAR(VP.LR_NUM(+)) " _
            & "AND CT.PALLET_ID = CTAD.PALLET_ID AND CT.RECEIVER_ID = CTAD.RECEIVER_ID AND CT.ARRIVAL_NUM = CTAD.ARRIVAL_NUM "
        Set dsCargo_Tracking_Global = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
        If OraDatabase.lastservererr <> 0 Then ' Oracle flub.  Cancel function.
            MsgBox "Error Retrieving Data from database; please contact TS"
            Exit Sub
        ElseIf dsCargo_Tracking_Global.recordcount = 0 Then ' Pallet doesn't exist.  Cancel function.
            MsgBox "No Pallet matching that Barcode in Database"
            Exit Sub
        ElseIf dsCargo_Tracking_Global.recordcount = 1 Then ' Only 1 record, blank out the "next/prev" buttons
            CMDNext.Enabled = False
            CMDPrevoius.Enabled = False
        Else ' multiple pallets, enable "next" button
            CMDNext.Enabled = True
            CMDPrevoius.Enabled = False
        End If
    ElseIf sAction = "next" Then ' multiple pallets with same Barcode; "Next" button pressed.
        CMDPrevoius.Enabled = True
        dsCargo_Tracking_Global.MoveNext ' move to next pallet
       
        dsCargo_Tracking_Global.MoveNext ' check if there is another record after this; if not, disable NEXT button
        If dsCargo_Tracking_Global.EOF Then CMDNext.Enabled = False
        dsCargo_Tracking_Global.MovePrevious
    ElseIf sAction = "prev" Then ' multiple pallets with same Barcode; "Prev" button pressed.
        CMDNext.Enabled = True
        dsCargo_Tracking_Global.MovePrevious ' move to previous pallet
        
        dsCargo_Tracking_Global.MovePrevious ' check if there is another record before this; if not, disable PREV button
        If dsCargo_Tracking_Global.BOF Then CMDPrevoius.Enabled = False
        dsCargo_Tracking_Global.MoveNext
'    ElseIf sAction = "current" Then ' Refresh screen for current pallet.  Useful if we just hit "save", for example.
        ' do nothing.  we are on the correct pallet-record.
    End If
        
    Call cmdClear_Click ' blank out the screen in preparation of pallet data
        
    ' after the above logic is executed, we are now sitting on the DB record for the desired pallet.  Populate CT (aka top-half-of-screen) fields...
    txtVesselNo = dsCargo_Tracking_Global.fields("ARRIVAL_NUM").Value
    If Not IsNull(dsCargo_Tracking_Global.fields("VESSEL_NAME").Value) Then
       txtVessel = dsCargo_Tracking_Global.fields("VESSEL_NAME").Value
    End If
    txtCommCode = dsCargo_Tracking_Global.fields("COMMODITY_CODE").Value
    txtComm = dsCargo_Tracking_Global.fields("THE_COMM").Value
    txtQtyRecvd = dsCargo_Tracking_Global.fields("QTY_RECEIVED").Value
    txtOwnerId = dsCargo_Tracking_Global.fields("RECEIVER_ID").Value
    txtOwner = dsCargo_Tracking_Global.fields("THE_CUST").Value
    txtQtyInHouse = dsCargo_Tracking_Global.fields("QTY_IN_HOUSE").Value
    If Not IsNull(dsCargo_Tracking_Global.fields("WAREHOUSE_LOCATION").Value) Then
        txtWHSloc = dsCargo_Tracking_Global.fields("WAREHOUSE_LOCATION").Value
    End If
    If Not IsNull(dsCargo_Tracking_Global.fields("ACTUAL_LOCATION").Value) Then
        txtACTloc = dsCargo_Tracking_Global.fields("ACTUAL_LOCATION").Value
    End If
    If Not IsNull(dsCargo_Tracking_Global.fields("WEIGHT").Value) Then
        txtWT = dsCargo_Tracking_Global.fields("WEIGHT").Value
    End If
    If Not IsNull(dsCargo_Tracking_Global.fields("WEIGHT_UNIT").Value) Then
        txtWtUnt = dsCargo_Tracking_Global.fields("WEIGHT_UNIT").Value
    End If
    If Not IsNull(dsCargo_Tracking_Global.fields("REC_DATE").Value) Then
        txtDtRecd = dsCargo_Tracking_Global.fields("REC_DATE").Value
        cmdUnReceive.Enabled = True
    Else
        cmdUnReceive.Enabled = False
    End If
    If Not IsNull(dsCargo_Tracking_Global.fields("REC_TIME").Value) Then
        txtTmRecd = dsCargo_Tracking_Global.fields("REC_TIME").Value
    End If
    If Not IsNull(dsCargo_Tracking_Global.fields("CARG_DESC").Value) Then
        txtCrgDes = dsCargo_Tracking_Global.fields("CARG_DESC").Value
    End If
    If Not IsNull(dsCargo_Tracking_Global.fields("SHIP_TIME").Value) Then
        txtTmShipped = dsCargo_Tracking_Global.fields("SHIP_TIME").Value
    End If
    If Not IsNull(dsCargo_Tracking_Global.fields("SHIP_DATE").Value) Then
        txtDtShipped = dsCargo_Tracking_Global.fields("SHIP_DATE").Value
    End If
    lblQtyDmg.Caption = dsCargo_Tracking_Global.fields("THE_DMG").Value
    lblQtyExpc.Caption = dsCargo_Tracking_Global.fields("QTY_EXPECTED").Value
    
    short_term_loopcounter = 0
    cmbRecType.ListIndex = 0
    For short_term_loopcounter = 0 To cmbRecType.ListCount - 1
        If cmbRecType.List(short_term_loopcounter) = dsCargo_Tracking_Global.fields("RECEIVING_TYPE").Value Then
            cmbRecType.ListIndex = short_term_loopcounter
        End If
    Next short_term_loopcounter
    
    sCommType = dsCargo_Tracking_Global.fields("COMMODITY_TYPE").Value
    
    ' make all CT fields Black text
    txtVesselNo.ForeColor = vbBlack
    txtVessel.ForeColor = vbBlack
    txtCommCode.ForeColor = vbBlack
    txtComm.ForeColor = vbBlack
    txtQtyRecvd.ForeColor = vbBlack
    txtOwnerId.ForeColor = vbBlack
    txtOwner.ForeColor = vbBlack
    txtQtyInHouse.ForeColor = vbBlack
    txtDtRecd.ForeColor = vbBlack
    txtTmRecd.ForeColor = vbBlack
    txtCrgDes.ForeColor = vbBlack
    cmbRecType.ForeColor = vbBlack
    txtWT.ForeColor = vbBlack
    txtWtUnt.ForeColor = vbBlack
    txtTmShipped.ForeColor = vbBlack
    txtDtShipped.ForeColor = vbBlack
    txtWHSloc.ForeColor = vbBlack
    txtACTloc.ForeColor = vbBlack
        
    ' set "current" variables for this pallet to compare later for changes.
    sCurVes = txtVesselNo.Text
    iCurComm = txtCommCode.Text
    iCurQtyRec = txtQtyRecvd.Text
    iCurQtyInHouse = txtQtyInHouse.Text
    iCurOwner = txtOwnerId.Text
    If Not IsNull(txtWT.Text) And txtWT.Text <> "" Then
        lCurWt = txtWT.Text
    Else
        lCurWt = 0
    End If
    If Not IsNull(txtWtUnt.Text) And txtWtUnt.Text <> "" Then
        sCurWtUnt = txtWtUnt.Text
    Else
        sCurWtUnt = ""
    End If
    sCurDateRec = txtDtRecd.Text
    sCurTimeRec = txtTmRecd.Text
    sCurDateShip = txtDtShipped.Text
    sCurTimeShip = txtTmShipped.Text
    sCurCrgDes = txtCrgDes.Text
    sCurWHSloc = txtWHSloc.Text
    sCurACTloc = txtACTloc.Text
    sCurRecType = cmbRecType.List(cmbRecType.ListIndex)
    
    ' Proceed to determine receive info (if pallet is received)
' NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), '') LOGIN_ID,    AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)  , EMPLOYEE PERS
    sqlstmt = "SELECT  NVL(ACTIVITY_DESCRIPTION, 'NONE') THE_DESC, SERVICE_NAME " _
            & "FROM CARGO_ACTIVITY CA, SERVICE_CATEGORY SC " _
            & "WHERE CA.PALLET_ID = '" & txtPltNum.Text & "' AND CA.ARRIVAL_NUM = '" & txtVesselNo.Text & "' AND CA.CUSTOMER_ID = " & txtOwnerId.Text & " " _
            & "AND CA.ACTIVITY_NUM = '1' " _
            & "AND CA.SERVICE_CODE = SC.SERVICE_CODE"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If dsSHORT_TERM_DATA.recordcount > 0 Then
'        lblRecBy.Caption = dsSHORT_TERM_DATA.Fields("LOGIN_ID").Value
        lblRecType.Caption = dsSHORT_TERM_DATA.fields("SERVICE_NAME").Value
        lblActDesc.Caption = dsSHORT_TERM_DATA.fields("THE_DESC").Value
    Else
'        lblRecBy.Caption = ""
        lblRecType.Caption = ""
        lblActDesc.Caption = ""
    End If
    
    lblRecBy.Caption = get_checker_name(txtPltNum.Text, CStr(iCurOwner), sCurVes, "1")
        
    ' apply proper descriptions and colors
    Call cmbRecType_LostFocus
    Call txtCommCode_LostFocus
    
        
        
        
    ' last step:  populate bottom grid with pallet history
',NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), '') Login_ID     TO_CHAR(CA.ACTIVITY_ID)=SUBSTR(PERS.EMPLOYEE_ID, -4)  and       EMPLOYEE PERS
    sqlstmt = "Select CA.*,SC.SERVICE_NAME FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC" _
            & "  WHERE CA.PALLET_ID='" & Trim$(sPlt_id) & "' AND CA.SERVICE_CODE=SC.SERVICE_CODE AND" _
            & "  CUSTOMER_ID='" & Trim(txtOwnerId) & "' and" _
            & " CA.ARRIVAL_NUM='" & dsCargo_Tracking_Global.fields("ARRIVAL_NUM").Value & "' " _
            & " ORDER BY CA.ACTIVITY_NUM"
      
    Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
    GrdPlt.RemoveAll
    
    If OraDatabase.lastservererr = 0 And dsCARGO_ACTIVITY.recordcount > 0 Then
       With dsCARGO_ACTIVITY
          For iRec = 1 To .recordcount
             If .fields("ACTIVITY_NUM").Value <> 1 Then
                'Trim("" & .Fields("LOGIN_ID").Value)
                GrdPlt.AddItem Trim("" & Format(.fields("DATE_OF_ACTIVITY").Value, "MM/DD/YYYY")) + _
                               Chr$(9) + Trim("" & Format(.fields("DATE_OF_ACTIVITY").Value, "HH:NN:SS")) + _
                               Chr$(9) + Trim("" & .fields("SERVICE_CODE").Value & " - " & .fields("SERVICE_NAME").Value) + _
                               Chr$(9) + get_checker_name(txtPltNum.Text, CStr(iCurOwner), sCurVes, .fields("ACTIVITY_NUM").Value) + _
                               Chr$(9) + Trim("" & .fields("ORDER_NUM").Value) + _
                               Chr$(9) + Trim("" & .fields("CUSTOMER_ID").Value) + _
                               Chr$(9) + Trim("" & .fields("QTY_CHANGE").Value) + _
                               Chr$(9) + .fields("ACTIVITY_NUM").Value + _
                               Chr$(9) + .fields("SERVICE_CODE").Value + _
                               Chr$(9) + Trim("" & .fields("ACTIVITY_DESCRIPTION").Value) + _
                               Chr$(9) + Trim("" & .fields("ACTIVITY_BILLED").Value) + _
                               Chr$(9) + Trim("" & .fields("ARRIVAL_NUM").Value) + _
                               Chr$(9) + Trim("" & .fields("QTY_LEFT").Value) + _
                               Chr$(9) + Trim("" & .fields("TO_MISCBILL").Value)
                GrdPlt.Refresh
             End If
             .MoveNext
          Next iRec
       End With
    Else
       If OraDatabase.lastservererr <> 0 Then
          MsgBox OraDatabase.LastServerErrText, vbInformation, sMsg
          OraDatabase.LastServerErrReset
          Exit Sub
       End If
       
       MsgBox "No Activity found for Pallet No. " & txtPltNum, vbInformation, sMsg
    End If
    
End Sub











Private Sub cmdCommCode_Click()
    Dim iPos As Integer
    load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Commodity List"
    frmPV.lstPV.Clear
    
    sqlstmt = "SELECT * FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE"
    Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
    If OraDatabase.lastservererr = 0 And dsCOMMODITY_PROFILE.recordcount > 0 Then
        For iRec = 1 To dsCOMMODITY_PROFILE.recordcount
            frmPV.lstPV.AddItem dsCOMMODITY_PROFILE.fields("COMMODITY_CODE").Value & " : " & dsCOMMODITY_PROFILE.fields("COMMODITY_NAME").Value
            dsCOMMODITY_PROFILE.MoveNext
        Next iRec
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCommCode.Text = Left$(gsPVItem, iPos - 1)
            txtComm = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtCommCode.SetFocus
End Sub

Private Sub cmdDelete_Click()
    GrdPlt.DeleteSelected
End Sub

Private Sub cmdDeleteCT_Click()
' THIS FUNCTION IS VERY BAD
' It deletes the cargo_Tracking record, but doesn't bother to check (or deal with) any cargo_activiy records.
' Inventory has an alternate method theyuse to delete pallets anyway, so I am switching
' the visible" attribute of this button to false.
   
   
   
'   If txtVesselNo = -1 Then
'      sqlstmt = " DELETE FROM CARGO_TRACKING WHERE PALLET_ID='" & Trim(txtPltNum) & "' AND ARRIVAL_NUM='" & Trim(GrdPlt.Columns(4).Value) & "'" _
'              & " AND RECEIVER_ID='" & Trim(txtOwnerId) & "'"
'   ElseIf txtVesselNo <> -1 Then
'      sqlstmt = " DELETE FROM CARGO_TRACKING WHERE PALLET_ID='" & Trim(txtPltNum) & "' AND ARRIVAL_NUM='" & Trim(txtVesselNo) & "'" _
'              & " AND RECEIVER_ID='" & Trim(txtOwnerId) & "'"
'   End If

'    sqlstmt = " DELETE FROM CARGO_TRACKING WHERE PALLET_ID='" & Trim(txtPltNum) & "' AND ARRIVAL_NUM='" & Trim(txtVesselNo) & "'" _
'              & " AND RECEIVER_ID='" & Trim(txtOwnerId) & "'"
'
'   OraSession.begintrans
'
'   OraDatabase.ExecuteSQL sqlstmt
   
   If OraDatabase.lastservererr = 0 Then
       OraSession.committrans
       txtPltNum_LostFocus
   Else
       OraSession.rollback
       MsgBox "ERROR WHILE DELETING THE RECORDS !.", vbCritical + vbInformation, sMsg
    End If
    
    Call txtPltNum_LostFocus
    
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub

Private Sub cmdOrdDtls_Click()
'Opens a new screen to show the details of a particular order number
    If GrdPlt.Rows <> 0 Then
        gsOrderNum = GrdPlt.Columns(4).Value
        giCustId = GrdPlt.Columns(5).Value
    End If
    Unload Me
    frmOrderNum.Show
End Sub


Private Sub cmdPrint_Click()  'Prints the screen inforamtion
    Dim iLine As Long
    Dim sql1 As String
    Dim dsTemp1 As Object
    
    Dim Ctl As Control
    For Each Ctl In frmPltCorrection
        If (TypeOf Ctl Is TextBox) Or (TypeOf Ctl Is ComboBox) Then
            If Ctl.ForeColor = &HFF0000 Then
                MsgBox "Some Pallet data is awaiting changes.  Will not print until changes are reverted or committed."
                Exit Sub
            End If
        End If
    Next Ctl

    Printer.FontSize = 10
    Printer.Orientation = 2
    
    For iLine = 1 To 3
        Printer.Print
    Next iLine
    Printer.FontBold = True
    Printer.FontUnderline = True
    Printer.Print Tab(75); "PALLET DETAIL"
    Printer.FontUnderline = False
    Printer.Print
    Printer.Print
    Printer.FontBold = False
    
    Printer.Print Tab(5); "BARCODE"; Tab(27); ":"; Tab(33); txtPltNum
    Printer.Print
    Printer.Print Tab(5); "VESSEL"; Tab(27); ":"; Tab(33); txtVesselNo & " - " & txtVessel; Tab(100); "RECEIVE TYPE"; Tab(125); ":"; Tab(130); txtRecType
    Printer.Print
    Printer.Print Tab(5); "COMMODITY"; Tab(27); ":"; Tab(33); txtCommCode & " - " & txtComm
    Printer.Print
    Printer.Print Tab(5); "OWNER"; Tab(27); ":"; Tab(33); txtOwner.Text; Tab(75); "EXPECTED QTY :"; Tab(95); lblQtyExpc
    Printer.Print
    Printer.Print Tab(5); "QTY RECEIVED"; Tab(27); ":"; Tab(33); txtQtyRecvd; Tab(55); _
                          "QTY INHOUSE"; Tab(75); ":"; Tab(80); txtQtyInHouse; Tab(110); "QTY DMG"; Tab(125); ":"; Tab(130); lblQtyDmg.Caption
    Printer.Print
    Printer.Print Tab(5); "DATE RECEIVED"; Tab(27); ":"; Tab(33); txtDtRecd; Tab(55); _
                          "TIME"; Tab(75); ":"; Tab(80); txtTmRecd
    
    Printer.Print
        sqlstmt = "SELECT VARIETY FROM CARGO_TRACKING WHERE "
        sqlstmt = sqlstmt & " RECEIVER_ID = " & Val(txtOwner.Text)
        sqlstmt = sqlstmt & " AND ARRIVAL_NUM  = '" & txtVesselNo & "'"
        sqlstmt = sqlstmt & " AND PALLET_ID = '" & txtPltNum.Text & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'        If dsSHORT_TERM_DATA.recordcount > 0 Then
'            If Not IsNull(dsSHORT_TERM_DATA.Fields("LOGIN_ID")) Then
                Printer.Print Tab(5); "VARIETY"; Tab(27); ":"; Tab(33); dsSHORT_TERM_DATA.fields("VARIETY").Value & " " & txtCrgDes; Tab(100); "RECEIVED BY: " & get_checker_name(txtPltNum.Text, txtOwnerId.Text, sCurVes, "1") 'dsSHORT_TERM_DATA.Fields("LOGIN_ID").Value
'            Else
'                Printer.Print Tab(5); "VARIETY"; Tab(27); ":"; Tab(33); txtCrgDes
'            End If
'        Else
'            Printer.Print Tab(5); "VARIETY"; Tab(27); ":"; Tab(33); txtCrgDes
'        End If
    
    Printer.Print
    
    Printer.Print Tab(2); "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------" _
                        ; "------------------------------------------------------------"
    Printer.FontBold = True
    Printer.Print Tab(2); "DATE"; Tab(18); "TIME"; Tab(30); "ACTION"; Tab(40); _
                  "ORDER NO."; Tab(65); "CUSTOMER"; Tab(90); "QUANTITY"; _
                  Tab(103); "CHECKER "; Tab(120); "QTY LEFT"; Tab(135); "DESCRIPTION"
   Printer.FontBold = False
   Printer.Print Tab(2); "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------" _
                       ; "------------------------------------------------------------"
    
    If lblQC.Caption <> "" Then
        Printer.FontBold = True
        Printer.Print Tab(73); "***QC***"
        Printer.FontBold = False
    End If
    
    If GrdPlt.Rows = 0 Then
      Printer.FontBold = True
      Printer.Print Tab(70); "NO ACTIVITY"
      Printer.FontBold = False
    End If
   
    GrdPlt.MoveFirst
    For iRec = 0 To GrdPlt.Rows - 1
        
        sql1 = "Select CUSTOMER_NAME  from customer_profile where customer_id = " _
              & "'" & GrdPlt.Columns(5).Value & "' "
        
        Set dsTemp1 = OraDatabase.dbcreatedynaset(sql1, 0&)
               
        If OraDatabase.lastservererr = 0 And dsTemp1.recordcount > 0 Then
            Printer.Print Tab(2); Format(GrdPlt.Columns(0).Value, "MM/DD/YY"); _
                          Tab(18); Format(GrdPlt.Columns(1).Value, "HH:MM:SS"); _
                          Tab(32); GrdPlt.Columns(2).Value; _
                          Tab(44); GrdPlt.Columns(4).Value; _
                          Tab(64); Mid(dsTemp1.fields("CUSTOMER_NAME").Value, 1, 24); _
                          Tab(103); GrdPlt.Columns(6).Value; _
                          Tab(113); GrdPlt.Columns(3).Value; _
                          Tab(132); GrdPlt.Columns(12).Value; _
                          Tab(145); GrdPlt.Columns(9).Value
            GrdPlt.MoveNext
        End If
        
    Next iRec
    Printer.Print Tab(2); "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------" _
                                       ; "------------------------------------------------------------"
    Printer.EndDoc
End Sub

Private Function ValidateGridRow(RowNum As Integer, ServDate As String, ServTime As String, qty As Integer, ServCode As Integer, qty_left As Integer) As Boolean
    Dim Error_row As Integer
    Error_row = RowNum + 1
    ' make sure date and time are a valid format
    If Not IsDate(Format(ServDate, "MM/DD/YY")) Then
        MsgBox "Activity Line " & Error_row & " Date not in a valid format.  Cancelling Update"
        ValidateGridRow = False
    End If
    If Not IsDate(Format(ServTime, "HH:MM:SS")) Then
        MsgBox "Activity Line " & Error_row & " Time not in a valid format.  Cancelling Update"
        ValidateGridRow = False
    End If
    
    ' make sure the QTY_CHANGED isn't higher than the original
    If Abs(qty) > txtQtyRecvd.Text Then
        MsgBox "Activity Line " & Error_row & " has a quantity change value greater than the listed QTY_RECEIVED.  Cancelling Update"
        ValidateGridRow = False
        Exit Function
    End If
    
    ' make sure QTY_left isn't too high or low
    If qty_left > txtQtyRecvd.Text Then
        MsgBox "Activity Line " & Error_row & " has a QTY_LEFT change value greater than the listed QTY_RECEIVED.  Cancelling Update"
        ValidateGridRow = False
        Exit Function
    End If
    If qty_left < 0 Then
        MsgBox "Activity Line " & Error_row & " has a QTY_LEFT change value Less than 0.  Cancelling Update"
        ValidateGridRow = False
        Exit Function
    End If
    
    ' activity types 7, 9, and 13 expect negative QTY changes; all others expect positive (who came up with THAT idea...)
    If (ServCode = 7 Or ServCode = 9 Or ServCode = 13) And qty >= 0 Then
        MsgBox "Activity Line " & Error_row & " (Service Code " & ServCode & ") Expects a Negative quantity.  Cancelling Update"
        ValidateGridRow = False
        Exit Function
    End If
    If (ServCode <> 7 And ServCode <> 9 And ServCode <> 13) And qty < 0 Then
        MsgBox "Activity Line " & Error_row & " (Service Code " & ServCode & ") Expects a Positive quantity.  Cancelling Update"
        ValidateGridRow = False
        Exit Function
    End If
    
    ValidateGridRow = True
    
End Function

Private Sub cmdSave_Click()
    Dim sDtOfActivity As String
    Dim i As Integer
    Dim iRow As Integer
    Dim iError As Boolean
    Dim dsPers As Object
    Dim lRecCount  As Long
    Dim ActivityInsertNum As Integer
    Dim sClemValues() As String
    
    On Error GoTo Err_Save
    
    If txtPltNum = "" Then
        MsgBox "Please Enter a Valid Pallet Number.", vbInformation, sMsg
        Exit Sub
    End If
    
    With GrdPlt
        If .Rows <> 0 Then
            .MoveFirst
            For iRow = 0 To .Rows - 1
                    ' 0 = date, 1 = time, 6 = qty, 8 = service code, 12 = qty left
                If ValidateGridRow(iRow, .Columns(0).Value, .Columns(1).Value, .Columns(6).Value, .Columns(8).Value, .Columns(12).Value) = False Then
                    Exit Sub
                End If
            Next iRow
        End If
    End With
        
    'Initials are required to commit the changes
    If txtIntls = "" Then
        MsgBox "please Enter Your Initials !.", vbInformation + vbCritical, sMsg
        Exit Sub
    End If
    
    If txtDtRecd.Text = "" Or txtTmRecd.Text = "" Then
        MsgBox "Date / Time received cannot be empty.  If you wish to un-receive a pallet, please use the Unreceive button."
        Exit Sub
    End If
    
    If cmbRecType.ListIndex = 0 Then
        MsgBox "Please choose a valid Receive option"
        Exit Sub
    End If
    
    If sCommType = "CLEMENTINES" And txtCrgDes.ForeColor = vbBlue Then
        ' they are trying to change the desc of a clementine... which has other fields worth of data in it.  needs to validate.
        sClemValues = Split(txtCrgDes.Text, "-", 3)
        If Len(sClemValues(0)) <> 4 Or (Not IsNumeric(sClemValues(0))) Or Len(sClemValues(1)) <> 3 Or (Not IsNumeric(sClemValues(1))) Or Len(sClemValues(2)) <> 3 Or (Not IsNumeric(sClemValues(2))) Then
            MsgBox "For Clementines, a changed cargo description must be of the format NNNN-XXX-YYY" & vbCrLf & "Where NNNN is the packinghouse, XXX is the cargo Size, and YYY is the received case count" & vbCrLf & vbCrLf & "For Example, 7808-024-360"
            Exit Sub
        End If
    End If
    
    If FinalValidate = False Then
        Exit Sub
    End If
'    iError = False
    
    
    ' start a dedicated transaction; that way if we run into an issue, we can roll it back.
    OraSession.begintrans
    
    
    ' first part:  save the changes to the top box data.
    '' first first part:  any changes to vessel num or customer id need to happen FIRST, so that we can maintain consistency with CARGO_ACTIVITY
    If txtVesselNo.ForeColor = &HFF0000 Or txtOwnerId.ForeColor = &HFF0000 Then
        ' attempting to change vessel
        sqlstmt = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND " _
                & "RECEIVER_ID = '" & txtOwnerId.Text & "' AND ARRIVAL_NUM = '" & txtVesselNo.Text & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        If dsSHORT_TERM_DATA.fields("THE_COUNT").Value > 0 Then
            MsgBox "The new customer/vessel combination you are trying to change this pallet to is taken.  Cannot Commit changes."
            'OraSession.rollback
            Exit Sub
        Else ' can do the change; proceed with both CARGO_TRACKING and CARGO_ACTIVITY
            sqlstmt = "UPDATE CARGO_ACTIVITY SET ARRIVAL_NUM = '" & txtVesselNo.Text & "', CUSTOMER_ID = '" & txtOwnerId.Text & "' " _
                    & "WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND CUSTOMER_ID = '" & iCurOwner & "' AND ARRIVAL_NUM = '" & sCurVes & "'"
            OraDatabase.ExecuteSQL sqlstmt 'update CA with all data.
                        
            sqlstmt = "UPDATE CARGO_TRACKING SET ARRIVAL_NUM = '" & txtVesselNo.Text & "', RECEIVER_ID = '" & txtOwnerId.Text & "' " _
                    & "WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND RECEIVER_ID = '" & iCurOwner & "' AND ARRIVAL_NUM = '" & sCurVes & "'"
            OraDatabase.ExecuteSQL sqlstmt ' change CT
        End If
    End If


    'update the rest of the CT data
    If txtCommCode.ForeColor = &HFF0000 Or txtDtRecd.ForeColor = &HFF0000 Or txtTmRecd.ForeColor = &HFF0000 Or txtCrgDes.ForeColor = &HFF0000 Or txtQtyRecvd.ForeColor = &HFF0000 Or txtQtyInHouse.ForeColor = &HFF0000 Or cmbRecType.ForeColor = &HFF0000 Or lblRecType.ForeColor = &HFF0000 Or txtDtShipped.ForeColor = &HFF0000 Or txtTmShipped.ForeColor = &HFF0000 Or txtWHSloc.ForeColor = &HFF0000 Or txtACTloc.ForeColor = &HFF0000 Then
        sqlstmt = "UPDATE CARGO_TRACKING SET QTY_DAMAGED = QTY_DAMAGED" ' a throw-away statement, just so that all other statements don't need to see if tey are the first in line
        If txtCommCode.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", COMMODITY_CODE = '" & txtCommCode.Text & "'"
        End If
        If txtDtRecd.ForeColor = &HFF0000 Or txtTmRecd.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", DATE_RECEIVED = TO_DATE('" & txtDtRecd.Text & " " & txtTmRecd.Text & "', 'MM/DD/YYYY HH24:MI:SS')"
        End If
        If sCommType = "CLEMENTINES" Then
            If txtDtShipped.ForeColor = &HFF0000 Or txtTmShipped.ForeColor = &HFF0000 Then
                If txtDtShipped.Text = "" And txtTmShipped.Text = "" Then
                    sqlstmt = sqlstmt & ", MARK = 'AT POW'"
                Else
                    sqlstmt = sqlstmt & ", MARK = 'SHIPPED'"
                End If
            End If
        End If
        If txtCrgDes.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", CARGO_DESCRIPTION = '" & txtCrgDes.Text & "'"
            ' and if this is clementines...
            If sCommType = "CLEMENTINES" Then
                sqlstmt = sqlstmt & ", EXPORTER_CODE = '" & sClemValues(0) & "'"
                sqlstmt = sqlstmt & ", CARGO_SIZE = '" & sClemValues(1) & "'"
            End If
        End If
        If txtQtyRecvd.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", QTY_RECEIVED = '" & txtQtyRecvd.Text & "'"
        End If
        If txtQtyInHouse.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", QTY_IN_HOUSE = '" & txtQtyInHouse.Text & "'"
        End If
        If txtWT.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", WEIGHT = '" & txtWT.Text & "'"
        End If
        If txtWtUnt.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", WEIGHT_UNIT = '" & txtWtUnt.Text & "'"
        End If
        If txtWHSloc.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", WAREHOUSE_LOCATION = '" & txtWHSloc.Text & "'"
        End If
        If cmbRecType.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", RECEIVING_TYPE = '" & cmbRecType.List(cmbRecType.ListIndex) & "'"
        End If

        sqlstmt = sqlstmt & " WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND RECEIVER_ID = '" & txtOwnerId.Text & "' AND ARRIVAL_NUM = '" & txtVesselNo.Text & "'"

        OraDatabase.ExecuteSQL sqlstmt 'update CT with non-primary-key data
    End If
    
    If txtACTloc.ForeColor = &HFF0000 Then
        sqlstmt = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA SET ACTUAL_LOCATION = '" & txtACTloc.Text & "'"
        sqlstmt = sqlstmt & " WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND RECEIVER_ID = '" & txtOwnerId.Text & "' AND ARRIVAL_NUM = '" & txtVesselNo.Text & "'"
        OraDatabase.ExecuteSQL sqlstmt
    End If

    If txtDtRecd.ForeColor = &HFF0000 Or txtTmRecd.ForeColor = &HFF0000 Or txtQtyRecvd.ForeColor = &HFF0000 Or cmbRecType.ForeColor = &HFF0000 Or lblRecType.ForeColor = &HFF0000 Then
        sqlstmt = "UPDATE CARGO_ACTIVITY SET ACTIVITY_NUM = ACTIVITY_NUM" ' a throw-away statement, just so that all other statements don't need to see if tey are the first in line
        If txtDtRecd.ForeColor = &HFF0000 Or txtTmRecd.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", DATE_OF_ACTIVITY = TO_DATE('" & txtDtRecd.Text & " " & txtTmRecd.Text & "', 'MM/DD/YYYY HH24:MI:SS')"
        End If
        If txtQtyRecvd.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", QTY_CHANGE = '" & txtQtyRecvd.Text & "', QTY_LEFT = '" & txtQtyRecvd.Text & "'"
        End If
        If cmbRecType.ForeColor = &HFF0000 Or lblRecType.ForeColor = &HFF0000 Then
            sqlstmt = sqlstmt & ", SERVICE_CODE = '" & cmbRecType.ItemData(cmbRecType.ListIndex) & "'"
        End If

        sqlstmt = sqlstmt & " WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND CUSTOMER_ID = '" & txtOwnerId.Text & "' AND ARRIVAL_NUM = '" & txtVesselNo.Text & "' AND ACTIVITY_NUM = '1'"
        OraDatabase.ExecuteSQL sqlstmt 'update CA with non-primary-key data
    
        ' note:  there may not be a CA record, if this program is being used to RECEIVE the cargo.
        ' if that is the case, do...
        sqlstmt = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY " _
                & "WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND CUSTOMER_ID = '" & txtOwnerId.Text & "' AND ARRIVAL_NUM = '" & txtVesselNo.Text & "' " _
                & "AND ACTIVITY_NUM = '1' "
        Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        If dsSHORT_TERM_DATA.fields("THE_COUNT").Value <= 0 Then
            ' oops, no receive record.  lets put one in.
            sqlstmt = "INSERT INTO CARGO_ACTIVITY (PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM, QTY_CHANGE, QTY_LEFT, DATE_OF_ACTIVITY, SERVICE_CODE, ACTIVITY_NUM, ACTIVITY_ID) " _
                    & "VALUES ('" & Trim$(txtPltNum.Text) & "', '" & txtOwnerId.Text & "', '" & txtVesselNo.Text & "', " _
                    & "'" & txtQtyRecvd.Text & "', '" & txtQtyRecvd.Text & "', TO_DATE('" & txtDtRecd.Text & " " & txtTmRecd.Text & "', 'MM/DD/YYYY HH24:MI:SS'), " _
                    & "'" & cmbRecType.ItemData(cmbRecType.ListIndex) & "', '1', '513')"
            OraDatabase.ExecuteSQL sqlstmt ' add CA
        End If
    
    End If
    
    ' put ARRIVAL_NUM into ORDER_NUM for trucks or transfers.
    ' done AFTER THE FACT of above sql's, since we don't know till the end if a receive type was changed.
    sqlstmt = "UPDATE CARGO_ACTIVITY SET ORDER_NUM = DECODE(SERVICE_CODE, '8', '" & txtVesselNo.Text & "', '11', '" & txtVesselNo.Text & "', NULL)" _
            & "WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND CUSTOMER_ID = '" & txtOwnerId.Text & "' AND ARRIVAL_NUM = '" & txtVesselNo.Text & "' " _
            & "AND ACTIVITY_NUM = '1' "
    OraDatabase.ExecuteSQL sqlstmt 'update CA if this is trucked in
    
    ' END FIRST PART, TOP BOX COMPLETE




'    Took the table lock out to avoid unnecessary conflicts  -- LFW, 1/14/04
'    For i = 0 To 9
'        OraDatabase.LastServerErrReset
'        sqlstmt = "LOCK TABLE CARGO_TRACKING IN EXCLUSIVE MODE NOWAIT"
'        lRecCount = OraDatabase.ExecuteSQL(sqlstmt)
'        If OraDatabase.lastservererr = 0 Then Exit For
'    Next 'i
'    If OraDatabase.lastservererr <> 0 Then
'        OraDatabase.lastservererr
'        MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Pallet Correction"
'        Exit Sub
'    End If
           
'    If OraDatabase.lastservererr = 0 And dsCargo_Tracking_Global.recordcount > 0 Then
'        dsCargo_Tracking_Global.edit
'            dsCargo_Tracking_Global.fields("COMMODITY_CODE").Value = txtCommCode.Text
'            dsCargo_Tracking_Global.fields("RECEIVER_ID").Value = txtOwnerId.Text
'            dsCargo_Tracking_Global.fields("QTY_RECEIVED").Value = txtQtyRecvd.Text
'            dsCargo_Tracking_Global.fields("QTY_IN_HOUSE").Value = txtQtyInHouse.Text
'            dsCargo_Tracking_Global.fields("DATE_RECEIVED").Value = Format(txtDtRecd.Text & " " & txtTmRecd.Text, "MM/DD/YYYY HH:NN:SS")
'            dsCargo_Tracking_Global.fields("ARRIVAL_NUM").Value = Trim(txtVesselNo)
'            dsCargo_Tracking_Global.fields("CARGO_DESCRIPTION").Value = txtCrgDes
'        dsCargo_Tracking_Global.Update
'    End If
'    If OraDatabase.lastservererr = 0 Then
'        OraSession.committrans
'        dsCargo_Tracking_Global.DbRefresh
'    Else
'        OraSession.rollback
'        MsgBox "ERROR WHILE PROCESSING THE DATA !.", vbCritical + vbInformation, sMsg
'        Exit Sub
'    End If
'
'    ' UPDATE THE ORIGINATING RECORD.  We need to make sure the original recipet stays kosher with the date received.
'    sqlstmt = " UPDATE CARGO_ACTIVITY SET DATE_OF_ACTIVITY = TO_DATE('" & Format(txtDtRecd.Text & " " & txtTmRecd.Text, "MM/DD/YYYY HH:NN:SS") & "', 'MM/DD/YYYY HH24:MI:SS') " & _
'            " WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "'" & _
'              " AND ACTIVITY_NUM = '1' AND CUSTOMER_ID = '" & Trim$(txtOwnerId.Text) & "' AND ARRIVAL_NUM='" & Trim(txtVesselNo) & "'"
'    OraDatabase.ExecuteSQL sqlstmt
'
'
'   OraSession.begintrans
   'Save Grid Data here
    sqlstmt = " DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "'" & _
              " AND ACTIVITY_NUM != '1' " & _
              " AND ARRIVAL_NUM= '" & txtVesselNo.Text & "' AND CUSTOMER_ID = '" & txtOwnerId.Text & "'"
    OraDatabase.ExecuteSQL sqlstmt
    
    
    GrdPlt.MoveFirst
    ActivityInsertNum = 1
    For i = 0 To GrdPlt.Rows - 1
        ActivityInsertNum = ActivityInsertNum + 1
        
        sDtOfActivity = GrdPlt.Columns(0).Value & " " & GrdPlt.Columns(1).Value
                
'        sqlstmt = "SELECT distinct SUBSTR(Employee_ID, -4) THE_EMP from EMPLOYEE where SUBSTR(EMPLOYEE_NAME, 0, 8) ='" & GrdPlt.Columns(3).Value & "'"
'        Set dsPers = OraDatabase.DbCreateDynaset(sqlstmt, 0&)
        
        'dsPers.Fields("THE_EMP").Value
        sqlstmt = "INSERT INTO CARGO_ACTIVITY " _
                & "(ACTIVITY_NUM, SERVICE_CODE, QTY_CHANGE, ACTIVITY_ID, ACTIVITY_DESCRIPTION, ORDER_NUM, CUSTOMER_ID, DATE_OF_ACTIVITY, PALLET_ID, ACTIVITY_BILLED, ARRIVAL_NUM, QTY_LEFT, TO_MISCBILL) VALUES " _
                & "('" & ActivityInsertNum & "', '" & GrdPlt.Columns(8).Value & "', '" & GrdPlt.Columns(6).Value & "', '" & get_checker_DB_value(GrdPlt.Columns(3).Value) & "', " _
                & "'" & GrdPlt.Columns(9).Value & "', '" & GrdPlt.Columns(4).Value & "', '" & txtOwnerId.Text & "', TO_DATE('" & sDtOfActivity & "', 'MM/DD/YYYY HH24:MI:SS'), " _
                & "'" & txtPltNum.Text & "', '" & GrdPlt.Columns(10).Value & "', '" & txtVesselNo.Text & "', '" & GrdPlt.Columns(12).Value & "', '" & GrdPlt.Columns(13).Value & "')"
        OraDatabase.ExecuteSQL sqlstmt
'
'        'Inserting the copy of data in CA_ARCHIVE with initials
'        sqlstmt = "select * from CA_ARCHIVE"
'        Set dsCARGO_ACTIVITY_ARCHIVE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'        If OraDatabase.lastservererr = 0 Then
'            With dsCARGO_ACTIVITY_ARCHIVE
'                .AddNew
'                .Fields("PALLET_ID").Value = txtPltNum.Text
'                .Fields("Comments").Value = txtIntls
'                .Fields("date_of_activity").Value = CDate(Format(sDtOfActivity, "MM/DD/YYYY HH:MM:SS"))
'                .Fields("activity_ID").Value = dsPers.Fields("Employee_Id").Value
'                .Fields("order_num").Value = GrdPlt.Columns(4).Value
'                .Fields("customer_id").Value = GrdPlt.Columns(5).Value
'                .Fields("qty_change").Value = GrdPlt.Columns(6).Value
'                .Fields("activity_num").Value = GrdPlt.Columns(7).Value
'                .Fields("service_code").Value = GrdPlt.Columns(8).Value
'                .Fields("activity_description").Value = GrdPlt.Columns(9).Value
'                .Fields("activity_billed").Value = GrdPlt.Columns(10).Value
'                .Fields("arrival_num").Value = GrdPlt.Columns(11).Value
'                .Update
'           End With
'        End If
        
'        If GrdPlt.Columns(8).Value = 11 Then
'            ' if a transfer is being altered, then we need to update the transfer-to line of the previous owner as well
'            ' 05/19/2010:  NEGATIVE; this will be to OPS to correct outbound-transfer lines.
'        End If

               
'        Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'        With dsCARGO_ACTIVITY
'            .edit
'            .Fields("DATE_OF_ACTIVITY").Value = CDate(Format(sDtOfActivity, "MM/DD/YYYY HH:MM:SS"))
'            .Fields("ARRIVAL_NUM").Value = Trim(txtVesselNo)
'            .Fields("CUSTOMER_ID").Value = GrdPlt.Columns(5).Value
'            .Fields("QTY_CHANGE").Value = GrdPlt.Columns(6).Value
'            .Fields("ACTIVITY_ID").Value = dsPers.Fields("Employee_Id").Value
'            .Fields("SERVICE_CODE").Value = GrdPlt.Columns(8).Value
'            .Fields("ACTIVITY_DESCRIPTION").Value = GrdPlt.Columns(9).Value
''            If GrdPlt.Columns(8).Value = 8 Then
''                .fields("ORDER_NUM").Value = Trim(txtVesselNo)
''            Else
'                .Fields("ORDER_NUM").Value = GrdPlt.Columns(4).Value
''            End If
'            .Fields("QTY_LEFT").Value = GrdPlt.Columns(12).Value
'          .Update
'        End With
        
'        If OraDatabase.lastservererr <> 0 Then
'            iError = True
'        End If
        GrdPlt.MoveNext
    Next i
    
'    If OraDatabase.lastservererr <> 0 Then
'        iError = True
'    End If
    
'    sLRNUM = txtVesselNo
    
        OraSession.committrans
        ' with the changes committed, the "set ship time" trigger on CARGO_TRACKING_ADDITIONAL_DATA will have fired.
        ' that means we can now piggyback the current clementine version to its datasource as well.
        OraSession.begintrans
        If txtDtShipped.ForeColor = &HFF0000 Or txtTmShipped.ForeColor = &HFF0000 Then
            If txtDtShipped.Text = "" Or txtTmShipped.Text = "" Then
                sqlstmt = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA CTAD SET SHIPOUTTIME = NULL WHERE" _
                        & " CTAD.PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND " _
                        & " CTAD.RECEIVER_ID = '" & txtOwnerId.Text & "' AND " _
                        & " CTAD.ARRIVAL_NUM = '" & txtVesselNo.Text & "'"
                OraDatabase.ExecuteSQL sqlstmt
            Else
                sqlstmt = "UPDATE CARGO_TRACKING_ADDITIONAL_DATA CTAD SET SHIPOUTTIME = TO_DATE('" & txtDtShipped.Text & " " & txtTmShipped.Text & "', 'MM/DD/YYYY HH24:MI:SS') WHERE" _
                        & " CTAD.PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND " _
                        & " CTAD.RECEIVER_ID = '" & txtOwnerId.Text & "' AND " _
                        & " CTAD.ARRIVAL_NUM = '" & txtVesselNo.Text & "'"
                OraDatabase.ExecuteSQL sqlstmt
            End If
        End If
        sqlstmt = "UPDATE CARGO_TRACKING_EXT CTE SET SHIPOUTTIME = (SELECT SHIPOUTTIME FROM CARGO_TRACKING_ADDITIONAL_DATA CTAD WHERE " _
                & " CTE.PALLET_ID = CTAD.PALLET_ID AND " _
                & " CTE.RECEIVER_ID = CTAD.RECEIVER_ID AND " _
                & " CTE.ARRIVAL_NUM = CTAD.ARRIVAL_NUM) WHERE " _
                & " CTE.PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND " _
                & " CTE.RECEIVER_ID = '" & txtOwnerId.Text & "' AND " _
                & " CTE.ARRIVAL_NUM = '" & txtVesselNo.Text & "'"
        OraDatabase.ExecuteSQL sqlstmt
        OraSession.committrans
        
        
        
        MsgBox "CHANGES ARE MADE SUCCESSFULLY.", vbInformation, sMsg
'        dsCargo_Tracking_Global.DbRefresh
'    Else
'        OraSession.rollback
'        MsgBox "ERROR WHILE PROCESSING THE DATA !.", vbCritical + vbInformation, sMsg
'    End If
    
    
    sPwd = " "
    
'    txtQtyInHouse.Locked = True
'    txtPltNum_LostFocus
'
    
    
    Call SHOW_RECORDS(Trim$(txtPltNum), "first")
    Exit Sub
    
    
Err_Save:
    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation
    OraSession.rollback
    End

End Sub

Private Sub cmdTrans_Click()
   If GrdPlt.Rows <> 0 And GrdPlt.Columns(8).Value = 11 Then
        gsLotNum = txtPltNum
    End If
    Unload Me
    frmTransfer.Show
End Sub

Private Sub cmdUnReceive_Click()
    Dim receive_type As Integer

    sqlstmt = "SELECT NVL(COUNT(*), 0) THE_COUNT FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & txtPltNum.Text & "' AND CUSTOMER_ID = '" & iCurOwner _
            & "' AND ARRIVAL_NUM = '" & sCurVes & "' AND (ACTIVITY_NUM != 1 OR SERVICE_CODE NOT IN ('1', '8', '11', '17'))"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If dsSHORT_TERM_DATA.fields("THE_COUNT").Value > 0 Then
        MsgBox "This pallet already has outgoing activity on it.  Cannot Unreceive"
        Exit Sub
    End If
    
    sqlstmt = "SELECT SERVICE_CODE FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & txtPltNum.Text & "' AND CUSTOMER_ID = '" & txtOwnerId.Text _
            & "' AND ARRIVAL_NUM = '" & txtVesselNo & "' AND ACTIVITY_NUM = 1"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    receive_type = dsSHORT_TERM_DATA.fields("SERVICE_CODE").Value
    
    ' ok, if we get here, pallet can be "unreceived", begin procedure.
    sqlstmt = "DELETE FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & txtPltNum.Text & "' AND CUSTOMER_ID = '" & txtOwnerId.Text _
            & "' AND ARRIVAL_NUM = '" & txtVesselNo & "'"
    OraDatabase.ExecuteSQL sqlstmt
    


    ' edit:  adam walter, feb 2010.  As this button needs to be able to unreceive multiple types of cargo,
    ' and each has different "unreceivig needs"...
    
    If receive_type = 1 Then ' this was from a ship
        sqlstmt = "UPDATE CARGO_TRACKING SET DATE_RECEIVED = NULL, QTY_DAMAGED = NULL, QTY_IN_HOUSE = QTY_RECEIVED" _
                & " WHERE PALLET_ID = '" & txtPltNum.Text & "' AND RECEIVER_ID = '" & txtOwnerId.Text _
                & "' AND ARRIVAL_NUM = '" & txtVesselNo & "'"
        OraDatabase.ExecuteSQL sqlstmt
    
    ElseIf receive_type = 8 Then ' this was from a truck
        sqlstmt = "DELETE FROM CARGO_TRACKING " _
                & " WHERE PALLET_ID = '" & txtPltNum.Text & "' AND RECEIVER_ID = '" & txtOwnerId.Text _
                & "' AND ARRIVAL_NUM = '" & txtVesselNo & "'"
        OraDatabase.ExecuteSQL sqlstmt
    
    ElseIf receive_type = 11 Then ' this was from a transfer
        sqlstmt = "DELETE FROM CARGO_TRACKING " _
                & " WHERE PALLET_ID = '" & txtPltNum.Text & "' AND RECEIVER_ID = '" & txtOwnerId.Text _
                & "' AND ARRIVAL_NUM = '" & txtVesselNo & "'"
        OraDatabase.ExecuteSQL sqlstmt
    
    ElseIf receive_type = 17 Then ' this was from an autotransfer
        sqlstmt = "DELETE FROM CARGO_TRACKING " _
                & " WHERE PALLET_ID = '" & txtPltNum.Text & "' AND RECEIVER_ID = '" & txtOwnerId.Text _
                & "' AND ARRIVAL_NUM = '" & txtVesselNo & "'"
        OraDatabase.ExecuteSQL sqlstmt
    
    End If
    
    
    txtDtRecd.Text = ""
    txtTmRecd.Text = ""
    
    MsgBox "Pallet Unreceived"
    
    Call txtPltNum_LostFocus
End Sub

Private Sub cmdVessel_Click()
Dim iPos As Integer

    load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Vessel List"
    frmPV.lstPV.Clear
    
    sqlstmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If OraDatabase.lastservererr = 0 And dsVESSEL_PROFILE.recordcount > 0 Then
        For iRec = 1 To dsVESSEL_PROFILE.recordcount
            frmPV.lstPV.AddItem dsVESSEL_PROFILE.fields("LR_NUM").Value & " : " & dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
            dsVESSEL_PROFILE.MoveNext
        Next iRec
    End If
    
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtVesselNo.Text = Left$(gsPVItem, iPos - 1)
            txtVessel = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    
    txtVesselNo.SetFocus

End Sub





Private Sub GrdPlt_AfterColUpdate(ByVal ColIndex As Integer)
    If ColIndex = 2 Then
        sqlstmt = "SELECT SERVICE_NAME FROM SERVICE_CATEGORY WHERE SERVICE_CODE = '" & GrdPlt.Columns(8).Value & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        GrdPlt.Columns(2).Value = GrdPlt.Columns(8).Value & " - " & dsSHORT_TERM_DATA.fields("SERVICE_NAME").Value
    End If

'    If ColIndex = 4 Then
'        If GrdPlt.Columns(8).Value = 8 Then
'            txtVesselNo = GrdPlt.Columns(4).Value
'        End If
'    End If
'
End Sub
'-------------------------------------------------------------------------------------------------------
Private Sub GrdPlt_BeforeColUpdate(ByVal ColIndex As Integer, ByVal OldValue As Variant, Cancel As Integer)

    With GrdPlt
        Select Case ColIndex
'            Case 0    'validate the date
'                If .Columns(0).Value <> "" Then
'                    If Not IsDate(Format(.Columns(0).Value, "MM/DD/YY")) Then
'                        MsgBox "Invalid DATE", vbInformation, sMsg
'                        Cancel = True
'                    End If
'                End If
'            Case 1    'Validate the time
'                If .Columns(1).Value = "" Then Exit Sub
'                    If Not IsDate(Format(.Columns(1).Value, "HH:MM:SS")) Then
'                        MsgBox "Invalid TIME", vbInformation, sMsg
'                        Cancel = True
'                    End If
'
            Case 2
                If ValidService(Trim(.Columns(2).Value)) = False Then
                    MsgBox "Entered service type (" & Trim(.Columns(2).Value) & ") Not Valid."
                    Cancel = True
                End If

'            Case 5    'validate the customer id
'
'                sqlstmt = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & .Columns(5).Value
'                Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'                If OraDatabase.lastservererr = 0 And dsCUSTOMER_PROFILE.recordcount = 0 Then
'                    If OraDatabase.lastservererr <> 0 Then
'                        MsgBox OraDatabase.LastServerErrText, vbInformation, sMsg
'                    End If
'                    MsgBox "Invalid Customer Id", vbInformation, sMsg
'                    Cancel = True
'                End If
'
'            Case 6    'validate the Quantity change
'                Call ValidQty(Trim(.Columns(2).Value))
        End Select
    End With
End Sub
'---------------------------------------------------------------------------------------------------------

'---------------------------------------------------------------------------------------------------------
'Pawan....modified to make changes so that qtyreceived should
'not be affected by the changes made in the quantity......10/09/2001
'Sub ValidQty(SerName As String)
'    Dim iNewQty As Integer
'    Dim iOldQtyRecvd As Integer
'    Dim iOldQtyINH As Integer
'
'    iNewQty = GrdPlt.Columns(6).Value
'    iOldQtyRecvd = txtQtyRecvd
'    iOldQtyINH = txtQtyInHouse
'    'iQtyLeft = CInt(GrdPlt.Columns(12).Value)
'
'    Select Case SerName
'        Case "Delivery"
'            If iNewQty < 0 Then
'                    If MsgBox("Quantity cannot be less then zero." & vbCrLf & _
'                               "Do You want to overwrite it ? ", vbQuestion + vbYesNo, sMsg) = vbNo Then
'                        GrdPlt.Columns(6).Value = iOldQty
'                        txtQtyInHouse = iOldQtyINH
'                        Exit Sub
'                    Else
'
'                        If bPwd = False Then
'                            frmPwd.Show vbModal
'                            If bPwd = False Then
'                                GrdPlt.Columns(6).Value = iOldQty
'                                txtQtyInHouse = iOldQtyINH
'                            End If
'                        End If
'                        Exit Sub
'                    End If
'                End If
'
'                If iNewQty = iOldQty Then
'                    GrdPlt.Columns(6).Value = iOldQty
'                    txtQtyInHouse = iOldQtyINH
'                End If
'
'                If iNewQty > iOldQty Then
'                     If iNewQty > Val(txtQtyRecvd) Then
'                        If MsgBox("The Qty Change cannot exceed then the Qty Received " & vbCrLf & _
'                                   " DO you want to overwrite it ? ", vbQuestion + vbYesNo, sMsg) = vbNo Then
'                            GrdPlt.Columns(6).Value = iOldQty
'                            Exit Sub
'                        Else
'                            If bPwd = False Then
'                                frmPwd.Show vbModal
'                                If bPwd = False Then
'                                    GrdPlt.Columns(6).Value = iOldQty
'                                    txtQtyInHouse = iOldQtyINH
'                                End If
'                            End If
'                            Exit Sub
'                        End If
'                    End If
'
'                    txtQtyInHouse.Text = iOldQtyINH - (iNewQty - iOldQty)
'                    iQtyLeft = iQtyLeft - (iNewQty - iOldQty)
'
'                ElseIf iNewQty < iOldQty Then
'                    txtQtyInHouse.Text = iOldQtyINH + (iOldQty - iNewQty)
'                    iQtyLeft = iQtyLeft - (iNewQty - iOldQty)
'                End If
'
'               GrdPlt.Columns(12).Value = CStr(iQtyLeft)
'        Case "Return"
'            If iNewQty > 0 Then
'                If MsgBox("This value should be less then zero." & vbCrLf & _
'                         "Do you want to overwrite it ?", vbQuestion + vbYesNo, sMsg) = vbNo Then
'                    GrdPlt.Columns(6).Value = iOldQty
'                    Exit Sub
'                Else
'                    If bPwd = False Then
'                        frmPwd.Show vbModal
'                        If bPwd = False Then
'                            GrdPlt.Columns(6).Value = iOldQty
'                            'txtQtyRecvd = iOldQtyRecvd
'                            txtQtyInHouse = iOldQtyINH
'                        End If
'                    End If
'                    Exit Sub
'                End If
'            End If
'
'            If iNewQty = iOldQty Then
'                txtQtyInHouse = iOldQtyINH
'                GrdPlt.Columns(6).Value = iOldQty
'                'txtQtyRecvd = iOldQtyRecvd
'            End If
'
'            If Abs(iNewQty) > Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH + (Abs(iNewQty) - Abs(iOldQty))
'                'txtQtyRecvd = iOldQtyRecvd + (Abs(iNewQty) - Abs(iOldQty))
'               iQtyLeft = iQtyLeft + (Abs(iNewQty) - Abs(iOldQty))
'           ElseIf Abs(iNewQty) < Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH - (Abs(iOldQty) - Abs(iNewQty))
'                'txtQtyRecvd = iOldQtyRecvd - (Abs(iOldQty) - Abs(iNewQty))
'                iQtyLeft = iQtyLeft - (Abs(iNewQty) - Abs(iOldQty))
'            End If
'           GrdPlt.Columns(12).Value = CStr(iQtyLeft)
'
'        Case "Dock Return"
'            If iNewQty > 0 Then
'                If MsgBox("This value should be less then zero." & vbCrLf & _
'                         "Do you want to overwrite it ?", vbQuestion + vbYesNo, sMsg) = vbNo Then
'                    GrdPlt.Columns(6).Value = iOldQty
'                    Exit Sub
'                Else
'                    If bPwd = False Then
'                        frmPwd.Show vbModal
'                        If bPwd = False Then
'                            GrdPlt.Columns(6).Value = iOldQty
'                            'txtQtyRecvd = iOldQtyRecvd
'                            txtQtyInHouse = iOldQtyINH
'                        End If
'                    End If
'                    Exit Sub
'                End If
'            End If
'
'            If iNewQty = iOldQty Then
'                txtQtyInHouse = iOldQtyINH
'                GrdPlt.Columns(6).Value = iOldQty
'                'txtQtyRecvd = iOldQtyRecvd
'            End If
'
'                If Abs(iNewQty) > Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH + (Abs(iNewQty) - Abs(iOldQty))
'                'txtQtyRecvd = iOldQtyRecvd + (Abs(iNewQty) - Abs(iOldQty))
'               iQtyLeft = iQtyLeft + (Abs(iNewQty) - Abs(iOldQty))
'           ElseIf Abs(iNewQty) < Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH - (Abs(iOldQty) - Abs(iNewQty))
'                'txtQtyRecvd = iOldQtyRecvd - (Abs(iOldQty) - Abs(iNewQty))
'                iQtyLeft = iQtyLeft - (Abs(iNewQty) - Abs(iOldQty))
'            End If
'           GrdPlt.Columns(12).Value = CStr(iQtyLeft)
'
'        Case "FromPort"
'            If iNewQty < 0 Then
'                If MsgBox("Quantity Cannot be zero. " & vbCrLf & " Do you want to overwrite" _
'                           & "it ?", vbQuestion + vbYesNo, sMsg) = vbNo Then
'                    GrdPlt.Columns(6).Value = iOldQty
'                Else
'                    If bPwd = False Then
'                        frmPwd.Show vbModal
'                        If bPwd = False Then
'                            GrdPlt.Columns(6).Value = iOldQty
'                            'txtQtyRecvd = iOldQtyRecvd
'                            txtQtyInHouse = iOldQtyINH
'                        End If
'                    End If
'                    Exit Sub
'                End If
'            End If
'
'            If iNewQty = iOldQty Then
'                txtQtyInHouse = iOldQtyINH
'                'txtQtyRecvd = iOldQtyRecvd
'            End If
'
'            If iNewQty > iOldQty Then
'                txtQtyInHouse.Text = iOldQtyINH + (iNewQty - iOldQty)
'                txtQtyRecvd = iOldQtyRecvd + (iNewQty - iOldQty)
'
'            ElseIf iNewQty < iOldQty Then
'                txtQtyInHouse.Text = iOldQtyINH - (iOldQty - iNewQty)
'                txtQtyRecvd = iOldQtyRecvd - (iOldQty - iNewQty)
'
'            End If
'            iQtyLeft = iQtyLeft + (iNewQty - iOldQty)
'            GrdPlt.Columns(12).Value = CStr(iQtyLeft)
'
'        Case "Recoup"
'                If iNewQty = iOldQty Then
'                    txtQtyInHouse = iOldQtyINH
''                    txtQtyRecvd = iOldQtyRecvd
'                End If
'
'                If iOldQty <= 0 And iNewQty <= 0 Then
'                    txtQtyInHouse = iOldQtyINH + (iNewQty - iOldQty)
'                ElseIf iOldQty <= 0 And iNewQty > 0 Then
'                    txtQtyInHouse = iOldQtyINH + (iNewQty - iOldQty)
'                ElseIf iOldQty > 0 And iNewQty <= 0 Then
'                    txtQtyInHouse = iOldQtyINH - (iOldQty - iNewQty)
'                ElseIf iOldQty > 0 And iNewQty > 0 Then
'                    txtQtyInHouse = iOldQtyINH - (iOldQty - iNewQty)
'                End If
'
'        Case "Void"
'            If iNewQty = iOldQty Then
'                txtQtyInHouse = iOldQtyINH
'                'txtQtyRecvd = iOldQtyRecvd
'            End If
'
'            If iNewQty > 0 Then
'                If MsgBox("This value should be less then zero." & vbCrLf & " Do you want to " _
'                          & "overwrite it ?", vbQuestion + vbYesNo, sMsg) = vbNo Then
'                    txtQtyInHouse = iOldQtyINH
'                    GrdPlt.Columns(6).Value = iOldQty
'                    'txtQtyRecvd = iOldQtyRecvd
'                    Exit Sub
'                Else
'                    If bPwd = False Then
'                        frmPwd.Show vbModal
'                        If bPwd = False Then
'                            GrdPlt.Columns(6).Value = iOldQty
'                           ' txtQtyRecvd = iOldQtyRecvd
'                            txtQtyInHouse = iOldQtyINH
'                        End If
'                    End If
'                    Exit Sub
'                End If
'            End If
'
'            If Abs(iNewQty) > Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH + (Abs(iNewQty)) - Abs(iOldQty)
'                'txtQtyRecvd = iOldQtyRecvd + (Abs(iNewQty) - Abs(iOldQty))
'            ElseIf Abs(iNewQty) < Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH - (Abs(iOldQty) - Abs(iOldQty))
'                'txtQtyRecvd = iOldQtyRecvd - (Abs(iOldQty) - Abs(iNewQty))
'            End If
'    End Select
'
'End Sub
'---------------------------------------------------------------------------------------------------------

Function ValidService(SerName As String) As Boolean
    ValidService = True
    
    With GrdPlt
        Select Case SerName
'            Case "Delivery"
'                .Columns(8).Value = 6
'                If .Columns(6).Value < 0 Then
'                   MsgBox "Quantity cannot be less then zero for Delivery." & vbCrLf & _
'                          "Make the appropraite changes in the Quantity ? ", vbInformation, sMsg
'                    ValidService = False
'                End If
                            
            Case "Return"
                If .Columns(8).Value = 13 Then
                    .Columns(8).Value = 7
'                If .Columns(6).Value > 0 Then
'                    MsgBox "Qunatity should be less then zero for Return ." & vbCrLf & _
'                           "Make the appropraite changes in the Quantity ?", vbInformation, sMsg
'
'                    ValidService = False
'                End If
                Else
                    MsgBox "Can only change to Full Return from Dock Return."
                    ValidService = False
                End If
            
            Case "Dock Return"
                If .Columns(8).Value = 7 Then
                    .Columns(8).Value = 13
'                If .Columns(6).Value > 0 Then
'                    MsgBox "Qunatity should be less then zero for Return ." & vbCrLf & _
'                           "Make the appropraite changes in the Quantity ?", vbInformation, sMsg
'
'                    ValidService = False
'                End If
                Else
                    MsgBox "Can only change to Dock Return from Full Return."
                    ValidService = False
                End If
            
'            Case "FromPort"
'                .Columns(8).Value = 8
'                If .Columns(6).Value < 0 Then
'                   MsgBox "Quantity cannot be less then zero for Delivery." & vbCrLf & _
'                          "Make the appropraite changes in the Quantity ? ", vbInformation, sMsg
'                    ValidService = False
'                End If
                
                
            Case "Recoup"
                .Columns(8).Value = 9
                
                
            Case "TRANSFER OWNER"
                .Columns(8).Value = 11
                
                
            Case "Void"
'                If .Columns(6).Value > 0 Then
'                    MsgBox "Qunatity should be less then zero for Void ." & vbCrLf & _
'                           "Make the appropraite changes in the Quantity ?", vbInformation, sMsg
'                ValidService = False
'                End If
                .Columns(8).Value = 12
                
            Case "Outbound Autotrans"
                .Columns(8).Value = 17
'                If .Columns(6).Value < 0 Then
'                   MsgBox "Quantity cannot be less then zero for Autotrans." & vbCrLf & _
'                          "Make the appropraite changes in the Quantity ? ", vbInformation, sMsg
'                    ValidService = False
'                End If

            Case Else
                ValidService = False

        End Select
    End With
    
End Function

Private Sub GrdPlt_BeforeDelete(Cancel As Integer, DispPromptMsg As Integer)
'    MsgBox "Please note:  Deleted records will NOT be recorded until the 'commit' button is pressed.  Updates to quantity are the responsibility of the user."
    DispPromptMsg = 0
'   Dim sDtOfActivity As String
'   Dim i As Integer
'   Dim iRow As Integer
'   Dim iError As Boolean
'   Dim dsPers  As Object
'
'   DispPromptMsg = 0
'
'    If txtIntls = "" Then
'        MsgBox "Please Enter your Initials.", vbInformation, sMsg
'        Cancel = True
'        Exit Sub
'    End If
'
'    If MsgBox("Are you sure to delete this row ?", vbCritical + vbYesNo, sMsg) = vbNo Then
'        Cancel = True
'        Call SHOW_RECORDS(Trim$(txtPltNum))
'        Exit Sub
'    Else
'        If ValidDelete(GrdPlt.Columns(2).Value) = False Then
'            Cancel = True
'            Exit Sub
'        End If
'    End If
'
'    OraSession.begintrans
'
'    If OraDatabase.lastservererr <> 0 Then
'        iError = True
'    End If
'
'    sqlstmt = " SELECT * FROM CARGO_TRACKING" & _
'              " WHERE PALLET_ID ='" & Trim$(txtPltNum.Text) & "'" & _
'              " AND RECEIVER_ID =" & GrdPlt.Columns(5).Value & _
'              " AND ARRIVAL_NUM = '" & GrdPlt.Columns(11).Value & "'"
'    Set dsCargo_Tracking = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'    If OraDatabase.lastservererr = 0 And dsCargo_Tracking.recordcount > 0 Then
'        dsCargo_Tracking.edit
'        dsCargo_Tracking.Fields("COMMODITY_CODE").Value = txtCommCode.Text
'        dsCargo_Tracking.Fields("RECEIVER_ID").Value = txtOwnerId.Text
'        dsCargo_Tracking.Fields("QTY_RECEIVED").Value = txtQtyRecvd.Text
'        dsCargo_Tracking.Fields("QTY_IN_HOUSE").Value = txtQtyInHouse.Text
'        dsCargo_Tracking.Fields("DATE_RECEIVED").Value = Format(txtDtRecd.Text & " " & txtTmRecd.Text, "MM/DD/YYYY HH:NN:SS")
'        dsCargo_Tracking.Update
'    End If
'
'    If OraDatabase.lastservererr <> 0 Then
'        iError = True
'    End If
'
'    sDtOfActivity = GrdPlt.Columns(0).Value & " " & GrdPlt.Columns(1).Value
'
'    sqlstmt = "SELECT distinct Employee_ID from PERSONNEL where  Login_id='" & GrdPlt.Columns(3).Value & "'"
'    Set dsPers = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'    sqlstmt = "select * from CA_ARCHIVE"
'    Set dsCARGO_ACTIVITY_ARCHIVE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'        If OraDatabase.lastservererr = 0 Then
'          With dsCARGO_ACTIVITY_ARCHIVE
'          .AddNew
'            .Fields("PALLET_ID").Value = txtPltNum.Text
'            .Fields("Comments").Value = "Deleted By - " & txtIntls
'            .Fields("date_of_activity").Value = CDate(Format(sDtOfActivity, "MM/DD/YYYY HH:MM:SS"))
'            .Fields("activity_ID").Value = dsPers.Fields("Employee_id").Value
'            .Fields("order_num").Value = GrdPlt.Columns(4).Value
'            .Fields("customer_id").Value = GrdPlt.Columns(5).Value
'            .Fields("qty_change").Value = GrdPlt.Columns(6).Value
'            .Fields("activity_num").Value = GrdPlt.Columns(7).Value
'            .Fields("service_code").Value = GrdPlt.Columns(8).Value
'            .Fields("activity_description").Value = GrdPlt.Columns(9).Value
'            .Fields("activity_billed").Value = GrdPlt.Columns(10).Value
'          .Update
'          End With
'        End If
'
'        If OraDatabase.lastservererr <> 0 Then
'            iError = True
'        End If
'      'Delete record from cargo_activity table
'
''    If GrdPlt.Columns(2).Value = "Void" Then
''        sqlstmt = "  SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND ORDER_NUM = '" & GrdPlt.Columns(4).Value & "'" & _
''                  " AND SERVICE_CODE='6' AND QTY_CHANGE='" & GrdPlt.Columns(6).Value & "' AND ACTIVITY_DESCRIPTION='VOID'"
''        Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
''        If dsCARGO_ACTIVITY.recordcount > 0 Then
''             dsCARGO_ACTIVITY.edit
''             dsCARGO_ACTIVITY.fields("ACTIVITY_DESCRIPTION").Value = Null
''             dsCARGO_ACTIVITY.Update
''        End If
''    End If
''
''    If GrdPlt.Columns(2).Value = "Return" Or GrdPlt.Columns(2).Value = "Dock Return" Then
''        sqlstmt = "  SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND ORDER_NUM = '" & GrdPlt.Columns(4).Value & "'" & _
''                  " AND SERVICE_CODE='6' AND QTY_CHANGE='" & Abs(GrdPlt.Columns(6).Value) & "' AND ACTIVITY_DESCRIPTION='RETURN'"
''        Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
''        If dsCARGO_ACTIVITY.recordcount > 0 Then
''             dsCARGO_ACTIVITY.edit
''             dsCARGO_ACTIVITY.fields("ACTIVITY_DESCRIPTION").Value = Null
''             dsCARGO_ACTIVITY.Update
''        End If
''    End If
'
'
'       sqlstmt = " SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & Trim$(txtPltNum.Text) & "' AND " & _
'                  " ACTIVITY_Num = ' " & GrdPlt.Columns(7).Value & "'"
'
'        Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'        'dsCARGO_ACTIVITY.recordcount
'        dsCARGO_ACTIVITY.Delete
'
'        If OraDatabase.lastservererr <> 0 Then
'            iError = True
'        End If
'
'        If Not iError Then
'            OraSession.committrans
'        Else
'            OraSession.rollback
'            MsgBox "ERROR WHILE DELETING DATA !.", vbCritical + vbInformation, sMsg
'        End If
'
'        'dsCargo_Tracking_Global.DbRefresh
'        txtPltNum_LostFocus
'        Call SHOW_RECORDS(Trim$(txtPltNum))
'
End Sub
'Pawan....modified to make changes so that qtyreceived should not be affected
' by the changes made because of deleting the grd values......10/09/2001
'Function ValidDelete(SerName As String) As Boolean
'    Dim iNewQty As Integer
'    Dim iOldQtyRecvd As Integer
'    Dim iOldQtyINH As Integer
'
'    iNewQty = 0
'    iOldQtyRecvd = txtQtyRecvd
'    iOldQtyINH = txtQtyInHouse
'
'    ValidDelete = True
'
'    Select Case SerName
'        Case "Delivery"
'
'            txtQtyInHouse.Text = iOldQtyINH + (iOldQty - iNewQty)
'
'        Case "Return"
'            If Abs(iNewQty) > Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH + (Abs(iNewQty) - Abs(iOldQty))
'                'txtQtyRecvd = iOldQtyRecvd + (Abs(iNewQty) - Abs(iOldQty))
'           ElseIf Abs(iNewQty) < Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH - (Abs(iOldQty) - Abs(iNewQty))
'                'txtQtyRecvd = iOldQtyRecvd - (Abs(iOldQty) - Abs(iNewQty))
'           End If
'
'        Case "Dock Return"
'            If Abs(iNewQty) > Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH + (Abs(iNewQty) - Abs(iOldQty))
'                'txtQtyRecvd = iOldQtyRecvd + (Abs(iNewQty) - Abs(iOldQty))
'           ElseIf Abs(iNewQty) < Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH - (Abs(iOldQty) - Abs(iNewQty))
'                'txtQtyRecvd = iOldQtyRecvd - (Abs(iOldQty) - Abs(iNewQty))
'           End If
'
'        Case "FromPort"
'                txtQtyInHouse.Text = iOldQtyINH - (iOldQty - iNewQty)
'                txtQtyRecvd = iOldQtyRecvd - (iOldQty - iNewQty)
'
'        Case "Recoup"
'                If iOldQty <= 0 Then
'                    txtQtyInHouse = iOldQtyINH + (iNewQty - iOldQty)
'                ElseIf iOldQty > 0 Then
'                    txtQtyInHouse = iOldQtyINH - (iOldQty - iNewQty)
'                End If
'
'        Case "Void"
'            If Abs(iNewQty) > Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH + (Abs(iNewQty)) - Abs(iOldQty)
'                'txtQtyRecvd = iOldQtyRecvd + (Abs(iNewQty) - Abs(iOldQty))
'            ElseIf Abs(iNewQty) < Abs(iOldQty) Then
'                txtQtyInHouse.Text = iOldQtyINH - (Abs(iOldQty) - Abs(iOldQty))
'                'txtQtyRecvd = iOldQtyRecvd - (Abs(iOldQty) - Abs(iNewQty))
'            End If
'
'        Case "TRANSFER OWNER"
'            txtQtyInHouse.Text = iOldQtyINH + Abs(iOldQty)
'
'        Case "Outbound Autotrans"
'            txtQtyInHouse.Text = Abs(iOldQty)
'
'    End Select
'
'    If Val(txtQtyInHouse) > Val(txtQtyRecvd) Then
'        If MsgBox("Deleteing this record will make the Quantity InHouse more then the quantity received !" & vbCrLf & _
'                  "Do You still want to delete it ?", vbQuestion + vbYesNo, sMsg) = vbNo Then
'            txtQtyInHouse = iOldQtyINH
'            GrdPlt.Columns(6).Value = iOldQty
'            txtQtyRecvd = iOldQtyRecvd
'            ValidDelete = False
'            Exit Function
'        Else
'            If bPwd = False Then
'                frmPwd.Show vbModal
'                If bPwd = False Then
'                    GrdPlt.Columns(6).Value = iOldQty
'                    txtQtyRecvd = iOldQtyRecvd
'                    txtQtyInHouse = iOldQtyINH
'                    ValidDelete = False
'                End If
'            End If
'            Exit Function
'        End If
'    End If
'
'    If txtQtyInHouse.Text < 0 Then
'        If MsgBox("Deleteing the record will make the InHouse Quantity equal to or less then zero." & vbCrLf & _
'                   "Do you still want to delete it ?", vbQuestion + vbYesNo, sMsg) = vbNo Then
'            txtQtyInHouse = iOldQtyINH
'            GrdPlt.Columns(6).Value = iOldQty
'            txtQtyRecvd = iOldQtyRecvd
'            Exit Function
'        Else
'            If bPwd = False Then
'                frmPwd.Show vbModal
'                If bPwd = False Then
'                    GrdPlt.Columns(6).Value = iOldQty
'                    txtQtyRecvd = iOldQtyRecvd
'                    txtQtyInHouse = iOldQtyINH
'                    ValidDelete = False
'                End If
'            End If
'            Exit Function
'        End If
'    End If
'
'End Function

'Private Sub GrdPlt_Click()
'
'   If GrdPlt.Rows = 0 Then Exit Sub
'
'    With GrdPlt
'        OldDt = .Columns(0).Value
'        OldTime = .Columns(1).Value
'        iOldCustId = .Columns(5).Value
'        iOldQty = .Columns(6).Value
'        If .Columns(12).Value <> "" Then iQtyLeft = CInt(.Columns(12).Value)
'
'    End With
'End Sub


'Private Sub txtComm_KeyPress(KeyAscii As Integer)
'    KeyAscii = 0
'End Sub
Private Sub txtCommCode_LostFocus()
    If iCurComm <> txtCommCode Then
        txtCommCode.ForeColor = vbBlue
    Else
        txtCommCode.ForeColor = vbBlack
    End If
    
    If Trim(txtCommCode.Text) <> "" Then
        sqlstmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE =" & txtCommCode.Text
        Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        If OraDatabase.lastservererr = 0 And dsCOMMODITY_PROFILE.recordcount > 0 Then
            txtComm = dsCOMMODITY_PROFILE.fields("COMMODITY_NAME").Value
            ' if this is NOT an authorized pallet, do NOT allow changes!
            If IsNull(dsCOMMODITY_PROFILE.fields("COMMODITY_TYPE").Value) Or (dsCOMMODITY_PROFILE.fields("COMMODITY_TYPE").Value <> "CHILEAN" And dsCOMMODITY_PROFILE.fields("COMMODITY_TYPE").Value <> "BOOKING" And dsCOMMODITY_PROFILE.fields("COMMODITY_TYPE").Value <> "BRAZILIAN" And dsCOMMODITY_PROFILE.fields("COMMODITY_TYPE").Value <> "PERUVIAN" And dsCOMMODITY_PROFILE.fields("COMMODITY_TYPE").Value <> "ARG FRUIT" And dsCOMMODITY_PROFILE.fields("COMMODITY_TYPE").Value <> "ARG JUICE" And dsCOMMODITY_PROFILE.fields("COMMODITY_TYPE").Value <> "STEEL" And dsCOMMODITY_PROFILE.fields("COMMODITY_TYPE").Value <> "CLEMENTINES") Then
                cmdSave.Enabled = False
            Else
                cmdSave.Enabled = True
            End If
        Else
            MsgBox "Invalid Commodity Code", vbInformation, sMsg
            txtComm = ""
        End If
    Else
        txtComm = ""
    End If
End Sub

Private Sub txtDtRecd_LostFocus()
    If txtDtRecd = "" Then Exit Sub
    If sCurDateRec <> txtDtRecd Then
        txtDtRecd.ForeColor = vbBlue
    Else
        txtDtRecd.ForeColor = vbBlack
    End If
    
End Sub
'Private Sub txtOwner_KeyPress(KeyAscii As Integer)
'    KeyAscii = 0
'End Sub

Private Sub txtOwnerId_LostFocus()
    If iCurOwner <> txtOwnerId Then
        txtOwnerId.ForeColor = vbBlue
    Else
        txtOwnerId.ForeColor = vbBlack
    End If
    
    
    If txtOwnerId <> "" Then
        sqlstmt = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtOwnerId
        Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
        If OraDatabase.lastservererr = 0 And dsCUSTOMER_PROFILE.recordcount > 0 Then
            txtOwner = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
'            GrdPlt.MoveFirst
'            For iRec = 0 To GrdPlt.Rows - 1
'               GrdPlt.Columns(5).Value = txtOwnerId
'               GrdPlt.MoveNext
'            Next iRec
        Else
            If OraDatabase.lastservererr <> 0 Then
                MsgBox OraDatabase.LastServerErrText, vbInformation, sMsg
            End If
            MsgBox "Invalid Customer Id", vbInformation, sMsg
            txtOwnerId.Text = iCurOwner
            txtOwnerId.ForeColor = vbBlack
        End If
    End If
    
End Sub

Private Sub txtQtyInHouse_LostFocus()
    If iCurQtyInHouse <> txtQtyInHouse Then
'        If MsgBox("Are you sure you want to change the Current In-House QTY? ", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbYes Then
            If bPwd = False Then
                frmPwd.Show vbModal
            End If
            If bPwd = True Then
                txtQtyInHouse.ForeColor = vbBlue
 '               MsgBox "IMPORTANT NOTE:  Changes to the In-House Quantity will not be applied to activity records, " & vbCrLf & "activity updating is the responsibility of the user"
            End If
'        Else
'            txtQtyInHouse.ForeColor = vbBlack
'            txtQtyInHouse.Text = iCurQtyInHouse
'        End If
    Else
        txtQtyInHouse.ForeColor = vbBlack
    End If
'    If bPwd = False Then
'            txtQtyInHouse.Locked = False
            
'            Exit Sub
'        End If
'        Else
'            txtQtyInHouse.Locked = True
'        End If
'    Else
'        txtQtyInHouse.Locked = False
'    End If
End Sub

Private Sub txtQtyRecvd_LostFocus()
    If iCurQtyRec <> txtQtyRecvd Then
'        If MsgBox("Are you sure you want to change the Original QTY? ", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbYes Then
            If bPwd = False Then
                frmPwd.Show vbModal
            End If
            If bPwd = True Then
                txtQtyRecvd.ForeColor = vbBlue
                ' MsgBox "IMPORTANT NOTE:  Changing the original QTY received will update the Inbound activity record, " & vbCrLf & "but adjustments to outbound values are the responsibility of the user"
            End If
'        Else
'            txtQtyRecvd.ForeColor = vbBlack
'            txtQtyRecvd.Text = iCurQtyRec
'        End If
    Else
        txtQtyRecvd.ForeColor = vbBlack
    End If
End Sub

Private Sub txtTmRecd_LostFocus()
    If txtTmRecd = "" Then Exit Sub
    If sCurTimeRec <> txtTmRecd Then
        txtTmRecd.ForeColor = vbBlue
    Else
        txtTmRecd.ForeColor = vbBlack
    End If
End Sub

Private Sub txtTmShipped_lostfocus()
'    If txtTmShipped = "" Then Exit Sub
    If sCurTimeShip <> txtTmShipped Then
        txtTmShipped.ForeColor = vbBlue
    Else
        txtTmShipped.ForeColor = vbBlack
    End If

End Sub

Private Sub txtWHSloc_lostfocus()
    If sCurWHSloc <> txtWHSloc Then
        txtWHSloc.ForeColor = vbBlue
    Else
        txtWHSloc.ForeColor = vbBlack
    End If

End Sub

Private Sub txtACTloc_lostfocus()
    If sCurACTloc <> txtACTloc Then
        txtACTloc.ForeColor = vbBlue
    Else
        txtACTloc.ForeColor = vbBlack
    End If

End Sub

'Private Sub txtVessel_KeyPress(KeyAscii As Integer)
'    KeyAscii = 0
'End Sub
Private Sub txtVesselNo_LostFocus()

    If sCurVes <> txtVesselNo Then
        txtVesselNo.ForeColor = vbBlue
    Else
        txtVesselNo.ForeColor = vbBlack
    End If
    
    If Trim(txtVesselNo) = "-1" Then
        MsgBox " Arrival Num can not be -1 ", vbInformation, sMsg
        txtVesselNo = ""
        Exit Sub
    End If
    
    If Trim(txtVesselNo) = "" Then
        txtVessel.Text = ""
        Exit Sub
    End If
    
    If Trim(txtVesselNo.Text) <> "" Then
        sqlstmt = "SELECT * FROM VESSEL_PROFILE WHERE TO_CHAR(LR_NUM) ='" & txtVesselNo.Text & "'"
        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        If OraDatabase.lastservererr = 0 And dsVESSEL_PROFILE.recordcount > 0 Then
            txtVessel = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
        Else
            'MsgBox "Invalid Vessel No", vbInformation, sMsg
            'txtVesselNo = ""
            txtVessel = ""
        End If
    Else
        txtVessel = ""
    End If
End Sub





























'---------------------------------------------------------------------------------------------------------
'      sqlstmt = "SELECT LR_NUM,VESSEL_NAME FROM VESSEL_PROFILE WHERE ARRIVAL_NUM = '" & Trim$(dsCargo_Tracking_Global.fields("ARRIVAL_NUM").Value) & "'"
'      Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'      If dsVESSEL_PROFILE.recordcount > 0 Then
'         txtVessel = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
'         txtVesselNo = dsVESSEL_PROFILE.fields("LR_NUM").Value
'      Else
'         txtVesselNo = Trim$(dsCargo_Tracking_Global.fields("ARRIVAL_NUM").Value)
'         txtVessel = ""
'      End If
'      sLRNUM = Trim(txtVesselNo)
'      sqlstmt = " SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE" & _
'                " COMMODITY_CODE =" & dsCargo_Tracking_Global.fields("COMMODITY_CODE").Value
'      Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'      sqlstmt = " SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE" & _
'                " CUSTOMER_ID =" & dsCargo_Tracking_Global.fields("RECEIVER_ID").Value
'      Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'      If dsCargo_Tracking_Global.recordcount > 1 Then
'         CMDNext.Enabled = True
'         CMDPrevoius.Enabled = False
'         cmdDeleteCT.Enabled = True
'      Else
'         CMDNext.Enabled = False
'         CMDPrevoius.Enabled = False
'         cmdDeleteCT.Enabled = False
'      End If
''     txtVessel = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
'      txtCommCode = dsCargo_Tracking_Global.fields("COMMODITY_CODE").Value
'      txtComm = dsCOMMODITY_PROFILE.fields("COMMODITY_NAME").Value
'      txtOwnerId = dsCargo_Tracking_Global.fields("RECEIVER_ID").Value
      
'      sqlstmt = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = "
'      sqlstmt = sqlstmt & " (SELECT ACTIVITY_ID FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & txtPltNum & "'"
'      sqlstmt = sqlstmt & " AND CUSTOMER_ID = " & Val(txtOwnerId.Text)
'      sqlstmt = sqlstmt & " AND ARRIVAL_NUM  = '" & sLRNUM & "'"
'      sqlstmt = sqlstmt & " AND ACTIVITY_NUM = '1')"
'      Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'      If dsSHORT_TERM_DATA.recordcount > 0 Then
'          If Not IsNull(dsSHORT_TERM_DATA.fields("LOGIN_ID")) Then
'              lblRecBy.Caption = dsSHORT_TERM_DATA.fields("LOGIN_ID").Value
'          End If
'      End If
'
'      sqlstmt = "SELECT NVL(ACTIVITY_DESCRIPTION, 'NONE') THE_DESC, SERVICE_NAME FROM CARGO_ACTIVITY CA, SERVICE_CATEGORY SC "
'      sqlstmt = sqlstmt & " WHERE PALLET_ID = '" & txtPltNum & "'"
'      sqlstmt = sqlstmt & " AND CUSTOMER_ID = " & Val(txtOwnerId.Text)
'      sqlstmt = sqlstmt & " AND ARRIVAL_NUM  = '" & sLRNUM & "'"
'      sqlstmt = sqlstmt & " AND ACTIVITY_NUM = '1'"
'      sqlstmt = sqlstmt & " AND CA.SERVICE_CODE = SC.SERVICE_CODE"
'      Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'      If dsSHORT_TERM_DATA.recordcount > 0 Then
'            lblRecType = dsSHORT_TERM_DATA.fields("SERVICE_NAME").Value
'            lblActDesc = dsSHORT_TERM_DATA.fields("THE_DESC").Value
'      Else
'            lblRecType = ""
'            lblActDesc = ""
'      End If
'
'      sqlstmt = "SELECT * FROM CARGO_TRACKING_ADDITIONAL_DATA WHERE pallet_id ='" & txtPltNum & "' AND ARRIVAL_NUM = '" & txtVesselNo & "' AND RECEIVER_ID = '" & txtOwnerId & "'"
'      Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'      If dsSHORT_TERM_DATA.recordcount > 0 Then
'        lblQtyExpc = dsSHORT_TERM_DATA.fields("QTY_EXPECTED").Value
'      Else
'        lblQtyExpc = "N/A"
'      End If
'
'      lblQtyDmg.Caption = Val("" & dsCargo_Tracking_Global.fields("QTY_DAMAGED").Value)
'      If IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value) Then
'        txtOwner = "UNKNOWN"
'      Else
'        txtOwner = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
'      End If
'      txtQtyRecvd = dsCargo_Tracking_Global.fields("QTY_RECEIVED").Value
'      txtQtyInHouse = dsCargo_Tracking_Global.fields("QTY_IN_HOUSE").Value
'      If IsNull(dsCargo_Tracking_Global.fields("DATE_RECEIVED").Value) = False Then
'         txtDtRecd = Format(dsCargo_Tracking_Global.fields("DATE_RECEIVED").Value, "MM/DD/YYYY")
'         txtTmRecd = Format(dsCargo_Tracking_Global.fields("DATE_RECEIVED").Value, "HH:NN:SS")
'      End If
'      txtCrgDes = Trim("" & dsCargo_Tracking_Global.fields("CARGO_DESCRIPTION").Value)
'   Else
'      If OraDatabase.lastservererr <> 0 Then
'         MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
'         OraDatabase.LastServerErrReset
'         Exit Sub
'      End If
'
'      MsgBox " Invalid pallet", vbInformation, "PALLET"
'      Exit Sub
'   End If
       
'   lblQC.Caption = ""
'   sqlstmt = "SELECT COUNT(*) THE_COUNT FROM CARGO_TRACKING_AUDIT WHERE PALLET_ID = '" & txtPltNum & "'"
'   sqlstmt = sqlstmt & " AND RECEIVER_ID = " & txtOwnerId
'   sqlstmt = sqlstmt & " AND ARRIVAL_NUM  = '" & txtVesselNo & "'"
'   sqlstmt = sqlstmt & " AND WAREHOUSE_LOCATION LIKE '%QC%'"
'   Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'   If dsSHORT_TERM_DATA.fields("THE_COUNT").Value > 0 Then
'       lblQC.Caption = "**QC**"
'   End If

'   sqlstmt = "Select CA.*,PERS.Login_ID,SC.SERVICE_NAME FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC," _
'           & " PERSONNEL PERS WHERE CA.PALLET_ID='" & Trim$(sPlt_id) & "' AND CA.SERVICE_CODE=SC.SERVICE_CODE AND" _
'           & " CA.ACTIVITY_ID=PERS.EMPLOYEE_ID  and CUSTOMER_ID='" & Trim(txtOwnerId) & "' and" _
'           & " CA.ARRIVAL_NUM='" & dsCargo_Tracking_Global.fields("ARRIVAL_NUM").Value & "' " _
'           & " ORDER BY CA.ACTIVITY_NUM"
'
'   Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'   GrdPlt.RemoveAll
'
'   If OraDatabase.lastservererr = 0 And dsCARGO_ACTIVITY.recordcount > 0 Then
'      With dsCARGO_ACTIVITY
'         For iRec = 1 To .recordcount
'            If .fields("ACTIVITY_NUM").Value <> 1 Then
'               GrdPlt.AddItem Trim("" & Format(.fields("DATE_OF_ACTIVITY").Value, "MM/DD/YYYY")) + _
'                              Chr$(9) + Trim("" & Format(.fields("DATE_OF_ACTIVITY").Value, "HH:NN:SS")) + _
'                              Chr$(9) + Trim("" & .fields("SERVICE_NAME").Value) + _
'                              Chr$(9) + Trim("" & .fields("LOGIN_ID").Value) + _
'                              Chr$(9) + Trim("" & .fields("ORDER_NUM").Value) + _
'                              Chr$(9) + Trim("" & .fields("CUSTOMER_ID").Value) + _
'                              Chr$(9) + Trim("" & .fields("QTY_CHANGE").Value) + _
'                              Chr$(9) + .fields("ACTIVITY_NUM").Value + _
'                              Chr$(9) + .fields("SERVICE_CODE").Value + _
'                              Chr$(9) + Trim("" & .fields("ACTIVITY_DESCRIPTION").Value) + _
'                              Chr$(9) + Trim("" & .fields("ACTIVITY_BILLED").Value) + _
'                              Chr$(9) + Trim("" & .fields("ARRIVAL_NUM").Value) + _
'                              Chr$(9) + Trim("" & .fields("QTY_LEFT").Value)
'               GrdPlt.Refresh
'            End If
'            .MoveNext
'         Next iRec
'      End With
'   Else
'      If OraDatabase.lastservererr <> 0 Then
'         MsgBox OraDatabase.LastServerErrText, vbInformation, sMsg
'         OraDatabase.LastServerErrReset
'         Exit Sub
'      End If
'
'      MsgBox "No Activity found for Pallet No. " & txtPltNum, vbInformation, sMsg
'   End If
'---------------------------------------------------------------------------------------

'------------------------------------------------------------------------------------------------------------------------------------
'Sub MoveRecords()
'
'   sqlstmt = " SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE" & _
'            " COMMODITY_CODE =" & dsCargo_Tracking_Global.fields("COMMODITY_CODE").Value
'   Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'   sqlstmt = " SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE" & _
'             " CUSTOMER_ID =" & dsCargo_Tracking_Global.fields("RECEIVER_ID").Value
'   Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'    If Not IsNumeric(dsCargo_Tracking_Global.fields("ARRIVAL_NUM").Value) Then
'        txtVessel = ""
'    Else
'        sqlstmt = " SELECT VESSEL_NAME FROM VESSEL_PROFILE WHERE" & _
'                  " LR_NUM ='" & dsCargo_Tracking_Global.fields("ARRIVAL_NUM").Value & "'"
'        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'        If dsVESSEL_PROFILE.recordcount = 0 Or (IsNull(dsVESSEL_PROFILE.fields("VESSEL_NAME").Value) = True) Then
'            txtVessel = ""
'        Else
'            txtVessel = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
'        End If
'    End If
'
'   txtVesselNo = dsCargo_Tracking_Global.fields("ARRIVAL_NUM").Value
'   sLRNUM = Trim(txtVesselNo)
'
'   txtCommCode = dsCargo_Tracking_Global.fields("COMMODITY_CODE").Value
'   txtComm = dsCOMMODITY_PROFILE.fields("COMMODITY_NAME").Value
'   txtOwnerId = dsCargo_Tracking_Global.fields("RECEIVER_ID").Value
'
'
'      sqlstmt = "SELECT LOGIN_ID FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = "
'      sqlstmt = sqlstmt & " (SELECT ACTIVITY_ID FROM CARGO_ACTIVITY WHERE PALLET_ID = '" & txtPltNum & "'"
'      sqlstmt = sqlstmt & " AND CUSTOMER_ID = " & Val(txtOwnerId.Text)
'      sqlstmt = sqlstmt & " AND ARRIVAL_NUM  = '" & sLRNUM & "'"
'      sqlstmt = sqlstmt & " AND ACTIVITY_NUM = '1')"
'      Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'      If dsSHORT_TERM_DATA.recordcount > 0 Then
'          If Not IsNull(dsSHORT_TERM_DATA.fields("LOGIN_ID")) Then
'              lblRecBy.Caption = dsSHORT_TERM_DATA.fields("LOGIN_ID").Value
'          End If
'      End If
'
'      sqlstmt = "SELECT NVL(ACTIVITY_DESCRIPTION, 'NONE') THE_DESC, SERVICE_NAME FROM CARGO_ACTIVITY CA, SERVICE_CATEGORY SC "
'      sqlstmt = sqlstmt & " WHERE PALLET_ID = '" & txtPltNum & "'"
'      sqlstmt = sqlstmt & " AND CUSTOMER_ID = " & Val(txtOwnerId.Text)
'      sqlstmt = sqlstmt & " AND ARRIVAL_NUM  = '" & sLRNUM & "'"
'      sqlstmt = sqlstmt & " AND ACTIVITY_NUM = '1'"
'      sqlstmt = sqlstmt & " AND CA.SERVICE_CODE = SC.SERVICE_CODE"
'      Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'      If dsSHORT_TERM_DATA.recordcount > 0 Then
'            lblRecType = dsSHORT_TERM_DATA.fields("SERVICE_NAME").Value
'            lblActDesc = dsSHORT_TERM_DATA.fields("THE_DESC").Value
'      Else
'            lblRecType = ""
'            lblActDesc = ""
'      End If
'
'      sqlstmt = "SELECT * FROM CARGO_TRACKING_ADDITIONAL_DATA WHERE pallet_id ='" & txtPltNum & "' AND ARRIVAL_NUM = '" & txtVesselNo & "' AND RECEIVER_ID = '" & txtOwnerId & "'"
'      Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'      If dsSHORT_TERM_DATA.recordcount > 0 Then
'        lblQtyExpc = dsSHORT_TERM_DATA.fields("QTY_EXPECTED").Value
'      Else
'        lblQtyExpc = "N/A"
'      End If
'
'
'   txtOwner = dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value
'   txtQtyRecvd = dsCargo_Tracking_Global.fields("QTY_RECEIVED").Value
'   txtQtyInHouse = dsCargo_Tracking_Global.fields("QTY_IN_HOUSE").Value
'   If IsNull(dsCargo_Tracking_Global.fields("DATE_RECEIVED").Value) = False Then
'      txtDtRecd = Format(dsCargo_Tracking_Global.fields("DATE_RECEIVED").Value, "MM/DD/YYYY")
'      txtTmRecd = Format(dsCargo_Tracking_Global.fields("DATE_RECEIVED").Value, "HH:NN:SS")
'   Else
'      txtDtRecd = ""
'      txtTmRecd = ""
'   End If
'   txtCrgDes = Trim("" & dsCargo_Tracking_Global.fields("CARGO_DESCRIPTION").Value)
'   sqlstmt = "Select CA.*,PERS.Login_ID,SC.SERVICE_NAME FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC," _
'           & " PERSONNEL PERS WHERE CA.PALLET_ID='" & Trim$(txtPltNum) & "' AND CA.SERVICE_CODE=SC.SERVICE_CODE AND" _
'           & " CA.ACTIVITY_ID=PERS.EMPLOYEE_ID and CUSTOMER_ID='" & Trim(txtOwnerId) & "' AND" _
'           & " CA.ARRIVAL_NUM='" & dsCargo_Tracking_Global.fields("ARRIVAL_NUM").Value & "' " _
'           & " ORDER BY CA.ACTIVITY_NUM"
'
'   Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
'
'   GrdPlt.RemoveAll
'   If OraDatabase.lastservererr = 0 And dsCARGO_ACTIVITY.recordcount > 0 Then
'      With dsCARGO_ACTIVITY
'         For iRec = 1 To .recordcount
'            If .fields("ACTIVITY_NUM").Value <> 1 Then
'               GrdPlt.AddItem Trim("" & Format(.fields("DATE_OF_ACTIVITY").Value, "MM/DD/YYYY")) + _
'                              Chr$(9) + Trim("" & Format(.fields("DATE_OF_ACTIVITY").Value, "HH:NN:SS")) + _
'                              Chr$(9) + Trim("" & .fields("SERVICE_NAME").Value) + _
'                              Chr$(9) + Trim("" & .fields("LOGIN_ID").Value) + _
'                              Chr$(9) + Trim("" & .fields("ORDER_NUM").Value) + _
'                              Chr$(9) + Trim("" & .fields("CUSTOMER_ID").Value) + _
'                              Chr$(9) + Trim("" & .fields("QTY_CHANGE").Value) + _
'                              Chr$(9) + .fields("ACTIVITY_NUM").Value + _
'                              Chr$(9) + .fields("SERVICE_CODE").Value + _
'                              Chr$(9) + Trim("" & .fields("ACTIVITY_DESCRIPTION").Value) + _
'                              Chr$(9) + Trim("" & .fields("ACTIVITY_BILLED").Value) + _
'                              Chr$(9) + Trim("" & .fields("ARRIVAL_NUM").Value) + _
'                              Chr$(9) + Trim("" & .fields("QTY_LEFT").Value)
'               GrdPlt.Refresh
'            End If
'            .MoveNext
'         Next iRec
'      End With
'   End If
'
'
'End Sub
'
'------------------------------------------------------------------------------------------------------------------------------------


Private Function FinalValidate() As Boolean

    Dim ValidateString As String
    ValidateString = ""
    
    If txtVesselNo.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Vessel (LR)#" & vbCrLf
    End If
    If txtCommCode.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Commodity Code" & vbCrLf
    End If
    If txtOwnerId.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Owner #" & vbCrLf
    End If
    If txtDtRecd.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Date Received" & vbCrLf
    End If
    If txtTmRecd.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Time Received" & vbCrLf
    End If
    If txtDtShipped.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Date Shipped" & vbCrLf
    End If
    If txtTmShipped.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Time Shipped" & vbCrLf
    End If
    If txtWHSloc.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Expected Warehouse Location" & vbCrLf
    End If
    If txtACTloc.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Actual Warehouse Location" & vbCrLf
    End If
    If txtCrgDes.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Cargo Description" & vbCrLf
        If sCommType = "CLEMENTINES" Then
            ValidateString = ValidateString & "Packing House" & vbCrLf
            ValidateString = ValidateString & "Cargo Size" & vbCrLf
        End If
    End If
    If cmbRecType.ForeColor = vbBlue Then
        ValidateString = ValidateString & "Receiving Type" & vbCrLf
    End If
    If txtQtyRecvd.ForeColor = vbBlue Then
        ValidateString = ValidateString & "QTY Received" & vbCrLf
    End If
    If txtQtyInHouse.ForeColor = vbBlue Then
        ValidateString = ValidateString & "QTY In-House (NOTE:  Does not auto-change activity records)" & vbCrLf
    End If
    
    ' all messages accounted for...?
    If ValidateString = "" Then
        ValidateString = vbCrLf & "No CARGO_TRACKING changes detected (CARGO_ACTIVITY record changes may be present)"
    Else
        ValidateString = "The following CARGO_TRACKING changes will be enacted:" & vbCrLf & vbCrLf & ValidateString & vbCrLf & "Do you wish to Proceed?"
    End If
    
    ' throw the message to the user for verification
    If MsgBox(ValidateString, vbQuestion + vbYesNo, "PALLET CORRECTION") = vbYes Then
        FinalValidate = True
    Else
        FinalValidate = False
    End If
    
End Function

Private Sub txtWT_lostfocus()
    If lCurWt <> txtWT.Text Then
        txtWT.ForeColor = vbBlue
    Else
        txtWT.ForeColor = vbBlack
    End If

End Sub

Private Sub txtWtUnt_lostfocus()
    If sCurWtUnt <> txtWtUnt.Text Then
        txtWtUnt.ForeColor = vbBlue
    Else
        txtWtUnt.ForeColor = vbBlack
    End If

End Sub
