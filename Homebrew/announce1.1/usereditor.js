var updateRequest = createRequest();

var doctorCBList = new Array();

var usernames = new Array();


function recurseNodes(node, traversalString) {

    returnString = traversalString + "Node Name:" + node.nodeName
                                            + " Node Value:" + node.nodeValue
                                            + " Node Type:" + node.nodeType + "<br/>";

    for (i = 0; i < node.childNodes.length; i++) {
        returnString = returnString + recurseNodes(node.childNodes[i]);
    }

    return (returnString);
}

function buildUserEditorHTML( xmlDoc ) {

    var users = xmlDoc.getElementsByTagName("User");
    
    var tblRow, tblData, p, span, editCB, adminCB, updateBtn, deleteBtn, filterBtn;
    var i, userName, canEdit, canAdmin;

    var userTbl = document.getElementById("UserTblBody");
    
    
    for (i = 0; i < users.length; i++) {

        usernames[i] = userName = users[i].getElementsByTagName("UserName")[0].childNodes[0].nodeValue;
        canEdit = parseInt(users[i].getElementsByTagName("Edit")[0].childNodes[0].nodeValue);
        canAdmin = parseInt(users[i].getElementsByTagName("Admin")[0].childNodes[0].nodeValue);

        tblRow = document.createElement("tr");
        if( (i % 2) == 0 ) {
            tblRow.className = "UserRowOdd";
        }

        tblData = document.createElement("td");
        tblData.className = "UserName UserData";
        tblData.innerHTML = "<p>" + userName + "</p>";
        tblRow.appendChild( tblData );
        
        tblData = document.createElement("td");
        tblData.className = "CanEdit UserData";
        tblRow.appendChild( tblData );

                      
        editCB = document.createElement("input");
        editCB.id = userName + "CanEditCB";
        editCB.className = "CanEdit";
        editCB.type = "checkbox";
        editCB.checked = canEdit;
        tblData.appendChild(editCB);

        p = document.createElement("span");
        p.className = "CanEdit";
        p.innerHTML = "Edit";
        tblData.appendChild(p);
        
        tblRow.appendChild( tblData );

        tblData = document.createElement("td");
        tblData.className = "CanAdmin UserData";
        tblRow.appendChild( tblData );
        
        adminCB = document.createElement("input");
        adminCB.id = userName + "CanAdminCB";
        adminCB.className = "CanAdmin";
        adminCB.type = "checkbox";
        adminCB.checked = canAdmin;
        tblData.appendChild( adminCB );

        p = document.createElement("span");
        p.className = "CanAdmin";
        p.innerHTML = "Admin";
        tblData.appendChild(p);
        
        tblRow.appendChild( tblData );
        
        tblData = document.createElement("td");
        tblData.className = "UserFunctions UserData";

        updateBtn = document.createElement("input");
        updateBtn.id = userName + "UpdateBtn";
        updateBtn.value = "Update";
        updateBtn.type = "button";
        updateBtn.className = "UpdateBtn";
        updateBtn.onclick = updateUser;
        tblData.appendChild( updateBtn );
        
        deleteBtn = document.createElement("input");
        deleteBtn.id = userName + "DeleteBtn";
        deleteBtn .value = "Delete";
        deleteBtn.type = "button";
        deleteBtn.className = "DeleteBtn";
        deleteBtn.onclick = deleteUser;
        tblData.appendChild( deleteBtn );
        
        filterBtn = document.createElement("input");
        filterBtn.id = userName + "FilterBtn";
        filterBtn.value = "Filter";
        filterBtn.type = "button";
        filterBtn.className = "FilterBtn";
        filterBtn.onclick = editFilterUser;
        tblData.appendChild( filterBtn );
        
        tblRow.appendChild( tblData );

        userTbl.appendChild(tblRow);

    }

}

function updateResult() {

    if (updateRequest.readyState == 4) {
        if (updateRequest.status == 200) {
            window.location.reload();
        }
    }
}

function addUserKeyPress() {

    event.returnValue = (((event.keyCode >= 48) && (event.keyCode <= 57)) ||
                        ((event.keyCode >= 65) && (event.keyCode <= 90)) ||
                        ((event.keyCode >= 97) && (event.keyCode <= 122)));
    
}

function addUser() {

    var username = document.getElementById("NewUserFld").value;
    
    var i;

    for (i = 0; i < usernames.length; i++) {
        if (usernames[i] == username) {
            alert(username + " already exists in the system.");
            return;
        }
    }
    
    var url = "backend/requestBroker.php?requestType=addUser&username=" + username;

    updateRequest.open("POST", url, true);
    updateRequest.onreadystatechange = updateResult;
    updateRequest.send(null);
}

function updateUser() {

    var username = this.id.substring(0, this.id.length - "UpdateBtn".length);
    
    var url = "backend/requestBroker.php?requestType=updateUser&username=" + username;

    url += "&edit=" + ((document.getElementById( username + "CanEditCB" ).checked)?(1):(0));
    url += "&admin=" + ((document.getElementById(username + "CanAdminCB").checked) ? (1) : (0));

    updateRequest.open("POST", url, true);
    updateRequest.onreadystatechange = updateResult;
    updateRequest.send(null);
}

function deleteUser() {

    var username = this.id.substring(0, this.id.length - "UpdateBtn".length);

    var url = "backend/requestBroker.php?requestType=removeUser&username=" + username;
 
    updateRequest.open("POST", url, true);
    updateRequest.onreadystatechange = updateResult;
    updateRequest.send(null);
}

function editFilterUser( ) {

    var username = this.id.substring(0, this.id.length - "FilterBtn".length);

    window.showModalDialog( "editFilter.php?username=" + escape(username) );
}