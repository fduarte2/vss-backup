VERSION 5.00
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "CRYSTL32.OCX"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmCManData 
   BackColor       =   &H00FFFFC0&
   Caption         =   "Cargo Manifest Data Entry"
   ClientHeight    =   7050
   ClientLeft      =   915
   ClientTop       =   1680
   ClientWidth     =   15240
   BeginProperty Font 
      Name            =   "MS Sans Serif"
      Size            =   8.25
      Charset         =   0
      Weight          =   700
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   ScaleHeight     =   7050
   ScaleWidth      =   15240
   Begin VB.TextBox txtLRNum 
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
      Left            =   1170
      MaxLength       =   7
      TabIndex        =   0
      Top             =   120
      Width           =   975
   End
   Begin SSDataWidgets_B.SSDBDropDown SSDBDroploc 
      Height          =   255
      Left            =   10440
      TabIndex        =   25
      Top             =   600
      Width           =   1815
      DataFieldList   =   "Column 0"
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      ColumnHeaders   =   0   'False
      BackColorOdd    =   16777152
      RowHeight       =   423
      Columns(0).Width=   3200
      Columns(0).DataType=   8
      Columns(0).FieldLen=   4096
      _ExtentX        =   3201
      _ExtentY        =   450
      _StockProps     =   77
   End
   Begin SSDataWidgets_B.SSDBGrid SSDBGridmanifest 
      Height          =   4095
      Left            =   0
      TabIndex        =   8
      Top             =   2040
      Width           =   15255
      ScrollBars      =   2
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   10
      AllowAddNew     =   -1  'True
      AllowColumnMoving=   0
      AllowColumnSwapping=   0
      BackColorOdd    =   16777152
      RowHeight       =   423
      Columns.Count   =   10
      Columns(0).Width=   1535
      Columns(0).Caption=   "BOL*"
      Columns(0).Name =   "BOL"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   5900
      Columns(1).Caption=   "MARK*"
      Columns(1).Name =   "MARK"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   2355
      Columns(2).Caption=   "QUANTITY 1*"
      Columns(2).Name =   "QUANTITY1"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1614
      Columns(3).Caption=   "UNIT 1*"
      Columns(3).Name =   "UNIT1"
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2328
      Columns(4).Caption=   "QUANTITY 2"
      Columns(4).Name =   "QUANTITY2"
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1482
      Columns(5).Caption=   "UNIT 2"
      Columns(5).Name =   "UNIT2"
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1905
      Columns(6).Caption=   "WEIGHT"
      Columns(6).Name =   "WEIGHT"
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   2514
      Columns(7).Caption=   "WEIGHT UNIT"
      Columns(7).Name =   "WEIGHT UNIT"
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   2275
      Columns(8).Caption=   "STATUS"
      Columns(8).Name =   "STATUS"
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   4180
      Columns(9).Caption=   "CARGO LOCATION"
      Columns(9).Name =   "CARGO LOCATION"
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      _ExtentX        =   26908
      _ExtentY        =   7223
      _StockProps     =   79
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
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.CommandButton cmdVesselEntry 
      Caption         =   "&Vessel Entry"
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
      Left            =   12360
      TabIndex        =   13
      Top             =   6240
      Width           =   1395
   End
   Begin Crystal.CrystalReport CrtManifest 
      Left            =   9960
      Top             =   240
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&Print"
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
      Left            =   11160
      TabIndex        =   12
      Top             =   6240
      Width           =   1065
   End
   Begin VB.CommandButton cmdBilling 
      Caption         =   "&Billing"
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
      Left            =   13920
      TabIndex        =   14
      Top             =   6240
      Width           =   1155
   End
   Begin VB.Frame fraType 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Type"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   825
      Left            =   6480
      TabIndex        =   23
      Top             =   240
      Width           =   1215
      Begin VB.OptionButton optImpex 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Import"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Index           =   0
         Left            =   180
         TabIndex        =   5
         Top             =   240
         Width           =   825
      End
      Begin VB.OptionButton optImpex 
         BackColor       =   &H00FFFFC0&
         Caption         =   "Export"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   8.25
            Charset         =   0
            Weight          =   400
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   255
         Index           =   1
         Left            =   180
         TabIndex        =   6
         Top             =   480
         Width           =   825
      End
   End
   Begin VB.CommandButton cmdRecipientList 
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
      Left            =   2190
      Picture         =   "CManData.frx":0000
      Style           =   1  'Graphical
      TabIndex        =   17
      TabStop         =   0   'False
      Top             =   540
      Width           =   345
   End
   Begin VB.CommandButton cmdVesselList 
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
      Left            =   2190
      Picture         =   "CManData.frx":0102
      Style           =   1  'Graphical
      TabIndex        =   9
      TabStop         =   0   'False
      Top             =   120
      Width           =   345
   End
   Begin VB.TextBox txtRecipientId 
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
      Left            =   1170
      MaxLength       =   6
      TabIndex        =   1
      Top             =   540
      Width           =   975
   End
   Begin VB.TextBox txtCustomerName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
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
      Left            =   2580
      MaxLength       =   40
      TabIndex        =   18
      Top             =   540
      Width           =   3435
   End
   Begin VB.TextBox txtVesselName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
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
      Left            =   2580
      MaxLength       =   40
      TabIndex        =   15
      Top             =   120
      Width           =   3435
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
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
      Left            =   8760
      TabIndex        =   10
      Top             =   6240
      Width           =   1065
   End
   Begin VB.CommandButton cmdRetrieve 
      Caption         =   "&Retrieve"
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
      Left            =   9960
      TabIndex        =   11
      Top             =   6240
      Width           =   1065
   End
   Begin VB.TextBox txtDateReceived 
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
      Left            =   1170
      MaxLength       =   10
      TabIndex        =   4
      Top             =   1440
      Width           =   1425
   End
   Begin VB.CheckBox chkReceiveCargo 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Receive Cargo into Inventory"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   405
      Left            =   8400
      TabIndex        =   7
      Top             =   600
      Value           =   1  'Checked
      Width           =   1425
   End
   Begin VB.CommandButton cmdCommodityList 
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
      Left            =   2190
      Picture         =   "CManData.frx":0204
      Style           =   1  'Graphical
      TabIndex        =   20
      TabStop         =   0   'False
      Top             =   960
      Width           =   345
   End
   Begin VB.TextBox txtCommodityCode 
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
      Left            =   1170
      MaxLength       =   12
      TabIndex        =   3
      Top             =   960
      Width           =   975
   End
   Begin VB.TextBox txtCommodityName 
      BackColor       =   &H00FFFFC0&
      Enabled         =   0   'False
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
      Left            =   2580
      MaxLength       =   40
      TabIndex        =   21
      Top             =   960
      Width           =   3435
   End
   Begin SSDataWidgets_B.SSDBDropDown SSDBDropStatus 
      Height          =   255
      Left            =   10440
      TabIndex        =   26
      Top             =   360
      Width           =   1815
      DataFieldList   =   "Column 0"
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      ColumnHeaders   =   0   'False
      BackColorOdd    =   16777152
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   3201
      _ExtentY        =   450
      _StockProps     =   77
   End
   Begin SSDataWidgets_B.SSDBDropDown SSDBDropUnit 
      Height          =   255
      Left            =   10560
      TabIndex        =   27
      Top             =   0
      Width           =   1815
      DataFieldList   =   "Column 0"
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      Rows            =   1
      ColumnHeaders   =   0   'False
      BackColorOdd    =   16777152
      RowHeight       =   423
      Columns(0).Width=   3200
      _ExtentX        =   3201
      _ExtentY        =   450
      _StockProps     =   77
      DataFieldToDisplay=   "Column 0"
   End
   Begin VB.Shape Shape1 
      BorderColor     =   &H80000002&
      BorderWidth     =   2
      Height          =   4125
      Left            =   0
      Top             =   2040
      Width           =   15345
   End
   Begin VB.Label lblOwner 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Owner"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   150
      TabIndex        =   16
      Top             =   600
      Width           =   975
   End
   Begin VB.Label lblVessel 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Vessel"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   150
      TabIndex        =   2
      Top             =   180
      Width           =   975
   End
   Begin VB.Label lblStatus 
      BorderStyle     =   1  'Fixed Single
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
      Left            =   0
      TabIndex        =   24
      Top             =   6720
      Width           =   15825
   End
   Begin VB.Label lblDateReceived 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Date Rcvd"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   120
      TabIndex        =   22
      Top             =   1440
      Width           =   1125
   End
   Begin VB.Label lblCommodity 
      BackColor       =   &H00FFFFC0&
      Caption         =   "Commodity"
      ForeColor       =   &H00000000&
      Height          =   225
      Left            =   150
      TabIndex        =   19
      Top             =   1020
      Width           =   975
   End
