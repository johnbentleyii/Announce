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
	<title>RPS Announcement</title>
	<link type="text/css" rel="stylesheet" href="main.css" />
	<script src="request.js"></script>
    <script src="filter.js"></script>
    <script>
     <?php echo( '<!--
        var username = "' . $username . '";' );?>
 
          
        function initialLoad() {
            
            var xmlDoc;
            var request;
           
            request = createRequest();
                   
            request.open("POST", "backend/requestBroker.php?requestType=getFilter&username=" + username, false );
            request.send( null );
           
            xmlDoc = request.responseXML;
                 
            buildFilterHTML( xmlDoc );               
        }
    -->
    </script>
</head>

<body onload="javascript:initialLoad();">
	<div id="FilterDiv">
	    <p><input id="ShowAllRB" type="radio" name="Filter" value="showall" onclick="updateAll();" />Show All Doctors</p>
	    <p><input id="SelectRB" type="radio" name="Filter" value="select" onclick="updateAll();" />Show Doctors Selected Below:</p>
	    <div id="DoctorListDiv">
	    </div>
    </div>
    <div id="ToolsDiv">
        <input id="FilterBtn" type="button" value="OK" onclick="saveFilter();window.close();"/>
        <input id="FilterBtn" type="button" value="Cancel" onclick="window.close()"/>
    </div>
</body>

</html> 
