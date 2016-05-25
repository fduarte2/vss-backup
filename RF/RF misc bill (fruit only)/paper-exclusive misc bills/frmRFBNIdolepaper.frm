VERSION 5.00
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Object = "{86CF1D34-0C5F-11D2-A9FC-0000F8754DA1}#2.0#0"; "MSCOMCT2.OCX"
Begin VB.Form frmRFBNIdolepaper 
   AutoRedraw      =   -1  'True
   Caption         =   "Paper RF-BNI Truck Loading bill generator"
   ClientHeight    =   10545
   ClientLeft      =   1740
   ClientTop       =   2145
   ClientWidth     =   16290
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
   ScaleHeight     =   10545
   ScaleWidth      =   16290
   Visible         =   0   'False
   Begin SSDataWidgets_B.SSDBGrid grdData 
      Height          =   7575
      Left            =   105
      TabIndex        =   2
      Top             =   2040
      Width           =   16095
      _Version        =   196616
      DataMode        =   2
      Col.Count       =   12
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
      ExtraHeight     =   873
      Columns.Count   =   12
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
      Columns(2).Caption=   "TICKET#/PO"
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
      Columns(10).Width=   3201
      Columns(10).Caption=   "RATE"
      Columns(10).Name=   "RATE"
      Columns(10).DataField=   "Column 10"
      Columns(10).DataType=   5
      Columns(10).FieldLen=   256
      Columns(11).Width=   3200
      Columns(11).Visible=   0   'False
      Columns(11).Caption=   "COMMODITY_CODE"
      Columns(11).Name=   "COMMODITY_CODE"
      Columns(11).DataField=   "Column 11"
      Columns(11).DataType=   2
      Columns(11).FieldLen=   256
      _ExtentX        =   28390
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
      Left            =   8880
      TabIndex        =   5
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
      Left            =   7320
      TabIndex        =   4
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
      Left            =   5760
      TabIndex        =   3
      Top             =   9720
      Width           =   1215
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
      Left            =   7440
      TabIndex        =   1
      Top             =   1440
      Width           =   1215
   End
   Begin MSComCtl2.DTPicker dtpDate 
      Height          =   375
      Left            =   7440
      TabIndex        =   0
      Top             =   840
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
      Format          =   16449539
      CurrentDate     =   36951
   End
End
Attribute VB_Name = "frmRFBNIdolepaper"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
        

Dim Sqlstmt As String
Dim dsAssetProfile As Object
Dim dsLOCATION_ID As Object
Dim dsRATEINFO As Object
Dim gsSqlStmt As String
Dim gs1SqlStmt As String
Dim iRec As Integer
Dim iPos As Integer

Dim i_original_qty_displayed_not_yet_misc As Integer
Dim i_qty_to_be_updated_in_Cargo_act As Integer

Dim strDescripOutput As String
Dim intServiceCode As Integer
Dim intCommCode As Integer
'Dim dblRate As Double



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
    
    Sqlstmt = "SELECT * FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID='" & CustId & "'"
    Set dsCUSTOMER = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
    If OraDatabaseRF.LastServerErr = 0 And dsCUSTOMER.RecordCount > 0 Then
        iPos = InStr(1, dsCUSTOMER.Fields("CUSTOMER_NAME").Value, " ")
        If iPos <> 0 Then
            CustName = Mid(dsCUSTOMER.Fields("CUSTOMER_NAME").Value, 1, iPos - 1)
        Else
            CustName = dsCUSTOMER.Fields("CUSTOMER_NAME").Value
        End If
        
    Else
        CustName = CStr(CustId)
    End If


End Function


Private Sub cmdExit_Click()
    
    Unload Me
    
End Sub
Private Sub cmdPrint_Click()

    grdData.MoveFirst
    Printer.Orientation = 2
    Printer.Print "Printed on : " & Format(Now, "mm/dd/yyyy")
    Printer.Print ""
    Printer.Print ""
    Printer.FontSize = 12
    Printer.FontBold = True
        
    Printer.Print Tab(55); "PAPER INBOUND TRUCKLOADING"
    Printer.FontSize = 9
    Printer.FontBold = True
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(10); "DESCRIPTION"; Tab(30); ":"; Tab(35); strDescripOutput
    Printer.Print ""
    Printer.Print Tab(10); "SERVICE"; Tab(30); ":"; Tab(35); 6221
    Printer.Print ""
    Printer.Print Tab(10); "COMMODITY"; Tab(30); ":"; Tab(35); CommName(1272)
    Printer.Print ""
    Printer.Print ""
    Printer.Print Tab(5); "----------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------" _
                        ; "----------------------------------------------------------------------------------------------------------"
    Printer.Print Tab(7); "DATE"; Tab(20); "OWNER"; Tab(50); "TICKET#"; Tab(65); "ORDER #"; Tab(80); "COMMODITY"; Tab(100); "PLTS"; _
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

    Printer.EndDoc
    grdData.MoveFirst
    