End
Attribute VB_Name = "frmCManData"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Dim myflag As Boolean
Private Sub chkReceiveCargo_LostFocus()

    Call cmdRetrieve_Click
    
End Sub
Private Sub cmdBilling_Click()

   Unload frmCManData
   frmVeslBill.Show
   
End Sub
Private Sub cmdCommodityList_Click()

    Dim iPos As Integer
    
    Me.MousePointer = vbHourglass
    
    lblStatus.Caption = "Loading Commodities..."
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Commodity List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE ORDER BY COMMODITY_CODE"
    Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RecordCount > 0 Then
        While Not dsCOMMODITY_PROFILE.eof
            frmPV.lstPV.AddItem dsCOMMODITY_PROFILE.Fields("COMMODITY_CODE").Value & " : " & dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value
            dsCOMMODITY_PROFILE.MoveNext
        Wend
    End If
    
    lblStatus.Caption = "Commodities Loaded."
    Me.MousePointer = vbDefault
    
    frmPV.Show vbModal
    
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCommodityCode.Text = Left$(gsPVItem, iPos - 1)
            txtCommodityName.Text = Mid$(gsPVItem, iPos + 3)
            gComcode = txtCommodityCode.Text
        End If
    End If
    
End Sub
Private Sub cmdContinue_Click()

    'If SaveSuccess Then
        'Call ClearScreen("BOL_ONWARDS")
    'End If
    
End Sub
Private Sub cmdBOL_Click(Index As Integer)

    'If lstBOL.ListCount > 0 Then
        'lstBOL.Visible = Not lstBOL.Visible
    'Else
        'lstBOL.Visible = False
    'End If
    
End Sub
Private Sub cmdMark_Click(Index As Integer)

    'If lstMark.ListCount > 0 Then
        'lstMark.Visible = Not lstMark.Visible
    'Else
        'lstMark.Visible = False
    'End If
    
End Sub
Private Sub cmdPrint_Click()

    Dim iRec As Integer
    
    Printer.Orientation = 2
    
    Printer.Print ""
    Printer.Print Tab(5); "Printed on:"; Tab(20); Date
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 12
    Printer.Print Tab(65); "CARGO MANIFEST"
    Printer.FontSize = 9
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(5); "VESSEL"; Tab(25); txtVesselName
    Printer.Print Tab(5); "CUSTOMER"; Tab(25); txtCustomerName
    Printer.Print Tab(5); "COMMODITY"; Tab(25); txtCommodityName
    Printer.Print Tab(5); "START DATE"; Tab(25); txtDateReceived
    Printer.FontSize = 8
    Printer.Print ""
    Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------------------"
    Printer.Print Tab(5); "BOL"; Tab(30); "MARK"; Tab(80); "QTY1"; Tab(95); "UNT1"; Tab(105); "QTY2"; _
                  Tab(120); "UNT2"; Tab(130); "WT"; Tab(145); "WT UNT"; Tab(160); "STATUS"; Tab(175); "LOCATION"
    Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------------------"
                          
    
    With SSDBGridmanifest
        .MoveFirst
    
        For iRec = 0 To .Rows - 1
            
            Printer.Print Tab(5); .Columns(0).Value; Tab(30); .Columns(1).Value; _
                          Tab(80); Trim("" & .Columns(2).Value); Tab(95); .Columns(3).Value; _
                          Tab(105); Trim(.Columns(4).Value); Tab(120); Trim(.Columns(5).Value); _
                          Tab(130); Trim(.Columns(6).Value); Tab(145); Trim(.Columns(7).Value); _
                          Tab(160); Trim(.Columns(8).Value); Tab(175); Trim(.Columns(9).Value)
            .MoveNext
            
        Next iRec
        
        Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------------------"
        Printer.Print Tab(15); "TOTAL :"; Tab(45); .Rows
        Printer.Print Tab(3); "----------------------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------------------"
        .MoveFirst
    End With
    
    Printer.EndDoc
        
