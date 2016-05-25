VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmOrigManifest 
   AutoRedraw      =   -1  'True
   Caption         =   "ORIGINAL MANIFEST"
   ClientHeight    =   8760
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
   ScaleHeight     =   8760
   ScaleWidth      =   14940
   StartUpPosition =   3  'Windows Default
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
      Height          =   375
      Left            =   13020
      TabIndex        =   14
      Top             =   885
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
      Height          =   375
      Left            =   13020
      TabIndex        =   13
      Top             =   2160
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
      Left            =   13020
      TabIndex        =   12
      Top             =   1515
      Width           =   1215
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
      Left            =   13020
      TabIndex        =   11
      Top             =   240
      Width           =   1215
   End
   Begin VB.Frame Frame1 
      Height          =   2055
      Left            =   480
      TabIndex        =   0
      Top             =   240
      Width           =   11775
      Begin VB.CheckBox chkComm 
         Caption         =   "ALL COMMODITY"
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
         Left            =   8520
         TabIndex        =   7
         Top             =   1440
         Width           =   2175
      End
      Begin VB.CheckBox chkCust 
         Caption         =   "ALL CUSTOMER"
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
         Left            =   8520
         TabIndex        =   15
         Top             =   960
         Width           =   2055
      End
      Begin VB.TextBox txtCommodity 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   3120
         TabIndex        =   10
         Top             =   1395
         Width           =   4815
      End
      Begin VB.TextBox txtCustomer 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   3120
         TabIndex        =   9
         Top             =   922
         Width           =   4815
      End
      Begin VB.TextBox txtVessel 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   3120
         TabIndex        =   8
         Top             =   480
         Width           =   4815
      End
      Begin VB.TextBox txtCommCode 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   2040
         TabIndex        =   3
         Top             =   1395
         Width           =   855
      End
      Begin VB.TextBox txtCustId 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   2040
         TabIndex        =   2
         Top             =   922
         Width           =   855
      End
      Begin VB.TextBox txtLrNum 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   2040
         TabIndex        =   1
         Top             =   480
         Width           =   855
      End
      Begin VB.Label Label3 
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
         Left            =   465
         TabIndex        =   6
         Top             =   1455
         Width           =   1350
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
         Left            =   615
         TabIndex        =   5
         Top             =   975
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
         Left            =   960
         TabIndex        =   4
         Top             =   533
         Width           =   855
      End
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   5655
      Left            =   120
      TabIndex        =   16
      Top             =   2880
      Width           =   14775
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   14
      RowHeight       =   503
      Columns.Count   =   14
      Columns(0).Width=   1111
      Columns(0).Caption=   "CUST"
      Columns(0).Name =   "CUST"
      Columns(0).Alignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1270
      Columns(1).Caption=   "COMM"
      Columns(1).Name =   "COMM"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1931
      Columns(2).Caption=   "BOL"
      Columns(2).Name =   "BOL"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   5715
      Columns(3).Caption=   "MARK"
      Columns(3).Name =   "MARK"
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1164
      Columns(4).Caption=   "QTY1"
      Columns(4).Name =   "QTY1"
      Columns(4).Alignment=   1
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1217
      Columns(5).Caption=   "UNT1"
      Columns(5).Name =   "UNT1"
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1164
      Columns(6).Caption=   "QTY2"
      Columns(6).Name =   "QTY2"
      Columns(6).Alignment=   1
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1217
      Columns(7).Caption=   "UNT2"
      Columns(7).Name =   "UNT2"
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1508
      Columns(8).Caption=   "WT"
      Columns(8).Name =   "WT"
      Columns(8).Alignment=   1
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1508
      Columns(9).Caption=   "WT UNT"
      Columns(9).Name =   "WT UNT"
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   1640
      Columns(10).Caption=   "STATUS"
      Columns(10).Name=   "STATUS"
      Columns(10).CaptionAlignment=   2
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   2064
      Columns(11).Caption=   "LOC"
      Columns(11).Name=   "LOC"
      Columns(11).CaptionAlignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   1720
      Columns(12).Caption=   "DT RCVD"
      Columns(12).Name=   "DT RCVD"
      Columns(12).CaptionAlignment=   2
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   1826
      Columns(13).Caption=   "QTY INH"
      Columns(13).Name=   "QTY INH"
      Columns(13).Alignment=   1
      Columns(13).CaptionAlignment=   2
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      _ExtentX        =   26061
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
End
Attribute VB_Name = "frmOrigManifest"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim iRec As Integer

