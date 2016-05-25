VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Begin VB.Form frmRFServ 
   AutoRedraw      =   -1  'True
   Caption         =   "RF - BNI MISC BILLS - FRUIT ONLY"
   ClientHeight    =   10770
   ClientLeft      =   1740
   ClientTop       =   2145
   ClientWidth     =   18255
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
   ScaleHeight     =   10770
   ScaleWidth      =   18255
   Visible         =   0   'False
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   7575
      Left            =   720
      TabIndex        =   3
      Top             =   2040
      Width           =   16695
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   11
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowColumnSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   8388608
      RowHeight       =   450
      ExtraHeight     =   635
      Columns.Count   =   11
      Columns(0).Width=   1746
      Columns(0).Caption=   "DATE"
      Columns(0).Name =   "DATE"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   4180
      Columns(1).Caption=   "OWNER"
      Columns(1).Name =   "OWNER"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   2143
      Columns(2).Caption=   "SHIP #"
      Columns(2).Name =   "SHIP #"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   3069
      Columns(3).Caption=   "ORDER #"
      Columns(3).Name =   "ORDER #"
      Columns(3).CaptionAlignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   3016
      Columns(4).Caption=   "COMMODITY"
      Columns(4).Name =   "COMMODITY"
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1244
      Columns(5).Caption=   "PLTS"
      Columns(5).Name =   "PLTS"
      Columns(5).Alignment=   1
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1588
      Columns(6).Caption=   "CASES"
      Columns(6).Name =   "CASES"
      Columns(6).Alignment=   1
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   2566
      Columns(7).Caption=   "AVG. WT"
      Columns(7).Name =   "AVG. WT"
      Columns(7).Alignment=   1
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   2328
      Columns(8).Caption=   "WEIGHT"
      Columns(8).Name =   "WEIGHT"
      Columns(8).Alignment=   1
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   2170
      Columns(9).Caption=   "IN/OUT"
      Columns(9).Name =   "IN/OUT"
      Columns(9).Alignment=   1
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   3200
      Columns(10).Caption=   "BILLED COMM"
      Columns(10).Name=   "BILLED COMM"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   2
      Columns(10).FieldLen=   256
      _ExtentX        =   29448
      _ExtentY        =   13361
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
   Begin SSDataWidgets_B.SSDBGrid grdTrans 
      Height          =   7455
      Left            =   360
      TabIndex        =   8
      Top             =   1800
      Width           =   16455
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   9
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowGroupSizing=   0   'False
      AllowColumnSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   8388608
      RowHeight       =   503
      Columns.Count   =   9
      Columns(0).Width=   2037
      Columns(0).Caption=   "SHIP #"
      Columns(0).Name =   "SHIP #"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   3200
      Columns(1).Caption=   "ORIGINAL OWNER"
      Columns(1).Name =   "ORIGINAL OWNER"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   3200
      Columns(2).Caption=   "NEW OWNER"
      Columns(2).Name =   "NEW OWNER"
      Columns(2).Alignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   3200
      Columns(3).Caption=   "DATE"
      Columns(3).Name =   "DATE"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2646
      Columns(4).Caption=   "ORDER #"
      Columns(4).Name =   "ORDER #"
      Columns(4).Alignment=   1
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   3200
      Columns(5).Caption=   "PLTS"
      Columns(5).Name =   "PLTS"
      Columns(5).Alignment=   1
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   3200
      Columns(6).Caption=   "COMMODITY"
      Columns(6).Name =   "COMMODITY"
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   3200
      Columns(7).Caption=   "AMOUNT"
      Columns(7).Name =   "AMOUNT"
      Columns(7).Alignment=   1
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   3200
      Columns(8).Caption=   "BILLED COMM"
      Columns(8).Name =   "BILLED COMM"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      _ExtentX        =   29025
      _ExtentY        =   13150
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
   Begin VB.CheckBox Check1 
      Caption         =   "View  Only Saved and  Unsaved Record"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   120
      TabIndex        =   16
      Top             =   120
      Width           =   4095
   End
   Begin VB.CheckBox chkEnzaFruit 
      Caption         =   "Enza Fruit"
      Height          =   225
      Left            =   9960
      TabIndex        =   15
      Top             =   360
      Visible         =   0   'False
      Width           =   1095
   End
   Begin VB.TextBox txtCommodity 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   11160
      TabIndex        =   14
      Top             =   1320
      Visible         =   0   'False
      Width           =   1575
   End
   Begin VB.TextBox txtService 
      Appearance      =   0  'Flat
      Height          =   330
      Left            =   5400
      TabIndex        =   12
      Top             =   1320
      Width           =   1095
   End
   Begin VB.TextBox txtDesc 
      Appearance      =   0  'Flat
      Height          =   375
      Left            =   5400
      TabIndex        =   9
      Top             =   840
      Width           =   7320
   End
   Begin VB.CommandButton cmdExit 
      Cancel          =   -1  'True
      Caption         =   "E&xit"
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
      Left            =   9705
      TabIndex        =   7
      Top             =   9720
      Width           =   1215
   End
   Begin VB.CommandButton cndRefresh 
      Caption         =   "&Refresh"
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
      Left            =   7905
      TabIndex        =   6
      Top             =   9720
      Width           =   1215
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
      Left            =   6120
      TabIndex        =   5
      Top             =   9720
      Width           =   1215
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
      Left            =   4305
      TabIndex        =   4
      Top             =   9720
      Width           =   1215
   End
   Begin VB.ComboBox cboService 
      Height          =   345
      ItemData        =   "frmRFServ.frx":0000
      Left            =   6825
      List            =   "frmRFServ.frx":0002
      TabIndex        =   2
      Top             =   255
      Width           =   2895
   End
   Begin VB.CommandButton cmdRet 
      Caption         =   "Re&trieve"
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
      Left            =   11520
      TabIndex        =   1
      Top             =   240
      Width           =   1215
   End
   Begin MSComCtl2.DTPicker dtpDate 
      Height          =   375
      Left            =   5400
      TabIndex        =   0
      Top             =   240
      Width           =   1215
      _ExtentX        =   2143
      _ExtentY        =   661
      _Version        =   393216
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      CustomFormat    =   "MM/dd/yyyy"
      Format          =   16580611
      CurrentDate     =   36951
   End
   Begin VB.Label lblSaveNotify 
      Alignment       =   2  'Center
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H000000FF&
      Height          =   975
      Left            =   12960
      TabIndex        =   17
      Top             =   240
      Width           =   3615
   End
   Begin VB.Label Label3 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Commodity  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   10080
      TabIndex        =   13
      Top             =   1380
      Visible         =   0   'False
      Width           =   1050
   End
   Begin VB.Label Label2 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   " Service  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   4440
      TabIndex        =   11
      Top             =   1380
      Width           =   765
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Description :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   225
      Left            =   4200
      TabIndex        =   10
      Top             =   840
      Width           =   1020
   End
End
Attribute VB_Name = "frmRFServ"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
        ' EDIT ADAM WALTER, April 2009.
        ' due to... last minute contract negotiations, Pandol products (inbound truckloading only) in A2 are NOT billed the same general rate.
        ' I am "hard coding" them out of this program and writing a separate PHP script to deal with them.
        ' with any luck, the whole concept of RF-BNI misc bills will go away in the future, repalced by a contract-driven billing system...
        

Dim Sqlstmt As String
Dim dsAssetProfile As Object
Dim dsLOCATION_ID As Object
Dim dsRATEINFO As Object
Dim gsSqlStmt As String
Dim gs1SqlStmt As String
Dim iRec As Integer
Dim iPos As Integer
Dim iTemp As Double
Dim sUnit As String
Dim strDescripOutput As String
Function GetBniComm(Comm As Integer, Cust As Integer, RecType As String) As String
    ' this was a first try at "pseudo-dole" coding.  it has since been determined that customer 9722 is going to be cron-handled;
    ' however, sicne this function will cause no harm to anything, I won't be modifying it, instead adjusting the "where"
    ' clause in the calling routine.
    If Cust = 453 Or Cust = 9722 Then
        If RecType = "T" Then
            GetBniComm = "5298"
        ElseIf RecType = "S" Then
            GetBniComm = "5103"
        End If
    Else
        GetBniComm = Comm
    End If

End Function

Function CommName(CommId As Integer) As String
    
    Sqlstmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE='" & CommId & "'"
    Set dsCOMMODITY = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
    If OraDatabaseRF.LastServerErr = 0 And dsCOMMODITY.RecordCount > 0 Then
        CommName = dsCOMMODITY.Fields("COMMODITY_NAME").Value
    Else
        CommName = CStr(CommId)
    End If

End Function
Function CustName(CustId As Integer) As String
    
    Sqlstmt = "SELECT SUBSTR(CUSTOMER_NAME, 0, 20) THE_CUST FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID='" & CustId & "'"
    Set dsCUSTOMER = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
    If OraDatabaseRF.LastServerErr = 0 And dsCUSTOMER.RecordCount > 0 Then
'        iPos = InStr(1, dsCUSTOMER.Fields("CUSTOMER_NAME").Value, " ")
'        If iPos <> 0 Then
'            CustName = Mid(dsCUSTOMER.Fields("CUSTOMER_NAME").Value, 1, iPos - 1)
'        Else
'            CustName = dsCUSTOMER.Fields("CUSTOMER_NAME").Value
'        End If
        CustName = dsCUSTOMER.Fields("THE_CUST").Value
    Else
        CustName = CStr(CustId)
    End If


