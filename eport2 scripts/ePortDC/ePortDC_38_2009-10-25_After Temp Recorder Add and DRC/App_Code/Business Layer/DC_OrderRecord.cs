// This is a "safe" class, meaning that it is created once 
// and never overwritten. Any custom code you add to this class 
// will be preserved when you regenerate your application.
//
// Typical customizations that may be done in this class include
//  - adding custom event handlers
//  - overriding base class methods

using System;
using System.Runtime;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// Provides access to the data in a database record from a table (or view).
/// Also provides access to the <see cref="DC_OrderTable"></see> that each record is associated with.
/// </summary>
/// <remarks>
/// <para>
/// This is a "safe" class, meaning that it is generated once and never overwritten. 
/// Any changes you make to this class will be preserved when you regenerate your application.
/// </para>
/// </remarks>
/// <seealso cref="DC_OrderTable"></seealso>
[SerializableAttribute()]
public class DC_OrderRecord : BaseDC_OrderRecord
{
} // End class DC_OrderRecord

// Custom class to contain all the custom functions
public class PortCustom
{

	private string GetNewConnectionString()
	{
		// Get the name of the setting stored in web.config e.e. DatabaseePort1
		string sConnName = Business.DC_OrderRecord.TableUtils.ConnectionName;

		// Get the value of the setting from web.config file
		string sConnConfig = System.Configuration.ConfigurationManager.AppSettings.Get(sConnName);

		// Config string looks like "Provider=SQLOLEDB;Data Source=BRIJ-LENOVO;Database=ePort;Trusted_Connection=yes;User Id=;Password="
		// and this does not work with SQL Server 2005 connection, so build a new one
		// For Trusted connection: "Server=myServerAddress;Database=myDataBase;Trusted_Connection=True;"
		// For Standard security : "Server=myServerAddress;Database=myDataBase;User ID=myUsername;Password=myPassword;Trusted_Connection=False;"
		int i;
		int j;
		
		//Get Server Name
		i = sConnConfig.IndexOf("Data Source", 1) + 12;
		j = sConnConfig.IndexOf(";", i);
		string sSQLServerName = sConnConfig.Substring(i, j - i);

		// Get Dababase Name
		i = sConnConfig.IndexOf("Database", 1) + 9;
		j = sConnConfig.IndexOf(";", i);
		string sSQLDatabaseName = sConnConfig.Substring(i, j - i);

		// Get Trusted 
		i = sConnConfig.IndexOf("Trusted_Connection", 1) + 19;
		j = sConnConfig.IndexOf(";", i);
		string sSQLTrusted = sConnConfig.Substring(i, j - i);
		
		// Get User Id 
		i = sConnConfig.IndexOf("User Id", 1) + 8;
		j = sConnConfig.IndexOf(";", i);
		string sSQLUserId = sConnConfig.Substring(i, j - i);
		
		// Get Password 
		i = sConnConfig.IndexOf("Password", 1) + 9;
		j = sConnConfig.Length;
		string sSQLPassword = sConnConfig.Substring(i, j - i);

		// Build new connection string
		string sConnString = "";
		if (sSQLTrusted.ToUpper().Trim() == "YES")
		{
			sConnString = "Server=" + sSQLServerName + ";Database=" + sSQLDatabaseName + ";Trusted_Connection=True;";
		}
		else
		{
			sConnString = "Server=" + sSQLServerName + ";Database=" + sSQLDatabaseName + ";User ID=" + sSQLUserId + ";Password=" + sSQLPassword + ";Trusted_Connection=False;";
		}
		
		return sConnString;
	}

	public void CreateToQueue(string sOrderNum, string sUserId, string sFileName)
	{
		RequestToQueue(0, "", sFileName, sOrderNum, "CREATE", sUserId);
	}

	public void EmailToQueue(string sOrderNum,  string sUserId, string sEmailType)
	{
		RequestToQueue(0, sEmailType, "", sOrderNum, "EMAIL", sUserId);
	}

	public void PrintToQueue(string sOrderNum,  string sUserId, string sFileName, int iCopies)
	{
		RequestToQueue(iCopies, "", sFileName, sOrderNum, "PRINT", sUserId);
	}

	public void RequestToQueue( int iCopies,
								string sEmailType,
								string sFileName,
								string sOrderNum,
								string sRequestType,
								string sUserId )
	{
	    // Execute Stored Procedure to Insert the record into a print queue
		System.Data.SqlClient.SqlConnection myConnection = new System.Data.SqlClient.SqlConnection();

		try 
		{
			myConnection.ConnectionString = GetNewConnectionString();
			myConnection.Open();
			System.Data.SqlClient.SqlCommand myCommand = myConnection.CreateCommand();

			// Specify stored procedure to run
			myCommand.CommandText = "pCustomDC_RequestQueueAdd";
			myCommand.CommandType = System.Data.CommandType.StoredProcedure;

			myCommand.Parameters.Add("@p_Copies", System.Data.SqlDbType.SmallInt);
			myCommand.Parameters["@p_Copies"].Value = iCopies;
			myCommand.Parameters.Add("@p_EmailType", System.Data.SqlDbType.NVarChar, 20);
			myCommand.Parameters["@p_EmailType"].Value = sEmailType;
			myCommand.Parameters.Add("@p_FileName", System.Data.SqlDbType.NVarChar, 250);
			myCommand.Parameters["@p_FileName"].Value = sFileName;
			myCommand.Parameters.Add("@p_OrderNum", System.Data.SqlDbType.NChar, 12);
			myCommand.Parameters["@p_OrderNum"].Value = sOrderNum;
			myCommand.Parameters.Add("@p_RequestType", System.Data.SqlDbType.NChar, 10);
			myCommand.Parameters["@p_RequestType"].Value = sRequestType;
			myCommand.Parameters.Add("@p_UserId", System.Data.SqlDbType.NChar, 12);
			myCommand.Parameters["@p_UserId"].Value = sUserId;
	
			// Call the stored procedure
			myCommand.ExecuteNonQuery();
			
			// Close the connection
			myConnection.Close();
		}
		catch (System.Exception e)
		{
			string sMessage = e.Message;
		}
	}

	public void RefreshTotals(string sOrderNum)
	{
	    // Execute Stored Procedure to Insert the record into a print queue
		System.Data.SqlClient.SqlConnection myConnection = new System.Data.SqlClient.SqlConnection();
		try 
		{
			myConnection.ConnectionString = GetNewConnectionString();
			myConnection.Open();
			System.Data.SqlClient.SqlCommand myCommand = myConnection.CreateCommand();
			
			// Specify stored procedure to run
			myCommand.CommandText = "pCustomDC_TotalQtyUpdate";
			myCommand.CommandType = System.Data.CommandType.StoredProcedure;
			
			myCommand.Parameters.Add("@p_OrderNum", System.Data.SqlDbType.NChar, 12);
			myCommand.Parameters["@p_OrderNum"].Value = sOrderNum;
	
			// Call the stored procedure
			myCommand.ExecuteNonQuery();
			
			// Close the connection
			myConnection.Close();
		}
		catch (System.Exception e)
		{
			string sMessage = e.Message;
		}
	}

	public void SyncOrder(string sOrderNum)
	{
	    // Execute Stored Procedure to Insert the record into a print queue
		System.Data.SqlClient.SqlConnection myConnection = new System.Data.SqlClient.SqlConnection();
		try 
		{
			myConnection.ConnectionString = GetNewConnectionString();
			myConnection.Open();
			System.Data.SqlClient.SqlCommand myCommand = myConnection.CreateCommand();
			
			// Specify stored procedure to run
			myCommand.CommandText = "pCustomDC_OrderSync";
			myCommand.CommandType = System.Data.CommandType.StoredProcedure;
			
			myCommand.Parameters.Add("@p_OrderNum", System.Data.SqlDbType.NChar, 12);
			myCommand.Parameters["@p_OrderNum"].Value = sOrderNum;
	
			// Call the stored procedure
			myCommand.ExecuteNonQuery();
			
			// Close the connection
			myConnection.Close();
		}
		catch (System.Exception e)
		{
			string sMessage = e.Message;
		}
	}

} // End class PortCustom


}
