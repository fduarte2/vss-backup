
// This file implements the code-behind class for ShowDC_OrderTablePage.aspx.
// App_Code\ShowDC_OrderTablePage.Controls.vb contains the Table, Row and Record control classes
// for the page.  Best practices calls for overriding methods in the Row or Record control classes.

#region "Using statements"    

using System;
using System.Data;
using System.Collections;
using System.ComponentModel;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using BaseClasses;
using BaseClasses.Utils;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;
using BaseClasses.Web.UI.WebControls;
        
using ePortDC.Business;
using ePortDC.Data;
        

#endregion

  
namespace ePortDC.UI
{
  
partial class ShowDC_OrderTablePage
        : BaseApplicationPage
// Code-behind class for the ShowDC_OrderTablePage page.
// Place your customizations in Section 1. Do not modify Section 2.
{
        

#region "Section 1: Place your customizations here."    

        public ShowDC_OrderTablePage()
        {
            this.Initialize();
        }

        public void LoadData()
        {
            // LoadData reads database data and assigns it to UI controls.
            // Customize by adding code before or after the call to LoadData_Base()
            // or replace the call to LoadData_Base().
            LoadData_Base();
         }

#region "Ajax Functions"

        
        [System.Web.Services.WebMethod()]
        public static Object[] GetRecordFieldValue(String tableName , 
                                                    String recordID , 
                                                    String columnName, 
                                                    String title, 
                                                    bool persist, 
                                                    int popupWindowHeight, 
                                                    int popupWindowWidth, 
                                                    bool popupWindowScrollBar)
        {
            // GetRecordFieldValue gets the pop up window content from the column specified by
            // columnName in the record specified by the recordID in data base table specified by tableName.
            // Customize by adding code before or after the call to  GetRecordFieldValue_Base()
            // or replace the call to  GetRecordFieldValue_Base().

            return GetRecordFieldValue_Base(tableName, recordID, columnName, title, persist, popupWindowHeight, popupWindowWidth, popupWindowScrollBar);
        }

    
        [System.Web.Services.WebMethod()]
        public static object[] GetImage(String tableName,
      
                                        String recordID, 
                                        String columnName, 
                                        String title, 
                                        bool persist, 
                                        int popupWindowHeight, 
                                        int popupWindowWidth, 
                                        bool popupWindowScrollBar)
        {
            // GetImage gets the Image url for the image in the column "columnName" and
            // in the record specified by recordID in data base table specified by tableName.
            // Customize by adding code before or after the call to  GetImage_Base()
            // or replace the call to  GetImage_Base().
            return GetImage_Base(tableName, recordID, columnName, title, persist, popupWindowHeight, popupWindowWidth, popupWindowScrollBar);
        }
        
    
        [System.Web.Services.WebMethod]
        public static string[] GetAutoCompletionList_DC_OrderSearchArea(String prefixText, int count)
        {
            // GetDC_OrderSearchAreaCompletionList gets the list of suggestions from the database.
            // prefixText is the search text typed by the user .
            // count specifies the number of suggestions to be returned.
            // Customize by adding code before or after the call to  GetAutoCompletionList_DC_OrderSearchArea_Base()
            // or replace the call to GetAutoCompletionList_DC_OrderSearchArea_Base().
            return GetAutoCompletionList_DC_OrderSearchArea_Base(prefixText, count);
        }
      
#endregion

    // Page Event Handlers - buttons, sort, links
    




#region "CrystalReport"

	/// <summary>
	///Override the CrystalReportButton_Click and call DisplayReportAsPDF_CrystalReportButton function
	/// </summary>
	public  void CrystalReportButton_Click(object sender, System.EventArgs e)    
	{
		CrystalDecisions.CrystalReports.Engine.ReportDocument crReportDocument = new CrystalDecisions.CrystalReports.Engine.ReportDocument();
		string fileName= @"dccrit01.rpt";
		try
		{
			if (fileName.Substring(1).StartsWith(@":\"))
			    crReportDocument.Load(fileName);
			else
			    crReportDocument.Load(this.Page.MapPath(fileName));
		}
		catch (Exception ex)
		{	
			string errMsg = ex.Message.Replace("\n", "").Replace("\r", "");
			errMsg += " Please make sure the dlls for Crystal Report are compatible with the Crystal Report file.";
			BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", errMsg);
		}

		try
		{
			//************If you see a "Missing Parameter Value" error message or if your Crystal Report file requires paarameters,
			//************please uncomment this section & specify appropriate values. 
			////Set parameter
			//CrystalDecisions.Shared.ParameterDiscreteValue paramValue = new CrystalDecisions.Shared.ParameterDiscreteValue();
			//paramValue.Value = "ALFKI";
			//crReportDocument.SetParameterValue("CurrentCustID", paramValue);
			//**********************************************
	
			//************If you see a 'Logon Failed' error message or if your database requires authentication,
			//************please uncomment this section & specify appropriate values. 
  		
			////define and locate required objects for db login
			//CrystalDecisions.CrystalReports.Engine.Database db = crReportDocument.Database;
			//CrystalDecisions.CrystalReports.Engine.Tables tables = db.Tables;
			//CrystalDecisions.Shared.TableLogOnInfo tableLoginInfo = new CrystalDecisions.Shared.TableLogOnInfo();

			////define connection information
			//CrystalDecisions.Shared.ConnectionInfo dbConnInfo = new CrystalDecisions.Shared.ConnectionInfo();
			//dbConnInfo.UserID = "username";
			//dbConnInfo.Password = "pwd";
			//dbConnInfo.ServerName = "DBName";

			////apply connection information to each table
			//foreach (CrystalDecisions.CrystalReports.Engine.Table table in tables)
			//{
			//	tableLoginInfo = table.LogOnInfo;
			//	tableLoginInfo.ConnectionInfo = dbConnInfo;
			//	table.ApplyLogOnInfo(tableLoginInfo);
			//}
			//**********************************************
  		
  		
		}
		catch (Exception ex)
		{
			string errMsg = ex.Message.Replace("\n", "").Replace("\r", "");
			BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", errMsg);
			errMsg += " If this is a deployment machine, make sure network service has permissions to read and write to the windows\\temp folder.";
		}
		DisplayReportAsPDF_CrystalReportButton(crReportDocument);
	}

#endregion

#region "CrystalReport"

    /// <summary>
    /// This function creates an instance of the Crystal Report in PDF format and displays it
    /// </summary>
    private void DisplayReportAsPDF_CrystalReportButton(CrystalDecisions.CrystalReports.Engine.ReportDocument reportObject)
    {
        try
        {
            // Export as a stream
            System.IO.Stream stream = reportObject.ExportToStream(CrystalDecisions.Shared.ExportFormatType.PortableDocFormat);
            byte[] content = new byte[stream.Length];
            stream.Read(content, 0, content.Length);

            //output as an attachment
            BaseClasses.Utils.NetUtils.WriteResponseBinaryAttachment(this.Page.Response, "report.pdf", content, 0, true);
        }
        catch (Exception ex)
        {
            string errMsg = ex.Message.Replace("\n", "").Replace("\r", "");
            errMsg += " Please modify the button click function in your code-behind.";
            BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", errMsg);
        }
    }

#endregion
#endregion

#region "Section 2: Do not modify this section."
        

        private void Initialize()
        {
            // Called by the class constructor to initialize event handlers for Init and Load
            // You can customize by modifying the constructor in Section 1.
            this.Init += new EventHandler(Page_InitializeEventHandlers);
            this.Load += new EventHandler(Page_Load);

            
        }

        // Handles base.Init. Registers event handler for any button, sort or links.
        // You can add additional Init handlers in Section 1.
        protected virtual void Page_InitializeEventHandlers(object sender, System.EventArgs e)
        {
            // Register the Event handler for any Events.
        
              this.CrystalReportButton.Button.Click += new EventHandler(CrystalReportButton_Click);
        }

        // Handles base.Load.  Read database data and put into the UI controls.
        // You can add additional Load handlers in Section 1.
        protected virtual void Page_Load(object sender, EventArgs e)
        {
        
            // Check if user has access to this page.  Redirects to either sign-in page
            // or 'no access' page if not. Does not do anything if role-based security
            // is not turned on, but you can override to add your own security.
            this.Authorize(this.GetAuthorizedRoles());

            // Load data only when displaying the page for the first time
            if ((!this.IsPostBack)) {   
        
                // Setup the header text for the validation summary control.
                this.ValidationSummary1.HeaderText = GetResourceValue("ValidationSummaryHeaderText", "ePortDC");

        // Read the data for all controls on the page.
        // To change the behavior, override the DataBind method for the individual
        // record or table UI controls.
        this.LoadData();
    }
    }

    public static object[] GetRecordFieldValue_Base(String tableName , 
                                                    String recordID , 
                                                    String columnName, 
                                                    String title, 
                                                    bool persist, 
                                                    int popupWindowHeight, 
                                                    int popupWindowWidth, 
                                                    bool popupWindowScrollBar)
    {
        string content =  NetUtils.EncodeStringForHtmlDisplay(BaseClasses.Utils.MiscUtils.GetFieldData(tableName, recordID, columnName)) ;
        // returnValue is an array of string values.
        // returnValue(0) represents title of the pop up window.
        // returnValue(1) represents content ie, image url.
        // retrunValue(2) represents whether pop up window should be made persistant
        // or it should closes as soon as mouse moved out.
        // returnValue(3), (4) represents pop up window height and width respectivly
        // ' returnValue(5) represents whether pop up window should contain scroll bar.
        // (0),(2),(3) and (4) is initially set as pass through attribute.
        // They can be modified by going to Attribute tab of the properties window of the control in aspx page.
        object[] returnValue = new object[6];
        returnValue[0] = title;
        returnValue[1] = content;
        returnValue[2] = persist;
        returnValue[3] = popupWindowWidth;
        returnValue[4] = popupWindowHeight;
        returnValue[5] = popupWindowScrollBar;
        return returnValue;
    }

    public static object[] GetImage_Base(String tableName, 
                                          String recordID, 
                                          String columnName, 
                                          String title, 
                                          bool persist, 
                                          int popupWindowHeight, 
                                          int popupWindowWidth, 
                                          bool popupWindowScrollBar)
    {
        string  content= "<IMG src =" + "\"../Shared/ExportFieldValue.aspx?Table=" + tableName + "&Field=" + columnName + "&Record=" + recordID + "\"/>";
        // returnValue is an array of string values.
        // returnValue(0) represents title of the pop up window.
        // returnValue(1) represents content ie, image url.
        // retrunValue(2) represents whether pop up window should be made persistant
        // or it should closes as soon as mouse moved out.
        // returnValue(3), (4) represents pop up window height and width respectivly
        // returnValue(5) represents whether pop up window should contain scroll bar.
        // (0),(2),(3), (4) and (5) is initially set as pass through attribute.
        // They can be modified by going to Attribute tab of the properties window of the control in aspx page.
        object[] returnValue = new object[6];
        returnValue[0] = title;
        returnValue[1] = content;
        returnValue[2] = persist;
        returnValue[3] = popupWindowWidth;
        returnValue[4] = popupWindowHeight;
        returnValue[5] = popupWindowScrollBar;
        return returnValue;
    }
    
    public static string[] GetAutoCompletionList_DC_OrderSearchArea_Base(String prefixText, int count)
    {
        // Since this method is a shared/static method it does not maintain information about page or controls within the page.
        // Hence we can not invoke any method associated with any controls.
        // So, if we need to use any control in the page we need to instantiate it.
        ePortDC.UI.Controls.ShowDC_OrderTablePage.DC_OrderTableControl control = new ePortDC.UI.Controls.ShowDC_OrderTablePage.DC_OrderTableControl();
        return control.GetAutoCompletionList_DC_OrderSearchArea(prefixText, count);
    }
      

    // Load data from database into UI controls.
    // Modify LoadData in Section 1 above to customize.  Or override DataBind() in
    // the individual table and record controls to customize.
    public void LoadData_Base()
    {
    
            try {
                // Load data only when displaying the page for the first time
                if ((!this.IsPostBack)) {

                    // Must start a transaction before performing database operations
                    DbUtils.StartTransaction();

                    // Load data for each record and table UI control.
                    // Ordering is important because child controls get 
                    // their parent ids from their parent UI controls.
        
                    this.DC_OrderTableControl.LoadData();
                    this.DC_OrderTableControl.DataBind();           
          
                }
            } catch (Exception ex) {
                // An error has occured so display an error message.
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "Page_Load_Error_Message", ex.Message);
            } finally {
                if (!this.IsPostBack) {
                    // End database transaction
                    DbUtils.EndTransaction();
                }
            }
        
        }

        // Write out event methods for the page events
        
#endregion

  
}
  
}
  