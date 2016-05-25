VERSION 5.00
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmAdTrkLodBill 
   AutoRedraw      =   -1  'True
   Caption         =   "ADVANCED TRUCK LOADING & BACKHAUL BILLING"
   ClientHeight    =   10350
   ClientLeft      =   165
   ClientTop       =   450
   ClientWidth     =   15780
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
   ScaleHeight     =   10350
   ScaleWidth      =   15780
   StartUpPosition =   3  'Windows Default
   Begin SSDataWidgets_B.SSDBGrid grdData3 
      Height          =   2625
      Left            =   158
      TabIndex        =   7
      Top             =   6600
      Width           =   15345
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
      ForeColorEven   =   0
      ForeColorOdd    =   8388608
      RowHeight       =   450
      ExtraHeight     =   53
      Columns.Count   =   14
      Columns(0).Width=   1323
      Columns(0).Caption=   "CUST"
      Columns(0).Name =   "CUST"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1349
      Columns(1).Caption=   "COMM"
      Columns(1).Name =   "COMM"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   4154
      Columns(2).Caption=   "SERVICE"
      Columns(2).Name =   "SERV"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   3200
      Columns(3).Visible=   0   'False
      Columns(3).Caption=   "SERCODE"
      Columns(3).Name =   "SERCODE"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1720
      Columns(4).Caption=   "DATE"
      Columns(4).Name =   "DATE"
      Columns(4).Alignment=   2
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1773
      Columns(5).Caption=   "PLTS"
      Columns(5).Name =   "PLTS"
      Columns(5).Alignment=   1
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1799
      Columns(6).Caption=   "CASES"
      Columns(6).Name =   "CASES"
      Columns(6).Alignment=   1
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1773
      Columns(7).Caption=   "WT"
      Columns(7).Name =   "WT"
      Columns(7).Alignment=   1
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1244
      Columns(8).Caption=   "RATE"
      Columns(8).Name =   "RATE"
      Columns(8).Alignment=   1
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1323
      Columns(9).Caption=   "UNIT"
      Columns(9).Name =   "UNIT"
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   1852
      Columns(10).Caption=   "AMT"
      Columns(10).Name=   "AMT"
      Columns(10).Alignment=   1
      Columns(10).CaptionAlignment=   2
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   1588
      Columns(11).Caption=   "BILL TO"
      Columns(11).Name=   "BILL TO"
      Columns(11).CaptionAlignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   1349
      Columns(12).Caption=   "LOC"
      Columns(12).Name=   "LOC"
      Columns(12).CaptionAlignment=   2
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   4710
      Columns(13).Caption=   "COMMENTS"
      Columns(13).Name=   "COMMENTS"
      Columns(13).CaptionAlignment=   2
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      _ExtentX        =   27067
      _ExtentY        =   4630
      _StockProps     =   79
      Caption         =   "TERMINAL SERVICE"
      ForeColor       =   0
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
   Begin VB.CommandButton cmdPreBill 
      Caption         =   "&SAVE"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   405
      Left            =   2280
      TabIndex        =   6
      Top             =   9600
      Width           =   1245
   End
   Begin VB.CommandButton cmdInvoice 
      Caption         =   "&INVOICE"
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   405
      Left            =   4200
      TabIndex        =   5
      Top             =   9600
      Width           =   1245
   End
   Begin VB.CommandButton Command3 
      Cancel          =   -1  'True
      Caption         =   "&EXIT"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   405
      Left            =   6000
      TabIndex        =   4
      Top             =   9600
      Width           =   1245
   End
   Begin SSDataWidgets_B.SSDBGrid grddata2 
      Height          =   2865
      Left            =   158
      TabIndex        =   3
      Top             =   3600
      Width           =   15345
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
      ForeColorEven   =   0
      ForeColorOdd    =   8388608
      RowHeight       =   450
      ExtraHeight     =   53
      Columns.Count   =   14
      Columns(0).Width=   1323
      Columns(0).Caption=   "CUST"
      Columns(0).Name =   "CUST"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1349
      Columns(1).Caption=   "COMM"
      Columns(1).Name =   "COMM"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   4154
      Columns(2).Caption=   "SERVICE"
      Columns(2).Name =   "SERV"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   3200
      Columns(3).Visible=   0   'False
      Columns(3).Caption=   "SERCODE"
      Columns(3).Name =   "SERCODE"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1720
      Columns(4).Caption=   "DATE"
      Columns(4).Name =   "DATE"
      Columns(4).Alignment=   2
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1773
      Columns(5).Caption=   "PLTS"
      Columns(5).Name =   "PLTS"
      Columns(5).Alignment=   1
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1799
      Columns(6).Caption=   "CASES"
      Columns(6).Name =   "CASES"
      Columns(6).Alignment=   1
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1773
      Columns(7).Caption=   "WT"
      Columns(7).Name =   "WT"
      Columns(7).Alignment=   1
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1244
      Columns(8).Caption=   "RATE"
      Columns(8).Name =   "RATE"
      Columns(8).Alignment=   1
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1323
      Columns(9).Caption=   "UNIT"
      Columns(9).Name =   "UNIT"
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   1852
      Columns(10).Caption=   "AMT"
      Columns(10).Name=   "AMT"
      Columns(10).Alignment=   1
      Columns(10).CaptionAlignment=   2
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   1588
      Columns(11).Caption=   "BILL TO"
      Columns(11).Name=   "BILL TO"
      Columns(11).CaptionAlignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   1349
      Columns(12).Caption=   "LOC"
      Columns(12).Name=   "LOC"
      Columns(12).CaptionAlignment=   2
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   4710
      Columns(13).Caption=   "COMMENTS"
      Columns(13).Name=   "COMMENTS"
      Columns(13).CaptionAlignment=   2
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      _ExtentX        =   27067
      _ExtentY        =   5054
      _StockProps     =   79
      Caption         =   "BACKHAUL"
      ForeColor       =   0
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
   Begin Crystal.CrystalReport crw1 
      Left            =   630
      Top             =   0
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      WindowControlBox=   -1  'True
      WindowMaxButton =   -1  'True
      WindowMinButton =   -1  'True
      PrintFileLinesPerPage=   60
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   3075
      Left            =   128
      TabIndex        =   1
      Top             =   450
      Width           =   15345
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
      ForeColorEven   =   0
      ForeColorOdd    =   8388608
      RowHeight       =   450
      ExtraHeight     =   53
      Columns.Count   =   14
      Columns(0).Width=   1323
      Columns(0).Caption=   "CUST"
      Columns(0).Name =   "CUST"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1349
      Columns(1).Caption=   "COMM"
      Columns(1).Name =   "COMM"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   4154
      Columns(2).Caption=   "SERVICE"
      Columns(2).Name =   "SERV"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   3200
      Columns(3).Visible=   0   'False
      Columns(3).Caption=   "SERCODE"
      Columns(3).Name =   "SERCODE"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1720
      Columns(4).Caption=   "DATE"
      Columns(4).Name =   "DATE"
      Columns(4).Alignment=   2
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1773
      Columns(5).Caption=   "PLTS"
      Columns(5).Name =   "PLTS"
      Columns(5).Alignment=   1
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1799
      Columns(6).Caption=   "CASES"
      Columns(6).Name =   "CASES"
      Columns(6).Alignment=   1
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1773
      Columns(7).Caption=   "WT"
      Columns(7).Name =   "WT"
      Columns(7).Alignment=   1
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1244
      Columns(8).Caption=   "RATE"
      Columns(8).Name =   "RATE"
      Columns(8).Alignment=   1
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1323
      Columns(9).Caption=   "UNIT"
      Columns(9).Name =   "UNIT"
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   1852
      Columns(10).Caption=   "AMT"
      Columns(10).Name=   "AMT"
      Columns(10).Alignment=   1
      Columns(10).CaptionAlignment=   2
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   1588
      Columns(11).Caption=   "BILL TO"
      Columns(11).Name=   "BILL TO"
      Columns(11).CaptionAlignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   1349
      Columns(12).Caption=   "LOC"
      Columns(12).Name=   "LOC"
      Columns(12).CaptionAlignment=   2
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   4710
      Columns(13).Caption=   "COMMENTS"
      Columns(13).Name=   "COMMENTS"
      Columns(13).CaptionAlignment=   2
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      _ExtentX        =   27067
      _ExtentY        =   5424
      _StockProps     =   79
      Caption         =   "ADVANCE TRUCK LOADING "
      ForeColor       =   0
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
   Begin VB.Label Label2 
      Caption         =   "Any description put under the ""Comments"" column will show up on the printed invoice just after the billing number."
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   11.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00000000&
      Height          =   615
      Left            =   7680
      TabIndex        =   8
      Top             =   9480
      Width           =   7815
   End
   Begin VB.Label lblVessel 
      AutoSize        =   -1  'True
      Caption         =   "Label2"
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
      Left            =   8093
      TabIndex        =   2
      Top             =   60
      Width           =   555
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "VESSEL  :"
      BeginProperty Font 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00C00000&
      Height          =   225
      Left            =   6983
      TabIndex        =   0
      Top             =   60
      Width           =   825
   End
End
Attribute VB_Name = "frmAdTrkLodBill"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Dim SqlStmt As String
Dim iRec As Integer
Dim iLrNum As Integer
Dim lBillNum As Long
Dim lStartBillNum As Long
Dim lEndBillNum As Long
Dim iRow As Long
Dim iLine As Integer
Dim sPltCaseWt As String
Dim valid As Boolean
Dim Cid As String
Dim Ccode As String
Dim Scode As String
Dim Acode As String
Dim ServLoc As String
Dim sLoc As String
Dim AdBilling_success As Boolean
Dim BckBilling_success As Boolean
Dim TerBilling_success As Boolean


