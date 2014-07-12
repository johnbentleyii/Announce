var updateRequest = createRequest();

var doctorCBList = new Array();

var practitioners = new Array( );


function buildPractitionerEditorHTML(xmlDoc) {

    var doctorNames = xmlDoc.getElementsByTagName("DoctorName");

    for (i = 0; i < doctorNames.length; i++) {
        practitioners[i] = doctorNames[i].text;        
    }

    reloadPracitioners();
}

function updateResult() {

    if (updateRequest.readyState == 4) {
        if (updateRequest.status == 200) {
 //           alert(updateRequest.responseText);
            window.location.reload();
        }
    }
}

function addPractitionerKeyPress() {

    event.returnValue = (((event.keyCode >= 48) && (event.keyCode <= 57)) ||
                        ((event.keyCode >= 65) && (event.keyCode <= 90)) ||
                        ((event.keyCode >= 97) && (event.keyCode <= 122)) ||
                        (event.keyCode == 32) );
    
}

function trimBlanks(str) {

    var begin = 0, end = str.length - 1;

    while ((begin < str.length) && (str[begin] == " ")) {
        begin++;
    }

    while ((end >= 0) && (str[end] == " ")) {
        end--;
    }

    return (str.substr(begin, end - begin + 1));
}

function addPractitioner() {

    var practitioner = trimBlanks( document.getElementById("NewPractitonerFld").value );
    
    var i;

    for (i = 0; i < practitioners.length; i++) {
        if (practitioners[i] == practitioner) {
            alert(practitioner + " already exists in the system.");
            return;
        }
    }

    var url = "backend/requestBroker.php?requestType=addDoctor&doctorname=" + practitioner + "&order=" + practitioners.length;

    practitioners[practitioners.length] = practitioner;

    updateRequest.open("POST", url, true);
    updateRequest.onreadystatechange = updateResult;
    updateRequest.send(null)
}

function practitionerSelected() {

    var practitionerSelect = document.getElementById("PractitionerSelect");

    document.getElementById("MoveUpBtn").disabled = (practitionerSelect.selectedIndex == null) || (practitionerSelect.selectedIndex == 0);
    document.getElementById("MoveDownBtn").disabled = (practitionerSelect.selectedIndex == null) || (practitionerSelect.selectedIndex == (practitioners.length - 1));    
}

function practionerSwap(first, second) {

    var swap = practitioners[first];

    practitioners[first] = practitioners[second];
    practitioners[second] = swap;
}

function reloadPracitioners() {
    var practitionerSelect = document.getElementById("PractitionerSelect");

    while (practitionerSelect.firstChild != null) {
        practitionerSelect.removeChild(practitionerSelect.firstChild);
    }

    var i, option;

    for (i = 0; i < practitioners.length; i++) {
        option = document.createElement("option");
        option.id = practitioners[i] + "Option";
        option.className = "PracitionerOption";
        option.value = practitioners[i];
        option.innerHTML = practitioners[i];
        practitionerSelect.appendChild(option);
    }
}

function moveUp() {

    var practitionerSelect = document.getElementById("PractitionerSelect");
    var selectedIndex = practitionerSelect.selectedIndex;

    practionerSwap(selectedIndex, selectedIndex - 1);

    reloadPracitioners();

    practitionerSelect.selectedIndex = selectedIndex - 1;
    practitionerSelected();
}

function moveDown() {

    var practitionerSelect = document.getElementById("PractitionerSelect");
    var selectedIndex = practitionerSelect.selectedIndex;

    practionerSwap(selectedIndex, selectedIndex + 1);

    reloadPracitioners();

    practitionerSelect.selectedIndex = selectedIndex + 1;
    practitionerSelected();
}

function saveOrder() {
    
    var url = "backend/requestBroker.php?requestType=updateDoctorOrder&doctornames=" + practitioners;
    
    updateRequest.open("POST", url, true);
    updateRequest.onreadystatechange = updateResult;
    updateRequest.send(null);
}

function deletePractitioner() {

    var practitionerSelect = document.getElementById("PractitionerSelect");
    var selectedIndex = practitionerSelect.selectedIndex;

    var url = "backend/requestBroker.php?requestType=removeDoctor&doctorname=" + practitionerSelect.options[selectedIndex].text;

    practitioners.splice(selectedIndex, 1);
 
    updateRequest.open("POST", url, true);
    updateRequest.onreadystatechange = updateResult;
    updateRequest.send(null);
}

function editFilterUser( ) {

    var username = this.id.substring(0, this.id.length - "FilterBtn".length);

    window.showModalDialog( "editFilter.php?username=" + escape(username) );
}