End Function
Private Sub cboService_Click()

Dim ENZA As String

chkEnzaFruit.Value = 0
cmdSave.Enabled = False

    If cboService.ListIndex = 1 Then
    
        chkEnzaFruit.Visible = True
    
    End If
    
    If cboService.ListIndex = 3 Then
        lblSaveNotify.Caption = "Transfer charges now automatically generated."
        cmdRet.Enabled = False
'    ElseIf cboService.ListIndex = 1 Then
'        lblSaveNotify.Caption = "Truckloading charges now automatically generated."
'        cmdRet.Enabled = False
    Else
        lblSaveNotify.Caption = ""
        cmdRet.Enabled = True
    End If

End Sub
Private Sub cboService_LostFocus()

    If cboService.ListIndex = 0 Then chkEnzaFruit.Visible = False
    If cboService.ListIndex = 2 Then chkEnzaFruit.Visible = False
    If cboService.ListIndex = 3 Then chkEnzaFruit.Visible = False
    If cboService.ListIndex = 4 Then chkEnzaFruit.Visible = False
    
    If cboService.ListIndex = 0 Or cboService.ListIndex = 1 Or cboService.ListIndex = 2 Then
        txtCommodity.Enabled = False
    Else
        txtCommodity.Enabled = True
    End If
    

End Sub

Private Sub Check1_Click()
    grdData.RemoveAll
    grdData.Visible = False
    grdTrans.RemoveAll
    grdTrans.Visible = False
    txtService = ""
    txtCommodity = ""
    txtDesc = ""
    If Check1.Value = 1 Then
        cmdSave.Enabled = False
    Else
        cmdSave.Enabled = True
    End If
End Sub

Private Sub cmdExit_Click()
    
    Unload Me
    
End Sub
Private Sub cmdPrint_Click()

    If cboService.ListIndex = -1 Then Exit Sub
    grdData.MoveFirst
    grdTrans.MoveFirst
    Printer.Orientation = 2
    Printer.Print "Printed on : " & Format(Now, "mm/dd/yyyy")
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 12
    Printer.FontBold = True
    If cboService.ListIndex = 0 Or cboService.ListIndex = 1 Or cboService.ListIndex = 2 Or cboService.ListIndex = 4 Then
        
'        If cboService.ListIndex = 0 Then
'            Printer.Print Tab(60); "DOCK RETURNS"
'        ElseIf cboService.ListIndex = 1 Then
'            Printer.Print Tab(50); "CUSTOMER INBOUND LOADS"
'        ElseIf cboService.ListIndex = 2 Then
'            Printer.Print Tab(50); "FULL RETURN LOADS"
'        ElseIf cboService.ListIndex = 4 Then
'            Printer.Print Tab(50); "CUSTOMER TRANSFER"
'        End If
        Printer.Print Tab(50); UCase(cboService.Text)
        Printer.FontSize = 9
        Printer.FontBold = True
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(10); "DESCRIPTION"; Tab(30); ":"; Tab(35); txtDesc
        Printer.Print ""
        Printer.Print Tab(10); "SERVICE"; Tab(30); ":"; Tab(35); txtService
        Printer.Print ""
'        Printer.Print Tab(10); "COMMODITY"; Tab(30); ":"; Tab(35); txtCommodity
'        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------"
        Printer.Print Tab(7); "DATE"; Tab(20); "OWNER"; Tab(50); "SHIP#"; Tab(65); "ORDER #"; Tab(80); "COMMODITY"; Tab(100); "PLTS"; _
                      Tab(115); "CASES"; Tab(130); "AVG. WT."; Tab(145); "WEIGHT"; Tab(160); "IN/OUT"
        Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------"
        With grdData
            For iRec = 0 To .Rows - 1
                Printer.Print Tab(7); .Columns(0).Value; Tab(20); .Columns(1).Value; Tab(50); .Columns(2).Value; Tab(65); .Columns(3).Value; _
                              Tab(80); .Columns(4).Value; Tab(100); .Columns(5).Value; Tab(115); .Columns(6).Value; _
                              Tab(130); .Columns(7).Value; Tab(145); .Columns(8).Value; Tab(160); .Columns(9).Value
                .MoveNext
            Next iRec
        End With
        Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------"
    ElseIf cboService.ListIndex = 3 Then
        Printer.Print Tab(45); "TRANSFER OWNERSHIP CHARGES"
        Printer.FontSize = 9
        Printer.FontBold = True
        Printer.Print ""
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(10); "DESCRIPTION"; Tab(30); ":"; Tab(35); txtDesc
        Printer.Print ""
        Printer.Print Tab(10); "SERVICE"; Tab(30); ":"; Tab(35); txtService
        Printer.Print ""
'        Printer.Print Tab(10); "COMMODITY"; Tab(30); ":"; Tab(35); txtCommodity
'        Printer.Print ""
        Printer.Print ""
        Printer.Print
        Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------"
        Printer.Print Tab(7); "SHIP #"; Tab(20); "ORIGINAL"; Tab(50); "NEW"; Tab(65); "DATE"; Tab(80); "ORDER #"; Tab(100); "PLTS"; _
                      Tab(115); "COMMODITY"; Tab(130); "AMOUNT"
        Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------"
        With grdTrans
            For iRec = 0 To .Rows - 1
                Printer.Print Tab(7); .Columns(0).Value; Tab(20); .Columns(1).Value; Tab(50); .Columns(2).Value; Tab(65); .Columns(3).Value; _
                              Tab(80); .Columns(4).Value; Tab(100); .Columns(5).Value; Tab(115); .Columns(6).Value; _
                              Tab(130); .Columns(7).Value
                .MoveNext
            Next iRec
        End With
        Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------" _
                            ; "----------------------------------------------------------------------------------------------------------"
                            
        
    End If
    
    Printer.EndDoc
    grdData.MoveFirst
    grdTrans.MoveFirst
    
End Sub
Private Sub cmdRet_Click()
    ' recent changes have me excising customer 9722 from this routine.  It's handled via cron now.

    Dim iTransfer_To As Integer
    Dim BniComm As String
    
    
    
    grdData.RemoveAll
    grdData.Visible = False
    grdTrans.RemoveAll
    grdTrans.Visible = False
    txtService = ""
    txtCommodity = ""
    txtDesc = ""
    
    
    
'If chkEnzaFruit.Visible = True And chkEnzaFruit.Value = 1 Then
'    Call RetEnza
'    Exit Sub
'End If
    
'  Select Case cboService.ListIndex
    Sqlstmt = "SELECT RF_SERVICE_CODE FROM RFBNI_MISC_BILL_HEADINGS WHERE DESCRIPTION = '" & cboService.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
    iTemp = dsSHORT_TERM_DATA.Fields("RF_SERVICE_CODE").Value
'    Case 0
'      iTemp = 13
'
'    Case 1
'      iTemp = 8
'
'    Case 2
'      iTemp = 7
'
'    Case 3
'      iTemp = 11
      
