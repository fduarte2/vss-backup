VERSION 5.00
Object = "{1C0489F8-9EFD-423D-887A-315387F18C8F}#1.0#0"; "vsflex8l.ocx"
Object = "{831FDD16-0C5C-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCTL.OCX"
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "comdlg32.ocx"
Begin VB.Form frm_LocationUpdater 
   BackColor       =   &H00FFFFFF&
   Caption         =   "POWNS Operations"
   ClientHeight    =   10845
   ClientLeft      =   165
   ClientTop       =   855
   ClientWidth     =   16695
   Icon            =   "frm_LocationUpdater.frx":0000
   LinkTopic       =   "Form1"
   ScaleHeight     =   10845
   ScaleWidth      =   16695
   StartUpPosition =   3  'Windows Default
   Begin POWNSOP.OSizer ct_OSizerTop 
      Height          =   8535
      Left            =   360
      TabIndex        =   1
      Top             =   600
      Width           =   11535
      _ExtentX        =   20346
      _ExtentY        =   15055
      InitialPosition =   35
      SplitBarWidth   =   80
      Horizontal      =   -1  'True
      SplitColour     =   16777215
      DragColour      =   16777152
      BackColour      =   16777215
      FancyBar        =   0   'False
      ExpandOneEachHalf=   -1  'True
      SizerOnly       =   0   'False
      PosUnits        =   0
      LTLimit         =   0
      RBLimit         =   0
      Begin POWNSOP.vsfGroup fg_Group 
         Height          =   2175
         Left            =   0
         TabIndex        =   22
         Top             =   6360
         Width           =   5895
         _ExtentX        =   10398
         _ExtentY        =   3836
      End
      Begin VB.Frame fra_Top 
         BackColor       =   &H00FFFFFF&
         BorderStyle     =   0  'None
         Height          =   2895
         Left            =   0
         TabIndex        =   2
         Top             =   0
         Width           =   9375
         Begin VB.Frame fraFilter 
            BackColor       =   &H00FFFFFF&
            Caption         =   "Filter and Sort"
            Height          =   2055
            Left            =   0
            TabIndex        =   13
            Top             =   1680
            Width           =   9375
            Begin VB.CheckBox chk_inhouse_only 
               BackColor       =   &H00FFFFFF&
               Caption         =   "In House Only"
               BeginProperty Font 
                  Name            =   "MS Sans Serif"
                  Size            =   9.75
                  Charset         =   0
                  Weight          =   400
                  Underline       =   0   'False
                  Italic          =   0   'False
                  Strikethrough   =   0   'False
               EndProperty
               Height          =   255
               Left            =   7800
               TabIndex        =   23
               Top             =   240
               Value           =   1  'Checked
               Width           =   1575
            End
            Begin VB.CheckBox chkAutoRefresh 
               BackColor       =   &H80000009&
               Caption         =   "Auto Refresh Grid"
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
               Left            =   7800
               TabIndex        =   19
               Top             =   600
               Width           =   2295
            End
            Begin VB.CommandButton cmd_Refresh 
               Caption         =   "Refresh"
               BeginProperty Font 
                  Name            =   "MS Sans Serif"
                  Size            =   9.75
                  Charset         =   0
                  Weight          =   400
                  Underline       =   0   'False
                  Italic          =   0   'False
                  Strikethrough   =   0   'False
               EndProperty
               Height          =   615
               Left            =   7800
               TabIndex        =   18
               Top             =   1080
               Width           =   1455
            End
            Begin VB.CommandButton cmdRemAll 
               Caption         =   "<<"
               Height          =   255
               Left            =   3480
               TabIndex        =   17
               Top             =   1320
               Width           =   495
            End
            Begin VB.CommandButton cmdAddAll 
               Caption         =   ">>"
               Height          =   255
               Left            =   3480
               TabIndex        =   16
               Top             =   360
               Width           =   495
            End
            Begin VB.CommandButton cmd_Rem 
               Caption         =   "<"
               Height          =   255
               Left            =   3480
               TabIndex        =   15
               Top             =   1000
               Width           =   495
            End
            Begin VB.CommandButton cmd_Add 
               Caption         =   ">"
               Height          =   255
               Left            =   3480
               TabIndex        =   14
               Top             =   680
               Width           =   495
            End
            Begin VSFlex8LCtl.VSFlexGrid fgFields 
               Height          =   1215
               Left            =   120
               TabIndex        =   20
               Top             =   240
               Width           =   3255
               _cx             =   5741
               _cy             =   2143
               Appearance      =   1
               BorderStyle     =   1
               Enabled         =   -1  'True
               BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
                  Name            =   "MS Sans Serif"
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
               BackColorBkg    =   -2147483636
               BackColorAlternate=   -2147483643
               GridColor       =   -2147483633
               GridColorFixed  =   -2147483632
               TreeColor       =   -2147483632
               FloodColor      =   192
               SheetBorder     =   -2147483642
               FocusRect       =   1
               HighLight       =   1
               AllowSelection  =   -1  'True
               AllowBigSelection=   -1  'True
               AllowUserResizing=   0
               SelectionMode   =   1
               GridLines       =   1
               GridLinesFixed  =   2
               GridLineWidth   =   1
               Rows            =   1
               Cols            =   2
               FixedRows       =   1
               FixedCols       =   0
               RowHeightMin    =   0
               RowHeightMax    =   0
               ColWidthMin     =   0
               ColWidthMax     =   0
               ExtendLastCol   =   -1  'True
               FormatString    =   $"frm_LocationUpdater.frx":1782
               ScrollTrack     =   0   'False
               ScrollBars      =   3
               ScrollTips      =   0   'False
               MergeCells      =   0
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
               ExplorerBar     =   0
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
            Begin VSFlex8LCtl.VSFlexGrid fgSelection 
               Height          =   1455
               Left            =   4080
               TabIndex        =   21
               Top             =   240
               Width           =   3495
               _cx             =   6165
               _cy             =   2566
               Appearance      =   1
               BorderStyle     =   1
               Enabled         =   -1  'True
               BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
                  Name            =   "MS Sans Serif"
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
               BackColorBkg    =   -2147483636
               BackColorAlternate=   -2147483643
               GridColor       =   -2147483633
               GridColorFixed  =   -2147483632
               TreeColor       =   -2147483632
               FloodColor      =   192
               SheetBorder     =   -2147483642
               FocusRect       =   1
               HighLight       =   1
               AllowSelection  =   -1  'True
               AllowBigSelection=   -1  'True
               AllowUserResizing=   0
               SelectionMode   =   1
               GridLines       =   1
               GridLinesFixed  =   2
               GridLineWidth   =   1
               Rows            =   1
               Cols            =   4
               FixedRows       =   1
               FixedCols       =   1
               RowHeightMin    =   0
               RowHeightMax    =   0
               ColWidthMin     =   0
               ColWidthMax     =   0
               ExtendLastCol   =   -1  'True
               FormatString    =   $"frm_LocationUpdater.frx":17C8
               ScrollTrack     =   0   'False
               ScrollBars      =   3
               ScrollTips      =   0   'False
               MergeCells      =   0
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
               ExplorerBar     =   1
               PicturesOver    =   0   'False
               FillStyle       =   0
               RightToLeft     =   0   'False
               PictureType     =   0
               TabBehavior     =   0
               OwnerDraw       =   0
               Editable        =   2
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
            Begin VB.Image Image5 
               Height          =   1080
               Left            =   120
               Picture         =   "frm_LocationUpdater.frx":1834
               Top             =   240
               Visible         =   0   'False
               Width           =   1035
            End
         End
         Begin VB.Frame fraVessels 
            BackColor       =   &H00FFFFFF&
            Height          =   1695
            Left            =   120
            TabIndex        =   3
            Top             =   0
            Width           =   9135
            Begin VB.TextBox txtLocation 
               Height          =   375
               Left            =   5640
               TabIndex        =   7
               Top             =   1080
               Width           =   1935
            End
            Begin VB.CommandButton cmd_UPD_Selection 
               Caption         =   "Update Selection"
               BeginProperty Font 
                  Name            =   "MS Sans Serif"
                  Size            =   9.75
                  Charset         =   0
                  Weight          =   400
                  Underline       =   0   'False
                  Italic          =   0   'False
                  Strikethrough   =   0   'False
               EndProperty
               Height          =   615
               Left            =   7800
               TabIndex        =   6
               Top             =   840
               Width           =   1455
            End
            Begin VB.ComboBox cmb_vessel 
               Height          =   315
               Left            =   240
               TabIndex        =   5
               Top             =   480
               Width           =   5055
            End
            Begin VB.ComboBox cmb_Customer 
               Height          =   315
               Left            =   240
               TabIndex        =   4
               Top             =   1080
               Width           =   5055
            End
            Begin VB.Image Image4 
               Height          =   1080
               Left            =   11280
               Picture         =   "frm_LocationUpdater.frx":3A51
               Top             =   240
               Width           =   1035
            End
            Begin VB.Label lbl_Location 
               BackColor       =   &H00FFFFFF&
               Caption         =   "New Location"
               BeginProperty Font 
                  Name            =   "MS Sans Serif"
                  Size            =   9.75
                  Charset         =   0
                  Weight          =   400
                  Underline       =   0   'False
                  Italic          =   0   'False
                  Strikethrough   =   0   'False
               EndProperty
               Height          =   255
               Left            =   5640
               TabIndex        =   12
               Top             =   840
               Width           =   1935
            End
            Begin VB.Label lbl_Records 
               BackColor       =   &H80000009&
               Caption         =   "Number of Pallets:"
               BeginProperty Font 
                  Name            =   "MS Sans Serif"
                  Size            =   9.75
                  Charset         =   0
                  Weight          =   700
                  Underline       =   0   'False
                  Italic          =   0   'False
                  Strikethrough   =   0   'False
               EndProperty
               Height          =   255
               Left            =   5640
               TabIndex        =   11
               Top             =   120
               Width           =   3615
            End
            Begin VB.Label lbl_Selection 
               BackColor       =   &H00FFFFFF&
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
               Left            =   5640
               TabIndex        =   10
               Top             =   480
               Width           =   3615
            End
            Begin VB.Label lbl_Customer 
               BackColor       =   &H00FFFFFF&
               Caption         =   "Select Customer"
               BeginProperty Font 
                  Name            =   "MS Sans Serif"
                  Size            =   9.75
                  Charset         =   0
                  Weight          =   400
                  Underline       =   0   'False
                  Italic          =   0   'False
                  Strikethrough   =   0   'False
               EndProperty
               Height          =   255
               Left            =   240
               TabIndex        =   9
               Top             =   840
               Width           =   3375
            End
            Begin VB.Label lbl_Vessel 
               BackColor       =   &H80000009&
               Caption         =   "Select Vessel "
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
               Left            =   240
               TabIndex        =   8
               Top             =   240
               Width           =   2295
            End
         End
      End
   End
   Begin MSComDlg.CommonDialog cdlImportFile 
      Left            =   120
      Top             =   0
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
   End
   Begin MSComctlLib.StatusBar sb_Status 
      Align           =   2  'Align Bottom
      Height          =   255
      Left            =   0
      TabIndex        =   0
      Top             =   10590
      Width           =   16695
      _ExtentX        =   29448
      _ExtentY        =   450
      _Version        =   393216
      BeginProperty Panels {8E3867A5-8586-11D1-B16A-00C0F0283628} 
         NumPanels       =   3
         BeginProperty Panel1 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
            Object.Width           =   4410
            MinWidth        =   4410
            Text            =   "Status"
            TextSave        =   "Status"
            Key             =   "pan_status"
         EndProperty
         BeginProperty Panel2 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
            Style           =   6
            Alignment       =   2
            TextSave        =   "7/18/2006"
         EndProperty
         BeginProperty Panel3 {8E3867AB-8586-11D1-B16A-00C0F0283628} 
            Text            =   "Disconnected."
            TextSave        =   "Disconnected."
            Key             =   "key_db"
         EndProperty
      EndProperty
   End
   Begin VB.Menu mnu_file 
      Caption         =   "File"
      Begin VB.Menu mnu_Open 
         Caption         =   "Open Saved Configuration"
      End
      Begin VB.Menu mnu_Save 
         Caption         =   "Save Configuration"
      End
      Begin VB.Menu mnu_sep1 
         Caption         =   "-"
      End
      Begin VB.Menu mnu_exit 
         Caption         =   "Exit"
      End
   End
   Begin VB.Menu mnu_Tools 
      Caption         =   "Tools"
      Begin VB.Menu mnu_Print 
         Caption         =   "Print Grid"
      End
      Begin VB.Menu mnu_Sep4 
         Caption         =   "-"
      End
      Begin VB.Menu mnu_Database 
         Caption         =   "Database Select"
      End
      Begin VB.Menu mnu_Format 
         Caption         =   "Format"
         Begin VB.Menu mnu_Frmt_Date 
            Caption         =   "Date (mm/dd/yyyy)"
         End
         Begin VB.Menu mnu_Frmt_Date_Hour 
            Caption         =   "Date (mm/dd/yy HH:MM)"
         End
      End
      Begin VB.Menu mnu_sep2 
         Caption         =   "-"
      End
      Begin VB.Menu mnu_Manifest 
         Caption         =   "Manifest"
      End
      Begin VB.Menu mnuSep3 
         Caption         =   "-"
      End
      Begin VB.Menu mnu_Purge 
         Caption         =   "Purge Deleted Pallets"
      End
   End
   Begin VB.Menu mnu_Help 
      Caption         =   "Help"
      Begin VB.Menu mnu_Content 
         Caption         =   "Contents..."
      End
      Begin VB.Menu mnu_Sep3 
         Caption         =   "-"
      End
      Begin VB.Menu mnu_About 
         Caption         =   "About POWNS"
      End
   End
