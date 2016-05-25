VERSION 5.00
Object = "{1C0489F8-9EFD-423D-887A-315387F18C8F}#1.0#0"; "vsflex8l.ocx"
Begin VB.Form frm_Purge 
   Caption         =   "Form1"
   ClientHeight    =   5895
   ClientLeft      =   60
   ClientTop       =   405
   ClientWidth     =   11160
   LinkTopic       =   "Form1"
   ScaleHeight     =   5895
   ScaleWidth      =   11160
   StartUpPosition =   3  'Windows Default
   Begin VB.CommandButton cmdCancel 
      Cancel          =   -1  'True
      Caption         =   "Cancel"
      Height          =   375
      Left            =   9960
      TabIndex        =   1
      Top             =   5400
      Width           =   1095
   End
   Begin VB.CommandButton cmdOK 
      Caption         =   "OK"
      Height          =   375
      Left            =   8400
      TabIndex        =   0
      Top             =   5400
      Width           =   1095
   End
   Begin VSFlex8LCtl.VSFlexGrid fg_Delete 
      Height          =   4815
      Left            =   120
      TabIndex        =   3
      Top             =   480
      Width           =   10920
      _cx             =   19262
      _cy             =   8493
      Appearance      =   0
      BorderStyle     =   0
      Enabled         =   -1  'True
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Tahoma"
         Size            =   8.25
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      MousePointer    =   0
      BackColor       =   -2147483643
      ForeColor       =   -2147483640
      BackColorFixed  =   -2147483633
      ForeColorFixed  =   -2147483630
      BackColorSel    =   -2147483635
      ForeColorSel    =   -2147483634
      BackColorBkg    =   8421504
      BackColorAlternate=   -2147483643
      GridColor       =   14737632
      GridColorFixed  =   0
      TreeColor       =   -2147483632
      FloodColor      =   192
      SheetBorder     =   0
      FocusRect       =   1
      HighLight       =   1
      AllowSelection  =   -1  'True
      AllowBigSelection=   -1  'True
      AllowUserResizing=   0
      SelectionMode   =   3
      GridLines       =   1
      GridLinesFixed  =   12
      GridLineWidth   =   1
      Rows            =   1
      Cols            =   1
      FixedRows       =   1
      FixedCols       =   0
      RowHeightMin    =   0
      RowHeightMax    =   0
      ColWidthMin     =   0
      ColWidthMax     =   0
      ExtendLastCol   =   -1  'True
      FormatString    =   ""
      ScrollTrack     =   0   'False
      ScrollBars      =   3
      ScrollTips      =   0   'False
      MergeCells      =   6
      MergeCompare    =   0
      AutoResize      =   -1  'True
      AutoSizeMode    =   0
      AutoSearch      =   0
      AutoSearchDelay =   2
      MultiTotals     =   -1  'True
      SubtotalPosition=   1
      OutlineBar      =   0
      OutlineCol      =   0
      Ellipsis        =   0
      ExplorerBar     =   3
      PicturesOver    =   0   'False
      FillStyle       =   0
      RightToLeft     =   0   'False
      PictureType     =   0
      TabBehavior     =   0
      OwnerDraw       =   0
      Editable        =   0
      ShowComboButton =   1
      WordWrap        =   0   'False
      TextStyle       =   0
      TextStyleFixed  =   0
      OleDragMode     =   0
      OleDropMode     =   0
      ComboSearch     =   3
      AutoSizeMouse   =   -1  'True
      FrozenRows      =   0
      FrozenCols      =   0
      AllowUserFreezing=   0
      BackColorFrozen =   0
      ForeColorFrozen =   0
      WallPaperAlignment=   9
      AccessibleName  =   ""
      AccessibleDescription=   ""
      AccessibleValue =   ""
      AccessibleRole  =   24
   End
   Begin VB.Label lbl_Warning 
      Caption         =   "The Following xx pallets will be removed from the manifest. Press OK to confirm."
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   120
      TabIndex        =   2
      Top             =   120
      Width           =   10935
   End
   Begin VB.Menu mnu_Popup 
      Caption         =   "mnu_Popup"
      Visible         =   0   'False
      Begin VB.Menu mnu_Print 
         Caption         =   "Print Grid"
      End
   End
End
Attribute VB_Name = "frm_Purge"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False

Dim str_Where As String
Dim str_Fields As String
Dim str_Arr_num As String
Dim str_Cust_id As String

