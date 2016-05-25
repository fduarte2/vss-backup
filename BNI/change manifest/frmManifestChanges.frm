VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmManifestChanges 
   Caption         =   "CARGO MANIFEST CHANGES"
   ClientHeight    =   8655
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   14940
   BeginProperty Font 
      Name            =   "Times New Roman"
      Size            =   9
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   ScaleHeight     =   8655
   ScaleWidth      =   14940
   StartUpPosition =   3  'Windows Default
   Begin VB.Frame Frame2 
      Caption         =   "PRESENT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00C00000&
      Height          =   1695
      Index           =   0
      Left            =   143
      TabIndex        =   30
      Top             =   1920
      Width           =   14655
      Begin VB.Label Label26 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "STATUS  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   11925
         TabIndex        =   56
         Top             =   285
         Width           =   990
      End
      Begin VB.Label Label25 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "LOC  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   12255
         TabIndex        =   55
         Top             =   765
         Width           =   660
      End
      Begin VB.Label Label24 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "QTY1  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   4740
         TabIndex        =   54
         Top             =   285
         Width           =   630
      End
      Begin VB.Label Label23 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "UNT1  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   4740
         TabIndex        =   53
         Top             =   765
         Width           =   615
      End
      Begin VB.Label Label22 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "QTY2  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   7245
         TabIndex        =   52
         Top             =   285
         Width           =   630
      End
      Begin VB.Label Label21 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "UNT2  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   7260
         TabIndex        =   51
         Top             =   765
         Width           =   615
      End
      Begin VB.Label Label20 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "WT  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   9840
         TabIndex        =   50
         Top             =   285
         Width           =   435
      End
      Begin VB.Label Label19 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "WT UNT  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   9420
         TabIndex        =   49
         Top             =   765
         Width           =   855
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   3
         Left            =   5640
         TabIndex        =   48
         Top             =   285
         Width           =   60
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   4
         Left            =   5640
         TabIndex        =   47
         Top             =   765
         Width           =   60
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   5
         Left            =   8160
         TabIndex        =   46
         Top             =   285
         Width           =   60
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   6
         Left            =   8160
         TabIndex        =   45
         Top             =   765
         Width           =   60
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   8
         Left            =   10440
         TabIndex        =   44
         Top             =   285
         Width           =   60
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   9
         Left            =   10440
         TabIndex        =   43
         Top             =   765
         Width           =   60
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   10
         Left            =   13080
         TabIndex        =   42
         Top             =   285
         Width           =   60
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   11
         Left            =   13080
         TabIndex        =   41
         Top             =   765
         Width           =   60
      End
      Begin VB.Label Label18 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "BOL  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   645
         TabIndex        =   40
         Top             =   285
         Width           =   555
      End
      Begin VB.Label Label17 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "MARK  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   480
         TabIndex        =   39
         Top             =   765
         Width           =   720
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   0
         Left            =   1440
         TabIndex        =   38
         Top             =   285
         Width           =   60
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   1
         Left            =   1440
         TabIndex        =   37
         Top             =   765
         Width           =   60
      End
      Begin VB.Label Label16 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "VESSEL  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   360
         TabIndex        =   36
         Top             =   1200
         Width           =   855
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   2
         Left            =   1440
         TabIndex        =   35
         Top             =   1200
         Width           =   60
      End
      Begin VB.Label Label14 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "CUSTOMER  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   6675
         TabIndex        =   34
         Top             =   1200
         Width           =   1200
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   7
         Left            =   8160
         TabIndex        =   33
         Top             =   1200
         Width           =   60
      End
      Begin VB.Label Label4 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "COMMODITY  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   11565
         TabIndex        =   32
         Top             =   1200
         Width           =   1350
      End
      Begin VB.Label lblPresent 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   12
         Left            =   13080
         TabIndex        =   31
         Top             =   1200
         Width           =   60
      End
   End
   Begin VB.Frame Frame2 
      Caption         =   "ORIGINAL"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   -1  'True
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00C00000&
      Height          =   1695
      Index           =   1
      Left            =   143
      TabIndex        =   3
      Top             =   120
      Width           =   14655
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   12
         Left            =   13080
         TabIndex        =   29
         Top             =   1200
         Width           =   60
      End
      Begin VB.Label Label15 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "COMMODITY  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   11565
         TabIndex        =   28
         Top             =   1200
         Width           =   1350
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   11
         Left            =   8160
         TabIndex        =   27
         Top             =   1200
         Width           =   60
      End
      Begin VB.Label Label5 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "CUSTOMER  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   6675
         TabIndex        =   26
         Top             =   1200
         Width           =   1200
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   10
         Left            =   1440
         TabIndex        =   25
         Top             =   1200
         Width           =   60
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "VESSEL  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   360
         TabIndex        =   24
         Top             =   1200
         Width           =   855
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   9
         Left            =   1440
         TabIndex        =   23
         Top             =   765
         Width           =   60
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   8
         Left            =   1440
         TabIndex        =   22
         Top             =   285
         Width           =   60
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "MARK  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   480
         TabIndex        =   21
         Top             =   765
         Width           =   720
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "BOL  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   645
         TabIndex        =   20
         Top             =   285
         Width           =   555
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   7
         Left            =   13080
         TabIndex        =   19
         Top             =   765
         Width           =   60
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   6
         Left            =   13080
         TabIndex        =   18
         Top             =   285
         Width           =   60
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   5
         Left            =   10440
         TabIndex        =   17
         Top             =   765
         Width           =   60
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   4
         Left            =   10440
         TabIndex        =   16
         Top             =   285
         Width           =   60
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   3
         Left            =   8160
         TabIndex        =   15
         Top             =   765
         Width           =   60
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   2
         Left            =   8160
         TabIndex        =   14
         Top             =   285
         Width           =   60
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   1
         Left            =   5640
         TabIndex        =   13
         Top             =   765
         Width           =   60
      End
      Begin VB.Label lblOrig 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "-"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00C00000&
         Height          =   225
         Index           =   0
         Left            =   5640
         TabIndex        =   12
         Top             =   285
         Width           =   60
      End
      Begin VB.Label Label11 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "WT UNT  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   9420
         TabIndex        =   11
         Top             =   765
         Width           =   855
      End
      Begin VB.Label Label10 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "WT  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   9840
         TabIndex        =   10
         Top             =   285
         Width           =   435
      End
      Begin VB.Label Label9 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "UNT2  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   7260
         TabIndex        =   9
         Top             =   765
         Width           =   615
      End
      Begin VB.Label Label8 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "QTY2  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   7245
         TabIndex        =   8
         Top             =   285
         Width           =   630
      End
      Begin VB.Label Label7 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "UNT1  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   4740
         TabIndex        =   7
         Top             =   765
         Width           =   615
      End
      Begin VB.Label Label6 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "QTY1  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   4740
         TabIndex        =   6
         Top             =   285
         Width           =   630
      End
      Begin VB.Label Label13 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "LOC  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   12255
         TabIndex        =   5
         Top             =   765
         Width           =   660
      End
      Begin VB.Label Label12 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "STATUS  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   11925
         TabIndex        =   4
         Top             =   285
         Width           =   990
      End
   End
   Begin VB.CommandButton cmdClear 
      Caption         =   "CLEAR"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   8168
      TabIndex        =   2
      Top             =   8160
      Width           =   1215
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "EXIT"
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
      Left            =   10853
      TabIndex        =   1
      Top             =   8160
      Width           =   1215
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "PRINT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   345
      Left            =   5498
      TabIndex        =   0
      Top             =   8160
      Width           =   1215
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   4215
      Left            =   120
      TabIndex        =   57
      Top             =   3840
      Width           =   14895
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   17
      AllowUpdate     =   0   'False
      MultiLine       =   0   'False
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowColumnMoving=   0
      AllowGroupSwapping=   0   'False
      AllowColumnSwapping=   0
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      MaxSelectedRows =   1
      RowHeight       =   450
      ExtraHeight     =   318
      Columns.Count   =   17
      Columns(0).Width=   1111
      Columns(0).Caption=   "LR#"
      Columns(0).Name =   "LR#"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1111
      Columns(1).Caption=   "CUST"
      Columns(1).Name =   "CUST"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1323
      Columns(2).Caption=   "COMM"
      Columns(2).Name =   "COMM"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1402
      Columns(3).Caption=   "BOL"
      Columns(3).Name =   "BOL"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   3069
      Columns(4).Caption=   "MARK"
      Columns(4).Name =   "MARK"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1244
      Columns(5).Caption=   "QTY1"
      Columns(5).Name =   "QTY1"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1058
      Columns(6).Caption=   "UNT1"
      Columns(6).Name =   "UNT1"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1244
      Columns(7).Caption=   "QTY2"
      Columns(7).Name =   "QTY2"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1058
      Columns(8).Caption=   "UNT2"
      Columns(8).Name =   "UNT2"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1508
      Columns(9).Caption=   "WT"
      Columns(9).Name =   "WT"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   1588
      Columns(10).Caption=   "WT UNT"
      Columns(10).Name=   "WT UNT"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   1429
      Columns(11).Caption=   "STATUS"
      Columns(11).Name=   "STATUS"
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   1614
      Columns(12).Caption=   "LOC"
      Columns(12).Name=   "LOC"
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   3200
      Columns(13).Caption=   "COMMENTS"
      Columns(13).Name=   "COMMENTS"
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      Columns(14).Width=   1588
      Columns(14).Caption=   "INITIALS"
      Columns(14).Name=   "INITIALS"
      Columns(14).DataField=   "Column 14"
      Columns(14).DataType=   8
      Columns(14).FieldLen=   256
      Columns(15).Width=   2117
      Columns(15).Caption=   "DT CHANGE"
      Columns(15).Name=   "DT CHANGE"
      Columns(15).DataField=   "Column 15"
      Columns(15).DataType=   8
      Columns(15).FieldLen=   256
      Columns(16).Width=   3201
      Columns(16).Caption=   "LOTNUM"
      Columns(16).Name=   "LOTNUM"
      Columns(16).DataField=   "Column 16"
      Columns(16).DataType=   8
      Columns(16).FieldLen=   256
      _ExtentX        =   26273
      _ExtentY        =   7435
      _StockProps     =   79
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
End
Attribute VB_Name = "frmManifestChanges"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim iRec As Integer
Dim sLotNum As String

