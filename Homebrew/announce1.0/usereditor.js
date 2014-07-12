var updateRequest;

var doctorCBList = new Array();

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

    var userTbl = document.getElementById("UserTbl");
    var tblRow, tblData, p, editCB, adminCB, updateBtn, delButton;
    var i, userName, canEdit, canAdmin;

    for (i = 0; i < users.length; i++) {

        userName = users[i].getElementsByTagName("UserName")[0].childNodes[0].nodeValue;
        canEdit = parseInt(users[i].getElementsByTagName("Edit")[0].childNodes[0].nodeValue);
        canAdmin = parseInt(users[i].getElementsByTagName("Admin")[0].childNodes[0].nodeValue);

        tblRow = document.createElement("tr");

        tblData = document.createElement("td");
        tblData.className = "UserName";
        tblData.innerHTML = "<p>" + userName + "</p>";
        tblRow.appendChild( tblData );
        
        tblData = document.createElement("td");
        tblData.className = "CanEdit";
        tblRow.appendChild( tblData );
        
        editCB = document.createElement("input");
        editCB.id = userName + "CanEditCB";
        editCB.className = "CanEdit";
        editCB.type = "checkbox";
        editCB.checked = canEdit;
        tblData.appendChild(editCB);
        
        tblRow.appendChild( tblData );

        tblData = document.createElement("td");
        tblData.className = "CanAdmin";
        tblRow.appendChild( tblData );
        
        adminCB = document.createElement("input");
        adminCB.id = userName + "CanAdminCB";
        adminCB.className = "CanAdmin";
        adminCB.type = "checkbox";
        adminCB.checked = canAdmin;
        tblData.appendChild( adminCB );
        
        tblRow.appendChild( tblData );
        
        tblData = document.createElement("td");
        tblData.className = "UserFunctions";
        tblRow.appendChild( tblData );

        userTbl.appendChild(tblRow);
       
    }

}

function updateResult() {

    if (updateRequest.readyState == 4) {
        if (updateRequest.status == 200) {
            alert(updateRequest.responseText);
        }
    }
}

function updateAll() {

    document.getElementById("DoctorListDiv").disabled = document.getElementById("ShowAllRB").checked;
}

function saveFilter() {

    var url = "backend/requestBroker.php?username=" + username + "&requestType=";
 
    
    if (document.getElementById("ShowAllRB").checked) {
        url += "updateFilterShowAll";
    } else {
        url += "updateFilterSelected&showList=";

        for (i = 0; i < doctorCBList.length; i++) {
            if (doctorCBList[i].checked) {
                url += escape((doctorCBList[i].id.substring(0, doctorCBList[i].id.length - ("CB".length))));
                url += ",";
            }
        }
    }
    
    updateRequest.open("POST", url, true);
    updateRequest.onreadystatechange = updateResult;
    updateRequest.send(null);
}