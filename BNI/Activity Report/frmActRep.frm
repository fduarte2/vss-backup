VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Begin VB.Form frmActRep 
   Caption         =   "ACTIVITY REPORT"
   ClientHeight    =   8850
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   15240
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
   MaxButton       =   0   'False
   ScaleHeight     =   8850
   ScaleWidth      =   15240
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdEmail 
      Caption         =   "Email File"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   9240
      TabIndex        =   20
      Top             =   3000
      Width           =   1095
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
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
      Left            =   5226
      TabIndex        =   9
      Top             =   3000
      Width           =   975
   End
   Begin VB.CommandButton cmdRet 
      BackColor       =   &H0000FFFF&
      Caption         =   "&Run"
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
      Left            =   3840
      MaskColor       =   &H0000FFFF&
      TabIndex        =   7
      Top             =   3000
      Width           =   975
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "&Exit"
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
      Left            =   10560
      TabIndex        =   12
      Top             =   3000
      Width           =   975
   End
   Begin VB.CommandButton cmdClear 
      Caption         =   "&Clear"
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
      Left            =   7998
      TabIndex        =   11
      Top             =   3000
      Width           =   975
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&Print"
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
      Left            =   6612
      TabIndex        =   10
      Top             =   3000
      Width           =   975
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   5175
      Left            =   120
      TabIndex        =   8
      Top             =   3600
      Width           =   15015
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   16
      AllowAddNew     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   0
      ForeColorOdd    =   8388608
      RowHeight       =   450
      ExtraHeight     =   26
      Columns.Count   =   16
      Columns(0).Width=   3043
      Columns(0).Caption=   "VESSEL"
      Columns(0).Name =   "VESSEL"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1720
      Columns(1).Caption=   "DATE"
      Columns(1).Name =   "DATE"
      Columns(1).Alignment=   2
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1508
      Columns(2).Caption=   "BL#"
      Columns(2).Name =   "BL#"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1244
      Columns(3).Caption=   "W/O"
      Columns(3).Name =   "W/O"
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1270
      Columns(4).Caption=   "LOAD#"
      Columns(4).Name =   "LOAD#"
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   3200
      Columns(5).Caption=   "DELIVER-TO"
      Columns(5).Name =   "DELIVER-TO"
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   2090
      Columns(6).Caption=   "CARRIER"
      Columns(6).Name =   "CARRIER"
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1958
      Columns(7).Caption=   "ITEM#"
      Columns(7).Name =   "ITEM#"
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   4207
      Columns(8).Caption=   "LOT/MARK"
      Columns(8).Name =   "LOT/MARK"
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   847
      Columns(9).Caption=   "PLT"
      Columns(9).Name =   "PLT"
      Columns(9).Alignment=   1
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   953
      Columns(10).Caption=   "BIN"
      Columns(10).Name=   "BIN"
      Columns(10).Alignment=   1
      Columns(10).CaptionAlignment=   2
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   979
      Columns(11).Caption=   "DRM"
      Columns(11).Name=   "DRM"
      Columns(11).Alignment=   1
      Columns(11).CaptionAlignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   1164
      Columns(12).Caption=   "GAL"
      Columns(12).Name=   "GAL"
      Columns(12).Alignment=   1
      Columns(12).CaptionAlignment=   2
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   1244
      Columns(13).Caption=   "BAL"
      Columns(13).Name=   "BAL"
      Columns(13).Alignment=   1
      Columns(13).CaptionAlignment=   2
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      Columns(14).Width=   3200
      Columns(14).Visible=   0   'False
      Columns(14).Caption=   "LOTNUM"
      Columns(14).Name=   "LOTNUM"
      Columns(14).DataField=   "Column 14"
      Columns(14).DataType=   8
      Columns(14).FieldLen=   256
      Columns(15).Width=   3200
      Columns(15).Visible=   0   'False
      Columns(15).Caption=   "ACTNUM"
      Columns(15).Name=   "ACTNUM"
      Columns(15).DataField=   "Column 15"
      Columns(15).DataType=   8
      Columns(15).FieldLen=   256
      _ExtentX        =   26485
      _ExtentY        =   9128
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
   Begin VB.Frame Frame1 
      Height          =   2655
      Left            =   1853
      TabIndex        =   13
      Top             =   120
      Width           =   11535
      Begin VB.CommandButton cmdlist 
         Caption         =   "..."
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Index           =   1
         Left            =   3840
         TabIndex        =   5
         Top             =   2002
         Width           =   495
      End
      Begin VB.TextBox txtVessel 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   4560
         TabIndex        =   19
         TabStop         =   0   'False
         Top             =   2002
         Width           =   5175
      End
      Begin VB.TextBox txtLrNum 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   1800
         TabIndex        =   4
         Top             =   2002
         Width           =   1815
      End
      Begin VB.CheckBox chkAll 
         Caption         =   "All"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Left            =   10200
         TabIndex        =   6
         Top             =   2040
         Value           =   1  'Checked
         Width           =   855
      End
      Begin VB.CommandButton cmdlist 
         Caption         =   "..."
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   330
         Index           =   0
         Left            =   3840
         TabIndex        =   3
         Top             =   1200
         Width           =   495
      End
      Begin VB.TextBox txtCustName 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   4560
         TabIndex        =   14
         TabStop         =   0   'False
         Top             =   1200
         Width           =   5175
      End
      Begin VB.TextBox txtCustId 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   1800
         TabIndex        =   2
         Top             =   1200
         Width           =   1815
      End
      Begin MSComCtl2.DTPicker DtpEndDt 
         Height          =   375
         Left            =   6480
         TabIndex        =   1
         Top             =   480
         Width           =   1215
         _ExtentX        =   2143
         _ExtentY        =   661
         _Version        =   393216
         CustomFormat    =   "MM/dd/yyyy"
         Format          =   20643843
         CurrentDate     =   36726
      End
      Begin MSComCtl2.DTPicker DtpStartDt 
         Height          =   375
         Left            =   1800
         TabIndex        =   0
         Top             =   480
         Width           =   1215
         _ExtentX        =   2143
         _ExtentY        =   661
         _Version        =   393216
         CustomFormat    =   "MM/dd/yyyy"
         Format          =   20643843
         CurrentDate     =   36726
      End
      Begin VB.Label Label4 
         AutoSize        =   -1  'True
         Caption         =   "Vessel  :"
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
         Left            =   840
         TabIndex        =   18
         Top             =   2055
         Width           =   705
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         Caption         =   "Customer  :"
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
         Left            =   585
         TabIndex        =   17
         Top             =   1260
         Width           =   960
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         Caption         =   "End Date  :"
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
         Left            =   5400
         TabIndex        =   16
         Top             =   555
         Width           =   870
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         Caption         =   "Start Date  :"
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
         Left            =   555
         TabIndex        =   15
         Top             =   555
         Width           =   990
      End
   End