Sub NEW_PAGE()

    Dim iLine As Integer
       
    iNum = 0
    iRow = iRow + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iRow, "", 1, 0)
        
    iRow = iRow + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iRow, Space(227) & sInvNum, 0, 0)
       
    iRow = iRow + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iRow, "", 0, 0)
    
    iLine = iLine + 1
    iRow = iRow + 1
    iNum = iNum + 1
    Call PreInv_AddNew(iRow, Space(227) & sInvDt, 0, 0)
    
    For iLine = 1 To 33
        iLine = iLine + 1
        iRow = iRow + 1
        iNum = iNum + 1
        Call PreInv_AddNew(iRow, "", 0, 0)
    Next iLine
    
End Sub
Sub PreInv_AddNew(RowNum As Long, Row_Text As String, eof As Integer, Amt As Double)
    
    With dsPreInv
        .AddNew
        .fields("Row_Num").Value = RowNum
        .fields("Text").Value = Row_Text
        .fields("eof").Value = eof
        .fields("AMT").Value = Amt
        .Update
    End With
    
End Sub
Private Sub cmdInvoice_Click()

   iLine = 0
   iRec = 0
   bStart = True
   iCustId = 0
   dTotalAmount = 0
   dGrandTotal = 0
   iNum = 0
   
   Call SubPreInv
   
   OraSession.BeginTrans
   
   
   SqlStmt = " SELECT * FROM BILLING WHERE SERVICE_STATUS = 'PREINVOICE' AND " _
           & " BILLING_TYPE IN ('ADTRKLOAD','BACKHAUL','TERMSER') AND LR_NUM='" & iLrNum & "' ORDER BY CUSTOMER_ID, " _
           & " BILLING_TYPE,BILLING_NUM"
              
   Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
   If OraDatabase.LastServerErr = 0 And dsBILLING.RecordCount > 0 Then
        
      'StatusBar1.SimpleText = "PROCESSING TRUCK LOADING PREBILLS FOR PRINTING..."
        
      For iRec = 1 To dsBILLING.RecordCount
            
         DoEvents
            
         'Get from Customer table based on Customer Code
         SqlStmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = " _
                 & "'" & dsBILLING.fields("CUSTOMER_ID").Value & "'"
         Set dsCUSTOMER_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)

         SqlStmt = "SELECT * FROM CARGO_MANIFEST WHERE CONTAINER_NUM = " _
                 & "'" & dsBILLING.fields("LOT_NUM").Value & "'"
         Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
               
         'Check to see if we need to change the invoice number and headings on the page
         If (iCustId <> dsBILLING.fields("CUSTOMER_ID").Value) Or _
            (sBillingType <> dsBILLING.fields("BILLING_TYPE").Value) Then
                
            If bStart = False Then
               For iLine = 1 To 2
                  If iNum = 54 Then Call NEW_PAGE
                  iNum = iNum + 1
                  iRow = iRow + 1
                  Call PreInv_AddNew(iRow, "", 0, 0)
               Next iLine
                   
               If iNum = 54 Then Call NEW_PAGE
               iNum = iNum + 1
               iRow = iRow + 1
               Call PreInv_AddNew(iRow, Space(5) & "----------------------------------------------------------------------------------------------------" _
                                                 & "---------------------------------------------------------------------------------------------", 0, 0)
                                     
               If iNum = 54 Then Call NEW_PAGE
               iNum = iNum + 1
               iRow = iRow + 1
               Call PreInv_AddNew(iRow, Space(140) & "TOTAL : ", 0, Format(Round(dTotalAmount, 3), "##,###,###,##0.00"))
                   
               If iNum = 54 Then Call NEW_PAGE
               iNum = iNum + 1
               iRow = iRow + 1
               Call PreInv_AddNew(iRow, Space(5) & "----------------------------------------------------------------------------------------------------" _
                                                 & "---------------------------------------------------------------------------------------------", 3, 0)
            End If
                
            bStart = False
                
            dGrandTotal = dGrandTotal + dTotalAmount
            dTotalAmount = 0
               
            iCustId = dsBILLING.fields("CUSTOMER_ID").Value
            'iLrNum = dsBILLING.FIELDS("LR_NUM").Value
            lInvoiceNum = fnMaxInvNum
            sInvNum = CStr(lInvoiceNum)
            sBillingType = dsBILLING.fields("BILLING_TYPE").Value
            
             
            iNum = 0
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(227) & CStr(lInvoiceNum), 1, 0)
                
            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, "", 0, 0)
               
            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(227) & Format(Now, "MM/DD/YYYY"), 0, 0)
            sInvDt = Format(Now, "MM/DD/YYYY")
            
            For iLine = 1 To 6
               If iNum = 54 Then Call NEW_PAGE
               iNum = iNum + 1
               iRow = iRow + 1
               Call PreInv_AddNew(iRow, "", 0, 0)
            Next iLine
                    
            If Not IsNull(dsBILLING.fields("CARE_OF")) Then
               If (dsBILLING.fields("CARE_OF").Value = "1") Or (dsBILLING.fields("CARE_OF").Value = "Y") Then
                  If iNum = 54 Then Call NEW_PAGE
                  iNum = iNum + 1
                  iRow = iRow + 1
                  Call PreInv_AddNew(iRow, Space(34) & lblVessel, 0, 0)
               
                  If iNum = 54 Then Call NEW_PAGE
                  iNum = iNum + 1
                  iRow = iRow + 1
                  Call PreInv_AddNew(iRow, Space(34) & "C/O " & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
               Else
                  If iNum = 54 Then Call NEW_PAGE
                  iNum = iNum + 1
                  iRow = iRow + 1
                  Call PreInv_AddNew(iRow, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
               End If
            Else
               If iNum = 54 Then Call NEW_PAGE
               iNum = iNum + 1
               iRow = iRow + 1
               Call PreInv_AddNew(iRow, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_NAME").Value, 0, 0)
            End If
                
            If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value) Then
               If iNum = 54 Then Call NEW_PAGE
               iNum = iNum + 1
               iRow = iRow + 1
               Call PreInv_AddNew(iRow, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS1").Value, 0, 0)
            End If
                  
            If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value) Then
               If iNum = 54 Then Call NEW_PAGE
               iNum = iNum + 1
               iRow = iRow + 1
               Call PreInv_AddNew(iRow, Space(34) & dsCUSTOMER_PROFILE.fields("CUSTOMER_ADDRESS2").Value, 0, 0)
            End If
                
            If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value) Then
               sCityStateZip = dsCUSTOMER_PROFILE.fields("CUSTOMER_City").Value
            End If
         
            If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value) Then
               sCityStateZip = sCityStateZip & ", " & dsCUSTOMER_PROFILE.fields("CUSTOMER_State").Value
            End If
                
            If Not IsNull(dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value) Then
               sCityStateZip = sCityStateZip & " - " & dsCUSTOMER_PROFILE.fields("CUSTOMER_Zip").Value
            End If
        
            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(34) & sCityStateZip, 0, 0)
               
            If dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value <> "US" Then
               If iNum = 54 Then Call NEW_PAGE
               iNum = iNum + 1
               iRow = iRow + 1
               Call PreInv_AddNew(iRow, Space(34) & fnCountryName(dsCUSTOMER_PROFILE.fields("COUNTRY_CODE").Value), 0, 0)
            End If
            
            For iLine = 1 To 6
               If iNum = 54 Then Call NEW_PAGE
               iNum = iNum + 1
               iRow = iRow + 1
               Call PreInv_AddNew(iRow, "", 0, 0)
            Next iLine
            
            SqlStmt = " SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE='" & dsBILLING.fields("SERVICE_CODE").Value & "'"
            Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
            dsBILLING.Edit
            dsBILLING.fields("INVOICE_NUM").Value = lInvoiceNum
            dsBILLING.fields("SERVICE_STATUS").Value = "INVOICED"
            dsBILLING.fields("INVOICE_DATE").Value = Format(Now, "mm/dd/yyyy")
            dsBILLING.Update
                
            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            
            Call PreInv_AddNew(iRow, Space(6) & Format(dsBILLING.fields("SERVICE_DATE").Value, "MM/DD/YY") _
                              & Space(12) & dsBILLING.fields("SERVICE_DESCRIPTION").Value & "  ,  " & Extract_Name(dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value), 0, _
                              Format(dsBILLING.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
            
            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            If Not IsNull(dsBILLING.fields("LOT_NUM").Value) Or dsBILLING.fields("LOT_NUM").Value <> "" Then
                If dsBILLING.fields("LOT_NUM").Value <> 0 Or Not IsNull(dsBILLING.fields("LOT_NUM").Value) Then
                    sPltCaseWt = "Total pallets : " & dsBILLING.fields("LOT_NUM").Value & ";"
                End If
                If dsBILLING.fields("THRESHOLD_QTY").Value <> 0 Then
                    sPltCaseWt = sPltCaseWt & "Total Cases : " & dsBILLING.fields("THRESHOLD_QTY").Value & ";"
                End If
                If dsBILLING.fields("SERVICE_QTY").Value <> 0 Then
                    sPltCaseWt = sPltCaseWt & "Weight : " & dsBILLING.fields("SERVICE_QTY").Value
                End If
            
                Call PreInv_AddNew(iRow, Space(36) & Trim(sPltCaseWt) & _
                                  "@ " & dsBILLING.fields("service_rate").Value & " / " & dsBILLING.fields("SERVICE_UNIT").Value, 0, 0)
            Else
                If dsBILLING.fields("THRESHOLD_QTY").Value <> 0 Then
                    sPltCaseWt = sPltCaseWt & "Total Pcs : " & dsBILLING.fields("THRESHOLD_QTY").Value & ";"
                End If
                If dsBILLING.fields("SERVICE_QTY").Value <> 0 Then
                    sPltCaseWt = sPltCaseWt & "Weight : " & dsBILLING.fields("SERVICE_QTY").Value
                End If
                
                Call PreInv_AddNew(iRow, Space(36) & sPltCaseWt & _
                                  "@ " & dsBILLING.fields("service_rate").Value & " / " & dsBILLING.fields("SERVICE_UNIT").Value, 0, 0)
            End If
                      
            dTotalAmount = dTotalAmount + dsBILLING.fields("service_amount").Value
            
         Else
            DoEvents
            
            dTotalAmount = dTotalAmount + dsBILLING.fields("SERVICE_AMOUNT").Value
          
            SqlStmt = " SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE='" & dsBILLING.fields("SERVICE_CODE").Value & "'"
            Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
            dsBILLING.Edit
            dsBILLING.fields("INVOICE_NUM").Value = lInvoiceNum
            dsBILLING.fields("SERVICE_STATUS").Value = "INVOICED"
            dsBILLING.fields("INVOICE_DATE").Value = Format(Now, "mm/dd/yyyy")
            dsBILLING.Update
            
            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            Call PreInv_AddNew(iRow, Space(6) & Format(dsBILLING.fields("SERVICE_DATE").Value, "MM/DD/YY") _
                              & Space(12) & dsBILLING.fields("SERVICE_DESCRIPTION").Value & "  ,  " & Extract_Name(dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value), 0, _
                              Format(dsBILLING.fields("SERVICE_AMOUNT").Value, "##,###,###,##0.00"))
            
            If iNum = 54 Then Call NEW_PAGE
            iNum = iNum + 1
            iRow = iRow + 1
            If Not IsNull(dsBILLING.fields("LOT_NUM").Value) Or dsBILLING.fields("LOT_NUM").Value <> "" Then
                If dsBILLING.fields("LOT_NUM").Value <> 0 Or Not IsNull(dsBILLING.fields("LOT_NUM").Value) Then
                    sPltCaseWt = "Total pallets : " & dsBILLING.fields("LOT_NUM").Value & ";"
                End If
                If dsBILLING.fields("THRESHOLD_QTY").Value <> 0 Or Not IsNull(dsBILLING.fields("THRESHOLD_QTY").Value) Then
                    sPltCaseWt = sPltCaseWt & "Total Cases : " & dsBILLING.fields("THRESHOLD_QTY").Value & ";"
                End If
                If dsBILLING.fields("SERVICE_QTY").Value <> 0 Or Not IsNull(dsBILLING.fields("SERVICE_QTY").Value) Then
                    sPltCaseWt = sPltCaseWt & "Weight : " & dsBILLING.fields("SERVICE_QTY").Value
                End If
            
                Call PreInv_AddNew(iRow, Space(36) & Trim(sPltCaseWt) & _
                                  "@ " & dsBILLING.fields("service_rate").Value & " / " & dsBILLING.fields("SERVICE_UNIT").Value, 0, 0)
            Else
                If dsBILLING.fields("THRESHOLD_QTY").Value <> 0 Or Not IsNull(dsBILLING.fields("THRESHOLD_QTY").Value) Then
                    sPltCaseWt = sPltCaseWt & "Total Pcs : " & dsBILLING.fields("THRESHOLD_QTY").Value & ";"
                End If
                If dsBILLING.fields("SERVICE_QTY").Value <> 0 Or Not IsNull(dsBILLING.fields("SERVICE_QTY").Value) Then
                    sPltCaseWt = sPltCaseWt & "Weight : " & dsBILLING.fields("SERVICE_QTY").Value
                End If
                
                Call PreInv_AddNew(iRow, Space(36) & sPltCaseWt & _
                                  "@ " & dsBILLING.fields("service_rate").Value & " / " & dsBILLING.fields("SERVICE_UNIT").Value, 0, 0)
            End If
         
         End If
         dsBILLING.MoveNext
      Next iRec
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox "Error " & OraDatabase.LastServerErrText & " occured." & vbCrLf & _
                 " Not able to process ADVANCE BILLING !", vbExclamation, "ADVANCE BILLING"
            OraSession.Rollback
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
            
        MsgBox "No Records Found", vbInformation + vbExclamation, "ADVANCE BILLING"
        OraSession.Rollback
        Exit Sub
    End If
    
    For iLine = 1 To 2
        If iNum = 54 Then Call NEW_PAGE
        iNum = iNum + 1
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, "", 0, 0)
    Next iLine
                
    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, Space(5) & "-------------------------------------------" _
    & "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
                                    
    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, Space(140) & "TOTAL : ", 0, Format(Round(dTotalAmount, 3), "##,###,###,##0.00"))
                   
    If iNum = 54 Then Call NEW_PAGE
    iNum = iNum + 1
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, Space(5) & "-------------------------------------------" _
    & "------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0)
    
    dGrandTotal = dGrandTotal + dTotalAmount
    
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, "", 2, 0)
    For i = 1 To 34
        iRow = iRow + 1
        Call PreInv_AddNew(iRow, "", 0, 0)
    Next i
    
    iRow = iRow + 1
    Call PreInv_AddNew(iRow, Space(45) & "GRAND TOTAL OF ADVANCE TRUCK LOADING INVOICES  :  " & Format(Round(dGrandTotal, 3), "##,###,###,##0.00"), 0, 0)
    
    
    If OraDatabase.LastServerErr = 0 Then
      OraSession.CommitTrans
        crw1.ReportFileName = App.Path & "\TrkBILL.rpt"
         crw1.Connect = "DSN = BNI1;UID = sag_owner;PWD = sag"
         crw1.Action = 1
    
    Else
        MsgBox OraDatabase.LastServerErrText, vbCritical, sMsg
        OraSession.Rollback
        OraDatabase.LastServerErrReset
    End If
    