End Sub
Private Sub cmdRecipientList_Click()

    Dim iPos As Integer
    
    Me.MousePointer = vbHourglass
    lblStatus.Caption = "Loading Customers..."
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Customer List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
        While Not dsCUSTOMER_PROFILE.eof
            frmPV.lstPV.AddItem dsCUSTOMER_PROFILE.Fields("CUSTOMER_ID").Value & " : " & dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
            dsCUSTOMER_PROFILE.MoveNext
        Wend
    End If
    
    lblStatus.Caption = "Customers Loaded."
    Me.MousePointer = vbDefault
    
    frmPV.Show vbModal
    
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtRecipientId.Text = Left$(gsPVItem, iPos - 1)
            txtCustomerName.Text = Mid$(gsPVItem, iPos + 3)
            gEcode = txtRecipientId.Text
        End If
        txtCommodityCode.SetFocus
    End If
    
End Sub
Private Sub cmdRetrieve_Click()

    Dim i, j As Integer
    Dim iFound As Integer
    Dim sContainerNum As String
    Dim bFrominventory As Boolean
    Dim sCargoBol As String
    Dim sCargoMark As String
    Dim lQuanExp As String
    Dim lQuan2Exp As String
    Dim sQty1Unit As String
    Dim sQty2Unit As String
    Dim lCargoWeight As String
    Dim sCargoWUnit As String
    Dim sCargoStatus As String
    Dim sCargoLoc As String
    Dim sValue As String
    Dim iImpex As String
     
     bRetrive = True
     SSDBGridmanifest.RemoveAll
     If Trim$(txtLRNum.Text) = "" Then
        MsgBox "Please enter Vessel Number.", vbInformation, "Retrieve"
        txtLRNum.SetFocus
        Exit Sub
     End If
    
     If Trim$(txtRecipientId.Text) = "" Then
        MsgBox "Please enter Owner.", vbInformation, "Retrieve"
        txtRecipientId.SetFocus
        Exit Sub
     End If
    
     If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Please enter Commodity Code.", vbInformation, "Retrieve"
        txtCommodityCode.SetFocus
        Exit Sub
     End If
     
     iImpex = ""
     
     If optImpex(0).Value = False And optImpex(1).Value = False Then
        MsgBox "Please select a type.", vbInformation, "Retrieve"
        optImpex(0).SetFocus
        Exit Sub
     End If
     
     If optImpex(0).Value = True Then
        iImpex = "I"
     ElseIf optImpex(1).Value = True Then
        iImpex = "E"
     End If
     
     If chkReceiveCargo.Value = 1 Then 'Checked
        gsSqlStmt = " SELECT * FROM " _
                  & " CARGO_MANIFEST CM,CARGO_TRACKING CT " _
                  & " WHERE CM.LR_NUM = '" & Trim(txtLRNum.Text) & "' AND " _
                  & " CM.RECIPIENT_ID = '" & Trim(txtRecipientId.Text) & "' AND " _
                  & " CM.COMMODITY_CODE ='" & Trim(txtCommodityCode.Text) & "' AND " _
                  & " CM.IMPEX='" & iImpex & "' AND" _
                  & " CM.CONTAINER_NUM=CT.LOT_NUM AND " _
                  & " CT.DATE_RECEIVED>=TO_DATE('" & Format(txtDateReceived, "MM/DD/YYYY") & "','MM/DD/YYYY')"
        
        Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
     Else
        gsSqlStmt = " SELECT * FROM " _
                  & " CARGO_MANIFEST CM WHERE " _
                  & " LR_NUM = '" & Trim(txtLRNum.Text) & "' AND " _
                  & " CM.RECIPIENT_ID = '" & Trim(txtRecipientId.Text) & "' AND" _
                  & " CM.COMMODITY_CODE ='" & Trim(txtCommodityCode.Text) & "' AND " _
                  & " CM.IMPEX='" & iImpex & "' AND" _
                  & " CONTAINER_NUM NOT IN (SELECT LOT_NUM FROM " _
                  & " CARGO_TRACKING WHERE LOT_NUM IN(SELECT LOT_NUM FROM" _
                  & " VOYAGE_CARGO WHERE LR_NUM ='" & Trim(txtLRNum.Text) & "'))"
                  
        Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
     End If
     If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RecordCount > 0 Then
         If OraDatabase.LastServerErr <> 0 Then
             MsgBox "Error occured while retrieving. Please contact administrator.", vbExclamation, "Retrieve"
             Exit Sub
         End If
         If dsCARGO_MANIFEST.Fields("IMPEX").Value = "I" Then
             optImpex(0).Value = True
         ElseIf dsCARGO_MANIFEST.Fields("IMPEX").Value = "E" Then
             optImpex(1).Value = True
         'ElseIf dsCARGO_MANIFEST.Fields("IMPEX").Value = "B" Then
             'optImpex(2).Value = True
         End If
         'Show fields
         'If dsCARGO_MANIFEST.RECORDCOUNT > 9 Then
             'MsgBox "There are more than 10 Records. Only first 10 will be used.", vbInformation, "Manifest"
         'End If
         'i = 0
         Do While Not dsCARGO_MANIFEST.eof
             If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_BOL").Value) Then
                 sCargoBol = dsCARGO_MANIFEST.Fields("CARGO_BOL").Value
                 sCargoMark = dsCARGO_MANIFEST.Fields("CARGO_MARK").Value
                 lQuanExp = dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value
                 If Not IsNull(dsCARGO_MANIFEST.Fields("QTY2_EXPECTED").Value) Then
                     lQuan2Exp = dsCARGO_MANIFEST.Fields("QTY2_EXPECTED").Value
                 End If
                 If Not IsNull(dsCARGO_MANIFEST.Fields("QTY1_UNIT")) Then
                     sQty1Unit = dsCARGO_MANIFEST.Fields("QTY1_UNIT").Value
                 End If
                 If Not IsNull(dsCARGO_MANIFEST.Fields("QTY2_UNIT")) Then
                     sQty2Unit = dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value
                 End If
                 lCargoWeight = dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value
                 If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value) Then
                     sCargoWUnit = dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value
                 End If
                 If Not IsNull(dsCARGO_MANIFEST.Fields("MANIFEST_STATUS")) Then
                     sCargoStatus = dsCARGO_MANIFEST.Fields("MANIFEST_STATUS").Value
                 End If
                 If Not IsNull(dsCARGO_MANIFEST.Fields("CARGO_LOCATION").Value) Then
                     sCargoLoc = dsCARGO_MANIFEST.Fields("CARGO_LOCATION").Value
                 End If
                 sValue = sCargoBol + Chr$(9) + sCargoMark + Chr$(9) + _
                          lQuanExp + Chr$(9) + sQty1Unit + Chr$(9) + lQuan2Exp + Chr$(9) + _
                          sQty2Unit + Chr$(9) + lCargoWeight + Chr$(9) + sCargoWUnit + Chr$(9) + _
                          sCargoStatus + Chr$(9) + sCargoLoc
                 SSDBGridmanifest.AddItem sValue
             End If
                dsCARGO_MANIFEST.MoveNext
                SSDBGridmanifest.MoveNext
         Loop
         gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text
         gsSqlStmt = gsSqlStmt & " AND RECIPIENT_ID = " & txtRecipientId.Text
         gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & txtCommodityCode.Text
         Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
         'Get date
         gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value & "'"
         Set dsCARGO_TRACKING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
         If OraDatabase.LastServerErr = 0 Then
             If dsCARGO_TRACKING.RecordCount > 0 Then
                 'chkReceiveCargo.Value = 1
                 If Not IsNull(dsCARGO_TRACKING.Fields("DATE_RECEIVED")) Then
                     txtDateReceived.Text = Format(dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value, "MM/DD/YYYY")
                 End If
             Else
                 'chkReceiveCargo.Value = 0
             End If
         End If
     End If
     
