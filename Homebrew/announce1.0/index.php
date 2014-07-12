<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"   
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title>RPS Announcement</title>
	<link type="text/css" rel="stylesheet" href="main.css" />
	<script src="request.js"></script>
    <script>
    <!--
    
        var selectedUsername;
        var userSelect;
        
        function selectUser() {
            selectedUsername = userSelect.options[userSelect.selectedIndex].value;
        }
        
        function openAnnouncements() {
                      
            window.open( "announcements.php?username=" + escape(selectedUsername ), "_self" );
        }
      
        function initialLoad() {
            
            var request = createRequest();
                   
            request.open("POST", "backend/requestBroker.php?requestType=getUsers", false );
            request.send( null );
           
            var xmlDoc = request.responseXML;
            
            var usernames = xmlDoc.getElementsByTagName( "UserName" );
            userSelect = document.getElementById( "UserSelect" );
            var option, username;
            
            for( i=0; i<usernames.length; i++ ) {
            
                username = usernames[i].text;
                
                option = document.createElement( "option" );
                option.id = username + "Opt";
                option.label = username;
                option.value = username;
                userSelect.appendChild( option );
            }               
            
            selectedUsername = usernames[0].text;
        }
    -->
    </script>
</head>

<body onload="javascript:initialLoad();">
	<div id="UserDiv">
	   <select id="UserSelect" onchange="selectUser();">
	   </select>
    </div>
    <div id="ToolsDiv">
        <input id="FilterBtn" type="button" value="OK" onclick="openAnnouncements();"/>
        <input id="FilterBtn" type="button" value="Cancel" onclick="window.close()"/>
    </div>
</body>

</html> 