End
Attribute VB_Name = "frm_LocationUpdater"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Dim str_WhereStatement As String
Dim str_Pallet_List As String
Dim lng_G_Count As Long
Dim lng_L_Count As Long
Dim str_DWhere As String
Dim str_DQuery As String
Dim WithEvents mnu_Popup As Menu
Attribute mnu_Popup.VB_VarHelpID = -1


Private Sub cmd_Add_Click()
Dim int_row As Integer
Dim top As Integer
Dim bottom As Integer
Dim i As Integer
Dim r As Integer
If fgFields.Row < fgFields.RowSel Then
    top = fgFields.Row
    bottom = fgFields.RowSel
Else
    top = fgFields.RowSel
    bottom = fgFields.Row
End If
For int_row = top To bottom
    If Not fgFields.RowHidden(int_row) Then
        fgFields.RowHidden(int_row) = Not fgFields.RowHidden(int_row)
        i = 1
        r = 0
        Do While r = 0 And i < fgSelection.Rows
            If CLng(fgSelection.TextMatrix(i, 0)) = int_row Then
                r = i
                fgSelection.RowHidden(r) = Not fgSelection.RowHidden(r)
            End If
            i = i + 1
        Loop
        
    End If
Next int_row
fgSelection.AutoSize 1, 3
If chkAutoRefresh.Value = vbChecked Then Call cmd_Refresh_Click
End Sub