End Sub
Sub AdBilling()
   AdBilling_success = True
   SqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & iLrNum & "' AND BILLING_TYPE = 'ADTRKLOAD' " & _
             "And SERVICE_STATUS <> 'DELETED'"
   Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
   
   If dsBILLING.RecordCount > 0 Then
      If dsBILLING.fields("SERVICE_STATUS").Value = "INVOICED" Then
         MsgBox "Advance Truck Loading Invoices have already been generated for this vessel.", vbInformation + vbExclamation, "ADVANCE TRUCK LOADING"
      ElseIf dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE" Then
         'don't save the records into the billing table
         MsgBox "Advance Truck Loading Prebills have already been generated for this vessel." & vbCrLf & _
                "You may go to the new BNI system to edit the prebills", vbInformation + vbExclamation, "ADVANCE TRUCK LOADING"
      End If
      
      Exit Sub
   Else
      If OraDatabase.LastServerErr <> 0 Then
         MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
         OraSession.Rollback
         Unload Me
      End If
        
      SqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
      Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
      If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
         If IsNull(dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value) Then
            lBillNum = 1
         Else
            lBillNum = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
         End If
      Else
         lBillNum = 1
      End If
      
      If lStartBillNum = 0 Then
        lStartBillNum = lBillNum
      End If
      
      grdData.MoveFirst
      For iRec = 1 To grdData.Rows
         If Val(grdData.Columns(10).Value) > 0.0000001 Then
           
            dsBILLING.AddNew
            dsBILLING.fields("CUSTOMER_ID").Value = grdData.Columns(11).Value
            dsBILLING.fields("SERVICE_CODE").Value = Extract_Code(grdData.Columns(2).Value)
            dsBILLING.fields("LOT_NUM").Value = grdData.Columns(5).Value   ' STORES NO. OF PALLETS
            dsBILLING.fields("BILLING_NUM").Value = lBillNum
            dsBILLING.fields("EMPLOYEE_ID").Value = 4
            dsBILLING.fields("SERVICE_START").Value = grdData.Columns(4).Value
            dsBILLING.fields("SERVICE_STOP").Value = grdData.Columns(4).Value
            dsBILLING.fields("SERVICE_AMOUNT").Value = grdData.Columns(10).Value
            dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
            dsBILLING.fields("SERVICE_DESCRIPTION").Value = grdData.Columns(13).Value
            dsBILLING.fields("LR_NUM").Value = iLrNum
            dsBILLING.fields("ARRIVAL_NUM").Value = 1
            dsBILLING.fields("COMMODITY_CODE").Value = grdData.Columns(1).Value
            dsBILLING.fields("INVOICE_NUM").Value = 0
            dsBILLING.fields("SERVICE_DATE").Value = grdData.Columns(4).Value
            dsBILLING.fields("SERVICE_QTY").Value = grdData.Columns(7).Value 'STORES PALLET WT.
            dsBILLING.fields("SERVICE_NUM").Value = grdData.Columns(3).Value
            dsBILLING.fields("THRESHOLD_QTY").Value = grdData.Columns(6).Value 'STORES TOTAL CASES
            dsBILLING.fields("LEASE_NUM").Value = 0
            dsBILLING.fields("SERVICE_UNIT").Value = grdData.Columns(9).Value
            dsBILLING.fields("SERVICE_RATE").Value = grdData.Columns(8).Value
            dsBILLING.fields("LABOR_RATE_TYPE").Value = ""
            dsBILLING.fields("LABOR_TYPE").Value = ""
            dsBILLING.fields("PAGE_NUM").Value = 1
            dsBILLING.fields("CARE_OF").Value = "Y"
            dsBILLING.fields("BILLING_TYPE").Value = "ADTRKLOAD"
            
            'Added for Asset Coding 06.12.2001 LJG
            'Ignore box # for WING C1 - C8, D1 - D6, E1 - E8, treat them as WING C, D or E  -- LFW, 11/18/03
            If InStr(Trim$(grdData.Columns(12).Value), "WING C") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING C'"
            ElseIf InStr(Trim$(grdData.Columns(12).Value), "WING D") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING D'"
            ElseIf InStr(Trim$(grdData.Columns(12).Value), "WING E") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING E'"
            Else
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = '" & Trim$(grdData.Columns(12).Value) & "'"
            End If
                        
            Set dsAssetProfile = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
            If dsAssetProfile.RecordCount = 0 Then
                MsgBox "Please Add ASSET_CODE for the Service Location Code '" & Trim$(grdData.Columns(12).Value) & "'  to ASSET_PROFILE.", vbInformation, "Save"
            Else
                Acode = dsAssetProfile.fields("ASSET_CODE").Value
                sLoc = grdData.Columns(12).Value
                Call AssetCode_Validate(Acode, sLoc)
                If valid = False Then
                   grdData.Columns(12).Selected = True
                   AdBilling_success = False
                   Exit Sub
                End If
               
                dsBILLING.fields("ASSET_CODE").Value = dsAssetProfile.fields("ASSET_CODE").Value
            End If
            
            dsBILLING.Update
       
            If OraDatabase.LastServerErr <> 0 Then
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
                OraSession.Rollback
                Unload Me
            End If
       
            lBillNum = lBillNum + 1
            grdData.MoveNext
         End If
      Next iRec
      
      lEndBillNum = lBillNum - 1
   End If
   
