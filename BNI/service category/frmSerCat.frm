VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{6B7E6392-850A-101B-AFC0-4210102A8DA7}#1.3#0"; "COMCTL32.OCX"
Begin VB.Form frmServiceCat 
   Caption         =   "BNI - SERVICE"
   ClientHeight    =   7755
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   14055
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   ScaleHeight     =   7755
   ScaleWidth      =   14055
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdDelete 
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
      Height          =   375
      Left            =   6600
      TabIndex        =   11
      Top             =   6960
      Width           =   1095
   End
   Begin VB.Frame Frame1 
      Height          =   1815
      Left            =   120
      TabIndex        =   15
      Top             =   120
      Width           =   13815
      Begin VB.TextBox txtSign 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   11160
         TabIndex        =   4
         Top             =   840
         Width           =   495
      End
      Begin VB.TextBox txtGLTask 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   11160
         TabIndex        =   7
         Top             =   1320
         Width           =   1575
      End
      Begin VB.TextBox txtSubGl 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   6240
         TabIndex        =   6
         Top             =   1320
         Width           =   1215
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
         Height          =   375
         Left            =   11160
         TabIndex        =   8
         Top             =   360
         Width           =   1095
      End
      Begin VB.TextBox txtFeeType 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   6240
         TabIndex        =   3
         Top             =   840
         Width           =   495
      End
      Begin VB.TextBox txtGL 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   1680
         TabIndex        =   5
         Top             =   1320
         Width           =   615
      End
      Begin VB.TextBox txtType 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   1680
         MaxLength       =   20
         TabIndex        =   2
         Top             =   840
         Width           =   2655
      End
      Begin VB.TextBox txtName 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   6240
         MaxLength       =   30
         TabIndex        =   1
         Top             =   360
         Width           =   3615
      End
      Begin VB.TextBox txtCode 
         Appearance      =   0  'Flat
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Left            =   1680
         MaxLength       =   4
         TabIndex        =   0
         Top             =   360
         Width           =   615
      End
      Begin VB.Label Label8 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "SERVICE SIGN  :"
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
         Left            =   9480
         TabIndex        =   23
         Top             =   840
         Width           =   1440
      End
      Begin VB.Label Label7 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "GL TASK  :"
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
         Left            =   9960
         TabIndex        =   22
         Top             =   1320
         Width           =   960
      End
      Begin VB.Label Label6 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "SUB GL-CODE  :"
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
         Left            =   4440
         TabIndex        =   21
         Top             =   1440
         Width           =   1410
      End
      Begin VB.Label Label5 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "FEE TYPE  :"
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
         Left            =   4875
         TabIndex        =   20
         Top             =   900
         Width           =   975
      End
      Begin VB.Label Label4 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "GL-CODE  :"
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
         TabIndex        =   19
         Top             =   1380
         Width           =   990
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "TYPE  :"
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
         Left            =   855
         TabIndex        =   18
         Top             =   900
         Width           =   615
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "NAME  :"
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
         Left            =   5160
         TabIndex        =   17
         Top             =   420
         Width           =   690
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   " CODE  :"
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
         Left            =   720
         TabIndex        =   16
         Top             =   420
         Width           =   720
      End
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&PRINT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   4680
      TabIndex        =   10
      Top             =   6960
      Width           =   1095
   End
   Begin ComctlLib.StatusBar StBar 
      Align           =   2  'Align Bottom
      Height          =   330
      Left            =   0
      TabIndex        =   14
      Top             =   7425
      Width           =   14055
      _ExtentX        =   24791
      _ExtentY        =   582
      Style           =   1
      SimpleText      =   ""
      _Version        =   327682
      BeginProperty Panels {0713E89E-850A-101B-AFC0-4210102A8DA7} 
         NumPanels       =   1
         BeginProperty Panel1 {0713E89F-850A-101B-AFC0-4210102A8DA7} 
            Key             =   ""
            Object.Tag             =   ""
         EndProperty
      EndProperty
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "&EXIT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   8520
      TabIndex        =   12
      Top             =   6960
      Width           =   1095
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&SAVE"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   2760
      TabIndex        =   9
      Top             =   6960
      Width           =   1095
   End
   Begin SSDataWidgets_B.SSDBGrid grdSerCat 
      Height          =   4700
      Left            =   0
      TabIndex        =   13
      Top             =   1920
      Width           =   13935
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   8
      AllowUpdate     =   0   'False
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   0
      ForeColorOdd    =   16711680
      RowHeight       =   423
      ExtraHeight     =   106
      Columns.Count   =   8
      Columns(0).Width=   1270
      Columns(0).Caption=   "CODE"
      Columns(0).Name =   "CODE"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   6059
      Columns(1).Caption=   "NAME"
      Columns(1).Name =   "NAME"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   5398
      Columns(2).Caption=   "TYPE"
      Columns(2).Name =   "TYPE"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1535
      Columns(3).Caption=   "FEE-TYPE"
      Columns(3).Name =   "FEE-TYPE"
      Columns(3).Alignment=   2
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1535
      Columns(4).Caption=   "GL-CODE"
      Columns(4).Name =   "GL-CODE"
      Columns(4).Alignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   2355
      Columns(5).Caption=   "SUB GL-CODE"
      Columns(5).Name =   "SUB GL-CODE"
      Columns(5).Alignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   4154
      Columns(6).Caption=   "GL TASK"
      Columns(6).Name =   "GL TASK"
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1138
      Columns(7).Caption=   "SIGN"
      Columns(7).Name =   "SIGN"
      Columns(7).Alignment=   2
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      _ExtentX        =   24580
      _ExtentY        =   8281
      _StockProps     =   79
      Caption         =   "COMMODITY"
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
End
Attribute VB_Name = "frmServiceCat"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim SqlStmt As String
Dim iRec As Integer

