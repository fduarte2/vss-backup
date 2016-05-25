VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCTL.OCX"
Begin VB.Form frmDelHistory 
   AutoRedraw      =   -1  'True
   Caption         =   "DELETE HISTORY"
   ClientHeight    =   8580
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   14970
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
   ScaleHeight     =   8580
   ScaleWidth      =   14970
   StartUpPosition =   3  'Windows Default
   Begin MSComctlLib.StatusBar StatusBar1 
      Align           =   2  'Align Bottom
      Height          =   330
      Left            =   0
      TabIndex        =   1
      Top             =   8250
      Width           =   14970
      _ExtentX        =   26405
      _ExtentY        =   582
      Style           =   1
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   1
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
         EndProperty
      EndProperty
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   7695
      Left            =   165
      TabIndex        =   0
      Top             =   240
      Width           =   14655
      _Version        =   196616
      DataMode        =   2
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Col.Count       =   12
      AllowUpdate     =   0   'False
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowDragDrop   =   0   'False
      RowHeight       =   450
      ExtraHeight     =   318
      Columns.Count   =   12
      Columns(0).Width=   1852
      Columns(0).Caption=   "DELIVERY#"
      Columns(0).Name =   "DELIVERY#"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1323
      Columns(1).Caption=   "VESSEL"
      Columns(1).Name =   "VESSEL"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1244
      Columns(2).Caption=   "CUST"
      Columns(2).Name =   "OWNER"
      Columns(2).Alignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1429
      Columns(3).Caption=   "COMM"
      Columns(3).Name =   "COMMODITY"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   2487
      Columns(4).Caption=   "BOL"
      Columns(4).Name =   "BOL"
      Columns(4).CaptionAlignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   5662
      Columns(5).Caption=   "MARK"
      Columns(5).Name =   "MARK"
      Columns(5).CaptionAlignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   1138
      Columns(6).Caption=   "QTY1"
      Columns(6).Name =   "QTY1"
      Columns(6).Alignment=   1
      Columns(6).CaptionAlignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1138
      Columns(7).Caption=   "QTY2"
      Columns(7).Name =   "QTY2"
      Columns(7).Alignment=   1
      Columns(7).CaptionAlignment=   2
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1614
      Columns(8).Caption=   "WT"
      Columns(8).Name =   "WT"
      Columns(8).Alignment=   1
      Columns(8).CaptionAlignment=   2
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   1402
      Columns(9).Caption=   "INITIALS"
      Columns(9).Name =   "INITIALS"
      Columns(9).CaptionAlignment=   2
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   4233
      Columns(10).Caption=   "REASON"
      Columns(10).Name=   "REASON"
      Columns(10).CaptionAlignment=   2
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   1667
      Columns(11).Caption=   "DATE"
      Columns(11).Name=   "DATE"
      Columns(11).Alignment=   2
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      _ExtentX        =   25850
      _ExtentY        =   13573
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
Attribute VB_Name = "frmDelHistory"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim iRec As Integer

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    Me.Show
    Call FillGrid
End Sub

Sub FillGrid()
    Dim jRec As Integer
    Dim sLotNum As String
    Dim iDeliveryNum As Double
    Dim iCount As Integer
    
    gsSqlStmt = "SELECT * FROM CARGO_DELIVERY_DELETE ORDER BY DELETE_DATE ASC"
    Set dsCARGO_DELIVERY_DELETE = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_DELIVERY_DELETE.RECORDCOUNT > 0 Then
        
        For iRec = 1 To dsCARGO_DELIVERY_DELETE.RECORDCOUNT
            DoEvents
            
            iDeliveryNum = dsCARGO_DELIVERY_DELETE.fields("DELIVERY_NUM").Value
            
          
            
            gsSqlStmt = " SELECT * FROM CARGO_MANIFEST CM, CARGO_DELIVERY CD" _
                      & " WHERE CD.LOT_NUM=CM.CONTAINER_NUM" _
                      & " AND CD.DELIVERY_NUM='" & iDeliveryNum & "' ORDER BY LOT_NUM"
                      
            Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
            For jRec = 1 To dsCARGO_MANIFEST.RECORDCOUNT
                grdData.AddItem CStr(iDeliveryNum) + Chr(9) + "" & dsCARGO_MANIFEST.fields("LR_NUM").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("RECIPIENT_ID").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("CARGO_BOL").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("CARGO_MARK").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("QTY_EXPECTED").Value + Chr(9) + _
                                "" & dsCARGO_MANIFEST.fields("QTY2_EXPECTED").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("CARGO_WEIGHT").Value + Chr(9) + _
                                "" & UCase(dsCARGO_DELIVERY_DELETE.fields("INITIALS").Value) + Chr(9) + _
                                "" & dsCARGO_DELIVERY_DELETE.fields("COMMENTS").Value + Chr(9) + _
                                "" & Format(dsCARGO_DELIVERY_DELETE.fields("DELETE_DATE").Value, "MM/DD/YYYY")
                iCount = iCount + 1
                grdData.Refresh
                dsCARGO_MANIFEST.MoveNext
            Next jRec
            
            dsCARGO_DELIVERY_DELETE.MoveNext
        Next iRec
        
    End If
    gsSqlStmt = " SELECT * FROM CARGO_DELIVERY CD,CARGO_MANIFEST CM" _
              & " WHERE DELIVERY_NUM<0 AND CD.LOT_NUM=CM.CONTAINER_NUM " _
              & " AND DELIVERY_NUM NOT IN ( SELECT DELIVERY_NUM FROM CARGO_DELIVERY_DELETE) " _
              & " ORDER BY DELIVERY_NUM ASC, LOT_NUM"
              
    Set dsCARGO_MANIFEST = OraDatabase.DbCreateDynaset(gsSqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsCARGO_MANIFEST.RECORDCOUNT > 0 Then
        For jRec = 1 To dsCARGO_MANIFEST.RECORDCOUNT
            DoEvents
                grdData.AddItem CStr(dsCARGO_MANIFEST.fields("DELIVERY_NUM").Value) + Chr(9) + "" & dsCARGO_MANIFEST.fields("LR_NUM").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("RECIPIENT_ID").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("COMMODITY_CODE").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("CARGO_BOL").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("CARGO_MARK").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("QTY_EXPECTED").Value + Chr(9) + _
                                "" & dsCARGO_MANIFEST.fields("QTY2_EXPECTED").Value + Chr(9) + _
                                dsCARGO_MANIFEST.fields("CARGO_WEIGHT").Value + Chr(9) + _
                                "" & UCase(dsCARGO_DELIVERY_DELETE.fields("INITIALS").Value) + Chr(9) + _
                                "" & dsCARGO_DELIVERY_DELETE.fields("COMMENTS").Value + Chr(9) + _
                                "" & Format(dsCARGO_DELIVERY_DELETE.fields("DELETE_DATE").Value, "MM/DD/YYYY")
                iCount = iCount + 1
                grdData.Refresh
                dsCARGO_MANIFEST.MoveNext
            Next jRec
    End If
    
    StatusBar1.SimpleText = iCount & "   Records"
End Sub