Sub FillGrid()
    
    Dim sLrNum As String
    Dim sCustId  As String
    Dim sComm As String
    Dim sBOL As String
    Dim sMark As String
    Dim sQty1 As String
    Dim sUnt1 As String
    Dim sQty2 As String
    Dim sUnt2 As String
    Dim sWt As String
    Dim sWtUnt As String
    Dim sStatus As String
    Dim sLoc As String
    Dim sReason As String
    Dim sInitials As String
    Dim sDt As String
    Dim LotNum As String
            
    gsSqlStmt = " SELECT * FROM CARGO_MANIFEST_CHANGES Where LR_NUM = " & ship_no & "  ORDER BY DATE_OF_CHANGE DESC,LR_NUM,RECIPIENT_ID," _
              & " COMMODITY_CODE,CHANGE_NUM"
    '
    Set dsCARGO_MANIFEST_CHANGES = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_CHANGES.recordcount > 0 Then
        For iRec = 1 To dsCARGO_MANIFEST_CHANGES.recordcount
            
            
            sLrNum = dsCARGO_MANIFEST_CHANGES.FIELDS("LR_NUM").Value
            sCustId = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("RECIPIENT_ID").Value
            sComm = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("COMMODITY_CODE").Value
            sBOL = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("CARGO_BOL").Value
            sMark = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("CARGO_MARK").Value
            sQty1 = CStr(dsCARGO_MANIFEST_CHANGES.FIELDS("QTY_EXPECTED").Value)
            sUnt1 = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("QTY1_UNIT").Value
            sQty2 = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("QTY2_EXPECTED").Value
            sUnt2 = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("QTY2_UNIT").Value
            sWt = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("CARGO_WEIGHT").Value
            sWtUnt = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("CARGO_WEIGHT_UNIT").Value
            sStatus = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("MANIFEST_STATUS").Value
            sLoc = "" & dsCARGO_MANIFEST_CHANGES.FIELDS("CARGO_LOCATION").Value
            sReason = dsCARGO_MANIFEST_CHANGES.FIELDS("REASON").Value
            sInitials = dsCARGO_MANIFEST_CHANGES.FIELDS("INITIALS").Value
            sDt = dsCARGO_MANIFEST_CHANGES.FIELDS("DATE_OF_CHANGE").Value
            sLotNum = dsCARGO_MANIFEST_CHANGES.FIELDS("CONTAINER_NUM").Value
            
            grdData.AddItem sLrNum + Chr(9) + sCustId + Chr(9) + _
                            sComm + Chr(9) + sBOL + Chr(9) + _
                            sMark + Chr(9) + sQty1 + Chr(9) + _
                            sUnt1 + Chr(9) + sQty2 + Chr(9) + _
                            sUnt2 + Chr(9) + sWt + Chr(9) + _
                            sWtUnt + Chr(9) + sStatus + Chr(9) + _
                            sLoc + Chr(9) + sReason + Chr(9) + _
                            sInitials + Chr(9) + sDt + Chr(9) + _
                            sLotNum

            
                            
            dsCARGO_MANIFEST_CHANGES.MoveNext
         Next iRec
    Else
        MsgBox "There is No Change History for The Vessel No : " & ship_no, vbInformation, "History_Form"
        
      
    End If