'  End Select

    ' Adam Walter, June 2007
    ' I hate to do this, but... rather than re-write most of this routine to make a change removing
    ' Some hard coding, I am patching it this way:  by assigning the service code based on the dropdown box
    ' and doing this SQL, I can figure out some info that should not be hardcoded (codes and rates, specifically)
    Sqlstmt = "SELECT * FROM RFBNI_MISC_BILL_HEADINGS WHERE RF_SERVICE_CODE = '" & iTemp & "'"
    Set dsRATEINFO = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
    'txtCommodity = dsRATEINFO.Fields("BNI_COMMODITY_DEF").Value & ' this needs adjustment
    txtService = dsRATEINFO.Fields("BNI_SERVICE_DEF").Value
    'strDescripOutput = "@ $" & dsRATEINFO.Fields("RATE").Value & " / " & dsRATEINFO.Fields("PER_UNIT").Value & ' this needs adjustment
    
    
    If cboService.ListIndex = -1 Then Exit Sub ' They didnt choose one
    
    If iTemp = 13 Then    'DOCK RETURNS
        
        grdData.Visible = True
        If Check1.Value = 0 Then
            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='13' AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
                    & " AND BILLING_FLAG IS NULL"
                    'AND CUSTOMER_ID NOT IN ('439', '440')
        Else
            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='13' AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
                    & " " 'AND BILLING_FLAG IS NULL"
        End If
        Set dsDOCK_RETURN = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseBNI.LastServerErr = 0 And dsDOCK_RETURN.RecordCount > 0 Then
            
            'txtDesc = "DOCK RETURN PLT. TO INVENTRY @$6.49/PLT"
            ''txtDesc = "DOCK RETURN PLT. TO INVENTRY @$6.75/PLT"
            '' Per HD #2562
            txtDesc = "DOCK RETURN PLT. TO INVENTORY " '& strDescripOutput
            'txtCommodity = "5101"
            'txtService = "6221"
            With dsDOCK_RETURN
                For iRec = 1 To .RecordCount
                    grdData.AddItem Trim$(.Fields("SERVICE_DATE").Value) + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
                                    Trim$(.Fields("LR_NUM").Value) + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("SERVICE_QTY").Value + Chr(9) + _
                                    CStr(Abs(.Fields("CASES").Value)) + Chr(9) + .Fields("AVG_WT").Value + Chr(9) + _
                                    .Fields("WEIGHT").Value + Chr(9) + CStr(.Fields("AMOUNT").Value) + _
                                    Chr(9) + .Fields("COMMODITY_CODE").Value
                    .MoveNext
                Next iRec
            End With
        End If
        
 '                & " AND CT.RECEIVER_ID NOT IN ('439', '440') "
       Sqlstmt = " SELECT COUNT(*) PLTCOUNT ,SUM(QTY_CHANGE) CASES,TO_CHAR(DATE_OF_ACTIVITY ,'MM/DD/YYYY') ACT_DATE,CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM, " _
                & " NVL(BNI_COMM, COMMODITY_CODE) BNI_COMM, COMMODITY_CODE, RECEIVING_TYPE " _
                & " FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC WHERE " _
                & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY')+1 " _
                & " AND CUSTOMER_ID<>1 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION<>'VOID') " _
                & " AND SERVICE_CODE ='13' AND TO_MISCBILL IS NULL" _
                & " AND CT.ARRIVAL_NUM=CA.ARRIVAL_NUM " _
                & " AND CT.RECEIVER_ID=CA.CUSTOMER_ID " _
                & " AND CT.PALLET_ID=CA.PALLET_ID" _
                & " AND CT.COMMODITY_CODE=RTBC.RF_COMM" _
                & " AND CT.RECEIVER_ID NOT IN ('9722')" _
                & " AND CT.COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE CP, LU_RF_MISC_BILLS_COMM_DISPLAY LRMBCD WHERE CP.COMMODITY_TYPE = LRMBCD.COMMODITY_TYPE) " _
                & " GROUP BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY'),NVL(BNI_COMM, COMMODITY_CODE),COMMODITY_CODE, RECEIVING_TYPE" _
                & " ORDER BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,NVL(BNI_COMM, COMMODITY_CODE),COMMODITY_CODE"

        Set dsDOCK_RETURN = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseRF.LastServerErr = 0 And dsDOCK_RETURN.RecordCount > 0 Then
            
            'txtDesc = "DOCK RETURN PLT. TO INVENTRY @$6.49/PLT"
            ''txtDesc = "DOCK RETURN PLT. TO INVENTRY @$6.75/PLT"
            '' Per HD #2562
            txtDesc = "DOCK RETURN PLT. TO INVENTORY " '& strDescripOutput
            'txtCommodity = "5101"
            'txtService = "6221"
            With dsDOCK_RETURN
                For iRec = 1 To .RecordCount
                    BniComm = GetBniComm(.Fields("BNI_COMM").Value, .Fields("CUSTOMER_ID").Value, .Fields("RECEIVING_TYPE").Value)
                    
                    '' Note: DOCK RETURN RATE IS USED HERE
                    grdData.AddItem .Fields("ACT_DATE").Value + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
                                    .Fields("ARRIVAL_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("PLTCOUNT").Value + Chr(9) + _
                                    CStr(Abs(.Fields("CASES").Value)) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + BniComm
                    .MoveNext
                Next iRec
            End With
    
        Else
            If OraDatabaseRF.LastServerErr <> 0 Then
                MsgBox OraDatabaseRF.LastServerErrText, vbCritical
                OraDatabaseRF.LastServerErrReset
                Exit Sub
            End If
        End If
    
    ElseIf iTemp = 8 Then   ' INBOUND
        ' adding new logic for dole paper exclusion.  April 2009.  boy i hope i dont have to do this too many more times...
    
        grdData.Visible = True
        If Check1.Value = 0 Then
            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='8' AND SERVICE_DATE >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') + 1 " _
                    & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
                    & " AND COMMODITY_CODE != '1272'" _
                    & " AND DESCRIPTION NOT LIKE '%PANDOL A2 RATE%' AND BILLING_FLAG IS NULL"
        Else
            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='8' AND SERVICE_DATE >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') + 1 " _
                    & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
                    & " AND COMMODITY_CODE != '1272'" _
                    & " AND DESCRIPTION NOT LIKE '%PANDOL A2 RATE%'"   'AND BILLING_FLAG IS NULL
        End If
        Set dsINBOUNDS = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseBNI.LastServerErr = 0 And dsINBOUNDS.RecordCount > 0 Then
            
            'txtDesc = "INBOUND TRUCKLOADING IN/OUT @1.58/CWT"
            ''txtDesc = "INBOUND TRUCKLOADING IN/OUT @1.00/CWT"
            '' Per HD# 2562
            ''txtDesc = "INBOUND TRUCKLOADING IN/OUT @1.08/CWT"
            '' Per HD# 2826 rate changed to 1.71 from 1.08
            txtDesc = "INBOUND TRUCKLOADING IN/OUT " '& strDescripOutput
            'txtCommodity = "5101"
            'txtService = "6221"
            With dsINBOUNDS
                For iRec = 1 To .RecordCount
                    grdData.AddItem CStr(.Fields("SERVICE_DATE").Value) + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
                                    .Fields("LR_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("SERVICE_QTY").Value + Chr(9) + _
                                    CStr(Abs(.Fields("CASES").Value)) + Chr(9) + .Fields("AVG_WT").Value + Chr(9) + _
                                    .Fields("WEIGHT").Value + Chr(9) + CStr(.Fields("AMOUNT").Value) + _
                                    Chr(9) + .Fields("COMMODITY_CODE").Value
                    .MoveNext
                Next iRec
            End With
         End If
         
         'took out the checking of "arrival_num not like '%101%'", added "not customer 1512"  -- LFW, 4/25/05
         'HD 9584 - AW - 7/15/2014.  customer 1512 is now back into the Inbound Billing rotation.
         ' removing dolepaper from the data grab.  April 2009, Adam Walter.
'                & " AND (REMARK != 'DOLEPAPERSYSTEM' OR REMARK IS NULL)"
'                & " AND CT.RECEIVER_ID NOT IN ('439', '440') "
'                   AND CUSTOMER_ID not in (1, 1512)
         Sqlstmt = " SELECT COUNT(*) PLTCOUNT,SUM(QTY_CHANGE) CASES,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY') ACT_DATE,CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM, RECEIVING_TYPE," _
                & " NVL(BNI_COMM, COMMODITY_CODE) BNI_COMM, COMMODITY_CODE, SUM(WEIGHT) THE_WEIGHT FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC WHERE " _
                & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY')+1 " _
                & " AND CUSTOMER_ID not in (1) AND TO_MISCBILL IS NULL AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION<>'VOID') " _
                & " AND SERVICE_CODE ='8' AND CT.ARRIVAL_NUM=CA.ARRIVAL_NUM " _
                & " AND CT.RECEIVER_ID=CA.CUSTOMER_ID " _
                & " AND CT.PALLET_ID=CA.PALLET_ID" _
                & " AND CT.RECEIVER_ID NOT IN ('9722', '453')" _
                & " AND CT.COMMODITY_CODE=RTBC.RF_COMM" _
                & " AND CT.COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE CP, LU_RF_MISC_BILLS_COMM_DISPLAY LRMBCD WHERE CP.COMMODITY_TYPE = LRMBCD.COMMODITY_TYPE) " _
                & " AND CT.PALLET_ID NOT IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE RECEIVER_ID = '1608' AND WAREHOUSE_LOCATION = 'A2') " _
                & " GROUP BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY'),NVL(BNI_COMM, COMMODITY_CODE),COMMODITY_CODE, RECEIVING_TYPE" _
                & " ORDER BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,NVL(BNI_COMM, COMMODITY_CODE),COMMODITY_CODE"
                
               
        Set dsINBOUNDS = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseRF.LastServerErr = 0 And dsINBOUNDS.RecordCount > 0 Then
            ''txtDesc = "INBOUND TRUCKLOADING IN/OUT @1.00/CWT"
            'txtDesc = "INBOUND TRUCKLOADING IN/OUT @1.58/CWT"
            '' Per HD# 2562
            ''txtDesc = "INBOUND TRUCKLOADING IN/OUT @1.08/CWT"
            '' Per HD# 2826
            '' Rate changed to 1.71 from 1.08
            txtDesc = "INBOUND TRUCKLOADING IN/OUT " '& strDescripOutput
            'txtCommodity = "5101"
            'txtService = "6221"
            
            With dsINBOUNDS
                For iRec = 1 To .RecordCount
                    BniComm = GetBniComm(.Fields("BNI_COMM").Value, .Fields("CUSTOMER_ID").Value, .Fields("RECEIVING_TYPE").Value)
'                    If (Val("" & .Fields("COMMODITY_CODE").Value) = 1272) Then
'                        grdData.AddItem .Fields("ACT_DATE").Value + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
'                                        .Fields("ARRIVAL_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
'                                        CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("PLTCOUNT").Value + Chr(9) + _
'                                        .Fields("CASES").Value + Chr(9) + CStr(Round(Val("" & .Fields("THE_WEIGHT").Value) / Val("" & .Fields("PLTCOUNT").Value), 1)) + Chr(9) + _
'                                        CStr(.Fields("THE_WEIGHT").Value) + Chr(9) + CStr(Round(Val("" & .Fields("THE_WEIGHT").Value) * 9.35 / 2000, 2))
'                    Else
                    grdData.AddItem .Fields("ACT_DATE").Value + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
                                    .Fields("ARRIVAL_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("PLTCOUNT").Value + Chr(9) + _
                                    .Fields("CASES").Value + Chr(9) + Chr(9) + Chr(9) + Chr(9) + BniComm
'                    End If
                    .MoveNext
                Next iRec
            End With
    
        Else
            If OraDatabaseRF.LastServerErr <> 0 Then
                MsgBox OraDatabaseRF.LastServerErrText, vbCritical
                OraDatabaseRF.LastServerErrReset
                Exit Sub
            End If
        End If
    
    ElseIf iTemp = 7 Then   'RETURNS
        
        grdData.Visible = True
        If Check1.Value = 0 Then
            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='7' AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
                    & " AND BILLING_FLAG IS NULL"
        Else
            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='7' AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
                    & " " 'AND BILLING_FLAG IS NULL"
        End If
        Set dsRETURN = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseBNI.LastServerErr = 0 And dsRETURN.RecordCount > 0 Then
            
            ''txtDesc = "RETURNS TRUCKLOADING IN/OUT @1.50/CWT"
            '' Per HD #2562
            txtDesc = "RETURNS TRUCKLOADING IN/OUT " '& strDescripOutput
            'txtCommodity = "5101"
            'txtService = "6221"
            With dsRETURN
                For iRec = 1 To .RecordCount
                    grdData.AddItem Format(.Fields("SERVICE_DATE").Value, "mm/dd/yyyy") + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
                                    .Fields("LR_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("SERVICE_QTY").Value + Chr(9) + _
                                    CStr(Abs(.Fields("CASES").Value)) + Chr(9) + "" & .Fields("AVG_WT").Value + Chr(9) + _
                                    "" & .Fields("WEIGHT").Value + Chr(9) + "" & .Fields("AMOUNT").Value + _
                                    Chr(9) + .Fields("COMMODITY_CODE").Value
                    .MoveNext
                Next iRec
            End With
         End If
         
'                & " AND CT.RECEIVER_ID NOT IN ('439', '440') "
        Sqlstmt = " SELECT COUNT(*) PLTCOUNT,SUM(QTY_CHANGE) CASES,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY') ACT_DATE,CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM, " _
                & " NVL(BNI_COMM, COMMODITY_CODE) BNI_COMM, COMMODITY_CODE, RECEIVING_TYPE FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC WHERE " _
                & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY')+1 " _
                & " AND CUSTOMER_ID<>1 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION<>'VOID') " _
                & " AND SERVICE_CODE ='7' AND TO_MISCBILL IS NULL" _
                & " AND CT.ARRIVAL_NUM=CA.ARRIVAL_NUM " _
                & " AND CT.RECEIVER_ID=CA.CUSTOMER_ID " _
                & " AND CT.COMMODITY_CODE=RTBC.RF_COMM" _
                & " AND CT.COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE CP, LU_RF_MISC_BILLS_COMM_DISPLAY LRMBCD WHERE CP.COMMODITY_TYPE = LRMBCD.COMMODITY_TYPE) " _
                & " AND CT.RECEIVER_ID NOT IN ('9722')" _
                & " AND CT.PALLET_ID=CA.PALLET_ID" _
                & " GROUP BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY'),NVL(BNI_COMM, COMMODITY_CODE),COMMODITY_CODE,RECEIVING_TYPE" _
                & " ORDER BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,NVL(BNI_COMM, COMMODITY_CODE),COMMODITY_CODE"
                
        Set dsRETURN = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseRF.LastServerErr = 0 And dsRETURN.RecordCount > 0 Then
            ''txtDesc = "RETURNS TRUCKLOADING IN/OUT @1.50/CWT"
            '' Per HD #2562
            txtDesc = "RETURNS TRUCKLOADING IN/OUT " '& strDescripOutput
            'txtCommodity = "5101"
            'txtService = "6221"
            
            With dsRETURN
                For iRec = 1 To .RecordCount
                    BniComm = GetBniComm(.Fields("BNI_COMM").Value, .Fields("CUSTOMER_ID").Value, .Fields("RECEIVING_TYPE").Value)
                    grdData.AddItem .Fields("ACT_DATE").Value + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
                                    .Fields("ARRIVAL_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("PLTCOUNT").Value + Chr(9) + _
                                    CStr(Abs(.Fields("CASES").Value)) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + BniComm
                    .MoveNext
                Next iRec
            End With
        Else
            If OraDatabaseRF.LastServerErr <> 0 Then
                MsgBox OraDatabaseRF.LastServerErrText, vbCritical
                OraDatabaseRF.LastServerErrReset
                Exit Sub
            End If
        End If
    
    ElseIf iTemp = 11 Then   ' TRANSFER
    
        grdTrans.Visible = True
        If Check1.Value = 0 Then
            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='11' AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
                    & " AND BILLING_FLAG IS NULL"
        Else
            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='11' AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
                    & " "   'AND BILLING_FLAG IS NULL"
        End If
        Set dsTRANSFER = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseBNI.LastServerErr = 0 And dsTRANSFER.RecordCount > 0 Then
            
            ''txtDesc = "Charges@$25/Order/25 Pallets"
            '' Per HD# 2562
            txtDesc = "Transfer Charges " '& strDescripOutput
            'txtCommodity = "5101"
            'txtService = "6120"
            With dsTRANSFER
                For iRec = 1 To .RecordCount
                'transfer_to is actually Transfer_from and customer_id is the new customer
                    grdTrans.AddItem .Fields("LR_NUM").Value + Chr(9) + CustName(.Fields("TRANSFER_TO").Value) + Chr(9) + _
                                    CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + Format(.Fields("SERVICE_DATE").Value, "mm/dd/yyyy") + Chr(9) + _
                                    .Fields("ORDER_NUM").Value + Chr(9) + .Fields("SERVICE_QTY").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + Format(.Fields("AMOUNT").Value, "00.00")
                    .MoveNext
                Next iRec
            End With
         End If
         
'                & " AND ORDER_NUM<>CA.ARRIVAL_NUM" _
'                & " AND CT.RECEIVER_ID NOT IN ('439', '440') "
'                & " AND CP.COMMODITY_TYPE IN ('CHILEAN', 'PERUVIAN', 'BRAZILIAN', 'ARG FRUIT')"
        Sqlstmt = " SELECT COUNT(*) PLTCOUNT,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY') ACT_DATE,CUSTOMER_ID,DECODE(COUNT(DISTINCT CA.ARRIVAL_NUM), 1, MAX(CA.ARRIVAL_NUM), 0) THE_ARV,ORDER_NUM, RECEIVING_TYPE," _
                & " NVL(BNI_COMM, CP.COMMODITY_CODE) BNI_COMM, CP.COMMODITY_CODE FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, COMMODITY_PROFILE CP, RF_TO_BNI_COMM RTBC WHERE " _
                & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY')+1 " _
                & " AND CUSTOMER_ID<>1 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION<>'VOID') " _
                & " AND SERVICE_CODE ='11' AND TO_MISCBILL IS NULL" _
                & " AND CT.ARRIVAL_NUM=CA.ARRIVAL_NUM " _
                & " AND CT.COMMODITY_CODE=CP.COMMODITY_CODE " _
                & " AND CT.RECEIVER_ID=CA.CUSTOMER_ID " _
                & " AND CT.PALLET_ID=CA.PALLET_ID" _
                & " AND CT.COMMODITY_CODE=RTBC.RF_COMM" _
                & " AND CT.COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE CP, LU_RF_MISC_BILLS_COMM_DISPLAY LRMBCD WHERE CP.COMMODITY_TYPE = LRMBCD.COMMODITY_TYPE) " _
                & " AND CT.RECEIVER_ID NOT IN ('9722')" _
                & " AND CA.ACTIVITY_NUM != '1'" _
                & " GROUP BY CUSTOMER_ID,ORDER_NUM,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY'),NVL(BNI_COMM, CP.COMMODITY_CODE),CP.COMMODITY_CODE,RECEIVING_TYPE" _
                & " ORDER BY CUSTOMER_ID,ORDER_NUM,NVL(BNI_COMM, CP.COMMODITY_CODE),CP.COMMODITY_CODE"
            
        Set dsTRANSFER = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseRF.LastServerErr = 0 And dsTRANSFER.RecordCount > 0 Then
            
            ''txtDesc = "Charges@$25/Order/25 Pallets"
            '' Per HD# 2562
            txtDesc = "Transfer Charges " '& strDescripOutput
            'txtCommodity = "5101"
            'txtService = "6120"
            
            With dsTRANSFER
                For iRec = 1 To .RecordCount
                
                    Sqlstmt = " SELECT CUSTOMER_ID FROM CARGO_ACTIVITY WHERE ARRIVAL_NUM='" & .Fields("ORDER_NUM").Value & "'" _
                            & " AND DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                            & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY')+1 " _
                            & " AND CUSTOMER_ID<>'" & .Fields("CUSTOMER_ID").Value & "'" _
                            & " AND ORDER_NUM=ARRIVAL_NUM AND SERVICE_CODE='11'"
                    Set dsTRANSFER_TO = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
                    
                    If OraDatabaseRF.LastServerErr = 0 And dsTRANSFER_TO.RecordCount > 0 Then
                        iTransfer_To = dsTRANSFER_TO.Fields("CUSTOMER_ID").Value
                    Else
                        iTransfer_To = -1
                    End If
                    Sqlstmt = "SELECT * FROM RFBNI_MISC_BILL_RATE WHERE RF_SERVICE_CODE = '11' AND BNI_COMMODITY_DEF = '" & .Fields("BNI_COMM").Value & "'"
                    Set dsSHORT_TERM_DATA = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
        
                    
                    '' NOTE: TRANSFER RATE IS USED HERE
                    BniComm = GetBniComm(.Fields("BNI_COMM").Value, .Fields("CUSTOMER_ID").Value, .Fields("RECEIVING_TYPE").Value)
                    grdTrans.AddItem .Fields("THE_ARV").Value + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
                                      CustName(iTransfer_To) + Chr(9) + _
                                     .Fields("ACT_DATE").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                     .Fields("PLTCOUNT").Value + Chr(9) + _
                                     CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + CStr(roundUp(.Fields("PLTCOUNT").Value / 25) * dsSHORT_TERM_DATA.Fields("RATE").Value) + Chr(9) + BniComm
                                        
                    .MoveNext
                Next iRec
            End With
    
        Else
            If OraDatabaseRF.LastServerErr <> 0 Then
                MsgBox OraDatabaseRF.LastServerErrText, vbCritical
                OraDatabaseRF.LastServerErrReset
                Exit Sub
            End If
        End If
        
    ElseIf iTemp = 20 Then   'WALMART REPACKS
        
        grdData.Visible = True
        If Check1.Value = 0 Then
            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='20' AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
                    & " AND BILLING_FLAG IS NULL"
        Else
            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='20' AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
                    & " " 'AND BILLING_FLAG IS NULL"
        End If
        Set dsRETURN = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseBNI.LastServerErr = 0 And dsRETURN.RecordCount > 0 Then
            
            ''txtDesc = "RETURNS TRUCKLOADING IN/OUT @1.50/CWT"
            '' Per HD #2562
            txtDesc = "REPACK-RETURNS TRUCKUNLOADING " '& strDescripOutput
            'txtCommodity = "5101"
            'txtService = "6221"
            With dsRETURN
                For iRec = 1 To .RecordCount
                    grdData.AddItem Format(.Fields("SERVICE_DATE").Value, "mm/dd/yyyy") + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
                                    .Fields("LR_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("SERVICE_QTY").Value + Chr(9) + _
                                    CStr(Abs(.Fields("CASES").Value)) + Chr(9) + "" & .Fields("AVG_WT").Value + Chr(9) + _
                                    "" & .Fields("WEIGHT").Value + Chr(9) + "" & .Fields("AMOUNT").Value + _
                                    Chr(9) + .Fields("COMMODITY_CODE").Value
                    .MoveNext
                Next iRec
            End With
         End If
         
'                & " AND CT.RECEIVER_ID NOT IN ('439', '440') "
        Sqlstmt = " SELECT COUNT(DISTINCT CA.PALLET_ID) PLTCOUNT,SUM(QTY_CHANGE) CASES,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY') ACT_DATE,CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM, RECEIVING_TYPE," _
                & " NVL(BNI_COMM, COMMODITY_CODE) BNI_COMM, COMMODITY_CODE FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, RF_TO_BNI_COMM RTBC WHERE " _
                & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY')+1 " _
                & " AND CUSTOMER_ID<>1 AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION<>'VOID') " _
                & " AND SERVICE_CODE ='20' AND TO_MISCBILL IS NULL" _
                & " AND CT.ARRIVAL_NUM=CA.ARRIVAL_NUM " _
                & " AND CT.RECEIVER_ID=CA.CUSTOMER_ID " _
                & " AND CT.RECEIVER_ID NOT IN ('9722')" _
                & " AND CT.COMMODITY_CODE=RTBC.RF_COMM" _
                & " AND CT.COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE CP, LU_RF_MISC_BILLS_COMM_DISPLAY LRMBCD WHERE CP.COMMODITY_TYPE = LRMBCD.COMMODITY_TYPE) " _
                & " AND CT.PALLET_ID=CA.PALLET_ID" _
                & " GROUP BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY'),NVL(BNI_COMM, COMMODITY_CODE),COMMODITY_CODE,RECEIVING_TYPE" _
                & " ORDER BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,NVL(BNI_COMM, COMMODITY_CODE),COMMODITY_CODE"
                
        Set dsRETURN = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseRF.LastServerErr = 0 And dsRETURN.RecordCount > 0 Then
            ''txtDesc = "RETURNS TRUCKLOADING IN/OUT @1.50/CWT"
            '' Per HD #2562
            txtDesc = "REPACK-RETURNS TRUCKUNLOADING " '& strDescripOutput
            'txtCommodity = "5101"
            'txtService = "6221"
            
            With dsRETURN
                For iRec = 1 To .RecordCount
                    BniComm = GetBniComm(.Fields("BNI_COMM").Value, .Fields("CUSTOMER_ID").Value, .Fields("RECEIVING_TYPE").Value)
                    grdData.AddItem .Fields("ACT_DATE").Value + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
                                    .Fields("ARRIVAL_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("PLTCOUNT").Value + Chr(9) + _
                                    CStr(Abs(.Fields("CASES").Value)) + Chr(9) + Chr(9) + Chr(9) + Chr(9) + BniComm
                    .MoveNext
                Next iRec
            End With
        Else
            If OraDatabaseRF.LastServerErr <> 0 Then
                MsgBox OraDatabaseRF.LastServerErrText, vbCritical
                OraDatabaseRF.LastServerErrReset
                Exit Sub
            End If
        End If
    
    End If
    
    If cboService.ListIndex = 3 Then
        cmdSave.Enabled = False
    Else
        cmdSave.Enabled = True
    End If
    
End Sub
'Private Sub RetEnza()
'
'    If cboService.ListIndex = 1 Then   ' INBOUND
'
'        grdData.Visible = True
'        If Check1.Value = 0 Then
'            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE= '8' AND SERVICE_DATE  >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
'                    & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') + 1 " _
'                    & " AND BILLING_FLAG IS NULL"
'        Else
'            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE= '8' AND SERVICE_DATE  >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
'                    & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') + 1 " _
'                    & ""    ' AND BILLING_FLAG IS NULL"
'        End If
'        Set dsRetEnza = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
'        If OraDatabaseBNI.LastServerErr = 0 And dsRetEnza.RecordCount > 0 Then
'
'            txtDesc = "INBOUND TRUCKLOADING IN/OUT @17.25/PLT"
'            txtCommodity = "5411"
'            txtService = "6221"
'            With dsRetEnza
'                For iRec = 1 To .RecordCount
'                    grdData.AddItem .Fields("SERVICE_DATE").Value + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
'                                    .Fields("LR_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
'                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("SERVICE_QTY").Value + Chr(9) + _
'                                    CStr(Abs(.Fields("CASES").Value)) + Chr(9) + .Fields("AVG_WT").Value + Chr(9) + _
'                                    .Fields("WEIGHT").Value + Chr(9) + CStr(.Fields("AMOUNT").Value)
'                    .MoveNext
'                Next iRec
'            End With
'         End If
'
'         'took out the checking of arrival_num like '%101%', just stick with customer id  -- LFW, 4/25/05
''                 & " AND CT.RECEIVER_ID NOT IN ('439', '440') "
'         Sqlstmt = " SELECT COUNT(*) PLTCOUNT,SUM(QTY_CHANGE) CASES,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY') ACT_DATE,CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM, " _
'                 & " COMMODITY_CODE FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE " _
'                 & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
'                 & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') + 1 " _
'                 & " AND CUSTOMER_ID = '1512' AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID') " _
'                 & " AND SERVICE_CODE = '8' AND TO_MISCBILL IS NULL " _
'                 & " AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM " _
'                 & " AND CT.RECEIVER_ID = CA.CUSTOMER_ID " _
'                 & " AND CT.COMMODITY_CODE IN (SELECT COMMODITY_CODE FROM COMMODITY_PROFILE CP, LU_RF_MISC_BILLS_COMM_DISPLAY LRMBCD WHERE CP.COMMODITY_TYPE = LRMBCD.COMMODITY_TYPE) " _
'                 & " AND CT.PALLET_ID = CA.PALLET_ID" _
'                 & " GROUP BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY'),COMMODITY_CODE" _
'                 & " ORDER BY CUSTOMER_ID,CA.ARRIVAL_NUM,ORDER_NUM,COMMODITY_CODE"
'
'        Set dsRetEnza = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
'        If OraDatabaseRF.LastServerErr = 0 And dsRetEnza.RecordCount > 0 Then
'            txtDesc = "INBOUND TRUCKLOADING IN/OUT @17.25/PLT"
'            txtCommodity = "5411"
'            txtService = "6221"
'
'            With dsRetEnza
'                For iRec = 1 To .RecordCount
'                    grdData.AddItem .Fields("ACT_DATE").Value + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
'                                    .Fields("ARRIVAL_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
'                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("PLTCOUNT").Value + Chr(9) + _
'                                    .Fields("CASES").Value
'                    .MoveNext
'                Next iRec
'            End With
'        End If
'
'    End If
'
'Call grdDataCalc
'
'End Sub
Private Sub cmdSave_Click()

    Dim CommList As String
'    Dim CommCount As Integer

'    CommCount = 0
'    grdData.MoveFirst
'    For iRec = 0 To grdData.Rows - 1
'        If (IsNumeric(Trim(grdData.Columns(10).Value))) Then
'            CommCount = CommCount + 1
'        End If
'        grdData.MoveNext
'    Next iRec
    
'    If CommCount > 0 Then
'        If MsgBox("You have chosen to change the commodity codes of " & CommCount & " Order(s).  Continue?", vbQuestion + vbYesNo, "Verify") = vbNo Then
'            End
'            Exit Sub
'        Else
'            grdData.MoveFirst
'            For iRec = 0 To grdData.Rows - 1
'                If (IsNumeric(Trim(grdData.Columns(10).Value))) Then
'                    Sqlstmt = "SELECT COUNT(*) THE_COUNT FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '" & Trim(grdData.Columns(10).Value) & "'"
'                    Set dsSHORT_TERM_DATA = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
'                    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value <= 0 Then
'                        MsgBox "Commodity " & Trim(grdData.Columns(10).Value) & " on Line " & (iRec + 1) & " was not changed for this update.  It is not valid in the scanned cargo system." & vbCrLf & vbCrLf & "Please use the Pallet Correction Program to update the commodities for the pallets in question."
''                        MsgBox "Line " & (iRec + 1) & " did not have it's commodity changed; " & vbCrLf & vbCrLf & "Commodity " & Trim(grdData.Columns(10).Value) & " is not valid for the scanned cargo system." & vbCrLf & vbCrLf & "Please use the Pallet Correction Program to change the Commodities of the Pallets on this order."
'                    Else
'
'                        Sqlstmt = "UPDATE CARGO_TRACKING SET COMMODITY_CODE = '" & Trim(grdData.Columns(10).Value) & "' WHERE " _
'                        & "PALLET_ID IN (SELECT PALLET_ID FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & Trim(grdData.Columns(3).Value) & "') AND " _
'                        & "ARRIVAL_NUM = '" & Trim(grdData.Columns(2).Value) & "' AND COMMODITY_CODE IN " _
'                        & "(SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_NAME = '" & Trim(grdData.Columns(4).Value) & "')"
'
'                        OraDatabaseRF.ExecuteSQL (Sqlstmt)
'
'                        grdData.Columns(4).Value = CommName(grdData.Columns(10).Value)
'                    End If
'                End If
'                grdData.MoveNext
'            Next iRec
'        End If
'    End If



    Dim iService As Integer
    Dim Location As String
    Dim PeruvService As String
    OraSession.BeginTrans
    
