Attribute VB_Name = "modGlobal"
Option Explicit


Global OraSession As Object
Global OraDatabase As Object
Global dsCARGO_ACTIVITY As Object
Global dsCARGO_ACTIVITY_SUM As Object
Global dsCARGO_MANIFEST As Object
Global dsCARGO_TRACKING As Object
Global dsCARGO_TRACKING_MAX As Object
Global dsCARGO_ORIGINAL_MANIFEST As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsVESSEL_PROFILE As Object
Global dsCOUNTRY As Object
Global dsSTORAGE_RATE As Object
Global dsLOCATION_CATEGORY As Object

Global gsSqlStmt As String
Global giPrintFileNum As Integer