End Sub
'Private Sub cboBOL_Click()
'    If cboBOL = "" Then Exit Sub
'    cboMark.Clear
'
'    For iRec = 0 To lblOrig.UBound
'        lblOrig(iRec) = ""
'    Next iRec
'
'    grdData.RemoveAll
'
'    gsSqlStmt = " SELECT * FROM CARGO_MANIFEST WHERE CARGO_BOL='" & Trim(cboBOL.Text) & "'" _
'              & " AND  LR_NUM='" & Trim(txtLrNum) & "' AND " _
'              & " COMMODITY_CODE ='" & Trim(txtCommCode) & "' AND RECIPIENT_ID='" & Trim(txtCustId) & "'"
'    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
'        For iRec = 1 To dsCARGO_MANIFEST.RECORDCOUNT
'            cboMark.AddItem dsCARGO_MANIFEST.FIELDS("CARGO_MARK").Value
'            dsCARGO_MANIFEST.MoveNext
'        Next iRec
'    End If
' End Sub
 
'Private Sub cboMark_Click()
'
'    If cboMark = "" Then Exit Sub
'
'    gsSqlStmt = " SELECT * FROM CARGO_MANIFEST WHERE CARGO_BOL='" & Trim(cboBOL.Text) & "'" _
'              & " AND  LR_NUM='" & Trim(txtLrNum) & "' AND CARGO_MARK='" & Trim(cboMark) & "' AND " _
'              & " COMMODITY_CODE ='" & Trim(txtCommCode) & "' AND RECIPIENT_ID='" & Trim(txtCustId) & "'"
'    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
'        sLotNum = dsCARGO_MANIFEST.FIELDS("CONTAINER_NUM").Value
'    End If
'
'    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST_ORIGINAL WHERE CONTAINER_NUM='" & sLotNum & "'"
'    Set dsCARGO_MANIFEST_ORIGINAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_ORIGINAL.RECORDCOUNT > 0 Then
'        lblOrig(0) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_MARK").Value
'        lblOrig(1) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY_EXPECTED").Value
'        lblOrig(2) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY1_UNIT").Value
'        lblOrig(3) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY2_EXPECTED").Value
'        lblOrig(4) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY2_UNIT").Value
'        lblOrig(5) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_WEIGHT").Value
'        lblOrig(6) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_WEIGHT_UNIT").Value
'        lblOrig(7) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_LOCATION").Value
'        lblOrig(8) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("MANIFEST_STATUS").Value
'
'        gsSqlStmt = "SELECT * FROM CARGO_TRACKING_ORIGINAL WHERE LOT_NUM='" & sLotNum & "'"
'        Set dsCARGO_MANIFEST_ORIGINAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'        If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_ORIGINAL.RECORDCOUNT > 0 Then
'            lblOrig(9) = dsCARGO_TRACKING_ORIGINAL.FIELDS("DATE_RECEIVED").Value
'            lblOrig(10) = dsCARGO_TRACKING_ORIGINAL.FIELDS("QTY_IN_HOUSE").Value
'        Else
'            gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM='" & sLotNum & "'"
'            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
'                lblOrig(9) = dsCARGO_TRACKING.FIELDS("DATE_RECEIVED").Value
'                lblOrig(10) = dsCARGO_TRACKING.FIELDS("QTY_IN_HOUSE").Value
'            End If
'        End If
'    End If
'
'
'    gsSqlStmt = " SELECT * FROM CARGO_MANIFEST_CHANGES WHERE CONTAINER_NUM='" & sLotNum & "'" _
'              & " ORDER BY CHANGE_NUM"
'    Set dsCARGO_MANIFEST_CHANGES = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_CHANGES.RECORDCOUNT > 0 Then
'        With dsCARGO_MANIFEST_CHANGES
'            For iRec = 1 To .RECORDCOUNT
'                grdData.AddItem .FIELDS("LR_NUM").Value + Chr(9) + _
'                                .FIELDS("RECIPIENT_ID").Value + Chr(9) + _
'                                .FIELDS("COMMODITY_CODE").Value + Chr(9) + _
'                                .FIELDS("CARGO_BOL").Value + Chr(9) + _
'                                .FIELDS("CARGO_MARK").Value + Chr(9) + _
'                                .FIELDS("QTY_EXPECTED").Value + Chr(9) + _
'                                .FIELDS("QTY1_UNIT").Value + Chr(9) + _
'                                .FIELDS("QTY2_EXPECTED").Value + Chr(9) + _
'                                .FIELDS("QTY2_UNIT").Value + Chr(9) + _
'                                .FIELDS("CARGO_WEIGHT").Value + Chr(9) + _
'                                .FIELDS("CARGO_WEIGHT_UNIT").Value + Chr(9) + _
'                                .FIELDS("MANIFEST_STATUS").Value + Chr(9) + _
'                                .FIELDS("CARGO_LOCATION").Value + Chr(9) + _
'                                Chr(9) + Chr(9) + .FIELDS("COMMENTS").Value + Chr(9) + _
'                                .FIELDS("INITIALS").Value + _
'                                .FIELDS("DATE_OF_CHANGE").Value
'                dsCARGO_MANIFEST.MoveNext
'            Next iRec
'        End With
'    End If
'
'End Sub