Private Sub cmdClear_Click()
    txtCode = ""
    txtName = ""
    txtType = ""
    txtFeeType = ""
    txtGL = ""
    txtSubGl = ""
    txtGLTask = ""
    txtSign = ""
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub



Private Sub cmdSave_Click()
    If txtCode = "" Or txtName = "" Or txtGL = "" Then
        MsgBox "Field(s) are empty", vbInformation, "SERVICE"
        Exit Sub
    End If
    
    SqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE='" & txtCode & "'"
    Set dsSERVICE_CATEGORY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        With dsSERVICE_CATEGORY
            OraSession.BEGINTRANS
            If .RecordCount > 0 Then
                .EDIT
                .FIELDS("SERVICE_NAME").Value = Trim(txtName)
                .FIELDS("SERVICE_TYPE").Value = Trim(txtType)
                .FIELDS("FEE_TYPE").Value = Trim(txtFeeType)
                .FIELDS("GL_CODE").Value = Trim(txtGL)
                .FIELDS("SUB_GL_CODE").Value = Trim(txtSubGl)
                .FIELDS("GL_TASK").Value = Trim(txtGLTask)
                .FIELDS("SERVICE_SIGN").Value = Trim(txtSign)
                .Update
            Else
                .AddNew
                .FIELDS("SERVICE_CODE").Value = Trim(txtCode)
                .FIELDS("SERVICE_NAME").Value = Trim(txtName)
                .FIELDS("SERVICE_TYPE").Value = Trim(txtType)
                .FIELDS("FEE_TYPE").Value = Trim(txtFeeType)
                .FIELDS("GL_CODE").Value = Trim(txtGL)
                .FIELDS("SUB_GL_CODE").Value = Trim(txtSubGl)
                .FIELDS("GL_TASK").Value = Trim(txtGLTask)
                .FIELDS("SERVICE_SIGN").Value = Trim(txtSign)
                .Update
            End If
        End With
        OraSession.COMMITTRANS
    Else
        MsgBox OraDatabase.LastServerErrText, vbCritical, "SERVICE"
        OraDatabase.LastServerErrReset
        OraSession.ROLLBACK
        Exit Sub
    End If
    
    Call GrdData
    'Call cmdPrint_Click
    
End Sub

