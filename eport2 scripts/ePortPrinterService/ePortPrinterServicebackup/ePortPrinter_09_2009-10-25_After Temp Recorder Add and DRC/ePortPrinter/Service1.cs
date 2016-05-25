using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Diagnostics;
using System.ServiceProcess;
using System.Text;
using System.IO;
using Microsoft.Office.Interop.Excel;
using System.Data.SqlClient;
//using System.Web;

namespace ePortPrinter
{
    public partial class PrintService : ServiceBase
    {
        System.Timers.Timer eTimer = new System.Timers.Timer();

        public PrintService()
        {
            InitializeComponent();
        }

        protected override void OnStart(string[] args)
        {
            WriteToLog("");
            WriteToLog("Starting the Service at: " + DateTime.Now.ToString());

            // Get polling interval
            string sPollingMilliSec = System.Configuration.ConfigurationManager.AppSettings.Get("ePortPollingMilliSec");
            WriteToLog("Polling Interval: " + sPollingMilliSec + " milli seconds");

            // Get database connection string and write to log
            string sConnectionString = System.Configuration.ConfigurationManager.AppSettings.Get("ePortConnectionString");
            WriteToLog("Connection String: " + sConnectionString);

            // Enable the timer
            eTimer.Elapsed += new System.Timers.ElapsedEventHandler(eTimer_Tick);
            eTimer.Interval = System.Convert.ToInt32(sPollingMilliSec); // 5000 = 5 Seconds
            eTimer.Enabled = true;
        }

        protected override void OnStop()
        {
            eTimer.Enabled = false;
            WriteToLog("Stopping the Service at: " + DateTime.Now.ToString());
        }

        private void eTimer_Tick(object sender, EventArgs e)
        {
            eTimer.Enabled = false;

            // Process requests for print, create and emails
            ReadRequestQueue();

            // Sync Orders
            SyncOracle("ORDER", "dbo.DC_OrderSync", "OrderNum", "STRING", 12, "pCustomDC_OrderSync", "pCustomDC_OrderDelete");

            // Sync Picklist
            SyncOracle("PICKLIST", "dbo.DC_PickListSync", "OrderNum", "STRING", 12, "pCustomDC_PickListSync", "pCustomDC_PickListSync");

            // Sync Customer
            SyncOracle("CUSTOMER", "dbo.DC_CustomerSync", "CustomerId", "INT", 16, "pCustomDC_CustomerSync", "pCustomDC_CustomerDelete");

            // Sync Consignee
            SyncOracle("CONSIGNEE", "dbo.DC_ConsigneeSync", "ConsigneeId", "INT", 16, "pCustomDC_ConsigneeSync", "pCustomDC_ConsigneeDelete");

            eTimer.Enabled = true;
        }

        private void WriteToLog(string sMessage)
        {
            System.IO.StreamWriter ePortFile;
            string sLogFolder = System.Configuration.ConfigurationManager.AppSettings.Get("ePortLogFolder");
            string sLogFile = sLogFolder + "ePortPrinter_" + DateTime.Now.ToString("yyyyMM") + ".log";

            ePortFile = new StreamWriter(new FileStream(sLogFile, System.IO.FileMode.Append));
            ePortFile.WriteLine(sMessage);
            ePortFile.Flush();
            ePortFile.Close();
        }