Private Sub chkComm_Click()
    If chkComm.Value = vbChecked Then
        txtCommCode = ""
        txtCommodity = ""
        txtCommCode.BackColor = &H80000004
        txtCommodity.BackColor = &H80000004
        txtCommCode.Enabled = False
        txtCommodity.Enabled = False
    Else
        txtCommCode.BackColor = &H80000005
        txtCommodity.BackColor = &H80000005
        txtCommCode.Enabled = True
        txtCommodity.Enabled = True
    End If
End Sub

Private Sub chkCust_Click()
    If chkCust.Value = vbChecked Then
        txtCustId = ""
        txtCustomer = ""
        txtCustId.BackColor = &H80000004
        txtCustomer.BackColor = &H80000004
        txtCustId.Enabled = False
        txtCustomer.Enabled = False
    Else
        txtCustId.BackColor = &H80000005
        txtCustomer.BackColor = &H80000005
        txtCustId.Enabled = True
        txtCustomer.Enabled = True
    End If
        
End Sub

Private Sub cmdClear_Click()
    grdData.RemoveAll
    txtLrNum = ""
    txtVessel = ""
    txtCommCode = ""
    txtCommodity = ""
    txtCustId = ""
    txtCustomer = ""
    chkCust.Value = vbUnchecked
    chkComm.Value = vbUnchecked
    txtCustId.BackColor = &H80000005
    txtCustomer.BackColor = &H80000005
    txtCustId.Enabled = True
    txtCustomer.Enabled = True
    txtCommCode.BackColor = &H80000005
    txtCommodity.BackColor = &H80000005
    txtCommCode.Enabled = True
    txtCommodity.Enabled = True
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub

Private Sub cmdPrint_Click()
    
    
    If grdData.Rows = 0 Then Exit Sub
    
    grdData.MoveFirst
    
    Printer.Orientation = 2
    
    Printer.Print Tab(3); "Printed On : " & Format(Now, "MM/DD/YYYY")
    Printer.Print ""
    Printer.FontSize = 12
    Printer.FontBold = True
    Printer.Print Tab(55); "ORIGINAL MANIFEST"
     
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 8
    Printer.Print Tab(5); "Vessel"; Tab(17); " : "; Tab(20); txtVessel
    Printer.Print
    If chkCust.Value = vbChecked Then
       Printer.Print Tab(5); "Customer"; Tab(17); " : "; Tab(20); "ALL"
    Else
       Printer.Print Tab(5); "Customer"; Tab(17); " : "; Tab(20); txtCustomer
    End If
    Printer.Print
    If chkComm.Value = vbChecked Then
       Printer.Print Tab(5); "Commodity"; Tab(17); " : "; Tab(20); "ALL"
    Else
       Printer.Print Tab(5); "Commodity"; Tab(17); " : "; Tab(20); txtCommodity
    End If
    
    Printer.FontBold = False
    Printer.Print ""
    Printer.Print Tab(3); "---------------------------------------------------------------------------------------------" _
                        ; "---------------------------------------------------------------------------------------------" _
                        ; "---------------------------------------------------------------------------------------------"
    
    If chkCust.Value = vbChecked And chkComm.Value = vbChecked Then
        
        Printer.Print Tab(5); "CUST"; Tab(15); "COMM"; Tab(25); "BOL"; Tab(45); "MARK"; Tab(85); "QTY1"; _
                      Tab(95); "UNT1"; Tab(105); "QTY2"; Tab(115); "UNT2"; Tab(125); "WT"; Tab(140); "WT UNT"; _
                      Tab(155); "STATUS"; Tab(170); "LOC"; Tab(185); "DT RCVD"; Tab(200); "QTY INH"
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------" _
                            ; "---------------------------------------------------------------------------------------------" _
                            ; "---------------------------------------------------------------------------------------------"
                        
        For iRec = 0 To grdData.Rows - 1
    
            Printer.Print Tab(5); Trim(grdData.Columns(0).Value); Tab(15); Trim(grdData.Columns(1).Value); _
                          Tab(25); Trim(grdData.Columns(2).Value); Tab(45); Trim(grdData.Columns(3).Value); _
                          Tab(85); Trim(grdData.Columns(4).Value); Tab(95); Trim(grdData.Columns(5).Value); _
                          Tab(105); Trim(grdData.Columns(6).Value); Tab(115); Trim(grdData.Columns(7).Value); _
                          Tab(125); Trim(grdData.Columns(8).Value); Tab(140); Trim(grdData.Columns(9).Value); _
                          Tab(155); Trim(grdData.Columns(10).Value); Tab(170); Trim(grdData.Columns(11).Value); _
                          Tab(185); Trim(grdData.Columns(12).Value); Tab(200); Trim(grdData.Columns(13).Value)
                          
            
            Printer.Print ""
            
            grdData.MoveNext
        Next iRec
    
    ElseIf chkCust.Value = vbChecked And chkComm.Value = vbUnchecked Then
        
        Printer.Print Tab(5); "CUST"; Tab(15); "BOL"; Tab(35); "MARK"; Tab(75); "QTY1"; Tab(85); "UNT1"; _
                      Tab(95); "QTY2"; Tab(105); "UNT2"; Tab(115); "WT"; Tab(130); "WT UNT"; Tab(145); "STATUS"; _
                      Tab(160); "LOC"; Tab(175); "DT RCVD"; Tab(190); "QTY INH";
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------" _
                            ; "---------------------------------------------------------------------------------------------" _
                            ; "---------------------------------------------------------------------------------------------"
            For iRec = 0 To grdData.Rows - 1
    
                Printer.Print Tab(5); Trim(grdData.Columns(0).Value); Tab(15); Trim(grdData.Columns(2).Value); _
                              Tab(35); Trim(grdData.Columns(3).Value); Tab(75); Trim(grdData.Columns(4).Value); _
                              Tab(85); Trim(grdData.Columns(5).Value); Tab(95); Trim(grdData.Columns(6).Value); _
                              Tab(105); Trim(grdData.Columns(7).Value); Tab(115); Trim(grdData.Columns(8).Value); _
                              Tab(130); Trim(grdData.Columns(9).Value); Tab(145); Trim(grdData.Columns(10).Value); _
                              Tab(160); Trim(grdData.Columns(11).Value); Tab(175); Trim(grdData.Columns(12).Value); _
                              Tab(190); Trim(grdData.Columns(13).Value)
                
                Printer.Print ""
                
                grdData.MoveNext
           Next iRec
    
    ElseIf chkCust.Value = vbUnchecked And chkComm.Value = vbChecked Then
        
        Printer.Print Tab(5); "COMM"; Tab(15); "BOL"; Tab(35); "MARK"; Tab(75); "QTY1"; Tab(85); "UNT1"; _
                      Tab(95); "QTY2"; Tab(105); "UNT2"; Tab(115); "WT"; Tab(130); "WT UNT"; Tab(145); "STATUS"; _
                      Tab(160); "LOC"; Tab(175); "DT RCVD"; Tab(190); "QTY INH"
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------" _
                            ; "---------------------------------------------------------------------------------------------" _
                            ; "---------------------------------------------------------------------------------------------"
            For iRec = 0 To grdData.Rows - 1
    
        Printer.Print Tab(5); Trim(grdData.Columns(1).Value); Tab(15); Trim(grdData.Columns(2).Value); _
                              Tab(35); Trim(grdData.Columns(3).Value); Tab(75); Trim(grdData.Columns(4).Value); _
                              Tab(85); Trim(grdData.Columns(5).Value); Tab(95); Trim(grdData.Columns(6).Value); _
                              Tab(105); Trim(grdData.Columns(7).Value); Tab(115); Trim(grdData.Columns(8).Value); _
                              Tab(130); Trim(grdData.Columns(9).Value); Tab(145); Trim(grdData.Columns(10).Value); _
                              Tab(160); Trim(grdData.Columns(11).Value); Tab(175); Trim(grdData.Columns(12).Value); _
                              Tab(190); Trim(grdData.Columns(13).Value)
        Printer.Print ""
        
        grdData.MoveNext
    Next iRec
    ElseIf chkCust.Value = vbUnchecked And chkComm.Value = vbUnchecked Then
        
        Printer.Print Tab(5); "BOL"; Tab(15); "MARK"; Tab(55); "QTY1"; Tab(65); "UNT1"; Tab(75); "QTY2"; _
                      Tab(85); "UNT2"; Tab(95); "WT"; Tab(110); "WT UNT"; Tab(125); "STATUS"; _
                      Tab(140); "LOC"; Tab(160); "DT RCVD"; Tab(180); "QTY INH"
        Printer.Print Tab(3); "---------------------------------------------------------------------------------------------" _
                            ; "---------------------------------------------------------------------------------------------" _
                            ; "---------------------------------------------------------------------------------------------"
    
            For iRec = 0 To grdData.Rows - 1
    
        Printer.Print Tab(5); Trim(grdData.Columns(2).Value); Tab(15); Trim(grdData.Columns(3).Value); _
                      Tab(55); Trim(grdData.Columns(4).Value); Tab(65); Trim(grdData.Columns(5).Value); _
                      Tab(75); Trim(grdData.Columns(6).Value); Tab(85); Trim(grdData.Columns(7).Value); _
                      Tab(95); Trim(grdData.Columns(8).Value); Tab(110); Trim(grdData.Columns(9).Value); _
                      Tab(125); Trim(grdData.Columns(10).Value); Tab(140); Trim(grdData.Columns(11).Value); _
                      Tab(160); Trim(grdData.Columns(12).Value); Tab(180); Trim(grdData.Columns(13).Value)
        
        Printer.Print ""
        
        grdData.MoveNext
    Next iRec
    End If
    
    Printer.Print Tab(3); "---------------------------------------------------------------------------------------------" _
                        ; "---------------------------------------------------------------------------------------------" _
                        ; "---------------------------------------------------------------------------------------------"
    
    Printer.EndDoc
    
    grdData.MoveFirst
    
