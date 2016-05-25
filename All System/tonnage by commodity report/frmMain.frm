VERSION 5.00
Object = "{F9043C88-F6F2-101A-A3C9-08002B2F49FB}#1.2#0"; "COMDLG32.OCX"
Object = "{C932BA88-4374-101B-A56C-00AA003668DC}#1.1#0"; "MSMASK32.OCX"
Begin VB.Form frmMain 
   Caption         =   "Tonnage By Commodity"
   ClientHeight    =   4095
   ClientLeft      =   60
   ClientTop       =   345
   ClientWidth     =   6540
   LinkTopic       =   "Form1"
   ScaleHeight     =   4095
   ScaleWidth      =   6540
   StartUpPosition =   2  'CenterScreen
   Begin MSComDlg.CommonDialog TabSaveBox 
      Left            =   4800
      Top             =   3120
      _ExtentX        =   847
      _ExtentY        =   847
      _Version        =   393216
   End
   Begin VB.CheckBox chkSave 
      Height          =   255
      Left            =   720
      TabIndex        =   8
      Top             =   3240
      Width           =   255
   End
   Begin VB.Frame fraFrame 
      Height          =   1575
      Left            =   720
      TabIndex        =   3
      Top             =   600
      Width           =   5055
      Begin MSMask.MaskEdBox txtDate2 
         Height          =   285
         Left            =   2880
         TabIndex        =   6
         Top             =   720
         Width           =   1215
         _ExtentX        =   2143
         _ExtentY        =   503
         _Version        =   393216
         MaxLength       =   10
         Mask            =   "##/##/####"
         PromptChar      =   "_"
      End
      Begin MSMask.MaskEdBox txtDate1 
         Height          =   285
         Left            =   720
         TabIndex        =   5
         Top             =   720
         Width           =   1215
         _ExtentX        =   2143
         _ExtentY        =   503
         _Version        =   393216
         MaxLength       =   10
         Mask            =   "##/##/####"
         PromptChar      =   "_"
      End
      Begin VB.Label Label2 
         Caption         =   "To"
         Height          =   255
         Left            =   2520
         TabIndex        =   7
         Top             =   720
         Width           =   375
      End
      Begin VB.Label lblFromDate 
         Caption         =   "From :"
         Height          =   255
         Left            =   240
         TabIndex        =   4
         Top             =   720
         Width           =   495
      End
   End
   Begin VB.CommandButton cmdOk 
      Caption         =   "Print"
      Height          =   495
      Left            =   720
      TabIndex        =   1
      Top             =   2400
      Width           =   1575
   End
   Begin VB.CommandButton cmdQuit 
      Caption         =   "Quit"
      Height          =   495
      Left            =   4200
      TabIndex        =   0
      Top             =   2400
      Width           =   1575
   End
   Begin VB.Label Label1 
      Caption         =   "Create File With Printout"
      Height          =   375
      Left            =   1080
      TabIndex        =   9
      Top             =   3120
      Width           =   975
   End
   Begin VB.Label lblStatus 
      Height          =   255
      Left            =   360
      TabIndex        =   2
      Top             =   3720
      Width           =   5775
   End
End
Attribute VB_Name = "frmMain"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim iLine As Long
Dim iPage As Integer

Private Sub cmdOk_Click()

    Dim subTotal As Double
    Dim subTotal1 As Double
    Dim subTotal2 As Double
    Dim subTotal3 As Double
    Dim grandTotal As Double
    Dim grandTotal1 As Double
    Dim grandTotal2 As Double
    Dim grandTotal3 As Double
    Dim wt As Double
    Dim i As Integer
    Dim j As Integer
    Dim incr As Integer
    Dim iRec As Integer

    Dim iAmount As Double
    Dim iDesc As String
    Dim sComm As String
    Dim bFirst As Boolean
    Dim bFirst2 As Boolean
    Dim gsSqlStmt As String
    
    Dim sOutput1 As String
    Dim sOutput2 As String
    Dim sOutput1MEAS As String
    Dim sOutput2MEAS As String
    
    Dim WriteFile As Boolean
    
    Dim DataForWriteFile As String
    
    subTotal = 0
    subTotal1 = 0
    subTotal2 = 0
'    subTotal3 = 0
    grandTotal = 0
    grandTotal1 = 0
    grandTotal2 = 0