End Sub
Private Sub cmdRet_Click()

    Dim iTransfer_To As Integer
    Dim dRate As Double
    
    i_original_qty_displayed_not_yet_misc = 0
    
 
    grdData.RemoveAll
    grdData.Visible = True
    
       
    
    
'        If Check1.Value = 0 Then
'            Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='8' AND SERVICE_DATE >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
'                    & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') + 1 " _
'                    & " AND REMARK != 'DOLEPAPERSYSTEM'" _
'                    & " AND DESCRIPTION LIKE '%PANDOL A2 RATE%' AND BILLING_FLAG IS NULL"
'        Else
            Sqlstmt = " SELECT RFB.*, NVL(ASSET, CUSTOMER_ID) THE_CUST FROM RF_BNI_MISCBILLS RFB " _
                    & " WHERE RF_SERVICE_CODE='8' AND SERVICE_DATE >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                    & " AND SERVICE_DATE < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') + 1 " _
                    & " AND COMMODITY_CODE IN ('1272', '1299')" _
                    & " AND DESCRIPTION NOT LIKE '%PANDOL A2 RATE%' AND BILLING_FLAG IS NULL"
'        End If
        Set dsINBOUNDS = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseBNI.LastServerErr = 0 And dsINBOUNDS.RecordCount > 0 Then
            
            With dsINBOUNDS
                For iRec = 1 To .RecordCount
                    dRate = GetRate(.Fields("CUSTOMER_ID").Value, .Fields("COMMODITY_CODE").Value)
                    If dRate = 0 Then
                        MsgBox "Rate of 0 found in table for customer " & CStr(.Fields("CUSTOMER_ID").Value) & " / Commodity " & CStr(.Fields("COMMODITY_CODE").Value) & ".  Please contact TS", , "LU_DOLEPAPER_TRUCKLOADING_RATE"
                    End If
                    
                    grdData.AddItem CStr(.Fields("SERVICE_DATE").Value) + Chr(9) + CustName(.Fields("THE_CUST").Value) + Chr(9) + _
                                    .Fields("LR_NUM").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("SERVICE_QTY").Value + Chr(9) + _
                                    CStr(Abs(.Fields("CASES").Value)) + Chr(9) + .Fields("AVG_WT").Value + Chr(9) + _
                                    .Fields("WEIGHT").Value + Chr(9) + CStr(.Fields("AMOUNT").Value) + Chr(9) + CStr(dRate) + Chr(9) + .Fields("COMMODITY_CODE").Value
                    .MoveNext
                Next iRec
            End With
        End If
         
        Sqlstmt = " SELECT COUNT(*) PLTCOUNT,SUM(QTY_CHANGE) CASES,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY') ACT_DATE,CUSTOMER_ID,NVL(BAD.ORDER_NUM, CT.BOL) THE_BOL,CA.ORDER_NUM, MAX(DATE_OF_ACTIVITY) THE_DATESORT, " _
                & " COMMODITY_CODE, SUM(DECODE(WEIGHT_UNIT, 'LB', WEIGHT, 'KG', (WEIGHT *2.2046), (WEIGHT * 10000))) THE_WEIGHT " _
                & " FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT, BOOKING_ADDITIONAL_DATA BAD WHERE " _
                & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY')+1 " _
                & " AND TO_MISCBILL IS NULL AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION<>'VOID') " _
                & " AND SERVICE_CODE ='8' AND CT.ARRIVAL_NUM=CA.ARRIVAL_NUM " _
                & " AND (CT.REMARK = 'DOLEPAPERSYSTEM' OR CT.REMARK = 'BOOKINGSYSTEM')" _
                & " AND CT.RECEIVER_ID=CA.CUSTOMER_ID " _
                & " AND CT.PALLET_ID=CA.PALLET_ID" _
                & " AND CT.RECEIVER_ID=BAD.RECEIVER_ID(+) " _
                & " AND CT.PALLET_ID=BAD.PALLET_ID(+)" _
                & " AND CT.ARRIVAL_NUM=BAD.ARRIVAL_NUM(+)" _
                & " GROUP BY CUSTOMER_ID,NVL(BAD.ORDER_NUM, CT.BOL),CA.ORDER_NUM,TO_CHAR(DATE_OF_ACTIVITY,'MM/DD/YYYY'),COMMODITY_CODE" _
                & " ORDER BY MAX(DATE_OF_ACTIVITY) DESC"