Private Sub cmd_Refresh_Click()
 Call Load_Grid
End Sub

Private Sub cmd_Rem_Click()
Dim int_row As Integer
Dim top As Integer
Dim bottom As Integer
If fgSelection.Row < fgSelection.RowSel Then
    top = fgSelection.Row
    bottom = fgSelection.RowSel
Else
    top = fgSelection.RowSel
    bottom = fgSelection.Row
End If
For int_row = top To bottom
   
    If Not fgSelection.RowHidden(int_row) Then
        
            'fgFields.RowHidden(fgSelection.RowData(int_row)) = Not fgFields.RowHidden(int_row)'
            fgFields.RowHidden(CLng(fgSelection.Cell(flexcpText, int_row, 0))) = Not fgFields.RowHidden(CLng(fgSelection.Cell(flexcpText, int_row, 0)))
            fgSelection.RowHidden(int_row) = Not fgSelection.RowHidden(int_row)
        
    End If
Next int_row
 
If chkAutoRefresh.Value = vbChecked Then Call cmd_Refresh_Click
End Sub


Private Sub cmd_UPD_Selection_Click()
On Error GoTo errHandler
Dim bln_trace As Boolean
Dim int_resp As Integer
Dim int_RecUpd As Integer
Dim str_Update As String
Dim Count As Long
Dim str_pallet_Condition As String
Dim str_Prompt As String

