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
	<?php echo('<title>Patient List - ' . $username . '</title>'); ?>
	<link type="text/css" rel="stylesheet" href="main.css" />
	<script src="request.js"></script>
	<script src="getTextFromNodes.js"></script>    
    <script src="announcement.js"></script>
    <script>
     <?php echo( '<!--
        var username = "' . $username . '";' );?>

        var edit = false;
        var admin = false;
	var refreshRate;
        
        var refreshRequest = null;
        
        function editFilter() {
            window.showModalDialog( "editFilter.php?username=" + escape(username) ); 
           
            window.location.reload( true );
        }
        
        function userAdmin() {
            window.open( "editUsers.php?username=" + escape(username) ); 
           
       }
        
        function listAdmin() {
            window.open( "editPractitioners.php?username=" + escape(username) ); 
           
        }
        
        function updateData() {
            if( refreshRequest.readyState == 4 ) {
                if( refreshRequest.status == 200 ) {
 //                   xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
 //                   xmlDoc.async = "false";
 //                   xmlDoc.loadXML( refreshRequest.responseText );
             
                    refreshAnnouncementHTML( refreshRequest.responseXML);
                    
                    setTimeout("refreshPage(\"" + username + "\");", refreshRate);
                }
            }
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
            var button, div;
 
            //Get permissions for editing and administration           
            request = createRequest();
                    
            request.open("POST", "backend/requestBroker.php?requestType=getPermissions&username=" + username, false );
            request.send( null );
           
            xmlDoc = request.responseXML;
              
/*            traverseXML( xmlDoc );
*/                
            
            var edit = parseInt( (xmlDoc.getElementsByTagName('Edit'))[0].firstChild.nodeValue );
            var admin = parseInt( (xmlDoc.getElementsByTagName('Admin'))[0].firstChild.nodeValue );
    
            buildAnnouncementHTML( username, edit, admin );

            if(!edit) {
                request.open("POST", "backend/requestBroker.php?requestType=getSettings", false );
                request.send( null );
           
                xmlDoc = request.responseXML;

                refreshRate = parseInt( xmlDoc.getElementsByTagName('RefreshRateSecs')[0].childNodes[0].nodeValue ) * 1000;
                
                setTimeout("refreshPage();", refreshRate ); 
            } 
            if (admin){
            
                    div = document.getElementById("ToolsDiv");
                    
                    button = document.createElement("input");
                    button.id = "EditUsersBtn";
                    button.value = "User Admin";
                    button.type = "button";
                    button.className = "ToolsBtn";
                    button.onclick = userAdmin;
                    div.appendChild( button );
                    
                    button = document.createElement("input");
                    button.id = "EditListBtn";
                    button.value = "List Admin";
                    button.type = "button";
                    button.className = "ToolsBtn";
                    button.onclick = listAdmin;
                    div.appendChild( button );
            }
               
        }
    -->
    </script>
</head>

<body onload="javascript:initialLoad();">
	<div id="AnnouncementDiv">
    </div>
    
    <div id="ToolsDiv">
        <input id="FilterBtn" class="ToolBtn" type="button" value="Edit Filter" onclick="editFilter();"/>
    </div>

</body>

</html> 