'                & " ORDER BY CUSTOMER_ID,NVL(BAD.ORDER_NUM, CT.BOL),CA.ORDER_NUM,COMMODITY_CODE"
                
               
        Set dsINBOUNDS = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
        If OraDatabaseRF.LastServerErr = 0 And dsINBOUNDS.RecordCount > 0 Then
        
        
            
            With dsINBOUNDS
                For iRec = 1 To .RecordCount
                    dRate = GetRate(.Fields("CUSTOMER_ID").Value, .Fields("COMMODITY_CODE").Value)
                    
                    grdData.AddItem .Fields("ACT_DATE").Value + Chr(9) + CustName(.Fields("CUSTOMER_ID").Value) + Chr(9) + _
                                    .Fields("THE_BOL").Value + Chr(9) + .Fields("ORDER_NUM").Value + Chr(9) + _
                                    CommName(.Fields("COMMODITY_CODE").Value) + Chr(9) + .Fields("PLTCOUNT").Value + Chr(9) + _
                                    .Fields("CASES").Value + Chr(9) + CStr(Round(Val("" & .Fields("THE_WEIGHT").Value) / Val("" & .Fields("PLTCOUNT").Value), 1)) + Chr(9) + _
                                    CStr(.Fields("THE_WEIGHT").Value) + Chr(9) + CStr(Round(Val("" & .Fields("THE_WEIGHT").Value) * dRate / 2000, 2)) + Chr(9) + CStr(dRate) + Chr(9) + .Fields("COMMODITY_CODE").Value
                    i_original_qty_displayed_not_yet_misc = i_original_qty_displayed_not_yet_misc + .Fields("PLTCOUNT").Value
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
    
    
End Sub
Private Sub cmdSave_Click()


    Dim Location As String

    OraSession.BeginTrans
    
'    Sqlstmt = "SELECT COUNT(*) THE_COUNT FROM CARGO_ACTIVITY WHERE TO_MISCBILL IS NULL" _
'            & " AND DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
'            & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
'            & " AND SERVICE_CODE ='8' AND ACTIVITY_DESCRIPTION IS NULL " _
'            & " AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS)"
    Sqlstmt = "SELECT COUNT(*) THE_COUNT " _
                & " FROM CARGO_ACTIVITY CA, CARGO_TRACKING CT WHERE " _
                & " DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
                & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY')+1 " _
                & " AND TO_MISCBILL IS NULL AND (ACTIVITY_DESCRIPTION IS NULL OR ACTIVITY_DESCRIPTION<>'VOID') " _
                & " AND SERVICE_CODE ='8' AND CT.ARRIVAL_NUM=CA.ARRIVAL_NUM " _
                & " AND (CT.REMARK = 'DOLEPAPERSYSTEM' OR CT.REMARK = 'BOOKINGSYSTEM')" _
                & " AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM" _
                & " AND CT.RECEIVER_ID=CA.CUSTOMER_ID " _
                & " AND CT.PALLET_ID=CA.PALLET_ID"
    Set dsSHORT_TERM_DATA = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
    
    i_qty_to_be_updated_in_Cargo_act = dsSHORT_TERM_DATA.Fields("THE_COUNT").Value
    
    If i_qty_to_be_updated_in_Cargo_act <> i_original_qty_displayed_not_yet_misc Then
        MsgBox "Cargo activity is showing more rolls have been received since the 'Retrieve' button was pressed.  Please wait until scanners are not actively receiving paper before running this program."
        OraSession.Rollback
        Exit Sub
    End If
    
    
    grdData.MoveFirst
    
    If grdData.Columns(0).Value = "" Then
        MsgBox "No data on screen; save action cancelled"
        OraSession.Rollback
        Exit Sub
    End If
    
    Sqlstmt = " DELETE FROM RF_BNI_MISCBILLS WHERE RF_SERVICE_CODE='8' AND " _
            & " SERVICE_DATE= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "' ,'MM/DD/YYYY') " _
            & " AND COMMODITY_CODE IN ('1272', '1299') " _
            & " AND BILLING_FLAG IS NULL"
                
    Dim RecordCount As Integer
    RecordCount = OraDatabaseBNI.ExecuteSQL(Sqlstmt)

    Sqlstmt = " SELECT * FROM RF_BNI_MISCBILLS "
    Set dsRFBNI = OraDatabaseBNI.CreateDynaset(Sqlstmt, 0&)
    If OraDatabaseBNI.LastServerErr = 0 Then
        
        dsRFBNI.MoveLast
        With dsRFBNI
            For iRec = 0 To grdData.Rows - 1
                .AddNew
                .Fields("SERVICE_DATE").Value = grdData.Columns(0).Value
                
                iPos = InStr(1, Trim(grdData.Columns(1).Value), "-")
                If iPos > 0 Then
                    .Fields("CUSTOMER_ID").Value = SaveCust(Val(Trim(Mid(grdData.Columns(1).Value, 1, iPos - 1))), Val(grdData.Columns(11).Value))
                    .Fields("ASSET").Value = Val(Trim(Mid(grdData.Columns(1).Value, 1, iPos - 1)))
                Else
                    .Fields("CUSTOMER_ID").Value = SaveCust(Val(Trim(grdData.Columns(1).Value)), Val(grdData.Columns(11).Value))
                    .Fields("ASSET").Value = Val(Trim(grdData.Columns(1).Value))
                End If
            
                .Fields("LR_NUM").Value = Val(grdData.Columns(2).Value)
                .Fields("ORDER_NUM").Value = grdData.Columns(3).Value

                .Fields("COMMODITY_CODE").Value = Val(grdData.Columns(11).Value)

                .Fields("SERVICE_QTY").Value = Val(grdData.Columns(5).Value)
                .Fields("CASES").Value = Val(grdData.Columns(6).Value)
                .Fields("AVG_WT").Value = Val(grdData.Columns(7).Value)
                .Fields("WEIGHT").Value = Val(grdData.Columns(8).Value)
                .Fields("AMOUNT").Value = Val(grdData.Columns(9).Value)
                .Fields("SERVICE_CODE").Value = 6221