End Sub
Function Extract_Code(service As String) As Integer
    
    Dim iPos As Integer
    
    iPos = InStr(1, service, "-")
    If iPos > 0 Then
        Extract_Code = Trim(Mid(service, 1, iPos - 1))
    Else
        Extract_Code = service
    End If

End Function
Sub BHaulBilling()
   BckBilling_success = True
   SqlStmt = "SELECT * FROM BILLING WHERE LR_NUM='" & iLrNum & "' AND BILLING_TYPE='BACKHAUL' " & _
             "And SERVICE_STATUS <> 'DELETED'"
   Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
   
   If OraDatabase.LastServerErr = 0 And dsBILLING.RecordCount > 0 Then
      If dsBILLING.fields("SERVICE_STATUS").Value = "INVOICED" Then
         MsgBox "BACKHAUL invoices have already been generated for this vessel.", vbInformation + vbExclamation, "ADVANCE BILLING"
      ElseIf dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE" Then
         'don't save the records into the billing table
         MsgBox "BACKHAUL prebills have already been generated for this vessel." & vbCrLf & _
                "You may go to the new BNI system to edit the prebills", vbInformation + vbExclamation, "ADVANCE BILLING"
      End If
      
      Exit Sub
   Else
      If OraDatabase.LastServerErr <> 0 Then
         MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
         OraSession.Rollback
         Unload Me
      End If
        
      SqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
      Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
      If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
         If IsNull(dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value) Then
            lBillNum = 1
         Else
            lBillNum = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
         End If
      Else
         lBillNum = 1
      End If
       
      If lStartBillNum = 0 Then
        lStartBillNum = lBillNum
      End If
             
      grddata2.MoveFirst
      For iRec = 1 To grddata2.Rows
         If Val(grddata2.Columns(10).Value) > 0.0000001 Then
                
            dsBILLING.AddNew
            dsBILLING.fields("CUSTOMER_ID").Value = grddata2.Columns(11).Value
            dsBILLING.fields("SERVICE_CODE").Value = Extract_Code(grddata2.Columns(2).Value)
            dsBILLING.fields("LOT_NUM").Value = grddata2.Columns(5).Value   ' STORES NO. OF PALLETS
            dsBILLING.fields("BILLING_NUM").Value = lBillNum
            dsBILLING.fields("EMPLOYEE_ID").Value = 4
            dsBILLING.fields("SERVICE_START").Value = grddata2.Columns(4).Value
            dsBILLING.fields("SERVICE_STOP").Value = grddata2.Columns(4).Value
            dsBILLING.fields("SERVICE_AMOUNT").Value = grddata2.Columns(10).Value
            dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
            dsBILLING.fields("SERVICE_DESCRIPTION").Value = grddata2.Columns(13).Value
            dsBILLING.fields("LR_NUM").Value = iLrNum
            dsBILLING.fields("ARRIVAL_NUM").Value = 1
            dsBILLING.fields("COMMODITY_CODE").Value = grddata2.Columns(1).Value
            dsBILLING.fields("INVOICE_NUM").Value = 0
            dsBILLING.fields("SERVICE_DATE").Value = grddata2.Columns(4).Value
            dsBILLING.fields("SERVICE_QTY").Value = grddata2.Columns(7).Value    'STORES PALLET WT.
            dsBILLING.fields("SERVICE_NUM").Value = grddata2.Columns(3).Value
            dsBILLING.fields("THRESHOLD_QTY").Value = grddata2.Columns(6).Value  'STORES TOTAL CASES
            dsBILLING.fields("LEASE_NUM").Value = 0
            dsBILLING.fields("SERVICE_UNIT").Value = grddata2.Columns(9).Value
            dsBILLING.fields("SERVICE_RATE").Value = grddata2.Columns(8).Value
            dsBILLING.fields("LABOR_RATE_TYPE").Value = ""
            dsBILLING.fields("LABOR_TYPE").Value = ""
            dsBILLING.fields("PAGE_NUM").Value = 1
            dsBILLING.fields("CARE_OF").Value = "Y"
            dsBILLING.fields("BILLING_TYPE").Value = "BACKHAUL"
            
            'Added for Asset Coding 06.12.2001 LJG
            'Ignore box # for WING C1 - C8, D1 - D6, E1 - E8, treat them as WING C, D or E  -- LFW, 11/18/03
            If InStr(Trim$(grddata2.Columns(12).Value), "WING C") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING C'"
            ElseIf InStr(Trim$(grddata2.Columns(12).Value), "WING D") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING D'"
            ElseIf InStr(Trim$(grddata2.Columns(12).Value), "WING E") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING E'"
            Else
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = '" & Trim$(grddata2.Columns(12).Value) & "'"
            End If
            
            Set dsAssetProfile = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
            If dsAssetProfile.RecordCount = 0 Then
                MsgBox "Please Add ASSET_CODE for the Service Location Code '" & Trim$(grddata2.Columns(12).Value) & "'  to ASSET_PROFILE.", vbInformation, "Save"
            Else
                Acode = dsAssetProfile.fields("ASSET_CODE").Value
                sLoc = grddata2.Columns(12).Value
                Call AssetCode_Validate(Acode, sLoc)
                If valid = False Then
                   grddata2.Columns(12).Selected = True
                   BckBilling_success = False
                   Exit Sub
                End If

                dsBILLING.fields("ASSET_CODE").Value = dsAssetProfile.fields("ASSET_CODE").Value
            End If
            
            dsBILLING.Update
       
            If OraDatabase.LastServerErr <> 0 Then
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
                OraSession.Rollback
                Unload Me
            End If
       
            lBillNum = lBillNum + 1
            grddata2.MoveNext
         End If
      Next iRec
      
      lEndBillNum = lBillNum - 1
   End If
   
