using System;
using System.Web.UI.WebControls;
using System.ComponentModel;
using BaseClasses;
using BaseClasses.Utils;
using BaseClasses.Web.UI.WebControls;
using ePortDC.Business;
using ePortDC.Data;
        
namespace ePortDC.UI
{

#region Code customizations
    // Each page class is defined in a hierarchy sub-classed from the
    // Page class of Microsoft .NET Framework. The hierarchy is as follows:
    //
    //    System.Web.UI.Page   (Microsoft .NET Framework class)
    //     BaseClasses.Web.UI.BasePage    (Base Classes.  Source code included)
    //      BaseApplicationPage    (Application wide BasePage class.  You can make app wide page customizations here.)
    //        ShowOrder_DetailsTablePage    (Code-behind class. Created once. Never overwritten)
    //
    // All code customizations can be done in the code-behind class.  Since the
    // code-behind class is created once, and never overwritten, you can be
    // assured that all of your customizations are preserved.
    //
    // The code-behind class is the lowest class in the hierarchy.  This
    // means that can can override any functionality provided by any
    // of the parent classes.
    //
    // The BaseApplicationPage class (defined in <AppFolder>\Shared\BaseApplicationPage.vb)
    // allows you to modify the behavior of the System.Web.UI.Page
    // or BaseClasses.Web.UI.BasePage across the entire application.
    //
    // The table and record controls included in this control will have their code behind classes
    // generated in <AppFolder>\App_Code\${ControlSafeClassDirectory}\ShowOrder_DetailsTablePage.Controls.vb
    // Customizations made to these table and record controls will be added to this class.
#endregion
    // Code-behind class for the SignIn_Control user control.

    partial class SignIn_Control : BaseApplicationUserControl
    {

#region "Section 1: Place your customizations here."

        public SignIn_Control()
        {
            this.Load += new EventHandler(Page_Load);
        }

        // LoadData reads database data and assigns it to UI controls.
        // Customize by adding code before or after the call to LoadData_Base()
        // or replace the call to LoadData_Base().
        public void LoadData()
        {
            LoadData_Base();
        }

        // Write out override methods for the page events
#endregion

#region "Section 2: Do not modify this section."

        // Handles MyBase.Load.  If you need to, you can add additional Load handlers in Section 1.
        // Read database data and put into the UI controls.
        protected virtual void Page_Load(System.Object sender, System.EventArgs e) 
        {
            // Load data only when displaying the page for the first time
            if (!this.IsPostBack) {

                // Read the data for all controls on the page.
                // To change the behavior, override the DataBind method for the individual
                // record or table UI controls.
                this.LoadData();
            }
        }

        // Load data from database into UI controls. 
        // Modify LoadData in Section 1 above to customize.  Or override DataBind() in
        // the individual table and record controls to customize.
        public void LoadData_Base()
        {
            try
            {
                // Load data only when displaying the page for the first time
                if (!this.IsPostBack)
                {
                    // Must start a transaction before performing database operations
                    DbUtils.StartTransaction();

                    // Load data for each record and table UI control.
                    // Ordering is important because child controls get 
                    // their parent ids from their parent UI controls.
                }
            }
            catch (Exception ex)
            {
                // An error has occured so display an error message.
                MiscUtils.RegisterJScriptAlert(this, "Page_Load_Error_Message", ex.Message);
            }
            finally
            {
                if (!this.IsPostBack) 
                {
                    // End database transaction
                    DbUtils.EndTransaction();
                }
            }
        }

        public virtual void Login(string redirectUrl)
        {
            this.SignInControl.Login(redirectUrl);
        }

        //Performs the login
        public virtual void Login(bool bRedirectOnSuccess)
        {
            this.SignInControl.Login(bRedirectOnSuccess);
        }
#endregion

    }
}