'                .Fields("DESCRIPTION").Value = strDescripOutput
                ' get Cargo Description for output
                If .Fields("COMMODITY_CODE").Value = 1272 Then
                    gsSqlStmt = "SELECT CARGO_DESCRIPTION THE_MARK FROM CARGO_TRACKING WHERE BOL = '" & grdData.Columns(2).Value & "'"
                Else
                    gsSqlStmt = "SELECT BOL || ' ' || BOOKING_NUM THE_MARK FROM BOOKING_ADDITIONAL_DATA WHERE ORDER_NUM = '" & grdData.Columns(2).Value & "'"
                End If
                Set dsSHORT_TERM_DATA = OraDatabaseRF.DbCreateDynaset(gsSqlStmt, 0&)
                .Fields("DESCRIPTION").Value = "REF: " & grdData.Columns(2).Value & "; MARK: " & dsSHORT_TERM_DATA.Fields("THE_MARK").Value & "; " & grdData.Columns(5).Value & " ROLL(S); " & Round(Val(grdData.Columns(8).Value) / 2000, 2) & " NT @ $" & Format(grdData.Columns(10).Value, "0.00") & " / NT"

                .Fields("RF_SERVICE_CODE").Value = 8
                
                'get location from CARGO_ACTIVITY
                'get asset from asset_profile based on the location code
                gsSqlStmt = " SELECT ACTIVITY_BILLED FROM CARGO_ACTIVITY WHERE ORDER_NUM='" & Trim(grdData.Columns(3).Value) & "' AND " _
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
                
                grdData.MoveNext
                
            Next iRec
        End With
            
       ''OraSession.CommitTrans
    End If
    
    ' Adam Walter, April 2009.  Exlcuding dole paper from mass-update, as it will have its own program (for now).
    Sqlstmt = " UPDATE CARGO_ACTIVITY SET TO_MISCBILL='Y' WHERE TO_MISCBILL IS NULL" _
            & " AND DATE_OF_ACTIVITY >= TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')" _
            & " AND DATE_OF_ACTIVITY < TO_DATE('" & Format(dtpDate, "MM/DD/YYYY") & "','MM/DD/YYYY')+1 " _
            & " AND SERVICE_CODE ='8' AND ACTIVITY_DESCRIPTION IS NULL " _
            & " AND CUSTOMER_ID IN (SELECT CUSTOMER_ID FROM DOLEPAPER_EDI_IMPORT_CUSTOMERS)"
    OraDatabaseRF.ExecuteSQL (Sqlstmt)

    If OraDatabaseRF.LastServerErr = 0 And OraDatabaseBNI.LastServerErr = 0 Then
       OraSession.CommitTrans
        MsgBox " Save successful !", vbInformation, "SAVE"
    Else
        OraSession.Rollback
        MsgBox " ORACLE ERROR ! " & vbCrLf & "Unable Save the records", vbCritical
        OraDatabaseRF.LastServerErrReset
        OraDatabaseBNI.LastServerErrReset
        Exit Sub
    End If
    
End Sub


