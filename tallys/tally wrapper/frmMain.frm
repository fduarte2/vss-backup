VERSION 5.00
Begin VB.Form frmMain 
   Caption         =   "Checker Tally Menu"
   ClientHeight    =   8175
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   5895
   LinkTopic       =   "Form1"
   ScaleHeight     =   8175
   ScaleWidth      =   5895
   StartUpPosition =   2  'CenterScreen
   Begin VB.Frame Frame1 
      Height          =   7935
      Left            =   120
      TabIndex        =   0
      Top             =   120
      Width           =   5655
      Begin VB.CommandButton cmdSteelOut 
         Caption         =   "Steel Outbound"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   600
         TabIndex        =   10
         Top             =   6120
         Width           =   4695
      End
      Begin VB.CommandButton CmdBookIn 
         Caption         =   "Barnett Inbound"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   600
         TabIndex        =   11
         Top             =   6960
         Width           =   2295
      End
      Begin VB.CommandButton cmdBookOut 
         Caption         =   "Barnett Outbound"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   3000
         TabIndex        =   12
         Top             =   6960
         Width           =   2295
      End
      Begin VB.CommandButton cmdDoleDTOut 
         Caption         =   "Dole Dock Ticket Outbound"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   3000
         TabIndex        =   6
         Top             =   2760
         Width           =   2295
      End
      Begin VB.CommandButton cmdDoleDTIn 
         Caption         =   "Dole Dock Ticket Inbound"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   600
         TabIndex        =   5
         Top             =   2760
         Width           =   2295
      End
      Begin VB.CommandButton cmdAbitOutbound 
         Caption         =   "Out-Bound AbitibiBowater"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   3000
         TabIndex        =   4
         Top             =   1920
         Width           =   2295
      End
      Begin VB.CommandButton cmdAbitInbound 
         Caption         =   "In-Bound AbitibiBowater"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   600
         TabIndex        =   3
         Top             =   1920
         Width           =   2295
      End
      Begin VB.CommandButton cmdKiwiOut 
         Caption         =   "Outbound Kiwi"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   600
         TabIndex        =   9
         Top             =   5280
         Width           =   4695
      End
      Begin VB.CommandButton cmdJuice 
         Caption         =   "Juice Truck Out"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   600
         TabIndex        =   8
         Top             =   4440
         Width           =   4695
      End
      Begin VB.CommandButton cmdCLMInBound 
         Caption         =   "In-Bound Clementines"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   600
         TabIndex        =   1
         Top             =   1080
         Width           =   2295
      End
      Begin VB.CommandButton cmdCLM 
         Caption         =   "Out-Bound Clememtines"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   3000
         TabIndex        =   2
         Top             =   1080
         Width           =   2295
      End
      Begin VB.CommandButton cmdFruit 
         Caption         =   "Fruit"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   735
         Left            =   600
         TabIndex        =   7
         Top             =   3600
         Width           =   4695
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         Caption         =   "Checker Tally Menu"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   18
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H00FF0000&
         Height          =   435
         Index           =   1
         Left            =   1200
         TabIndex        =   13
         Top             =   480
         Width           =   3495
      End
   End
End
Attribute VB_Name = "frmMain"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit    'Juice Proj 4/18/2007 Rudy:

Private Sub cmdAbitInbound_Click()
    
  Dim retVal As Variant
  retVal = Shell(App.Path & "\PaperInbound.exe", 1)
    
End Sub

Private Sub cmdAbitOutbound_Click()
    
  Dim retVal As Variant
  retVal = Shell(App.Path & "\PaperOutbound.exe ", 1)
    
End Sub

Private Sub cmdBookOut_Click()

  Dim retVal As Variant
  retVal = Shell(App.Path & "\BarnettTallyOut.exe", 1)
    
End Sub

Private Sub cmdBookIn_Click()

  Dim retVal As Variant
  retVal = Shell(App.Path & "\BarnettTallyIn.exe", 1)
    
End Sub

Private Sub cmdCLM_Click()
    
  Dim retVal As Variant
  retVal = Shell(App.Path & "\CLMTally.exe", 1)
    
End Sub

Private Sub cmdCLMInBound_Click()

  Dim retVal As Variant
  retVal = Shell(App.Path & "\CLMInBoundTally.exe", 1)
  
End Sub

Private Sub cmdDoleDTIn_Click()
    
  Dim retVal As Variant
  retVal = Shell(App.Path & "\DoleDTPaperInbound.exe", 1)

End Sub

Private Sub cmdDoleDTOut_Click()
    
  Dim retVal As Variant
  retVal = Shell(App.Path & "\DoleDTPaperOutbound.exe", 1)

End Sub

Private Sub cmdFruit_Click()
    
  Dim retVal As Variant
  retVal = Shell(App.Path & "\FruitTally.exe", 1)

End Sub

Private Sub cmdKiwiOut_Click()

  Dim retVal As Variant
  retVal = Shell(App.Path & "\KiwiOutboundTally.exe", 1)

End Sub

Private Sub cmdSteelOut_Click()
    
  Dim retVal As Variant
  retVal = Shell(App.Path & "\SteelOut.exe", 1)

End Sub

'Private Sub cmdPaper_Click()
'
'  Dim retVal As Variant
'  retVal = Shell(App.Path & "\PaperTally.exe", 1)
'
'End Sub


Private Sub Form_Load()
    
    'Juice Proj 4/18/2007 Rudy: commented out, huge screen, few small buttons
          'Juice Proj 4/19/2007 Rudy: commented back in
    Me.Height = Screen.Height
    Me.Width = Screen.Width
'
    Me.Frame1.Left = 0.45 * (Screen.Width - Me.Frame1.Width)
    Me.Frame1.Top = 0.4 * (Screen.Height - Me.Frame1.Height)

    'Juice Proj 4/18/2007 Rudy: looks better
'  Me.Height = Screen.Height * 0.47
'  Me.Width = Screen.Width * 0.33

    'Juice Proj 4/23/2007 Rudy: Fix Fernando's reported issue of tab order, esp important as user has no mouse.
'    cmdJuice.TabIndex = 4
'    cmdPaper.TabIndex = 5
'    cmdKiwiOut.TabIndex = 6
 
End Sub

'----------------------------Juice Proj 4/18/2007 Rudy: new code just for juice begin------------------------
Private Sub cmdJuice_Click()
    
  Dim retVal As Variant
  retVal = Shell(App.Path & "\JuiceTally.exe", 1)

End Sub

