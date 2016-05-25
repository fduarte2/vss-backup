using System;
using System.Web.UI;
using System.Collections;
using BaseClasses.Data;
using BaseClasses.Utils;
using BaseClasses.Resources;
using ProjectNameSpace.Business; 

namespace ePortDC.UI
{
    public class BaseApplicationPage : BaseClasses.Web.UI.BasePage
    {
        public BaseApplicationPage()
        {
            this.Load += new EventHandler(Page_Load);
            this.PreRender += new EventHandler(Control_ClearControls_PreRender);
            this.Unload += new EventHandler(Page_SaveControls_Unload);
        }

        private void Page_Load(object sender, System.EventArgs e)
        {
            if ((!(this.IsPostBack)))
            {
                this.DataBind();
            }
        }
        private bool _modifyRedirectUrlInProgress = false;

        public virtual string ModifyRedirectUrl(string redirectUrl, string redirectArgument, bool bEncrypt)
        {
            const string PREFIX_NO_ENCODE = "NoUrlEncode:";
            if ((_modifyRedirectUrlInProgress))
            {
                return null;
            }
            else
            {
                _modifyRedirectUrlInProgress = true;
            }
            string finalRedirectUrl = redirectUrl;
            string finalRedirectArgument = redirectArgument;
            if ((finalRedirectUrl == null) || (finalRedirectUrl.Length == 0))
            {
                return "";
            }
            else if ((finalRedirectUrl.IndexOf('{') < 0))
            {
                return finalRedirectUrl;
            }
            else
            {
                if (redirectArgument != null && redirectArgument.Length > 0)
                {
                    string[] arguments = redirectArgument.Split(',');
                    for (int i = 0; i <= (arguments.Length - 1); i++)
                    {
                        finalRedirectUrl = finalRedirectUrl.Replace("{" + i.ToString() + "}", "{" + arguments[i] + "}");
                    }
                    finalRedirectArgument = "";
                }
                ArrayList controlList = GetAllRecordAndTableControls();
                if (controlList.Count == 0)
                {
                    return finalRedirectUrl;
                }
                Hashtable controlIdList = new Hashtable();
                foreach (System.Web.UI.Control control in controlList)
                {
                    controlIdList.Add(control.UniqueID, control);
                }
                ArrayList forwardTo = new ArrayList();
                string remainingUrl = finalRedirectUrl;
                while ((remainingUrl.IndexOf('{') > 0) & (remainingUrl.IndexOf('}') > 0) & (remainingUrl.IndexOf('{') < remainingUrl.IndexOf('}')))
                {
                    int leftIndex = remainingUrl.IndexOf('{');
                    int rightIndex = remainingUrl.IndexOf('}');
                    string expression = remainingUrl.Substring(leftIndex + 1, rightIndex - leftIndex - 1);
                    remainingUrl = remainingUrl.Substring(rightIndex + 1);
                    string prefix = null;
                    if ((expression.IndexOf(":") > 0))
                    {
                        prefix = expression.Substring(0, expression.IndexOf(":"));
                    }
                    if ((prefix != null) && (prefix.Length > 0) && (!((StringUtils.InvariantLCase(prefix) == StringUtils.InvariantLCase(PREFIX_NO_ENCODE)))) && (!(BaseRecord.IsKnownExpressionPrefix(prefix))))
                    {
                        if ((controlIdList.Contains(prefix)) & (!(forwardTo.Contains(prefix))))
                        {
                            forwardTo.Add(prefix);
                        }
                    }
                }

                foreach (string containerId in forwardTo)
                {
                    Control ctl = ((Control)(controlIdList[containerId]));
                    if (ctl != null)
                    {
                        if (ctl is BaseApplicationRecordControl)
                        {
                            finalRedirectUrl = ((BaseApplicationRecordControl)(ctl)).ModifyRedirectUrl(finalRedirectUrl, finalRedirectArgument, bEncrypt);
                        }
                        else if (ctl is BaseApplicationTableControl)
                        {
                            finalRedirectUrl = ((BaseApplicationTableControl)(ctl)).ModifyRedirectUrl(finalRedirectUrl, finalRedirectArgument, bEncrypt);
                        }
                    }
                }
                foreach (System.Web.UI.Control control in controlList)
                {
                    if ((forwardTo.IndexOf(control.ID) < 0))
                    {
                        if (control is BaseApplicationRecordControl)
                        {
                            finalRedirectUrl = ((BaseApplicationRecordControl)(control)).ModifyRedirectUrl(finalRedirectUrl, finalRedirectArgument, bEncrypt);
                        }
                        else if (control is BaseApplicationTableControl)
                        {
                            finalRedirectUrl = ((BaseApplicationTableControl)(control)).ModifyRedirectUrl(finalRedirectUrl, finalRedirectArgument, bEncrypt);
                        }
                    }
                }
            }
            _modifyRedirectUrlInProgress = false;
            return finalRedirectUrl;
        }

        private ArrayList GetAllRecordAndTableControls()
        {
            ArrayList controlList = new ArrayList();
            GetAllRecordAndTableControls(this, controlList);
            return controlList;
        }