Private Sub cmdClear_Click()
'    txtLrNum = ""
'    txtVessel = ""
'    txtCustId = ""
'    txtCustomer = ""
'    txtCommCode = ""
'    txtCommodity = ""
'    cboBOL.ListIndex = -1
'    cboMark.ListIndex = -1
    For iRec = 0 To lblOrig.UBound
        lblOrig(iRec) = ""
    Next iRec
    
    For iRec = 0 To lblPresent.UBound
        lblPresent(iRec) = ""
    Next iRec
    
'    grdData.RemoveAll
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub

Private Sub cmdPrint_Click()
    
    Printer.Print "Printed on : " & Format(Now, "MM/DD/YYYY")
    Printer.Print ""
    Printer.Print ""
    Printer.FontBold = True
    Printer.FontSize = 12
    Printer.Print Tab(35); "MANIFEST REPORT"
    Printer.FontSize = 9
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(32); "ORIGINAL MANIFEST"; Tab(80); "CHANGED MANIFEST"
    Printer.FontBold = False
    Printer.Print Tab(5); "---------------------------------------------------------------------------------------------"; _
                        "---------------------------------------------------------------------------------------------";
    Printer.Print Tab(5); "LR #"; Tab(35); lblOrig(10); Tab(90); grdData.Columns(0).Value
    Printer.Print ""
    Printer.Print Tab(5); "CUSTOMER"; Tab(35); lblOrig(11); Tab(90); grdData.Columns(1).Value
    Printer.Print ""
    Printer.Print Tab(5); "COMMODITY"; Tab(35); lblOrig(12); Tab(90); grdData.Columns(2).Value
    Printer.Print ""
    Printer.Print Tab(5); "BOL"; Tab(35); lblOrig(8); Tab(90); grdData.Columns(3).Value
    Printer.Print ""
    Printer.Print Tab(5); "MARK"; Tab(35); lblOrig(9); Tab(90); grdData.Columns(4).Value
    Printer.Print ""
    Printer.Print Tab(5); "QTY1"; Tab(35); lblOrig(0); Tab(90); grdData.Columns(5).Value
    Printer.Print ""
    Printer.Print Tab(5); "UNIT 1"; Tab(35); lblOrig(1); Tab(90); grdData.Columns(6).Value
    Printer.Print ""
    Printer.Print Tab(5); "QTY2"; Tab(35); lblOrig(2); Tab(90); grdData.Columns(7).Value
    Printer.Print ""
    Printer.Print Tab(5); "UNIT 2"; Tab(35); lblOrig(3); Tab(90); grdData.Columns(8).Value
    Printer.Print ""
    Printer.Print Tab(5); "WEIGHT"; Tab(35); lblOrig(4); Tab(90); grdData.Columns(9).Value
    Printer.Print ""
    Printer.Print Tab(5); "WT UNIT"; Tab(35); lblOrig(5); Tab(90); grdData.Columns(10).Value
    Printer.Print ""
    Printer.Print Tab(5); "STATUS"; Tab(35); lblOrig(6); Tab(90); grdData.Columns(11).Value
    Printer.Print ""
    Printer.Print Tab(5); "LOCATION"; Tab(35); lblOrig(7); Tab(90); grdData.Columns(12).Value
    Printer.Print ""
    Printer.Print Tab(5); "---------------------------------------------------------------------------------------------"; _
                        "---------------------------------------------------------------------------------------------";
    Printer.Print ""
    Printer.Print Tab(5); "REASON :"; Tab(25); grdData.Columns(13).Value
    Printer.Print ""
    Printer.Print Tab(5); "CHENGED BY :"; Tab(35); grdData.Columns(14).Value; Tab(45); "ON  :" & grdData.Columns(15).Value
    
    Printer.EndDoc
    
    
    
