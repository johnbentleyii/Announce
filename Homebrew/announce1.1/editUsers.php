<?php

    if( !isset( $_REQUEST['username'] ) ) {
        die( "Error - username required but not present" );
    }    
    
    $username = $_REQUEST['username'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"   
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=8" />
	<?php echo('<title>Edit Users - ' . $username . '</title>'); ?>
	<link type="text/css" rel="stylesheet" href="main.css" />
	<script type="text/javascript" src="request.js"></script>
    <script type="text/javascript" src="usereditor.js"></script>
    <script type="text/javascript">
     <?php echo( '<!--
        var username = "' . $username . '";' );?>
 
          
        function initialLoad() {
            
            var xmlDoc, settingsXMLDoc;
            var request;
            var admin;
            
            request = createRequest();
                   
            request.open("POST", "backend/requestBroker.php?requestType=getPermissions&username=" + username, false );
            request.send( null );
           
            xmlDoc = request.responseXML;
            
            admin = parseInt( xmlDoc.getElementsByTagName('Admin')[0].childNodes[0].nodeValue );
            
            if( admin ) {
                request.open("POST", "backend/requestBroker.php?requestType=getUsers&username=" + username, false );
                request.send( null );
           
                xmlDoc = request.responseXML;
                              
                buildUserEditorHTML( xmlDoc );                  
            }     
        }
    -->
    </script>
</head>

<body onload="javascript:initialLoad();">
	<div id="UserDiv">
	   <h1>Users</h1>
	   <div id="AddUserDiv">
	        <p>Add User: 
	            <input id="NewUserFld" type="text" size="50" onkeypress="javascript:addUserKeyPress();"/>
	            <input id="AddBtn" type="button" value="Add" onclick="javascript:addUser();" />
	        </p>
	   </div>
	   <h2>Current Users:</h2>
	   <table id="UserTbl">
	        <tbody id="UserTblBody">
	        </tbody>
	   </table>
	</div>
	<div id="ToolsDiv">
        <input id="CloseBtn" type="button" value="Close" onclick="window.close()"/>
    </div>
</body>

</html> 