'    If chkEnzaFruit.Visible = True And chkEnzaFruit.Value = 1 Then
'        Call SavEnaz
'    Exit Sub
'    End If
    
    grdTrans.MoveFirst
    grdData.MoveFirst
    
    Sqlstmt = "SELECT RF_SERVICE_CODE FROM RFBNI_MISC_BILL_HEADINGS WHERE DESCRIPTION = '" & cboService.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
    iService = dsSHORT_TERM_DATA.Fields("RF_SERVICE_CODE").Value
'    If cboService.ListIndex = 0 Then iService = 13
'    If cboService.ListIndex = 1 Then iService = 8
'    If cboService.ListIndex = 2 Then iService = 7
'    If cboService.ListIndex = 3 Then iService = 11
    
    Sqlstmt = " DELETE FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='" & iService & "' AND " _
            & " SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
            & " AND COMMODITY_CODE IN (SELECT BNI_COMMODITY_DEF FROM RFBNI_MISC_BILL_RATE)" _
            & " AND COMMODITY_CODE != '1272' " _
            & " AND BILLING_FLAG IS NULL AND DESCRIPTION NOT LIKE '%PANDOL A2 RATE%'"
                
    Dim RecordCount As Integer
    RecordCount = OraDatabaseBNI.ExecuteSQL(Sqlstmt)

    Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS "
    Set dsRFBNI = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
    If OraDatabaseBNI.LastServerErr = 0 Then
        
        dsRFBNI.MoveLast
        With dsRFBNI
            If cboService.ListIndex = 3 Then
                For iRec = 0 To grdTrans.Rows - 1
                    .AddNew
                    .Fields("SERVICE_DATE").Value = grdTrans.Columns(3).Value
                    iPos = InStr(1, Trim(grdTrans.Columns(2).Value), "-")
                    If iPos > 0 Then
                        .Fields("CUSTOMER_ID").Value = Val(Trim(Mid(grdTrans.Columns(2).Value, 1, iPos - 1)))
                    Else
                        .Fields("CUSTOMER_ID").Value = Val(Trim(grdTrans.Columns(2).Value))
                    End If
                    iPos = InStr(1, Trim(grdTrans.Columns(1).Value), "-")
                    
                    'transfer_to is actually Transfer_from and customer_id is the new customer
                    If iPos > 0 Then
                        .Fields("TRANSFER_TO").Value = Val(Trim(Mid(grdTrans.Columns(1).Value, 1, iPos - 1)))
                    Else
                        .Fields("TRANSFER_TO").Value = Val(Trim(grdTrans.Columns(1).Value))
                    End If
                    .Fields("LR_NUM").Value = Val(Left(grdTrans.Columns(0).Value, 7))
                    .Fields("ORDER_NUM").Value = grdTrans.Columns(4).Value
