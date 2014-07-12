var updateRequest;

function buildAnnouncementHTML(username, edit, admin) {

    var parentDiv = document.getElementById("AnnouncementDiv");

    //clear out the old in case the filter has been changed
    while (parentDiv.firstChild != null) {
        parentDiv.removeChild(parentDiv.firstChild);
    }

    var request = createRequest();
    
    request.open("POST", "backend/requestBroker.php?requestType=getAnnouncements&username=" + username, false);
    request.send(null);

    var xmlDoc = request.responseXML;
    
    var announcements = xmlDoc.getElementsByTagName("Announcement");
    var sizePercent = (1/announcements.length) * 95;
    var i, doctorName, announcementDiv, innerAnnounceDiv, doctorP, patientTA, noteTA;
        
    for( i=0; i<announcements.length; i++ ) {

        doctorName = announcements.item(i).getElementsByTagName('DoctorName')(0).text;
        
        announcementDiv = document.createElement('div');
        announcementDiv.id = doctorName + "AnnounceDiv";
        announcementDiv.className = "AnnounceDiv";
        announcementDiv.style.width = sizePercent + '%';
        parentDiv.appendChild(announcementDiv);

        innerAnnounceDiv = document.createElement('div');
        innerAnnounceDiv.id = doctorName + "InnerAnnounceDiv";
        innerAnnounceDiv.className = "InnerAnnounceDiv";
        announcementDiv.appendChild(innerAnnounceDiv);

        doctorP = document.createElement('p');
        doctorP.id = doctorName + "DoctorP";
        doctorP.className = "DoctorP";
        doctorP.innerHTML = doctorName;
        innerAnnounceDiv.appendChild(doctorP);

        patientTA = document.createElement('textarea');
        patientTA.id = doctorName + "PatientTA";
        patientTA.className = "PatientTA";    
        patientTA.value = ((announcements.item(i)).getElementsByTagName('PatientList')(0)).text;
        innerAnnounceDiv.appendChild(patientTA);
        
        noteTA = document.createElement('textarea');
        noteTA.id = doctorName + "NoteTA";
        noteTA.className = "NoteTA";
        noteTA.value = ((announcements.item(i)).getElementsByTagName('NoteList')(0)).text;
        innerAnnounceDiv.appendChild(noteTA);
        

        if (edit) {
            updateRequest = createRequest();
            patientTA.onchange = patientListUpdated;
            patientTA.onkeydown = patientListKeyDown;
            noteTA.onchange = noteListUpdated;
            noteTA.onkeydown = noteListKeyDown;
        } else {
            patientTA.readonly = "true";
            patientTA.onkeypress = readOnly;
            noteTA.readonly = "true";
            noteTA.onkeypress = readOnly;
        }

    }

}

function readOnly() {
    return (false);
}


function refreshAnnouncementHTML(xmlDoc) {

    var announcements = xmlDoc.getElementsByTagName("Announcement");
    var sizePercent = (1 / announcements.length) * 100;
    var i, doctorName, announcementDiv, doctorP, patientTA, noteTA;

    for (i = 0; i < announcements.length; i++) {

        doctorName = ((announcements.item(i)).getElementsByTagName('DoctorName')(0)).text;

        patientTA = document.getElementById(doctorName + "PatientTA");
        patientTA.value = ((announcements.item(i)).getElementsByTagName('PatientList')(0)).text;

        noteTA = document.getElementById(doctorName + "NoteTA");
        noteTA.value = ((announcements.item(i)).getElementsByTagName('NoteList')(0)).text;
    }
}

function updateResult() {

    if (updateRequest.readyState == 4) {
        if (updateRequest.status == 200) {
        //    alert(updateRequest.responseText);
        }
    }
}

function updatePatientList(patientListTA) {


    var url = "backend/requestBroker.php?requestType=updatePatientList" +
                "&doctorname=" + (patientListTA.id.substring(0, patientListTA.id.length - ("PatientTA".length)))
                + "&patientlist=" + escape(patientListTA.value);

    updateRequest.open("POST", url, true);
    updateRequest.onreadystatechange = updateResult;
    updateRequest.send(null);
}

function patientListUpdated() {
    updatePatientList(this);
}

function patientListKeyDown() {

    if (event.ctrlLeft || (event.keyCode == 13)) {
        updatePatientList(this);
            
    }
    
    return (true);
}



function updateNoteList(noteListTA) {

    var url = "backend/requestBroker.php?requestType=updateNoteList" +
                "&doctorname=" + (noteListTA.id.substring(0, noteListTA.id.length - ("NoteTA".length)))
                + "&notelist=" + escape(noteListTA.value);

    updateRequest.open("POST", url, true);
    updateRequest.onreadystatechange = updateResult;
    updateRequest.send(null);

}

function noteListUpdated() {

    updateNoteList(this);
}

function noteListKeyDown() {

    if (event.ctrlLeft || (event.keyCode == 13)) {
        updateNoteList(this);

    }
        
    return (true);
}
