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

function buildFilterHTML(xmlDoc) {

    var filters = xmlDoc.getElementsByTagName("Filter");

    var showAllNodes, showAll;

    var doctorListDiv = document.getElementById("DoctorListDiv");
    var doctorP, doctorCB, doctorSpan, i, doctorName, shown;
    var doctorCBC = 0;

    doctorListDiv.display = "none";

    for (i = 0; i < filters.length; i++) {

        showAllNodes = filters[i].getElementsByTagName('ShowAll');
        
      
        if (showAllNodes.length > 0) {
            showAll = parseInt(showAllNodes(0).childNodes[0].nodeValue);
        } else {

            doctorName = filters[i].getElementsByTagName('DoctorName')(0).childNodes[0].nodeValue;
            shown = ((filters[i].getElementsByTagName('Shown')(0).childNodes[0].nodeValue) == 'True');
            
            doctorP = document.createElement('p');
            doctorP.id = doctorName + "P";
            doctorP.className = "DoctorList";
            
            
            doctorCB = document.createElement('input');
            doctorCB.id = doctorName + "CB";
            doctorCB.name = "DoctorList";
            doctorCB.type = "checkbox";
            doctorCB.checked = shown;
            doctorP.appendChild(doctorCB);
            
            doctorSpan = document.createElement('Span');
            doctorSpan.id = doctorName + "Span";
            doctorSpan.innerHTML = doctorName;
            doctorP.appendChild(doctorSpan);

            doctorListDiv.appendChild(doctorP);
            
            doctorCBList[doctorCBC++] = doctorCB;
        }
    }

    doctorListDiv.disabled = showAll;
    document.getElementById("ShowAllRB").checked = showAll;
    document.getElementById("SelectRB").checked = !showAll;

    doctorListDiv.display = "block";
    
    updateRequest = createRequest();
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