'                    .Fields("COMMODITY_CODE").Value = Val(Trim(txtCommodity))
                    .Fields("COMMODITY_CODE").Value = Val(grdTrans.Columns(8).Value)
                    .Fields("SERVICE_QTY").Value = Val(grdTrans.Columns(5).Value)
                    '.FIELDS("CASES").Value = grdTrans.Columns(2).Value
                    '.FIELDS("AVG_WT").Value = grdTrans.Columns(2).Value
                    '.FIELDS("WEIGHT").Value = grdTrans.Columns(2).Value
                    .Fields("AMOUNT").Value = Val(grdTrans.Columns(7).Value)
                    .Fields("SERVICE_CODE").Value = Val(Trim(txtService))
                    '' Per HD# 2384, description was modified by pwu. 9/8/2006
                    .Fields("DESCRIPTION").Value = Trim(txtDesc) & GetDescription(iService, Val(grdTrans.Columns(8).Value), Val(Trim(Mid(grdTrans.Columns(2).Value, 1, iPos - 1)))) & " (" & "ORDER NO:" & grdTrans.Columns(4).Value & " " & _
                                                    "PLTS:" & grdTrans.Columns(5).Value & ")"
                    .Fields("RF_SERVICE_CODE").Value = Val(Trim(iService))
                    
                    'Added for asset Coding 09.05.2001 pawan
                    
                    'get location from CARGO_ACTIVITY
                    'get asset from asset_profile based on the location code
                    gsSqlStmt = " SELECT ACTIVITY_BILLED FROM CARGO_ACTIVITY WHERE ORDER_NUM='" & Trim(grdTrans.Columns(4).Value) & "' AND " _
                                & " CUSTOMER_ID='" & Trim(.Fields("CUSTOMER_ID").Value) & "'"
                    Set dsLOCATION_ID = OraDatabaseRF.DbCreateDynaset(gsSqlStmt, 0&)
                    If Not IsNull(dsLOCATION_ID.Fields("ACTIVITY_BILLED")) Then
                    If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "a" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "A" Then
                        Location = "WING A"
                    Else
                        If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "b" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "B" Then
                            Location = "WING B"
                        Else
                            If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "c" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "C" Then
                                Location = "WING C"
                            Else
                                If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "d" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "D" Then
                                    Location = "WING D"
                                Else
                                    If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "e" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "E" Then
                                        Location = "WING E"
                                    Else
                                        If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "f" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "F" Then
                                            Location = "WING F"
                                        Else
                                            If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "g" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "G" Then
                                                Location = "WING G"
                                            Else
                                                Location = "0000" 'pawan pawan pawan pawan - horrible, as usual... -STM
                                            End If
                    
                                        End If
                    
                                    End If
                    
                                End If
                    
                            End If
                    
                        End If
                    
                    End If
                    Else
                    Location = "0000"
                    End If
                    gs1SqlStmt = " Select * from ASSET_PROFILE where " & _
                                " SERVICE_LOCATION_CODE = '" & Location & "'"
                    Set dsAssetProfile = OraDatabaseBNI.DbCreateDynaset(gs1SqlStmt, 0&)
            
                    If dsAssetProfile.RecordCount = 0 Then
                        .Fields("ASSET_CODE").Value = "W000"
                    Else
                        .Fields("ASSET_CODE").Value = dsAssetProfile.Fields("ASSET_CODE").Value
                    End If
                    
                    
                          
                    '............
                    '.....................
                    
                    
                    
                    
                    .Update
                    grdTrans.MoveNext
                Next iRec
            Else
                For iRec = 0 To grdData.Rows - 1
                    If Val(Trim(txtService)) = 6221 And (Left$(grdData.Columns(4).Value, 4) = "5305" Or Left$(grdData.Columns(4).Value, 4) = "5320") Then
                        PeruvService = Val("6227")
                    Else
                        PeruvService = Val(Trim(txtService))
                    End If
                    
                    .AddNew
                    .Fields("SERVICE_DATE").Value = grdData.Columns(0).Value
                    iPos = InStr(1, Trim(grdData.Columns(1).Value), "-")
                    
                    If iPos > 0 Then
                        .Fields("CUSTOMER_ID").Value = Val(Trim(Mid(grdData.Columns(1).Value, 1, iPos - 1)))
                    Else
                        .Fields("CUSTOMER_ID").Value = Val(Trim(grdData.Columns(1).Value))
                    End If
                    If (IsNumeric(Left(grdData.Columns(2).Value, 7))) Then
                        .Fields("LR_NUM").Value = Val(Left(grdData.Columns(2).Value, 7))
                    Else
                        .Fields("LR_NUM").Value = 0
                    End If