End Sub
Sub TerminalBilling()
  TerBilling_success = True
   SqlStmt = "SELECT * FROM BILLING WHERE LR_NUM='" & iLrNum & "' AND BILLING_TYPE='TERMSER' " & _
             "And SERVICE_STATUS <> 'DELETED'"
   Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
   If OraDatabase.LastServerErr = 0 And dsBILLING.RecordCount > 0 Then
        
      If dsBILLING.fields("SERVICE_STATUS").Value = "INVOICED" Then
         MsgBox "TERMINAL SERVICE Invoices have already being generated for this vessel.", vbInformation + vbExclamation, "ADVANCE BILLING"
      ElseIf dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE" Then
         'don't save the records into the billing table
         MsgBox "TERMINAL SERVICE Prebills have already being generated for this vessel." & vbCrLf & _
                "You may go to the new BNI system to edit the prebills", vbInformation + vbExclamation, "ADVANCE BILLING"
      End If
      
      Exit Sub
   Else
      If OraDatabase.LastServerErr <> 0 Then
         MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
         OraSession.Rollback
         Unload Me
      End If
        
      SqlStmt = "SELECT MAX(BILLING_NUM) FROM BILLING"
      Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
      If OraDatabase.LastServerErr = 0 And dsBILLING_MAX.RecordCount > 0 Then
         If IsNull(dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value) Then
            lBillNum = 1
         Else
            lBillNum = dsBILLING_MAX.fields("MAX(BILLING_NUM)").Value + 1
         End If
      Else
         lBillNum = 1
      End If
       
      If lStartBillNum = 0 Then
        lStartBillNum = lBillNum
      End If
 
      grdData3.MoveFirst
      For iRec = 1 To grdData3.Rows
         If Val(grdData3.Columns(10).Value) > 0.0000001 Then
                
            dsBILLING.AddNew
            dsBILLING.fields("CUSTOMER_ID").Value = grdData3.Columns(11).Value
            dsBILLING.fields("SERVICE_CODE").Value = Extract_Code(grdData3.Columns(2).Value)
            dsBILLING.fields("LOT_NUM").Value = grdData3.Columns(5).Value   ' STORES NO. OF PALLETS
            dsBILLING.fields("BILLING_NUM").Value = lBillNum
            dsBILLING.fields("EMPLOYEE_ID").Value = 4
            dsBILLING.fields("SERVICE_START").Value = grdData3.Columns(4).Value
            dsBILLING.fields("SERVICE_STOP").Value = grdData3.Columns(4).Value
            dsBILLING.fields("SERVICE_AMOUNT").Value = grdData3.Columns(10).Value
            dsBILLING.fields("SERVICE_STATUS").Value = "PREINVOICE"
            dsBILLING.fields("SERVICE_DESCRIPTION").Value = grdData3.Columns(13).Value
            dsBILLING.fields("LR_NUM").Value = iLrNum
            dsBILLING.fields("ARRIVAL_NUM").Value = 1
            dsBILLING.fields("COMMODITY_CODE").Value = grdData3.Columns(1).Value
            dsBILLING.fields("INVOICE_NUM").Value = 0
            dsBILLING.fields("SERVICE_DATE").Value = grdData3.Columns(4).Value
            dsBILLING.fields("SERVICE_QTY").Value = grdData3.Columns(7).Value 'STORES PALLET WT.
            dsBILLING.fields("SERVICE_NUM").Value = grdData3.Columns(3).Value
            dsBILLING.fields("THRESHOLD_QTY").Value = grdData3.Columns(6).Value 'STORES TOTAL CASES
            dsBILLING.fields("LEASE_NUM").Value = 0
            dsBILLING.fields("SERVICE_UNIT").Value = grdData3.Columns(9).Value
            dsBILLING.fields("SERVICE_RATE").Value = grdData3.Columns(8).Value
            dsBILLING.fields("LABOR_RATE_TYPE").Value = ""
            dsBILLING.fields("LABOR_TYPE").Value = ""
            dsBILLING.fields("PAGE_NUM").Value = 1
            dsBILLING.fields("CARE_OF").Value = "Y"
            dsBILLING.fields("BILLING_TYPE").Value = "TERMSER"
            
            'Added for Asset Coding 06.12.2001 LJG
            'Ignore box # for WING C1 - C8, D1 - D6, E1 - E8, treat them as WING C, D or E  -- LFW, 11/18/03
            If InStr(Trim$(grdData3.Columns(12).Value), "WING C") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING C'"
            ElseIf InStr(Trim$(grdData3.Columns(12).Value), "WING D") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING D'"
            ElseIf InStr(Trim$(grdData3.Columns(12).Value), "WING E") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING E'"
            Else
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = '" & Trim$(grdData3.Columns(12).Value) & "'"
            End If
            
            Set dsAssetProfile = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
            
            If dsAssetProfile.RecordCount = 0 Then
                MsgBox "Please Add ASSET_CODE for the Service Location Code '" & Trim$(grdData3.Columns(12).Value) & "' to ASSET_PROFILE.", vbInformation, "Save"
            Else
                Acode = dsAssetProfile.fields("ASSET_CODE").Value
                sLoc = grdData3.Columns(12).Value
                Call AssetCode_Validate(Acode, sLoc)
                If valid = False Then
                   grdData3.Columns(12).Selected = True
                   BckBilling_success = False
                   Exit Sub
                End If

               dsBILLING.fields("ASSET_CODE").Value = dsAssetProfile.fields("ASSET_CODE").Value
            End If
            
            dsBILLING.Update
       
            If OraDatabase.LastServerErr <> 0 Then
                MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
                OraSession.Rollback
                Unload Me
            End If
            
            lBillNum = lBillNum + 1
            grdData3.MoveNext
         End If
      Next iRec
      
      lEndBillNum = lBillNum - 1
   End If
   
End Sub
Function Extract_Name(serv As String) As String
    
    Dim iPos As Integer
    iPos = InStr(1, serv, "-")
    If iPos > 0 Then
        Extract_Name = Mid(serv, iPos + 1)
    Else
        Extract_Name = serv
    End If
    
End Function

Private Sub cmdPreBill_Click()

    Dim iR As Integer
    Dim iC As Integer
    
    'Initialize the variables
    lStartBillNum = 0
    lEndBillNum = 0
    
   
    On Error GoTo errHandler
    
    OraSession.BeginTrans
    
    grdData.MoveFirst
    For iR = 0 To grdData.Rows - 1
        For iC = 0 To grdData.Cols - 1
            If iC = 0 And Trim$(grdData.Columns(iC).Value) <> "" And IsNumeric(grdData.Columns(iC).Value) Then
              Call Customer_Validate(grdData.Columns(iC).Value)
              If valid = False Then
                   grdData.Columns(iC).Selected = True
                   OraSession.Rollback
                   Exit Sub
              End If
            End If
            
            If iC = 1 And Trim$(grdData.Columns(iC).Value) <> "" And IsNumeric(grdData.Columns(iC).Value) Then
             If Len(Trim$(grdData.Columns(iC).Value)) > 4 Or Len(Trim$(grdData.Columns(iC).Value)) < 4 Then
                MsgBox "Invalid Commodity Code. Commodity Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grdData.Columns(iC).Selected = True
                OraSession.Rollback
                Exit Sub
             Else
              Call CommodityCode_Validate(grdData.Columns(iC).Value)
              If valid = False Then
                   grdData.Columns(iC).Selected = True
                   OraSession.Rollback
                   Exit Sub
              End If
             End If
            End If
            
            If iC = 2 And Trim$(grdData.Columns(iC).Value) <> "" And IsNumeric(grdData.Columns(iC).Value) Then
             If Len(Trim$(grdData.Columns(iC).Value)) > 4 Or Len(Trim$(grdData.Columns(iC).Value)) < 4 Then
                MsgBox "Invalid Service Code. Service Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grdData.Columns(iC).Selected = True
                OraSession.Rollback
                Exit Sub
             Else
              Call ServiceCode_Validate(grdData.Columns(iC).Value)
              If valid = False Then
                   grdData.Columns(iC).Selected = True
                   OraSession.Rollback
                   Exit Sub
              End If
             End If
            End If
            
            If Not (iC = 3 Or iC = 5 Or iC = 12 Or iC = 13) Then
                If Trim(grdData.Columns(iC).Value) = "" Then
                    MsgBox "Required values are missing for the Advance Truck Loading.", vbInformation + vbExclamation, "ADVANCE BILLING"
                    OraSession.Rollback
                    Exit Sub
                End If
            End If
            
        Next iC
        grdData.MoveNext
    Next iR
    Call AdBilling
    
      
    grddata2.MoveFirst
    For iR = 0 To grddata2.Rows - 1
        For iC = 0 To grddata2.Cols - 1
           If iC = 0 And Trim$(grddata2.Columns(iC).Value) <> "" And IsNumeric(grddata2.Columns(iC).Value) Then
              Call Customer_Validate(grddata2.Columns(iC).Value)
              If valid = False Then
                   grddata2.Columns(iC).Selected = True
                   OraSession.Rollback
                   Exit Sub
              End If
            End If
            
            If iC = 1 And Trim$(grddata2.Columns(iC).Value) <> "" And IsNumeric(grddata2.Columns(iC).Value) Then
             If Len(Trim$(grddata2.Columns(iC).Value)) > 4 Or Len(Trim$(grddata2.Columns(iC).Value)) < 4 Then
                MsgBox "Invalid Commodity Code. Commodity Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grddata2.Columns(iC).Selected = True
                OraSession.Rollback
                Exit Sub
             Else
              Call CommodityCode_Validate(grddata2.Columns(iC).Value)
              If valid = False Then
                   grddata2.Columns(iC).Selected = True
                   OraSession.Rollback
                   Exit Sub
              End If
             End If
            End If
            
            If iC = 2 And Trim$(grddata2.Columns(iC).Value) <> "" And IsNumeric(grddata2.Columns(iC).Value) Then
             If Len(Trim$(grddata2.Columns(iC).Value)) > 4 Or Len(Trim$(grddata2.Columns(iC).Value)) < 4 Then
                MsgBox "Invalid Service Code. Service Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grddata2.Columns(iC).Selected = True
                OraSession.Rollback
                Exit Sub
             Else
              Call ServiceCode_Validate(grddata2.Columns(iC).Value)
              If valid = False Then
                   grddata2.Columns(iC).Selected = True
                   OraSession.Rollback
                   Exit Sub
              End If
             End If
            End If
            
            If Not (iC = 3 Or iC = 5 Or iC = 12 Or iC = 13) Then
                If Trim(grddata2.Columns(iC).Value) = "" Then
                    MsgBox "Required values are missing for BACKHAUL.", vbInformation + vbExclamation, "BACKHAUL"
                    OraSession.Rollback
                    Exit Sub
                End If
            End If
             
        Next iC
        grddata2.MoveNext
    Next iR
     Call BHaulBilling
   
       
    grdData3.MoveFirst
    For iR = 0 To grdData3.Rows - 1
        For iC = 0 To grdData3.Cols - 1
           If iC = 0 And Trim$(grdData3.Columns(iC).Value) <> "" And IsNumeric(grdData3.Columns(iC).Value) Then
              Call Customer_Validate(grdData3.Columns(iC).Value)
              If valid = False Then
                   grdData3.Columns(iC).Selected = True
                   OraSession.Rollback
                   Exit Sub
              End If
            End If
            
            If iC = 1 And Trim$(grdData3.Columns(iC).Value) <> "" And IsNumeric(grdData3.Columns(iC).Value) Then
             If Len(Trim$(grdData3.Columns(iC).Value)) > 4 Or Len(Trim$(grdData3.Columns(iC).Value)) < 4 Then
                MsgBox "Invalid Commodity Code. Commodity Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grdData3.Columns(iC).Selected = True
                OraSession.Rollback
                Exit Sub
             Else
              Call CommodityCode_Validate(grdData3.Columns(iC).Value)
              If valid = False Then
                   grdData3.Columns(iC).Selected = True
                   OraSession.Rollback
                   Exit Sub
              End If
             End If
            End If
            
            If iC = 2 And Trim$(grdData3.Columns(iC).Value) <> "" And IsNumeric(grdData3.Columns(iC).Value) Then
             If Len(Trim$(grdData3.Columns(iC).Value)) > 4 Or Len(Trim$(grdData3.Columns(iC).Value)) < 4 Then
                MsgBox "Invalid Service Code. Service Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grdData3.Columns(iC).Selected = True
                OraSession.Rollback
                Exit Sub
             Else
              Call ServiceCode_Validate(grdData3.Columns(iC).Value)
              If valid = False Then
                   grdData3.Columns(iC).Selected = True
                   OraSession.Rollback
                   Exit Sub
               End If
             End If
            End If
            
            If Not (iC = 3 Or iC = 5 Or iC = 12 Or iC = 13) Then
                If Trim(grdData3.Columns(iC).Value) = "" Then
                    MsgBox "Required values are missing for TERMINAL SERVICES.", vbInformation + vbExclamation, "TERMINAL SERVICES"
                    OraSession.Rollback
                    Exit Sub
                End If
            End If
            
        Next iC
        grdData3.MoveNext
    Next iR
    Call TerminalBilling
    
    If AdBilling_success = False Or BckBilling_success = False Or TerBilling_success = False Then
          OraSession.Rollback
          Exit Sub
    End If

    
    'Processed Advance Vessel Billing, starting/ending billing #'s are set by the subroutines
    If lEndBillNum >= lStartBillNum Then
        Call AddNewInvDt("Advance Billing", CStr(lStartBillNum), CStr(lEndBillNum))
    End If
    
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        MsgBox "Advance Billing - Prebills Were Generated Successfully! " & vbCrLf & _
               "Please Go To The New BNI System To Print Prebills.", vbInformation, "Advance Billing"
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured." & vbCrLf & _
               "Unable to process ADVANCE BILLING prebills!", vbExclamation, "ADVANCE BILLS"
        OraSession.Rollback
        OraDatabase.LastServerErrReset
    End If
    
    Exit Sub

