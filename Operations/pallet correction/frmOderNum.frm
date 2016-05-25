VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmOrderNum 
   AutoRedraw      =   -1  'True
   BackColor       =   &H00FFFFC0&
   Caption         =   "ORDER DETAIL"
   ClientHeight    =   10095
   ClientLeft      =   60
   ClientTop       =   705
   ClientWidth     =   15240
   BeginProperty Font 
      Name            =   "Times New Roman"
      Size            =   8.25
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   ScaleHeight     =   10095
   ScaleWidth      =   15240
   WindowState     =   2  'Maximized
   Begin VB.CommandButton cmdDelOrder 
      Caption         =   "Delete &Order"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   9360
      TabIndex        =   17
      Top             =   9600
      Width           =   1335
   End
   Begin VB.Frame Frame1 
      BackColor       =   &H00FFFFC0&
      Height          =   2175
      Left            =   2580
      TabIndex        =   6
      Top             =   0
      Width           =   9855
      Begin VB.TextBox txtIntls 
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
         Left            =   7260
         TabIndex        =   15
         Top             =   1560
         Width           =   1335
      End
      Begin VB.ComboBox cboAction 
         Height          =   330
         ItemData        =   "frmOderNum.frx":0000
         Left            =   1980
         List            =   "frmOderNum.frx":0019
         TabIndex        =   14
         Top             =   1560
         Width           =   1815
      End
      Begin VB.TextBox txtOrderNum 
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
         Left            =   1980
         TabIndex        =   12
         Top             =   1080
         Width           =   1815
      End
      Begin VB.TextBox txtCustId 
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
         Left            =   2040
         TabIndex        =   10
         Top             =   600
         Width           =   1215
      End
      Begin VB.TextBox txtCustName 
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
         Left            =   3840
         TabIndex        =   9
         Top             =   600
         Width           =   4815
      End
      Begin VB.CommandButton cmdCustId 
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
         Left            =   3300
         Picture         =   "frmOderNum.frx":005A
         Style           =   1  'Graphical
         TabIndex        =   8
         Top             =   615
         Width           =   375
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Initials  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   6360
         TabIndex        =   16
         Top             =   1620
         Width           =   720
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Action  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   1095
         TabIndex        =   13
         Top             =   1620
         Width           =   660
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Order No  :"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   225
         Left            =   855
         TabIndex        =   11
         Top             =   1140
         Width           =   900
      End
      Begin VB.Label lblOwnerId 
         Alignment       =   1  'Right Justify
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "Owner Id  :"
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
         TabIndex        =   7
         Top             =   660
         Width           =   900
      End
   End
   Begin VB.CommandButton cmdPrint 
      Caption         =   "&Print"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   350
      Left            =   3600
      TabIndex        =   4
      Top             =   9615
      Width           =   1320
   End
   Begin VB.CommandButton cmdDelete 
      Caption         =   "&Delete"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   350
      Left            =   7920
      Style           =   1  'Graphical
      TabIndex        =   3
      Top             =   9615
      Width           =   1320
   End
   Begin VB.CommandButton CmdExit 
      Cancel          =   -1  'True
      Caption         =   "&Exit"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   350
      Left            =   10800
      Style           =   1  'Graphical
      TabIndex        =   5
      Top             =   9600
      Width           =   1320
   End
   Begin VB.CommandButton cmdCancel 
      Caption         =   "&Refresh"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   350
      Left            =   6480
      Style           =   1  'Graphical
      TabIndex        =   2
      Top             =   9615
      Width           =   1320
   End
   Begin VB.CommandButton cmdSave 
      Caption         =   "&Save"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   350
      Left            =   5040
      Style           =   1  'Graphical
      TabIndex        =   1
      Top             =   9615
      Width           =   1320
   End
   Begin VB.CommandButton cmdPltDtls 
      Caption         =   "Pa&llet Details"
      BeginProperty Font 
         Name            =   "Arial"
         Size            =   9
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   350
      Left            =   2160
      Style           =   1  'Graphical
      TabIndex        =   0
      Top             =   9600
      Width           =   1320
   End
   Begin SSDataWidgets_B.SSDBGrid ssdbgOrdDtls 
      Height          =   7155
      Left            =   120
      TabIndex        =   18
      Top             =   2340
      Width           =   15015
      ScrollBars      =   2
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
      Col.Count       =   15
      AllowDelete     =   -1  'True
      AllowColumnSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowColumnMoving=   0
      AllowColumnSwapping=   0
      AllowDragDrop   =   0   'False
      SelectTypeRow   =   1
      PictureButton   =   "frmOderNum.frx":015C
      PictureComboButton=   "frmOderNum.frx":1E0E
      MaxSelectedRows =   1
      ForeColorEven   =   8388608
      RowHeight       =   423
      ExtraHeight     =   476
      Columns.Count   =   15
      Columns(0).Width=   1799
      Columns(0).Caption=   "DATE"
      Columns(0).Name =   "DATE"
      Columns(0).CaptionAlignment=   2
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   1588
      Columns(1).Caption=   "TIME"
      Columns(1).Name =   "TIME"
      Columns(1).CaptionAlignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1561
      Columns(2).Caption=   "ACTION"
      Columns(2).Name =   "ACTION"
      Columns(2).CaptionAlignment=   2
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   1667
      Columns(3).Caption=   "CHECKER"
      Columns(3).Name =   "CHECKER"
      Columns(3).Alignment=   2
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   3678
      Columns(4).Caption=   "PALLET"
      Columns(4).Name =   "PALLET"
      Columns(4).Alignment=   2
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1693
      Columns(5).Caption=   "QUANTITY"
      Columns(5).Name =   "QUANTITY"
      Columns(5).Alignment=   2
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   2196
      Columns(6).Caption=   "ACTIVITY DESCRIPTION"
      Columns(6).Name =   "ACTIVITY DESCRIPTION"
      Columns(6).Alignment=   2
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1429
      Columns(7).Caption=   "QTY INH"
      Columns(7).Name =   "QTY INH"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   1826
      Columns(8).Caption=   "QTY RECVD"
      Columns(8).Name =   "QTY RECVD"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   3200
      Columns(9).Visible=   0   'False
      Columns(9).Caption=   "SER CODE"
      Columns(9).Name =   "SER CODE"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   3200
      Columns(10).Visible=   0   'False
      Columns(10).Caption=   "ACT NUM"
      Columns(10).Name=   "ACT NUM"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   3200
      Columns(11).Visible=   0   'False
      Columns(11).Caption=   "ACT BILLED"
      Columns(11).Name=   "ACT BILLED"
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   5662
      Columns(12).Caption=   "CARGO DESCRIPTION"
      Columns(12).Name=   "CARGO DESCRIPTION"
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   3200
      Columns(13).Visible=   0   'False
      Columns(13).Caption=   "Flag"
      Columns(13).Name=   "Flag"
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      Columns(14).Width=   2275
      Columns(14).Caption=   "ARRIVAL_NUM"
      Columns(14).Name=   "ARRIVAL_NUM"
      Columns(14).DataField=   "Column 14"
      Columns(14).DataType=   8
      Columns(14).FieldLen=   256
      Columns(14).Locked=   -1  'True
      _ExtentX        =   26485
      _ExtentY        =   12621
      _StockProps     =   79
      Caption         =   "ORDER DETAILS"
      BackColor       =   12632256
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
   Begin VB.Label plt 
      BackStyle       =   0  'Transparent
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
      Left            =   13920
      TabIndex        =   20
      Top             =   1560
      Width           =   495
   End
   Begin VB.Label Label4 
      BackStyle       =   0  'Transparent
      Caption         =   "Plt Count  :"
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
      Left            =   12960
      TabIndex        =   19
      Top             =   1560
      Width           =   975
   End