Public Sub LocalReset()
 str_Where = ""
 str_Fields = ""
 str_Arr_num = ""
 str_Cust_id = ""
 fg_Delete.Rows = 1
End Sub

Public Property Get WHERE() As String
    WHERE = str_Where
End Property

Public Property Let WHERE(ByVal vNewValue As String)
    str_Where = vNewValue
End Property

Public Property Get DFIELDS() As String
   DFIELDS = str_Fields
End Property

Public Property Let DFIELDS(ByVal vNewValue As String)
  str_Fields = vNewValue
End Property

Public Property Get ARRIVAL_NUM() As String
    ARRIVAL_NUM = str_Arr_num
End Property

Public Property Let ARRIVAL_NUM(ByVal vNewValue As String)
    str_Arr_num = vNewValue
End Property

Public Property Get CUST_ID() As String
    CUST_ID = str_Cust_id
End Property

Public Property Let CUST_ID(ByVal vNewValue As String)
    str_Cust_id = vNewValue
End Property

Public Sub ViewDeleted()
Dim str_sql As String
str_sql = "SELECT " & str_Fields & ", PALLET_ID ID FROM CARGO_TRACKING " & str_Where
If Len(str_sql) = 0 Then Exit Sub
    FillGroup fg_Delete, str_sql, fg_Delete.Cols
    fg_Delete.Redraw = flexRDDirect
    If fg_Delete.Rows > 1 Then
        lbl_Warning.Caption = "The following " & Str(fg_Delete.Rows - 1) & " pallets will be removed from the manifest. Press OK to confirm."
        cmdOK.Enabled = True
    Else
        lbl_Warning.Caption = "No pallets marked for delete."
        cmdOK.Enabled = False
    End If
    lbl_Warning.FontBold = True
End Sub

Private Sub cmdCancel_Click()
    Unload Me
End Sub


Private Sub cmdOK_Click()
Dim int_resp As Integer
Dim str_sql As String
Dim int_RecUpd As Integer

int_resp = MsgBox("Are you sure to remove the pallets. This is the final warning!", vbQuestion + vbYesNo, "Confirm Delete")

If int_resp = vbNo Then Exit Sub

str_sql = "DELETE FROM CARGO_TRACKING " & str_Where

Screen.MousePointer = vbHourglass
ordb.BeginTrans

ordb.Execute str_sql, int_RecUpd
Screen.MousePointer = vbDefault
If int_RecUpd = fg_Delete.Rows - 1 Then
    ordb.CommitTrans
    MsgBox "Delete Successful!", vbOKOnly, "Update"
    Save_a_Copy
    Me.Hide
Else
    ordb.RollbackTrans
    MsgBox int_RecUpd & " records count does not match! No Data changes!", vbOKOnly, "Revert"
End If
Exit Sub

errHandler:
ordb.RollbackTrans
MsgBox Err.Description, vbOKOnly, "Unexpected Error (cmdOK_Click)"
End Sub

Private Sub fg_Delete_MouseUp(Button As Integer, Shift As Integer, X As Single, Y As Single)
    If Button = 2 Then PopupMenu mnu_PopUp
End Sub

Private Sub Form_Resize()
On Error Resume Next
fg_Delete.Move 120, cmdCancel.Height * 2 + 240, Me.ScaleWidth - 240, Me.ScaleHeight - cmdCancel.Height * 2 - 360
cmdCancel.Move Me.ScaleWidth - cmdCancel.Width - 120, cmdCancel.Height + 120
cmdOK.Move cmdCancel.left - cmdOK.Width - 120, cmdOK.Height + 120

End Sub

Private Sub mnu_Print_Click()
    fg_Delete.PrintGrid "Deleted records from Cargo Tracking " & str_Where, True, 2
End Sub

Private Sub Save_a_Copy()
Dim str_file_name As String
Dim fso As New FileSystemObject

If Not fso.FolderExists(App.Path & "\PALLETE_BCK") Then fso.CreateFolder (App.Path & "\PALLETE_BCK")

str_file_name = str_Arr_num
If Len(str_Cust_id) > 0 Then str_file_name = str_file_name & "_" & Trim(str_Cust_id) & "_"
    str_file_name = App.Path & "\PALLETE_BCK\DEL" & str_file_name & right("0" & Trim(Str(Hour(Now()))), 2) & right("0" & Trim(Str(Minute(Now()))), 2)
    fg_Delete.SaveGrid str_file_name & ".xls", flexFileExcel, flexXLSaveFixedCells Or flexXLSaveRaw
End Sub