errHandler:
     
    If OraDatabase.LastServerErr = 0 Then
         MsgBox "Error occured. Unable to process ADVANCE BILLING prebills!", vbExclamation, "ADVANCE BILLS"
    Else
         MsgBox "Error " & OraDatabase.LastServerErrText & " occured." & vbCrLf & _
                "Unable to process ADVANCE BILLING prebills!", vbExclamation, "ADVANCE BILLS"
    End If
         
    OraSession.Rollback
    OraDatabase.LastServerErrReset

End Sub

Private Sub Command3_Click()

   Unload Me
   
End Sub
Private Sub Form_Load()

    Me.Left = (Screen.Width - Me.Width) / 2
    Me.Top = (Screen.Height - Me.Height) / 2
    
    lblVessel = Trim(frmVeslEnt.txtVesselName)
    iLrNum = Trim(frmVeslEnt.txtLRNum)
    
    grdData.RowHeight = 300
    grddata2.RowHeight = 300
    grdData3.RowHeight = 300
                
    Call Show_Record
    
End Sub
 Sub Show_Record()
 
    Dim dWt As Double
    Dim dQTY1 As Long
    Dim dQTY2 As Double
    Dim sUnit As String
    Dim dSerRate As Double
    Dim iSerCode As Integer
    Dim sDateDep As String
    Dim sService As String
    Dim jRec As Integer
    Dim lTotalCount As Long
    Dim lDisThreshold As Long
    Dim lQty As Long
    Dim bSecondLine As Boolean
    Dim dAmt As Double
    Dim dsSERVICE As Object
    Dim sLoc As String
    
    SqlStmt = "SELECT * FROM BILLING WHERE LR_NUM = '" & iLrNum & "' AND BILLING_TYPE IN ('ADTRKLOAD','BACKHAUL','TERMSER') " & _
              "AND SERVICE_STATUS <> 'DELETED'"
    Set dsBILLING = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsBILLING.RecordCount > 0 Then
        MsgBox "Advance Vessel Bills For Vessel " & iLrNum & " Have Already Been Generated!" & vbCrLf & _
               "Please Go To The New BNI System To View The Prebills or Invoices," & vbCrLf & _
               "Or Delete Existing Prebills and Process the Vessel Again.", vbInformation, "ADVANCE INVOICES"
        Exit Sub
    End If
            
    SqlStmt = "SELECT DATE_DEPARTED FROM VOYAGE WHERE LR_NUM = '" & iLrNum & "'"
    Set dsVOYAGE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsVOYAGE.RecordCount > 0 Then
        sDateDep = Format(dsVOYAGE.fields("DATE_DEPARTED").Value, "MM/DD/YYYY")
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
            OraDatabase.LastServerErrReset
            Unload Me
        End If
        
        MsgBox " SET DEPARTURE DATE FOR THIS VESSEL", vbInformation + vbExclamation, "DEPARTURE DATE"
        Unload Me
    End If
    
    SqlStmt = " SELECT SUM(QTY_EXPECTED) SUMQTY1, SUM(QTY2_EXPECTED) SUMQTY2,SUM(CARGO_WEIGHT) SUMWT ,COMMODITY_CODE,RECIPIENT_ID,CARGO_LOCATION" _
            & " FROM CARGO_MANIFEST WHERE LR_NUM = '" & iLrNum & "' " _
            & " GROUP BY RECIPIENT_ID, COMMODITY_CODE,CARGO_LOCATION ORDER BY RECIPIENT_ID,COMMODITY_CODE"
            
    Set dsCARGO_MANIFEST = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    dsCARGO_MANIFEST.MoveFirst
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RecordCount > 0 Then
           
        With dsCARGO_MANIFEST
            For iRec = 1 To .RecordCount
               
                dWt = .fields("SUMWT").Value
                
                'dQTY2 = .fields("SUMQTY2").Value
                   If IsNull(.fields("SUMQTY2").Value) Then
                    dQTY2 = 0
                   Else
                    dQTY2 = .fields("SUMQTY2").Value
                   End If

                dQTY1 = .fields("SUMQTY1").Value
                sLoc = .fields("CARGO_LOCATION").Value
                          
                SqlStmt = " SELECT DISTINCT SERVICE_CODE FROM TERMINAL_RATE WHERE " _
                       & " COMMODITY_CODE =  '" & .fields("COMMODITY_CODE").Value & "' ORDER BY SERVICE_CODE" '_
