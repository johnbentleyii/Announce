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
	<title>RPS Announcement</title>
	<link type="text/css" rel="stylesheet" href="main.css" />
	<script src="request.js"></script>
    <script src="announcement.js"></script>
    <script>
     <?php echo( '<!--
        var username = "' . $username . '";' );?>
        var refreshRate;
 
        var edit = false;
        var admin = false;
        
        var refreshRequest = null;
        
        function editFilter() {
            window.showModalDialog( "editFilter.php?username=" + escape(username) ); 
           
            window.location.reload( true );
        }
        
        function updateData() {
            if( refreshRequest.readyState == 4 ) {
                if( refreshRequest.status == 200 ) {
                    xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
                    xmlDoc.async = "false";
                    xmlDoc.loadXML( refreshRequest.responseText );
             
                    refreshAnnouncementHTML( xmlDoc );
                    
                    setTimeout("refreshPage(\"" + username + "\");", refreshRate);
                }
            }
        }
        
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
          
        function refreshPage() {
        
            if( refreshRequest == null )
                refreshRequest = createRequest();
                
            refreshRequest.open("POST", "backend/requestBroker.php?requestType=getAnnouncements&username=" + username, true );
            refreshRequest.onreadystatechange = updateData;
            refreshRequest.send( null );
        }
        
        function initialLoad() {
            
            var xmlDoc;
            var request;
 
            //Get permissions for editing and administration           
            request = createRequest();
                    
            request.open("POST", "backend/requestBroker.php?requestType=getPermissions&username=" + username, false );
            request.send( null );
           
            xmlDoc = request.responseXML;
              
/*            traverseXML( xmlDoc );
*/                
            
            edit = parseInt( xmlDoc.getElementsByTagName('Edit')[0].childNodes[0].nodeValue );
            admin = parseInt( xmlDoc.getElementsByTagName('Admin')[0].childNodes[0].nodeValue );
    
            buildAnnouncementHTML( username, edit, admin );

            if(!edit) {
                request.open("POST", "backend/requestBroker.php?requestType=getSettings", false );
                request.send( null );
           
                xmlDoc = request.responseXML;

                refreshRate = parseInt( xmlDoc.getElementsByTagName('RefreshRateSecs')[0].childNodes[0].nodeValue ) * 1000;
                
                setTimeout("refreshPage();", refreshRate ); 
            }
               
        }
    -->
    </script>
</head>

<body onload="javascript:initialLoad();">
	<div id="AnnouncementDiv">
    </div>
<!--    
    <div id="Tools">
        <input id="FilterBtn" type="button" value="Edit Filter" onclick="editFilter();"/>
    </div>
-->
</body>

</html> 