'    grandTotal3 = 0
    iLine = 8
    iPage = 1
    bFirst = False
    bFirst2 = True
    
    ' Before processing lets make sure that the date is valid
    If Not IsDate(Me.txtDate1.Text) Then
       MsgBox ("You Must Enter a Valid Starting Date!")
       Me.txtDate1.SetFocus
       Exit Sub
    End If
    
    If Not IsDate(Me.txtDate2.Text) Then
       MsgBox ("You Must Enter a Valid Ending Date!")
       Me.txtDate2.SetFocus
       Exit Sub
    End If
    
    TabSaveBox.FileName = ""
    If chkSave.Value = 1 Then
        TabSaveBox.Filter = "XLS files (*.xls)|*.xls|All files (*.*)|*.*"
        TabSaveBox.InitDir = "C:\"
        TabSaveBox.ShowSave
        If TabSaveBox.FileName = "" Then
            ' User canceled.
            Exit Sub
        Else
            Open TabSaveBox.FileName For Output As #1
        End If
        
        WriteFile = True
    Else
        WriteFile = False
    End If
    
    
    Dim rsDetail As Object
    Dim rsDetail1 As Object
    Dim rsDetail2 As Object
    Dim rsDetail3 As Object
    Dim rsDetail4 As Object
    Dim rsDetail5 As Object
    Dim rsDet As Object
    Dim rsDet1 As Object
    Dim rsDet2 As Object
    Dim rsDet3 As Object
    Dim rsDet4 As Object
    Dim rsDet5 As Object
        
    DataForWriteFile = "<table>"
    
    Printer.Orientation = 1
    Printer.FontBold = False
    Printer.FontSize = 8
    Printer.Print Tab(3); "Printed On : " & Format(Now, "MM/DD/YYYY"); Tab(110); "Page No. : " & iPage
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=3>Printed On : " & Format(Now, "MM/DD/YYYY") & "</td><td colspan=3>&nbsp;</td></tr>"
    Printer.Print
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>&nbsp;</td></tr>"
    Printer.FontSize = 12
    
    Printer.Print Tab(35); "Tonnage By Commodity:  From " & Me.txtDate1 & "  To " & Me.txtDate2
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>Tonnage By Commodity:  From " & Me.txtDate1 & "  To " & Me.txtDate2 & "</td></tr>"
    
    Printer.FontSize = 8
    Printer.Print ""
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>&nbsp;</td></tr>"
    Printer.Print ""
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>&nbsp;</td></tr>"
    Printer.Print ""
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>&nbsp;</td></tr>"

    Printer.Print Tab(10); "COMMODITY NAME"; Tab(70); "WEIGHT (IN TONS)"; Tab(100); "COUNT1"; Tab(135); "COUNT2"
            DataForWriteFile = DataForWriteFile & "<tr><td>COMMODITY NAME</td><td>WEIGHT (IN TONS)</td><td>COUNT1</td><td>&nbsp;</td><td>COUNT2</td><td>&nbsp;</td></tr>"
    'Printer.Print Tab(10); "COMMODITY NAME"; Tab(70); "WEIGHT (IN TONS)"; Tab(100); "COUNT1"
                  
    Printer.FontBold = True
    Printer.Print Tab(2); "----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>-----------------------------------------------------------------------------------------------------------------------------</td></tr>"
    Printer.FontBold = False
    Printer.Print ""
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>&nbsp;</td></tr>"
    
    i = 0
    j = 0
    
    Me.cmdOk.Enabled = False
    
    MB  ' Mouse Busy
    
    Do While j < 9500
    
        If (j < 5000) Then
            incr = 1000
        ElseIf (j < 6000) Then
            incr = 100
        ElseIf (j < 7000) Then
            incr = 1000
        ElseIf (j < 8000) Then
            incr = 100
        Else
            incr = 500
        End If
        
        i = j
        j = i + incr

'                    " (COMMODITY_CODE != '1272' OR RECIPIENT_ID = '312') AND"
        gsSqlStmt = " SELECT COMMODITY_CODE, sum(CARGO_WEIGHT)/2000 CW, sum(QTY_EXPECTED) QTY1, sum(QTY2_EXPECTED) QTY2, QTY1_UNIT FROM CARGO_MANIFEST WHERE" & _
                    " COMMODITY_CODE >= '" & i & "' AND COMMODITY_CODE < '" & j & "' AND" & _
                    " CONTAINER_NUM IN ( SELECT CONTAINER_NUM FROM VOYAGE_CARGO WHERE LR_NUM IN" & _
                    " (SELECT LR_NUM FROM VOYAGE WHERE DATE_DEPARTED >= TO_DATE('" & Format(Me.txtDate1.Text, "dd-mmm-yyyy") & " 01:00:00', 'dd-mon-yyyy hh24:mi:ss')" & _
                    " AND DATE_DEPARTED <= TO_DATE('" & Format(Me.txtDate2.Text, "dd-mmm-yyyy") & " 23:59:59', 'dd-mon-yyyy hh24:mi:ss')))" & _
                    " GROUP BY COMMODITY_CODE, QTY1_UNIT order by commodity_code"
        'LFW, 12/16/02      " AND CARGO_MARK NOT LIKE '%*%' GROUP BY COMMODITY_CODE order by commodity_code"
        Set rsDetail = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
        
        ' Lets start at the top of the file again and create the export file
        If OraDatabase.LastServerErr = 0 And rsDetail.RecordCount > 0 Then

            rsDetail.MoveFirst
            With rsDetail
                For iRec = 1 To .RecordCount
                    gsSqlStmt = " SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE " & _
                                " COMMODITY_CODE='" & .Fields("COMMODITY_CODE").Value & "'"
                    
                    Set rsDet = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
                    If OraDatabase.LastServerErr = 0 And rsDet.RecordCount > 0 Then
                        bFirst = True
                        
                        'Use this formula to calculate net ton for 4963-Futter Lumber
                        '# of GBF = # of MBF * 1000; Wt(in LBs) = # of GBF * 3; Wt(in Net Tons) = Wt(in LBs) / 2000
                        sComm = .Fields("COMMODITY_CODE").Value
                        If sComm = "4963" Then
                            wt = .Fields("CW").Value * 1000 * 3
                        Else
                            wt = .Fields("CW").Value
                        End If
                        wt = Round(wt, 0)
                        
                        If .Fields("COMMODITY_CODE").Value <> "9100" Then
                            subTotal = subTotal + wt
                            grandTotal = grandTotal + wt
                        End If
                        
      '                  If .Fields("COMMODITY_CODE").Value <> "1223" And .Fields("COMMODITY_CODE").Value <> "9210" Then
                            subTotal1 = subTotal1 + .Fields("QTY1").Value
                            grandTotal1 = grandTotal1 + .Fields("QTY1").Value
                           
                            If Not IsNull(.Fields("QTY2").Value) Then
                                subTotal2 = subTotal2 + .Fields("QTY2").Value
                                grandTotal2 = grandTotal2 + .Fields("QTY2").Value
                            End If
                            
      '                  End If
                        
                        If Not IsNull(.Fields("QTY1").Value) And Format(.Fields("QTY1").Value, "##,##0") <> "0" Then
                            sOutput1 = Format(.Fields("QTY1").Value, "##,##0")
                            sOutput1MEAS = .Fields("QTY1_UNIT").Value
                        Else
                            sOutput1 = ""
                            sOutput1MEAS = ""
                        End If
                        If Not IsNull(.Fields("QTY2").Value) And Format(.Fields("QTY2").Value, "##,##0") <> "0" Then
                            sOutput2 = Format(.Fields("QTY2").Value, "##,##0")
'                            sOutput2 = .Fields("QTY2").Value
'                            sOutput2MEAS = .Fields("QTY2_UNIT").Value
                        Else
                            sOutput2 = ""
