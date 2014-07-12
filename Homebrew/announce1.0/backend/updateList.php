<?php	

require_once( "queryDB.php" );

function updateNoteList( $doctorName, $noteList ) {
	

	$tsql = "UPDATE AnnouncementTbl " .
		"SET NoteList = '" . $noteList . "' " .
		"WHERE DoctorName = '" . $doctorName . "' ";
		
	return( queryDB( $tsql ) );

}


function updatePatientList( $doctorName, $patientList ) {
	

	$tsql = "UPDATE AnnouncementTbl " .
		"SET PatientList = '" . $patientList . "' " .
		"WHERE DoctorName = '" . $doctorName . "' ";
    
    return( queryDB( $tsql ) );
}

?>