End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    Call FillGrid
End Sub

'Private Sub txtCommCode_Validate(Cancel As Boolean)
'     If Trim$(txtCommCode) = "" Then Exit Sub
'
'     If Not IsNumeric(txtCommCode) Then
'        MsgBox "Expecting Numeric Values.", vbInformation, "COMMODITY CODE"
'        txtCommCode = ""
'        Cancel = True
'        Exit Sub
'    End If
'
'    gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & txtCommCode.Text
'    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RECORDCOUNT > 0 Then
'        txtCommodity.Text = dsCOMMODITY_PROFILE.FIELDS("COMMODITY_NAME").Value
'    Else
'        MsgBox "Invalid COMMODITY CODE.", vbExclamation, "COMMODITY"
'        txtCommCode = ""
'        Cancel = True
'        Exit Sub
'    End If
'
'    If txtLrNum <> "" And txtCustId <> "" Then Call FillBOL
'
'End Sub
'
'Private Sub txtCustId_Validate(Cancel As Boolean)
'    If Trim$(txtCustId) = "" Then Exit Sub
'
'     If Not IsNumeric(txtCustId) Then
'        MsgBox "Expecting Numeric Values.", vbInformation, "CUSTOMER ID"
'        txtCustId = ""
'        Cancel = True
'        Exit Sub
'    End If
'
'    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtCustId.Text
'    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RECORDCOUNT > 0 Then
'        txtCustomer.Text = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value
'    Else
'        MsgBox "Invalid CUSTOMER ID.", vbExclamation, "CUSTOMER"
'        txtCustId = ""
'        Cancel = True
'        Exit Sub
'    End If
'
'    If txtLrNum <> "" And txtCommCode <> "" Then Call FillBOL
'End Sub
'
'Private Sub txtLrNum_Validate(Cancel As Boolean)
'    If Trim$(txtLrNum) = "" Then Exit Sub
'
'     If Not IsNumeric(txtLrNum) Then
'        MsgBox "Expecting Numeric Values.", vbInformation, "LR NUMBER"
'        txtLrNum = ""
'        Cancel = True
'        Exit Sub
'    End If
'
'
'    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLrNum.Text
'    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RECORDCOUNT > 0 Then
'        txtVessel = dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
'    Else
'        MsgBox "Invalid LrNum .", vbExclamation, "Vessel"
'        txtLrNum = ""
'        Cancel = True
'        Exit Sub
'    End If
'
'    If txtCommCode <> "" And txtCustId <> "" Then Call FillBOL
'
'End Sub

