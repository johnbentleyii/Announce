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
    <script src="announcement.js"></script>
    <script>
     <?php echo( '<!--
        var username = "' . $username . '";' );?>
 
        function recurseNodes( node, traversalString ) {

            returnString = traversalString + "Node Name:" + node.nodeName 
                                            + " Node Value:" + node.nodeValue 
                                            + " Node Type:" + node.nodeType + "<br/>";
            
            for( i=0; i<node.childNodes.length; i++ ) {
                returnString = returnString + recurseNodes( node.childNodes[i] );
            }     
            
            return( returnString );
        }
        
        function traverseXML( xmlDoc ) {
        
            traversalString = recurseNodes( xmlDoc.documentElement, "<p>" ) + "</p>";
            var div = document.getElementById("AnnouncementDiv");
            div.innerHTML = traversalString;
        }
          
        function initialLoad() {
            
            var xmlDoc;
            var request;
           
           
            request = createRequest();
 
                   
            request.open("POST", "backend/requestBroker.php?requestType=getFilter&username=" + username, false );
            request.send( null );
           
            xmlDoc = request.responseXML;
                
                  
            buildFilterHTML( "FilterDiv", xmlDoc );               
        }
    -->
    </script>
</head>

<body onload="javascript:initialLoad();">
	<div id="FilterDiv">
    </div>
    <div id="Tools">
        <input id="FilterBtn" type="button" value="OK" onclick="saveFilter();"/>
        <input id="FilterBtn" type="button" value="Cancel" onclick="window.close()"/>
    </div>
</body>

</html> 