'                            sOutput2MEAS = ""
                        End If
                        
                        If iLine = 73 Then
                            Printer.Print ""
                            Printer.Print Tab(30); "This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,"
                                    DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,</td></tr>"
                            Printer.Print Tab(30); " this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately."
                                    DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately.</td></tr>"
                            Call PageHeader
                        End If
                        
                        If wt <> 0 Then
                            iLine = iLine + 1
    '                        If .Fields("COMMODITY_CODE").Value <> "1223" And .Fields("COMMODITY_CODE").Value <> "9100" And .Fields("COMMODITY_CODE").Value <> "9210" Then
    '                            Printer.Print Tab(10); rsDet.Fields("COMMODITY_NAME").Value; Tab(70); wt; Tab(100); sOutput1; Tab(113); sOutput1MEAS; Tab(135); sOutput2 '; Tab(148); sOutput2MEAS
                            If .Fields("COMMODITY_CODE").Value = "9100" Then
                                Printer.Print Tab(10); rsDet.Fields("COMMODITY_NAME").Value; Tab(100); Format(sOutput1, "##,##0"); Tab(113); sOutput1MEAS; Tab(135); sOutput2 '; Tab(148); sOutput2MEAS
                                        DataForWriteFile = DataForWriteFile & "<tr><td>" & rsDet.Fields("COMMODITY_NAME").Value & "</td><td>&nbsp;</td><td>" & Format(sOutput1, "##,##0") & "</td><td>" & sOutput1MEAS & "</td><td>" & sOutput2 & "</td><td>&nbsp;</td></tr>"
                            Else
                                'Printer.Print Tab(10); rsDet.Fields("COMMODITY_NAME").Value; Tab(70); wt
                                Printer.Print Tab(10); rsDet.Fields("COMMODITY_NAME").Value; Tab(70); Format(wt, "##,##0"); Tab(100); Format(sOutput1, "##,##0"); Tab(113); sOutput1MEAS; Tab(135); sOutput2
                                        DataForWriteFile = DataForWriteFile & "<tr><td>" & rsDet.Fields("COMMODITY_NAME").Value & "</td><td>" & Format(wt, "##,##0") & "</td><td>" & Format(sOutput1, "##,##0") & "</td><td>" & sOutput1MEAS & "</td><td>" & sOutput2 & "</td><td>&nbsp;</td></tr>"
                            End If
                        End If
                    Else
                        MsgBox OraDatabase.LastServerErrText, vbCritical
                        Exit Sub
                    End If
                    .MoveNext
                Next iRec
            End With
        Else
            If OraDatabase.LastServerErr <> 0 Then
                MsgBox OraDatabase.LastServerErrText, vbCritical
                OraDatabase.LastServerErrReset
                Exit Sub
            End If
        End If
        
        If bFirst = True Then
            If iLine = 75 Then Call PageHeader
            iLine = iLine + 1
            Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                   DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>-----------------------------------------------------------------------------------------------------------------------------</td></tr>"
        
            If iLine = 75 Then Call PageHeader
            iLine = iLine + 1
            Printer.FontBold = True
            Printer.Print Tab(5); " Sub Total "; Tab(66); Format(subTotal, "##,##0"); Tab(94); Format(subTotal1, "##,##0") '; Tab(126); Format(subTotal2, "##,##0")
                    DataForWriteFile = DataForWriteFile & "<tr><td>Sub Total</td><td>" & Format(subTotal, "##,##0") & "</td><td>" & Format(subTotal1, "##,##0") & "</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>"
            subTotal = 0
            subTotal1 = 0
            subTotal2 = 0
            Printer.FontBold = False
            If iLine = 75 Then Call PageHeader
            iLine = iLine + 1
            Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                    DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>-----------------------------------------------------------------------------------------------------------------------------</td></tr>"
            bFirst = False
        End If
    Loop
    
    ' Pawan Updated on 11/19/2001 for vessel = -1........
    If iLine = 75 Then Call PageHeader
    iLine = iLine + 1
    Printer.FontBold = True
    'Printer.Print Tab(10); "FOR THE VESSEL UNKNOWN"
    Printer.Print Tab(5); "VIA THE GATE & RAIL"
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>VIA THE GATE & RAIL</td></tr>"
    Printer.FontBold = False
