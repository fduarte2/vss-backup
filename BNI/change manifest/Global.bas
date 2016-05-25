Attribute VB_Name = "modGlobal"
Option Explicit

Global OraSession As Object
Global OraDatabase As Object
Global dsEXPORTER_PROFILE As Object
Global dsSHORT_TERM_DATA As Object
Global dsCARGO_ACTIVITY As Object
Global dsCARGO_MANIFEST As Object
Global dsCARGO_MANIFEST_ORIGINAL As Object
Global dsCARGO_MANIFEST_CHANGES As Object
Global dsCARGO_MANIFEST_CHANGES_MAX As Object
Global dsCARGO_HISTORY_CHANGES As Object
Global dsCARGO_TRACKING As Object
Global dsCARGO_TRACKING_ALL As Object
Global dsCARGO_TRACKING_ORIGINAL As Object
Global dsCARGO_TRACKING_CHANGES As Object
Global dsCARGO_TRACKING_CHANGES_MAX As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsVESSEL_PROFILE As Object
Global dsVOYAGE_CARGO As Object
Global dsVOYAGE As Object
Global dsUNITS As Object
Global dsLOCATION_CATEGORY As Object
Global dsFREE_TIME As Object
Global dsTEMP As Object
Global gsPVItem As String
Global gsSqlStmt As String
Global gCont As Double 'getting container number
Global gsave As Boolean 'for save
Global gcontinue As Boolean 'for continue
Global gLotNum As String
Global gQtyRcvd As Double
Global gQtyExpct As Double
Global gQtyInHus As Double
Global gQtyChng As Double
Global iCheck As Boolean

Global iFound As Boolean
Global i As Integer
Global ship_no As Long
