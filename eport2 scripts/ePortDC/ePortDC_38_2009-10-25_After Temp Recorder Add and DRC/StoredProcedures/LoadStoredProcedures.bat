
@setlocal
@echo off

rem NEED AT LEAST 4 PARAMETERS
if %4 == "" goto :SYNTAX

SET APPLICATION_NAME=%1
SET STORED_PROC_DIR=%2
SET SERVERNAME=%3
SET DATABASENAME=%4

rem IF ONLY 4 PARAMETERS, THIS IS WINDOWS AUTHENTICATION
if "%5" == "" (
    SET AUTHENTICATION=-WindowsAuthentication
) else (
    rem IF MORE THAN 6 PARAM, IT'S INVALID
    if not "%7" == "" goto :SYNTAX

    rem THERE'S NO 6TH PARAMETER, IT'S INVALID
    if "%6" == "" goto :SYNTAX

    rem SQLUSERNAME=%5
    rem SQLPASSWORD=%6
    SET AUTHENTICATION=-username "%5" -password "%6"
)

LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_Commodity" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_CommoditySize" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_Consignee" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_Customer" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_CustomerPrice" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_CustomsBrokerOffice" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_Order" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_OrderDetail" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_OrderStatus" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_PackHouse" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_PickList" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_PortOfEntry" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_Transporter" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_User" %AUTHENTICATION%
LoadStoredProcedures.exe -application %APPLICATION_NAME% -directory %STORED_PROC_DIR% -server %SERVERNAME% -database %DATABASENAME% -table "DC_Vessel" %AUTHENTICATION%

goto :END

:SYNTAX
ECHO.
ECHO USAGE:
ECHO.
ECHO Windows Server Authentication:
ECHO        LoadStoredProcedures.bat [ApplicationName]
ECHO                                 [ApplicationDirectory]
ECHO                                 [ServerName]
ECHO                                 [DatabaseName]
ECHO.
ECHO SQL Server Authentication:
ECHO        LoadStoredProcedures.bat [ApplicationName]
ECHO                                 [ApplicationDirectory]
ECHO                                 [ServerName]
ECHO                                 [DatabaseName]
ECHO                                 [Username]
ECHO                                 [Password]
ECHO.
ECHO Example of Windows Server Authentication:
ECHO        LoadStoredProcedures.bat MyApplication C:\MyApplication
ECHO                                 MySQLServerName MyDatabaseName
ECHO.
ECHO Example of SQL Server Authentication:
ECHO        LoadStoredProcedures.bat MyApplication
ECHO                                 "C:\My Application Folder"
ECHO                                 MySQLServerName MyDatabaseName sa sapassword

:END