'    gsSqlStmt = " SELECT CM.COMMODITY_CODE cd, SUM(CM.CARGO_WEIGHT)/2000 CW,SUM(CM.QTY_EXPECTED) QTY1, SUM(CM.QTY2_EXPECTED) QTY2 FROM CARGO_MANIFEST cm, cargo_tracking ct WHERE" & _
'                    " lr_num in ('-1', '3', '2') and DATE_RECEIVED >= TO_DATE('" & Format(Me.txtDate1.Text, "dd-mmm-yyyy") & " 01:00:00', 'dd-mon-yyyy HH24:MI:SS')" & _
'                    " AND DATE_RECEIVED <= TO_DATE('" & Format(Me.txtDate2.Text, "dd-mmm-yyyy") & " 23:59:59', 'dd-mon-yyyy HH24:MI:SS')" & _
'                    " AND  CT.LOT_NUM=CM.CONTAINER_NUM GROUP BY CM.COMMODITY_CODE  ORDER BY CM.COMMODITY_CODE"
'LFW, 12/16/02      " AND  CT.LOT_NUM=CM.CONTAINER_NUM AND CM.CARGO_MARK NOT LIKE '%*%' GROUP BY CM.COMMODITY_CODE  ORDER BY CM.COMMODITY_CODE"
        
        
    gsSqlStmt = " SELECT CM.COMMODITY_CODE cd, SUM(CM.CARGO_WEIGHT)/2000 CW,SUM(CM.QTY_EXPECTED) QTY1, QTY1_UNIT, "
    gsSqlStmt = gsSqlStmt & "SUM(CM.QTY2_EXPECTED) QTY2 FROM CARGO_MANIFEST cm, cargo_tracking ct WHERE "
    gsSqlStmt = gsSqlStmt & "lr_num in ('-1', '3', '2', '4', '8') and "     ' -1 is Gate, 3 is Rail, 2 is Chiquita export paper (why not include 4 commercial booking paper?) (now adding 8, "over the road steel".  seriously, stop making up numbers ;p)
    gsSqlStmt = gsSqlStmt & "DATE_RECEIVED >= TO_DATE('" & Format(Me.txtDate1.Text, "dd-mmm-yyyy") & " 00:00:01', 'dd-mon-yyyy HH24:MI:SS') AND "
    gsSqlStmt = gsSqlStmt & "DATE_RECEIVED <= TO_DATE('" & Format(Me.txtDate2.Text, "dd-mmm-yyyy") & " 23:59:59', 'dd-mon-yyyy HH24:MI:SS') AND "
    gsSqlStmt = gsSqlStmt & "CT.LOT_NUM=CM.CONTAINER_NUM GROUP BY CM.COMMODITY_CODE, QTY1_UNIT  ORDER BY CM.COMMODITY_CODE"
    
    Set rsDetail1 = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)     '
        
    If OraDatabase.LastServerErr = 0 And rsDetail1.RecordCount > 0 Then
        'rsDetail.MoveFirst
        rsDetail1.MoveFirst
        With rsDetail1
            For iRec = 1 To .RecordCount
                gsSqlStmt = " SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE " & _
                            " COMMODITY_CODE='" & .Fields("cd").Value & "'"
                Set rsDet1 = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And rsDet1.RecordCount > 0 Then
                    bFirst = True
                    
                    sComm = .Fields("cd").Value
                    
                    'Use this formula to calculate net ton for 4963-Futter Lumber
                    '# of GBF = # of MBF * 1000; Wt(in LBs) = # of GBF * 3; Wt(in Net Tons) = Wt(in LBs) / 2000
                    If sComm = "4963" Then
                        wt = .Fields("CW").Value * 1000 * 3
                    Else
                        wt = .Fields("CW").Value
                    End If
                    wt = Round(wt, 0)
                    
                    subTotal = subTotal + wt
                    subTotal1 = subTotal1 + .Fields("QTY1").Value
                        
                    If Not IsNull(.Fields("QTY2").Value) Then
                        subTotal2 = subTotal2 + .Fields("QTY2").Value
                        grandTotal2 = grandTotal2 + .Fields("QTY2").Value
                    End If
                        
                    If Not IsNull(.Fields("QTY1").Value) And Format(.Fields("QTY1").Value, "##,##0") <> "0" Then
                        sOutput1 = Format(.Fields("QTY1").Value, "##,##0")
                        sOutput1MEAS = .Fields("QTY1_UNIT").Value
                    Else
                        sOutput1 = ""
                        sOutput1MEAS = ""
                    End If
                    If Not IsNull(.Fields("QTY2").Value) And Format(.Fields("QTY2").Value, "##,##0") <> "0" Then
                        sOutput2 = Format(.Fields("QTY2").Value, "##,##0")
'                        sOutput2MEAS = .Fields("QTY2_UNIT").Value
                    Else
                        sOutput2 = ""
'                        sOutput2MEAS = ""
                    End If
                    
                    If (.Fields("cd").Value = "1299" Or .Fields("cd").Value = "1272") Then
                        ' add nothing
                    Else
                        grandTotal = grandTotal + wt
                        grandTotal1 = grandTotal1 + .Fields("QTY1").Value
                    End If
                                       
                    If iLine = 73 Then
                        Printer.Print ""
                        Printer.Print Tab(30); "This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,"
                                DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,</td></tr>"
                        Printer.Print Tab(30); " this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately."
                                DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately.</td></tr>"
                        Call PageHeader
                    End If
                    
                    iLine = iLine + 1
                    If (.Fields("cd").Value = "1299" Or .Fields("cd").Value = "1272") Then
                        Printer.Print Tab(10); rsDet1.Fields("COMMODITY_NAME").Value & " *"; Tab(70); Format(wt, "##,##0"); Tab(100); Format(sOutput1, "##,##0"); Tab(113); sOutput1MEAS; Tab(135); sOutput2 '; Tab(148); sOutput2MEAS
                                DataForWriteFile = DataForWriteFile & "<tr><td>" & rsDet1.Fields("COMMODITY_NAME").Value & " *</td><td>" & Format(wt, "##,##0") & "</td><td>" & Format(sOutput1, "##,##0") & "</td><td>" & sOutput1MEAS & "</td><td>" & sOutput2 & "</td><td>&nbsp;</td></tr>"
                    Else
                        Printer.Print Tab(10); rsDet1.Fields("COMMODITY_NAME").Value; Tab(70); Format(wt, "##,##0"); Tab(100); Format(sOutput1, "##,##0"); Tab(113); sOutput1MEAS; Tab(135); sOutput2 '; Tab(148); sOutput2MEAS
                                DataForWriteFile = DataForWriteFile & "<tr><td>" & rsDet1.Fields("COMMODITY_NAME").Value & "</td><td>" & Format(wt, "##,##0") & "</td><td>" & Format(sOutput1, "##,##0") & "</td><td>" & sOutput1MEAS & "</td><td>" & sOutput2 & "</td><td>&nbsp;</td></tr>"
                    End If
                Else
                    MsgBox OraDatabase.LastServerErrText, vbCritical
                    Exit Sub
                End If
                .MoveNext
            Next iRec
        End With
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErrText, vbCritical
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
    End If
    
    '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~2983 begin~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
'    Printer.FontBold = True
'    Printer.Print
'    Printer.Print Tab(5); "OTHER VIA THE GATE "
'    Printer.FontBold = False
    
    gsSqlStmt = "SELECT COMMODITY_CODE, SUM(WEIGHT/2000) CW, SUM(SERVICE_QTY) QTY1, SUM(CASES) QTY2 FROM RF_BNI_MISCBILLS "
    gsSqlStmt = gsSqlStmt & "WHERE SERVICE_CODE Between '6220' AND '6229' "