Private Sub Form_Load()
    
    Me.Left = (Screen.Width - Me.Width) / 2
    Me.Top = (Screen.Height - Me.Height) / 2
    
'    intServiceCode = 6221
'    intCommCode = 1272
'    dblRate = 9.35
'    strDescripOutput = "DOLE PAPER INBOUND TRUCKLOADING CHARGE, $" & dblRate & " / 2000LBS"
    
    
    grdData.Visible = True
    dtpDate = Format(Now, "mm/dd/yyyy")
    Me.Show
        
End Sub
Private Sub grdData_AfterColUpdate(ByVal ColIndex As Integer)
   
   
    Select Case ColIndex
        Case 8
            If Trim(grdData.Columns(8).Value) = "" Then Exit Sub ' changed weight to blank, no go
            If Trim(grdData.Columns(10).Value) = "" Then Exit Sub ' no rate specified (may happen if an ALREADY MADE bill tries to be altered
            grdData.Columns(7).Value = grdData.Columns(8).Value / grdData.Columns(5).Value ' redo avg weight
            grdData.Columns(9).Value = Round(grdData.Columns(8).Value * grdData.Columns(10).Value / 2000, 2) ' recalc total based on weight
        Case 10
            If Trim(grdData.Columns(8).Value) = "" Then Exit Sub ' changed weight to blank, no go
            If Trim(grdData.Columns(10).Value) = "" Then Exit Sub ' no rate specified (may happen if an ALREADY MADE bill tries to be altered
            grdData.Columns(7).Value = grdData.Columns(8).Value / grdData.Columns(5).Value ' redo avg weight
            grdData.Columns(9).Value = Round(grdData.Columns(8).Value * grdData.Columns(10).Value / 2000, 2) ' recalc total based on weight
    End Select
                
End Sub
Private Function GetRate(Cust As Integer, Comm As Integer) As Double

    Sqlstmt = "SELECT RATE " _
                & " FROM LU_DOLEPAPER_TRUCKLOADING_RATE Where " _
                & " CUSTOMER_ID = '" & Cust & "' AND COMMODITY_CODE = '" & Comm & "'"
    Set dsSHORT_TERM_DATA = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
    If dsSHORT_TERM_DATA.RecordCount > 0 Then
        GetRate = Format(dsSHORT_TERM_DATA.Fields("RATE").Value, "0.00")
    Else
        GetRate = 0
    End If

'    Select Case Cust
'        Case 313
'            GetRate = 9.58
'        Case 314
'            GetRate = 9.58
'        Case 909
'            GetRate = 9.73
'        Case 916
'            GetRate = 9.58
'        Case 918
'            GetRate = 9.58
'        Case 919
'            GetRate = 9.58
'        Case 920
'            GetRate = 9.58
'        Case 921
'            GetRate = 9.58
'        Case 922
'            GetRate = 9.58
'        Case 1602
'            GetRate = 9.58
'        Case 1603
'            GetRate = 9.58
'        Case 1605
'            GetRate = 9.58
'        Case 1613
'            GetRate = 9.58
'        Case 1998
'            GetRate = 9.35
'        Case 1999
'            GetRate = 9.35
'        Case 2012
'            GetRate = 9.58
'        Case 2337
'            GetRate = 9.58
'        Case 7008
'            GetRate = 9.73
'        Case 7512
'            GetRate = 9.73
'        Case Else
'            GetRate = 0
'    End Select

End Function

Private Function SaveCust(Cust As Integer, Comm As Integer) As Integer
    
    Sqlstmt = "SELECT NEW_CUST " _
                & " FROM LU_MISCPAPER_CUSTOMER_CONV Where " _
                & " OWNER_ID = '" & Cust & "'"
    Set dsSHORT_TERM_DATA = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
    If dsSHORT_TERM_DATA.RecordCount > 0 Then
        SaveCust = dsSHORT_TERM_DATA.Fields("NEW_CUST").Value
    Else
        ' is this a dole paper customer who needs their invidual mill-bills sent all to someone else?
        Sqlstmt = "SELECT PAPER_BILL_TO_CUST FROM LU_DOLEPAPER_TRUCKLOADING_RATE " _
                    & " WHERE CUSTOMER_ID = '" & Cust & "' AND COMMODITY_CODE = '" & Comm & "'"
        Set dsSHORT_TERM_DATA = OraDatabaseRF.CreateDynaset(Sqlstmt, 0&)
        If dsSHORT_TERM_DATA.RecordCount > 0 Then
            SaveCust = dsSHORT_TERM_DATA.Fields("PAPER_BILL_TO_CUST").Value
        Else
            SaveCust = Cust
        End If
    End If

End Function