        private void SyncOracle(string sSyncEntity, string sSyncTable, string sSyncKeyName, string sSyncKeyType, int iSyncKeyLength, string sSyncSPChange, string sSyncSPDelete)
        {
            try
            {
                // Get Mail details
                string sSMTPServer = System.Configuration.ConfigurationManager.AppSettings.Get("ePortSMTPServer");
                string sEmailFrom = System.Configuration.ConfigurationManager.AppSettings.Get("ePortEmailFrom");
                string sEmailToAdmin = System.Configuration.ConfigurationManager.AppSettings.Get("ePortEmailToAdmin");

                // Get database connection string
                string sConnectionString = System.Configuration.ConfigurationManager.AppSettings.Get("ePortConnectionString");
                SqlConnection myConnection = new SqlConnection(sConnectionString);
                myConnection.Open();

                // Sync table
                string mySQLQuery = "SELECT * ";
                mySQLQuery = mySQLQuery + " FROM " + sSyncTable;
                mySQLQuery = mySQLQuery + " WHERE NextRetryDateTime < '" + DateTime.Now.ToString() + "'";
                mySQLQuery = mySQLQuery + " AND RetryCount < 20";
                mySQLQuery = mySQLQuery + " ORDER BY SyncId";
                SqlCommand myCommand = new SqlCommand(mySQLQuery, myConnection);
                SqlDataAdapter daSync = new SqlDataAdapter(myCommand);
                DataSet dsSync = new DataSet();
                daSync.Fill(dsSync);

                // Read the data
                for (int i = 0; i < dsSync.Tables[0].Rows.Count; i++)
                {
                    // Get all the Request queue fields
                    Int32 iSyncId = 0;
                    if (dsSync.Tables[0].Rows[i]["SyncId"] != DBNull.Value)
                    {
                        iSyncId = (Int32)dsSync.Tables[0].Rows[i]["SyncId"];
                    }
                    string sKey = "";
                    int iKey = 0;
                    string sKeyValue = "";
                    if (sSyncKeyType == "STRING")
                    {
                        if (dsSync.Tables[0].Rows[i][sSyncKeyName] != DBNull.Value)
                        {
                            sKey = dsSync.Tables[0].Rows[i][sSyncKeyName].ToString().Trim();
                            sKeyValue = sKey;
                        }
                    }
                    else
                    {
                        if (dsSync.Tables[0].Rows[i][sSyncKeyName] != DBNull.Value)
                        {
                            if (iSyncKeyLength == 16)
                            {
                                iKey = (Int16)dsSync.Tables[0].Rows[i][sSyncKeyName];
                            }
                            else
                            {
                                iKey = (Int32)dsSync.Tables[0].Rows[i][sSyncKeyName];
                            }
                            sKeyValue = iKey.ToString();
                        }
                    }
                    DateTime dtNextRetryDateTime = DateTime.Now;
                    if (dsSync.Tables[0].Rows[i]["NextRetryDateTime"] != DBNull.Value)
                    {
                        dtNextRetryDateTime = (DateTime)dsSync.Tables[0].Rows[i]["NextRetryDateTime"];
                    }
                    Int16 iRetryCount = 0;
                    if (dsSync.Tables[0].Rows[i]["RetryCount"] != DBNull.Value)
                    {
                        iRetryCount = (Int16)dsSync.Tables[0].Rows[i]["RetryCount"];
                    }
                    string sType = ""; // C = Changed, D = Deleted
                    if (dsSync.Tables[0].Rows[i]["Type"] != DBNull.Value)
                    {
                        sType = dsSync.Tables[0].Rows[i]["Type"].ToString().Trim();
                    }

                    // Carry out the requested activity
                    string sStoredProcName = "";
                    switch (sType.ToUpper())
                    {
                        case "C": // Changed
                            {
                                sStoredProcName = sSyncSPChange;
                                break;
                            }

                        case "D": // Deleted
                            {
                                sStoredProcName = sSyncSPDelete;
                                break;
                            }
                    }

                    try
                    {
                        // Call Stored Proc to Sync the entity
                        string mySQLQuery1 = sStoredProcName;
                        SqlCommand myCommand1 = new SqlCommand(mySQLQuery1, myConnection);
                        myCommand1.CommandType = CommandType.StoredProcedure;
                        if (sSyncKeyType == "STRING")
                        {
                            myCommand1.Parameters.Add(new SqlParameter("@p_" + sSyncKeyName, SqlDbType.NChar, iSyncKeyLength));
                            myCommand1.Parameters["@p_" + sSyncKeyName].Value = sKey;
                        }
                        else
                        {
                            myCommand1.Parameters.Add(new SqlParameter("@p_" + sSyncKeyName, SqlDbType.Int));
                            myCommand1.Parameters["@p_" + sSyncKeyName].Value = iKey;
                        }
                        myCommand1.ExecuteNonQuery();

                        // Delete the record from sync table if successful, else it will go to catch block
                        string mySQLQuery2 = "DELETE FROM " + sSyncTable + " WHERE SyncId = " + iSyncId;
                        SqlCommand myCommand2 = new SqlCommand(mySQLQuery2, myConnection);
                        myCommand2.CommandType = CommandType.Text;
                        myCommand2.ExecuteNonQuery();

                        // Send an email if successful after retries
                        if (iRetryCount > 0)
                        {
                            System.Net.Mail.MailMessage mail = new System.Net.Mail.MailMessage();
                            mail.From = new System.Net.Mail.MailAddress(sEmailFrom);
                            mail.To.Add(sEmailToAdmin);
                            mail.Subject = sSyncEntity + " SYNC SUCCESS: " + sSyncKeyName + " = " + sKeyValue + " AT: " + DateTime.Now.ToString();
                            mail.Body = sSyncEntity + " was successfully syncronized from SQL Server to Oracle. " + sSyncKeyName + ": " + sKeyValue;
                            System.Net.Mail.SmtpClient smtp = new System.Net.Mail.SmtpClient(sSMTPServer);
                            smtp.Send(mail);
                        }
                    }
                    catch (SqlException ex)
                    {
                        WriteToLog(sSyncEntity + " SYNC ERROR: " + sSyncKeyName + " = " + sKeyValue + " AT: " + DateTime.Now.ToString() + " --- " + ex.Message);
                        if (iRetryCount == 0)
                        {
                            // If this is first failure, send email to alert admins
                            System.Net.Mail.MailMessage mail = new System.Net.Mail.MailMessage();
                            mail.From = new System.Net.Mail.MailAddress(sEmailFrom);
                            mail.To.Add(sEmailToAdmin);
                            mail.Subject = sSyncEntity + " SYNC ERROR: " + sSyncKeyName + " = " + sKeyValue + " AT: " + DateTime.Now.ToString();
                            mail.Body = sSyncEntity + " could not be syncronized from SQL Server to Oracle. " + sSyncKeyName + ": " + sKeyValue + " --- " + ex.Message;
                            System.Net.Mail.SmtpClient smtp = new System.Net.Mail.SmtpClient(sSMTPServer);
                            smtp.Send(mail);
                        }

                        try
                        {
                            // Recover the db connection
                            myConnection.Close();
                            myConnection.Open();

                            // Retry 20 times before giveing up
                            iRetryCount++;
                            if (iRetryCount == 20)
                            {
                                WriteToLog(sSyncEntity + " SYNC FAILED AFTER 20 RETRIES: " + sSyncKeyName + " = " + sKeyValue + " AT: " + DateTime.Now.ToString() + " --- " + ex.Message);
                                System.Net.Mail.MailMessage mail = new System.Net.Mail.MailMessage();
                                mail.From = new System.Net.Mail.MailAddress(sEmailFrom);
                                mail.To.Add(sEmailToAdmin);
                                mail.Subject = sSyncEntity + " FINAL SYNC ERROR: " + sSyncKeyName + " = " + sKeyValue + " AT: " + DateTime.Now.ToString();
                                mail.Body = sSyncEntity + " could not be syncronized after 20 retries from SQL Server to Oracle. " + sSyncKeyName + ": " + sKeyValue + " --- " + ex.Message;
                                System.Net.Mail.SmtpClient smtp = new System.Net.Mail.SmtpClient(sSMTPServer);
                                smtp.Send(mail);

                                // Delete record from the database
                                string mySQLQuery3 = "DELETE FROM " + sSyncTable + " WHERE SyncId = " + iSyncId;
                                SqlCommand myCommand3 = new SqlCommand(mySQLQuery3, myConnection);
                                myCommand3.CommandType = CommandType.Text;
                                myCommand3.ExecuteNonQuery();
                            }
                            else
                            {
                                // Update record to retry later
                                dtNextRetryDateTime = dtNextRetryDateTime.AddMinutes(iRetryCount * 5);
                                string mySQLQuery4 = "UPDATE " + sSyncTable + " SET ";
                                mySQLQuery4 = mySQLQuery4 + " NextRetryDateTime = '" + dtNextRetryDateTime.ToString() + "'";
                                mySQLQuery4 = mySQLQuery4 + " , RetryCount = " + iRetryCount;
                                mySQLQuery4 = mySQLQuery4 + " WHERE SyncId = " + iSyncId;
                                SqlCommand myCommand4 = new SqlCommand(mySQLQuery4, myConnection);
                                myCommand4.CommandType = CommandType.Text;
                                myCommand4.ExecuteNonQuery();
                            }
                        }
                        catch (SqlException ex1)
                        {
                            WriteToLog(sSyncEntity + "DATABASE ERROR: " + sSyncKeyName + " = " + sKeyValue + " AT: " + DateTime.Now.ToString() + " --- " + ex1.Message);
                        }
                    }
                }

                // Close the reader to release the record
                daSync.Dispose();

                // Close the connection
                myConnection.Close();
            }
            catch (Exception theException)
            {
                String errorMessage;
                errorMessage = "Error: ";
                errorMessage = String.Concat(errorMessage, theException.Message);
                errorMessage = String.Concat(errorMessage, " Line: ");
                errorMessage = String.Concat(errorMessage, theException.Source);
                WriteToLog("SyncOracle Error: " + errorMessage);
            }
        }

