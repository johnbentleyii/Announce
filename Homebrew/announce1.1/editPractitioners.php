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
	<?php echo('<title>Edit Practitioners - ' . $username . '</title>'); ?>
	<link type="text/css" rel="stylesheet" href="main.css" />
	<script src="request.js"></script>
    <script src="practitioner.js"></script>
    <script>
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
                request.open("POST", "backend/requestBroker.php?requestType=getDoctors", false );
                request.send( null );
           
                xmlDoc = request.responseXML;
                              
                buildPractitionerEditorHTML( xmlDoc );                          
            }     
        }
    -->

    </script>
</head>

<body onload="javascript:initialLoad();">
	<div id="UserDiv">
	   <h1>Practitioners</h1>
	   <div id="AddPractitionerDiv">
	        <p>Add Practitioner: 
	            <input id="NewPractitonerFld" type="text" size="50"/>
	            <input id="AddBtn" type="button" value="Add" onclick="javascript:addPractitioner();" />
	        </p>
	   </div>
	   <h2>Pracitioners:</h2>
	   <div id="PracitionersDiv">
	        <div id="PractitionerSelectDiv">
	            <select id="PractitionerSelect" size="10" onclick="javascript:practitionerSelected();">
	            </select>
	        </div>
	        <div id="PractitonerControlsDiv">
	            <input id="MoveUpBtn" type="button" disabled="true" value="Move Up" onclick="javascript:moveUp();"/>
	            <input id="MoveDownBtn" type="button" disabled="true" value="Move Down" onclick="javascript:moveDown();"/>
	            <input id="SaveOrderBtn" type="button" value="Save Order" onclick="javascript:saveOrder();"/>
	            <input id="DeleteBtn" type="button" value="Delete" onclick="javascript:deletePractitioner();"/>
	        </div>
	   </div>
	</div>
	<div id="ToolsDiv">
        <input id="CloseBtn" type="button" value="Close" onclick="window.close()"/>
    </div>
</body>

</html> 