End
Attribute VB_Name = "frmActRep"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim iRec As Integer
Dim SqlStmt As String
Dim iPos As Integer
Dim iCount As Integer
Dim iCountpallet As Double
Dim iCountBin As Double
Dim iCountDrum As Double
Dim iCountGallon As Double
Dim sLotNum() As String
Dim bSave As Boolean
Dim bAll As Boolean
Dim iLrNum As Integer

Private Sub chkAll_Click()
    If chkAll.Value = vbChecked Then
        txtLrNum = ""
        txtVessel = ""
    End If
End Sub

Private Sub cmdClear_Click()
    txtCustId = ""
    txtCustName = ""
    grdData.RemoveAll
    DtpStartDt.Value = Format(Now, "MM/DD/YYYY")
    DtpEndDt.Value = Format(Now, "MM/DD/YYYY")
    DtpStartDt.SetFocus
End Sub

Private Sub cmdEmail_Click()
    Dim iRec As Long
    
    If grdData.Rows = 0 Then Exit Sub
    
    If grdData.DataChanged = True Then
        If MsgBox("Do you want to save the changes", vbYesNo + vbQuestion, sMsg) = vbYes Then
            cmdSave_Click
        End If
    End If
    
    On Error GoTo errhandler
    
    'Open "C:\Seth\output.txt" For Output As #1
    Open "D:\Inventory\Activity_output.txt" For Output As #1
            
    grdData.MoveFirst
    
    Print #1, Tab(5); "Printed on " & Format(Now, "MM/DD/YYYY");
    Print #1, Tab(50); ; "PORT OF WILMINGTON, DELAWARE"
    Print #1, ""
    Print #1, Tab(70); "ACTIVITY REPORT"
    Print #1, ""
    Print #1, ""
    Print #1, Tab(5); "Customer"; Tab(15); " : "; Tab(20); Trim(txtCustName)
    Print #1, ""
    Print #1, Tab(5); "From "; Tab(15); " : "; Tab(20); Format(DtpStartDt, "MM/DD/YYYY"); Tab(45); "TO :   " & Format(DtpEndDt, "MM/DD/YYYY")
    Print #1, ""
    
    Print #1, "----------------------------------------------------------------------------------------------------" _
                   ; "---------------------------------------------------------------------------------------"

    Print #1, Tab(3); "VESSEL"; Tab(35); "SHIP DATE"; _
                  Tab(49); "BOL"; Tab(64); "W/O; "; _
                  Tab(74); "LOAD #"; Tab(89); "DELIVER-TO"; _
                  Tab(111); "CARRIER"; Tab(129); "ITEM"; _
                  Tab(145); "LOT/MARK"; Tab(180); "BIN"; Tab(186); "DRM"
                  
    Print #1, "----------------------------------------------------------------------------------------------------" _
                   ; "---------------------------------------------------------------------------------------"
                   
    For iRec = 0 To grdData.Rows - 1
        
        Print #1, ""
        
        Print #1, Tab(3); grdData.Columns(0).Value; Tab(35); grdData.Columns(1).Value; _
                      Tab(49); grdData.Columns(2).Value; Tab(64); grdData.Columns(3).Value; _
                      Tab(74); grdData.Columns(4).Value; Tab(89); grdData.Columns(5).Value; _
                      Tab(111); grdData.Columns(6).Value; Tab(129); grdData.Columns(7).Value; _
                      Tab(145); grdData.Columns(8).Value; Tab(180); grdData.Columns(10).Value; Tab(186); grdData.Columns(11).Value
                      
        grdData.MoveNext
    Next iRec
    Print #1, ""
    
    Print #1, "----------------------------------------------------------------------------------------------------" _
                   ; "---------------------------------------------------------------------------------------"

    Print #1, Tab(40); "SHIPMENT TOTAL :"; Tab(55); grdData.Rows _
                  ; Tab(165); "TOTALS :"; Tab(180); iCountBin; Tab(186); iCountDrum
                  
    Print #1, "----------------------------------------------------------------------------------------------------" _
                   ; "---------------------------------------------------------------------------------------"
    
    grdData.MoveFirst
    
    Close #1
    
    Exit Sub
    