End Sub

Private Sub cmdRet_Click()
    Dim DtRcvd As String
    Dim QtyInH As String
    
    grdData.RemoveAll
    
    If chkCust.Value = vbUnchecked And chkComm.Value = vbUnchecked Then
    
        gsSqlStmt = " SELECT * FROM CARGO_MANIFEST_ORIGINAL WHERE LR_NUM='" & Trim(txtLrNum) & "' " _
                  & " AND RECIPIENT_ID='" & Trim(txtCustId) & "' AND COMMODITY_CODE='" & Trim(txtCommCode) & "'" _
                  & " ORDER BY CARGO_BOL"
                  
        grdData.Columns(0).Visible = False
        grdData.Columns(1).Visible = False
        
    ElseIf chkCust.Value = vbUnchecked And chkComm.Value = vbChecked Then
        
        grdData.Columns(0).Visible = False
        grdData.Columns(1).Visible = True
        
        gsSqlStmt = " SELECT * FROM CARGO_MANIFEST_ORIGINAL WHERE LR_NUM='" & Trim(txtLrNum) & "' " _
                  & " AND RECIPIENT_ID='" & Trim(txtCustId) & "' ORDER BY COMMODITY_CODE,CARGO_BOL"
                  
    
    ElseIf chkCust.Value = vbChecked And chkComm.Value = vbUnchecked Then
        
        grdData.Columns(0).Visible = True
        grdData.Columns(1).Visible = False
        
        gsSqlStmt = " SELECT * FROM CARGO_MANIFEST_ORIGINAL WHERE LR_NUM='" & Trim(txtLrNum) & "' " _
                  & " AND COMMODITY_CODE='" & Trim(txtCommCode) & "' ORDER BY RECIPIENT_ID,CARGO_BOL"
                  
    
    ElseIf chkCust.Value = vbChecked And chkComm.Value = vbChecked Then
        
        grdData.Columns(0).Visible = True
        grdData.Columns(1).Visible = True
        
        gsSqlStmt = " SELECT * FROM CARGO_MANIFEST_ORIGINAL WHERE LR_NUM='" & Trim(txtLrNum) & "' " _
                  & " ORDER BY RECIPIENT_ID,COMMODITY_CODE,CARGO_BOL"
        
    End If
    
    Set dsCARGO_MANIFEST_ORIGINAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST_ORIGINAL.recordcount > 0 Then
        With dsCARGO_MANIFEST_ORIGINAL
            For iRec = 1 To .recordcount
                gsSqlStmt = "SELECT * FROM CARGO_TRACKING_ORIGINAL WHERE LOT_NUM='" & .FIELDS("CONTAINER_NUM").Value & "'"
                Set dsCARGO_TRACKING_ORIGINAL = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING_ORIGINAL.recordcount > 0 Then
                    DtRcvd = dsCARGO_TRACKING_ORIGINAL.FIELDS("DATE_RECEIVED").Value
                    QtyInH = dsCARGO_TRACKING_ORIGINAL.FIELDS("QTY_IN_HOUSE").Value
                Else
                    gsSqlStmt = "SELECT * FROM CARGO_TRACKING WHERE LOT_NUM='" & .FIELDS("CONTAINER_NUM") & "'"
                    Set dsCARGO_TRACKING = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
                    If OraDatabase.LastServerErr = 0 And dsCARGO_TRACKING.recordcount > 0 Then
                        DtRcvd = dsCARGO_TRACKING.FIELDS("DATE_RECEIVED").Value
                        QtyInH = dsCARGO_TRACKING.FIELDS("QTY_IN_HOUSE").Value
                    End If
                End If
                    
                grdData.AddItem .FIELDS("RECIPIENT_ID").Value + Chr(9) + _
                                .FIELDS("COMMODITY_CODE").Value + Chr(9) + _
                                .FIELDS("CARGO_BOL").Value + Chr(9) + _
                                .FIELDS("CARGO_MARK").Value + Chr(9) + _
                                .FIELDS("QTY_EXPECTED").Value + Chr(9) + _
                                .FIELDS("QTY1_UNIT").Value + Chr(9) + _
                                .FIELDS("QTY2_EXPECTED").Value + Chr(9) + _
                                .FIELDS("QTY2_UNIT").Value + Chr(9) + _
                                .FIELDS("CARGO_WEIGHT").Value + Chr(9) + _
                                .FIELDS("CARGO_WEIGHT_UNIT").Value + Chr(9) + _
                                .FIELDS("MANIFEST_STATUS").Value + Chr(9) + _
                                .FIELDS("CARGO_LOCATION").Value + Chr(9) + _
                                DtRcvd + Chr(9) + QtyInH
                                   
                .MoveNext
            Next iRec
        End With
    End If
            
       