        private void ReadRequestQueue()
        {
            try
            {
                // Get database connection string
                string sConnectionString = System.Configuration.ConfigurationManager.AppSettings.Get("ePortConnectionString");
                SqlConnection myConnection = new SqlConnection(sConnectionString);
                myConnection.Open();

                // Print one file every time so that we get them spaced out for the printer to accept the data
                string mySQLQuery = "SELECT RequestId, Copies, EmailType, FileName, OrderNum, RequestType, UserId";
                mySQLQuery = mySQLQuery + " FROM dbo.DC_RequestQueue";
                mySQLQuery = mySQLQuery + " WHERE RequestId = (SELECT MIN(RequestId) FROM dbo.DC_RequestQueue)";
                SqlCommand myCommand = new SqlCommand(mySQLQuery, myConnection);
                SqlDataAdapter daRequestQueue = new SqlDataAdapter(myCommand);
                DataSet dsRequestQueue = new DataSet();
                daRequestQueue.Fill(dsRequestQueue);

                // Read the data
                if (dsRequestQueue.Tables[0].Rows.Count > 0)
                {
                    // Get all the Request queue fields
                    Int32 iRequestId = (Int32)dsRequestQueue.Tables[0].Rows[0]["RequestId"];
                    Int16 iCopies = 0;
                    if (dsRequestQueue.Tables[0].Rows[0]["Copies"] != DBNull.Value)
                    {
                        iCopies = (Int16)dsRequestQueue.Tables[0].Rows[0]["Copies"];
                    }
                    string sEmailType = ""; // ORDERENTRY, ORDERCOMPLETE, ORDERALERT, ORDERCUSTOMS
                    if (dsRequestQueue.Tables[0].Rows[0]["EmailType"] != DBNull.Value)
                    {
                        sEmailType = dsRequestQueue.Tables[0].Rows[0]["EmailType"].ToString().Trim();
                    }
                    string sFileName = "";
                    if (dsRequestQueue.Tables[0].Rows[0]["FileName"] != DBNull.Value)
                    {
                        sFileName = dsRequestQueue.Tables[0].Rows[0]["FileName"].ToString().Trim();
                    }
                    string sOrderNum = "";
                    if (dsRequestQueue.Tables[0].Rows[0]["OrderNum"] != DBNull.Value)
                    {
                        sOrderNum = dsRequestQueue.Tables[0].Rows[0]["OrderNum"].ToString().Trim();
                    }
                    string sRequestType = ""; // CREATE, EMAIL, PRINT
                    if (dsRequestQueue.Tables[0].Rows[0]["RequestType"] != DBNull.Value)
                    {
                        sRequestType = dsRequestQueue.Tables[0].Rows[0]["RequestType"].ToString().Trim();
                    }
                    string sUserId = "";
                    if (dsRequestQueue.Tables[0].Rows[0]["UserId"] != DBNull.Value)
                    {
                        sUserId = dsRequestQueue.Tables[0].Rows[0]["UserId"].ToString().Trim();
                    }

                    // Carry out the requested activity
                    switch (sRequestType.ToUpper())
                    {
                        case "CREATE":
                            {
                                WriteToLog("CREATE: " + sUserId + " ORDER: " + sOrderNum + " FILE: " + sFileName + " AT: " + DateTime.Now.ToString());
                                CreateExcelForms(sFileName, sOrderNum);
                                break;
                            }

                        case "EMAIL":
                            {
                                WriteToLog("EMAIL : " + sUserId + " ORDER: " + sOrderNum + " EMAIL TYPE: " + sEmailType + " AT: " + DateTime.Now.ToString());
                                SendEmail(sEmailType, sOrderNum);
                                break;
                            }

                        case "PRINT":
                            {
                                // Get Order Header from database
                                string mySQLQuery2 = "SELECT * FROM dbo.DC_User";
                                mySQLQuery2 = mySQLQuery2 + " WHERE UserId = '" + sUserId + "'";
                                SqlCommand myCommand2 = new SqlCommand(mySQLQuery2, myConnection);
                                SqlDataAdapter daUser = new SqlDataAdapter(myCommand2);
                                DataSet dsUser = new DataSet();
                                daUser.Fill(dsUser);
                                string sPrinter = "";
                                if (dsUser.Tables[0].Rows[0]["Printer"] != System.DBNull.Value)
                                {
                                    sPrinter = dsUser.Tables[0].Rows[0]["Printer"].ToString().Trim();
                                }
                                WriteToLog("PRINT : " + sUserId + " ORDER: " + sOrderNum + " FILE: " + sFileName + " PRINTER: " + sPrinter + " AT: " + DateTime.Now.ToString());
                                if ((sPrinter.ToUpper().Trim() != "") && (sPrinter.ToUpper().Trim() != "NONE"))
                                {
                                    PrintExcelFile(sFileName, sPrinter, iCopies);
                                }
                                break;
                            }
                    }

                    // Delete record from the database
                    string mySQLQuery1 = "DELETE FROM dbo.DC_RequestQueue WHERE RequestId = " + iRequestId;
                    SqlCommand myCommand1 = new SqlCommand(mySQLQuery1, myConnection);
                    myCommand1.CommandType = CommandType.Text;
                    myCommand1.ExecuteNonQuery();
                }

                // Close the reader to release the record
                daRequestQueue.Dispose();

                // Close the connection
                myConnection.Close();
            }
            catch (Exception theException)
            {
                String errorMessage;
                errorMessage = "Error: ";
                errorMessage = String.Concat(errorMessage, theException.Message);
                errorMessage = String.Concat(errorMessage, " Line: ");
                errorMessage = String.Concat(errorMessage, theException.Source);
                WriteToLog("ReadRequestQueue Error: " + errorMessage);
            }
        }

        private void PrintExcelFile(string sFile, string sPrinter, Int32 iCopies)
        {
            try
            {
                // Clean up any other excel objects around from before
                GC.Collect();

                // Create Excel application instance
                Microsoft.Office.Interop.Excel.Application oApp = new Microsoft.Office.Interop.Excel.Application();
                oApp.Visible = false;
                oApp.UserControl = false;

                // Open workbook
                Microsoft.Office.Interop.Excel._Workbook oBook = oApp.Workbooks.Open(sFile,
                    0, true, 5, "", "", true,
                    Microsoft.Office.Interop.Excel.XlPlatform.xlWindows, "\t", false, false, 0, true, false,
                    Microsoft.Office.Interop.Excel.XlCorruptLoad.xlNormalLoad);

                Microsoft.Office.Interop.Excel._Worksheet oSheet = (Microsoft.Office.Interop.Excel._Worksheet)oBook.ActiveSheet;

                // Suppress any alerts
                oApp.AlertBeforeOverwriting = false;
                oApp.DisplayAlerts = false;

                // Print file, get printer name from web.config
                if (sPrinter.ToUpper().Trim() == "DEFAULT")
                {
                    sPrinter = "Microsoft Office Document Image Writer";
                    oSheet.PrintOut(1, 1, iCopies, false, sPrinter, true, false, "PrintExcel.mdi");
                }
                else
                {
                    oSheet.PrintOut(1, 1, iCopies, false, sPrinter, false, false, "");
                }

                // Need to clean up and extinguish all excel references
                oBook.Close(null, null, null);
                oApp.Workbooks.Close();
                oApp.Quit();
                System.Runtime.InteropServices.Marshal.ReleaseComObject(oSheet);
                System.Runtime.InteropServices.Marshal.ReleaseComObject(oBook);
                System.Runtime.InteropServices.Marshal.ReleaseComObject(oApp);
                oSheet = null;
                oBook = null;
                oApp = null;

                // Force final cleanup
                GC.Collect();
            }
            catch (Exception theException)
            {
                String errorMessage;
                errorMessage = "Error: ";
                errorMessage = String.Concat(errorMessage, theException.Message);
                errorMessage = String.Concat(errorMessage, " Line: ");
                errorMessage = String.Concat(errorMessage, theException.Source);
                WriteToLog("PrintExcelFile Error: " + errorMessage);
            }
        }