str_Prompt = ""
If lng_L_Count > 0 Then
    bln_trace = Collect_Pallets_List()
    Count = lng_L_Count
    str_pallet_Condition = " AND PALLET_ID IN (" & str_Pallet_List & ")"
Else
    'Count = lng_G_Count
    'str_pallet_Condition = ""
    ' original design update all if nothing selected,
    ' changed by request to update nothing if no selection has been made.
    Count = 0
    MsgBox "Please make selection first and then update.", vbOKOnly, "Update Location"
    Exit Sub
End If

If Count = 1 Then
    str_Prompt = str_Pallet_List & " pallet "
Else
    str_Prompt = Count & " pallets "
End If

int_resp = MsgBox("Are you sure to set the location of the selected " & str_Prompt & "to: " & txtLocation, vbQuestion + vbYesNo, "Confirm Update")

If int_resp = vbNo Then Exit Sub
If Len(Trim(txtLocation)) = 0 Then
    int_resp = MsgBox("The next step will remove the location for the selected " & str_Prompt & ". Are you sure to continue?", vbQuestion + vbYesNo, "Confirm Update")
    If int_resp = vbNo Then Exit Sub
End If
If Count > 100 Then
    int_resp = MsgBox("You will change the location of more that 100 pallets. Please confirm to proceed!", vbCritical + vbYesNo, "Confirm Update")
    If int_resp = vbNo Then Exit Sub
End If
str_Update = "UPDATE CARGO_TRACKING SET WAREHOUSE_LOCATION = '" & txtLocation & "'" & str_WhereStatement & str_pallet_Condition

Screen.MousePointer = vbHourglass
ordb.Execute str_Update, int_RecUpd
Screen.MousePointer = vbDefault
If int_RecUpd = Count Then
    'Call cmd_Refresh_Click
    bln_trace = Assign_Pallets_List()
    If Not bln_trace Then Call cmd_Refresh_Click
    MsgBox "Update Successful!", vbOKOnly, "Update"
Else
    MsgBox int_RecUpd & " records updated!", vbOKOnly, "Update"
End If
Exit Sub

errHandler:

MsgBox Err.Description, vbOKOnly, "Unexpected Error (cmd_UPD_Selection_Click)"
Screen.MousePointer = vbDefault
End Sub

Private Sub cmdAddAll_Click()
fgFields.Row = 1
fgFields.RowSel = fgSelection.Rows - 1
Call cmd_Add_Click
fgSelection.Row = 0
fgSelection.RowSel = 0
End Sub

Private Sub cmdRemAll_Click()
fgSelection.Row = 1
fgSelection.RowSel = fgSelection.Rows - 1
Call cmd_Rem_Click
fgFields.Row = 0
fgFields.RowSel = 0
End Sub

Private Sub ct_OSizerTop_Resize()
On Error Resume Next
    fraFilter.Move fraFilter.left, fraFilter.top, fra_Top.Width, fra_Top.Height - fraFilter.top
    If fraFilter.Height > 1880 Then
        fgFields.Height = fraFilter.Height - fgFields.top - 120
        fgSelection.Height = fraFilter.Height - fgSelection.top - 120
    End If
End Sub

Private Sub fg_Group_AfterSelection()
On Error GoTo errHndlr
Dim i As Long
Dim l_sel As Long
l_sel = 0
With fg_Group.vsFlexGrid
    If .SelectedRows > 0 Then
        For i = 0 To .SelectedRows
            If .SelectedRow(i) > 0 Then
                If Not .IsSubtotal(.SelectedRow(i)) Then
                    l_sel = l_sel + 1
                End If
            End If
        Next i
        lbl_Selection.Caption = "Selected Pallets: " & l_sel
        lng_L_Count = l_sel
    Else
    lbl_Selection.Caption = ""
    lng_L_Count = 0
    End If