End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
End Sub

Private Sub txtCommCode_Validate(Cancel As Boolean)
     If Trim$(txtCommCode) = "" Then Exit Sub
     
     If Not IsNumeric(txtCommCode) Then
        MsgBox "Expecting Numeric Values.", vbInformation, "COMMODITY CODE"
        txtCommCode = ""
        Cancel = True
        Exit Sub
    End If
    
    gsSqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = " & txtCommCode.Text
    Set dsCOMMODITY_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCOMMODITY_PROFILE.recordcount > 0 Then
        txtCommodity.Text = dsCOMMODITY_PROFILE.FIELDS("COMMODITY_NAME").Value
    Else
        MsgBox "Invalid COMMODITY CODE.", vbExclamation, "COMMODITY"
        txtCommCode = ""
        Cancel = True
        Exit Sub
    End If
    
End Sub

Private Sub txtCustId_Validate(Cancel As Boolean)
    If Trim$(txtCustId) = "" Then Exit Sub
     
     If Not IsNumeric(txtCustId) Then
        MsgBox "Expecting Numeric Values.", vbInformation, "CUSTOMER ID"
        txtCustId = ""
        Cancel = True
        Exit Sub
    End If
    
    gsSqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " & txtCustId.Text
    Set dsCUSTOMER_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCUSTOMER_PROFILE.recordcount > 0 Then
        txtCustomer.Text = dsCUSTOMER_PROFILE.FIELDS("CUSTOMER_NAME").Value
    Else
        MsgBox "Invalid CUSTOMER ID.", vbExclamation, "CUSTOMER"
        txtCustId = ""
        Cancel = True
        Exit Sub
    End If
    
End Sub

Private Sub txtLrNum_Validate(Cancel As Boolean)
    If Trim$(txtLrNum) = "" Then Exit Sub
     
     If Not IsNumeric(txtLrNum) Then
        MsgBox "Expecting Numeric Values.", vbInformation, "LR NUMBER"
        txtLrNum = ""
        Cancel = True
        Exit Sub
    End If
    
    
    gsSqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM = " & txtLrNum.Text
    Set dsVESSEL_PROFILE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVESSEL_PROFILE.recordcount > 0 Then
        txtVessel = dsVESSEL_PROFILE.FIELDS("VESSEL_NAME").Value
    Else
        MsgBox "Invalid LrNum .", vbExclamation, "Vessel"
        txtLrNum = ""
        Cancel = True
        Exit Sub
    End If
    
End Sub