End Sub
Private Sub cmdSave_Click()

    If SaveSuccess Then
       SSDBGridmanifest.RemoveAll
       Call ClearScreen
    End If
    
End Sub
Private Sub cmdVesselEntry_Click()

    Unload frmCManData
    frmVeslEnt.Show
    
End Sub
Private Sub cmdVesselList_Click()

    Dim iPos As Integer
    
    Me.MousePointer = vbHourglass
    
    lblStatus.Caption = "Loading Vessels..."
    Load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "Vessel List"
    frmPV.lstPV.Clear
    
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RecordCount > 0 Then
        While Not dsVESSEL_PROFILE.eof
            frmPV.lstPV.AddItem dsVESSEL_PROFILE.Fields("LR_NUM").Value & " : " & dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value
            dsVESSEL_PROFILE.MoveNext
        Wend
    End If
    
    lblStatus.Caption = "Vessels Loaded."
    Me.MousePointer = vbDefault
    frmPV.Show vbModal
    
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtLRNum.Text = Left$(gsPVItem, iPos - 1)
            txtVesselName.Text = Mid$(gsPVItem, iPos + 3)
            Call txtLRNum_LostFocus
        End If
        txtRecipientId.SetFocus
    End If
    
End Sub
Private Sub Form_Activate()

 txtLRNum.Text = frmVeslEnt.txtLRNum.Text
 gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLRNum.Text
 Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
 txtVesselName.Text = NVL(dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value, "")

End Sub
Private Sub Form_Load()

Dim Index As Integer
    'Center the form
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    Me.MousePointer = vbHourglass
    
    lblStatus.Caption = "Logging to database..."
    Me.Show
    Me.Refresh
    DoEvents
    
    On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    'Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    'Set OraDatabase = OraSession.OpenDatabase("test", "SAG_OWNER/SAG", 0&)
    
    'Display Vessel Number
    'txtLRNum.Text = frmVeslEnt.txtLRNum.Text
    'txtVesselName.Text = frmVeslEnt.txtVesselName.Text
     
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
        'cboManifestStatus.ListIndex = 0
        optImpex(0).Value = True
        'Load units
        gsSqlStmt = "SELECT * FROM UNITS"
        Set dsUNITS = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsUNITS.RecordCount > 0 Then
            'Load units
            gsSqlStmt = "SELECT DISTINCT UOM FROM UNITS"
            Set dsUNITS = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsUNITS.RecordCount > 0 Then
                While Not dsUNITS.eof
                  If Not IsNull(dsUNITS.Fields("UOM").Value) Then
                      SSDBDropUnit.AddItem dsUNITS.Fields("UOM").Value
                  End If
                  dsUNITS.MoveNext
                  SSDBDropUnit.MoveNext
                Wend
            End If
        End If
        'Load location category
        gsSqlStmt = "SELECT LOCATION_TYPE FROM LOCATION_CATEGORY ORDER BY LOCATION_TYPE"
        Set dsLOCATION_CATEGORY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsLOCATION_CATEGORY.RecordCount > 0 Then
            While Not dsLOCATION_CATEGORY.eof
               If Not IsNull(dsLOCATION_CATEGORY.Fields("LOCATION_TYPE")) Then
                   SSDBDroploc.AddItem dsLOCATION_CATEGORY.Fields("LOCATION_TYPE").Value
               End If
                dsLOCATION_CATEGORY.MoveNext
                SSDBDroploc.MoveNext
            Wend
        End If
        'Load Manifest status
        gsSqlStmt = "SELECT DISTINCT MANIFEST_STATUS FROM CARGO_MANIFEST "
        Set dsCARGO_STATUS = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCARGO_STATUS.RecordCount > 0 Then
            While Not dsCARGO_STATUS.eof
               If Not IsNull(dsCARGO_STATUS.Fields("MANIFEST_STATUS")) Then
                   SSDBDropStatus.AddItem dsCARGO_STATUS.Fields("MANIFEST_STATUS").Value
               End If
                dsCARGO_STATUS.MoveNext
                SSDBDroploc.MoveNext
            Wend
        End If
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
    
    Me.MousePointer = vbDefault
    
    'Call ClearScreen("FULL")
    Call display_Combo
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Cargo Manifest"
    lblStatus.Caption = "Error Occured."
    Me.MousePointer = vbDefault
    On Error GoTo 0