End With

Exit Sub
errHndlr:

End Sub

Private Function Collect_Pallets_List() As Boolean
On Error GoTo errHndlr
Dim i As Long
Dim l_sel As Long
l_sel = 0
str_Pallet_List = ""
With fg_Group.vsFlexGrid
    If .SelectedRows > 0 Then
        For i = 0 To .SelectedRows
            If .SelectedRow(i) > 0 Then
                If Not .IsSubtotal(.SelectedRow(i)) Then
                    l_sel = l_sel + 1
                    If l_sel > 1 Then str_Pallet_List = str_Pallet_List & ","
                     str_Pallet_List = str_Pallet_List & "'" & .Cell(flexcpTextDisplay, .SelectedRow(i), .Cols - 1) & "'"
                End If
            End If
        Next i
        lng_L_Count = l_sel
    Else
    lbl_Selection.Caption = ""
    End If
End With
Exit Function
errHndlr:
End Function
Private Function Assign_Pallets_List() As Boolean
On Error GoTo errHndlr
Dim i As Long
Dim l_sel As Long
Dim int_Assign_Col As Integer
Dim bln_Return_val As Boolean
l_sel = 0
int_Assign_Col = -1
i = 1
str_Pallet_List = ""
bln_Return_val = False
With fg_Group.vsFlexGrid
    If .SelectedRows > 0 Then
        Do While i < .Cols And int_Assign_Col = 0
            If .TextMatrix(0, i) = "WAREHOUSE LOCATION" Then
                int_Assign_Col = i
            End If
            i = i + 1
        Loop
        If int_Assign_Col > -1 Then
            For i = 0 To .SelectedRows
                If .SelectedRow(i) > 0 Then
                    If Not .IsSubtotal(.SelectedRow(i)) Then
                        .TextMatrix(.SelectedRow(i), int_Assign_Col) = txtLocation.text
                    End If
                End If
            Next i
        End If
        bln_Return_val = True
    Else
    Assign_Pallets_List = bln_Return_val
    End If
End With
Assign_Pallets_List = bln_Return_val
Exit Function
errHndlr:
Assign_Pallets_List = bln_Return_val
End Function

Private Sub fg_Group_GridDblClck()
Dim c As Long
Dim r As Long

Dim int_row As Integer

Dim i As Integer

With fg_Group.vsFlexGrid
    int_row = .MouseRow
    c = .MouseCol
    If r < 1 And c < 0 Then Exit Sub
    If .IsSubtotal(r) Then Exit Sub
    Debug.Print .TextMatrix(0, c), .TextMatrix(int_row, c)
   
    i = 1
    r = 0
    Do While r = 0 And i < fgSelection.Rows
      If fgSelection.TextMatrix(i, 1) = .TextMatrix(0, c) Then
          r = i
          If Len(fgSelection.TextMatrix(r, 3)) > 0 Then fgSelection.TextMatrix(r, 3) = fgSelection.TextMatrix(r, 3) & ","
          fgSelection.TextMatrix(r, 3) = fgSelection.TextMatrix(r, 3) & .TextMatrix(int_row, c)
          fgSelection.ColDataType(3) = flexDTString
          fgSelection.ColAlignment(3) = flexAlignLeftCenter
      End If
      i = i + 1
    Loop
End With
End Sub

Private Sub fgFields_DblClick()
Dim int_row As Integer
int_row = fgFields.MouseRow
    If int_row > 0 Then
        fgFields.Row = int_row
        Call cmd_Add_Click
    End If
End Sub

Private Sub fgSelection_Click()
    If fgSelection.Col = 2 Then fgSelection.ColComboList(2) = str_slComboNames
End Sub

Private Sub fgSelection_DblClick()
Dim int_row As Integer
If fgSelection.MouseCol <> 1 Then Exit Sub
int_row = fgSelection.MouseRow
    If int_row > 0 Then
        fgSelection.Row = int_row
        Call cmd_Rem_Click
    End If
    fgSelection.AutoSize 0, 2
End Sub
Private Sub Form_Load()
str_DatabaseName = "RF.WORLD"
str_user = "SAG_OWNER"
str_UPass = "OWNER"
Call mnu_Frmt_Date_Click
Initialize_DB (False)
 sb_Status.Panels(1).text = "LIVE: " & str_DatabaseName
 If ordb.State = adStateOpen Then
    sb_Status.Panels(3).text = "Connected."
    InitDisplay
 Else
 sb_Status.Panels(3).text = "Disconnected."
 End If
 
' mnu_PopUp.a
'  Caption = "mnu_PopUp"
'      Enabled = 0             'False
'      Visible = 0             'False
'      Begin VB.Menu mnu_save
'         Caption = "Save Settings"
'      End
'      Begin VB.Menu mnu_Load
'         Caption = "Load Settings"
'      End
'      Begin VB.Menu mnu_Sep
'         Caption = "-"
'      End
'      Begin VB.Menu mnu_Add
'         Caption = "Add Column"
'      End
End Sub