'                       & " AND (CUSTOMER_ID='" & .FIELDS("RECIPIENT_ID").Value & "' or CUSTOMER_ID is NULL)"
                        
                Set dsSERVICE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                dsSERVICE.MoveFirst
                If OraDatabase.LastServerErr = 0 And dsSERVICE.RecordCount > 0 Then
                    For jRec = 1 To dsSERVICE.RecordCount
                        
                        dSerRate = 0
                        SqlStmt = " SELECT * FROM TERMINAL_RATE WHERE " _
                                & " COMMODITY_CODE =  '" & .fields("COMMODITY_CODE").Value & "'" _
                                & " AND SERVICE_CODE ='" & dsSERVICE.fields("SERVICE_CODE").Value & "'" _
                                & " AND CUSTOMER_ID='" & .fields("RECIPIENT_ID").Value & "' AND LOCATION='" & sLoc & "'"
                        Set dsTERMINAL_RATES = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                        If dsTERMINAL_RATES.RecordCount = 0 Then
                             SqlStmt = " SELECT * FROM TERMINAL_RATE WHERE " _
                                     & " COMMODITY_CODE =  '" & .fields("COMMODITY_CODE").Value & "'" _
                                     & " AND SERVICE_CODE ='" & dsSERVICE.fields("SERVICE_CODE").Value & "'" _
                                     & " AND CUSTOMER_ID='" & .fields("RECIPIENT_ID").Value & "' AND LOCATION IS NULL"
                            
                            Set dsTERMINAL_RATES = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                            If dsTERMINAL_RATES.RecordCount = 0 Then
                                SqlStmt = " SELECT * FROM TERMINAL_RATE WHERE " _
                                        & " COMMODITY_CODE =  '" & .fields("COMMODITY_CODE").Value & "'" _
                                        & " AND SERVICE_CODE ='" & dsSERVICE.fields("SERVICE_CODE").Value & "'" _
                                        & " AND CUSTOMER_ID IS NULL AND LOCATION IS NULL "
                                        
                                Set dsTERMINAL_RATES = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                                If dsTERMINAL_RATES.RecordCount = 0 Then
                                    'MsgBox " No Rates found for commodity : " & .fields("COMMODITY_CODE").Value, vbInformation
                                    'Exit Sub
                                End If
                                'sLoc = ""
                            End If
                            'sLoc = ""
                        End If
                  
                        If Not IsNull(dsTERMINAL_RATES.fields("RATE").Value) Then
                            dSerRate = dsTERMINAL_RATES.fields("RATE").Value
                        End If
                        iSerCode = dsSERVICE.fields("SERVICE_CODE").Value
                        sUnit = "" & dsTERMINAL_RATES("UNIT").Value
                     
                        If sUnit = "CWT" Then
                            dAmt = Round(.fields("SUMWT").Value * dSerRate / 100, 2)
                        ElseIf sUnit = "TON" Or sUnit = "NT" Then
                            'dAmt = Round(dQTY1 * dSerRate / 2000, 2)dWt
                            dAmt = Round(dWt * dSerRate / 2000, 2)
                        ElseIf sUnit = "PLT" Then
                            dAmt = Round(dQTY1 * dSerRate, 2)
                        ElseIf sUnit = "CTN" Then
                            dAmt = Round(dQTY2 * dSerRate, 2)
                        End If
                  
                        SqlStmt = "SELECT SERVICE_NAME FROM SERVICE_CATEGORY WHERE SERVICE_CODE='" & iSerCode & "'"
                        Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
                        If OraDatabase.LastServerErr = 0 And dsSERVICE_CATEGORY.RecordCount > 0 Then
                            sService = dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value
                        End If
                        
                        If dSerRate <> 0 And dAmt <> 0 Then
                            If Val(Left(iSerCode, 2)) = 61 Then      'BACKHAUL
                                If Left(Trim(.fields("COMMODITY_CODE").Value), 1) = 3 Then
                                    grddata2.AddItem CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + _
                                                     CStr(.fields("COMMODITY_CODE").Value) + Chr(9) + _
                                                     sService + Chr(9) + CStr(iSerCode) + Chr(9) + _
                                                     sDateDep + Chr(9) + Chr(9) + _
                                                     CStr(dQTY2) + Chr(9) + CStr(dWt) + Chr(9) + _
                                                    CStr(dSerRate) + Chr(9) + sUnit + Chr(9) + _
                                                    CStr(dAmt) + Chr(9) + CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + sLoc
                                Else
                                    grddata2.AddItem CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + _
                                                     CStr(.fields("COMMODITY_CODE").Value) + Chr(9) + _
                                                     sService + Chr(9) + CStr(iSerCode) + Chr(9) + _
                                                     sDateDep + Chr(9) + CStr(dQTY1) + Chr(9) + _
                                                     CStr(dQTY2) + Chr(9) + CStr(dWt) + Chr(9) + _
                                                    CStr(dSerRate) + Chr(9) + sUnit + Chr(9) + _
                                                    CStr(dAmt) + Chr(9) + CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + sLoc
                                End If
                       
                            ElseIf Val(Left(iSerCode, 2)) = 62 Then      'ADVANCE TRUCK LOADING
                    
                                If sUnit = "CWT" Then       'IF UNIT IS CWT RATE=
                                
                                    If Left(Trim(.fields("COMMODITY_CODE").Value), 1) = 3 Then
                           
                                        grdData.AddItem CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + _
                                                        CStr(.fields("COMMODITY_CODE").Value) + Chr(9) + _
                                                        sService + Chr(9) + CStr(iSerCode) + Chr(9) + _
                                                        sDateDep + Chr(9) + Chr(9) + _
                                                        CStr(dQTY2) + Chr(9) + CStr(dWt) + Chr(9) + _
                                                        CStr(dSerRate) + Chr(9) + sUnit + Chr(9) + _
                                                        CStr(dAmt) + Chr(9) + CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + sLoc
                                    Else
                                        grdData.AddItem CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + _
                                                        CStr(.fields("COMMODITY_CODE").Value) + Chr(9) + _
                                                        sService + Chr(9) + CStr(iSerCode) + Chr(9) + _
                                                        sDateDep + Chr(9) + CStr(dQTY1) + Chr(9) + _
                                                        CStr(dQTY2) + Chr(9) + CStr(dWt) + Chr(9) + _
                                                        CStr(dSerRate) + Chr(9) + sUnit + Chr(9) + _
                                                        CStr(dAmt) + Chr(9) + CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + sLoc
                                    End If
                                Else
                                    grdData.AddItem CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + _
                                                    CStr(.fields("COMMODITY_CODE").Value) + Chr(9) + _
                                                    sService + Chr(9) + CStr(iSerCode) + Chr(9) + _
                                                    sDateDep + Chr(9) + CStr(dQTY1) + Chr(9) + _
                                                    CStr(dQTY2) + Chr(9) + CStr(dWt) + Chr(9) + _
                                                    CStr(dSerRate) + Chr(9) + sUnit + Chr(9) + _
                                                    CStr(dAmt) + Chr(9) + CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + sLoc
                                End If
                  
                            ElseIf Val(Left(iSerCode, 2)) = 65 Then        'TERMINAL
                        
                                If Left(Trim(.fields("COMMODITY_CODE").Value), 1) = 3 Then
                                    grdData3.AddItem CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + _
                                                     CStr(.fields("COMMODITY_CODE").Value) + Chr(9) + _
                                                     sService + Chr(9) + CStr(iSerCode) + Chr(9) + _
                                                     sDateDep + Chr(9) + Chr(9) + _
                                                     CStr(dQTY2) + Chr(9) + CStr(dWt) + Chr(9) + _
                                                     CStr(dSerRate) + Chr(9) + sUnit + Chr(9) + _
                                                    CStr(dAmt) + Chr(9) + CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + sLoc
                                Else
                                    grdData3.AddItem CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + _
                                                     CStr(.fields("COMMODITY_CODE").Value) + Chr(9) + _
                                                     sService + Chr(9) + CStr(iSerCode) + Chr(9) + _
                                                     sDateDep + Chr(9) + CStr(dQTY1) + Chr(9) + _
                                                     CStr(dQTY2) + Chr(9) + CStr(dWt) + Chr(9) + _
                                                     CStr(dSerRate) + Chr(9) + sUnit + Chr(9) + _
                                                     CStr(dAmt) + Chr(9) + CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + sLoc
                                End If
                                
                            ElseIf Val(Left(iSerCode, 2)) = 66 Then
                                If Left(Trim(.fields("COMMODITY_CODE").Value), 1) = 3 Then
                                    grdData3.AddItem CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + _
                                                     CStr(.fields("COMMODITY_CODE").Value) + Chr(9) + _
                                                     sService + Chr(9) + CStr(iSerCode) + Chr(9) + _
                                                     sDateDep + Chr(9) + Chr(9) + _
                                                     CStr(dQTY2) + Chr(9) + CStr(dWt) + Chr(9) + _
                                                     CStr(dSerRate) + Chr(9) + sUnit + Chr(9) + _
                                                    CStr(dAmt) + Chr(9) + CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + sLoc
                                Else
                                    grdData3.AddItem CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + _
                                                     CStr(.fields("COMMODITY_CODE").Value) + Chr(9) + _
                                                     sService + Chr(9) + CStr(iSerCode) + Chr(9) + _
                                                     sDateDep + Chr(9) + CStr(dQTY1) + Chr(9) + _
                                                     CStr(dQTY2) + Chr(9) + CStr(dWt) + Chr(9) + _
                                                     CStr(dSerRate) + Chr(9) + sUnit + Chr(9) + _
                                                     CStr(dAmt) + Chr(9) + CStr(.fields("RECIPIENT_ID").Value) + Chr(9) + sLoc
                                End If
                            
                            End If
                        End If
                        dsSERVICE.MoveNext
                    Next jRec
               End If
               .MoveNext
            Next iRec
        End With
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErrText, vbCritical, "ERROR"
            OraDatabase.LastServerErrReset
            Unload Me
        End If
        
        MsgBox "NO Records Found", vbInformation, "ADVANCE BILLING"
        
        'Unload Me
            
    End If
    
End Sub


Private Sub GrdData_AfterColUpdate(ByVal ColIndex As Integer)

    Select Case ColIndex
        Case 0 'CUSTOMER
           If Trim$(grdData.Columns(ColIndex).Value) <> "" And IsNumeric(grdData.Columns(ColIndex).Value) Then
               Call Customer_Validate(grdData.Columns(ColIndex).Value)
               If valid = False Then
                grdData.Columns(ColIndex).Value = ""
                Exit Sub
               End If
            End If
        Case 1   'COMMODITY
            If Trim$(grdData.Columns(ColIndex).Value) <> "" And IsNumeric(grdData.Columns(ColIndex).Value) Then
             If Len(Trim$(grdData.Columns(ColIndex).Value)) > 4 Or Len(Trim$(grdData.Columns(ColIndex).Value)) < 4 Then
                MsgBox "Invalid Commodity Code. Commodity Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grdData.Columns(ColIndex).Selected = True
                Exit Sub
             Else
               Call CommodityCode_Validate(grdData.Columns(ColIndex).Value)
               If valid = False Then
                grdData.Columns(ColIndex).Value = ""
                Exit Sub
               End If
             End If
            End If
        Case 2 'SERVICE
            If Trim$(grdData.Columns(ColIndex).Value) <> "" And IsNumeric(grdData.Columns(ColIndex).Value) Then
             If Len(Trim$(grdData.Columns(ColIndex).Value)) > 4 Or Len(Trim$(grdData.Columns(ColIndex).Value)) < 4 Then
                MsgBox "Invalid Service Code. Service Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grdData.Columns(ColIndex).Selected = True
                Exit Sub
             Else
              Call ServiceCode_Validate(grdData.Columns(ColIndex).Value)
               If valid = False Then
                grdData.Columns(ColIndex).Value = ""
                Exit Sub
               End If
             End If
            End If
        Case 12 'LOC
           If Trim$(grdData.Columns(ColIndex).Value) <> "" Then

            'Ignore box # for WING C1 - C8, D1 - D6, E1 - E8, treat them as WING C, D or E  -- LFW, 11/18/03
            If InStr(Trim$(grdData.Columns(ColIndex).Value), "WING C") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING C'"
            ElseIf InStr(Trim$(grdData.Columns(ColIndex).Value), "WING D") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING D'"
            ElseIf InStr(Trim$(grdData.Columns(ColIndex).Value), "WING E") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING E'"
            Else
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = '" & Trim$(grdData.Columns(ColIndex).Value) & "'"
            End If

            Set dsAssetProfile = OraDatabase.dbcreatedynaset(SqlStmt, 0&)

            If dsAssetProfile.RecordCount > 0 Then
                Acode = dsAssetProfile.fields("ASSET_CODE").Value
                sLoc = grdData.Columns(ColIndex).Value
                Call AssetCode_Validate(Acode, sLoc)
                  If valid = False Then
                    grdData.Columns(ColIndex).Value = ""
                    Exit Sub
                  End If
            End If
         End If