'Sub FillBOL()
'
'    cboBOL.Clear
'    gsSqlStmt = " SELECT * FROM CARGO_MANIFEST WHERE LR_NUM='" & Trim(txtLrNum) & "' AND " _
'            & " COMMODITY_CODE ='" & Trim(txtCommCode) & "' AND RECIPIENT_ID='" & Trim(txtCustId) & "'" _
'            & " ORDER BY CARGO_BOL"

Private Sub grdData_Click()
    
    Call cmdClear_Click
    
    If grdData.Rows = 0 Then Exit Sub
    
    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST_ORIGINAL WHERE CONTAINER_NUM='" & Trim(grdData.Columns(16).Value) & "'"
    
    Set dsCARGO_MANIFEST_ORIGINAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_ORIGINAL.recordcount > 0 Then
        lblOrig(8) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_BOL").Value
        lblOrig(9) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_MARK").Value
        lblOrig(0) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY_EXPECTED").Value
        lblOrig(1) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY1_UNIT").Value
        lblOrig(2) = "" & dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY2_EXPECTED").Value
        lblOrig(3) = "" & dsCARGO_MANIFEST_ORIGINAL.FIELDS("QTY2_UNIT").Value
        lblOrig(4) = "" & dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_WEIGHT").Value
        lblOrig(5) = "" & dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_WEIGHT_UNIT").Value
        lblOrig(7) = "" & dsCARGO_MANIFEST_ORIGINAL.FIELDS("CARGO_LOCATION").Value
        lblOrig(6) = "" & dsCARGO_MANIFEST_ORIGINAL.FIELDS("MANIFEST_STATUS").Value
        lblOrig(10) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("LR_NUM").Value
        lblOrig(11) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("RECIPIENT_ID").Value
        lblOrig(12) = dsCARGO_MANIFEST_ORIGINAL.FIELDS("COMMODITY_CODE").Value
    End If
    
    gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM='" & Trim(grdData.Columns(16).Value) & "'"
    
    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_ORIGINAL.recordcount > 0 Then
        lblPresent(0) = dsCARGO_MANIFEST.FIELDS("CARGO_BOL").Value
        lblPresent(1) = dsCARGO_MANIFEST.FIELDS("CARGO_MARK").Value
        lblPresent(2) = dsCARGO_MANIFEST.FIELDS("LR_NUM").Value
        lblPresent(3) = dsCARGO_MANIFEST.FIELDS("QTY_EXPECTED").Value
        lblPresent(4) = "" & dsCARGO_MANIFEST.FIELDS("QTY1_UNIT").Value
        lblPresent(5) = "" & dsCARGO_MANIFEST.FIELDS("QTY2_EXPECTED").Value
        lblPresent(6) = "" & dsCARGO_MANIFEST.FIELDS("QTY2_UNIT").Value
        lblPresent(7) = "" & dsCARGO_MANIFEST.FIELDS("RECIPIENT_ID").Value
        lblPresent(8) = "" & dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT").Value
        lblPresent(9) = "" & dsCARGO_MANIFEST.FIELDS("CARGO_WEIGHT_UNIT").Value
        lblPresent(10) = "" & dsCARGO_MANIFEST.FIELDS("MANIFEST_STATUS").Value
        lblPresent(11) = dsCARGO_MANIFEST.FIELDS("CARGO_LOCATION").Value
        lblPresent(12) = dsCARGO_MANIFEST.FIELDS("COMMODITY_CODE").Value
    End If
    
End Sub

'
'    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
'        For iRec = 1 To dsCARGO_MANIFEST.RECORDCOUNT
'            cboBOL.AddItem dsCARGO_MANIFEST.FIELDS("CARGO_BOL").Value
'            dsCARGO_MANIFEST.MoveNext
'        Next iRec
'    Else
'        MsgBox " No Record Found .", vbInformation, "CARGO BOL"
'        Exit Sub
'    End If
'End Sub
Private Sub grdData_InitColumnProps()

End Sub