Private Sub InitDisplay()
Dim str_querry As String
Dim bln_trace As Boolean
Dim i As Integer
'init display grids
fgSelection.ColComboList(2) = str_slComboNames
fgSelection.TextMatrix(0, 1) = "Display"
fgSelection.TextMatrix(0, 2) = "OPER"
fgSelection.TextMatrix(0, 3) = "Filter"
fgSelection.ColHidden(0) = True
fgFields.ColHidden(0) = True
fgSelection.AutoSize 1, 3

'Load Vessels
If ordb.State = adStateOpen Then
    str_querry = "SELECT DISTINCT CARGO_TRACKING.ARRIVAL_NUM, VESSEL_PROFILE.VESSEL_NAME "
    str_querry = str_querry & " FROM CARGO_TRACKING,VESSEL_PROFILE"
    str_querry = str_querry & " WHERE CARGO_TRACKING.ARRIVAL_NUM = VESSEL_PROFILE.ARRIVAL_NUM ORDER BY CARGO_TRACKING.ARRIVAL_NUM"
    bln_trace = FillCombo(cmb_vessel, str_querry)
    'Load Customers
    str_querry = "SELECT DISTINCT CARGO_TRACKING.RECEIVER_ID, CUSTOMER_PROFILE.CUSTOMER_NAME "
    str_querry = str_querry & " FROM CARGO_TRACKING,CUSTOMER_PROFILE"
    str_querry = str_querry & " WHERE CARGO_TRACKING.RECEIVER_ID = CUSTOMER_PROFILE.CUSTOMER_ID ORDER BY CARGO_TRACKING.RECEIVER_ID"
    bln_trace = FillCombo(cmb_Customer, str_querry)
    'Load fields
    str_querry = "SELECT  DISTINCT COLUMN_NAME"
    str_querry = str_querry & " FROM DBA_TAB_COLUMNS"
    str_querry = str_querry & " WHERE TABLE_NAME = 'CARGO_TRACKING' AND OWNER = 'SAG_OWNER' ORDER BY COLUMN_NAME"
    bln_trace = FillGrid(fgFields, str_querry, True)
    bln_trace = FillGrid(fgSelection, str_querry, False)
    fg_Group.vsFlexGrid.Rows = 1
    arr_combo_count = fgFields.Rows - 1
    ReDim arr_combo(fgFields.Rows - 1)
    ReDim arr_colPos(fgFields.Rows - 1)
    For i = 0 To arr_combo_count
        arr_combo(i).bln_In = False
        arr_combo(i).int_id = i
        arr_combo(i).str_Name = fgFields.Cell(flexcpText, i, 1)
    Next i
End If
End Sub




Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
   Call Close_DB
End Sub

Private Sub Form_Resize()

On Error Resume Next
Dim grouptop As Long
ct_OSizerTop.Move 0, 0, Me.ScaleWidth, Me.ScaleHeight - sb_Status.Height
If Me.ScaleWidth > 11490 Then

    fraVessels.Move 60, 0, Me.ScaleWidth - 120
    fraFilter.Move 60, fraFilter.top, Me.ScaleWidth - 120
    grouptop = fraFilter.top + fraFilter.Height + 60
    
    Image4.Move fraFilter.Width - Image4.Width - 120
    cmd_UPD_Selection.Move Image4.left - cmd_UPD_Selection.Width - 120
    txtLocation.Move cmd_UPD_Selection.left - txtLocation.Width - 120
    lbl_Records.Move cmd_UPD_Selection.left - txtLocation.Width - 120
    lbl_Selection.Move cmd_UPD_Selection.left - txtLocation.Width - 120
    lbl_Location.Move txtLocation.left
    cmd_Refresh.Move cmd_UPD_Selection.left
    chkAutoRefresh.Move cmd_Refresh.left
    chk_inhouse_only.Move cmd_Refresh.left
    fgSelection.Move fgSelection.left, fgSelection.top, cmd_Refresh.left - fgSelection.left - 120
    Image4.Move fraVessels.Width - Image4.Width - 120
    'fg_Group.left = 60
    'fg_Group.Width = Me.ScaleWidth - 120 '   60, fg_Group, Me.ScaleWidth - 120, Me.ScaleHeight - grouptop - 120
    'fg_Group.Height = Me.ScaleHeight - grouptop - sb_Status.Height - 120
End If
If Me.ScaleHeight > 6000 Then
    ' fg_Group.left = 60
    'fg_Group.Width = Me.ScaleWidth - 120 '   60, fg_Group, Me.ScaleWidth - 120, Me.ScaleHeight - grouptop - 120
    'fg_Group.Height = Me.ScaleHeight - grouptop - sb_Status.Height - 120
End If

End Sub

Private Sub mnu_About_Click()
    frm_About.Show
End Sub

Private Sub mnu_Database_Click()
    frm_Database.Show
    
End Sub

Private Sub mnu_exit_Click()
    End