errhandler:
    MsgBox "ERROR # : " & Err.Number & vbCrLf & Err.Description, vbCritical, "ERROR"
    Err.Clear
    Exit Sub
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub



Private Sub cmdlist_Click(Index As Integer)
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.lstPV.Clear
    
    Select Case Index
        Case 0
            frmPV.Caption = "Customer List"
            SqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
            Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.recordcount > 0 Then
                For iRec = 1 To dsCUSTOMER_PROFILE.recordcount
                    frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value
                    dsCUSTOMER_PROFILE.MoveNext
                Next iRec
            End If
        
            frmPV.Show vbModal
            If gsPVItem <> "" Then
                iPos = InStr(gsPVItem, " : ")
                If iPos > 0 Then
                    txtCustId.Text = Left$(gsPVItem, iPos - 1)
                    txtCustName.Text = Mid$(gsPVItem, iPos + 3)
                End If
            End If
        
        Case 1
        
            frmPV.Caption = "Vessel List"
            SqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
            Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.recordcount > 0 Then
                For iRec = 1 To dsVESSEL_PROFILE.recordcount
                    frmPV.lstPV.AddItem dsVESSEL_PROFILE.FIELDS("LR_NUM").Value & " : " & dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
                    dsVESSEL_PROFILE.MoveNext
                Next iRec
            End If
        
            frmPV.Show vbModal
            If gsPVItem <> "" Then
                iPos = InStr(gsPVItem, " : ")
                If iPos > 0 Then
                    txtLrNum = Left$(gsPVItem, iPos - 1)
                    txtVessel.Text = Mid$(gsPVItem, iPos + 3)
                End If
            End If
    End Select
        
End Sub
Private Sub cmdPrint_Click()
    Dim iRec As Long
    
    If grdData.Rows = 0 Then Exit Sub
    
    If grdData.DataChanged = True Then
        If MsgBox("Do you want to save the changes", vbYesNo + vbQuestion, sMsg) = vbYes Then
            cmdSave_Click
        End If
    End If
    
    On Error GoTo errhandler
    
    Printer.Orientation = vbPRORLandscape
    Printer.FontSize = 10
        
    grdData.MoveFirst
    Printer.Print Tab(5); "Printed on " & Format(Now, "MM/DD/YYYY");
    Printer.FontBold = True
    Printer.FontSize = 12
    Printer.Print Tab(50); ; "PORT OF WILMINGTON, DELAWARE"
    Printer.FontSize = 9
    Printer.Print ""
    Printer.Print Tab(70); "ACTIVITY REPORT"
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(5); "Customer"; Tab(15); " : "; Tab(20); Trim(txtCustName)
    Printer.Print ""
    Printer.Print Tab(5); "From "; Tab(15); " : "; Tab(20); Format(DtpStartDt, "MM/DD/YYYY"); Tab(45); "TO :   " & Format(DtpEndDt, "MM/DD/YYYY")
    Printer.Print ""
    Printer.FontBold = False
    Printer.FontSize = 9
    
    Printer.Print "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------"

    Printer.Print Tab(3); "VESSEL"; Tab(35); "SHIP DATE"; _
                  Tab(49); "BOL"; Tab(64); "W/O; "; _
                  Tab(74); "LOAD #"; Tab(89); "DELIVER-TO"; _
                  Tab(111); "CARRIER"; Tab(129); "ITEM"; _
                  Tab(145); "LOT/MARK"; Tab(180); "BIN"; Tab(186); "DRM"
                  
    Printer.Print "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------"
    
    For iRec = 0 To grdData.Rows - 1
        
        Printer.Print ""
        
        Printer.Print Tab(3); grdData.Columns(0).Value; Tab(35); grdData.Columns(1).Value; _
                      Tab(49); grdData.Columns(2).Value; Tab(64); grdData.Columns(3).Value; _
                      Tab(74); grdData.Columns(4).Value; Tab(89); grdData.Columns(5).Value; _
                      Tab(111); grdData.Columns(6).Value; Tab(129); grdData.Columns(7).Value; _
                      Tab(145); grdData.Columns(8).Value; Tab(180); grdData.Columns(10).Value; Tab(186); grdData.Columns(11).Value
                      
        grdData.MoveNext
    Next iRec
    Printer.Print ""
    
    Printer.Print "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------"
    

    Printer.Print Tab(40); "SHIPMENT TOTAL :"; Tab(50); grdData.Rows _
                  ; Tab(165); "TOTALS :"; Tab(180); iCountBin; Tab(186); iCountDrum
                  
    Printer.Print "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------" _
                   ; "----------------------------------------------------------------------------------------------------"
                   
    
    grdData.MoveFirst
    
    Printer.EndDoc
    
    Exit Sub
    
