Attribute VB_Name = "Module1"
Option Explicit     'Juice Proj 4/19/2007 Rudy: Whole module added to this project.  Decided to have
                          ' a separate module in case want this functionality in another project

Global wrdApp As Word.Application

Public Sub CreateWordObject()
  'Test if object is already created before calling CreateObject:
  frmD_W_His.lblStatus.Caption = "CreateWordObject TypeName(wrd App) <> Application"
  If TypeName(wrdApp) <> "Application" Then
    frmD_W_His.lblStatus.Caption = "CreateWordObject CreateObject(Word.Application)"
    Set wrdApp = CreateObject("Word.Application")
'   Msg Box (xlApp.Version)
  End If
End Sub

Public Sub UseWordObject()
      'Note as of 5/14/07 DO NOT USE, it's been rewritten as UseBodyObject
      
  'Msg Box TypeName(wrd App) 'if displays "Application" then
      'Reference to Word is valid, else reference is invalid and
      'an error occurs on the following line:
  'Msg Box wrd App.Name
    
Dim msm As String
Dim DumOrder As String
  
  wrdApp.WindowState = wdWindowStateMinimize
  wrdApp.Visible = True     'useful for de bugging, ie can SEE it happening.  Will not run without it??!!
  'wrd App.Visible = False
  wrdApp.Activate
  wrdApp.WindowState = wdWindowStateMinimize
  
  msm = App.Path & "\DummyNumBarcode.doc"
  'msm = App.Path & "\DummyBarcode.doc"
  msm = wrdApp.Documents.Open(FileName:=msm, ReadOnly:=True)
  'msm = Me.Application.Documents.Open(FileName:="DummyNumBarcode.doc", ReadOnly:=True)
  
  wrdApp.Application.Selection.Paste        'paste's contents of clipboard
    
  'DumOrder = "*DM00033938*"
  DumOrder = Clipboard.GetText
  DumOrder = Replace(DumOrder, "*", "")
  
  'try just this rem'd out:
  'wrd App.Application.ActiveDocument.Sections.PageSetup.DifferentFirstPageHeaderFooter = False
  'wrd App.Application.ActiveDocument.Sections.Add.Headers.Item(wdHeaderFooterPrimary).Range.Text = "tetsting"
  
  'creates 2 pages:
  'wrd App.Application.ActiveDocument.Sections.Add.Headers.Item(wdHeaderFooterPrimary).Range.Text = "Dummy Number:  " & DumOrder
  'creates just 1 page :
  wrdApp.Application.ActiveDocument.Sections.Item(wdHeaderFooterPrimary).Headers.Item(wdHeaderFooterPrimary).Range.Text = "Dummy Number:  " & DumOrder
    
  '---------tried various things begin---------------
  'wrd app.DDEPoke(
'  'msm =wrd App.Documents.Open("mydoc.doc",,,,,,,,,,,TRUE,,,)
  'wrd App.NewDocument
'  wrd App.NewWindow    'reqs a doc open?
'  msm = wrd App.Documents.Add(, , , True)
  'wrd App.SubstituteFont("Arial", "BC C39 2 to 1 HD Wide")
  'wrd App.FontNames = "BC C39 2 to 1 HD Wide"

  'wrd App.PrintPreview

    
'Create a new document in Word
'Dim oApp As Word.Application
'Dim oSec As Word.Section
'Dim oDoc As Word.Document
'    Set oApp = wrd App.Application
'    'Set oDoc = oApp.Documents.Add
'
'  With oDoc
'    'Set oSec = .Sections(1)
'
'    oSec.PageSetup.DifferentFirstPageHeaderFooter = False
'
'    oSec.Headers(wdHeaderFooterPrimary).Range.Text = "Dummy Number:  " & DumOrder
'  End With
  
  
'Set HeadersFooters

  '---------tried various things end---------------
  
  
  'looks like I req Do Events here!!
  DoEvents
  
  wrdApp.PrintOut

  wrdApp.Visible = False
  
  'try a Do Events here!!
  DoEvents

End Sub
Public Sub CloseWordObject()
  frmD_W_His.lblStatus.Caption = "CloseWordObject begin"
  If TypeName(wrdApp) = "Application" Then
    wrdApp.Quit SaveChanges:=wdDoNotSaveChanges
    frmD_W_His.lblStatus.Caption = "Set wrdApp = Nothing"
    Set wrdApp = Nothing
  End If
End Sub

Sub SetHeadersFooters()         '<-- Sub not used, was able to make it all work in UseWordObject
                               'which is now OLD - SO DO NOT USE
Dim oApp As Word.Application
Dim oSec As Word.Section
Dim oDoc As Word.Document

'Create a new document in Word
Set oApp = New Word.Application
Set oDoc = oApp.Documents.Add

  With oDoc
            
    '=== SECTION 1 ==================================================
    
    'paste the clipboard:
    'oApp.Application.Selection.Paste        'paste's contents of clipboard
    
    
    Dim DumOrder As String
    
    DumOrder = "*DM00033938*"

    
    'Add two pages to the first section where the first page in the
    'section has different headers and footers than the second page
    Set oSec = .Sections(1)
    
    'oSec.PageSetup.DifferentFirstPageHeaderFooter = True
    oSec.PageSetup.DifferentFirstPageHeaderFooter = False
    'oSec.Range.InsertAfter "3 *DM00033938*"
    
'''        'oSec.Range.FormattedText
'''        oSec.Range.Font.Name = "BC C39 2 to 1 HD Wide"
'''        oSec.Range.Text DumOrder
'''        'oSec.Range.InsertAfter DumOrder
'''        oSec.Range.Select
'''        oSec.Range.Font.Name = "BC C39 2 to 1 HD Wide"
''''        .Range(oSec.Range.End - 1).InsertBr eak wdPageBr eak
''''        oSec.Range.InsertAfter "Text on Page 2 (Section 1)"
        
      'Add the headers/footers for the first section (that contains two
      'pages)
      'oSec.Headers(wdHeaderFooterFirstPage).Range.Text = "1" & DumOrder
      oSec.Headers(wdHeaderFooterPrimary).Range.Text = "Dummy Number:  " & DumOrder
'        oSec.Footers(wdHeaderFooterFirstPage).Range.Text = _
'              "Page1 -- Section 1 First Page Footer"
'        oSec.Footers(wdHeaderFooterPrimary).Range.Text = _
'              "Page2 -- Section 1 Primary Footer"

    'Save the document
      '.SaveAs App.Path & "\DummyBarcode.doc"
        
    .PrintOut
        
        
      '.Close
        
  End With
    
End Sub