        private void SendEmail(string sEmailType, string sOrderNum)
        {
            // Get database connection string
            string sConnectionString = System.Configuration.ConfigurationManager.AppSettings.Get("ePortConnectionString");
            Int16 iCustomerId = 0;
            Int16 iVesselId = 0;
            string sVesselName = "";

            // Print one file every time so that we get them spaced out for the printer to accept the data
            string mySQLQuery = @"SELECT O.CustomerId, O.VesselId, V.VesselName";
            mySQLQuery = mySQLQuery + " FROM dbo.DC_Order O, dbo.DC_Vessel V";
            mySQLQuery = mySQLQuery + " WHERE O.OrderNum = '" + sOrderNum + "'";
            mySQLQuery = mySQLQuery + " AND O.VesselId = V.VesselId";
            SqlConnection myConnection = new SqlConnection(sConnectionString);
            SqlCommand myCommand = new SqlCommand(mySQLQuery, myConnection);
            myConnection.Open();
            SqlDataReader readerOrder;
            readerOrder = myCommand.ExecuteReader();

            // Read the data
            if (readerOrder.Read())
            {
                iCustomerId = readerOrder.GetInt16(0);
                iVesselId = readerOrder.GetInt16(1);
                sVesselName = readerOrder.GetString(2).ToString().Trim();
            }

            // Get configuration settings
            string sSMTPServer = System.Configuration.ConfigurationManager.AppSettings.Get("ePortSMTPServer");
            string sEmailFrom = System.Configuration.ConfigurationManager.AppSettings.Get("ePortEmailFrom");
            string sEmailToDC = System.Configuration.ConfigurationManager.AppSettings.Get("ePortEmailToDC");
            string sEmailToDC440 = System.Configuration.ConfigurationManager.AppSettings.Get("ePortEmailToDC440");
            string sEmailToOrderAlert = System.Configuration.ConfigurationManager.AppSettings.Get("ePortEmailToOrderAlert");
            string sEmailToCustoms = System.Configuration.ConfigurationManager.AppSettings.Get("ePortEmailToCustoms");

            string sDocumentFolder = System.Configuration.ConfigurationManager.AppSettings.Get("ePortFormDocumentsFolder");
            string sFilePrefix = sDocumentFolder + iVesselId.ToString() + "-" + sVesselName + "\\" + sOrderNum;

            try
            {
                System.Net.Mail.MailMessage mail = new System.Net.Mail.MailMessage();
                mail.From = new System.Net.Mail.MailAddress(sEmailFrom);

                switch (sEmailType.ToUpper())
                {
                    case "ORDERENTRY":
                        {
                            if (iCustomerId == 440)
                            {
                                mail.To.Add(sEmailToDC440);
                            }
                            else
                            {
                                mail.To.Add(sEmailToDC);
                            }
                            mail.Subject = "Order: " + sOrderNum + " : Order Entry Confirmation " + DateTime.Now.ToShortDateString() + " " + DateTime.Now.ToShortTimeString();
                            mail.Body = "Thank you for your order request. Order No. : " + sOrderNum;
                            if (File.Exists(sFilePrefix + "_Form1_OrderEntry.xls"))
                            {
                                mail.Attachments.Add(new System.Net.Mail.Attachment(sFilePrefix + "_Form1_OrderEntry.xls"));
                            }
                            break;
                        }

                    case "ORDERCOMPLETE":
                        {
                            if (iCustomerId == 440)
                            {
                                mail.To.Add(sEmailToDC440);
                            }
                            else
                            {
                                mail.To.Add(sEmailToDC);
                            }

                            // Get Customs Broker Email Ids and add to subject
                            string mySQLQuery1 = @"SELECT B.Email1, B.Email2, B.Email3, B.Email4, B.Email5";
                            mySQLQuery1 = mySQLQuery1 + " FROM dbo.DC_CustomsBrokerOffice B, dbo.DC_Consignee C, dbo.DC_Order O";
                            mySQLQuery1 = mySQLQuery1 + " WHERE B.CustomsBrokerOfficeId = C.CustomsBrokerOfficeId";
                            mySQLQuery1 = mySQLQuery1 + " AND C.ConsigneeId = O.ConsigneeId";
                            mySQLQuery1 = mySQLQuery1 + " AND O.OrderNum = '" + sOrderNum + "'";
                            SqlConnection myConnection1 = new SqlConnection(sConnectionString);
                            SqlCommand myCommand1 = new SqlCommand(mySQLQuery1, myConnection1);
                            myConnection1.Open();
                            SqlDataReader readerCustomsBroker;
                            readerCustomsBroker = myCommand1.ExecuteReader();

                            // Read the data
                            string sCustomsBrokerEmailList = "";
                            if (readerCustomsBroker.Read())
                            {
                                for (int i = 0; i < 5; i++)
                                {
                                    if (!readerCustomsBroker.IsDBNull(i))
                                        sCustomsBrokerEmailList = sCustomsBrokerEmailList + readerCustomsBroker.GetString(i) + ";";
                                }
                            }
                            string sSubject = "Order: " + sOrderNum + " : Order Completed at: " + DateTime.Now.ToShortDateString() + " " + DateTime.Now.ToShortTimeString();
                            mail.Subject = sSubject;
                            string sBody = "Thank you for your order. Your order in now complete. Order No. : " + sOrderNum;
                            if (sCustomsBrokerEmailList != "")
                                sBody = sBody + "\r\nCustoms Broker(s): (" + sCustomsBrokerEmailList + ")";
                            mail.Body = sBody;
                            if (File.Exists(sFilePrefix + "_Form1_OrderEntry.xls"))
                            {
                                mail.Attachments.Add(new System.Net.Mail.Attachment(sFilePrefix + "_Form1_OrderEntry.xls"));
                            }
                            if (File.Exists(sFilePrefix + "_Form2_DeliveryOrder.xls"))
                            {
                                mail.Attachments.Add(new System.Net.Mail.Attachment(sFilePrefix + "_Form2_DeliveryOrder.xls"));
                            }
                            if (File.Exists(sFilePrefix + "_Form3_BillOfLading.xls"))
                            {
                                mail.Attachments.Add(new System.Net.Mail.Attachment(sFilePrefix + "_Form3_BillOfLading.xls"));
                            }
                            if (File.Exists(sFilePrefix + "_Form4_TallySheet.csv"))
                            {
                                mail.Attachments.Add(new System.Net.Mail.Attachment(sFilePrefix + "_Form4_TallySheet.csv"));
                            }
                            if (File.Exists(sFilePrefix + "_Form5_ConfirmationOfSale.xls"))
                            {
                                mail.Attachments.Add(new System.Net.Mail.Attachment(sFilePrefix + "_Form5_ConfirmationOfSale.xls"));
                            }
                            if (File.Exists(sFilePrefix + "_Form6_PARS.xls"))
                            {
                                mail.Attachments.Add(new System.Net.Mail.Attachment(sFilePrefix + "_Form6_PARS.xls"));
                            }
                            break;
                        }

                    case "ORDERALERT":
                        {
                            mail.To.Add(sEmailToOrderAlert);
                            mail.Subject = "Order: " + sOrderNum + " : Alert : Order has changed at: " + DateTime.Now.ToShortDateString() + " " + DateTime.Now.ToShortTimeString();
                            mail.Body = "Please verify the Pick List for the changed Order. Order No. : " + sOrderNum;
                            break;
                        }

                    case "ORDERCUSTOMS":
                        {
                            mail.To.Add(sEmailToCustoms);
                            mail.Subject = "Order: " + sOrderNum + " : Alert : Order Ready for Customs at: " + DateTime.Now.ToShortDateString() + " " + DateTime.Now.ToShortTimeString();
                            mail.Body = "Order has been submitted for Customs. Order No. : " + sOrderNum;
                            break;
                        }
                }
               
                System.Net.Mail.SmtpClient smtp = new System.Net.Mail.SmtpClient(sSMTPServer);
                smtp.Send(mail);
            }
            catch (Exception theException)
            {
                String errorMessage;
                errorMessage = "";
                errorMessage = String.Concat(errorMessage, theException.Message);
                errorMessage = String.Concat(errorMessage, " Line: ");
                errorMessage = String.Concat(errorMessage, theException.Source);
                WriteToLog("SendEmail: " + errorMessage);
            }
        }

