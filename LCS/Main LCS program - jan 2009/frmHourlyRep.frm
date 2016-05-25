VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "mscomct2.ocx"
Begin VB.Form frmHourlyRep 
   Caption         =   "Hourly Report"
   ClientHeight    =   9075
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   14805
   BeginProperty Font 
      Name            =   "Times New Roman"
      Size            =   9.75
      Charset         =   0
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   LinkTopic       =   "Form1"
   ScaleHeight     =   9075
   ScaleWidth      =   14805
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
      Height          =   5895
      Left            =   0
      TabIndex        =   12
      Top             =   3000
      Width           =   14775
      _Version        =   196616
      RowHeight       =   503
      Columns.Count   =   23
      Columns(0).Width=   3200
      Columns(0).Caption=   "Supervisor"
      Columns(0).Name =   "Supervisor"
      Columns(0).DataField=   "Column 0"
      Columns(0).DataType=   8
      Columns(0).FieldLen=   256
      Columns(1).Width=   979
      Columns(1).Caption=   "0-1"
      Columns(1).Name =   "0-1"
      Columns(1).DataField=   "Column 1"
      Columns(1).DataType=   8
      Columns(1).FieldLen=   256
      Columns(2).Width=   979
      Columns(2).Caption=   "1-2"
      Columns(2).Name =   "1-2"
      Columns(2).DataField=   "Column 2"
      Columns(2).DataType=   8
      Columns(2).FieldLen=   256
      Columns(3).Width=   979
      Columns(3).Caption=   "2-3"
      Columns(3).Name =   "2-3"
      Columns(3).DataField=   "Column 3"
      Columns(3).DataType=   8
      Columns(3).FieldLen=   256
      Columns(4).Width=   979
      Columns(4).Caption=   "3-4"
      Columns(4).Name =   "3-4"
      Columns(4).DataField=   "Column 4"
      Columns(4).DataType=   8
      Columns(4).FieldLen=   256
      Columns(5).Width=   979
      Columns(5).Caption=   "4-5"
      Columns(5).Name =   "4-5"
      Columns(5).DataField=   "Column 5"
      Columns(5).DataType=   8
      Columns(5).FieldLen=   256
      Columns(6).Width=   979
      Columns(6).Caption=   "5-6"
      Columns(6).Name =   "5-6"
      Columns(6).DataField=   "Column 6"
      Columns(6).DataType=   8
      Columns(6).FieldLen=   256
      Columns(7).Width=   979
      Columns(7).Caption=   "6-7"
      Columns(7).Name =   "6-7"
      Columns(7).DataField=   "Column 7"
      Columns(7).DataType=   8
      Columns(7).FieldLen=   256
      Columns(8).Width=   979
      Columns(8).Caption=   "7-8"
      Columns(8).Name =   "7-8"
      Columns(8).DataField=   "Column 8"
      Columns(8).DataType=   8
      Columns(8).FieldLen=   256
      Columns(9).Width=   979
      Columns(9).Caption=   "8-9"
      Columns(9).Name =   "8-9"
      Columns(9).DataField=   "Column 9"
      Columns(9).DataType=   8
      Columns(9).FieldLen=   256
      Columns(10).Width=   979
      Columns(10).Caption=   "9-10"
      Columns(10).Name=   "9-10"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   8
      Columns(10).FieldLen=   256
      Columns(11).Width=   979
      Columns(11).Caption=   "10-11"
      Columns(11).Name=   "10-11"
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   8
      Columns(11).FieldLen=   256
      Columns(12).Width=   979
      Columns(12).Caption=   "11-12"
      Columns(12).Name=   "11-12"
      Columns(12).DataField=   "Column 12"
      Columns(12).DataType=   8
      Columns(12).FieldLen=   256
      Columns(13).Width=   979
      Columns(13).Caption=   "12-13"
      Columns(13).Name=   "12-13"
      Columns(13).DataField=   "Column 13"
      Columns(13).DataType=   8
      Columns(13).FieldLen=   256
      Columns(14).Width=   979
      Columns(14).Caption=   "13-14"
      Columns(14).Name=   "13-14"
      Columns(14).DataField=   "Column 14"
      Columns(14).DataType=   8
      Columns(14).FieldLen=   256
      Columns(15).Width=   979
      Columns(15).Caption=   "14-15"
      Columns(15).Name=   "14-15"
      Columns(15).DataField=   "Column 15"
      Columns(15).DataType=   8
      Columns(15).FieldLen=   256
      Columns(16).Width=   979
      Columns(16).Caption=   "15-16"
      Columns(16).Name=   "15-16"
      Columns(16).DataField=   "Column 16"
      Columns(16).DataType=   8
      Columns(16).FieldLen=   256
      Columns(17).Width=   979
      Columns(17).Caption=   "17-18"
      Columns(17).Name=   "17-18"
      Columns(17).DataField=   "Column 17"
      Columns(17).DataType=   8
      Columns(17).FieldLen=   256
      Columns(18).Width=   979
      Columns(18).Caption=   "19-20"
      Columns(18).Name=   "19-20"
      Columns(18).DataField=   "Column 18"
      Columns(18).DataType=   8
      Columns(18).FieldLen=   256
      Columns(19).Width=   979
      Columns(19).Caption=   "20-21"
      Columns(19).Name=   "20-21"
      Columns(19).DataField=   "Column 19"
      Columns(19).DataType=   8
      Columns(19).FieldLen=   256
      Columns(20).Width=   979
      Columns(20).Caption=   "21-22"
      Columns(20).Name=   "21-22"
      Columns(20).DataField=   "Column 20"
      Columns(20).DataType=   8
      Columns(20).FieldLen=   256
      Columns(21).Width=   979
      Columns(21).Caption=   "22-23"
      Columns(21).Name=   "22-23"
      Columns(21).DataField=   "Column 21"
      Columns(21).DataType=   8
      Columns(21).FieldLen=   256
      Columns(22).Width=   979
      Columns(22).Caption=   "23-24"
      Columns(22).Name=   "23-24"
      Columns(22).DataField=   "Column 22"
      Columns(22).DataType=   8
      Columns(22).FieldLen=   256
      _ExtentX        =   26061
      _ExtentY        =   10398
      _StockProps     =   79
      BeginProperty PageFooterFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      BeginProperty PageHeaderFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "Times New Roman"
         Size            =   9.75
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
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
      Height          =   1335
      Left            =   960
      TabIndex        =   2
      Top             =   1560
      Width           =   10335
      Begin VB.CommandButton cmdExit 
         Caption         =   "&Exit"
         Height          =   375
         Left            =   6360
         TabIndex        =   11
         Top             =   840
         Width           =   1935
      End
      Begin VB.CommandButton cmdPrint 
         Caption         =   "&Print"
         Height          =   375
         Left            =   3600
         TabIndex        =   10
         Top             =   840
         Width           =   1935
      End
      Begin VB.CommandButton cmdRetrive 
         Caption         =   "&Retrive"
         Height          =   375
         Left            =   720
         TabIndex        =   9
         Top             =   840
         Width           =   1935
      End
      Begin VB.ListBox List2 
         Height          =   285
         Left            =   6240
         TabIndex        =   8
         Top             =   360
         Width           =   735
      End
      Begin VB.ListBox List1 
         Height          =   285
         ItemData        =   "frmHourlyRep.frx":0000
         Left            =   3840
         List            =   "frmHourlyRep.frx":000A
         TabIndex        =   6
         Top             =   360
         Width           =   1095
      End
      Begin MSComCtl2.DTPicker DTPDate 
         Height          =   375
         Left            =   840
         TabIndex        =   4
         Top             =   240
         Width           =   1335
         _ExtentX        =   2355
         _ExtentY        =   661
         _Version        =   393216
         Format          =   61800449
         CurrentDate     =   37264
      End
      Begin VB.Label Label4 
         Caption         =   "End Time:"
         Height          =   255
         Left            =   5280
         TabIndex        =   7
         Top             =   360
         Width           =   855
      End
      Begin VB.Label Label3 
         Caption         =   "Start Time:"
         Height          =   255
         Left            =   2760
         TabIndex        =   5
         Top             =   360
         Width           =   975
      End
      Begin VB.Label Label2 
         Caption         =   "Date:"
         Height          =   255
         Left            =   240
         TabIndex        =   3
         Top             =   360
         Width           =   495
      End
   End
   Begin VB.Label Label1 
      Caption         =   "The Number of Employee Hourly Report"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3120
      TabIndex        =   1
      Top             =   1080
      Width           =   5295
   End
   Begin VB.Label labTitle 
      Alignment       =   2  'Center
      BackStyle       =   0  'Transparent
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   18
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   975
      Left            =   1440
      TabIndex        =   0
      Top             =   0
      Width           =   10095
   End
   Begin VB.Image Image1 
      BorderStyle     =   1  'Fixed Single
      Height          =   950
      Left            =   120
      Picture         =   "frmHourlyRep.frx":0024
      Stretch         =   -1  'True
      Top             =   0
      Width           =   975
   End
   Begin VB.Line Line1 
      X1              =   0
      X2              =   11760
      Y1              =   960
      Y2              =   960
   End
End
Attribute VB_Name = "frmHourlyRep"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