'    gsSqlStmt = gsSqlStmt & "AND SERVICE_DATE >= TO_DATE('07/1/2006', 'MM/DD/YYYY') "
'    gsSqlStmt = gsSqlStmt & "AND SERVICE_DATE <= TO_DATE('5/31/2007', 'MM/DD/YYYY') "
    
    gsSqlStmt = gsSqlStmt & "AND SERVICE_DATE >= TO_DATE('" & Format(Me.txtDate1.Text, "dd-mmm-yyyy") & "', 'dd-mon-yyyy') "
    gsSqlStmt = gsSqlStmt & "AND SERVICE_DATE <= TO_DATE('" & Format(Me.txtDate2.Text, "dd-mmm-yyyy") & " 23:59:59', 'dd-mon-yyyy HH24:MI:SS') "

    gsSqlStmt = gsSqlStmt & "AND BILLING_FLAG = 'Y'"
    gsSqlStmt = gsSqlStmt & "AND COMMODITY_CODE != '5315'"
    gsSqlStmt = gsSqlStmt & "AND DESCRIPTION Like 'INBOUND TRUCKLOADING IN/OUT%'"
    gsSqlStmt = gsSqlStmt & "GROUP BY COMMODITY_CODE ORDER BY COMMODITY_CODE"

    Set rsDetail2 = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)     '
        
    If OraDatabase.LastServerErr = 0 And rsDetail2.RecordCount > 0 Then
        rsDetail2.MoveFirst
        With rsDetail2
            For iRec = 1 To .RecordCount
                gsSqlStmt = " SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE " & _
                            " COMMODITY_CODE='" & .Fields("COMMODITY_CODE").Value & "'"
                Set rsDet2 = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
                
                If OraDatabase.LastServerErr = 0 And rsDet2.RecordCount > 0 Then
                    bFirst = True
                    
                    sComm = .Fields("COMMODITY_CODE").Value
                    
                    wt = .Fields("CW").Value
                    wt = Round(wt, 0)
                    subTotal = subTotal + wt
                    
                    subTotal1 = subTotal1 + .Fields("QTY1").Value
                        
                    If Not IsNull(.Fields("QTY2").Value) Then
                        subTotal2 = subTotal2 + .Fields("QTY2").Value
                        grandTotal2 = grandTotal2 + .Fields("QTY2").Value
                    End If
                        
'                    If Not IsNull(.Fields("QTY1").Value) And Format(.Fields("QTY1").Value, "##,##0") <> "0" Then
'                        sOutput1 = Format(.Fields("QTY1").Value, "##,##0")
'                        sOutput1MEAS = .Fields("QTY1_UNIT").Value
'                    Else
'                        sOutput1 = ""
'                        sOutput1MEAS = ""
'                    End If
'                    If Not IsNull(.Fields("QTY2").Value) And Format(.Fields("QTY2").Value, "##,##0") <> "0" Then
'                        sOutput2 = Format(.Fields("QTY2").Value, "##,##0")
'                        sOutput2MEAS = .Fields("QTY2_UNIT").Value
'                    Else
'                        sOutput2 = ""
'                        sOutput2MEAS = ""
'                    End If
                    
                    grandTotal = grandTotal + wt
                    grandTotal1 = grandTotal1 + .Fields("QTY1").Value
                                       
                    If iLine = 73 Then
                        Printer.Print ""
                        Printer.Print Tab(30); "This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,"
                                DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,</td></tr>"
                        Printer.Print Tab(30); " this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately."
                                DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately.</td></tr>"
                        Call PageHeader
                    End If
                    
                    iLine = iLine + 1
                    Printer.Print Tab(10); rsDet2.Fields("COMMODITY_NAME").Value; Tab(70); Format(wt, "##,##0"); Tab(100); Format(.Fields("QTY1").Value, "##,##0"); Tab(113); "PLT"; Tab(135); Format(.Fields("QTY2").Value, "##,##0") & " cases"
                                DataForWriteFile = DataForWriteFile & "<tr><td>" & rsDet2.Fields("COMMODITY_NAME").Value & "</td><td>" & Format(wt, "##,##0") & "</td><td>" & Format(.Fields("QTY1").Value, "##,##0") & "</td><td>" & "PLT" & "</td><td>" & Format(.Fields("QTY2").Value, "##,##0") & "</td><td>cases</td></tr>"
                Else
                    MsgBox OraDatabase.LastServerErrText, vbCritical
                    Exit Sub
                End If
                .MoveNext
            Next iRec
        End With
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErrText, vbCritical
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
    End If
    '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~2983 end~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    
    ' HD 8527.
    ' Up until now, "trucked in cargo from RF" was defined as any cargo that received a Trucked In RF-to-BNI invoce.
    ' Now, however, we have an RF trucked-in cargo whose bills get deleted instead of sent.  OYE.
    ' So, for commodity 5315 ONLY (hence the excusion above), we also have to look at RF.
    gsSqlStmt = "SELECT COMMODITY_CODE, COUNT(*)/1.05 CW, COUNT(*) QTY1, SUM(QTY_RECEIVED) QTY2 FROM CARGO_TRACKING_ALL CT, CARGO_ACTIVITY_ALL CA "
    gsSqlStmt = gsSqlStmt & "WHERE SERVICE_CODE = '8' "
    gsSqlStmt = gsSqlStmt & "AND DATE_OF_ACTIVITY >= TO_DATE('" & Format(Me.txtDate1.Text, "dd-mmm-yyyy") & "', 'dd-mon-yyyy') "
    gsSqlStmt = gsSqlStmt & "AND DATE_OF_ACTIVITY <= TO_DATE('" & Format(Me.txtDate2.Text, "dd-mmm-yyyy") & " 23:59:59', 'dd-mon-yyyy HH24:MI:SS') "
    gsSqlStmt = gsSqlStmt & "AND CT.ARRIVAL_NUM = CA.ARRIVAL_NUM "
    gsSqlStmt = gsSqlStmt & "AND CT.PALLET_ID = CA.PALLET_ID "
    gsSqlStmt = gsSqlStmt & "AND CT.RECEIVER_ID = CA.CUSTOMER_ID "
    gsSqlStmt = gsSqlStmt & "AND COMMODITY_CODE = '5315'"
    gsSqlStmt = gsSqlStmt & "GROUP BY COMMODITY_CODE ORDER BY COMMODITY_CODE"
    Set rsDetail5 = OraDatabaseRF.DBCreateDynaset(gsSqlStmt, 0&)     '
        
    If OraDatabase.LastServerErr = 0 And rsDetail5.RecordCount > 0 Then
        rsDetail5.MoveFirst
        With rsDetail5
            For iRec = 1 To .RecordCount
                gsSqlStmt = " SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE " & _
                            " COMMODITY_CODE='" & .Fields("COMMODITY_CODE").Value & "'"
                Set rsDet5 = OraDatabase.DBCreateDynaset(gsSqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And rsDet5.RecordCount > 0 Then
                    bFirst = True
                    
                    sComm = .Fields("COMMODITY_CODE").Value
                    
                    wt = .Fields("CW").Value
                    wt = Round(wt, 0)
                    subTotal = subTotal + wt
                    
                    subTotal1 = subTotal1 + .Fields("QTY1").Value
                        
                    If Not IsNull(.Fields("QTY2").Value) Then
                        subTotal2 = subTotal2 + .Fields("QTY2").Value
                        grandTotal2 = grandTotal2 + .Fields("QTY2").Value
                    End If
                    
                    grandTotal = grandTotal + wt
                    grandTotal1 = grandTotal1 + .Fields("QTY1").Value
                                       
                    If iLine = 73 Then
                        Printer.Print ""
                        Printer.Print Tab(30); "This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,"
                                DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,</td></tr>"
                        Printer.Print Tab(30); " this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately."
                                DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately.</td></tr>"
                        Call PageHeader
                    End If
                    
                    iLine = iLine + 1
                    Printer.Print Tab(10); rsDet5.Fields("COMMODITY_NAME").Value; Tab(70); Format(wt, "##,##0"); Tab(100); Format(.Fields("QTY1").Value, "##,##0"); Tab(113); "PLT"; Tab(135); Format(.Fields("QTY2").Value, "##,##0") & " cases"
                                DataForWriteFile = DataForWriteFile & "<tr><td>" & rsDet5.Fields("COMMODITY_NAME").Value & "</td><td>" & Format(wt, "##,##0") & "</td><td>" & Format(.Fields("QTY1").Value, "##,##0") & "</td><td>" & "PLT" & "</td><td>" & Format(.Fields("QTY2").Value, "##,##0") & "</td><td>cases</td></tr>"
                Else
                    MsgBox OraDatabase.LastServerErrText, vbCritical
                    Exit Sub
                End If
                .MoveNext
            Next iRec
        End With
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErrText, vbCritical
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
    End If
                        
    
    
    '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~customer 440 start~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    ' Added Adam Walter, May 2008.  Grabs domestic clementine values from RF
    
    gsSqlStmt = "SELECT CT.COMMODITY_CODE, SUM(WEIGHT * 2.2 * QTY_RECEIVED / 2000) TONS, COUNT(*) PALLETS, SUM(QTY_RECEIVED) CARTONS " & _
                "FROM CARGO_TRACKING CT, COMMODITY_PROFILE CP WHERE DATE_RECEIVED >= TO_DATE('" & Format(Me.txtDate1.Text, "dd-mmm-yyyy") & " 00:00:01', 'dd-mon-yyyy HH24:MI:SS') AND " & _
                "DATE_RECEIVED <= TO_DATE('" & Format(Me.txtDate2.Text, "dd-mmm-yyyy") & " 23:59:59', 'dd-mon-yyyy HH24:MI:SS') AND " & _
                "RECEIVING_TYPE = 'T' AND CT.COMMODITY_CODE = CP.COMMODITY_CODE AND " & _
                "COMMODITY_TYPE = 'CLEMENTINES' GROUP BY CT.COMMODITY_CODE ORDER BY CT.COMMODITY_CODE"
    Set rsDetail3 = OraDatabaseRF.DBCreateDynaset(gsSqlStmt, 0&)
        
    If OraDatabase.LastServerErr = 0 And rsDetail3.RecordCount > 0 Then
        rsDetail3.MoveFirst
        With rsDetail3
            For iRec = 1 To .RecordCount
                gsSqlStmt = " SELECT COMMODITY_CODE || '-' || COMMODITY_NAME THE_COMM FROM COMMODITY_PROFILE WHERE " & _
                            " COMMODITY_CODE='" & .Fields("COMMODITY_CODE").Value & "'"
                Set rsDet3 = OraDatabaseRF.DBCreateDynaset(gsSqlStmt, 0&)
                
                If OraDatabase.LastServerErr = 0 And rsDet3.RecordCount > 0 Then
                    bFirst = True
                    
                    sComm = .Fields("COMMODITY_CODE").Value
                    
                    wt = .Fields("TONS").Value
                    wt = Round(wt, 0)
                    subTotal = subTotal + wt
                    
                    subTotal1 = subTotal1 + .Fields("PALLETS").Value
                        
                    If Not IsNull(.Fields("CARTONS").Value) Then
                        subTotal2 = subTotal2 + .Fields("CARTONS").Value
                        grandTotal2 = grandTotal2 + .Fields("CARTONS").Value
                    End If
                        
'                    If Not IsNull(.Fields("QTY1").Value) And Format(.Fields("QTY1").Value, "##,##0") <> "0" Then
'                        sOutput1 = Format(.Fields("QTY1").Value, "##,##0")
'                        sOutput1MEAS = .Fields("QTY1_UNIT").Value
'                    Else
'                        sOutput1 = ""
'                        sOutput1MEAS = ""
'                    End If
                    If Not IsNull(.Fields("QTY2").Value) And Format(.Fields("QTY2").Value, "##,##0") <> "0" Then
                        sOutput2 = Format(.Fields("QTY2").Value, "##,##0")
'                        sOutput2MEAS = .Fields("QTY2_UNIT").Value
                    Else
                        sOutput2 = ""
'                        sOutput2MEAS = ""
                    End If
'
                    grandTotal = grandTotal + wt
                    grandTotal1 = grandTotal1 + .Fields("PALLETS").Value
                                       
                    If iLine = 73 Then
                        Printer.Print ""
                        Printer.Print Tab(30); "This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,"
                                DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,</td></tr>"
                        Printer.Print Tab(30); " this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately."
                                DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately.</td></tr>"
                        Call PageHeader
                    End If
                    
                    iLine = iLine + 1
                    Printer.Print Tab(10); rsDet3.Fields("THE_COMM").Value; Tab(70); Format(wt, "##,##0"); Tab(100); Format(.Fields("PALLETS").Value, "##,##0"); Tab(113); "PLT"; Tab(135); Format(.Fields("CARTONS").Value, "##,##0"); Tab(148); " cases"
                                DataForWriteFile = DataForWriteFile & "<tr><td>" & rsDet3.Fields("THE_COMM").Value & "</td><td>" & Format(wt, "##,##0") & "</td><td>" & Format(.Fields("PALLETS").Value, "##,##0") & "</td><td>" & "PLT" & "</td><td>" & Format(.Fields("CARTONS").Value, "##,##0") & "</td><td>cases</td></tr>"
                Else
                    MsgBox OraDatabase.LastServerErrText, vbCritical
                    Exit Sub
                End If
                .MoveNext
            Next iRec
        End With
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErrText, vbCritical
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
    End If
    
    
    '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~DOLEPAPER start~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    ' Added Adam Walter, May 2009.  Grabs Dole dock ticket paper from RF
    
    gsSqlStmt = "SELECT SUM(WEIGHT / 2000) TONS, COUNT(*) PALLETS " & _
                "FROM CARGO_TRACKING WHERE DATE_RECEIVED >= TO_DATE('" & Format(Me.txtDate1.Text, "dd-mmm-yyyy") & " 00:00:01', 'dd-mon-yyyy HH24:MI:SS') AND " & _
                "DATE_RECEIVED <= TO_DATE('" & Format(Me.txtDate2.Text, "dd-mmm-yyyy") & " 23:59:59', 'dd-mon-yyyy HH24:MI:SS') AND " & _
                "COMMODITY_CODE = '1272'"
'                "RECEIVING_TYPE = 'T' AND "

    Set rsDetail4 = OraDatabaseRF.DBCreateDynaset(gsSqlStmt, 0&)
        
    If OraDatabase.LastServerErr = 0 And rsDetail4.RecordCount > 0 And rsDetail4.Fields("PALLETS").Value > 0 Then
        rsDetail4.MoveFirst
        With rsDetail4
            gsSqlStmt = " SELECT COMMODITY_CODE || '-' || COMMODITY_NAME THE_COMM FROM COMMODITY_PROFILE WHERE " & _
                        " COMMODITY_CODE='1272'"
            Set rsDet4 = OraDatabaseRF.DBCreateDynaset(gsSqlStmt, 0&)
            
            If OraDatabase.LastServerErr = 0 And rsDet4.RecordCount > 0 Then
                bFirst = True
                
'                sComm = .Fields("COMMODITY_CODE").Value
                
                wt = .Fields("TONS").Value
                wt = Round(wt, 0)
                subTotal = subTotal + wt
                
                subTotal1 = subTotal1 + .Fields("PALLETS").Value
                    
'                If Not IsNull(.Fields("CARTONS").Value) Then
'                    subTotal2 = subTotal2 + .Fields("CARTONS").Value
'                    grandTotal2 = grandTotal2 + .Fields("CARTONS").Value
'                End If
                    
'                If Not IsNull(.Fields("QTY1").Value) And Format(.Fields("QTY1").Value, "##,##0") <> "0" Then
'                    sOutput1 = Format(.Fields("QTY1").Value, "##,##0")
'                    sOutput1MEAS = .Fields("QTY1_UNIT").Value
'                Else
'                    sOutput1 = ""
'                    sOutput1MEAS = ""
'                End If
'                If Not IsNull(.Fields("QTY2").Value) And Format(.Fields("QTY2").Value, "##,##0") <> "0" Then
'                    sOutput2 = Format(.Fields("QTY2").Value, "##,##0")
'                    sOutput2MEAS = .Fields("QTY2_UNIT").Value
'                Else
'                    sOutput2 = ""
'                    sOutput2MEAS = ""
'                End If
                
'                grandTotal = grandTotal + wt
'                grandTotal1 = grandTotal1 + .Fields("PALLETS").Value
                                   
                If iLine = 73 Then
                    Printer.Print ""
                    Printer.Print Tab(30); "This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,"
                            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>This report was programmed on 02/18/2010.   If a new commodity has been added since this date then,</td></tr>"
                    Printer.Print Tab(30); " this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately."
                            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately.</td></tr>"
                    Call PageHeader
                End If
                
                iLine = iLine + 1
                Printer.Print Tab(10); rsDet4.Fields("THE_COMM").Value & " * Scanned Non-Abitibi"; Tab(70); Format(wt, "##,##0"); Tab(100); Format(.Fields("PALLETS").Value, "##,##0"); Tab(113); "ROLL"; ' Tab(135); Format(.Fields("CARTONS").Value, "##,##0") & " cartons"
                        DataForWriteFile = DataForWriteFile & "<tr><td>" & rsDet4.Fields("THE_COMM").Value & " * Scanned Non-Abitibi</td><td>" & Format(wt, "##,##0") & "</td><td>" & Format(.Fields("PALLETS").Value, "##,##0") & "</td><td>" & "ROLL" & "</td><td>&nbsp;</td><td>&nbsp;</td></tr>"
            Else
                MsgBox OraDatabase.LastServerErrText, vbCritical
                Exit Sub
            End If
            .MoveNext
        End With
    Else
        If OraDatabase.LastServerErr <> 0 Then
            MsgBox OraDatabase.LastServerErrText, vbCritical
            OraDatabase.LastServerErrReset
            Exit Sub
        End If
    End If
    
    
       
        
    If bFirst = True Then
        If iLine = 75 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>-----------------------------------------------------------------------------------------------------------------------------</td></tr>"
        
        If iLine = 75 Then Call PageHeader
        iLine = iLine + 1
        Printer.FontBold = True
        Printer.Print Tab(5); " Sub Total "; Tab(66); Format(subTotal, "##,##0"); Tab(94); Format(subTotal1, "##,##0") '; Tab(126); Format(subTotal2, "##,##0")
                    DataForWriteFile = DataForWriteFile & "<tr><td>Sub Total</td><td>" & Format(subTotal, "##,##0") & "</td><td>" & Format(subTotal1, "##,##0") & "</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>"
        subTotal = 0
        Printer.FontBold = False
        
        If iLine = 75 Then Call PageHeader
        iLine = iLine + 1
        Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>-----------------------------------------------------------------------------------------------------------------------------</td></tr>"
        bFirst = False
    End If
      
    'pawan...........
    
    If iLine = 75 Then Call PageHeader
    iLine = iLine + 1
    Printer.FontSize = 12
    Printer.FontBold = True
    Printer.Print Tab(2); " Grand Total "; Tab(44); Format(Round(grandTotal, 2), "##,##0.00");
            DataForWriteFile = DataForWriteFile & "<tr><td>Grand Total</td><td>" & Format(Round(grandTotal, 2), "##,##0.00") & "</td><td colspan=4>&nbsp;</td></tr>"
'    Printer.Print Tab(2); " Grand Total "; Tab(29); Format(Round(grandTotal, 2), "##,##0.00"); Tab(48); Format(grandTotal1, "##,##0"); Tab(61); Format(grandTotal2, "##,##0")
    Printer.FontBold = False
    Printer.FontSize = 8
    If iLine = 75 Then Call PageHeader
    iLine = iLine + 1
    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
                    DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>-----------------------------------------------------------------------------------------------------------------------------</td></tr>"
    Printer.Print Tab(30); " Items marked with an * are not counted in the Grand Total."
                    DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>Items marked with an * are not counted in the Grand Total.</td></tr>"
    Printer.Print Tab(27); "This report was programmed on 02/18/2010.   If new commodities/vessels have been added since this date, then"
                    DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>This report was programmed on 02/18/2010.   If new commodities/vessels have been added since this date, then</td></tr>"
    Printer.Print Tab(30); " this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately."
                    DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>this program will need to be reviewed for accuracy.  Please log a TS helpdesk entry immediately.</td></tr>"
    Printer.Print ""
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>&nbsp;</td></tr>"
    Printer.Print ""
            DataForWriteFile = DataForWriteFile & "<tr><td colspan=6>&nbsp;</td></tr>"
'    Printer.Print Tab(2); "---Note:  during the conversion of Dole Paper products to a fully-automated system, this report will not record"
'    Printer.Print Tab(2); "---Abitibi Dock Ticket Paper (customer 312, commodity 1272)"
        
    MN  ' Mouse Normal
    
    Printer.EndDoc
    
    Set rsDetail = Nothing
    Set rsDetail1 = Nothing
    Set rsDetail2 = Nothing
    Set rsDet = Nothing
    Set rsDet1 = Nothing
    Set rsDet2 = Nothing

    If chkSave.Value = 1 Then
        Print #1, DataForWriteFile
        Close #1
    End If
    
    Me.cmdOk.Enabled = True
End Sub
Sub PageHeader()
    iPage = iPage + 1
    iLine = 0
    iLine = 8
    Printer.NewPage
    Printer.Orientation = 1
    
    Printer.FontBold = False
    Printer.Print Tab(3); "Printed On : " & Format(Now, "MM/DD/YYYY"); Tab(110); "Page No. : " & iPage
    Printer.Print
   
    Printer.FontSize = 12
    
    Printer.Print Tab(30); "Tonnage By Commodity:  From " & Me.txtDate1 & "  To " & Me.txtDate2
    
    Printer.FontSize = 8
    Printer.Print ""
    Printer.Print ""
    Printer.Print ""

    Printer.Print Tab(10); "COMMODITY NAME"; Tab(70); "WEIGHT (IN TONS)"; Tab(100); "COUNT1"; Tab(135); "COUNT2"
    'Printer.Print Tab(10); "COMMODITY NAME"; Tab(70); "WEIGHT (IN TONS)"; Tab(100); "COUNT1"
                  
    Printer.FontBold = True
    Printer.Print Tab(2); "---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"
    Printer.FontBold = False
    Printer.Print ""
End Sub

'updated
Private Sub cmdQuit_Click()
    Unload Me
    End
End Sub

'updated
Private Sub Form_Activate()
  If App.PrevInstance Then
     ' Make sure only one copy at a time is running!
     End
  End If
End Sub

'updated
Private Sub Form_Load()

    lblStatus.Caption = "Logging on to database..."
    Me.Show
    Me.Refresh
    DoEvents
    
    Me.txtDate1.SetFocus
    
    'On Error GoTo Err_FormLoad
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the Ora Database Objects
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
    'Set OraDatabase = OraSession.OpenDatabase("BNI.DEV", "SAG_OWNER/SAG_DEV", 0&)
    Set OraDatabaseRF = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
    'Set OraDatabase = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        lblStatus.Caption = "Logon Successful."
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        lblStatus.Caption = "Logon Failed."
        Unload Me
    End If
   
    'open access db
'
'    Set accessDb = New ADODB.Connection
'    Set cn2 = New ADODB.Connection
'    Set RS1 = New ADODB.Recordset
'    Set RS = New ADODB.Recordset
'    Set RS2 = New ADODB.Recordset
'
'    cn2.ConnectionString = "DSN=check;UID=sa;PWD=;"
    'cn2.Open
    
    ' We will default the filename to a string based on todays date
    'ChDrive "C"
    'ChDir ("\")
    
    'gsMyFileName = CurDir & "LCS_Lift_" & Mid$(Format(Date, "dd-mm-yyyy"), 4, 2) & Mid$(Format(Date, "dd-mm-yyyy"), 1, 2) & ".DTA"
    'gsTextDirectory = App.Path
    'Me.txtFilename.Text = gsMyFileName
    'Me.txtDate2.Text = Mid$(Format(Date, "mm/dd/yyyy"), 1, 10)
       
    On Error GoTo 0
    
    Exit Sub
    
Err_FormLoad:

    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation, "Tonnage by Commodity"
    lblStatus.Caption = "Error Occured."
    On Error GoTo 0

End Sub