errhandler:
    MsgBox "ERROR # : " & Err.Number & vbCrLf & Err.Description, vbCritical, "ERROR"
    Err.Clear
    Exit Sub
   
End Sub

Private Sub cmdRet_Click()
    Dim sLotNum As String
    Dim iQty As Long
    Dim sOrderNum As String
    Dim sDate As String
    Dim sDeliveryNum As String
    Dim sDeliverTo As String
    Dim sCarrier As String
    Dim sMark As String
    Dim sQtyUnit1 As String
    Dim sQtyUnit2 As String
    Dim iQty2 As Long
    Dim iPallet As Integer
    Dim iBin As Double
    Dim iDrum As Double
    Dim sItem As String
    Dim sActNum As String
    Dim iQtyIn As Integer
    Dim sVessel As String
    Dim sBOL As String
    Dim sOldVessel As String
    Dim sPrintVessel As String
    Dim iQtyChg As Integer
    Dim bFirst As Boolean
    Dim jRec As Integer
    Dim dsCARGO_ACT As Object
    
    grdData.RemoveAll
    iCountpallet = 0
    iCountBin = 0
    iCountDrum = 0
    iCountGallon = 0
    bAll = False
    sOldVessel = ""
    sPrintVessel = ""
    iQtyIn = 0
    
    
    If DtpStartDt > DtpEndDt Then
        MsgBox "Invalid Time Period", vbInformation, sMsg
        Exit Sub
    End If
    
    If txtCustId = "" Then
        MsgBox "Enter Customer", vbInformation, sMsg
        Exit Sub
    End If
    
    If txtLrNum = "" Then chkAll.Value = vbChecked
    