End Sub

Private Sub lblQty1_Click()

End Sub

Private Sub Form_Unload(Cancel As Integer)
  frmVeslEnt.Show
End Sub

Private Sub SSDBGridmanifest_RowColChange(ByVal LastRow As Variant, ByVal LastCol As Integer)

  If Not LastCol <= -1 Then
    SSDBGridmanifest.Columns(LastCol).Text = UCase$(SSDBGridmanifest.Columns(LastCol).Text)
  End If
  
End Sub
Private Sub txtCommodityCode_LostFocus()

    If Trim$(txtCommodityCode) <> "" Then
        gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & txtCommodityCode.Text
        Set dsCOMMODITY_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.RecordCount > 0 Then
            txtCommodityName.Text = dsCOMMODITY_PROFILE.Fields("COMMODITY_NAME").Value
        Else
            MsgBox "Commodity does not exist.", vbExclamation, "Commodity"
        End If
    End If
    gComcode = txtCommodityCode.Text
    
End Sub
Private Sub txtLRNum_GotFocus()

'Display Vessel Number
    frmCManData.txtLRNum.Text = frmVeslEnt.txtLRNum.Text
    frmCManData.txtVesselName.Text = frmVeslEnt.txtVesselName.Text
    
End Sub
Private Sub txtLRNum_LostFocus()

    If Trim$(txtLRNum) <> "" And IsNumeric(txtLRNum) Then
        gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLRNum.Text
        Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.RecordCount > 0 Then
            txtVesselName.Text = dsVESSEL_PROFILE.Fields("VESSEL_NAME").Value
            
            'Default Date Received from the Voyage
            gsSqlStmt = "SELECT DATE_DEPARTED FROM VOYAGE WHERE LR_NUM = " & txtLRNum.Text & " AND "
            gsSqlStmt = gsSqlStmt & "ARRIVAL_NUM = 1"
            Set dsVOYAGE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsVOYAGE.RecordCount > 0 Then
                If Not IsNull(dsVOYAGE.Fields("DATE_DEPARTED").Value) Then
                    txtDateReceived.Text = Format(dsVOYAGE.Fields("DATE_DEPARTED").Value, "mm/dd/yyyy")
                End If
            End If
        Else
            Exit Sub
        End If
    End If
    'gVcode = txtLRNum.Text
    
End Sub
Private Sub txtQty1_LostFocus(Index As Integer)
    
    If Trim$(txtLRNum.Text) = "" Then
        MsgBox "Please enter Vessel Number.", vbInformation, "Check"
        txtLRNum.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtRecipientId.Text) = "" Then
        MsgBox "Please enter Owner.", vbInformation, "Check"
        txtRecipientId.SetFocus
        Exit Sub
    End If
    
    If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Please enter Commodity Code.", vbInformation, "Check"
        txtCommodityCode.SetFocus
        Exit Sub
    End If
  
    Call setQtyInHouse
    
End Sub
Private Sub txtRecipientId_LostFocus()

    If Trim$(txtRecipientId) <> "" Then
        gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtRecipientId.Text
        Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.RecordCount > 0 Then
            txtCustomerName.Text = dsCUSTOMER_PROFILE.Fields("CUSTOMER_NAME").Value
        Else
            MsgBox "Customer does not exist.", vbExclamation, "Customer"
        End If
    End If
    gEcode = txtRecipientId.Text
    
