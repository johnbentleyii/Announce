<?php	

require_once( "queryDB.php" );

function updateNoteList( $doctorName, $noteList ) {
	

	$tsql = "UPDATE AnnouncementTbl " .
		"SET NoteList = '" . $noteList . "' " .
		"WHERE f.DoctorName = '" . $doctorName . "' ";
		
	queryDB( $tsql );

}


function updatePatientList( $doctorName, $patientList ) {
	

	$tsql = "UPDATE AnnouncementTbl " .
		"SET PatientList = '" . $PatientList . "' " .
		"WHERE f.DoctorName = '" . $doctorName . "' ";
    
    queryDB( $tsql );
}

?>