        private void GetAllRecordAndTableControls(Control ctl, ArrayList controlList)
        {
            if (ctl == null)
            {
                return;
            }
            if (ctl is BaseApplicationRecordControl || ctl is BaseApplicationTableControl)
            {
                controlList.Add(ctl);
            }
            
            foreach (Control nextCtl in ctl.Controls)
            {
                GetAllRecordAndTableControls(nextCtl, controlList);
            }
        }

		public string GetResourceValue(string keyVal, string appName) 
		{ 
			object resObj = GetGlobalResourceObject(appName, keyVal); 
			try 
			{
				if (!(resObj == null)) 
				{ 
					return resObj.ToString(); 
				}
                return keyVal; 
			}
			catch(Exception e) 
			{
                return keyVal; 
			}
			
		}
        // -----------------------------------------------------------------------------
        // <summary>
        // Register Control buttonCtrl with ScriptManager to perform traditional postback instead of default async postback
        // </summary>
        // <returns></returns>
        // <remarks>
        // </remarks>
        // <history>
        // 	[sramarao]	3/2007	Created
        // </history>
        // -----------------------------------------------------------------------------
        public void RegisterPostBackTrigger(System.Web.UI.Control buttonCtrl, System.Web.UI.Control updatePanelCtrl)
        {
            try
            {
                // Get current ScriptManager
                ScriptManager scriptMgr = ScriptManager.GetCurrent(this.Page);
                System.Web.UI.UpdatePanel CurrentUpdatePanel = (UpdatePanel)updatePanelCtrl;
                // If Scriptmanager not preset return.
                // If buttonCtrl is not surrounded by an UpdatePanel then return.
                if (scriptMgr != null && CurrentUpdatePanel != null)
                {
                    scriptMgr.RegisterPostBackControl(buttonCtrl);
                }
            }
            catch (Exception ex)
            {
                throw ex;
            }
        }



        private bool _ShouldSaveControlsToSession = false;

        public bool ShouldSaveControlsToSession
        {
            get
            {
                return this._ShouldSaveControlsToSession;
            }
            set
            {
                this._ShouldSaveControlsToSession = value;
            }
        }

        protected void Page_SaveControls_Unload(object sender, EventArgs e)
        {
            if (this.ShouldSaveControlsToSession)
            {
                this.SaveControlsToSession();
            }
        }

        protected virtual void SaveControlsToSession()
        {
        }

        protected void Control_ClearControls_PreRender(object sender, EventArgs e)
        {
            this.ClearControlsFromSession();
        }

        protected virtual void ClearControlsFromSession()
        {
        }

        public void SaveToSession(Control control, string value)
        {
            SaveToSession(control.UniqueID, value);
        }

        public string GetFromSession(Control control, string defaultValue)
        {
            return GetFromSession(control.UniqueID, defaultValue);
        }

        public string GetFromSession(Control control)
        {
            return GetFromSession(control.UniqueID, null);
        }

        public void RemoveFromSession(Control control)
        {
            RemoveFromSession(control.UniqueID);
        }

        public bool InSession(Control control)
        {
            return InSession(control.UniqueID);
        }

        public void SaveToSession(Control control, string variable, string value)
        {
            SaveToSession(control.UniqueID + variable, value);
        }

        public string GetFromSession(Control control, string variable, string defaultValue)
        {
            return GetFromSession(control.UniqueID + variable, defaultValue);
        }

        public void RemoveFromSession(Control control, string variable)
        {
            RemoveFromSession(control.UniqueID + variable);
        }

        public bool InSession(Control control, string variable)
        {
            return InSession(control.UniqueID + variable);
        }

        public void SaveToSession(string name, string value)
        {
            this.Session[GetValueKey(name)] = value;
        }

        public string GetFromSession(string name, string defaultValue)
        {
            string value = ((string)(this.Session[GetValueKey(name)]));
            if (value == null || value.Length == 0)
            {
                value = defaultValue;
            }
            return value;
        }

        public string GetFromSession(string name)
        {
            return GetFromSession(name, null);
        }

        public void RemoveFromSession(string name)
        {
            this.Session.Remove(GetValueKey(name));
        }

        public bool InSession(string name)
        {
            return (!(this.Session[GetValueKey(name)] == null));
        }

        public string GetValueKey(string name)
        {
            return this.Session.SessionID + this.AppRelativeVirtualPath + name;
        }
        public virtual string Encrypt(string Source)
        {
            string s = "";
            try
            {
                Crypto CheckCrypto = new Crypto(Crypto.Providers.DES);
                // You may change SessionID to any other key if needed:
                s = CheckCrypto.Encrypt(Source, this.Session.SessionID, false);
                s = System.Web.HttpUtility.UrlEncode(s);
            }
            catch { s = Source; }
            return s;
        }
        public virtual string Decrypt(string Source)
        {
            string s = "";
            try {
                Crypto CheckCrypto = new Crypto(Crypto.Providers.DES);
                // You may change SessionID to any other key if needed:
                s=CheckCrypto.Decrypt(Source, this.Session.SessionID, false);
                s=System.Web.HttpUtility.UrlDecode(s);
            }
            catch {
                throw new System.UriFormatException(RU.GetErrMsg(RU.ErrRes.GetRecords));
            }
            return s;
        }
    }
}