End Sub

Private Sub mnu_Frmt_Date_Click()
    mnu_Frmt_Date.Checked = True
    mnu_Frmt_Date_Hour.Checked = False
    str_Format_Date_Received = str_FRMT_Date
End Sub

Private Sub mnu_Frmt_Date_Hour_Click()
    mnu_Frmt_Date.Checked = False
    mnu_Frmt_Date_Hour.Checked = True
    str_Format_Date_Received = str_FRMT_Date_Hour
End Sub

Private Sub mnu_Manifest_Click()
    frm_Import.Show
End Sub

Private Sub mnu_Open_Click()
  On Error Resume Next
  Dim i As Integer
  Dim txtImportFile As String
    cdlImportFile.FileName = "*.nsi"
    cdlImportFile.Filter = "Configuration (*.nsi)"
    cdlImportFile.Action = 1
    If Err = 0 And Len(cdlImportFile.FileName) > 0 And InStr(cdlImportFile.FileName, "*") = 0 Then
        Screen.MousePointer = vbHourglass
        txtImportFile = cdlImportFile.FileName
        fgSelection.LoadGrid txtImportFile, flexFileAll
        fgFields.Rows = 1
        fgSelection.Select 1, 0
        fgFields.Sort = flexSortGenericAscending
        For i = 1 To fgSelection.Rows - 1
            fgFields.AddItem fgSelection.TextMatrix(i, 0) & vbTab & fgSelection.TextMatrix(i, 1)
            fgFields.RowHidden(i) = Not fgSelection.RowHidden(i)
        Next i
        Screen.MousePointer = vbDefault
    End If
    On Error GoTo 0
    If chkAutoRefresh.Value Then Call cmd_Refresh_Click
    Screen.MousePointer = vbDefault
End Sub

Private Sub mnu_Print_Click()
    fg_Group.vsFlexGrid.PrintGrid cmb_vessel.text & " / " & cmb_Customer.text, True, 2
End Sub

Private Sub mnu_Purge_Click()

   
Dim str_sql As String
Dim i As Integer

If Len(str_DQuery) = 0 Then Exit Sub

    str_sql = "SELECT " & str_DQuery & " FROM CARGO_TRACKING " & str_DWhere & " AND LOWER(WAREHOUSE_LOCATION) = 'delete'"
    frm_Purge.LocalReset
    frm_Purge.WHERE = str_DWhere & " AND LOWER(WAREHOUSE_LOCATION) = 'delete'"
    frm_Purge.DFIELDS = str_DQuery
    If cmb_vessel.ListIndex > -1 Then frm_Purge.ARRIVAL_NUM = cmb_vessel.ItemData(cmb_vessel.ListIndex)
    If cmb_Customer.ListIndex > -1 Then frm_Purge.CUST_ID = cmb_Customer.ItemData(cmb_Customer.ListIndex)
    frm_Purge.fg_Delete.Cols = fg_Group.vsFlexGrid.Cols
    
'    For i = 0 To fg_Group.vsFlexGrid.Cols - 1
'         frm_Purge.fg_Delete.TextMatrix(0, i) = fg_Group.vsFlexGrid.TextMatrix(0, i)
'    Next i
     
    frm_Purge.ViewDeleted
    frm_Purge.Caption = "Remove pallets " & str_DWhere
   frm_Purge.Show vbModal
  
End Sub

Private Sub mnu_Save_Click()
  On Error Resume Next
  Dim txtImportFile As String
    cdlImportFile.FileName = "*.nsi"
    cdlImportFile.Filter = "Configuration (*.nsi)"
    cdlImportFile.Action = 2
    If Err = 0 Then
        txtImportFile = cdlImportFile.FileName
        If InStr(txtImportFile, ".") = 0 Then txtImportFile = Trim(txtImportFile) & ".nsi"
        fgSelection.SaveGrid txtImportFile, flexFileAll
    End If
    On Error GoTo 0
End Sub

Private Sub Load_Grid()

Dim str_query As String
Dim str_Order As String
Dim bln_Track As Boolean
Dim int_row As Integer

Dim int_FieldsCount As Integer
Dim str_FieldColumns As String
Dim str_FieldFilter As String
If cmb_vessel.ListIndex < 0 And cmb_Customer.ListIndex < 0 Then
    Exit Sub
End If


Screen.MousePointer = vbHourglass
int_FieldsCount = 0
str_FieldColumns = "id,"
str_FieldFilter = ""
str_WhereStatement = ""
fg_Group.vsFlexGrid.Rows = 1
str_query = ""
str_Order = ""

fg_Group.vsFlexGrid.Cols = int_FieldsCount