End Select
End Sub

Private Sub GrdData2_AfterColUpdate(ByVal ColIndex As Integer)

    Select Case ColIndex
        Case 0 'CUSTOMER
           If Trim$(grddata2.Columns(ColIndex).Value) <> "" And IsNumeric(grddata2.Columns(ColIndex).Value) Then
               Call Customer_Validate(grddata2.Columns(ColIndex).Value)
               If valid = False Then
                grddata2.Columns(ColIndex).Value = ""
                Exit Sub
               End If
            End If
        Case 1   'COMMODITY
            If Trim$(grddata2.Columns(ColIndex).Value) <> "" And IsNumeric(grddata2.Columns(ColIndex).Value) Then
             If Len(Trim$(grddata2.Columns(ColIndex).Value)) > 4 Or Len(Trim$(grddata2.Columns(ColIndex).Value)) < 4 Then
                MsgBox "Invalid Commodity Code. Commodity Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grddata2.Columns(ColIndex).Selected = True
                Exit Sub
             Else
               Call CommodityCode_Validate(grddata2.Columns(ColIndex).Value)
               If valid = False Then
                grddata2.Columns(ColIndex).Value = ""
                Exit Sub
               End If
             End If
            End If
        Case 2 'SERVICE
            If Trim$(grddata2.Columns(ColIndex).Value) <> "" And IsNumeric(grddata2.Columns(ColIndex).Value) Then
             If Len(Trim$(grddata2.Columns(ColIndex).Value)) > 4 Or Len(Trim$(grddata2.Columns(ColIndex).Value)) < 4 Then
                MsgBox "Invalid Service Code. Service Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grddata2.Columns(ColIndex).Selected = True
                Exit Sub
             Else
              Call ServiceCode_Validate(grddata2.Columns(ColIndex).Value)
               If valid = False Then
                grddata2.Columns(ColIndex).Value = ""
                Exit Sub
               End If
             End If
            End If
        Case 12 'LOC
            If Trim$(grddata2.Columns(ColIndex).Value) <> "" Then

            'Ignore box # for WING C1 - C8, D1 - D6, E1 - E8, treat them as WING C, D or E  -- LFW, 11/18/03
            If InStr(Trim$(grddata2.Columns(ColIndex).Value), "WING C") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING C'"
            ElseIf InStr(Trim$(grddata2.Columns(ColIndex).Value), "WING D") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING D'"
            ElseIf InStr(Trim$(grddata2.Columns(ColIndex).Value), "WING E") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING E'"
            Else
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = '" & Trim$(grddata2.Columns(ColIndex).Value) & "'"
            End If

            Set dsAssetProfile = OraDatabase.dbcreatedynaset(SqlStmt, 0&)

            If dsAssetProfile.RecordCount > 0 Then
                Acode = dsAssetProfile.fields("ASSET_CODE").Value
                sLoc = grddata2.Columns(ColIndex).Value
                Call AssetCode_Validate(Acode, sLoc)
                  If valid = False Then
                    grddata2.Columns(ColIndex).Value = ""
                    Exit Sub
                  End If
            End If
         End If
    End Select
End Sub

Private Sub GrdData3_AfterColUpdate(ByVal ColIndex As Integer)

    Select Case ColIndex
        Case 0 'CUSTOMER
           If Trim$(grdData3.Columns(ColIndex).Value) <> "" And IsNumeric(grdData3.Columns(ColIndex).Value) Then
               Call Customer_Validate(grdData3.Columns(ColIndex).Value)
               If valid = False Then
                grdData3.Columns(ColIndex).Value = ""
                Exit Sub
               End If
            End If
        Case 1   'COMMODITY
            If Trim$(grdData3.Columns(ColIndex).Value) <> "" And IsNumeric(grdData3.Columns(ColIndex).Value) Then
             If Len(Trim$(grdData3.Columns(ColIndex).Value)) > 4 Or Len(Trim$(grdData3.Columns(ColIndex).Value)) < 4 Then
                MsgBox "Invalid Commodity Code. Commodity Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grdData3.Columns(ColIndex).Selected = True
                Exit Sub
             Else
               Call CommodityCode_Validate(grdData3.Columns(ColIndex).Value)
               If valid = False Then
                grdData3.Columns(ColIndex).Value = ""
                Exit Sub
               End If
             End If
            End If
        Case 2 'SERVICE
            If Trim$(grdData3.Columns(ColIndex).Value) <> "" And IsNumeric(grdData3.Columns(ColIndex).Value) Then
             If Len(Trim$(grdData3.Columns(ColIndex).Value)) > 4 Or Len(Trim$(grdData3.Columns(ColIndex).Value)) < 4 Then
                MsgBox "Invalid Service Code. Service Code must be 4 characters long.", vbExclamation, "Advance Billing"
                grdData3.Columns(ColIndex).Selected = True
                Exit Sub
             Else
              Call ServiceCode_Validate(grdData3.Columns(ColIndex).Value)
               If valid = False Then
                grdData3.Columns(ColIndex).Value = ""
                Exit Sub
               End If
             End If
            End If
        Case 12 'LOC
            If Trim$(grdData3.Columns(ColIndex).Value) <> "" Then

            'Ignore box # for WING C1 - C8, D1 - D6, E1 - E8, treat them as WING C, D or E  -- LFW, 11/18/03
            If InStr(Trim$(grdData3.Columns(ColIndex).Value), "WING C") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING C'"
            ElseIf InStr(Trim$(grdData3.Columns(ColIndex).Value), "WING D") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING D'"
            ElseIf InStr(Trim$(grdData3.Columns(ColIndex).Value), "WING E") > 0 Then
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = 'WING E'"
            Else
                SqlStmt = "Select * from ASSET_PROFILE where SERVICE_LOCATION_CODE = '" & Trim$(grdData3.Columns(ColIndex).Value) & "'"
            End If

            Set dsAssetProfile = OraDatabase.dbcreatedynaset(SqlStmt, 0&)

            If dsAssetProfile.RecordCount > 0 Then
                Acode = dsAssetProfile.fields("ASSET_CODE").Value
                sLoc = grdData3.Columns(ColIndex).Value
                Call AssetCode_Validate(Acode, sLoc)
                  If valid = False Then
                    grdData3.Columns(ColIndex).Value = ""
                    Exit Sub
                  End If
            End If
         End If
    End Select
End Sub


Public Sub Customer_Validate(Cid)
 valid = True
 
 'Check Customer Code against PROD db
'  SqlStmt = "SELECT c.customer_id,a.address_id FROM ra_customers c, ra_addresses_all a where a.bill_to_flag in ('Y','P') and " _
'            & " c.status = 'A' and c.customer_id = a.customer_id and c.customer_number = " & Trim$(Cid)
'  Set dsRA_CUSTOMER = OraDatabase2.dbcreatedynaset(SqlStmt, 0&)

  SqlStmt = "SELECT CUSTOMER_ID FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & Trim$(Cid) & "'"
  Set dsRA_CUSTOMER = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
  If OraDatabase.LastServerErr = 0 And dsRA_CUSTOMER.RecordCount = 0 Then
     MsgBox "Customer Code [" & Trim$(Cid) & "] not valid.", vbExclamation, sMsg
     valid = False
  End If
End Sub

Public Sub CommodityCode_Validate(Ccode)
 valid = True
  
 'Check Commodity Code against PROD db
'  SqlStmt = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005837' and flex_value='" & Trim$(Ccode) & "' and enabled_flag='Y'"
'  Set dsFND_FLEX_VALUE = OraDatabase2.dbcreatedynaset(SqlStmt, 0&)
  SqlStmt = "SELECT * FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '" & Trim$(Ccode) & "'"
  Set dsFND_FLEX_VALUE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
  If OraDatabase.LastServerErr = 0 And dsFND_FLEX_VALUE.RecordCount = 0 Then
      MsgBox "Commodity Code [" & Trim$(Ccode) & "] not valid.", vbExclamation, sMsg
      valid = False
  End If
End Sub

Public Sub ServiceCode_Validate(Scode)
 valid = True
   
 'Check Service Code against PROD db
'  SqlStmt = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005836' and flex_value='" & Trim(Scode) & "' and enabled_flag='Y'"
'  Set dsFND_FLEX_VALUE = OraDatabase2.dbcreatedynaset(SqlStmt, 0&)
'    If OraDatabase2.LastServerErr = 0 And dsFND_FLEX_VALUE.RecordCount = 0 Then
'     MsgBox "Service Code [" & Trim$(Scode) & "] not found in Oracle.", vbExclamation, sMsg
'     valid = False
'    End If
End Sub

Public Sub AssetCode_Validate(Acode, sLoc)
 valid = True
 
 'Check asset Code against PROD db
'  SqlStmt = "SELECT * FROM fnd_flex_values where flex_value_set_id='1005838' and flex_value='" & Trim$(Acode) & "' and enabled_flag='Y'"
'  Set dsFND_FLEX_VALUE = OraDatabase2.dbcreatedynaset(SqlStmt, 0&)
'    If OraDatabase2.LastServerErr = 0 And dsFND_FLEX_VALUE.RecordCount = 0 Then
'     MsgBox "Asset Code [" & Trim$(Acode) & "] for the Service Location [" & Trim$(sLoc) & "] not found in Oracle.", vbExclamation, sMsg
'     valid = False
'    End If
End Sub


'Private Sub grddata2_Click()
'
'    If grdData.Col = 2 And grdData.Columns(2) = "" Then
'        grdData.Columns(2) = "6131-TRANS TO COLD STGE"
'    End If
'
'End Sub
