using System.ComponentModel;
using System.Web.UI;
using System.Web;

namespace ePortDC
{
    public class FvDsHyperLink : BaseClasses.Web.UI.WebControls.PopupWindowHyperLink
    {
        public FvDsHyperLink() : base()
        {
            this.WindowFeatures = "width=380, height=300, resizable=yes ,scrollbars=no";
            this.WindowName = "dswin";
            this.ImageUrl = "~/Images/DateSelector.gif";
            this.CssClass = "dslink";
        }

        [Editor("System.Web.UI.Design.UrlEditor, System.Design, Version=1.0.3300.0, Culture=neutral, PublicKeyToken=b03f5f7f11d50a3a", typeof(System.Drawing.Design.UITypeEditor)), Category("Navigation"), DefaultValue(""), Description("HyperLink_NavigateUrl"), Bindable(false)]
        public new string NavigateUrl
        {
            get
            {
                return this.DeriveNavigateUrl();
            }
        }

        protected override string WindowUrlParam
        {
            get
            {
                string url = this.NavigateUrl;
                if (url != null && url.Length > 0)
                {
                    url = this.ResolveUrl(url);
                }
                string s = BaseClasses.Web.AspxTextWriter.CreateJScriptStringLiteral(url);
                try
                {
                    s += string.Format("+escape(document.getElementById({0}).value)", BaseClasses.Web.AspxTextWriter.CreateJScriptStringLiteral(this.GetControlToUpdate().ClientID));
                }
                catch
                {
                }
                return s;
            }
        }

        [Category("Behavior"), DefaultValue("width=280, height=250, resizable,scrollbars=yes"), Bindable(true)]
        public new string WindowFeatures
        {
            get
            {
                return base.WindowFeatures;
            }
            set
            {
                base.WindowFeatures = value;
            }
        }
        private string _ControlToUpdate = "";

        [Bindable(true), Category("Behavior"), DefaultValue("")]
        public string ControlToUpdate
        {
            get
            {
                return this._ControlToUpdate;
            }
            set
            {
                this._ControlToUpdate = value;
            }
        }
        private string _Format = "";

        [Bindable(true), Category("Behavior"), DefaultValue("")]
        public string Format
        {
            get
            {
                return this._Format;
            }
            set
            {
                this._Format = value;
            }
        }

        public Control GetControlToUpdate()
        {
            if (this.ControlToUpdate != null && this.ControlToUpdate.Length > 0)
            {
                return this.NamingContainer.FindControl(this.ControlToUpdate);
            }
            return null;
        }

        protected string DeriveNavigateUrl()
        {
            Control c = this.GetControlToUpdate();
            return string.Format("~/Shared/DateSelector.aspx?Format={0}&Target={1}&usnh=n&DefaultDate=", HttpUtility.UrlEncode(this.Format), HttpUtility.UrlEncode(c.ClientID));
        }

        protected override void Render(System.Web.UI.HtmlTextWriter writer)
        {
            Control c = this.GetControlToUpdate();	    
            if (c != null && !c.Visible)
            {
                return;
            }
            string baseNavUrl = base.NavigateUrl;
            base.NavigateUrl = this.NavigateUrl;
            base.Render(writer);
            base.NavigateUrl = baseNavUrl;
        }

        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);
            this._ControlToUpdate = System.Convert.ToString(this.ViewState["ControlToUpdate"]);
            this._Format = System.Convert.ToString(this.ViewState["Format"]);
        }

        protected override object SaveViewState()
        {
            this.ViewState["ControlToUpdate"] = this._ControlToUpdate;
            this.ViewState["Format"] = this._Format;
            return base.SaveViewState();
        }
    }
}