'                    .Fields("LR_NUM").Value = Val(Left(grdData.Columns(2).Value, 7))
                    .Fields("ORDER_NUM").Value = grdData.Columns(3).Value
'                    If (IsNumeric(Trim(grdData.Columns(10).Value))) Then
'                        .Fields("COMMODITY_CODE").Value = Val(grdData.Columns(10).Value)
'                    Else
''                        If (Trim(grdData.Columns(10).Value) = "DOLEPAPER") Then
''                            .Fields("COMMODITY_CODE").Value = 1272
''                        Else
'                        .Fields("COMMODITY_CODE").Value = Val(Trim(txtCommodity))
''                        End If
'                    End If
'                    .Fields("COMMODITY_CODE").Value = Val(grdData.Columns(11).Value)
                    .Fields("COMMODITY_CODE").Value = Val(grdData.Columns(10).Value)
                    .Fields("SERVICE_QTY").Value = Val(grdData.Columns(5).Value)
                    .Fields("CASES").Value = Val(grdData.Columns(6).Value)
                    .Fields("AVG_WT").Value = Val(grdData.Columns(7).Value)
                    .Fields("WEIGHT").Value = Val(grdData.Columns(8).Value)
                    .Fields("AMOUNT").Value = Val(grdData.Columns(9).Value)
