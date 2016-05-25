
// This file implements the code-behind class for DateSelector.aspx.
// App_Code\DateSelector.Controls.vb contains the Table, Row and Record control classes
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
  
partial class DateSelector
        : BaseApplicationPage
// Code-behind class for the DateSelector page.
// Place your customizations in Section 1. Do not modify Section 2.
{
        

#region "Section 1: Place your customizations here."    

        public DateSelector()
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
        
    
#endregion

    // Page Event Handlers - buttons, sort, links
    
              public void CancelButton_Click(object sender, EventArgs args)
              {
            
          // Click handler for CancelButton.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          CancelButton_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        
              public void OkButton_Click(object sender, EventArgs args)
              {
            
          // Click handler for OkButton.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          OkButton_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        

#endregion

#region "Section 2: Do not modify this section."
        

        private void Initialize()
        {
            // Called by the class constructor to initialize event handlers for Init and Load
            // You can customize by modifying the constructor in Section 1.
            this.Init += new EventHandler(Page_InitializeEventHandlers);
            this.Load += new EventHandler(Page_Load);

            
        base.Load += new System.EventHandler(this.Page_Load_RegisterAutoCloseScript);
        base.Load += new System.EventHandler(this.Page_Load_RegisterOkButtonHandler);
        base.Load += new System.EventHandler(DateSelector_Load);
        this.Init += new EventHandler(Page_Init);

        }

        // Handles base.Init. Registers event handler for any button, sort or links.
        // You can add additional Init handlers in Section 1.
        protected virtual void Page_InitializeEventHandlers(object sender, System.EventArgs e)
        {
            // Register the Event handler for any Events.
        
              this.CancelButton.Button.Click += new EventHandler(CancelButton_Click);
              this.OkButton.Button.Click += new EventHandler(OkButton_Click);
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
    

    // Load data from database into UI controls.
    // Modify LoadData in Section 1 above to customize.  Or override DataBind() in
    // the individual table and record controls to customize.
    public void LoadData_Base()
    {
    
        }

        // Write out event methods for the page events
        
              // event handler for Button with Layout
              public void CancelButton_Click_Base(object sender, EventArgs args)
              {
              
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                
            } catch (Exception ex) {
                shouldRedirect = false;
                this.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
            if (shouldRedirect) {
                this.ShouldSaveControlsToSession = true;
                this.CloseWindow(true);
            }
        
            else if (TargetKey != null && !shouldRedirect){
            this.ShouldSaveControlsToSession = true ; 
            this.CloseWindow(true);
            }
        
              }
            
              // event handler for Button with Layout
              public void OkButton_Click_Base(object sender, EventArgs args)
              {
              
            try {
                
            } catch (Exception ex) {
                this.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
          

#region Web Control Variables
   
#endregion

    protected string TargetName
    {
        get
        {
            return this.Request.Params["Target"];
        }
    }

    protected string Format
    {
        get
        {
            string s = this.Request.Params["Format"];
            if ((s == null) || (s.Length < 1))
            {
                s = "d";
            }
            return s;
        }
    }

    protected System.DateTime DefaultDate
    {
        get
        {
            string s = this.Request.Params["DefaultDate"];
            System.DateTime d;
            try
            {
                d = System.DateTime.ParseExact(s, Format, null);
            }
            catch //(Exception e)
            {
                try
                {
                    d = System.DateTime.Parse(s);
                }
                catch //(Exception e2)
                {
                    d = System.DateTime.Now;
                }
            }
            if (d.Year == System.DateTime.MaxValue.Year && 
                d.Month == System.DateTime.MaxValue.Month && 
                d.Day == System.DateTime.MaxValue.Day)
            {
                //HACK: Microsoft: Workaround for an MS bug that causes a yellow screen when a Calendar control is set to 12/31/9999.
                d = System.DateTime.Now;
            }
            return d;
        }
    }

    protected string SelectedDateString
    {
        get
        {
			String dateTimeStr = this.Calendar1.SelectedDate.ToShortDateString() + " " + DateTime.Now.ToLongTimeString();
            return DateTime.Parse(dateTimeStr).ToString(this.Format);
        }
    }

    protected override void UpdateSessionNavigationHistory()
    {
        //Do nothing
    }

    private string CreateUpdateScript(string valueString)
    {
        System.Text.StringBuilder sb = new System.Text.StringBuilder();
        sb.Append("<script language=javascript>");
        sb.Append("\r\n");
        sb.Append("<!--");
        sb.Append("\r\n");
        sb.AppendFormat("updateTarget({0});", 
            BaseClasses.Web.AspxTextWriter.CreateJScriptStringLiteral(valueString));
        sb.Append("\r\n");
        sb.Append("//-->");
        sb.Append("\r\n");
        sb.Append("</script>");
        sb.Append("\r\n");
        return sb.ToString();
    }

    private void RegisterUpdateScript()
    {
        const string ScriptKey = "DateUpdateScript";
        string script = CreateUpdateScript(SelectedDateString);
        ClientScript.RegisterStartupScript(typeof(string), ScriptKey, script);
    }

    private void Calendar1_SelectionChanged(object sender, System.EventArgs e)
    {
        this.RegisterUpdateScript();
    }

    private void OkButton_Script_Click(object sender, System.EventArgs e)
    {
        this.RegisterUpdateScript();
    }

    private void Calendar1_VisibleMonthChanged(object sender, System.Web.UI.WebControls.MonthChangedEventArgs e)
    {
        System.DateTime d = this.GetClosestValidVisibleDate(this.Calendar1.VisibleDate);
        if (!this.Calendar1.VisibleDate.Equals(d))
        {
            this.Calendar1.VisibleDate = d;
        }
    }

    protected System.DateTime GetClosestValidVisibleDate(System.DateTime d)
    {
        if (d.Year == System.DateTime.MaxValue.Year && d.Month == System.DateTime.MaxValue.Month)
        {
            return d.AddMonths(-1);
        }
        else if (d.Year == System.DateTime.MinValue.Year && d.Month == System.DateTime.MinValue.Month)
        {
            return d.AddMonths(1);
        }
        return d;
    }
    
    private void Page_Init(object sender, EventArgs e)
    {
        this.Calendar1.VisibleMonthChanged -= new MonthChangedEventHandler(this.Calendar1_VisibleMonthChanged);
        this.Calendar1.SelectionChanged -= new EventHandler(this.Calendar1_SelectionChanged);
    }

    private void Page_Load_RegisterAutoCloseScript(object sender, System.EventArgs e)
    {
        this.RegisterAutoCloseScript();
    }

    private void Page_Load_RegisterOkButtonHandler(object sender, System.EventArgs e)
    {
        if (this.OkButton != null)
        {
            this.OkButton.Button.Click += new System.EventHandler(this.OkButton_Script_Click);
        }
    }
    
    private void DateSelector_Load(object sender, System.EventArgs e)
    {
        this.Calendar1.VisibleMonthChanged += new System.Web.UI.WebControls.MonthChangedEventHandler(this.Calendar1_VisibleMonthChanged);
        this.Calendar1.SelectionChanged += new System.EventHandler(this.Calendar1_SelectionChanged);
    }

    private void RegisterAutoCloseScript()
    {
        if (BaseClasses.Utils.StringUtils.InvariantUCase(this.Request.Browser.Browser).IndexOf("IE") < 0)
        {
            return;
        }
        if (this.Request.Browser.MajorVersion < 5)
        {
            return;
        }

        //attach a function to the parent window's onunload event
        //that will close the current window
        string script = BaseClasses.Web.AspxTextWriter.CreateJScriptBlock(
            "function onParentUnload() { window.close(); }\r\nif (window.opener && !window.opener.closed) { window.opener.attachEvent(\'onunload\', onParentUnload);}");
        ClientScript.RegisterClientScriptBlock(typeof(string), "AutoCloseScript", script);
    }


#endregion

  
}
  
}
  