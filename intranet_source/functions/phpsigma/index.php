<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>Sigma Grid PHP Sample</title>
</head>
<body>
    <br />
    <h3>
This sample has passed test with</h3>
    <ul>
        <li>Apache1.3.39 (Unix)</li>
        <li>PHP4.4.7</li>
        <li>MySQL5.0.27-standard</li>
    </ul>
    <h3>

Please follow the steps
    </h3>
    <ul>
        <li>
Download the sample and unzip to a directory.
        </li>
        <li>Copy the whole directory together with grid to apache php directory.</li>
        <li>Create a database and then run CreatTable.sql in a new database or existing database.
        </li>
        <li>Modify parameters of mysql_connect in php/ConnectionManager.php. </li>
        <li>
For load_xml and load_xml_paging, you need to download xml2json.js and copy it to /grid. Xml2json.js is released under GPL license and can be downloaded at <a href="http://www.thomasfrank.se/downloadableJS/xml2json.js">
        http://www.thomasfrank.se/downloadableJS/xml2json.js</a>. &nbsp;<br />
    <br />
        </li>
    </ul>
    <h3>

Sample list
    </h3>
    <ul>
        <li><a href="php/load_json_array/index.php">
load_json_array</a> - demonstrates how
            to get data from server side in json array format. </li>
        <li><a href="php/load_json_array_paging/index.php">
load_json_array_paging</a> - demonstrates
            how to get data from server side in json array format and present data by pages.
        </li>
        <li><a href="php/load_json_obj/index.php">
load_json_object</a> - demonstrates how
            to get data from server side in json object format. </li>
        <li><a href="php/load_json_obj_paging/index.php">
load_json_object_paging</a> - demonstrates
            how to get data from server side in json object format and present data by pages.
        </li>
        <li><a href="php/load_xml/index.php">load_xml </a>- demonstrate how to get data from server side in XML format. </li>
        <li><a href="php/load_xml_paging/index.php">
load_xml_paging</a> - demonstrates how
            to get data from server side in XML format and present data by pages. </li>
        <li><a href="php/crud/index.php">
crud</a> - demonstrates how to load data from server side and save modified data to server side. </li>
	<li><a href="php/sample/index.php">
sample</a> - demonstrates how to load data from server side and save modified data to server side. </li>

    </ul>
    <h3>About Sigmasoft</h3>
    <p>
    Sigmasoft Technologies LLC is a software company providing cross-browser javascript GUI components and tools & services involved. Our aim is to make AJAX simple and easy. 
<br>Sigmasoft also provides end-to-end solutions in web development (Web 2.0, PHP, ASP.NET, ASP, JSP, XML, Flash), application development and IT consulting services. Please send email to sales@sigmawidgets.com for further infomation.
</p>
</body>
</html>
