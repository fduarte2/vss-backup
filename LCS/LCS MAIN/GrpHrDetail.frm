VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Begin VB.Form frmGrpHrDetail 
   AutoRedraw      =   -1  'True
   Caption         =   "GROUP HOURLY DETAILS"
   ClientHeight    =   11115
   ClientLeft      =   60
   ClientTop       =   -90
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
   ScaleHeight     =   11115
   ScaleWidth      =   15240
   Begin Crystal.CrystalReport crw1 
      Left            =   270
      Top             =   270
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      PrintFileLinesPerPage=   60
   End
   Begin VB.Frame Frame3 
      Height          =   3975
      Left            =   90
      TabIndex        =   2
      Top             =   4380
      Width           =   15840
      Begin VB.CommandButton cmdLabTicket 
         Caption         =   "LABOR TICKET"
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
         Left            =   13320
         TabIndex        =   30
         Top             =   765
         Width           =   1785
      End
      Begin VB.CommandButton cmdSave 
         Caption         =   "SAVE"
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
         Left            =   10680
         TabIndex        =   29
         Top             =   765
         Width           =   1785
      End
      Begin VB.CommandButton cmdDelGrp 
         Caption         =   "DELETE GROUP"
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
         Left            =   10680
         TabIndex        =   25
         Top             =   240
         Width           =   1785
      End
      Begin VB.CommandButton cmdClearAll 
         Caption         =   "CLEAR ALL"
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
         Left            =   10680
         TabIndex        =   19
         Top             =   1800
         Width           =   1785
      End
      Begin VB.CommandButton cmdExit 
         Caption         =   "EXIT"
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
         Left            =   13320
         TabIndex        =   18
         Top             =   1275
         Width           =   1785
      End
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
         Left            =   10680
         TabIndex        =   17
         Top             =   1275
         Width           =   1785
      End
      Begin VB.CommandButton cmdDefValues 
         Caption         =   "USE DEFAULT VALUES "
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   435
         Left            =   1470
         TabIndex        =   15
         Top             =   1125
         Width           =   2325
      End
      Begin SSDataWidgets_B.SSDBGrid grdDefaultVal 
         Height          =   1545
         Left            =   2250
         TabIndex        =   14
         Top             =   2340
         Width           =   13320
         _Version        =   196616
         DataMode        =   2
         Col.Count       =   12
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
         Columns.Count   =   12
         Columns(0).Width=   2011
         Columns(0).Caption=   "REG/OT"
         Columns(0).Name =   "REG/OT"
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(0).Style=   3
         Columns(0).Row.Count=   2
         Columns(0).Col.Count=   2
         Columns(0).Row(0).Col(0)=   "REG"
         Columns(0).Row(1).Col(0)=   "OT"
         Columns(1).Width=   1349
         Columns(1).Caption=   "BILL"
         Columns(1).Name =   "BILL"
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(1).Style=   2
         Columns(2).Width=   1746
         Columns(2).Caption=   "SRVC"
         Columns(2).Name =   "SRVC"
         Columns(2).CaptionAlignment=   2
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(2).Style=   3
         Columns(3).Width=   1984
         Columns(3).Caption=   "EQUIP"
         Columns(3).Name =   "EQUIP"
         Columns(3).CaptionAlignment=   2
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         Columns(3).Style=   3
         Columns(4).Width=   1984
         Columns(4).Caption=   "COMM"
         Columns(4).Name =   "COMM"
         Columns(4).CaptionAlignment=   2
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         Columns(4).Style=   3
         Columns(5).Width=   2011
         Columns(5).Caption=   "CATE"
         Columns(5).Name =   "CATE"
         Columns(5).CaptionAlignment=   2
         Columns(5).DataField=   "Column 5"
         Columns(5).DataType=   8
         Columns(5).FieldLen=   256
         Columns(5).Style=   3
         Columns(6).Width=   2011
         Columns(6).Caption=   "SHIP"
         Columns(6).Name =   "SHIP"
         Columns(6).CaptionAlignment=   2
         Columns(6).DataField=   "Column 6"
         Columns(6).DataType=   8
         Columns(6).FieldLen=   256
         Columns(6).Style=   3
         Columns(7).Width=   1958
         Columns(7).Caption=   "CUST"
         Columns(7).Name =   "CUST"
         Columns(7).DataField=   "Column 7"
         Columns(7).DataType=   8
         Columns(7).FieldLen=   256
         Columns(7).Style=   3
         Columns(8).Width=   2196
         Columns(8).Caption=   "SPEC"
         Columns(8).Name =   "SPEC"
         Columns(8).DataField=   "Column 8"
         Columns(8).DataType=   8
         Columns(8).FieldLen=   256
         Columns(9).Width=   3122
         Columns(9).Caption=   "REMARK"
         Columns(9).Name =   "REMARK"
         Columns(9).DataField=   "Column 9"
         Columns(9).DataType=   8
         Columns(9).FieldLen=   256
         Columns(10).Width=   2170
         Columns(10).Caption=   "EXACTEND"
         Columns(10).Name=   "EXACTEND"
         Columns(10).DataField=   "Column 10"
         Columns(10).DataType=   8
         Columns(10).FieldLen=   256
         Columns(11).Width=   3200
         Columns(11).Visible=   0   'False
         Columns(11).Caption=   "ROWNUM"
         Columns(11).Name=   "ROWNUM"
         Columns(11).DataField=   "Column 11"
         Columns(11).DataType=   8
         Columns(11).FieldLen=   256
         _ExtentX        =   23495
         _ExtentY        =   2725
         _StockProps     =   79
         Caption         =   "DEFAULT VALUES"
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
      Begin SSDataWidgets_B.SSDBGrid grdHrGrp 
         Height          =   1815
         Left            =   4140
         TabIndex        =   20
         Top             =   360
         Width           =   5145
         _Version        =   196616
         DataMode        =   2
         Col.Count       =   5
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
         Columns.Count   =   5
         Columns(0).Width=   2937
         Columns(0).Caption=   "START TIME"
         Columns(0).Name =   "START TIME"
         Columns(0).Alignment=   2
         Columns(0).CaptionAlignment=   2
         Columns(0).DataField=   "Column 0"
         Columns(0).DataType=   8
         Columns(0).FieldLen=   256
         Columns(0).Style=   3
         Columns(0).Row.Count=   24
         Columns(0).Col.Count=   2
         Columns(0).Row(0).Col(0)=   "7:00 AM"
         Columns(0).Row(1).Col(0)=   "8:00 AM"
         Columns(0).Row(2).Col(0)=   "9:00 AM"
         Columns(0).Row(3).Col(0)=   "10:00 AM"
         Columns(0).Row(4).Col(0)=   "11:00 AM"
         Columns(0).Row(5).Col(0)=   "12:00 PM"
         Columns(0).Row(6).Col(0)=   "1:00 PM"
         Columns(0).Row(7).Col(0)=   "2:00 PM"
         Columns(0).Row(8).Col(0)=   "3:00 PM"
         Columns(0).Row(9).Col(0)=   "4:00 PM"
         Columns(0).Row(10).Col(0)=   "5:00 PM"
         Columns(0).Row(11).Col(0)=   "6:00 PM"
         Columns(0).Row(12).Col(0)=   "7:00 PM"
         Columns(0).Row(13).Col(0)=   "8:00 PM"
         Columns(0).Row(14).Col(0)=   "9:00 PM"
         Columns(0).Row(15).Col(0)=   "10:00 PM"
         Columns(0).Row(16).Col(0)=   "11:00 PM"
         Columns(0).Row(17).Col(0)=   "12:00 AM"
         Columns(0).Row(18).Col(0)=   "1:00 AM"
         Columns(0).Row(19).Col(0)=   "2:00 AM"
         Columns(0).Row(20).Col(0)=   "3:00 AM"
         Columns(0).Row(21).Col(0)=   "4:00 AM"
         Columns(0).Row(22).Col(0)=   "5:00 AM"
         Columns(0).Row(23).Col(0)=   "6:00 AM"
         Columns(1).Width=   2752
         Columns(1).Caption=   "END TIME"
         Columns(1).Name =   "END TIME"
         Columns(1).Alignment=   2
         Columns(1).CaptionAlignment=   2
         Columns(1).DataField=   "Column 1"
         Columns(1).DataType=   8
         Columns(1).FieldLen=   256
         Columns(1).Style=   3
         Columns(1).Row.Count=   24
         Columns(1).Col.Count=   2
         Columns(1).Row(0).Col(0)=   "7:00 AM"
         Columns(1).Row(1).Col(0)=   "8:00 AM"
         Columns(1).Row(2).Col(0)=   "9:00 AM"
         Columns(1).Row(3).Col(0)=   "10:00 AM"
         Columns(1).Row(4).Col(0)=   "11:00 AM"
         Columns(1).Row(5).Col(0)=   "12:00 PM"
         Columns(1).Row(6).Col(0)=   "1:00 PM"
         Columns(1).Row(7).Col(0)=   "2:00 PM"
         Columns(1).Row(8).Col(0)=   "3:00 PM"
         Columns(1).Row(9).Col(0)=   "4:00 PM"
         Columns(1).Row(10).Col(0)=   "5:00 PM"
         Columns(1).Row(11).Col(0)=   "6:00 PM"
         Columns(1).Row(12).Col(0)=   "7:00 PM"
         Columns(1).Row(13).Col(0)=   "8:00 PM"
         Columns(1).Row(14).Col(0)=   "9:00 PM"
         Columns(1).Row(15).Col(0)=   "10:00 PM"
         Columns(1).Row(16).Col(0)=   "11:00 PM"
         Columns(1).Row(17).Col(0)=   "12:00 AM"
         Columns(1).Row(18).Col(0)=   "1:00 AM"
         Columns(1).Row(19).Col(0)=   "2:00 AM"
         Columns(1).Row(20).Col(0)=   "3:00 AM"
         Columns(1).Row(21).Col(0)=   "4:00 AM"
         Columns(1).Row(22).Col(0)=   "5:00 AM"
         Columns(1).Row(23).Col(0)=   "6:00 AM"
         Columns(2).Width=   2540
         Columns(2).Caption=   "HRS."
         Columns(2).Name =   "HRS."
         Columns(2).Alignment=   1
         Columns(2).CaptionAlignment=   2
         Columns(2).DataField=   "Column 2"
         Columns(2).DataType=   8
         Columns(2).FieldLen=   256
         Columns(3).Width=   3200
         Columns(3).Visible=   0   'False
         Columns(3).Caption=   "GrpHr"
         Columns(3).Name =   "GrpHr"
         Columns(3).DataField=   "Column 3"
         Columns(3).DataType=   8
         Columns(3).FieldLen=   256
         Columns(4).Width=   3200
         Columns(4).Visible=   0   'False
         Columns(4).Caption=   "ROWNUM"
         Columns(4).Name =   "ROWNUM"
         Columns(4).DataField=   "Column 4"
         Columns(4).DataType=   8
         Columns(4).FieldLen=   256
         _ExtentX        =   9075
         _ExtentY        =   3201
         _StockProps     =   79
         Caption         =   "HOUR GROUP"
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
   Begin VB.Frame Frame2 
      Height          =   3435
      Left            =   135
      TabIndex        =   1
      Top             =   930
      Width           =   15750
      Begin VB.OptionButton optFilter 
         Caption         =   "Employee   ID"
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
         Index           =   2
         Left            =   450
         TabIndex        =   27
         Top             =   1575
         Width           =   1695
      End
      Begin VB.TextBox txtFiltEmpId 
         Appearance      =   0  'Flat
         Height          =   330
         Left            =   960
         MaxLength       =   4
         TabIndex        =   26
         Top             =   1920
         Width           =   1275
      End
      Begin VB.OptionButton optFilter 
         Caption         =   "Location"
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
         Index           =   1
         Left            =   450
         TabIndex        =   24
         Top             =   2460
         Width           =   1905
      End
      Begin VB.OptionButton optFilter 
         Caption         =   "None"
         BeginProperty Font 
            Name            =   "Times New Roman"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   390
         Index           =   0
         Left            =   450
         TabIndex        =   23
         Top             =   1020
         Value           =   -1  'True
         Width           =   2355
      End
      Begin VB.ComboBox cboLocation 
         Height          =   345
         Left            =   450
         TabIndex        =   22
         Top             =   2775
         Width           =   2265
      End
      Begin VB.ListBox lstSelectedEmp 
         Height          =   2760
         Left            =   10440
         Sorted          =   -1  'True
         TabIndex        =   13
         Top             =   360
         Width           =   4425
      End
      Begin VB.CommandButton cmdSelect 
         Caption         =   "<<  REMOVE ALL"
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
         Index           =   3
         Left            =   8160
         TabIndex        =   12
         Top             =   2520
         Width           =   1815
      End
      Begin VB.CommandButton cmdSelect 
         Caption         =   "<  REMOVE"
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
         Index           =   2
         Left            =   8160
         TabIndex        =   11
         Top             =   1920
         Width           =   1815
      End
      Begin VB.CommandButton cmdSelect 
         Caption         =   "ADD ALL  >>"
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
         Index           =   1
         Left            =   8160
         TabIndex        =   10
         Top             =   1320
         Width           =   1815
      End
      Begin VB.CommandButton cmdSelect 
         Caption         =   "ADD  >"
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
         Index           =   0
         Left            =   8160
         TabIndex        =   9
         Top             =   720
         Width           =   1815
      End
      Begin VB.ListBox lstAllEmp 
         Height          =   2760
         Left            =   3000
         Sorted          =   -1  'True
         TabIndex        =   8
         Top             =   360
         Width           =   4425
      End
      Begin VB.Label Label4 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "FILTER"
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
         Left            =   1155
         TabIndex        =   21
         Top             =   570
         Width           =   645
      End
   End
   Begin VB.Frame Frame1 
      Height          =   915
      Left            =   1388
      TabIndex        =   0
      Top             =   -30
      Width           =   13245
      Begin VB.ComboBox cboGrp 
         Height          =   345
         Left            =   10350
         TabIndex        =   4
         Top             =   360
         Width           =   2715
      End
      Begin MSComCtl2.DTPicker dtpDate 
         Height          =   345
         Left            =   1260
         TabIndex        =   3
         Top             =   360
         Width           =   1275
         _ExtentX        =   2249
         _ExtentY        =   609
         _Version        =   393216
         CustomFormat    =   "MM/dd/yyyy"
         Format          =   20774915
         CurrentDate     =   36920
      End
      Begin VB.Label lblSuper 
         AutoSize        =   -1  'True
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
         Height          =   225
         Left            =   5145
         TabIndex        =   28
         Top             =   420
         Width           =   45
      End
      Begin VB.Label Label3 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "GROUP  :"
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
         Left            =   9360
         TabIndex        =   7
         Top             =   420
         Width           =   810
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         BackStyle       =   0  'Transparent
         Caption         =   "SUPERVISIOR  :"
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
         Left            =   3420
         TabIndex        =   6
         Top             =   420
         Width           =   1410
      End
      Begin VB.Label Label1 
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
         Left            =   360
         TabIndex        =   5
         Top             =   420
         Width           =   645
      End
   End
   Begin SSDataWidgets_B.SSDBGrid grdDetail 
      Height          =   2805
      Left            =   165
      TabIndex        =   16
      Top             =   8460
      Width           =   15615
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   13
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      AllowRowSizing  =   0   'False
      AllowGroupSizing=   0   'False
      AllowGroupMoving=   0   'False
      AllowGroupSwapping=   0   'False
      AllowGroupShrinking=   0   'False
      AllowColumnShrinking=   0   'False
      AllowDragDrop   =   0   'False
      ForeColorEven   =   8388608
      RowHeight       =   503
      Columns.Count   =   13
      Columns(0).Width=   3200
      Columns(0).Caption=   "EMP ID"
      Columns(0).Name =   "EMP ID"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   2752
      Columns(1).Caption=   "REG/OT"
      Columns(1).Name =   "REG/OT"
      Columns(1).Alignment=   2
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   1376
      Columns(2).Caption=   "BILL"
      Columns(2).Name =   "BILL"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(2).Style=   2
      Columns(3).Width=   1746
      Columns(3).Caption=   "SRVC"
      Columns(3).Name =   "EQUIP"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   1984
      Columns(4).Caption=   "EQUIP"
      Columns(4).Name =   "SRVC"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   1958
      Columns(5).Caption=   "COMM"
      Columns(5).Name =   "COMM"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   2064
      Columns(6).Caption=   "CATE"
      Columns(6).Name =   "CATE"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   1879
      Columns(7).Caption=   "SHIP"
      Columns(7).Name =   "SHIP"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   2037
      Columns(8).Caption=   "CUST"
      Columns(8).Name =   "CUST"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   2275
      Columns(9).Caption=   "SPEC"
      Columns(9).Name =   "SPEC"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   3016
      Columns(10).Caption=   "REMARK"
      Columns(10).Name=   "REMARK"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   2275
      Columns(11).Caption=   "EXACTEND"
      Columns(11).Name=   "EXACTEND"
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   3200
      Columns(12).Visible=   0   'False
      Columns(12).Caption=   "ROWNUM"
      Columns(12).Name=   "ROWNUM"
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      _ExtentX        =   27543
      _ExtentY        =   4948
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
Attribute VB_Name = "frmGrpHrDetail"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit            '5/2/2007 HD2759 Rudy:

