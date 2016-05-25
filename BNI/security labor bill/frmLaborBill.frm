VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Begin VB.Form frmLaborBill 
   Caption         =   "SECURITY LABOR BILLING"
   ClientHeight    =   11115
   ClientLeft      =   165
   ClientTop       =   450
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
   ScaleHeight     =   11115
   ScaleWidth      =   15240
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdPrint 
      Caption         =   "SAVE AND &PRINT"
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
      Left            =   6360
      TabIndex        =   27
      Top             =   4440
      Width           =   2295
   End
   Begin VB.TextBox txtTotHrs 
      Appearance      =   0  'Flat
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
      Height          =   330
      Left            =   10193
      TabIndex        =   26
      Top             =   5040
      Width           =   1335
   End
   Begin VB.TextBox txtTotAmt 
      Appearance      =   0  'Flat
      BackColor       =   &H00FFFFC0&
      BeginProperty DataFormat 
         Type            =   1
         Format          =   """$""#,##0.00;(""$""#,##0.00)"
         HaveTrueFalseNull=   0
         FirstDayOfWeek  =   0
         FirstWeekOfYear =   0
         LCID            =   1033
         SubFormatType   =   2
      EndProperty
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
      Left            =   6113
      Locked          =   -1  'True
      TabIndex        =   23
      TabStop         =   0   'False
      Top             =   5040
      Width           =   1575
   End
   Begin VB.CommandButton Command1 
      Caption         =   "&Recalculate"
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
      Left            =   2633
      TabIndex        =   22
      Top             =   5025
      Width           =   1335
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
      Height          =   2415
      Left            =   2880
      TabIndex        =   21
      Top             =   120
      Width           =   10275
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   6
      ForeColorEven   =   8388608
      RowHeight       =   503
      ExtraHeight     =   106
      Columns.Count   =   6
      Columns(0).Width=   3200
      Columns(0).Caption=   "DATE"
      Columns(0).Name =   "DATE"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   3200
      Columns(1).Caption=   "CUSTOMER"
      Columns(1).Name =   "CUSTOMER"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   3200
      Columns(2).Caption=   "LR NUM"
      Columns(2).Name =   "LR NUM"
      Columns(2).Alignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   3016
      Columns(3).Caption=   "COMMODITY"
      Columns(3).Name =   "COMMODITY"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2884
      Columns(4).Caption=   "SERVICE"
      Columns(4).Name =   "SERVICE"
      Columns(4).Alignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1535
      Columns(5).Caption=   "STATUS"
      Columns(5).Name =   "status"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      _ExtentX        =   18124
      _ExtentY        =   4260
      _StockProps     =   79
      Caption         =   "Select the ticket you want to generate a bill for..."
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
   Begin VB.CommandButton cmdRet 
      Caption         =   "RETRIEVE"
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
      Left            =   2644
      TabIndex        =   18
      Top             =   4440
      Width           =   1335
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   5655
      Left            =   120
      TabIndex        =   7
      Top             =   5535
      Width           =   15135
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   14
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   8388608
      RowHeight       =   503
      Columns.Count   =   14
      Columns(0).Width=   1720
      Columns(0).Caption=   "SERVICE "
      Columns(0).Name =   "SERVICE "
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   2381
      Columns(1).Caption=   "COMMODITY"
      Columns(1).Name =   "COMMODITY"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   3043
      Columns(2).Caption=   "LABOR TYPE"
      Columns(2).Name =   "LABOR TYPE"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(2).Style=   3
      Columns(2).Row.Count=   3
      Columns(2).Col.Count=   2
      Columns(2).Row(0).Col(0)=   "SECP"
      Columns(2).Row(1).Col(0)=   "SUPE"
      Columns(2).Row(2).Col(0)=   "SWEE"
      Columns(3).Width=   2328
      Columns(3).Caption=   "START HOUR"
      Columns(3).Name =   "START HOUR"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2090
      Columns(4).Caption=   "END HOUR"
      Columns(4).Name =   "END HOUR"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   2170
      Columns(5).Caption=   "RATE TYPE"
      Columns(5).Name =   "RATE TYPE"
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(5).Style=   3
      Columns(5).Row.Count=   4
      Columns(5).Col.Count=   2
      Columns(5).Row(0).Col(0)=   "ST"
      Columns(5).Row(1).Col(0)=   "DF"
      Columns(5).Row(2).Col(0)=   "DT"
      Columns(5).Row(3).Col(0)=   "OT"
      Columns(6).Width=   1958
      Columns(6).Caption=   "QUANTITY"
      Columns(6).Name =   "QUANTITY"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1429
      Columns(7).Caption=   "RATE"
      Columns(7).Name =   "RATE"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1852
      Columns(8).Caption=   "AMOUNT"
      Columns(8).Name =   "AMOUNT"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   3200
      Columns(9).Visible=   0   'False
      Columns(9).Caption=   "BILLING NO."
      Columns(9).Name =   "BILLING NO."
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   1482
      Columns(10).Caption=   "HOURS"
      Columns(10).Name=   "SUM_HOURS"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   2170
      Columns(11).Caption=   "SUPERVISOR"
      Columns(11).Name=   "supervisor"
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   2937
      Columns(12).Caption=   "N- UnBillable"
      Columns(12).Name=   "UnBillable(N)"
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   1
      Columns(13).Width=   1455
      Columns(13).Caption=   "UID"
      Columns(13).Name=   "UID"
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      _ExtentX        =   26696
      _ExtentY        =   9975
      _StockProps     =   79
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
   Begin VB.CommandButton cmdClear 
      Caption         =   "&CLEAR"
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
      Left            =   8947
      TabIndex        =   9
      Top             =   4440
      Width           =   1335
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
      Left            =   11051
      TabIndex        =   10
      Top             =   4440
      Width           =   1335
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
      Left            =   4745
      TabIndex        =   8
      Top             =   4440
      Width           =   1335
   End
   Begin VB.Frame Frame1 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   1575
      Left            =   240
      TabIndex        =   11
      Top             =   2640
      Width           =   14295
      Begin VB.TextBox txtDate 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   1920
         MaxLength       =   20
         TabIndex        =   28
         Top             =   1027
         Width           =   1575
      End
      Begin VB.CommandButton cmdList 
         Height          =   315
         Index           =   1
         Left            =   9960
         Picture         =   "frmLaborBill.frx":0000
         Style           =   1  'Graphical
         TabIndex        =   3
         Top             =   600
         Width           =   345
      End
      Begin VB.CommandButton cmdList 
         Height          =   315
         Index           =   0
         Left            =   3000
         Picture         =   "frmLaborBill.frx":0102
         Style           =   1  'Graphical
         TabIndex        =   1
         Top             =   600
         Width           =   345
      End
      Begin MSComCtl2.DTPicker DTPBillDt 
         Height          =   330
         Left            =   480
         TabIndex        =   4
         Top             =   240
         Visible         =   0   'False
         Width           =   1335
         _ExtentX        =   2355
         _ExtentY        =   582
         _Version        =   393216
         CustomFormat    =   "MM/dd/yyyy"
         Format          =   16515075
         CurrentDate     =   36720
      End
      Begin VB.TextBox txtLRNum 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   1920
         MaxLength       =   7
         TabIndex        =   0
         Top             =   600
         Width           =   975
      End
      Begin VB.TextBox txtVesselName 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   330
         Left            =   3480
         MaxLength       =   40
         TabIndex        =   15
         TabStop         =   0   'False
         Top             =   600
         Width           =   3435
      End
      Begin VB.TextBox txtCustomerName 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFC0&
         Enabled         =   0   'False
         Height          =   330
         Left            =   10560
         MaxLength       =   40
         TabIndex        =   14
         TabStop         =   0   'False
         Top             =   600
         Width           =   3435
      End
      Begin VB.TextBox txtCustomerId 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   8880
         MaxLength       =   6
         TabIndex        =   2
         Top             =   600
         Width           =   975
      End
      Begin VB.CheckBox chkCareOf 
         BackColor       =   &H00C0C0C0&
         Caption         =   "C/O?"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   285
         Left            =   12870
         TabIndex        =   6
         Top             =   1050
         Value           =   1  'Checked
         Width           =   765
      End
      Begin VB.TextBox txtDescription 
         Appearance      =   0  'Flat
         BackColor       =   &H00FFFFFF&
         Height          =   330
         Left            =   5460
         MaxLength       =   100
         TabIndex        =   5
         Top             =   1080
         Width           =   7065
      End
      Begin VB.Label Label4 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "DESCRIPTION  :"
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
         Left            =   3960
         TabIndex        =   17
         Top             =   1080
         Width           =   1425
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "DATE  :"
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
         Left            =   960
         TabIndex        =   16
         Top             =   1080
         Width           =   645
      End
      Begin VB.Label Label2 
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
         Left            =   7560
         TabIndex        =   13
         Top             =   645
         Width           =   1200
      End
      Begin VB.Label Label1 
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
         Left            =   750
         TabIndex        =   12
         Top             =   645
         Width           =   855
      End
   End
   Begin MSComCtl2.DTPicker DTPicker1 
      Height          =   330
      Left            =   720
      TabIndex        =   19
      Top             =   1440
      Width           =   1335
      _ExtentX        =   2355
      _ExtentY        =   582
      _Version        =   393216
      CustomFormat    =   "MM/dd/yyyy"
      Format          =   16515075
      CurrentDate     =   36935
   End
   Begin VB.Label Label7 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Total Hours  :"
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
      Left            =   8760
      TabIndex        =   25
      Top             =   5100
      Width           =   1125
   End
   Begin VB.Label Label5 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Total Amount  :"
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
      Left            =   4680
      TabIndex        =   24
      Top             =   5100
      Width           =   1260
   End
   Begin VB.Label Label6 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "START DATE  :"
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
      TabIndex        =   20
      Top             =   1020
      Width           =   1320
   End
End
Attribute VB_Name = "frmLaborBill"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

' Changed July 2008, included SECP in labor-type box

Dim SqlStmt As String
'Added for asset Coding 08.15.2001 pawan
Dim dsAssetProfile As Object
Dim dsLOCATION_ID As Object
Dim dsLOCATION_CODE As Object
Dim gsSqlStmt As String
Dim gs1SqlStmt As String
Dim Acode As String
Dim flag As Boolean
'.............
Dim iRec As Integer
Dim iPos As Integer
Dim iLrNum  As String
Dim iCust As String
Dim iDate As String
Dim iComm As String
Dim iService As String
Dim dDiff As Double
Dim dHr  As Double
Dim dMin As Double
Dim color As Double
Dim valid As Boolean
Dim Asset As String
Dim sLoc As String

Sub ClearScreen()
    grdData.MoveFirst
    grdData.RemoveAll
    grdData.Columns(2).Value = ""
    grdData.Columns(5).Value = ""
    txtDate.Text = Format$(Now, "mm/dd/yyyy")
    txtDescription.Text = ""
    chkCareOf.Value = 1 'Checked
    txtLRNum = ""
    txtVesselName = ""
    txtCustomerId = ""
    txtCustomerName = ""
    grdData.RowHeight = 300
    txtTotAmt = "0.00"
    txtTotHrs = ""
    
End Sub

Private Sub cmdClear_Click()
    Call ClearScreen
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub

Private Sub cmdList_Click(Index As Integer)
    
    
    Me.MousePointer = 11
    
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    
    frmPV.lstPV.Clear
    
    Select Case Index
    
        Case 0
            frmPV.Caption = "VESSEL LIST"
            SqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
            Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RecordCount > 0 Then
                For iRec = 1 To dsVESSEL_PROFILE.RecordCount
                    frmPV.lstPV.AddItem dsVESSEL_PROFILE.Fields("LR_NUM").Value & " : " & dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value
                    dsVESSEL_PROFILE.MoveNext
                Next iRec
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
            
        Case 1
            frmPV.Caption = "CUSTOMER LIST"
            SqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
            Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
                For iRec = 1 To dsCUSTOMER_PROFILE.RecordCount
                    frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.Fields("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
                    dsCUSTOMER_PROFILE.MoveNext
                Next iRec
            End If
            
            frmPV.Show vbModal
            If gsPVItem <> "" Then
                iPos = InStr(gsPVItem, " : ")
                If iPos > 0 Then
                    txtCustomerId.Text = Left$(gsPVItem, iPos - 1)
                    txtCustomerName.Text = Mid$(gsPVItem, iPos + 3)
                End If
            End If
            txtCustomerId.SetFocus
            
    End Select
    Me.MousePointer = 0
End Sub

Private Sub cmdPrint_Click()
    Call cmdSave_Click
    If SaveCheckForPrint = False Then
        Exit Sub
    End If
    
    If grdData.Rows = 0 Then Exit Sub
    
    grdData.MoveFirst
    
    Printer.Orientation = 2
    Printer.Print "Printed On : " & Format(Now, "MM/DD/YYYY")
    Printer.Print
    Printer.FontSize = 12
    Printer.FontBold = True
    Printer.Print Tab(60); "LABOR BILLING"
    Printer.FontBold = False
    Printer.FontSize = 9
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(5); "CUSTOMER"; Tab(25); ":"; Tab(30); txtCustomerName
    Printer.Print ""
    Printer.Print Tab(5); "VESSEL"; Tab(25); ":"; Tab(30); txtVesselName
    Printer.Print ""
    Printer.Print Tab(5); "SERVICE DATE"; Tab(25); ":"; Tab(30); txtDate
    Printer.Print ""
    Printer.Print Tab(5); "DESCRIPTION"; Tab(25); ":"; Tab(30); txtDescription
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------"
    Printer.Print Tab(7); "SERVICE"; Tab(25); "COMMODITY"; Tab(50); "LABOR TYPE"; Tab(70); "START"; Tab(85); "END"; Tab(105); "RATE TYPE"; _
                  Tab(120); "QUANTITY"; Tab(140); "RATE"; Tab(160); "AMOUNT"; Tab(175); "SUPERVISOR"
    Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------"
    
    For iRec = 0 To grdData.Rows - 1
        
        Printer.Print Tab(9); grdData.Columns(0).Value; Tab(28); grdData.Columns(1).Value; Tab(50); grdData.Columns(2).Value; _
                      Tab(70); grdData.Columns(3).Value; Tab(85); grdData.Columns(4).Value; Tab(108); grdData.Columns(5).Value; _
                      Tab(124); grdData.Columns(6).Value; Tab(140); grdData.Columns(7).Value; Tab(160); grdData.Columns(8).Value; Tab(175); grdData.Columns(11).Value
        grdData.MoveNext
    Next iRec
    Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------"
                        
    Printer.Print Tab(5); "Total Records"; Tab(25); ":"; Tab(30); grdData.Rows
    Printer.Print ""
    Printer.Print Tab(5); "Total Amount"; Tab(25); ":"; Tab(30); txtTotAmt
    Printer.Print ""
    Printer.Print Tab(5); "Total Hours"; Tab(25); ":"; Tab(30); txtTotHrs
    
    Printer.EndDoc
End Sub

Private Sub cmdRet_Click()
     Dim sDesc As String
     Dim sLaborRate As String
     Dim sCommodity As String
    
     grdData.RemoveAll
     
    If Trim(txtLRNum) = "" And Trim(txtCustomerId) = "" Then
        MsgBox " LR NUM / CUSTOMER ID FIELD IS EMPTY", vbInformation, "LABOR BILLING"
        Exit Sub
    End If
    
    SqlStmt = " SELECT * FROM BILLING WHERE LR_NUM='" & Trim(txtLRNum) & "' AND " _
            & " CUSTOMER_ID='" & Trim(txtCustomerId) & "' AND " _
            & " SERVICE_DATE>=TO_DATE('" & Format(txtDate, "MM/DD/YYYY") & "','MM/DD/YYYY') AND" _
            & " SERVICE_DATE<TO_DATE('" & Format(txtDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1" _
            & " AND SERVICE_STATUS='PREINVOICE' AND BILLING_TYPE = 'LABOR' ORDER BY BILLING_NUM"
            
    Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBILLING.RecordCount > 0 Then
        
        iPos = InStr(dsBILLING.Fields("SERVICE_DESCRIPTION").Value, ":")
        If iPos > 0 Then
            txtDescription = Mid(dsBILLING.Fields("SERVICE_DESCRIPTION").Value, 1, iPos - 1)
        Else
            txtDescription = dsBILLING.Fields("SERVICE_DESCRIPTION").Value
        End If
        
        For iRec = 1 To dsBILLING.RecordCount
            ' HD #79 - Added an automatic check for Bulk Services - switches the labor
            ' rate type automatically for the user if we are using premier juice (5635)
            sLaborRate = dsBILLING.Fields("SERVICE_RATE").Value
            sCommodity = dsBILLING.Fields("COMMODITY_CODE").Value
            If sCommodity = "5635" Then
              If sLaborRate = "SUPE" Then
                sLaborRate = "BFSU"
              ElseIf sLaborRate = "FLAB" Then
                sLaborRate = "BFLA"
              ElseIf sLaborRate = "FOPR" Then
                sLaborRate = "BFOO"
              ElseIf sLaborRate = "BFOP" Then
                sLaborRate = "BFEO"
              ElseIf sLaborRate = "CHEC" Then
                sLaborRate = "BFCH"
              ElseIf sLaborRate = "LIFT" Then
                sLaborRate = "BFYD"
              End If
            End If

            grdData.AddItem dsBILLING.Fields("SERVICE_CODE").Value + Chr(9) + _
                            dsBILLING.Fields("COMMODITY_CODE").Value + Chr(9) + _
                            dsBILLING.Fields("LABOR_TYPE").Value + Chr(9) + _
                            Format(dsBILLING.Fields("SERVICE_START").Value, "HH:MM") + Chr(9) + _
                            Format(dsBILLING.Fields("SERVICE_STOP").Value, "HH:MM") + Chr(9) + _
                            sLaborRate + Chr(9) + _
                            dsBILLING.Fields("SERVICE_QTY").Value + Chr(9) + _
                            dsBILLING.Fields("SERVICE_RATE").Value + Chr(9) + _
                            dsBILLING.Fields("SERVICE_AMOUNT").Value + Chr(9) + _
                            dsBILLING.Fields("BILLING_NUM").Value + Chr(9)
            grdData.Refresh
            
            dsBILLING.MoveNext
        Next iRec
    End If
         
End Sub

Private Sub cmdSave_Click()
    Dim iResponse As Integer
    Dim lBillingNum As Long
    Dim lStartBillNum As Long
    Dim lEndBillNum As Long
    
    Dim i As Integer
    Dim iRecSaved As Integer
    Dim lRecCount As Long
    Dim iCol As Integer
    Dim dsBILLING_EDIT  As Object
'    Dim rsPROD As Object
    
    'Lock all the required tables in exclusive mode, try 10 times
    'On Error Resume Next
    'For i = 0 To 9
    '    OraDatabase.LastServerErrReset
    '    SqlStmt = "LOCK TABLE BILLING IN EXCLUSIVE MODE NOWAIT"
    '    lRecCount = OraDatabase.ExecuteSQL(SqlStmt)
    '    If OraDatabase.LastServerErr = 0 Then Exit For
    'Next 'i
    'If OraDatabase.LastServerErr <> 0 Then
    '    OraDatabase.LastServerErr
    '    MsgBox "Tables could not be locked. Please try again. Server Message: " & OraDatabase.LastServerErrText, vbExclamation, "Save Delivery"
    '    Exit Sub
    'End If
    
    SaveCheckForPrint = False
                    
    'Begin a transaction
    OraSession.BeginTrans
    
    On Error GoTo errHandler
    
    If txtLRNum = "" Or txtCustomerId = "" Then
        MsgBox "Field(s) are empty.", vbInformation, sMsg
        OraSession.Rollback
        Exit Sub
    End If
    
 Call txtCustomerId_Validate(True)
    
    Call CalcTotal
    
    grdData.MoveFirst
    For iRec = 0 To grdData.Rows - 1
        For iCol = 0 To grdData.Cols - 3
            If iCol = 0 And Trim$(grdData.Columns(0).Value) <> "" And IsNumeric(grdData.Columns(0).Value) Then
                ''Billing Check
                'SqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " & Trim(grdData.Columns(0).Value)
                'Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                'If dsSERVICE_CATEGORY.recordcount = 0 Then
                '    MsgBox "Invalid Service Code.", vbExclamation, sMsg
                '    grdData.Columns(0).Selected = True
                '    OraSession.Rollback
                '    Exit Sub
                'End If
                'FinApps Check - Service Code
'                SqlStmt = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005836' and flex_value='" & grdData.Columns(0).Value & "' and enabled_flag='Y'"
'                Set rsPROD = OraFinApp.dbcreatedynaset(SqlStmt, 0&)
'                If (rsPROD.RecordCount = 0) Then
'                    MsgBox "Invalid Service Code.", vbExclamation, sMsg
'                    grdData.Columns(0).Selected = True
'                    OraSession.Rollback
'                    Exit Sub
'
'                'FinApps Check - Commodity Code
'                SqlStmt = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005837' and flex_value='" & grdData.Columns(1).Value & "' and enabled_flag='Y'"
'                Set rsPROD = OraFinApp.dbcreatedynaset(SqlStmt, 0&)
'                If (rsPROD.RecordCount = 0) Then
'                    MsgBox "Invalid Service Code.", vbExclamation, sMsg
'                    grdData.Columns(1).Selected = True
'                    OraSession.Rollback
'                    Exit Sub
'                End If
'                End If

             If Len(Trim$(grdData.Columns(0).Value)) > 4 Or Len(Trim$(grdData.Columns(0).Value)) < 4 Then
                MsgBox "Invalid Service Code. Service Code must be 4 characters long.", vbExclamation, "Labor Billing"
                grdData.Columns(0).Selected = True
                OraSession.Rollback
                Exit Sub
             Else
               Call ServiceCode_Validate
                 If valid = False Then
                   grdData.Columns(0).Selected = True
                   OraSession.Rollback
                   Exit Sub
                 End If
            End If
            End If
         
            If iCol = 1 And Trim$(grdData.Columns(1).Value) <> "" And IsNumeric(grdData.Columns(1).Value) Then
             If Len(Trim$(grdData.Columns(1).Value)) > 4 Or Len(Trim$(grdData.Columns(1).Value)) < 4 Then
                MsgBox "Invalid Commodity Code. Commodity Code must be 4 characters long.", vbExclamation, "Labor Billing"
                grdData.Columns(1).Selected = True
                OraSession.Rollback
                Exit Sub
             Else
              Call CommodityCode_Validate
                If valid = False Then
                   grdData.Columns(1).Selected = True
                   OraSession.Rollback
                   Exit Sub
                End If
             End If
            End If
            
            If iCol <> 9 Then
                If grdData.Columns(iCol).Value = "" Then
                    MsgBox "Fill the information in the grid", vbInformation, sMsg
                    OraSession.Rollback
                    Exit Sub
                End If
            End If
        Next iCol
        grdData.MoveNext
    Next iRec
    grdData.MoveFirst
    
    lStartBillNum = 0
    lEndBillNum = 0
        
    'Get the new max values, replace with the sequence later
    SqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
    Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
        If IsNull(dsBILLING_MAX.Fields("MAX(BILLING_NUM)").Value) Then
            lBillingNum = 1
        Else
            lBillingNum = dsBILLING_MAX.Fields("MAX(BILLING_NUM)").Value + 1
        End If
    Else
        lBillingNum = 1
    End If
        
    lStartBillNum = lBillingNum
    
    Acode = ""
    SqlStmt = "SELECT * FROM BILLING"
    Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    '  updated as Tonya wants to save for the already billed quantity
    
    If OraDatabase.LastServerErr <> 0 Then
        'Rollback transaction
        MsgBox "Database error occurred. Changes can not be saved.", vbExclamation, "Save"
        OraSession.Rollback
        Exit Sub
    End If
    
    For iRec = 0 To grdData.Rows - 1
        If Trim$(grdData.Columns(9).Value) = "" Then
            'Add new billing record
            'pawan to update for not billable
            If (grdData.Columns(12).Value = "n" Or grdData.Columns(12).Value = "N") Then
                SqlStmt = " UPDATE HOURLY_DETAIL SET TO_BNI='N' WHERE HIRE_DATE >= TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                        & " AND HIRE_DATE <= TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                        & " AND BILLING_FLAG = 'Y' AND LABOR_TYPE IS NOT NULL" _
                        & " AND VESSEL_ID = " & Val(iLrNum) _
                        & " AND CUSTOMER_ID = " & Val(iCust) _
                        & " AND COMMODITY_CODE = " & Val(iComm) _
                        & " AND SERVICE_CODE LIKE '" & iService & "%'" _
                        & " AND USER_ID='" & grdData.Columns(13).Value & "'" _
                        & " AND TO_BNI IS NULL"         '& " AND SERVICE_CODE = '" & grdData.Columns(0).Value & "'"
                OraDatabase2.DbExecuteSQL (SqlStmt)
                
                If OraDatabase2.LastServerErr <> 0 Then
                    MsgBox OraDatabase2.LastServerErrText, vbCritical, sMsg
                    OraSession.Rollback
                    Exit Sub
                End If
            Else
                'pawan......
                If Trim$(grdData.Columns(8).Value) <> "" Then
                    dsBILLING.AddNew
                    dsBILLING.Fields("CUSTOMER_ID").Value = txtCustomerId.Text
                    dsBILLING.Fields("SERVICE_CODE").Value = grdData.Columns(0).Value
                    dsBILLING.Fields("BILLING_NUM").Value = lBillingNum
                    grdData.Columns(9).Value = lBillingNum
                    dsBILLING.Fields("EMPLOYEE_ID").Value = 4
                    dsBILLING.Fields("SERVICE_START").Value = Format$(txtDate.Text, "MM/DD/YYYY") & " " & grdData.Columns(3).Value & ":00"
                    dsBILLING.Fields("SERVICE_STOP").Value = Format$(txtDate.Text, "MM/DD/YYYY") & " " & grdData.Columns(4).Value & ":00"
                    dsBILLING.Fields("SERVICE_AMOUNT").Value = grdData.Columns(8).Value
                    dsBILLING.Fields("SERVICE_STATUS").Value = "PREINVOICE"
                
                    SqlStmt = "SELECT * FROM LABOR_RATE_TYPE WHERE RATE_TYPE='" & Trim(grdData.Columns(5).Value) & "'"
                    Set dsLABOR_RATE_TYPE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                
                    SqlStmt = "SELECT * FROM LABOR_CATEGORY WHERE LABOR_TYPE = '" & Left(grdData.Columns(2).Value, 4) & "' ORDER BY LABOR_TYPE"
                    Set dsLABOR_CATEGORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                    
                    If OraDatabase.LastServerErr = 0 And dsLABOR_CATEGORY.RecordCount > 0 Then
                        dsBILLING.Fields("SERVICE_DESCRIPTION").Value = Trim$(txtDescription.Text) & " : " & dsLABOR_CATEGORY.Fields("LABOR_DESCRIPTION").Value & " : " & dsLABOR_RATE_TYPE.Fields("RATE_DESCRIPTION").Value
                    Else
                        dsBILLING.Fields("SERVICE_DESCRIPTION").Value = txtDescription.Text
                    End If
                    
                    dsBILLING.Fields("LR_NUM").Value = txtLRNum.Text
                    dsBILLING.Fields("ARRIVAL_NUM").Value = 1
                    dsBILLING.Fields("COMMODITY_CODE").Value = grdData.Columns(1).Value
                    dsBILLING.Fields("INVOICE_NUM").Value = 0
                    dsBILLING.Fields("SERVICE_DATE").Value = Format$(txtDate.Text, "MM/DD/YYYY")
                    dsBILLING.Fields("SERVICE_QTY").Value = grdData.Columns(6).Value
                    dsBILLING.Fields("SERVICE_NUM").Value = 1
                    dsBILLING.Fields("THRESHOLD_QTY").Value = 0
                    dsBILLING.Fields("LEASE_NUM").Value = 0
                    dsBILLING.Fields("SERVICE_UNIT").Value = ""
                    dsBILLING.Fields("SERVICE_RATE").Value = grdData.Columns(7).Value
                    dsBILLING.Fields("LABOR_RATE_TYPE").Value = Left$(grdData.Columns(5).Value, 2)
                    dsBILLING.Fields("LABOR_TYPE").Value = Left(grdData.Columns(2).Value, 4)
                    dsBILLING.Fields("PAGE_NUM").Value = 1
                    dsBILLING.Fields("CARE_OF").Value = chkCareOf.Value
                    dsBILLING.Fields("BILLING_TYPE").Value = "LABOR"
                    'Added for asset Coding 08.15.2001 pawan
                    
                    'get location from hourly detail
                    'get asset from asset_profile based on the location code
                    gsSqlStmt = " SELECT LOCATION_ID FROM HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(txtDate.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                                & " AND HIRE_DATE < TO_DATE('" & Format(txtDate.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1" _
                                & " AND CUSTOMER_ID= " & Val(Trim(txtCustomerId.Text)) & "AND " _
                                & " COMMODITY_CODE=" & grdData.Columns(1).Value & " AND " _
                                & " SERVICE_CODE=" & grdData.Columns(0).Value & " AND " _
                                & " VESSEL_ID=" & Val(Trim(txtLRNum.Text))
                    Set dsLOCATION_ID = OraDatabase2.dbcreatedynaset(gsSqlStmt, 0&)
                    
                    If dsLOCATION_ID.RecordCount > 0 Then
                        gs1SqlStmt = " Select * FROM LOCATION_CATEGORY WHERE LOCATION_ID = '" & dsLOCATION_ID.Fields("LOCATION_ID").Value & "' "
                        Set dsLOCATION_CODE = OraDatabase2.dbcreatedynaset(gs1SqlStmt, 0&)
                    
                        sLoc = dsLOCATION_CODE.Fields("LOCATION_CODE").Value
                        gs1SqlStmt = " Select * from ASSET_PROFILE where " & _
                                " SERVICE_LOCATION_CODE = '" & dsLOCATION_CODE.Fields("LOCATION_CODE").Value & "' "
                        Set dsAssetProfile = OraDatabase.dbcreatedynaset(gs1SqlStmt, 0&)
                    
                        If dsAssetProfile.RecordCount = 0 Then
                            Acode = "W000"
                        Else
                            Acode = dsAssetProfile.Fields("ASSET_CODE").Value
                            Call AssetCode_Validate(Acode, sLoc)
                            If valid = False Then
                               OraSession.Rollback
                               Exit Sub
                            End If
                        End If
                    Else
                        Acode = "W000"
                    End If
                    
                    dsBILLING.Fields("ASSET_CODE").Value = Acode
                    dsBILLING.Update
                    
                    If OraDatabase.LastServerErr = 0 Then
                        lBillingNum = lBillingNum + 1
                    Else
                        'Rollback transaction
                        MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
                        OraSession.Rollback
                        Exit Sub
                    End If
                End If
                
                SqlStmt = " UPDATE HOURLY_DETAIL SET TO_BNI='Y' WHERE HIRE_DATE >= TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                        & " AND HIRE_DATE < TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY') +1" _
                        & " AND BILLING_FLAG = 'Y' AND LABOR_TYPE IS NOT NULL" _
                        & " AND VESSEL_ID = " & Val(iLrNum) _
                        & " AND CUSTOMER_ID = " & Val(iCust) _
                        & " AND COMMODITY_CODE = " & Val(iComm) _
                        & " AND SERVICE_CODE LIKE '" & iService & "%'" _
                        & " AND USER_ID='" & grdData.Columns(13).Value & "'" _
                        & " AND TO_BNI IS NULL"    '& " AND SERVICE_CODE = '" & grdData.Columns(0).Value & "'"
                OraDatabase2.DbExecuteSQL (SqlStmt)
                
                If OraDatabase2.LastServerErr <> 0 Then
                    MsgBox OraDatabase2.LastServerErrText, vbCritical, sMsg
                    OraSession.Rollback
                    Exit Sub
                End If
            End If
        Else
            'IF RECORDS ARE ALREADY IN THE SYSTEM
            'Added the billing type check so it won't mess up with other billing type   -- LFW, 3/15/04
            SqlStmt = "SELECT * FROM BILLING WHERE BILLING_NUM = " & Trim(grdData.Columns(9).Value) & " AND BILLING_TYPE = 'LABOR'"
            Set dsBILLING_EDIT = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
             
            If OraDatabase.LastServerErr = 0 And dsBILLING_EDIT.RecordCount > 0 Then
                dsBILLING_EDIT.EDIT
                'dsBILLING.fields("CUSTOMER_ID").Value = txtCustomerId.Text
                dsBILLING_EDIT.Fields("SERVICE_CODE").Value = grdData.Columns(0).Value
                dsBILLING_EDIT.Fields("SERVICE_START").Value = Format$(txtDate.Text, "MM/DD/YYYY") & " " & grdData.Columns(3).Value & ":00"
                dsBILLING_EDIT.Fields("SERVICE_STOP").Value = Format$(txtDate.Text, "MM/DD/YYYY") & " " & grdData.Columns(4).Value & ":00"
                dsBILLING_EDIT.Fields("SERVICE_AMOUNT").Value = grdData.Columns(8).Value
                                
                SqlStmt = "SELECT * FROM LABOR_RATE_TYPE WHERE RATE_TYPE='" & Trim(grdData.Columns(5).Value) & "'"
                Set dsLABOR_RATE_TYPE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
                SqlStmt = "SELECT * FROM LABOR_CATEGORY WHERE LABOR_TYPE = '" & Left(grdData.Columns(2).Value, 4) & "' ORDER BY LABOR_TYPE"
                Set dsLABOR_CATEGORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                
                If OraDatabase.LastServerErr = 0 And dsLABOR_CATEGORY.RecordCount > 0 Then
                    dsBILLING_EDIT.Fields("SERVICE_DESCRIPTION").Value = Trim$(txtDescription.Text) & " : " & dsLABOR_CATEGORY.Fields("LABOR_DESCRIPTION").Value & " : " & dsLABOR_RATE_TYPE.Fields("RATE_DESCRIPTION").Value
                Else
                    dsBILLING_EDIT.Fields("SERVICE_DESCRIPTION").Value = txtDescription.Text
                End If
                
                'dsBILLING.fields("LR_NUM").Value = txtLRNum.Text
                dsBILLING_EDIT.Fields("COMMODITY_CODE").Value = grdData.Columns(1).Value
                'dsBILLING.fields("SERVICE_DATE").Value = DTPBillDt.Value
                dsBILLING_EDIT.Fields("SERVICE_QTY").Value = grdData.Columns(6).Value
                dsBILLING_EDIT.Fields("SERVICE_RATE").Value = grdData.Columns(7).Value
                dsBILLING_EDIT.Fields("LABOR_RATE_TYPE").Value = Left$(grdData.Columns(5).Value, 2)
                dsBILLING_EDIT.Fields("LABOR_TYPE").Value = Left(grdData.Columns(2).Value, 4)
                dsBILLING_EDIT.Fields("CARE_OF").Value = chkCareOf.Value
                
                'Added for asset Coding 08.15.2001 pawan
                'get location from hourly detail
                'get asset from asset_profile based on the location code
                gsSqlStmt = " SELECT LOCATION_ID FROM HOURLY_DETAIL WHERE HIRE_DATE >= TO_DATE('" & Format(txtDate.Text, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                             & " AND HIRE_DATE < TO_DATE('" & Format(txtDate.Text, "MM/DD/YYYY") & "','MM/DD/YYYY') +1" _
                            & " AND CUSTOMER_ID=" & Val(Trim(txtCustomerId.Text)) & " AND " _
                            & " COMMODITY_CODE=" & grdData.Columns(1).Value & " AND " _
                            & " SERVICE_CODE=" & grdData.Columns(0).Value & " AND " _
                            & " VESSEL_ID=" & Val(Trim(txtLRNum.Text))
                Set dsLOCATION_ID = OraDatabase2.dbcreatedynaset(gsSqlStmt, 0&)
                
                If dsLOCATION_ID.RecordCount > 0 Then
                    gs1SqlStmt = " Select * FROM LOCATION_CATEGORY WHERE LOCATION_ID = '" & dsLOCATION_ID.Fields("LOCATION_ID").Value & "' "
                    Set dsLOCATION_CODE = OraDatabase2.dbcreatedynaset(gs1SqlStmt, 0&)
                
                    gs1SqlStmt = " Select * from ASSET_PROFILE where " & _
                            " SERVICE_LOCATION_CODE = '" & dsLOCATION_CODE.Fields("LOCATION_CODE").Value & "' "
                    Set dsAssetProfile = OraDatabase.dbcreatedynaset(gs1SqlStmt, 0&)
        
                    If dsAssetProfile.RecordCount = 0 Then
                        Acode = "W000"
                    Else
                        Acode = dsAssetProfile.Fields("ASSET_CODE").Value
                        Call AssetCode_Validate(Acode, sLoc)
                        If valid = False Then
                           OraSession.Rollback
                           Exit Sub
                        End If
                    End If
                Else
                     Acode = "W000"
                End If
                
                dsBILLING_EDIT.Fields("ASSET_CODE").Value = Acode
                dsBILLING_EDIT.Update
            
                If OraDatabase.LastServerErr <> 0 Then
                    'Rollback transaction
                    MsgBox "Error occured while saving to Billing table. Changes are not saved.", vbExclamation, "Save"
                    OraSession.Rollback
                    Exit Sub
                End If
            End If
        End If
        
        grdData.MoveNext
    Next iRec
    
    lEndBillNum = lBillingNum - 1
    
    'log to invoicedate table if generated prebills
    If lEndBillNum >= lStartBillNum Then
        Call AddNewInvDt("Labor", CStr(lStartBillNum), CStr(lEndBillNum))
    End If
    
    If OraDatabase.LastServerErr = 0 And OraDatabase2.LastServerErr = 0 Then
        'Commit transaction
        OraSession.CommitTrans
        MsgBox "Changes were saved Successfully!", vbInformation, sMsg
        SaveCheckForPrint = True
    Else
       'Rollback transaction
        MsgBox "Error occured while saving Labor bills. Changes can not be saved!", vbExclamation, sMsg
        OraSession.Rollback
    End If
    
    Exit Sub

errHandler:
     
    If OraDatabase.LastServerErr = 0 And OraDatabase2.LastServerErr = 0 Then
         MsgBox "Error Occured. Unable to Process Labor Prebills!", vbExclamation, sMsg
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox "Error " & OraDatabase.LastServerErrText & " occured." & vbCrLf & _
                   "Unable to Process Labor Prebills!", vbExclamation, sMsg
        Else
            MsgBox "Error " & OraDatabase2.LastServerErrText & " occured." & vbCrLf & _
                   "Unable to Process Labor Prebills!", vbExclamation, sMsg
        End If
    End If
         
    OraSession.Rollback
    OraDatabase.LastServerErrReset
    OraDatabase2.LastServerErrReset
    
End Sub

Private Sub AddNewInvDt(sType As String, sStBillNo As String, sEdBillNo As String)
    Dim dsINVDATE As Object
    Dim dsID As Object
    Dim SqlStmt As String
    Dim DtID As Long
    
    SqlStmt = "SELECT MAX(ID) MAXID FROM INVOICEDATE"
    Set dsID = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsID.RecordCount > 0 Then
        If Not IsNull(dsID.Fields("MAXID").Value) Then
            DtID = dsID.Fields("MAXID").Value + 1
        Else
            DtID = 0
        End If
    Else
        DtID = 0
    End If
    
    SqlStmt = "SELECT * FROM INVOICEDATE"
    Set dsINVDATE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        With dsINVDATE
            .AddNew
            .Fields("ID").Value = DtID
            .Fields("RUN_DATE").Value = Format(Now, "MM/DD/YYYY HH:MM:SS")
            .Fields("BILL_TYPE").Value = "B"
            .Fields("TYPE").Value = sType
            .Fields("START_INV_NO").Value = sStBillNo
            .Fields("END_INV_NO").Value = sEdBillNo
            .Update
        End With
    Else
        MsgBox OraSession.LastServerErrText, vbInformation, "Labor Bill"
    End If
End Sub

Private Sub Command1_Click()
    If grdData.Rows <> 0 Then
        Call CalcTotal
    End If
End Sub

Private Sub Command2_Click()

End Sub

Private Sub DTPicker1_LOSTFOCUS()
    SSDBGrid1.RemoveAll
    Call fillGrid  'DATA FROM LCS
End Sub

Private Sub Form_Load()
    
    On Error GoTo Err_FormLoad
    
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    Call ClearScreen
    DTPicker1.Value = Format$(Now, "mm/dd/yyyy")
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Billing"
    
    On Error GoTo 0
    
End Sub

Private Sub Form_Resize()
    
  Dim iTop As Integer
  
  If (Me.WindowState <> vbMinimized) Then
    iTop = Command1.Top + Command1.Height + Screen.TwipsPerPixelY * 3
    grdData.Move Screen.TwipsPerPixelX * 3, iTop, Me.ScaleWidth - Screen.TwipsPerPixelX * 6, Me.ScaleHeight - iTop - Screen.TwipsPerPixelY * 3
  End If
  
End Sub

Private Sub GrdData_AfterColUpdate(ByVal ColIndex As Integer)

    Select Case ColIndex
        Case 0 'SERVICE
            If Trim$(grdData.Columns(ColIndex).Value) <> "" And IsNumeric(grdData.Columns(ColIndex).Value) Then
'                SqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " & Trim(grdData.Columns(ColIndex).Value)
'                Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
'                If dsSERVICE_CATEGORY.RecordCount = 0 Then
'                    MsgBox "Invalid Service Code.", vbExclamation, sMsg
'                    grdData.Columns(0).Value = ""
'                    Exit Sub
'                End If
              Call ServiceCode_Validate
               If valid = False Then
                grdData.Columns(0).Value = ""
                Exit Sub
               End If
            End If
        
        Case 1   'COMMODITY
           
            If Trim$(grdData.Columns(ColIndex).Value) <> "" And IsNumeric(grdData.Columns(ColIndex).Value) Then
'                SqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & Trim$(grdData.Columns(ColIndex).Value)
'                Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
'                If dsCOMMODITY_PROFILE.RecordCount = 0 Then
'                    MsgBox "Invalid Commodity Code.", vbExclamation, "Commodity"
'                    grdData.Columns(1).Value = ""
'                End If
              Call CommodityCode_Validate
               If valid = False Then
                grdData.Columns(1).Value = ""
                Exit Sub
               End If
            End If
                        
        Case 2   'LABOR TYPE
            If Trim$(grdData.Columns(ColIndex).Value) = "" Then Exit Sub
            
            SqlStmt = "SELECT * FROM LABOR_CATEGORY WHERE LABOR_TYPE='" & Trim(UCase(grdData.Columns(2).Value)) & "'"
            Set dsLABOR_CATEGORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
            If OraDatabase.LastServerErr = 0 And dsLABOR_CATEGORY.RecordCount = 0 Then
                MsgBox "Invalid LABOR TYPE", vbInformation, "LABOR BILLING"
            ElseIf OraDatabase.LastServerErr <> 0 Then
                MsgBox OraDatabase.LastServerErrText, vbCritical, sMsg
                Exit Sub
            Else
                SqlStmt = " SELECT  * FROM LABOR_RATE WHERE RATE_TYPE = '" & grdData.Columns(5).Value & "'" _
                        & " AND LABOR_TYPE = '" & grdData.Columns(2).Value & "'"
                Set dsLABOR_RATE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        
                If dsLABOR_RATE.RecordCount > 0 Then
                    grdData.Columns(7).Value = CStr(dsLABOR_RATE.Fields("RATE").Value)
                    If grdData.Columns(10).Value <> "" Then
                        grdData.Columns(8).Value = Round(grdData.Columns(10).Value * dsLABOR_RATE.Fields("RATE").Value, 2) * grdData.Columns(6).Value
                    End If
                End If
            
            End If
        
        Case 3   'START HR
            
            If Trim$(grdData.Columns(ColIndex).Value) <> "" Then
                If Not IsValidTime(grdData.Columns(ColIndex).Value) Then
                    MsgBox "Start Hour is not valid. Please enter in HH:MM (24 Hour) format.", vbExclamation, "Invalid Start Hour"
                    grdData.Columns(3).Value = ""
                    Exit Sub
                Else
                    Call CalcServiceAmount
                End If
            End If
        
        Case 4   'END HR
            
            If Trim$(grdData.Columns(ColIndex).Value) <> "" Then
                If Not IsValidTime(grdData.Columns(ColIndex).Value) Then
                    MsgBox "End Hour is not valid. Please enter in HH:MM (24 Hour) format.", vbExclamation, "Invalid End Hour"
                    grdData.Columns(4).Value = ""
                    Exit Sub
                Else
                
                If Trim(Left(grdData.Columns(3).Value, 2)) > Trim(Left(grdData.Columns(4).Value, 2)) Then
                    MsgBox "End Hr. should be greater then the Start Hr.", vbInformation, "EQUIPMENT BILL"
                    grdData.Columns(4).Value = ""
                    Exit Sub
                End If
                
                Call CalcServiceAmount
                End If
            End If
            
        Case 5   'RATE TYPE
            
            If Trim$(grdData.Columns(ColIndex).Value) = "" Then Exit Sub
            
            SqlStmt = "SELECT * FROM LABOR_RATE_TYPE WHERE RATE_TYPE='" & Trim(grdData.Columns(5).Value) & "'"
            Set dsLABOR_RATE_TYPE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsLABOR_RATE_TYPE.RecordCount = 0 Then
                MsgBox "Invalid LABOR RATE TYPE", vbInformation, "LABOR RATE TYPE"
                Exit Sub
            Else
                If OraDatabase.LastServerErr <> 0 Then
                    MsgBox OraDatabase.LastServerErrText, vbCritical, sMsg
                    Exit Sub
                End If
            End If
            
            SqlStmt = " SELECT * FROM LABOR_RATE WHERE LABOR_TYPE = '" & Left(grdData.Columns(2).Value, 4) & "' " _
                    & " AND RATE_TYPE = '" & Left$(grdData.Columns(5).Value, 2) & "'"
            Set dsLABOR_RATE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsLABOR_RATE.RecordCount > 0 Then
                grdData.Columns(7).Value = dsLABOR_RATE.Fields("RATE").Value
            End If
             Call CalcServiceAmount
                
        Case 6   'QUANTITY
        
            If Trim$(grdData.Columns(ColIndex).Value) <> "" Then
                If Not IsNumeric(grdData.Columns(ColIndex).Value) Then
                    MsgBox "Please enter a valid numeric Quantity.", vbExclamation, "Invalid Quantity"
                    grdData.Columns(ColIndex).Value = ""
                    Exit Sub
                End If
                Call CalcServiceAmount
                
            End If
            
        Case 7   'RATE
            
            If Trim$(grdData.Columns(ColIndex).Value) <> "" Then
                If Not IsNumeric(grdData.Columns(ColIndex).Value) Then
                    MsgBox "Please enter a valid numeric Rate.", vbExclamation, "Invalid Rate"
                    grdData.Columns(ColIndex).Value = ""
                    Exit Sub
                End If
            End If
                Call CalcServiceAmount
        Case 8   'TOTAL AMOUNT
            Dim i As Integer
            Dim dServiceAmount As Double
        
            dServiceAmount = 0
            iRec = 0
            grdData.MoveFirst
            
            For iRec = 0 To grdData.Rows - 1
                If grdData.Columns(8).Value <> "" Then
                    dServiceAmount = dServiceAmount + grdData.Columns(8).Value
              End If
              grdData.MoveNext
            Next iRec
            txtTotAmt = Format(dServiceAmount, "##0.00")
    End Select
End Sub
Public Sub CalcServiceAmount()
    Dim dAmt As Double
    Dim iHourStart As Integer
    Dim iHourEnd As Integer
    Dim iMinuteStart As Integer
    Dim iMinuteEnd As Integer
    Dim dHourDiff As Double
    Dim lStartMinute As Integer
    Dim lEndMinute As Integer
    
    
'    GrdData.MoveRecords aiIndex
    
    For iRec = 0 To grdData.Cols - 7
        If Trim$(grdData.Columns(iRec).Value) = "" Then Exit Sub
    Next iRec
    
'    GrdData.MoveRecords aiIndex
    
    iHourStart = Left$(grdData.Columns(3).Value, 2)
    iHourEnd = Left$(grdData.Columns(4).Value, 2)
    iMinuteStart = Right$(grdData.Columns(3).Value, 2)
    iMinuteEnd = Right$(grdData.Columns(4).Value, 2)
    lStartMinute = iHourStart * 60 + iMinuteStart
    lEndMinute = iHourEnd * 60 + iMinuteEnd
    dHourDiff = (lEndMinute - lStartMinute) / 60#
    grdData.Columns(10).Value = dHourDiff
            
    grdData.Columns(8).Value = Val(grdData.Columns(7).Value) * Val(grdData.Columns(6).Value) * dHourDiff
    'Call CalcTotal
    
End Sub

Public Sub CalcTotal()
    Dim i As Integer
    Dim dServiceAmount As Double
    
    Dim dTotal As Double
    
    
    dServiceAmount = 0
    iRec = 0
    grdData.MoveFirst
    
    For iRec = 0 To grdData.Rows - 1
        If grdData.Columns(8).Value <> "" Then
            dServiceAmount = dServiceAmount + grdData.Columns(8).Value
      End If
      grdData.MoveNext
    Next iRec
    txtTotAmt = Format(dServiceAmount, "##0.00")
    
    grdData.MoveFirst
    For iRec = 0 To grdData.Rows - 1
        If Trim(grdData.Columns(6).Value) = "" Then
            MsgBox "Please enter a valid numeric Quantity.", vbExclamation, "Invalid Quantity"
            Exit Sub
        End If
    
        If grdData.Columns(3).Value <> "" And grdData.Columns(4).Value <> "" Then
            dDiff = DateDiff("n", grdData.Columns(3).Value, grdData.Columns(4).Value)
            dHr = dDiff \ 60
            dMin = (dDiff Mod 60) / 60
                
            dTotal = dTotal + (CDbl(dHr + dMin) * grdData.Columns(6).Value)
        End If
        grdData.MoveNext
    Next iRec
    
    txtTotHrs = dTotal '+ Val(txtTotHrs)
    
End Sub

Private Sub GrdData_BeforeDelete(Cancel As Integer, DispPromptMsg As Integer)
    DispPromptMsg = 0
    If grdData.Columns(9).Value <> "" Then
        SqlStmt = "DELETE FROM BILLING WHERE BILLING_NUM = " & grdData.Columns(9).Value & " AND BILLING_TYPE = 'LABOR'"
        
        OraDatabase.ExecuteSQL (SqlStmt)
        If OraDatabase.LastServerErr = 0 Then
            grdData.DeleteSelected
        Else
            MsgBox OraDatabase.LastServerErrText, vbCritical, sMsg
            Exit Sub
        End If
    End If
    

End Sub

Private Sub grdData_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
    
    If grdData.Col = 2 And Len(Trim(grdData.Columns(2).Value)) < 1 Then
        grdData.Columns(2).RemoveAll
        SqlStmt = "SELECT  * FROM LABOR_CATEGORY WHERE LABOR_TYPE LIKE '" & Chr(KeyAscii) & "%'"
        Set dsLABOR_CATEGORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsLABOR_CATEGORY.RecordCount > 0 Then
            For iRec = 1 To dsLABOR_CATEGORY.RecordCount
                grdData.Columns(2).AddItem dsLABOR_CATEGORY.Fields("LABOR_TYPE").Value
                dsLABOR_CATEGORY.MoveNext
            Next iRec
        End If
    ElseIf grdData.Col = 5 And Len(Trim(grdData.Columns(5).Value)) < 1 Then
        grdData.Columns(5).RemoveAll
        SqlStmt = "SELECT  * FROM LABOR_RATE_TYPE WHERE RATE_TYPE LIKE '" & Chr(KeyAscii) & "%'"
        Set dsLABOR_RATE_TYPE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsLABOR_RATE_TYPE.RecordCount > 0 Then
            For iRec = 1 To dsLABOR_RATE_TYPE.RecordCount
                grdData.Columns(5).AddItem dsLABOR_RATE_TYPE.Fields("RATE_TYPE").Value
                dsLABOR_RATE_TYPE.MoveNext
            Next iRec
        End If
    End If
    
End Sub

Private Sub grdData_RowColChange(ByVal LastRow As Variant, ByVal LastCol As Integer)

   If LastCol = 6 Then
       Call CalcTotal
   End If
    
End Sub

Private Sub txtCustomerId_Validate(Cancel As Boolean)
  If Trim$(txtCustomerId) <> "" And IsNumeric(txtCustomerId) Then
    'Check Customer Code against PROD db
'        SqlStmt = "SELECT c.customer_id,a.address_id FROM ra_customers c, ra_addresses_all a where a.bill_to_flag in ('Y','P') and " _
'               & " c.status = 'A' and c.customer_id = a.customer_id and c.customer_number = " & txtCustomerId.Text
'        Set rsPROD = OraFinApp.dbcreatedynaset(SqlStmt, 0&)
'         If OraFinApp.LastServerErr = 0 And rsPROD.RecordCount = 0 Then
'            Cancel = True
'            MsgBox "Customer Code [" & txtCustomerId.Text & "] not found in Oracle.", vbExclamation, sMsg
'            txtCustomerId.SelStart = 0
'            txtCustomerId.SelLength = Len(txtCustomerId)
'         Else
          'Get the Customer Name against BNI
            SqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtCustomerId.Text
            Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
             If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
               txtCustomerName.Text = dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
             Else
               MsgBox "Customer does not exist in BNI.", vbExclamation, sMsg
               Cancel = True
               txtCustomerId.SelStart = 0
               txtCustomerId.SelLength = Len(txtCustomerId)
             End If
'        End If
  End If
  
End Sub
Private Sub txtLRNum_Validate(Cancel As Boolean)
    If Trim$(txtLRNum) <> "" And IsNumeric(txtLRNum) Then
        SqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLRNum.Text
        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RecordCount > 0 Then
            txtVesselName.Text = dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value
        Else
            
            If Trim$(txtLRNum.Text) = "0" Then
                txtVesselName.Text = "Unknown"
            Else
            MsgBox "Invalid Vessel .", vbExclamation, sMsg
            Cancel = True
            txtLRNum.SelStart = 0
            txtLRNum.SelLength = Len(txtLRNum)
            End If
        End If
    End If
End Sub

Private Sub fillGrid()
    Dim iPreDate As String
    Dim iPreCust As String
    Dim iPreVessel As String
    Dim iPreComm As String
    Dim iPreService As String
    Dim iFirstLine As Boolean
    ' pawan
    Dim status As String
    status = ""
    ' pawan
    'SSDBGrid1.RemoveAll
    iPreDate = ""
    iPreCust = ""
    iPreVessel = ""
    iPreComm = ""
    iPreService = ""
    iFirstLine = True
    
    SqlStmt = " SELECT DISTINCT HIRE_DATE,CUSTOMER_ID,VESSEL_ID,COMMODITY_CODE,SERVICE_CODE,TO_BNI FROM HOURLY_DETAIL WHERE " _
            & " HIRE_DATE >= TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY') AND " _
            & " HIRE_DATE < TO_DATE('" & Format(DTPicker1.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')+1" _
            & " AND BILLING_FLAG = 'Y' AND LABOR_TYPE IS NOT NULL " _
            & " ORDER BY HIRE_DATE,CUSTOMER_ID,VESSEL_ID,COMMODITY_CODE,SERVICE_CODE"
            'AND TO_BNI IS NULL
    Set dsHOURLY_DETAIL_DISTINCT = OraDatabase2.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If dsHOURLY_DETAIL_DISTINCT.RecordCount > 0 Then
            'FILL THE DBGRID
            While Not dsHOURLY_DETAIL_DISTINCT.EOF
                
                If iFirstLine Then
                    iPreDate = Trim$(Format$(dsHOURLY_DETAIL_DISTINCT.Fields("HIRE_DATE").Value, "MM/DD/YYYY"))
                    iPreCust = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("CUSTOMER_ID").Value)
                    iPreVessel = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("VESSEL_ID").Value)
                    iPreComm = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("COMMODITY_CODE").Value)
                    iPreService = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("SERVICE_CODE").Value)
                    'pawan.........for this & all in the loop
                    If dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "Y" Then
                        status = "BILLED"
                    Else
                        If ((dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "N") Or (dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "n")) Then
                            status = "UN BILLABLE"
                        Else
                            status = "NOT BILLED"
                        End If
                    End If
                Else
                    If iPreDate <> Trim$(Format$(dsHOURLY_DETAIL_DISTINCT.Fields("HIRE_DATE").Value, "MM/DD/YYYY")) Then
                        SSDBGrid1.AddItem Trim$(iPreDate) + Chr(9) + _
                            Trim$(iPreCust) + Chr(9) + _
                            Trim$(iPreVessel) + Chr(9) + _
                            Trim$(iPreComm) + Chr(9) + _
                            Left$(iPreService, 3) & "X" + Chr(9) + _
                            Trim$(status)
                            ' pawan
                        SSDBGrid1.Refresh
                        iPreDate = Trim$(Format$(dsHOURLY_DETAIL_DISTINCT.Fields("HIRE_DATE").Value, "MM/DD/YYYY"))
                        iPreCust = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("CUSTOMER_ID").Value)
                        iPreVessel = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("VESSEL_ID").Value)
                        iPreComm = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("COMMODITY_CODE").Value)
                        iPreService = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("SERVICE_CODE").Value)
                        If dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "Y" Then
                            status = "BILLED"
                        Else
                            If ((dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "N") Or (dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "n")) Then
                                status = "UN BILLABLE"
                            Else
                                status = "NOT BILLED"
                            End If
                        End If
                    Else
                        If iPreCust <> Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("CUSTOMER_ID").Value) Then
                            SSDBGrid1.AddItem Trim$(iPreDate) + Chr(9) + _
                                Trim$(iPreCust) + Chr(9) + _
                                Trim$(iPreVessel) + Chr(9) + _
                                Trim$(iPreComm) + Chr(9) + _
                                Left$(iPreService, 3) & "X" + Chr(9) + _
                                Trim$(status)
                                ' pawan
                            SSDBGrid1.Refresh
                            iPreCust = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("CUSTOMER_ID").Value)
                            iPreVessel = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("VESSEL_ID").Value)
                            iPreComm = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("COMMODITY_CODE").Value)
                            iPreService = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("SERVICE_CODE").Value)
                            If dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "Y" Then
                                status = "BILLED"
                            Else
                                If ((dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "N") Or (dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "n")) Then
                                    status = "UN BILLABLE"
                                Else
                                    status = "NOT BILLED"
                                End If
                            End If
                        Else
                            If iPreVessel <> Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("VESSEL_ID").Value) Then
                                SSDBGrid1.AddItem Trim$(iPreDate) + Chr(9) + _
                                            Trim$(iPreCust) + Chr(9) + _
                                            Trim$(iPreVessel) + Chr(9) + _
                                            Trim$(iPreComm) + Chr(9) + _
                                            Left$(iPreService, 3) & "X" + Chr(9) + _
                                            Trim$(status)
                                            ' pawan
                                SSDBGrid1.Refresh
                                iPreVessel = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("VESSEL_ID").Value)
                                iPreComm = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("COMMODITY_CODE").Value)
                                iPreService = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("SERVICE_CODE").Value)
                                If dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "Y" Then
                                    status = "BILLED"
                                Else
                                    If ((dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "N") Or (dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "n")) Then
                                        status = "UN BILLABLE"
                                    Else
                                        status = "NOT BILLED"
                                    End If
                                End If
                            Else
                                If iPreComm <> Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("COMMODITY_CODE").Value) Then
                                    SSDBGrid1.AddItem Trim$(iPreDate) + Chr(9) + _
                                            Trim$(iPreCust) + Chr(9) + _
                                            Trim$(iPreVessel) + Chr(9) + _
                                            Trim$(iPreComm) + Chr(9) + _
                                            Left$(iPreService, 3) & "X" + Chr(9) + _
                                            Trim$(status)
                                            ' pawan
                                    SSDBGrid1.Refresh
                                    iPreComm = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("COMMODITY_CODE").Value)
                                    iPreService = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("SERVICE_CODE").Value)
                                    If dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "Y" Then
                                        status = "BILLED"
                                    Else
                                        If ((dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "N") Or (dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "n")) Then
                                            status = "UN BILLABLE"
                                        Else
                                            status = "NOT BILLED"
                                        End If
                                    End If
                                Else
                                    If Left$(iPreService, 3) <> Left$(Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("SERVICE_CODE").Value), 3) Then
                                        SSDBGrid1.AddItem Trim$(iPreDate) + Chr(9) + _
                                            Trim$(iPreCust) + Chr(9) + _
                                            Trim$(iPreVessel) + Chr(9) + _
                                            Trim$(iPreComm) + Chr(9) + _
                                            Left$(iPreService, 3) & "X" + Chr(9) + _
                                            Trim$(status)
                                            ' pawan
                                        SSDBGrid1.Refresh
                                        iPreService = Trim$(dsHOURLY_DETAIL_DISTINCT.Fields("SERVICE_CODE").Value)
                                        If dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "Y" Then
                                            status = "BILLED"
                                        Else
                                            If ((dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "N") Or (dsHOURLY_DETAIL_DISTINCT.Fields("TO_BNI").Value = "n")) Then
                                                status = "UN BILLABLE"
                                            Else
                                                status = "NOT BILLED"
                                            End If
                                        End If
                                    End If
                                End If
                            End If
                        End If
                    End If
                End If
                
                iFirstLine = False
                
            dsHOURLY_DETAIL_DISTINCT.MoveNext
            Wend
            SSDBGrid1.AddItem Trim$(iPreDate) + Chr(9) + _
                    Trim$(iPreCust) + Chr(9) + _
                    Trim$(iPreVessel) + Chr(9) + _
                    Trim$(iPreComm) + Chr(9) + _
                    Left$(iPreService, 3) & "X" + Chr(9) + _
                    Trim$(status)
            SSDBGrid1.Refresh
        Else
            MsgBox "NO RECORDS, TRY LATER.", vbInformation, "LABOR TICKETS"
            Exit Sub
        End If
    End If
    
End Sub
Private Sub SSDBGrid1_Click()
   
    Dim iEARNING_TYPE_ID As String
    Dim sDesc As String
    Dim iResponse As Integer
    Dim sStart As String
    Dim sEnd As String
    Dim iRateType As String
    Dim dStart As Date
    Dim dEnd As Date
    Dim sRate As String
    Dim sRateType As String
    Dim dAmt As Double
    
    'pawan.....
    Dim super As String
    Dim bni As String
    'pawan ......
    grdData.RemoveAll
    flag = False
    iLrNum = ""
    iCust = ""
    iDate = ""
    iComm = ""
    iService = ""
    iRateType = ""
    'SSDBGrid1.BackColorEven = color
    If SSDBGrid1.Rows = 0 Then Exit Sub
    'color = SSDBGrid1.BackColorEven
    'SSDBGrid1.BackColorEven = 255
    iDate = Format(SSDBGrid1.Columns(0).Value, "mm/dd/yyyy")
    iCust = Trim$(SSDBGrid1.Columns(1).Value)
    iLrNum = Trim$(SSDBGrid1.Columns(2).Value)
    iComm = Trim$(SSDBGrid1.Columns(3).Value)
    iService = Left$(Trim$(SSDBGrid1.Columns(4).Value), 3)
    
    txtLRNum.Text = iLrNum
    Call txtLRNum_Validate(True)
    txtCustomerId.Text = iCust
    Call txtCustomerId_Validate(True)
    txtDate = iDate
    
    If Trim(txtLRNum) = "" And Trim(txtCustomerId) = "" Then
        MsgBox " LR NUM / CUSTOMER ID FIELD IS EMPTY", vbInformation, "LABOR BILLING"
        Exit Sub
    End If
'
'    SqlStmt = " SELECT * FROM BILLING WHERE LR_NUM='" & Trim(txtLRNum) & "' AND " _
'            & " CUSTOMER_ID='" & Trim(txtCustomerId) & "' AND " _
'            & " COMMODITY_CODE=" & Val(iComm) & " AND " _
'            & " SERVICE_CODE LIKE '" & iService & "%' AND " _
'            & " SERVICE_DATE>=TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'            & " AND SERVICE_DATE<TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY') +1" _
'            & " AND SERVICE_STATUS='INVOICED' AND BILLING_TYPE='LABOR' ORDER BY BILLING_NUM"
'    Set dsBILLING = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
'
'    If OraDatabase.LastServerErr = 0 And dsBILLING.recordcount > 0 Then
'        MsgBox "Already Invoiced", vbInformation, "LABOR BILLING"
'        Exit Sub
'    End If
'
'
'    'GET DATA FROM BILLING TABLE IF HAVE ANY
'    SqlStmt = " SELECT * FROM BILLING WHERE LR_NUM='" & Trim(txtLRNum) & "' AND " _
'            & " CUSTOMER_ID='" & Trim(txtCustomerId) & "' AND " _
'            & " COMMODITY_CODE=" & Val(iComm) & " AND " _
'            & " SERVICE_CODE LIKE '" & iService & "%' AND " _
'            & " SERVICE_DATE>=TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'            & " AND SERVICE_DATE<TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY') +1" _
'            & " AND SERVICE_STATUS='PREINVOICE' AND BILLING_TYPE='LABOR' ORDER BY BILLING_NUM"
'    Set dsBILLING = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
'
'    If OraDatabase.LastServerErr = 0 Then
'        If dsBILLING.recordcount > 0 Then
'
'            'iResponse = MsgBox("YOU ALREADAY GENERATED THE BILL FOR" & iDate & "-" & iLrNum & "-" & iCust & ". PLEASE ADD INTO GROD IF MISSED ENTRIES.", vbQestion + vbYesNo, "LABOR BILL")
'            MsgBox "YOU ALREADY GENERATED THE BILL FOR" & iDate & "-" & iCust & "-" & iLrNum & "-" & iComm & ". PLEASE ADD INTO GRID IF MISSED ENTRIES.", vbInformation, "LABOR BILL"
''            If iResponse = vbYes Then
''
''            End If
''
'            iPos = InStr(dsBILLING.FIELDS("SERVICE_DESCRIPTION").Value, ":")
'            If iPos > 0 Then
'                txtDescription = Mid(dsBILLING.FIELDS("SERVICE_DESCRIPTION").Value, 1, iPos - 1)
'            Else
'                txtDescription = dsBILLING.FIELDS("SERVICE_DESCRIPTION").Value
'            End If
'
'            For iRec = 1 To dsBILLING.recordcount
'
'                grdData.AddItem dsBILLING.FIELDS("SERVICE_CODE").Value + Chr(9) + _
'                                dsBILLING.FIELDS("COMMODITY_CODE").Value + Chr(9) + _
'                                dsBILLING.FIELDS("LABOR_TYPE").Value + Chr(9) + _
'                                Format(dsBILLING.FIELDS("SERVICE_START").Value, "HH:MM") + Chr(9) + _
'                                Format(dsBILLING.FIELDS("SERVICE_STOP").Value, "HH:MM") + Chr(9) + _
'                                dsBILLING.FIELDS("LABOR_RATE_TYPE").Value + Chr(9) + _
'                                dsBILLING.FIELDS("SERVICE_QTY").Value + Chr(9) + _
'                                dsBILLING.FIELDS("SERVICE_RATE").Value + Chr(9) + _
'                                dsBILLING.FIELDS("SERVICE_AMOUNT").Value + Chr(9) + _
'                                dsBILLING.FIELDS("BILLING_NUM").Value
'                grdData.Refresh
'
'                dsBILLING.MoveNext
'            Next iRec
'        Else
                    
'***************************************************************************
    ' HD 9312
    ' If this bill was already run, populate the bottom box with the Billed information instead
'    SqlStmt = "SELECT COUNT(*) THE_COUNT FROM BILLING WHERE BILLING_TYPE = 'LABOR' " + _
'                " AND LR_NUM = '" & iLrNum & "' " + _
'                " AND TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') = '" & Format$(txtDate.Text, "MM/DD/YYYY") & "' " + _
'                " AND SERVICE_STATUS != 'DELETED' " + _
'                " AND CUSTOMER_ID = '" & iCust & "' " + _
'                " AND COMMODITY_CODE = '" & iComm & "' " + _
'                " AND SERVICE_CODE LIKE '" & iService & "%' "
'    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
'    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value > 0 Then
'        Call FillFromBilling
'        Exit Sub
'    End If
'******************************************************************************
                    
            SqlStmt = " SELECT HIRE_DATE,CUSTOMER_ID,VESSEL_ID,COMMODITY_CODE,SERVICE_CODE,EARNING_TYPE_ID,LABOR_TYPE," _
                    & " START_TIME,END_TIME, SUM(DURATION) SUM_HOURS, COUNT(*) MY_COUNT,LU.USER_NAME UN,HD.USER_ID ud,TO_BNI" _
                    & " FROM HOURLY_DETAIL HD,LCS_USER LU WHERE HIRE_DATE >= TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
                    & " AND HIRE_DATE < TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY') +1" _
                    & " AND BILLING_FLAG = 'Y' AND LABOR_TYPE IS NOT NULL " _
                    & " AND VESSEL_ID = " & Val(iLrNum) _
                    & " AND CUSTOMER_ID = " & Val(iCust) _
                    & " AND COMMODITY_CODE = " & Val(iComm) _
                    & " AND SERVICE_CODE LIKE '" & iService & "%' " _
                    & " AND LU.USER_ID = HD.USER_ID " _
                    & " GROUP BY HIRE_DATE,CUSTOMER_ID,VESSEL_ID,COMMODITY_CODE,SERVICE_CODE,EARNING_TYPE_ID,LABOR_TYPE," _
                    & " START_TIME,END_TIME,LU.USER_NAME,TO_BNI,HD.USER_ID ORDER BY SERVICE_CODE,LABOR_TYPE"
                    '  pawan
                    'AND TO_BNI IS NULL
            Set dsHOURLY_DETAIL = OraDatabase2.dbcreatedynaset(SqlStmt, 0&)
            
            
            
            If OraDatabase.LastServerErr = 0 And dsHOURLY_DETAIL.RecordCount > 0 Then
                
                    For iRec = 1 To dsHOURLY_DETAIL.RecordCount
                        sEnd = ""
                        sStart = ""
                        'GET RATE
                        dAmt = 0
                        sRate = ""
                        sRateType = ""
'                        If (dsHOURLY_DETAIL.fields("TO_BNI").Value = "Y") Then
'                            flag = True
'                        End If
                        If (dsHOURLY_DETAIL.Fields("TO_BNI").Value = "Y") Then
                            flag = True
                        End If
                                                 
                        dStart = Format(dsHOURLY_DETAIL.Fields("START_TIME").Value, "HH:MM")
                        dEnd = Format(dsHOURLY_DETAIL.Fields("END_TIME").Value, "HH:MM")
                        super = Trim$(dsHOURLY_DETAIL.Fields("UN").Value)
                    If Not IsNull(dsHOURLY_DETAIL.Fields("TO_BNI").Value) Then
                        If dStart >= #6:00:00 AM# And dStart < #7:00:00 AM# And dEnd >= #7:00:00 AM# Then
                            
                            sEnd = "7:00"
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "DT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                                            'pawan.........for this & all below
                            dStart = #7:00:00 AM#
                        End If
                        
                        If dStart >= #7:00:00 AM# And dStart < #8:00:00 AM# And dEnd >= #8:00:00 AM# Then
                            
                            sEnd = "8:00"
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "OT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                            dStart = #8:00:00 AM#
                        End If
                        
                        If dStart >= #8:00:00 AM# And dEnd <= #12:00:00 PM# Then
                            
                            sEnd = "8:00"
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "ST" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                            
                            If dEnd > #12:00:00 PM# Then dStart = #12:00:00 PM#
                        End If
                        
                        If dEnd > #12:00:00 PM# And dStart < #12:00:00 PM# Then
                            
                           sEnd = "12:00"
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "ST" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                           dStart = #1:00:00 PM#
                        End If
                        
                        If dStart = #12:00:00 PM# And dEnd = #1:00:00 PM# Then
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "DT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                        End If
                        
                        If dStart >= #1:00:00 PM# And dStart < #5:00:00 PM# And dEnd < #5:00:00 PM# Then
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "ST" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                           'dStart = #5:00:00 PM#
                        End If
                        
                        If dStart >= #1:00:00 PM# And dStart < #5:00:00 PM# And dEnd >= #5:00:00 PM# Then
                            
                           sEnd = "17:00"
                           
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "ST" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                           dStart = #5:00:00 PM#
                        End If
                        
                        If dStart >= #5:00:00 PM# And dEnd <= #6:00:00 PM# And dEnd > #5:00:00 PM# Then

                           'sEnd = "18:00"
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "OT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                           'dStart = #6:00:00 PM#
                           End If
                        
                        If dStart >= #5:00:00 PM# And dStart < #6:00:00 PM# And dEnd > #6:00:00 PM# Then

                           sEnd = "18:00"
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "OT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                           dStart = #6:00:00 PM#
                        End If
                        
                        If dStart >= #6:00:00 PM# And dEnd = #7:00:00 PM# Then
                            
                           'sStart = "18:00"
                           'sEnd = #7:00:00 PM#
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "DT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                          'dStart = #7:00:00 PM#
                        End If
                        
                        If dStart >= #6:00:00 PM# And dStart < #7:00:00 PM# And dEnd > #7:00:00 PM# Then
                            
                           'sStart = "18:00"
                           sEnd = #7:00:00 PM#
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "DT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                          dStart = #7:00:00 PM#
                        End If
                        
                        If dEnd > #7:00:00 PM# Then
                            
                                                     
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "OT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("TO_BNI").Value) + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                                            
                           
                        End If
                    Else
                        If dStart >= #6:00:00 AM# And dStart < #7:00:00 AM# And dEnd >= #7:00:00 AM# Then
                            
                            sEnd = "7:00"
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "DT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                                            'pawan.........for this & all below
                            dStart = #7:00:00 AM#
                        End If
                        
                        If dStart >= #7:00:00 AM# And dStart < #8:00:00 AM# And dEnd >= #8:00:00 AM# Then
                            
                            sEnd = "8:00"
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "OT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                            dStart = #8:00:00 AM#
                        End If
                        
                        If dStart >= #8:00:00 AM# And dEnd <= #12:00:00 PM# Then
                            
                            sEnd = "8:00"
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "ST" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                            
                            If dEnd > #12:00:00 PM# Then dStart = #12:00:00 PM#
                        End If
                        
                        If dEnd > #12:00:00 PM# And dStart < #12:00:00 PM# Then
                            
                           sEnd = "12:00"
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "ST" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                           dStart = #1:00:00 PM#
                        End If
                        
                        If dStart = #12:00:00 PM# And dEnd = #1:00:00 PM# Then
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "DT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                        End If
                        
                        If dStart >= #1:00:00 PM# And dStart < #5:00:00 PM# And dEnd < #5:00:00 PM# Then
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "ST" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                           'dStart = #5:00:00 PM#
                        End If
                        
                        If dStart >= #1:00:00 PM# And dStart < #5:00:00 PM# And dEnd >= #5:00:00 PM# Then
                            
                           sEnd = "17:00"
                           
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "ST" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                           dStart = #5:00:00 PM#
                        End If
                        
                        If dStart >= #5:00:00 PM# And dEnd <= #6:00:00 PM# And dEnd > #5:00:00 PM# Then

                           'sEnd = "18:00"
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "OT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                           'dStart = #6:00:00 PM#
                           End If
                        
                        If dStart >= #5:00:00 PM# And dStart < #6:00:00 PM# And dEnd > #6:00:00 PM# Then

                           sEnd = "18:00"
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "OT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                           dStart = #6:00:00 PM#
                        End If
                        
                        If dStart >= #6:00:00 PM# And dEnd = #7:00:00 PM# Then
                            
                           'sStart = "18:00"
                           'sEnd = #7:00:00 PM#
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "DT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                          'dStart = #7:00:00 PM#
                        End If
                        
                        If dStart >= #6:00:00 PM# And dStart < #7:00:00 PM# And dEnd > #7:00:00 PM# Then
                            
                           'sStart = "18:00"
                           sEnd = #7:00:00 PM#
                           grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(sEnd, "HH:MM") + Chr(9) + _
                                            "DT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                          dStart = #7:00:00 PM#
                        End If
                        
                        If dEnd > #7:00:00 PM# Then
                            
                                                     
                            grdData.AddItem Trim$(dsHOURLY_DETAIL.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("LABOR_TYPE").Value) + Chr(9) + _
                                            Format(dStart, "HH:MM") + Chr(9) + Format(dEnd, "HH:MM") + Chr(9) + _
                                            "OT" + Chr(9) + Trim$(dsHOURLY_DETAIL.Fields("MY_COUNT").Value) + Chr(9) + _
                                            sRate + Chr(9) + CStr(dAmt) + Chr(9) + Chr(9) + Chr(9) + super + Chr(9) + Chr(9) + _
                                            Trim$(dsHOURLY_DETAIL.Fields("ud").Value)
                                            
                           
                        End If
                        
                    End If
                                            
                            grdData.Refresh
                        
                        
                        
                            dsHOURLY_DETAIL.MoveNext
                    Next iRec
                
            End If
            
'        End If
'    Else
'        Exit Sub
'    End If
'
    grdData.MoveFirst
    
    For iRec = 0 To grdData.Rows - 1
                    
            If grdData.Columns(3).Value <> "" And grdData.Columns(4).Value <> "" Then
                dDiff = DateDiff("n", grdData.Columns(3).Value, grdData.Columns(4).Value)
                dHr = dDiff \ 60
                dMin = (dDiff Mod 60) / 60
                
                grdData.Columns(10).Value = CDbl(dHr + dMin)
            End If
        
    
            SqlStmt = " SELECT  * FROM LABOR_RATE WHERE RATE_TYPE = '" & grdData.Columns(5).Value & "'" _
                    & " AND LABOR_TYPE = '" & grdData.Columns(2).Value & "'"
            Set dsLABOR_RATE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
   '
            If dsLABOR_RATE.RecordCount > 0 Then
                ' hardcode adjustment, HD9515.  This is temporary, as the long term goal is to move this whole program out of VB into PHP
                If grdData.Columns(2).Value = "SECP" And iCust = 2025 Then
                    grdData.Columns(7).Value = CStr(36.05)
                Else
                    grdData.Columns(7).Value = CStr(dsLABOR_RATE.Fields("RATE").Value)
                End If
                If grdData.Columns(10).Value <> "" Then
 '                   grdData.Columns(8).Value = Round(grdData.Columns(10).Value * dsLABOR_RATE.Fields("RATE").Value, 2) * grdData.Columns(6).Value
                    grdData.Columns(8).Value = Round(grdData.Columns(10).Value * grdData.Columns(7).Value, 2) * grdData.Columns(6).Value
                End If
            End If

       grdData.MoveNext
    Next iRec
    
         
    Call CalcTotal
    
    grdData.MoveFirst
    
End Sub


Public Sub CalcServiceAmount2()
    Dim iTotAmt As Double
    
    grdData.MoveFirst
    
    iTotAmt = 0
    For iRec = 0 To grdData.Rows - 1
        iTotAmt = iTotAmt + Val(grdData.Columns(8).Value)
    Next iRec
    txtTotAmt = iTotAmt
    
End Sub

Public Sub ServiceCode_Validate()
  valid = True
                
  'FinApps Check - Service Code
'   SqlStmt = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005836' and flex_value='" & grdData.Columns(0).Value & "' and enabled_flag='Y'"
'   Set rsPROD = OraFinApp.dbcreatedynaset(SqlStmt, 0&)
'   If (rsPROD.RecordCount = 0) Then
'      MsgBox "Service Code [" & Trim$(grdData.Columns(0).Value) & "] not found in Oracle.", vbExclamation, sMsg
'      valid = False
'   End If
End Sub

Public Sub CommodityCode_Validate()
  valid = True
                
  'FinApps Check - Commodity Code
'   SqlStmt = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005837' and flex_value='" & grdData.Columns(1).Value & "' and enabled_flag='Y'"
'   Set rsPROD = OraFinApp.dbcreatedynaset(SqlStmt, 0&)
'   If (rsPROD.RecordCount = 0) Then
'      MsgBox "Commodity Code [" & Trim$(grdData.Columns(1).Value) & "] not found in Oracle.", vbExclamation, sMsg
'      valid = False
'   End If
End Sub

Public Sub AssetCode_Validate(Asset, sLoc)
 valid = True
 
 'Check asset Code against PROD db
'  SqlStmt = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005838' and flex_value='" & Trim$(Asset) & "' and enabled_flag='Y'"
'   Set rsPROD = OraFinApp.dbcreatedynaset(SqlStmt, 0&)
'   If (rsPROD.RecordCount = 0) Then
'     MsgBox "Asset Code [" & Trim$(Asset) & "] for the Service Location [" & Trim$(sLoc) & "] not found in Oracle.", vbExclamation, sMsg
'      valid = False
'   End If
End Sub

Private Sub FillFromBilling()
' THIS ID FOR HD9312
' THE CODE CALLING THIS FUNCTION IS DISABLED UNTIL WE GET A BETTER DEFINITION OF WHAT IS BEING DONE
' SHOULD WE REACTIVATE THIS FUCTION, PLEASE REMOVE THIS COMMENT
    SqlStmt = "SELECT SERVICE_CODE, COMMODITY_CODE, LABOR_TYPE, TO_CHAR(SERVICE_START, 'HH24:MI') THE_START, TO_CHAR(SERVICE_STOP, 'HH24:MI') THE_END, " + _
                " LABOR_RATE_TYPE, SERVICE_RATE, SERVICE_QTY, SERVICE_AMOUNT " + _
                " FROM BILLING WHERE BILLING_TYPE = 'LABOR' " + _
                " AND LR_NUM = '" & iLrNum & "' " + _
                " AND TO_CHAR(SERVICE_DATE, 'MM/DD/YYYY') = '" & Format$(txtDate.Text, "MM/DD/YYYY") & "' " + _
                " AND SERVICE_STATUS != 'DELETED' " + _
                " AND CUSTOMER_ID = '" & iCust & "' " + _
                " AND COMMODITY_CODE = '" & iComm & "' " + _
                " AND SERVICE_CODE LIKE '" & iService & "%' "
    Set dsBILLING_RETRIEVE_DATA = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    For iRec = 1 To dsBILLING_RETRIEVE_DATA.RecordCount

        SqlStmt = "SELECT USER_NAME, H.USER_ID FROM HOURLY_DETAIL H, LCS_USER U WHERE U.USER_ID = H.USER_ID " + _
                    " AND H.VESSEL_ID = " & Val(iLrNum) & " " + _
                    " AND H.CUSTOMER_ID = " & Val(iCust) & " " + _
                    " AND H.COMMODITY_CODE = " & Val(iComm) & " " + _
                    " AND H.SERVICE_CODE LIKE '" & iService & "%' " + _
                    " AND H.HIRE_DATE >= TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " + _
                    " AND HIRE_DATE < TO_DATE('" & Format(iDate, "MM/DD/YYYY") & "','MM/DD/YYYY') +1"
        Set dsSHORT_TERM_DATA = OraDatabase2.dbcreatedynaset(SqlStmt, 0&)
        
        'fill grid
        grdData.AddItem Trim$(dsBILLING_RETRIEVE_DATA.Fields("SERVICE_CODE").Value) + Chr(9) + _
                         Trim$(dsBILLING_RETRIEVE_DATA.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                         Trim$(dsBILLING_RETRIEVE_DATA.Fields("LABOR_TYPE").Value) + Chr(9) + _
                         dsBILLING_RETRIEVE_DATA.Fields("THE_START").Value + Chr(9) + _
                         dsBILLING_RETRIEVE_DATA.Fields("THE_END").Value + Chr(9) + _
                         Trim$(dsBILLING_RETRIEVE_DATA.Fields("LABOR_RATE_TYPE").Value) + Chr(9) + _
                         Trim$(dsBILLING_RETRIEVE_DATA.Fields("SERVICE_QTY").Value) + Chr(9) + _
                         dsBILLING_RETRIEVE_DATA.Fields("SERVICE_RATE").Value + Chr(9) + _
                         dsBILLING_RETRIEVE_DATA.Fields("SERVICE_AMOUNT").Value + Chr(9) + Chr(9) + Chr(9) + _
                         Trim$(dsSHORT_TERM_DATA.Fields("USER_NAME").Value) + Chr(9) + _
                         Chr(9) + _
                         Trim$(dsSHORT_TERM_DATA.Fields("USER_ID").Value)
    
        grdData.Refresh
        dsBILLING_RETRIEVE_DATA.MoveNext
        
    Next iRec

    grdData.MoveFirst
    
    For iRec = 0 To grdData.Rows - 1
                    
            If grdData.Columns(3).Value <> "" And grdData.Columns(4).Value <> "" Then
                dDiff = DateDiff("n", grdData.Columns(3).Value, grdData.Columns(4).Value)
                dHr = dDiff \ 60
                dMin = (dDiff Mod 60) / 60
                
                grdData.Columns(10).Value = CDbl(dHr + dMin)
            End If
        
    
            SqlStmt = " SELECT  * FROM LABOR_RATE WHERE RATE_TYPE = '" & grdData.Columns(5).Value & "'" _
                    & " AND LABOR_TYPE = '" & grdData.Columns(2).Value & "'"
            Set dsLABOR_RATE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
            If dsLABOR_RATE.RecordCount > 0 Then
                grdData.Columns(7).Value = CStr(dsLABOR_RATE.Fields("RATE").Value)
                If grdData.Columns(10).Value <> "" Then
                    grdData.Columns(8).Value = Round(grdData.Columns(10).Value * dsLABOR_RATE.Fields("RATE").Value, 2) * grdData.Columns(6).Value
                End If
            End If

       grdData.MoveNext
    Next iRec
    
         
    Call CalcTotal
    
    grdData.MoveFirst

End Sub