'    If chkAll.Value = vbChecked Then
'
'        bAll = True
'
'        SqlStmt = " SELECT * FROM CARGO_TRACKING CT,CARGO_MANIFEST CM,VOYAGE_CARGO VC" _
'              & " WHERE CT.LOT_NUM=VC.LOT_NUM " _
'              & " AND CT.OWNER_ID='" & Trim(txtCustId) & "'" _
'              & " AND CM.CONTAINER_NUM=VC.LOT_NUM " _
'              & " ORDER BY VC.LR_NUM"
'
'    Else
'        SqlStmt = " SELECT * FROM CARGO_TRACKING CT,CARGO_MANIFEST CM,VOYAGE_CARGO VC" _
'              & " WHERE CT.LOT_NUM=VC.LOT_NUM " _
'              & " AND CT.OWNER_ID='" & Trim(txtCustId) & "'" _
'              & " AND CM.CONTAINER_NUM=VC.LOT_NUM " _
'              & " AND VC.LR_NUM='" & Trim(txtLrNum) & "'"
'    End If
'
'    Set dsCARGO_TRACKING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
'    If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.recordcount > 0 Then
'        For iRec = 1 To dsCARGO_TRACKING.recordcount
'
'            bFirst = True
'            sBOL = Trim("" & dsCARGO_TRACKING.FIELDS("CARGO_BOL").Value)
'            sMark = Trim("" & dsCARGO_TRACKING.FIELDS("CARGO_MARK").Value)
'            sLotNum = dsCARGO_TRACKING.FIELDS("LOT_NUM").Value
'            iLrNum = dsCARGO_TRACKING.FIELDS("LR_NUM").Value
'            iQty = dsCARGO_TRACKING.FIELDS("QTY_RECEIVED").Value
'            sQtyUnit1 = Trim("" & dsCARGO_TRACKING.FIELDS("QTY1_UNIT").Value)
'            sQtyUnit2 = Trim("" & dsCARGO_TRACKING.FIELDS("QTY2_UNIT").Value)
'
'            If bAll = True Then
'                SqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM='" & iLrNum & "'"
'                Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
'                sVessel = dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
'            Else
'                 sVessel = Trim(txtVessel)
'            End If
'
'
'            SqlStmt = " SELECT * FROM CARGO_ACTIVITY WHERE LOT_NUM='" & sLotNum & "' AND " _
'                    & " CUSTOMER_ID='" & Trim(txtCustId) & "' AND " _
'                    & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(DtpStartDt, "MM/DD/YYYY") & "','MM/DD/YYYY') AND " _
'                    & " DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpEndDt, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
'                    & " ORDER BY DATE_OF_ACTIVITY "
'            Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
'            If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.recordcount > 0 Then
'
'                SqlStmt = " SELECT SUM(QTY_CHANGE) QTYSUM FROM CARGO_ACTIVITY WHERE " _
'                    & " CUSTOMER_ID='" & Trim(txtCustId) & "' AND LOT_NUM='" & sLotNum & "' AND " _
'                    & " DATE_OF_ACTIVITY < TO_DATE('" & Format(DtpStartDt, "MM/DD/YYYY") & "','MM/DD/YYYY')"
'
'                Set dsCARGO_ACT = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
'                If OraDatabase.LastServerErr = 0 And dsCARGO_ACT.recordcount > 0 Then
'                    iQtyIn = 0 + Val(Trim("" & dsCARGO_ACT.FIELDS("QTYSUM").Value))
'                End If
'
'                iQty = iQty - iQtyIn
'                grdData.AddItem sVessel + Chr(9) + Chr(9) + sBOL + Chr(9) + _
'                            Chr(9) + Chr(9) + "BEG BALANCE" + Chr(9) + _
'                            Chr(9) + Chr(9) + sMark + Chr(9) + _
'                            Chr(9) + Chr(9) + Chr(9) + _
'                            Chr(9) + CStr(iQty)
'
'
'
'                For jRec = 1 To dsCARGO_ACTIVITY.recordcount
'
'                    DoEvents
'                    iPallet = 0
'                    iBin = 0
'                    iDrum = 0
'
'                    With dsCARGO_ACTIVITY
'                        sOrderNum = Trim("" & .FIELDS("ORDER_NUM").Value)
'                        sDate = Format(.FIELDS("DATE_OF_ACTIVITY").Value, "MM/DD/YYYY")
'                        sActNum = .FIELDS("ACTIVITY_NUM").Value
'                        iQtyChg = .FIELDS("QTY_CHANGE").Value
'                    End With
'
'
'
'
'                    SqlStmt = " SELECT * FROM CARGO_DELIVERY WHERE LOT_NUM='" & dsCARGO_ACTIVITY.FIELDS("LOT_NUM").Value & "' AND" _
'                            & " ACTIVITY_NUM=' " & dsCARGO_ACTIVITY.FIELDS("ACTIVITY_NUM").Value & "' AND" _
'                            & " SERVICE_CODE=' " & dsCARGO_ACTIVITY.FIELDS("SERVICE_CODE").Value & "' AND" _
'                            & " DELIVERY_NUM > 0 "
'
'                    Set dsCARGO_DELIVERY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
'                    If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY.recordcount > 0 Then
'                        With dsCARGO_DELIVERY
'                            If Not IsNull(.FIELDS("DELIVERY_NUM").Value) Then
'                                sDeliveryNum = .FIELDS("DELIVERY_NUM").Value
'                                iPos = InStr(1, .FIELDS("DELIVER_TO").Value, Chr(13))
'                                If iPos <> 0 Then
'                                    sDeliverTo = Trim(Mid(.FIELDS("DELIVER_TO").Value, 1, iPos - 1))
'                                Else
'                                    sDeliverTo = Trim(.FIELDS("DELIVER_TO").Value)
'                                End If
'                                If Not IsNull(.FIELDS("DELIVERY_DESCRIPTION").Value) Then
'                                    sCarrier = .FIELDS("DELIVERY_DESCRIPTION").Value
'                                End If
'                            End If
'                        End With
'                    End If
'
'
'
'                    SqlStmt = " SELECT * FROM CARGO_ACTIVITY_EXT WHERE " _
'                            & " LOT_NUM='" & dsCARGO_ACTIVITY.FIELDS("LOT_NUM").Value & "'AND " _
'                            & " ACTIVITY_NUM='" & dsCARGO_ACTIVITY.FIELDS("ACTIVITY_NUM").Value & "'"
'
'                    Set dsCARGO_ACTIVITY_EXT = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
'                    If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY_EXT.recordcount > 0 Then
'                        With dsCARGO_ACTIVITY_EXT
'                            iQty2 = 0 + .FIELDS("QTY2").Value
'                            sItem = Trim("" & .FIELDS("ITEM").Value)
'                        End With
'                    End If
'
'                    Select Case sQtyUnit1
'                        Case "PLT"
'                            iPallet = iQtyChg
'                            If sQtyUnit2 = "BIN" Then
'                                iBin = iQtyChg
'                            ElseIf sQtyUnit2 = "DRUM" Then
'                                iDrum = iQty2
'                            End If
'                        Case "BIN"
'                            iBin = iQty
'                            If sQtyUnit2 = "PLT" Then iPallet = iQty2
'                        Case "DRUM"
'                            iDrum = iQty
'                            If sQtyUnit2 = "PLT" Then iPallet = iQty2
'                    End Select
'
'                    iCountpallet = iQty + iCountpallet
'                    iCountBin = iBin + iCountBin
'                    iCountDrum = iDrum + iCountDrum
'                    iCountGallon = (300 * iBin + 64 * iDrum) + iCountGallon
'
'                    iQty = iQty - iQtyChg
'
'                    grdData.AddItem Chr(9) + sDate + Chr(9) + sBOL + Chr(9) + _
'                                    sDeliveryNum + Chr(9) + sOrderNum + Chr(9) + sDeliverTo + Chr(9) + _
'                                    sCarrier + Chr(9) + sItem + Chr(9) + sMark + Chr(9) + _
'                                    CStr(iPallet) + Chr(9) + CStr(iBin) + Chr(9) + CStr(iDrum) + Chr(9) + _
'                                    CStr(300 * iBin + 64 * iDrum) + Chr(9) + _
'                                    CStr(iQty) + Chr(9) + sLotNum + Chr(9) + sActNum
'
'
'
'                    grdData.Refresh
'
'                    dsCARGO_ACTIVITY.MoveNext
'                Next jRec
'            End If
'            dsCARGO_TRACKING.MoveNext
'        Next iRec
'    Else
'        If OraDatabase.LastServerErr <> 0 Then
'            MsgBox OraDatabase.LastServerErrText, vbCritical, sMsg
'            OraDatabase.LastServerErrReset
'            Exit Sub
'        End If
'
'        MsgBox "No Records Found", vbInformation, sMsg
'        Exit Sub
'    End If
    
    
    ' OK, this is going to look really bad, but this is VB, so it already does ;) - STM
    If chkAll.Value = vbChecked Then
      bAll = True
      SqlStmt = "SELECT * FROM CARGO_TRACKING CT,CARGO_MANIFEST CM,VOYAGE_CARGO VC" _
              & " Where CT.LOT_NUM = VC.LOT_NUM" _
              & " AND CM.CONTAINER_NUM=VC.LOT_NUM AND" _
              & " (CT.LOT_NUM in (select lot_num from" _
              & " cargo_activity where customer_id = '" & Trim(txtCustId) & "' and" _
              & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(DtpStartDt, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
              & " AND DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpEndDt, "MM/DD/YYYY") & "','MM/DD/YYYY')))" _
              & " ORDER BY VC.LR_NUM"
    Else
      SqlStmt = "SELECT * FROM CARGO_TRACKING CT,CARGO_MANIFEST CM,VOYAGE_CARGO VC" _
              & " Where CT.LOT_NUM = VC.LOT_NUM" _
              & " AND CM.CONTAINER_NUM=VC.LOT_NUM " _
              & " AND VC.LR_NUM='" & Trim(txtLrNum) & "' AND" _
              & " (CT.LOT_NUM in (select lot_num from" _
              & " cargo_activity where customer_id = '" & Trim(txtCustId) & "' and" _
              & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(DtpStartDt, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
              & " AND DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpEndDt, "MM/DD/YYYY") & "','MM/DD/YYYY')))"
    End If
              
              
    Set dsCARGO_TRACKING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.recordcount > 0 Then
        For iRec = 1 To dsCARGO_TRACKING.recordcount
    
            bFirst = True
            sBOL = Trim("" & dsCARGO_TRACKING.FIELDS("CARGO_BOL").Value)
            sMark = Trim("" & dsCARGO_TRACKING.FIELDS("CARGO_MARK").Value)
            sLotNum = dsCARGO_TRACKING.FIELDS("LOT_NUM").Value
            iLrNum = dsCARGO_TRACKING.FIELDS("LR_NUM").Value
            iQty = dsCARGO_TRACKING.FIELDS("QTY_RECEIVED").Value
            sQtyUnit1 = Trim("" & dsCARGO_TRACKING.FIELDS("QTY1_UNIT").Value)
            sQtyUnit2 = Trim("" & dsCARGO_TRACKING.FIELDS("QTY2_UNIT").Value)
   
            If bAll = True Then
                SqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM='" & iLrNum & "'"
                Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                sVessel = dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
            Else
                 sVessel = Trim(txtVessel)
            End If
                    
            
            SqlStmt = " SELECT * FROM CARGO_ACTIVITY WHERE LOT_NUM='" & sLotNum & "' AND " _
                    & " CUSTOMER_ID='" & Trim(txtCustId) & "' AND " _
                    & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(DtpStartDt, "MM/DD/YYYY") & "','MM/DD/YYYY') AND " _
                    & " DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpEndDt, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                    & " ORDER BY DATE_OF_ACTIVITY "
            Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.recordcount > 0 Then
                
                SqlStmt = " SELECT SUM(QTY_CHANGE) QTYSUM FROM CARGO_ACTIVITY WHERE " _
                    & " CUSTOMER_ID='" & Trim(txtCustId) & "' AND LOT_NUM='" & sLotNum & "' AND " _
                    & " DATE_OF_ACTIVITY < TO_DATE('" & Format(DtpStartDt, "MM/DD/YYYY") & "','MM/DD/YYYY')"
            
                Set dsCARGO_ACT = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsCARGO_ACT.recordcount > 0 Then
                    iQtyIn = 0 + Val(Trim("" & dsCARGO_ACT.FIELDS("QTYSUM").Value))
                End If
                
                ' Added Adam Walter, 3/27/06; only withdrawls should be shown, this quick SQL check
                ' is the first half of what will prevent transfers from displaying
                SqlStmt = " SELECT * FROM CARGO_ACTIVITY WHERE LOT_NUM='" & sLotNum & "' AND " _
                    & " CUSTOMER_ID='" & Trim(txtCustId) & "' AND " _
                    & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(DtpStartDt, "MM/DD/YYYY") & "','MM/DD/YYYY') AND " _
                    & " DATE_OF_ACTIVITY <= TO_DATE('" & Format(DtpEndDt, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                    & " AND (ORDER_NUM != '0' OR ORDER_NUM IS NULL) ORDER BY DATE_OF_ACTIVITY "
                Set dsCARGO_ACT_NONTRANSFER_COUNT = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                
                If (dsCARGO_ACT_NONTRANSFER_COUNT.recordcount > 0) Then
                    iQty = iQty - iQtyIn
                    grdData.AddItem sVessel + Chr(9) + Chr(9) + sBOL + Chr(9) + _
                                Chr(9) + Chr(9) + "BEG BALANCE" + Chr(9) + _
                                Chr(9) + Chr(9) + sMark + Chr(9) + _
                                Chr(9) + Chr(9) + Chr(9) + _
                                Chr(9) + CStr(iQty)
                End If
                
                
                For jRec = 1 To dsCARGO_ACTIVITY.recordcount
                    ' Added Adam Walter, 3/27/06; only withdrawls should be shown, this if statement
                    ' is the other half of what will prevent Transfers from displaying
                    If (dsCARGO_ACTIVITY.FIELDS("ORDER_NUM").Value <> 0 Or IsNull(dsCARGO_ACTIVITY.FIELDS("ORDER_NUM").Value)) Then
    
                        DoEvents
                        iPallet = 0
                        iBin = 0
                        iDrum = 0
            
                        With dsCARGO_ACTIVITY
                            sOrderNum = Trim("" & .FIELDS("ORDER_NUM").Value)
                            sDate = Format(.FIELDS("DATE_OF_ACTIVITY").Value, "MM/DD/YYYY")
                            sActNum = .FIELDS("ACTIVITY_NUM").Value
                            iQtyChg = .FIELDS("QTY_CHANGE").Value
                        End With
        
    
                        
        
                        SqlStmt = " SELECT * FROM CARGO_DELIVERY WHERE LOT_NUM='" & dsCARGO_ACTIVITY.FIELDS("LOT_NUM").Value & "' AND" _
                                & " ACTIVITY_NUM=' " & dsCARGO_ACTIVITY.FIELDS("ACTIVITY_NUM").Value & "' AND" _
                                & " SERVICE_CODE=' " & dsCARGO_ACTIVITY.FIELDS("SERVICE_CODE").Value & "' AND" _
                                & " DELIVERY_NUM > 0 "
        
                        Set dsCARGO_DELIVERY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                        If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY.recordcount > 0 Then
                            With dsCARGO_DELIVERY
                                If Not IsNull(.FIELDS("DELIVERY_NUM").Value) Then
                                    sDeliveryNum = .FIELDS("DELIVERY_NUM").Value
                                    iPos = InStr(1, .FIELDS("DELIVER_TO").Value, Chr(13))
                                    If iPos <> 0 Then
                                        sDeliverTo = Trim(Mid(.FIELDS("DELIVER_TO").Value, 1, iPos - 1))
                                    Else
                                        sDeliverTo = Trim(.FIELDS("DELIVER_TO").Value)
                                    End If
                                    If Not IsNull(.FIELDS("DELIVERY_DESCRIPTION").Value) Then
                                        sCarrier = .FIELDS("DELIVERY_DESCRIPTION").Value
                                    End If
                                End If
                            End With
                        End If
    
    
    
                        SqlStmt = " SELECT * FROM CARGO_ACTIVITY_EXT WHERE " _
                                & " LOT_NUM='" & dsCARGO_ACTIVITY.FIELDS("LOT_NUM").Value & "'AND " _
                                & " ACTIVITY_NUM='" & dsCARGO_ACTIVITY.FIELDS("ACTIVITY_NUM").Value & "'"
        
                        Set dsCARGO_ACTIVITY_EXT = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                        If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY_EXT.recordcount > 0 Then
                            With dsCARGO_ACTIVITY_EXT
                                iQty2 = 0 + .FIELDS("QTY2").Value
                                sItem = Trim("" & .FIELDS("ITEM").Value)
                            End With
                        End If
               
                        Select Case sQtyUnit1
                            Case "PLT"
                                iPallet = iQtyChg
                                If sQtyUnit2 = "BIN" Then
                                    iBin = iQtyChg
                                ElseIf sQtyUnit2 = "DRUM" Then
                                    iDrum = iQty2
                                End If
                            Case "BIN"
                                iBin = iQty
                                If sQtyUnit2 = "PLT" Then iPallet = iQty2
                            Case "DRUM"
                                iDrum = iQty
                                If sQtyUnit2 = "PLT" Then iPallet = iQty2
                        End Select
                
                        iCountpallet = iQty + iCountpallet
                        iCountBin = iBin + iCountBin
                        iCountDrum = iDrum + iCountDrum
                        iCountGallon = (300 * iBin + 64 * iDrum) + iCountGallon
                
                        iQty = iQty - iQtyChg
                
                        grdData.AddItem Chr(9) + sDate + Chr(9) + sBOL + Chr(9) + _
                                        sDeliveryNum + Chr(9) + sOrderNum + Chr(9) + sDeliverTo + Chr(9) + _
                                        sCarrier + Chr(9) + sItem + Chr(9) + sMark + Chr(9) + _
                                        CStr(iPallet) + Chr(9) + CStr(iBin) + Chr(9) + CStr(iDrum) + Chr(9) + _
                                        CStr(300 * iBin + 64 * iDrum) + Chr(9) + _
                                        CStr(iQty) + Chr(9) + sLotNum + Chr(9) + sActNum
                
                
                
                        grdData.Refresh
                    End If
                    dsCARGO_ACTIVITY.MoveNext
                Next jRec
            End If
            dsCARGO_TRACKING.MoveNext
        Next iRec
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErrText, vbCritical, sMsg
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
        
        MsgBox "No Records Found", vbInformation, sMsg
        Exit Sub
    End If
End Sub

Private Sub cmdSave_Click()
    Dim sString As String
    Dim i As Integer
    Dim bError As Boolean
    
    ReDim sLotNum(grdData.Rows)
    
    i = 0
    If grdData.Rows < 0 Then Exit Sub
        
    grdData.MoveFirst
    
    For iRec = 0 To grdData.Rows - 1
        If Len(Trim(grdData.Columns(7).Value)) > 15 Then
            MsgBox "Row " & iRec + 1 & " contains larger value" & vbCrLf & "Records should not be more then 15 characters long", vbInformation, sMsg
            Exit Sub
        End If
        grdData.MoveNext
    Next iRec
    
    grdData.MoveFirst
    
    For iRec = 0 To grdData.Rows - 1
        If grdData.Columns(14).Value <> "" And grdData.Columns(15).Value <> "" Then
            If Trim(grdData.Columns(7).Value) <> "" Then
                SqlStmt = " SELECT * FROM CARGO_ACTIVITY_EXT WHERE LOT_NUM='" & grdData.Columns(14).Value & "'" _
                        & " AND ACTIVITY_NUM ='" & Val(grdData.Columns(15).Value) & "'"
                    
                Set dsCARGO_ACTIVITY_EXT = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY_EXT.recordcount > 0 Then
                    With dsCARGO_ACTIVITY_EXT
                        .EDIT
                        .FIELDS("ITEM").Value = Trim(grdData.Columns(7).Value)
                        .Update
                    End With
                Else
                    If OraDatabase.LastServerErr <> 0 Then
                        MsgBox OraDatabase.LastServerErrText, vbCritical, sMsg
                        OraDatabase.LastServerErrReset
                        Exit Sub
                    End If
                    bError = True
                    sLotNum(i) = grdData.Columns(14).Value
                    i = i + 1
                End If
            End If
        End If
        grdData.MoveNext
    Next iRec
    
    grdData.MoveFirst
    
    If bError = True Then
        bError = False
        For iRec = 0 To UBound(sLotNum)
            sString = sString & vbCrLf & sLotNum(iRec)
        Next iRec
        MsgBox "Error while saving Item for the following pallets " & vbCrLf & sString, vbInformation, sMsg
    Else
        MsgBox "Saved Successfully", vbInformation + vbExclamation, sMsg
    End If
    
End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    DtpStartDt.Value = Format(Now, "MM/DD/YYYY")
    DtpEndDt.Value = Format(Now, "MM/DD/YYYY")
    
    
    
End Sub

Private Sub txtCustId_Validate(Cancel As Boolean)
    If Trim$(txtCustId) <> "" Then
        SqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & Trim(txtCustId)
        Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.recordcount > 0 Then
            txtCustName.Text = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value
        Else
            MsgBox "INVALID CUSTOMER.", vbExclamation, "Customer"
            Cancel = True
        End If
    End If
End Sub

Private Sub txtLrNum_Validate(Cancel As Boolean)
    If Trim$(txtLrNum) <> "" Then
        SqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & Trim(txtLrNum)
        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.recordcount > 0 Then
            txtVessel.Text = dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
            chkAll.Value = vbUnchecked
        Else
            MsgBox "INVALID VESSEL.", vbExclamation, "VESSEL"
            Cancel = True
        End If
    End If
End Sub