Dim sqlStmt As String
Dim irec As Integer
Dim iPos As Integer
Dim dsLOCATION As Object
Dim dsGROUP As Object
Dim dsDETAILS As Object
Dim iRowNum As Integer
Const sMsg As String = " GROUP HOURLY DETAILS"
Dim sDelStartTime As String
Dim sDelEndTime As String

Private Sub cboGrp_Click()
    
    'If Trim(cboSuper.Text) = "" Then Exit Sub
    
    DoEvents
    lstSelectedEmp.Clear
    grdHrGrp.RemoveAll
    grdDefaultVal.RemoveAll
    grdDetail.RemoveAll
    
    If cboGrp.Text = "Create New Group" Then Exit Sub
    Me.MousePointer = 11
     sqlStmt = " SELECT DISTINCT EMPLOYEE_ID FROM HOURLY_DETAIL  WHERE HOURLY_GROUP='" & Trim(cboGrp.Text) & "' AND  " _
            & " USER_ID ='" & Extract_ID(lblSuper) & "'"
    Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsHOURLY_DETAIL.RecordCount > 0 Then
        With dsHOURLY_DETAIL
            For irec = 1 To .RecordCount
                lstSelectedEmp.AddItem .Fields("EMPLOYEE_ID").Value & " - " & EMP_NAME(.Fields("EMPLOYEE_ID").Value)
                dsHOURLY_DETAIL.MoveNext
                lstSelectedEmp.Refresh
                DoEvents
            Next irec
        End With
    End If
    
    sqlStmt = " SELECT DISTINCT START_TIME, END_TIME,DURATION FROM HOURLY_DETAIL  WHERE HOURLY_GROUP='" & Trim(cboGrp.Text) & "' AND  " _
            & " USER_ID ='" & Extract_ID(lblSuper) & "'"
    Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsHOURLY_DETAIL.RecordCount > 0 Then
        With dsHOURLY_DETAIL
            For irec = 1 To .RecordCount
                grdHrGrp.AddItem Format(.Fields("START_TIME").Value, "HH:MM AMPM") + Chr(9) + _
                                 Format(.Fields("END_TIME").Value, "HH:MM AMPM") + Chr(9) + _
                                 CStr(.Fields("DURATION").Value) + Chr(9) + Chr(9) + CStr(irec - 1)
                grdHrGrp.Refresh
                DoEvents
                dsHOURLY_DETAIL.MoveNext
            Next irec
        End With
    End If
    
   Me.MousePointer = 0