Private Sub cmdDelete_Click()
    If Trim$(txtCode.Text) = "" Then
        MsgBox "Service Code cannot be empty!", vbInformation, "SERVICE"
        txtCode.SetFocus
        Exit Sub
    End If
    
    OraSession.BEGINTRANS
        
    SqlStmt = "DELETE FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " & Val(txtCode.Text)
    OraDatabase.ExecuteSQL (SqlStmt)
        
    If OraDatabase.LastServerErr = 0 Then
        OraSession.COMMITTRANS
        MsgBox "DELETE SUCESSFULLY.", vbInformation, "DELETE SERVICE CODE"
    Else
        MsgBox "DELETE FAILED BECAUSE OF ORACLE ERROR, " & OraDatabase.LastServerErrText, vbCritical, "DELETE SERVICE CODE"
        OraDatabase.LastServerErrReset
        OraSession.ROLLBACK
        Exit Sub
    End If
    
    Call GrdData
    Call cmdClear_Click
End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    Call GrdData
End Sub

Sub GrdData()
    grdSerCat.RemoveAll
    
    SqlStmt = "SELECT * FROM SERVICE_CATEGORY ORDER BY SERVICE_CODE"
    Set dsSERVICE_CATEGORY = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RecordCount > 0 Then
    
        With dsSERVICE_CATEGORY
            For iRec = 1 To .RecordCount
                DoEvents
                grdSerCat.AddItem .FIELDS("SERVICE_CODE").Value + Chr(9) + _
                                     .FIELDS("SERVICE_NAME").Value + Chr(9) + _
                                     Trim("" & .FIELDS("SERVICE_TYPE").Value) + Chr(9) + _
                                     Trim("" & .FIELDS("FEE_TYPE").Value) + Chr(9) + _
                                     Trim("" & .FIELDS("GL_CODE").Value) + Chr(9) + _
                                     Trim("" & .FIELDS("SUB_GL_CODE").Value) + Chr(9) + _
                                     Trim("" & .FIELDS("GL_TASK").Value) + Chr(9) + _
                                     Trim("" & .FIELDS("SERVICE_SIGN").Value)
                grdSerCat.Refresh
                .MoveNext
            Next iRec
        End With
        
        StBar.SimpleText = dsSERVICE_CATEGORY.RecordCount & "  RECORDS"
    End If
End Sub
Private Sub cmdPrint_Click()
   
    Printer.Print ""
   
    Printer.Print Tab(5); "Printed on:"; Tab(20); Date
    Printer.Print ""
    Printer.Print ""
'    Printer.FontBold = True
    Printer.FontSize = 12
    Printer.Print Tab(55); "SERVICE"
    Printer.FontSize = 9
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(7); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "--------------------------------------------------------------------"
    Printer.Print Tab(5); "SERVICE"; Tab(45); "TYPE"; Tab(65); "FEE TYPE"; Tab(80); "GL-CODE"; Tab(95); "SUB GL-CODE"; Tab(115); "GL_TASK"; Tab(135); "SIGN"
'    Printer.FontBold = False
    Printer.Print Tab(7); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "--------------------------------------------------------------------"
    
    With grdSerCat
        .MoveFirst
    
        For iRec = 0 To .Rows - 1
            
            Printer.Print Tab(5); Trim(.Columns(1).Value); Tab(45); .Columns(2).Value; Tab(65); .Columns(3).Value; Tab(80); .Columns(4).Value _
                          ; Tab(95); Trim(.Columns(5).Value); Tab(115); Trim(.Columns(6).Value); Tab(135); Trim(.Columns(7).Value)
            .MoveNext
            
        Next iRec
        
        Printer.Print Tab(7); "----------------------------------------------------------------------------------------------------------------------" _
                            ; "--------------------------------------------------------------------"
        Printer.Print Tab(15); "TOTAL RECORDS:"; Tab(45); .Rows
        Printer.Print Tab(7); "----------------------------------------------------------------------------------------------------------------------" _
                          ; "--------------------------------------------------------------------"
        .MoveFirst
    End With
    
    Printer.EndDoc
        
End Sub

Private Sub grdSerCat_Click()
    With grdSerCat
        txtCode = .Columns(0).Value
        txtName = .Columns(1).Value
        txtType = .Columns(2).Value
        txtFeeType = .Columns(3).Value
        txtGL = .Columns(4).Value
        txtSubGl = .Columns(5).Value
        txtGLTask = .Columns(6).Value
        txtSign = .Columns(7).Value
    End With
End Sub
Private Sub txtFeeType_KeyPress(KeyAscii As Integer)
     KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub txtType_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub
Private Sub txtGL_KeyPress(KeyAscii As Integer)
     KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub
