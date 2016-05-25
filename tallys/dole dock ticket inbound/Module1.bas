Attribute VB_Name = "Module1"
Option Explicit
Global strSql As String
Global dsMAIN_DATA As Object
Global dsSHORT_TERM_DATA As Object
Global dsDAMAGE_CHECK As Object
Global dsPER_DOCKTICKET As Object
Global dsDAMAGE_REPORT As Object
Global dsUNSCANNED As Object

Global strVesselName As String
Global strVesselNameOutput As String
Global strCommodityName As String
Global strStartTime As String
Global strEndTime As String
Global strOrderNum As String
Global strCustName As String
Global sPath As String

Global OraSession As Object
Global OraDatabase As Object