End Sub

Function EMP_NAME(sID As String) As String
Dim dsEMPLOYEE As Object       '5/2/2007 HD2759 Rudy:

    sqlStmt = "SELECT * FROM EMPLOYEE WHERE EMPLOYEE_ID='" & Trim(sID) & "'"
    Set dsEMPLOYEE = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsEMPLOYEE.RecordCount > 0 Then
        EMP_NAME = dsEMPLOYEE.Fields("EMPLOYEE_NAME").Value
    End If
    
    dsEMPLOYEE = Nothing       '5/2/2007 HD2759 Rudy:
End Function

Private Sub cboGrp_KeyPress(KeyAscii As Integer)
    KeyAscii = 0
End Sub

Private Sub cboLocation_Click()
     If cboLocation.ListIndex <> -1 Then Call Load_Emp_list
End Sub

Sub Super()
    Dim sSuper As String
    Dim sDt As String
    
    If lblSuper = "" Then Exit Sub
    
    Me.MousePointer = 11
    
    sSuper = Extract_ID(Trim(lblSuper))
    
    cboGrp.Clear
    cboGrp.AddItem "Create New Group"
    
    If Format(Now, "dddd") = "Monday" And CDate(Format(Now, "HH:MM AMPM")) > TimeValue("01:00 PM") Then
        sqlStmt = " SELECT DISTINCT HOURLY_GROUP FROM HOURLY_DETAIL WHERE USER_ID='" & Trim(sSuper) & "' AND " _
                & " HIRE_DATE >TO_DATE('" & Format(Now, "MM/DD/YYYY") & "','MM/DD/YYYY')-1 AND " _
                & " HIRE_DATE <TO_DATE('" & Format(Now, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
                & " AND HOURLY_GROUP IS NOT NULL ORDER BY HOURLY_GROUP"
    Else
        If Format(DTPDate.Value, "dddd") = "Monday" And Format(Now, "dddd") <> "Monday" Then
            sDt = Format(DTPDate, "MM/DD/YYYY") & " " & Format("01:00 PM", "HH:MM AMPM")
            
            sqlStmt = " SELECT DISTINCT HOURLY_GROUP FROM HOURLY_DETAIL WHERE USER_ID='" & Trim(sSuper) & "' AND " _
                    & " START_TIME >TO_DATE('" & sDt & "','MM/DD/YYYY HH:MI PM') AND " _
                    & " HIRE_DATE >TO_DATE('" & Format(Now, "MM/DD/YYYY") & "','MM/DD/YYYY')-1 AND " _
                    & " HIRE_DATE <TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
                    & " AND HOURLY_GROUP IS NOT NULL ORDER BY HOURLY_GROUP"
            
        Else
            sqlStmt = " SELECT DISTINCT HOURLY_GROUP FROM HOURLY_DETAIL WHERE USER_ID='" & Trim(sSuper) & "' AND " _
                    & " HIRE_DATE >TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY')-1 AND " _
                    & " HIRE_DATE <TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
                    & " AND HOURLY_GROUP IS NOT NULL ORDER BY HOURLY_GROUP"
        End If
    End If
    
    Set dsGROUP = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsGROUP.RecordCount > 0 Then
           
        For irec = 1 To dsGROUP.RecordCount
            cboGrp.AddItem dsGROUP.Fields("HOURLY_GROUP").Value
            dsGROUP.MoveNext
        Next irec
    End If
    cboGrp.ListIndex = 0
    Me.MousePointer = 0
    
End Sub

Private Sub cmdClearAll_Click()
    
    lstSelectedEmp.Clear
    
    grdHrGrp.CancelUpdate
    grdDefaultVal.CancelUpdate
    grdDetail.CancelUpdate
    
    grdHrGrp.RemoveAll
    grdDefaultVal.RemoveAll
    grdDetail.RemoveAll
    
'    dtpDate.Value = Format(Now, "MM/DD/YYYY")
'    cboSuper.ListIndex = -1
    cboGrp.Text = "Create New Group"
    
End Sub
Function Extract_ID(sID As String) As String
                                                                            
    iPos = InStr(1, Trim(sID), "-")
    If iPos <> 0 Then
        Extract_ID = Trim(Mid(Trim(sID), 1, iPos - 1))
    Else
        Extract_ID = sID
    End If
    
End Function
Private Sub cmdDefValues_Click()
    
    
    Dim sEmp As String
    Dim sLoc As String
    
    
    'grdDetail.RemoveAll
    grdDetail.MoveFirst
    grdDefaultVal.MoveFirst
    
    If grdDetail.rows <> 0 And lstSelectedEmp.ListIndex <> -1 Then
        grdDetail.AddItem lstSelectedEmp.List(lstSelectedEmp.ListIndex) + Chr(9) + grdDefaultVal.Columns(0).Value + Chr(9) + _
                          grdDefaultVal.Columns(1).Value + Chr(9) + grdDefaultVal.Columns(2).Value + Chr(9) + _
                          grdDefaultVal.Columns(3).Value + Chr(9) + grdDefaultVal.Columns(4).Value + Chr(9) + _
                          sLoc + Chr(9) + grdDefaultVal.Columns(6).Value + Chr(9) + _
                          grdDefaultVal.Columns(7).Value + Chr(9) + grdDefaultVal.Columns(8).Value + Chr(9) + _
                          grdDefaultVal.Columns(9).Value + Chr(9) + grdDefaultVal.Columns(10).Value + Chr(9) + _
                          grdDefaultVal.Columns(11).Value
        Exit Sub
    
    End If
    
'    If grdDetail.Rows <> 0 And lstSelectedEmp.ListIndex = -1 Then
'        grdDetail.AddItem Chr(9) + grdDefaultVal.Columns(0).Value + Chr(9) + _
'                          grdDefaultVal.Columns(1).Value + Chr(9) + grdDefaultVal.Columns(2).Value + Chr(9) + _
'                          grdDefaultVal.Columns(3).Value + Chr(9) + grdDefaultVal.Columns(4).Value + Chr(9) + _
'                          sLoc + Chr(9) + grdDefaultVal.Columns(6).Value + Chr(9) + _
'                          grdDefaultVal.Columns(7).Value + Chr(9) + grdDefaultVal.Columns(8).Value + Chr(9) + _
'                          grdDefaultVal.Columns(9).Value + Chr(9) + grdDefaultVal.Columns(10).Value + Chr(9) + _
'                          grdDefaultVal.Columns(11).Value
'        Exit Sub
'
'    End If
    
    grdDetail.RemoveAll
    
    For irec = 0 To lstSelectedEmp.ListCount - 1
        
        sEmp = Extract_ID(lstSelectedEmp.List(irec))
        
        sqlStmt = " SELECT LOCATION_ID FROM DAILY_HIRE_LIST WHERE EMPLOYEE_ID='" & Trim(sEmp) & "' AND " _
                & " HIRE_DATE>TO_DATE( '" & Format(DTPDate.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')-1 " _
                & " AND HIRE_DATE<TO_DATE( '" & Format(DTPDate.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 "
                
        Set dsLOCATION = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsLOCATION.RecordCount > 0 Then
            sLoc = dsLOCATION.Fields("LOCATION_ID").Value
        Else
            sLoc = ""
        End If
    
        grdDetail.AddItem lstSelectedEmp.List(irec) + Chr(9) + grdDefaultVal.Columns(0).Value + Chr(9) + _
                          grdDefaultVal.Columns(1).Value + Chr(9) + grdDefaultVal.Columns(2).Value + Chr(9) + _
                          grdDefaultVal.Columns(3).Value + Chr(9) + grdDefaultVal.Columns(4).Value + Chr(9) + _
                          sLoc + Chr(9) + grdDefaultVal.Columns(6).Value + Chr(9) + _
                          grdDefaultVal.Columns(7).Value + Chr(9) + grdDefaultVal.Columns(8).Value + Chr(9) + _
                          grdDefaultVal.Columns(9).Value + Chr(9) + grdDefaultVal.Columns(10).Value + Chr(9) + _
                          grdDefaultVal.Columns(11).Value
                          
        grdDetail.Columns(12).Value = CStr(iRowNum)
                          
                          
        grdDetail.MoveNext
    Next irec
End Sub

Private Sub cmdDelGrp_Click()
    
    If cboGrp.Text = "Create New Group" Then Exit Sub
    
    Me.MousePointer = 11
    
    sqlStmt = " DELETE FROM HOURLY_DETAIL WHERE USER_ID='" & Extract_ID(Trim(lblSuper)) & "' " _
            & " AND HOURLY_GROUP='" & Trim(cboGrp.Text) & "'"
                
    OraSession.BeginTrans
    OraDatabase.ExecuteSQL (sqlStmt)
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        Call UpdateRowNo
        Call cmdClearAll_Click
        Call Super
        Me.MousePointer = 0
    Else
        Me.MousePointer = 0
        OraSession.Rollback
        MsgBox "Unable to delete the records " & vbCrLf & OraDatabase.LastServerErrText, vbCritical, "ERROR"
        OraDatabase.LastServerErrReset
    End If
    
End Sub

Private Sub cmdExit_Click()
    Unload Me
End Sub
Private Function rowNo(empId As String)
    Dim RowRS As Object
    Dim myRowSQL As String
    sqlStmt = " Select max(Row_Number) MAXROWNO from Hourly_Detail where Employee_ID = '" & empId & "' and " _
            & " Hire_Date = to_date('" & Format(DTPDate, "MM/DD/YYYY") & "','mm/dd/yyyy') Order by Row_Number"
    Set RowRS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If Not IsNull(RowRS.Fields("MAXROWNO").Value) Then
        rowNo = RowRS.Fields("MAXROWNO").Value + 1
    Else
        rowNo = 1
     End If
  
End Function

Private Sub cmdLabTicket_Click()
    frmLaborTicket.Show
End Sub

Private Sub cmdPrint_Click()
    Dim For1 As String
    Dim For2 As String
    Dim Formula  As String
    Dim sUserId As String
    
    sUserId = Extract_ID(Trim(lblSuper))
    
     '{HOURLY_DETAIL.USER_ID}='E000442'  AND {HOURLY_DETAIL.HIRE_DATE} < Date (2001,01,24)
     
    For1 = "{HOURLY_DETAIL.USER_ID}='" + sUserId + "' "
    For2 = "{HOURLY_DETAIL.HIRE_DATE} = Date (" + CStr(Format(DTPDate, "YYYY")) + "," + CStr(Format(DTPDate, "MM")) + "," + CStr(Format(DTPDate, "DD")) + ")"
    Formula$ = For1$ + " AND " + For2$
    
    crw1.SelectionFormula = Formula
    
    crw1.ReportFileName = App.Path & "\GrpHrDetail.rpt"
    
    crw1.Connect = "DSN = LCS;UID = LABOR;PWD =LABOR"
    
    crw1.Formulas(0) = "SUPNAME= " + Chr$(39) + UCase(EMP_NAME(sUserId)) + Chr$(39)
    crw1.Formulas(0) = "HIREDATE= " + Chr$(39) + CStr(Format(DTPDate, "mm/dd/yyyy")) + Chr$(39)
    crw1.Action = 1
    
    If crw1.LastErrorNumber <> 0 Then MsgBox crw1.LastErrorString
    
    
End Sub

'Private Sub cmdReset_Click()
'     Call cboGrp_Click
'End Sub

Private Sub cmdSave_Click()
    Dim sStartTime As String
    Dim sEndTime As String
    Dim sNewGrp As String
    Dim iDuration As Double
    Dim sSTime As String
    Dim sETime As String
    Dim iNew As Long
    
    
    If Trim(lblSuper.Caption) = "" Then
        MsgBox "Only Supervisor can save the records", vbInformation, "LABOR"
        Exit Sub
    End If
    
    If cboGrp.Text = "" Then cboGrp.Text = "Create New Group"
    
    If grdDetail.rows = 0 Then Exit Sub
    If grdHrGrp.rows = 0 Then Exit Sub
    If iRowNum = -1 Then
        MsgBox "Enter Start & End Time ", vbInformation, sMsg
        Exit Sub
    End If
    
    grdDetail.MoveFirst
    
    With grdDetail
        For irec = 0 To .rows - 1
            If Trim(.Columns(0).Value) = "" Or Trim(.Columns(1).Value) = "" Or Trim(.Columns(3).Value) = "" Or _
                Trim(.Columns(5).Value) = "" Or Trim(.Columns(6).Value) = "" Or Trim(.Columns(7).Value) = "" Or _
                Trim(.Columns(8).Value) = "" Then
                    MsgBox " Required  Fields are Empty.", vbInformation, sMsg
                Exit Sub
            End If
            .MoveNext
        Next irec
    End With
    
   
    grdDetail.MoveFirst
    grdHrGrp.MoveFirst
    iRowNum = CInt(grdDetail.Columns(12).Value)
    For irec = 0 To grdHrGrp.rows - 1
        If grdHrGrp.Columns(0).Value <> "" And grdHrGrp.Columns(1).Value <> "" And grdHrGrp.Columns(2).Value <> "" Then
            If CStr(iRowNum) = grdHrGrp.Columns(4).Value Then
                sStartTime = Format(grdHrGrp.Columns(0).Value, "HH:MM")
                sEndTime = Format(grdHrGrp.Columns(1).Value, "HH:MM")
                iDuration = CDbl(grdHrGrp.Columns(2).Value)
                Exit For
            End If
        Else
            MsgBox " Fields are empty in Hour Group Grid", vbInformation, sMsg
            Exit Sub
        End If
        grdHrGrp.MoveNext
    Next irec
        
     If cboGrp.Text = "Create New Group" Then
        iNew = NewGrp
        cboGrp = iNew
     Else
        iNew = cboGrp
     End If
                
    If sStartTime = "" Or sEndTime = "" Then
        MsgBox " Enter Start & End Time ", vbInformation, sMsg
    End If
    
    OraSession.BeginTrans
    
    Me.MousePointer = 11
    
    sSTime = Format(DTPDate.Value, "MM/DD/YYYY") & " " & Format(sStartTime, "HH:MM AMPM")
    sETime = Format(DTPDate.Value, "MM/DD/YYYY") & " " & Format(sEndTime, "HH:MM AMPM")
    
    sqlStmt = " SELECT * FROM HOURLY_DETAIL WHERE USER_ID='" & Extract_ID(Trim(lblSuper)) & "' " _
            & " AND HIRE_DATE>TO_DATE( '" & Format(DTPDate.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')-1 " _
            & " AND HIRE_DATE<TO_DATE( '" & Format(DTPDate.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
            & " AND HOURLY_GROUP='" & Trim(cboGrp.Text) & "'" _
            & " AND START_TIME= TO_DATE('" & sSTime & "','mm/dd/yyyy HH:MI PM') " _
            & " AND END_TIME= TO_DATE('" & sETime & "','MM/DD/YYYY HH:MI PM') "
    
    Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsHOURLY_DETAIL.RecordCount > 0 Then
        sqlStmt = " DELETE FROM HOURLY_DETAIL WHERE USER_ID='" & Extract_ID(Trim(lblSuper)) & "' " _
                & " AND HIRE_DATE>TO_DATE( '" & Format(DTPDate.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')-1 " _
                & " AND HIRE_DATE<TO_DATE( '" & Format(DTPDate.Value, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
                & " AND HOURLY_GROUP='" & Trim(cboGrp.Text) & "'" _
                & " AND START_TIME= TO_DATE('" & sSTime & "','mm/dd/yyyy HH:MI PM') " _
                & " AND END_TIME= TO_DATE('" & sETime & "','MM/DD/YYYY HH:MI PM') "
            
            OraDatabase.ExecuteSQL (sqlStmt)
            
    End If
    
    sqlStmt = "SELECT * FROM HOURLY_DETAIL"
    Set dsHOURLY_DETAIL = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        grdDetail.MoveFirst
        With dsHOURLY_DETAIL
            For irec = 0 To grdDetail.rows - 1
                .AddNew
                .Fields("HIRE_DATE").Value = Format(DTPDate.Value, "MM/DD/YYYY")
                .Fields("EMPLOYEE_ID").Value = Extract_ID(Trim(grdDetail.Columns(0).Value))
                .Fields("EARNING_TYPE_ID").Value = Trim(grdDetail.Columns(1).Value)
                If grdDetail.Columns(2).Value = -1 Then
                    .Fields("BILLING_FLAG").Value = "Y"
                Else
                    .Fields("BILLING_FLAG").Value = "N"
                End If
                .Fields("SERVICE_CODE").Value = Trim(grdDetail.Columns(3).Value)
                .Fields("EQUIPMENT_ID").Value = Trim(grdDetail.Columns(4).Value)
                .Fields("COMMODITY_CODE").Value = Trim(grdDetail.Columns(5).Value)
                .Fields("LOCATION_ID").Value = Trim(grdDetail.Columns(6).Value)
                .Fields("VESSEL_ID").Value = Trim(grdDetail.Columns(7).Value)
                .Fields("CUSTOMER_ID").Value = Trim(grdDetail.Columns(8).Value)
                .Fields("SPECIAL_CODE").Value = Trim(grdDetail.Columns(9).Value)
                .Fields("REMARK").Value = Trim(grdDetail.Columns(10).Value)
                .Fields("EXACT_END").Value = Trim(grdDetail.Columns(11).Value)
                .Fields("START_TIME").Value = Format(DTPDate.Value, "MM/DD/YYYY") & " " & Format(sStartTime, "HH:MM AMPM")
                .Fields("END_TIME").Value = Format(DTPDate.Value, "MM/DD/YYYY") & " " & Format(sEndTime, "HH:MM AMPM")
                .Fields("DURATION").Value = iDuration
                .Fields("USER_ID").Value = Extract_ID(Trim(lblSuper))
                .Fields("TIME_ENTRY").Value = Format(Now, "MM/DD/YYYY HH:MM")
                .Fields("HOURLY_GROUP").Value = iNew
                .Fields("ROW_NUMBER") = rowNo(Extract_ID(Trim(grdDetail.Columns(0).Value)))
                .Update
                grdDetail.MoveNext
            Next irec
            
        End With
        grdDetail.MoveFirst
    End If
    
    cboGrp = iNew
    If OraDatabase.LastServerErr = 0 Then
        OraSession.CommitTrans
        MsgBox " Changes are saved successfully !", vbInformation, sMsg
        Call UpdateRowNo
        Call Super
        Me.MousePointer = 0
        
    Else
        Me.MousePointer = 0
        OraSession.Rollback
        MsgBox OraDatabase.LastServerErrText, vbCritical, sMsg
        OraDatabase.LastServerErrReset
        Exit Sub
    End If
End Sub
Function NewGrp() As Long
Dim DSMAX As Object      '5/2/2007 HD2759 Rudy:

    sqlStmt = "SELECT MAX(HOURLY_GROUP) MAXHR FROM HOURLY_DETAIL WHERE HIRE_DATE=TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY')"
    Set DSMAX = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If Not IsNull(DSMAX.Fields("MAXHR").Value) Then
            NewGrp = DSMAX.Fields("MAXHR").Value + 1
        Else
            NewGrp = 1
        End If
    End If
    
    Set DSMAX = Nothing    '5/2/2007 HD2759 Rudy:
End Function
Private Sub cmdSelect_Click(Index As Integer)
    
    Select Case Index
        
        Case 0
                If lstAllEmp.ListCount <> 0 And lstAllEmp.ListIndex <> -1 Then
                    If lstSelectedEmp.ListCount > 0 Then
                        For irec = 0 To lstSelectedEmp.ListCount
                            If Trim(lstAllEmp.List(lstAllEmp.ListIndex)) = Trim(lstSelectedEmp.List(irec)) Then
                                Exit Sub
                            End If
                        Next irec
                    End If
                    lstSelectedEmp.AddItem lstAllEmp.List(lstAllEmp.ListIndex)
                        'lstAllEmp.RemoveItem (lstAllEmp.ListIndex)
                End If
        Case 1
                If lstAllEmp.ListCount = 0 Then Exit Sub
                lstSelectedEmp.Clear
                For irec = 0 To lstAllEmp.ListCount - 1
                    lstSelectedEmp.AddItem lstAllEmp.List(irec)
                Next irec
                lstAllEmp.Clear
        Case 2
                If lstSelectedEmp.ListCount <> 0 And lstSelectedEmp.ListIndex <> -1 Then
                    'lstAllEmp.AddItem lstSelectedEmp.List(lstSelectedEmp.ListIndex)
                    lstSelectedEmp.RemoveItem (lstSelectedEmp.ListIndex)
                End If
        Case 3
                If lstSelectedEmp.ListCount = 0 Then Exit Sub
                'For iRec = 0 To lstSelectedEmp.ListCount - 1
                 '   lstAllEmp.AddItem lstSelectedEmp.List(iRec)
                'Next iRec
                lstSelectedEmp.Clear
                
    End Select
    
End Sub



Private Sub dtpDate_Validate(Cancel As Boolean)
    Dim dCheck As Date
    
    If Format(Now, "dddd") = "Monday" Then
        If CDate(Format(Now, "HH:MM AMPM")) > TimeValue("01:00 PM") Then
            If CDate(Format(DTPDate, "mm/dd/yyyy")) < CDate(Format(Now, "mm/dd/yyyy")) Then
                MsgBox " You can not access the perivious records!"
                DTPDate = Format(Now, "mm/dd/yyyy")
                Cancel = True
            End If
        End If
    ElseIf Format(Now, "dddd") <> "Monday" Then
        If Format(Now, "dddd") = "Tuesday" Then
            If CDate(DateAdd("d", -1, Format(Now, "mm/dd/yyyy"))) > CDate(Format(DTPDate, "mm/dd/yyyy")) Then
                MsgBox " You can not access the perivious records!"
                DTPDate = Format(Now, "mm/dd/yyyy")
                Cancel = True
            End If
    ElseIf Format(Now, "dddd") = "Wednesday" Then
        If CDate(DateAdd("d", -2, Format(Now, "mm/dd/yyyy"))) > CDate(Format(DTPDate, "mm/dd/yyyy")) Then
                MsgBox " You can not access the perivious records!"
                DTPDate = Format(Now, "mm/dd/yyyy")
                Cancel = True
         End If
    ElseIf Format(Now, "dddd") = "Thursday" Then
        If CDate(DateAdd("d", -3, Format(Now, "mm/dd/yyyy"))) > CDate(Format(DTPDate, "mm/dd/yyyy")) Then
                MsgBox " You can not access the perivious records!"
                DTPDate = Format(Now, "mm/dd/yyyy")
                Cancel = True
         End If
    ElseIf Format(Now, "dddd") = "Friday" Then
        If CDate(DateAdd("d", -4, Format(Now, "mm/dd/yyyy"))) > CDate(Format(DTPDate, "mm/dd/yyyy")) Then
                MsgBox " You can not access the perivious records!"
                DTPDate = Format(Now, "mm/dd/yyyy")
                Cancel = True
         End If
    ElseIf Format(Now, "dddd") = "Saturday" Then
        If CDate(DateAdd("d", -5, Format(Now, "mm/dd/yyyy"))) > CDate(Format(DTPDate, "mm/dd/yyyy")) Then
                MsgBox " You can not access the perivious records!"
                DTPDate = Format(Now, "mm/dd/yyyy")
                Cancel = True
         End If
    ElseIf Format(Now, "dddd") = "Sunday" Then
        If CDate(DateAdd("d", -6, Format(Now, "mm/dd/yyyy"))) > CDate(Format(DTPDate, "mm/dd/yyyy")) Then
                MsgBox " You can not access the perivious records!"
                DTPDate = Format(Now, "mm/dd/yyyy")
                Cancel = True
         End If
    End If
 End If
 
 cboGrp.Clear
 cboGrp.Text = "Create New Group"
 Call Super
 Call Load_Emp_list

End Sub
Sub UpdateRowNo()
Dim jRec As Integer
Dim RowRS As Object
Dim empId As String
Dim rowsql As String   '5/2/2007 HD2759 Rudy:

    OraSession.BeginTrans
    
    For irec = 0 To lstSelectedEmp.ListCount - 1
        empId = Extract_ID(lstSelectedEmp.List(irec))
        
        rowsql = " Select Row_Number from Hourly_Detail where Employee_ID = '" & empId & "' and " _
                & " Hire_Date = to_date('" & Format(DTPDate, "MM/DD/YYYY") & "','mm/dd/yyyy') order by Row_Number"
        Set RowRS = OraDatabase.DBCreateDynaset(rowsql, 0&)
        If RowRS.RecordCount > 0 Then
          
            If RowRS.RecordCount = 1 And RowRS.Fields("row_Number").Value = 1 Then
                OraSession.CommitTrans
                Exit Sub
            End If
            For jRec = 1 To RowRS.RecordCount
        
                RowRS.Edit
                RowRS.Fields("ROW_NUMBER").Value = jRec
                RowRS.Update
            
                RowRS.MoveNext
            Next jRec
        End If
    
    Next irec
    
    OraSession.CommitTrans
    
End Sub

Private Sub Form_Load()
Dim DSLIST As Object     '5/2/2007 HD2759 Rudy:

    Me.Top = (Screen.Height - Me.Height) / 2
    Me.Left = (Screen.Width - Me.Width) / 2
    
   
    
    DTPDate.Value = Format(Now, "MM/DD/YYYY")
    
    DoEvents

    sqlStmt = "SELECT *  FROM EMPLOYEE WHERE EMPLOYEE_TYPE_ID='SUPV' AND EMPLOYEE_ID='" & Trim(UserID) & "' ORDER BY EMPLOYEE_ID"
    Set dsSUPERVISOR = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsSUPERVISOR.RecordCount > 0 Then
        lblSuper = UserID & "-" & dsSUPERVISOR.Fields("EMPLOYEE_NAME").Value
    End If
    
    sqlStmt = " SELECT DISTINCT LOCATION_ID FROM DAILY_HIRE_LIST LOCATION_CATEGORY WHERE " _
            & " HIRE_DATE=TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY') AND " _
            & " LOCATION_ID IS NOT NULL ORDER BY LOCATION_ID"
    Set DSLIST = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And DSLIST.RecordCount > 0 Then
        For irec = 1 To DSLIST.RecordCount
            cboLocation.AddItem DSLIST.Fields("LOCATION_ID").Value
            DSLIST.MoveNext
        Next irec
    End If
    
    'lblSuper.Caption = UserId
    
    Call FillGridCombo
    Call Load_Emp_list
   ' grdHrGrp.Rows = 1
    
    cboGrp.AddItem "Create New Group", 0
    
    Set DSLIST = Nothing       '5/2/2007 HD2759 Rudy:
End Sub

Sub Load_Emp_list()
Dim dsEMPLOYEE As Object     '5/2/2007 HD2759 Rudy:

    lstAllEmp.Clear
    
    If optFilter(0).Value = True Then
        sqlStmt = "SELECT  E.EMPLOYEE_ID,EMPLOYEE_NAME FROM EMPLOYEE E, DAILY_HIRE_LIST DHL " _
                & " WHERE E.EMPLOYEE_TYPE_ID<>'SUPV' AND E.EMPLOYEE_ID=DHL.EMPLOYEE_ID " _
                & " AND DHL.HIRE_DATE=TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY') ORDER BY EMPLOYEE_ID"
    ElseIf optFilter(1).Value = True Then
        If cboLocation.ListIndex <> -1 Then
            sqlStmt = " SELECT  E.EMPLOYEE_ID,E.EMPLOYEE_NAME FROM EMPLOYEE E, DAILY_HIRE_LIST DHL " _
                    & " WHERE E.EMPLOYEE_TYPE_ID<>'SUPV' AND E.EMPLOYEE_ID=DHL.EMPLOYEE_ID " _
                    & " AND DHL.HIRE_DATE=TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                    & " AND DHL.LOCATION_ID='" & Trim(cboLocation) & "' ORDER BY EMPLOYEE_ID"
        Else
            Set dsEMPLOYEE = Nothing       '5/2/2007 HD2759 Rudy:
            Exit Sub
        End If
    ElseIf optFilter(2).Value = True Then
        If txtFiltEmpId = "" Then Exit Sub
        
        sqlStmt = " SELECT  E.EMPLOYEE_ID,E.EMPLOYEE_NAME FROM EMPLOYEE E, DAILY_HIRE_LIST DHL " _
                & " WHERE E.EMPLOYEE_TYPE_ID<>'SUPV' AND E.EMPLOYEE_ID=DHL.EMPLOYEE_ID " _
                & " AND DHL.HIRE_DATE=TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY') " _
                & " AND E.EMPLOYEE_ID LIKE '%" & Trim(txtFiltEmpId) & "' "
        
        Set dsEMPLOYEE = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsEMPLOYEE.RecordCount = 0 Then
            MsgBox "Not Hired today", vbInformation, sMsg
            
            Set dsEMPLOYEE = Nothing       '5/2/2007 HD2759 Rudy:
            Exit Sub
        End If
    End If
        
    Set dsEMPLOYEE = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsEMPLOYEE.RecordCount > 0 Then
        For irec = 1 To dsEMPLOYEE.RecordCount
            lstAllEmp.AddItem dsEMPLOYEE.Fields("EMPLOYEE_ID").Value & " - " & dsEMPLOYEE.Fields("EMPLOYEE_NAME").Value
            dsEMPLOYEE.MoveNext
            lstAllEmp.Refresh
            DoEvents
        Next irec
    End If
    
    Set dsEMPLOYEE = Nothing       '5/2/2007 HD2759 Rudy:
End Sub

Private Sub grdDefaultVal_AfterColUpdate(ByVal ColIndex As Integer)
    
    Dim dsValidate As Object
    
    grdDefaultVal.Columns(11).Value = iRowNum
    
    Select Case ColIndex
        
        Case 2            ' SERVICE
             sqlStmt = "SELECT * FROM SERVICE WHERE SERVICE_CODE='" & Trim(grdDefaultVal.Columns(2).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Service !", vbInformation + vbExclamation, sMsg
                grdDefaultVal.Columns(2).Value = ""
                Exit Sub
            End If
        
        Case 3             'EQUIPMENT
            
            sqlStmt = "SELECT * FROM EQUIPMENT WHERE EQUIPMENT_ID='" & Trim(grdDefaultVal.Columns(3).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Equipment !", vbInformation + vbExclamation, sMsg
                grdDefaultVal.Columns(3).Value = ""
                Exit Sub
            End If
            
        Case 4             'COMMODITY
            
            sqlStmt = "SELECT * FROM COMMODITY WHERE COMMODITY_CODE='" & Trim(grdDefaultVal.Columns(4).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Commodity !", vbInformation + vbExclamation, sMsg
                grdDefaultVal.Columns(4).Value = ""
                Exit Sub
            End If
            
        Case 5            'LOCATION
            
            sqlStmt = "SELECT * FROM LOCATION_CATEGORY WHERE LOCATION_ID='" & Trim(grdDefaultVal.Columns(5).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Location !", vbInformation + vbExclamation, sMsg
                grdDefaultVal.Columns(5).Value = ""
                Exit Sub
            End If
            
        Case 6            ' VESSEL
            
            sqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM='" & Trim(grdDefaultVal.Columns(6).Value) & "'"
            Set dsValidate = OraDatabaseBNI.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabaseBNI.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Vessel !", vbInformation + vbExclamation, sMsg
                grdDefaultVal.Columns(6).Value = ""
                Exit Sub
            End If
        Case 7  'CUSTOMER
        
            iPos = InStr(1, Trim(grdDefaultVal.Columns(7).Value), "-")
            If iPos <> 0 Then
                grdDefaultVal.Columns(7).Value = Mid(Trim(grdDefaultVal.Columns(7).Value), 1, iPos - 1)
            End If
            
            
            sqlStmt = "SELECT * FROM CUSTOMER WHERE CUSTOMER_ID='" & Trim(grdDefaultVal.Columns(7).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Customer !", vbInformation + vbExclamation, sMsg
                grdDefaultVal.Columns(7).Value = ""
                Exit Sub
            End If
        Case 10
            If Not IsDate(grdDefaultVal.Columns(10).Value) Then
                MsgBox " Not a Valid Date", vbInformation + vbExclamation, sMsg
                grdDefaultVal.Columns(10).Value = ""
                Exit Sub
            End If
    End Select
    
End Sub
Sub FillGridCombo()

    Dim dsGrid As Object
    
    sqlStmt = "SELECT * FROM SERVICE ORDER BY SERVICE_CODE"
    Set dsGrid = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsGrid.RecordCount > 0 Then
        For irec = 1 To dsGrid.RecordCount
            DoEvents
            grdDefaultVal.Columns(2).AddItem dsGrid.Fields("SERVICE_CODE").Value
            dsGrid.MoveNext
        Next irec
    End If
            
    sqlStmt = "SELECT * FROM EQUIPMENT ORDER BY EQUIPMENT_ID"
    Set dsGrid = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsGrid.RecordCount > 0 Then
        For irec = 1 To dsGrid.RecordCount
            DoEvents
            grdDefaultVal.Columns(3).AddItem dsGrid.Fields("EQUIPMENT_ID").Value
            dsGrid.MoveNext
        Next irec
    End If
            
    sqlStmt = "SELECT * FROM COMMODITY ORDER BY COMMODITY_CODE"
    Set dsGrid = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsGrid.RecordCount > 0 Then
        For irec = 1 To dsGrid.RecordCount
            DoEvents
            grdDefaultVal.Columns(4).AddItem dsGrid.Fields("COMMODITY_CODE").Value
            dsGrid.MoveNext
        Next irec
    End If
            
    sqlStmt = "SELECT * FROM LOCATION_CATEGORY ORDER BY LOCATION_ID"
    Set dsGrid = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsGrid.RecordCount > 0 Then
        For irec = 1 To dsGrid.RecordCount
            DoEvents
            grdDefaultVal.Columns(5).AddItem dsGrid.Fields("LOCATION_ID").Value
            dsGrid.MoveNext
        Next irec
    End If
            
    sqlStmt = "SELECT * FROM VESSEL_PROFILE ORDER BY LR_NUM"
    Set dsGrid = OraDatabaseBNI.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsGrid.RecordCount > 0 Then
        For irec = 1 To dsGrid.RecordCount
            DoEvents
            grdDefaultVal.Columns(6).AddItem dsGrid.Fields("LR_NUM").Value
            dsGrid.MoveNext
        Next irec
    End If
    
    sqlStmt = "SELECT * FROM CUSTOMER ORDER BY CUSTOMER_ID"
    Set dsGrid = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsGrid.RecordCount > 0 Then
        For irec = 1 To dsGrid.RecordCount
            DoEvents
            grdDefaultVal.Columns(7).AddItem dsGrid.Fields("CUSTOMER_NAME").Value
            dsGrid.MoveNext
        Next irec
    End If
    
End Sub
Private Sub grdDefaultVal_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub grdDetail_AfterColUpdate(ByVal ColIndex As Integer)
    Dim dsValidate As Object
    
    grdDetail.Columns(12).Value = iRowNum
    
    Select Case ColIndex
        Case 0             'EMPLOYEE
                
            sqlStmt = "SELECT * FROM EMPLOYEE WHERE EMPLOYEE_ID='" & Trim(grdDetail.Columns(0).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Employee !", vbInformation + vbExclamation, sMsg
                grdDetail.Columns(0).Value = ""
                Exit Sub
            End If
        Case 3            'service
            sqlStmt = "SELECT * FROM SERVICE WHERE SERVICE_CODE='" & Trim(grdDetail.Columns(3).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Service !", vbInformation + vbExclamation, sMsg
                grdDetail.Columns(3).Value = ""
                Exit Sub
            End If
            
        Case 4             'EQUIPMENT
            
            sqlStmt = "SELECT * FROM EQUIPMENT WHERE EQUIPMENT_ID='" & Trim(grdDetail.Columns(4).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Equipment !", vbInformation + vbExclamation, sMsg
                grdDetail.Columns(4).Value = ""
                Exit Sub
            End If
            
        Case 5             'COMMODITY
            
            sqlStmt = "SELECT * FROM COMMODITY WHERE COMMODITY_CODE='" & Trim(grdDetail.Columns(5).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Commodity !", vbInformation + vbExclamation, sMsg
                grdDetail.Columns(5).Value = ""
                Exit Sub
            End If
            
        Case 6            'LOCATION
            
            sqlStmt = "SELECT * FROM LOCATION_CATEGORY WHERE LOCATION_ID='" & Trim(grdDetail.Columns(6).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Location !", vbInformation + vbExclamation, sMsg
                grdDetail.Columns(6).Value = ""
                Exit Sub
            End If
            
        Case 7            ' VESSEL
            
            sqlStmt = "SELECT * FROM VESSEL_PROFILE WHERE LR_NUM='" & Trim(grdDetail.Columns(7).Value) & "'"
            Set dsValidate = OraDatabaseBNI.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabaseBNI.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Vessel !", vbInformation + vbExclamation, sMsg
                grdDetail.Columns(7).Value = ""
                Exit Sub
            End If
        Case 8             'CUSTOMER
            
            sqlStmt = "SELECT * FROM CUSTOMER WHERE CUSTOMER_ID='" & Trim(grdDetail.Columns(8).Value) & "'"
            Set dsValidate = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
            If OraDatabase.LastServerErr = 0 And dsValidate.RecordCount = 0 Then
                MsgBox "Invalid Customer !", vbInformation + vbExclamation, sMsg
                grdDetail.Columns(8).Value = ""
                Exit Sub
            End If
            
        Case 11
            If Not IsDate(grdDetail.Columns(11).Value) Then
                MsgBox " Not a Valid Date", vbInformation + vbExclamation, sMsg
                grdDetail.Columns(11).Value = ""
                Exit Sub
            End If
    End Select
    
        
        
End Sub

Private Sub grdDetail_BeforeUpdate(Cancel As Integer)
    grdDetail.Columns(12).Value = iRowNum
End Sub

Private Sub grdDetail_KeyPress(KeyAscii As Integer)
    KeyAscii = Asc(UCase(Chr(KeyAscii)))
End Sub

Private Sub grdHrGrp_AfterColUpdate(ByVal ColIndex As Integer)
    
    Dim dDiff As Double
    Dim dHr As Double
    Dim dMin As Double
    
    
    grdDefaultVal.RemoveAll
    grdDetail.RemoveAll
        
       
    If grdHrGrp.Columns(4).Value = "" Then
        grdHrGrp.Columns(4).Value = grdHrGrp.rows - 1
        iRowNum = grdHrGrp.rows - 1
    Else
        iRowNum = grdHrGrp.Columns(4).Value
    End If
    
    Select Case ColIndex
        Case 0
            
            If Not IsDate(grdHrGrp.Columns(0).Value) Then
                MsgBox "Not a valid Time", vbInformation, sMsg
                grdHrGrp.Columns(0).Value = ""
                Exit Sub
            End If
            
            If grdHrGrp.Columns(0).Value <> "" And grdHrGrp.Columns(1).Value <> "" Then
                
                dDiff = DateDiff("n", grdHrGrp.Columns(0).Value, grdHrGrp.Columns(1).Value)
                dHr = dDiff \ 60
                dMin = (dDiff Mod 60) / 60
                
                '5/2/2007 HD2759 Rudy:
                'grdData.Columns(2).Value = CDbl(dHr + dMin)   'variable not defined, so rem'd out
                
            End If
            
        Case 1
            If Not IsDate(grdHrGrp.Columns(1).Value) Then
                MsgBox "Not a valid Time", vbInformation, sMsg
                grdHrGrp.Columns(1).Value = ""
                Exit Sub
            End If
            
            If grdHrGrp.Columns(0).Value <> "" And grdHrGrp.Columns(1).Value <> "" Then
                
                dDiff = DateDiff("n", grdHrGrp.Columns(0).Value, grdHrGrp.Columns(1).Value)
                dHr = dDiff \ 60
                dMin = (dDiff Mod 60) / 60
                
                
                grdHrGrp.Columns(2).Value = CDbl(dHr + dMin)
                
            End If
        Case 2
        
    End Select
    
End Sub

Private Sub grdHrGrp_AfterDelete(RtnDispErrMsg As Integer)
    
    Me.MousePointer = 11
    sqlStmt = " DELETE FROM HOURLY_DETAIL WHERE HOURLY_GROUP='" & Trim(cboGrp.Text) & "'" _
            & " AND HIRE_DATE >TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY')-1" _
            & " AND HIRE_DATE <TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1" _
            & " AND START_TIME = TO_DATE('" & sDelStartTime & "' ,'MM/DD/YYYY HH:MI PM') " _
            & " AND END_TIME = TO_DATE('" & sDelEndTime & "','MM/DD/YYYY HH:MI PM') "
    OraDatabase.ExecuteSQL (sqlStmt)
    
    UpdateRowNo
    
    Call Super
    Me.MousePointer = 0
    
End Sub

Private Sub grdHrGrp_BeforeDelete(Cancel As Integer, DispPromptMsg As Integer)
     'DispPromptMsg = 0
End Sub

Private Sub grdHrGrp_BeforeRowColChange(Cancel As Integer)
    Dim dDiff As Double
    Dim dHr As Double
    Dim dMin As Double
    
    
    If grdHrGrp.Cols = 0 Or grdHrGrp.Cols = 1 Then
        If grdHrGrp.Columns(0).Value <> "" And grdHrGrp.Columns(1).Value <> "" Then
            dDiff = DateDiff("n", grdHrGrp.Columns(0).Value, grdHrGrp.Columns(1).Value)
            dHr = dDiff \ 60
            dMin = (dDiff Mod 60) / 60
            grdHrGrp.Columns(2).Value = CDbl(dHr + dMin)
        End If
    End If
    
End Sub

Private Sub grdHrGrp_Click()
Dim sStartTime As String
Dim sEndDate As String
Dim sEmp As String
Dim iBill As String
Dim sEndTime As String     '5/2/2007 HD2759 Rudy:

    grdDefaultVal.RemoveAll
    grdDetail.RemoveAll
    
    iRowNum = -1
    sDelStartTime = ""
    sDelEndTime = ""
    
    If grdHrGrp.Columns(4).Value = "" Then grdHrGrp.Columns(4).Value = grdHrGrp.rows
    
    
    
    If cboGrp.Text <> "Create New Group" Then
        If grdHrGrp.rows = 0 Then Exit Sub
        If grdHrGrp.Columns(0).Value = "" Or grdHrGrp.Columns(1).Value = "" Then Exit Sub
            
        sStartTime = Format(DTPDate, "MM/DD/YYYY") & " " & Format(grdHrGrp.Columns(0).Value, "HH:MM AMPM")
        sEndTime = Format(DTPDate, "MM/DD/YYYY") & " " & Format(grdHrGrp.Columns(1).Value, "HH:MM AMPM")
        
        sDelStartTime = sStartTime
        sDelEndTime = sEndTime
        
        Me.MousePointer = 11
        
        sqlStmt = " SELECT * FROM HOURLY_DETAIL WHERE HOURLY_GROUP='" & Trim(cboGrp.Text) & "'" _
                & " AND HIRE_DATE >TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY')-1" _
                & " AND HIRE_DATE <TO_DATE('" & Format(DTPDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1" _
                & " AND START_TIME = TO_DATE('" & sStartTime & "' ,'MM/DD/YYYY HH:MI PM') " _
                & " AND END_TIME = TO_DATE('" & sEndTime & "','MM/DD/YYYY HH:MI PM') "
                
        Set dsDETAILS = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
        If OraDatabase.LastServerErr = 0 And dsDETAILS.RecordCount > 0 Then
            With dsDETAILS
                For irec = 1 To dsDETAILS.RecordCount
                    
                    sEmp = .Fields("EMPLOYEE_ID").Value & " - " & EMP_NAME(.Fields("EMPLOYEE_ID").Value)
                    
                    If .Fields("BILLING_FLAG").Value = "Y" Then iBill = -1
                    If .Fields("BILLING_FLAG").Value = "N" Then iBill = 0
                    
                    grdDetail.AddItem sEmp + Chr(9) + _
                                      .Fields("EARNING_TYPE_ID").Value + Chr(9) + iBill + Chr(9) + _
                                      CStr(.Fields("SERVICE_CODE").Value) + Chr(9) + _
                                      CStr(.Fields("EQUIPMENT_ID").Value) + Chr(9) + CStr(.Fields("COMMODITY_CODE").Value) + Chr(9) + _
                                      .Fields("LOCATION_ID").Value + Chr(9) + CStr(.Fields("VESSEL_ID").Value) + Chr(9) + _
                                      .Fields("CUSTOMER_ID").Value + Chr(9) + Trim("" & .Fields("SPECIAL_CODE").Value) + Chr(9) + _
                                      Trim("" & .Fields("REMARK").Value) + Chr(9) + Trim("" & .Fields("EXACT_END").Value) + Chr(9) + _
                                      CStr(grdHrGrp.Columns(4).Value)
                    dsDETAILS.MoveNext
                Next irec
            End With
        End If
    End If
    iRowNum = grdHrGrp.Columns(4).Value
    
    Me.MousePointer = 0
    
End Sub

Private Sub optFilter_Click(Index As Integer)
    lstAllEmp.Clear
    Select Case Index
        Case 0
            txtFiltEmpId = ""
            cboLocation.ListIndex = -1
            Call Load_Emp_list
        Case 1
            txtFiltEmpId = ""
        Case 2
            cboLocation.ListIndex = -1
    End Select
End Sub

Private Sub txtFiltEmpId_Validate(Cancel As Boolean)
Dim dsEMPLOYEE As Object     '5/2/2007 HD2759 Rudy:

  If txtFiltEmpId = "" Then Exit Sub
  
  sqlStmt = " SELECT  * FROM EMPLOYEE WHERE EMPLOYEE_ID LIKE '%" & Trim(txtFiltEmpId) & "' "
  
  Set dsEMPLOYEE = OraDatabase.DBCreateDynaset(sqlStmt, 0&)
  If OraDatabase.LastServerErr = 0 And dsEMPLOYEE.RecordCount = 0 Then
    MsgBox "Invalid Empolyee ID", vbInformation, sMsg
    txtFiltEmpId = ""
    Cancel = True
    
    Set dsEMPLOYEE = Nothing     '5/2/2007 HD2759 Rudy:
    Exit Sub
  End If
  
  Call Load_Emp_list
  
  Set dsEMPLOYEE = Nothing       '5/2/2007 HD2759 Rudy:

End Sub
