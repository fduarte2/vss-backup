Attribute VB_Name = "modGlobal"
Option Explicit


Global OraSession As Object
Global OraDatabase As Object
'Global OraPROD As Object
Global dsASSET_CHECK As Object
Global dsBILLING As Object
Global dsBILLING_CHECK As Object
Global dsBILLING_MAX As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsSERVICE_CATEGORY As Object
Global dsVESSEL_PROFILE As Object
Global dsBILLING_NO As Object


Global gsPVItem As String
Global gsSqlStmt As String

'Billing number
Global glBillingNum As Long

Global itemSelected As Boolean