End
Attribute VB_Name = "frmOrderNum"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim dsCARGO_ACTIVITY As Object
Dim iQtyOld As Integer
Dim iQtyNew As Integer
Dim bDelete As Boolean

Sub Clear_Screen()
    ssdbgOrdDtls.RemoveAll
End Sub
Private Sub cboAction_Click()
    If txtCustId = "" Then Exit Sub
    If txtOrderNum = "" Then Exit Sub
    Call Show_record
End Sub
Private Sub cboAction_GotFocus()
   If cboAction.ListIndex <> -1 Then cboAction_Click
End Sub
Private Sub cboAction_KeyPress(KeyAscii As Integer)
   If KeyAscii = 13 Then Call cboAction_Click
End Sub
Private Sub cmdCancel_Click()
    Call Clear_Screen
    Call Show_record
End Sub
Private Sub cmdCustId_Click()
    Dim iPos As Integer
    Dim sqlstmt As String
    Dim dsCUST_LIST As Object
    
    load frmPV
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Left = (Screen.Width - frmPV.Width) / 2
    frmPV.Caption = "CUSTOMER LIST"
    frmPV.lstPV.Clear
    
    sqlstmt = "SELECT * FROM CUSTOMER_PROFILE ORDER BY CUSTOMER_ID"
    Set dsCUST_LIST = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If OraDatabase.lastservererr = 0 And dsCUST_LIST.recordcount > 0 Then
        While Not dsCUST_LIST.EOF
            frmPV.lstPV.AddItem dsCUST_LIST.fields("CUSTOMER_ID").Value & " : " & dsCUST_LIST.fields("CUSTOMER_NAME").Value
            dsCUST_LIST.MoveNext
        Wend
    End If
    frmPV.Show vbModal
    If gsPVItem <> "" Then
        iPos = InStr(gsPVItem, " : ")
        If iPos > 0 Then
            txtCustId.Text = Left$(gsPVItem, iPos - 1)
            txtCustName = Mid$(gsPVItem, iPos + 3)
        End If
    End If
    txtCustId.SetFocus
End Sub

Private Sub cmdDelete_Click()
    If MsgBox("Are you sure to delete the selected row(s) ?", vbCritical + vbYesNo, "ORDER NUMBER") = vbNo Then
        cmdCancel_Click
        Exit Sub
    Else
        ssdbgOrdDtls.DeleteSelected
        
    End If
End Sub

Private Sub cmdDelOrder_Click()
Dim iRows As Integer
Dim bCan As Boolean
Dim i As Integer
Dim sqlstmt As String
Dim iNewQtyRecvd As Integer
Dim iNewQtyINH  As Integer
Dim iOldQtyRecvd As Integer
Dim iOldQtyINH As Integer
Dim iQty As Integer


bCan = False
bDelete = True

If cboAction.ListIndex <> 0 Then
    MsgBox "First choose Action ='ALL'", vbInformation, "ORDER DETAIL"
    Exit Sub
End If

If MsgBox("Are you sure to delete this Order ?", vbQuestion + vbYesNo, "ORDER DETAIL") = vbNo Then
    Exit Sub
End If

ssdbgOrdDtls.MoveFirst

For iRows = 0 To ssdbgOrdDtls.Rows - 1
        
    iNewQtyRecvd = 0
    iNewQtyINH = 0
    
    iOldQtyRecvd = ssdbgOrdDtls.Columns(8).Value
    iOldQtyINH = ssdbgOrdDtls.Columns(7).Value
    iQty = Trim(ssdbgOrdDtls.Columns(5).Value)
    
    Select Case Trim(ssdbgOrdDtls.Columns(2).Value)
         
         Case "Delivery"
            iNewQtyINH = iOldQtyINH + iQty
            iNewQtyRecvd = iOldQtyRecvd
            If Val(iNewQtyINH) > Val(iOldQtyRecvd) Then
                bCan = True
                ssdbgOrdDtls.Columns(13).Value = 1
            ElseIf iNewQtyINH < 0 Then
                bCan = True
                ssdbgOrdDtls.Columns(13).Value = 1
            Else
               ssdbgOrdDtls.Columns(13).Value = 0
                
                ssdbgOrdDtls.Columns(7).Value = iNewQtyINH
                ssdbgOrdDtls.Columns(8).Value = iNewQtyRecvd
            End If
         Case "Return", "Dock Return"    'RETURN
               iNewQtyINH = iOldQtyINH - Abs(iQtyOld)
               iNewQtyRecvd = iOldQtyRecvd - Abs(iQtyOld)
               If Val(iNewQtyINH) > Val(iNewQtyRecvd) Then
                  bCan = True
                  ssdbgOrdDtls.Columns(13).Value = 1
               ElseIf iNewQtyINH < 0 Then
                  bCan = True
                  ssdbgOrdDtls.Columns(13).Value = 0
               Else
                  ssdbgOrdDtls.Columns(13).Value = 0
                  ssdbgOrdDtls.Columns(7).Value = iNewQtyINH
                  ssdbgOrdDtls.Columns(8).Value = iNewQtyRecvd
               End If
                
         Case "FromPort"    'FROM PORT
                   iNewQtyINH = iOldQtyINH - iQty
                   iNewQtyRecvd = iOldQtyRecvd - iQty
                If Val(iNewQtyINH) > Val(iNewQtyRecvd) Then
                    bCan = True
                    ssdbgOrdDtls.Columns(13).Value = 0
                ElseIf iNewQtyINH < 0 Then
                    bCan = True
                    ssdbgOrdDtls.Columns(13).Value = 0
                Else
                   ssdbgOrdDtls.Columns(13).Value = 0
                    ssdbgOrdDtls.Columns(7).Value = iNewQtyINH
                    ssdbgOrdDtls.Columns(8).Value = iNewQtyRecvd
                End If
                
         Case "Recoup"    'RECOUP
                iNewQtyINH = iOldQtyINH - iQty
                iNewQtyRecvd = iOldQtyRecvd
                If Val(iNewQtyINH) > Val(iOldQtyRecvd) Then
                    bCan = True
                    ssdbgOrdDtls.Columns(13).Value = 0
                ElseIf iNewQtyINH < 0 Then
                    bCan = True
                    ssdbgOrdDtls.Columns(13).Value = 0
                Else
                    ssdbgOrdDtls.Columns(13).Value = 0
                    ssdbgOrdDtls.Columns(7).Value = iNewQtyINH
                    ssdbgOrdDtls.Columns(8).Value = iNewQtyRecvd
                End If
                
         Case "Void"   'VOID
                iNewQtyINH = iOldQtyINH - Abs(iQty)
                iNewQtyRecvd = iOldQtyRecvd - Abs(iQty)
                If Val(iNewQtyINH) > Val(iOldQtyRecvd) Then
                    bCan = True
                    ssdbgOrdDtls.Columns(13).Value = 1
                ElseIf iNewQtyINH < 0 Then
                    bCan = True
                    ssdbgOrdDtls.Columns(13).Value = 0
                Else
                     ssdbgOrdDtls.Columns(13).Value = 0
                    ssdbgOrdDtls.Columns(7).Value = iNewQtyINH
                    ssdbgOrdDtls.Columns(8).Value = iNewQtyRecvd
                End If
         End Select
       bCan = False
       ssdbgOrdDtls.MoveNext
    Next iRows
    
    ssdbgOrdDtls.MoveFirst
    For i = 0 To ssdbgOrdDtls.Rows - 1
       If ssdbgOrdDtls.Columns(13).Value = 0 Then
       'CHECK QUERY Added ARRIVAL_NUM AND CUSTOMER_ID IS NOT EXISTING IN WHERE clause - Ramesh
         sqlstmt = " UPDATE CARGO_TRACKING SET QTY_IN_HOUSE='" & Trim(ssdbgOrdDtls.Columns(7).Value) & "', " & _
                   " QTY_RECEIVED='" & Trim(ssdbgOrdDtls.Columns(8).Value) & "' where " & _
                   " PALLET_ID='" & Trim(ssdbgOrdDtls.Columns(4).Value) & "'" & _
                   " AND ARRIVAL_NUM='" & Trim(ssdbgOrdDtls.Columns(14).Value) & "'" & _
                   " AND RECEIVER_ID='" & Trim(txtCustId) & "'"

         OraDatabase.DbExecuteSQL (sqlstmt)
       'CHECK QUERY Added ARRIVAL_NUM AND IN WHERE clause - Ramesh
         sqlstmt = " DELETE FROM CARGO_ACTIVITY WHERE CUSTOMER_ID='" & txtCustId & "'" & _
                   " AND ORDER_NUM='" & txtOrderNum & "'" & _
                   " AND PALLET_ID ='" & Trim(ssdbgOrdDtls.Columns(4).Value) & "'" & _
                   " AND ARRIVAL_NUM='" & Trim(ssdbgOrdDtls.Columns(14).Value) & "'" & _
                   " AND ACTIVITY_NUM='" & Trim(ssdbgOrdDtls.Columns(10).Value) & "'"
            
         OraDatabase.DbExecuteSQL (sqlstmt)
        ssdbgOrdDtls.Columns(13).Value = 1
            
        End If
        ssdbgOrdDtls.MoveNext
    Next i
        
    Call Show_record
    
    If ssdbgOrdDtls.Rows <> 0 Then
            MsgBox "Make Quantity Inhouse and Quantity Received appropriate for deleteing the rest of the lines " & vbCrLf & _
                   "And try to delete one by one", vbInformation, "ORDER DETAIL"
            Exit Sub
    End If
    bDelete = False
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub

Private Sub cmdOk_Click()
    
    Call Show_record
End Sub

Private Sub cmdPltDtls_Click()
    If ssdbgOrdDtls.Rows <> 0 Then
        gsLotNum = ssdbgOrdDtls.Columns(4).Value
    End If
    Unload Me
    frmPltCorrection.Show
End Sub

Private Sub cmdPrint_Click()
    Dim iLine As Long
    Dim iRec As Integer
    Dim sql1 As String
    Dim sqlstmt As String
    Dim PltCnt As Integer
    Dim load As String
    Dim sqlstmt1 As String
    Dim dsLoad As Object
    load = ""
    sqlstmt = " select load_num from pl_order_head where " & _
                   " ORDER_NUM='" & txtOrderNum & "'"
    Set dsLoad = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If OraDatabase.lastservererr = 0 And dsLoad.recordcount > 0 Then
        load = dsLoad.fields("load_num").Value
    Else
        MsgBox "NO RECORD FOUND ", vbInformation, "Load No"
    End If
                   
                   
    Printer.Orientation = 2
    
    For iLine = 1 To 3
        Printer.Print
    Next iLine
    Printer.FontBold = True
    Printer.FontUnderline = True
    Printer.Print Tab(65); "ORDER DETAILS"
    Printer.FontUnderline = False
    Printer.Print
    Printer.Print
    Printer.FontBold = False
    Printer.Print Tab(5); "OWNER ID  :"; Tab(30); txtCustId & " - " & txtCustName
    Printer.Print
    Printer.Print
    Printer.Print Tab(5); "ORDER NO.  :"; Tab(30); txtOrderNum
    Printer.Print
    Printer.Print
    Printer.Print Tab(5); "LOAD NO.  :"; Tab(30); load
    Printer.Print
    Printer.Print
    Printer.Print
    Printer.Print
    Printer.Print Tab(3); "DATE"; Tab(15); "TIME"; Tab(27); "ACTION"; Tab(39); _
                  "CHECKER "; Tab(55); "PALLET NO."; Tab(80); "QUANTITY"; _
                  Tab(94); "DESCRIPTION"; Tab(120); "VARIETY"; Tab(162); "VESSEL"
    Printer.Print Tab(2); "----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
    
    ssdbgOrdDtls.MoveFirst
    For iRec = 0 To ssdbgOrdDtls.Rows - 1
            'Commented out line below so it will print single spaced.  05.30.2001 Lyle Granger
            'Printer.Print ""
            Printer.Print Tab(3); Format(ssdbgOrdDtls.Columns(0).Value, "MM/DD/YY"); _
                      Tab(14); ssdbgOrdDtls.Columns(1).Value; _
                      Tab(27); ssdbgOrdDtls.Columns(2).Value; _
                      Tab(39); ssdbgOrdDtls.Columns(3).Value; _
                      Tab(54); ssdbgOrdDtls.Columns(4).Value; _
                      Tab(82); ssdbgOrdDtls.Columns(5).Value; _
                      Tab(90); Mid(ssdbgOrdDtls.Columns(6).Value, 1.3); _
                      Tab(120); ssdbgOrdDtls.Columns(12).Value; _
                      Tab(162); ssdbgOrdDtls.Columns(14).Value
                      
            ssdbgOrdDtls.MoveNext
    Next iRec
    
            'Added 9 lines below to put total number of pallets on bottom of printout.  05.30.2001 Lyle Granger
            sqlstmt = " Select * from CARGO_ACTIVITY where ORDER_NUM = '" & txtOrderNum & "'" & _
                      " and SERVICE_CODE = 6 and ACTIVITY_DESCRIPTION is null "
            Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
            PltCnt = dsCARGO_ACTIVITY.recordcount

            Printer.Print
            Printer.Print Tab(2); "----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
            Printer.Print
            Printer.Print Tab(2); "Total Pallets: "; Tab(80); PltCnt
    
    Printer.EndDoc
    ssdbgOrdDtls.MoveFirst
End Sub

Private Sub Form_Paint()
    Me.Refresh
End Sub

