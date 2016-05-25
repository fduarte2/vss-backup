VERSION 5.00
Object = "{3B7C8863-D78F-101B-B9B5-04021C009402}#1.2#0"; "RICHTX32.OCx"
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "comdlg32.ocx"
Begin VB.Form frmDblEntryRpt 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Overlapped Hours Exception Report"
   ClientHeight    =   7125
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   10395
   Icon            =   "frmDblEntryRpt.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   7125
   ScaleWidth      =   10395
   StartUpPosition =   3  'Windows Default
   Begin MSComDlg.CommonDialog CmDialog1 
      Left            =   9240
      Top             =   3000
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "E&XIT"
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
      Left            =   9120
      TabIndex        =   2
      ToolTipText     =   "Return Back"
      Top             =   1200
      Width           =   1215
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&PRINT"
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
      Left            =   9150
      TabIndex        =   1
      ToolTipText     =   "Print Overlapped Hours Exception  Report"
      Top             =   600
      Width           =   1215
   End
   Begin RichTextLib.RichTextBox rteDblRpt 
      Height          =   6855
      Left            =   120
      TabIndex        =   0
      Top             =   120
      Width           =   8895
      _ExtentX        =   15690
      _ExtentY        =   12091
      _Version        =   393217
      ReadOnly        =   -1  'True
      ScrollBars      =   3
      TextRTF         =   $"frmDblEntryRpt.frx":0442
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
End
Attribute VB_Name = "frmDblEntryRpt"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit              '5/2/2007 HD2759 Rudy:

'****************************************
'To Close the Current Form and Show the Previous Form
'****************************************
Private Sub cmdExit_Click()
  Unload Me
End Sub

'****************************************
'To Print the Contents of Rich Text Box
'****************************************
Private Sub cmdPrint_Click()
  On Error Resume Next
  CmDialog1.Flags = cdlPDReturnDC + cdlPDNoPageNums
  If rteDblRpt.SelLength = 0 Then
    CmDialog1.Flags = CmDialog1.Flags + cdlPDAllPages
  Else
    CmDialog1.Flags = CmDialog1.Flags + cdlPDSelection
  End If
  CmDialog1.ShowPrinter       'Display Printer Dialog Box
  rteDblRpt.SelPrint CmDialog1.hDC
  Printer.EndDoc
End Sub

Private Sub Form_Load()
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
End Sub

'****************************************
'To Close the Current Form and Show the Previous Form
'****************************************
Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  Unload Me
  If HideDblEntry = True Or OverlapEntry = True Then
    frmHiring.Show
    HideDblEntry = False
    OverlapEntry = False
  Else
    frmSelect.Show
  End If
End Sub