'                    .Fields("SERVICE_CODE").Value = Val(Trim(txtService))
                    .Fields("SERVICE_CODE").Value = Val(Trim(PeruvService))
                    '' Per HD# 2384, description was modified by pwu. 9/8/2006
                    .Fields("DESCRIPTION").Value = Trim(txtDesc) & GetDescription(iService, Val(grdData.Columns(10).Value), Val(Trim(Mid(grdData.Columns(1).Value, 1, iPos - 1)))) & " (" & "ORDER NO:" & grdData.Columns(3).Value & " " & _
                                                "PLTS:" & grdData.Columns(5).Value & " " & _
                                                "CASES:" & grdData.Columns(6).Value & " " & _
                                                "WEIGHT:" & grdData.Columns(8).Value & ")"

                    .Fields("RF_SERVICE_CODE").Value = Val(Trim(iService))
                    'Added for asset Coding 09.05.2001 pawan
                    
                    'get location from CARGO_ACTIVITY
                    'get asset from asset_profile based on the location code
                    gsSqlStmt = " SELECT ACTIVITY_BILLED FROM CARGO_ACTIVITY WHERE ORDER_NUM='" & Trim(grdTrans.Columns(4).Value) & "' AND " _
                                & " CUSTOMER_ID='" & Trim(.Fields("CUSTOMER_ID").Value) & "'"
                    Set dsLOCATION_ID = OraDatabaseRF.DbCreateDynaset(gsSqlStmt, 0&)
                    If Not IsNull(dsLOCATION_ID.Fields("ACTIVITY_BILLED")) Then
                    If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "a" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "A" Then
                        Location = "WING A"
                    Else
                        If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "b" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "B" Then
                            Location = "WING B"
                        Else
                            If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "c" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "C" Then
                                Location = "WING C"
                            Else
                                If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "d" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "D" Then
                                    Location = "WING D"
                                Else
                                    If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "e" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "E" Then
                                        Location = "WING E"
                                    Else
                                        If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "f" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "F" Then
                                            Location = "WING F"
                                        Else
                                            If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "g" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "G" Then
                                                Location = "WING G"
                                            Else
                                                Location = "0000" 'pawan pawan pawan pawan
                                            End If
                    
                                        End If
                    
                                    End If
                    
                                End If
                    
                            End If
                    
                        End If
                    
                    End If
                    Else
                    Location = "0000"
                    End If
                    gs1SqlStmt = " Select * from ASSET_PROFILE where " & _
                                " SERVICE_LOCATION_CODE = '" & Location & "'"
                    Set dsAssetProfile = OraDatabaseBNI.DbCreateDynaset(gs1SqlStmt, 0&)
            
                    If dsAssetProfile.RecordCount = 0 Then
                        .Fields("ASSET_CODE").Value = "W000"
                    Else
                        .Fields("ASSET_CODE").Value = dsAssetProfile.Fields("ASSET_CODE").Value
                    End If
                    
                    
                          
                    '............
                    '.....................
                    .Update
'                    grdData.Columns(10).Value = ""
                    
                    
                    grdData.MoveNext
                    
                Next iRec
            End If
        End With
            
       ''OraSession.CommitTrans
    End If
    
    Sqlstmt = " UPDATE CARGO_ACTIVITY SET TO_MISCBILL='Y' WHERE TO_MISCBILL IS NULL" _
            & " AND DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
            & " AND CUSTOMER_ID NOT IN ('9722') " _
            & " AND SERVICE_CODE ='" & iService & "' AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION != 'VOID')" _
            & " AND (PALLET_ID, CUSTOMER_ID, ARRIVAL_NUM) IN (SELECT PALLET_ID, RECEIVER_ID, ARRIVAL_NUM FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP, LU_RF_MISC_BILLS_COMM_DISPLAY LRMBCD WHERE CP.COMMODITY_TYPE = LRMBCD.COMMODITY_TYPE AND CT.COMMODITY_CODE = CP.COMMODITY_CODE) " _
            & " AND CUSTOMER_ID NOT IN (SELECT CUSTOMER_ID FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS)" _
            & " AND PALLET_ID NOT IN (SELECT PALLET_ID FROM CARGO_TRACKING WHERE RECEIVER_ID = '1608' AND WAREHOUSE_LOCATION = 'A2')"
    OraDatabaseRF.ExecuteSQL (Sqlstmt)

    If OraDatabaseRF.LastServerErr = 0 And OraDatabaseBNI.LastServerErr = 0 Then
       OraSession.CommitTrans
        MsgBox " Save successful !", vbInformation, "SAVE"
    Else
        OraSession.RollBack
        MsgBox " ORACLE ERROR ! " & vbCrLf & "Unable Save the records", vbCritical
        OraDatabaseRF.LastServerErrReset
        OraDatabaseBNI.LastServerErrReset
        Exit Sub
    End If
    
End Sub
'Private Sub SavEnaz()
'
'Dim iService As Integer
'Dim Location As String
'grdTrans.MoveFirst
'grdData.MoveFirst
'
'    If cboService.ListIndex = 0 Then iService = 13
'    If cboService.ListIndex = 1 Then iService = 8
'    If cboService.ListIndex = 2 Then iService = 7
'    If cboService.ListIndex = 3 Then iService = 11
'
'    Sqlstmt = " DELETE FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='" & iService & "' AND " _
'            & " SERVICE_DATE >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
'            & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') + 1 " _
'            & " AND BILLING_FLAG IS NULL "
'
'    OraDatabaseBNI.ExecuteSQL (Sqlstmt)
'
'    Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS "
'    Set dsSavRFBNI = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
'    If OraDatabaseBNI.LastServerErr = 0 Then
'
'        With dsSavRFBNI
'
'            For iRec = 0 To grdData.Rows - 1
'            .AddNew
'                    .Fields("SERVICE_DATE").Value = grdData.Columns(0).Value
'                    iPos = InStr(1, Trim(grdData.Columns(1).Value), "-")
'                    If iPos > 0 Then
'                        .Fields("CUSTOMER_ID").Value = Trim(Mid(grdData.Columns(1).Value, 1, iPos - 1))
'                    Else
'                        .Fields("CUSTOMER_ID").Value = Trim(grdData.Columns(1).Value)
'                    End If
'
'                    .Fields("LR_NUM").Value = grdData.Columns(2).Value
'                    .Fields("ORDER_NUM").Value = grdData.Columns(3).Value
'                    .Fields("COMMODITY_CODE").Value = Trim(txtCommodity)
'                    .Fields("SERVICE_QTY").Value = grdData.Columns(5).Value
'                    .Fields("CASES").Value = grdData.Columns(6).Value
'                    .Fields("AVG_WT").Value = grdData.Columns(7).Value
'                    .Fields("WEIGHT").Value = grdData.Columns(8).Value
'                    .Fields("AMOUNT").Value = grdData.Columns(9).Value
'                    .Fields("SERVICE_CODE").Value = Trim(txtService)
'                    ''.Fields("DESCRIPTION").Value = Trim(txtDesc)
'                    .Fields("DESCRIPTION").Value = "ORDER NO:" & grdData.Columns(3).Value & " " & _
'                            "PLTS:" & grdData.Columns(5).Value & " " & _
'                            "CASES:" & grdData.Columns(6).Value & " " & _
'                            "WEIGHT:" & grdData.Columns(8).Value & Trim(txtDesc)
'                    .Fields("RF_SERVICE_CODE").Value = Trim(iService)
'                    'Added for asset Coding 09.05.2001 pawan
'
'                    'get location from CARGO_ACTIVITY
'                    'get asset from asset_profile based on the location code
'                    gsSqlStmt = " SELECT ACTIVITY_BILLED FROM CARGO_ACTIVITY WHERE ORDER_NUM='" & Trim(grdTrans.Columns(4).Value) & "' AND " _
'                                & " CUSTOMER_ID='" & Trim(.Fields("CUSTOMER_ID").Value) & "'"
'                    Set dsLOCATION_ID = OraDatabaseRF.DbCreateDynaset(gsSqlStmt, 0&)
'                    If Not IsNull(dsLOCATION_ID.Fields("ACTIVITY_BILLED")) Then
'                    If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "a" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "A" Then
'                        Location = "WING A"
'                    Else
'                        If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "b" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "B" Then
'                            Location = "WING B"
'                        Else
'                            If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "c" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "C" Then
'                                Location = "WING C"
'                            Else
'                                If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "d" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "D" Then
'                                    Location = "WING D"
'                                Else
'                                    If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "e" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "E" Then
'                                        Location = "WING E"
'                                    Else
'                                        If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "f" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "F" Then
'                                            Location = "WING F"
'                                        Else
'                                            If dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "g" Or dsLOCATION_ID.Fields("ACTIVITY_BILLED").Value = "G" Then
'                                                Location = "WING G"
'                                            Else
'                                                Location = "0000" 'pawan pawan pawan pawan
'                                            End If
'
'                                        End If
'
'                                    End If
'
'                                End If
'
'                            End If
'
'                        End If
'
'                    End If
'                    Else
'                    Location = "0000"
'                    End If
'                    gs1SqlStmt = " Select * from ASSET_PROFILE where " & _
'                                " SERVICE_LOCATION_CODE = '" & Location & "'"
'                    Set dsAssetProfile = OraDatabaseBNI.DbCreateDynaset(gs1SqlStmt, 0&)
'
'                    If dsAssetProfile.RecordCount = 0 Then
'                        .Fields("ASSET_CODE").Value = "W000"
'                    Else
'                        .Fields("ASSET_CODE").Value = dsAssetProfile.Fields("ASSET_CODE").Value
'                    End If
'
'
'
'                    '............
'                    '.....................
'
'
'
'                    .Update
'                    grdData.MoveNext
'            Next iRec
'        End With
'
'    End If
'
'    Sqlstmt = " UPDATE CARGO_ACTIVITY SET TO_MISCBILL='Y' WHERE TO_MISCBILL IS NULL" _
'            & " AND DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'            & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
'            & " AND SERVICE_CODE ='" & iService & "' AND ACTIVITY_DESCRIPTION IS NULL"
'    OraDatabaseRF.ExecuteSQL (Sqlstmt)
'
'    If OraDatabaseRF.LastServerErr = 0 And OraDatabaseBNI.LastServerErr = 0 Then
'        OraSession.CommitTrans
'        MsgBox " Save successful !", vbInformation, "SAVE"
'    Else
'        OraSession.RollBack
'        MsgBox " ORACLE ERROR ! " & vbCrLf & "Unable Save the records", vbCritical
'        OraDatabaseRF.LastServerErrReset
'        OraDatabaseBNI.LastServerErrReset
'        Exit Sub
'    End If
'
'End Sub
Private Sub cndRefresh_Click()
    
    Call cmdRet_Click
    