Private Sub ssdbgOrdDtls_BeforeUpdate(Cancel As Integer)
    Dim iQtyInHOld As Integer
    Dim iQtyINHNew As Integer
    Dim iqtyRecvdOld As Integer
    Dim iQtyRecvdNew As Integer
    Dim iQtyNew As Integer
    Dim sqlstmt As String
    Dim dsCargo_Tracking As Object
    
    If bDelete = True Then Exit Sub
    If Not IsDate(Format(ssdbgOrdDtls.Columns(0).Value, "MM/DD/YY")) Then
        MsgBox "Please enter a valid date"
    End If

    If Not IsDate(Format(ssdbgOrdDtls.Columns(0).Value, "HH:MM:SS")) Then
        MsgBox "Please enter a valid Time"
    End If
    
    iqtyRecvdOld = Trim(ssdbgOrdDtls.Columns(8).Value)  'Invisible at run time
    iQtyInHOld = Trim(ssdbgOrdDtls.Columns(7).Value)    'invisible at run time
    iQtyNew = Trim(ssdbgOrdDtls.Columns(5).Value)
    
    Select Case ssdbgOrdDtls.Columns(2).Value

        Case "Delivery"
             If iQtyNew < 0 Then
                If MsgBox("Quantity cannot be less then zero." & vbCrLf & _
                          "Do You want to overwrite it ? ", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                    ssdbgOrdDtls.Columns(5).Value = iQtyOld
                    Cancel = True
                    Exit Sub
                Else
                    If bPwd = False Then
                        frmPwd.Show vbModal     'Opens a new screen for entereing the password
                        If bPwd = False Then
                            ssdbgOrdDtls.Columns(5).Value = iQtyOld
                            Cancel = True
                            Exit Sub
                        End If
                    End If
                End If
            End If
            
            If iQtyNew = iQtyOld Then
                iQtyINHNew = iQtyInHOld
                iQtyRecvdNew = iqtyRecvdOld
            End If
                
            If iQtyNew > iQtyOld Then
                If iQtyNew > iqtyRecvdOld Then
                    If MsgBox("The Qty Change cannot exceed then the Qty Received " & vbCrLf & _
                                  " DO you want to overwrite it ? ", vbQuestion + vbYesNo) = vbNo Then
                        ssdbgOrdDtls.Columns(6).Value = iQtyOld
                        Cancel = True
                        Exit Sub
                    Else
                        If bPwd = False Then
                            frmPwd.Show vbModal
                            If bPwd = False Then
                                ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                Cancel = True
                                Exit Sub
                            End If
                        End If
                    End If
                End If
                
                iQtyINHNew = iQtyInHOld - (iQtyNew - iQtyOld)
                    
            ElseIf iQtyNew < iQtyOld Then
                iQtyINHNew = iQtyInHOld + (iQtyOld - iQtyNew)
            End If
                
            iQtyRecvdNew = iqtyRecvdOld
            If iQtyINHNew > iQtyRecvdNew Then
                If MsgBox("Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                      "Do You want to overwrite it ?", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                    ssdbgOrdDtls.Columns(6).Value = iQtyOld
                    Cancel = True
                    Exit Sub
                Else
                    If bPwd = False Then
                        frmPwd.Show vbModal
                        If bPwd = False Then
                            ssdbgOrdDtls.Columns(5).Value = iQtyOld
                            Cancel = True
                            Exit Sub
                        End If
                    End If
                End If
            End If
            
            Case "Return", "Dock Return"
                If iQtyNew > 0 Then
                    If MsgBox("This value should be less then zero." & vbCrLf & _
                            "Do you want to overwrite it ?", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                        ssdbgOrdDtls.Columns(6).Value = iQtyOld
                        Cancel = True
                        Exit Sub
                    Else
                        If bPwd = False Then
                            frmPwd.Show vbModal
                            If bPwd = False Then
                                ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                Cancel = True
                                Exit Sub
                            End If
                        End If
                    End If
                End If
                
                If iQtyNew = iQtyOld Then
                    iQtyINHNew = iQtyInHOld
                    iQtyRecvdNew = iqtyRecvdOld
                End If
                
                If Abs(iQtyNew) > Abs(iQtyOld) Then
                    If Abs(iQtyNew) > (iqtyRecvdOld - iQtyInHOld) Then
                        If MsgBox("Change in quantity cannot be more then the difference of " & _
                                " quantity received and quantity inhouse." & vbCrLf & "Do you want to" & _
                                " overwrite it ? ", vbQuestion + vbYesNo) = vbNo Then
                            ssdbgOrdDtls.Columns(6).Value = iQtyOld
                            Cancel = True
                            Exit Sub
                        Else
                            If bPwd = False Then
                                frmPwd.Show vbModal
                                If bPwd = False Then
                                    ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                    Cancel = True
                                    Exit Sub
                                End If
                            End If
                        End If
                    End If
                    
                    iQtyINHNew = iQtyInHOld + (Abs(iQtyNew) - Abs(iQtyOld))
                    iqtyRecvdOld = iqtyRecvdOld + (Abs(iQtyNew) - Abs(iQtyOld))
                
                ElseIf Abs(iQtyNew) < Abs(iQtyOld) Then
                    If (Abs(iQtyOld) - Abs(iQtyNew)) > iQtyInHOld Then
                        If MsgBox("Inhouse quantity cannot be less then zero." & vbCrLf & _
                                  "Do you want to overwrite it ?", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                            ssdbgOrdDtls.Columns(6).Value = iQtyOld
                            Cancel = True
                            Exit Sub
                        Else
                            If bPwd = False Then
                                frmPwd.Show vbModal
                                If bPwd = False Then
                                    ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                    Cancel = True
                                    Exit Sub
                                End If
                            End If
                        End If
                    End If
                    
                    iQtyINHNew = iQtyInHOld - (Abs(iQtyOld) - Abs(iQtyNew))
                    iQtyRecvdNew = iqtyRecvdOld - (Abs(iQtyOld) - Abs(iQtyNew))
                End If
                
                If Val(iQtyINHNew) > Val(iQtyRecvdNew) Then
                    If MsgBox("Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                              "Do you want to overwrite it ? ", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                        ssdbgOrdDtls.Columns(6).Value = iQtyOld
                        Cancel = True
                        Exit Sub
                    Else
                        If bPwd = False Then
                            frmPwd.Show vbModal
                            If bPwd = False Then
                                ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                Cancel = True
                                Exit Sub
                            End If
                        End If
                   End If
                End If
            
            Case "FromPort"
            
                If iQtyNew < 0 Then
                    If MsgBox("Quantity Cannot be zero. " & vbCrLf & " Do you want to overwrite" _
                              & "it ?", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                        ssdbgOrdDtls.Columns(6).Value = iQtyOld
                        Cancel = True
                        Exit Sub
                    Else
                        If bPwd = False Then
                            frmPwd.Show vbModal
                            If bPwd = False Then
                                ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                Cancel = True
                                Exit Sub
                            End If
                        End If
                    End If
                End If
                
                If iQtyNew = iQtyOld Then
                    iQtyINHNew = iQtyInHOld
                    iQtyRecvdNew = iqtyRecvdOld
                End If
                
                If iQtyNew > iQtyOld Then
                    iQtyINHNew = iQtyInHOld + (iQtyNew - iQtyOld)
                    iQtyRecvdNew = iqtyRecvdOld + (iQtyNew - iQtyOld)
                ElseIf iQtyNew < iQtyOld Then
                    iQtyINHNew = iQtyInHOld - (iQtyOld - iQtyNew)
                    iQtyRecvdNew = iqtyRecvdOld - (iQtyOld - iQtyNew)
                End If
                
                If iQtyINHNew > iQtyRecvdNew Then
                    If MsgBox("Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                              "Do you want to overwrite it ?", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                        ssdbgOrdDtls.Columns(6).Value = iQtyOld
                        Cancel = True
                        Exit Sub
                    Else
                        If bPwd = False Then
                            frmPwd.Show vbModal
                            If bPwd = False Then
                                ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                Cancel = True
                                Exit Sub
                            End If
                        End If
                    End If
                End If
                
            Case "Recoup"
            
                If iQtyNew = iQtyOld Then
                    iQtyINHNew = iQtyInHOld
                    iQtyRecvdNew = iqtyRecvdOld
                End If
                
                If iQtyOld <= 0 And iQtyNew <= 0 Then
                    iQtyINHNew = iQtyInHOld + (iQtyNew - iQtyOld)
                ElseIf iQtyOld <= 0 And iQtyNew > 0 Then
                    iQtyINHNew = iQtyInHOld + (iQtyNew - iQtyOld)
                ElseIf iQtyOld > 0 And iQtyNew <= 0 Then
                    iQtyINHNew = iQtyInHOld - (iQtyOld - iQtyNew)
                ElseIf iQtyOld > 0 And iQtyNew > 0 Then
                    iQtyINHNew = iQtyInHOld - (iQtyOld - iQtyNew)
                End If
                    
                If iQtyINHNew > iQtyRecvdNew Then
                    If MsgBox("Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                              "Do you want to overwrite it ?", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                        ssdbgOrdDtls.Columns(6).Value = iQtyOld
                        Cancel = True
                        Exit Sub
                    Else
                        If bPwd = False Then
                            frmPwd.Show vbModal
                            If bPwd = False Then
                                ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                Cancel = True
                                Exit Sub
                            End If
                        End If
                    End If
                End If
                
            Case "VOID"
            
                If iQtyNew = iQtyOld Then
                    iQtyINHNew = iQtyInHOld
                    iQtyRecvdNew = iqtyRecvdOld
                End If
                
                If iQtyNew > 0 Then
                    If MsgBox("This value should be less then zero." & vbCrLf & " Do you want to " _
                              & "overwrite it ?", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                        ssdbgOrdDtls.Columns(6).Value = iQtyOld
                        Cancel = True
                        Exit Sub
                    Else
                        If bPwd = False Then
                            frmPwd.Show vbModal
                            If bPwd = False Then
                                ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                Cancel = True
                                Exit Sub
                            End If
                        End If
                    End If
                End If
                
                If Abs(iQtyNew) > Abs(iQtyOld) Then
                    If Abs(iQtyNew) > (iqtyRecvdOld - iQtyInHOld) Then
                        If MsgBox("Change in quantity cannot be more then the difference of " _
                                  & "quantity received and quantity inhouse." & vbCrLf & _
                                  "Do you want to overwrite it ? ", vbQuestion + vbYesNo) = vbNo Then
                            ssdbgOrdDtls.Columns(6).Value = iQtyOld
                            Cancel = True
                            Exit Sub
                        Else
                            If bPwd = False Then
                                frmPwd.Show vbModal
                                If bPwd = False Then
                                    ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                    Cancel = True
                                    Exit Sub
                                End If
                            End If
                        End If
                    End If
                    
                    iQtyINHNew = iQtyInHOld + (Abs(iQtyNew)) - Abs(iQtyOld)
                    iQtyRecvdNew = iqtyRecvdOld + (Abs(iQtyNew) - Abs(iQtyOld))
                
                ElseIf Abs(iQtyNew) < Abs(iQtyOld) Then
                    If (Abs(iQtyOld) - Abs(iQtyNew)) > iQtyInHOld Then
                        If MsgBox("Inhouse quantity cannot be less then zero." & vbCrLf & _
                                  "Do you want to overwrite it ?", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                            ssdbgOrdDtls.Columns(6).Value = iQtyOld
                            Cancel = True
                            Exit Sub
                        Else
                            If bPwd = False Then
                                frmPwd.Show vbModal
                                If bPwd = False Then
                                    ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                    Cancel = True
                                    Exit Sub
                                End If
                            End If
                        End If
                    End If
                    
                    iQtyINHNew = iQtyInHOld - (Abs(iQtyOld) - Abs(iQtyOld))
                    iQtyRecvdNew = iqtyRecvdOld - (Abs(iQtyOld) - Abs(iQtyNew))
                End If
                
                If iQtyINHNew > iQtyRecvdNew Then
                    If MsgBox("Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                              "Do you want to overwrite it ?", vbQuestion + vbYesNo, "PALLET CORRECTION") = vbNo Then
                        ssdbgOrdDtls.Columns(6).Value = iQtyOld
                        Cancel = True
                        Exit Sub
                    Else
                        If bPwd = False Then
                            frmPwd.Show vbModal
                            If bPwd = False Then
                                ssdbgOrdDtls.Columns(5).Value = iQtyOld
                                Cancel = True
                                Exit Sub
                            End If
                        End If
                    End If
                End If
                
        End Select
        
        If iQtyINHNew < 0 Then
            If MsgBox("InHouse Quantity cannot be equal to or less then zero." & vbCrLf & _
                    "Do you want to overwrie it ?", vbQuestion + vbYesNo) = vbNo Then
                ssdbgOrdDtls.Columns(6).Value = iQtyOld
                Cancel = True
                Exit Sub
            Else
                If bPwd = False Then
                    frmPwd.Show vbModal
                    If bPwd = False Then
                        ssdbgOrdDtls.Columns(5).Value = iQtyOld
                        Cancel = True
                        Exit Sub
                    End If
                End If
            End If
        End If
        
        ssdbgOrdDtls.Columns(8).Value = iQtyRecvdNew 'Invisible at run time
        ssdbgOrdDtls.Columns(7).Value = iQtyINHNew
        
End Sub
Private Sub cmdSave_Click()
    Dim sDtOfActivity As String
    Dim i As Integer
    Dim iRow As Integer
    Dim iElement As Integer
    Dim iError As Boolean
    Dim dsPers As Object
    Dim dsCargo_Tracking As Object
    Dim sqlstmt As String
    Dim iSerCode As Integer
            
    iError = False
    
    If txtIntls = "" Then
        MsgBox "Please Enter your Initials", vbInformation + vbExclamation, "SAVE"
        Exit Sub
    End If
    
    OraSession.begintrans
    
    ssdbgOrdDtls.MoveFirst
    
    For i = 0 To ssdbgOrdDtls.Rows - 1
        
        If UCase(Trim(ssdbgOrdDtls.Columns(2).Value)) = UCase("Delivery") Then
            ssdbgOrdDtls.Columns(2).Value = "Delivery"
            iSerCode = 6
        ElseIf UCase(Trim(ssdbgOrdDtls.Columns(2).Value)) = UCase("Return") Then
            ssdbgOrdDtls.Columns(2).Value = "Return"
            iSerCode = 7
        ElseIf UCase(Trim(ssdbgOrdDtls.Columns(2).Value)) = UCase("FromPort") Then
            ssdbgOrdDtls.Columns(2).Value = "FromPort"
            iSerCode = 8
        ElseIf UCase(Trim(ssdbgOrdDtls.Columns(2).Value)) = UCase("Recoup") Then
            ssdbgOrdDtls.Columns(2).Value = "Recoup"
            iSerCode = 9
        ElseIf UCase(Trim(ssdbgOrdDtls.Columns(2).Value)) = UCase("Void") Then
            ssdbgOrdDtls.Columns(2).Value = "Void"
            iSerCode = 12
        ElseIf UCase(Trim(ssdbgOrdDtls.Columns(2).Value)) = UCase("Dock Return") Then
            ssdbgOrdDtls.Columns(2).Value = "Dock Return"
            iSerCode = 13
        Else
            MsgBox "INVALID ACTION", vbInformation + vbExclamation, "COMMIT"
            Exit Sub
        End If
        
        sDtOfActivity = ssdbgOrdDtls.Columns(0).Value & " " & ssdbgOrdDtls.Columns(1).Value
        
'        sqlstmt = "SELECT distinct SUBSTR(Employee_ID, -4) THE_EMP from EMPLOYEE where " _
'                & " SUBSTR(EMPLOYEE_NAME, 0, 8) ='" & ssdbgOrdDtls.Columns(3).Value & "'"
                
'        Set dsPers = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
        '&&&&
        sqlstmt = "select * from CA_ARCHIVE"
        Set dsCARGO_ACTIVITY_ARCHIVE = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        If OraDatabase.lastservererr = 0 Then
            With dsCARGO_ACTIVITY_ARCHIVE
                .AddNew
                .fields("activity_description").Value = ssdbgOrdDtls.Columns(6).Value
                .fields("DATE_OF_ACTIVITY").Value = CDate(Format(sDtOfActivity, "MM/DD/YYYY HH:MM:SS"))
                .fields("ORDER_NUM").Value = txtOrderNum
                .fields("QTY_CHANGE").Value = ssdbgOrdDtls.Columns(5).Value
                .fields("SErvice_code").Value = iSerCode
'                .Fields("ACTIVITY_ID").Value = dsPers.Fields("THE_EMP").Value
                .fields("ACTIVITY_ID").Value = get_checker_DB_value(ssdbgOrdDtls.Columns(3).Value)
                .fields("Lot_Num").Value = ssdbgOrdDtls.Columns(4).Value
                .fields("Comments").Value = txtIntls
                .fields("activity_num").Value = ssdbgOrdDtls.Columns(10).Value
                .fields("order_num").Value = txtOrderNum
                .fields("customer_id").Value = txtCustId
                .fields("activity_billed").Value = ssdbgOrdDtls.Columns(11).Value
                .Update
           End With
        End If
      'CHECK QUERY Added ARRIVAL_NUM -Ramesh
      'Updating the data in Cargo_Activity
        'sqlstmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID =" _
             & " '" & ssdbgOrdDtls.Columns(4).Value & "' AND order_num = " _
             & " '" & txtOrderNum & "' AND customer_id= " _
             & " ' " & txtCustId & "'"
        sqlstmt = "SELECT * FROM CARGO_ACTIVITY WHERE PALLET_ID =" _
             & " '" & ssdbgOrdDtls.Columns(4).Value & "'" _
             & " AND order_num = '" & txtOrderNum & "'" _
             & " AND customer_id= ' " & txtCustId & "'" _
             & " AND ARRIVAL_NUM='" & ssdbgOrdDtls.Columns(14).Value & "'" _
             & " AND ACTIVITY_NUM =" & ssdbgOrdDtls.Columns(10).Value
             
        Set dsCARGO_ACTIVITY = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        
        With dsCARGO_ACTIVITY
            .edit
            .fields("activity_description").Value = ssdbgOrdDtls.Columns(6).Value
            .fields("DATE_OF_ACTIVITY").Value = CDate(Format(sDtOfActivity, "MM/DD/YYYY HH:MM:SS"))
            '.FIELDS("ORDER_NUM").Value = txtOrderNum
            .fields("QTY_CHANGE").Value = ssdbgOrdDtls.Columns(5).Value
            .fields("Service_code").Value = iSerCode
'            .Fields("ACTIVITY_ID").Value = dsPers.Fields("EMPLOYEE_ID").Value
            .fields("ACTIVITY_ID").Value = get_checker_DB_value(ssdbgOrdDtls.Columns(3).Value)
            .Update
        End With
      
        'sqlstmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID=" _
                & "'" & Trim(ssdbgOrdDtls.Columns(4).Value) & "'"
         sqlstmt = "SELECT * FROM CARGO_TRACKING WHERE PALLET_ID =" _
             & " '" & ssdbgOrdDtls.Columns(4).Value & "' AND RECEIVER_ID= " _
             & " ' " & Trim(txtCustId) & "' AND ARRIVAL_NUM='" & ssdbgOrdDtls.Columns(14).Value & "'"
                
        Set dsCargo_Tracking = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
        With dsCargo_Tracking
            .edit
            .fields("QTY_IN_HOUSE").Value = ssdbgOrdDtls.Columns(7).Value
            .fields("QTY_RECEIVED").Value = ssdbgOrdDtls.Columns(8).Value
            .Update
        End With
                  
        If OraDatabase.lastservererr <> 0 Then
            iError = True
        End If
        ssdbgOrdDtls.MoveNext
    Next i
    
    If OraDatabase.lastservererr <> 0 Then
        iError = True
    End If
    
    If Not iError Then
        OraSession.committrans
        MsgBox "CHANGES ARE MADE SUCCESSFULLY .", vbInformation, "PALLET CORRECTION"
        bPwd = False
        Call Show_record
    Else
        OraSession.rollback
        MsgBox "ERROR WHILE PROCESSING THE DATA !.", vbCritical + vbInformation, "PALLET CORRECTION"
    End If
    
End Sub

Private Sub Form_Load()
    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
    Me.Refresh
    On Error GoTo Err_FormLoad
    
    If giCustId <> 0 Then
        txtCustId = giCustId
        Call txtCustId_Validate(False)
    End If
    If gsOrderNum <> "" Then
        txtOrderNum = gsOrderNum
        cboAction.ListIndex = 0
        Call cboAction_Click
    End If
    bPwd = False
    Exit Sub
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation
    End
End Sub

Sub Show_record()
    Dim sqlstmt As String
    Dim sqlstmt1 As String
    Dim dsPallet As Object
    Dim dsCARGO_ACT As Object
    Dim iRec As Integer
    Dim dsCARGO_ACTIVITY As Object
    Dim sActDes As String
    
    ssdbgOrdDtls.RemoveAll
    ssdbgOrdDtls.RowHeight = 300
    
    Select Case cboAction.ListIndex
        
        Case 0      'ALL
               'sqlstmt = "SELECT CA.*, SC.SERVICE_NAME ,PERS.LOGIN_ID,CT.CARGO_DESCRIPTION" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " PERSONNEL PERS WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' AND " _
                        & " CA.SERVICE_CODE = SC.SERVICE_CODE AND" _
                        & " CA.LOT_NUM=CT.LOT_NUM " _
                        & " AND CA.ACTIVITY_ID = PERS.EMPLOYEE_ID ORDER BY DATE_OF_ACTIVITY"
                ',NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), '') LOGIN_ID     EMPLOYEE PERS       AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)
                sqlstmt = "SELECT CA.*, SC.SERVICE_NAME ,CT.CARGO_DESCRIPTION" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT" _
                        & " WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " ORDER BY DATE_OF_ACTIVITY"
                
                sqlstmt1 = " select count(distinct pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY  WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "' AND CUSTOMER_ID= '" & Trim(txtCustId) & "' " _
                        & " AND SERVICE_CODE <>'12'" _
                        & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')"
                
                'sqlstmt1 = " select count(distinct ct.pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " PERSONNEL PERS WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CA.ACTIVITY_ID = PERS.EMPLOYEE_ID "
                        
        Case 1 'DELIVERY
                ',NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), '') LOGIN_ID     EMPLOYEE PERS       AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)
                sqlstmt = "SELECT CA.*, SC.SERVICE_NAME ,CT.CARGO_DESCRIPTION" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE='6' AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " ORDER BY DATE_OF_ACTIVITY"
                        
                sqlstmt1 = " select count(distinct pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY  WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "' AND CUSTOMER_ID= '" & Trim(txtCustId) & "' " _
                        & " AND SERVICE_CODE <>'12'" _
                        & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')"
                
                'sqlstmt1 = " select count(distinct ct.pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " PERSONNEL PERS WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE='6' AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.ACTIVITY_ID = PERS.EMPLOYEE_ID "
        Case 2 'RETURN
                ',NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), '') LOGIN_ID     EMPLOYEE PERS       AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)
                sqlstmt = "SELECT CA.*, SC.SERVICE_NAME ,CT.CARGO_DESCRIPTION" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE ='7'  AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " ORDER BY DATE_OF_ACTIVITY"
                
                sqlstmt1 = " select count(distinct pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY  WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "' AND CUSTOMER_ID= '" & Trim(txtCustId) & "' " _
                        & " AND SERVICE_CODE <>'12'" _
                        & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')"
                
                'sqlstmt1 = " select count(distinct ct.pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " PERSONNEL PERS WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE ='7'  AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.ACTIVITY_ID = PERS.EMPLOYEE_ID "
            
            
        Case 3 'FROMPORT
                    ',NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), '') LOGIN_ID     EMPLOYEE PERS       AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)
                    sqlstmt = " SELECT CA.*, SC.SERVICE_NAME ,CT.CARGO_DESCRIPTION" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE='8' AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " ORDER BY DATE_OF_ACTIVITY"
            
                    sqlstmt1 = " select count(distinct pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY  WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "' AND CUSTOMER_ID= '" & Trim(txtCustId) & "' " _
                        & " AND SERVICE_CODE <>'12'" _
                        & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')"
                    
                    'sqlstmt1 = " select count(distinct ct.pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " PERSONNEL PERS WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE='8' AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.ACTIVITY_ID = PERS.EMPLOYEE_ID "
                    
            
        Case 4 'RECOUP
                ',NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), '') LOGIN_ID     EMPLOYEE PERS       AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)
                sqlstmt = "SELECT CA.*, SC.SERVICE_NAME ,CT.CARGO_DESCRIPTION" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE='9' AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " ORDER BY DATE_OF_ACTIVITY"
                        
                 sqlstmt1 = " select count(distinct pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY  WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "' AND CUSTOMER_ID= '" & Trim(txtCustId) & "' " _
                        & " AND SERVICE_CODE <>'12'" _
                        & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')"
                
                'sqlstmt1 = " select count(distinct ct.pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " PERSONNEL PERS WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE='9' AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.ACTIVITY_ID = PERS.EMPLOYEE_ID "
                
            
        Case 5 'VOID
                ',NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), '') LOGIN_ID     EMPLOYEE PERS       AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)
                sqlstmt = "SELECT CA.*, SC.SERVICE_NAME ,CT.CARGO_DESCRIPTION" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE='12' AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " ORDER BY DATE_OF_ACTIVITY"
                        
                sqlstmt1 = " select count(distinct pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY  WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "' AND CUSTOMER_ID= '" & Trim(txtCustId) & "' " _
                        & " AND SERVICE_CODE <>'12'" _
                        & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')"
                
                'sqlstmt1 = " select count(distinct ct.pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " PERSONNEL PERS WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE='12' AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.ACTIVITY_ID = PERS.EMPLOYEE_ID "
                
        Case 6    'Dock return
                ',NVL(SUBSTR(EMPLOYEE_NAME, 0, 8), '') LOGIN_ID     EMPLOYEE PERS       AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)
               sqlstmt = "SELECT CA.*, SC.SERVICE_NAME ,CT.CARGO_DESCRIPTION" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE ='13' AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " ORDER BY DATE_OF_ACTIVITY"
                        
                sqlstmt1 = " select count(distinct pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY  WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "' AND CUSTOMER_ID= '" & Trim(txtCustId) & "' " _
                        & " AND SERVICE_CODE <>'12'" _
                        & " AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION <> 'VOID')"
                
                'sqlstmt1 = " select count(distinct ct.pallet_id) sum" _
                        & " FROM CARGO_ACTIVITY CA,SERVICE_CATEGORY SC,CARGO_TRACKING CT," _
                        & " PERSONNEL PERS WHERE ORDER_NUM ='" & Trim$(txtOrderNum.Text) _
                        & "'AND CUSTOMER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.SERVICE_CODE ='13' AND CA.SERVICE_CODE = SC.SERVICE_CODE " _
                        & " AND CA.PALLET_ID=CT.PALLET_ID " _
                        & " AND CA.ARRIVAL_NUM=CT.ARRIVAL_NUM " _
                        & " AND CT.RECEIVER_ID='" & Trim(txtCustId) & "' " _
                        & " AND CA.ACTIVITY_ID = PERS.EMPLOYEE_ID "
                        
            
    End Select
    Set dsPallet = OraDatabase.dbcreatedynaset(sqlstmt1, 0&)
    If OraDatabase.lastservererr = 0 And dsPallet.recordcount > 0 Then
        plt.Caption = dsPallet.fields("sum").Value
    Else
        MsgBox "NO RECORD FOUND ", vbInformation, "Pallet ID"
        Exit Sub
    End If
    
    
    Set dsCARGO_ACT = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    
    If OraDatabase.lastservererr = 0 And dsCARGO_ACT.recordcount > 0 Then
        
        With ssdbgOrdDtls
            .CaptionAlignment = ssCaptionAlignmentCenter
            .Caption = "PALLET DETAILS"
        End With
        
        For iRec = 1 To dsCARGO_ACT.recordcount
            If Not IsNull(dsCARGO_ACT.fields("ACTIVITY_DESCRIPTION").Value) Then
                sActDes = dsCARGO_ACT.fields("ACTIVITY_DESCRIPTION").Value
            Else
                sActDes = ""
            End If
            
            sqlstmt = " SELECT * FROM CARGO_TRACKING WHERE PALLET_ID=" _
                     & " '" & dsCARGO_ACT.fields("PALLET_ID").Value & "'" _
                     & " AND ARRIVAL_NUM= '" & dsCARGO_ACT.fields("ARRIVAL_NUM").Value & "'" _
                     & "AND RECEIVER_ID = " & dsCARGO_ACT.fields("CUSTOMER_ID").Value
              
            Set dsCargo_Tracking = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
            
            With dsCARGO_ACT
                '.Fields("LOGIN_ID").Value
                ssdbgOrdDtls.AddItem Format(.fields("DATE_OF_ACTIVITY").Value, "MM/DD/YYYY") + _
                    Chr$(9) + Format(.fields("DATE_OF_ACTIVITY").Value, "HH:MM:SS") + _
                    Chr$(9) + .fields("SERVICE_NAME").Value + _
                    Chr$(9) + get_checker_name(.fields("PALLET_ID").Value, .fields("CUSTOMER_ID").Value, .fields("ARRIVAL_NUM").Value, .fields("ACTIVITY_NUM").Value) + _
                    Chr$(9) + .fields("PALLET_ID").Value + _
                    Chr$(9) + .fields("QTY_CHANGE").Value + _
                    Chr$(9) + sActDes + _
                    Chr$(9) + dsCargo_Tracking.fields("QTY_IN_HOUSE").Value + _
                    Chr$(9) + dsCargo_Tracking.fields("QTY_RECEIVED").Value + _
                    Chr$(9) + .fields("SERVICE_CODE").Value + _
                    Chr$(9) + Trim("" & .fields("ACTIVITY_NUM").Value) + _
                    Chr$(9) + Trim("" & .fields("ACTIVITY_BILLED").Value) + _
                    Chr$(9) + Trim("" & .fields("CARGO_DESCRIPTION").Value) + _
                    Chr$(9) + _
                    Chr$(9) + Trim("" & .fields("ARRIVAL_NUM").Value)
                    
                    
                .MoveNext
            End With
        Next iRec
        ssdbgOrdDtls.MoveFirst
    Else
        If OraDatabase.lastservererr <> 0 Then
            MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation
            Exit Sub
        End If
                
        MsgBox "NO RECORD(S) FOUND ", vbInformation, "ORDER NUMBER"
               
        'cboAction.SetFocus
        Call Clear_Screen
        Exit Sub
    End If
    
    Me.Refresh
End Sub


Private Sub ssdbgOrdDtls_BeforeDelete(Cancel As Integer, DispPromptMsg As Integer)
    Dim sqlstmt As String
    Dim iNewQtyRecvd As Integer
    Dim iNewQtyINH  As Integer
    Dim iOldQtyRecvd As Integer
    Dim iOldQtyINH As Integer
    Dim iQty As Integer
    
    DispPromptMsg = 0
    iNewQtyRecvd = 0
    iNewQtyINH = 0
    
    iOldQtyRecvd = ssdbgOrdDtls.Columns(8).Value
    iOldQtyINH = ssdbgOrdDtls.Columns(7).Value
    iQty = Trim(ssdbgOrdDtls.Columns(5).Value)
    
    Select Case Trim(ssdbgOrdDtls.Columns(2).Value)
        
        Case "Delivery"
                
            iNewQtyINH = iOldQtyINH - iQty
            iNewQtyRecvd = iOldQtyRecvd
            
            If Val(iNewQtyINH) > Val(iOldQtyRecvd) Then
            MsgBox "Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                              "Make the Quantity InHouse and Quantity Received appropriate before deleteing", vbInformation, "PALLET CORRECTION"
                  ssdbgOrdDtls.Columns(6).Value = iQty
                  Cancel = True
                  Exit Sub
            End If
                
        Case "Return", "Dock Return"     'RETURN
            iNewQtyINH = iOldQtyINH - Abs(iQtyOld)
            iNewQtyRecvd = iOldQtyRecvd - Abs(iQtyOld)
                
            If Val(iNewQtyINH) > Val(iNewQtyRecvd) Then
                MsgBox "Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                          "Make the Quantity InHouse and Quantity Received appropriate before deleteing", vbInformation, "PALLET CORRECTION"
                Cancel = True
                Exit Sub
            End If
               
        Case "FromPort"    'FROM PORT
            
                iNewQtyINH = iOldQtyINH - iQty
                iNewQtyRecvd = iOldQtyRecvd - iQty
                                
                If Val(iNewQtyINH) > Val(iNewQtyRecvd) Then
                    MsgBox "Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                              "Make the Quantity InHouse and Quantity Received appropriate before deleteing", vbInformation, "PALLET CORRECTION"
                    Cancel = True
                    Exit Sub
                End If
                
            Case "Recoup"    'RECOUP
            
                iNewQtyINH = iOldQtyINH - iQty
                iNewQtyRecvd = iOldQtyRecvd
                
                If Val(iNewQtyINH) > Val(iOldQtyRecvd) Then
                    MsgBox "Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                              "Make the Quantity InHouse and Quantity Received appropriate before deleteing", vbInformation, "PALLET CORRECTION"
                    Cancel = True
                    Exit Sub
                    
                End If
    
            Case "Void"   'VOID
            
                iNewQtyINH = iOldQtyINH - Abs(iQty)
                iNewQtyRecvd = iOldQtyRecvd - Abs(iQty)
                
                If Val(iNewQtyINH) > Val(iOldQtyRecvd) Then
                    MsgBox "Quantity InHouse cannot be more then the quantity received !." & vbCrLf & _
                            "Make the Quantity InHouse and Quantity Received appropriate before deleteing", vbInformation, "PALLET CORRECTION"
                    Cancel = True
                    Exit Sub
                End If
        End Select
        
        If iNewQtyINH < 0 Then
            MsgBox "InHouse Quantity cannot be equal to or less then zero." & vbCrLf & _
                "Make the Quantity InHouse and Quantity Received appropriate before deleteing", vbInformation, "PALLET CORRECTION"
            Cancel = True
            Exit Sub
        End If
       
    OraSession.begintrans
    'CONFIRM THIS 2 Queries -RAMESH
    'sqlstmt = "UPDATE CARGO_TRACKING SET QTY_IN_HOUSE='" & iNewQtyINH & "',QTY_RECEIVED=" _
            & "'" & iNewQtyRecvd & "' where LOT_NUM='" & Trim(ssdbgOrdDtls.Columns(4).Value) & "'"
    
    sqlstmt = " UPDATE CARGO_TRACKING SET QTY_IN_HOUSE='" & iNewQtyINH & "',QTY_RECEIVED=" & _
              "'" & iNewQtyRecvd & "' where PALLET_ID='" & Trim(ssdbgOrdDtls.Columns(4).Value) & "'" & _
              " AND RECEIVER_ID =" & Trim(txtCustId) & " AND ARRIVAL_NUM = '" & Trim(ssdbgOrdDtls.Columns(14).Value) & "'"
    
    OraDatabase.DbExecuteSQL (sqlstmt)

    sqlstmt = " DELETE FROM CARGO_ACTIVITY WHERE CUSTOMER_ID='" & txtCustId & "'" & _
              " AND ORDER_NUM='" & txtOrderNum & "'" & _
              " AND PALLET_ID ='" & Trim(ssdbgOrdDtls.Columns(4).Value) & "'" & _
              " AND ARRIVAL_NUM = '" & Trim(ssdbgOrdDtls.Columns(14).Value) & "'" & _
              " AND ACTIVITY_NUM='" & Trim(ssdbgOrdDtls.Columns(10).Value) & "'"
            
    OraDatabase.DbExecuteSQL (sqlstmt)
    
    If OraDatabase.lastservererr = 0 Then
        OraSession.committrans
        Call Show_record
    Else
        OraSession.rollback
        Cancel = True
        cmdCancel_Click
    End If
        
End Sub

Private Sub ssdbgOrdDtls_Click()
    If ssdbgOrdDtls.Columns(5).Value <> "" Then
        iQtyOld = ssdbgOrdDtls.Columns(5).Value
    End If
End Sub

Private Sub txtCustId_Validate(Cancel As Boolean)
    Dim sqlstmt As String
    Dim dsCUSTID As Object
    
    If txtCustId = "" Then Exit Sub
    
    If Not IsNumeric(txtCustId) Then
        MsgBox "Not a valid Id", vbInformation, "ORDER DETAIL"
        txtCustId = ""
        Cancel = True
        Exit Sub
    End If
                                                                                                                                                                                                                                                               
    sqlstmt = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID= " _
             & " '" & Trim(txtCustId) & "'"
    Set dsCUSTID = OraDatabase.dbcreatedynaset(sqlstmt, 0&)
    If OraDatabase.lastservererr = 0 And dsCUSTID.recordcount > 0 Then
        txtCustName = dsCUSTID.fields("CUSTOMER_NAME").Value
        Cancel = False
    Else
        If OraDatabase.lastservererr <> 0 Then
            MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation
            Exit Sub
        End If
        MsgBox "INVALID CUSTOMER ID !", vbInformation + vbExclamation, "CUSTOMER"
        txtCustId = ""
        Cancel = True
    End If
End Sub

Private Sub txtCustName_KeyPress(KeyAscii As Integer)
    KeyAscii = 0
End Sub

Private Sub txtOrderNum_GotFocus()
    txtOrderNum.SelStart = 0
    txtOrderNum.SelLength = Len(txtOrderNum)
End Sub

Private Sub txtOrderNum_LostFocus()

    txtOrderNum.Text = UCase(txtOrderNum.Text)

End Sub