End Sub
Public Function SaveSuccess() As Integer

    Dim sContainerNum As String
    Dim dContainerNum As Double
    Dim iAlreadyExists As Integer
    Dim intNoOFRec As Integer
    Dim lFreeDays As Long
    Dim count As Integer
    Dim ct As Integer
     
    SaveSuccess = False
    If Trim$(txtLRNum.Text) = "" Then
        MsgBox "Please Select/Enter a Vessel.", vbInformation, "Save"
        txtLRNum.SetFocus
        Exit Function
    End If
    
    If Trim$(txtRecipientId.Text) = "" Then
        MsgBox "Please Select/Enter an Owner.", vbInformation, "Save"
        txtRecipientId.SetFocus
        Exit Function
    End If
    
    If Trim$(txtCommodityCode.Text) = "" Then
        MsgBox "Please Select/Enter a Commodity.", vbInformation, "Save"
        txtCommodityCode.SetFocus
        Exit Function
    End If
    
    'Added this to make sure that the date field was always filled. 06.12.2001 LJG
    If Trim$(txtDateReceived.Text) = "" Then
        MsgBox "Please Enter a Date Received.", vbInformation, "Save"
        txtCommodityCode.SetFocus
        Exit Function
    End If
        
    'This checks all the values in the grid to make sure all needed fields are filled
    'before the save.  06.12.2001 LJG
    SSDBGridmanifest.MoveFirst
        
    For count = 0 To SSDBGridmanifest.Rows - 1
        If Trim$(SSDBGridmanifest.Columns(0).Value) = "" Then
            MsgBox "Please enter a Cargo BOL.", vbInformation, "Save"
            SSDBGridmanifest.Columns(0).SetFocus
            SSDBGridmanifest.MoveFirst
            Exit Function
        End If
        
        If Trim$(SSDBGridmanifest.Columns(1).Value) = "" Then
            MsgBox "Please enter a Cargo Mark.", vbInformation, "Save"
            SSDBGridmanifest.Columns(1).SetFocus
            SSDBGridmanifest.MoveFirst
            Exit Function
        End If
        
        If Trim$(SSDBGridmanifest.Columns(2).Value) = "" Then
            MsgBox "Please enter a value for Qty 1.", vbInformation, "Save"
            SSDBGridmanifest.Columns(2).SetFocus
            SSDBGridmanifest.MoveFirst
            Exit Function
        End If
        
        If Trim$(SSDBGridmanifest.Columns(3).Value) = "" Then
            MsgBox "Please enter a value for Unit 1.", vbInformation, "Save"
            SSDBGridmanifest.Columns(3).SetFocus
            SSDBGridmanifest.MoveFirst
            Exit Function
        End If
        
        If Trim$(SSDBGridmanifest.Columns(4).Value) <> "" Then
            If Trim$(SSDBGridmanifest.Columns(5).Value) = "" Then
                MsgBox "Please enter a value for Unit 2.", vbInformation, "Save"
                SSDBGridmanifest.Columns(5).SetFocus
                SSDBGridmanifest.MoveFirst
            Exit Function
            End If
        End If
        
        If Trim$(SSDBGridmanifest.Columns(6).Value) = "" Then
            MsgBox "Please enter a value for Weight.", vbInformation, "Save"
            SSDBGridmanifest.Columns(6).SetFocus
            SSDBGridmanifest.MoveFirst
            Exit Function
        End If
        
        If Trim$(SSDBGridmanifest.Columns(7).Value) = "" Then
            MsgBox "Please enter a Weight Unit.", vbInformation, "Save"
            SSDBGridmanifest.Columns(7).SetFocus
            SSDBGridmanifest.MoveFirst
            Exit Function
        End If
        
        If Trim$(SSDBGridmanifest.Columns(8).Value) = "" Then
            MsgBox "Please enter a value for Status.", vbInformation, "Save"
            SSDBGridmanifest.Columns(8).SetFocus
            SSDBGridmanifest.MoveFirst
            Exit Function
        End If
        
        If Trim$(SSDBGridmanifest.Columns(9).Value) = "" Then
            MsgBox "Please enter a Cargo Location.", vbInformation, "Save"
            SSDBGridmanifest.Columns(9).SetFocus
            SSDBGridmanifest.MoveFirst
            Exit Function
        End If
        
        SSDBGridmanifest.MoveNext
    Next
    
    
    'Check to see if the CargoLocation value is valid.  06.14.2001
    Dim dsLocCat As Object
    gsSqlStmt = " Select * from LOCATION_CATEGORY where " & _
                " LOCATION_TYPE = '" & Trim$(SSDBGridmanifest.Columns(9).Value) & "' "
    Set dsLocCat = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
     
    If dsLocCat.RecordCount = "" Then
        MsgBox "Please enter a Valid Cargo Location.", vbInformation, "Save"
        SSDBGridmanifest.Columns(9).SetFocus
        SSDBGridmanifest.MoveFirst
        Exit Function
    End If
    
    If chkReceiveCargo.Value = 1 Then 'checked
       Call setQtyInHouse
    End If
    If OraDatabase.LastServerErr = 0 Then
        'Begin a transaction
        OraSession.BeginTrans
        SSDBGridmanifest.MoveFirst
        For count = 0 To SSDBGridmanifest.Rows - 1
            gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text
            gsSqlStmt = gsSqlStmt & " AND RECIPIENT_ID = " & txtRecipientId.Text
            gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & txtCommodityCode.Text
            gsSqlStmt = gsSqlStmt & " AND CARGO_BOL = '" & SSDBGridmanifest.Columns(0).Value & "'"
            gsSqlStmt = gsSqlStmt & " AND CARGO_MARK = '" & SSDBGridmanifest.Columns(1).Value & "'"
    
            Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        
            If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RecordCount > 0 Then
                iAlreadyExists = True
                sContainerNum = dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value
            Else
                iAlreadyExists = False
                'Get the minimum container number and subtract 1 to get new container number
                gsSqlStmt = "SELECT MIN(TO_NUMBER(CONTAINER_NUM)) INTO :A FROM CARGO_MANIFEST"
                Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RecordCount > 0 Then
                    sContainerNum = dsCARGO_MANIFEST.Fields("MIN(TO_NUMBER(CONTAINER_NUM))").Value
                    dContainerNum = Val(sContainerNum)
                    dContainerNum = dContainerNum - 1
                    sContainerNum = CStr(dContainerNum)
                End If
            
                gsSqlStmt = "SELECT * FROM CARGO_MANIFEST"
                Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            End If
            If iAlreadyExists Then
                dsCARGO_MANIFEST.Edit
            Else
                dsCARGO_MANIFEST.AddNew
                dsCARGO_MANIFEST.Fields("LR_NUM").Value = txtLRNum.Text
                dsCARGO_MANIFEST.Fields("ARRIVAL_NUM").Value = 1
                dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value = sContainerNum
                dsCARGO_MANIFEST.Fields("RECIPIENT_ID").Value = txtRecipientId.Text
                dsCARGO_MANIFEST.Fields("CARGO_BOL").Value = SSDBGridmanifest.Columns(0).Value
                dsCARGO_MANIFEST.Fields("CARGO_MARK").Value = SSDBGridmanifest.Columns(1).Value
            End If
            dsCARGO_MANIFEST.Fields("EXPORTER_ID").Value = txtRecipientId.Text
            dsCARGO_MANIFEST.Fields("QTY_EXPECTED").Value = SSDBGridmanifest.Columns(2).Value
            If Trim$(SSDBGridmanifest.Columns(6).Text) <> "" Then
                dsCARGO_MANIFEST.Fields("CARGO_WEIGHT").Value = SSDBGridmanifest.Columns(6).Value
            End If
            dsCARGO_MANIFEST.Fields("CARGO_WEIGHT_UNIT").Value = SSDBGridmanifest.Columns(7).Value
            dsCARGO_MANIFEST.Fields("COMMODITY_CODE").Value = txtCommodityCode.Text
            dsCARGO_MANIFEST.Fields("CARGO_LOCATION").Value = SSDBGridmanifest.Columns(9).Value
            If optImpex(0).Value = True Then
                dsCARGO_MANIFEST.Fields("IMPEX").Value = "I"
            ElseIf optImpex(1).Value = True Then
                dsCARGO_MANIFEST.Fields("IMPEX").Value = "E"
            'ElseIf optImpex(2).Value = True Then
                'dsCARGO_MANIFEST.Fields("IMPEX").Value = "B"
            End If
            dsCARGO_MANIFEST.Fields("MANIFEST_STATUS").Value = SSDBGridmanifest.Columns(8).Value
            dsCARGO_MANIFEST.Fields("QTY2_EXPECTED").Value = SSDBGridmanifest.Columns(4).Value
            dsCARGO_MANIFEST.Fields("QTY1_UNIT").Value = SSDBGridmanifest.Columns(3).Value
            dsCARGO_MANIFEST.Fields("QTY2_UNIT").Value = SSDBGridmanifest.Columns(5).Value
            dsCARGO_MANIFEST.Update
            
            gsSqlStmt = "SELECT * FROM VOYAGE_CARGO WHERE LOT_NUM = '" & sContainerNum & "'"
            Set dsVOYAGE_CARGO = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 Then
                If dsVOYAGE_CARGO.RecordCount = 0 Then
                    dsVOYAGE_CARGO.AddNew
                    dsVOYAGE_CARGO.Fields("LR_NUM").Value = txtLRNum.Text
                    dsVOYAGE_CARGO.Fields("ARRIVAL_NUM").Value = 1
                    dsVOYAGE_CARGO.Fields("CONTAINER_NUM").Value = sContainerNum
                    dsVOYAGE_CARGO.Fields("LOT_NUM").Value = sContainerNum
                    dsVOYAGE_CARGO.Update
                End If
            End If
            'If Received Cargo into Inventory is checked then the data goes to Cargo_tracking Table
            If chkReceiveCargo.Value = 1 Then 'checked
                gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM = '" & sContainerNum & "'"
                Set dsCARGO_TRACKING = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                
                gsSqlStmt = "SELECT * FROM VOYAGE WHERE LR_NUM = " & txtLRNum.Text
                Set dsVOYAGE = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 Then
                    'Check if already exists
                    If dsCARGO_TRACKING.RecordCount > 0 Then
                        dsCARGO_TRACKING.Edit
                        'dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = txtQty1(count).Text 'gQtyInHus
                    Else
                        dsCARGO_TRACKING.AddNew
                        dsCARGO_TRACKING.Fields("LOT_NUM").Value = sContainerNum
                        'dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = txtQty1(count).Text
                    End If
                    dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value = txtCommodityCode.Text
                    dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value = Format(txtDateReceived.Text, "MM/DD/YYYY")
                    dsCARGO_TRACKING.Fields("OWNER_ID").Value = txtRecipientId.Text
                    dsCARGO_TRACKING.Fields("QTY_RECEIVED").Value = SSDBGridmanifest.Columns(2).Value
                    dsCARGO_TRACKING.Fields("RECEIVER_ID").Value = 4 'Super User
                    dsCARGO_TRACKING.Fields("WHSE_RCPT_NUM").Value = 0 'No Whse Rcpt Num Yet
                    dsCARGO_TRACKING.Fields("QTY_UNIT").Value = SSDBGridmanifest.Columns(3).Value
                    dsCARGO_TRACKING.Fields("WAREHOUSE_LOCATION").Value = SSDBGridmanifest.Columns(9).Value
                    dsCARGO_TRACKING.Fields("QTY_IN_HOUSE").Value = SSDBGridmanifest.Columns(2).Value
                                    
                    'Set Free Time
                    'Check if LR_NUM is -1 means its not a ship AND commodity is not 6172
                    If (dsVOYAGE_CARGO.Fields("LR_NUM").Value = -1) And (dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value <> 6172) Then
                       'Update only if WHSE_RCPT_NUM is 0
                       If dsCARGO_TRACKING.Fields("WHSE_RCPT_NUM").Value = 0 Then
                           dsCARGO_TRACKING.Fields("START_FREE_TIME").Value = dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value
                           dsCARGO_TRACKING.Fields("FREE_TIME_END").Value = dsCARGO_TRACKING.Fields("DATE_RECEIVED").Value
                       End If
                    Else
                       'Check if commodity code is 6172, if so set WHSE_RCPT_NUM to -6172 and do not set Start Free Time/Free Time End
                       If dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value = 6172 Then
                           dsCARGO_TRACKING.Fields("WHSE_RCPT_NUM").Value = -6172
                       Else
                           'Get free days for commodity and fill Start Free Time/Free Time End
                           'EDIT Adam Walter, Sep 2008.  New routine to determine free days.