End Sub


Private Sub Form_Load()
    
    Me.Left = (Screen.Width - Me.Width) / 2
    Me.Top = (Screen.Height - Me.Height) / 2
    
    Sqlstmt = "SELECT DESCRIPTION FROM RFBNI_MISC_BILL_HEADINGS"
    Set dsSHORT_TERM_DATA = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
    For iRec = 1 To dsSHORT_TERM_DATA.RecordCount
        cboService.AddItem dsSHORT_TERM_DATA.Fields("DESCRIPTION").Value
        dsSHORT_TERM_DATA.MoveNext
    Next iRec
    
    grdData.Visible = False
    grdTrans.Visible = False
    dtpDate = Format(Now, "mm/dd/yyyy")
    cboService.ListIndex = -1
    Me.Show
        
End Sub
Private Sub grdData_AfterColUpdate(ByVal ColIndex As Integer)
   
'   If chkEnzaFruit.Visible = True And chkEnzaFruit.Value = 1 Then Exit Sub
   
    Sqlstmt = "SELECT RF_SERVICE_CODE FROM RFBNI_MISC_BILL_HEADINGS WHERE DESCRIPTION = '" & cboService.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
    iTemp = dsSHORT_TERM_DATA.Fields("RF_SERVICE_CODE").Value
'  Select Case cboService.ListIndex
'
'    Case 0
'      iTemp = 13
'
'    Case 1
'      iTemp = 8
'
'    Case 2
'      iTemp = 7
'
'    Case 3
'      iTemp = 11
'
'  End Select
   
    Sqlstmt = "SELECT * FROM RFBNI_MISC_BILL_RATE WHERE RF_SERVICE_CODE = '" & iTemp & "' AND BNI_COMMODITY_DEF = '" & Val(grdData.Columns(10).Value) & "'"
    Set dsRATEINFO = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
    
    If Left$(grdData.Columns(1).Value, 3) = "402" And Left$(grdData.Columns(4).Value, 4) = "5305" And cboService.ListIndex = 1 Then
        iTemp = 30.5
    Else
        iTemp = dsRATEINFO.Fields("RATE").Value
    End If
    
    sUnit = dsRATEINFO.Fields("PER_UNIT").Value
   
    Select Case ColIndex
        Case 7
            If Trim(grdData.Columns(6).Value) = "" And Trim(grdData.Columns(7).Value) = "" Then Exit Sub
            grdData.Columns(8).Value = grdData.Columns(6).Value * grdData.Columns(7).Value
            
            If sUnit = "CWT" Then
                grdData.Columns(9).Value = Round(grdData.Columns(8).Value * iTemp / 100, 2)
            ElseIf sUnit = "25PLT" Then
                grdData.Columns(9).Value = roundUp(grdData.Columns(5).Value / 25) * iTemp
            ElseIf sUnit = "PLT" Then
                grdData.Columns(9).Value = Round(grdData.Columns(5).Value * iTemp, 2)
            Else
                ' shouldn't happen... for now
                grdData.Columns(9).Value = grdData.Columns(5).Value * iTemp
                MsgBox "An unexpected rate unit was discovered in the MiscBill system.  The automatically-calculated total will assume a unit of 'per pallet'; please contact Finance immediately."
            End If
            
'        Case 10
'            If Trim(grdData.Columns(10).Value) = "" Then Exit Sub
'            grdData.Columns(11).Value = grdData.Columns(10).Value
            
'            If cboService.ListIndex = 1 Then '' InBound
'                ''grdData.Columns(9).Value = Round(grdData.Columns(8).Value * 1 / 100, 2)
'                ''grdData.Columns(9).Value = Round(grdData.Columns(8).Value * 1.58 / 100, 2)
'                ''  Per HD #2562
'                ''grdData.Columns(9).Value = Round(grdData.Columns(8).Value * 1.08 / 100, 2)
'                '' Per HD #2826 change 1.08 to 1.71
'                grdData.Columns(9).Value = Round(grdData.Columns(8).Value * iTemp / 100, 2)
'            ElseIf cboService.ListIndex = 2 Then '' Customer Returns (Full Returns)
'                ''grdData.Columns(9).Value = Round(grdData.Columns(8).Value * 1.5 / 100, 2)
'                '' Per HD# 2562
'                grdData.Columns(9).Value = Round(grdData.Columns(8).Value * iTemp / 100, 2)
'            End If
    End Select
                
End Sub

'Private Sub grdTrans_AfterColUpdate(ByVal ColIndex As Integer)
'
'   If chkEnzaFruit.Visible = True And chkEnzaFruit.Value = 1 Then Exit Sub
'
'  Select Case cboService.ListIndex
'
'    Case 0
'      iTemp = 13
'
'    Case 1
'      iTemp = 8
'
'    Case 2
'      iTemp = 7
'
'    Case 3
'      iTemp = 11
'
'  End Select
'
'    Sqlstmt = "SELECT * FROM RFBNI_MISC_BILL_RATE WHERE RF_SERVICE_CODE = '" & iTemp & "'"
'    Set dsRATEINFO = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
'
'    iTemp = dsRATEINFO.Fields("RATE").Value
'    sUnit = dsRATEINFO.Fields("PER_UNIT").Value
'
'    Select Case ColIndex
'        Case 7
'            If Trim(grdData.Columns(6).Value) = "" And Trim(grdData.Columns(7).Value) = "" Then Exit Sub
'            grdData.Columns(8).Value = grdData.Columns(6).Value * grdData.Columns(7).Value
'
'            If sUnit = "ORDER" Then
'                grdTrans.Columns(7).Value = iTemp
'            Else
'                ' shouldn't happen... for now
'                grdTrans.Columns(7).Value = grdData.Columns(5).Value * iTemp
'                MsgBox "An unexpected rate unit was discovered in the MiscBill system.  No auto-calculation done; please contact Finance immediately."
'            End If
'    End Select
'
'End Sub

'Private Sub grdDataCalc()
'
'    If chkEnzaFruit.Visible = True And chkEnzaFruit.Value = 1 Then
'
'        If cboService.ListIndex = 1 Then
'        grdData.Columns(9).Value = Round(grdData.Columns(5).Value * 17.25, 2)
'        End If
'
'    End If
'
'End Sub

Private Function GetDescription(RFCode As Integer, Comm As Integer, Cust As Integer) As String
    Dim TempRate As String
    Sqlstmt = "SELECT * FROM RFBNI_MISC_BILL_RATE WHERE RF_SERVICE_CODE = '" & RFCode & "' AND BNI_COMMODITY_DEF = '" & Comm & "'"
    Set dsSHORT_TERM_DATA = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
    If Cust = 402 And Comm = 5305 And cboService.ListIndex = 1 Then
        TempRate = "30.50"
    Else
        TempRate = dsSHORT_TERM_DATA.Fields("RATE").Value
    End If
    
    
    GetDescription = "@ $" & TempRate & " / " & dsSHORT_TERM_DATA.Fields("PER_UNIT").Value
 
End Function

