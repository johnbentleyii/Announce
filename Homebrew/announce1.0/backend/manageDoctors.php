<?php	

require_once( "queryDB.php" );

function removeDoctor( $doctorname ) {

  $tsql = "DELETE FROM FilterTbl " .
    "WHERE Show = '" . $doctorname ."'";
  
  queryDB( $tsql );
    
  $tsql = "DELETE FROM AnnouncementTbl " .
    "WHERE DoctorName = '" . $doctorname ."'";

  return( queryDB( $tsql ) );
}

function addDoctor( $doctorname, $order ) {

  $tsql = "INSERT INTO UserTbl " . 
	  "([DoctorName], [PatientList], [NoteList], [SortOrder] ) ".
    "VALUES " .
    "('" . $doctorname . "', '', '', " . $order . ")";
    
	return( queryDB( $tsql ) );

}

?>