For int_row = 1 To fgSelection.Rows - 1
   
    If Not fgSelection.RowHidden(int_row) Then
        If Len(str_query) > 0 Then
            str_query = str_query & ","
            str_Order = str_Order & ","
            str_FieldColumns = str_FieldColumns & vbTab
        End If
        
        'Add a field and apply formating for the date

        If fgSelection.RowData(int_row) = "DATE_RECEIVED" Then
            str_query = str_query & "TO_CHAR(" & fgSelection.RowData(int_row) & ",'" & str_Format_Date_Received & "') " & fgSelection.RowData(int_row) & " "
            str_Order = str_Order & fgSelection.RowData(int_row) & " "
        Else
            str_query = str_query & fgSelection.RowData(int_row) & " "
            str_Order = str_Order & fgSelection.RowData(int_row) & " "
        End If
        int_FieldsCount = int_FieldsCount + 1
        
            str_FieldColumns = str_FieldColumns & Trim(fgSelection.TextMatrix(int_row, 1))
       
        If Len(Trim(fgSelection.Cell(flexcpTextDisplay, int_row, 2))) > 0 Then
        str_FieldFilter = str_FieldFilter & " AND "
        'apply filter - build the where clause
        Select Case fgSelection.Cell(flexcpValue, int_row, 2)
            Case 1, 2, 3, 4, 5: ' = or < or > or <>
                If left(fgSelection.Cell(flexcpTextDisplay, int_row, 3), 7) = "FILTER(" Then
                   
                    str_FieldFilter = str_FieldFilter & Mid(fgSelection.Cell(flexcpTextDisplay, int_row, 3), 8, Len(fgSelection.TextMatrix(int_row, 3)) - 8)
                Else
             
                    str_FieldFilter = str_FieldFilter & fgSelection.RowData(int_row) & " " & fgSelection.Cell(flexcpTextDisplay, int_row, 2) & " '" & fgSelection.TextMatrix(int_row, 3) & "'"
                End If
            Case 6, 7: 'In, not in
               
                str_FieldFilter = str_FieldFilter & fgSelection.RowData(int_row) & " " & fgSelection.Cell(flexcpTextDisplay, int_row, 2) & " ('" & Replace(fgSelection.TextMatrix(int_row, 3), ",", "','") & "')"
            Case 8: ' = date
               
                str_FieldFilter = str_FieldFilter & " TO_CHAR(" & fgSelection.RowData(int_row) & ",'MM/DD/YYYY') = '" & fgSelection.TextMatrix(int_row, 3) & "'"
            Case 9, 10:  ' < or > date
             
                str_FieldFilter = str_FieldFilter & fgSelection.RowData(int_row) & fgSelection.Cell(flexcpTextDisplay, int_row, 2) & "TO_DATE('" & fgSelection.TextMatrix(int_row, 3) & "','MM/DD/YYYY')"
            Case Else
                
        End Select
        End If
        fg_Group.vsFlexGrid.Cols = int_FieldsCount
        fg_Group.vsFlexGrid.TextMatrix(0, int_FieldsCount - 1) = Trim(fgSelection.TextMatrix(int_row, 1))
        
    End If
Next int_row
If int_FieldsCount = 0 Then
    Screen.MousePointer = vbDefault
    Exit Sub
End If
str_DQuery = str_query
If cmb_vessel.text <> "" Then str_WhereStatement = " WHERE ARRIVAL_NUM = '" & cmb_vessel.ItemData(cmb_vessel.ListIndex) & "'"
If cmb_Customer.text <> "" Then
    If Len(str_WhereStatement) > 0 Then
        str_WhereStatement = str_WhereStatement & " AND RECEIVER_ID = '" & cmb_Customer.ItemData(cmb_Customer.ListIndex) & "'"
    Else
        str_WhereStatement = " WHERE RECEIVER_ID = '" & cmb_Customer.ItemData(cmb_Customer.ListIndex) & "'"
    End If
End If
' keep the combo's selections
str_DWhere = str_WhereStatement
' add filters etc.
If Len(str_FieldFilter) > 0 Then str_WhereStatement = str_WhereStatement & str_FieldFilter
If chk_inhouse_only.Value = vbChecked Then str_WhereStatement = str_WhereStatement & " AND QTY_IN_HOUSE > 0"
str_query = "SELECT " & str_query & ", PALLET_ID id FROM CARGO_TRACKING" & str_WhereStatement & " ORDER BY " & str_Order
Debug.Print str_query
int_FieldsCount = int_FieldsCount + 1
fg_Group.vsFlexGrid.Cols = int_FieldsCount
fg_Group.vsFlexGrid.TextMatrix(0, int_FieldsCount - 1) = "id"
If int_FieldsCount > 0 Then
    fg_Group.vsFlexGrid.Rows = 1
    ' fg_Group.vsFlexGrid.Cols = int_FieldsCount - 1
    'fg_Group.vsFlexGrid.AddItem str_FieldColumns
    bln_Track = FillGroup(fg_Group.vsFlexGrid, str_query, int_FieldsCount)
    lbl_Records.Caption = "Number of Pallets: " & fg_Group.vsFlexGrid.Rows - 1
    lng_G_Count = fg_Group.vsFlexGrid.Rows - 1
    fg_Group.Update
    'fg_Group.vsFlexGrid.TextMatrix(0, int_FieldsCount - 1) = "id"
End If
fg_Group.vsFlexGrid.ColHidden(int_FieldsCount - 1) = True
Screen.MousePointer = vbDefault

End Sub