'                           gsSqlStmt = "SELECT * FROM FREE_TIME WHERE COMMODITY_CODE = " & dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value
'                           Set dsFREE_TIME = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
'                          If OraDatabase.LastServerErr = 0 And dsFREE_TIME.RecordCount > 0 Then
                            lFreeDays = FindFreeTime(txtCommodityCode.Text, txtRecipientId.Text, txtLRNum.Text)
                          If lFreeDays <> -1 Then
                              dsCARGO_TRACKING.Fields("FREE_DAYS").Value = lFreeDays
                              If Not IsNull(dsVOYAGE.Fields("FREE_TIME_START")) Then
                                  dsCARGO_TRACKING.Fields("START_FREE_TIME").Value = Format(dsVOYAGE.Fields("FREE_TIME_START").Value, "MM/DD/YYYY")
                                  dsCARGO_TRACKING.Fields("FREE_TIME_END").Value = DateAdd("d", lFreeDays, Format(dsVOYAGE.Fields("FREE_TIME_START").Value, "MM/DD/YYYY"))
                              End If
                          Else
                              MsgBox "There is No present Free Time for the Commodity Code:" & dsCARGO_TRACKING.Fields("COMMODITY_CODE").Value, vbExclamation, "Free Time Error"
                          End If
                       End If
                    End If
                    dsCARGO_TRACKING.Update
                End If
            End If
            SSDBGridmanifest.MoveNext
         Next
    End If
      
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        lblStatus.Caption = "Save Successful."
        SaveSuccess = True
    Else
        OraSession.Rollback
        lblStatus.Caption = "Save Failed."
        MsgBox "Error occured while saving.", vbExclamation, "Save"
    End If
    
