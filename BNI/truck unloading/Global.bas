Attribute VB_Name = "modGlobal"
Option Explicit


Global OraSession As Object
Global OraDatabase As Object
Global dsBILLING As Object
Global dsDOCK_RCPT_HANDLING_RATE As Object
Global dsDOCK_RCPTS As Object
Global dsCARGO_ORIGINAL_WEIGHT As Object
Global dsUNITS As Object
Global dsUNIT_CONVERSION As Object
Global dsBILLING_MAX As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsSERVICE_CATEGORY As Object
Global dsVESSEL_PROFILE As Object
Global dsLOCATION_CATEGORY As Object
Global dsBILL_CHECK As Object
Global dsSHORT_TERM_DATA As Object
Global gsPVItem As String
Global gsSqlStmt As String

'Billing number
Global glBillingNum As Long