        private void CreateExcelForms(string sForm, string sOrderNum)
        {
            try
            {
                // Get the value of the setting from web.config file
                string sDocumentFolder = System.Configuration.ConfigurationManager.AppSettings.Get("ePortFormDocumentsFolder");
                string sTemplateFolder = System.Configuration.ConfigurationManager.AppSettings.Get("ePortFormTemplatesFolder");
                string sSignatureFolder = System.Configuration.ConfigurationManager.AppSettings.Get("ePortSignatureFolder");

                // Get database connection string
                string sConnectionString = System.Configuration.ConfigurationManager.AppSettings.Get("ePortConnectionString");
                SqlConnection myConnection = new SqlConnection(sConnectionString);
                myConnection.Open();

                // Get Order Header from database
                string mySQLQuery = "SELECT * FROM dbo.DC_Order";
                mySQLQuery = mySQLQuery + " WHERE OrderNum = '" + sOrderNum + "'";
                SqlCommand myCommand = new SqlCommand(mySQLQuery, myConnection);
                SqlDataAdapter daOrder = new SqlDataAdapter(myCommand);
                DataSet dsOrder = new DataSet();
                daOrder.Fill(dsOrder);

                // Get current Order record data
                string sCustomerId = dsOrder.Tables[0].Rows[0]["CustomerId"].ToString().Trim();
                string sVesselId = dsOrder.Tables[0].Rows[0]["VesselId"].ToString().Trim();
                string sConsigneeId = dsOrder.Tables[0].Rows[0]["ConsigneeId"].ToString().Trim();
                string sCommodityCode = dsOrder.Tables[0].Rows[0]["CommodityCode"].ToString().Trim();
                string sPARSPortOfEntryNum = "";
                if (dsOrder.Tables[0].Rows[0]["PARSPortOfEntryNum"] != System.DBNull.Value)
                {
                    sPARSPortOfEntryNum = dsOrder.Tables[0].Rows[0]["PARSPortOfEntryNum"].ToString().Trim();
                }
                string sTransporterId = "";
                string sCarrierName = "";
                if (dsOrder.Tables[0].Rows[0]["TransporterId"] != System.DBNull.Value)
                {
                    sTransporterId = dsOrder.Tables[0].Rows[0]["TransporterId"].ToString().Trim();
                }
                
                // Get vessel details from database
                string mySQLQuery1 = "SELECT * FROM dbo.DC_Vessel";
                mySQLQuery1 = mySQLQuery1 + " WHERE VesselId = " + sVesselId;
                SqlCommand myCommand1 = new SqlCommand(mySQLQuery1, myConnection);
                SqlDataAdapter daVessel = new SqlDataAdapter(myCommand1);
                DataSet dsVessel = new DataSet();
                daVessel.Fill(dsVessel);

                // Get Customer details from database
                string mySQLQuery2 = "SELECT * FROM dbo.DC_Customer";
                mySQLQuery2 = mySQLQuery2 + " WHERE CustomerId = " + sCustomerId;
                SqlCommand myCommand2 = new SqlCommand(mySQLQuery2, myConnection);
                SqlDataAdapter daCustomer = new SqlDataAdapter(myCommand2);
                DataSet dsCustomer = new DataSet();
                daCustomer.Fill(dsCustomer);

                // Get Consignee details from database
                string mySQLQuery3 = "SELECT * FROM dbo.DC_Consignee";
                mySQLQuery3 = mySQLQuery3 + " WHERE ConsigneeId = " + sConsigneeId;
                SqlCommand myCommand3 = new SqlCommand(mySQLQuery3, myConnection);
                SqlDataAdapter daConsignee = new SqlDataAdapter(myCommand3);
                DataSet dsConsignee = new DataSet();
                daConsignee.Fill(dsConsignee);

                // Get Commodity details from database
                string mySQLQuery4 = "SELECT * FROM dbo.DC_Commodity";
                mySQLQuery4 = mySQLQuery4 + " WHERE CommodityCode = " + sCommodityCode;
                SqlCommand myCommand4 = new SqlCommand(mySQLQuery4, myConnection);
                SqlDataAdapter daCommodity = new SqlDataAdapter(myCommand4);
                DataSet dsCommodity = new DataSet();
                daCommodity.Fill(dsCommodity);

                // Get Transporter details from database
                if (sTransporterId != "")
                {
                    string mySQLQuery5 = "SELECT * FROM dbo.DC_Transporter";
                    mySQLQuery5 = mySQLQuery5 + " WHERE TransporterId = " + sTransporterId;
                    SqlCommand myCommand5 = new SqlCommand(mySQLQuery5, myConnection);
                    SqlDataAdapter daTransporter = new SqlDataAdapter(myCommand5);
                    DataSet dsTransporter = new DataSet();
                    daTransporter.Fill(dsTransporter);
                    if (dsTransporter.Tables[0].Rows[0]["CarrierName"] != System.DBNull.Value)
                    {
                        sCarrierName = dsTransporter.Tables[0].Rows[0]["CarrierName"].ToString().Trim();
                    }
                }

                // Set country for customer, for 440 Customer Id it's domestic US
                string sCountry = "";
                if (sCustomerId != "440")
                {
                    sCountry = "CANADA ";
                }

                // Set Vessel for folders
                string sVesselTag = sVesselId + "-" + dsVessel.Tables[0].Rows[0]["VesselName"].ToString().Trim();

                // BN - Create Form4 using an external VB program
                if (sForm == "Form4_TallySheet")
                {
                    string sCLMTallyApp = System.Configuration.ConfigurationManager.AppSettings.Get("ePortTallyApp");
                    if (!Directory.Exists(sDocumentFolder + sVesselTag))
                    {
                        Directory.CreateDirectory(sDocumentFolder + sVesselTag);
                    }
                    string sDocumentFileName = sDocumentFolder + sVesselTag + "\\" + sOrderNum + "_Form4_TallySheet.csv";
                    string sCommandArgs = "";
                    sCommandArgs = sCommandArgs + sOrderNum;
                    if (sCustomerId != "440")
                    {
                        sCommandArgs = sCommandArgs + " " + "439";
                    }
                    else
                    {
                        sCommandArgs = sCommandArgs + " " + "440";
                    }
                    sCommandArgs = sCommandArgs + " " + sVesselId;
                    sCommandArgs = sCommandArgs + " " + sDocumentFileName;
                    Process pCLMTallyApp = new Process();
                    pCLMTallyApp.StartInfo.FileName = sCLMTallyApp;
                    pCLMTallyApp.StartInfo.Arguments = sCommandArgs;
                    pCLMTallyApp.Start();
                    return;
                }

                // Clean up any other excel objects around from before
                GC.Collect();

                // Create Excel application instance
                Microsoft.Office.Interop.Excel.Application oApp = new Microsoft.Office.Interop.Excel.Application();
                oApp.Visible = false;
                oApp.UserControl = false;

                // Build the template file name
                string sTemplateFileName = sTemplateFolder + sForm + ".xls";

                // For PARS, make the file name based on customer short name
                if (sForm == "Form6_PARS")
                {
                    string sPARSFileName = sTemplateFolder + sForm + "_" + dsCustomer.Tables[0].Rows[0]["CustomerShortName"].ToString().Trim() + ".xls";
                    if (File.Exists(sPARSFileName))
                    {
                        sTemplateFileName = sPARSFileName;
                    }
                }

                // Open a template workbook
                Microsoft.Office.Interop.Excel._Workbook oBook = (Microsoft.Office.Interop.Excel._Workbook)(oApp.Workbooks.Add(sTemplateFileName));
                Microsoft.Office.Interop.Excel._Worksheet oSheet = (Microsoft.Office.Interop.Excel._Worksheet)oBook.ActiveSheet;

                switch (sForm)
                {
                    case "Form1_OrderEntry":
                        {
                            // Fill header data into an Excel sheet
                            oSheet.Cells[1, 2] = dsVessel.Tables[0].Rows[0]["VesselName"].ToString().Trim();
                            oSheet.Cells[2, 2] = dsVessel.Tables[0].Rows[0]["ArrivalDate"].ToString();
                            oSheet.Cells[3, 2] = dsCommodity.Tables[0].Rows[0]["CommodityName"].ToString().Trim();
                            oSheet.Cells[4, 2] = dsCustomer.Tables[0].Rows[0]["CustomerName"].ToString().Trim();

                            // Fill the data into an Excel row
                            int iRow = 11;
                            int iCommentsRow = 12;
                            oSheet.Cells[iRow, 1] = dsOrder.Tables[0].Rows[0]["PickUpDate"].ToString();
                            oSheet.Cells[iRow, 2] = sOrderNum;
                            oSheet.Cells[iRow, 3] = dsConsignee.Tables[0].Rows[0]["ConsigneeName"].ToString().Trim();
                            oSheet.Cells[iRow, 4] = dsOrder.Tables[0].Rows[0]["DirectOrder"].ToString().Trim();
                            oSheet.Cells[iRow, 5] = dsOrder.Tables[0].Rows[0]["CustomerPO"].ToString().Trim();
                            oSheet.Cells[iRow, 6] = dsOrder.Tables[0].Rows[0]["DeliveryDate"].ToString();

                            // Fill all the Size headers
                            string mySQLQuery11 = "SELECT * FROM dbo.DC_CommoditySize";
                            mySQLQuery11 = mySQLQuery11 + " ORDER BY SizeId";
                            SqlCommand myCommand11 = new SqlCommand(mySQLQuery11, myConnection);
                            SqlDataAdapter daCommoditySize = new SqlDataAdapter(myCommand11);
                            DataSet dsCommoditySize = new DataSet();
                            daCommoditySize.Fill(dsCommoditySize);

                            foreach (DataRow dr in dsCommoditySize.Tables[0].Rows)
                            {
                                oSheet.Cells[4, 6 + (Int16)dr["SizeId"]] = dr["Descr"].ToString().Trim();
                            }

                            // Get Order Detail records
                            string mySQLQuery12 = "SELECT * FROM dbo.DC_OrderDetail";
                            mySQLQuery12 = mySQLQuery12 + " WHERE OrderNum = '" + sOrderNum + "'";
                            mySQLQuery12 = mySQLQuery12 + " ORDER BY OrderDetailId";
                            SqlCommand myCommand12 = new SqlCommand(mySQLQuery12, myConnection);
                            SqlDataAdapter daOrderDetail = new SqlDataAdapter(myCommand12);
                            DataSet dsOrderDetail = new DataSet();
                            daOrderDetail.Fill(dsOrderDetail);

                            foreach (DataRow dr in dsOrderDetail.Tables[0].Rows)
                            {
                                oSheet.Cells[iRow, 6 + (Int16)dr["OrderSizeId"]] = dr["OrderQty"];
                                if (dr["Comments"] != DBNull.Value)
                                {
                                    oSheet.Cells[iCommentsRow, 6 + (Int16)dr["OrderSizeId"]] = dr["Comments"].ToString().Trim();
                                    iCommentsRow++;
                                }
                            }
                            break;
                        }

                    case "Form2_DeliveryOrder":
                        {
                            // Fill header data into an Excel sheet
                            oSheet.Cells[1, 9] = dsOrder.Tables[0].Rows[0]["LoadType"].ToString().Trim();
                            oSheet.Cells[2, 3] = dsCommodity.Tables[0].Rows[0]["CommodityName"].ToString().Trim() + " '" + sCommodityCode + "'";
                            if (sCustomerId != "440")
                            {
                                oSheet.Cells[4, 4] = "439";
                                oSheet.Cells[4, 5] = "Dominion Citrus";
                            }
                            else
                            {
                                oSheet.Cells[4, 4] = "440";
                                oSheet.Cells[4, 5] = "Domestic Clementines";
                                oSheet.Cells[9, 5] = "Container #";
                                oSheet.Cells[9, 9] = "Station/Pack Hse";
                            }
                            oSheet.Cells[4, 9] = sOrderNum;
                            oSheet.Cells[6, 4] = sVesselId + " '" + dsVessel.Tables[0].Rows[0]["VesselName"].ToString().Trim() + "'";
                            oSheet.Cells[6, 9] = dsOrder.Tables[0].Rows[0]["PickUpDate"].ToString();

                            // Get Order Detail records
                            string mySQLQuery21 = "SELECT * FROM dbo.DC_OrderDetail";
                            mySQLQuery21 = mySQLQuery21 + " WHERE OrderNum = '" + sOrderNum + "'";
                            mySQLQuery21 = mySQLQuery21 + " ORDER BY OrderDetailId";
                            SqlCommand myCommand21 = new SqlCommand(mySQLQuery21, myConnection);
                            SqlDataAdapter daOrderDetail = new SqlDataAdapter(myCommand21);
                            DataSet dsOrderDetail = new DataSet();
                            daOrderDetail.Fill(dsOrderDetail);

                            int iRow = 10;
                            int iRecordCount = 1;
                            foreach (DataRow dr in dsOrderDetail.Tables[0].Rows)
                            {
                                // Fill the details from order detail
                                oSheet.Cells[iRow, 1] = iRecordCount;
                                oSheet.Cells[iRow, 2] = "Req";
                                oSheet.Cells[iRow, 3] = "";
                                oSheet.Cells[iRow, 4] = dr["OrderQty"];
                                oSheet.Cells[iRow, 5] = "";
                                oSheet.Cells[iRow, 7] = dr["SizeLow"] + "-" + dr["SizeHigh"] + "/" + dr["WeightKG"];
                                oSheet.Cells[iRow, 9] = "";
                                oSheet.Cells[iRow, 10] = "DC";
                                iRow++;
                                iRecordCount++;

                                if (sCustomerId != "440")
                                {
                                    // Get Order Picklist records
                                    string mySQLQuery22 = "SELECT * FROM dbo.DC_PickList";
                                    mySQLQuery22 = mySQLQuery22 + " WHERE OrderNum = '" + sOrderNum + "'";
                                    mySQLQuery22 = mySQLQuery22 + " AND OrderDetailId = " + dr["OrderDetailId"].ToString().Trim();
                                    mySQLQuery22 = mySQLQuery22 + " ORDER BY PickListId";
                                    SqlCommand myCommand22 = new SqlCommand(mySQLQuery22, myConnection);
                                    SqlDataAdapter daPickList = new SqlDataAdapter(myCommand22);
                                    DataSet dsPickList = new DataSet();
                                    daPickList.Fill(dsPickList);

                                    // Get the records using the created where clause from the detail table for the order number
                                    foreach (DataRow drPickList in dsPickList.Tables[0].Rows)
                                    {
                                        // Fill the details for picklist
                                        oSheet.Cells[iRow, 1] = "";
                                        oSheet.Cells[iRow, 2] = "Act";
                                        oSheet.Cells[iRow, 3] = drPickList["PalletQty"];
                                        oSheet.Cells[iRow, 4] = "";
                                        oSheet.Cells[iRow, 5] = drPickList["PackHouseId"];
                                        oSheet.Cells[iRow, 8] = "";
                                        oSheet.Cells[iRow, 9] = drPickList["PalletLocation"];
                                        oSheet.Cells[iRow, 10] = "DSPC";
                                        iRow++;
                                    }
                                }

                            }
                            break;
                        }

                    case "Form3_BillOfLading":
                        {
                            // Fill Order header data
                            oSheet.Cells[7, 8] = dsOrder.Tables[0].Rows[0]["OrderNum"].ToString().Trim();
                            oSheet.Cells[8, 9] = dsOrder.Tables[0].Rows[0]["SNMGNum"].ToString().Trim();
                            oSheet.Cells[9, 8] = dsOrder.Tables[0].Rows[0]["CustomerPO"].ToString().Trim();
                            oSheet.Cells[8, 2] = dsOrder.Tables[0].Rows[0]["TruckTag"].ToString().Trim();
                            oSheet.Cells[10, 2] = dsOrder.Tables[0].Rows[0]["TrailerNum"].ToString().Trim();
                            oSheet.Cells[12, 9] = dsOrder.Tables[0].Rows[0]["DeliveryDate"].ToString();
                            oSheet.Cells[12, 4] = sCarrierName;
                            oSheet.Cells[32, 2] = dsOrder.Tables[0].Rows[0]["TotalCount"].ToString();
                            oSheet.Cells[32, 4] = dsCommodity.Tables[0].Rows[0]["CommodityName"].ToString().Trim();
                            oSheet.Cells[32, 6] = dsOrder.Tables[0].Rows[0]["TotalQuantityKG"].ToString();
                            oSheet.Cells[32, 8] = dsOrder.Tables[0].Rows[0]["TotalPalletCount"].ToString();
                            oSheet.Cells[34, 2] = dsOrder.Tables[0].Rows[0]["ZUser1"].ToString().Trim();
                            oSheet.Cells[36, 4] = dsOrder.Tables[0].Rows[0]["TotalBoxDamaged"].ToString();
                            oSheet.Cells[38, 7] = dsOrder.Tables[0].Rows[0]["ZUser2"].ToString().Trim();
                            oSheet.Cells[46, 8] = sCarrierName;

                            // Customer info
                            oSheet.Cells[21, 9] = dsCustomer.Tables[0].Rows[0]["CustomerName"].ToString().Trim();
                            oSheet.Cells[22, 9] = dsCustomer.Tables[0].Rows[0]["Address"].ToString().Trim();
                            oSheet.Cells[23, 9] = dsCustomer.Tables[0].Rows[0]["City"].ToString().Trim() + " " + dsCustomer.Tables[0].Rows[0]["StateProvince"].ToString().Trim();
                            oSheet.Cells[24, 9] = sCountry + dsCustomer.Tables[0].Rows[0]["PostalCode"].ToString().Trim();

                            // Consignee details
                            oSheet.Cells[20, 1] = dsConsignee.Tables[0].Rows[0]["ConsigneeName"].ToString().Trim();
                            oSheet.Cells[21, 1] = dsConsignee.Tables[0].Rows[0]["Address"].ToString().Trim();
                            oSheet.Cells[22, 1] = dsConsignee.Tables[0].Rows[0]["City"].ToString().Trim() + " " + dsConsignee.Tables[0].Rows[0]["StateProvince"].ToString().Trim() + " " + sCountry;
                            oSheet.Cells[23, 1] = dsConsignee.Tables[0].Rows[0]["Phone"].ToString().Trim();

                            // Blank out customs information for domestic customer
                            if (sCustomerId == "440")
                            {
                                oSheet.Cells[2, 1] = "0";
                                oSheet.Cells[39, 1] = "";
                                oSheet.Cells[42, 1] = "";
                                oSheet.Cells[43, 1] = "";
                                oSheet.Cells[44, 1] = "SEAL #:";
                                oSheet.Cells[44, 2] = dsOrder.Tables[0].Rows[0]["SealNum"].ToString().Trim();
                                oSheet.Cells[45, 1] = "";
                                oSheet.Cells[45, 2] = "";
                                oSheet.Cells[46, 1] = "";
                                oSheet.Cells[46, 2] = "";
                                oSheet.Cells[49, 1] = "";
                                oSheet.Cells[50, 1] = "";
                                oSheet.Cells[51, 1] = "";
                                oSheet.Cells[52, 1] = "";
                                oSheet.Cells[53, 1] = "";
                                oSheet.Cells[54, 1] = "";
                            }
                            else
                            {
                                // Fill Customs Broker Office details
                                string mySQLQuery31 = "SELECT * FROM dbo.DC_CustomsBrokerOffice";
                                mySQLQuery31 = mySQLQuery31 + " WHERE CustomsBrokerOfficeId = " + dsConsignee.Tables[0].Rows[0]["CustomsBrokerOfficeId"].ToString().Trim();
                                SqlCommand myCommand31 = new SqlCommand(mySQLQuery31, myConnection);
                                SqlDataAdapter daCustomsBrokerOffice = new SqlDataAdapter(myCommand31);
                                DataSet dsCustomsBrokerOffice = new DataSet();
                                daCustomsBrokerOffice.Fill(dsCustomsBrokerOffice);

                                oSheet.Cells[2, 1] = dsCustomsBrokerOffice.Tables[0].Rows[0]["CustomsBrokerOfficeId"].ToString().Trim();
                                oSheet.Cells[39, 5] = dsCustomsBrokerOffice.Tables[0].Rows[0]["BorderCrossing"].ToString().Trim();
                                oSheet.Cells[43, 1] = dsCustomsBrokerOffice.Tables[0].Rows[0]["CustomsBroker"].ToString().Trim();
                                oSheet.Cells[44, 2] = dsCustomsBrokerOffice.Tables[0].Rows[0]["ContactName"].ToString().Trim();
                                oSheet.Cells[45, 2] = dsCustomsBrokerOffice.Tables[0].Rows[0]["Phone"].ToString().Trim();
                                oSheet.Cells[46, 2] = dsCustomsBrokerOffice.Tables[0].Rows[0]["Fax"].ToString().Trim();
                            }

                            // Get signature file name
                            string sSignatureFile = sDocumentFolder + sVesselTag + "\\" + sOrderNum + "_Signature.bmp";

                            // Get signature file from web, because file can only be saved in website root folder
                            string sSignatureFileWeb = sSignatureFolder + sVesselTag + "\\" + sOrderNum + "_Signature.bmp";

                            // Check if web signature file exists, if so copy to documents folder
                            if (File.Exists(sSignatureFileWeb))
                            {
                                File.Copy(sSignatureFileWeb, sSignatureFile, true);
                            }

                            // See if signature file exists if so, add to excel file
                            if (!File.Exists(sSignatureFile))
                            {
                                sSignatureFile = System.AppDomain.CurrentDomain.BaseDirectory + "\\SignatureBlank.bmp";
                                if (!File.Exists(sSignatureFile))
                                {
                                    sSignatureFile = "";
                                }
                            }

                            if (sSignatureFile != "")
                            {
                                oSheet.Shapes.AddPicture(sSignatureFile,
                                    Microsoft.Office.Core.MsoTriState.msoFalse,
                                    Microsoft.Office.Core.MsoTriState.msoTrue,
                                    291, 651, 160, 21);
                            }
                            break;
                        }

                    case "Form5_ConfirmationOfSale":
                        {
                            // Fill header data into an Excel sheet
                            oSheet.Cells[19, 1] = sOrderNum; // Column 5c
                            oSheet.Cells[45, 3] = sOrderNum; // Column 19
                            oSheet.Cells[15, 1] = sCarrierName; // Column 5a
                            oSheet.Cells[18, 7] = dsOrder.Tables[0].Rows[0]["PickUpDate"].ToString().Trim(); // Column 6a
                            oSheet.Cells[18, 9] = dsOrder.Tables[0].Rows[0]["PickUpDate"].ToString().Trim(); // Column 6b
                            oSheet.Cells[22, 1] = "Ocean Shipment - " + dsVessel.Tables[0].Rows[0]["VesselName"].ToString().Trim(); // Column 5d
                            oSheet.Cells[45, 1] = dsOrder.Tables[0].Rows[0]["CustomerPO"].ToString().Trim();
                            oSheet.Cells[45, 7] = dsOrder.Tables[0].Rows[0]["TotalQuantityKG"].ToString().Trim(); // Column 20
                            DateTime dtDeliveryDate = (DateTime)dsOrder.Tables[0].Rows[0]["DeliveryDate"];
                            if (dsCustomer.Tables[0].Rows[0]["DestCode"].ToString().Trim() == "")
                            {
                                oSheet.Cells[47, 1] = "";
                            }                            
                            else
                            {
                                oSheet.Cells[47, 1] = "      DRC#:  " + dsCustomer.Tables[0].Rows[0]["DestCode"].ToString().Trim();
                            }
                            if (dsOrder.Tables[0].Rows[0]["ZUser2"].ToString().Trim() == "")
                            {
                                oSheet.Cells[48, 2] = "";
                                oSheet.Cells[48, 4] = "";
                                oSheet.Cells[48, 7] = "";
                            }
                            else
                            {
                                oSheet.Cells[48, 4] = dsOrder.Tables[0].Rows[0]["ZUser2"].ToString().Trim();
                            }
                            oSheet.Cells[51, 3] = dtDeliveryDate.Year; // Column 23
                            oSheet.Cells[51, 4] = dtDeliveryDate.Month; // Column 23
                            oSheet.Cells[51, 6] = dtDeliveryDate.Day; // Column 23
                            
                            // Customer details
                            oSheet.Cells[4, 7] = dsCustomer.Tables[0].Rows[0]["CustomerName"].ToString().Trim(); // Column 2
                            oSheet.Cells[60, 2] = dsCustomer.Tables[0].Rows[0]["CustomerName"].ToString().Trim(); // Column 26
                            oSheet.Cells[5, 7] = dsCustomer.Tables[0].Rows[0]["Address"].ToString().Trim();
                            oSheet.Cells[6, 7] = dsCustomer.Tables[0].Rows[0]["City"].ToString().Trim() + ", " + dsCustomer.Tables[0].Rows[0]["StateProvince"].ToString().Trim();
                            oSheet.Cells[7, 7] = sCountry + dsCustomer.Tables[0].Rows[0]["PostalCode"].ToString().Trim();
                            oSheet.Cells[14, 7] = dsCustomer.Tables[0].Rows[0]["Origin"].ToString().Trim(); // Column 5b

                            // Get fixed freight
                            decimal dFixedFreight = 0.00m;
                            dFixedFreight += (decimal)dsVessel.Tables[0].Rows[0]["FixedFreight"];
                            if ( (dsCustomer.Tables[0].Rows[0]["Origin"].ToString().Trim() == "MOROCCO") || (sCustomerId == "440") )
                            {
                                oSheet.Cells[18, 7] = dsVessel.Tables[0].Rows[0]["ArrivalDate"].ToString(); // Column 6a
                                if (dsOrder.Tables[0].Rows[0]["TransportCharges"] != System.DBNull.Value)
                                {
                                    oSheet.Cells[53, 8] = (decimal)dsOrder.Tables[0].Rows[0]["TransportCharges"] + dFixedFreight; // Column 24
                                }
                                else
                                {
                                    oSheet.Cells[53, 8] = dFixedFreight;
                                }
                            }
                            else
                            {
                                oSheet.Cells[18, 7] = dsOrder.Tables[0].Rows[0]["PickUpDate"].ToString(); // Column 6a
                                oSheet.Cells[53, 8] = dFixedFreight; // Column 24
                                if (dsOrder.Tables[0].Rows[0]["TransportCharges"] != System.DBNull.Value)
                                {
                                    oSheet.Cells[55, 8] = (decimal)dsOrder.Tables[0].Rows[0]["TransportCharges"]; // Column 25
                                }
                                else
                                {
                                    oSheet.Cells[55, 8] = 0; // Column 25
                                }
                            }

                            // Consignee details
                            oSheet.Cells[9, 7] = dsConsignee.Tables[0].Rows[0]["ConsigneeName"].ToString().Trim(); // Column 4
                            oSheet.Cells[10, 7] = dsConsignee.Tables[0].Rows[0]["Address"].ToString().Trim();
                            oSheet.Cells[11, 7] = dsConsignee.Tables[0].Rows[0]["City"].ToString().Trim() + ", " + dsConsignee.Tables[0].Rows[0]["StateProvince"].ToString().Trim();
                            oSheet.Cells[12, 7] = sCountry + dsConsignee.Tables[0].Rows[0]["PostalCode"].ToString().Trim();

                            // Get Order Detail records
                            string mySQLQuery51 = "SELECT * FROM dbo.DC_OrderDetail";
                            mySQLQuery51 = mySQLQuery51 + " WHERE OrderNum = '" + sOrderNum + "'";
                            mySQLQuery51 = mySQLQuery51 + " ORDER BY OrderDetailId";
                            SqlCommand myCommand51 = new SqlCommand(mySQLQuery51, myConnection);
                            SqlDataAdapter daOrderDetail = new SqlDataAdapter(myCommand51);
                            DataSet dsOrderDetail = new DataSet();
                            daOrderDetail.Fill(dsOrderDetail);

                            int iRow = 30;
                            foreach (DataRow dr in dsOrderDetail.Tables[0].Rows)
                            {
                                oSheet.Cells[iRow, 1] = dsCommodity.Tables[0].Rows[0]["HarmonizedSystemTariff"].ToString(); // Column 11
                                oSheet.Cells[iRow, 2] = dsCommodity.Tables[0].Rows[0]["CommodityName"].ToString().Trim() + " - Maroc Star - " + dr["SizeLow"].ToString() + "'s"; // Column 12
                                oSheet.Cells[iRow, 6] = "Morocco"; // Column 13
                                oSheet.Cells[iRow, 7] = dr["WeightKG"].ToString() + " KG"; // Column 14
                                oSheet.Cells[iRow, 8] = dr["DeliveredQty"].ToString(); // Column 15
                                oSheet.Cells[iRow, 9] = dr["Price"].ToString(); // Column 16
                                iRow++;
                            }
                            break;
                        }

                    case "Form6_PARS":
                        {
                            // Fill header data into an Excel sheet
                            oSheet.Cells[12, 6] = sCarrierName;
                            oSheet.Cells[13, 6] = dsOrder.Tables[0].Rows[0]["DriverName"].ToString().Trim();
                            oSheet.Cells[14, 6] = dsOrder.Tables[0].Rows[0]["PARSDriverPhoneMobile"].ToString().Trim();
                            oSheet.Cells[15, 6] = dsOrder.Tables[0].Rows[0]["PARSCarrierDispatchPhone"].ToString().Trim();
                            DateTime dtPARSETABorder = (DateTime)dsOrder.Tables[0].Rows[0]["PARSETABorder"];
                            oSheet.Cells[18, 4] = dtPARSETABorder.ToString("yyyy-MM-dd");
                            oSheet.Cells[18, 10] = dtPARSETABorder.ToString("HH:MM");
                            oSheet.Cells[20, 4] = dsOrder.Tables[0].Rows[0]["TruckTag"].ToString().Trim();
                            oSheet.Cells[21, 4] = dsOrder.Tables[0].Rows[0]["TrailerNum"].ToString().Trim();
                            oSheet.Cells[22, 4] = dsOrder.Tables[0].Rows[0]["TotalCount"].ToString().Trim();
                            oSheet.Cells[23, 4] = dsOrder.Tables[0].Rows[0]["TotalPalletCount"].ToString().Trim();
                            oSheet.Cells[24, 8] = dsOrder.Tables[0].Rows[0]["PARSBarCode"].ToString().Trim();
                            oSheet.Cells[27, 2] = dsOrder.Tables[0].Rows[0]["CustomerPO"].ToString().Trim();
                            oSheet.Cells[27, 4] = sOrderNum;
                            oSheet.Cells[29, 8] = dsOrder.Tables[0].Rows[0]["PARSPortOfEntryNum"].ToString().Trim();
                            break;
                        }

                }

                // Save As a file, overwrite it if it already exists without asking
                oApp.AlertBeforeOverwriting = false;
                oApp.DisplayAlerts = false;

                //Check if vessel folder is there, if not create it
                if (!Directory.Exists(sDocumentFolder + sVesselTag))
                {
                    Directory.CreateDirectory(sDocumentFolder + sVesselTag);
                }
                string sExcelFileName = sDocumentFolder + sVesselTag + "\\" + sOrderNum + "_" + sForm + ".xls";
                oBook.SaveAs(sExcelFileName, Microsoft.Office.Interop.Excel.XlFileFormat.xlWorkbookNormal,
                    null, null, false, false, Microsoft.Office.Interop.Excel.XlSaveAsAccessMode.xlShared,
                    false, false, null, null, null);

                // Need to clean up and extinguish all excel references
                oBook.Close(null, null, null);
                oApp.Workbooks.Close();
                oApp.Quit();
                System.Runtime.InteropServices.Marshal.ReleaseComObject(oSheet);
                System.Runtime.InteropServices.Marshal.ReleaseComObject(oBook);
                System.Runtime.InteropServices.Marshal.ReleaseComObject(oApp);
                oSheet = null;
                oBook = null;
                oApp = null;
                myConnection.Close();

                // Force final cleanup
                GC.Collect();
            }
            catch (Exception theException)
            {
                String errorMessage;
                errorMessage = "Error: ";
                errorMessage = String.Concat(errorMessage, theException.Message);
                errorMessage = String.Concat(errorMessage, " Line: ");
                errorMessage = String.Concat(errorMessage, theException.Source);
                WriteToLog("CreateExcelForms: " + errorMessage);
            }
        }
    }
}