End Function
Private Sub setQtyInHouse()

 Dim count As Integer
 Dim intNoOFRec As Integer
 intNoOFRec = 0
   
     SSDBGridmanifest.MoveFirst
     For count = 0 To SSDBGridmanifest.Rows - 1
         gsSqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE LR_NUM = " & txtLRNum.Text
         gsSqlStmt = gsSqlStmt & " AND RECIPIENT_ID = " & txtRecipientId.Text
         gsSqlStmt = gsSqlStmt & " AND COMMODITY_CODE = " & txtCommodityCode.Text
         gsSqlStmt = gsSqlStmt & " AND CARGO_BOL = '" & SSDBGridmanifest.Columns(0).Value & "'"
         gsSqlStmt = gsSqlStmt & " AND CARGO_MARK = '" & SSDBGridmanifest.Columns(1).Value & "'"
         Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
     
     If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RecordCount > 0 Then
        gLotNum = Trim$(dsCARGO_MANIFEST.Fields("CONTAINER_NUM").Value)
        gsSqlStmt = "SELECT sum(QTY_CHANGE) as SUM_CHANGE FROM CARGO_ACTIVITY WHERE LOT_NUM = '" & gLotNum & "'"
        Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
        
        If OraDatabase.LastServerErr = 0 And dsCARGO_ACTIVITY.RecordCount > 0 Then
               gQtyChng = 0
            If Not IsNull(dsCARGO_ACTIVITY.Fields("SUM_CHANGE").Value) Then
                gQtyChng = Val(Trim$(dsCARGO_ACTIVITY.Fields("SUM_CHANGE").Value))
            Else
                gQtyChng = 0
            End If
     
            If Val(Trim$(SSDBGridmanifest.Columns(2).Text)) < gQtyChng Then
                MsgBox "Quantity Received should not be less than quantity shipped out " & gQtyChng, vbInformation, "Quantity Received"
                Exit Sub
            Else
                gQtyInHus = Val(Trim$(SSDBGridmanifest.Columns(2).Text)) - gQtyChng
                'Exit Sub
            End If
         Else
            Exit Sub
        End If
      Else
        Exit Sub
     End If
     SSDBGridmanifest.MoveNext
 Next
 
End Sub
Private Function SetIndex(ByVal lclCombo As ComboBox) As Integer

Dim i As Integer
For i = 0 To lclCombo.ListCount - 1
    If UCase(lclCombo.Text) = UCase(Mid(lclCombo.List(i), 1, Len(lclCombo.Text))) Then
        SetIndex = i
        Exit Function
    End If
Next i

End Function
Private Sub display_Combo()

 SSDBGridmanifest.Columns(3).DropDownHwnd = SSDBDropUnit.hWnd
 SSDBGridmanifest.Columns(5).DropDownHwnd = SSDBDropUnit.hWnd
 SSDBGridmanifest.Columns(7).DropDownHwnd = SSDBDropUnit.hWnd
 SSDBGridmanifest.Columns(8).DropDownHwnd = SSDBDropStatus.hWnd
 SSDBGridmanifest.Columns(9).DropDownHwnd = SSDBDroploc.hWnd
 'SSDBDropDown1.Width = SSDBGrid1.Columns(1).Width
 'SSDBGrid1.Columns(1).DropDownHwnd = Combo1.hWnd
 
End Sub
Public Sub ClearScreen()

    txtVesselName.Text = ""
    txtRecipientId.Text = ""
    txtCustomerName.Text = ""
    txtCommodityCode.Text = ""
    optImpex(0).Value = True
    txtLRNum.SetFocus
    
End Sub


Private Function FindFreeTime(commodity As String, cus_id As String, lr_num As String) As Integer

On Error GoTo Err_Handler

    Dim rs As Object
    Dim strSql As String
    Dim retVal As Integer



        '' Prepare sql statement- 1st check
        strSql = "select f.FREE_DAYS" & _
                    " from free_time f" & _
                    " where f.COMMODITY_CODE =" & commodity & _
                    " and f.CUSTOMER_ID=" & cus_id & _
                    " and f.LR_NUM=" & lr_num
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)

        If rs.RecordCount = 1 Then
            retVal = Int(Val(rs.Fields(0).Value))
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If

        '' Prepare sql statement- 2nd check
        strSql = "select f.FREE_DAYS" & _
            " from free_time f" & _
            " where f.COMMODITY_CODE =" & commodity & _
            " and f.CUSTOMER_ID=" & cus_id & _
            " and f.LR_NUM IS NULL"
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        If rs.RecordCount = 1 Then
            retVal = Int(Val(rs.Fields(0).Value))
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If


        '' Prepare sql statement- 3rd check
        strSql = "select f.FREE_DAYS" & _
                    " from free_time f" & _
                    " where f.COMMODITY_CODE =" & commodity & _
                    " and f.CUSTOMER_ID IS NULL" & _
                    " and f.LR_NUM IS NULL"

        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        If rs.RecordCount = 1 Then
            retVal = Int(Val(rs.Fields(0).Value))
            Set rs = Nothing
            FindFreeTime = retVal
            Exit Function
        End If


        '' Set variables to Nothing-Nothing found after 3 checks
        Set rs = Nothing
        retVal = -1
        FindFreeTime = retVal
        Exit Function


Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        Me.Name & "." & "FindFreeTime"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        retVal = -1
        FindFreeTime = retVal
    End If

End Function




